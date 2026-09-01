<?php

namespace App\Services\Reports\WorkingSheetReport;

use App\Models\Client;
use App\Models\Driver;
use App\Models\VehicleManagement;
use App\Models\WorkingSheet;
use Illuminate\Database\Eloquent\Builder;

class WorkingSheetReportService
{
    /**
     * Get Working Sheet Report Listing
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
     * Build Working Sheet Report Query
     */
    protected function buildQuery(
        array $filters = []
    ): Builder {

        $query = WorkingSheet::query()
            ->with([
                'dutySlip.driver',
                'dutySlip.vehicle',
                'dutySlip.client',
            ]);


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        |
        | Search by:
        | - Sheet No
        | - Remarks
        | - Duty Slip No
        | - Driver Code / Name
        | - Vehicle Number / Registration Number
        | - Client Code / Company Name
        |
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            $query->where(function (Builder $q) use ($search) {

                /*
                |--------------------------------------------------------------------------
                | Working Sheet Fields
                |--------------------------------------------------------------------------
                */

                $q->where(
                    'sheet_no',
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
                | Duty Slip Search
                |--------------------------------------------------------------------------
                */

                $q->orWhereHas(
                    'dutySlip',
                    function (Builder $dutySlipQuery) use ($search) {

                        /*
                        | Duty Slip Number
                        |
                        | Assuming duty_slips table has duty_slip_no.
                        */

                        $dutySlipQuery->where(
                            'duty_slip_no',
                            'like',
                            '%' . $search . '%'
                        );
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Driver Search
                |--------------------------------------------------------------------------
                */

                $q->orWhereHas(
                    'dutySlip.driver',
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
                    'dutySlip.vehicle',
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
                    'dutySlip.client',
                    function (Builder $clientQuery) use ($search) {

                        $clientQuery->where(
                            'client_code',
                            'like',
                            '%' . $search . '%'
                        );

                        $clientQuery->orWhere(
                            'company_name',
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
        |
        | working_sheets
        |      ↓
        | duty_slips
        |      ↓
        | driver_id
        |
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['driver_id']) &&
            $filters['driver_id'] !== ''
        ) {

            $query->whereHas(
                'dutySlip',
                function (Builder $dutySlipQuery) use ($filters) {

                    $dutySlipQuery->where(
                        'driver_id',
                        $filters['driver_id']
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VEHICLE FILTER
        |--------------------------------------------------------------------------
        |
        | working_sheets
        |      ↓
        | duty_slips
        |      ↓
        | vehicle_id
        |
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['vehicle_id']) &&
            $filters['vehicle_id'] !== ''
        ) {

            $query->whereHas(
                'dutySlip',
                function (Builder $dutySlipQuery) use ($filters) {

                    $dutySlipQuery->where(
                        'vehicle_id',
                        $filters['vehicle_id']
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CLIENT FILTER
        |--------------------------------------------------------------------------
        |
        | working_sheets
        |      ↓
        | duty_slips
        |      ↓
        | client_id
        |
        |--------------------------------------------------------------------------
        */

        if (
            isset($filters['client_id']) &&
            $filters['client_id'] !== ''
        ) {

            $query->whereHas(
                'dutySlip',
                function (Builder $dutySlipQuery) use ($filters) {

                    $dutySlipQuery->where(
                        'client_id',
                        $filters['client_id']
                    );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DATE FROM
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['date_from'])) {

            $query->whereDate(
                'work_date',
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
                'work_date',
                '<=',
                $filters['date_to']
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
        | LATEST FIRST
        |--------------------------------------------------------------------------
        */

        $query->orderByDesc('work_date')
            ->orderByDesc('id');


        return $query;
    }


    /**
     * Get Working Sheet Report Filter Options
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
     * Get Available Working Sheet Statuses
     */
    protected function getStatuses(): array
    {
        return [
            'draft',
            'submitted',
            'approved',
            'rejected',
            'completed',
        ];
    }
}