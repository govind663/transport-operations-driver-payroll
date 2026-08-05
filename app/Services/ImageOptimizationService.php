<?php

namespace App\Services;

use App\Models\ImageMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class ImageOptimizationService
{
    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    */

    protected float $startedAt = 0.0;

    protected int $maxImagesToAnalyze = 60;

    protected int $maxStringLength = 500;

    /*
    |--------------------------------------------------------------------------
    | IMPORTANT
    |--------------------------------------------------------------------------
    |
    | 0.1 second was too aggressive for database/meta operations.
    | 0.5 sec gives the optimizer enough time without making the
    | request unnecessarily expensive.
    |
    */

    protected float $maxExecutionSeconds = 0.5;

    protected ?string $cdnBaseUrl = null;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        protected ImageLearningService $learningService,
        protected ImageOptimizationLogger $logger,
        protected ImageSizeDetector $sizeDetector
    ) {
        $this->cdnBaseUrl = config('app.image_cdn_url');

        /*
        |--------------------------------------------------------------------------
        | Normalize CDN URL
        |--------------------------------------------------------------------------
        */

        if ($this->cdnBaseUrl) {
            $this->cdnBaseUrl = rtrim(
                $this->cdnBaseUrl,
                '/'
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Optimize HTML
    |--------------------------------------------------------------------------
    */

    public function optimize(
        string $html,
        Request $request
    ): string {

        $this->startedAt = microtime(true);

        try {

            $routePath = '/' .
                trim($request->path(), '/');

            /*
            |--------------------------------------------------------------------------
            | Root route
            |--------------------------------------------------------------------------
            */

            if ($routePath === '/') {
                $routePath = '/';
            }

            /*
            |--------------------------------------------------------------------------
            | Request ID
            |--------------------------------------------------------------------------
            */

            $requestId = (string) Str::uuid();

            $position = 0;


            /*
            |--------------------------------------------------------------------------
            | Browser WebP Support
            |--------------------------------------------------------------------------
            */

            $acceptHeader = strtolower(
                (string) $request->header('Accept', '')
            );

            $supportsWebp = str_contains(
                $acceptHeader,
                'image/webp'
            );


            /*
            |--------------------------------------------------------------------------
            | Process Images
            |--------------------------------------------------------------------------
            */

            $result = preg_replace_callback(
                '/<img\b[^>]*>/i',

                function ($matches) use (
                    &$position,
                    $routePath,
                    $request,
                    $requestId,
                    $supportsWebp
                ) {

                    try {

                        /*
                        |--------------------------------------------------------------------------
                        | Time Protection
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $this->timeBudgetExceeded()
                        ) {
                            return $matches[0];
                        }


                        $position++;


                        /*
                        |--------------------------------------------------------------------------
                        | Maximum Images
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $position >
                            $this->maxImagesToAnalyze
                        ) {
                            return $matches[0];
                        }


                        $imgTag = $matches[0];


                        /*
                        |--------------------------------------------------------------------------
                        | SRC
                        |--------------------------------------------------------------------------
                        */

                        preg_match(
                            '/\bsrc\s*=\s*["\']([^"\']+)["\']/i',
                            $imgTag,
                            $srcMatch
                        );

                        $src = $srcMatch[1] ?? '';


                        if ($src === '') {
                            return $imgTag;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Parse URL
                        |--------------------------------------------------------------------------
                        */

                        $parsedPath = parse_url(
                            $src,
                            PHP_URL_PATH
                        );

                        if ($parsedPath) {
                            $src = $parsedPath;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Skip External / Data Images
                        |--------------------------------------------------------------------------
                        */

                        if (
                            str_starts_with(
                                strtolower($src),
                                'http://'
                            ) ||
                            str_starts_with(
                                strtolower($src),
                                'https://'
                            ) ||
                            str_starts_with(
                                $src,
                                '//'
                            ) ||
                            str_starts_with(
                                strtolower($src),
                                'data:'
                            ) ||
                            str_starts_with(
                                strtolower($src),
                                'blob:'
                            )
                        ) {
                            return $imgTag;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Explicit No Optimize
                        |--------------------------------------------------------------------------
                        */

                        if (
                            preg_match(
                                '/\bdata-no-optimize\b/i',
                                $imgTag
                            )
                        ) {
                            return $imgTag;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Attributes
                        |--------------------------------------------------------------------------
                        */

                        preg_match(
                            '/\balt\s*=\s*["\']([^"\']*)["\']/i',
                            $imgTag,
                            $altMatch
                        );

                        preg_match(
                            '/\bclass\s*=\s*["\']([^"\']*)["\']/i',
                            $imgTag,
                            $classMatch
                        );

                        preg_match(
                            '/\bid\s*=\s*["\']([^"\']*)["\']/i',
                            $imgTag,
                            $idMatch
                        );

                        preg_match(
                            '/\bwidth\s*=\s*["\']([^"\']*)["\']/i',
                            $imgTag,
                            $widthMatch
                        );

                        preg_match(
                            '/\bheight\s*=\s*["\']([^"\']*)["\']/i',
                            $imgTag,
                            $heightMatch
                        );


                        $imageAlt =
                            $altMatch[1] ?? '';

                        $imageClass =
                            $classMatch[1] ?? '';

                        $imageId =
                            $idMatch[1] ?? '';

                        $imageWidth =
                            $widthMatch[1] ?? null;

                        $imageHeight =
                            $heightMatch[1] ?? null;


                        /*
                        |--------------------------------------------------------------------------
                        | Context
                        |--------------------------------------------------------------------------
                        */

                        $context = strtolower(
                            $imgTag
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Image Role
                        |--------------------------------------------------------------------------
                        */

                        $imageRole = 'content';

                        if (
                            str_contains(
                                $context,
                                'hero'
                            )
                        ) {

                            $imageRole = 'hero';

                        } elseif (
                            str_contains(
                                $context,
                                'logo'
                            )
                        ) {

                            $imageRole = 'logo';

                        } elseif (
                            str_contains(
                                $context,
                                'product'
                            )
                        ) {

                            $imageRole = 'product';

                        } elseif (
                            str_contains(
                                $context,
                                'icon'
                            )
                        ) {

                            $imageRole = 'icon';
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | AI Score
                        |--------------------------------------------------------------------------
                        */

                        $score = 0;

                        $confidence = 0;

                        $reasons = [];


                        if ($position === 1) {

                            $score += 50;

                            $confidence += 30;

                            $reasons[] =
                                'LCP candidate';
                        }


                        if (
                            $imageRole === 'hero'
                        ) {

                            $score += 40;

                            $confidence += 25;

                            $reasons[] =
                                'Hero image';
                        }


                        if (
                            $imageRole === 'product'
                        ) {

                            $score += 25;

                            $confidence += 15;

                            $reasons[] =
                                'Product image';
                        }


                        if (
                            $imageRole === 'logo'
                        ) {

                            $score += 20;

                            $confidence += 10;

                            $reasons[] =
                                'Logo image';
                        }


                        if (
                            $imageRole === 'icon'
                        ) {

                            $score -= 25;

                            $reasons[] =
                                'Icon image';
                        }


                        if ($position <= 3) {
                            $score += 15;
                        }


                        if ($position >= 10) {
                            $score -= 10;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Adaptive Learning
                        |--------------------------------------------------------------------------
                        */

                        $adaptive =
                            $this->learningService
                                ->getAdaptiveBoost(
                                    $routePath,
                                    $context,
                                    $position
                                );


                        $score +=
                            (int) (
                                $adaptive['boost'] ?? 0
                            );


                        $confidence +=
                            (int) (
                                $adaptive['confidence'] ?? 0
                            );


                        $reasons = array_merge(
                            $reasons,
                            $adaptive['reasons'] ?? []
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Priority
                        |--------------------------------------------------------------------------
                        */

                        $loading = 'lazy';

                        $fetchpriority = '';

                        $mode = 'deferred';


                        if ($score >= 80) {

                            $loading = 'eager';

                            $fetchpriority = 'high';

                            $mode = 'critical';

                        } elseif ($score >= 40) {

                            $loading = 'eager';

                            $fetchpriority = 'auto';

                            $mode = 'important';
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | CLEAN ORIGINAL PATH
                        |--------------------------------------------------------------------------
                        */

                        $originalPath = $src;


                        $cleanPath = parse_url(
                            $originalPath,
                            PHP_URL_PATH
                        );


                        $cleanPath = ltrim(
                            (string) $cleanPath,
                            '/'
                        );


                        if ($cleanPath === '') {
                            return $imgTag;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | IMAGE META
                        |--------------------------------------------------------------------------
                        */

                        $meta = null;


                        /*
                        |--------------------------------------------------------------------------
                        | 1. Size Detector
                        |--------------------------------------------------------------------------
                        */

                        try {

                            $size =
                                $this->sizeDetector
                                    ->getSize(
                                        $cleanPath
                                    );

                        } catch (Throwable $e) {

                            $size = null;

                            Log::warning(
                                'ImageSizeDetector failed.',
                                [
                                    'path' => $cleanPath,
                                    'message' =>
                                        $e->getMessage(),
                                ]
                            );
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Resolve Meta From Detector
                        |--------------------------------------------------------------------------
                        */

                        if (
                            is_array($size) &&
                            !empty($size['meta']) &&
                            $size['meta'] instanceof ImageMeta
                        ) {

                            $meta =
                                $size['meta'];
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | 2. Exact Match
                        |--------------------------------------------------------------------------
                        */

                        if (!$meta) {

                            $meta =
                                ImageMeta::query()
                                    ->where(
                                        'path',
                                        $cleanPath
                                    )
                                    ->whereNull(
                                        'deleted_at'
                                    )
                                    ->first();
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | 3. Extension Agnostic Match
                        |--------------------------------------------------------------------------
                        */

                        if (!$meta) {

                            $filename =
                                pathinfo(
                                    $cleanPath,
                                    PATHINFO_FILENAME
                                );


                            if ($filename !== '') {

                                $meta =
                                    ImageMeta::query()
                                        ->whereNull(
                                            'deleted_at'
                                        )
                                        ->where(
                                            'path',
                                            'LIKE',
                                            '%' .
                                            $filename .
                                            '%'
                                        )
                                        ->latest('id')
                                        ->first();
                            }
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | 4. CREATE IMAGE META
                        |--------------------------------------------------------------------------
                        |
                        | This is the important fix.
                        |
                        | If image_meta does not exist,
                        | create it automatically.
                        |
                        */

                        if (!$meta) {

                            try {

                                $detectedWidth =
                                    $this->nullableInteger(
                                        $size['width'] ?? null
                                    );

                                $detectedHeight =
                                    $this->nullableInteger(
                                        $size['height'] ?? null
                                    );

                                $detectedType =
                                    $this->detectImageType(
                                        $cleanPath
                                    );

                                $detectedFileSize =
                                    $this->detectFileSize(
                                        $cleanPath
                                    );

                                $detectedHash =
                                    $this->generateImageHash(
                                        $cleanPath
                                    );


                                $meta =
                                    ImageMeta::query()
                                        ->create([
                                            'path' =>
                                                $cleanPath,

                                            'width' =>
                                                $detectedWidth,

                                            'height' =>
                                                $detectedHeight,

                                            'type' =>
                                                $detectedType,

                                            'file_size' =>
                                                $detectedFileSize,

                                            'hash' =>
                                                $detectedHash,
                                        ]);

                            } catch (Throwable $e) {

                                Log::warning(
                                    'ImageMeta creation failed.',
                                    [
                                        'path' =>
                                            $cleanPath,

                                        'message' =>
                                            $e->getMessage(),
                                    ]
                                );
                            }
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | META ID
                        |--------------------------------------------------------------------------
                        */

                        $metaId =
                            $meta?->id;


                        /*
                        |--------------------------------------------------------------------------
                        | Actual Dimensions
                        |--------------------------------------------------------------------------
                        */

                        $detectedWidth =
                            $size['width'] ?? null;

                        $detectedHeight =
                            $size['height'] ?? null;


                        /*
                        |--------------------------------------------------------------------------
                        | Apply Detected Dimensions
                        |--------------------------------------------------------------------------
                        */

                        if (
                            !$imageWidth &&
                            $detectedWidth
                        ) {

                            $imageWidth =
                                (string) $detectedWidth;
                        }


                        if (
                            !$imageHeight &&
                            $detectedHeight
                        ) {

                            $imageHeight =
                                (string) $detectedHeight;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Add Dimensions / Fallback Style
                        |--------------------------------------------------------------------------
                        */

                        if (
                            !$imageWidth ||
                            !$imageHeight
                        ) {

                            if (
                                $detectedWidth &&
                                $detectedHeight
                            ) {

                                $imgTag =
                                    str_replace(
                                        '<img',
                                        '<img width="' .
                                        $detectedWidth .
                                        '" height="' .
                                        $detectedHeight .
                                        '"',
                                        $imgTag
                                    );

                            } elseif (
                                !preg_match(
                                    '/\bstyle\s*=/i',
                                    $imgTag
                                )
                            ) {

                                $imgTag =
                                    str_replace(
                                        '<img',
                                        '<img style="height:auto; max-width:100%;"',
                                        $imgTag
                                    );
                            }
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Build Optimized Source
                        |--------------------------------------------------------------------------
                        */

                        $optimizedSrc =
                            $cleanPath;


                        /*
                        |--------------------------------------------------------------------------
                        | WebP
                        |--------------------------------------------------------------------------
                        */

                        if ($supportsWebp) {

                            $optimizedSrc =
                                preg_replace(
                                    '/\.(jpg|jpeg|png)$/i',
                                    '.webp',
                                    $optimizedSrc
                                );
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | CDN
                        |--------------------------------------------------------------------------
                        */

                        if ($this->cdnBaseUrl) {

                            $optimizedSrc =
                                $this->cdnBaseUrl .
                                '/' .
                                ltrim(
                                    $optimizedSrc,
                                    '/'
                                );
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Update SRC
                        |--------------------------------------------------------------------------
                        */

                        $imgTag =
                            preg_replace(
                                '/\bsrc\s*=\s*["\']([^"\']+)["\']/i',
                                'src="' .
                                e($optimizedSrc) .
                                '"',
                                $imgTag
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | Decoding
                        |--------------------------------------------------------------------------
                        */

                        if (
                            !preg_match(
                                '/\bdecoding\s*=/i',
                                $imgTag
                            )
                        ) {

                            $imgTag =
                                str_replace(
                                    '<img',
                                    '<img decoding="async"',
                                    $imgTag
                                );
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Loading
                        |--------------------------------------------------------------------------
                        */

                        if (
                            !preg_match(
                                '/\bloading\s*=/i',
                                $imgTag
                            )
                        ) {

                            $imgTag =
                                str_replace(
                                    '<img',
                                    '<img loading="' .
                                    $loading .
                                    '"',
                                    $imgTag
                                );
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Fetch Priority
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $fetchpriority !== '' &&
                            !preg_match(
                                '/\bfetchpriority\s*=/i',
                                $imgTag
                            )
                        ) {

                            $imgTag =
                                str_replace(
                                    '<img',
                                    '<img fetchpriority="' .
                                    $fetchpriority .
                                    '"',
                                    $imgTag
                                );
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | LOGGER
                        |--------------------------------------------------------------------------
                        */

                        $this->logger->push([

                            /*
                            |--------------------------------------------------------------------------
                            | Request
                            |--------------------------------------------------------------------------
                            */

                            'request_id' =>
                                $requestId,

                            'route_path' =>
                                $routePath,

                            'full_url' =>
                                $request->fullUrl(),

                            'http_method' =>
                                $request->method(),


                            /*
                            |--------------------------------------------------------------------------
                            | Image
                            |--------------------------------------------------------------------------
                            */

                            'image_position' =>
                                $position,

                            'image_src' =>
                                $meta?->path ??
                                $cleanPath,

                            'image_alt' =>
                                $this->sanitizeString(
                                    $imageAlt
                                ),

                            'image_class' =>
                                $this->sanitizeString(
                                    $imageClass
                                ),

                            'image_id' =>
                                $this->sanitizeString(
                                    $imageId
                                ),

                            'image_width' =>
                                $this->nullableInteger(
                                    $imageWidth
                                ) ??
                                $detectedWidth,

                            'image_height' =>
                                $this->nullableInteger(
                                    $imageHeight
                                ) ??
                                $detectedHeight,

                            'image_role' =>
                                $imageRole,


                            /*
                            |--------------------------------------------------------------------------
                            | IMPORTANT
                            |--------------------------------------------------------------------------
                            */

                            'image_meta_id' =>
                                $metaId,


                            /*
                            |--------------------------------------------------------------------------
                            | Optimization
                            |--------------------------------------------------------------------------
                            */

                            'status' =>
                                'optimized',

                            'mode' =>
                                $mode,

                            'score' =>
                                $score,

                            'confidence' =>
                                $confidence >= 60
                                    ? 'high'
                                    : (
                                        $confidence >= 30
                                            ? 'medium'
                                            : 'low'
                                    ),


                            /*
                            |--------------------------------------------------------------------------
                            | Browser
                            |--------------------------------------------------------------------------
                            */

                            'loading_value' =>
                                $loading,

                            'fetchpriority_value' =>
                                $fetchpriority,

                            'decoding_value' =>
                                'async',


                            /*
                            |--------------------------------------------------------------------------
                            | Learning
                            |--------------------------------------------------------------------------
                            */

                            'reasons' =>
                                $this->sanitizeReasons(
                                    $reasons
                                ),

                            'context_payload' => [

                                'route_path' =>
                                    $routePath,

                                'position' =>
                                    $position,

                                'image_meta_id' =>
                                    $metaId,

                                'supports_webp' =>
                                    $supportsWebp,

                                'original_path' =>
                                    $cleanPath,

                                'optimized_path' =>
                                    $optimizedSrc,
                            ],
                        ]);


                        /*
                        |--------------------------------------------------------------------------
                        | Return Optimized IMG
                        |--------------------------------------------------------------------------
                        */

                        return $imgTag;

                    } catch (Throwable $e) {

                        /*
                        |--------------------------------------------------------------------------
                        | NEVER BREAK PAGE
                        |--------------------------------------------------------------------------
                        */

                        Log::warning(
                            'Image optimization failed.',
                            [
                                'route' =>
                                    $request->path(),

                                'position' =>
                                    $position,

                                'message' =>
                                    $e->getMessage(),

                                'file' =>
                                    $e->getFile(),

                                'line' =>
                                    $e->getLine(),
                            ]
                        );

                        return $matches[0];
                    }

                },
                $html
            );


            /*
            |--------------------------------------------------------------------------
            | preg_replace_callback Failure
            |--------------------------------------------------------------------------
            */

            return is_string($result)
                ? $result
                : $html;

        } catch (Throwable $e) {

            Log::warning(
                'ImageOptimizationService failed.',
                [
                    'message' =>
                        $e->getMessage(),

                    'file' =>
                        $e->getFile(),

                    'line' =>
                        $e->getLine(),
                ]
            );

            return $html;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Detect Image Type
    |--------------------------------------------------------------------------
    */

    protected function detectImageType(
        string $path
    ): ?string {

        $extension =
            strtolower(
                pathinfo(
                    $path,
                    PATHINFO_EXTENSION
                )
            );


        return match ($extension) {

            'jpg',
            'jpeg' => 'image/jpeg',

            'png' => 'image/png',

            'webp' => 'image/webp',

            'gif' => 'image/gif',

            'svg' => 'image/svg+xml',

            'avif' => 'image/avif',

            default => null,
        };
    }


    /*
    |--------------------------------------------------------------------------
    | Detect File Size
    |--------------------------------------------------------------------------
    */

    protected function detectFileSize(
        string $path
    ): ?int {

        try {

            $fullPath =
                public_path(
                    ltrim($path, '/')
                );


            if (
                is_file($fullPath) &&
                is_readable($fullPath)
            ) {

                $size =
                    filesize($fullPath);

                return $size !== false
                    ? (int) $size
                    : null;
            }

        } catch (Throwable) {

            return null;
        }


        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | Generate Image Hash
    |--------------------------------------------------------------------------
    */

    protected function generateImageHash(
        string $path
    ): ?string {

        try {

            $fullPath =
                public_path(
                    ltrim($path, '/')
                );


            if (
                is_file($fullPath) &&
                is_readable($fullPath)
            ) {

                $hash =
                    hash_file(
                        'sha256',
                        $fullPath
                    );

                return $hash ?: null;
            }

        } catch (Throwable) {

            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | Fallback Hash
        |--------------------------------------------------------------------------
        */

        return hash(
            'sha256',
            $path
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Sanitize Reasons
    |--------------------------------------------------------------------------
    */

    protected function sanitizeReasons(
        array $reasons
    ): array {

        return collect($reasons)
            ->map(
                fn ($reason) =>
                    $this->sanitizeString(
                        (string) $reason,
                        180
                    )
            )
            ->filter()
            ->take(10)
            ->values()
            ->all();
    }


    /*
    |--------------------------------------------------------------------------
    | Sanitize String
    |--------------------------------------------------------------------------
    */

    protected function sanitizeString(
        ?string $value,
        ?int $limit = null
    ): string {

        $value =
            $value ?? '';

        $value =
            strip_tags($value);

        $value =
            preg_replace(
                '/\s+/',
                ' ',
                $value
            ) ?? '';

        $value =
            trim($value);


        return Str::limit(
            $value,
            $limit ??
                $this->maxStringLength,
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
    | Time Budget
    |--------------------------------------------------------------------------
    */

    protected function timeBudgetExceeded(): bool
    {
        return (
            microtime(true) -
            $this->startedAt
        ) >= $this->maxExecutionSeconds;
    }
}