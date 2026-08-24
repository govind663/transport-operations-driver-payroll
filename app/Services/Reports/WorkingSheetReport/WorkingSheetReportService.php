<?php

namespace App\Services\Reports\WorkingSheetReport;

use App\Models\WorkingSheet;
use Illuminate\Database\Eloquent\Builder;

class WorkingSheetReportService
{
    /**
     * Get Working Sheet Report Listing
     */
    public function getReport(array $filters = [], int $perPage = 15)
    {
        $query = $this->buildQuery($filters);

        return $query
            ->paginate($perPage)
            ->withQueryString();
    }


    /**
     * Build Working Sheet Report Query
     */
    protected function buildQuery(array $filters = []): Builder
    {
        $query = WorkingSheet::query();


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
                 * according to WorkingSheet model.
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
            // after checking WorkingSheet model.
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
            // after checking WorkingSheet model.
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
            // after checking WorkingSheet model.
        }


        /*
        |--------------------------------------------------------------------------
        | Date From
        |--------------------------------------------------------------------------
        */
        if (!empty($filters['date_from'])) {

            // Actual working sheet date column will be added
            // after checking WorkingSheet model.
        }


        /*
        |--------------------------------------------------------------------------
        | Date To
        |--------------------------------------------------------------------------
        */
        if (!empty($filters['date_to'])) {

            // Actual working sheet date column will be added
            // after checking WorkingSheet model.
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
        | Latest First
        |--------------------------------------------------------------------------
        */
        $query->latest();

        return $query;
    }


    /**
     * Get Working Sheet Report Filter Options
     */
    public function getFilterOptions(): array
    {
        return [
            'statuses' => $this->getStatuses(),
        ];
    }


    /**
     * Get Available Working Sheet Statuses
     */
    protected function getStatuses(): array
    {
        return [
            'pending',
            'approved',
            'completed',
            'cancelled',
        ];
    }
}