<?php

namespace App\Http\Controllers\backend\Reports;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Reports\DutyReport\DutyReportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DutyReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DUTY REPORT SERVICE
    |--------------------------------------------------------------------------
    */

    protected DutyReportService $dutyReportService;


    /*
    |--------------------------------------------------------------------------
    | CONSTRUCTOR
    |--------------------------------------------------------------------------
    */

    public function __construct(
        DutyReportService $dutyReportService
    ) {
        $this->dutyReportService = $dutyReportService;
    }


    /*
    |--------------------------------------------------------------------------
    | DISPLAY DUTY REPORT
    |--------------------------------------------------------------------------
    */

    /**
     * Display Duty Report.
     *
     * Admin / Operations / Accountant:
     * - Can view all drivers' duty assignments.
     * - Can filter by any driver.
     *
     * Driver:
     * - Can view only their own duty assignments.
     * - Cannot select another driver.
     * - Supplied driver_id is ignored.
     */
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | AUTHENTICATION AND AUTHORIZATION
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        abort_unless(
            $user instanceof User,
            403,
            'You are not authorized to access this report.'
        );

        $hasReportAccess = in_array(
            $user->role,
            [
                User::ROLE_ADMIN,
                User::ROLE_OPERATIONS,
                User::ROLE_ACCOUNTANT,
                User::ROLE_DRIVER,
            ],
            true
        );

        abort_unless(
            $hasReportAccess,
            403,
            'You are not authorized to access this report.'
        );

        $isDriver = $user->role === User::ROLE_DRIVER;


        /*
        |--------------------------------------------------------------------------
        | REPORT FILTER VALIDATION
        |--------------------------------------------------------------------------
        */

        $rules = [

            /*
            |--------------------------------------------------------------------------
            | SEARCH
            |--------------------------------------------------------------------------
            */

            'search' => [
                'nullable',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | VEHICLE
            |--------------------------------------------------------------------------
            */

            'vehicle_id' => [
                'nullable',
                'integer',
                'min:1',
            ],


            /*
            |--------------------------------------------------------------------------
            | CLIENT
            |--------------------------------------------------------------------------
            */

            'client_id' => [
                'nullable',
                'integer',
                'min:1',
            ],


            /*
            |--------------------------------------------------------------------------
            | DUTY STATUS
            |--------------------------------------------------------------------------
            */

            'status' => [
                'nullable',
                'in:pending,assigned,accepted,rejected,started,completed,cancelled',
            ],


            /*
            |--------------------------------------------------------------------------
            | DATE FROM
            |--------------------------------------------------------------------------
            */

            'date_from' => [
                'nullable',
                'date',
            ],


            /*
            |--------------------------------------------------------------------------
            | DATE TO
            |--------------------------------------------------------------------------
            */

            'date_to' => [
                'nullable',
                'date',
                'after_or_equal:date_from',
            ],

        ];


        /*
        |--------------------------------------------------------------------------
        | DRIVER FILTER
        |--------------------------------------------------------------------------
        */

        if (!$isDriver) {

            /*
             * Admin / Operations / Accountant
             * can select any valid Driver.
             */

            $rules['driver_id'] = [
                'nullable',
                'integer',
                'min:1',
                'exists:drivers,id',
            ];
        }

        /*
         * Driver ke liye driver_id validation rule add nahi hoga.
         * Isliye request mein doosra driver_id bhejne par bhi
         * woh validated filters mein include nahi hoga.
         */


        /*
        |--------------------------------------------------------------------------
        | VALIDATE REQUEST
        |--------------------------------------------------------------------------
        */

        $filters = $request->validate($rules);


        /*
        |--------------------------------------------------------------------------
        | DRIVER FILTER RESTRICTION
        |--------------------------------------------------------------------------
        */

        if ($isDriver) {

            /*
             * Never accept driver_id from Driver request.
             * DutyReportService automatically resolves
             * authenticated Driver profile.
             */

            unset($filters['driver_id']);
        }


        /*
        |--------------------------------------------------------------------------
        | DUTY REPORT
        |--------------------------------------------------------------------------
        */

        $duties = $this->dutyReportService->getReport(
            $filters,
            15
        );


        /*
        |--------------------------------------------------------------------------
        | FILTER OPTIONS
        |--------------------------------------------------------------------------
        */

        $filterOptions = $this->dutyReportService
            ->getFilterOptions();


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
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
