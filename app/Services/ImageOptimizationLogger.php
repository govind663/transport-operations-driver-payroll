<?php

namespace App\Services;

use App\Models\ImageOptimizationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ImageOptimizationLogger
{
    /**
     * Temporary rows collected during current request.
     */
    protected array $rows = [];

    /**
     * System notes collected during current request.
     */
    protected array $systemNotes = [];

    /**
     * Logging configuration.
     */
    protected bool $databaseLoggingEnabled = true;

    protected bool $fileLoggingEnabled = true;

    /**
     * Maximum image logs per request.
     */
    protected int $maxRowsPerRequest = 80;

    /**
     * 100 = every request
     * 50  = approximately 50%
     * 20  = approximately 20%
     */
    protected int $sampleRate = 100;


    /*
    |--------------------------------------------------------------------------
    | Push Image Row
    |--------------------------------------------------------------------------
    */

    public function push(array $row): void
    {
        if (count($this->rows) >= $this->maxRowsPerRequest) {
            return;
        }

        $this->rows[] = $row;
    }


    /*
    |--------------------------------------------------------------------------
    | Push System Note
    |--------------------------------------------------------------------------
    */

    public function pushSystemNote(array $note): void
    {
        $this->systemNotes[] = $note;
    }


    /*
    |--------------------------------------------------------------------------
    | Persist
    |--------------------------------------------------------------------------
    */

    public function persist(Request $request): void
    {
        /*
        |--------------------------------------------------------------------------
        | Sampling
        |--------------------------------------------------------------------------
        */

        if (!$this->shouldPersist()) {
            $this->reset();

            return;
        }

        try {

            /*
            |--------------------------------------------------------------------------
            | Database
            |--------------------------------------------------------------------------
            */

            if (
                $this->databaseLoggingEnabled &&
                !empty($this->rows)
            ) {
                $this->persistToDatabase($request);
            }


            /*
            |--------------------------------------------------------------------------
            | Human Readable File Log
            |--------------------------------------------------------------------------
            */

            if (
                $this->fileLoggingEnabled &&
                (
                    !empty($this->rows) ||
                    !empty($this->systemNotes)
                )
            ) {
                $this->writeHumanReadableLog($request);
            }

        } catch (Throwable $e) {

            Log::warning(
                'ImageOptimizationLogger persist failed.',
                [
                    'message' => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine(),
                ]
            );

        } finally {

            /*
            |--------------------------------------------------------------------------
            | Always clear request memory
            |--------------------------------------------------------------------------
            */

            $this->reset();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | System Failure Log
    |--------------------------------------------------------------------------
    */

    public function logSystemFailure(
        Request $request,
        Throwable $e
    ): void {

        try {

            $date = now()->format('Y-m-d');

            $time = now()->format('H-i-s');

            $path = trim(
                str_replace(
                    ['/', '\\'],
                    '-',
                    $request->path()
                ),
                '-'
            );

            $path = $path !== ''
                ? $path
                : 'home';

            $file =
                "activity/image-brain-errors/{$date}/" .
                "{$time}-{$path}.log";


            $lines = [

                '========================================',

                'Image Brain System Failure',

                '========================================',

                'Date Time : ' .
                    now()->format('Y-m-d H:i:s'),

                'Route     : /' .
                    ltrim($request->path(), '/'),

                'Method    : ' .
                    $request->method(),

                'Error     : ' .
                    $this->sanitize(
                        $e->getMessage(),
                        500
                    ),

                'File      : ' .
                    $this->sanitize(
                        $e->getFile(),
                        500
                    ),

                'Line      : ' .
                    $e->getLine(),

                '----------------------------------------',
            ];


            Storage::disk('local')->put(
                $file,
                implode(PHP_EOL, $lines)
            );

        } catch (Throwable $failure) {

            Log::warning(
                'Unable to write image optimizer failure log.',
                [
                    'message' => $failure->getMessage(),
                ]
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Persist Image Logs To Database
    |--------------------------------------------------------------------------
    */

    protected function persistToDatabase(
        Request $request
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Auth User
        |--------------------------------------------------------------------------
        |
        | Do NOT force user ID = 1.
        | Guest pages can legitimately have NULL.
        |
        */

        $userId = Auth::id();

        $insertData = [];

        $now = now();


        foreach ($this->rows as $row) {

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            | image_meta_id is now explicitly inserted.
            |
            */

            $imageMetaId = !empty($row['image_meta_id'])
                ? (int) $row['image_meta_id']
                : null;


            $insertData[] = [

                /*
                |--------------------------------------------------------------------------
                | Request
                |--------------------------------------------------------------------------
                */

                'request_id' => $this->sanitize(
                    $row['request_id'] ?? null,
                    100
                ),

                'route_path' => $this->sanitize(
                    $row['route_path'] ?? null,
                    255
                ),

                'full_url' => $this->sanitize(
                    $row['full_url'] ?? null,
                    2048
                ),

                'http_method' => $this->sanitize(
                    $row['http_method'] ?? null,
                    20
                ),


                /*
                |--------------------------------------------------------------------------
                | IMAGE META RELATION
                |--------------------------------------------------------------------------
                |
                | This was missing in your previous logger.
                |
                */

                'image_meta_id' => $imageMetaId,


                /*
                |--------------------------------------------------------------------------
                | Image Information
                |--------------------------------------------------------------------------
                */

                'image_position' => (int) (
                    $row['image_position'] ?? 0
                ),

                'image_src' => $this->sanitize(
                    $row['image_src'] ?? null,
                    2048
                ),

                'image_alt' => $this->sanitize(
                    $row['image_alt'] ?? null,
                    500
                ),

                'image_class' => $this->sanitize(
                    $row['image_class'] ?? null,
                    500
                ),

                'image_id' => $this->sanitize(
                    $row['image_id'] ?? null,
                    255
                ),

                'image_role' => $this->sanitize(
                    $row['image_role'] ?? null,
                    255
                ),


                /*
                |--------------------------------------------------------------------------
                | Optimization
                |--------------------------------------------------------------------------
                */

                'status' => $this->sanitize(
                    $row['status'] ?? null,
                    50
                ),

                'mode' => $this->sanitize(
                    $row['mode'] ?? null,
                    50
                ),

                'score' => (int) (
                    $row['score'] ?? 0
                ),

                'confidence' => $this->sanitize(
                    $row['confidence'] ?? null,
                    20
                ),


                /*
                |--------------------------------------------------------------------------
                | Browser Attributes
                |--------------------------------------------------------------------------
                */

                'loading_value' => $this->sanitize(
                    $row['loading_value'] ?? null,
                    20
                ),

                'fetchpriority_value' => $this->sanitize(
                    $row['fetchpriority_value'] ?? null,
                    20
                ),

                'decoding_value' => $this->sanitize(
                    $row['decoding_value'] ?? null,
                    20
                ),


                /*
                |--------------------------------------------------------------------------
                | Dimensions
                |--------------------------------------------------------------------------
                */

                'image_width' => $this->nullableInteger(
                    $row['image_width'] ?? null
                ),

                'image_height' => $this->nullableInteger(
                    $row['image_height'] ?? null
                ),


                /*
                |--------------------------------------------------------------------------
                | JSON Data
                |--------------------------------------------------------------------------
                */

                'reasons' => json_encode(
                    $row['reasons'] ?? [],
                    JSON_UNESCAPED_UNICODE |
                    JSON_UNESCAPED_SLASHES
                ),

                'context_payload' => json_encode(
                    $row['context_payload'] ?? [],
                    JSON_UNESCAPED_UNICODE |
                    JSON_UNESCAPED_SLASHES
                ),


                /*
                |--------------------------------------------------------------------------
                | Request Information
                |--------------------------------------------------------------------------
                */

                'user_agent' => $this->sanitize(
                    $request->userAgent(),
                    1000
                ),

                'ip_address' => $this->sanitize(
                    $request->ip(),
                    45
                ),


                /*
                |--------------------------------------------------------------------------
                | Audit
                |--------------------------------------------------------------------------
                */

                'created_by' => $userId,

                'updated_by' => $userId,

                'created_at' => $now,

                'updated_at' => $now,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Bulk Insert
        |--------------------------------------------------------------------------
        */

        if (!empty($insertData)) {

            ImageOptimizationLog::query()->insert(
                $insertData
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Human Readable File Log
    |--------------------------------------------------------------------------
    */

    protected function writeHumanReadableLog(
        Request $request
    ): void {

        $date = now()->format('Y-m-d');

        $time = now()->format('H-i-s');

        $path = trim(
            str_replace(
                ['/', '\\'],
                '-',
                $request->path()
            ),
            '-'
        );

        $path = $path !== ''
            ? $path
            : 'home';


        $file =
            "activity/image-brain/{$date}/" .
            "{$time}-{$path}-" .
            Str::random(6) .
            ".log";


        $lines = [];

        $lines[] =
            '========================================';

        $lines[] =
            'Smart Image Brain Activity';

        $lines[] =
            '========================================';

        $lines[] =
            'Date Time : ' .
            now()->format('Y-m-d H:i:s');

        $lines[] =
            'Route     : /' .
            ltrim($request->path(), '/');

        $lines[] =
            'Method    : ' .
            $request->method();

        $lines[] =
            'Rows      : ' .
            count($this->rows);

        $lines[] =
            '----------------------------------------';


        foreach ($this->rows as $index => $row) {

            $lines[] =
                'Image #' .
                ($index + 1);

            $lines[] =
                'Meta ID       : ' .
                ($row['image_meta_id'] ?? 'N/A');

            $lines[] =
                'Position      : ' .
                ($row['image_position'] ?? 'N/A');

            $lines[] =
                'Status        : ' .
                ($row['status'] ?? 'N/A');

            $lines[] =
                'Mode          : ' .
                ($row['mode'] ?? 'N/A');

            $lines[] =
                'Confidence    : ' .
                ($row['confidence'] ?? 'N/A');

            $lines[] =
                'Score         : ' .
                ($row['score'] ?? 0);

            $lines[] =
                'Loading       : ' .
                ($row['loading_value'] ?? 'N/A');

            $lines[] =
                'FetchPriority : ' .
                ($row['fetchpriority_value'] ?? 'N/A');

            $lines[] =
                'Decoding      : ' .
                ($row['decoding_value'] ?? 'N/A');

            $lines[] =
                'Source        : ' .
                $this->sanitize(
                    $row['image_src'] ?? 'N/A',
                    1000
                );

            $lines[] =
                'Width         : ' .
                ($row['image_width'] ?? 'N/A');

            $lines[] =
                'Height        : ' .
                ($row['image_height'] ?? 'N/A');

            $lines[] =
                'Reasons       :';


            foreach (
                ($row['reasons'] ?? []) as $reason
            ) {

                $lines[] =
                    '  - ' .
                    $this->sanitize(
                        (string) $reason,
                        180
                    );
            }


            $lines[] =
                '----------------------------------------';
        }


        /*
        |--------------------------------------------------------------------------
        | System Notes
        |--------------------------------------------------------------------------
        */

        if (!empty($this->systemNotes)) {

            $lines[] =
                'System Notes';

            $lines[] =
                '----------------------------------------';


            foreach ($this->systemNotes as $note) {

                $lines[] =
                    'Type    : ' .
                    $this->sanitize(
                        $note['note_type'] ?? 'N/A',
                        100
                    );

                $lines[] =
                    'Message : ' .
                    $this->sanitize(
                        $note['message'] ?? 'N/A',
                        250
                    );

                $lines[] =
                    '----------------------------------------';
            }
        }


        Storage::disk('local')->put(
            $file,
            implode(PHP_EOL, $lines)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Sampling
    |--------------------------------------------------------------------------
    */

    protected function shouldPersist(): bool
    {
        if ($this->sampleRate >= 100) {
            return true;
        }

        if ($this->sampleRate <= 0) {
            return false;
        }

        return random_int(
            1,
            100
        ) <= $this->sampleRate;
    }


    /*
    |--------------------------------------------------------------------------
    | Sanitize
    |--------------------------------------------------------------------------
    */

    protected function sanitize(
        ?string $value,
        int $limit = 255
    ): ?string {

        if ($value === null) {
            return null;
        }

        $value = strip_tags($value);

        $value = preg_replace(
            '/\s+/',
            ' ',
            $value
        ) ?? '';

        $value = trim($value);


        if ($value === '') {
            return null;
        }


        return Str::limit(
            $value,
            $limit,
            ''
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Nullable Integer
    |--------------------------------------------------------------------------
    */

    protected function nullableInteger(
        mixed $value
    ): ?int {

        if (
            $value === null ||
            $value === '' ||
            !is_numeric($value)
        ) {
            return null;
        }

        return (int) $value;
    }


    /*
    |--------------------------------------------------------------------------
    | Reset
    |--------------------------------------------------------------------------
    */

    protected function reset(): void
    {
        $this->rows = [];

        $this->systemNotes = [];
    }
}