<?php

namespace App\Services\Reports\PayrollReport;

use App\Models\Driver;
use App\Models\SalaryProcessing;
use Illuminate\Database\Eloquent\Builder;

class PayrollReportService
{
    /*
    |--------------------------------------------------------------------------
    | GET PAYROLL REPORT
    |--------------------------------------------------------------------------
    */

    /**
     * Get Payroll Report Listing.
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


    /*
    |--------------------------------------------------------------------------
    | BUILD QUERY
    |--------------------------------------------------------------------------
    */

    /**
     * Build Payroll Report Query.
     */
    protected function buildQuery(
        array $filters = []
    ): Builder {

        $query = SalaryProcessing::query()
            ->with([
                'driver',
                'processedBy',
                'approvedBy',
                'paidBy',
            ]);


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = trim(
                $filters['search']
            );

            $query->where(function (Builder $q) use ($search) {

                /*
                |--------------------------------------------------------------------------
                | Salary Processing Search
                |--------------------------------------------------------------------------
                */

                $q->where(
                    'role',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'status',
                    'like',
                    "%{$search}%"
                );


                /*
                |--------------------------------------------------------------------------
                | Driver Search
                |--------------------------------------------------------------------------
                */

                $q->orWhereHas(
                    'driver',
                    function (Builder $driverQuery) use ($search) {

                        $driverQuery
                            ->where(
                                'driver_code',
                                'like',
                                "%{$search}%"
                            )

                            ->orWhere(
                                'first_name',
                                'like',
                                "%{$search}%"
                            )

                            ->orWhere(
                                'last_name',
                                'like',
                                "%{$search}%"
                            )

                            ->orWhere(
                                'mobile',
                                'like',
                                "%{$search}%"
                            )

                            ->orWhere(
                                'email',
                                'like',
                                "%{$search}%"
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
        | ROLE FILTER
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['role']) &&
            $filters['role'] !== ''
        ) {

            $query->where(
                'role',
                $filters['role']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | MONTH FILTER
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['month']) &&
            $filters['month'] !== ''
        ) {

            $query->where(
                'salary_month',
                $filters['month']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | YEAR FILTER
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['year']) &&
            $filters['year'] !== ''
        ) {

            $query->where(
                'salary_year',
                $filters['year']
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
        | PAYMENT DATE FROM
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['date_from'])) {

            $query->whereDate(
                'payment_date',
                '>=',
                $filters['date_from']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAYMENT DATE TO
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['date_to'])) {

            $query->whereDate(
                'payment_date',
                '<=',
                $filters['date_to']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LATEST FIRST
        |--------------------------------------------------------------------------
        */

        $query
            ->orderByDesc('salary_year')
            ->orderByDesc('salary_month')
            ->orderByDesc('id');


        return $query;
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER OPTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get Payroll Report Filter Options.
     */
    public function getFilterOptions(): array
    {
        return [
            'drivers'  => $this->getDrivers(),
            'statuses' => $this->getStatuses(),
            'roles'    => $this->getRoles(),
            'months'   => $this->getMonths(),
            'years'    => $this->getYears(),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | DRIVER OPTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get Available Drivers.
     *
     * Only active drivers are displayed in the filter.
     */
    protected function getDrivers()
    {
        return Driver::query()
            ->select([
                'id',
                'driver_code',
                'first_name',
                'last_name',
                'mobile',
            ])
            ->where(
                'status',
                'active'
            )
            ->orderBy(
                'first_name'
            )
            ->orderBy(
                'last_name'
            )
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS OPTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get Available Payroll Statuses.
     */
    protected function getStatuses(): array
    {
        return SalaryProcessing::STATUSES;
    }


    /*
    |--------------------------------------------------------------------------
    | ROLE OPTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get Available Payroll Roles.
     */
    protected function getRoles(): array
    {
        return [
            SalaryProcessing::ROLE_DRIVER,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | MONTH OPTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get Month Options.
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


    /*
    |--------------------------------------------------------------------------
    | YEAR OPTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get Year Options.
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