<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\VehiclePrice\StoreVehiclePriceRequest;
use App\Http\Requests\Backend\VehiclePrice\UpdateVehiclePriceRequest;
use App\Models\VehicleManagement;
use App\Models\VehiclePrice;
use App\Services\VehiclePrice\VehiclePriceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehiclePriceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
    */

    protected VehiclePriceService $vehiclePriceService;


    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct(
        VehiclePriceService $vehiclePriceService
    ) {
        $this->vehiclePriceService = $vehiclePriceService;
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    | Display all vehicle prices.
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $prices = $this->vehiclePriceService->search(
            $request->input('search')
        );

        return view('backend.vehicle_price.index',
            compact('prices')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    | Show create vehicle price form.
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        $vehicles = VehicleManagement::query()
            ->with([
                'vehicleCategory',
                'vehicleType',
            ])
            ->orderBy('vehicle_number')
            ->get();

        return view('backend.vehicle_price.create',
            compact('vehicles')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    | Store vehicle price.
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreVehiclePriceRequest $request
    ): RedirectResponse {

        $validated = $request->validated();

        $vehicle = VehicleManagement::findOrFail(
            $validated['vehicle_id']
        );

        $this->vehiclePriceService->store(
            $vehicle,
            $validated
        );

        return redirect()
            ->route('vehicle-price.index')
            ->with(
                'message',
                'Vehicle price added successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    | Display specific vehicle price.
    |--------------------------------------------------------------------------
    */

    public function show(
        VehiclePrice $vehiclePrice
    ): View {

        $vehiclePrice->load([
            'vehicle',
            'vehicle.vehicleCategory',
            'vehicle.vehicleType',
            'createdBy',
            'updatedBy',
        ]);

        return view('backend.vehicle_price.show',
            compact('vehiclePrice')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    | Show edit vehicle price form.
    |--------------------------------------------------------------------------
    */

    public function edit(
        VehiclePrice $vehiclePrice
    ): View {

        $vehiclePrice->load([
            'vehicle',
            'vehicle.vehicleCategory',
            'vehicle.vehicleType',
        ]);

        return view('backend.vehicle_price.edit',
            compact('vehiclePrice')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    | Update existing vehicle price.
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateVehiclePriceRequest $request,
        VehiclePrice $vehiclePrice
    ): RedirectResponse {

        $this->vehiclePriceService->update(
            $vehiclePrice,
            $request->validated()
        );

        return redirect()
            ->route('vehicle-price.index')
            ->with(
                'message',
                'Vehicle price updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    | Soft delete vehicle price.
    |--------------------------------------------------------------------------
    */

    public function destroy(
        VehiclePrice $vehiclePrice
    ): RedirectResponse {

        $this->vehiclePriceService->delete(
            $vehiclePrice
        );

        return redirect()
            ->route('vehicle-price.index')
            ->with(
                'message',
                'Vehicle price deleted successfully.'
            );
    }
}