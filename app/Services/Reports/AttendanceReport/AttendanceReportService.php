<?php

namespace App\Services\Reports\AttendanceReport;

use App\Models\User;
use App\Models\Driver;
use App\Models\DriverAttendance;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceReportService
{
    /*
    |--------------------------------------------------------------------------
    | GET ATTENDANCE REPORT
    |--------------------------------------------------------------------------
    */

    /**
     * Get Driver-wise Monthly Attendance Report.
     *
     * Driver:
     * - Automatically resolves logged-in driver's profile.
     * - Cannot access another driver's report.
     *
     * Admin / Operations / Accountant:
     * - Can select any driver.
     * - Can view selected driver's attendance report.
     */
    public function getReport(array $filters = []): array
    {
        /*
        |--------------------------------------------------------------------------
        | AUTHORIZATION
        |--------------------------------------------------------------------------
        */

        $user = $this->getAuthorizedUser();

        $isDriver = $user->role === User::ROLE_DRIVER;

        /*
        |--------------------------------------------------------------------------
        | MONTH VALIDATION
        |--------------------------------------------------------------------------
        */

        $month = $filters['month'] ?? now()->format('Y-m');

        abort_unless(
            is_string($month)
                && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/D', $month) === 1,
            422,
            'Invalid report month.'
        );

        try {
            $selectedMonth = Carbon::createFromFormat(
                '!Y-m',
                $month
            );
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
        | DRIVER RESOLUTION
        |--------------------------------------------------------------------------
        */

        if ($isDriver) {

            /*
             * Driver can only access their own profile.
             * Ignore driver_id supplied through request filters.
             */

            $driver = $this->getAuthenticatedDriver($user);

            $driverId = $driver->id;

        } else {

            /*
             * Admin / Operations / Accountant can select a driver.
             */

            $driverId = $filters['driver_id'] ?? null;

            $driver = null;

            if (!empty($driverId)) {

                $driver = Driver::query()
                    ->whereKey($driverId)
                    ->first();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | INITIALIZE REPORT DATA
        |--------------------------------------------------------------------------
        */

        $attendances = collect();

        $calendar = [];

        /*
        |--------------------------------------------------------------------------
        | MONTHLY ATTENDANCE RECORDS
        |--------------------------------------------------------------------------
        */

        if ($driver) {

            $attendances = DriverAttendance::query()
                ->where('driver_id', $driver->id)
                ->whereBetween(
                    'attendance_date',
                    [
                        $startDate->toDateString(),
                        $endDate->toDateString(),
                    ]
                )
                ->orderBy('attendance_date')
                ->get([
                    'id',
                    'driver_id',
                    'attendance_date',
                    'status',
                    'in_time',
                    'out_time',
                    'total_hours',
                    'source',
                ]);

            /*
            |--------------------------------------------------------------------------
            | MAP ATTENDANCE BY DATE
            |--------------------------------------------------------------------------
            */

            $attendanceByDate = $attendances->keyBy(
                fn ($attendance) => Carbon::parse(
                    $attendance->attendance_date
                )->toDateString()
            );

            /*
            |--------------------------------------------------------------------------
            | BUILD MONTHLY CALENDAR
            |--------------------------------------------------------------------------
            */

            for (
                $date = $startDate->copy();
                $date->lte($endDate);
                $date->addDay()
            ) {

                $dateKey = $date->toDateString();

                $attendance = $attendanceByDate->get($dateKey);

                $calendar[] = [
                    'date' => $date->copy(),

                    'date_key' => $dateKey,

                    'day' => $date->day,

                    'weekday' => $date->format('D'),

                    'is_marked' => $attendance !== null,

                    'status' => $attendance?->status,

                    'in_time' => $attendance?->in_time,

                    'out_time' => $attendance?->out_time,

                    'total_hours' => $attendance?->total_hours,

                    'source' => $attendance?->source,

                    'attendance_id' => $attendance?->id,
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | MONTHLY SUMMARY
        |--------------------------------------------------------------------------
        */

        $totalDays = $startDate->daysInMonth;

        $markedDays = $attendances->count();

        $notMarkedDays = $totalDays - $markedDays;

        $markingPercentage = $totalDays > 0
            ? round(($markedDays / $totalDays) * 100, 2)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | RETURN REPORT
        |--------------------------------------------------------------------------
        */

        return [
            'driver' => $driver,

            'month' => $selectedMonth,

            'calendar' => $calendar,

            'attendances' => $attendances,

            'summary' => [
                'total_days' => $totalDays,

                'marked_days' => $markedDays,

                'not_marked_days' => $notMarkedDays,

                'marking_percentage' => $markingPercentage,
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | GET FILTER OPTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get Driver Options for Attendance Report Filters.
     *
     * Driver:
     * - Receives only their own driver profile.
     *
     * Admin / Operations / Accountant:
     * - Receive all driver profiles.
     */
    public function getFilterOptions(): array
    {
        /*
        |--------------------------------------------------------------------------
        | AUTHORIZATION
        |--------------------------------------------------------------------------
        */

        $user = $this->getAuthorizedUser();

        /*
        |--------------------------------------------------------------------------
        | DRIVER OPTIONS
        |--------------------------------------------------------------------------
        */

        $query = Driver::query()
            ->select([
                'id',
                'driver_code',
                'first_name',
                'last_name',
            ]);

        if ($user->role === User::ROLE_DRIVER) {

            $driver = $this->getAuthenticatedDriver($user);

            /*
             * Restrict Driver filter options to their own profile.
             */

            $query->whereKey($driver->id);
        }

        return [
            'drivers' => $query
                ->orderBy('driver_code')
                ->get(),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | AUTHORIZED USER
    |--------------------------------------------------------------------------
    */

    /**
     * Get authenticated user and verify report access.
     *
     * Allowed Roles:
     * - Admin
     * - Operations
     * - Accountant
     * - Driver
     */
    private function getAuthorizedUser(): User
    {
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

        return $user;
    }


    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATED DRIVER
    |--------------------------------------------------------------------------
    */

    /**
     * Resolve authenticated user's Driver profile.
     */
    private function getAuthenticatedDriver(User $user): Driver
    {
        abort_unless(
            $user->role === User::ROLE_DRIVER,
            403,
            'Unauthorized driver access.'
        );

        $driver = $user->driver;

        abort_unless(
            $driver instanceof Driver,
            403,
            'Driver profile not found for this account.'
        );

        return $driver;
    }
}
