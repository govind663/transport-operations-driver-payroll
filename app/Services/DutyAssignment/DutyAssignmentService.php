<?php

namespace App\Services\DutyAssignment;

use App\Models\DutyAssignment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DutyAssignmentService
{
    /*
    |--------------------------------------------------------------------------
    | GET DUTY ASSIGNMENTS
    |--------------------------------------------------------------------------
    |
    | Role based visibility:
    |
    | admin       -> All assignments
    | operations  -> All assignments
    | accountant  -> All assignments
    | driver      -> Only own driver assignments
    |
    */

    public function getDutyAssignments(): Collection
    {
        return $this->accessibleDutyAssignmentsQuery()
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
    |
    | Driver can only access own linked assignments.
    | Other allowed roles can access all assignments.
    |
    */

    public function findById(
        string|int $id
    ): DutyAssignment {
        return $this->accessibleDutyAssignmentsQuery()
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

            /*
            |--------------------------------------------------------------------------
            | Created By
            |--------------------------------------------------------------------------
            */

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
                    strtoupper(
                        trim(
                            $data['assignment_no']
                        )
                    );
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

                $data['assigned_by'] =
                    Auth::id();
            }


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $data['status'] =
                $data['status']
                ?? DutyAssignment::STATUS_PENDING;


            /*
            |--------------------------------------------------------------------------
            | Create Duty Assignment
            |--------------------------------------------------------------------------
            */

            $dutyAssignment =
                DutyAssignment::create($data);


            /*
            |--------------------------------------------------------------------------
            | Fresh Model
            |--------------------------------------------------------------------------
            */

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
                        bin2hex(
                            random_bytes(4)
                        ),
                        0,
                        6
                    )
                );

        } while (
            DutyAssignment::withTrashed()
                ->where(
                    'assignment_no',
                    $assignmentNo
                )
                ->exists()
        );

        return $assignmentNo;
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    |
    | Driver can update only own accessible assignment.
    | Admin / Operations / Accountant can update all.
    |
    */

    public function update(
        DutyAssignment $dutyAssignment,
        array $data
    ): DutyAssignment {
        return DB::transaction(
            function () use (
                $dutyAssignment,
                $data
            ) {

                /*
                |--------------------------------------------------------------------------
                | Security Check
                |--------------------------------------------------------------------------
                |
                | Re-fetch assignment using role based scope.
                |
                */

                $dutyAssignment =
                    $this->findById(
                        $dutyAssignment->id
                    );


                /*
                |--------------------------------------------------------------------------
                | Updated By
                |--------------------------------------------------------------------------
                */

                $data['updated_by'] =
                    Auth::id();


                /*
                |--------------------------------------------------------------------------
                | Update
                |--------------------------------------------------------------------------
                */

                $dutyAssignment->update(
                    $data
                );


                /*
                |--------------------------------------------------------------------------
                | Fresh Model
                |--------------------------------------------------------------------------
                */

                return $dutyAssignment->fresh([
                    'travelRequest',
                    'driver',
                    'vehicle',
                    'assignedBy',
                    'createdBy',
                    'updatedBy',
                    'dutySlip',
                ]);
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    |
    | Driver can delete only own accessible assignment.
    | Admin / Operations / Accountant can delete all assignments.
    |
    */

    public function delete(
        DutyAssignment $dutyAssignment
    ): bool {
        return DB::transaction(
            function () use (
                $dutyAssignment
            ) {

                /*
                |--------------------------------------------------------------------------
                | Security Check
                |--------------------------------------------------------------------------
                */

                $dutyAssignment =
                    $this->findById(
                        $dutyAssignment->id
                    );


                /*
                |--------------------------------------------------------------------------
                | Deleted By
                |--------------------------------------------------------------------------
                */

                $dutyAssignment->deleted_by =
                    Auth::id();


                $dutyAssignment->save();


                /*
                |--------------------------------------------------------------------------
                | Soft Delete
                |--------------------------------------------------------------------------
                */

                return $dutyAssignment->delete();
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACCESSIBLE DUTY ASSIGNMENTS QUERY
    |--------------------------------------------------------------------------
    |
    | Centralized role based visibility.
    |
    */

    protected function accessibleDutyAssignmentsQuery(): Builder
    {
        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $query = DutyAssignment::query();


        /*
        |--------------------------------------------------------------------------
        | DRIVER ROLE
        |--------------------------------------------------------------------------
        |
        | Only assignments linked to the authenticated user's
        | Driver master record.
        |
        | drivers.user_id = auth user id
        |
        */

        if (
            $user &&
            $user->role === 'driver'
        ) {

            $query->whereHas(
                'driver',
                function (Builder $driverQuery) use ($user) {

                    $driverQuery->where(
                        'user_id',
                        $user->id
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN / OPERATIONS / ACCOUNTANT
        |--------------------------------------------------------------------------
        |
        | No restriction.
        | All Duty Assignments are returned.
        |
        */


        return $query;
    }
}