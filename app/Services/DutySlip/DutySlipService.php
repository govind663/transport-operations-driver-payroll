<?php

namespace App\Services\DutySlip;

use App\Models\DutySlip;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DutySlipService
{
    /*
    |--------------------------------------------------------------------------
    | GET DUTY SLIPS
    |--------------------------------------------------------------------------
    */

    public function getDutySlips(
        int $perPage = 20
    ): LengthAwarePaginator {

        return DutySlip::query()
            ->with([
                'dutyAssignment',
                'createdBy',
                'updatedBy',
                'workingSheet',
            ])
            ->latest('id')
            ->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND DUTY SLIP
    |--------------------------------------------------------------------------
    */

    public function findById(
        string|int $id
    ): DutySlip {

        return DutySlip::query()
            ->with([
                'dutyAssignment',
                'createdBy',
                'updatedBy',
                'workingSheet',
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
    ): DutySlip {

        return DB::transaction(function () use ($data) {

            $data['created_by'] = Auth::id();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $data['status'] =
                $data['status']
                ?? DutySlip::STATUS_OPEN;

            return DutySlip::create($data);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        DutySlip $dutySlip,
        array $data
    ): DutySlip {

        return DB::transaction(function () use (
            $dutySlip,
            $data
        ) {

            $data['updated_by'] = Auth::id();

            $dutySlip->update($data);

            return $dutySlip->fresh([
                'dutyAssignment',
                'createdBy',
                'updatedBy',
                'workingSheet',
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(
        DutySlip $dutySlip
    ): bool {

        return DB::transaction(function () use (
            $dutySlip
        ) {

            $dutySlip->deleted_by = Auth::id();

            $dutySlip->save();

            return $dutySlip->delete();
        });
    }
}