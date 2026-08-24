<?php

namespace App\Services\Reports\VehicleReport;

use App\Models\VehicleManagement;
use Illuminate\Database\Eloquent\Builder;

class VehicleReportService
{
    /**
     * Get Vehicle Report Listing
     */
    public function getReport(array $filters = [], int $perPage = 15)
    {
        $query = $this->buildQuery($filters);

        return $query
            ->paginate($perPage)
            ->withQueryString();
    }


    /**
     * Build Vehicle Report Query
     */
    protected function buildQuery(array $filters = []): Builder
    {
        $query = VehicleManagement::query();


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            $query->where(function (Builder $q) use ($search) {

                /*
                 * Actual searchable columns will be added
                 * according to VehicleManagement model.
                 */

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Vehicle Category Filter
        |--------------------------------------------------------------------------
        */
        if (
            isset($filters['vehicle_category_id']) &&
            $filters['vehicle_category_id'] !== ''
        ) {

            // Actual category foreign key will be added
            // after checking VehicleManagement model.
        }


        /*
        |--------------------------------------------------------------------------
        | Vehicle Type Filter
        |--------------------------------------------------------------------------
        */
        if (
            isset($filters['vehicle_type_id']) &&
            $filters['vehicle_type_id'] !== ''
        ) {

            // Actual type foreign key will be added
            // after checking VehicleManagement model.
        }


        /*
        |--------------------------------------------------------------------------
        | Driver Filter
        |--------------------------------------------------------------------------
        */
        if (
            isset($filters['driver_id']) &&
            $filters['driver_id'] !== ''
        ) {

            // Actual driver foreign key will be added
            // after checking VehicleManagement model.
        }


        /*
        |--------------------------------------------------------------------------
        | Client Filter
        |--------------------------------------------------------------------------
        */
        if (
            isset($filters['client_id']) &&
            $filters['client_id'] !== ''
        ) {

            // Actual client foreign key will be added
            // after checking VehicleManagement model.
        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
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
        | Date From
        |--------------------------------------------------------------------------
        */
        if (!empty($filters['date_from'])) {

            // Actual vehicle date column will be added
            // after checking VehicleManagement model.
        }


        /*
        |--------------------------------------------------------------------------
        | Date To
        |--------------------------------------------------------------------------
        */
        if (!empty($filters['date_to'])) {

            // Actual vehicle date column will be added
            // after checking VehicleManagement model.
        }


        /*
        |--------------------------------------------------------------------------
        | Latest First
        |--------------------------------------------------------------------------
        */
        $query->latest();

        return $query;
    }


    /**
     * Get Vehicle Report Filter Options
     */
    public function getFilterOptions(): array
    {
        return [
            'statuses' => $this->getStatuses(),
        ];
    }


    /**
     * Get Available Vehicle Statuses
     */
    protected function getStatuses(): array
    {
        return [
            'active',
            'inactive',
        ];
    }
}