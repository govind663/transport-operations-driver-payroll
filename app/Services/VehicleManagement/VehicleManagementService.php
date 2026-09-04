<?php

namespace App\Services\VehicleManagement;

use App\Models\VehicleManagement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class VehicleManagementService
{
    /*
    |--------------------------------------------------------------------------
    | Get All Vehicles
    |--------------------------------------------------------------------------
    */
    public function getVehicles(): Collection
    {
        return VehicleManagement::with([
            'vehicleCategory',
            'vehicleType',
            'vehiclePrices',
            'createdBy',
            'updatedBy',
        ])
            ->latest()
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Find Vehicle
    |--------------------------------------------------------------------------
    */
    public function findById(
        int|string $id
    ): VehicleManagement {

        return VehicleManagement::with([
            'vehicleCategory',
            'vehicleType',
            'vehiclePrices',
        ])->findOrFail($id);
    }


    /*
    |--------------------------------------------------------------------------
    | Store Vehicle
    |--------------------------------------------------------------------------
    */
    public function store(array $data): VehicleManagement
    {
        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */
        $data['created_by'] = Auth::id();

        return VehicleManagement::create($data);
    }


    /*
    |--------------------------------------------------------------------------
    | Update Vehicle
    |--------------------------------------------------------------------------
    */
    public function update(
        VehicleManagement $vehicle,
        array $data
    ): VehicleManagement {

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */
        $data['updated_by'] = Auth::id();

        $vehicle->update($data);

        return $vehicle->refresh();
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Vehicle
    |--------------------------------------------------------------------------
    */
    public function delete(
        VehicleManagement $vehicle
    ): bool {

        return (bool) $vehicle->delete();
    }


    /*
    |--------------------------------------------------------------------------
    | Active Vehicles
    |--------------------------------------------------------------------------
    */
    public function getActiveVehicles(): Collection
    {
        return VehicleManagement::active()
            ->with([
                'vehicleCategory',
                'vehicleType',
                'vehiclePrices',
            ])
            ->orderBy('vehicle_number')
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Maintenance Vehicles
    |--------------------------------------------------------------------------
    */
    public function getMaintenanceVehicles(): Collection
    {
        return VehicleManagement::maintenance()
            ->with([
                'vehicleCategory',
                'vehicleType',
                'vehiclePrices',
            ])
            ->orderBy('vehicle_number')
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Vehicles By Category
    |--------------------------------------------------------------------------
    */
    public function getByCategory(
        int|string $vehicleCategoryId
    ): Collection {

        return VehicleManagement::query()
            ->where(
                'vehicle_category_id',
                $vehicleCategoryId
            )
            ->with([
                'vehicleCategory',
                'vehicleType',
                'vehiclePrices',
            ])
            ->orderBy('vehicle_number')
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Vehicles By Type
    |--------------------------------------------------------------------------
    */
    public function getByType(
        int|string $vehicleTypeId
    ): Collection {

        return VehicleManagement::query()
            ->where(
                'vehicle_type_id',
                $vehicleTypeId
            )
            ->with([
                'vehicleCategory',
                'vehicleType',
                'vehiclePrices',
            ])
            ->orderBy('vehicle_number')
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Search Vehicles
    |--------------------------------------------------------------------------
    */
    public function search(?string $keyword): Collection
    {
        return VehicleManagement::query()

            ->with([
                'vehicleCategory',
                'vehicleType',
                'vehiclePrices',
            ])

            ->when($keyword, function ($query) use ($keyword) {

                $query->where(function ($q) use ($keyword) {

                    $q->where(
                        'vehicle_number',
                        'like',
                        "%{$keyword}%"
                    )
                        ->orWhere(
                            'registration_number',
                            'like',
                            "%{$keyword}%"
                        )
                        ->orWhere(
                            'chassis_number',
                            'like',
                            "%{$keyword}%"
                        )
                        ->orWhere(
                            'engine_number',
                            'like',
                            "%{$keyword}%"
                        )
                        ->orWhere(
                            'manufacturer',
                            'like',
                            "%{$keyword}%"
                        )
                        ->orWhere(
                            'model',
                            'like',
                            "%{$keyword}%"
                        )

                        ->orWhereHas(
                            'vehicleCategory',
                            function ($categoryQuery) use ($keyword) {

                                $categoryQuery->where(
                                    'name',
                                    'like',
                                    "%{$keyword}%"
                                );
                            }
                        )

                        ->orWhereHas(
                            'vehicleType',
                            function ($typeQuery) use ($keyword) {

                                $typeQuery->where(
                                    'name',
                                    'like',
                                    "%{$keyword}%"
                                );
                            }
                        );
                });
            })

            ->latest()

            ->get();
    }
}