<?php

namespace App\Services\Reports\WorkingSheetReport;

use App\Models\User;
use App\Models\Driver;
use App\Models\WorkingSheet;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class WorkingSheetReportService
{
    /**
     * Get Driver-wise Monthly Working Sheet Report.
     *
     * Driver:
     * - Can only view their own report.
     *
     * Admin / Operations / Accountant:
     * - Can view any selected driver's report.
     */
    public function getReport(array $filters = []): array
    {
        /*
        |--------------------------------------------------------------------------
        | AUTHENTICATED USER
        |--------------------------------------------------------------------------
        */

        /** @var User|null $user */
        $user = Auth::user();

        abort_unless(
            $user instanceof User,
            403,
            'Unauthorized access.'
        );

        /*
        |--------------------------------------------------------------------------
        | ROLE ACCESS CONTROL
        |--------------------------------------------------------------------------
        */

        $isDriver = $user->isDriver();

        $hasReportAccess = $isDriver
            || $user->isAdmin()
            || $user->isOperations()
            || $user->isAccountant();

        abort_unless(
            $hasReportAccess,
            403,
            'You are not authorized to access this report.'
        );

        /*
        |--------------------------------------------------------------------------
        | SELECTED MONTH
        |--------------------------------------------------------------------------
        */

        $month = $filters['month'] ?? now()->format('Y-m');

        try {
            $selectedMonth = Carbon::createFromFormat('!Y-m', $month);
        } catch (\Throwable $exception) {
            abort(422, 'Invalid report month.');
        }

        abort_unless(
            $selectedMonth
                && $selectedMonth->format('Y-m') === $month,
            422,
            'Invalid report month.'
        );

        $startDate = $selectedMonth->copy()->startOfMonth();
        $endDate = $selectedMonth->copy()->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | DRIVER ACCESS CONTROL
        |--------------------------------------------------------------------------
        */

        $driver = null;

        $driverId = $filters['driver_id'] ?? null;

        if ($isDriver) {

            /*
             * Driver:
             * Ignore any driver_id supplied through URL or form.
             */

            $driver = $this->getAuthenticatedDriver();

            $driverId = $driver->id;

        } elseif (!empty($driverId)) {

            /*
             * Admin / Operations / Accountant:
             * Use selected driver.
             */

            $driver = Driver::query()
                ->whereKey($driverId)
                ->first();

        }

        /*
        |--------------------------------------------------------------------------
        | INITIALIZE REPORT DATA
        |--------------------------------------------------------------------------
        */

        $workingSheets = collect();

        $calendar = [];

        /*
        |--------------------------------------------------------------------------
        | FETCH MONTHLY WORKING SHEETS
        |--------------------------------------------------------------------------
        */

        if ($driver) {

            $driverId = $driver->id;

            $workingSheets = WorkingSheet::query()

                /*
                |--------------------------------------------------------------------------
                | RELATIONSHIPS
                |--------------------------------------------------------------------------
                */

                ->with([
                    'driver',
                    'dutySlip.driver',
                    'dutySlip.vehicle',
                    'dutySlip.dutyAssignment.driver',
                    'dutySlip.dutyAssignment.vehicle',
                    'dutySlip.dutyAssignment.travelRequest.client',
                ])

                /*
                |--------------------------------------------------------------------------
                | MONTH FILTER
                |--------------------------------------------------------------------------
                */

                ->whereBetween('work_date', [
                    $startDate->toDateString(),
                    $endDate->toDateString(),
                ])

                /*
                |--------------------------------------------------------------------------
                | DRIVER MATCHING
                |--------------------------------------------------------------------------
                |
                | 1. working_sheets.driver_id
                | 2. duty_slips.driver_id
                | 3. duty_assignments.driver_id
                |
                */

                ->where(function (Builder $query) use ($driverId) {

                    $query->where('driver_id', $driverId)

                        ->orWhereHas(
                            'dutySlip',
                            function (Builder $dutySlipQuery) use ($driverId) {

                                $dutySlipQuery->where(
                                    'driver_id',
                                    $driverId
                                )

                                ->orWhereHas(
                                    'dutyAssignment',
                                    function (Builder $assignmentQuery) use ($driverId) {

                                        $assignmentQuery->where(
                                            'driver_id',
                                            $driverId
                                        );

                                    }
                                );

                            }
                        );

                })

                /*
                |--------------------------------------------------------------------------
                | ORDERING
                |--------------------------------------------------------------------------
                */

                ->orderBy('work_date')
                ->orderBy('id')

                /*
                |--------------------------------------------------------------------------
                | FETCH ALL MATCHING RECORDS
                |--------------------------------------------------------------------------
                */

                ->get();

            /*
            |--------------------------------------------------------------------------
            | GROUP WORKING SHEETS BY DATE
            |--------------------------------------------------------------------------
            */

            $workingSheetsByDate = $workingSheets->groupBy(
                function ($sheet) {

                    return Carbon::parse(
                        $sheet->work_date
                    )->toDateString();

                }
            );

            /*
            |--------------------------------------------------------------------------
            | BUILD COMPLETE MONTHLY CALENDAR
            |--------------------------------------------------------------------------
            */

            for (
                $date = $startDate->copy();
                $date->lte($endDate);
                $date->addDay()
            ) {

                $dateKey = $date->toDateString();

                $daySheets = $workingSheetsByDate->get(
                    $dateKey,
                    collect()
                );

                $calendar[] = [

                    'date' => $date->copy(),

                    'date_key' => $dateKey,

                    'day' => $date->day,

                    'weekday' => $date->format('D'),

                    /*
                    |--------------------------------------------------------------------------
                    | WORKING STATUS
                    |--------------------------------------------------------------------------
                    */

                    'is_marked' => $daySheets->isNotEmpty(),

                    /*
                    |--------------------------------------------------------------------------
                    | ALL WORKING SHEETS FOR THIS DATE
                    |--------------------------------------------------------------------------
                    */

                    'working_sheets' => $daySheets,

                    'sheet_count' => $daySheets->count(),

                    /*
                    |--------------------------------------------------------------------------
                    | DAILY KM
                    |--------------------------------------------------------------------------
                    */

                    'total_km' => round(
                        (float) $daySheets->sum('total_km'),
                        2
                    ),

                    /*
                    |--------------------------------------------------------------------------
                    | DAILY WORKING HOURS
                    |--------------------------------------------------------------------------
                    */

                    'total_hours' => round(
                        (float) $daySheets->sum('total_hours'),
                        2
                    ),

                    /*
                    |--------------------------------------------------------------------------
                    | DAILY OVERTIME
                    |--------------------------------------------------------------------------
                    */

                    'overtime_hours' => round(
                        (float) $daySheets->sum('overtime_hours'),
                        2
                    ),

                    /*
                    |--------------------------------------------------------------------------
                    | DAILY AMOUNT
                    |--------------------------------------------------------------------------
                    */

                    'total_amount' => round(
                        (float) $daySheets->sum('total_amount'),
                        2
                    ),

                ];

            }

        }

        /*
        |--------------------------------------------------------------------------
        | MONTHLY SUMMARY
        |--------------------------------------------------------------------------
        */

        $totalDays = $startDate->daysInMonth;

        /*
         * A date is counted only once,
         * even when multiple Working Sheets exist on that date.
         */

        $markedDays = collect($calendar)
            ->where('is_marked', true)
            ->count();

        $notMarkedDays = $totalDays - $markedDays;

        $markingPercentage = $totalDays > 0
            ? round(($markedDays / $totalDays) * 100, 2)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | FINANCIAL AND WORKING SUMMARY
        |--------------------------------------------------------------------------
        */

        $summary = [

            'total_days' => $totalDays,

            'marked_days' => $markedDays,

            'not_marked_days' => $notMarkedDays,

            'marking_percentage' => $markingPercentage,

            /*
            |--------------------------------------------------------------------------
            | TOTAL WORKING SHEETS
            |--------------------------------------------------------------------------
            */

            'total_sheets' => $workingSheets->count(),

            /*
            |--------------------------------------------------------------------------
            | TOTAL KM
            |--------------------------------------------------------------------------
            */

            'total_km' => round(
                (float) $workingSheets->sum('total_km'),
                2
            ),

            /*
            |--------------------------------------------------------------------------
            | TOTAL WORKING HOURS
            |--------------------------------------------------------------------------
            */

            'total_hours' => round(
                (float) $workingSheets->sum('total_hours'),
                2
            ),

            /*
            |--------------------------------------------------------------------------
            | TOTAL OVERTIME HOURS
            |--------------------------------------------------------------------------
            */

            'overtime_hours' => round(
                (float) $workingSheets->sum('overtime_hours'),
                2
            ),

            /*
            |--------------------------------------------------------------------------
            | BASE AMOUNT
            |--------------------------------------------------------------------------
            */

            'base_amount' => round(
                (float) $workingSheets->sum('base_amount'),
                2
            ),

            /*
            |--------------------------------------------------------------------------
            | EXTRA KM AMOUNT
            |--------------------------------------------------------------------------
            */

            'extra_km_amount' => round(
                (float) $workingSheets->sum('extra_km_amount'),
                2
            ),

            /*
            |--------------------------------------------------------------------------
            | OVERTIME AMOUNT
            |--------------------------------------------------------------------------
            */

            'overtime_amount' => round(
                (float) $workingSheets->sum('overtime_amount'),
                2
            ),

            /*
            |--------------------------------------------------------------------------
            | ALLOWANCE AMOUNT
            |--------------------------------------------------------------------------
            */

            'allowance_amount' => round(
                (float) $workingSheets->sum('allowance_amount'),
                2
            ),

            /*
            |--------------------------------------------------------------------------
            | EXPENSE AMOUNT
            |--------------------------------------------------------------------------
            */

            'expense_amount' => round(
                (float) $workingSheets->sum('expense_amount'),
                2
            ),

            /*
            |--------------------------------------------------------------------------
            | OTHER AMOUNT
            |--------------------------------------------------------------------------
            */

            'other_amount' => round(
                (float) $workingSheets->sum('other_amount'),
                2
            ),

            /*
            |--------------------------------------------------------------------------
            | GRAND TOTAL
            |--------------------------------------------------------------------------
            */

            'total_amount' => round(
                (float) $workingSheets->sum('total_amount'),
                2
            ),

        ];

        /*
        |--------------------------------------------------------------------------
        | RETURN REPORT
        |--------------------------------------------------------------------------
        */

        return [

            'driver' => $driver,

            'month' => $selectedMonth,

            'calendar' => $calendar,

            'working_sheets' => $workingSheets,

            'summary' => $summary,

        ];

    }

    /**
     * Get Report Filter Options.
     *
     * Driver:
     * Only receives their own driver profile.
     *
     * Admin / Operations / Accountant:
     * Receive all available driver options.
     */
    public function getFilterOptions(): array
    {
        /** @var User|null $user */
        $user = Auth::user();

        abort_unless(
            $user instanceof User,
            403,
            'Unauthorized access.'
        );

        $isDriver = $user->isDriver();

        $hasReportAccess = $isDriver
            || $user->isAdmin()
            || $user->isOperations()
            || $user->isAccountant();

        abort_unless(
            $hasReportAccess,
            403,
            'You are not authorized to access this report.'
        );

        /*
        |--------------------------------------------------------------------------
        | DRIVER ROLE
        |--------------------------------------------------------------------------
        */

        if ($isDriver) {

            $driver = $this->getAuthenticatedDriver();

            $drivers = Driver::query()

                ->select([
                    'id',
                    'driver_code',
                    'first_name',
                    'last_name',
                ])

                ->whereKey($driver->id)

                ->get();

        } else {

            /*
            |--------------------------------------------------------------------------
            | ADMIN / OPERATIONS / ACCOUNTANT
            |--------------------------------------------------------------------------
            */

            $drivers = Driver::query()

                ->select([
                    'id',
                    'driver_code',
                    'first_name',
                    'last_name',
                ])

                ->orderBy('first_name')
                ->orderBy('last_name')

                ->get();

        }

        /*
        |--------------------------------------------------------------------------
        | RETURN FILTER OPTIONS
        |--------------------------------------------------------------------------
        */

        return [

            'drivers' => $drivers,

            'statuses' => WorkingSheet::statuses(),

        ];

    }

    /**
     * Resolve Logged-in Driver Profile.
     *
     * Relationship:
     * users.id = drivers.user_id
     */
    protected function getAuthenticatedDriver(): Driver
    {
        /** @var User|null $user */
        $user = Auth::user();

        abort_unless(
            $user instanceof User && $user->isDriver(),
            403,
            'Unauthorized driver access.'
        );

        /*
        |--------------------------------------------------------------------------
        | GET LINKED DRIVER PROFILE
        |--------------------------------------------------------------------------
        */

        $driver = $user->driver;

        abort_unless(
            $driver instanceof Driver,
            403,
            'Driver profile not found.'
        );

        return $driver;
    }
}
