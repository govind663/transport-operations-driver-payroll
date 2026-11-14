<?php

namespace App\Http\Controllers\backend\Reports;

use App\Http\Controllers\Controller;
use App\Services\Reports\VehicleReport\VehicleReportService;
use Illuminate\Contracts\View\View;
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
            | Vehicle Category
            |--------------------------------------------------------------------------
            */

            'vehicle_category_id' => [
                'nullable',
                'integer',
                'min:1',
            ],


            /*
            |--------------------------------------------------------------------------
            | Vehicle Type
            |--------------------------------------------------------------------------
            */

            'vehicle_type_id' => [
                'nullable',
                'integer',
                'min:1',
            ],


            /*
            |--------------------------------------------------------------------------
            | Driver
            |--------------------------------------------------------------------------
            */

            'driver_id' => [
                'nullable',
                'integer',
                'min:1',
            ],


            /*
            |--------------------------------------------------------------------------
            | Client
            |--------------------------------------------------------------------------
            */

            'client_id' => [
                'nullable',
                'integer',
                'min:1',
            ],


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => [
                'nullable',
                'string',
                'max:50',
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
