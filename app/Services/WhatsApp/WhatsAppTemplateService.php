<?php

namespace App\Services\WhatsApp;

use InvalidArgumentException;
use RuntimeException;

class WhatsAppTemplateService
{
    /**
     * Local WhatsApp template registry.
     *
     * Used for:
     * - Local template management
     * - Variable validation
     * - Message preview
     * - Meta Cloud API payload generation
     *
     * Actual Meta template names, languages, categories,
     * and component definitions must match approved templates.
     */
    protected array $templates;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->templates = $this->defaultTemplates();
    }

    /**
     * Get all available WhatsApp templates.
     *
     * @return array
     */
    public function getTemplates(): array
    {
        $result = [];

        foreach (array_keys($this->templates) as $templateKey) {
            $template = $this->getTemplate($templateKey);

            $result[$templateKey] = [
                'key' => $templateKey,
                'title' => $template['title'],
                'name' => $template['name'],
                'language' => $template['language'],
                'category' => $template['category'],
                'variables' => $template['required_variables'],
                'optional_variables' => $template['optional_variables'],
                'requires_document' => $template['requires_document'],
                'status' => 'configured_locally',
            ];
        }

        return $result;
    }

    /**
     * Get a single template definition.
     *
     * Individual template name/language/category can be
     * overridden through config/services.php.
     */
    public function getTemplate(string $templateKey): array
    {
        if (!isset($this->templates[$templateKey])) {
            throw new InvalidArgumentException(
                "WhatsApp template [{$templateKey}] does not exist."
            );
        }

        $template = $this->templates[$templateKey];

        $overrides = config(
            'services.whatsapp.templates.' . $templateKey,
            []
        );

        if (!is_array($overrides)) {
            $overrides = [];
        }

        /*
         * Only controlled configuration overrides are allowed.
         * Required variables and their positional order remain protected.
         */
        $allowedOverrides = [
            'name',
            'language',
            'category',
            'otp_button',
            'otp_expiry_minutes',
        ];

        foreach ($allowedOverrides as $key) {
            if (array_key_exists($key, $overrides)) {
                $template[$key] = $overrides[$key];
            }
        }

        $this->validateTemplateDefinition(
            $templateKey,
            $template
        );

        return $template;
    }

    /**
     * Get required variable names for a template.
     */
    public function getRequiredVariables(
        string $templateKey
    ): array {
        $template = $this->getTemplate($templateKey);

        return $template['required_variables'];
    }

    /**
     * Get optional variable names for a template.
     */
    public function getOptionalVariables(
        string $templateKey
    ): array {
        $template = $this->getTemplate($templateKey);

        return $template['optional_variables'];
    }

    /**
     * Build a WhatsApp Cloud API template payload.
     *
     * Compatible with WhatsAppMasterService:
     *
     * buildPayload($templateKey, $variables, $document)
     *
     * Supported templates:
     * 1. user_welcome_notification
     * 2. account_verification_otp
     * 3. monthly_attendance_report
     * 4. monthly_working_sheet_report
     * 5. monthly_salary_slip
     * 6. driver_duty_assignment
     *
     * @return array
     */
    public function buildPayload(
        string $templateKey,
        array $variables = [],
        ?array $document = null
    ): array {
        $template = $this->getTemplate($templateKey);

        $variables = $this->validateVariables(
            $templateKey,
            $template,
            $variables
        );

        $components = [];

        /*
         * Add PDF document header for report templates.
         */
        if ($template['requires_document']) {
            $components[] = $this->buildDocumentHeader(
                $document
            );
        }

        /*
         * Prepare body parameters in the exact positional
         * order configured for the approved Meta template.
         */
        $bodyParameters = [];

        foreach ($template['body_variables'] as $variable) {
            if (!array_key_exists($variable, $variables)) {
                throw new InvalidArgumentException(
                    "Body variable [{$variable}] is missing for template [{$templateKey}]."
                );
            }

            $bodyParameters[] = [
                'type' => 'text',
                'text' => $variables[$variable],
            ];
        }

        if (!empty($bodyParameters)) {
            $components[] = [
                'type' => 'body',
                'parameters' => $bodyParameters,
            ];
        }

        /*
         * Authentication OTP button.
         *
         * The configured button definition must match
         * the approved Meta authentication template.
         */
        if (
            $templateKey === 'account_verification_otp' &&
            !empty($template['otp_button'])
        ) {
            $button = $template['otp_button'];

            $components[] = [
                'type' => 'button',
                'sub_type' => $button['sub_type'],
                'index' => (string) $button['index'],
                'parameters' => [
                    [
                        'type' => 'text',
                        'text' => $variables['otp'],
                    ],
                ],
            ];
        }

        return [
            'name' => $template['name'],
            'language' => [
                'code' => $template['language'],
            ],
            'components' => $components,
        ];
    }

    /**
     * Render a human-readable local message preview.
     *
     * No WhatsApp API request is made here.
     *
     * @return array
     */
    public function renderPreview(
        string $templateKey,
        array $variables = [],
        ?array $document = null
    ): array {
        $template = $this->getTemplate($templateKey);

        $variables = $this->validateVariables(
            $templateKey,
            $template,
            $variables
        );

        $replacements = [];

        foreach ($variables as $key => $value) {
            $replacements['{{' . $key . '}}'] = $value;
        }

        $message = strtr(
            $template['preview_text'],
            $replacements
        );

        $documentDetails = null;

        if ($template['requires_document']) {
            $documentDetails = $this->getDocumentDetails(
                $document
            );

            if ($documentDetails === null) {
                throw new InvalidArgumentException(
                    'A PDF document is required to preview this WhatsApp template.'
                );
            }
        }

        return [
            'success' => true,
            'mode' => 'local_preview',
            'template_key' => $templateKey,
            'template_name' => $template['name'],
            'title' => $template['title'],
            'category' => $template['category'],
            'language' => $template['language'],
            'message' => $message,
            'requires_document' => $template['requires_document'],
            'document' => $documentDetails,
            'variables' => $variables,
        ];
    }

    /**
     * Validate template variables.
     */
    protected function validateVariables(
        string $templateKey,
        array $template,
        array $variables
    ): array {
        $requiredVariables = $template['required_variables'];

        $optionalVariables = $template['optional_variables'];

        $allowedVariables = array_merge(
            $requiredVariables,
            $optionalVariables
        );

        /*
         * Prevent accidental or misspelled variables.
         */
        $unknownVariables = array_diff(
            array_keys($variables),
            $allowedVariables
        );

        if (!empty($unknownVariables)) {
            throw new InvalidArgumentException(
                'Unknown WhatsApp template variables: ' .
                implode(', ', $unknownVariables)
            );
        }

        $normalized = [];

        /*
         * Validate required variables.
         */
        foreach ($requiredVariables as $variable) {
            if (
                !array_key_exists($variable, $variables) ||
                $variables[$variable] === null
            ) {
                throw new InvalidArgumentException(
                    "Required WhatsApp template variable [{$variable}] is missing."
                );
            }

            $normalized[$variable] = $this->normalizeVariable(
                $templateKey,
                $variable,
                $variables[$variable]
            );
        }

        /*
         * Validate optional variables.
         */
        foreach ($optionalVariables as $variable) {
            if (
                !array_key_exists($variable, $variables) ||
                $variables[$variable] === null
            ) {
                continue;
            }

            $normalized[$variable] = $this->normalizeVariable(
                $templateKey,
                $variable,
                $variables[$variable]
            );
        }

        /*
         * OTP expiry is fixed at the approved Meta template level.
         * It is not sent as a dynamic body parameter.
         */
        if ($templateKey === 'account_verification_otp') {
            $configuredExpiry = (int) $template['otp_expiry_minutes'];

            if (!isset($normalized['expiry_minutes'])) {
                $normalized['expiry_minutes'] = (string) $configuredExpiry;
            }

            if (
                (int) $normalized['expiry_minutes'] !== $configuredExpiry
            ) {
                throw new InvalidArgumentException(
                    "OTP expiry must match the configured Meta template expiry of {$configuredExpiry} minutes."
                );
            }
        }

        return $normalized;
    }


    /**
     * Normalize and validate an individual variable.
     */
    protected function normalizeVariable(
        string $templateKey,
        string $variable,
        mixed $value
    ): string {
        if (!is_scalar($value)) {
            throw new InvalidArgumentException(
                "WhatsApp template variable [{$variable}] must be a scalar value."
            );
        }

        $value = trim((string) $value);

        if ($value === '') {
            throw new InvalidArgumentException(
                "WhatsApp template variable [{$variable}] cannot be empty."
            );
        }

        /*
         * OTP validation.
         */
        if ($variable === 'otp') {
            if (!preg_match('/^\d{4,8}$/', $value)) {
                throw new InvalidArgumentException(
                    'OTP must contain between 4 and 8 digits.'
                );
            }

            return $value;
        }

        /*
         * OTP expiry validation.
         */
        if ($variable === 'expiry_minutes') {
            if (
                !ctype_digit($value) ||
                (int) $value < 1 ||
                (int) $value > 90
            ) {
                throw new InvalidArgumentException(
                    'OTP expiry must be between 1 and 90 minutes.'
                );
            }

            return $value;
        }

        /*
         * Activation URL validation.
         */
        if ($variable === 'activation_url') {
            if (!filter_var($value, FILTER_VALIDATE_URL)) {
                throw new InvalidArgumentException(
                    'A valid activation URL is required.'
                );
            }

            $scheme = strtolower(
                (string) parse_url($value, PHP_URL_SCHEME)
            );

            $mode = strtolower(
                (string) config('services.whatsapp.mode', 'local')
            );

            if (
                $mode === 'live' &&
                $scheme !== 'https'
            ) {
                throw new InvalidArgumentException(
                    'Live WhatsApp activation links must use HTTPS.'
                );
            }

            if (!in_array($scheme, ['http', 'https'], true)) {
                throw new InvalidArgumentException(
                    'Activation URL must use HTTP or HTTPS.'
                );
            }

            return $value;
        }

        /*
         * Registered mobile number in welcome message.
         */
        if ($variable === 'mobile') {
            $number = preg_replace('/\D+/', '', $value);

            if (
                !is_string($number) ||
                !preg_match('/^[1-9]\d{7,14}$/', $number)
            ) {
                throw new InvalidArgumentException(
                    'Welcome message mobile number must include a valid country code.'
                );
            }

            return $value;
        }

        /*
         * Year validation.
         */
        if ($variable === 'year') {
            if (!preg_match('/^\d{4}$/', $value)) {
                throw new InvalidArgumentException(
                    'Report year must be a valid four-digit year.'
                );
            }

            return $value;
        }

        /*
         * Maximum allowed text lengths.
         */
        $maxLengths = [
            'name' => 100,
            'driver_name' => 100,
            'month' => 30,
            'duty_date' => 40,
            'reporting_time' => 40,
            'reporting_location' => 200,
        ];

        $maxLength = $maxLengths[$variable] ?? 500;

        if (mb_strlen($value) > $maxLength) {
            throw new InvalidArgumentException(
                "WhatsApp template variable [{$variable}] exceeds {$maxLength} characters."
            );
        }

        return $value;
    }

    /**
     * Build PDF document header component.
     *
     * Live mode:
     * Requires a Meta-uploaded media ID.
     *
     * Local mode:
     * Includes filename only for local preview/logging.
     * No fake Meta media ID is generated.
     */
    protected function buildDocumentHeader(
        ?array $document
    ): array {
        $details = $this->getDocumentDetails($document);

        if ($details === null) {
            throw new InvalidArgumentException(
                'A PDF document is required for this WhatsApp template.'
            );
        }

        $mode = strtolower(
            trim((string) config(
                'services.whatsapp.mode',
                'local'
            ))
        );

        if (!in_array($mode, ['local', 'live'], true)) {
            throw new RuntimeException(
                'Invalid WhatsApp mode configured.'
            );
        }

        $documentPayload = [
            'filename' => $details['filename'],
        ];

        if ($mode === 'live') {
            $mediaId = $details['media_id'];

            if ($mediaId === null || $mediaId === '') {
                throw new RuntimeException(
                    'A Meta media ID is required for live WhatsApp PDF sending.'
                );
            }

            $documentPayload['id'] = $mediaId;
        }

        return [
            'type' => 'header',
            'parameters' => [
                [
                    'type' => 'document',
                    'document' => $documentPayload,
                ],
            ],
        ];
    }

    /**
     * Extract safe document metadata.
     *
     * Supported formats:
     *
     * [
     *     'id' => 'META_MEDIA_ID',
     *     'filename' => 'Attendance.pdf',
     * ]
     *
     * [
     *     'media_id' => 'META_MEDIA_ID',
     *     'filename' => 'Attendance.pdf',
     * ]
     *
     * [
     *     'path' => 'attendance/Attendance.pdf',
     *     'filename' => 'Attendance.pdf',
     * ]
     */
    protected function getDocumentDetails(
        ?array $document
    ): ?array {
        if (empty($document)) {
            return null;
        }

        /*
         * Support nested document data.
         */
        $documentData = $document['document'] ?? $document;

        if (!is_array($documentData)) {
            return null;
        }

        $mediaId = $documentData['id']
            ?? $documentData['media_id']
            ?? ($documentData['media']['id'] ?? null);

        $filename = $documentData['filename']
            ?? $documentData['file_name']
            ?? (
                isset($documentData['path']) &&
                is_string($documentData['path'])
                    ? basename($documentData['path'])
                    : null
            );

        if (!is_string($filename) || trim($filename) === '') {
            $filename = 'document.pdf';
        }

        $filename = basename(trim($filename));

        $filename = preg_replace(
            '/[^A-Za-z0-9._-]/',
            '_',
            $filename
        );

        if (!is_string($filename) || $filename === '') {
            $filename = 'document.pdf';
        }

        if (
            strtolower(
                pathinfo($filename, PATHINFO_EXTENSION)
            ) !== 'pdf'
        ) {
            $filename .= '.pdf';
        }

        return [
            'media_id' => is_scalar($mediaId)
                ? trim((string) $mediaId)
                : null,

            'filename' => $filename,

            'mime_type' => 'application/pdf',
        ];
    }

    /**
     * Validate template definition and configuration overrides.
     */
    protected function validateTemplateDefinition(
        string $templateKey,
        array $template
    ): void {
        $name = $template['name'] ?? '';

        if (
            !is_string($name) ||
            !preg_match('/^[a-z0-9_]+$/', $name)
        ) {
            throw new InvalidArgumentException(
                "Invalid Meta template name configured for [{$templateKey}]."
            );
        }

        $language = $template['language'] ?? '';

        if (
            !is_string($language) ||
            !preg_match(
                '/^[a-zA-Z]{2,3}(?:[_-][a-zA-Z0-9]{2,8})*$/',
                $language
            )
        ) {
            throw new InvalidArgumentException(
                "Invalid WhatsApp template language configured for [{$templateKey}]."
            );
        }

        $categories = [
            'UTILITY',
            'AUTHENTICATION',
            'MARKETING',
        ];

        if (
            !in_array(
                $template['category'] ?? '',
                $categories,
                true
            )
        ) {
            throw new InvalidArgumentException(
                "Invalid WhatsApp template category configured for [{$templateKey}]."
            );
        }

        if (
            !isset($template['required_variables']) ||
            !is_array($template['required_variables']) ||
            !isset($template['optional_variables']) ||
            !is_array($template['optional_variables']) ||
            !isset($template['body_variables']) ||
            !is_array($template['body_variables'])
        ) {
            throw new InvalidArgumentException(
                "Invalid variable definition for WhatsApp template [{$templateKey}]."
            );
        }

        $allowedVariables = array_merge(
            $template['required_variables'],
            $template['optional_variables']
        );

        foreach ($template['body_variables'] as $variable) {
            if (
                !is_string($variable) ||
                !in_array($variable, $allowedVariables, true)
            ) {
                throw new InvalidArgumentException(
                    "Invalid body variable configured for WhatsApp template [{$templateKey}]."
                );
            }
        }

        if (
            !isset($template['requires_document']) ||
            !is_bool($template['requires_document'])
        ) {
            throw new InvalidArgumentException(
                "Invalid document requirement configuration for [{$templateKey}]."
            );
        }

        if (
            !isset($template['preview_text']) ||
            !is_string($template['preview_text'])
        ) {
            throw new InvalidArgumentException(
                "Invalid preview text configured for [{$templateKey}]."
            );
        }

        if ($templateKey === 'account_verification_otp') {
            $expiry = $template['otp_expiry_minutes'] ?? null;

            if (
                !is_numeric($expiry) ||
                (int) $expiry < 1 ||
                (int) $expiry > 90
            ) {
                throw new InvalidArgumentException(
                    'Configured OTP expiry must be between 1 and 90 minutes.'
                );
            }

            $button = $template['otp_button'] ?? null;

            if ($button !== null) {
                if (
                    !is_array($button) ||
                    !in_array(
                        $button['sub_type'] ?? '',
                        ['url'],
                        true
                    ) ||
                    !isset($button['index']) ||
                    !ctype_digit((string) $button['index'])
                ) {
                    throw new InvalidArgumentException(
                        'Invalid OTP button configuration.'
                    );
                }
            }
        }
    }


    /**
     * Default six Mastermind Travels templates.
     *
     * IMPORTANT:
     * These are local definitions.
     * Actual template names, languages, categories, body
     * parameters and buttons must match Meta-approved templates.
     */
    protected function defaultTemplates(): array
    {
        $signature = 'Regards, Mastermind Travels - Transport Operations & Driver Payroll Team';

        return [

            /*
             * 1. New user welcome and account setup.
             */
            'user_welcome_notification' => [

                'title' => 'New User Welcome & Account Setup',

                'name' => 'user_welcome_notification',

                'language' => 'en_US',

                'category' => 'UTILITY',

                'required_variables' => [
                    'name',
                    'mobile',
                    'activation_url',
                ],

                'optional_variables' => [],

                'body_variables' => [
                    'name',
                    'mobile',
                    'activation_url',
                ],

                'requires_document' => false,

                'preview_text' =>
                    "Hello {{name}},\n\n" .
                    "Welcome to Mastermind Travels!\n\n" .
                    "Your account has been created successfully.\n" .
                    "Registered mobile number: {{mobile}}\n\n" .
                    "Set your password securely using this link:\n" .
                    "{{activation_url}}\n\n" .
                    "Please do not share your activation link with anyone.\n\n" .
                    $signature,
            ],

            /*
             * 2. Account verification OTP.
             */
            'account_verification_otp' => [

                'title' => 'Account Verification OTP',

                'name' => 'account_verification_otp',

                'language' => 'en_US',

                'category' => 'AUTHENTICATION',

                'required_variables' => [
                    'otp',
                ],

                'optional_variables' => [
                    'expiry_minutes',
                ],

                'body_variables' => [
                    'otp',
                ],

                'requires_document' => false,

                /*
                 * This button configuration must match
                 * the approved Meta authentication template.
                 */
                'otp_button' => [
                    'sub_type' => 'url',
                    'index' => '0',
                ],

                /*
                 * Must match the approved Meta template expiry.
                 */
                'otp_expiry_minutes' => 5,

                'preview_text' =>
                    "Your Mastermind Travels verification code is {{otp}}.\n\n" .
                    "This code expires in {{expiry_minutes}} minutes.\n" .
                    "Do not share this code with anyone.",
            ],

            /*
             * 3. Monthly attendance sheet.
             */
            'monthly_attendance_report' => [

                'title' => 'Monthly Attendance Sheet',

                'name' => 'monthly_attendance_report',

                'language' => 'en_US',

                'category' => 'UTILITY',

                'required_variables' => [
                    'driver_name',
                    'month',
                    'year',
                ],

                'optional_variables' => [],

                'body_variables' => [
                    'driver_name',
                    'month',
                    'year',
                ],

                'requires_document' => true,

                'preview_text' =>
                    "Dear {{driver_name}},\n\n" .
                    "Your monthly attendance sheet for {{month}} {{year}} is attached as a PDF.\n\n" .
                    "Please review the attendance details and contact the operations team if you notice any discrepancy.\n\n" .
                    $signature,
            ],

            /*
             * 4. Monthly working sheet report.
             */
            'monthly_working_sheet_report' => [

                'title' => 'Monthly Working Sheet Report',

                'name' => 'monthly_working_sheet_report',

                'language' => 'en_US',

                'category' => 'UTILITY',

                'required_variables' => [
                    'driver_name',
                    'month',
                    'year',
                ],

                'optional_variables' => [],

                'body_variables' => [
                    'driver_name',
                    'month',
                    'year',
                ],

                'requires_document' => true,

                'preview_text' =>
                    "Dear {{driver_name}},\n\n" .
                    "Your monthly working sheet report for {{month}} {{year}} is attached as a PDF.\n\n" .
                    "Please review your working details and contact the operations team for any clarification.\n\n" .
                    $signature,
            ],

            /*
             * 5. Monthly salary slip.
             */
            'monthly_salary_slip' => [

                'title' => 'Monthly Salary Slip',

                'name' => 'monthly_salary_slip',

                'language' => 'en_US',

                'category' => 'UTILITY',

                'required_variables' => [
                    'driver_name',
                    'month',
                    'year',
                ],

                'optional_variables' => [],

                'body_variables' => [
                    'driver_name',
                    'month',
                    'year',
                ],

                'requires_document' => true,

                'preview_text' =>
                    "Dear {{driver_name}},\n\n" .
                    "Your salary slip for {{month}} {{year}} is attached as a PDF.\n\n" .
                    "This document contains your monthly payroll information. Please keep it confidential.\n\n" .
                    "For payroll-related queries, please contact the accounts team.\n\n" .
                    $signature,
            ],

            /*
             * 6. Driver duty assignment.
             */
            'driver_duty_assignment' => [

                'title' => 'Driver Duty Assignment',

                'name' => 'driver_duty_assignment',

                'language' => 'en_US',

                'category' => 'UTILITY',

                'required_variables' => [
                    'driver_name',
                    'duty_date',
                    'reporting_time',
                    'reporting_location',
                ],

                'optional_variables' => [],

                'body_variables' => [
                    'driver_name',
                    'duty_date',
                    'reporting_time',
                    'reporting_location',
                ],

                'requires_document' => false,

                'preview_text' =>
                    "Dear {{driver_name}},\n\n" .
                    "A new duty has been assigned to you.\n\n" .
                    "Duty Date: {{duty_date}}\n" .
                    "Reporting Time: {{reporting_time}}\n" .
                    "Reporting Location: {{reporting_location}}\n\n" .
                    "Please report on time and contact the operations team if you need assistance.\n\n" .
                    $signature,
            ],

        ];
    }
}
