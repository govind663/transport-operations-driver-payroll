<?php

namespace App\Services\VehicleType;

use App\Models\VehicleType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class VehicleTypeService
{
    /*
    |--------------------------------------------------------------------------
    | Get All Vehicle Types
    |--------------------------------------------------------------------------
    */

    public function getVehicleTypes(): Collection
    {
        return VehicleType::with([
            'vehicleCategory',
            'createdBy',
            'updatedBy',
        ])
            ->latest()
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Find Vehicle Type
    |--------------------------------------------------------------------------
    */

    public function findById(int|string $id): VehicleType
    {
        return VehicleType::with([
            'vehicleCategory',
        ])->findOrFail($id);
    }


    /*
    |--------------------------------------------------------------------------
    | Store Vehicle Type
    |--------------------------------------------------------------------------
    */

    public function store(array $data): VehicleType
    {
        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $data['created_by'] = Auth::id();


        return VehicleType::create($data);
    }


    /*
    |--------------------------------------------------------------------------
    | Update Vehicle Type
    |--------------------------------------------------------------------------
    */

    public function update(
        VehicleType $vehicleType,
        array $data
    ): VehicleType {

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $data['updated_by'] = Auth::id();


        $vehicleType->update($data);


        return $vehicleType->refresh();
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Vehicle Type
    |--------------------------------------------------------------------------
    */

    public function delete(VehicleType $vehicleType): bool
    {
        return (bool) $vehicleType->delete();
    }


    /*
    |--------------------------------------------------------------------------
    | Active Vehicle Types
    |--------------------------------------------------------------------------
    */

    public function getActiveVehicleTypes(): Collection
    {
        return VehicleType::active()
            ->with([
                'vehicleCategory',
            ])
            ->orderBy('name')
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Vehicle Types By Category
    |--------------------------------------------------------------------------
    */

    public function getByCategory(
        int|string $vehicleCategoryId
    ): Collection {

        return VehicleType::active()
            ->where(
                'vehicle_category_id',
                $vehicleCategoryId
            )
            ->orderBy('name')
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Search Vehicle Types
    |--------------------------------------------------------------------------
    */

    public function search(?string $keyword): Collection
    {
        return VehicleType::query()

            ->with([
                'vehicleCategory',
            ])

            ->when($keyword, function ($query) use ($keyword) {

                $query->where(function ($q) use ($keyword) {

                    $q->where(
                        'name',
                        'like',
                        "%{$keyword}%"
                    )
                        ->orWhere(
                            'code',
                            'like',
                            "%{$keyword}%"
                        )
                        ->orWhere(
                            'description',
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
                        );
                });
            })

            ->latest()

            ->get();
    }
}