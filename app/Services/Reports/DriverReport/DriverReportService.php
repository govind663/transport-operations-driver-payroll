<?php

namespace App\Services\Reports\DriverReport;

use Illuminate\Database\Eloquent\Builder;

class DriverReportService
{
    /**
     * Get Driver Report Listing
     */
    public function getReport(array $filters = [], int $perPage = 15)
    {
        $query = $this->buildQuery($filters);

        return $query
            ->paginate($perPage)
            ->withQueryString();
    }


    /**
     * Build Driver Report Query
     */
    protected function buildQuery(array $filters = []): Builder
    {
        /*
        |--------------------------------------------------------------------------
        | Driver Model Query
        |--------------------------------------------------------------------------
        |
        | Driver model and its actual relationships/columns will be added
        | here once the Driver model structure is confirmed.
        |
        */

        $query = \App\Models\Driver::query();


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            $query->where(function (Builder $q) use ($search) {

                // Actual searchable columns will be added
                // according to Driver model.

            });
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

            // Actual date column will be added
            // after checking Driver model.
        }


        /*
        |--------------------------------------------------------------------------
        | Date To
        |--------------------------------------------------------------------------
        */
        if (!empty($filters['date_to'])) {

            // Actual date column will be added
            // after checking Driver model.
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
     * Get Driver Report Filter Options
     */
    public function getFilterOptions(): array
    {
        return [
            'statuses' => $this->getStatuses(),
        ];
    }


    /**
     * Get Available Driver Statuses
     */
    protected function getStatuses(): array
    {
        return [
            'active',
            'inactive',
        ];
    }
}