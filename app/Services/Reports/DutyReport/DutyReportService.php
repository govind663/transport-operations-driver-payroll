<?php

namespace App\Services\Reports\DutyReport;

use Illuminate\Database\Eloquent\Builder;
use App\Models\DutyAssignment;

class DutyReportService
{
    /**
     * Get Duty Report Listing
     */
    public function getReport(array $filters = [], int $perPage = 15)
    {
        $query = $this->buildQuery($filters);

        return $query
            ->paginate($perPage)
            ->withQueryString();
    }


    /**
     * Build Duty Report Query
     */
    protected function buildQuery(array $filters = []): Builder
    {
        $query = DutyAssignment::query();


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
                 * according to DutyAssignment model.
                 */

            });
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
            // after checking DutyAssignment model.
        }


        /*
        |--------------------------------------------------------------------------
        | Vehicle Filter
        |--------------------------------------------------------------------------
        */
        if (
            isset($filters['vehicle_id']) &&
            $filters['vehicle_id'] !== ''
        ) {

            // Actual vehicle foreign key will be added
            // after checking DutyAssignment model.
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
            // after checking DutyAssignment model.
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

            // Actual duty date column will be added
            // after checking DutyAssignment model.
        }


        /*
        |--------------------------------------------------------------------------
        | Date To
        |--------------------------------------------------------------------------
        */
        if (!empty($filters['date_to'])) {

            // Actual duty date column will be added
            // after checking DutyAssignment model.
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
     * Get Duty Report Filter Options
     */
    public function getFilterOptions(): array
    {
        return [
            'statuses' => $this->getStatuses(),
        ];
    }


    /**
     * Get Available Duty Statuses
     */
    protected function getStatuses(): array
    {
        return [
            'pending',
            'assigned',
            'in_progress',
            'completed',
            'cancelled',
        ];
    }
}