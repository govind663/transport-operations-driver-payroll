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
use RuntimeException;
use Throwable;

class SalaryProcessingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
    */

    protected SalaryProcessingService $salaryProcessingService;

    protected PayrollReportService $payrollReportService;


    /*
    |--------------------------------------------------------------------------
    | Constructor
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
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        $isAdmin = $user->isAdmin();

        $isOperations = $user->isOperations();

        $isAccountant = $user->isAccountant();

        $isDriver = $user->isDriver();


        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        $filters = [
            'search' => $request->input('search'),
            'driver_id' => $request->input('driver_id'),
            'role' => $request->input('role'),
            'month' => $request->input('month'),
            'year' => $request->input('year'),
            'status' => $request->input('status'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
        ];


        /*
        |--------------------------------------------------------------------------
        | Driver Restriction
        |--------------------------------------------------------------------------
        |
        | Driver users can only see their own payroll records.
        |
        */

        if ($isDriver) {
            $driver = Driver::query()
                ->where('user_id', $user->id)
                ->first();

            // If no driver profile is linked, return an empty result.
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
        |
        | Keep payrollReport available for existing report data and expose
        | payrolls for the existing Blade listing.
        |
        */

        $payrolls = $payrollReport;


        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $filterOptions = $this->payrollReportService->getFilterOptions();


        /*
        |--------------------------------------------------------------------------
        | Driver Dropdown
        |--------------------------------------------------------------------------
        |
        | Driver users should not see the complete driver list.
        |
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

            $drivers = $driver ? collect([$driver]) : collect();
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
        | Month / Year Options For Automatic Payroll
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

        // Payroll generation is limited to Admin, Operations and Accountant.
        $canGeneratePayroll = $isAdmin || $isOperations || $isAccountant;

        $payrollGenerationResult = session('payroll_generation_result');

        $statuses = SalaryProcessing::STATUSES;

        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */
        return view('backend.salary-processings.index',
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
    | Driver selection is optional:
    | - Empty driver_id: generate payroll for all eligible drivers.
    | - Selected driver_id: generate payroll for that driver only.
    |
    | The service reads attendance and approved/completed Working Sheets.
    | This controller does not create or modify those source records.
    |
    */
    public function generateMonthly(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (
            !$user instanceof User ||
            (!$user->isAdmin() && !$user->isOperations() && !$user->isAccountant())
        ) {
            abort(403);
        }

        $validated = $request->validate([
            'salary_month' => ['required', 'integer', 'between:1,12'],
            'salary_year' => ['required', 'integer', 'between:2000,2100'],
            'driver_id' => ['nullable', 'integer', 'exists:drivers,id'],
        ]);

        try {
            $result = $this->salaryProcessingService->generateMonthlyPayroll(
                (int) $validated['salary_month'],
                (int) $validated['salary_year'],
                !empty($validated['driver_id'])
                    ? (int) $validated['driver_id']
                    : null
            );

            $generatedCount = (int) ($result['generated_count'] ?? 0);

            $skippedCount = (int) ($result['skipped_count'] ?? 0);

            $skipped = collect($result['skipped'] ?? [])
                ->take(50)
                ->values()
                ->all();

            $message = sprintf(
                'Monthly payroll generation completed: %d generated, %d skipped.',
                $generatedCount,
                $skippedCount
            );

            return redirect()
                ->route('salary-processing.index', [
                    'month' => $validated['salary_month'],
                    'year' => $validated['salary_year'],
                ])
                ->with('message', $message)
                ->with('payroll_generation_result', [
                    'generated_count' => $generatedCount,
                    'skipped_count' => $skippedCount,
                    'skipped' => $skipped,
                ]);
        } catch (RuntimeException $exception) {
            return back()
                ->withInput()
                ->with('error', $exception->getMessage());
        } catch (Throwable $exception) {
            Log::error('Monthly payroll generation failed.', [
                'user_id' => $user->id,
                'salary_month' => $validated['salary_month'],
                'salary_year' => $validated['salary_year'],
                'driver_id' => $validated['driver_id'] ?? null,
                'message' => $exception->getMessage(),
                'exception' => $exception,
            ]);

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
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

        $isDriver = $user->isDriver();

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

        $statuses = SalaryProcessing::STATUSES;

        return view('backend.salary-processings.create',
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
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

        $data = $request->validated();

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
                    ->with('error', 'You can only process your own salary.');
            }

            $data['driver_id'] = $driver->id;
        }

        $data['role'] = SalaryProcessing::ROLE_DRIVER;
        $data['processed_by'] = $user->id;
        $data['status'] = $data['status'] ?? SalaryProcessing::STATUS_PROCESSED;

        $this->salaryProcessingService->create($data);

        return redirect()
            ->route('salary-processing.index')
            ->with('message', 'Salary processed successfully.');
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show(
        SalaryProcessing $salaryProcessing
    ): View {
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

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

        $salaryProcessing->load([
            'driver',
            'processedBy',
            'approvedBy',
            'paidBy',
        ]);

        return view('backend.salary-processings.show',
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
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

        // Drivers are allowed to view their own payroll, not edit it.
        if ($user->isDriver()) {
            abort(403, 'You are not allowed to edit salary processing.');
        }

        $drivers = Driver::query()
            ->orderBy('driver_code')
            ->get();

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

        $statuses = SalaryProcessing::STATUSES;

        return view('backend.salary-processings.edit',
            compact(
                'salaryProcessing',
                'drivers',
                'salaryProcessings',
                'statuses'
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
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

        // Drivers cannot update payroll records.
        if ($user->isDriver()) {
            abort(403, 'You are not allowed to update salary processing.');
        }

        $data = $request->validated();

        $data['role'] = SalaryProcessing::ROLE_DRIVER;

        $this->salaryProcessingService->update(
            $salaryProcessing,
            $data
        );

        return redirect()
            ->route('salary-processing.index')
            ->with('message', 'Salary processing updated successfully.');
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        SalaryProcessing $salaryProcessing
    ): RedirectResponse {
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

        // Drivers cannot delete payroll records.
        if ($user->isDriver()) {
            abort(403, 'You are not allowed to delete salary processing.');
        }

        $this->salaryProcessingService->delete($salaryProcessing);

        return redirect()
            ->route('salary-processing.index')
            ->with('message', 'Salary processing deleted successfully.');
    }
}
