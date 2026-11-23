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
       DRIVER ATTENDANCE - DESIGN SYSTEM
    ================================================================ */
    .driver-attendance-page {
        --da-primary: #2457a7;
        --da-primary-dark: #193f7d;
        --da-primary-soft: #edf4ff;
        --da-border: #e7edf5;
        --da-muted: #718096;
        --da-text: #344054;
        --da-success: #16834a;
        --da-danger: #c43c48;
        --da-warning: #a66b13;
        --da-info: #1684a5;
    }

    .driver-attendance-page .page-header {
        margin-bottom: 22px;
        padding: 23px 25px;
        background: linear-gradient(115deg, #ffffff 0%, #f4f8ff 100%);
        border: 1px solid var(--da-border);
        border-radius: 15px;
        box-shadow: 0 5px 18px rgba(26, 47, 84, .045);
    }

    .driver-attendance-page .page-header h4 {
        margin-bottom: 7px;
        color: var(--da-primary);
        font-size: 22px;
        font-weight: 750;
        letter-spacing: -.3px;
    }

    .driver-attendance-page .page-header p {
        margin-bottom: 0;
        color: var(--da-muted);
        line-height: 1.55;
    }

    .driver-attendance-page .card-box {
        overflow: hidden;
        border: 1px solid var(--da-border);
        border-radius: 14px;
        box-shadow: 0 7px 24px rgba(26, 47, 84, .055);
    }

    .driver-attendance-page .card-box > .pd-20 {
        padding: 22px 24px;
    }

    .driver-attendance-page .btn {
        border-radius: 8px;
        font-weight: 650;
        transition: transform .16s ease, box-shadow .16s ease;
    }

    .driver-attendance-page .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(31, 55, 92, .12);
    }

    .driver-attendance-page .form-control {
        min-height: 41px;
        border-color: #dce3ee;
        border-radius: 8px;
        background: #fff;
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
       DRIVER HEADER
    ================================================================ */
    .driver-welcome-strip {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 20px;
        padding: 16px 19px;
        border: 1px solid #dce8f9;
        border-radius: 12px;
        background: linear-gradient(110deg, #f4f8ff 0%, #ffffff 100%);
    }

    .driver-welcome-strip .welcome-icon {
        display: inline-flex;
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #e4efff;
        color: var(--da-primary);
        font-size: 18px;
    }

    .driver-welcome-strip .welcome-title {
        color: #26364b;
        font-size: 14px;
        font-weight: 750;
    }

    .driver-welcome-strip .welcome-subtitle {
        margin-top: 3px;
        color: var(--da-muted);
        font-size: 11px;
    }

    .driver-welcome-strip .welcome-month {
        color: var(--da-primary);
        font-size: 12px;
        font-weight: 750;
        white-space: nowrap;
    }

    /* ================================================================
       MANAGEMENT VIEW SWITCHER
    ================================================================ */
    .attendance-view-switcher {
        display: inline-flex;
        align-items: center;
        gap: 3px;
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
        color: var(--da-primary);
        background: #fff;
    }

    .attendance-view-switcher a.active {
        color: #fff;
        background: var(--da-primary);
        box-shadow: 0 3px 8px rgba(36, 87, 167, .2);
    }

    /* ================================================================
       FILTERS
    ================================================================ */
    .attendance-filter-form {
        padding: 22px;
        border: 1px solid var(--da-border);
        border-radius: 11px;
        background: #fbfcff;
    }

    .attendance-filter-form label,
    .management-calendar-filter label {
        display: block;
        margin-bottom: 7px;
        color: #344054;
        font-size: 12px;
        font-weight: 750;
    }

    .attendance-filter-form .form-control,
    .management-calendar-filter .form-control {
        background: #fff;
    }

    .management-calendar-filter {
        padding: 17px;
        border: 1px solid var(--da-border);
        border-radius: 10px;
        background: #fbfcff;
    }

    /* ================================================================
       CALENDAR TOOLBAR
    ================================================================ */
    .driver-calendar-toolbar {
        gap: 14px;
        padding-bottom: 18px;
        border-bottom: 1px solid #edf1f7;
    }

    .driver-section-eyebrow {
        display: inline-block;
        margin-bottom: 6px;
        color: #718096;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.1px;
    }

    .driver-calendar-toolbar h4 {
        color: var(--da-primary);
        font-weight: 750;
        letter-spacing: -.25px;
    }

    .driver-calendar-navigation {
        gap: 5px;
        padding: 5px;
        border: 1px solid #e8edf5;
        border-radius: 10px;
        background: #f8faff;
    }

    .calendar-nav-btn {
        display: inline-flex;
        width: 35px;
        height: 35px;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-color: #e0e7f1;
        background: #fff;
    }

    .calendar-month-label {
        min-width: 112px;
        color: #26364b;
        text-align: center;
        font-size: 14px;
        font-weight: 800;
    }

    .calendar-month-picker {
        width: 145px;
        min-height: 35px !important;
        margin-left: 7px;
        padding: 5px 8px;
        font-size: 11px;
    }

    /* ================================================================
       SUMMARY CARDS
    ================================================================ */
    .attendance-summary-card {
        position: relative;
        height: 100%;
        min-height: 132px;
        padding: 17px 18px;
        overflow: hidden;
        border: 1px solid var(--da-border);
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(26, 47, 84, .035);
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .attendance-summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 9px 21px rgba(26, 47, 84, .085);
    }

    .attendance-summary-card::after {
        position: absolute;
        right: -19px;
        bottom: -28px;
        width: 82px;
        height: 82px;
        border-radius: 50%;
        background: rgba(36, 87, 167, .035);
        content: "";
        pointer-events: none;
    }

    .attendance-summary-card .summary-icon {
        display: inline-flex;
        width: 35px;
        height: 35px;
        align-items: center;
        justify-content: center;
        float: right;
        border-radius: 10px;
        background: #f1f5fb;
        color: var(--da-primary);
        font-size: 15px;
    }

    .attendance-summary-card .summary-label {
        display: block;
        padding-top: 4px;
        color: #667085;
        font-size: 11px;
        font-weight: 750;
    }

    .attendance-summary-card .summary-value {
        display: block;
        margin-top: 9px;
        color: #26364b;
        font-size: 26px;
        font-weight: 800;
        line-height: 1.1;
    }

    .attendance-summary-card .summary-caption {
        display: block;
        margin-top: 6px;
        color: #98a2b3;
        font-size: 10px;
        line-height: 1.35;
    }

    .summary-card-present .summary-icon {
        color: #16834a;
        background: #eaf8f0;
    }

    .summary-card-absent .summary-icon {
        color: #c43c48;
        background: #fff0f1;
    }

    .summary-card-hours .summary-icon {
        color: #2457a7;
        background: #edf4ff;
    }

    .summary-card-average .summary-icon {
        color: #1684a5;
        background: #eaf8fb;
    }

    .summary-card-exception .summary-icon {
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

    .summary-card-rate .summary-icon {
        color: #16834a;
        background: #eaf8f0;
    }

    /* ================================================================
       PRESENT SHARE INDICATOR
    ================================================================ */
    .attendance-progress-panel {
        padding: 16px 18px;
        border: 1px solid #e4ebf5;
        border-radius: 11px;
        background: #fbfcff;
    }

    .attendance-progress-panel .progress {
        height: 8px;
        overflow: hidden;
        border-radius: 20px;
        background: #e9eef5;
    }

    .attendance-progress-panel .progress-bar {
        border-radius: 20px;
        background: linear-gradient(90deg, #2c66b8, #39a477);
    }

    .attendance-progress-panel .progress-label {
        color: #526176;
        font-size: 11px;
        font-weight: 750;
    }

    .attendance-progress-panel .progress-value {
        color: var(--da-primary);
        font-size: 13px;
        font-weight: 800;
    }

    /* ================================================================
       CALENDAR LEGEND
    ================================================================ */
    .attendance-calendar-legend {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px;
        padding: 12px 14px 5px;
        border: 1px solid #edf1f7;
        border-radius: 9px;
        background: #fbfcfe;
    }

    .attendance-calendar-legend .badge {
        margin-bottom: 7px;
        padding: 7px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
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
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .5px;
        text-transform: uppercase;
    }

    .attendance-day-card {
        position: relative;
        display: block;
        width: 100%;
        min-height: 137px;
        padding: 11px;
        overflow: hidden;
        border: 1px solid #e7ecf3;
        border-radius: 10px;
        background: #fff;
        color: var(--da-text);
        text-align: left;
        overflow-wrap: anywhere;
        box-shadow: 0 2px 7px rgba(26, 47, 84, .025);
        transition: border-color .16s ease, box-shadow .16s ease, transform .16s ease;
    }

    button.attendance-day-card {
        cursor: pointer;
    }

    button.attendance-day-card:focus {
        outline: 2px solid #6b9de6;
        outline-offset: 2px;
    }

    button.attendance-day-card:hover {
        z-index: 1;
        transform: translateY(-2px);
        border-color: #a9bfdc;
        box-shadow: 0 8px 17px rgba(26, 47, 84, .085);
    }

    .attendance-day-card.is-today {
        border: 2px solid #3978d4;
        background: #f7faff;
        box-shadow: 0 0 0 3px rgba(57, 120, 212, .08);
    }

    .attendance-day-card.is-other-month {
        min-height: 137px;
        border-style: dashed;
        background: #f8f9fb;
        color: #adb5bd;
        box-shadow: none;
        cursor: default;
    }

    .attendance-day-number {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 5px;
        margin-bottom: 10px;
        color: #344054;
        font-size: 13px;
        font-weight: 800;
    }

    .attendance-day-number small {
        padding: 3px 6px;
        border-radius: 20px;
        background: #eaf2ff;
        color: #2457a7;
        font-size: 9px;
        font-weight: 750;
    }

    .attendance-day-details {
        color: #475467;
        font-size: 10px;
        line-height: 1.55;
    }

    .attendance-day-details .badge {
        display: inline-block;
        max-width: 100%;
        padding: 6px 8px;
        border-radius: 6px;
        font-size: 9px;
        font-weight: 750;
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

    .attendance-day-time {
        display: block;
        margin-top: 5px;
        color: #667085;
        font-size: 10px;
        font-weight: 650;
    }

    .attendance-day-hours {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 5px;
        padding: 4px 6px;
        border-radius: 5px;
        background: #edf4ff;
        color: #2457a7;
        font-size: 9px;
        font-weight: 800;
    }

    .attendance-day-sheet {
        display: block;
        margin-top: 5px;
        color: #718096;
        font-size: 9px;
    }

    .attendance-day-click-hint {
        display: block;
        margin-top: 8px;
        color: #3978d4;
        font-size: 9px;
        font-weight: 750;
    }

    /* ================================================================
       MANAGEMENT DAY COUNTS
    ================================================================ */
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
        font-size: 9px;
        font-weight: 750;
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

    .attendance-day-counts .count-exception {
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

    /* ================================================================
       DAY DETAIL MODALS
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
        font-weight: 800;
    }

    .attendance-day-modal .modal-body {
        padding: 18px 20px;
    }

    .attendance-day-modal .modal-footer {
        border-top: 1px solid #edf1f7;
        background: #fbfcfe;
    }

    .attendance-detail-box {
        height: 100%;
        padding: 12px;
        border: 1px solid #e8edf5;
        border-radius: 9px;
        background: #fff;
    }

    .attendance-detail-box small {
        display: block;
        color: #718096;
        font-size: 10px;
        font-weight: 700;
    }

    .attendance-detail-box strong {
        display: block;
        margin-top: 5px;
        color: #26364b;
        font-size: 14px;
        font-weight: 800;
    }

    /* ================================================================
       TABLE VIEW
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

    .driver-code,
    .attendance-time,
    .amount-text {
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

        .attendance-summary-card {
            min-height: 124px;
        }
    }

    @media (max-width: 767.98px) {
        .driver-attendance-page .page-header {
            padding: 18px;
        }

        .driver-attendance-page .card-box > .pd-20 {
            padding: 16px;
        }

        .driver-welcome-strip {
            align-items: flex-start;
            padding: 13px;
        }

        .driver-calendar-toolbar {
            align-items: flex-start !important;
        }

        .driver-calendar-navigation {
            width: 100%;
            justify-content: center;
        }

        .calendar-month-picker {
            width: 125px;
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

        .attendance-day-time {
            font-size: 8px;
        }

        .attendance-day-hours {
            padding: 3px 4px;
            font-size: 8px;
        }

        .attendance-day-sheet,
        .attendance-day-click-hint {
            font-size: 8px;
        }

        .attendance-summary-card {
            min-height: 117px;
            padding: 12px;
        }

        .attendance-summary-card .summary-value {
            font-size: 22px;
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

        .attendance-day-counts {
            gap: 2px;
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
            min-height: 94px;
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

        .attendance-summary-card .summary-label {
            font-size: 10px;
        }

        .attendance-summary-card .summary-caption {
            font-size: 9px;
        }

        .driver-welcome-strip .welcome-icon {
            width: 36px;
            height: 36px;
            flex-basis: 36px;
        }
    }
</style>

@endpush


@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | COMMON MONTH SELECTION
    |--------------------------------------------------------------------------
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
    | ATTENDANCE STATUS CLASSIFICATION
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

    $attendanceExceptionStatuses = [
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

    $attendanceOffStatuses = [
        'holiday',
        'weekly_off',
    ];

    $getAttendanceCategory = function ($status) use (
        $attendancePresentStatuses,
        $attendanceAbsentStatuses,
        $attendanceExceptionStatuses,
        $attendanceLeaveStatuses,
        $attendanceOffStatuses
    ) {
        if (in_array($status, $attendancePresentStatuses, true)) {
            return 'present';
        }

        if (in_array($status, $attendanceAbsentStatuses, true)) {
            return 'absent';
        }

        if (in_array($status, $attendanceExceptionStatuses, true)) {
            return 'exception';
        }

        if (in_array($status, $attendanceLeaveStatuses, true)) {
            return 'leave';
        }

        if (in_array($status, $attendanceOffStatuses, true)) {
            return 'off';
        }

        return 'other';
    };

    $getAttendanceStatusMeta = function ($status) use ($getAttendanceCategory) {
        $category = $getAttendanceCategory($status);

        $meta = [
            'category' => $category,
            'label' => ucfirst(str_replace('_', ' ', $status ?? 'Unknown')),
            'badge' => 'secondary',
            'icon' => 'fa-info-circle',
            'card' => 'secondary',
        ];

        switch ($category) {
            case 'present':
                $meta['badge'] = 'success';
                $meta['icon'] = 'fa-check-circle';
                $meta['card'] = 'success';
                break;

            case 'absent':
                $meta['badge'] = 'danger';
                $meta['icon'] = 'fa-times-circle';
                $meta['card'] = 'danger';
                break;

            case 'exception':
                $meta['badge'] = 'warning';
                $meta['icon'] = 'fa-exclamation-circle';
                $meta['card'] = 'warning';
                break;

            case 'leave':
                $meta['badge'] = 'primary';
                $meta['icon'] = 'fa-calendar';
                $meta['card'] = 'primary';
                break;

            case 'off':
                $meta['badge'] = 'info';
                $meta['icon'] = 'fa-calendar-o';
                $meta['card'] = 'info';
                break;

            default:
                $meta['badge'] = 'secondary';
                $meta['icon'] = 'fa-info-circle';
                $meta['card'] = 'secondary';
                break;
        }

        return $meta;
    };


    /*
    |--------------------------------------------------------------------------
    | VIEW SELECTION
    |--------------------------------------------------------------------------
    */

    $managementCalendarView = request()->query('view', 'calendar');

    if (!in_array($managementCalendarView, ['calendar', 'table'], true)) {
        $managementCalendarView = 'calendar';
    }


    /*
    |--------------------------------------------------------------------------
    | DRIVER MONTHLY ATTENDANCE QUERY
    |--------------------------------------------------------------------------
    */

    $driverCalendarDriver = null;
    $driverCalendarAttendances = collect();

    $driverCalendarMarkedCount = 0;
    $driverCalendarPresentCount = 0;
    $driverCalendarAbsentCount = 0;
    $driverCalendarExceptionCount = 0;
    $driverCalendarLeaveCount = 0;
    $driverCalendarNotMarkedCount = 0;
    $driverCalendarDueDays = 0;
    $driverCalendarTotalHours = 0;
    $driverCalendarDaysWithHours = 0;
    $driverCalendarAverageHours = 0;
    $driverCalendarPresentShare = 0;

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

            $driverCalendarExceptionCount = $driverCalendarAttendances
                ->filter(function ($attendance) use ($getAttendanceCategory) {
                    return $getAttendanceCategory($attendance->status) === 'exception';
                })
                ->count();

            $driverCalendarLeaveCount = $driverCalendarAttendances
                ->filter(function ($attendance) use ($getAttendanceCategory) {
                    return $getAttendanceCategory($attendance->status) === 'leave';
                })
                ->count();

            $driverCalendarTotalHours = (float) $driverCalendarAttendances
                ->sum(function ($attendance) {
                    return is_numeric($attendance->total_hours)
                        ? (float) $attendance->total_hours
                        : 0;
                });

            $driverCalendarDaysWithHours = $driverCalendarAttendances
                ->filter(function ($attendance) {
                    return $attendance->total_hours !== null
                        && is_numeric($attendance->total_hours);
                })
                ->count();

            $driverCalendarAverageHours = $driverCalendarDaysWithHours > 0
                ? $driverCalendarTotalHours / $driverCalendarDaysWithHours
                : 0;

            $driverCalendarPresentShare = $driverCalendarMarkedCount > 0
                ? round(
                    ($driverCalendarPresentCount / $driverCalendarMarkedCount) * 100
                )
                : 0;

            /*
             * Not Marked is calculated only for elapsed calendar dates.
             * Future dates are Upcoming, not Not Marked.
             *
             * This calculation does not assume weekly-off or holiday rules.
             */
            foreach ($attendanceCalendarDays as $calendarDate) {
                if (
                    $calendarDate->format('Y-m') ===
                        $attendanceCalendarMonth->format('Y-m') &&
                    $calendarDate->format('Y-m-d') <=
                        $attendanceCalendarToday->toDateString()
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
    | MANAGEMENT CALENDAR DRIVER COLLECTION
    |--------------------------------------------------------------------------
    */

    $managementCalendarDrivers = $drivers ?? collect();

    if (
        $managementCalendarDrivers instanceof
        \Illuminate\Pagination\AbstractPaginator
    ) {
        $managementCalendarDrivers = $managementCalendarDrivers->getCollection();
    }

    $managementCalendarDrivers = collect($managementCalendarDrivers);

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


    /*
    |--------------------------------------------------------------------------
    | MANAGEMENT MONTHLY ATTENDANCE QUERY
    |--------------------------------------------------------------------------
    */

    $managementCalendarAttendances = collect();

    if (
        !$isDriver &&
        $managementCalendarView === 'calendar' &&
        $managementCalendarDrivers->isNotEmpty()
    ) {
        $managementCalendarDriverIds = $managementCalendarDrivers
            ->pluck('id')
            ->filter()
            ->values();

        if ($managementCalendarDriverIds->isNotEmpty()) {
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
        'exception' => 0,
        'leave' => 0,
        'not_marked' => 0,
        'other' => 0,
        'marked' => 0,
    ];

    if (!$isDriver && $managementCalendarView === 'calendar') {
        foreach ($attendanceCalendarDays as $calendarDate) {
            if (
                $calendarDate->format('Y-m') !==
                $attendanceCalendarMonth->format('Y-m')
            ) {
                continue;
            }

            $dateKey = $calendarDate->format('Y-m-d');

            $isAttendanceDue = $dateKey <=
                $attendanceCalendarToday->toDateString();

            $dateRecords = $managementCalendarRecordsByDate
                ->get($dateKey, collect())
                ->keyBy('driver_id');

            $dayRows = collect();

            $dayStats = [
                'present' => 0,
                'absent' => 0,
                'exception' => 0,
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

                if (array_key_exists($dayCategory, $dayStats)) {
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
                'exception',
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
    | MONTH NAVIGATION URLS
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
    | MANAGEMENT VIEW URLS
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

                    <h4>
                        {{ $isDriver ? 'My Attendance' : 'Driver Attendance Management' }}
                    </h4>

                    <p>
                        @if($isDriver)
                            Track your attendance, daily working hours and monthly attendance summary.
                        @else
                            Monitor attendance across drivers, review daily status and manage records.
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
        {{-- MANAGEMENT FILTERS --}}
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
                            <input
                                type="hidden"
                                name="month"
                                value="{{ request('month') }}"
                            >
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

                                    <option value="">
                                        All Drivers
                                    </option>

                                    @foreach($drivers as $driver)

                                        <option
                                            value="{{ $driver->id }}"
                                            {{ (string) request('driver_id') === (string) $driver->id ? 'selected' : '' }}
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

                                    <option value="">
                                        All Status
                                    </option>

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

                                    <option value="">
                                        All Sources
                                    </option>

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
        {{-- DRIVER PERSONAL ATTENDANCE DASHBOARD --}}
        {{-- ========================================================= --}}
        @if($isDriver)

            <div class="card-box mb-30">

                <div class="pd-20">

                    @if(!$driverCalendarDriver)

                        <div class="alert alert-warning mb-0" role="alert">

                            <i class="fa fa-exclamation-triangle mr-1"></i>

                            Your user account is not mapped with a Driver record.
                            Please contact the administrator.

                        </div>

                    @else

                        {{-- ================================================= --}}
                        {{-- DRIVER WELCOME STRIP --}}
                        {{-- ================================================= --}}
                        <div class="driver-welcome-strip">

                            <div class="d-flex align-items-center">

                                <span class="welcome-icon mr-3">
                                    <i class="fa fa-id-card-o"></i>
                                </span>

                                <div>

                                    <div class="welcome-title">
                                        Welcome, {{ auth()->user()->name ?? 'Driver' }}
                                    </div>

                                    <div class="welcome-subtitle">
                                        Your personal attendance dashboard
                                    </div>

                                </div>

                            </div>

                            <div class="welcome-month">
                                <i class="fa fa-calendar mr-1"></i>
                                {{ $attendanceCalendarMonth->format('F Y') }}
                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- DRIVER CALENDAR TOOLBAR --}}
                        {{-- ================================================= --}}
                        <div class="driver-calendar-toolbar d-flex flex-wrap justify-content-between align-items-center mb-20">

                            <div class="mb-3 mb-md-0">

                                <span class="driver-section-eyebrow">
                                    MONTHLY ATTENDANCE
                                </span>

                                <h4 class="text-blue h4 mb-1">
                                    My Attendance Calendar
                                </h4>

                                <p class="text-muted mb-0">
                                    Select a date to view your complete attendance details.
                                </p>

                            </div>


                            <div class="d-flex flex-wrap align-items-center">

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

                        </div>


                        {{-- Month Picker --}}
                        <form
                            action="{{ route('driver-attendances.index') }}"
                            method="GET"
                            class="management-calendar-filter mb-4"
                        >

                            <div class="row align-items-end">

                                <div class="col-md-4 col-sm-7 mb-2 mb-md-0">

                                    <label for="driver-attendance-month">
                                        View Attendance Month
                                    </label>

                                    <input
                                        type="month"
                                        id="driver-attendance-month"
                                        name="month"
                                        value="{{ $attendanceCalendarMonth->format('Y-m') }}"
                                        class="form-control"
                                        max="{{ now()->format('Y-m') }}"
                                    >

                                </div>

                                <div class="col-md-3 col-sm-5">

                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        <i class="fa fa-calendar-check-o"></i>
                                        View Month
                                    </button>

                                </div>

                                <div class="col-md-5 d-none d-md-block text-md-right">

                                    <small class="text-muted">
                                        <i class="fa fa-info-circle mr-1"></i>
                                        Choose a month to review your attendance history.
                                    </small>

                                </div>

                            </div>

                        </form>


                        {{-- ================================================= --}}
                        {{-- MONTHLY SUMMARY CARDS --}}
                        {{-- ================================================= --}}
                        <div class="row mb-2">

                            {{-- Present --}}
                            <div class="col-xl-3 col-md-4 col-6 mb-3">

                                <div class="attendance-summary-card summary-card-present">

                                    <span class="summary-icon">
                                        <i class="fa fa-check-circle"></i>
                                    </span>

                                    <span class="summary-label">
                                        Present Days
                                    </span>

                                    <span class="summary-value text-success">
                                        {{ $driverCalendarPresentCount }}
                                    </span>

                                    <span class="summary-caption">
                                        Present and duty records
                                    </span>

                                </div>

                            </div>


                            {{-- Total Working Hours --}}
                            <div class="col-xl-3 col-md-4 col-6 mb-3">

                                <div class="attendance-summary-card summary-card-hours">

                                    <span class="summary-icon">
                                        <i class="fa fa-clock-o"></i>
                                    </span>

                                    <span class="summary-label">
                                        Total Working Hours
                                    </span>

                                    <span class="summary-value text-primary">
                                        {{ number_format($driverCalendarTotalHours, 2) }}
                                    </span>

                                    <span class="summary-caption">
                                        Total hours recorded this month
                                    </span>

                                </div>

                            </div>


                            {{-- Average Working Hours --}}
                            <div class="col-xl-3 col-md-4 col-6 mb-3">

                                <div class="attendance-summary-card summary-card-average">

                                    <span class="summary-icon">
                                        <i class="fa fa-line-chart"></i>
                                    </span>

                                    <span class="summary-label">
                                        Average Hours
                                    </span>

                                    <span class="summary-value" style="color:#1684a5;">
                                        {{ number_format($driverCalendarAverageHours, 2) }}
                                    </span>

                                    <span class="summary-caption">
                                        Average per day with recorded hours
                                    </span>

                                </div>

                            </div>


                            {{-- Absent --}}
                            <div class="col-xl-3 col-md-4 col-6 mb-3">

                                <div class="attendance-summary-card summary-card-absent">

                                    <span class="summary-icon">
                                        <i class="fa fa-times-circle"></i>
                                    </span>

                                    <span class="summary-label">
                                        Absent Days
                                    </span>

                                    <span class="summary-value text-danger">
                                        {{ $driverCalendarAbsentCount }}
                                    </span>

                                    <span class="summary-caption">
                                        Recorded absences
                                    </span>

                                </div>

                            </div>


                            {{-- Exceptions --}}
                            <div class="col-xl-3 col-md-4 col-6 mb-3">

                                <div class="attendance-summary-card summary-card-exception">

                                    <span class="summary-icon">
                                        <i class="fa fa-exclamation-triangle"></i>
                                    </span>

                                    <span class="summary-label">
                                        Exceptions
                                    </span>

                                    <span class="summary-value" style="color:#a66b13;">
                                        {{ $driverCalendarExceptionCount }}
                                    </span>

                                    <span class="summary-caption">
                                        Half day, late, early exit or punch issue
                                    </span>

                                </div>

                            </div>


                            {{-- Leave --}}
                            <div class="col-xl-3 col-md-4 col-6 mb-3">

                                <div class="attendance-summary-card summary-card-leave">

                                    <span class="summary-icon">
                                        <i class="fa fa-calendar"></i>
                                    </span>

                                    <span class="summary-label">
                                        Leave Days
                                    </span>

                                    <span class="summary-value" style="color:#5676d8;">
                                        {{ $driverCalendarLeaveCount }}
                                    </span>

                                    <span class="summary-caption">
                                        Recorded leave entries
                                    </span>

                                </div>

                            </div>


                            {{-- Not Marked --}}
                            <div class="col-xl-3 col-md-4 col-6 mb-3">

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
                                        No record on elapsed calendar dates
                                    </span>

                                </div>

                            </div>


                            {{-- Marked Records --}}
                            <div class="col-xl-3 col-md-4 col-6 mb-3">

                                <div class="attendance-summary-card summary-card-rate">

                                    <span class="summary-icon">
                                        <i class="fa fa-calendar-check-o"></i>
                                    </span>

                                    <span class="summary-label">
                                        Marked Records
                                    </span>

                                    <span class="summary-value" style="color:#16834a;">
                                        {{ $driverCalendarMarkedCount }}
                                    </span>

                                    <span class="summary-caption">
                                        Days with an attendance entry
                                    </span>

                                </div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- PRESENT SHARE INDICATOR --}}
                        {{-- ================================================= --}}
                        <div class="attendance-progress-panel mb-4">

                            <div class="d-flex justify-content-between align-items-center mb-2">

                                <div>

                                    <strong class="progress-label">
                                        Present Share of Marked Records
                                    </strong>

                                    <small class="text-muted d-block mt-1">
                                        Present records divided by all marked records.
                                    </small>

                                </div>

                                <span class="progress-value">
                                    {{ $driverCalendarPresentShare }}%
                                </span>

                            </div>

                            <div
                                class="progress"
                                role="progressbar"
                                aria-valuenow="{{ $driverCalendarPresentShare }}"
                                aria-valuemin="0"
                                aria-valuemax="100"
                                aria-label="Present share of marked records"
                            >

                                <div
                                    class="progress-bar"
                                    style="width: {{ $driverCalendarPresentShare }}%;"
                                ></div>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- ATTENDANCE CALENDAR LEGEND --}}
                        {{-- ================================================= --}}
                        <div class="attendance-calendar-legend mb-3">

                            <span class="badge badge-success">
                                <i class="fa fa-check-circle mr-1"></i>
                                Present
                            </span>

                            <span class="badge badge-danger">
                                <i class="fa fa-times-circle mr-1"></i>
                                Absent
                            </span>

                            <span class="badge badge-warning">
                                <i class="fa fa-exclamation-circle mr-1"></i>
                                Half Day / Exception
                            </span>

                            <span class="badge badge-primary">
                                <i class="fa fa-calendar mr-1"></i>
                                Leave
                            </span>

                            <span class="badge badge-info">
                                <i class="fa fa-calendar-o mr-1"></i>
                                Holiday / Weekly Off
                            </span>

                            <span class="badge badge-secondary">
                                <i class="fa fa-question-circle mr-1"></i>
                                Not Marked
                            </span>

                            <span class="badge badge-light border">
                                Upcoming
                            </span>

                        </div>


                        {{-- ================================================= --}}
                        {{-- DRIVER CALENDAR GRID --}}
                        {{-- ================================================= --}}
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
                                        $calendarDayKey <=
                                        $attendanceCalendarToday->toDateString();

                                    if ($dayAttendance) {
                                        $driverDayMeta = $getAttendanceStatusMeta(
                                            $dayAttendance->status
                                        );

                                        $driverDayCategory = $driverDayMeta['category'];
                                        $driverDayCardClass = $driverDayMeta['card'];
                                        $driverDayStatusLabel = $driverDayMeta['label'];
                                        $driverDayBadgeClass = $driverDayMeta['badge'];
                                    } else {
                                        $driverDayCategory = $isCalendarDayDue
                                            ? 'not_marked'
                                            : 'upcoming';

                                        $driverDayCardClass = $isCalendarDayDue
                                            ? 'not-marked'
                                            : 'upcoming';

                                        $driverDayStatusLabel = $isCalendarDayDue
                                            ? 'Not Marked'
                                            : 'Upcoming';

                                        $driverDayBadgeClass = $isCalendarDayDue
                                            ? 'secondary'
                                            : 'light';
                                    }

                                    $driverModalId = 'driverAttendanceDayModal' .
                                        $calendarDay->format('Ymd');
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

                                    <button
                                        type="button"
                                        class="attendance-day-card attendance-day-card--{{ $driverDayCardClass }} {{ $calendarDay->isToday() ? 'is-today' : '' }}"
                                        data-toggle="modal"
                                        data-target="#{{ $driverModalId }}"
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


                                        <div class="attendance-day-details">

                                            <span class="badge badge-{{ $driverDayBadgeClass }}">

                                                {{ $driverDayStatusLabel }}

                                            </span>


                                            @if($dayAttendance)

                                                @if($dayAttendance->in_time || $dayAttendance->out_time)

                                                    <span class="attendance-day-time">

                                                        <i class="fa fa-clock-o mr-1"></i>

                                                        {{ $dayAttendance->in_time
                                                            ? \Carbon\Carbon::parse($dayAttendance->in_time)->format('h:i A')
                                                            : '--' }}

                                                        -

                                                        {{ $dayAttendance->out_time
                                                            ? \Carbon\Carbon::parse($dayAttendance->out_time)->format('h:i A')
                                                            : '--' }}

                                                    </span>

                                                @endif


                                                @if($dayAttendance->total_hours !== null)

                                                    <span class="attendance-day-hours">

                                                        <i class="fa fa-hourglass-half"></i>

                                                        {{ number_format((float) $dayAttendance->total_hours, 2) }}
                                                        hrs

                                                    </span>

                                                @endif


                                                @if($dayAttendance->workingSheet && $dayAttendance->workingSheet->sheet_no)

                                                    <span class="attendance-day-sheet">

                                                        <i class="fa fa-file-text-o mr-1"></i>

                                                        {{ $dayAttendance->workingSheet->sheet_no }}

                                                    </span>

                                                @endif

                                            @elseif($isCalendarDayDue)

                                                <span class="attendance-day-time">
                                                    No attendance record
                                                </span>

                                            @else

                                                <span class="attendance-day-time">
                                                    Future date
                                                </span>

                                            @endif


                                            <span class="attendance-day-click-hint">
                                                <i class="fa fa-eye mr-1"></i>
                                                View Details
                                            </span>

                                        </div>

                                    </button>


                                    {{-- ================================================= --}}
                                    {{-- DRIVER DAY DETAILS MODAL --}}
                                    {{-- ================================================= --}}
                                    <div
                                        class="modal fade attendance-day-modal"
                                        id="{{ $driverModalId }}"
                                        tabindex="-1"
                                        role="dialog"
                                        aria-labelledby="{{ $driverModalId }}Label"
                                        aria-hidden="true"
                                    >

                                        <div
                                            class="modal-dialog modal-md modal-dialog-centered"
                                            role="document"
                                        >

                                            <div class="modal-content">

                                                <div class="modal-header">

                                                    <div>

                                                        <h5
                                                            class="modal-title"
                                                            id="{{ $driverModalId }}Label"
                                                        >

                                                            <i class="fa fa-calendar-check-o text-primary mr-1"></i>

                                                            My Attendance Details

                                                        </h5>

                                                        <small class="text-muted">

                                                            {{ $calendarDay->format('l, d F Y') }}

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

                                                    @if($dayAttendance)

                                                        <div class="mb-3">

                                                            <small class="text-muted d-block mb-2">
                                                                Attendance Status
                                                            </small>

                                                            <span class="badge badge-{{ $driverDayBadgeClass }} px-3 py-2">

                                                                <i class="fa {{ $driverDayMeta['icon'] }} mr-1"></i>

                                                                {{ $driverDayStatusLabel }}

                                                            </span>

                                                        </div>


                                                        <div class="row">

                                                            <div class="col-6 mb-3">

                                                                <div class="attendance-detail-box">

                                                                    <small>
                                                                        In Time
                                                                    </small>

                                                                    <strong>

                                                                        @if($dayAttendance->in_time)

                                                                            {{ \Carbon\Carbon::parse(
                                                                                $dayAttendance->in_time
                                                                            )->format('h:i A') }}

                                                                        @else

                                                                            -

                                                                        @endif

                                                                    </strong>

                                                                </div>

                                                            </div>


                                                            <div class="col-6 mb-3">

                                                                <div class="attendance-detail-box">

                                                                    <small>
                                                                        Out Time
                                                                    </small>

                                                                    <strong>

                                                                        @if($dayAttendance->out_time)

                                                                            {{ \Carbon\Carbon::parse(
                                                                                $dayAttendance->out_time
                                                                            )->format('h:i A') }}

                                                                        @else

                                                                            -

                                                                        @endif

                                                                    </strong>

                                                                </div>

                                                            </div>


                                                            <div class="col-6 mb-3">

                                                                <div class="attendance-detail-box">

                                                                    <small>
                                                                        Total Working Hours
                                                                    </small>

                                                                    <strong>

                                                                        @if($dayAttendance->total_hours !== null)

                                                                            {{ number_format(
                                                                                (float) $dayAttendance->total_hours,
                                                                                2
                                                                            ) }}
                                                                            hrs

                                                                        @else

                                                                            -

                                                                        @endif

                                                                    </strong>

                                                                </div>

                                                            </div>


                                                            <div class="col-6 mb-3">

                                                                <div class="attendance-detail-box">

                                                                    <small>
                                                                        Attendance Source
                                                                    </small>

                                                                    <strong style="font-size:12px;">

                                                                        {{ ucfirst(
                                                                            str_replace(
                                                                                '_',
                                                                                ' ',
                                                                                $dayAttendance->source ?? 'Unknown'
                                                                            )
                                                                        ) }}

                                                                    </strong>

                                                                </div>

                                                            </div>

                                                        </div>


                                                        @if($dayAttendance->workingSheet && $dayAttendance->workingSheet->sheet_no)

                                                            <div class="attendance-detail-box mb-3">

                                                                <small>
                                                                    Working Sheet Reference
                                                                </small>

                                                                <strong>
                                                                    {{ $dayAttendance->workingSheet->sheet_no }}
                                                                </strong>

                                                            </div>

                                                        @endif


                                                    @else

                                                        @if($isCalendarDayDue)

                                                            <div class="alert alert-secondary mb-0">

                                                                <i class="fa fa-info-circle mr-1"></i>

                                                                <strong>Not Marked</strong>

                                                                <p class="mb-0 mt-1">

                                                                    No attendance record exists for this date.
                                                                    This date is not automatically treated as Absent.

                                                                </p>

                                                            </div>

                                                        @else

                                                            <div class="alert alert-info mb-0">

                                                                <i class="fa fa-calendar mr-1"></i>

                                                                <strong>Upcoming Date</strong>

                                                                <p class="mb-0 mt-1">

                                                                    Attendance for this date has not yet become due.

                                                                </p>

                                                            </div>

                                                        @endif

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


                        {{-- ================================================= --}}
                        {{-- CALENDAR FOOTNOTE --}}
                        {{-- ================================================= --}}
                        <div class="text-muted mt-3">

                            <small>

                                <i class="fa fa-info-circle mr-1"></i>

                                Not Marked means no attendance record exists for an elapsed calendar date.
                                It is not an Absent status. Future dates are shown as Upcoming.

                                The present-share percentage is calculated only from marked records.
                                It is not a payroll or eligible-working-day attendance percentage.

                            </small>

                        </div>

                    @endif

                </div>

            </div>

        @endif

                {{-- ========================================================= --}}
        {{-- MANAGEMENT ALL DRIVERS CALENDAR --}}
        {{-- ========================================================= --}}
        @if(!$isDriver && $managementCalendarView === 'calendar')

            <div class="card-box mb-30">

                <div class="pd-20">

                    {{-- Management Calendar Header --}}
                    <div class="driver-calendar-toolbar d-flex flex-wrap justify-content-between align-items-center mb-20">

                        <div class="mb-3 mb-md-0">

                            <span class="driver-section-eyebrow">
                                MANAGEMENT ATTENDANCE OVERVIEW
                            </span>

                            <h4 class="text-blue h4 mb-1">
                                All Drivers Monthly Calendar
                            </h4>

                            <p class="text-muted mb-0">
                                Review daily attendance of all drivers in one calendar.
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

                        <input
                            type="hidden"
                            name="view"
                            value="calendar"
                        >

                        <div class="row align-items-end">

                            <div class="col-md-4 col-sm-6 mb-3 mb-md-0">

                                <label for="management-calendar-month">
                                    Select Month
                                </label>

                                <input
                                    type="month"
                                    id="management-calendar-month"
                                    name="month"
                                    value="{{ $attendanceCalendarMonth->format('Y-m') }}"
                                    class="form-control"
                                >

                            </div>


                            <div class="col-md-5 col-sm-6 mb-3 mb-md-0">

                                <label for="management-calendar-driver">
                                    Filter by Driver
                                </label>

                                <select
                                    name="calendar_driver_id"
                                    id="management-calendar-driver"
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
                                    title="Reset calendar filters"
                                >
                                    <i class="fa fa-refresh"></i>
                                </a>

                            </div>

                        </div>

                    </form>


                    {{-- ================================================= --}}
                    {{-- MANAGEMENT MONTHLY SUMMARY --}}
                    {{-- ================================================= --}}
                    <div class="row mb-2">

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
                                    Present driver-days
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

                            <div class="attendance-summary-card summary-card-exception">

                                <span class="summary-icon">
                                    <i class="fa fa-exclamation-triangle"></i>
                                </span>

                                <span class="summary-label">
                                    Exceptions
                                </span>

                                <span class="summary-value" style="color:#a66b13;">
                                    {{ $managementCalendarMonthlySummary['exception'] }}
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
                                    Missing elapsed driver-day records
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- Calendar Context --}}
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

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
                                <i class="fa fa-user mr-1"></i>
                                Single Driver Filter Applied
                            </span>

                        @endif

                    </div>


                    {{-- Calendar Legend --}}
                    <div class="attendance-calendar-legend mb-3">

                        <span class="badge badge-success">
                            Present
                        </span>

                        <span class="badge badge-danger">
                            Absent
                        </span>

                        <span class="badge badge-warning">
                            Half Day / Exceptions
                        </span>

                        <span class="badge badge-primary">
                            Leave
                        </span>

                        <span class="badge badge-info">
                            Holiday / Weekly Off
                        </span>

                        <span class="badge badge-secondary">
                            Not Marked
                        </span>

                        <span class="badge badge-light border">
                            Upcoming
                        </span>

                        <small class="text-muted mb-2 ml-md-2">
                            Click a date to open driver-wise details.
                        </small>

                    </div>


                    {{-- ================================================= --}}
                    {{-- MANAGEMENT CALENDAR GRID --}}
                    {{-- ================================================= --}}
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

                                $managementDayIsDue = $managementDayData
                                    ? $managementDayData['is_due']
                                    : false;

                                $managementDayCardClass = 'upcoming';

                                if ($managementDayStats) {
                                    if ($managementDayStats['absent'] > 0) {
                                        $managementDayCardClass = 'danger';
                                    } elseif ($managementDayStats['not_marked'] > 0) {
                                        $managementDayCardClass = 'not-marked';
                                    } elseif ($managementDayStats['exception'] > 0) {
                                        $managementDayCardClass = 'warning';
                                    } elseif ($managementDayStats['leave'] > 0) {
                                        $managementDayCardClass = 'primary';
                                    } elseif ($managementDayStats['present'] > 0) {
                                        $managementDayCardClass = 'success';
                                    } elseif ($managementDayStats['other'] > 0) {
                                        $managementDayCardClass = 'info';
                                    }
                                }

                                $managementModalId = 'managementAttendanceDayModal' .
                                    $calendarDay->format('Ymd');
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
                                    class="attendance-day-card attendance-day-card--{{ $managementDayCardClass }} {{ $calendarDay->isToday() ? 'is-today' : '' }}"
                                    data-toggle="modal"
                                    data-target="#{{ $managementModalId }}"
                                    aria-label="View driver attendance for {{ $calendarDay->format('d F Y') }}"
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
                                                    P: {{ $managementDayStats['present'] }}
                                                </span>

                                                <span class="count-absent">
                                                    A: {{ $managementDayStats['absent'] }}
                                                </span>

                                                <span class="count-exception">
                                                    E: {{ $managementDayStats['exception'] }}
                                                </span>

                                                <span class="count-leave">
                                                    L: {{ $managementDayStats['leave'] }}
                                                </span>

                                                <span class="count-unmarked">
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

                                        </div>

                                    @elseif($managementCalendarDrivers->isEmpty())

                                        <small class="text-muted">
                                            No drivers available
                                        </small>

                                    @elseif($managementDayIsDue)

                                        <span class="badge badge-secondary">
                                            Not Marked
                                        </span>

                                    @else

                                        <span class="badge badge-light border">
                                            Upcoming
                                        </span>

                                    @endif


                                    <span class="attendance-day-click-hint">
                                        <i class="fa fa-eye mr-1"></i>
                                        View Driver Details
                                    </span>

                                </button>


                                {{-- ================================================= --}}
                                {{-- MANAGEMENT DATE-WISE DETAILS MODAL --}}
                                {{-- ================================================= --}}
                                <div
                                    class="modal fade attendance-day-modal"
                                    id="{{ $managementModalId }}"
                                    tabindex="-1"
                                    role="dialog"
                                    aria-labelledby="{{ $managementModalId }}Label"
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
                                                        id="{{ $managementModalId }}Label"
                                                    >

                                                        <i class="fa fa-calendar-check-o text-primary mr-1"></i>

                                                        Driver Attendance Details

                                                    </h5>

                                                    <small class="text-muted">

                                                        {{ $calendarDay->format('l, d F Y') }}

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

                                                        $modalDayStats = $modalDayData
                                                            ? $modalDayData['stats']
                                                            : [];
                                                    @endphp


                                                    {{-- Modal Summary --}}
                                                    <div class="row mb-3">

                                                        <div class="col-md col-6 mb-2">

                                                            <div class="attendance-detail-box">

                                                                <small>
                                                                    Present
                                                                </small>

                                                                <strong class="text-success">
                                                                    {{ $modalDayStats['present'] ?? 0 }}
                                                                </strong>

                                                            </div>

                                                        </div>


                                                        <div class="col-md col-6 mb-2">

                                                            <div class="attendance-detail-box">

                                                                <small>
                                                                    Absent
                                                                </small>

                                                                <strong class="text-danger">
                                                                    {{ $modalDayStats['absent'] ?? 0 }}
                                                                </strong>

                                                            </div>

                                                        </div>


                                                        <div class="col-md col-6 mb-2">

                                                            <div class="attendance-detail-box">

                                                                <small>
                                                                    Exceptions
                                                                </small>

                                                                <strong style="color:#a66b13;">
                                                                    {{ $modalDayStats['exception'] ?? 0 }}
                                                                </strong>

                                                            </div>

                                                        </div>


                                                        <div class="col-md col-6 mb-2">

                                                            <div class="attendance-detail-box">

                                                                <small>
                                                                    Leave
                                                                </small>

                                                                <strong style="color:#5676d8;">
                                                                    {{ $modalDayStats['leave'] ?? 0 }}
                                                                </strong>

                                                            </div>

                                                        </div>


                                                        <div class="col-md col-6 mb-2">

                                                            <div class="attendance-detail-box">

                                                                <small>
                                                                    Not Marked
                                                                </small>

                                                                <strong class="text-secondary">
                                                                    {{ $modalDayStats['not_marked'] ?? 0 }}
                                                                </strong>

                                                            </div>

                                                        </div>

                                                    </div>


                                                    {{-- Driver-wise Details --}}
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

                                                                        if ($modalAttendance) {
                                                                            $modalMeta = $getAttendanceStatusMeta(
                                                                                $modalAttendance->status
                                                                            );

                                                                            $modalStatusLabel = $modalMeta['label'];
                                                                            $modalStatusClass = $modalMeta['badge'];
                                                                        } else {
                                                                            $modalStatusLabel = $modalCategory === 'upcoming'
                                                                                ? 'Upcoming'
                                                                                : 'Not Marked';

                                                                            $modalStatusClass = $modalCategory === 'upcoming'
                                                                                ? 'light'
                                                                                : 'secondary';
                                                                        }
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

                                                                                {{ \Carbon\Carbon::parse(
                                                                                    $modalAttendance->in_time
                                                                                )->format('h:i A') }}

                                                                            @else

                                                                                -

                                                                            @endif

                                                                        </td>


                                                                        <td>

                                                                            @if($modalAttendance && $modalAttendance->out_time)

                                                                                {{ \Carbon\Carbon::parse(
                                                                                    $modalAttendance->out_time
                                                                                )->format('h:i A') }}

                                                                            @else

                                                                                -

                                                                            @endif

                                                                        </td>


                                                                        <td>

                                                                            @if($modalAttendance && $modalAttendance->total_hours !== null)

                                                                                {{ number_format(
                                                                                    (float) $modalAttendance->total_hours,
                                                                                    2
                                                                                ) }}
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

                                                                                {{ ucfirst(
                                                                                    str_replace(
                                                                                        '_',
                                                                                        ' ',
                                                                                        $modalAttendance->source ?? 'Unknown'
                                                                                    )
                                                                                ) }}

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

                            P = Present, A = Absent, E = Exceptions, L = Leave,
                            NM = Not Marked. Not Marked is not treated as Absent.
                            Future dates are shown as Upcoming.

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
                 * Keep the existing paginator and load its relationships.
                 * Calendar data is loaded independently above.
                 */
                $attendances->getCollection()->loadMissing([
                    'driver',
                    'createdBy',
                    'workingSheet.dutySlip.dutyAssignment.travelRequest',
                ]);
            @endphp


            <div class="card-box mb-30">

                {{-- Table Header --}}
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

                                        $attendanceMeta = $getAttendanceStatusMeta(
                                            $attendance->status
                                        );

                                        $attendanceSource = $attendance->source;

                                        $attendanceSourceClass =
                                            $attendanceSource === 'working_sheet'
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

                                            <span class="badge badge-{{ $attendanceMeta['badge'] }} badge-pill px-3 py-2">

                                                <i class="fa {{ $attendanceMeta['icon'] }} mr-1"></i>

                                                {{ $attendanceMeta['label'] }}

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
                                                    No attendance records match the selected filters.
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
                            form.submit();
                        }

                    });

                });

            });

        });
    </script>

    <script src="{{ asset('backend/assets/datatable/js/datatable-init.js') }}"></script>

@endif

@endpush
