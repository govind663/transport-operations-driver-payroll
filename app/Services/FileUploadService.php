<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    /**
     * Upload File
     */
    public function upload($file, ?string $folder = null, $model = null): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Generate Folder
        |--------------------------------------------------------------------------
        */

        if (!$folder && $model) {
            $folder = $this->generateFolderFromModel($model);
        }

        $folder = $folder ?? 'uploads';

        /*
        |--------------------------------------------------------------------------
        | File Information
        |--------------------------------------------------------------------------
        */

        $mime = $file->getMimeType();
        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        /*
        |--------------------------------------------------------------------------
        | VIDEO
        |--------------------------------------------------------------------------
        */

        if ($mime === 'video/mp4') {

            $filename = time() . '_' . uniqid() . '.mp4';

            $relativePath = $folder . '/' . $filename;

            $fullPath = storage_path(
                'app/public/' . $relativePath
            );

            /*
            |--------------------------------------------------------------------------
            | Create Directory
            |--------------------------------------------------------------------------
            */

            if (!is_dir(dirname($fullPath))) {

                mkdir(
                    dirname($fullPath),
                    0755,
                    true
                );
            }

            $tempPath = $file->getRealPath();

            $command = sprintf(
                'ffmpeg -i %s -vcodec libx264 -crf 28 -preset fast -vf scale=1280:-2 -acodec aac -b:a 128k -movflags +faststart %s 2>&1',
                escapeshellarg($tempPath),
                escapeshellarg($fullPath)
            );

            exec($command);

            /*
            |--------------------------------------------------------------------------
            | Verify Upload
            |--------------------------------------------------------------------------
            */

            if (!file_exists($fullPath)) {
                return null;
            }

            return $relativePath;
        }

        /*
        |--------------------------------------------------------------------------
        | GIF
        |--------------------------------------------------------------------------
        */

        if ($mime === 'image/gif') {

            $filename = time() . '_' . uniqid() . '.gif';

            Storage::disk('public')->putFileAs(
                $folder,
                $file,
                $filename
            );

            return $folder . '/' . $filename;
        }

        /*
        |--------------------------------------------------------------------------
        | IMAGE
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $mime,
                [
                    'image/jpeg',
                    'image/png',
                    'image/webp',
                ],
                true
            )
        ) {

            /*
            |--------------------------------------------------------------------------
            | Create Image Resource
            |--------------------------------------------------------------------------
            */

            switch ($mime) {

                case 'image/jpeg':

                    $image = @imagecreatefromjpeg(
                        $file->getRealPath()
                    );

                    break;

                case 'image/png':

                    $image = @imagecreatefrompng(
                        $file->getRealPath()
                    );

                    break;

                case 'image/webp':

                    $image = @imagecreatefromwebp(
                        $file->getRealPath()
                    );

                    break;

                default:

                    $image = false;

                    break;
            }

            /*
            |--------------------------------------------------------------------------
            | Fallback If GD/Image Processing Fails
            |--------------------------------------------------------------------------
            */

            if (!$image) {

                $filename = time() . '_' . uniqid() . '.' . $extension;

                Storage::disk('public')->putFileAs(
                    $folder,
                    $file,
                    $filename
                );

                return $folder . '/' . $filename;
            }

            /*
            |--------------------------------------------------------------------------
            | Image Dimensions
            |--------------------------------------------------------------------------
            */

            $width = imagesx($image);
            $height = imagesy($image);

            /*
            |--------------------------------------------------------------------------
            | Responsive Sizes
            |--------------------------------------------------------------------------
            */

            $sizes = [
                150,
                300,
                600,
                800,
            ];

            foreach ($sizes as $size) {

                if ($width <= $size) {
                    continue;
                }

                $newWidth = $size;

                $newHeight = (int) floor(
                    $height * ($newWidth / $width)
                );

                $resized = imagecreatetruecolor(
                    $newWidth,
                    $newHeight
                );

                /*
                |--------------------------------------------------------------------------
                | PNG Transparency
                |--------------------------------------------------------------------------
                */

                imagealphablending(
                    $resized,
                    false
                );

                imagesavealpha(
                    $resized,
                    true
                );

                imagecopyresampled(
                    $resized,
                    $image,
                    0,
                    0,
                    0,
                    0,
                    $newWidth,
                    $newHeight,
                    $width,
                    $height
                );

                /*
                |--------------------------------------------------------------------------
                | Convert To WebP
                |--------------------------------------------------------------------------
                */

                ob_start();

                imagewebp(
                    $resized,
                    null,
                    75
                );

                $webpData = ob_get_clean();

                /*
                |--------------------------------------------------------------------------
                | Store Responsive Image
                |--------------------------------------------------------------------------
                */

                Storage::disk('public')->put(
                    $folder . '/' . $size . '_' . time() . '_' . uniqid() . '.webp',
                    $webpData
                );

                imagedestroy($resized);
            }

            /*
            |--------------------------------------------------------------------------
            | Main Image
            |--------------------------------------------------------------------------
            */

            $maxWidth = 800;

            if ($width > $maxWidth) {

                $newWidth = $maxWidth;

                $newHeight = (int) floor(
                    $height * ($newWidth / $width)
                );

                $resized = imagecreatetruecolor(
                    $newWidth,
                    $newHeight
                );

                imagealphablending(
                    $resized,
                    false
                );

                imagesavealpha(
                    $resized,
                    true
                );

                imagecopyresampled(
                    $resized,
                    $image,
                    0,
                    0,
                    0,
                    0,
                    $newWidth,
                    $newHeight,
                    $width,
                    $height
                );

                imagedestroy($image);

                $image = $resized;
            }

            /*
            |--------------------------------------------------------------------------
            | Final Filename
            |--------------------------------------------------------------------------
            */

            $filename = time() . '_' . uniqid() . '.webp';

            $filePath = $folder . '/' . $filename;

            /*
            |--------------------------------------------------------------------------
            | Convert To WebP
            |--------------------------------------------------------------------------
            */

            ob_start();

            imagewebp(
                $image,
                null,
                75
            );

            $webpData = ob_get_clean();

            /*
            |--------------------------------------------------------------------------
            | Store Main Image
            |--------------------------------------------------------------------------
            */

            Storage::disk('public')->put(
                $filePath,
                $webpData
            );

            /*
            |--------------------------------------------------------------------------
            | Cleanup
            |--------------------------------------------------------------------------
            */

            imagedestroy($image);

            /*
            |--------------------------------------------------------------------------
            | Verify
            |--------------------------------------------------------------------------
            */

            if (!Storage::disk('public')->exists($filePath)) {
                return null;
            }

            return $filePath;
        }

        /*
        |--------------------------------------------------------------------------
        | OTHER FILES
        |--------------------------------------------------------------------------
        */

        $filename = time() . '_' . uniqid() . '.' . $extension;

        Storage::disk('public')->putFileAs(
            $folder,
            $file,
            $filename
        );

        $filePath = $folder . '/' . $filename;

        /*
        |--------------------------------------------------------------------------
        | Verify
        |--------------------------------------------------------------------------
        */

        if (!Storage::disk('public')->exists($filePath)) {
            return null;
        }

        return $filePath;
    }

    /*
    |--------------------------------------------------------------------------
    | Delete File
    |--------------------------------------------------------------------------
    */

    public function delete(?string $filePath): void
    {
        if (
            $filePath &&
            Storage::disk('public')->exists($filePath)
        ) {

            Storage::disk('public')->delete(
                $filePath
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Replace File
    |--------------------------------------------------------------------------
    */

    public function replace(
        $file,
        ?string $oldFilePath,
        ?string $folder = null,
        $model = null
    ): ?string {

        if (!$file || !$file->isValid()) {
            return $oldFilePath;
        }

        /*
        |--------------------------------------------------------------------------
        | Upload New File First
        |--------------------------------------------------------------------------
        */

        $newFilePath = $this->upload(
            $file,
            $folder,
            $model
        );

        /*
        |--------------------------------------------------------------------------
        | Only Delete Old File If New Upload Succeeded
        |--------------------------------------------------------------------------
        */

        if ($newFilePath) {

            if ($oldFilePath) {

                $this->delete(
                    $oldFilePath
                );
            }

            return $newFilePath;
        }

        /*
        |--------------------------------------------------------------------------
        | Keep Existing File
        |--------------------------------------------------------------------------
        */

        return $oldFilePath;
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Folder From Model
    |--------------------------------------------------------------------------
    */

    private function generateFolderFromModel($model): string
    {
        $className = class_basename($model);

        return strtolower(
            preg_replace(
                '/(?<!^)[A-Z]/',
                '-$0',
                $className
            )
        );
    }
}