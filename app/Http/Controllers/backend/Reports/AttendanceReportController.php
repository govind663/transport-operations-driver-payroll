<?php

namespace App\Http\Controllers\backend\Reports;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Reports\AttendanceReport\AttendanceReportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE REPORT SERVICE
    |--------------------------------------------------------------------------
    */

    protected AttendanceReportService $attendanceReportService;


    /*
    |--------------------------------------------------------------------------
    | CONSTRUCTOR
    |--------------------------------------------------------------------------
    */

    public function __construct(
        AttendanceReportService $attendanceReportService
    ) {
        $this->attendanceReportService = $attendanceReportService;
    }


    /*
    |--------------------------------------------------------------------------
    | DISPLAY ATTENDANCE REPORT
    |--------------------------------------------------------------------------
    */

    /**
     * Display monthly Driver-wise Attendance Report.
     *
     * Admin / Operations / Accountant:
     * - Can select any driver.
     * - Can view selected driver's attendance report.
     *
     * Driver:
     * - Can view only their own attendance report.
     * - Cannot select or access another driver's report.
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
            | MONTH
            |--------------------------------------------------------------------------
            */

            'month' => [
                'nullable',
                'date_format:Y-m',
            ],

        ];

        /*
         * Only Admin / Operations / Accountant
         * can submit a driver_id filter.
         *
         * Driver's driver_id parameter is ignored.
         */

        if (!$isDriver) {

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
        | DRIVER FILTER RESTRICTION
        |--------------------------------------------------------------------------
        */

        if ($isDriver) {

            /*
             * Never accept driver_id from Driver request.
             * AttendanceReportService resolves the authenticated
             * Driver profile automatically.
             */

            unset($filters['driver_id']);
        }


        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE REPORT
        |--------------------------------------------------------------------------
        */

        $attendanceReport = $this->attendanceReportService
            ->getReport($filters);


        /*
        |--------------------------------------------------------------------------
        | FILTER OPTIONS
        |--------------------------------------------------------------------------
        */

        $filterOptions = $this->attendanceReportService
            ->getFilterOptions();


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view('backend.reports.attendances.index',
            compact(
                'attendanceReport',
                'filters',
                'filterOptions'
            )
        );
    }
}
