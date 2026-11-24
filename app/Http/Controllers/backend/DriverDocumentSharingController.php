<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\SalarySlip;
use App\Models\User;
use App\Services\Reports\AttendanceReport\AttendanceReportService;
use App\Services\Reports\WorkingSheetReport\WorkingSheetReportService;
use App\Services\WhatsApp\WhatsAppLogService;
use App\Services\WhatsApp\WhatsAppMasterService;
use App\Services\WhatsApp\WhatsAppMediaService;
use App\Services\WhatsApp\WhatsAppTemplateService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DriverDocumentSharingController extends Controller
{
    protected AttendanceReportService $attendanceReportService;

    protected WorkingSheetReportService $workingSheetReportService;

    protected WhatsAppMasterService $whatsappMasterService;

    protected WhatsAppMediaService $whatsappMediaService;

    protected WhatsAppLogService $whatsappLogService;

    protected WhatsAppTemplateService $whatsappTemplateService;

    public function __construct(
        AttendanceReportService $attendanceReportService,
        WorkingSheetReportService $workingSheetReportService,
        WhatsAppMasterService $whatsappMasterService,
        WhatsAppMediaService $whatsappMediaService,
        WhatsAppLogService $whatsappLogService,
        WhatsAppTemplateService $whatsappTemplateService
    ) {
        $this->attendanceReportService = $attendanceReportService;
        $this->workingSheetReportService = $workingSheetReportService;
        $this->whatsappMasterService = $whatsappMasterService;
        $this->whatsappMediaService = $whatsappMediaService;
        $this->whatsappLogService = $whatsappLogService;
        $this->whatsappTemplateService = $whatsappTemplateService;
    }

    /*
    |--------------------------------------------------------------------------
    | MONTHLY DRIVER DOCUMENTS
    |--------------------------------------------------------------------------
    */

    /**
     * Display all three monthly Driver Documents.
     *
     * 1. Attendance PDF
     * 2. Working Sheet PDF
     * 3. Salary Slip PDF
     *
     * PDF generation happens on page load.
     * WhatsApp dispatch never happens automatically.
     */
    public function index(Request $request): View
    {
        $user = $this->authorizeAdminAccess();

        $filters = $request->validate([
            'driver_id' => [
                'nullable',
                'integer',
                'exists:drivers,id',
            ],
            'month' => [
                'nullable',
                'date_format:Y-m',
            ],
        ]);

        $selectedMonth = $filters['month'] ?? now()->format('Y-m');

        $monthDate = Carbon::createFromFormat(
            '!Y-m',
            $selectedMonth
        );

        abort_unless(
            $monthDate !== false
                && $monthDate->format('Y-m') === $selectedMonth,
            422,
            'Invalid document report month.'
        );

        $drivers = Driver::query()
            ->select([
                'id',
                'user_id',
                'driver_code',
                'first_name',
                'last_name',
            ])
            ->with([
                'user:id,name,phone',
            ])
            ->orderBy('driver_code')
            ->get();

        $selectedDriver = null;

        if (!empty($filters['driver_id'])) {
            $selectedDriver = $drivers->firstWhere(
                'id',
                (int) $filters['driver_id']
            );

            abort_unless(
                $selectedDriver instanceof Driver,
                404,
                'Selected driver was not found.'
            );
        }

        $driverPhone = $selectedDriver?->user?->phone;

        $normalizedDriverPhone = $this->normalizeIndianMobileNumber(
            $driverPhone
        );

        $isValidDriverWhatsAppNumber = $normalizedDriverPhone !== null;

        /*
        |--------------------------------------------------------------------------
        | DOCUMENT DEFINITIONS
        |--------------------------------------------------------------------------
        */

        $documentTypes = [
            'attendance' => [
                'key' => 'attendance',
                'title' => 'Monthly Attendance Sheet',
                'description' => 'Monthly driver attendance calendar and summary.',
                'template_key' => 'monthly_attendance_report',
                'filename_prefix' => 'Attendance',
                'document_type' => 'attendance',
            ],

            'working_sheet' => [
                'key' => 'working_sheet',
                'title' => 'Monthly Working Sheet Report',
                'description' => 'Monthly driver working details and financial summary.',
                'template_key' => 'monthly_working_sheet_report',
                'filename_prefix' => 'Working-Sheet',
                'document_type' => 'working_sheet',
            ],

            'salary_slip' => [
                'key' => 'salary_slip',
                'title' => 'Monthly Salary Slip',
                'description' => 'Confidential monthly driver salary statement.',
                'template_key' => 'monthly_salary_slip',
                'filename_prefix' => 'Salary-Slip',
                'document_type' => 'salary_slip',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | INITIALIZE DOCUMENT DATA
        |--------------------------------------------------------------------------
        */

        $pdfUrls = [
            'attendance' => null,
            'working_sheet' => null,
            'salary_slip' => null,
        ];

        $whatsappMessages = [
            'attendance' => null,
            'working_sheet' => null,
            'salary_slip' => null,
        ];

        $attendanceReport = null;
        $attendancePdfStored = false;
        $attendancePdfPath = null;

        $workingSheetReport = null;
        $workingSheetPdfStored = false;
        $workingSheetPdfPath = null;

        $salarySlip = null;
        $salarySlipPdfStored = false;
        $salarySlipPdfPath = null;

        /*
        |--------------------------------------------------------------------------
        | GENERATE DOCUMENTS FOR SELECTED DRIVER
        |--------------------------------------------------------------------------
        */

        if ($selectedDriver instanceof Driver) {
            $driverId = (int) $selectedDriver->id;
            $year = (int) $monthDate->format('Y');
            $monthNumber = (int) $monthDate->format('m');

            $monthName = $monthDate->format('F');
            $yearText = $monthDate->format('Y');

            $driverName = $this->getDriverName($selectedDriver);

            /*
            |--------------------------------------------------------------------------
            | 1. ATTENDANCE REPORT
            |--------------------------------------------------------------------------
            */

            $attendanceReport = $this->attendanceReportService->getReport([
                'driver_id' => $driverId,
                'month' => $selectedMonth,
            ]);

            $attendanceFilename = sprintf(
                'Attendance-Driver-%d-%s.pdf',
                $driverId,
                $selectedMonth
            );

            $attendancePdfContent = Pdf::loadView(
                'backend.whatsapp.driver-documents.pdf.attendance',
                [
                    'attendanceReport' => $attendanceReport,
                    'selectedDriver' => $selectedDriver,
                    'monthDate' => $monthDate,
                ]
            )
                ->setPaper('a4', 'landscape')
                ->output();

            $attendanceStoredDocument = $this->whatsappMediaService
                ->storeGeneratedPdf(
                    $attendancePdfContent,
                    'attendance',
                    $driverId,
                    $year,
                    $monthNumber,
                    $attendanceFilename
                );

            $attendancePdfStored = (bool) (
                $attendanceStoredDocument['stored'] ?? false
            );

            abort_unless(
                $attendancePdfStored,
                500,
                'Attendance PDF could not be generated or stored.'
            );

            $attendanceDirectory = $this->whatsappMediaService
                ->getDocumentDirectory(
                    'attendance',
                    $year,
                    $monthNumber
                );

            $attendancePdfPath = $attendanceDirectory
                . DIRECTORY_SEPARATOR
                . $attendanceFilename;

            abort_unless(
                is_file($attendancePdfPath),
                500,
                'Generated Attendance PDF could not be located.'
            );

            $pdfUrls['attendance'] = $this->createDocumentSignedUrl(
                'driver-document-sharing.attendance-pdf',
                $selectedDriver,
                $selectedMonth
            );

            $whatsappMessages['attendance'] = $this->buildDocumentPreviewMessage(
                'monthly_attendance_report',
                $driverName,
                $monthName,
                $yearText,
                $attendancePdfPath,
                $attendanceFilename,
                'Attendance',
                $pdfUrls['attendance']
            );

            /*
            |--------------------------------------------------------------------------
            | 2. WORKING SHEET REPORT
            |--------------------------------------------------------------------------
            */

            $workingSheetReport = $this->workingSheetReportService->getReport([
                'driver_id' => $driverId,
                'month' => $selectedMonth,
            ]);

            $workingSheetFilename = sprintf(
                'Working-Sheet-Driver-%d-%s.pdf',
                $driverId,
                $selectedMonth
            );

            $workingSheetPdfContent = Pdf::loadView(
                'backend.whatsapp.driver-documents.pdf.working-sheet',
                [
                    'workingSheetReport' => $workingSheetReport,
                    'selectedDriver' => $selectedDriver,
                    'monthDate' => $monthDate,
                ]
            )
                ->setPaper('a4', 'landscape')
                ->output();

            $workingSheetStoredDocument = $this->whatsappMediaService
                ->storeGeneratedPdf(
                    $workingSheetPdfContent,
                    'working_sheet',
                    $driverId,
                    $year,
                    $monthNumber,
                    $workingSheetFilename
                );

            $workingSheetPdfStored = (bool) (
                $workingSheetStoredDocument['stored'] ?? false
            );

            abort_unless(
                $workingSheetPdfStored,
                500,
                'Working Sheet PDF could not be generated or stored.'
            );

            $workingSheetDirectory = $this->whatsappMediaService
                ->getDocumentDirectory(
                    'working_sheet',
                    $year,
                    $monthNumber
                );

            $workingSheetPdfPath = $workingSheetDirectory
                . DIRECTORY_SEPARATOR
                . $workingSheetFilename;

            abort_unless(
                is_file($workingSheetPdfPath),
                500,
                'Generated Working Sheet PDF could not be located.'
            );

            $pdfUrls['working_sheet'] = $this->createDocumentSignedUrl(
                'driver-document-sharing.working-sheet-pdf',
                $selectedDriver,
                $selectedMonth
            );

            $whatsappMessages['working_sheet'] = $this->buildDocumentPreviewMessage(
                'monthly_working_sheet_report',
                $driverName,
                $monthName,
                $yearText,
                $workingSheetPdfPath,
                $workingSheetFilename,
                'Working Sheet',
                $pdfUrls['working_sheet']
            );

            /*
            |--------------------------------------------------------------------------
            | 3. SALARY SLIP
            |--------------------------------------------------------------------------
            |
            | Use the existing generated SalarySlip record.
            | Do not calculate salary again in this controller.
            |--------------------------------------------------------------------------
            */

            $salarySlip = SalarySlip::query()
                ->with([
                    'driver',
                ])
                ->where('driver_id', $driverId)
                ->where('salary_month', $monthNumber)
                ->where('salary_year', $year)
                ->where('status', '!=', SalarySlip::STATUS_CANCELLED)
                ->latest('id')
                ->first();

            /*
             * A Salary Slip may not exist for every month.
             * In that case, keep the card available but do not generate
             * a PDF or a WhatsApp preview.
             */
            if ($salarySlip instanceof SalarySlip) {
                $salarySlipFilename = sprintf(
                    'Salary-Slip-Driver-%d-%s.pdf',
                    $driverId,
                    $selectedMonth
                );

                $salarySlipPdfContent = Pdf::loadView(
                    'backend.whatsapp.driver-documents.pdf.salary-slip',
                    [
                        'salarySlip' => $salarySlip,
                    ]
                )
                    ->setPaper('a4', 'portrait')
                    ->output();

                $salarySlipStoredDocument = $this->whatsappMediaService
                    ->storeGeneratedPdf(
                        $salarySlipPdfContent,
                        'salary_slip',
                        $driverId,
                        $year,
                        $monthNumber,
                        $salarySlipFilename
                    );

                $salarySlipPdfStored = (bool) (
                    $salarySlipStoredDocument['stored'] ?? false
                );

                abort_unless(
                    $salarySlipPdfStored,
                    500,
                    'Salary Slip PDF could not be generated or stored.'
                );

                $salarySlipDirectory = $this->whatsappMediaService
                    ->getDocumentDirectory(
                        'salary_slip',
                        $year,
                        $monthNumber
                    );

                $salarySlipPdfPath = $salarySlipDirectory
                    . DIRECTORY_SEPARATOR
                    . $salarySlipFilename;

                abort_unless(
                    is_file($salarySlipPdfPath),
                    500,
                    'Generated Salary Slip PDF could not be located.'
                );

                $pdfUrls['salary_slip'] = $this->createDocumentSignedUrl(
                    'driver-document-sharing.salary-slip-pdf',
                    $selectedDriver,
                    $selectedMonth
                );

                $whatsappMessages['salary_slip'] = $this->buildDocumentPreviewMessage(
                    'monthly_salary_slip',
                    $driverName,
                    $monthName,
                    $yearText,
                    $salarySlipPdfPath,
                    $salarySlipFilename,
                    'Salary Slip',
                    $pdfUrls['salary_slip']
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | WHATSAPP MODE
        |--------------------------------------------------------------------------
        */

        $whatsappMode = strtolower(
            (string) config('services.whatsapp.mode', 'local')
        );

        abort_unless(
            in_array($whatsappMode, ['local', 'live'], true),
            500,
            'Invalid WhatsApp mode configuration.'
        );

        $whatsappSharingMode = $whatsappMode === 'live'
            ? 'cloud_api'
            : 'personal';

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.whatsapp.driver-documents.index',
            compact(
                'user',
                'drivers',
                'selectedDriver',
                'selectedMonth',
                'monthDate',
                'documentTypes',
                'filters',
                'whatsappMode',
                'whatsappSharingMode',
                'driverPhone',
                'normalizedDriverPhone',
                'isValidDriverWhatsAppNumber',
                'pdfUrls',
                'whatsappMessages',
                'attendanceReport',
                'attendancePdfStored',
                'attendancePdfPath',
                'workingSheetReport',
                'workingSheetPdfStored',
                'workingSheetPdfPath',
                'salarySlip',
                'salarySlipPdfStored',
                'salarySlipPdfPath'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | WHATSAPP DOCUMENT DISPATCH
    |--------------------------------------------------------------------------
    */

    /**
     * Dispatch Attendance Report.
     */
    public function dispatchAttendanceReport(
        Request $request
    ): RedirectResponse {
        return $this->dispatchDriverDocument(
            $request,
            'attendance',
            'monthly_attendance_report',
            'Attendance',
            'driver-document-sharing.attendance-pdf'
        );
    }

    /**
     * Dispatch Working Sheet Report.
     */
    public function dispatchWorkingSheetReport(
        Request $request
    ): RedirectResponse {
        return $this->dispatchDriverDocument(
            $request,
            'working_sheet',
            'monthly_working_sheet_report',
            'Working Sheet',
            'driver-document-sharing.working-sheet-pdf'
        );
    }

    /**
     * Dispatch Salary Slip.
     */
    public function dispatchSalarySlip(
        Request $request
    ): RedirectResponse {
        return $this->dispatchDriverDocument(
            $request,
            'salary_slip',
            'monthly_salary_slip',
            'Salary Slip',
            'driver-document-sharing.salary-slip-pdf'
        );
    }

    /**
     * Shared dispatch handler for all three document types.
     *
     * Local:
     * - Creates personal_chat log.
     * - Generates wa.me URL.
     * - Does not mark the message as sent.
     *
     * Live:
     * - Uses WhatsAppMasterService.
     * - Sends the PDF through Cloud API.
     * - Does not fall back to Personal WhatsApp.
     */
    private function dispatchDriverDocument(
        Request $request,
        string $documentType,
        string $templateKey,
        string $documentLabel,
        string $downloadRoute
    ): RedirectResponse {
        $user = $this->authorizeAdminAccess();

        $validated = $request->validate([
            'driver_id' => [
                'required',
                'integer',
                'exists:drivers,id',
            ],
            'month' => [
                'required',
                'date_format:Y-m',
            ],
        ]);

        $selectedMonth = $validated['month'];

        $monthDate = $this->resolveDocumentMonth($selectedMonth);

        $driver = Driver::query()
            ->with('user:id,name,phone')
            ->findOrFail((int) $validated['driver_id']);

        $recipient = $this->normalizeIndianMobileNumber(
            $driver->user?->phone
        );

        if ($recipient === null) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'The selected driver does not have a valid Indian WhatsApp mobile number.'
                );
        }

        $driverId = (int) $driver->id;
        $year = (int) $monthDate->format('Y');
        $monthNumber = (int) $monthDate->format('m');

        $filenamePrefixes = [
            'attendance' => 'Attendance',
            'working_sheet' => 'Working-Sheet',
            'salary_slip' => 'Salary-Slip',
        ];

        abort_unless(
            isset($filenamePrefixes[$documentType]),
            400,
            'Invalid document type.'
        );

        $filename = sprintf(
            '%s-Driver-%d-%s.pdf',
            $filenamePrefixes[$documentType],
            $driverId,
            $selectedMonth
        );

        $directory = $this->whatsappMediaService->getDocumentDirectory(
            $documentType,
            $year,
            $monthNumber
        );

        $pdfPath = $directory . DIRECTORY_SEPARATOR . $filename;

        abort_unless(
            is_file($pdfPath),
            404,
            $documentLabel . ' PDF was not found. Please open the document page and generate it again.'
        );

        /*
         * Validate the private PDF before dispatch.
         */
        $document = $this->whatsappMediaService->validateDocument(
            $pdfPath,
            $filename
        );

        $pdfPath = $document['absolute_path'];
        $filename = $document['filename'];

        /*
         * Salary Slip must still exist and must not be cancelled.
         * Prevent dispatching a stale PDF after its record is removed
         * or cancelled.
         */
        if ($documentType === 'salary_slip') {
            $salarySlipExists = SalarySlip::query()
                ->where('driver_id', $driverId)
                ->where('salary_month', $monthNumber)
                ->where('salary_year', $year)
                ->where('status', '!=', SalarySlip::STATUS_CANCELLED)
                ->exists();

            abort_unless(
                $salarySlipExists,
                404,
                'A valid Salary Slip was not found for the selected driver and month.'
            );
        }

        $driverName = $this->getDriverName($driver);

        $monthName = $monthDate->format('F');
        $yearText = $monthDate->format('Y');

        $variables = [
            'driver_name' => $driverName,
            'month' => $monthName,
            'year' => $yearText,
        ];

        $whatsappMode = strtolower(
            (string) config('services.whatsapp.mode', 'local')
        );

        abort_unless(
            in_array($whatsappMode, ['local', 'live'], true),
            500,
            'Invalid WhatsApp mode configuration.'
        );

        /*
        |--------------------------------------------------------------------------
        | LOCAL MODE: PERSONAL WHATSAPP
        |--------------------------------------------------------------------------
        */

        if ($whatsappMode === 'local') {
            $documentDetails = [
                'path' => $pdfPath,
                'filename' => $filename,
            ];

            $preview = $this->whatsappTemplateService->renderPreview(
                $templateKey,
                $variables,
                $documentDetails
            );

            $pdfUrl = $this->createDocumentSignedUrl(
                $downloadRoute,
                $driver,
                $selectedMonth
            );

            $message = $this->appendSecurePdfLinkToMessage(
                (string) ($preview['message'] ?? ''),
                $documentLabel,
                $pdfUrl
            );

            /*
             * This log means only that the Personal WhatsApp link
             * was generated. It does not mean the message was sent.
             */
            $this->whatsappLogService->create([
                'driver_id' => $driverId,
                'created_by' => (int) $user->id,
                'recipient' => $recipient,
                'template_key' => $templateKey,
                'mode' => 'local',
                'channel' => 'personal_chat',
                'status' => 'chat_link_generated',
                'variables' => $variables,
                'document_path' => $pdfPath,
                'document_filename' => $filename,
            ]);

            $chatUrl = 'https://wa.me/'
                . $recipient
                . '?text='
                . rawurlencode($message);

            return redirect()->away($chatUrl);
        }

        /*
        |--------------------------------------------------------------------------
        | LIVE MODE: WHATSAPP CLOUD API
        |--------------------------------------------------------------------------
        |
        | sendTemplate() handles the existing MasterService workflow:
        | - Template validation
        | - Document media upload
        | - Cloud API document message
        | - WhatsApp log handling
        |
        | No Personal WhatsApp fallback is allowed.
        |--------------------------------------------------------------------------
        */

        try {
            $result = $this->whatsappMasterService->sendTemplate(
                $recipient,
                $templateKey,
                $variables,
                $pdfPath,
                $filename,
                (int) $user->id
            );

            if (!($result['sent'] ?? false)) {
                $error = $result['message']
                    ?? $result['error']
                    ?? $documentLabel . ' could not be sent through WhatsApp Cloud API.';

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', $error);
            }

            return redirect()
                ->back()
                ->with(
                    'success',
                    $documentLabel . ' was submitted successfully through WhatsApp Cloud API.'
                );
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    $documentLabel . ' could not be sent through WhatsApp Cloud API. Please check the WhatsApp dispatch history.'
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SECURE PDF DOWNLOAD HANDLERS
    |--------------------------------------------------------------------------
    */

    /**
     * Download Attendance PDF through a temporary signed URL.
     */
    public function downloadAttendancePdf(
        Request $request,
        Driver $driver,
        string $month
    ): BinaryFileResponse {
        return $this->downloadPrivateDriverDocument(
            $request,
            $driver,
            $month,
            'attendance',
            'Attendance'
        );
    }

    /**
     * Download Working Sheet PDF through a temporary signed URL.
     */
    public function downloadWorkingSheetPdf(
        Request $request,
        Driver $driver,
        string $month
    ): BinaryFileResponse {
        return $this->downloadPrivateDriverDocument(
            $request,
            $driver,
            $month,
            'working_sheet',
            'Working-Sheet'
        );
    }

    /**
     * Download Salary Slip PDF through a temporary signed URL.
     */
    public function downloadSalarySlipPdf(
        Request $request,
        Driver $driver,
        string $month
    ): BinaryFileResponse {
        return $this->downloadPrivateDriverDocument(
            $request,
            $driver,
            $month,
            'salary_slip',
            'Salary-Slip'
        );
    }

    /**
     * Shared private PDF download handler.
     */
    private function downloadPrivateDriverDocument(
        Request $request,
        Driver $driver,
        string $month,
        string $documentType,
        string $filenamePrefix
    ): BinaryFileResponse {
        abort_unless(
            $request->hasValidSignature(),
            403,
            'This document link is invalid or has expired.'
        );

        $monthDate = $this->resolveDocumentMonth($month);

        $filename = sprintf(
            '%s-Driver-%d-%s.pdf',
            $filenamePrefix,
            (int) $driver->id,
            $month
        );

        $directory = $this->whatsappMediaService->getDocumentDirectory(
            $documentType,
            (int) $monthDate->format('Y'),
            (int) $monthDate->format('m')
        );

        $pdfPath = $directory . DIRECTORY_SEPARATOR . $filename;

        abort_unless(
            is_file($pdfPath),
            404,
            'PDF was not found. Please ask the Admin to generate it again.'
        );

        $document = $this->whatsappMediaService->validateDocument(
            $pdfPath,
            $filename
        );

        return response()->download(
            $document['absolute_path'],
            $document['filename'],
            [
                'Content-Type' => 'application/pdf',
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store, max-age=0',
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DOCUMENT DISPATCH HISTORY
    |--------------------------------------------------------------------------
    */

    /**
     * Display WhatsApp Document Dispatch History.
     */
    public function history(Request $request): View
    {
        $user = $this->authorizeAdminAccess();

        $templates = $this->whatsappTemplateService->getTemplates();

        $filters = $request->validate([
            'driver_id' => [
                'nullable',
                'integer',
                'exists:drivers,id',
            ],
            'recipient' => [
                'nullable',
                'string',
                'max:20',
            ],
            'template_key' => [
                'nullable',
                Rule::in(array_keys($templates)),
            ],
            'mode' => [
                'nullable',
                Rule::in([
                    'local',
                    'live',
                ]),
            ],
            'channel' => [
                'nullable',
                Rule::in([
                    'personal_chat',
                    'cloud_api',
                ]),
            ],
            'status' => [
                'nullable',
                Rule::in([
                    'pending',
                    'local_test',
                    'chat_link_generated',
                    'sent',
                    'delivered',
                    'read',
                    'failed',
                ]),
            ],
            'date_from' => [
                'nullable',
                'date_format:Y-m-d',
            ],
            'date_to' => [
                'nullable',
                'date_format:Y-m-d',
                'after_or_equal:date_from',
            ],
            'per_page' => [
                'nullable',
                'integer',
                Rule::in([
                    10,
                    25,
                    50,
                    100,
                ]),
            ],
        ]);

        $perPage = (int) ($filters['per_page'] ?? 25);

        unset($filters['per_page']);

        $logs = $this->whatsappLogService->getHistory(
            $filters,
            $perPage
        );

        $drivers = Driver::query()
            ->select([
                'id',
                'driver_code',
                'first_name',
                'last_name',
            ])
            ->orderBy('driver_code')
            ->get();

        $statusOptions = [
            'pending' => 'Pending',
            'local_test' => 'Local Test',
            'chat_link_generated' => 'Chat Link Generated',
            'sent' => 'Sent',
            'delivered' => 'Delivered',
            'read' => 'Read',
            'failed' => 'Failed',
        ];

        $modeOptions = [
            'local' => 'Local Testing',
            'live' => 'Live WhatsApp',
        ];

        $channelOptions = [
            'personal_chat' => 'Personal WhatsApp',
            'cloud_api' => 'WhatsApp Cloud API',
        ];

        return view(
            'backend.whatsapp.driver-documents.history',
            compact(
                'user',
                'logs',
                'drivers',
                'templates',
                'filters',
                'statusOptions',
                'modeOptions',
                'channelOptions',
                'perPage'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DOCUMENT PREVIEW HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Generate WhatsApp preview message for a document.
     *
     * The document metadata is mandatory for templates configured
     * with requires_document = true.
     */
    private function buildDocumentPreviewMessage(
        string $templateKey,
        string $driverName,
        string $monthName,
        string $year,
        string $pdfPath,
        string $filename,
        string $documentLabel,
        string $pdfUrl
    ): string {
        $documentDetails = [
            'path' => $pdfPath,
            'filename' => $filename,
        ];

        $preview = $this->whatsappTemplateService->renderPreview(
            $templateKey,
            [
                'driver_name' => $driverName,
                'month' => $monthName,
                'year' => $year,
            ],
            $documentDetails
        );

        return $this->appendSecurePdfLinkToMessage(
            (string) ($preview['message'] ?? ''),
            $documentLabel,
            $pdfUrl
        );
    }

    /**
     * Generate a temporary signed document URL.
     */
    private function createDocumentSignedUrl(
        string $routeName,
        Driver $driver,
        string $month
    ): string {
        return URL::temporarySignedRoute(
            $routeName,
            now()->addDays(7),
            [
                'driver' => (int) $driver->id,
                'month' => $month,
            ]
        );
    }

    /**
     * Resolve Driver display name.
     */
    private function getDriverName(Driver $driver): string
    {
        $driverName = trim(
            (string) $driver->first_name
            . ' '
            . (string) $driver->last_name
        );

        if ($driverName === '') {
            $driverName = trim(
                (string) ($driver->user?->name ?? '')
            );
        }

        return $driverName !== ''
            ? $driverName
            : 'Driver';
    }

    /**
     * Add the private PDF URL to a Personal WhatsApp template preview.
     *
     * Important:
     * wa.me pre-fills the message but cannot attach the PDF itself.
     * The recipient can open the temporary signed download link.
     */
    private function appendSecurePdfLinkToMessage(
        string $message,
        string $documentLabel,
        string $pdfUrl
    ): string {
        $replacement = sprintf(
            "is ready.\n\nView / Download your %s PDF:\n%s",
            $documentLabel,
            $pdfUrl
        );

        $updatedMessage = preg_replace(
            '/is attached as a PDF\./i',
            $replacement,
            $message,
            1
        );

        if (
            !is_string($updatedMessage)
            || $updatedMessage === $message
        ) {
            $updatedMessage = rtrim($message)
                . "\n\nView / Download your "
                . $documentLabel
                . " PDF:\n"
                . $pdfUrl;
        }

        return $updatedMessage;
    }

    /*
    |--------------------------------------------------------------------------
    | MOBILE NUMBER HELPER
    |--------------------------------------------------------------------------
    */

    /**
     * Normalize and validate an Indian mobile number.
     *
     * Accepted examples:
     *
     * 9876543210
     * 09876543210
     * 919876543210
     *
     * Returns:
     * 91XXXXXXXXXX
     *
     * Invalid number returns null.
     */
    private function normalizeIndianMobileNumber(
        ?string $phone
    ): ?string {
        if ($phone === null || trim($phone) === '') {
            return null;
        }

        $number = preg_replace('/\D+/', '', $phone);

        if (!is_string($number) || $number === '') {
            return null;
        }

        if (strlen($number) === 10) {
            $number = '91' . $number;
        } elseif (
            strlen($number) === 11
            && str_starts_with($number, '0')
        ) {
            $number = '91' . substr($number, 1);
        } elseif (
            strlen($number) === 12
            && str_starts_with($number, '91')
        ) {
            // Already includes India's country code.
        } else {
            return null;
        }

        if (!preg_match('/^91[6-9][0-9]{9}$/', $number)) {
            return null;
        }

        return $number;
    }

    /*
    |--------------------------------------------------------------------------
    | DOCUMENT MONTH HELPER
    |--------------------------------------------------------------------------
    */

    /**
     * Validate and resolve a YYYY-MM document month.
     */
    private function resolveDocumentMonth(string $month): Carbon
    {
        abort_unless(
            preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month) === 1,
            404,
            'Invalid document report month.'
        );

        $monthDate = Carbon::createFromFormat('!Y-m', $month);

        abort_unless(
            $monthDate !== false
                && $monthDate->format('Y-m') === $month,
            404,
            'Invalid document report month.'
        );

        return $monthDate;
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    /**
     * Verify that only Admin can manage Driver Document Sharing.
     */
    private function authorizeAdminAccess(): User
    {
        $user = Auth::user();

        abort_unless(
            $user instanceof User,
            403,
            'Unauthenticated access.'
        );

        abort_unless(
            $user->role === User::ROLE_ADMIN,
            403,
            'You are not authorized to access Driver Document Sharing.'
        );

        return $user;
    }
}
