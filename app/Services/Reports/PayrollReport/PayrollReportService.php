<?php

namespace App\Services\Reports\PayrollReport;

use Illuminate\Database\Eloquent\Builder;
use App\Models\SalaryProcessing;

class PayrollReportService
{
    /**
     * Get Payroll Report Listing
     */
    public function getReport(array $filters = [], int $perPage = 15)
    {
        $query = $this->buildQuery($filters);

        return $query
            ->paginate($perPage)
            ->withQueryString();
    }


    /**
     * Build Payroll Report Query
     */
    protected function buildQuery(array $filters = []): Builder
    {
        $query = SalaryProcessing::query();


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
                 * according to SalaryProcessing model.
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
            // after checking SalaryProcessing model.
        }


        /*
        |--------------------------------------------------------------------------
        | Month Filter
        |--------------------------------------------------------------------------
        */
        if (
            isset($filters['month']) &&
            $filters['month'] !== ''
        ) {

            // Actual month column will be added
            // after checking SalaryProcessing model.
        }


        /*
        |--------------------------------------------------------------------------
        | Year Filter
        |--------------------------------------------------------------------------
        */
        if (
            isset($filters['year']) &&
            $filters['year'] !== ''
        ) {

            // Actual year column will be added
            // after checking SalaryProcessing model.
        }


        /*
        |--------------------------------------------------------------------------
        | Payment Status Filter
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

            // Actual payroll date column will be added
            // after checking SalaryProcessing model.
        }


        /*
        |--------------------------------------------------------------------------
        | Date To
        |--------------------------------------------------------------------------
        */
        if (!empty($filters['date_to'])) {

            // Actual payroll date column will be added
            // after checking SalaryProcessing model.
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
     * Get Payroll Report Filter Options
     */
    public function getFilterOptions(): array
    {
        return [
            'statuses' => $this->getStatuses(),
            'months'   => $this->getMonths(),
            'years'    => $this->getYears(),
        ];
    }


    /**
     * Get Available Payroll Statuses
     */
    protected function getStatuses(): array
    {
        return [
            'pending',
            'paid',
            'cancelled',
        ];
    }


    /**
     * Get Month Options
     */
    protected function getMonths(): array
    {
        return [
            1  => 'January',
            2  => 'February',
            3  => 'March',
            4  => 'April',
            5  => 'May',
            6  => 'June',
            7  => 'July',
            8  => 'August',
            9  => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];
    }


    /**
     * Get Year Options
     */
    protected function getYears(): array
    {
        $currentYear = now()->year;

        return range(
            $currentYear - 5,
            $currentYear + 1
        );
    }
}