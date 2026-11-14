<?php

namespace App\Http\Controllers\backend\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\DutyReport\DutyReportService;
use Illuminate\Contracts\View\View;
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

        // Validate date range only when date_from is provided.
        if ($request->filled('date_from')) {
            $dateToRules[] = 'after_or_equal:date_from';
        }

        $filters = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:255',
            ],

            'driver_id' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'vehicle_id' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'client_id' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'status' => [
                'nullable',
                'in:pending,assigned,accepted,rejected,started,completed,cancelled',
            ],

            'date_from' => [
                'nullable',
                'date',
            ],

            'date_to' => $dateToRules,
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
