<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SalarySlip\StoreSalarySlipRequest;
use App\Http\Requests\Backend\SalarySlip\UpdateSalarySlipRequest;
use App\Models\Driver;
use App\Models\SalaryProcessing;
use App\Models\SalarySlip;
use App\Models\User;
use App\Services\SalaryProcessing\SalaryProcessingService;
use App\Services\SalarySlip\SalarySlipService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SalarySlipController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Salary Slip Service
    |--------------------------------------------------------------------------
    */

    protected SalarySlipService $salarySlipService;

    protected SalaryProcessingService $salaryProcessingService;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        SalarySlipService $salarySlipService,
        SalaryProcessingService $salaryProcessingService
    ) {
        $this->salarySlipService = $salarySlipService;
        $this->salaryProcessingService = $salaryProcessingService;
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request
    ): View {

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
        | Filters
        |--------------------------------------------------------------------------
        */

        $filters = [

            'driver_id' =>
                $request->input('driver_id'),

            'salary_month' =>
                $request->input('salary_month'),

            'salary_year' =>
                $request->input('salary_year'),

            'status' =>
                $request->input('status'),

            'payment_status' =>
                $request->input('payment_status'),

            'payment_date' =>
                $request->input('payment_date'),

            'search' =>
                $request->input('search'),

        ];


        /*
        |--------------------------------------------------------------------------
        | Salary Slips
        |--------------------------------------------------------------------------
        */

        $salarySlips =
            $this->salarySlipService->getPaginated(
                $filters
            );


        /*
        |--------------------------------------------------------------------------
        | List Diagnostics (non-driver users only)
        |--------------------------------------------------------------------------
        |
        | These counts help distinguish an actually empty table from records
        | hidden by the current filter or SoftDeletes global scope.
        |
        */

        $activeSalarySlipCount = null;
        $trashedSalarySlipCount = null;

        if (!$isDriver) {
            $activeSalarySlipCount = SalarySlip::query()->count();
            $trashedSalarySlipCount = SalarySlip::onlyTrashed()->count();
        }


        /*
        |--------------------------------------------------------------------------
        | Drivers
        |--------------------------------------------------------------------------
        */

        $drivers = collect();

        if (!$isDriver) {

            $drivers = Driver::query()
                ->orderBy('driver_code')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Payroll Records Missing Salary Slips
        |--------------------------------------------------------------------------
        |
        | Only approved / paid payroll records without an active Salary Slip
        | are shown for manual recovery. The service performs the final
        | authorization, status, duplicate and soft-delete checks.
        |
        */

        $salaryProcessings = collect();

        if (!$isDriver) {

            $salaryProcessings = SalaryProcessing::query()
                ->with('driver')
                ->whereIn('status', [
                    SalaryProcessing::STATUS_APPROVED,
                    SalaryProcessing::STATUS_PAID,
                ])
                ->whereDoesntHave('salarySlips')
                ->latest('salary_year')
                ->latest('salary_month')
                ->latest('id')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Statuses
        |--------------------------------------------------------------------------
        */

        $statuses =
            SalarySlip::STATUSES;


        /*
        |--------------------------------------------------------------------------
        | Payment Statuses
        |--------------------------------------------------------------------------
        */

        $paymentStatuses =
            SalarySlip::PAYMENT_STATUSES;


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view('backend.salary-slips.index',
            compact(
                'salarySlips',
                'drivers',
                'salaryProcessings',
                'statuses',
                'paymentStatuses',
                'isAdmin',
                'isOperations',
                'isAccountant',
                'isDriver',
                'activeSalarySlipCount',
                'trashedSalarySlipCount'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE MISSING SALARY SLIP
    |--------------------------------------------------------------------------
    |
    | Recover a missing Salary Slip from an approved / paid payroll record.
    | Salary amounts are taken from the payroll snapshot, not recalculated
    | or entered manually in SalarySlipService.
    |
    */

    public function generateMissing(
        int $salaryProcessing
    ): RedirectResponse {

        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

        if (
            !$user->isAdmin() &&
            !$user->isOperations() &&
            !$user->isAccountant()
        ) {
            abort(403);
        }

        try {

            $salarySlip =
                $this->salaryProcessingService
                    ->generateSalarySlipForProcessing(
                        $salaryProcessing
                    );

            return redirect()
                ->route('salary-slips.index')
                ->with(
                    'message',
                    'Salary Slip generated successfully: '.$salarySlip->slip_no
                );

        } catch (\Throwable $exception) {

            report($exception);

            return redirect()
                ->route('salary-slips.index')
                ->with(
                    'error',
                    $exception->getMessage()
                );
        }
    }



    /*
    |--------------------------------------------------------------------------
    | PREVIEW STANDALONE SALARY SLIP
    |--------------------------------------------------------------------------
    |
    | Returns a read-only Attendance / Working Sheet / Advance preview.
    | No SalaryProcessing or SalarySlip record is created by this action.
    |
    */

    public function preview(
        Request $request
    ): JsonResponse {

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

        $validated = $request->validate([
            'driver_id' => [
                'required',
                'integer',
                'exists:drivers,id',
            ],
            'salary_month' => [
                'required',
                'regex:/^\\d{4}-(0[1-9]|1[0-2])$/',
            ],
            'period_from' => [
                'required',
                'date_format:Y-m-d',
            ],
            'period_to' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:period_from',
            ],
        ]);

        try {

            [$year, $month] = array_map(
                'intval',
                explode('-', $validated['salary_month'])
            );

            $preview = $this->salaryProcessingService
                ->previewStandaloneSalarySlip(
                    (int) $validated['driver_id'],
                    $month,
                    $year,
                    $validated['period_from'],
                    $validated['period_to']
                );

            return response()->json([
                'success' => true,
                'data' => $preview,
            ]);

        } catch (\InvalidArgumentException | \RuntimeException $exception) {

            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 422);

        } catch (\Throwable $exception) {

            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Unable to load the Salary Slip preview. Please try again or contact the administrator.',
            ], 500);
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


        /*
        |--------------------------------------------------------------------------
        | Driver Restriction
        |--------------------------------------------------------------------------
        |
        | SalarySlipService::create() is restricted to non-driver users.
        |
        */

        if ($user->isDriver()) {
            abort(403);
        }

        $drivers = Driver::query()
            ->orderBy('driver_code')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Salary Processings
        |--------------------------------------------------------------------------
        |
        | Manual Salary Slips cannot be linked to payroll records.
        | Payroll-generated slips are created by SalaryProcessingService.
        | Keep an empty collection for compatibility with the existing view.
        |
        */

        $salaryProcessings = collect();


        /*
        |--------------------------------------------------------------------------
        | Statuses
        |--------------------------------------------------------------------------
        */

        $statuses =
            SalarySlip::STATUSES;


        /*
        |--------------------------------------------------------------------------
        | Payment Statuses
        |--------------------------------------------------------------------------
        */

        $paymentStatuses =
            SalarySlip::PAYMENT_STATUSES;


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view('backend.salary-slips.create',
            compact(
                'drivers',
                'salaryProcessings',
                'statuses',
                'paymentStatuses'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreSalarySlipRequest $request
    ): RedirectResponse {

        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Driver Restriction
        |--------------------------------------------------------------------------
        |
        | Manual Salary Slip creation is not available to drivers.
        |
        */

        if ($user->isDriver()) {
            abort(403);
        }

        $data = $request->validated();

        try {

            /*
            |--------------------------------------------------------------------------
            | Rebuild Source Values On The Server
            |--------------------------------------------------------------------------
            |
            | Do not trust readonly browser fields. Attendance, Working Sheet
            | totals and paid advance deductions are loaded again from source
            | tables immediately before creating the standalone Salary Slip.
            |
            */

            [$year, $month] = array_map(
                'intval',
                explode('-', $data['salary_month'])
            );

            // Preserve optional adjustments entered by the administrator.
            $manualAdjustments = [];

            foreach ([
                'bonus_amount',
                'other_earnings',
                'loan_deduction',
                'penalty_deduction',
                'other_deductions',
            ] as $adjustmentField) {
                if (array_key_exists($adjustmentField, $data)) {
                    $manualAdjustments[$adjustmentField] = $data[$adjustmentField];
                }
            }

            $preview = $this->salaryProcessingService
                ->previewStandaloneSalarySlip(
                    (int) $data['driver_id'],
                    $month,
                    $year,
                    $data['period_from'] ?? null,
                    $data['period_to'] ?? null
                );

            $data = array_merge(
                $data,
                $preview['data'],
                $manualAdjustments,
                [
                    'salary_month' => $data['salary_month'],
                    'salary_year' => $year,
                    'period_from' => $preview['period_from'],
                    'period_to' => $preview['period_to'],
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Create Standalone Salary Slip
            |--------------------------------------------------------------------------
            */

            $this->salarySlipService->create($data);

        } catch (\InvalidArgumentException | \RuntimeException $exception) {

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'salary_month' => $exception->getMessage(),
                ]);
        }

        return redirect()
            ->route('salary-slips.index')
            ->with(
                'message',
                'Salary slip created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        SalarySlip $salarySlip
    ): View {

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
                ->where(
                    'user_id',
                    $user->id
                )
                ->first();

            if (
                !$driver ||
                (int) $salarySlip->driver_id !==
                (int) $driver->id
            ) {
                abort(403);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Relationships
        |--------------------------------------------------------------------------
        */

        $salarySlip->load([
            'driver',
            'salaryProcessing',
            'generatedBy',
            'issuedBy',
        ]);


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view('backend.salary-slips.show',
            compact('salarySlip')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        SalarySlip $salarySlip
    ): View {

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
                ->where(
                    'user_id',
                    $user->id
                )
                ->first();

            if (
                !$driver ||
                (int) $salarySlip->driver_id !==
                (int) $driver->id
            ) {
                abort(403);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Load Relationships
        |--------------------------------------------------------------------------
        */

        $salarySlip->load([
            'driver',
            'salaryProcessing',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Drivers
        |--------------------------------------------------------------------------
        */

        if ($user->isDriver()) {

            $drivers = collect([
                $salarySlip->driver
            ]);

        } else {

            $drivers = Driver::query()
                ->orderBy('driver_code')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Salary Processings
        |--------------------------------------------------------------------------
        |
        | Manual Salary Slip updates cannot change payroll ownership.
        | Keep an empty collection for compatibility with the existing view.
        |
        */

        $salaryProcessings = collect();


        /*
        |--------------------------------------------------------------------------
        | Statuses
        |--------------------------------------------------------------------------
        */

        $statuses =
            SalarySlip::STATUSES;


        /*
        |--------------------------------------------------------------------------
        | Payment Statuses
        |--------------------------------------------------------------------------
        */

        $paymentStatuses =
            SalarySlip::PAYMENT_STATUSES;


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view('backend.salary-slips.edit',
            compact(
                'salarySlip',
                'drivers',
                'salaryProcessings',
                'statuses',
                'paymentStatuses'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateSalarySlipRequest $request,
        SalarySlip $salarySlip
    ): RedirectResponse {

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

            $driver = Driver::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->first();

            if (
                !$driver ||
                (int) $salarySlip->driver_id !==
                (int) $driver->id
            ) {
                abort(403);
            }

            $data =
                $request->validated();

            $data['driver_id'] =
                $driver->id;

        } else {

            $data =
                $request->validated();
        }


        /*
        |--------------------------------------------------------------------------
        | Lifecycle Action Values
        |--------------------------------------------------------------------------
        |
        | Status and payment transitions must go through the dedicated
        | SalarySlipService methods. They must not be written directly.
        |
        */

        $requestedStatus = $data['status'] ?? null;

        $requestedPaymentStatus =
            $data['payment_status'] ?? null;

        $paymentDate = $data['payment_date'] ?? null;


        /*
        |--------------------------------------------------------------------------
        | Determine Whether Salary Details Can Be Updated
        |--------------------------------------------------------------------------
        */

        $canUpdateSalaryDetails =
            !$salarySlip->salary_processing_id &&
            !$salarySlip->isIssued() &&
            !$salarySlip->isPaid() &&
            !$salarySlip->isPartiallyPaid() &&
            !$salarySlip->isStatusPaid() &&
            !$salarySlip->isCancelled();


        /*
        |--------------------------------------------------------------------------
        | Issue / Cancel / Payment Lifecycle
        |--------------------------------------------------------------------------
        */

        if ($requestedStatus === SalarySlip::STATUS_CANCELLED) {

            $this->salarySlipService->cancel(
                $salarySlip
            );

            $successMessage =
                'Salary slip cancelled successfully.';

        } elseif (
            $requestedStatus === SalarySlip::STATUS_ISSUED
        ) {

            if ($canUpdateSalaryDetails) {
                $this->salarySlipService->update(
                    $salarySlip,
                    $data
                );
            }

            $this->salarySlipService->issue(
                $salarySlip
            );

            $successMessage =
                'Salary slip issued successfully.';

        } elseif (
            $requestedPaymentStatus === SalarySlip::PAYMENT_PAID
        ) {

            if ($canUpdateSalaryDetails) {
                $this->salarySlipService->update(
                    $salarySlip,
                    $data
                );
            }

            $this->salarySlipService->markAsPaid(
                $salarySlip,
                $paymentDate
            );

            $successMessage =
                'Salary slip marked as paid successfully.';

        } elseif (
            $requestedPaymentStatus === SalarySlip::PAYMENT_PARTIAL
        ) {

            if ($canUpdateSalaryDetails) {
                $this->salarySlipService->update(
                    $salarySlip,
                    $data
                );
            }

            $this->salarySlipService->markAsPartiallyPaid(
                $salarySlip,
                $paymentDate
            );

            $successMessage =
                'Salary slip marked as partially paid successfully.';

        } else {

            $this->salarySlipService->update(
                $salarySlip,
                $data
            );

            $successMessage =
                'Salary slip updated successfully.';
        }


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('salary-slips.index')
            ->with(
                'message',
                $successMessage
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        SalarySlip $salarySlip
    ): RedirectResponse {

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

            $driver = Driver::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->first();

            if (
                !$driver ||
                (int) $salarySlip->driver_id !==
                (int) $driver->id
            ) {
                abort(403);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Through Service
        |--------------------------------------------------------------------------
        */

        $this->salarySlipService->delete(
            $salarySlip
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('salary-slips.index')
            ->with(
                'message',
                'Salary slip deleted successfully.'
            );
    }
}
