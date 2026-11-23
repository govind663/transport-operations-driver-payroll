<?php

namespace App\Services\DutyAssignment;

use App\Models\DutyAssignment;
use App\Models\Driver;
use App\Models\User;
use App\Models\VehicleManagement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DutyAssignmentService
{
    /**
     * Get authenticated user.
     */
    protected function authenticatedUser(): User
    {
        $user = Auth::user();

        abort_unless(
            $user instanceof User,
            403,
            'Unauthenticated access.'
        );

        return $user;
    }

    /**
     * Check management role access.
     */
    protected function authorizeManagementAccess(): User
    {
        $user = $this->authenticatedUser();

        abort_unless(
            $user->isAdmin()
            || $user->isOperations()
            || $user->isAccountant(),
            403,
            'Unauthorized access.'
        );

        return $user;
    }

    /**
     * Get authenticated driver's profile.
     */
    protected function getAuthenticatedDriver(): Driver
    {
        $user = $this->authenticatedUser();

        abort_unless(
            $user->isDriver(),
            403,
            'Only drivers can access this resource.'
        );

        $driver = $user->driver;

        abort_unless(
            $driver instanceof Driver,
            403,
            'Driver profile not found.'
        );

        return $driver;
    }

    /*
    |--------------------------------------------------------------------------
    | GET DUTY ASSIGNMENTS
    |--------------------------------------------------------------------------
    |
    | Supports:
    | - Search
    | - Driver
    | - Vehicle
    | - Status
    | - Assigned Date From
    | - Assigned Date To
    |
    | Driver can only access own assignments.
    |
    */

    public function getDutyAssignments(
        array $filters = []
    ): Collection {
        return $this->applyFilters(
            $this->accessibleDutyAssignmentsQuery(),
            $filters
        )
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
    | APPLY FILTERS
    |--------------------------------------------------------------------------
    */

    protected function applyFilters(
        Builder $query,
        array $filters = []
    ): Builder {

        $user = $this->authenticatedUser();

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            $query->where(function (Builder $searchQuery) use ($search) {

                $searchQuery
                    ->where('assignment_no', 'like', "%{$search}%")
                    ->orWhere('reporting_location', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%")

                    ->orWhereHas('driver', function (Builder $driverQuery) use ($search) {

                        $driverQuery->where(function (Builder $driverSearch) use ($search) {

                            $driverSearch
                                ->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('driver_code', 'like', "%{$search}%");
                        });
                    })

                    ->orWhereHas('vehicle', function (Builder $vehicleQuery) use ($search) {

                        $vehicleQuery->where(function (Builder $vehicleSearch) use ($search) {

                            $vehicleSearch
                                ->where('vehicle_number', 'like', "%{$search}%")
                                ->orWhere('registration_number', 'like', "%{$search}%")
                                ->orWhere('vehicle_name', 'like', "%{$search}%")
                                ->orWhere('vehicle_model', 'like', "%{$search}%");
                        });
                    })

                    ->orWhereHas('travelRequest', function (Builder $travelQuery) use ($search) {

                        $travelQuery->where(function (Builder $travelSearch) use ($search) {

                            $travelSearch
                                ->where('request_no', 'like', "%{$search}%")
                                ->orWhere('passenger_name', 'like', "%{$search}%");
                        });
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | DRIVER FILTER
        |--------------------------------------------------------------------------
        |
        | Management roles can select a driver.
        | Driver cannot change driver_id through request.
        |
        */

        if (!$user->isDriver() && !empty($filters['driver_id'])) {

            $query->where(
                'driver_id',
                $filters['driver_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | VEHICLE FILTER
        |--------------------------------------------------------------------------
        */

        if (!$user->isDriver() && !empty($filters['vehicle_id'])) {

            $query->where(
                'vehicle_id',
                $filters['vehicle_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['status'])) {

            $query->where(
                'status',
                $filters['status']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ASSIGNED DATE FROM
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['date_from'])) {

            $query->whereDate(
                'assigned_at',
                '>=',
                $filters['date_from']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ASSIGNED DATE TO
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['date_to'])) {

            $query->whereDate(
                'assigned_at',
                '<=',
                $filters['date_to']
            );
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | GET FILTER OPTIONS
    |--------------------------------------------------------------------------
    */

    public function getFilterOptions(): array
    {
        $user = $this->authenticatedUser();

        $drivers = collect();

        $vehicles = collect();

        /*
        |--------------------------------------------------------------------------
        | MANAGEMENT FILTER OPTIONS
        |--------------------------------------------------------------------------
        */

        if (
            $user->isAdmin()
            || $user->isOperations()
            || $user->isAccountant()
        ) {

            $drivers = Driver::query()
                ->select([
                    'id',
                    'driver_code',
                    'first_name',
                    'last_name',
                ])
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get();

            $vehicles = VehicleManagement::query()
                ->orderBy('id', 'desc')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | DRIVER FILTER OPTION
        |--------------------------------------------------------------------------
        */

        if ($user->isDriver()) {

            $drivers = collect([
                $this->getAuthenticatedDriver(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS OPTIONS
        |--------------------------------------------------------------------------
        */

        $statuses = [
            DutyAssignment::STATUS_PENDING,
            DutyAssignment::STATUS_ASSIGNED,
            DutyAssignment::STATUS_ACCEPTED,
            DutyAssignment::STATUS_REJECTED,
            DutyAssignment::STATUS_STARTED,
            DutyAssignment::STATUS_COMPLETED,
            DutyAssignment::STATUS_CANCELLED,
        ];

        return [
            'drivers' => $drivers,
            'vehicles' => $vehicles,
            'statuses' => $statuses,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | FIND DUTY ASSIGNMENT
    |--------------------------------------------------------------------------
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
        $user = $this->authorizeManagementAccess();

        return DB::transaction(function () use ($data, $user) {

            $data['created_by'] = $user->id;

            /*
            |--------------------------------------------------------------------------
            | ASSIGNMENT NUMBER
            |--------------------------------------------------------------------------
            */

            if (empty($data['assignment_no'])) {

                $data['assignment_no'] =
                    $this->generateAssignmentNumber();

            } else {

                $data['assignment_no'] = strtoupper(
                    trim($data['assignment_no'])
                );
            }

            /*
            |--------------------------------------------------------------------------
            | ASSIGNED BY
            |--------------------------------------------------------------------------
            */

            if (
                empty($data['assigned_by'])
                && !empty($data['driver_id'])
            ) {
                $data['assigned_by'] = $user->id;
            }

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $data['status'] =
                $data['status']
                ?? DutyAssignment::STATUS_PENDING;

            /*
            |--------------------------------------------------------------------------
            | CREATE
            |--------------------------------------------------------------------------
            */

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
                'DUTY-'
                . now()->format('Ymd')
                . '-'
                . strtoupper(
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
        $user = $this->authenticatedUser();

        return DB::transaction(function () use (
            $dutyAssignment,
            $data,
            $user
        ) {

            $dutyAssignment = $this->findById(
                $dutyAssignment->id
            );

            if ($user->isDriver()) {

                $driver = $this->getAuthenticatedDriver();

                $data['driver_id'] = $driver->id;

                unset(
                    $data['created_by'],
                    $data['assigned_by'],
                    $data['deleted_by']
                );

            } else {

                $this->authorizeManagementAccess();
            }

            $data['updated_by'] = $user->id;

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
        $user = $this->authenticatedUser();

        return DB::transaction(function () use (
            $dutyAssignment,
            $user
        ) {

            $dutyAssignment = $this->findById(
                $dutyAssignment->id
            );

            if ($user->isDriver()) {

                $this->getAuthenticatedDriver();

            } else {

                $this->authorizeManagementAccess();
            }

            $dutyAssignment->deleted_by = $user->id;

            $dutyAssignment->save();

            return $dutyAssignment->delete();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSIBLE DUTY ASSIGNMENTS QUERY
    |--------------------------------------------------------------------------
    */

    protected function accessibleDutyAssignmentsQuery(): Builder
    {
        $user = $this->authenticatedUser();

        $query = DutyAssignment::query();

        /*
        |--------------------------------------------------------------------------
        | DRIVER
        |--------------------------------------------------------------------------
        */

        if ($user->isDriver()) {

            $driver = $this->getAuthenticatedDriver();

            return $query->where(
                'driver_id',
                $driver->id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MANAGEMENT ROLES
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $user->isAdmin()
                || $user->isOperations()
                || $user->isAccountant(),
            403,
            'You are not authorized to access duty assignments.'
        );

        return $query;
    }
}
