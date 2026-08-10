<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\VehicleType\StoreVehicleTypeRequest;
use App\Http\Requests\Backend\VehicleType\UpdateVehicleTypeRequest;
use App\Models\VehicleCategory;
use App\Models\VehicleType;
use App\Services\VehicleType\VehicleTypeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleTypeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Vehicle Type Service
    |--------------------------------------------------------------------------
    */

    protected VehicleTypeService $vehicleTypeService;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        VehicleTypeService $vehicleTypeService
    ) {
        $this->vehicleTypeService = $vehicleTypeService;
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    | Display all vehicle types.
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $vehicleTypes = $this->vehicleTypeService->search(
            $request->input('search')
        );

        return view(
            'backend.vehicle_types.index',
            compact('vehicleTypes')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    | Show create vehicle type form.
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        $vehicleCategories = VehicleCategory::active()
            ->orderBy('name')
            ->get();

        return view(
            'backend.vehicle_types.create',
            compact('vehicleCategories')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    | Store a new vehicle type.
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreVehicleTypeRequest $request
    ): RedirectResponse {

        $this->vehicleTypeService->store(
            $request->validated()
        );

        return redirect()
            ->route('vehicle-types.index')
            ->with(
                'success',
                'Vehicle type created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    | Display a specific vehicle type.
    |--------------------------------------------------------------------------
    */

    public function show(
        VehicleType $vehicleType
    ): View {

        $vehicleType->load([
            'vehicleCategory',
            'createdBy',
            'updatedBy',
        ]);

        return view(
            'backend.vehicle_types.show',
            compact('vehicleType')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    | Show edit vehicle type form.
    |--------------------------------------------------------------------------
    */

    public function edit(
        VehicleType $vehicleType
    ): View {

        $vehicleCategories = VehicleCategory::active()
            ->orderBy('name')
            ->get();

        return view(
            'backend.vehicle_types.edit',
            compact(
                'vehicleType',
                'vehicleCategories'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    | Update an existing vehicle type.
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateVehicleTypeRequest $request,
        VehicleType $vehicleType
    ): RedirectResponse {

        $this->vehicleTypeService->update(
            $vehicleType,
            $request->validated()
        );

        return redirect()
            ->route('vehicle-types.index')
            ->with(
                'success',
                'Vehicle type updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    | Soft delete vehicle type.
    |--------------------------------------------------------------------------
    */

    public function destroy(
        VehicleType $vehicleType
    ): RedirectResponse {

        $this->vehicleTypeService->delete(
            $vehicleType
        );

        return redirect()
            ->route('vehicle-types.index')
            ->with(
                'success',
                'Vehicle type deleted successfully.'
            );
    }
}