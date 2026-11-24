<?php

namespace App\Services\WhatsApp;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use RuntimeException;

class WhatsAppCloudApiService
{
    /**
     * Meta WhatsApp Graph API base URL.
     */
    protected string $baseUrl;

    /**
     * Graph API version.
     */
    protected string $apiVersion;

    /**
     * API request timeout in seconds.
     */
    protected int $timeout;

    /**
     * API connection timeout in seconds.
     */
    protected int $connectTimeout;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->baseUrl = rtrim(
            trim((string) config(
                'services.whatsapp.base_url',
                'https://graph.facebook.com'
            )),
            '/'
        );

        $this->apiVersion = trim(
            (string) config(
                'services.whatsapp.graph_api_version',
                'v27.0'
            )
        );

        $this->timeout = max(
            5,
            (int) config('services.whatsapp.timeout', 30)
        );

        $this->connectTimeout = max(
            3,
            (int) config('services.whatsapp.connect_timeout', 10)
        );
    }

    /**
     * Send an approved WhatsApp template message.
     *
     * Compatible with WhatsAppMasterService:
     *
     * sendTemplateMessage($recipient, $templatePayload)
     *
     * Supported template payload:
     *
     * [
     *     'name' => 'user_welcome_notification',
     *     'language' => [
     *         'code' => 'en_US',
     *     ],
     *     'components' => [],
     * ]
     *
     * Also supports:
     *
     * [
     *     'template' => [...]
     * ]
     *
     * @return array
     */
    public function sendTemplateMessage(
        string $recipient,
        array $templatePayload
    ): array {
        $this->assertLiveMode();

        $recipient = $this->normalizeRecipient($recipient);

        $template = $this->normalizeTemplatePayload(
            $templatePayload
        );

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipient,
            'type' => 'template',
            'template' => $template,
        ];

        /*
         * Important:
         *
         * Do not automatically retry message POST requests.
         *
         * A network timeout can happen after Meta has accepted
         * the message. Retrying may send duplicate messages.
         */
        return $this->postJson(
            $this->phoneNumberEndpoint('messages'),
            $payload
        );
    }

    /**
     * Upload a PDF document to Meta WhatsApp media storage.
     *
     * The returned media ID can be used in a WhatsApp
     * template document header parameter.
     *
     * @return array
     */
    public function uploadMedia(
        string $filePath,
        ?string $fileName = null
    ): array {
        $this->assertLiveMode();

        $this->getCredentials();

        $realPath = realpath($filePath);

        if (
            $realPath === false ||
            !is_file($realPath) ||
            !is_readable($realPath)
        ) {
            throw new InvalidArgumentException(
                'WhatsApp document file does not exist or is not readable.'
            );
        }

        /*
         * This business module currently supports PDF documents only.
         */
        $extension = strtolower(
            pathinfo($realPath, PATHINFO_EXTENSION)
        );

        if ($extension !== 'pdf') {
            throw new InvalidArgumentException(
                'Only PDF documents are allowed for WhatsApp reports.'
            );
        }

        $fileSize = filesize($realPath);

        if ($fileSize === false || $fileSize <= 0) {
            throw new InvalidArgumentException(
                'WhatsApp PDF document is empty or invalid.'
            );
        }

        /*
         * Configurable maximum upload size.
         */
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
                "PDF document exceeds the configured {$maxSizeMb} MB limit."
            );
        }

        /*
         * Validate MIME type.
         */
        $mimeType = $this->getMimeType($realPath);

        if ($mimeType !== 'application/pdf') {
            throw new InvalidArgumentException(
                'The selected file is not a valid PDF document.'
            );
        }

        /*
         * Validate actual PDF file signature.
         *
         * MIME type alone is not sufficient because a file
         * can be renamed with a .pdf extension.
         */
        $fileHandle = fopen($realPath, 'rb');

        if ($fileHandle === false) {
            throw new RuntimeException(
                'Unable to read WhatsApp PDF document.'
            );
        }

        try {
            $signature = fread($fileHandle, 5);
        } finally {
            fclose($fileHandle);
        }

        if ($signature !== '%PDF-') {
            throw new InvalidArgumentException(
                'The selected file does not contain a valid PDF signature.'
            );
        }

        $fileName = $this->normalizeFileName(
            $fileName ?: basename($realPath)
        );

        $stream = fopen($realPath, 'rb');

        if ($stream === false) {
            throw new RuntimeException(
                'Unable to open WhatsApp PDF document.'
            );
        }

        try {
            /*
             * Meta media upload endpoint:
             *
             * POST /{PHONE_NUMBER_ID}/media
             *
             * Multipart fields:
             * - messaging_product
             * - type
             * - file
             */
            $response = $this->httpClient()
                ->attach(
                    'file',
                    $stream,
                    $fileName,
                    [
                        'Content-Type' => $mimeType,
                    ]
                )
                ->post(
                    $this->phoneNumberEndpoint('media'),
                    [
                        'messaging_product' => 'whatsapp',
                        'type' => $mimeType,
                    ]
                );
        } catch (ConnectionException $exception) {
            throw new RuntimeException(
                'Unable to connect to Meta WhatsApp media service.',
                0,
                $exception
            );
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        $result = $this->parseResponse(
            $response,
            'WhatsApp PDF upload'
        );

        if (
            !isset($result['id']) ||
            !is_string($result['id']) ||
            trim($result['id']) === ''
        ) {
            throw new RuntimeException(
                'Meta did not return a valid media ID after PDF upload.'
            );
        }

        return $result;
    }

    /**
     * Retrieve uploaded media information from Meta.
     *
     * @return array
     */
    public function getMedia(string $mediaId): array
    {
        $this->assertLiveMode();

        $this->getCredentials();

        $mediaId = $this->validateMediaId($mediaId);

        try {
            $response = $this->httpClient()->get(
                $this->apiUrl(rawurlencode($mediaId))
            );
        } catch (ConnectionException $exception) {
            throw new RuntimeException(
                'Unable to connect to Meta WhatsApp media service.',
                0,
                $exception
            );
        }

        return $this->parseResponse(
            $response,
            'WhatsApp media retrieval'
        );
    }

    /**
     * Delete an uploaded media object from Meta.
     *
     * @return array
     */
    public function deleteMedia(string $mediaId): array
    {
        $this->assertLiveMode();

        $this->getCredentials();

        $mediaId = $this->validateMediaId($mediaId);

        try {
            $response = $this->httpClient()->delete(
                $this->apiUrl(rawurlencode($mediaId))
            );
        } catch (ConnectionException $exception) {
            throw new RuntimeException(
                'Unable to connect to Meta WhatsApp media service.',
                0,
                $exception
            );
        }

        return $this->parseResponse(
            $response,
            'WhatsApp media deletion'
        );
    }

    /**
     * Create a configured Laravel HTTP client.
     */
    protected function httpClient(): PendingRequest
    {
        $credentials = $this->getCredentials();

        return Http::acceptJson()
            ->withToken($credentials['access_token'])
            ->timeout($this->timeout)
            ->connectTimeout($this->connectTimeout);
    }

    /**
     * Send a JSON POST request to Meta.
     */
    protected function postJson(
        string $url,
        array $payload
    ): array {
        try {
            $response = $this->httpClient()->post(
                $url,
                $payload
            );
        } catch (ConnectionException $exception) {
            throw new RuntimeException(
                'Unable to connect to Meta WhatsApp Cloud API.',
                0,
                $exception
            );
        }

        return $this->parseResponse(
            $response,
            'WhatsApp template message'
        );
    }


    /**
     * Validate and decode Meta API response.
     *
     * @return array
     */
    protected function parseResponse(
        Response $response,
        string $operation
    ): array {
        $data = $response->json();

        if (!$response->successful()) {
            $error = is_array($data)
                ? ($data['error'] ?? [])
                : [];

            $message = is_array($error)
                ? ($error['message'] ?? null)
                : null;

            $errorCode = is_array($error)
                ? ($error['code'] ?? null)
                : null;

            $errorSubcode = is_array($error)
                ? ($error['error_subcode'] ?? null)
                : null;

            $details = is_string($message) && trim($message) !== ''
                ? trim($message)
                : 'Unknown Meta API error.';

            $errorReference = [];

            if ($errorCode !== null) {
                $errorReference[] = 'Code: ' . $errorCode;
            }

            if ($errorSubcode !== null) {
                $errorReference[] = 'Subcode: ' . $errorSubcode;
            }

            if (!empty($errorReference)) {
                $details .= ' (' . implode(', ', $errorReference) . ')';
            }

            throw new RuntimeException(
                $operation .
                ' failed. HTTP ' .
                $response->status() .
                ': ' .
                $details
            );
        }

        if (!is_array($data)) {
            throw new RuntimeException(
                $operation .
                ' returned an invalid response from Meta.'
            );
        }

        return $data;
    }

    /**
     * Get and validate Meta API credentials.
     *
     * @return array
     */
    protected function getCredentials(): array
    {
        $accessToken = trim(
            (string) config(
                'services.whatsapp.access_token',
                ''
            )
        );

        $phoneNumberId = trim(
            (string) config(
                'services.whatsapp.phone_number_id',
                ''
            )
        );

        if ($accessToken === '') {
            throw new RuntimeException(
                'WhatsApp access token is not configured.'
            );
        }

        if (
            $phoneNumberId === '' ||
            !ctype_digit($phoneNumberId)
        ) {
            throw new RuntimeException(
                'A valid WhatsApp Phone Number ID is not configured.'
            );
        }

        return [
            'access_token' => $accessToken,
            'phone_number_id' => $phoneNumberId,
        ];
    }

    /**
     * Ensure the application is running in live mode.
     *
     * This prevents accidental real API calls during local testing.
     */
    protected function assertLiveMode(): void
    {
        $mode = strtolower(
            trim((string) config(
                'services.whatsapp.mode',
                'local'
            ))
        );

        if ($mode !== 'live') {
            throw new RuntimeException(
                'WhatsApp Cloud API is disabled. Set WHATSAPP_MODE=live after Meta approval and configuration.'
            );
        }

        if (!preg_match('/^v\d+\.\d+$/', $this->apiVersion)) {
            throw new RuntimeException(
                'Invalid WhatsApp Graph API version configuration.'
            );
        }

        if (
            !filter_var($this->baseUrl, FILTER_VALIDATE_URL) ||
            strtolower((string) parse_url(
                $this->baseUrl,
                PHP_URL_SCHEME
            )) !== 'https'
        ) {
            throw new RuntimeException(
                'Invalid WhatsApp Graph API base URL. HTTPS is required.'
            );
        }

        $host = strtolower((string) parse_url(
            $this->baseUrl,
            PHP_URL_HOST
        ));

        if ($host === '') {
            throw new RuntimeException(
                'Invalid WhatsApp Graph API host configuration.'
            );
        }
    }

    /**
     * Build phone-number-specific endpoint.
     */
    protected function phoneNumberEndpoint(
        string $resource
    ): string {
        $credentials = $this->getCredentials();

        return $this->apiUrl(
            $credentials['phone_number_id'] .
            '/' .
            ltrim($resource, '/')
        );
    }

    /**
     * Build a versioned Graph API URL.
     */
    protected function apiUrl(string $path): string
    {
        return $this->baseUrl .
            '/' .
            $this->apiVersion .
            '/' .
            ltrim($path, '/');
    }

    /**
     * Normalize recipient number to international digits.
     *
     * Example:
     * +919876543210 => 919876543210
     */
    protected function normalizeRecipient(
        string $recipient
    ): string {
        $recipient = trim($recipient);

        $number = preg_replace(
            '/\D+/',
            '',
            $recipient
        );

        if (
            !is_string($number) ||
            !preg_match('/^[1-9]\d{7,14}$/', $number)
        ) {
            throw new InvalidArgumentException(
                'Invalid WhatsApp recipient number. Use international format with country code, e.g. +919876543210.'
            );
        }

        return $number;
    }

    /**
     * Normalize and validate template payload.
     *
     * Supports:
     *
     * ['name' => ..., 'language' => ...]
     *
     * and:
     *
     * ['template' => [...]]
     */
    protected function normalizeTemplatePayload(
        array $templatePayload
    ): array {
        $template = $templatePayload['template']
            ?? $templatePayload;

        if (!is_array($template)) {
            throw new InvalidArgumentException(
                'Invalid WhatsApp template payload.'
            );
        }

        $name = trim(
            (string) ($template['name'] ?? '')
        );

        if (
            $name === '' ||
            !preg_match('/^[a-z0-9_]+$/', $name)
        ) {
            throw new InvalidArgumentException(
                'A valid WhatsApp template name is required.'
            );
        }

        $language = $template['language'] ?? [];

        /*
         * Allow language as:
         *
         * 'en_US'
         *
         * or:
         *
         * ['code' => 'en_US']
         */
        if (is_string($language)) {
            $language = [
                'code' => $language,
            ];
        }

        if (!is_array($language)) {
            throw new InvalidArgumentException(
                'WhatsApp template language configuration is invalid.'
            );
        }

        $languageCode = trim(
            (string) ($language['code'] ?? '')
        );

        if (
            $languageCode === '' ||
            !preg_match(
                '/^[a-zA-Z]{2,3}(?:[_-][a-zA-Z0-9]{2,8})*$/',
                $languageCode
            )
        ) {
            throw new InvalidArgumentException(
                'A valid WhatsApp template language code is required.'
            );
        }

        $normalizedTemplate = [
            'name' => $name,
            'language' => [
                'code' => $languageCode,
            ],
        ];

        if (array_key_exists('components', $template)) {
            if (!is_array($template['components'])) {
                throw new InvalidArgumentException(
                    'WhatsApp template components must be an array.'
                );
            }

            foreach ($template['components'] as $component) {
                if (!is_array($component)) {
                    throw new InvalidArgumentException(
                        'Each WhatsApp template component must be an array.'
                    );
                }
            }

            $normalizedTemplate['components'] = array_values(
                $template['components']
            );
        }

        return $normalizedTemplate;
    }

    /**
     * Detect file MIME type.
     */
    protected function getMimeType(string $filePath): string
    {
        if (class_exists(\finfo::class)) {
            $fileInfo = new \finfo(FILEINFO_MIME_TYPE);

            $mimeType = $fileInfo->file($filePath);

            return is_string($mimeType)
                ? strtolower($mimeType)
                : '';
        }

        if (function_exists('mime_content_type')) {
            $mimeType = mime_content_type($filePath);

            return is_string($mimeType)
                ? strtolower($mimeType)
                : '';
        }

        throw new RuntimeException(
            'PHP file information extension is required to validate PDF documents.'
        );
    }

    /**
     * Sanitize document filename.
     */
    protected function normalizeFileName(string $fileName): string
    {
        $fileName = basename(trim($fileName));

        $fileName = preg_replace(
            '/[^A-Za-z0-9._-]/',
            '_',
            $fileName
        );

        if (!is_string($fileName) || $fileName === '') {
            $fileName = 'document.pdf';
        }

        if (
            strtolower(pathinfo(
                $fileName,
                PATHINFO_EXTENSION
            )) !== 'pdf'
        ) {
            $fileName .= '.pdf';
        }

        return $fileName;
    }

    /**
     * Validate a Meta media ID.
     */
    protected function validateMediaId(string $mediaId): string
    {
        $mediaId = trim($mediaId);

        if (
            $mediaId === '' ||
            !preg_match('/^[A-Za-z0-9_-]+$/', $mediaId)
        ) {
            throw new InvalidArgumentException(
                'Invalid WhatsApp media ID.'
            );
        }

        return $mediaId;
    }
}
