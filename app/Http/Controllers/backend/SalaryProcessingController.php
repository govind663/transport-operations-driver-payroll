<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SalaryProcessing\StoreSalaryProcessingRequest;
use App\Http\Requests\Backend\SalaryProcessing\UpdateSalaryProcessingRequest;
use App\Models\Driver;
use App\Models\SalaryProcessing;
use App\Models\User;
use App\Services\Reports\PayrollReport\PayrollReportService;
use App\Services\SalaryProcessing\SalaryProcessingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class SalaryProcessingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SERVICES
    |--------------------------------------------------------------------------
    */

    protected SalaryProcessingService $salaryProcessingService;

    protected PayrollReportService $payrollReportService;


    /*
    |--------------------------------------------------------------------------
    | CONSTRUCTOR
    |--------------------------------------------------------------------------
    */

    public function __construct(
        SalaryProcessingService $salaryProcessingService,
        PayrollReportService $payrollReportService
    ) {

        $this->salaryProcessingService = $salaryProcessingService;

        $this->payrollReportService = $payrollReportService;
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {

        /*
        |--------------------------------------------------------------------------
        | Auth User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | User Roles
        |--------------------------------------------------------------------------
        */

        $isAdmin = $user->isAdmin();

        $isOperations = $user->isOperations();

        $isAccountant = $user->isAccountant();

        $isDriver = $user->isDriver();


        /*
        |--------------------------------------------------------------------------
        | Salary Month / Year
        |--------------------------------------------------------------------------
        |
        | Blade sends salary_month and salary_year.
        | Retain month/year aliases for existing report service compatibility.
        |
        */

        $salaryMonth = $request->input(
            'salary_month',
            $request->input('month')
        );

        $salaryYear = $request->input(
            'salary_year',
            $request->input('year')
        );


        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $filters = [

            'search' => $request->input('search'),

            'driver_id' => $request->input('driver_id'),

            'role' => $request->input('role'),

            'month' => $salaryMonth,

            'year' => $salaryYear,

            'salary_month' => $salaryMonth,

            'salary_year' => $salaryYear,

            'status' => $request->input('status'),

            'date_from' => $request->input('date_from'),

            'date_to' => $request->input('date_to'),

            'payment_date' => $request->input('payment_date'),
        ];


        /*
        |--------------------------------------------------------------------------
        | Driver Restriction
        |--------------------------------------------------------------------------
        |
        | Driver users can see only their own payroll records.
        |
        */

        if ($isDriver) {

            $driver = Driver::query()
                ->where('user_id', $user->id)
                ->first();

            $filters['driver_id'] = $driver?->id ?? 0;
        }


        /*
        |--------------------------------------------------------------------------
        | Payroll Report
        |--------------------------------------------------------------------------
        */

        $payrollReport = $this->payrollReportService->getReport(
            $filters,
            1000000000000
        );


        /*
        |--------------------------------------------------------------------------
        | Payroll Records
        |--------------------------------------------------------------------------
        */

        $payrolls = $payrollReport;


        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $filterOptions = $this->payrollReportService
            ->getFilterOptions();


        /*
        |--------------------------------------------------------------------------
        | Driver Dropdown
        |--------------------------------------------------------------------------
        */

        if ($isDriver) {

            $driver = Driver::query()
                ->select([
                    'id',
                    'driver_code',
                    'first_name',
                    'last_name',
                    'mobile',
                ])
                ->where('user_id', $user->id)
                ->first();

            $drivers = $driver
                ? collect([$driver])
                : collect();

        } else {

            $drivers = Driver::query()
                ->select([
                    'id',
                    'driver_code',
                    'first_name',
                    'last_name',
                    'mobile',
                ])
                ->where('status', 'active')
                ->orderBy('driver_code')
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Add Drivers To Filter Options
        |--------------------------------------------------------------------------
        */

        $filterOptions['drivers'] = $drivers;


        /*
        |--------------------------------------------------------------------------
        | Month / Year Options
        |--------------------------------------------------------------------------
        */

        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];

        $currentMonth = (int) now()->month;

        $currentYear = (int) now()->year;


        /*
        |--------------------------------------------------------------------------
        | Payroll Generation Permission
        |--------------------------------------------------------------------------
        */

        $canGeneratePayroll =
            $isAdmin ||
            $isOperations ||
            $isAccountant;


        /*
        |--------------------------------------------------------------------------
        | Generation Result
        |--------------------------------------------------------------------------
        */

        $payrollGenerationResult = session(
            'payroll_generation_result'
        );


        /*
        |--------------------------------------------------------------------------
        | Payroll Statuses
        |--------------------------------------------------------------------------
        */

        $statuses = SalaryProcessing::STATUSES;


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.salary-processings.index',
            compact(
                'payrollReport',
                'payrolls',
                'filterOptions',
                'drivers',
                'statuses',
                'isAdmin',
                'isOperations',
                'isAccountant',
                'isDriver',
                'months',
                'currentMonth',
                'currentYear',
                'canGeneratePayroll',
                'payrollGenerationResult'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AUTOMATIC MONTHLY PAYROLL GENERATION
    |--------------------------------------------------------------------------
    |
    | Admin / Operations / Accountant selects Month and Year.
    |
    | Driver selection:
    | - Empty driver_id: generate for all eligible drivers.
    | - Selected driver_id: generate only for that driver.
    |
    | Source attendance and Working Sheet records are not modified.
    |
    */

    public function generateMonthly(
        Request $request
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Auth / Permission
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (
            !$user instanceof User ||
            (
                !$user->isAdmin() &&
                !$user->isOperations() &&
                !$user->isAccountant()
            )
        ) {

            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'salary_month' => [
                'required',
                'integer',
                'between:1,12',
            ],

            'salary_year' => [
                'required',
                'integer',
                'between:2000,2100',
            ],

            'driver_id' => [
                'nullable',
                'integer',
                'exists:drivers,id',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Generate Payroll
        |--------------------------------------------------------------------------
        */

        try {

            $result = $this->salaryProcessingService
                ->generateMonthlyPayroll(

                    (int) $validated['salary_month'],

                    (int) $validated['salary_year'],

                    !empty($validated['driver_id'])
                        ? (int) $validated['driver_id']
                        : null
                );


            /*
            |--------------------------------------------------------------------------
            | Generation Summary
            |--------------------------------------------------------------------------
            */

            $generatedCount = (int) (
                $result['generated_count'] ?? 0
            );

            $skippedCount = (int) (
                $result['skipped_count'] ?? 0
            );

            $skipped = collect(
                $result['skipped'] ?? []
            )
                ->take(50)
                ->values()
                ->all();


            /*
            |--------------------------------------------------------------------------
            | Success Message
            |--------------------------------------------------------------------------
            */

            $message = sprintf(
                'Monthly payroll generation completed: %d generated, %d skipped.',
                $generatedCount,
                $skippedCount
            );


            /*
            |--------------------------------------------------------------------------
            | Redirect
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('salary-processing.index', [

                    'salary_month' => $validated['salary_month'],

                    'salary_year' => $validated['salary_year'],

                ])
                ->with('message', $message)
                ->with('payroll_generation_result', [

                    'generated_count' => $generatedCount,

                    'skipped_count' => $skippedCount,

                    'skipped' => $skipped,

                ]);

        } catch (
            InvalidArgumentException |
            RuntimeException $exception
        ) {

            return back()
                ->withInput()
                ->with('error', $exception->getMessage());

        } catch (Throwable $exception) {

            Log::error(
                'Monthly payroll generation failed.',
                [

                    'user_id' => $user->id,

                    'salary_month' => $validated['salary_month'],

                    'salary_year' => $validated['salary_year'],

                    'driver_id' => $validated['driver_id'] ?? null,

                    'message' => $exception->getMessage(),

                    'exception' => $exception,

                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to generate monthly payroll. Please check the application log.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {

        /*
        |--------------------------------------------------------------------------
        | Auth User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

        $isDriver = $user->isDriver();


        /*
        |--------------------------------------------------------------------------
        | Driver List
        |--------------------------------------------------------------------------
        */

        if ($isDriver) {

            $drivers = Driver::query()
                ->where('user_id', $user->id)
                ->orderBy('driver_code')
                ->get();

        } else {

            $drivers = Driver::query()
                ->orderBy('driver_code')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Existing Payroll Records
        |--------------------------------------------------------------------------
        */

        if ($isDriver) {

            $driver = $drivers->first();

            $salaryProcessings = $driver

                ? SalaryProcessing::query()
                    ->where('driver_id', $driver->id)
                    ->with([
                        'driver',
                        'processedBy',
                        'approvedBy',
                        'paidBy',
                    ])
                    ->latest('salary_year')
                    ->latest('salary_month')
                    ->latest('id')
                    ->get()

                : collect();

        } else {

            $salaryProcessings = SalaryProcessing::query()
                ->with([
                    'driver',
                    'processedBy',
                    'approvedBy',
                    'paidBy',
                ])
                ->latest('salary_year')
                ->latest('salary_month')
                ->latest('id')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Status Options
        |--------------------------------------------------------------------------
        */

        $statuses = SalaryProcessing::STATUSES;


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.salary-processings.create',
            compact(
                'drivers',
                'salaryProcessings',
                'statuses',
                'isDriver'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreSalaryProcessingRequest $request
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Auth User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Validated Data
        |--------------------------------------------------------------------------
        */

        $data = $request->validated();


        /*
        |--------------------------------------------------------------------------
        | Driver Restriction
        |--------------------------------------------------------------------------
        */

        if ($user->isDriver()) {

            $driver = Driver::query()
                ->where('user_id', $user->id)
                ->first();

            if (
                !$driver ||
                !isset($data['driver_id']) ||
                (int) $data['driver_id'] !== (int) $driver->id
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'You can only process your own salary.'
                    );
            }

            $data['driver_id'] = $driver->id;
        }


        /*
        |--------------------------------------------------------------------------
        | Initial Payroll Status
        |--------------------------------------------------------------------------
        |
        | New payroll can only be Draft or Processed.
        | Approval and payment must use their lifecycle operations.
        |
        */

        $data['status'] = $data['status']
            ?? SalaryProcessing::STATUS_PROCESSED;

        if (!in_array(
            $data['status'],
            [
                SalaryProcessing::STATUS_DRAFT,
                SalaryProcessing::STATUS_PROCESSED,
            ],
            true
        )) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'New payroll can only be created as Draft or Processed.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | System Controlled Fields
        |--------------------------------------------------------------------------
        */

        $data['role'] = SalaryProcessing::ROLE_DRIVER;

        unset(
            $data['id'],
            $data['processing_no'],
            $data['processed_by'],
            $data['approved_by'],
            $data['paid_by'],
            $data['created_by'],
            $data['updated_by'],
            $data['deleted_by']
        );


        /*
        |--------------------------------------------------------------------------
        | Create Through Service
        |--------------------------------------------------------------------------
        */

        try {

            $salaryProcessing = $this->salaryProcessingService
                ->create($data);


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('salary-processing.index')
                ->with(
                    'message',
                    'Salary processing created successfully. Processing No: '
                    . $salaryProcessing->processing_no
                );

        } catch (
            InvalidArgumentException |
            RuntimeException $exception
        ) {

            return back()
                ->withInput()
                ->with('error', $exception->getMessage());

        } catch (Throwable $exception) {

            Log::error(
                'Salary processing creation failed.',
                [

                    'user_id' => $user->id,

                    'driver_id' => $data['driver_id'] ?? null,

                    'salary_month' => $data['salary_month'] ?? null,

                    'salary_year' => $data['salary_year'] ?? null,

                    'message' => $exception->getMessage(),

                    'exception' => $exception,

                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to create salary processing. Please check the application log.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        SalaryProcessing $salaryProcessing
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Auth User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Driver Access Restriction
        |--------------------------------------------------------------------------
        */

        if ($user->isDriver()) {

            $driver = Driver::query()
                ->where('user_id', $user->id)
                ->first();

            if (
                !$driver ||
                (int) $salaryProcessing->driver_id !== (int) $driver->id
            ) {

                abort(403);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Load Relationships
        |--------------------------------------------------------------------------
        */

        $salaryProcessing->load([
            'driver',
            'processedBy',
            'approvedBy',
            'paidBy',
            'createdBy',
            'updatedBy',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Processing Number
        |--------------------------------------------------------------------------
        */

        if (empty($salaryProcessing->processing_no)) {

            Log::warning(
                'Salary Processing Number is missing.',
                [
                    'salary_processing_id' => $salaryProcessing->id,
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.salary-processings.show',
            compact('salaryProcessing')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        SalaryProcessing $salaryProcessing
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Auth User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Driver Restriction
        |--------------------------------------------------------------------------
        |
        | Drivers can view their payroll, but cannot edit it.
        |
        */

        if ($user->isDriver()) {

            abort(
                403,
                'You are not allowed to edit salary processing.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Load Payroll Relationships
        |--------------------------------------------------------------------------
        */

        $salaryProcessing->load([
            'driver',
            'processedBy',
            'approvedBy',
            'paidBy',
            'createdBy',
            'updatedBy',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Driver List
        |--------------------------------------------------------------------------
        */

        $drivers = Driver::query()
            ->orderBy('driver_code')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Existing Payroll Records
        |--------------------------------------------------------------------------
        */

        $salaryProcessings = SalaryProcessing::query()
            ->with([
                'driver',
                'processedBy',
                'approvedBy',
                'paidBy',
            ])
            ->latest('salary_year')
            ->latest('salary_month')
            ->latest('id')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Status Options
        |--------------------------------------------------------------------------
        */

        $statuses = SalaryProcessing::STATUSES;


        /*
        |--------------------------------------------------------------------------
        | Payroll Lock Status
        |--------------------------------------------------------------------------
        */

        $isLocked = $this->isPayrollLocked(
            $salaryProcessing
        );


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.salary-processings.edit',
            compact(
                'salaryProcessing',
                'drivers',
                'salaryProcessings',
                'statuses',
                'isLocked'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateSalaryProcessingRequest $request,
        SalaryProcessing $salaryProcessing
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Auth User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Driver Restriction
        |--------------------------------------------------------------------------
        */

        if ($user->isDriver()) {

            abort(
                403,
                'You are not allowed to update salary processing.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Payroll Lock Protection
        |--------------------------------------------------------------------------
        |
        | Approved, Paid and Cancelled payroll cannot be edited.
        | This check is enforced on the backend as well as Blade.
        |
        */

        if ($this->isPayrollLocked($salaryProcessing)) {

            return redirect()
                ->route(
                    'salary-processing.edit',
                    $salaryProcessing->id
                )
                ->with(
                    'error',
                    'Approved, paid or cancelled payroll cannot be edited.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Validated Data
        |--------------------------------------------------------------------------
        */

        $data = $request->validated();


        /*
        |--------------------------------------------------------------------------
        | System Controlled Fields
        |--------------------------------------------------------------------------
        */

        $data['role'] = SalaryProcessing::ROLE_DRIVER;


        /*
        |--------------------------------------------------------------------------
        | Preserve Existing Status When Not Submitted
        |--------------------------------------------------------------------------
        */

        $data['status'] = $data['status']
            ?? $salaryProcessing->status;


        /*
        |--------------------------------------------------------------------------
        | Protect Processing Number
        |--------------------------------------------------------------------------
        |
        | Processing Number is generated by the SalaryProcessing model.
        | It must never be accepted from the Edit form.
        |
        */

        unset(
            $data['id'],
            $data['processing_no'],
            $data['processed_by'],
            $data['approved_by'],
            $data['paid_by'],
            $data['created_by'],
            $data['deleted_by'],
            $data['gross_salary'],
            $data['total_deductions'],
            $data['net_salary']
        );


        /*
        |--------------------------------------------------------------------------
        | Update Through Service
        |--------------------------------------------------------------------------
        */

        try {

            $updatedPayroll = $this->salaryProcessingService->update(
                $salaryProcessing,
                $data
            );


            /*
            |--------------------------------------------------------------------------
            | Success Message
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('salary-processing.index')
                ->with(
                    'message',
                    'Salary processing updated successfully. Processing No: '
                    . $updatedPayroll->processing_no
                );

        } catch (
            InvalidArgumentException |
            RuntimeException $exception
        ) {

            return back()
                ->withInput()
                ->with('error', $exception->getMessage());

        } catch (Throwable $exception) {

            Log::error(
                'Salary processing update failed.',
                [

                    'salary_processing_id' => $salaryProcessing->id,

                    'processing_no' => $salaryProcessing->processing_no,

                    'user_id' => $user->id,

                    'status' => $data['status'] ?? null,

                    'message' => $exception->getMessage(),

                    'exception' => $exception,

                ]
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to update salary processing. Please check the application log.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        SalaryProcessing $salaryProcessing
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Auth User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Driver Restriction
        |--------------------------------------------------------------------------
        */

        if ($user->isDriver()) {

            abort(
                403,
                'You are not allowed to delete salary processing.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Through Service
        |--------------------------------------------------------------------------
        */

        try {

            $processingNo = $salaryProcessing->processing_no;

            $this->salaryProcessingService->delete(
                $salaryProcessing
            );


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('salary-processing.index')
                ->with(
                    'message',
                    'Salary processing deleted successfully. Processing No: '
                    . $processingNo
                );

        } catch (RuntimeException $exception) {

            return back()
                ->with('error', $exception->getMessage());

        } catch (Throwable $exception) {

            Log::error(
                'Salary processing deletion failed.',
                [

                    'salary_processing_id' => $salaryProcessing->id,

                    'processing_no' => $salaryProcessing->processing_no,

                    'user_id' => $user->id,

                    'message' => $exception->getMessage(),

                    'exception' => $exception,

                ]
            );

            return back()
                ->with(
                    'error',
                    'Unable to delete salary processing. Please check the application log.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK PAYROLL LOCK
    |--------------------------------------------------------------------------
    |
    | Locked:
    | - approved
    | - paid
    | - cancelled
    |
    | Editable:
    | - draft
    | - processed
    |
    */

    private function isPayrollLocked(
        SalaryProcessing $salaryProcessing
    ): bool {

        return in_array(
            $salaryProcessing->status,
            [
                SalaryProcessing::STATUS_APPROVED,
                SalaryProcessing::STATUS_PAID,
                SalaryProcessing::STATUS_CANCELLED,
            ],
            true
        );
    }
}
