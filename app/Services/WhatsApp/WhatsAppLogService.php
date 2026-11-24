<?php

namespace App\Services\WhatsApp;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class WhatsAppLogService
{
    /**
     * WhatsApp message log table.
     */
    protected string $table = 'whatsapp_message_logs';

    /**
     * Supported internal message statuses.
     */
    protected array $statuses = [
        'pending',
        'local_test',
        'chat_link_generated',
        'sent',
        'delivered',
        'read',
        'failed',
    ];

    /**
     * Supported WhatsApp channels.
     */
    protected array $channels = [
        'personal_chat',
        'cloud_api',
    ];

    /**
     * JSON columns.
     */
    protected array $jsonColumns = [
        'variables',
        'message_payload',
        'provider_response',
    ];

    /**
     * Sensitive variable names that must never be stored.
     */
    protected array $sensitiveKeys = [
        'otp',
        'password',
        'current_password',
        'new_password',
        'activation_url',
        'verification_url',
        'access_token',
        'refresh_token',
        'token',
        'secret',
        'authorization',
        'api_key',
        'private_key',
        'client_secret',
        'app_secret',
        'signature',
        'cookie',
        'session',
    ];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $configuredTable = config(
            'services.whatsapp.log_table',
            'whatsapp_message_logs'
        );

        if (
            is_string($configuredTable) &&
            preg_match('/^[A-Za-z0-9_]+$/', $configuredTable)
        ) {
            $this->table = $configuredTable;
        }
    }

    /**
     * Create a WhatsApp message log.
     *
     * personal_chat:
     * - Local mode only.
     * - Uses chat_link_generated status.
     * - Does not represent a sent or delivered message.
     *
     * cloud_api:
     * - Local mode supports local_test.
     * - Live mode supports pending, sent, delivered,
     *   read and failed.
     */
    public function create(array $data): array
    {
        $this->assertLogTableExists();
        $this->assertRequiredColumnsExist();

        $recipient = $this->normalizeRecipient(
            (string) ($data['recipient'] ?? '')
        );

        $templateKey = trim(
            (string) ($data['template_key'] ?? '')
        );

        if (
            $templateKey === '' ||
            !preg_match('/^[a-z0-9_]+$/', $templateKey)
        ) {
            throw new InvalidArgumentException(
                'A valid WhatsApp template key is required for logging.'
            );
        }

        $mode = strtolower(
            trim((string) ($data['mode'] ?? 'local'))
        );

        if (!in_array($mode, ['local', 'live'], true)) {
            throw new InvalidArgumentException(
                'Invalid WhatsApp log mode.'
            );
        }

        $status = $this->normalizeStatus(
            (string) ($data['status'] ?? 'pending')
        );

        /*
         * Local test records belong to cloud_api.
         * Other local records default to personal_chat.
         */
        $defaultChannel = $mode === 'live'
            ? 'cloud_api'
            : (
                $status === 'local_test'
                    ? 'cloud_api'
                    : 'personal_chat'
            );

        $channel = strtolower(
            trim((string) ($data['channel'] ?? $defaultChannel))
        );

        if (!in_array($channel, $this->channels, true)) {
            throw new InvalidArgumentException(
                'Invalid WhatsApp channel.'
            );
        }

        $this->validateModeChannelStatus(
            $mode,
            $channel,
            $status
        );

        $reference = trim(
            (string) ($data['reference'] ?? '')
        );

        if ($reference === '') {
            $reference = (string) Str::uuid();
        }

        if (
            strlen($reference) > 36 ||
            !preg_match('/^[A-Za-z0-9-]+$/', $reference)
        ) {
            throw new InvalidArgumentException(
                'Invalid WhatsApp message reference.'
            );
        }

        $providerMessageId = $this->normalizeProviderMessageId(
            $data['provider_message_id'] ?? null
        );

        $documentPath = $this->sanitizeDocumentPath(
            $data['document_path'] ?? null
        );

        $documentFilename = $this->resolveDocumentFilename(
            $data['document_filename'] ?? null,
            $data['document_path'] ?? null
        );

        $now = now();

        $record = [
            'reference' => $reference,

            'driver_id' => $this->normalizeNullableId(
                $data['driver_id'] ?? null
            ),

            'created_by' => $this->normalizeNullableId(
                $data['created_by'] ?? null
            ),

            'recipient' => $recipient,

            'template_key' => $templateKey,

            'mode' => $mode,

            'channel' => $channel,

            'status' => $status,

            'variables' => $this->encodeJson(
                $this->sanitizeVariables(
                    $data['variables'] ?? []
                )
            ),

            'document_path' => $documentPath,

            'document_filename' => $documentFilename,

            'message_payload' => $this->encodeNullableJson(
                $this->sanitizeMessagePayload(
                    $data['message_payload'] ?? null
                )
            ),

            'provider_response' => $this->encodeNullableJson(
                $this->sanitizeProviderResponse(
                    $data['provider_response'] ?? null
                )
            ),

            'provider_message_id' => $providerMessageId,

            'error_message' => $this->sanitizeErrorMessage(
                $data['error_message'] ?? null
            ),

            'sent_at' => in_array(
                $status,
                ['sent', 'delivered', 'read'],
                true
            ) ? $now : null,

            'delivered_at' => in_array(
                $status,
                ['delivered', 'read'],
                true
            ) ? $now : null,

            'read_at' => $status === 'read'
                ? $now
                : null,

            'failed_at' => $status === 'failed'
                ? $now
                : null,

            'status_updated_at' => $now,

            'created_at' => $now,

            'updated_at' => $now,
        ];

        $id = DB::table($this->table)->insertGetId($record);

        $log = $this->findById((int) $id);

        if ($log === null) {
            throw new RuntimeException(
                'WhatsApp message log was created but could not be retrieved.'
            );
        }

        return $log;
    }

    /**
     * Validate allowed mode, channel and status combinations.
     */
    protected function validateModeChannelStatus(
        string $mode,
        string $channel,
        string $status
    ): void {
        if (
            $mode === 'live' &&
            $channel !== 'cloud_api'
        ) {
            throw new InvalidArgumentException(
                'Live WhatsApp mode only supports the Cloud API channel.'
            );
        }

        if (
            $channel === 'personal_chat' &&
            $mode !== 'local'
        ) {
            throw new InvalidArgumentException(
                'Personal WhatsApp chat is only available in local mode.'
            );
        }

        if (
            $channel === 'personal_chat' &&
            $status !== 'chat_link_generated'
        ) {
            throw new InvalidArgumentException(
                'Personal WhatsApp chat logs must use chat_link_generated status.'
            );
        }

        if (
            $mode === 'local' &&
            $channel === 'cloud_api' &&
            $status !== 'local_test'
        ) {
            throw new InvalidArgumentException(
                'Local Cloud API logs must use local_test status.'
            );
        }

        if (
            $channel === 'cloud_api' &&
            $status === 'chat_link_generated'
        ) {
            throw new InvalidArgumentException(
                'chat_link_generated status is only valid for personal_chat.'
            );
        }
    }

    /**
     * Find message log by primary ID.
     */
    public function findById(int $id): ?array
    {
        $this->assertLogTableExists();

        $record = DB::table($this->table)
            ->where('id', $id)
            ->first();

        return $record
            ? $this->decodeRecord((array) $record)
            : null;
    }

    /**
     * Find message log using internal reference.
     */
    public function findByReference(string $reference): ?array
    {
        $this->assertLogTableExists();

        $reference = trim($reference);

        if ($reference === '') {
            return null;
        }

        $record = DB::table($this->table)
            ->where('reference', $reference)
            ->first();

        return $record
            ? $this->decodeRecord((array) $record)
            : null;
    }

    /**
     * Find message log using Meta WhatsApp message ID.
     */
    public function findByProviderMessageId(
        string $providerMessageId
    ): ?array {
        $this->assertLogTableExists();

        $providerMessageId = trim($providerMessageId);

        if ($providerMessageId === '') {
            return null;
        }

        $record = DB::table($this->table)
            ->where('provider_message_id', $providerMessageId)
            ->first();

        return $record
            ? $this->decodeRecord((array) $record)
            : null;
    }

    /**
     * Update status using Meta message ID.
     *
     * Used by the WhatsApp webhook controller.
     */
    public function updateStatusByProviderMessageId(
        string $providerMessageId,
        string $status,
        array $metadata = []
    ): bool {
        $this->assertLogTableExists();

        $record = $this->findByProviderMessageId(
            $providerMessageId
        );

        if ($record === null) {
            return false;
        }

        return $this->updateStatusRecord(
            $record,
            $status,
            $metadata
        );
    }

    /**
     * Update status using internal reference.
     *
     * Used by WhatsAppMasterService when the API request
     * succeeds or fails before a provider ID is available.
     */
    public function updateStatusByReference(
        string $reference,
        string $status,
        array $metadata = []
    ): bool {
        $this->assertLogTableExists();

        $record = $this->findByReference($reference);

        if ($record === null) {
            return false;
        }

        return $this->updateStatusRecord(
            $record,
            $status,
            $metadata
        );
    }

    /**
     * Update status using primary log ID.
     */
    public function updateStatusById(
        int $id,
        string $status,
        array $metadata = []
    ): bool {
        $this->assertLogTableExists();

        $record = $this->findById($id);

        if ($record === null) {
            return false;
        }

        return $this->updateStatusRecord(
            $record,
            $status,
            $metadata
        );
    }

    /**
     * Process one Meta status webhook item.
     */
    public function updateFromWebhook(array $statusData): bool
    {
        $providerMessageId = $statusData['id'] ?? null;
        $status = $statusData['status'] ?? null;

        if (
            !is_string($providerMessageId) ||
            !is_string($status)
        ) {
            return false;
        }

        return $this->updateStatusByProviderMessageId(
            $providerMessageId,
            $status,
            [
                'timestamp' => $statusData['timestamp'] ?? null,
                'errors' => $statusData['errors'] ?? [],
            ]
        );
    }

    /**
     * Mark message as sent.
     */
    public function markSent(
        string $providerMessageId,
        array $metadata = []
    ): bool {
        return $this->updateStatusByProviderMessageId(
            $providerMessageId,
            'sent',
            $metadata
        );
    }

    /**
     * Mark message as delivered.
     */
    public function markDelivered(
        string $providerMessageId,
        array $metadata = []
    ): bool {
        return $this->updateStatusByProviderMessageId(
            $providerMessageId,
            'delivered',
            $metadata
        );
    }

    /**
     * Mark message as read.
     */
    public function markRead(
        string $providerMessageId,
        array $metadata = []
    ): bool {
        return $this->updateStatusByProviderMessageId(
            $providerMessageId,
            'read',
            $metadata
        );
    }

    /**
     * Mark message as failed.
     */
    public function markFailed(
        string $providerMessageId,
        string $errorMessage,
        array $metadata = []
    ): bool {
        $metadata['error_message'] = $errorMessage;

        return $this->updateStatusByProviderMessageId(
            $providerMessageId,
            'failed',
            $metadata
        );
    }


    /**
     * Get WhatsApp message history with filters.
     */
    public function getHistory(
        array $filters = [],
        int $perPage = 25
    ): LengthAwarePaginator {
        $this->assertLogTableExists();

        $perPage = max(1, min($perPage, 100));

        $query = DB::table($this->table)
            ->orderByDesc('id');

        $this->applyHistoryFilters($query, $filters);

        $paginator = $query->paginate($perPage);

        $paginator->getCollection()->transform(
            function ($record) {
                return $this->decodeRecord((array) $record);
            }
        );

        return $paginator;
    }

    /**
     * Get message statistics for dashboard.
     */
    public function getStatistics(array $filters = []): array
    {
        $this->assertLogTableExists();

        $query = DB::table($this->table);

        $this->applyHistoryFilters(
            $query,
            $filters,
            false
        );

        $statusCounts = (clone $query)
            ->select('status')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        $statistics = [];

        foreach ($this->statuses as $status) {
            $statistics[$status] = (int) (
                $statusCounts[$status] ?? 0
            );
        }

        $statistics['total'] = array_sum($statistics);

        $statistics['successful'] =
            $statistics['sent'] +
            $statistics['delivered'] +
            $statistics['read'];

        $statistics['unsuccessful'] = $statistics['failed'];

        $statistics['chat_links_generated'] =
            $statistics['chat_link_generated'];

        return $statistics;
    }

    /**
     * Get status summary for a specific driver.
     */
    public function getDriverStatistics(
        int $driverId,
        array $filters = []
    ): array {
        if ($driverId <= 0) {
            throw new InvalidArgumentException(
                'A valid driver ID is required.'
            );
        }

        $filters['driver_id'] = $driverId;

        return $this->getStatistics($filters);
    }

    /**
     * Update an existing log status safely.
     *
     * A transaction and row lock prevent concurrent webhook
     * requests from overwriting each other's status transitions.
     */
    protected function updateStatusRecord(
        array $record,
        string $status,
        array $metadata = []
    ): bool {
        $status = $this->normalizeStatus($status);

        $recordId = (int) ($record['id'] ?? 0);

        if ($recordId <= 0) {
            return false;
        }

        return DB::transaction(function () use (
            $recordId,
            $status,
            $metadata
        ) {
            $lockedRecord = DB::table($this->table)
                ->where('id', $recordId)
                ->lockForUpdate()
                ->first();

            if (!$lockedRecord) {
                return false;
            }

            $record = (array) $lockedRecord;

            $currentStatus = (string) ($record['status'] ?? '');
            $channel = (string) ($record['channel'] ?? '');
            $mode = (string) ($record['mode'] ?? '');

            /*
             * Personal chat records are terminal.
             * A generated wa.me link is not proof of delivery.
             */
            if (
                $channel === 'personal_chat' ||
                $currentStatus === 'chat_link_generated'
            ) {
                return false;
            }

            /*
             * Local test records cannot receive live delivery updates.
             */
            if ($currentStatus === 'local_test') {
                return false;
            }

            /*
             * Only live Cloud API records support delivery tracking.
             */
            if (
                $channel !== 'cloud_api' ||
                $mode !== 'live'
            ) {
                return false;
            }

            /*
             * These statuses cannot be applied through a delivery update.
             */
            if (in_array(
                $status,
                [
                    'pending',
                    'local_test',
                    'chat_link_generated',
                ],
                true
            )) {
                return false;
            }

            /*
             * Delivered/read must not regress to failed.
             */
            if (
                $status === 'failed' &&
                in_array(
                    $currentStatus,
                    ['delivered', 'read'],
                    true
                )
            ) {
                return false;
            }

            /*
             * A failed message is terminal.
             */
            if (
                $currentStatus === 'failed' &&
                $status !== 'failed'
            ) {
                return false;
            }

            $statusRank = [
                'pending' => 0,
                'sent' => 1,
                'delivered' => 2,
                'read' => 3,
                'failed' => 4,
                'local_test' => 5,
                'chat_link_generated' => 6,
            ];

            if (
                isset($statusRank[$currentStatus]) &&
                isset($statusRank[$status]) &&
                $statusRank[$status] < $statusRank[$currentStatus]
            ) {
                return false;
            }

            $now = $this->resolveStatusTimestamp(
                $metadata['timestamp'] ?? null
            );

            $update = [
                'status' => $status,
                'status_updated_at' => $now,
                'updated_at' => now(),
            ];

            /*
             * Provider message ID.
             */
            if (array_key_exists('provider_message_id', $metadata)) {
                $update['provider_message_id'] =
                    $this->normalizeProviderMessageId(
                        $metadata['provider_message_id']
                    );
            }

            /*
             * Sanitized provider response.
             */
            if (array_key_exists('provider_response', $metadata)) {
                $update['provider_response'] =
                    $this->encodeNullableJson(
                        $this->sanitizeProviderResponse(
                            $metadata['provider_response']
                        )
                    );
            }

            /*
             * Sanitized message payload.
             */
            if (array_key_exists('message_payload', $metadata)) {
                $update['message_payload'] =
                    $this->encodeNullableJson(
                        $this->sanitizeMessagePayload(
                            $metadata['message_payload']
                        )
                    );
            }

            /*
             * Document metadata.
             */
            if (array_key_exists('document_path', $metadata)) {
                $update['document_path'] =
                    $this->sanitizeDocumentPath(
                        $metadata['document_path']
                    );
            }

            if (array_key_exists('document_filename', $metadata)) {
                $update['document_filename'] =
                    $this->resolveDocumentFilename(
                        $metadata['document_filename'],
                        $metadata['document_path'] ?? null
                    );
            }

            /*
             * Preserve the first known timestamp for each stage.
             */
            if ($status === 'sent') {
                $update['sent_at'] = $record['sent_at'] ?? $now;
            }

            if ($status === 'delivered') {
                $update['sent_at'] = $record['sent_at'] ?? $now;
                $update['delivered_at'] =
                    $record['delivered_at'] ?? $now;
            }

            if ($status === 'read') {
                $update['sent_at'] = $record['sent_at'] ?? $now;

                $update['delivered_at'] =
                    $record['delivered_at'] ?? $now;

                $update['read_at'] = $record['read_at'] ?? $now;
            }

            if ($status === 'failed') {
                $update['failed_at'] = $record['failed_at'] ?? $now;

                $update['error_message'] =
                    $this->resolveWebhookError($metadata)
                    ?? ($record['error_message'] ?? null);
            } elseif (array_key_exists('error_message', $metadata)) {
                $update['error_message'] =
                    $this->sanitizeErrorMessage(
                        $metadata['error_message']
                    );
            }

            return DB::table($this->table)
                ->where('id', $recordId)
                ->update($update) > 0;
        });
    }

    /**
     * Apply history filters to a query.
     */
    protected function applyHistoryFilters(
        $query,
        array $filters,
        bool $includeStatus = true
    ): void {
        if (!empty($filters['recipient'])) {
            $recipient = $this->digitsOnly(
                (string) $filters['recipient']
            );

            if ($recipient !== '') {
                $query->where(
                    'recipient',
                    'like',
                    '%' . $recipient . '%'
                );
            }
        }

        foreach (['driver_id', 'created_by'] as $column) {
            if (
                isset($filters[$column]) &&
                ctype_digit((string) $filters[$column])
            ) {
                $query->where(
                    $column,
                    (int) $filters[$column]
                );
            }
        }

        if (!empty($filters['template_key'])) {
            $templateKey = trim(
                (string) $filters['template_key']
            );

            if ($templateKey !== '') {
                $query->where('template_key', $templateKey);
            }
        }

        if (!empty($filters['mode'])) {
            $mode = strtolower(
                trim((string) $filters['mode'])
            );

            if (in_array($mode, ['local', 'live'], true)) {
                $query->where('mode', $mode);
            }
        }

        if (!empty($filters['channel'])) {
            $channel = strtolower(
                trim((string) $filters['channel'])
            );

            if (in_array($channel, $this->channels, true)) {
                $query->where('channel', $channel);
            }
        }

        if (
            $includeStatus &&
            !empty($filters['status'])
        ) {
            $status = strtolower(
                trim((string) $filters['status'])
            );

            if (in_array($status, $this->statuses, true)) {
                $query->where('status', $status);
            }
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate(
                'created_at',
                '>=',
                $filters['date_from']
            );
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate(
                'created_at',
                '<=',
                $filters['date_to']
            );
        }
    }

    /**
     * Sanitize template variables before database storage.
     */
    protected function sanitizeVariables(mixed $variables): array
    {
        if (!is_array($variables)) {
            return [];
        }

        $sanitized = [];

        foreach ($variables as $key => $value) {
            $keyName = strtolower((string) $key);

            if ($this->isSensitiveKey($keyName)) {
                $sanitized[$key] = '[REDACTED]';
                continue;
            }

            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizeVariables($value);
                continue;
            }

            if (is_scalar($value) || $value === null) {
                $sanitized[$key] = $value;
                continue;
            }

            $sanitized[$key] = '[UNSUPPORTED VALUE]';
        }

        return $sanitized;
    }

    /**
     * Sanitize template payload.
     *
     * Dynamic text values and media IDs are never persisted.
     */
    protected function sanitizeMessagePayload(
        mixed $payload
    ): ?array {
        if (!is_array($payload)) {
            return null;
        }

        $template = $payload['template'] ?? $payload;

        if (!is_array($template)) {
            return null;
        }

        $safe = [
            'name' => $this->safeScalar($template['name'] ?? null, 100),
            'language' => $this->safeScalar(
                $template['language'] ?? null,
                30
            ),
            'components' => [],
        ];

        $components = $template['components'] ?? [];

        if (!is_array($components)) {
            return $safe;
        }

        foreach ($components as $component) {
            if (!is_array($component)) {
                continue;
            }

            $safeComponent = [
                'type' => $this->safeScalar(
                    $component['type'] ?? null,
                    30
                ),
            ];

            if (isset($component['sub_type'])) {
                $safeComponent['sub_type'] =
                    $this->safeScalar($component['sub_type'], 30);
            }

            if (isset($component['index'])) {
                $safeComponent['index'] =
                    is_numeric($component['index'])
                        ? (int) $component['index']
                        : null;
            }

            $parameters = $component['parameters'] ?? [];

            if (is_array($parameters)) {
                $safeComponent['parameters'] = [];

                foreach ($parameters as $parameter) {
                    if (!is_array($parameter)) {
                        continue;
                    }

                    $type = $parameter['type'] ?? null;

                    $safeParameter = [
                        'type' => $this->safeScalar($type, 30),
                    ];

                    if ($type === 'document') {
                        $document = $parameter['document'] ?? [];

                        $safeParameter['document'] = [
                            'filename' => is_array($document)
                                ? $this->resolveDocumentFilename(
                                    $document['filename'] ?? null,
                                    null
                                )
                                : null,
                            'id' => '[NOT STORED]',
                        ];
                    } elseif ($type === 'text') {
                        $safeParameter['text'] = '[REDACTED]';
                    } elseif ($type === 'image' || $type === 'video') {
                        $safeParameter[$type] = [
                            'id' => '[NOT STORED]',
                        ];
                    } elseif ($type === 'currency') {
                        $safeParameter['currency'] = '[REDACTED]';
                    } elseif ($type === 'date_time') {
                        $safeParameter['date_time'] = '[REDACTED]';
                    }

                    $safeComponent['parameters'][] = $safeParameter;
                }
            }

            $safe['components'][] = $safeComponent;
        }

        return $safe;
    }

    /**
     * Keep only useful non-secret fields from Meta response.
     */
    protected function sanitizeProviderResponse(
        mixed $response
    ): ?array {
        if (!is_array($response)) {
            return null;
        }

        $safe = [];

        if (isset($response['messaging_product'])) {
            $safe['messaging_product'] =
                $this->safeScalar(
                    $response['messaging_product'],
                    50
                );
        }

        if (
            isset($response['contacts']) &&
            is_array($response['contacts'])
        ) {
            $safe['contacts'] = [];

            foreach ($response['contacts'] as $contact) {
                if (!is_array($contact)) {
                    continue;
                }

                $safe['contacts'][] = [
                    'input' => isset($contact['input'])
                        ? $this->digitsOnly(
                            (string) $contact['input']
                        )
                        : null,

                    'wa_id' => isset($contact['wa_id'])
                        ? $this->digitsOnly(
                            (string) $contact['wa_id']
                        )
                        : null,
                ];
            }
        }

        if (
            isset($response['messages']) &&
            is_array($response['messages'])
        ) {
            $safe['messages'] = [];

            foreach ($response['messages'] as $message) {
                if (!is_array($message)) {
                    continue;
                }

                $safe['messages'][] = [
                    'id' => $this->safeScalar(
                        $message['id'] ?? null,
                        191
                    ),
                    'message_status' => $this->safeScalar(
                        $message['message_status'] ?? null,
                        50
                    ),
                ];
            }
        }

        return $safe ?: null;
    }


    /**
     * Check whether a variable/key contains sensitive information.
     */
    protected function isSensitiveKey(string $key): bool
    {
        $normalizedKey = strtolower(
            preg_replace('/[^a-z0-9_]/i', '', $key) ?? ''
        );

        foreach ($this->sensitiveKeys as $sensitiveKey) {
            if (
                $normalizedKey === $sensitiveKey ||
                str_contains($normalizedKey, $sensitiveKey)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return a safe scalar string with a maximum length.
     */
    protected function safeScalar(
        mixed $value,
        int $maxLength = 255
    ): ?string {
        if (!is_string($value) && !is_numeric($value)) {
            return null;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        return mb_substr($value, 0, $maxLength);
    }

    /**
     * Sanitize document path.
     *
     * Only storage/app-relative paths are accepted.
     * Arbitrary absolute paths and traversal paths are rejected.
     */
    protected function sanitizeDocumentPath(
        mixed $path
    ): ?string {
        if (!is_string($path) || trim($path) === '') {
            return null;
        }

        $path = trim($path);

        if (
            str_contains($path, "\0") ||
            preg_match('/^[a-z][a-z0-9+.-]*:\/\//i', $path)
        ) {
            return null;
        }

        $path = str_replace('\\', '/', $path);

        /*
         * Absolute filesystem paths are accepted only when their
         * resolved location is inside storage/app.
         */
        if (
            str_starts_with($path, '/') ||
            preg_match('/^[A-Za-z]:\//', $path)
        ) {
            $storageRoot = realpath(storage_path('app'));
            $realPath = realpath($path);

            if (
                $storageRoot === false ||
                $realPath === false
            ) {
                return null;
            }

            $storageRoot = rtrim(
                str_replace('\\', '/', $storageRoot),
                '/'
            );

            $realPath = str_replace('\\', '/', $realPath);

            $prefix = $storageRoot . '/';

            if (!str_starts_with($realPath, $prefix)) {
                return null;
            }

            $path = substr(
                $realPath,
                strlen($prefix)
            );
        }

        $segments = explode('/', $path);
        $safeSegments = [];

        foreach ($segments as $segment) {
            if (
                $segment === '' ||
                $segment === '.'
            ) {
                continue;
            }

            if (
                $segment === '..' ||
                preg_match('/[\x00-\x1F\x7F]/', $segment)
            ) {
                return null;
            }

            $safeSegments[] = $segment;
        }

        if (empty($safeSegments)) {
            return null;
        }

        $safePath = implode('/', $safeSegments);

        if (strlen($safePath) > 500) {
            return null;
        }

        return $safePath;
    }

    /**
     * Resolve a safe document filename.
     */
    protected function resolveDocumentFilename(
        mixed $filename,
        mixed $documentPath
    ): ?string {
        $candidate = null;

        if (is_string($filename) && trim($filename) !== '') {
            $candidate = $filename;
        } elseif (
            is_string($documentPath) &&
            trim($documentPath) !== ''
        ) {
            $candidate = $documentPath;
        }

        if ($candidate === null) {
            return null;
        }

        $candidate = str_replace('\\', '/', trim($candidate));
        $candidate = basename($candidate);

        if (
            $candidate === '' ||
            $candidate === '.' ||
            $candidate === '..' ||
            preg_match('/[\x00-\x1F\x7F]/', $candidate)
        ) {
            return null;
        }

        return mb_substr($candidate, 0, 255);
    }

    /**
     * Normalize international recipient number.
     */
    protected function normalizeRecipient(
        string $recipient
    ): string {
        $recipient = $this->digitsOnly($recipient);

        if (!preg_match('/^[1-9]\d{7,14}$/', $recipient)) {
            throw new InvalidArgumentException(
                'A valid international recipient number is required for WhatsApp logging.'
            );
        }

        return $recipient;
    }

    /**
     * Return digits only.
     */
    protected function digitsOnly(string $value): string
    {
        return preg_replace('/\D+/', '', $value) ?? '';
    }

    /**
     * Normalize nullable numeric IDs.
     */
    protected function normalizeNullableId(
        mixed $value
    ): ?int {
        if ($value === null || $value === '') {
            return null;
        }

        if (
            !is_scalar($value) ||
            !ctype_digit((string) $value)
        ) {
            throw new InvalidArgumentException(
                'Invalid ID supplied to WhatsApp log service.'
            );
        }

        $id = (int) $value;

        if ($id <= 0) {
            throw new InvalidArgumentException(
                'WhatsApp log IDs must be positive integers.'
            );
        }

        return $id;
    }

    /**
     * Normalize provider message ID.
     */
    protected function normalizeProviderMessageId(
        mixed $value
    ): ?string {
        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        $value = trim($value);

        if (
            strlen($value) > 191 ||
            preg_match('/[\x00-\x1F\x7F]/', $value)
        ) {
            throw new InvalidArgumentException(
                'Invalid WhatsApp provider message ID.'
            );
        }

        return $value;
    }

    /**
     * Validate message status.
     */
    protected function normalizeStatus(
        string $status
    ): string {
        $status = strtolower(trim($status));

        if (!in_array($status, $this->statuses, true)) {
            throw new InvalidArgumentException(
                "Unsupported WhatsApp message status [{$status}]."
            );
        }

        return $status;
    }

    /**
     * Safely encode JSON.
     */
    protected function encodeJson(array $value): string
    {
        return json_encode(
            $value,
            JSON_THROW_ON_ERROR |
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES
        );
    }

    /**
     * Encode nullable JSON.
     */
    protected function encodeNullableJson(
        ?array $value
    ): ?string {
        return $value === null
            ? null
            : $this->encodeJson($value);
    }

    /**
     * Decode stored JSON fields.
     */
    protected function decodeRecord(array $record): array
    {
        foreach ($this->jsonColumns as $column) {
            $value = $record[$column] ?? null;

            if (!is_string($value) || $value === '') {
                $record[$column] = null;
                continue;
            }

            $decoded = json_decode($value, true);

            $record[$column] = is_array($decoded)
                ? $decoded
                : null;
        }

        return $record;
    }

    /**
     * Sanitize error messages before database persistence.
     */
    protected function sanitizeErrorMessage(
        mixed $message
    ): ?string {
        if (!is_string($message) || trim($message) === '') {
            return null;
        }

        $message = trim($message);

        /*
         * Redact Bearer credentials.
         */
        $message = preg_replace(
            '/Bearer\s+[^\s,;"\']+/i',
            'Bearer [REDACTED]',
            $message
        ) ?? $message;

        /*
         * Redact sensitive query parameters and key/value pairs.
         */
        $sensitivePattern = implode(
            '|',
            array_map(
                fn ($key) => preg_quote($key, '/'),
                $this->sensitiveKeys
            )
        );

        $message = preg_replace(
            '/(["\']?(?:' . $sensitivePattern . ')["\']?\s*[:=]\s*)' .
            '(["\']?)([^"\'&,\s}\]]+)(["\']?)/i',
            '$1[REDACTED]',
            $message
        ) ?? $message;

        /*
         * Redact URL query credentials.
         */
        $message = preg_replace(
            '/([?&](?:access_token|refresh_token|token|api_key|authorization|secret|password|signature)=)[^&\s]+/i',
            '$1[REDACTED]',
            $message
        ) ?? $message;

        return mb_substr($message, 0, 5000);
    }

    /**
     * Extract safe error information from Meta webhook data.
     */
    protected function resolveWebhookError(
        array $metadata
    ): ?string {
        if (!empty($metadata['error_message'])) {
            return $this->sanitizeErrorMessage(
                $metadata['error_message']
            );
        }

        $errors = $metadata['errors'] ?? [];

        if (!is_array($errors) || empty($errors)) {
            return null;
        }

        $messages = [];

        foreach ($errors as $error) {
            if (!is_array($error)) {
                continue;
            }

            $parts = [];

            if (isset($error['code']) && is_scalar($error['code'])) {
                $parts[] = 'Code: ' . (string) $error['code'];
            }

            if (!empty($error['title'])) {
                $parts[] = (string) $error['title'];
            }

            if (!empty($error['message'])) {
                $parts[] = (string) $error['message'];
            }

            $details = $error['error_data']['details'] ?? null;

            if (is_string($details) && $details !== '') {
                $parts[] = $details;
            }

            if (!empty($parts)) {
                $messages[] = implode(' - ', $parts);
            }
        }

        return $this->sanitizeErrorMessage(
            implode('; ', $messages)
        );
    }

    /**
     * Resolve webhook status timestamp.
     *
     * Meta commonly sends Unix timestamps as strings.
     */
    protected function resolveStatusTimestamp(
        mixed $timestamp
    ): Carbon {
        if (
            is_numeric($timestamp) &&
            (float) $timestamp > 0
        ) {
            try {
                return Carbon::createFromTimestamp(
                    (int) $timestamp
                );
            } catch (Throwable) {
                return now();
            }
        }

        if (is_string($timestamp) && trim($timestamp) !== '') {
            try {
                return Carbon::parse($timestamp);
            } catch (Throwable) {
                return now();
            }
        }

        return now();
    }

    /**
     * Ensure the WhatsApp log table exists.
     */
    protected function assertLogTableExists(): void
    {
        if (!Schema::hasTable($this->table)) {
            throw new RuntimeException(
                "WhatsApp log table [{$this->table}] does not exist. Please run the WhatsApp message log migration first."
            );
        }
    }

    /**
     * Verify columns required by this service.
     */
    protected function assertRequiredColumnsExist(): void
    {
        $requiredColumns = [
            'reference',
            'driver_id',
            'created_by',
            'recipient',
            'template_key',
            'mode',
            'channel',
            'status',
            'variables',
            'document_path',
            'document_filename',
            'message_payload',
            'provider_response',
            'provider_message_id',
            'error_message',
            'sent_at',
            'delivered_at',
            'read_at',
            'failed_at',
            'status_updated_at',
            'created_at',
            'updated_at',
        ];

        foreach ($requiredColumns as $column) {
            if (!Schema::hasColumn($this->table, $column)) {
                throw new RuntimeException(
                    "WhatsApp log table [{$this->table}] is missing required column [{$column}]."
                );
            }
        }
    }
}
