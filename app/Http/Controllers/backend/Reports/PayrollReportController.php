<?php

namespace App\Http\Controllers\backend\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\PayrollReport\PayrollReportService;
use Illuminate\Http\Request;

class PayrollReportController extends Controller
{
    /**
     * Payroll Report Service
     */
    protected PayrollReportService $payrollReportService;


    /**
     * Constructor
     */
    public function __construct(
        PayrollReportService $payrollReportService
    ) {
        $this->payrollReportService = $payrollReportService;
    }


    /**
     * Display Payroll Report
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
            'month',
            'year',
            'status',
            'date_from',
            'date_to',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Payroll Report
        |--------------------------------------------------------------------------
        */
        $payrolls = $this->payrollReportService->getReport(
            $filters,
            15
        );


        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */
        $filterOptions = $this->payrollReportService
            ->getFilterOptions();


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */
        return view('backend.reports.payroll.index',
            compact(
                'payrolls',
                'filters',
                'filterOptions'
            )
        );
    }
}