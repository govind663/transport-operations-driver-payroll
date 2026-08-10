<?php

namespace App\Services\VehicleCategory;

use App\Models\VehicleCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class VehicleCategoryService
{
    /*
    |--------------------------------------------------------------------------
    | Get All Vehicle Categories
    |--------------------------------------------------------------------------
    */

    public function getVehicleCategories(): Collection
    {
        return VehicleCategory::with([
            'createdBy',
            'updatedBy',
        ])
            ->latest()
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Find Vehicle Category
    |--------------------------------------------------------------------------
    */

    public function findById(int|string $id): VehicleCategory
    {
        return VehicleCategory::findOrFail($id);
    }


    /*
    |--------------------------------------------------------------------------
    | Store Vehicle Category
    |--------------------------------------------------------------------------
    */

    public function store(array $data): VehicleCategory
    {
        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $data['created_by'] = Auth::id();


        return VehicleCategory::create($data);
    }


    /*
    |--------------------------------------------------------------------------
    | Update Vehicle Category
    |--------------------------------------------------------------------------
    */

    public function update(
        VehicleCategory $vehicleCategory,
        array $data
    ): VehicleCategory {

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $data['updated_by'] = Auth::id();


        $vehicleCategory->update($data);


        return $vehicleCategory->refresh();
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Vehicle Category
    |--------------------------------------------------------------------------
    */

    public function delete(VehicleCategory $vehicleCategory): bool
    {
        /*
        |--------------------------------------------------------------------------
        | Delete
        |--------------------------------------------------------------------------
        */

        return (bool) $vehicleCategory->delete();
    }


    /*
    |--------------------------------------------------------------------------
    | Active Vehicle Categories
    |--------------------------------------------------------------------------
    */

    public function getActiveVehicleCategories(): Collection
    {
        return VehicleCategory::active()
            ->orderBy('name')
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Search Vehicle Categories
    |--------------------------------------------------------------------------
    */

    public function search(?string $keyword): Collection
    {
        return VehicleCategory::query()

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
                        );
                });
            })

            ->latest()

            ->get();
    }
}