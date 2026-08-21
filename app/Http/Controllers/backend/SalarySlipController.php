<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SalarySlip\StoreSalarySlipRequest;
use App\Http\Requests\Backend\SalarySlip\UpdateSalarySlipRequest;
use App\Models\Driver;
use App\Models\SalaryProcessing;
use App\Models\SalarySlip;
use App\Models\User;
use App\Services\SalarySlip\SalarySlipService;
use Illuminate\Http\RedirectResponse;
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


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        SalarySlipService $salarySlipService
    ) {
        $this->salarySlipService = $salarySlipService;
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
                $filters,
                20
            );


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
                'statuses',
                'paymentStatuses',
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
        | Drivers
        |--------------------------------------------------------------------------
        */

        if ($user->isDriver()) {

            $driver = Driver::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->first();

            if (!$driver) {
                abort(403);
            }

            $drivers = collect([
                $driver
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
        | IMPORTANT:
        | salary_processings table does NOT contain
        | period_from / period_to.
        |
        | Sorting is done using salary_year,
        | salary_month and id.
        |
        */

        if ($user->isDriver()) {

            $driverId =
                $drivers->first()->id;

            $salaryProcessings =
                SalaryProcessing::query()
                    ->where(
                        'driver_id',
                        $driverId
                    )
                    ->with('driver')
                    ->orderByDesc('salary_year')
                    ->orderByDesc('salary_month')
                    ->orderByDesc('id')
                    ->get();

        } else {

            $salaryProcessings =
                SalaryProcessing::query()
                    ->with('driver')
                    ->orderByDesc('salary_year')
                    ->orderByDesc('salary_month')
                    ->orderByDesc('id')
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

            if (!$driver) {
                abort(403);
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
            SalarySlip::ROLE_DRIVER;


        /*
        |--------------------------------------------------------------------------
        | Generated By
        |--------------------------------------------------------------------------
        */

        $data['generated_by'] =
            Auth::id();


        /*
        |--------------------------------------------------------------------------
        | Default Status
        |--------------------------------------------------------------------------
        */

        $data['status'] =
            $data['status']
            ?? SalarySlip::STATUS_GENERATED;


        /*
        |--------------------------------------------------------------------------
        | Default Payment Status
        |--------------------------------------------------------------------------
        */

        $data['payment_status'] =
            $data['payment_status']
            ?? SalarySlip::PAYMENT_UNPAID;


        /*
        |--------------------------------------------------------------------------
        | Create Salary Slip
        |--------------------------------------------------------------------------
        */

        $this->salarySlipService->create(
            $data
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
        | IMPORTANT:
        | Do NOT use period_from here.
        |
        */

        if ($user->isDriver()) {

            $salaryProcessings =
                SalaryProcessing::query()
                    ->where(
                        'driver_id',
                        $salarySlip->driver_id
                    )
                    ->with('driver')
                    ->orderByDesc('salary_year')
                    ->orderByDesc('salary_month')
                    ->orderByDesc('id')
                    ->get();

        } else {

            $salaryProcessings =
                SalaryProcessing::query()
                    ->with('driver')
                    ->orderByDesc('salary_year')
                    ->orderByDesc('salary_month')
                    ->orderByDesc('id')
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
        | Role
        |--------------------------------------------------------------------------
        */

        $data['role'] =
            SalarySlip::ROLE_DRIVER;


        /*
        |--------------------------------------------------------------------------
        | Issue Information
        |--------------------------------------------------------------------------
        */

        if (
            isset($data['status']) &&
            $data['status'] ===
                SalarySlip::STATUS_ISSUED
        ) {

            /*
            |--------------------------------------------------------------------------
            | Only Set Issued Details If Not Already Issued
            |--------------------------------------------------------------------------
            */

            if (!$salarySlip->isIssued()) {

                $data['issued_by'] =
                    Auth::id();

                $data['issued_at'] =
                    now();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Update Salary Slip
        |--------------------------------------------------------------------------
        */

        $this->salarySlipService->update(
            $salarySlip,
            $data
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
                'Salary slip updated successfully.'
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