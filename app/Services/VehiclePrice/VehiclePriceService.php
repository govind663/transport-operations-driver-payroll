<?php

namespace App\Services\VehiclePrice;

use App\Models\VehicleManagement;
use App\Models\VehiclePrice;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class VehiclePriceService
{
    /*
    |--------------------------------------------------------------------------
    | Get All Vehicle Prices
    |--------------------------------------------------------------------------
    */

    public function getPrices(): Collection
    {
        return VehiclePrice::with([
            'vehicle',
            'createdBy',
            'updatedBy',
        ])
            ->latest('effective_date')
            ->latest('id')
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Get Vehicle Prices
    |--------------------------------------------------------------------------
    */

    public function getByVehicle(
        int|string $vehicleId
    ): Collection {

        return VehiclePrice::query()
            ->where('vehicle_id', $vehicleId)
            ->with([
                'vehicle',
                'createdBy',
                'updatedBy',
            ])
            ->latest('effective_date')
            ->latest('id')
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Find Price
    |--------------------------------------------------------------------------
    */

    public function findById(
        int|string $id
    ): VehiclePrice {

        return VehiclePrice::with([
            'vehicle',
            'createdBy',
            'updatedBy',
        ])->findOrFail($id);
    }


    /*
    |--------------------------------------------------------------------------
    | Get Latest Price
    |--------------------------------------------------------------------------
    */

    public function getLatestPrice(
        int|string $vehicleId
    ): ?VehiclePrice {

        return VehiclePrice::query()
            ->where('vehicle_id', $vehicleId)
            ->latest('effective_date')
            ->latest('id')
            ->first();
    }


    /*
    |--------------------------------------------------------------------------
    | Store Price
    |--------------------------------------------------------------------------
    */

    public function store(
        VehicleManagement $vehicle,
        array $data
    ): VehiclePrice {

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $data['vehicle_id'] = $vehicle->id;
        $data['created_by'] = Auth::id();


        return VehiclePrice::create($data);
    }


    /*
    |--------------------------------------------------------------------------
    | Update Price
    |--------------------------------------------------------------------------
    */

    public function update(
        VehiclePrice $vehiclePrice,
        array $data
    ): VehiclePrice {

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        $data['updated_by'] = Auth::id();


        $vehiclePrice->update($data);


        return $vehiclePrice->refresh();
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Price
    |--------------------------------------------------------------------------
    */

    public function delete(
        VehiclePrice $vehiclePrice
    ): bool {

        return (bool) $vehiclePrice->delete();
    }


    /*
    |--------------------------------------------------------------------------
    | Search Prices
    |--------------------------------------------------------------------------
    */

    public function search(?string $keyword): Collection
    {
        return VehiclePrice::query()

            ->with([
                'vehicle',
            ])

            ->when($keyword, function ($query) use ($keyword) {

                $query->where(function ($q) use ($keyword) {

                    $q->where(
                        'price',
                        'like',
                        "%{$keyword}%"
                    )
                        ->orWhere(
                            'remarks',
                            'like',
                            "%{$keyword}%"
                        )

                        ->orWhereHas(
                            'vehicle',
                            function ($vehicleQuery) use ($keyword) {

                                $vehicleQuery
                                    ->where(
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
                                        'manufacturer',
                                        'like',
                                        "%{$keyword}%"
                                    )
                                    ->orWhere(
                                        'model',
                                        'like',
                                        "%{$keyword}%"
                                    );
                            }
                        );
                });
            })

            ->latest('effective_date')
            ->latest('id')
            ->get();
    }
}