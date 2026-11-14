<?php

namespace App\Services\Reports\VehicleReport;

use App\Models\Client;
use App\Models\Driver;
use App\Models\DutyAssignment;
use App\Models\VehicleCategory;
use App\Models\VehicleManagement;
use App\Models\VehicleType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VehicleReportService
{
    /**
     * Get Vehicle Report Listing
     */
    public function getReport(
        array $filters = [],
        int $perPage = 10000000000000
    ): LengthAwarePaginator {

        $query = $this->buildQuery($filters);

        return $query
            ->paginate($perPage)
            ->withQueryString();
    }


    /**
     * Build Vehicle Report Query
     */
    protected function buildQuery(
        array $filters = []
    ): Builder {

        /*
        |--------------------------------------------------------------------------
        | Vehicle Model Query
        |--------------------------------------------------------------------------
        */

        $query = VehicleManagement::query();


        /*
        |--------------------------------------------------------------------------
        | EAGER LOAD RELATIONSHIPS
        |--------------------------------------------------------------------------
        */

        $query->with([
            'vehicleCategory',
            'vehicleType',
        ]);


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        |
        | Search by vehicle number, registration number,
        | chassis number, engine number, manufacturer,
        | model, color, remarks, category and type.
        |
        */

        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            if ($search !== '') {

                $searchTerm = '%' . $search . '%';

                $query->where(function (Builder $q) use (
                    $searchTerm
                ) {

                    $q->where(
                        'vehicle_number',
                        'LIKE',
                        $searchTerm
                    )

                    ->orWhere(
                        'registration_number',
                        'LIKE',
                        $searchTerm
                    )

                    ->orWhere(
                        'chassis_number',
                        'LIKE',
                        $searchTerm
                    )

                    ->orWhere(
                        'engine_number',
                        'LIKE',
                        $searchTerm
                    )

                    ->orWhere(
                        'manufacturer',
                        'LIKE',
                        $searchTerm
                    )

                    ->orWhere(
                        'model',
                        'LIKE',
                        $searchTerm
                    )

                    ->orWhere(
                        'color',
                        'LIKE',
                        $searchTerm
                    )

                    ->orWhere(
                        'remarks',
                        'LIKE',
                        $searchTerm
                    )

                    ->orWhereHas(
                        'vehicleCategory',
                        function (Builder $categoryQuery) use (
                            $searchTerm
                        ) {
                            $categoryQuery->where(
                                'name',
                                'LIKE',
                                $searchTerm
                            )->orWhere(
                                'code',
                                'LIKE',
                                $searchTerm
                            );
                        }
                    )

                    ->orWhereHas(
                        'vehicleType',
                        function (Builder $typeQuery) use (
                            $searchTerm
                        ) {
                            $typeQuery->where(
                                'name',
                                'LIKE',
                                $searchTerm
                            )->orWhere(
                                'code',
                                'LIKE',
                                $searchTerm
                            );
                        }
                    );
                });
            }
        }


        /*
        |--------------------------------------------------------------------------
        | VEHICLE CATEGORY FILTER
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['vehicle_category_id']) &&
            $filters['vehicle_category_id'] !== ''
        ) {

            $query->where(
                'vehicle_category_id',
                $filters['vehicle_category_id']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VEHICLE TYPE FILTER
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['vehicle_type_id']) &&
            $filters['vehicle_type_id'] !== ''
        ) {

            $query->where(
                'vehicle_type_id',
                $filters['vehicle_type_id']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DRIVER FILTER
        |--------------------------------------------------------------------------
        |
        | vehicle_management does not contain driver_id.
        | The relationship is available through duty_assignments.vehicle_id
        | and duty_assignments.driver_id.
        |
        */

        if (
            isset($filters['driver_id']) &&
            $filters['driver_id'] !== ''
        ) {

            $vehicleIds = DutyAssignment::query()
                ->select('vehicle_id')
                ->whereNotNull('vehicle_id')
                ->where(
                    'driver_id',
                    $filters['driver_id']
                );

            $query->whereIn(
                'id',
                $vehicleIds
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CLIENT FILTER
        |--------------------------------------------------------------------------
        |
        | vehicle_management and travel_requests do not contain client_id.
        | Client association is matched using:
        | clients.company_name = travel_requests.company_name
        | and duty_assignments connects travel_requests to vehicles.
        |
        */

        if (
            isset($filters['client_id']) &&
            $filters['client_id'] !== ''
        ) {

            $clientCompanyNames = Client::query()
                ->select('company_name')
                ->whereKey($filters['client_id'])
                ->whereNotNull('company_name');

            $vehicleIds = DutyAssignment::query()
                ->select('vehicle_id')
                ->whereNotNull('vehicle_id')
                ->whereHas(
                    'travelRequest',
                    function (Builder $travelRequestQuery) use (
                        $clientCompanyNames
                    ) {
                        $travelRequestQuery->whereIn(
                            'company_name',
                            $clientCompanyNames
                        );
                    }
                );

            $query->whereIn(
                'id',
                $vehicleIds
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VEHICLE STATUS FILTER
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
        | DATE FROM FILTER
        |--------------------------------------------------------------------------
        |
        | VehicleManagement has created_at as its available record date.
        |
        */

        if (!empty($filters['date_from'])) {

            $query->whereDate(
                'created_at',
                '>=',
                $filters['date_from']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DATE TO FILTER
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['date_to'])) {

            $query->whereDate(
                'created_at',
                '<=',
                $filters['date_to']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LATEST FIRST
        |--------------------------------------------------------------------------
        */

        $query->latest('id');

        return $query;
    }


    /**
     * Get Vehicle Report Filter Options
     */
    public function getFilterOptions(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | VEHICLE CATEGORIES
            |--------------------------------------------------------------------------
            */

            'categories' => $this->getCategories(),


            /*
            |--------------------------------------------------------------------------
            | VEHICLE TYPES
            |--------------------------------------------------------------------------
            */

            'types' => $this->getTypes(),


            /*
            |--------------------------------------------------------------------------
            | DRIVERS
            |--------------------------------------------------------------------------
            */

            'drivers' => $this->getDrivers(),


            /*
            |--------------------------------------------------------------------------
            | CLIENTS
            |--------------------------------------------------------------------------
            */

            'clients' => $this->getClients(),


            /*
            |--------------------------------------------------------------------------
            | VEHICLE STATUSES
            |--------------------------------------------------------------------------
            */

            'statuses' => $this->getStatuses(),

        ];
    }


    /**
     * Get Active Vehicle Categories
     */
    protected function getCategories()
    {
        return VehicleCategory::query()
            ->active()
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'code',
            ]);
    }


    /**
     * Get Active Vehicle Types
     */
    protected function getTypes()
    {
        return VehicleType::query()
            ->active()
            ->orderBy('name')
            ->get([
                'id',
                'vehicle_category_id',
                'name',
                'code',
            ]);
    }


    /**
     * Get Active Drivers
     */
    protected function getDrivers()
    {
        return Driver::query()
            ->active()
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
     * Get Active Clients
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


    /**
     * Get Available Vehicle Statuses
     */
    protected function getStatuses(): array
    {
        return [
            'active',
            'inactive',
            'maintenance',
            'sold',
        ];
    }
}
