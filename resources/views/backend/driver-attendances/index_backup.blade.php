@extends('backend.layouts.master')

@section('title')
    {{ $isDriver ? 'My Attendance' : 'Driver Attendance' }}
@endsection

@push('styles')

@if(!$isDriver)
<link rel="stylesheet" href="{{ asset('backend/assets/datatable/css/dataTables-responsive.css') }}">
@endif

<style>
    /* ================================================================
       DRIVER ATTENDANCE - COMMON THEME
    ================================================================ */
    .driver-attendance-page {
        --attendance-primary: #2457a7;
        --attendance-primary-light: #edf4ff;
        --attendance-border: #e8edf5;
        --attendance-muted: #718096;
        --attendance-text: #344054;
    }

    .driver-attendance-page .page-header {
        margin-bottom: 22px;
        padding: 22px 24px;
        background: linear-gradient(115deg, #fff 0%, #f5f8ff 100%);
        border: 1px solid var(--attendance-border);
        border-radius: 14px;
        box-shadow: 0 5px 18px rgba(26, 47, 84, .045);
    }

    .driver-attendance-page .page-header h4 {
        margin-bottom: 7px;
        font-size: 22px;
        font-weight: 700;
        letter-spacing: -.25px;
    }

    .driver-attendance-page .page-header p {
        color: var(--attendance-muted);
        line-height: 1.55;
    }

    .driver-attendance-page .card-box {
        overflow: hidden;
        border: 1px solid var(--attendance-border);
        border-radius: 14px;
        box-shadow: 0 7px 24px rgba(26, 47, 84, .055);
    }

    .driver-attendance-page .card-box > .pd-20 {
        padding: 22px 24px;
    }

    .driver-attendance-page .btn {
        border-radius: 7px;
        font-weight: 600;
        transition: transform .16s ease, box-shadow .16s ease;
    }

    .driver-attendance-page .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(31, 55, 92, .12);
    }

    .driver-attendance-page .form-control {
        border-color: #dce3ee;
        border-radius: 7px;
        box-shadow: none;
    }

    .driver-attendance-page .form-control:focus {
        border-color: #7da5e8;
        box-shadow: 0 0 0 3px rgba(36, 87, 167, .09);
    }

    .driver-attendance-page .alert {
        border-radius: 9px;
        line-height: 1.55;
    }

    /* ================================================================
       FILTER SECTION
    ================================================================ */
    .driver-attendance-page .attendance-filter-form {
        padding: 22px;
        border: 1px solid var(--attendance-border);
        border-radius: 11px;
        background: #fbfcff;
    }

    .driver-attendance-page .attendance-filter-form label {
        margin-bottom: 7px;
        color: #344054;
        font-size: 12px;
        font-weight: 700;
    }

    .driver-attendance-page .attendance-filter-form .form-control {
        min-height: 42px;
        background-color: #fff;
    }

    /* ================================================================
       VIEW SWITCHER
    ================================================================ */
    .attendance-view-switcher {
        display: inline-flex;
        align-items: center;
        padding: 4px;
        border: 1px solid #e1e8f2;
        border-radius: 9px;
        background: #f7f9fc;
    }

    .attendance-view-switcher a {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 14px;
        border-radius: 7px;
        color: #667085;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all .18s ease;
    }

    .attendance-view-switcher a:hover {
        color: var(--attendance-primary);
        background: #fff;
    }

    .attendance-view-switcher a.active {
        color: #fff;
        background: var(--attendance-primary);
        box-shadow: 0 3px 8px rgba(36, 87, 167, .2);
    }

    /* ================================================================
       CALENDAR TOOLBAR
    ================================================================ */
    .driver-attendance-page .driver-calendar-toolbar {
        gap: 14px;
        padding-bottom: 18px;
        border-bottom: 1px solid #edf1f7;
    }

    .driver-attendance-page .driver-section-eyebrow {
        display: inline-block;
        margin-bottom: 6px;
        color: #718096;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.1px;
    }

    .driver-attendance-page .driver-calendar-toolbar h4 {
        font-weight: 700;
        letter-spacing: -.25px;
    }

    .driver-attendance-page .driver-calendar-navigation {
        gap: 4px;
        padding: 5px;
        border: 1px solid #e8edf5;
        border-radius: 10px;
        background: #f8faff;
    }

    .driver-attendance-page .calendar-nav-btn {
        display: inline-flex;
        width: 34px;
        height: 34px;
        align-items: center;
        justify-content: center;
        margin: 0 !important;
        border-color: #e0e7f1;
        background: #fff;
    }

    .driver-attendance-page .calendar-month-label {
        min-width: 112px;
        color: #26364b;
        text-align: center;
        font-size: 14px;
        font-weight: 750;
    }

    /* ================================================================
       SUMMARY CARDS
    ================================================================ */
    .attendance-summary-card {
        position: relative;
        height: 100%;
        min-height: 132px;
        padding: 17px 18px;
        border: 1px solid var(--attendance-border);
        border-radius: 11px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(26, 47, 84, .035);
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .attendance-summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(26, 47, 84, .08);
    }

    .attendance-summary-card .summary-icon {
        display: inline-flex;
        width: 34px;
        height: 34px;
        align-items: center;
        justify-content: center;
        float: right;
        border-radius: 9px;
        background: #f1f5fb;
        color: var(--attendance-primary);
        font-size: 15px;
    }

    .attendance-summary-card .summary-label {
        display: block;
        padding-top: 4px;
        color: #667085;
        font-size: 12px;
        font-weight: 650;
    }

    .attendance-summary-card .summary-value {
        display: block;
        margin-top: 8px;
        font-size: 27px;
        font-weight: 750;
        line-height: 1.1;
    }

    .attendance-summary-card .summary-caption {
        display: block;
        margin-top: 5px;
        color: #98a2b3;
        font-size: 10px;
    }

    .summary-card-present .summary-icon {
        color: #16834a;
        background: #eaf8f0;
    }

    .summary-card-absent .summary-icon {
        color: #c43c48;
        background: #fff0f1;
    }

    .summary-card-halfday .summary-icon {
        color: #b7791f;
        background: #fff7e6;
    }

    .summary-card-leave .summary-icon {
        color: #5676d8;
        background: #eef2ff;
    }

    .summary-card-unmarked .summary-icon {
        color: #667085;
        background: #f0f2f5;
    }

    /* ================================================================
       CALENDAR LEGEND
    ================================================================ */
    .attendance-calendar-legend {
        gap: 5px;
        padding: 12px 14px 5px;
        border: 1px solid #edf1f7;
        border-radius: 9px;
        background: #fbfcfe;
    }

    .attendance-calendar-legend .badge {
        padding: 7px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 650;
    }

    /* ================================================================
       CALENDAR GRID
    ================================================================ */
    .attendance-calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 9px;
    }

    .attendance-calendar-weekday {
        padding: 11px 6px;
        border: 1px solid #edf1f7;
        border-radius: 8px;
        background: #f6f8fc;
        color: #64748b;
        text-align: center;
        font-size: 11px;
        font-weight: 750;
        letter-spacing: .45px;
        text-transform: uppercase;
    }

    .attendance-day-card {
        position: relative;
        display: block;
        width: 100%;
        min-height: 136px;
        padding: 11px;
        border: 1px solid #e7ecf3;
        border-radius: 10px;
        background: #fff;
        color: var(--attendance-text);
        text-align: left;
        overflow-wrap: anywhere;
        box-shadow: 0 2px 7px rgba(26, 47, 84, .025);
        transition: border-color .16s ease, box-shadow .16s ease, transform .16s ease;
    }

    .attendance-day-card:hover {
        z-index: 1;
        transform: translateY(-2px);
        border-color: #bdcce2;
        box-shadow: 0 7px 16px rgba(26, 47, 84, .075);
    }

    .attendance-day-card.is-today {
        border: 2px solid #3978d4;
        background: #f7faff;
        box-shadow: 0 0 0 3px rgba(57, 120, 212, .08);
    }

    .attendance-day-card.is-other-month {
        min-height: 136px;
        background: #f8f9fb;
        color: #adb5bd;
        border-style: dashed;
        box-shadow: none;
    }

    .attendance-day-number {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
        color: #344054;
        font-size: 13px;
        font-weight: 750;
    }

    .attendance-day-number small {
        padding: 3px 6px;
        border-radius: 20px;
        background: #eaf2ff;
        color: #2457a7;
        font-size: 9px;
        font-weight: 700;
    }

    .attendance-day-details {
        color: #475467;
        font-size: 11px;
        line-height: 1.55;
    }

    .attendance-day-details .badge {
        padding: 6px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        white-space: normal;
        text-align: left;
    }

    .attendance-day-card--success {
        border-top: 3px solid #28a36a;
    }

    .attendance-day-card--danger {
        border-top: 3px solid #dc5661;
    }

    .attendance-day-card--warning {
        border-top: 3px solid #e6ad35;
    }

    .attendance-day-card--primary {
        border-top: 3px solid #5676d8;
    }

    .attendance-day-card--info {
        border-top: 3px solid #36a5c8;
    }

    .attendance-day-card--secondary,
    .attendance-day-card--not-marked {
        border-top: 3px solid #b8c0cc;
    }

    .attendance-day-card--not-marked {
        background: #fafbfc;
    }

    .attendance-day-card--upcoming {
        border-top: 3px solid #cbd5e1;
        background: #fafcff;
    }

    .attendance-day-counts {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 4px;
        margin-top: 7px;
    }

    .attendance-day-counts span {
        display: block;
        padding: 4px 5px;
        border-radius: 5px;
        background: #f5f7fa;
        color: #667085;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .attendance-day-counts .count-present {
        background: #eaf8f0;
        color: #16834a;
    }

    .attendance-day-counts .count-absent {
        background: #fff0f1;
        color: #c43c48;
    }

    .attendance-day-counts .count-halfday {
        background: #fff7e6;
        color: #a66b13;
    }

    .attendance-day-counts .count-leave {
        background: #eef2ff;
        color: #4c63bc;
    }

    .attendance-day-counts .count-unmarked {
        background: #f0f2f5;
        color: #667085;
    }

    .attendance-day-click-hint {
        display: block;
        margin-top: 8px;
        color: #3978d4;
        font-size: 9px;
        font-weight: 700;
    }

    /* ================================================================
       MANAGEMENT CALENDAR FILTER
    ================================================================ */
    .management-calendar-filter {
        padding: 16px;
        border: 1px solid #e8edf5;
        border-radius: 10px;
        background: #fbfcff;
    }

    .management-calendar-filter label {
        margin-bottom: 6px;
        color: #526176;
        font-size: 11px;
        font-weight: 750;
    }

    .management-calendar-filter .form-control {
        min-height: 40px;
        background: #fff;
    }

    /* ================================================================
       DATE DETAILS MODAL
    ================================================================ */
    .attendance-day-modal .modal-content {
        overflow: hidden;
        border: 0;
        border-radius: 13px;
        box-shadow: 0 14px 45px rgba(20, 35, 60, .2);
    }

    .attendance-day-modal .modal-header {
        padding: 17px 20px;
        border-bottom: 1px solid #edf1f7;
        background: #f7f9fc;
    }

    .attendance-day-modal .modal-title {
        color: #26364b;
        font-size: 16px;
        font-weight: 750;
    }

    .attendance-day-modal .modal-body {
        padding: 18px 20px;
    }

    .attendance-day-modal .table thead th {
        padding: 11px 9px;
        border-top: 0;
        background: #f7f9fc;
        color: #526176;
        font-size: 10px;
        font-weight: 750;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .attendance-day-modal .table tbody td {
        padding: 11px 9px;
        color: #344054;
        font-size: 11px;
        vertical-align: middle;
    }

    /* ================================================================
       ATTENDANCE TABLE
    ================================================================ */
    .attendance-table-heading {
        gap: 12px;
    }

    .attendance-table-wrap {
        border-top: 1px solid #edf1f7;
    }

    .attendance-management-table {
        margin-bottom: 0;
    }

    .attendance-management-table thead th {
        padding: 14px 12px;
        border-top: 0;
        border-bottom: 1px solid #e7edf5;
        background: #f7f9fc;
        color: #526176;
        font-size: 11px;
        font-weight: 750;
        letter-spacing: .35px;
        text-transform: uppercase;
        white-space: nowrap;
        vertical-align: middle;
    }

    .attendance-management-table tbody td {
        padding: 13px 12px;
        border-top: 1px solid #eef1f5;
        color: #344054;
        font-size: 12px;
        vertical-align: middle;
    }

    .attendance-management-table tbody tr:hover {
        background: #f9fbff;
    }

    .attendance-time,
    .driver-code,
    .amount-text {
        font-weight: 650;
    }

    .attendance-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
    }

    .driver-name-cell {
        min-width: 150px;
        font-weight: 650;
    }

    .pagination {
        flex-wrap: wrap;
        gap: 4px;
    }

    /* ================================================================
       RESPONSIVE
    ================================================================ */
    @media (max-width: 991.98px) {
        .attendance-day-card,
        .attendance-day-card.is-other-month {
            min-height: 124px;
        }
    }

    @media (max-width: 767.98px) {
        .driver-attendance-page .page-header {
            padding: 18px;
        }

        .driver-attendance-page .card-box > .pd-20 {
            padding: 17px;
        }

        .driver-attendance-page .driver-calendar-toolbar {
            align-items: flex-start !important;
        }

        .driver-calendar-navigation {
            width: 100%;
            justify-content: center;
        }

        .attendance-calendar-grid {
            gap: 5px;
        }

        .attendance-calendar-weekday {
            padding: 8px 2px;
            font-size: 9px;
            letter-spacing: 0;
        }

        .attendance-day-card,
        .attendance-day-card.is-other-month {
            min-height: 105px;
            padding: 6px;
            border-radius: 7px;
        }

        .attendance-day-number {
            margin-bottom: 7px;
            font-size: 11px;
        }

        .attendance-day-details {
            font-size: 9px;
        }

        .attendance-day-details .badge {
            padding: 4px 5px;
            font-size: 8px;
        }

        .attendance-summary-card {
            min-height: 120px;
            padding: 13px;
        }

        .attendance-summary-card .summary-value {
            font-size: 23px;
        }

        .attendance-summary-card .summary-icon {
            width: 29px;
            height: 29px;
            font-size: 12px;
        }

        .attendance-filter-form {
            padding: 15px;
        }

        .attendance-view-switcher a {
            padding: 8px 10px;
            font-size: 11px;
        }

        .attendance-day-counts span {
            padding: 3px;
            font-size: 8px;
        }
    }

    @media (max-width: 420px) {
        .attendance-calendar-grid {
            gap: 3px;
        }

        .attendance-day-card,
        .attendance-day-card.is-other-month {
            min-height: 92px;
            padding: 4px;
        }

        .attendance-day-number {
            margin-bottom: 5px;
            font-size: 10px;
        }

        .attendance-day-number small {
            padding: 2px 4px;
            font-size: 8px;
        }

        .attendance-calendar-legend {
            padding: 9px 9px 3px;
        }

        .attendance-calendar-legend .badge {
            padding: 6px 8px;
            font-size: 9px;
        }

        .attendance-day-counts {
            gap: 2px;
        }
    }
</style>

@endpush


@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE CALENDAR - COMMON DATA
    |--------------------------------------------------------------------------
    |
    | Calendar data is loaded separately from the existing paginated table.
    | Therefore, the monthly calendar does not depend on the 20-row paginator.
    |
    | Missing attendance records are treated as NOT MARKED, never as ABSENT.
    |
    */

    $attendanceCalendarMonth = now()->startOfMonth();

    $requestedCalendarMonth = request()->query('month');

    if (
        is_string($requestedCalendarMonth) &&
        preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])$/', $requestedCalendarMonth)
    ) {
        try {
            $parsedCalendarMonth = \Carbon\Carbon::createFromFormat(
                '!Y-m',
                $requestedCalendarMonth
            );

            if ($parsedCalendarMonth) {
                $attendanceCalendarMonth = $parsedCalendarMonth->startOfMonth();
            }
        } catch (\Throwable $exception) {
            $attendanceCalendarMonth = now()->startOfMonth();
        }
    }

    $attendanceCalendarToday = now()->startOfDay();

    $attendanceCalendarStart = $attendanceCalendarMonth->copy()
        ->startOfMonth()
        ->startOfWeek(\Carbon\Carbon::MONDAY);

    $attendanceCalendarEnd = $attendanceCalendarMonth->copy()
        ->endOfMonth()
        ->endOfWeek(\Carbon\Carbon::SUNDAY);

    $attendanceCalendarDays = [];

    for (
        $calendarDate = $attendanceCalendarStart->copy();
        $calendarDate->lte($attendanceCalendarEnd);
        $calendarDate->addDay()
    ) {
        $attendanceCalendarDays[] = $calendarDate->copy();
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS GROUPS
    |--------------------------------------------------------------------------
    */

    $attendancePresentStatuses = [
        'present',
        'present_late',
        'present_early_exit',
        'on_duty',
        'work_from_home',
    ];

    $attendanceAbsentStatuses = [
        'absent',
    ];

    $attendanceHalfDayStatuses = [
        'half_day',
        'late',
        'early_exit',
        'missing_punch',
    ];

    $attendanceLeaveStatuses = [
        'leave',
        'maternity_leave',
        'sick_leave',
        'casual_leave',
        'paid_leave',
        'unpaid_leave',
        'restricted_holiday',
        'comp_off',
    ];

    /*
     * This closure keeps the status classification consistent
     * between calendar counts, date cards and detail modals.
     */
    $getAttendanceCategory = function ($status) use (
        $attendancePresentStatuses,
        $attendanceAbsentStatuses,
        $attendanceHalfDayStatuses,
        $attendanceLeaveStatuses
    ) {
        if (in_array($status, $attendancePresentStatuses, true)) {
            return 'present';
        }

        if (in_array($status, $attendanceAbsentStatuses, true)) {
            return 'absent';
        }

        if (in_array($status, $attendanceHalfDayStatuses, true)) {
            return 'half_day';
        }

        if (in_array($status, $attendanceLeaveStatuses, true)) {
            return 'leave';
        }

        return 'other';
    };


    /*
    |--------------------------------------------------------------------------
    | CALENDAR VIEW SELECTION
    |--------------------------------------------------------------------------
    */

    $managementCalendarView = request()->query('view', 'calendar');

    if (!in_array($managementCalendarView, ['calendar', 'table'], true)) {
        $managementCalendarView = 'calendar';
    }


    /*
    |--------------------------------------------------------------------------
    | DRIVER CALENDAR DATA
    |--------------------------------------------------------------------------
    */

    $driverCalendarDriver = null;
    $driverCalendarAttendances = collect();

    $driverCalendarMarkedCount = 0;
    $driverCalendarPresentCount = 0;
    $driverCalendarAbsentCount = 0;
    $driverCalendarHalfDayCount = 0;
    $driverCalendarLeaveCount = 0;
    $driverCalendarNotMarkedCount = 0;
    $driverCalendarDueDays = 0;

    if ($isDriver) {
        $calendarUser = auth()->user();

        $driverCalendarDriver = $calendarUser
            ? $calendarUser->driver
            : null;

        if ($driverCalendarDriver) {
            $driverCalendarAttendances = \App\Models\DriverAttendance::query()
                ->with('workingSheet')
                ->where('driver_id', $driverCalendarDriver->id)
                ->whereBetween('attendance_date', [
                    $attendanceCalendarMonth->copy()->startOfMonth()->toDateString(),
                    $attendanceCalendarMonth->copy()->endOfMonth()->toDateString(),
                ])
                ->get()
                ->keyBy(function ($attendance) {
                    return \Carbon\Carbon::parse(
                        $attendance->attendance_date
                    )->format('Y-m-d');
                });

            $driverCalendarMarkedCount = $driverCalendarAttendances->count();

            $driverCalendarPresentCount = $driverCalendarAttendances
                ->filter(function ($attendance) use ($getAttendanceCategory) {
                    return $getAttendanceCategory($attendance->status) === 'present';
                })
                ->count();

            $driverCalendarAbsentCount = $driverCalendarAttendances
                ->filter(function ($attendance) use ($getAttendanceCategory) {
                    return $getAttendanceCategory($attendance->status) === 'absent';
                })
                ->count();

            $driverCalendarHalfDayCount = $driverCalendarAttendances
                ->filter(function ($attendance) use ($getAttendanceCategory) {
                    return $getAttendanceCategory($attendance->status) === 'half_day';
                })
                ->count();

            $driverCalendarLeaveCount = $driverCalendarAttendances
                ->filter(function ($attendance) use ($getAttendanceCategory) {
                    return $getAttendanceCategory($attendance->status) === 'leave';
                })
                ->count();

            /*
             * Not Marked is calculated only for elapsed dates.
             * Future dates are displayed as Upcoming, not Not Marked.
             */
            foreach ($attendanceCalendarDays as $calendarDate) {
                if (
                    $calendarDate->format('Y-m') === $attendanceCalendarMonth->format('Y-m') &&
                    $calendarDate->format('Y-m-d') <= $attendanceCalendarToday->toDateString()
                ) {
                    $driverCalendarDueDays++;
                }
            }

            $driverCalendarMarkedDueCount = $driverCalendarAttendances
                ->filter(function ($attendance) use ($attendanceCalendarToday) {
                    return \Carbon\Carbon::parse($attendance->attendance_date)
                        ->startOfDay()
                        ->lte($attendanceCalendarToday);
                })
                ->count();

            $driverCalendarNotMarkedCount = max(
                0,
                $driverCalendarDueDays - $driverCalendarMarkedDueCount
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | MANAGEMENT - ALL DRIVERS MONTHLY CALENDAR
    |--------------------------------------------------------------------------
    |
    | This query is intentionally independent of $attendances paginator.
    | It loads all selected-month attendance records for the eligible drivers.
    |
    */

    $managementCalendarDrivers = collect($drivers ?? []);

    $selectedCalendarDriverId = request()->query('calendar_driver_id');

    if (
        $selectedCalendarDriverId &&
        !$managementCalendarDrivers->contains(function ($driver) use ($selectedCalendarDriverId) {
            return (string) $driver->id === (string) $selectedCalendarDriverId;
        })
    ) {
        $selectedCalendarDriverId = null;
    }

    if ($selectedCalendarDriverId) {
        $managementCalendarDrivers = $managementCalendarDrivers
            ->filter(function ($driver) use ($selectedCalendarDriverId) {
                return (string) $driver->id === (string) $selectedCalendarDriverId;
            })
            ->values();
    }

    $managementCalendarDriverIds = $managementCalendarDrivers
        ->pluck('id')
        ->filter()
        ->values();

    $managementCalendarAttendances = collect();

    if (!$isDriver && $managementCalendarDriverIds->isNotEmpty()) {
        $managementCalendarAttendances = \App\Models\DriverAttendance::query()
            ->with([
                'driver',
                'workingSheet',
                'createdBy',
            ])
            ->whereIn('driver_id', $managementCalendarDriverIds->all())
            ->whereBetween('attendance_date', [
                $attendanceCalendarMonth->copy()->startOfMonth()->toDateString(),
                $attendanceCalendarMonth->copy()->endOfMonth()->toDateString(),
            ])
            ->orderBy('attendance_date')
            ->orderBy('driver_id')
            ->get();
    }

    $managementCalendarRecordsByDate = $managementCalendarAttendances->groupBy(
        function ($attendance) {
            return \Carbon\Carbon::parse(
                $attendance->attendance_date
            )->format('Y-m-d');
        }
    );

    $managementCalendarDaysData = [];

    $managementCalendarMonthlySummary = [
        'present' => 0,
        'absent' => 0,
        'half_day' => 0,
        'leave' => 0,
        'not_marked' => 0,
        'other' => 0,
        'marked' => 0,
    ];

    $managementCalendarDueDays = 0;

    if (!$isDriver) {
        foreach ($attendanceCalendarDays as $calendarDate) {
            if (
                $calendarDate->format('Y-m') !==
                $attendanceCalendarMonth->format('Y-m')
            ) {
                continue;
            }

            $dateKey = $calendarDate->format('Y-m-d');

            $isAttendanceDue = $dateKey <= $attendanceCalendarToday->toDateString();

            if ($isAttendanceDue) {
                $managementCalendarDueDays++;
            }

            $dateRecords = $managementCalendarRecordsByDate->get(
                $dateKey,
                collect()
            )->keyBy('driver_id');

            $dayRows = collect();

            $dayStats = [
                'present' => 0,
                'absent' => 0,
                'half_day' => 0,
                'leave' => 0,
                'not_marked' => 0,
                'other' => 0,
                'upcoming' => 0,
                'marked' => 0,
                'total_drivers' => $managementCalendarDrivers->count(),
            ];

            foreach ($managementCalendarDrivers as $calendarDriver) {
                $dayAttendance = $dateRecords->get($calendarDriver->id);

                if ($dayAttendance) {
                    $dayCategory = $getAttendanceCategory(
                        $dayAttendance->status
                    );

                    $dayStats['marked']++;
                } elseif ($isAttendanceDue) {
                    $dayCategory = 'not_marked';
                } else {
                    $dayCategory = 'upcoming';
                }

                if (isset($dayStats[$dayCategory])) {
                    $dayStats[$dayCategory]++;
                }

                $dayRows->push([
                    'driver' => $calendarDriver,
                    'attendance' => $dayAttendance,
                    'category' => $dayCategory,
                ]);
            }

            $managementCalendarDaysData[$dateKey] = [
                'date' => $calendarDate->copy(),
                'rows' => $dayRows,
                'stats' => $dayStats,
                'is_due' => $isAttendanceDue,
            ];

            foreach ([
                'present',
                'absent',
                'half_day',
                'leave',
                'not_marked',
                'other',
                'marked',
            ] as $summaryKey) {
                $managementCalendarMonthlySummary[$summaryKey] +=
                    $dayStats[$summaryKey];
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CALENDAR NAVIGATION URLS
    |--------------------------------------------------------------------------
    */

    $calendarPreviousMonthQuery = request()->query();

    $calendarPreviousMonthQuery['month'] = $attendanceCalendarMonth
        ->copy()
        ->subMonthNoOverflow()
        ->format('Y-m');

    $calendarNextMonthQuery = request()->query();

    $calendarNextMonthQuery['month'] = $attendanceCalendarMonth
        ->copy()
        ->addMonthNoOverflow()
        ->format('Y-m');

    $calendarCurrentMonthQuery = request()->query();

    $calendarCurrentMonthQuery['month'] = now()->format('Y-m');

    $calendarPreviousMonthUrl = route(
        'driver-attendances.index',
        $calendarPreviousMonthQuery
    );

    $calendarNextMonthUrl = route(
        'driver-attendances.index',
        $calendarNextMonthQuery
    );

    $calendarCurrentMonthUrl = route(
        'driver-attendances.index',
        $calendarCurrentMonthQuery
    );


    /*
    |--------------------------------------------------------------------------
    | MANAGEMENT VIEW SWITCH URLS
    |--------------------------------------------------------------------------
    */

    $calendarViewQuery = request()->query();
    $calendarViewQuery['view'] = 'calendar';

    $tableViewQuery = request()->query();
    $tableViewQuery['view'] = 'table';

    $managementCalendarUrl = route(
        'driver-attendances.index',
        $calendarViewQuery
    );

    $managementTableUrl = route(
        'driver-attendances.index',
        $tableViewQuery
    );
@endphp


<div class="pd-ltr-20 xs-pd-20-10 driver-attendance-page">

    <div class="min-height-200px">

        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}
        <div class="page-header">

            <div class="row align-items-center">

                <div class="{{ !$isDriver ? 'col-md-8' : 'col-md-12' }} col-sm-12">

                    <h4 class="text-blue">
                        {{ $isDriver ? 'My Attendance' : 'Driver Attendance Management' }}
                    </h4>

                    <p class="mb-0">
                        @if($isDriver)
                            View your monthly attendance, working hours and daily attendance status.
                        @else
                            Monitor attendance across all drivers, review daily status and manage attendance records.
                        @endif
                    </p>

                </div>

                @if(!$isDriver)
                    <div class="col-md-4 col-sm-12 text-md-right mt-3 mt-md-0">

                        <a
                            href="{{ route('driver-attendances.create') }}"
                            class="btn btn-primary"
                        >
                            <i class="fa fa-plus"></i>
                            Add Driver Attendance
                        </a>

                    </div>
                @endif

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- MANAGEMENT VIEW SWITCHER --}}
        {{-- ========================================================= --}}
        @if(!$isDriver)

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-20">

                <div class="mb-2">
                    <h5 class="mb-1 font-weight-bold text-dark">
                        Attendance Overview
                    </h5>

                    <small class="text-muted">
                        Switch between monthly calendar and detailed attendance records.
                    </small>
                </div>

                <div class="attendance-view-switcher mb-2">

                    <a
                        href="{{ $managementCalendarUrl }}"
                        class="{{ $managementCalendarView === 'calendar' ? 'active' : '' }}"
                    >
                        <i class="fa fa-calendar"></i>
                        Calendar View
                    </a>

                    <a
                        href="{{ $managementTableUrl }}"
                        class="{{ $managementCalendarView === 'table' ? 'active' : '' }}"
                    >
                        <i class="fa fa-table"></i>
                        Table View
                    </a>

                </div>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- MANAGEMENT TABLE FILTERS --}}
        {{-- ========================================================= --}}
        @if(!$isDriver)

            <div class="card-box mb-30">

                <div class="pd-20">

                    <h4 class="text-blue h4 mb-20">
                        <i class="fa fa-filter mr-1"></i>
                        Attendance Filters
                    </h4>

                    <form
                        method="GET"
                        action="{{ route('driver-attendances.index') }}"
                        class="attendance-filter-form"
                    >

                        <input
                            type="hidden"
                            name="view"
                            value="{{ $managementCalendarView }}"
                        >

                        @if(request('month'))
                            <input type="hidden" name="month" value="{{ request('month') }}">
                        @endif

                        @if(request('calendar_driver_id'))
                            <input
                                type="hidden"
                                name="calendar_driver_id"
                                value="{{ request('calendar_driver_id') }}"
                            >
                        @endif

                        <div class="row">

                            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">

                                <label>Search</label>

                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    class="form-control"
                                    placeholder="Driver name, code or mobile"
                                >

                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">

                                <label>Driver</label>

                                <select
                                    name="driver_id"
                                    class="form-control custom-select2"
                                >

                                    <option value="">All Drivers</option>

                                    @foreach($drivers as $driver)

                                        <option
                                            value="{{ $driver->id }}"
                                            {{ (string) request('driver_id') === (string) $driver->id ? 'selected' : '' }}
                                        >
                                            {{ $driver->first_name }} {{ $driver->last_name }}

                                            @if(!empty($driver->driver_code))
                                                - {{ $driver->driver_code }}
                                            @endif
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">

                                <label>Attendance Date</label>

                                <input
                                    type="date"
                                    name="attendance_date"
                                    value="{{ request('attendance_date') }}"
                                    class="form-control"
                                >

                            </div>

                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">

                                <label>Date From</label>

                                <input
                                    type="date"
                                    name="date_from"
                                    value="{{ request('date_from') }}"
                                    class="form-control"
                                >

                            </div>

                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">

                                <label>Date To</label>

                                <input
                                    type="date"
                                    name="date_to"
                                    value="{{ request('date_to') }}"
                                    class="form-control"
                                >

                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">

                                <label>Status</label>

                                <select
                                    name="status"
                                    class="form-control custom-select2"
                                >

                                    <option value="">All Status</option>

                                    @foreach($statuses as $status)

                                        <option
                                            value="{{ $status }}"
                                            {{ request('status') === $status ? 'selected' : '' }}
                                        >
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">

                                <label>Source</label>

                                <select
                                    name="source"
                                    class="form-control custom-select2"
                                >

                                    <option value="">All Sources</option>

                                    @foreach($sources as $source)

                                        <option
                                            value="{{ $source }}"
                                            {{ request('source') === $source ? 'selected' : '' }}
                                        >
                                            {{ ucfirst(str_replace('_', ' ', $source)) }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-lg-6 col-md-8 col-sm-12 mb-3 d-flex align-items-end">

                                <button
                                    type="submit"
                                    class="btn btn-primary mr-2"
                                >
                                    <i class="fa fa-search"></i>
                                    Apply Filters
                                </button>

                                <a
                                    href="{{ route('driver-attendances.index', ['view' => $managementCalendarView]) }}"
                                    class="btn btn-secondary"
                                >
                                    <i class="fa fa-refresh"></i>
                                    Reset
                                </a>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        @endif

                {{-- ========================================================= --}}
        {{-- DRIVER PERSONAL MONTHLY CALENDAR --}}
        {{-- ========================================================= --}}
        @if($isDriver)

            <div class="card-box mb-30">

                <div class="pd-20">

                    {{-- Calendar Toolbar --}}
                    <div class="driver-calendar-toolbar d-flex flex-wrap justify-content-between align-items-center mb-20">

                        <div class="mb-3 mb-md-0">

                            <span class="driver-section-eyebrow">
                                PERSONAL ATTENDANCE
                            </span>

                            <h4 class="text-blue h4 mb-1">
                                My Attendance Calendar
                            </h4>

                            <p class="text-muted mb-0">
                                Review your monthly attendance, working hours and daily status.
                            </p>

                        </div>

                        <div class="driver-calendar-navigation d-flex flex-wrap align-items-center">

                            <a
                                href="{{ $calendarPreviousMonthUrl }}"
                                class="btn btn-outline-secondary btn-sm calendar-nav-btn"
                                aria-label="Previous month"
                                title="Previous month"
                            >
                                <i class="fa fa-chevron-left"></i>
                            </a>

                            <strong class="calendar-month-label mx-2">
                                {{ $attendanceCalendarMonth->format('F Y') }}
                            </strong>

                            <a
                                href="{{ $calendarNextMonthUrl }}"
                                class="btn btn-outline-secondary btn-sm calendar-nav-btn"
                                aria-label="Next month"
                                title="Next month"
                            >
                                <i class="fa fa-chevron-right"></i>
                            </a>

                            <a
                                href="{{ $calendarCurrentMonthUrl }}"
                                class="btn btn-primary btn-sm ml-2"
                            >
                                Today
                            </a>

                        </div>

                    </div>


                    {{-- Driver Mapping Check --}}
                    @if(!$driverCalendarDriver)

                        <div class="alert alert-warning mb-0" role="alert">

                            <i class="fa fa-exclamation-triangle mr-1"></i>

                            Your user account is not mapped with a Driver record.
                            Please contact the administrator.

                        </div>

                    @else

                        {{-- Monthly Summary --}}
                        <div class="row mb-3">

                            <div class="col-xl col-md-4 col-6 mb-3">

                                <div class="attendance-summary-card">

                                    <span class="summary-icon">
                                        <i class="fa fa-calendar-check-o"></i>
                                    </span>

                                    <span class="summary-label">
                                        Marked Records
                                    </span>

                                    <span class="summary-value text-primary">
                                        {{ $driverCalendarMarkedCount }}
                                    </span>

                                    <span class="summary-caption">
                                        Total attendance entries
                                    </span>

                                </div>

                            </div>

                            <div class="col-xl col-md-4 col-6 mb-3">

                                <div class="attendance-summary-card summary-card-present">

                                    <span class="summary-icon">
                                        <i class="fa fa-check-circle"></i>
                                    </span>

                                    <span class="summary-label">
                                        Present
                                    </span>

                                    <span class="summary-value text-success">
                                        {{ $driverCalendarPresentCount }}
                                    </span>

                                    <span class="summary-caption">
                                        Present days
                                    </span>

                                </div>

                            </div>

                            <div class="col-xl col-md-4 col-6 mb-3">

                                <div class="attendance-summary-card summary-card-absent">

                                    <span class="summary-icon">
                                        <i class="fa fa-times-circle"></i>
                                    </span>

                                    <span class="summary-label">
                                        Absent
                                    </span>

                                    <span class="summary-value text-danger">
                                        {{ $driverCalendarAbsentCount }}
                                    </span>

                                    <span class="summary-caption">
                                        Recorded absences
                                    </span>

                                </div>

                            </div>

                            <div class="col-xl col-md-4 col-6 mb-3">

                                <div class="attendance-summary-card summary-card-halfday">

                                    <span class="summary-icon">
                                        <i class="fa fa-adjust"></i>
                                    </span>

                                    <span class="summary-label">
                                        Half Day / Exceptions
                                    </span>

                                    <span class="summary-value" style="color:#b7791f;">
                                        {{ $driverCalendarHalfDayCount }}
                                    </span>

                                    <span class="summary-caption">
                                        Half day, late or punch issues
                                    </span>

                                </div>

                            </div>

                            <div class="col-xl col-md-4 col-6 mb-3">

                                <div class="attendance-summary-card summary-card-leave">

                                    <span class="summary-icon">
                                        <i class="fa fa-calendar"></i>
                                    </span>

                                    <span class="summary-label">
                                        Leave
                                    </span>

                                    <span class="summary-value" style="color:#5676d8;">
                                        {{ $driverCalendarLeaveCount }}
                                    </span>

                                    <span class="summary-caption">
                                        Recorded leave entries
                                    </span>

                                </div>

                            </div>

                            <div class="col-xl col-md-4 col-6 mb-3">

                                <div class="attendance-summary-card summary-card-unmarked">

                                    <span class="summary-icon">
                                        <i class="fa fa-calendar-o"></i>
                                    </span>

                                    <span class="summary-label">
                                        Not Marked
                                    </span>

                                    <span class="summary-value text-secondary">
                                        {{ $driverCalendarNotMarkedCount }}
                                    </span>

                                    <span class="summary-caption">
                                        No record on elapsed days
                                    </span>

                                </div>

                            </div>

                        </div>


                        {{-- Calendar Legend --}}
                        <div class="attendance-calendar-legend d-flex flex-wrap align-items-center mb-3">

                            <span class="badge badge-success mr-2 mb-2">
                                Present
                            </span>

                            <span class="badge badge-danger mr-2 mb-2">
                                Absent
                            </span>

                            <span class="badge badge-warning mr-2 mb-2">
                                Half Day / Exceptions
                            </span>

                            <span class="badge badge-primary mr-2 mb-2">
                                Leave
                            </span>

                            <span class="badge badge-info mr-2 mb-2">
                                Holiday / Weekly Off
                            </span>

                            <span class="badge badge-secondary mr-2 mb-2">
                                Not Marked
                            </span>

                            <span class="badge badge-light border mr-2 mb-2">
                                Upcoming
                            </span>

                        </div>


                        {{-- Driver Calendar Grid --}}
                        <div
                            class="attendance-calendar-grid"
                            role="group"
                            aria-label="Monthly driver attendance calendar"
                        >

                            @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $weekday)

                                <div class="attendance-calendar-weekday">
                                    {{ $weekday }}
                                </div>

                            @endforeach


                            @foreach($attendanceCalendarDays as $calendarDay)

                                @php
                                    $calendarDayKey = $calendarDay->format('Y-m-d');

                                    $isCalendarCurrentMonth =
                                        $calendarDay->format('Y-m') ===
                                        $attendanceCalendarMonth->format('Y-m');

                                    $dayAttendance = $isCalendarCurrentMonth
                                        ? $driverCalendarAttendances->get($calendarDayKey)
                                        : null;

                                    $isCalendarDayDue =
                                        $calendarDayKey <= $attendanceCalendarToday->toDateString();

                                    $driverDayCategory = $dayAttendance
                                        ? $getAttendanceCategory($dayAttendance->status)
                                        : ($isCalendarDayDue ? 'not_marked' : 'upcoming');

                                    $driverDayStatusClass = match ($driverDayCategory) {
                                        'present' => 'success',
                                        'absent' => 'danger',
                                        'half_day' => 'warning',
                                        'leave' => 'primary',
                                        'other' => 'info',
                                        'not_marked' => 'secondary',
                                        default => 'light',
                                    };

                                    $driverDayStatusLabel = $dayAttendance
                                        ? ucfirst(str_replace('_', ' ', $dayAttendance->status ?? 'Unknown'))
                                        : ($isCalendarDayDue ? 'Not Marked' : 'Upcoming');
                                @endphp


                                @if(!$isCalendarCurrentMonth)

                                    <div
                                        class="attendance-day-card is-other-month"
                                        aria-hidden="true"
                                    >

                                        <div class="attendance-day-number">
                                            <span>{{ $calendarDay->day }}</span>
                                        </div>

                                    </div>

                                @else

                                    <div class="attendance-day-card
                                        {{ $calendarDay->isToday() ? 'is-today' : '' }}
                                        {{ $driverDayCategory === 'not_marked'
                                            ? 'attendance-day-card--not-marked'
                                            : 'attendance-day-card--' . $driverDayStatusClass }}">

                                        <div class="attendance-day-number">

                                            <span>
                                                {{ $calendarDay->day }}
                                            </span>

                                            @if($calendarDay->isToday())

                                                <small>
                                                    Today
                                                </small>

                                            @endif

                                        </div>


                                        <div class="attendance-day-details">

                                            <span class="badge badge-{{ $driverDayStatusClass }} d-inline-block mb-2">
                                                {{ $driverDayStatusLabel }}
                                            </span>


                                            @if($dayAttendance)

                                                @if($dayAttendance->in_time || $dayAttendance->out_time)

                                                    <div class="text-muted">

                                                        <i class="fa fa-clock-o"></i>

                                                        {{ $dayAttendance->in_time
                                                            ? \Carbon\Carbon::parse($dayAttendance->in_time)->format('h:i A')
                                                            : '--' }}

                                                        –

                                                        {{ $dayAttendance->out_time
                                                            ? \Carbon\Carbon::parse($dayAttendance->out_time)->format('h:i A')
                                                            : '--' }}

                                                    </div>

                                                @endif


                                                @if($dayAttendance->total_hours !== null)

                                                    <div class="text-muted mt-1">

                                                        <i class="fa fa-hourglass-half"></i>

                                                        {{ number_format((float) $dayAttendance->total_hours, 2) }}
                                                        hrs

                                                    </div>

                                                @endif


                                                @if($dayAttendance->workingSheet && $dayAttendance->workingSheet->sheet_no)

                                                    <div class="text-muted mt-1">

                                                        <i class="fa fa-file-text-o"></i>

                                                        {{ $dayAttendance->workingSheet->sheet_no }}

                                                    </div>

                                                @endif

                                            @elseif(!$isCalendarDayDue)

                                                <small class="text-muted">
                                                    Attendance date is upcoming.
                                                </small>

                                            @else

                                                <small class="text-muted">
                                                    No attendance record exists.
                                                </small>

                                            @endif

                                        </div>

                                    </div>

                                @endif

                            @endforeach

                        </div>


                        <div class="text-muted mt-3">

                            <small>
                                <i class="fa fa-info-circle mr-1"></i>

                                Not Marked means no attendance record exists for an elapsed date.
                                It is not an Absent status. Future dates are shown as Upcoming.
                            </small>

                        </div>

                    @endif

                </div>

            </div>


        {{-- ========================================================= --}}
        {{-- MANAGEMENT ALL DRIVERS CALENDAR --}}
        {{-- ========================================================= --}}
        @elseif($managementCalendarView === 'calendar')

            <div class="card-box mb-30">

                <div class="pd-20">

                    {{-- Calendar Toolbar --}}
                    <div class="driver-calendar-toolbar d-flex flex-wrap justify-content-between align-items-center mb-20">

                        <div class="mb-3 mb-md-0">

                            <span class="driver-section-eyebrow">
                                MANAGEMENT ATTENDANCE OVERVIEW
                            </span>

                            <h4 class="text-blue h4 mb-1">
                                All Drivers Monthly Calendar
                            </h4>

                            <p class="text-muted mb-0">
                                View daily attendance status for all drivers in one place.
                            </p>

                        </div>


                        <div class="driver-calendar-navigation d-flex flex-wrap align-items-center">

                            <a
                                href="{{ $calendarPreviousMonthUrl }}"
                                class="btn btn-outline-secondary btn-sm calendar-nav-btn"
                                aria-label="Previous month"
                                title="Previous month"
                            >
                                <i class="fa fa-chevron-left"></i>
                            </a>

                            <strong class="calendar-month-label mx-2">
                                {{ $attendanceCalendarMonth->format('F Y') }}
                            </strong>

                            <a
                                href="{{ $calendarNextMonthUrl }}"
                                class="btn btn-outline-secondary btn-sm calendar-nav-btn"
                                aria-label="Next month"
                                title="Next month"
                            >
                                <i class="fa fa-chevron-right"></i>
                            </a>

                            <a
                                href="{{ $calendarCurrentMonthUrl }}"
                                class="btn btn-primary btn-sm ml-2"
                            >
                                Today
                            </a>

                        </div>

                    </div>


                    {{-- Management Calendar Filters --}}
                    <form
                        action="{{ route('driver-attendances.index') }}"
                        method="GET"
                        class="management-calendar-filter mb-4"
                    >

                        <input type="hidden" name="view" value="calendar">

                        <div class="row align-items-end">

                            <div class="col-md-4 col-sm-6 mb-3 mb-md-0">

                                <label>
                                    Select Month
                                </label>

                                <input
                                    type="month"
                                    name="month"
                                    value="{{ $attendanceCalendarMonth->format('Y-m') }}"
                                    class="form-control"
                                >

                            </div>


                            <div class="col-md-5 col-sm-6 mb-3 mb-md-0">

                                <label>
                                    Filter by Driver
                                </label>

                                <select
                                    name="calendar_driver_id"
                                    class="form-control custom-select2"
                                >

                                    <option value="">
                                        All Drivers
                                    </option>

                                    @foreach($drivers as $driver)

                                        <option
                                            value="{{ $driver->id }}"
                                            {{ (string) $selectedCalendarDriverId === (string) $driver->id ? 'selected' : '' }}
                                        >

                                            {{ $driver->first_name }}
                                            {{ $driver->last_name }}

                                            @if(!empty($driver->driver_code))
                                                - {{ $driver->driver_code }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <div class="col-md-3 col-sm-12">

                                <button
                                    type="submit"
                                    class="btn btn-primary mr-1"
                                >

                                    <i class="fa fa-filter"></i>
                                    Apply

                                </button>

                                <a
                                    href="{{ route('driver-attendances.index', ['view' => 'calendar']) }}"
                                    class="btn btn-secondary"
                                >

                                    <i class="fa fa-refresh"></i>

                                </a>

                            </div>

                        </div>

                    </form>


                    {{-- Monthly Summary --}}
                    <div class="row mb-3">

                        <div class="col-xl col-md-4 col-6 mb-3">

                            <div class="attendance-summary-card summary-card-present">

                                <span class="summary-icon">
                                    <i class="fa fa-check-circle"></i>
                                </span>

                                <span class="summary-label">
                                    Present
                                </span>

                                <span class="summary-value text-success">
                                    {{ $managementCalendarMonthlySummary['present'] }}
                                </span>

                                <span class="summary-caption">
                                    Driver-days marked present
                                </span>

                            </div>

                        </div>


                        <div class="col-xl col-md-4 col-6 mb-3">

                            <div class="attendance-summary-card summary-card-absent">

                                <span class="summary-icon">
                                    <i class="fa fa-times-circle"></i>
                                </span>

                                <span class="summary-label">
                                    Absent
                                </span>

                                <span class="summary-value text-danger">
                                    {{ $managementCalendarMonthlySummary['absent'] }}
                                </span>

                                <span class="summary-caption">
                                    Recorded absences
                                </span>

                            </div>

                        </div>


                        <div class="col-xl col-md-4 col-6 mb-3">

                            <div class="attendance-summary-card summary-card-halfday">

                                <span class="summary-icon">
                                    <i class="fa fa-adjust"></i>
                                </span>

                                <span class="summary-label">
                                    Half Day / Exceptions
                                </span>

                                <span class="summary-value" style="color:#b7791f;">
                                    {{ $managementCalendarMonthlySummary['half_day'] }}
                                </span>

                                <span class="summary-caption">
                                    Half day, late or punch issues
                                </span>

                            </div>

                        </div>


                        <div class="col-xl col-md-4 col-6 mb-3">

                            <div class="attendance-summary-card summary-card-leave">

                                <span class="summary-icon">
                                    <i class="fa fa-calendar"></i>
                                </span>

                                <span class="summary-label">
                                    Leave
                                </span>

                                <span class="summary-value" style="color:#5676d8;">
                                    {{ $managementCalendarMonthlySummary['leave'] }}
                                </span>

                                <span class="summary-caption">
                                    Recorded leave entries
                                </span>

                            </div>

                        </div>


                        <div class="col-xl col-md-4 col-6 mb-3">

                            <div class="attendance-summary-card summary-card-unmarked">

                                <span class="summary-icon">
                                    <i class="fa fa-calendar-o"></i>
                                </span>

                                <span class="summary-label">
                                    Not Marked
                                </span>

                                <span class="summary-value text-secondary">
                                    {{ $managementCalendarMonthlySummary['not_marked'] }}
                                </span>

                                <span class="summary-caption">
                                    Missing records on elapsed dates
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- Additional Calendar Information --}}
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">

                        <div class="text-muted mb-2">

                            <small>

                                <i class="fa fa-users mr-1"></i>

                                Drivers included:

                                <strong>
                                    {{ $managementCalendarDrivers->count() }}
                                </strong>

                            </small>

                            <span class="mx-2">|</span>

                            <small>

                                <i class="fa fa-calendar-check-o mr-1"></i>

                                Marked records:

                                <strong>
                                    {{ $managementCalendarMonthlySummary['marked'] }}
                                </strong>

                            </small>

                        </div>

                        @if($selectedCalendarDriverId)

                            <span class="badge badge-info mb-2">
                                Driver filter applied
                            </span>

                        @endif

                    </div>


                    {{-- Calendar Legend --}}
                    <div class="attendance-calendar-legend d-flex flex-wrap align-items-center mb-3">

                        <span class="badge badge-success mr-2 mb-2">
                            Present
                        </span>

                        <span class="badge badge-danger mr-2 mb-2">
                            Absent
                        </span>

                        <span class="badge badge-warning mr-2 mb-2">
                            Half Day / Exceptions
                        </span>

                        <span class="badge badge-primary mr-2 mb-2">
                            Leave
                        </span>

                        <span class="badge badge-secondary mr-2 mb-2">
                            Not Marked
                        </span>

                        <span class="badge badge-light border mr-2 mb-2">
                            Upcoming
                        </span>

                        <small class="text-muted ml-md-2 mb-2">
                            Click any date to view driver-wise details.
                        </small>

                    </div>


                    {{-- Management Calendar Grid --}}
                    <div
                        class="attendance-calendar-grid"
                        role="group"
                        aria-label="All drivers monthly attendance calendar"
                    >

                        @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $weekday)

                            <div class="attendance-calendar-weekday">
                                {{ $weekday }}
                            </div>

                        @endforeach


                        @foreach($attendanceCalendarDays as $calendarDay)

                            @php
                                $managementDateKey = $calendarDay->format('Y-m-d');

                                $isManagementCurrentMonth =
                                    $calendarDay->format('Y-m') ===
                                    $attendanceCalendarMonth->format('Y-m');

                                $managementDayData = $isManagementCurrentMonth
                                    ? ($managementCalendarDaysData[$managementDateKey] ?? null)
                                    : null;

                                $managementDayStats = $managementDayData
                                    ? $managementDayData['stats']
                                    : null;

                                $managementDayHasRecords = $managementDayStats
                                    ? $managementDayStats['marked'] > 0
                                    : false;

                                $managementDayIsDue = $managementDayData
                                    ? $managementDayData['is_due']
                                    : false;

                                $managementDayCardClass = 'attendance-day-card--upcoming';

                                if ($managementDayStats) {
                                    if ($managementDayStats['absent'] > 0) {
                                        $managementDayCardClass = 'attendance-day-card--danger';
                                    } elseif ($managementDayStats['not_marked'] > 0) {
                                        $managementDayCardClass = 'attendance-day-card--not-marked';
                                    } elseif ($managementDayStats['half_day'] > 0) {
                                        $managementDayCardClass = 'attendance-day-card--warning';
                                    } elseif ($managementDayStats['leave'] > 0) {
                                        $managementDayCardClass = 'attendance-day-card--primary';
                                    } elseif ($managementDayStats['present'] > 0) {
                                        $managementDayCardClass = 'attendance-day-card--success';
                                    } elseif ($managementDayStats['other'] > 0) {
                                        $managementDayCardClass = 'attendance-day-card--info';
                                    }
                                }
                            @endphp


                            @if(!$isManagementCurrentMonth)

                                <div
                                    class="attendance-day-card is-other-month"
                                    aria-hidden="true"
                                >

                                    <div class="attendance-day-number">
                                        <span>{{ $calendarDay->day }}</span>
                                    </div>

                                </div>

                            @else

                                <button
                                    type="button"
                                    class="attendance-day-card {{ $managementDayCardClass }} {{ $calendarDay->isToday() ? 'is-today' : '' }}"
                                    data-toggle="modal"
                                    data-target="#attendanceDayModal{{ $calendarDay->format('Ymd') }}"
                                    aria-label="View attendance details for {{ $calendarDay->format('d F Y') }}"
                                >

                                    <div class="attendance-day-number">

                                        <span>
                                            {{ $calendarDay->day }}
                                        </span>

                                        @if($calendarDay->isToday())

                                            <small>
                                                Today
                                            </small>

                                        @endif

                                    </div>


                                    @if($managementDayStats && $managementCalendarDrivers->isNotEmpty())

                                        <div class="attendance-day-details">

                                            <div class="attendance-day-counts">

                                                <span class="count-present">
                                                    <i class="fa fa-check"></i>
                                                    P: {{ $managementDayStats['present'] }}
                                                </span>

                                                <span class="count-absent">
                                                    <i class="fa fa-times"></i>
                                                    A: {{ $managementDayStats['absent'] }}
                                                </span>

                                                <span class="count-halfday">
                                                    <i class="fa fa-adjust"></i>
                                                    H: {{ $managementDayStats['half_day'] }}
                                                </span>

                                                <span class="count-leave">
                                                    <i class="fa fa-calendar"></i>
                                                    L: {{ $managementDayStats['leave'] }}
                                                </span>

                                                <span class="count-unmarked">
                                                    <i class="fa fa-question"></i>
                                                    NM: {{ $managementDayStats['not_marked'] }}
                                                </span>

                                            </div>


                                            @if($managementDayStats['upcoming'] > 0)

                                                <small class="text-muted d-block mt-1">

                                                    Upcoming:
                                                    {{ $managementDayStats['upcoming'] }}

                                                </small>

                                            @endif


                                            @if($managementDayStats['other'] > 0)

                                                <small class="text-info d-block mt-1">

                                                    Other:
                                                    {{ $managementDayStats['other'] }}

                                                </small>

                                            @endif


                                            <span class="attendance-day-click-hint">
                                                <i class="fa fa-eye"></i>
                                                View Details
                                            </span>

                                        </div>

                                    @elseif($managementCalendarDrivers->isEmpty())

                                        <small class="text-muted">
                                            No drivers available
                                        </small>

                                    @else

                                        <div class="attendance-day-details">

                                            @if($managementDayIsDue)

                                                <span class="badge badge-secondary">
                                                    Not Marked: 0
                                                </span>

                                            @else

                                                <span class="badge badge-light border">
                                                    Upcoming
                                                </span>

                                            @endif

                                            <span class="attendance-day-click-hint">
                                                <i class="fa fa-eye"></i>
                                                View Details
                                            </span>

                                        </div>

                                    @endif

                                </button>


                                {{-- ================================================= --}}
                                {{-- DATE-WISE DRIVER ATTENDANCE DETAILS MODAL --}}
                                {{-- ================================================= --}}
                                <div
                                    class="modal fade attendance-day-modal"
                                    id="attendanceDayModal{{ $calendarDay->format('Ymd') }}"
                                    tabindex="-1"
                                    role="dialog"
                                    aria-labelledby="attendanceDayModalLabel{{ $calendarDay->format('Ymd') }}"
                                    aria-hidden="true"
                                >

                                    <div
                                        class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable"
                                        role="document"
                                    >

                                        <div class="modal-content">

                                            <div class="modal-header">

                                                <div>

                                                    <h5
                                                        class="modal-title"
                                                        id="attendanceDayModalLabel{{ $calendarDay->format('Ymd') }}"
                                                    >

                                                        <i class="fa fa-calendar-check-o text-primary mr-1"></i>

                                                        Attendance Details -
                                                        {{ $calendarDay->format('d F Y') }}

                                                    </h5>

                                                    <small class="text-muted">

                                                        Driver-wise attendance information for the selected date.

                                                    </small>

                                                </div>

                                                <button
                                                    type="button"
                                                    class="close"
                                                    data-dismiss="modal"
                                                    aria-label="Close"
                                                >
                                                    <span aria-hidden="true">&times;</span>
                                                </button>

                                            </div>


                                            <div class="modal-body">

                                                @if($managementCalendarDrivers->isEmpty())

                                                    <div class="alert alert-info mb-0">

                                                        No drivers are available for this calendar.

                                                    </div>

                                                @else

                                                    @php
                                                        $modalDayData = $managementCalendarDaysData[$managementDateKey] ?? null;

                                                        $modalDayRows = $modalDayData
                                                            ? $modalDayData['rows']
                                                            : collect();
                                                    @endphp


                                                    <div class="row mb-3">

                                                        <div class="col-md-3 col-6 mb-2">

                                                            <div class="p-2 rounded border">

                                                                <small class="text-muted d-block">
                                                                    Present
                                                                </small>

                                                                <strong class="text-success">
                                                                    {{ $modalDayData['stats']['present'] ?? 0 }}
                                                                </strong>

                                                            </div>

                                                        </div>

                                                        <div class="col-md-3 col-6 mb-2">

                                                            <div class="p-2 rounded border">

                                                                <small class="text-muted d-block">
                                                                    Absent
                                                                </small>

                                                                <strong class="text-danger">
                                                                    {{ $modalDayData['stats']['absent'] ?? 0 }}
                                                                </strong>

                                                            </div>

                                                        </div>

                                                        <div class="col-md-3 col-6 mb-2">

                                                            <div class="p-2 rounded border">

                                                                <small class="text-muted d-block">
                                                                    Half Day
                                                                </small>

                                                                <strong style="color:#b7791f;">
                                                                    {{ $modalDayData['stats']['half_day'] ?? 0 }}
                                                                </strong>

                                                            </div>

                                                        </div>

                                                        <div class="col-md-3 col-6 mb-2">

                                                            <div class="p-2 rounded border">

                                                                <small class="text-muted d-block">
                                                                    Not Marked
                                                                </small>

                                                                <strong class="text-secondary">
                                                                    {{ $modalDayData['stats']['not_marked'] ?? 0 }}
                                                                </strong>

                                                            </div>

                                                        </div>

                                                    </div>


                                                    <div class="table-responsive">

                                                        <table class="table table-bordered table-hover mb-0">

                                                            <thead>

                                                                <tr>

                                                                    <th>Sr. No.</th>

                                                                    <th>Driver</th>

                                                                    <th>In Time</th>

                                                                    <th>Out Time</th>

                                                                    <th>Total Hours</th>

                                                                    <th>Status</th>

                                                                    <th>Source</th>

                                                                </tr>

                                                            </thead>


                                                            <tbody>

                                                                @forelse($modalDayRows as $modalKey => $modalRow)

                                                                    @php
                                                                        $modalDriver = $modalRow['driver'];
                                                                        $modalAttendance = $modalRow['attendance'];
                                                                        $modalCategory = $modalRow['category'];

                                                                        $modalStatusClass = match ($modalCategory) {
                                                                            'present' => 'success',
                                                                            'absent' => 'danger',
                                                                            'half_day' => 'warning',
                                                                            'leave' => 'primary',
                                                                            'other' => 'info',
                                                                            'not_marked' => 'secondary',
                                                                            default => 'light',
                                                                        };

                                                                        $modalStatusLabel = $modalAttendance
                                                                            ? ucfirst(str_replace('_', ' ', $modalAttendance->status ?? 'Unknown'))
                                                                            : ($modalCategory === 'upcoming' ? 'Upcoming' : 'Not Marked');
                                                                    @endphp


                                                                    <tr>

                                                                        <td>
                                                                            {{ $modalKey + 1 }}
                                                                        </td>


                                                                        <td>

                                                                            <strong>
                                                                                {{ trim($modalDriver->first_name . ' ' . $modalDriver->last_name) ?: '-' }}
                                                                            </strong>

                                                                            @if(!empty($modalDriver->driver_code))

                                                                                <small class="text-muted d-block">
                                                                                    {{ $modalDriver->driver_code }}
                                                                                </small>

                                                                            @endif

                                                                        </td>


                                                                        <td>

                                                                            @if($modalAttendance && $modalAttendance->in_time)

                                                                                {{ \Carbon\Carbon::parse($modalAttendance->in_time)->format('h:i A') }}

                                                                            @else

                                                                                -

                                                                            @endif

                                                                        </td>


                                                                        <td>

                                                                            @if($modalAttendance && $modalAttendance->out_time)

                                                                                {{ \Carbon\Carbon::parse($modalAttendance->out_time)->format('h:i A') }}

                                                                            @else

                                                                                -

                                                                            @endif

                                                                        </td>


                                                                        <td>

                                                                            @if($modalAttendance && $modalAttendance->total_hours !== null)

                                                                                {{ number_format((float) $modalAttendance->total_hours, 2) }}
                                                                                hrs

                                                                            @else

                                                                                -

                                                                            @endif

                                                                        </td>


                                                                        <td>

                                                                            <span class="badge badge-{{ $modalStatusClass }}">

                                                                                {{ $modalStatusLabel }}

                                                                            </span>

                                                                        </td>


                                                                        <td>

                                                                            @if($modalAttendance)

                                                                                {{ ucfirst(str_replace('_', ' ', $modalAttendance->source ?? 'Unknown')) }}

                                                                            @else

                                                                                -

                                                                            @endif

                                                                        </td>

                                                                    </tr>

                                                                @empty

                                                                    <tr>

                                                                        <td colspan="7" class="text-center text-muted py-4">

                                                                            No driver details are available for this date.

                                                                        </td>

                                                                    </tr>

                                                                @endforelse

                                                            </tbody>

                                                        </table>

                                                    </div>

                                                @endif

                                            </div>


                                            <div class="modal-footer">

                                                <button
                                                    type="button"
                                                    class="btn btn-secondary"
                                                    data-dismiss="modal"
                                                >
                                                    Close
                                                </button>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            @endif

                        @endforeach

                    </div>


                    <div class="text-muted mt-3">

                        <small>

                            <i class="fa fa-info-circle mr-1"></i>

                            P = Present, A = Absent, H = Half Day / Exceptions,
                            L = Leave, NM = Not Marked.
                            Not Marked means no attendance record exists for an elapsed date;
                            it is not treated as Absent. Future dates are shown as Upcoming.

                        </small>

                    </div>

                </div>

            </div>

        @endif

                {{-- ========================================================= --}}
        {{-- MANAGEMENT PAGINATED TABLE VIEW --}}
        {{-- ========================================================= --}}
        @if(!$isDriver && $managementCalendarView === 'table')

            @php
                /*
                 * Keep the existing management paginator.
                 * Load related data to avoid repeated relationship queries.
                 */
                $attendances->getCollection()->loadMissing([
                    'driver',
                    'createdBy',
                    'workingSheet.dutySlip.dutyAssignment.travelRequest',
                ]);
            @endphp


            <div class="card-box mb-30">

                {{-- Card Header --}}
                <div class="pd-20">

                    <div class="attendance-table-heading d-flex flex-wrap justify-content-between align-items-center">

                        <div>

                            <h4 class="text-blue h4 mb-1">
                                All Driver Attendance Records
                            </h4>

                            <small class="text-muted">
                                Detailed attendance records with edit and delete actions.
                            </small>

                        </div>


                        <span class="badge badge-primary px-3 py-2">
                            Total Records: {{ $attendances->total() }}
                        </span>

                    </div>

                </div>


                {{-- Attendance Table --}}
                <div class="pb-20">

                    <div class="table-responsive attendance-table-wrap">

                        <table
                            class="table hover multiple-select-row data-table-export1 nowrap p-3 attendance-management-table"
                            data-title="Driver Attendance"
                        >

                            <thead>

                                <tr>

                                    <th>Sr. No.</th>

                                    <th>Driver</th>

                                    <th>Attendance Date</th>

                                    <th>In Time</th>

                                    <th>Out Time</th>

                                    <th>Total Hours</th>

                                    <th>Status</th>

                                    <th>Source</th>

                                    <th>Created By</th>

                                    <th class="no-export">Edit</th>

                                    <th class="no-export">Delete</th>

                                </tr>

                            </thead>


                            <tbody>

                                @forelse($attendances as $key => $attendance)

                                    @php
                                        $attendanceWorkingSheet = $attendance->workingSheet;

                                        $attendanceDutySlip = $attendanceWorkingSheet
                                            ? $attendanceWorkingSheet->dutySlip
                                            : null;

                                        $attendanceDutyAssignment = $attendanceDutySlip
                                            ? $attendanceDutySlip->dutyAssignment
                                            : null;

                                        $attendanceTravelRequest = $attendanceDutyAssignment
                                            ? $attendanceDutyAssignment->travelRequest
                                            : null;


                                        $attendanceStatus = $attendance->status;

                                        $attendanceStatusCategory = $getAttendanceCategory(
                                            $attendanceStatus
                                        );

                                        $attendanceStatusClass = match ($attendanceStatusCategory) {
                                            'present' => 'badge-success',
                                            'absent' => 'badge-danger',
                                            'half_day' => 'badge-warning',
                                            'leave' => 'badge-primary',
                                            'other' => 'badge-info',
                                            default => 'badge-secondary',
                                        };

                                        $attendanceStatusIcon = match ($attendanceStatusCategory) {
                                            'present' => 'fa-check-circle',
                                            'absent' => 'fa-times-circle',
                                            'half_day' => 'fa-adjust',
                                            'leave' => 'fa-calendar',
                                            'other' => 'fa-info-circle',
                                            default => 'fa-clock-o',
                                        };


                                        $attendanceSource = $attendance->source;

                                        $attendanceSourceClass = $attendanceSource === 'working_sheet'
                                            ? 'badge-info'
                                            : 'badge-secondary';
                                    @endphp


                                    <tr>

                                        {{-- Sr. No. --}}
                                        <td>

                                            {{ ($attendances->currentPage() - 1) * $attendances->perPage() + $key + 1 }}

                                        </td>


                                        {{-- Driver --}}
                                        <td class="driver-name-cell">

                                            @if($attendance->driver)

                                                <strong class="text-dark">

                                                    {{ trim(
                                                        $attendance->driver->first_name . ' ' .
                                                        $attendance->driver->last_name
                                                    ) }}

                                                </strong>

                                                @if(!empty($attendance->driver->driver_code))

                                                    <small class="text-muted d-block driver-code">

                                                        {{ $attendance->driver->driver_code }}

                                                    </small>

                                                @endif

                                            @else

                                                <span class="text-muted">
                                                    Driver Not Available
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Attendance Date --}}
                                        <td>

                                            @if($attendance->attendance_date)

                                                <strong>

                                                    {{ \Carbon\Carbon::parse(
                                                        $attendance->attendance_date
                                                    )->format('d M Y') }}

                                                </strong>

                                                <small class="text-muted d-block">

                                                    {{ \Carbon\Carbon::parse(
                                                        $attendance->attendance_date
                                                    )->format('l') }}

                                                </small>

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- In Time --}}
                                        <td>

                                            @if($attendance->in_time)

                                                <span class="attendance-time">

                                                    <i class="fa fa-sign-in text-success mr-1"></i>

                                                    {{ \Carbon\Carbon::parse(
                                                        $attendance->in_time
                                                    )->format('h:i A') }}

                                                </span>

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- Out Time --}}
                                        <td>

                                            @if($attendance->out_time)

                                                <span class="attendance-time">

                                                    <i class="fa fa-sign-out text-danger mr-1"></i>

                                                    {{ \Carbon\Carbon::parse(
                                                        $attendance->out_time
                                                    )->format('h:i A') }}

                                                </span>

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- Total Hours --}}
                                        <td>

                                            @if($attendance->total_hours !== null)

                                                <span class="badge badge-info px-2 py-2">

                                                    {{ number_format(
                                                        (float) $attendance->total_hours,
                                                        2
                                                    ) }}

                                                    hrs

                                                </span>

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- Status --}}
                                        <td>

                                            <span class="badge {{ $attendanceStatusClass }} badge-pill px-3 py-2">

                                                <i class="fa {{ $attendanceStatusIcon }} mr-1"></i>

                                                {{ ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $attendanceStatus ?? 'Unknown'
                                                    )
                                                ) }}

                                            </span>

                                        </td>


                                        {{-- Source --}}
                                        <td>

                                            <span class="badge {{ $attendanceSourceClass }}">

                                                {{ ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $attendanceSource ?? 'Unknown'
                                                    )
                                                ) }}

                                            </span>

                                        </td>


                                        {{-- Created By --}}
                                        <td>

                                            @if($attendance->createdBy)

                                                <span class="text-dark">

                                                    {{ $attendance->createdBy->name }}

                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    -
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Edit --}}
                                        <td class="no-export">

                                            <a
                                                href="{{ route('driver-attendances.edit', $attendance->id) }}"
                                                class="btn btn-warning btn-sm attendance-action-btn"
                                                aria-label="Edit attendance"
                                                title="Edit attendance"
                                            >

                                                <i class="dw dw-pencil-1"></i>

                                                <span>Edit</span>

                                            </a>

                                        </td>


                                        {{-- Delete --}}
                                        <td class="no-export">

                                            <form
                                                action="{{ route(
                                                    'driver-attendances.destroy',
                                                    $attendance->id
                                                ) }}"
                                                method="POST"
                                                class="delete-form"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger btn-sm attendance-action-btn"
                                                >

                                                    <i class="dw dw-trash"></i>

                                                    <span>Delete</span>

                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="11" class="text-center py-5">

                                            <div class="p-3">

                                                <i class="fa fa-calendar-times-o fa-3x text-muted mb-3"></i>

                                                <h5 class="text-muted">
                                                    No Driver Attendance Found
                                                </h5>

                                                <p class="text-muted mb-0">

                                                    No attendance records match
                                                    the selected filters.

                                                </p>

                                            </div>

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- Pagination --}}
                    @if($attendances->hasPages())

                        <div class="pd-20">

                            {{ $attendances->appends(request()->query())->links() }}

                        </div>

                    @endif

                </div>

            </div>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- FOOTER --}}
    {{-- ========================================================= --}}
    <x-backend.footer />

</div>

@endsection


{{-- ================================================================ --}}
{{-- JAVASCRIPT --}}
{{-- ================================================================ --}}
@push('scripts')

@if(!$isDriver && $managementCalendarView === 'table')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /*
            |--------------------------------------------------------------------------
            | DELETE CONFIRMATION
            |--------------------------------------------------------------------------
            */

            document.querySelectorAll('.delete-form').forEach(function (form) {

                form.addEventListener('submit', function (event) {

                    event.preventDefault();

                    if (typeof Swal === 'undefined') {

                        if (window.confirm(
                            'Are you sure you want to delete this attendance record?'
                        )) {
                            form.submit();
                        }

                        return;
                    }


                    Swal.fire({

                        title: 'Are you sure?',

                        text: 'This attendance record will be moved to trash.',

                        icon: 'warning',

                        showCancelButton: true,

                        confirmButtonColor: '#d33',

                        cancelButtonColor: '#6c757d',

                        confirmButtonText: 'Yes, Delete',

                        cancelButtonText: 'Cancel',

                        reverseButtons: true

                    }).then(function (result) {

                        if (result.isConfirmed) {

                            /*
                             * Native submit avoids triggering this listener again.
                             */
                            form.submit();

                        }

                    });

                });

            });


            /*
            |--------------------------------------------------------------------------
            | ATTENDANCE TABLE INITIALIZATION
            |--------------------------------------------------------------------------
            |
            | Keep the existing project DataTable initialization.
            |
            */

        });
    </script>

    <script src="{{ asset('backend/assets/datatable/js/datatable-init.js') }}"></script>

@endif

@endpush
