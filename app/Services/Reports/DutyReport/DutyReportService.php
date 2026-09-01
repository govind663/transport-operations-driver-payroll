<?php

namespace App\Services\Reports\DutyReport;

use App\Models\Client;
use App\Models\Driver;
use App\Models\DutyAssignment;
use App\Models\VehicleManagement;
use Illuminate\Database\Eloquent\Builder;

class DutyReportService
{
    /**
     * Get Duty Report Listing
     */
    public function getReport(
        array $filters = [],
        int $perPage = 15
    ) {
        $query = $this->buildQuery($filters);

        return $query
            ->paginate($perPage)
            ->withQueryString();
    }


    /**
     * Build Duty Report Query
     */
    protected function buildQuery(
        array $filters = []
    ): Builder {

        $query = DutyAssignment::query()
            ->with([
                'driver',
                'vehicle',
                'travelRequest.client',
            ]);


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
        | - Vehicle Number
        | - Registration Number
        | - Client Company Name
        |
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            $query->where(function (Builder $q) use ($search) {

                /*
                |--------------------------------------------------------------------------
                | Duty Assignment Fields
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
                | Driver Search
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
                | Vehicle Search
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
                | Client Search
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
        | DRIVER FILTER
        |--------------------------------------------------------------------------
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
        | Client is connected through:
        |
        | duty_assignments
        |       ↓
        | travel_requests
        |       ↓
        | clients
        |
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['client_id']) &&
            $filters['client_id'] !== ''
        ) {

            $query->whereHas(
                'travelRequest',
                function (Builder $travelRequestQuery) use ($filters) {

                    $travelRequestQuery->where(
                        'client_id',
                        $filters['client_id']
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
        | DATE FROM
        |--------------------------------------------------------------------------
        |
        | Duty date is based on reporting_time.
        |
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['date_from'])) {

            $query->whereDate(
                'reporting_time',
                '>=',
                $filters['date_from']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DATE TO
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['date_to'])) {

            $query->whereDate(
                'reporting_time',
                '<=',
                $filters['date_to']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LATEST FIRST
        |--------------------------------------------------------------------------
        */

        $query->orderByDesc('reporting_time')
            ->orderByDesc('id');


        return $query;
    }


    /**
     * Get Duty Report Filter Options
     */
    public function getFilterOptions(): array
    {
        return [
            'drivers' => $this->getDrivers(),

            'vehicles' => $this->getVehicles(),

            'clients' => $this->getClients(),

            'statuses' => $this->getStatuses(),
        ];
    }


    /**
     * Get Active Drivers
     */
    protected function getDrivers()
    {
        return Driver::query()
            ->where('status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get([
                'id',
                'driver_code',
                'first_name',
                'last_name',
            ]);
    }


    /**
     * Get Active Vehicles
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


    /**
     * Get Active Clients
     */
    protected function getClients()
    {
        return Client::query()
            ->where('status', 1)
            ->orderBy('company_name')
            ->get([
                'id',
                'client_code',
                'company_name',
                'contact_person',
            ]);
    }


    /**
     * Get Available Duty Statuses
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
}