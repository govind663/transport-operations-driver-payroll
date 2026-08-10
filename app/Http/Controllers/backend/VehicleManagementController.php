<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\VehicleManagement\StoreVehicleManagementRequest;
use App\Http\Requests\Backend\VehicleManagement\UpdateVehicleManagementRequest;
use App\Models\VehicleManagement;
use App\Services\VehicleCategory\VehicleCategoryService;
use App\Services\VehicleManagement\VehicleManagementService;
use App\Services\VehicleType\VehicleTypeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleManagementController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
    */

    protected VehicleManagementService $vehicleManagementService;

    protected VehicleCategoryService $vehicleCategoryService;

    protected VehicleTypeService $vehicleTypeService;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        VehicleManagementService $vehicleManagementService,
        VehicleCategoryService $vehicleCategoryService,
        VehicleTypeService $vehicleTypeService
    ) {
        $this->vehicleManagementService = $vehicleManagementService;

        $this->vehicleCategoryService = $vehicleCategoryService;

        $this->vehicleTypeService = $vehicleTypeService;
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    | Display all vehicles.
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $vehicles = $this->vehicleManagementService->search(
            $request->input('search')
        );

        return view('backend.vehicle_management.index', compact('vehicles'));
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    | Show create vehicle form.
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Active Vehicle Categories
        |--------------------------------------------------------------------------
        */

        $vehicleCategories =
            $this->vehicleCategoryService
                ->getActiveVehicleCategories();


        /*
        |--------------------------------------------------------------------------
        | Active Vehicle Types
        |--------------------------------------------------------------------------
        */

        $vehicleTypes =
            $this->vehicleTypeService
                ->getActiveVehicleTypes();


        return view('backend.vehicle_management.create',
            compact(
                'vehicleCategories',
                'vehicleTypes'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    | Store a new vehicle.
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreVehicleManagementRequest $request
    ): RedirectResponse {

        $this->vehicleManagementService->store(
            $request->validated()
        );


        return redirect()
            ->route('vehicle-management.index')
            ->with(
                'success',
                'Vehicle created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    | Display a specific vehicle.
    |--------------------------------------------------------------------------
    */

    public function show(
        VehicleManagement $vehicleManagement
    ): View {

        return view('backend.vehicle_management.show', compact('vehicleManagement'));
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    | Show edit vehicle form.
    |--------------------------------------------------------------------------
    */

    public function edit(
        VehicleManagement $vehicleManagement
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Active Vehicle Categories
        |--------------------------------------------------------------------------
        */

        $vehicleCategories =
            $this->vehicleCategoryService
                ->getActiveVehicleCategories();


        /*
        |--------------------------------------------------------------------------
        | Active Vehicle Types
        |--------------------------------------------------------------------------
        */

        $vehicleTypes =
            $this->vehicleTypeService
                ->getActiveVehicleTypes();


        return view('backend.vehicle_management.edit',
            compact(
                'vehicleManagement',
                'vehicleCategories',
                'vehicleTypes'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    | Update existing vehicle.
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateVehicleManagementRequest $request,
        VehicleManagement $vehicleManagement
    ): RedirectResponse {

        $this->vehicleManagementService->update(
            $vehicleManagement,
            $request->validated()
        );


        return redirect()
            ->route('vehicle-management.index')
            ->with(
                'success',
                'Vehicle updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    | Soft delete vehicle.
    |--------------------------------------------------------------------------
    */

    public function destroy(
        VehicleManagement $vehicleManagement
    ): RedirectResponse {

        $this->vehicleManagementService->delete(
            $vehicleManagement
        );


        return redirect()
            ->route('vehicle-management.index')
            ->with(
                'success',
                'Vehicle deleted successfully.'
            );
    }
}