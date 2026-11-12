@extends('backend.layouts.master')

@section('title')

    @if($isDriver)

        My Attendance

    @else

        Driver Attendance

    @endif

@endsection


@push('styles')

@if(!$isDriver)
<link
    rel="stylesheet"
    href="{{ asset('backend/assets/datatable/css/dataTables-responsive.css') }}"
>
@endif

<style>

    .table td,
    .table th {
        vertical-align: middle;
    }

    .driver-code {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .attendance-time {
        font-weight: 600;
    }

    .amount-text {
        font-weight: 600;
    }

    .filter-card {
        background: #f8f9fa;
        border-radius: 6px;
    }

    .attendance-calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 8px;
    }

    .attendance-calendar-weekday {
        padding: 10px 6px;
        text-align: center;
        font-weight: 700;
        color: #495057;
        background: #f1f3f5;
        border-radius: 5px;
    }

    .attendance-day-card {
        min-height: 145px;
        padding: 10px;
        border: 1px solid #e5e7eb;
        border-radius: 7px;
        background: #fff;
        overflow-wrap: anywhere;
    }

    .attendance-day-card.is-other-month {
        background: #f8f9fa;
        color: #adb5bd;
        border-style: dashed;
    }

    .attendance-day-number {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .attendance-day-card.is-today {
        border-color: #007bff;
        box-shadow: 0 0 0 1px rgba(0, 123, 255, .12);
    }

    .attendance-day-details {
        font-size: 12px;
        line-height: 1.45;
    }

    .attendance-calendar-summary {
        height: 100%;
        border: 1px solid #e9ecef;
        border-radius: 7px;
        padding: 14px;
        background: #fff;
    }

    .attendance-calendar-summary .summary-value {
        display: block;
        font-size: 22px;
        font-weight: 700;
        line-height: 1.2;
    }

    @media (max-width: 767.98px) {
        .attendance-calendar-grid {
            gap: 4px;
        }

        .attendance-day-card {
            min-height: 112px;
            padding: 6px;
        }

        .attendance-calendar-weekday {
            padding: 7px 2px;
            font-size: 11px;
        }

        .attendance-day-details {
            font-size: 10px;
        }

        .attendance-day-card .badge {
            white-space: normal;
            font-size: 9px;
        }
    }

    @media (max-width: 420px) {
        .attendance-day-card {
            min-height: 96px;
            padding: 4px;
        }

        .attendance-day-number {
            margin-bottom: 5px;
            font-size: 12px;
        }
    }


    /* ================================================================
       Attendance page UI/UX refresh
       ================================================================ */
    .driver-attendance-page {
        --attendance-primary: #2457a7;
        --attendance-border: #e8edf5;
        --attendance-muted: #718096;
    }

    .driver-attendance-page .page-header {
        margin-bottom: 22px;
        padding: 22px 24px;
        background: linear-gradient(115deg, #ffffff 0%, #f5f8ff 100%);
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
        transition: transform .16s ease, box-shadow .16s ease, background-color .16s ease;
    }

    .driver-attendance-page .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(31, 55, 92, .12);
    }

    .driver-attendance-page .attendance-filter-form {
        padding: 22px;
        border: 1px solid var(--attendance-border) !important;
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
        border-color: #dce3ee;
        border-radius: 7px;
        background-color: #fff;
        box-shadow: none;
    }

    .driver-attendance-page .attendance-filter-form .form-control:focus {
        border-color: #7da5e8;
        box-shadow: 0 0 0 3px rgba(36, 87, 167, .09);
    }

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

    .driver-attendance-page .calendar-today-btn {
        margin-left: 8px !important;
    }

    .driver-attendance-page .attendance-calendar-summary {
        position: relative;
        min-height: 142px;
        padding: 17px 18px;
        border: 1px solid var(--attendance-border);
        border-radius: 11px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(26, 47, 84, .035);
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .driver-attendance-page .attendance-calendar-summary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(26, 47, 84, .08);
    }

    .driver-attendance-page .attendance-calendar-summary .summary-icon {
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

    .driver-attendance-page .attendance-calendar-summary .summary-value {
        margin-top: 8px;
        font-size: 27px;
        font-weight: 750;
        line-height: 1.1;
    }

    .driver-attendance-page .attendance-calendar-summary > small:first-of-type {
        padding-top: 4px;
        font-size: 12px;
        font-weight: 650;
    }

    .driver-attendance-page .attendance-calendar-summary .summary-caption {
        display: block;
        margin-top: 5px;
        color: #98a2b3;
        font-size: 10px;
    }

    .driver-attendance-page .summary-card-present .summary-icon {
        color: #16834a;
        background: #eaf8f0;
    }

    .driver-attendance-page .summary-card-absent .summary-icon {
        color: #c43c48;
        background: #fff0f1;
    }

    .driver-attendance-page .summary-card-unmarked .summary-icon {
        color: #667085;
        background: #f0f2f5;
    }

    .driver-attendance-page .attendance-calendar-legend {
        gap: 5px;
        padding: 12px 14px 5px;
        border: 1px solid #edf1f7;
        border-radius: 9px;
        background: #fbfcfe;
    }

    .driver-attendance-page .attendance-calendar-legend .badge {
        padding: 7px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 650;
    }

    .driver-attendance-page .attendance-calendar-grid {
        gap: 9px;
    }

    .driver-attendance-page .attendance-calendar-weekday {
        padding: 11px 6px;
        border: 1px solid #edf1f7;
        border-radius: 8px;
        background: #f6f8fc;
        color: #64748b;
        font-size: 11px;
        letter-spacing: .45px;
        text-transform: uppercase;
    }

    .driver-attendance-page .attendance-day-card {
        position: relative;
        min-height: 136px;
        padding: 11px;
        border: 1px solid #e7ecf3;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 2px 7px rgba(26, 47, 84, .025);
        transition: border-color .16s ease, box-shadow .16s ease, transform .16s ease;
    }

    .driver-attendance-page .attendance-day-card:hover {
        z-index: 1;
        transform: translateY(-2px);
        border-color: #bdcce2;
        box-shadow: 0 7px 16px rgba(26, 47, 84, .075);
    }

    .driver-attendance-page .attendance-day-card.is-today {
        border: 2px solid #3978d4;
        background: #f7faff;
        box-shadow: 0 0 0 3px rgba(57, 120, 212, .08);
    }

    .driver-attendance-page .attendance-day-number {
        margin-bottom: 11px;
        color: #344054;
        font-size: 13px;
    }

    .driver-attendance-page .attendance-day-number small {
        padding: 3px 6px;
        border-radius: 20px;
        background: #eaf2ff;
        font-size: 9px;
        font-weight: 700;
    }

    .driver-attendance-page .attendance-day-details {
        color: #475467;
        font-size: 11px;
        line-height: 1.55;
    }

    .driver-attendance-page .attendance-day-details .badge {
        padding: 6px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        white-space: normal;
        text-align: left;
    }

    .driver-attendance-page .attendance-day-card--success {
        border-top: 3px solid #28a36a;
    }

    .driver-attendance-page .attendance-day-card--danger {
        border-top: 3px solid #dc5661;
    }

    .driver-attendance-page .attendance-day-card--warning {
        border-top: 3px solid #e6ad35;
    }

    .driver-attendance-page .attendance-day-card--primary {
        border-top: 3px solid #5676d8;
    }

    .driver-attendance-page .attendance-day-card--info {
        border-top: 3px solid #36a5c8;
    }

    .driver-attendance-page .attendance-day-card--secondary,
    .driver-attendance-page .attendance-day-card--not-marked {
        border-top: 3px solid #b8c0cc;
    }

    .driver-attendance-page .attendance-day-card--not-marked {
        background: #fafbfc;
    }

    .driver-attendance-page .attendance-day-card.is-other-month {
        min-height: 136px;
        background: #f8f9fb;
        box-shadow: none;
    }

    .driver-attendance-page .attendance-table-heading {
        gap: 12px;
    }

    .driver-attendance-page .attendance-table-wrap {
        border-top: 1px solid #edf1f7;
    }

    .driver-attendance-page .attendance-management-table {
        margin-bottom: 0;
    }

    .driver-attendance-page .attendance-management-table thead th {
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
    }

    .driver-attendance-page .attendance-management-table tbody td {
        padding: 13px 12px;
        border-top: 1px solid #eef1f5;
        color: #344054;
        font-size: 12px;
        vertical-align: middle;
    }

    .driver-attendance-page .attendance-management-table tbody tr:hover {
        background: #f9fbff;
    }

    .driver-attendance-page .attendance-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
    }

    .driver-attendance-page .pagination {
        flex-wrap: wrap;
        gap: 4px;
    }

    .driver-attendance-page .alert {
        border-radius: 9px;
        line-height: 1.55;
    }

    @media (max-width: 991.98px) {
        .driver-attendance-page .attendance-day-card,
        .driver-attendance-page .attendance-day-card.is-other-month {
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

        .driver-attendance-page .driver-calendar-navigation {
            width: 100%;
            justify-content: center;
        }

        .driver-attendance-page .attendance-calendar-grid {
            gap: 5px;
        }

        .driver-attendance-page .attendance-calendar-weekday {
            padding: 8px 2px;
            font-size: 9px;
            letter-spacing: 0;
        }

        .driver-attendance-page .attendance-day-card,
        .driver-attendance-page .attendance-day-card.is-other-month {
            min-height: 105px;
            padding: 6px;
            border-radius: 7px;
        }

        .driver-attendance-page .attendance-day-number {
            margin-bottom: 7px;
            font-size: 11px;
        }

        .driver-attendance-page .attendance-day-details {
            font-size: 9px;
        }

        .driver-attendance-page .attendance-day-details .badge {
            padding: 4px 5px;
            font-size: 8px;
        }

        .driver-attendance-page .attendance-calendar-summary {
            min-height: 126px;
            padding: 13px;
        }

        .driver-attendance-page .attendance-calendar-summary .summary-value {
            font-size: 23px;
        }

        .driver-attendance-page .attendance-calendar-summary .summary-icon {
            width: 29px;
            height: 29px;
            font-size: 12px;
        }

        .driver-attendance-page .attendance-filter-form {
            padding: 15px;
        }
    }

    @media (max-width: 420px) {
        .driver-attendance-page .attendance-calendar-grid {
            gap: 3px;
        }

        .driver-attendance-page .attendance-day-card,
        .driver-attendance-page .attendance-day-card.is-other-month {
            min-height: 92px;
            padding: 4px;
        }

        .driver-attendance-page .attendance-day-number {
            margin-bottom: 5px;
            font-size: 10px;
        }

        .driver-attendance-page .attendance-day-number small {
            padding: 2px 4px;
            font-size: 8px;
        }

        .driver-attendance-page .attendance-calendar-legend {
            padding: 9px 9px 3px;
        }

        .driver-attendance-page .attendance-calendar-legend .badge {
            padding: 6px 8px;
            font-size: 9px;
        }
    }

</style>

@endpush


@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Driver Monthly Calendar Data
    |--------------------------------------------------------------------------
    |
    | The index controller intentionally keeps its existing 20-row paginator
    | for management users. A monthly calendar must not infer missing days
    | from that partial page, so the Driver view loads only the authenticated
    | Driver's records for the selected month.
    |
    | This query is read-only and is strictly scoped to the logged-in user's
    | mapped Driver. No attendance is created or changed here.
    |
    */

    $driverCalendarMonth = \Carbon\Carbon::now()->startOfMonth();
    $requestedCalendarMonth = request()->query('month');

    if (
        is_string($requestedCalendarMonth) &&
        preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])$/', $requestedCalendarMonth)
    ) {
        $parsedCalendarMonth = \Carbon\Carbon::createFromFormat(
            '!Y-m',
            $requestedCalendarMonth
        );

        if ($parsedCalendarMonth instanceof \Carbon\Carbon) {
            $driverCalendarMonth = $parsedCalendarMonth->startOfMonth();
        }
    }

    $driverCalendarDriver = null;
    $driverCalendarAttendances = collect();

    if ($isDriver) {
        $calendarUser = auth()->user();
        $driverCalendarDriver = $calendarUser ? $calendarUser->driver : null;

        if ($driverCalendarDriver) {
            $driverCalendarAttendances = \App\Models\DriverAttendance::query()
                ->with('workingSheet')
                ->where('driver_id', $driverCalendarDriver->id)
                ->whereBetween('attendance_date', [
                    $driverCalendarMonth->copy()->startOfMonth()->toDateString(),
                    $driverCalendarMonth->copy()->endOfMonth()->toDateString(),
                ])
                ->get()
                ->keyBy(function ($attendance) {
                    return \Carbon\Carbon::parse(
                        $attendance->attendance_date
                    )->format('Y-m-d');
                });
        }
    }

    $driverCalendarStart = $driverCalendarMonth->copy()
        ->startOfMonth()
        ->startOfWeek(\Carbon\Carbon::MONDAY);

    $driverCalendarEnd = $driverCalendarMonth->copy()
        ->endOfMonth()
        ->endOfWeek(\Carbon\Carbon::SUNDAY);

    $driverCalendarDays = [];

    for (
        $calendarDate = $driverCalendarStart->copy();
        $calendarDate->lte($driverCalendarEnd);
        $calendarDate->addDay()
    ) {
        $driverCalendarDays[] = $calendarDate->copy();
    }

    $driverCalendarMarkedCount = $driverCalendarAttendances->count();
    $driverCalendarPresentCount = $driverCalendarAttendances->filter(function ($attendance) {
        return in_array($attendance->status, [
            'present',
            'present_late',
            'present_early_exit',
        ], true);
    })->count();

    $driverCalendarAbsentCount = $driverCalendarAttendances->where('status', 'absent')->count();

    $driverCalendarLeaveStatuses = [
        'leave',
        'maternity_leave',
        'sick_leave',
        'casual_leave',
        'paid_leave',
        'unpaid_leave',
        'restricted_holiday',
    ];

    $driverCalendarLeaveCount = $driverCalendarAttendances->filter(function ($attendance) use ($driverCalendarLeaveStatuses) {
        return in_array($attendance->status, $driverCalendarLeaveStatuses, true);
    })->count();

    $driverCalendarNotMarkedCount = max(
        0,
        $driverCalendarMonth->daysInMonth - $driverCalendarMarkedCount
    );

    $driverCalendarPreviousMonthUrl = route('driver-attendances.index', [
        'month' => $driverCalendarMonth->copy()->subMonthNoOverflow()->format('Y-m'),
    ]);

    $driverCalendarNextMonthUrl = route('driver-attendances.index', [
        'month' => $driverCalendarMonth->copy()->addMonthNoOverflow()->format('Y-m'),
    ]);

    $driverCalendarCurrentMonthUrl = route('driver-attendances.index', [
        'month' => now()->format('Y-m'),
    ]);
@endphp

<div class="pd-ltr-20 xs-pd-20-10 driver-attendance-page">

    <div class="min-height-200px">


        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}
        <div class="page-header">

            <div class="row">

                {{-- ================================================= --}}
                {{-- PAGE TITLE --}}
                {{-- ================================================= --}}

                <div class="col-md-6 col-sm-12">

                    <h4 class="text-blue">

                        @if($isDriver)

                            My Attendance

                        @else

                            Driver Attendance

                        @endif

                    </h4>

                    <p class="mb-0">

                        @if($isDriver)

                            View your attendance, working hours
                            and attendance history.

                        @else

                            Manage driver attendance, working hours
                            and attendance records.

                        @endif

                    </p>

                </div>


                {{-- ================================================= --}}
                {{-- ADD ATTENDANCE --}}
                {{-- ================================================= --}}

                @if(!$isDriver)

                    <div class="col-md-6 col-sm-12 text-right">

                        <a
                            href="{{ route('driver-attendances.create') }}"
                            class="btn btn-primary">

                            <i class="fa fa-plus"></i>

                            Add Driver Attendance

                        </a>

                    </div>

                @endif

            </div>

        </div>

        @if(!$isDriver)

        {{-- ========================================================= --}}
        {{-- FILTER CARD --}}
        {{-- ========================================================= --}}
        <div class="card-box mb-30">

            <div class="pd-20">

                <h4 class="text-blue h4 mb-20">

                    Attendance Filters

                </h4>


                <form
                    method="GET"
                    action="{{ route('driver-attendances.index') }}"
                    class="form-horizontal attendance-filter-form"
                    enctype="multipart/form-data"
                >

                    <div class="row">


                        {{-- ================================================= --}}
                        {{-- SEARCH --}}
                        {{-- ================================================= --}}

                        <div class="col-md-3 col-sm-6 mb-20">

                            <label>

                                Search

                            </label>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control"
                                placeholder="Driver name, code or mobile"
                            >

                        </div>



                        {{-- ================================================= --}}
                        {{-- DRIVER --}}
                        {{-- ================================================= --}}

                        @if(!$isDriver)

                            <div class="col-md-3 col-sm-6 mb-20">

                                <label>

                                    Driver

                                </label>

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
                                            {{ request('driver_id') == $driver->id ? 'selected' : '' }}
                                        >

                                            {{ $driver->first_name }} {{ $driver->last_name }}

                                            @if(!empty($driver->driver_code))

                                                -
                                                {{ $driver->driver_code }}

                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        @endif



                        {{-- ================================================= --}}
                        {{-- EXACT DATE --}}
                        {{-- ================================================= --}}

                        <div class="col-md-2 col-sm-6 mb-20">

                            <label>

                                Attendance Date

                            </label>

                            <input
                                type="date"
                                name="attendance_date"
                                value="{{ request('attendance_date') }}"
                                class="form-control"
                            >

                        </div>



                        {{-- ================================================= --}}
                        {{-- DATE FROM --}}
                        {{-- ================================================= --}}

                        <div class="col-md-2 col-sm-6 mb-20">

                            <label>

                                Date From

                            </label>

                            <input
                                type="date"
                                name="date_from"
                                value="{{ request('date_from') }}"
                                class="form-control"
                            >

                        </div>



                        {{-- ================================================= --}}
                        {{-- DATE TO --}}
                        {{-- ================================================= --}}

                        <div class="col-md-2 col-sm-6 mb-20">

                            <label>

                                Date To

                            </label>

                            <input
                                type="date"
                                name="date_to"
                                value="{{ request('date_to') }}"
                                class="form-control"
                            >

                        </div>



                        {{-- ================================================= --}}
                        {{-- STATUS --}}
                        {{-- ================================================= --}}

                        <div class="col-md-2 col-sm-6 mb-20">

                            <label>

                                Status

                            </label>

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
                                        {{ request('status') == $status ? 'selected' : '' }}
                                    >

                                        {{ ucfirst(str_replace('_', ' ', $status)) }}

                                    </option>

                                @endforeach

                            </select>

                        </div>



                        {{-- ================================================= --}}
                        {{-- SOURCE --}}
                        {{-- ================================================= --}}

                        <div class="col-md-2 col-sm-6 mb-20">

                            <label>

                                Source

                            </label>

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
                                        {{ request('source') == $source ? 'selected' : '' }}
                                    >

                                        {{ ucfirst(str_replace('_', ' ', $source)) }}

                                    </option>

                                @endforeach

                            </select>

                        </div>



                        {{-- ================================================= --}}
                        {{-- BUTTONS --}}
                        {{-- ================================================= --}}

                        <div class="col-md-3 col-sm-6 mb-20 d-flex align-items-end">

                            <button
                                type="submit"
                                class="btn btn-primary mr-2"
                            >

                                <i class="fa fa-search"></i>

                                Filter

                            </button>


                            <a
                                href="{{ route('driver-attendances.index') }}"
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

        @if($isDriver)

            {{-- ========================================================= --}}
            {{-- DRIVER MONTHLY ATTENDANCE CALENDAR --}}
            {{-- ========================================================= --}}
            <div class="card-box mb-30">

                <div class="pd-20">

                    <div class="driver-calendar-toolbar d-flex flex-wrap justify-content-between align-items-center mb-20">

                        <div class="mb-3 mb-md-0">
                            <span class="driver-section-eyebrow">ATTENDANCE OVERVIEW</span>
                            <h4 class="text-blue h4 mb-1">My Attendance Calendar</h4>
                            <p class="text-muted mb-0">Your monthly attendance, work hours and daily status at a glance.</p>
                        </div>

                        <div class="driver-calendar-navigation d-flex flex-wrap align-items-center">
                            <a href="{{ $driverCalendarPreviousMonthUrl }}" class="btn btn-outline-secondary btn-sm mr-2 mb-2 calendar-nav-btn" aria-label="Previous month" title="Previous month">
                                <i class="fa fa-chevron-left"></i>
                            </a>

                            <strong class="calendar-month-label mx-2 mb-2">{{ $driverCalendarMonth->format('F Y') }}</strong>

                            <a href="{{ $driverCalendarNextMonthUrl }}" class="btn btn-outline-secondary btn-sm ml-2 mb-2 calendar-nav-btn" aria-label="Next month" title="Next month">
                                <i class="fa fa-chevron-right"></i>
                            </a>

                            <a href="{{ $driverCalendarCurrentMonthUrl }}" class="btn btn-primary btn-sm ml-2 mb-2 calendar-today-btn">
                                Today
                            </a>
                        </div>

                    </div>

                    @if(!$driverCalendarDriver)

                        <div class="alert alert-warning mb-0" role="alert">
                            Your user account is not mapped with a Driver record. Please contact the administrator.
                        </div>

                    @else

                        <div class="row mb-3">

                            <div class="col-xl-3 col-md-6 col-6 mb-3">
                                <div class="attendance-calendar-summary summary-card-marked">
                                    <span class="summary-icon"><i class="fa fa-calendar-check-o"></i></span>
                                    <small class="text-muted d-block">Marked Records</small>
                                    <span class="summary-value text-primary">{{ $driverCalendarMarkedCount }}</span>
                                    <small class="summary-caption">Attendance entries</small>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 col-6 mb-3">
                                <div class="attendance-calendar-summary summary-card-present">
                                    <span class="summary-icon"><i class="fa fa-check-circle"></i></span>
                                    <small class="text-muted d-block">Present</small>
                                    <span class="summary-value text-success">{{ $driverCalendarPresentCount }}</span>
                                    <small class="summary-caption">Days present</small>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 col-6 mb-3">
                                <div class="attendance-calendar-summary summary-card-absent">
                                    <span class="summary-icon"><i class="fa fa-times-circle"></i></span>
                                    <small class="text-muted d-block">Absent</small>
                                    <span class="summary-value text-danger">{{ $driverCalendarAbsentCount }}</span>
                                    <small class="summary-caption">Recorded absences</small>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 col-6 mb-3">
                                <div class="attendance-calendar-summary summary-card-unmarked">
                                    <span class="summary-icon"><i class="fa fa-calendar-o"></i></span>
                                    <small class="text-muted d-block">Not Marked</small>
                                    <span class="summary-value text-secondary">{{ $driverCalendarNotMarkedCount }}</span>
                                    <small class="summary-caption">No record available</small>
                                </div>
                            </div>

                        </div>

                        <div class="attendance-calendar-legend d-flex flex-wrap align-items-center mb-3">
                            <span class="badge badge-success mr-2 mb-2">Present</span>
                            <span class="badge badge-danger mr-2 mb-2">Absent</span>
                            <span class="badge badge-warning mr-2 mb-2">Half Day / Late / Missing Punch</span>
                            <span class="badge badge-primary mr-2 mb-2">Leave</span>
                            <span class="badge badge-secondary mr-2 mb-2">Not Marked / Pending</span>
                        </div>

                        <div class="attendance-calendar-grid" role="group" aria-label="Monthly attendance calendar">

                            @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $weekday)
                                <div class="attendance-calendar-weekday">{{ $weekday }}</div>
                            @endforeach

                            @foreach($driverCalendarDays as $calendarDay)

                                @php
                                    $calendarDayKey = $calendarDay->format('Y-m-d');
                                    $isCalendarCurrentMonth = $calendarDay->format('Y-m') === $driverCalendarMonth->format('Y-m');
                                    $dayAttendance = $isCalendarCurrentMonth
                                        ? $driverCalendarAttendances->get($calendarDayKey)
                                        : null;

                                    $dayStatusClass = 'secondary';

                                    if ($dayAttendance) {
                                        $dayStatusClass = match ($dayAttendance->status) {
                                            'present', 'present_late', 'present_early_exit' => 'success',
                                            'absent' => 'danger',
                                            'half_day', 'late', 'early_exit', 'missing_punch', 'unpaid_leave' => 'warning',
                                            'leave', 'maternity_leave', 'sick_leave', 'casual_leave', 'paid_leave', 'comp_off', 'restricted_holiday' => 'primary',
                                            'holiday', 'weekly_off', 'on_duty', 'work_from_home' => 'info',
                                            default => 'secondary',
                                        };
                                    }
                                @endphp

                                @if(!$isCalendarCurrentMonth)

                                    <div class="attendance-day-card is-other-month" aria-hidden="true">
                                        <div class="attendance-day-number">
                                            <span>{{ $calendarDay->day }}</span>
                                        </div>
                                    </div>

                                @else

                                    <div class="attendance-day-card {{ $calendarDay->isToday() ? 'is-today' : '' }} {{ $dayAttendance ? 'attendance-day-card--' . $dayStatusClass : 'attendance-day-card--not-marked' }}">

                                        <div class="attendance-day-number">
                                            <span>{{ $calendarDay->day }}</span>

                                            @if($calendarDay->isToday())
                                                <small class="text-primary">Today</small>
                                            @endif
                                        </div>

                                        @if($dayAttendance)

                                            <div class="attendance-day-details">
                                                <span class="badge badge-{{ $dayStatusClass }} d-inline-block mb-2">
                                                    {{ ucfirst(str_replace('_', ' ', $dayAttendance->status ?? 'Unknown')) }}
                                                </span>

                                                @if($dayAttendance->in_time || $dayAttendance->out_time)
                                                    <div class="text-muted">
                                                        <i class="fa fa-clock-o"></i>
                                                        {{ $dayAttendance->in_time ? \Carbon\Carbon::parse($dayAttendance->in_time)->format('h:i A') : '--' }}
                                                        –
                                                        {{ $dayAttendance->out_time ? \Carbon\Carbon::parse($dayAttendance->out_time)->format('h:i A') : '--' }}
                                                    </div>
                                                @endif

                                                @if($dayAttendance->total_hours !== null)
                                                    <div class="text-muted mt-1">
                                                        {{ number_format((float) $dayAttendance->total_hours, 2) }} hrs
                                                    </div>
                                                @endif

                                                @if($dayAttendance->workingSheet && $dayAttendance->workingSheet->sheet_no)
                                                    <div class="text-muted mt-1">
                                                        <i class="fa fa-file-text-o"></i>
                                                        {{ $dayAttendance->workingSheet->sheet_no }}
                                                    </div>
                                                @endif

                                            </div>

                                        @else

                                            <span class="badge badge-secondary">Not Marked</span>

                                        @endif

                                    </div>

                                @endif

                            @endforeach

                        </div>

                        <div class="text-muted mt-3">
                            <small>
                                Not Marked means no attendance record exists for that date. It is not an Absent status.
                                Leave records: {{ $driverCalendarLeaveCount }}.
                            </small>
                        </div>

                    @endif

                </div>

            </div>

        @else

            {{-- Management users keep the existing paginated attendance table. --}}
            @php
                $attendances->getCollection()->loadMissing([
                    'workingSheet.dutySlip.dutyAssignment.travelRequest',
                ]);
            @endphp

        {{-- ========================================================= --}}
        {{-- ATTENDANCE LIST CARD --}}
        {{-- ========================================================= --}}
        <div class="card-box mb-30">

            {{-- ===================================================== --}}
            {{-- CARD HEADER --}}
            {{-- ===================================================== --}}
            <div class="pd-20">

                <div class="attendance-table-heading d-flex flex-wrap justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">

                        @if($isDriver)

                            My Attendance

                        @else

                            All Driver Attendance

                        @endif

                    </h4>


                    <span class="badge badge-primary">

                        Total :

                        {{ $attendances->total() }}

                    </span>

                </div>

            </div>

            {{-- ===================================================== --}}
            {{-- TABLE --}}
            {{-- ===================================================== --}}
            <div class="pb-20">

                <div class="table-responsive attendance-table-wrap">

                    <table class="table hover multiple-select-row data-table-export1 nowrap p-3 attendance-management-table" data-title="Driver Attendance">

                        {{-- ================================================= --}}
                        {{-- TABLE HEADER --}}
                        {{-- ================================================= --}}
                        <thead>

                            <tr>

                                <th class="text-wrap">
                                    Sr. No.
                                </th>

                                @if(!$isDriver)

                                    <th class="text-wrap">
                                        Driver
                                    </th>

                                @endif

                                <th class="text-wrap">
                                    Attendance Date
                                </th>

                                <th class="text-wrap">
                                    In Time
                                </th>

                                <th class="text-wrap">
                                    Out Time
                                </th>

                                <th class="text-wrap">
                                    Total Hours
                                </th>

                                <th class="text-wrap">
                                    Duty Slip Number
                                </th>

                                <th class="text-wrap">
                                    Travel Request Number
                                </th>

                                <th class="text-wrap">
                                    Working Sheet Number
                                </th>

                                <th class="text-wrap">
                                    Status
                                </th>

                                <th class="text-wrap">
                                    Source
                                </th>

                                <th class="text-wrap">
                                    Created By
                                </th>

                                <th class="text-wrap no-export">
                                    Edit
                                </th>

                                <th class="text-wrap no-export">
                                    Delete
                                </th>

                            </tr>

                        </thead>

                        {{-- ================================================= --}}
                        {{-- TABLE BODY --}}
                        {{-- ================================================= --}}
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
                                @endphp

                                <tr>

                                    {{-- ===================================== --}}
                                    {{-- SR NO --}}
                                    {{-- ===================================== --}}
                                    <td>
                                        {{ ($attendances->currentPage() - 1) * $attendances->perPage() + $key + 1 }}
                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- DRIVER --}}
                                    {{-- ===================================== --}}
                                    @if(!$isDriver)

                                        <td>

                                            @if($attendance->driver)

                                                <strong class="text-dark">

                                                    {{ $attendance->driver->first_name . ' ' . $attendance->driver->last_name ?? '-' }}

                                                </strong>

                                            @else

                                                -

                                            @endif

                                        </td>

                                    @endif

                                    {{-- ===================================== --}}
                                    {{-- ATTENDANCE DATE --}}
                                    {{-- ===================================== --}}
                                    <td>

                                        @if($attendance->attendance_date)

                                            <strong>

                                                {{ \Carbon\Carbon::parse(
                                                    $attendance->attendance_date
                                                )->format('d-m-Y') }}

                                            </strong>

                                        @else

                                            -

                                        @endif

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- IN TIME --}}
                                    {{-- ===================================== --}}
                                    <td>

                                        @if($attendance->in_time)

                                            <span class="attendance-time">

                                                <i class="fa fa-sign-in text-success"></i>

                                                {{ \Carbon\Carbon::parse(
                                                    $attendance->in_time
                                                )->format('h:i A') }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- OUT TIME --}}
                                    {{-- ===================================== --}}
                                    <td>

                                        @if($attendance->out_time)

                                            <span class="attendance-time">

                                                <i class="fa fa-sign-out text-danger"></i>

                                                {{ \Carbon\Carbon::parse(
                                                    $attendance->out_time
                                                )->format('h:i A') }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- TOTAL HOURS --}}
                                    {{-- ===================================== --}}
                                    <td>

                                        @if($attendance->total_hours !== null)

                                            <span class="badge badge-info">

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

                                    {{-- ===================================== --}}
                                    {{-- DUTY SLIP NUMBER --}}
                                    {{-- ===================================== --}}
                                    <td>
                                        @if($attendanceDutySlip && $attendanceDutySlip->slip_no)
                                            <strong class="text-dark">{{ $attendanceDutySlip->slip_no }}</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- TRAVEL REQUEST NUMBER --}}
                                    {{-- ===================================== --}}
                                    <td>
                                        @if($attendanceTravelRequest && $attendanceTravelRequest->request_no)
                                            <strong class="text-dark">{{ $attendanceTravelRequest->request_no }}</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- WORKING SHEET NUMBER --}}
                                    {{-- ===================================== --}}
                                    <td>
                                        @if($attendanceWorkingSheet)
                                            <strong class="text-dark">
                                                {{ $attendanceWorkingSheet->sheet_no ?? '-' }}
                                            </strong>

                                            @if($attendanceWorkingSheet->total_km !== null)
                                                <small class="text-muted d-block mt-1">
                                                    <i class="fa fa-road"></i>
                                                    {{ number_format((float) $attendanceWorkingSheet->total_km, 2) }} KM
                                                </small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- STATUS --}}
                                    {{-- ===================================== --}}
                                    <td>

                                        @php

                                            $status =
                                                $attendance->status;

                                            $statusClass = match ($status) {

                                                'present' =>
                                                    'badge-success',

                                                'absent' =>
                                                    'badge-danger',

                                                'half_day' =>
                                                    'badge-warning',

                                                'leave' =>
                                                    'badge-primary',

                                                'pending' =>
                                                    'badge-secondary',

                                                default =>
                                                    'badge-secondary',

                                            };


                                            $statusIcon = match ($status) {

                                                'present' =>
                                                    'fa-check-circle',

                                                'absent' =>
                                                    'fa-times-circle',

                                                'half_day' =>
                                                    'fa-adjust',

                                                'leave' =>
                                                    'fa-calendar',

                                                'pending' =>
                                                    'fa-clock-o',

                                                default =>
                                                    'fa-info-circle',

                                            };

                                        @endphp


                                        <span
                                            class="badge {{ $statusClass }} badge-pill px-3 py-2"
                                        >

                                            <i class="fa {{ $statusIcon }}"></i>

                                            {{ ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $status ?? 'Unknown'
                                                )
                                            ) }}

                                        </span>

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- SOURCE --}}
                                    {{-- ===================================== --}}
                                    <td>

                                        @php

                                            $source =
                                                $attendance->source;

                                            $sourceClass =
                                                $source === 'working_sheet'
                                                    ? 'badge-info'
                                                    : 'badge-secondary';

                                        @endphp


                                        <span
                                            class="badge {{ $sourceClass }}"
                                        >

                                            {{ ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $source ?? 'Unknown'
                                                )
                                            ) }}

                                        </span>

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- CREATED BY --}}
                                    {{-- ===================================== --}}
                                    <td>

                                        @if($attendance->createdBy)

                                            {{ $attendance->createdBy->name }}

                                        @else

                                            -

                                        @endif

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- EDIT --}}
                                    {{-- ===================================== --}}
                                    <td class="no-export">

                                        @if(!$isDriver)
                                            <a
                                                href="{{ route('driver-attendances.edit', $attendance->id) }}"
                                                class="btn btn-warning btn-sm attendance-action-btn"
                                                aria-label="Edit attendance"
                                                title="Edit attendance"
                                            >
                                                <i class="dw dw-pencil-1"></i>
                                                <span>Edit</span>
                                            </a>
                                        @endif

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- DELETE --}}
                                    {{-- ===================================== --}}
                                    <td class="no-export">

                                        @if(!$isDriver)

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
                                                    class="btn btn-danger btn-sm"
                                                >

                                                    <i class="dw dw-trash"></i>

                                                    Delete

                                                </button>

                                            </form>

                                        @else

                                            -

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                {{-- ========================================= --}}
                                {{-- NO DATA --}}
                                {{-- ========================================= --}}
                                <tr>

                                    <td colspan="14" class="text-center" style="vertical-align: middle;">

                                        <div class="p-4">

                                            <i class="fa fa-calendar-check-o fa-2x text-muted mb-3"></i>

                                            <h5 class="text-muted">

                                                @if($isDriver)

                                                    No Attendance Found

                                                @else

                                                    No Driver Attendance Found

                                                @endif

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



                {{-- ===================================================== --}}
                {{-- PAGINATION --}}
                {{-- ===================================================== --}}

                @if($attendances->hasPages())

                    <div class="pd-20">

                        {{ $attendances->links() }}

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



@push('scripts')

@if(!$isDriver)

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | Driver Attendance Delete Confirmation
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.delete-form')
        .forEach(function (form) {

            form.addEventListener('submit', function (e) {

                e.preventDefault();


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
