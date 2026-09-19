<?php

namespace App\Services\DutyAssignment;

use App\Models\DutyAssignment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DutyAssignmentService
{
    /*
    |--------------------------------------------------------------------------
    | GET DUTY ASSIGNMENTS
    |--------------------------------------------------------------------------
    */

    public function getDutyAssignments(): Collection
    {
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
            ->get();
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
            | Assignment Number
            |--------------------------------------------------------------------------
            */

            if (empty($data['assignment_no'])) {
                $data['assignment_no'] =
                    $this->generateAssignmentNumber();
            } else {
                $data['assignment_no'] =
                    strtoupper(trim($data['assignment_no']));
            }

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

            $dutyAssignment = DutyAssignment::create($data);

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
    | GENERATE ASSIGNMENT NUMBER
    |--------------------------------------------------------------------------
    */

    protected function generateAssignmentNumber(): string
    {
        do {
            $assignmentNo =
                'DUTY-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(
                    substr(
                        bin2hex(random_bytes(4)),
                        0,
                        6
                    )
                );

        } while (
            DutyAssignment::withTrashed()
                ->where('assignment_no', $assignmentNo)
                ->exists()
        );

        return $assignmentNo;
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