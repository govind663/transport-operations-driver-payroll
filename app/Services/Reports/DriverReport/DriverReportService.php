<?php

namespace App\Services\Reports\DriverReport;

use App\Models\Driver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DriverReportService
{
    /**
     * Get Driver Report Listing
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
     * Build Driver Report Query
     */
    protected function buildQuery(
        array $filters = []
    ): Builder {

        /*
        |--------------------------------------------------------------------------
        | Driver Model Query
        |--------------------------------------------------------------------------
        */

        $query = Driver::query();


        /*
        |--------------------------------------------------------------------------
        | Search Filter
        |--------------------------------------------------------------------------
        |
        | Searchable Fields:
        | - Driver Code
        | - First Name
        | - Last Name
        | - Full Name
        | - Father Name
        | - Mobile
        | - Alternate Mobile
        | - Email
        | - License Number
        | - City
        | - State
        | - Pincode
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
                        'driver_code',
                        'LIKE',
                        $searchTerm
                    )

                    ->orWhere(
                        'first_name',
                        'LIKE',
                        $searchTerm
                    )

                    ->orWhere(
                        'last_name',
                        'LIKE',
                        $searchTerm
                    )

                    ->orWhereRaw(
                        "CONCAT_WS(' ', first_name, last_name) LIKE ?",
                        [$searchTerm]
                    )

                    ->orWhere(
                        'father_name',
                        'LIKE',
                        $searchTerm
                    )

                    ->orWhere(
                        'mobile',
                        'LIKE',
                        $searchTerm
                    )

                    ->orWhere(
                        'alternate_mobile',
                        'LIKE',
                        $searchTerm
                    )

                    ->orWhere(
                        'email',
                        'LIKE',
                        $searchTerm
                    )

                    ->orWhere(
                        'license_number',
                        'LIKE',
                        $searchTerm
                    )

                    ->orWhere(
                        'city',
                        'LIKE',
                        $searchTerm
                    )

                    ->orWhere(
                        'state',
                        'LIKE',
                        $searchTerm
                    )

                    ->orWhere(
                        'pincode',
                        'LIKE',
                        $searchTerm
                    );

                });

            }
        }


        /*
        |--------------------------------------------------------------------------
        | Employment Status Filter
        |--------------------------------------------------------------------------
        |
        | Available Statuses:
        | - active
        | - on_leave
        | - notice_period
        | - resigned
        | - terminated
        | - inactive
        |
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
        | Date From Filter
        |--------------------------------------------------------------------------
        |
        | Filter drivers by joining_date.
        |
        */

        if (!empty($filters['date_from'])) {

            $query->whereDate(
                'joining_date',
                '>=',
                $filters['date_from']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Date To Filter
        |--------------------------------------------------------------------------
        |
        | Filter drivers by joining_date.
        |
        */

        if (!empty($filters['date_to'])) {

            $query->whereDate(
                'joining_date',
                '<=',
                $filters['date_to']
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
     * Get Driver Report Filter Options
     */
    public function getFilterOptions(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Employment Statuses
            |--------------------------------------------------------------------------
            */

            'statuses' => $this->getStatuses(),

        ];
    }


    /**
     * Get Available Driver Statuses
     */
    protected function getStatuses(): array
    {
        return Driver::EMPLOYMENT_STATUSES;
    }
}
