<?php

namespace App\Services\Reports\DutyReport;

use App\Models\User;
use App\Models\Client;
use App\Models\Driver;
use App\Models\DutyAssignment;
use App\Models\VehicleManagement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DutyReportService
{
    /*
    |--------------------------------------------------------------------------
    | GET DUTY REPORT
    |--------------------------------------------------------------------------
    */

    /**
     * Get Duty Report Listing.
     *
     * Admin / Operations / Accountant:
     * - Can view all drivers' duty assignments.
     * - Can filter by any driver.
     *
     * Driver:
     * - Can view only their own duty assignments.
     * - Cannot access another driver's duties.
     * - Supplied driver_id filter is ignored.
     */
    public function getReport(
        array $filters = [],
        int $perPage = 10000000000000
    ) {
        $query = $this->buildQuery($filters);

        return $query
            ->paginate($perPage)
            ->withQueryString();
    }


    /*
    |--------------------------------------------------------------------------
    | BUILD DUTY REPORT QUERY
    |--------------------------------------------------------------------------
    */

    /**
     * Build Duty Report Query.
     */
    protected function buildQuery(
        array $filters = []
    ): Builder {

        /*
        |--------------------------------------------------------------------------
        | AUTHORIZATION
        |--------------------------------------------------------------------------
        */

        $user = $this->getAuthorizedUser();

        $isDriver = $user->role === User::ROLE_DRIVER;


        /*
        |--------------------------------------------------------------------------
        | BASE QUERY
        |--------------------------------------------------------------------------
        */

        $query = DutyAssignment::query()
            ->with([
                'driver',
                'vehicle',
                'travelRequest.client',
            ]);


        /*
        |--------------------------------------------------------------------------
        | DRIVER ACCESS RESTRICTION
        |--------------------------------------------------------------------------
        */

        if ($isDriver) {

            /*
             * Driver can only access their own duty assignments.
             * Ignore driver_id supplied through request filters.
             */

            $driver = $this->getAuthenticatedDriver($user);

            $query->where(
                'driver_id',
                $driver->id
            );

        } else {

            /*
             * Admin / Operations / Accountant
             * can select any driver.
             */

            if (
                isset($filters['driver_id']) &&
                $filters['driver_id'] !== ''
            ) {

                $query->where(
                    'driver_id',
                    $filters['driver_id']
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        |
        | Search by:
        | - Assignment No
        | - Reporting Location
        | - Remarks
        | - Driver Code
        | - Driver Name
        | - Driver Mobile
        | - Vehicle Number
        | - Registration Number
        | - Manufacturer
        | - Vehicle Model
        | - Client Company Name
        | - Client Code
        | - Contact Person
        |
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            $query->where(function (Builder $q) use ($search) {

                /*
                |--------------------------------------------------------------------------
                | DUTY ASSIGNMENT FIELDS
                |--------------------------------------------------------------------------
                */

                $q->where(
                    'assignment_no',
                    'like',
                    '%' . $search . '%'
                );

                $q->orWhere(
                    'reporting_location',
                    'like',
                    '%' . $search . '%'
                );

                $q->orWhere(
                    'remarks',
                    'like',
                    '%' . $search . '%'
                );


                /*
                |--------------------------------------------------------------------------
                | DRIVER SEARCH
                |--------------------------------------------------------------------------
                */

                $q->orWhereHas(
                    'driver',
                    function (Builder $driverQuery) use ($search) {

                        $driverQuery->where(
                            'driver_code',
                            'like',
                            '%' . $search . '%'
                        );

                        $driverQuery->orWhere(
                            'first_name',
                            'like',
                            '%' . $search . '%'
                        );

                        $driverQuery->orWhere(
                            'last_name',
                            'like',
                            '%' . $search . '%'
                        );

                        $driverQuery->orWhere(
                            'mobile',
                            'like',
                            '%' . $search . '%'
                        );
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | VEHICLE SEARCH
                |--------------------------------------------------------------------------
                */

                $q->orWhereHas(
                    'vehicle',
                    function (Builder $vehicleQuery) use ($search) {

                        $vehicleQuery->where(
                            'vehicle_number',
                            'like',
                            '%' . $search . '%'
                        );

                        $vehicleQuery->orWhere(
                            'registration_number',
                            'like',
                            '%' . $search . '%'
                        );

                        $vehicleQuery->orWhere(
                            'manufacturer',
                            'like',
                            '%' . $search . '%'
                        );

                        $vehicleQuery->orWhere(
                            'model',
                            'like',
                            '%' . $search . '%'
                        );
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | CLIENT SEARCH
                |--------------------------------------------------------------------------
                |
                | Duty Assignment
                |       ↓
                | Travel Request
                |       ↓
                | Client
                |
                |--------------------------------------------------------------------------
                */

                $q->orWhereHas(
                    'travelRequest.client',
                    function (Builder $clientQuery) use ($search) {

                        $clientQuery->where(
                            'company_name',
                            'like',
                            '%' . $search . '%'
                        );

                        $clientQuery->orWhere(
                            'client_code',
                            'like',
                            '%' . $search . '%'
                        );

                        $clientQuery->orWhere(
                            'contact_person',
                            'like',
                            '%' . $search . '%'
                        );
                    }
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | VEHICLE FILTER
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['vehicle_id']) &&
            $filters['vehicle_id'] !== ''
        ) {

            $query->where(
                'vehicle_id',
                $filters['vehicle_id']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CLIENT FILTER
        |--------------------------------------------------------------------------
        |
        | Client is resolved through:
        | Duty Assignment -> Travel Request -> Client
        |
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['client_id']) &&
            $filters['client_id'] !== ''
        ) {

            $query->whereHas(
                'travelRequest.client',
                function (Builder $clientQuery) use ($filters) {

                    $clientQuery->whereKey(
                        $filters['client_id']
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DATE FROM
        |--------------------------------------------------------------------------
        |
        | Travel date is based on travel_requests.travel_from_date.
        | reporting_time is a TIME column, not a DATE column.
        |
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['date_from'])) {

            $query->whereHas(
                'travelRequest',
                function (Builder $travelRequestQuery) use ($filters) {

                    $travelRequestQuery->whereDate(
                        'travel_from_date',
                        '>=',
                        $filters['date_from']
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DATE TO
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['date_to'])) {

            $query->whereHas(
                'travelRequest',
                function (Builder $travelRequestQuery) use ($filters) {

                    $travelRequestQuery->whereDate(
                        'travel_from_date',
                        '<=',
                        $filters['date_to']
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['status']) &&
            $filters['status'] !== ''
        ) {

            $query->where(
                'status',
                $filters['status']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LATEST FIRST
        |--------------------------------------------------------------------------
        */

        $query->orderByDesc('assigned_at')
            ->orderByDesc('id');


        /*
        |--------------------------------------------------------------------------
        | RETURN QUERY
        |--------------------------------------------------------------------------
        */

        return $query;
    }


    /*
    |--------------------------------------------------------------------------
    | GET FILTER OPTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get Duty Report Filter Options.
     *
     * Driver:
     * - Receives only their own Driver profile.
     *
     * Admin / Operations / Accountant:
     * - Receive all active Driver profiles.
     *
     * Vehicle, Client and Status options retain
     * the existing report filter structure.
     */
    public function getFilterOptions(): array
    {
        /*
        |--------------------------------------------------------------------------
        | AUTHORIZATION
        |--------------------------------------------------------------------------
        */

        $user = $this->getAuthorizedUser();


        /*
        |--------------------------------------------------------------------------
        | RETURN FILTER OPTIONS
        |--------------------------------------------------------------------------
        */

        return [

            'drivers' => $this->getDrivers($user),

            'vehicles' => $this->getVehicles(),

            'clients' => $this->getClients(),

            'statuses' => $this->getStatuses(),

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | GET DRIVERS
    |--------------------------------------------------------------------------
    */

    /**
     * Get Driver Options.
     *
     * Driver:
     * - Only their own Driver profile.
     *
     * Other authorized roles:
     * - All active Drivers.
     */
    protected function getDrivers(User $user)
    {
        $query = Driver::query();

        if ($user->role === User::ROLE_DRIVER) {

            $driver = $this->getAuthenticatedDriver($user);

            /*
             * Restrict Driver filter to authenticated Driver profile.
             */

            $query->whereKey($driver->id);

        } else {

            /*
             * Preserve existing active Driver filter options.
             */

            $query->where('status', 'active');
        }

        return $query
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get([
                'id',
                'driver_code',
                'first_name',
                'last_name',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | GET ACTIVE VEHICLES
    |--------------------------------------------------------------------------
    */

    /**
     * Get Active Vehicles.
     */
    protected function getVehicles()
    {
        return VehicleManagement::query()
            ->where('status', 'active')
            ->with([
                'vehicleCategory:id,name',
                'vehicleType:id,name',
            ])
            ->orderBy('vehicle_number')
            ->get([
                'id',
                'vehicle_category_id',
                'vehicle_type_id',
                'vehicle_number',
                'registration_number',
                'manufacturer',
                'model',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | GET ACTIVE CLIENTS
    |--------------------------------------------------------------------------
    */

    /**
     * Get Active Clients.
     */
    protected function getClients()
    {
        return Client::query()
            ->active()
            ->orderBy('company_name')
            ->get([
                'id',
                'client_code',
                'company_name',
                'contact_person',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | GET AVAILABLE DUTY STATUSES
    |--------------------------------------------------------------------------
    */

    /**
     * Get Available Duty Statuses.
     */
    protected function getStatuses(): array
    {
        return [
            'pending',
            'assigned',
            'accepted',
            'rejected',
            'started',
            'completed',
            'cancelled',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | AUTHORIZED USER
    |--------------------------------------------------------------------------
    */

    /**
     * Get authenticated user and verify Duty Report access.
     *
     * Allowed Roles:
     * - Admin
     * - Operations
     * - Accountant
     * - Driver
     */
    private function getAuthorizedUser(): User
    {
        $user = Auth::user();

        abort_unless(
            $user instanceof User,
            403,
            'You are not authorized to access this report.'
        );

        $hasReportAccess = in_array(
            $user->role,
            [
                User::ROLE_ADMIN,
                User::ROLE_OPERATIONS,
                User::ROLE_ACCOUNTANT,
                User::ROLE_DRIVER,
            ],
            true
        );

        abort_unless(
            $hasReportAccess,
            403,
            'You are not authorized to access this report.'
        );

        return $user;
    }


    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATED DRIVER
    |--------------------------------------------------------------------------
    */

    /**
     * Resolve authenticated user's Driver profile.
     */
    private function getAuthenticatedDriver(User $user): Driver
    {
        abort_unless(
            $user->role === User::ROLE_DRIVER,
            403,
            'Unauthorized driver access.'
        );

        $driver = $user->driver;

        abort_unless(
            $driver instanceof Driver,
            403,
            'Driver profile not found for this account.'
        );

        return $driver;
    }
}
