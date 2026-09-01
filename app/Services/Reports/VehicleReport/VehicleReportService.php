<?php

namespace App\Services\Reports\VehicleReport;

use App\Models\Client;
use App\Models\Driver;
use App\Models\VehicleCategory;
use App\Models\VehicleManagement;
use App\Models\VehicleType;
use Illuminate\Database\Eloquent\Builder;

class VehicleReportService
{
    /**
     * Get Vehicle Report Listing
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
     * Build Vehicle Report Query
     */
    protected function buildQuery(
        array $filters = []
    ): Builder {

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
        | model, color and remarks.
        |
        */

        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            $query->where(function (Builder $q) use ($search) {

                $q->where(
                    'vehicle_number',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'registration_number',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'chassis_number',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'engine_number',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'manufacturer',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'model',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'color',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'remarks',
                    'like',
                    "%{$search}%"
                );
            });
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
        | NOTE:
        | vehicle_management table does NOT contain driver_id.
        |
        | Driver filtering will be implemented through
        | DutyAssignment once the complete DutyAssignment /
        | TravelRequest relationship structure is available.
        |
        */

        /*
        |--------------------------------------------------------------------------
        | CLIENT FILTER
        |--------------------------------------------------------------------------
        |
        | NOTE:
        | vehicle_management table does NOT contain client_id.
        |
        | Client filtering will be implemented through
        | DutyAssignment / TravelRequest relationship.
        |
        */


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
        | vehicle_management does not have a dedicated
        | vehicle date column.
        |
        | created_at is therefore used.
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
            ->where('status', 1)
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
            ->where('status', 1)
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