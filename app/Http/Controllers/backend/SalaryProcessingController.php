<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\SalaryProcessing\StoreSalaryProcessingRequest;
use App\Http\Requests\Backend\SalaryProcessing\UpdateSalaryProcessingRequest;
use App\Models\Driver;
use App\Models\SalaryProcessing;
use App\Models\User;
use App\Services\SalaryProcessing\SalaryProcessingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SalaryProcessingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Service
    |--------------------------------------------------------------------------
    */

    protected SalaryProcessingService $salaryProcessingService;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        SalaryProcessingService $salaryProcessingService
    ) {
        $this->salaryProcessingService = $salaryProcessingService;
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

            'driver_id' =>
                $request->input('driver_id'),

            'salary_month' =>
                $request->input('salary_month'),

            'salary_year' =>
                $request->input('salary_year'),

            'status' =>
                $request->input('status'),

            'search' =>
                $request->input('search'),

        ];


        /*
        |--------------------------------------------------------------------------
        | Salary Processings
        |--------------------------------------------------------------------------
        */

        $salaryProcessings =
            $this->salaryProcessingService->getPaginated(
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
            SalaryProcessing::STATUSES;


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view('backend.salary-processings.index',
            compact(
                'salaryProcessings',
                'drivers',
                'statuses',
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

        $salaryProcessings = collect();

        if ($isDriver) {

            $driver = $drivers->first();

            if ($driver) {

                $salaryProcessings =
                    SalaryProcessing::query()
                        ->where(
                            'driver_id',
                            $driver->id
                        )
                        ->with('driver')
                        ->latest('salary_year')
                        ->latest('salary_month')
                        ->latest('id')
                        ->get();
            }

        } else {

            $salaryProcessings =
                SalaryProcessing::query()
                    ->with('driver')
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
        | Create Through Service
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
        ]);


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

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
                    ->with('driver')
                    ->latest('salary_year')
                    ->latest('salary_month')
                    ->latest('id')
                    ->get();

        } else {

            $salaryProcessings =
                SalaryProcessing::query()
                    ->with('driver')
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
        | Update Through Service
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
        | Delete Through Service
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