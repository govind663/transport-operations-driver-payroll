<?php

namespace App\Http\Controllers\backend\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\DriverReport\DriverReportService;
use Illuminate\Http\Request;

class DriverReportController extends Controller
{
    /**
     * Driver Report Service
     */
    protected DriverReportService $driverReportService;


    /**
     * Constructor
     */
    public function __construct(
        DriverReportService $driverReportService
    ) {
        $this->driverReportService = $driverReportService;
    }


    /**
     * Display Driver Report
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
            'status',
            'date_from',
            'date_to',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Driver Report
        |--------------------------------------------------------------------------
        */
        $drivers = $this->driverReportService->getReport(
            $filters,
            15
        );


        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */
        $filterOptions = $this->driverReportService
            ->getFilterOptions();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */
        return view('backend.reports.drivers.index',
            compact(
                'drivers',
                'filters',
                'filterOptions'
            )
        );
    }
}