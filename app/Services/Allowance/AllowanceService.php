<?php

namespace App\Services\Allowance;

use App\Models\Allowance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AllowanceService
{
    /*
    |--------------------------------------------------------------------------
    | GET ALLOWANCES
    |--------------------------------------------------------------------------
    */

    public function getAllowances(
        int $perPage = 20
    ): LengthAwarePaginator {

        return Allowance::query()
            ->with([
                'createdBy',
                'updatedBy',
            ])
            ->latest('id')
            ->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND ALLOWANCE
    |--------------------------------------------------------------------------
    */

    public function findById(
        string|int $id
    ): Allowance {

        return Allowance::query()
            ->with([
                'createdBy',
                'updatedBy',
            ])
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        array $data
    ): Allowance {

        return DB::transaction(function () use ($data) {

            $data['created_by'] = Auth::id();

            $data['updated_by'] = Auth::id();

            /*
            |--------------------------------------------------------------------------
            | Calculation Type
            |--------------------------------------------------------------------------
            */

            $data['calculation_type'] =
                $data['calculation_type']
                ?? Allowance::CALCULATION_FIXED;

            /*
            |--------------------------------------------------------------------------
            | Fixed / Percentage Values
            |--------------------------------------------------------------------------
            |
            | Fixed allowance mein percentage ko null rakhenge.
            | Percentage allowance mein amount ko null rakhenge.
            |--------------------------------------------------------------------------
            */

            if (
                $data['calculation_type']
                === Allowance::CALCULATION_FIXED
            ) {

                $data['percentage'] = null;

            } elseif (
                $data['calculation_type']
                === Allowance::CALCULATION_PERCENTAGE
            ) {

                $data['amount'] = null;
            }

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $data['status'] =
                $data['status']
                ?? Allowance::STATUS_ACTIVE;

            /*
            |--------------------------------------------------------------------------
            | Taxable Status
            |--------------------------------------------------------------------------
            */

            $data['is_taxable'] =
                $data['is_taxable']
                ?? false;

            return Allowance::create($data);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Allowance $allowance,
        array $data
    ): Allowance {

        return DB::transaction(function () use (
            $allowance,
            $data
        ) {

            $data['updated_by'] = Auth::id();

            /*
            |--------------------------------------------------------------------------
            | Calculation Type
            |--------------------------------------------------------------------------
            */

            $calculationType =
                $data['calculation_type']
                ?? $allowance->calculation_type;

            /*
            |--------------------------------------------------------------------------
            | Fixed / Percentage Values
            |--------------------------------------------------------------------------
            */

            if (
                $calculationType
                === Allowance::CALCULATION_FIXED
            ) {

                $data['percentage'] = null;

            } elseif (
                $calculationType
                === Allowance::CALCULATION_PERCENTAGE
            ) {

                $data['amount'] = null;
            }

            $allowance->update($data);

            return $allowance->fresh([
                'createdBy',
                'updatedBy',
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(
        Allowance $allowance
    ): bool {

        return DB::transaction(function () use (
            $allowance
        ) {

            /*
            |--------------------------------------------------------------------------
            | Soft Delete
            |--------------------------------------------------------------------------
            */

            if (
                in_array(
                    'deleted_by',
                    $allowance->getFillable(),
                    true
                )
            ) {

                $allowance->deleted_by = Auth::id();

                $allowance->save();
            }

            return $allowance->delete();
        });
    }
}