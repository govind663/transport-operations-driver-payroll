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
use Illuminate\View\View;

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

            'search' =>
                $request->input('search'),

            'driver_id' =>
                $request->input('driver_id'),

            'role' =>
                $request->input('role'),

            'month' =>
                $request->input('month'),

            'year' =>
                $request->input('year'),

            'status' =>
                $request->input('status'),

            'date_from' =>
                $request->input('date_from'),

            'date_to' =>
                $request->input('date_to'),

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
                ->where(
                    'user_id',
                    $user->id
                )
                ->first();

            if ($driver) {

                $filters['driver_id'] =
                    $driver->id;

            } else {

                /*
                |--------------------------------------------------------------------------
                | Driver profile does not exist
                |--------------------------------------------------------------------------
                */

                $filters['driver_id'] = 0;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Payroll Report
        |--------------------------------------------------------------------------
        */

        $payrollReport =
            $this->payrollReportService->getReport(
                $filters,
                20
            );


        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $filterOptions =
            $this->payrollReportService->getFilterOptions();


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
                ->where(
                    'user_id',
                    $user->id
                )
                ->first();

            $drivers = $driver
                ? collect([$driver])
                : collect();

        } else {

            /*
            |--------------------------------------------------------------------------
            | All Active Drivers
            |--------------------------------------------------------------------------
            */

            $drivers = Driver::query()
                ->select([
                    'id',
                    'driver_code',
                    'first_name',
                    'last_name',
                    'mobile',
                ])
                ->where(
                    'status',
                    'active'
                )
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

        $filterOptions['drivers'] =
            $drivers;


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.reports.payroll.index',
            compact(
                'payrollReport',
                'filterOptions',
                'isAdmin',
                'isOperations',
                'isAccountant',
                'isDriver'
            )
        );
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
        | Driver Role
        |--------------------------------------------------------------------------
        */

        $isDriver = $user->isDriver();


        /*
        |--------------------------------------------------------------------------
        | Drivers
        |--------------------------------------------------------------------------
        */

        if ($isDriver) {

            $drivers = Driver::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->orderBy('driver_code')
                ->get();

        } else {

            $drivers = Driver::query()
                ->orderBy('driver_code')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Salary Processings
        |--------------------------------------------------------------------------
        */

        if ($isDriver) {

            $driver = $drivers->first();

            if ($driver) {

                $salaryProcessings =
                    SalaryProcessing::query()
                        ->where(
                            'driver_id',
                            $driver->id
                        )
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

            } else {

                $salaryProcessings =
                    collect();
            }

        } else {

            $salaryProcessings =
                SalaryProcessing::query()
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
        | Statuses
        |--------------------------------------------------------------------------
        */

        $statuses =
            SalaryProcessing::STATUSES;


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

        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Validated Data
        |--------------------------------------------------------------------------
        */

        $data =
            $request->validated();


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
                !isset($data['driver_id']) ||
                (int) $data['driver_id'] !==
                (int) $driver->id
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'You can only process your own salary.'
                    );
            }

            $data['driver_id'] =
                $driver->id;
        }


        /*
        |--------------------------------------------------------------------------
        | Role
        |--------------------------------------------------------------------------
        */

        $data['role'] =
            SalaryProcessing::ROLE_DRIVER;


        /*
        |--------------------------------------------------------------------------
        | Processed By
        |--------------------------------------------------------------------------
        */

        $data['processed_by'] =
            $user->id;


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        $data['status'] =
            $data['status']
            ?? SalaryProcessing::STATUS_PROCESSED;


        /*
        |--------------------------------------------------------------------------
        | Create
        |--------------------------------------------------------------------------
        */

        $this->salaryProcessingService->create(
            $data
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('salary-processing.index')
            ->with(
                'message',
                'Salary processed successfully.'
            );
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


        /*
        |--------------------------------------------------------------------------
        | Driver Security
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
                (int) $salaryProcessing->driver_id !==
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

        $salaryProcessing->load([
            'driver',
            'processedBy',
            'approvedBy',
            'paidBy',
        ]);


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.salary-processings.show',
            compact(
                'salaryProcessing'
            )
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


        /*
        |--------------------------------------------------------------------------
        | Driver Security
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
                (int) $salaryProcessing->driver_id !==
                (int) $driver->id
            ) {

                abort(403);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Drivers
        |--------------------------------------------------------------------------
        */

        if ($user->isDriver()) {

            $drivers = Driver::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->orderBy('driver_code')
                ->get();

        } else {

            $drivers = Driver::query()
                ->orderBy('driver_code')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | Salary Processings
        |--------------------------------------------------------------------------
        */

        if ($user->isDriver()) {

            $salaryProcessings =
                SalaryProcessing::query()
                    ->where(
                        'driver_id',
                        $salaryProcessing->driver_id
                    )
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

        } else {

            $salaryProcessings =
                SalaryProcessing::query()
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
        | Statuses
        |--------------------------------------------------------------------------
        */

        $statuses =
            SalaryProcessing::STATUSES;


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


        /*
        |--------------------------------------------------------------------------
        | Existing Salary Driver Security
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
                (int) $salaryProcessing->driver_id !==
                (int) $driver->id
            ) {

                abort(403);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Validated Data
        |--------------------------------------------------------------------------
        */

        $data =
            $request->validated();


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
                !isset($data['driver_id']) ||
                (int) $data['driver_id'] !==
                (int) $driver->id
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'You can only update your own salary.'
                    );
            }

            $data['driver_id'] =
                $driver->id;
        }


        /*
        |--------------------------------------------------------------------------
        | Role
        |--------------------------------------------------------------------------
        */

        $data['role'] =
            SalaryProcessing::ROLE_DRIVER;


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $this->salaryProcessingService->update(
            $salaryProcessing,
            $data
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('salary-processing.index')
            ->with(
                'message',
                'Salary processing updated successfully.'
            );
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


        /*
        |--------------------------------------------------------------------------
        | Driver Security
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
                (int) $salaryProcessing->driver_id !==
                (int) $driver->id
            ) {

                abort(403);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        $this->salaryProcessingService->delete(
            $salaryProcessing
        );


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('salary-processing.index')
            ->with(
                'message',
                'Salary processing deleted successfully.'
            );
    }
}