<?php

namespace App\Http\Controllers\backend\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\WorkingSheetReport\WorkingSheetReportService;
use Illuminate\Http\Request;

class WorkingSheetReportController extends Controller
{
    /**
     * Working Sheet Report Service
     */
    protected WorkingSheetReportService $workingSheetReportService;


    /**
     * Constructor
     */
    public function __construct(
        WorkingSheetReportService $workingSheetReportService
    ) {
        $this->workingSheetReportService = $workingSheetReportService;
    }


    /**
     * Display Working Sheet Report
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
        | Working Sheet Report
        |--------------------------------------------------------------------------
        */
        $workingSheets = $this->workingSheetReportService->getReport(
            $filters,
            15
        );


        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */
        $filterOptions = $this->workingSheetReportService
            ->getFilterOptions();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */
        return view('backend.reports.working-sheets.index',
            compact(
                'workingSheets',
                'filters',
                'filterOptions'
            )
        );
    }
}