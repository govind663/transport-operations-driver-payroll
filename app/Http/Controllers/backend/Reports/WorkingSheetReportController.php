<?php

namespace App\Http\Controllers\backend\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\WorkingSheetReport\WorkingSheetReportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class WorkingSheetReportController extends Controller
{
    protected WorkingSheetReportService $workingSheetReportService;

    public function __construct(
        WorkingSheetReportService $workingSheetReportService
    ) {
        $this->workingSheetReportService = $workingSheetReportService;
    }


    /**
     * Display Driver-wise Monthly Working Sheet Report.
     *
     * Driver:
     * Automatically sees their own report.
     *
     * Admin / Operations / Accountant:
     * Can select any driver.
     */
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | AUTHENTICATED USER
        |--------------------------------------------------------------------------
        */

        $user = $request->user();

        abort_unless(
            $user,
            401,
            'Unauthenticated access.'
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDATE REQUEST
        |--------------------------------------------------------------------------
        */

        $rules = [

            'month' => [
                'nullable',
                'date_format:Y-m',
            ],

        ];


        /*
        |--------------------------------------------------------------------------
        | DRIVER ID VALIDATION
        |--------------------------------------------------------------------------
        |
        | Non-driver users can select a driver.
        | Driver users cannot choose another driver.
        |
        |--------------------------------------------------------------------------
        */

        if (!$user->isDriver()) {

            $rules['driver_id'] = [

                'nullable',

                'integer',

                'exists:drivers,id',

            ];

        }


        $filters = $request->validate($rules);


        /*
        |--------------------------------------------------------------------------
        | DEFAULT MONTH
        |--------------------------------------------------------------------------
        */

        $filters['month'] = $filters['month']
            ?? now()->format('Y-m');


        /*
        |--------------------------------------------------------------------------
        | DRIVER ACCESS CONTROL
        |--------------------------------------------------------------------------
        |
        | Always resolve Driver ID from the authenticated User.
        | Ignore any driver_id supplied by a Driver in the URL.
        |
        |--------------------------------------------------------------------------
        */

        if ($user->isDriver()) {

            $driver = $user->driver;

            abort_unless(
                $driver,
                403,
                'Your Driver profile is not linked to your account.'
            );

            $filters['driver_id'] = $driver->id;

        }


        /*
        |--------------------------------------------------------------------------
        | GET WORKING SHEET REPORT
        |--------------------------------------------------------------------------
        */

        $workingSheetReport = $this->workingSheetReportService
            ->getReport($filters);


        /*
        |--------------------------------------------------------------------------
        | GET FILTER OPTIONS
        |--------------------------------------------------------------------------
        */

        $filterOptions = $this->workingSheetReportService
            ->getFilterOptions();


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'backend.reports.working-sheets.index',
            compact(
                'workingSheetReport',
                'filters',
                'filterOptions'
            )
        );
    }
}
