<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\VehicleCategory\StoreVehicleCategoryRequest;
use App\Http\Requests\Backend\VehicleCategory\UpdateVehicleCategoryRequest;
use App\Models\VehicleCategory;
use App\Services\VehicleCategory\VehicleCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleCategoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Vehicle Category Service
    |--------------------------------------------------------------------------
    */

    protected VehicleCategoryService $vehicleCategoryService;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        VehicleCategoryService $vehicleCategoryService
    ) {
        $this->vehicleCategoryService = $vehicleCategoryService;
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    | Display all vehicle categories.
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $vehicleCategories = $this->vehicleCategoryService->search(
            $request->input('search')
        );

        return view('backend.vehicle_categories.index', compact('vehicleCategories'));
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    | Show create vehicle category form.
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        return view('backend.vehicle_categories.create');
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    | Store a new vehicle category.
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreVehicleCategoryRequest $request
    ): RedirectResponse {

        $this->vehicleCategoryService->store(
            $request->validated()
        );

        return redirect()
            ->route('vehicle-categories.index')
            ->with(
                'success',
                'Vehicle category created successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    | Display a specific vehicle category.
    |--------------------------------------------------------------------------
    */

    public function show(
        VehicleCategory $vehicleCategory
    ): View {

        return view('backend.vehicle_categories.show', compact('vehicleCategory'));
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    | Show edit vehicle category form.
    |--------------------------------------------------------------------------
    */

    public function edit(
        VehicleCategory $vehicleCategory
    ): View {

        return view('backend.vehicle_categories.edit', compact('vehicleCategory'));
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    | Update an existing vehicle category.
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateVehicleCategoryRequest $request,
        VehicleCategory $vehicleCategory
    ): RedirectResponse {

        $this->vehicleCategoryService->update(
            $vehicleCategory,
            $request->validated()
        );

        return redirect()
            ->route('vehicle-categories.index')
            ->with(
                'success',
                'Vehicle category updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    | Soft delete vehicle category.
    |--------------------------------------------------------------------------
    */

    public function destroy(
        VehicleCategory $vehicleCategory
    ): RedirectResponse {

        $this->vehicleCategoryService->delete(
            $vehicleCategory
        );

        return redirect()
            ->route('vehicle-categories.index')
            ->with(
                'success',
                'Vehicle category deleted successfully.'
            );
    }
}