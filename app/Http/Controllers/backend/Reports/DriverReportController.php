<?php

namespace App\Http\Controllers\backend\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\DriverReport\DriverReportService;
use Illuminate\Contracts\View\View;
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
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Report Filter Validation
        |--------------------------------------------------------------------------
        */

        $dateToRules = [
            'nullable',
            'date',
        ];

        if ($request->filled('date_from')) {
            $dateToRules[] = 'after_or_equal:date_from';
        }


        /*
        |--------------------------------------------------------------------------
        | Report Filters
        |--------------------------------------------------------------------------
        */

        $filters = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Search
            |--------------------------------------------------------------------------
            */

            'search' => [
                'nullable',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | Driver Status
            |--------------------------------------------------------------------------
            */

            'status' => [
                'nullable',
                'in:active,on_leave,notice_period,resigned,terminated,inactive',
            ],


            /*
            |--------------------------------------------------------------------------
            | Date From
            |--------------------------------------------------------------------------
            */

            'date_from' => [
                'nullable',
                'date',
            ],


            /*
            |--------------------------------------------------------------------------
            | Date To
            |--------------------------------------------------------------------------
            */

            'date_to' => $dateToRules,

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
