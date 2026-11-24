<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\File;
use InvalidArgumentException;
use RuntimeException;

class WhatsAppMediaService
{
    /**
     * WhatsApp Cloud API service.
     */
    protected WhatsAppCloudApiService $cloudApiService;

    /**
     * Allowed document types and their storage folders.
     */
    protected array $documentTypes = [
        'attendance' => [
            'folder' => 'attendance',
            'filename' => 'attendance',
        ],

        'working_sheet' => [
            'folder' => 'working-sheets',
            'filename' => 'working-sheet',
        ],

        'salary_slip' => [
            'folder' => 'salary-slips',
            'filename' => 'salary-slip',
        ],
    ];

    /**
     * Constructor.
     */
    public function __construct(
        WhatsAppCloudApiService $cloudApiService
    ) {
        $this->cloudApiService = $cloudApiService;
    }

    /**
     * Prepare an existing PDF for WhatsApp delivery.
     *
     * Local mode:
     * - Validate PDF.
     * - Return local document information.
     * - Do not upload to Meta.
     *
     * Live mode:
     * - Validate PDF.
     * - Upload PDF to Meta.
     * - Return Meta media ID.
     *
     * Compatible with WhatsAppMasterService:
     *
     * prepareDocument($documentPath, $documentFilename)
     *
     * @return array
     */
    public function prepareDocument(
        string $documentPath,
        ?string $documentFilename = null
    ): array {
        $mode = $this->getMode();

        $document = $this->validateDocument(
            $documentPath,
            $documentFilename
        );

        /*
         * Local mode:
         * No Meta API request is made.
         */
        if ($mode === 'local') {
            return [
                'id' => null,
                'media_id' => null,
                'filename' => $document['filename'],
                'mime_type' => $document['mime_type'],
                'size_bytes' => $document['size_bytes'],
                'size_mb' => $document['size_mb'],
                'path' => $document['relative_path'],
                'absolute_path' => $document['absolute_path'],
                'mode' => 'local',
                'uploaded' => false,
            ];
        }

        /*
         * Live mode:
         * Upload the validated PDF to Meta.
         *
         * No Personal WhatsApp fallback is performed here.
         */
        $response = $this->cloudApiService->uploadMedia(
            $document['absolute_path'],
            $document['filename']
        );

        $mediaId = $response['id'] ?? null;

        if (
            !is_string($mediaId) ||
            trim($mediaId) === ''
        ) {
            throw new RuntimeException(
                'Meta WhatsApp did not return a valid PDF media ID.'
            );
        }

        return [
            'id' => $mediaId,
            'media_id' => $mediaId,
            'filename' => $document['filename'],
            'mime_type' => $document['mime_type'],
            'size_bytes' => $document['size_bytes'],
            'size_mb' => $document['size_mb'],
            'path' => $document['relative_path'],
            'absolute_path' => $document['absolute_path'],
            'mode' => 'live',
            'uploaded' => true,
        ];
    }

    /**
     * Automatically save a generated PDF.
     *
     * Supported document types:
     * - attendance
     * - working_sheet
     * - salary_slip
     *
     * Storage structure:
     *
     * storage/app/whatsapp/drivers/{year}/{month}/attendance/
     * storage/app/whatsapp/drivers/{year}/{month}/working-sheets/
     * storage/app/whatsapp/drivers/{year}/{month}/salary-slips/
     *
     * Existing same-name PDF will be replaced.
     *
     * This method saves already-generated PDF content.
     * It does not generate or calculate the report.
     *
     * @return array
     */
    public function storeGeneratedPdf(
        string $pdfContent,
        string $documentType,
        int|string $driverId,
        int $year,
        int $month,
        ?string $filename = null
    ): array {
        $documentType = $this->normalizeDocumentType(
            $documentType
        );

        $driverId = $this->validateDriverId(
            $driverId
        );

        $this->validateYearAndMonth(
            $year,
            $month
        );

        $this->validatePdfContent($pdfContent);

        $this->validatePdfSize(
            strlen($pdfContent)
        );

        $typeConfig = $this->documentTypes[$documentType];

        $directory = $this->getDocumentDirectory(
            $documentType,
            $year,
            $month
        );

        /*
         * Create missing directories.
         */
        File::ensureDirectoryExists(
            $directory,
            0750,
            true
        );

        if (!is_dir($directory) || !is_writable($directory)) {
            throw new RuntimeException(
                'WhatsApp PDF storage directory is not writable.'
            );
        }

        /*
         * Generate default filename.
         */
        if ($filename === null || trim($filename) === '') {
            $filename =
                $typeConfig['filename'] .
                '-driver-' .
                $driverId .
                '.pdf';
        }

        $filename = $this->normalizeFileName(
            $filename
        );

        $absolutePath = $directory .
            DIRECTORY_SEPARATOR .
            $filename;

        /*
         * Use a temporary file to avoid incomplete PDFs
         * if writing is interrupted.
         */
        $temporaryPath = tempnam(
            $directory,
            '.whatsapp-'
        );

        if ($temporaryPath === false) {
            throw new RuntimeException(
                'Unable to create temporary WhatsApp PDF file.'
            );
        }

        try {
            $writtenBytes = file_put_contents(
                $temporaryPath,
                $pdfContent,
                LOCK_EX
            );

            if (
                $writtenBytes === false ||
                $writtenBytes !== strlen($pdfContent)
            ) {
                throw new RuntimeException(
                    'Unable to save complete WhatsApp PDF document.'
                );
            }

            /*
             * Keep generated reports private.
             */
            if (!chmod($temporaryPath, 0600)) {
                throw new RuntimeException(
                    'Unable to set private permissions on WhatsApp PDF.'
                );
            }

            /*
             * Atomic replacement of an existing PDF.
             */
            if (!rename($temporaryPath, $absolutePath)) {
                throw new RuntimeException(
                    'Unable to move generated PDF to its final storage location.'
                );
            }
        } finally {
            if (is_file($temporaryPath)) {
                @unlink($temporaryPath);
            }
        }

        /*
         * Revalidate the saved PDF.
         */
        $document = $this->validateDocument(
            $absolutePath,
            $filename
        );

        return [
            'success' => true,
            'document_type' => $documentType,
            'driver_id' => $driverId,
            'year' => $year,
            'month' => $month,
            'filename' => $document['filename'],
            'path' => $document['relative_path'],
            'absolute_path' => $document['absolute_path'],
            'mime_type' => $document['mime_type'],
            'size_bytes' => $document['size_bytes'],
            'size_mb' => $document['size_mb'],
            'stored' => true,
            'message' => 'WhatsApp PDF saved successfully.',
        ];
    }

    /**
     * Automatically save and prepare a generated PDF.
     *
     * Workflow:
     * 1. Create storage directories.
     * 2. Save generated PDF.
     * 3. Validate saved PDF.
     * 4. Upload to Meta only in live mode.
     *
     * @return array
     */
    public function storeAndPrepareDocument(
        string $pdfContent,
        string $documentType,
        int|string $driverId,
        int $year,
        int $month,
        ?string $filename = null
    ): array {
        $storedDocument = $this->storeGeneratedPdf(
            $pdfContent,
            $documentType,
            $driverId,
            $year,
            $month,
            $filename
        );

        $preparedDocument = $this->prepareDocument(
            $storedDocument['absolute_path'],
            $storedDocument['filename']
        );

        return array_merge(
            $storedDocument,
            [
                'id' => $preparedDocument['id'],
                'media_id' => $preparedDocument['media_id'],
                'mode' => $preparedDocument['mode'],
                'uploaded' => $preparedDocument['uploaded'],
            ]
        );
    }

    /**
     * Validate an existing PDF without uploading it.
     *
     * @return array
     */
    public function validateDocument(
        string $documentPath,
        ?string $documentFilename = null
    ): array {
        $absolutePath = $this->resolveDocumentPath(
            $documentPath
        );

        $extension = strtolower(
            pathinfo($absolutePath, PATHINFO_EXTENSION)
        );

        if ($extension !== 'pdf') {
            throw new InvalidArgumentException(
                'Only PDF documents are supported by WhatsApp reports.'
            );
        }

        $fileSize = filesize($absolutePath);

        if (
            $fileSize === false ||
            $fileSize <= 0
        ) {
            throw new InvalidArgumentException(
                'The selected PDF document is empty or invalid.'
            );
        }

        $this->validatePdfSize($fileSize);

        $mimeType = $this->detectMimeType(
            $absolutePath
        );

        if ($mimeType !== 'application/pdf') {
            throw new InvalidArgumentException(
                'The selected document is not a valid PDF file.'
            );
        }

        $this->validatePdfSignature(
            $absolutePath
        );

        $filename = $this->normalizeFileName(
            $documentFilename ?: basename($absolutePath)
        );

        return [
            'absolute_path' => $absolutePath,
            'relative_path' => $this->getRelativeStoragePath(
                $absolutePath
            ),
            'filename' => $filename,
            'mime_type' => 'application/pdf',
            'size_bytes' => $fileSize,
            'size_mb' => round(
                $fileSize / (1024 * 1024),
                2
            ),
            'extension' => 'pdf',
            'valid' => true,
        ];
    }


    /**
     * Get allowed document storage directory.
     */
    public function getDocumentDirectory(
        string $documentType,
        int $year,
        int $month
    ): string {
        $documentType = $this->normalizeDocumentType(
            $documentType
        );

        $this->validateYearAndMonth(
            $year,
            $month
        );

        $folder = $this->documentTypes[$documentType]['folder'];

        return storage_path(
            'app/whatsapp/drivers/' .
            $year . '/' .
            sprintf('%02d', $month) . '/' .
            $folder
        );
    }

    /**
     * Resolve and validate document path.
     *
     * Only files inside configured allowed media roots
     * can be used for WhatsApp document uploads.
     */
    protected function resolveDocumentPath(
        string $documentPath
    ): string {
        $documentPath = trim($documentPath);

        if ($documentPath === '') {
            throw new InvalidArgumentException(
                'WhatsApp PDF document path is required.'
            );
        }

        if (str_contains($documentPath, "\0")) {
            throw new InvalidArgumentException(
                'Invalid WhatsApp document path.'
            );
        }

        $candidates = [];

        $isAbsolutePath = str_starts_with(
            $documentPath,
            '/'
        ) || preg_match(
            '/^[A-Za-z]:[\\\\\/]/',
            $documentPath
        );

        if ($isAbsolutePath) {
            $candidates[] = $documentPath;
        } else {
            $candidates[] = base_path($documentPath);

            $candidates[] = storage_path(
                'app/' . ltrim($documentPath, '/\\')
            );
        }

        $resolvedPath = null;

        foreach (array_unique($candidates) as $candidate) {
            $realPath = realpath($candidate);

            if (
                $realPath !== false &&
                is_file($realPath) &&
                is_readable($realPath)
            ) {
                $resolvedPath = $realPath;
                break;
            }
        }

        if ($resolvedPath === null) {
            throw new InvalidArgumentException(
                'WhatsApp PDF document does not exist or is not readable.'
            );
        }

        if (!$this->isAllowedDocumentPath($resolvedPath)) {
            throw new InvalidArgumentException(
                'The selected PDF is outside the allowed WhatsApp document storage directory.'
            );
        }

        return $resolvedPath;
    }

    /**
     * Check whether file belongs to an allowed storage root.
     *
     * Default allowed root:
     * storage/app
     *
     * Optional configuration:
     * services.whatsapp.allowed_media_roots
     */
    protected function isAllowedDocumentPath(
        string $filePath
    ): bool {
        $allowedRoots = config(
            'services.whatsapp.allowed_media_roots',
            [storage_path('app')]
        );

        if (
            !is_array($allowedRoots) ||
            empty($allowedRoots)
        ) {
            $allowedRoots = [
                storage_path('app'),
            ];
        }

        $filePath = $this->normalizePath(
            $filePath
        );

        foreach ($allowedRoots as $root) {
            if (
                !is_string($root) ||
                trim($root) === ''
            ) {
                continue;
            }

            $realRoot = realpath($root);

            if (
                $realRoot === false ||
                !is_dir($realRoot)
            ) {
                continue;
            }

            $realRoot = $this->normalizePath(
                $realRoot
            );

            $prefix = rtrim($realRoot, '/') . '/';

            if (
                $filePath === $realRoot ||
                str_starts_with($filePath, $prefix)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get path relative to storage/app.
     */
    protected function getRelativeStoragePath(
        string $absolutePath
    ): string {
        $storageRoot = realpath(
            storage_path('app')
        );

        if ($storageRoot === false) {
            throw new RuntimeException(
                'Laravel storage/app directory is not available.'
            );
        }

        $storageRoot = $this->normalizePath(
            $storageRoot
        );

        $absolutePath = $this->normalizePath(
            $absolutePath
        );

        $prefix = rtrim($storageRoot, '/') . '/';

        if (!str_starts_with($absolutePath, $prefix)) {
            /*
             * Preserve absolute path for an explicitly configured
             * allowed root outside storage/app.
             */
            return $absolutePath;
        }

        return substr(
            $absolutePath,
            strlen($prefix)
        );
    }

    /**
     * Normalize a filesystem path.
     */
    protected function normalizePath(
        string $path
    ): string {
        return str_replace(
            '\\',
            '/',
            rtrim($path, '\\/')
        );
    }

    /**
     * Detect document MIME type.
     */
    protected function detectMimeType(
        string $filePath
    ): string {
        if (class_exists(\finfo::class)) {
            $fileInfo = new \finfo(FILEINFO_MIME_TYPE);

            $mimeType = $fileInfo->file($filePath);

            if (is_string($mimeType)) {
                return strtolower($mimeType);
            }
        }

        if (function_exists('mime_content_type')) {
            $mimeType = mime_content_type($filePath);

            if (is_string($mimeType)) {
                return strtolower($mimeType);
            }
        }

        throw new RuntimeException(
            'PHP fileinfo extension is required to validate WhatsApp PDF documents.'
        );
    }

    /**
     * Validate actual PDF signature.
     */
    protected function validatePdfSignature(
        string $filePath
    ): void {
        $handle = fopen($filePath, 'rb');

        if ($handle === false) {
            throw new RuntimeException(
                'Unable to read WhatsApp PDF document.'
            );
        }

        try {
            /*
             * PDF header is expected at the beginning of the file.
             */
            $header = fread($handle, 5);
        } finally {
            fclose($handle);
        }

        if ($header !== '%PDF-') {
            throw new InvalidArgumentException(
                'The selected file does not contain a valid PDF signature.'
            );
        }
    }

    /**
     * Validate generated PDF content.
     */
    protected function validatePdfContent(
        string $pdfContent
    ): void {
        if ($pdfContent === '') {
            throw new InvalidArgumentException(
                'Generated PDF content cannot be empty.'
            );
        }

        if (substr($pdfContent, 0, 5) !== '%PDF-') {
            throw new InvalidArgumentException(
                'Generated content does not contain a valid PDF signature.'
            );
        }
    }


    /**
     * Validate maximum PDF size.
     */
    protected function validatePdfSize(
        int $fileSize
    ): void {
        if ($fileSize <= 0) {
            throw new InvalidArgumentException(
                'PDF document must not be empty.'
            );
        }

        $maxSizeMb = max(
            1,
            (int) config(
                'services.whatsapp.max_media_size_mb',
                100
            )
        );

        $maxSizeBytes = $maxSizeMb * 1024 * 1024;

        if ($fileSize > $maxSizeBytes) {
            throw new InvalidArgumentException(
                "PDF document must not exceed {$maxSizeMb} MB."
            );
        }
    }

    /**
     * Normalize and validate document type.
     */
    protected function normalizeDocumentType(
        string $documentType
    ): string {
        $documentType = strtolower(
            trim(str_replace('-', '_', $documentType))
        );

        if (!isset($this->documentTypes[$documentType])) {
            throw new InvalidArgumentException(
                'Invalid WhatsApp document type. Allowed types: attendance, working_sheet, salary_slip.'
            );
        }

        return $documentType;
    }

    /**
     * Validate driver ID.
     */
    protected function validateDriverId(
        int|string $driverId
    ): string {
        $driverId = trim((string) $driverId);

        if (!preg_match('/^[1-9]\d*$/', $driverId)) {
            throw new InvalidArgumentException(
                'A valid driver ID is required to save the WhatsApp report.'
            );
        }

        return $driverId;
    }

    /**
     * Validate report year and month.
     */
    protected function validateYearAndMonth(
        int $year,
        int $month
    ): void {
        if (
            $year < 1900 ||
            $year > 2200
        ) {
            throw new InvalidArgumentException(
                'Please provide a valid report year.'
            );
        }

        if (
            $month < 1 ||
            $month > 12
        ) {
            throw new InvalidArgumentException(
                'Report month must be between 1 and 12.'
            );
        }
    }

    /**
     * Normalize PDF filename.
     */
    protected function normalizeFileName(
        string $filename
    ): string {
        $filename = basename(trim($filename));

        $filename = preg_replace(
            '/[^A-Za-z0-9._-]/',
            '_',
            $filename
        );

        if (
            !is_string($filename) ||
            $filename === ''
        ) {
            $filename = 'document.pdf';
        }

        if (
            strtolower(
                pathinfo($filename, PATHINFO_EXTENSION)
            ) !== 'pdf'
        ) {
            $filename .= '.pdf';
        }

        if (strlen($filename) > 150) {
            $filename = substr($filename, 0, 145) . '.pdf';
        }

        return $filename;
    }

    /**
     * Get current WhatsApp operating mode.
     *
     * Supported:
     * - local
     * - live
     */
    protected function getMode(): string
    {
        $mode = strtolower(
            trim((string) config(
                'services.whatsapp.mode',
                'local'
            ))
        );

        if (!in_array($mode, ['local', 'live'], true)) {
            throw new InvalidArgumentException(
                'Invalid WhatsApp mode. Allowed modes are local and live.'
            );
        }

        return $mode;
    }
}
