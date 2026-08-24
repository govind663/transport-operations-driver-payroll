<?php

namespace App\Http\Controllers\backend\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\DutyReport\DutyReportService;
use Illuminate\Http\Request;

class DutyReportController extends Controller
{
    /**
     * Duty Report Service
     */
    protected DutyReportService $dutyReportService;


    /**
     * Constructor
     */
    public function __construct(
        DutyReportService $dutyReportService
    ) {
        $this->dutyReportService = $dutyReportService;
    }


    /**
     * Display Duty Report
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
            'driver_id',
            'vehicle_id',
            'client_id',
            'status',
            'date_from',
            'date_to',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Duty Report
        |--------------------------------------------------------------------------
        */
        $duties = $this->dutyReportService->getReport(
            $filters,
            15
        );


        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */
        $filterOptions = $this->dutyReportService
            ->getFilterOptions();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */
        return view('backend.reports.duties.index',
            compact(
                'duties',
                'filters',
                'filterOptions'
            )
        );
    }
}