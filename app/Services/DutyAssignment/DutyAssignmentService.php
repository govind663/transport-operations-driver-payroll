<?php

namespace App\Services\DutyAssignment;

use App\Models\DutyAssignment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DutyAssignmentService
{
    /*
    |--------------------------------------------------------------------------
    | GET DUTY ASSIGNMENTS
    |--------------------------------------------------------------------------
    */

    public function getDutyAssignments(
        int $perPage = 20
    ): LengthAwarePaginator {

        return DutyAssignment::query()
            ->with([
                'travelRequest',
                'driver',
                'vehicle',
                'assignedBy',
                'createdBy',
                'updatedBy',
                'dutySlip',
            ])
            ->latest('id')
            ->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND DUTY ASSIGNMENT
    |--------------------------------------------------------------------------
    */

    public function findById(
        string|int $id
    ): DutyAssignment {

        return DutyAssignment::query()
            ->with([
                'travelRequest',
                'driver',
                'vehicle',
                'assignedBy',
                'createdBy',
                'updatedBy',
                'dutySlip',
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
    ): DutyAssignment {

        return DB::transaction(function () use ($data) {

            $data['created_by'] = Auth::id();

            /*
            |--------------------------------------------------------------------------
            | Assigned By
            |--------------------------------------------------------------------------
            */

            if (
                empty($data['assigned_by']) &&
                !empty($data['driver_id'])
            ) {
                $data['assigned_by'] = Auth::id();
            }

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $data['status'] =
                $data['status']
                ?? DutyAssignment::STATUS_PENDING;

            return DutyAssignment::create($data);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        DutyAssignment $dutyAssignment,
        array $data
    ): DutyAssignment {

        return DB::transaction(function () use (
            $dutyAssignment,
            $data
        ) {

            $data['updated_by'] = Auth::id();

            $dutyAssignment->update($data);

            return $dutyAssignment->fresh([
                'travelRequest',
                'driver',
                'vehicle',
                'assignedBy',
                'createdBy',
                'updatedBy',
                'dutySlip',
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(
        DutyAssignment $dutyAssignment
    ): bool {

        return DB::transaction(function () use (
            $dutyAssignment
        ) {

            $dutyAssignment->deleted_by = Auth::id();

            $dutyAssignment->save();

            return $dutyAssignment->delete();
        });
    }
}