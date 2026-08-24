<?php

namespace App\Http\Controllers\backend\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\VehicleReport\VehicleReportService;
use Illuminate\Http\Request;

class VehicleReportController extends Controller
{
    /**
     * Vehicle Report Service
     */
    protected VehicleReportService $vehicleReportService;


    /**
     * Constructor
     */
    public function __construct(
        VehicleReportService $vehicleReportService
    ) {
        $this->vehicleReportService = $vehicleReportService;
    }


    /**
     * Display Vehicle Report
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Report Filters
        |--------------------------------------------------------------------------
        */
        $filters = $request->only([
            'search',
            'vehicle_category_id',
            'vehicle_type_id',
            'driver_id',
            'client_id',
            'status',
            'date_from',
            'date_to',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Vehicle Report
        |--------------------------------------------------------------------------
        */
        $vehicles = $this->vehicleReportService->getReport(
            $filters,
            15
        );


        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */
        $filterOptions = $this->vehicleReportService
            ->getFilterOptions();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */
        return view('backend.reports.vehicles.index',
            compact(
                'vehicles',
                'filters',
                'filterOptions'
            )
        );
    }
}