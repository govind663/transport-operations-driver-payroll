<?php

namespace App\Services\WhatsApp;

use App\Services\WhatsApp\WhatsAppCloudApiService;
use App\Services\WhatsApp\WhatsAppTemplateService;
use App\Services\WhatsApp\WhatsAppMediaService;
use App\Services\WhatsApp\WhatsAppLogService;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use InvalidArgumentException;
use RuntimeException;
use Throwable;

class WhatsAppMasterService
{
    /**
     * WhatsApp service dependencies.
     */
    protected WhatsAppCloudApiService $cloudApiService;

    protected WhatsAppTemplateService $templateService;

    protected WhatsAppMediaService $mediaService;

    protected WhatsAppLogService $logService;

    /**
     * Constructor.
     */
    public function __construct(
        WhatsAppCloudApiService $cloudApiService,
        WhatsAppTemplateService $templateService,
        WhatsAppMediaService $mediaService,
        WhatsAppLogService $logService
    ) {
        $this->cloudApiService = $cloudApiService;
        $this->templateService = $templateService;
        $this->mediaService = $mediaService;
        $this->logService = $logService;
    }

    /**
     * Send WhatsApp template message.
     *
     * Supported operating contracts:
     *
     * LOCAL:
     * - mode: local
     * - channel: personal_chat
     * - status: chat_link_generated
     * - Generates a wa.me personal chat URL.
     * - Does not send a message through Meta Cloud API.
     *
     * LIVE:
     * - mode: live
     * - channel: cloud_api
     * - Creates pending log before API request.
     * - Updates the same log to sent or failed.
     * - Never falls back to personal WhatsApp.
     *
     * @param string $recipient
     * @param string $templateKey
     * @param array $variables
     * @param string|null $documentPath
     * @param string|null $documentFilename
     * @param int|null $userId
     *
     * @return array
     */
    public function sendTemplate(
        string $recipient,
        string $templateKey,
        array $variables = [],
        ?string $documentPath = null,
        ?string $documentFilename = null,
        ?int $userId = null
    ): array {
        $messageReference = (string) Str::uuid();

        $mode = $this->getMode();

        $recipientNumber = null;

        $log = null;

        $logCreated = false;

        $templatePayload = null;

        $userId = $userId ?? Auth::id();

        try {
            /*
             * Validate recipient according to operating mode.
             */
            $recipientNumber = $this->normalizeRecipient(
                $recipient,
                $mode
            );

            /*
             * Validate template key.
             */
            $this->validateTemplateKey($templateKey);

            /*
             * Prepare document attachment if provided.
             *
             * Local mode only prepares the document information.
             * Live mode uploads/prepares the document through
             * WhatsAppMediaService according to its implementation.
             */
            $document = null;

            if ($documentPath !== null) {
                $document = $this->mediaService->prepareDocument(
                    $documentPath,
                    $documentFilename
                );
            }

            /*
             * Build the WhatsApp template payload.
             *
             * This validates the template and its variables
             * before either operating mode proceeds.
             */
            $templatePayload = $this->templateService->buildPayload(
                $templateKey,
                $variables,
                $document
            );

            /*
             * Common log data.
             */
            $logData = [
                'reference' => $messageReference,
                'recipient' => $recipientNumber,
                'template_key' => $templateKey,
                'variables' => $variables,
                'mode' => $mode,
                'document_path' => $documentPath,
                'document_filename' => $documentFilename,
                'message_payload' => $templatePayload,
                'created_by' => $userId,
            ];

            /*
             * LOCAL MODE
             *
             * Personal WhatsApp chat link only.
             *
             * No Meta Cloud API request is made.
             * No sent/delivered/read status is recorded.
             */
            if ($mode === 'local') {
                $chatUrl = $this->buildPersonalChatUrl(
                    $recipientNumber
                );

                $logData['channel'] = 'personal_chat';
                $logData['status'] = 'chat_link_generated';
                $logData['provider_response'] = null;
                $logData['provider_message_id'] = null;

                $log = $this->logService->create($logData);

                $logCreated = true;

                Log::info(
                    'WhatsApp personal chat link generated.',
                    [
                        'reference' => $messageReference,
                        'recipient' => $recipientNumber,
                        'template' => $templateKey,
                        'mode' => 'local',
                        'channel' => 'personal_chat',
                        'status' => 'chat_link_generated',
                    ]
                );

                return [
                    'success' => true,
                    'mode' => 'local',
                    'channel' => 'personal_chat',
                    'status' => 'chat_link_generated',
                    'reference' => $messageReference,
                    'chat_url' => $chatUrl,
                    'provider_message_id' => null,
                    'log' => $log,
                    'message' => $documentPath !== null
                        ? 'Personal WhatsApp chat link generated successfully. Please attach the PDF manually in WhatsApp.'
                        : 'Personal WhatsApp chat link generated successfully.',
                    'sent' => false,
                ];
            }

            /*
             * LIVE MODE
             *
             * Only WhatsApp Cloud API is allowed.
             *
             * Create the pending log BEFORE making the API request.
             * The same reference will be updated after the API response.
             */
            $logData['channel'] = 'cloud_api';
            $logData['status'] = 'pending';
            $logData['provider_response'] = null;
            $logData['provider_message_id'] = null;

            $log = $this->logService->create($logData);

            $logCreated = true;

            Log::info(
                'WhatsApp Cloud API message queued for submission.',
                [
                    'reference' => $messageReference,
                    'recipient' => $recipientNumber,
                    'template' => $templateKey,
                    'mode' => 'live',
                    'channel' => 'cloud_api',
                    'status' => 'pending',
                ]
            );

            /*
             * Submit the message through Meta WhatsApp Cloud API.
             *
             * There is deliberately no personal-chat fallback here.
             */
            $response = $this->cloudApiService->sendTemplateMessage(
                $recipientNumber,
                $templatePayload
            );

            /*
             * Extract Meta provider message ID.
             *
             * A successful API submission must return a valid
             * provider message ID for webhook status tracking.
             */
            $providerMessageId = data_get(
                $response,
                'messages.0.id'
            );

            if (
                !is_string($providerMessageId) ||
                trim($providerMessageId) === ''
            ) {
                throw new RuntimeException(
                    'WhatsApp Cloud API response did not contain a valid provider message ID.'
                );
            }

            $providerMessageId = trim($providerMessageId);

            /*
             * Update the SAME log using its internal reference.
             *
             * WhatsAppLogService must support updating:
             * - provider_message_id
             * - provider_response
             * - message_payload
             *
             * The status becomes sent only after API success.
             */
            $updated = $this->logService->updateStatusByReference(
                $messageReference,
                'sent',
                [
                    'provider_message_id' => $providerMessageId,
                    'provider_response' => $response,
                    'message_payload' => $templatePayload,
                ]
            );

            if (!$updated) {
                throw new RuntimeException(
                    'WhatsApp API accepted the message, but its existing log could not be updated to sent.'
                );
            }

            $log = $this->logService->findByReference(
                $messageReference
            );

            Log::info(
                'WhatsApp Cloud API message submitted successfully.',
                [
                    'reference' => $messageReference,
                    'recipient' => $recipientNumber,
                    'template' => $templateKey,
                    'provider_message_id' => $providerMessageId,
                    'mode' => 'live',
                    'channel' => 'cloud_api',
                    'status' => 'sent',
                ]
            );

            return [
                'success' => true,
                'mode' => 'live',
                'channel' => 'cloud_api',
                'status' => 'sent',
                'reference' => $messageReference,
                'provider_message_id' => $providerMessageId,
                'log' => $log,
                'message' => 'WhatsApp message submitted successfully through WhatsApp Cloud API.',
                'sent' => true,
            ];

                    } catch (Throwable $exception) {
            /*
             * Update the existing log instead of creating
             * another log with the same reference.
             */
            if ($logCreated) {
                try {
                    $updated = $this->logService->updateStatusByReference(
                        $messageReference,
                        'failed',
                        [
                            'error_message' => $exception->getMessage(),
                        ]
                    );

                    if ($updated) {
                        $log = $this->logService->findByReference(
                            $messageReference
                        );
                    }
                } catch (Throwable $logException) {
                    Log::error(
                        'WhatsApp existing failure log could not be updated.',
                        [
                            'reference' => $messageReference,
                            'error' => $logException->getMessage(),
                        ]
                    );
                }
            } elseif (
                $mode === 'live' &&
                $recipientNumber !== null
            ) {
                /*
                 * If preparation failed before the pending log
                 * could be created, attempt to create one failed
                 * Cloud API log for this reference.
                 *
                 * This is not a duplicate because no log was created
                 * earlier in this execution.
                 */
                try {
                    $log = $this->logService->create([
                        'reference' => $messageReference,
                        'recipient' => $recipientNumber,
                        'template_key' => $templateKey,
                        'variables' => $variables,
                        'mode' => 'live',
                        'channel' => 'cloud_api',
                        'status' => 'failed',
                        'document_path' => $documentPath,
                        'document_filename' => $documentFilename,
                        'message_payload' => $templatePayload,
                        'provider_response' => null,
                        'provider_message_id' => null,
                        'error_message' => $exception->getMessage(),
                        'created_by' => $userId,
                    ]);

                    $logCreated = true;
                } catch (Throwable $logException) {
                    Log::error(
                        'WhatsApp failure could not be recorded.',
                        [
                            'reference' => $messageReference,
                            'error' => $logException->getMessage(),
                        ]
                    );
                }
            }

            Log::error(
                'WhatsApp message processing failed.',
                [
                    'reference' => $messageReference,
                    'recipient' => $recipientNumber,
                    'template' => $templateKey,
                    'mode' => $mode,
                    'channel' => $mode === 'live'
                        ? 'cloud_api'
                        : 'personal_chat',
                    'log_created' => $logCreated,
                    'error' => $exception->getMessage(),
                ]
            );

            return [
                'success' => false,
                'mode' => $mode,
                'channel' => $mode === 'live'
                    ? 'cloud_api'
                    : 'personal_chat',
                'status' => 'failed',
                'reference' => $messageReference,
                'provider_message_id' => null,
                'chat_url' => null,
                'log' => $log,
                'message' => 'WhatsApp message could not be processed.',
                'error' => $exception->getMessage(),
                'sent' => false,
            ];
        }
    }

    /**
     * Send welcome message after user registration.
     */
    public function sendWelcomeMessage(
        string $recipient,
        string $userName,
        string $activationUrl,
        ?int $userId = null
    ): array {
        return $this->sendTemplate(
            recipient: $recipient,
            templateKey: 'user_welcome_notification',
            variables: [
                'name' => $userName,
                'mobile' => $recipient,
                'activation_url' => $activationUrl,
            ],
            userId: $userId
        );
    }

    /**
     * Send account verification OTP.
     */
    public function sendVerificationOtp(
        string $recipient,
        string $otp,
        int $expiryMinutes = 5,
        ?int $userId = null
    ): array {
        return $this->sendTemplate(
            recipient: $recipient,
            templateKey: 'account_verification_otp',
            variables: [
                'otp' => $otp,
                'expiry_minutes' => $expiryMinutes,
            ],
            userId: $userId
        );
    }

    /**
     * Send monthly attendance PDF.
     */
    public function sendAttendanceReport(
        string $recipient,
        string $driverName,
        string $month,
        int $year,
        string $pdfPath,
        ?int $userId = null
    ): array {
        return $this->sendTemplate(
            recipient: $recipient,
            templateKey: 'monthly_attendance_report',
            variables: [
                'driver_name' => $driverName,
                'month' => $month,
                'year' => $year,
            ],
            documentPath: $pdfPath,
            documentFilename: 'Attendance-' . $month . '-' . $year . '.pdf',
            userId: $userId
        );
    }

    /**
     * Send monthly working sheet PDF.
     */
    public function sendWorkingSheetReport(
        string $recipient,
        string $driverName,
        string $month,
        int $year,
        string $pdfPath,
        ?int $userId = null
    ): array {
        return $this->sendTemplate(
            recipient: $recipient,
            templateKey: 'monthly_working_sheet_report',
            variables: [
                'driver_name' => $driverName,
                'month' => $month,
                'year' => $year,
            ],
            documentPath: $pdfPath,
            documentFilename: 'Working-Sheet-' . $month . '-' . $year . '.pdf',
            userId: $userId
        );
    }

    /**
     * Send monthly salary slip PDF.
     */
    public function sendSalarySlip(
        string $recipient,
        string $driverName,
        string $month,
        int $year,
        string $pdfPath,
        ?int $userId = null
    ): array {
        return $this->sendTemplate(
            recipient: $recipient,
            templateKey: 'monthly_salary_slip',
            variables: [
                'driver_name' => $driverName,
                'month' => $month,
                'year' => $year,
            ],
            documentPath: $pdfPath,
            documentFilename: 'Salary-Slip-' . $month . '-' . $year . '.pdf',
            userId: $userId
        );
    }

    /**
     * Send driver duty assignment notification.
     */
    public function sendDutyAssignment(
        string $recipient,
        string $driverName,
        string $dutyDate,
        string $reportingTime,
        string $reportingLocation,
        ?int $userId = null
    ): array {
        return $this->sendTemplate(
            recipient: $recipient,
            templateKey: 'driver_duty_assignment',
            variables: [
                'driver_name' => $driverName,
                'duty_date' => $dutyDate,
                'reporting_time' => $reportingTime,
                'reporting_location' => $reportingLocation,
            ],
            userId: $userId
        );
    }

        /**
     * Get current WhatsApp operating mode.
     *
     * Supported modes:
     * - local
     * - live
     */
    protected function getMode(): string
    {
        $mode = strtolower(
            trim((string) config('services.whatsapp.mode', 'local'))
        );

        if (!in_array($mode, ['local', 'live'], true)) {
            throw new InvalidArgumentException(
                'Invalid WhatsApp mode. Allowed modes are local and live.'
            );
        }

        return $mode;
    }

    /**
     * Generate a Personal WhatsApp chat URL.
     *
     * This only opens the recipient's WhatsApp chat.
     * It does not send a message or confirm delivery.
     */
    protected function buildPersonalChatUrl(
        string $recipient
    ): string {
        $recipient = preg_replace('/\D+/', '', $recipient) ?? '';

        if (!preg_match('/^[1-9]\d{7,14}$/', $recipient)) {
            throw new InvalidArgumentException(
                'A valid international recipient number is required to generate a WhatsApp chat link.'
            );
        }

        return 'https://wa.me/' . $recipient;
    }

    /**
     * Normalize recipient mobile number.
     *
     * Local mode:
     * - Accepts a number containing 8 to 15 digits.
     * - Country code should be included for a correct wa.me link.
     *
     * Live mode:
     * - Requires international format with country code.
     * - Example: +919876543210
     */
    protected function normalizeRecipient(
        string $recipient,
        string $mode
    ): string {
        $recipient = trim($recipient);

        if ($recipient === '') {
            throw new InvalidArgumentException(
                'WhatsApp recipient number is required.'
            );
        }

        if ($mode === 'live') {
            if (!preg_match('/^\+?[1-9]\d{7,14}$/', $recipient)) {
                throw new InvalidArgumentException(
                    'For live WhatsApp sending, provide the recipient number in international format, e.g. +919876543210.'
                );
            }

            return ltrim($recipient, '+');
        }

        $number = preg_replace('/\D+/', '', $recipient);

        if (
            !is_string($number) ||
            strlen($number) < 8 ||
            strlen($number) > 15 ||
            !preg_match('/^[1-9]\d+$/', $number)
        ) {
            throw new InvalidArgumentException(
                'Please provide a valid recipient mobile number with country code.'
            );
        }

        return $number;
    }

    /**
     * Validate template key.
     */
    protected function validateTemplateKey(
        string $templateKey
    ): void {
        if (
            $templateKey === '' ||
            !preg_match('/^[a-z0-9_]+$/', $templateKey)
        ) {
            throw new InvalidArgumentException(
                'Invalid WhatsApp template key.'
            );
        }
    }
}
