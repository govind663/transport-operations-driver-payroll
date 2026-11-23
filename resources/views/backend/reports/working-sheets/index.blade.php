
@extends('backend.layouts.master')

@section('title')
    Working Sheet Report
@endsection

@php
    $authUser = auth()->user();

    $isDriver = $authUser && $authUser->isDriver();

    $canSelectDriver = $authUser && (
        $authUser->isAdmin()
        || $authUser->isOperations()
        || $authUser->isAccountant()
    );

    $reportDriver = $workingSheetReport['driver'] ?? null;
    $reportMonth = $workingSheetReport['month'] ?? now();
    $reportCalendar = $workingSheetReport['calendar'] ?? [];
    $reportSheets = $workingSheetReport['working_sheets'] ?? collect();
    $reportSummary = $workingSheetReport['summary'] ?? [];

    $markingPercentage = max(
        0,
        min(100, (float) ($reportSummary['marking_percentage'] ?? 0))
    );

    $reportMonthStart = $reportMonth->copy()->startOfMonth();

    $reportCalendarStart = $reportMonthStart->copy()
        ->startOfWeek(\Carbon\Carbon::MONDAY);

    $reportCalendarEnd = $reportMonthStart->copy()
        ->endOfMonth()
        ->endOfWeek(\Carbon\Carbon::SUNDAY);
@endphp

@push('styles')
<style>
    .working-report-page {
        --wr-primary: #2457a7;
        --wr-text: #26364b;
        --wr-muted: #718096;
        --wr-border: #e7edf5;
        --wr-success: #16834a;
        --wr-danger: #c43c48;
        --wr-info: #1684a5;
        --wr-purple: #7154c8;
    }

    .working-report-page .page-header {
        margin-bottom: 20px;
        padding: 24px 26px;
        border: 1px solid #e1eaf7;
        border-radius: 15px;
        background: linear-gradient(115deg,#fff,#f0f6ff);
        box-shadow: 0 6px 22px rgba(26,47,84,.045);
    }

    .working-report-page .page-header h4 {
        margin-bottom: 7px;
        color: var(--wr-primary);
        font-size: 22px;
        font-weight: 800;
    }

    .working-report-page .page-header p {
        margin: 0;
        color: var(--wr-muted);
        font-size: 12px;
    }

    .working-report-page .card-box {
        overflow: hidden;
        border: 1px solid var(--wr-border);
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 7px 24px rgba(26,47,84,.05);
    }

    .working-report-page .card-box > .pd-20 {
        padding: 22px 24px;
    }

    .working-report-page .section-heading {
        margin-bottom: 17px;
        color: var(--wr-primary);
        font-size: 15px;
        font-weight: 800;
    }

    .working-report-page .report-filter-form {
        padding: 20px;
        border: 1px solid #e2eaf6;
        border-radius: 11px;
        background: #f8faff;
    }

    .working-report-page label {
        display: block;
        margin-bottom: 7px;
        color: #344054;
        font-size: 11px;
        font-weight: 750;
    }

    .working-report-page .form-control {
        min-height: 42px;
        border: 1px solid #dce4ef;
        border-radius: 8px;
        background: #fff;
        box-shadow: none;
        color: #344054;
        font-size: 12px;
    }

    .working-report-page .btn {
        border-radius: 8px;
        font-size: 11px;
        font-weight: 750;
    }

    .working-report-page .btn-primary {
        background: var(--wr-primary);
        border-color: var(--wr-primary);
    }

    /* DRIVER INFORMATION */

    .working-driver-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 19px;
        padding: 17px 20px;
        border: 1px solid #dce8f9;
        border-radius: 12px;
        background: linear-gradient(110deg,#f1f6ff,#fff);
    }

    .working-driver-main {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .working-driver-avatar {
        display: flex;
        width: 42px;
        height: 42px;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #e1edff;
        color: var(--wr-primary);
        font-size: 17px;
    }

    .working-driver-title {
        color: var(--wr-text);
        font-size: 14px;
        font-weight: 800;
    }

    .working-driver-subtitle {
        margin-top: 4px;
        color: var(--wr-muted);
        font-size: 10px;
    }

    .working-month-label {
        padding: 9px 13px;
        border-radius: 8px;
        background: #e8f0ff;
        color: var(--wr-primary);
        font-size: 11px;
        font-weight: 800;
    }

    /* SUMMARY */

    .working-report-summary {
        display: grid;
        grid-template-columns: repeat(4,minmax(0,1fr));
        gap: 14px;
        margin-bottom: 20px;
    }

    .working-summary-card {
        min-height: 125px;
        padding: 17px 18px;
        overflow: hidden;
        border: 1px solid var(--wr-border);
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(26,47,84,.035);
    }

    .working-summary-card .summary-icon {
        display: flex;
        width: 36px;
        height: 36px;
        align-items: center;
        justify-content: center;
        float: right;
        border-radius: 10px;
        background: #edf4ff;
        color: var(--wr-primary);
        font-size: 15px;
    }

    .working-summary-card .summary-label {
        display: block;
        padding-top: 3px;
        color: #667085;
        font-size: 10px;
        font-weight: 750;
    }

    .working-summary-card .summary-value {
        display: block;
        margin-top: 12px;
        color: var(--wr-text);
        font-size: 25px;
        font-weight: 850;
        line-height: 1.2;
        overflow-wrap: anywhere;
    }

    .working-summary-card .summary-caption {
        display: block;
        margin-top: 7px;
        color: #98a2b3;
        font-size: 9px;
    }

    .working-summary-card.marked .summary-icon {
        background: #eaf8f0;
        color: #16834a;
    }

    .working-summary-card.unmarked .summary-icon {
        background: #fff0f1;
        color: #c43c48;
    }

    .working-summary-card.hours .summary-icon {
        background: #eaf8fb;
        color: #1684a5;
    }

    .working-summary-card.amount .summary-icon {
        background: #f0edff;
        color: #7154c8;
    }

    /* CALENDAR */

    .working-calendar-card {
        margin-bottom: 25px;
    }

    .working-calendar-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 13px;
        margin-bottom: 17px;
    }

    .working-calendar-toolbar h4 {
        margin-bottom: 5px;
        color: var(--wr-primary);
        font-size: 17px;
        font-weight: 850;
    }

    .working-calendar-toolbar p {
        margin: 0;
        color: var(--wr-muted);
        font-size: 10px;
    }

    .working-progress-panel {
        display: grid;
        grid-template-columns: 105px minmax(0,1fr) 75px;
        align-items: center;
        gap: 16px;
        margin-bottom: 16px;
        padding: 15px 17px;
        border: 1px solid #e3ebf7;
        border-radius: 11px;
        background: linear-gradient(110deg,#f7faff,#fbfdff);
    }

    .working-progress-percentage {
        color: var(--wr-primary);
        font-size: 22px;
        font-weight: 850;
    }

    .working-progress-caption {
        display: block;
        margin-top: 4px;
        color: #8290a5;
        font-size: 9px;
    }

    .working-progress-panel .progress {
        height: 9px;
        overflow: hidden;
        border-radius: 20px;
        background: #e6ebf2;
    }

    .working-progress-panel .progress-bar {
        border-radius: 20px;
        background: linear-gradient(90deg,#2457a7,#36b887);
    }

    .working-progress-count {
        color: var(--wr-primary);
        text-align: right;
        font-size: 13px;
        font-weight: 850;
    }

    .working-progress-count small {
        display: block;
        margin-top: 4px;
        color: #8290a5;
        font-size: 9px;
        font-weight: 500;
    }

    .working-calendar-legend {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 9px 15px;
        margin-bottom: 17px;
        padding: 12px 14px;
        border: 1px solid #edf1f7;
        border-radius: 10px;
        background: #fbfcfe;
    }

    .working-legend-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #526176;
        font-size: 9px;
        font-weight: 750;
    }

    .working-legend-dot {
        width: 9px;
        height: 9px;
        border-radius: 3px;
    }

    .working-legend-marked { background: #20a36a; }
    .working-legend-unmarked { background: #b8c0cc; }

    .working-calendar-grid {
        display: grid;
        grid-template-columns: repeat(7,minmax(0,1fr));
        gap: 9px;
    }

    .working-weekday {
        padding: 11px 5px;
        border: 1px solid #edf1f7;
        border-radius: 8px;
        background: #f3f6fb;
        color: #64748b;
        text-align: center;
        font-size: 9px;
        font-weight: 850;
        text-transform: uppercase;
    }

    .working-day {
        min-width: 0;
        min-height: 145px;
        padding: 11px;
        overflow: hidden;
        border: 1px solid #e7ecf3;
        border-top: 3px solid #b8c0cc;
        border-radius: 10px;
        background: #fafbfc;
    }

    .working-day.is-marked {
        border-top-color: #20a36a;
        background: #fbfffc;
    }

    .working-day.is-today {
        outline: 2px solid #3978d4;
        outline-offset: -2px;
    }

    .working-day.is-empty {
        min-height: 145px;
        border: 1px dashed #e7ecf3;
        background: #f8f9fb;
    }

    .working-day-number {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 5px;
        margin-bottom: 10px;
        color: #344054;
        font-size: 13px;
        font-weight: 850;
    }

    .working-day-number small {
        padding: 3px 6px;
        border-radius: 20px;
        background: #eaf2ff;
        color: #2457a7;
        font-size: 8px;
    }

    .working-day-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        max-width: 100%;
        padding: 5px 7px;
        border-radius: 6px;
        background: #e7f7ee;
        color: #16834a;
        font-size: 9px;
        font-weight: 800;
    }

    .working-day-unmarked {
        background: #edf0f4;
        color: #667085;
    }

    .working-day-detail {
        display: block;
        margin-top: 7px;
        color: #667085;
        font-size: 9px;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .working-day-detail strong {
        color: #344054;
    }

    .working-day-sheet-list {
        margin: 7px 0 0;
        padding-left: 15px;
        color: #526176;
        font-size: 9px;
    }

    .working-day-sheet-list li {
        margin-bottom: 4px;
        overflow-wrap: anywhere;
    }

    .working-report-empty {
        padding: 55px 20px;
        text-align: center;
    }

    .working-report-empty i {
        display: block;
        margin-bottom: 14px;
        color: #aab4c2;
        font-size: 42px;
    }

    .working-report-empty h5 {
        color: #344054;
        font-size: 16px;
        font-weight: 800;
    }

    .working-report-empty p {
        margin: 0;
        color: #718096;
        font-size: 12px;
    }
</style>
@endpush

@section('content')

<div class="pd-ltr-20 xs-pd-20-10 working-report-page">

    <div class="min-height-200px">

        {{-- PAGE HEADER --}}

        <div class="page-header">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h4>
                        <i class="fa fa-file-text-o mr-1"></i>
                        Working Sheet Report
                    </h4>

                    <p>
                        Driver-wise monthly working sheet,
                        working hours, kilometres and earnings overview.
                    </p>

                </div>

                <div class="col-md-4 text-md-right mt-3 mt-md-0 no-print">

                    <button
                        type="button"
                        class="btn btn-dark"
                        onclick="window.print()"
                    >
                        <i class="fa fa-print mr-1"></i>
                        Print Report
                    </button>

                </div>

            </div>

        </div>

        {{-- ROLE-BASED FILTER CARD --}}

        <div class="card-box mb-30 report-filter-card">

            <div class="pd-20">

                <h4 class="section-heading">
                    <i class="fa fa-filter"></i>
                    Working Sheet Report Filters
                </h4>

                <form
                    method="GET"
                    action="{{ route('reports.working-sheets.index') }}"
                    class="report-filter-form"
                >

                    <div class="row">

                        {{-- ADMIN / OPERATIONS / ACCOUNTANT ONLY --}}

                        @if($canSelectDriver)

                            <div class="col-md-5 col-sm-6 mb-20">

                                <label for="working-report-driver">
                                    Select Driver
                                </label>

                                <select
                                    name="driver_id"
                                    id="working-report-driver"
                                    class="form-control"
                                >

                                    <option value="">
                                        Select Driver
                                    </option>

                                    @foreach(($filterOptions['drivers'] ?? []) as $driverOption)

                                        <option
                                            value="{{ $driverOption->id }}"
                                            {{ (string) request('driver_id') === (string) $driverOption->id ? 'selected' : '' }}
                                        >
                                            {{ $driverOption->first_name ?? '' }}
                                            {{ $driverOption->last_name ?? '' }}

                                            @if($driverOption->driver_code)
                                                - {{ $driverOption->driver_code }}
                                            @endif
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        @elseif($isDriver)

                            {{-- DRIVER CANNOT CHANGE DRIVER ID --}}

                            <div class="col-md-5 col-sm-6 mb-20">

                                <label>
                                    Driver
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $reportDriver ? trim(($reportDriver->first_name ?? '') . ' ' . ($reportDriver->last_name ?? '')) : 'Your Driver Profile' }}"
                                    readonly
                                >

                            </div>

                        @endif

                        {{-- ALL ALLOWED ROLES CAN SELECT MONTH --}}

                        <div class="col-md-3 col-sm-6 mb-20">

                            <label for="working-report-month">
                                Select Month
                            </label>

                            <input
                                type="month"
                                name="month"
                                id="working-report-month"
                                class="form-control"
                                value="{{ $reportMonth->format('Y-m') }}"
                                required
                            >

                        </div>

                        <div class="col-md-4 col-sm-12 mb-20 d-flex align-items-end">

                            <button
                                type="submit"
                                class="btn btn-primary mr-2"
                            >
                                <i class="fa fa-search mr-1"></i>
                                Generate Report
                            </button>

                            <a
                                href="{{ route('reports.working-sheets.index') }}"
                                class="btn btn-secondary"
                            >
                                <i class="fa fa-refresh mr-1"></i>
                                Reset
                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>

        {{-- REPORT RESULT --}}

        @if(!$reportDriver)

            <div class="card-box mb-30">

                <div class="working-report-empty">

                    <i class="fa fa-calendar"></i>

                    @if($isDriver)

                        <h5>Driver Profile Not Available</h5>

                        <p>
                            Your linked driver profile could not be found.
                            Please contact the administrator.
                        </p>

                    @else

                        <h5>Select a Driver to View Working Sheet Report</h5>

                        <p>
                            Choose a driver and month from the filters
                            above to view the monthly working sheet report.
                        </p>

                    @endif

                </div>

            </div>

        @else

            {{-- DRIVER INFORMATION --}}

            <div class="working-driver-info">

                <div class="working-driver-main">

                    <div class="working-driver-avatar">
                        <i class="fa fa-user"></i>
                    </div>

                    <div>

                        <div class="working-driver-title">
                            {{ $reportDriver->first_name ?? '' }}
                            {{ $reportDriver->last_name ?? '' }}
                        </div>

                        <div class="working-driver-subtitle">
                            Driver Code:
                            {{ $reportDriver->driver_code ?? $reportDriver->id }}
                        </div>

                    </div>

                </div>

                <div class="working-month-label">
                    <i class="fa fa-calendar mr-1"></i>
                    {{ $reportMonth->format('F Y') }}
                </div>

            </div>


            {{-- SUMMARY CARDS --}}

            <div class="working-report-summary">

                <div class="working-summary-card">

                    <span class="summary-icon">
                        <i class="fa fa-calendar"></i>
                    </span>

                    <span class="summary-label">Total Days</span>

                    <span class="summary-value">
                        {{ $reportSummary['total_days'] ?? 0 }}
                    </span>

                    <span class="summary-caption">
                        Calendar days in selected month
                    </span>

                </div>

                <div class="working-summary-card marked">

                    <span class="summary-icon">
                        <i class="fa fa-check-circle"></i>
                    </span>

                    <span class="summary-label">Marked Days</span>

                    <span class="summary-value">
                        {{ $reportSummary['marked_days'] ?? 0 }}
                    </span>

                    <span class="summary-caption">
                        Dates having Working Sheets
                    </span>

                </div>

                <div class="working-summary-card unmarked">

                    <span class="summary-icon">
                        <i class="fa fa-calendar-times-o"></i>
                    </span>

                    <span class="summary-label">Not Marked Days</span>

                    <span class="summary-value">
                        {{ $reportSummary['not_marked_days'] ?? 0 }}
                    </span>

                    <span class="summary-caption">
                        Dates without Working Sheets
                    </span>

                </div>

                <div class="working-summary-card hours">

                    <span class="summary-icon">
                        <i class="fa fa-clock-o"></i>
                    </span>

                    <span class="summary-label">Total Working Hours</span>

                    <span class="summary-value">
                        {{ number_format((float) ($reportSummary['total_hours'] ?? 0), 2) }}
                    </span>

                    <span class="summary-caption">
                        Total hours for selected month
                    </span>

                </div>

                <div class="working-summary-card">

                    <span class="summary-icon">
                        <i class="fa fa-file-text"></i>
                    </span>

                    <span class="summary-label">Total Sheets</span>

                    <span class="summary-value">
                        {{ $reportSummary['total_sheets'] ?? 0 }}
                    </span>

                    <span class="summary-caption">
                        Total Working Sheet records
                    </span>

                </div>

                <div class="working-summary-card">

                    <span class="summary-icon">
                        <i class="fa fa-road"></i>
                    </span>

                    <span class="summary-label">Total Kilometres</span>

                    <span class="summary-value">
                        {{ number_format((float) ($reportSummary['total_km'] ?? 0), 2) }}
                    </span>

                    <span class="summary-caption">
                        Total KM recorded
                    </span>

                </div>

                <div class="working-summary-card">

                    <span class="summary-icon">
                        <i class="fa fa-hourglass-half"></i>
                    </span>

                    <span class="summary-label">Overtime Hours</span>

                    <span class="summary-value">
                        {{ number_format((float) ($reportSummary['overtime_hours'] ?? 0), 2) }}
                    </span>

                    <span class="summary-caption">
                        Total overtime hours
                    </span>

                </div>

                <div class="working-summary-card amount">

                    <span class="summary-icon">
                        <i class="fa fa-money"></i>
                    </span>

                    <span class="summary-label">Total Amount</span>

                    <span class="summary-value">
                        ₹{{ number_format((float) ($reportSummary['total_amount'] ?? 0), 2) }}
                    </span>

                    <span class="summary-caption">
                        Total recorded Working Sheet amount
                    </span>

                </div>

            </div>

            {{-- CALENDAR CARD --}}

            <div class="card-box mb-30 working-calendar-card">

                <div class="pd-20">

                    <div class="working-calendar-toolbar">

                        <div>

                            <h4>
                                <i class="fa fa-calendar-check-o mr-1"></i>
                                Monthly Working Sheet Calendar
                            </h4>

                            <p>
                                Date-wise Working Sheets, kilometres,
                                working hours and amount overview.
                            </p>

                        </div>

                        <div class="no-print">

                            <button
                                type="button"
                                class="btn btn-outline-primary btn-sm"
                                onclick="window.print()"
                            >
                                <i class="fa fa-print mr-1"></i>
                                Print Calendar
                            </button>

                        </div>

                    </div>

                    {{-- PROGRESS --}}

                    <div class="working-progress-panel">

                        <div>

                            <div class="working-progress-percentage">
                                {{ number_format($markingPercentage, 2) }}%
                            </div>

                            <span class="working-progress-caption">
                                Working Sheet Progress
                            </span>

                        </div>

                        <div>

                            <div
                                class="progress"
                                role="progressbar"
                                aria-valuenow="{{ $markingPercentage }}"
                                aria-valuemin="0"
                                aria-valuemax="100"
                            >

                                <div
                                    class="progress-bar"
                                    style="width: {{ $markingPercentage }}%"
                                ></div>

                            </div>

                        </div>

                        <div class="working-progress-count">

                            {{ $reportSummary['marked_days'] ?? 0 }}/{{ $reportSummary['total_days'] ?? 0 }}

                            <small>Days Marked</small>

                        </div>

                    </div>

                    {{-- LEGEND --}}

                    <div class="working-calendar-legend">

                        <span class="working-legend-item">
                            <span class="working-legend-dot working-legend-marked"></span>
                            Working Sheet Available
                        </span>

                        <span class="working-legend-item">
                            <span class="working-legend-dot working-legend-unmarked"></span>
                            Not Marked
                        </span>

                    </div>

                    {{-- CALENDAR GRID --}}

                    <div
                        class="working-calendar-grid"
                        aria-label="Monthly Working Sheet calendar"
                    >

                        @foreach(
                            ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']
                            as $weekday
                        )

                            <div class="working-weekday">
                                {{ substr($weekday, 0, 3) }}
                            </div>

                        @endforeach

                        {{-- EMPTY CELLS BEFORE MONTH START --}}

                        @for(
                            $emptyDay = $reportCalendarStart->copy();
                            $emptyDay->lt($reportMonthStart);
                            $emptyDay->addDay()
                        )

                            <div class="working-day is-empty"></div>

                        @endfor

                        {{-- MONTH DAYS --}}

                        @foreach($reportCalendar as $calendarEntry)

                            @php
                                $calendarDate = $calendarEntry['date'];

                                $calendarIsMarked = $calendarEntry['is_marked'] ?? false;

                                $calendarIsToday = $calendarDate->isToday();

                                $calendarSheets = $calendarEntry['working_sheets'] ?? collect();
                            @endphp

                            <div
                                class="working-day
                                    {{ $calendarIsMarked ? 'is-marked' : '' }}
                                    {{ $calendarIsToday ? 'is-today' : '' }}"
                                title="{{ $calendarDate->format('d F Y') }}"
                            >

                                <div class="working-day-number">

                                    <span>
                                        {{ $calendarDate->day }}
                                    </span>

                                    @if($calendarIsToday)
                                        <small>Today</small>
                                    @endif

                                </div>

                                @if($calendarIsMarked)

                                    <span class="working-day-status">

                                        <i class="fa fa-check-circle"></i>

                                        {{ $calendarEntry['sheet_count'] ?? $calendarSheets->count() }}
                                        Sheet(s)

                                    </span>

                                    <span class="working-day-detail">
                                        KM:
                                        <strong>
                                            {{ number_format((float) ($calendarEntry['total_km'] ?? 0), 2) }}
                                        </strong>
                                    </span>

                                    <span class="working-day-detail">
                                        Hours:
                                        <strong>
                                            {{ number_format((float) ($calendarEntry['total_hours'] ?? 0), 2) }}
                                        </strong>
                                    </span>

                                    <span class="working-day-detail">
                                        Amount:
                                        <strong>
                                            ₹{{ number_format((float) ($calendarEntry['total_amount'] ?? 0), 2) }}
                                        </strong>
                                    </span>

                                    <ul class="working-day-sheet-list">

                                        @foreach($calendarSheets as $daySheet)

                                            <li>
                                                {{ $daySheet->sheet_no ?? 'Sheet #' . $daySheet->id }}
                                            </li>

                                        @endforeach

                                    </ul>

                                @else

                                    <span class="working-day-status working-day-unmarked">

                                        <i class="fa fa-minus-circle"></i>

                                        Not Marked

                                    </span>

                                    <span class="working-day-detail">
                                        No Working Sheet found.
                                    </span>

                                @endif

                            </div>

                        @endforeach

                        {{-- EMPTY CELLS AFTER MONTH END --}}

                        @for(
                            $emptyDay = $reportMonthStart->copy()
                                ->endOfMonth()
                                ->addDay();
                            $emptyDay->lte($reportCalendarEnd);
                            $emptyDay->addDay()
                        )

                            <div class="working-day is-empty"></div>

                        @endfor

                    </div>

                    {{-- CALENDAR INFORMATION --}}

                    <div class="mt-4 p-3"
                         style="border:1px solid #e3ebf7;border-radius:10px;background:#f8faff;">

                        <strong style="font-size:11px;color:#344054;">
                            <i class="fa fa-info-circle mr-1"></i>
                            Working Sheet Report Information
                        </strong>

                        <ul class="mt-2 mb-0"
                            style="padding-left:18px;color:#667085;font-size:10px;line-height:1.8;">

                            <li>
                                Marked means at least one Working Sheet exists for that date.
                            </li>

                            <li>
                                Not Marked means no matching Working Sheet exists for that date.
                            </li>

                            <li>
                                Multiple Working Sheets on the same date are counted separately
                                and all are retained in the report.
                            </li>

                            <li>
                                Not Marked does not automatically mean absent or leave.
                            </li>

                            <li>
                                Monthly summary includes all calendar days of the selected month.
                            </li>

                        </ul>

                    </div>

                </div>

            </div>


            {{-- MONTHLY FINANCIAL SUMMARY --}}

            <div class="card-box mb-30">

                <div class="pd-20">

                    <h4 class="section-heading">
                        <i class="fa fa-money"></i>
                        Monthly Earnings Breakdown
                    </h4>

                    <div class="table-responsive">

                        <table class="table table-bordered">

                            <thead>
                                <tr>
                                    <th>Particulars</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr>
                                    <td>Base Amount</td>
                                    <td class="text-right">
                                        ₹{{ number_format((float) ($reportSummary['base_amount'] ?? 0), 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Extra KM Amount</td>
                                    <td class="text-right">
                                        ₹{{ number_format((float) ($reportSummary['extra_km_amount'] ?? 0), 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Overtime Amount</td>
                                    <td class="text-right">
                                        ₹{{ number_format((float) ($reportSummary['overtime_amount'] ?? 0), 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Allowance Amount</td>
                                    <td class="text-right">
                                        ₹{{ number_format((float) ($reportSummary['allowance_amount'] ?? 0), 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Expense Amount</td>
                                    <td class="text-right">
                                        ₹{{ number_format((float) ($reportSummary['expense_amount'] ?? 0), 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td>Other Amount</td>
                                    <td class="text-right">
                                        ₹{{ number_format((float) ($reportSummary['other_amount'] ?? 0), 2) }}
                                    </td>
                                </tr>

                                <tr class="font-weight-bold">
                                    <td>Total Amount</td>
                                    <td class="text-right">
                                        ₹{{ number_format((float) ($reportSummary['total_amount'] ?? 0), 2) }}
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            {{-- DAY-WISE RECORDS --}}

            <div class="card-box mb-30">

                <div class="pd-20">

                    <div class="working-calendar-toolbar">

                        <div>

                            <h4>
                                <i class="fa fa-list-alt mr-1"></i>
                                Day-wise Working Sheet Records
                            </h4>

                            <p>
                                Complete list of Working Sheets for the selected driver and month.
                            </p>

                        </div>

                        <span class="badge badge-primary">
                            {{ $reportSheets->count() }} Records
                        </span>

                    </div>

                    <div class="table-responsive">

                        <table class="table table-bordered table-striped">

                            <thead>

                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Sheet No.</th>
                                    <th>Duty Slip</th>
                                    <th>Vehicle</th>
                                    <th>KM</th>
                                    <th>Hours</th>
                                    <th>Overtime</th>
                                    <th>Allowance</th>
                                    <th>Expenses</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th class="no-print">Action</th>
                                </tr>

                            </thead>

                            <tbody>

                                @forelse($reportSheets as $index => $sheet)

                                    @php
                                        $sheetVehicle = $sheet->dutySlip?->vehicle
                                            ?? $sheet->dutySlip?->dutyAssignment?->vehicle;
                                    @endphp

                                    <tr>

                                        <td>{{ $index + 1 }}</td>

                                        <td>
                                            {{ $sheet->work_date
                                                ? \Illuminate\Support\Carbon::parse($sheet->work_date)->format('d-m-Y')
                                                : '-' }}
                                        </td>

                                        <td>
                                            {{ $sheet->sheet_no ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $sheet->dutySlip?->slip_no ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $sheetVehicle?->vehicle_number
                                                ?? $sheetVehicle?->registration_number
                                                ?? '-' }}
                                        </td>

                                        <td>
                                            {{ number_format((float) $sheet->total_km, 2) }}
                                        </td>

                                        <td>
                                            {{ number_format((float) $sheet->total_hours, 2) }}
                                        </td>

                                        <td>
                                            {{ number_format((float) $sheet->overtime_hours, 2) }}
                                        </td>

                                        <td>
                                            ₹{{ number_format((float) $sheet->allowance_amount, 2) }}
                                        </td>

                                        <td>
                                            ₹{{ number_format((float) $sheet->expense_amount, 2) }}
                                        </td>

                                        <td>
                                            ₹{{ number_format((float) $sheet->total_amount, 2) }}
                                        </td>

                                        <td>
                                            <span class="badge badge-secondary">
                                                {{ ucfirst($sheet->status ?? 'N/A') }}
                                            </span>
                                        </td>

                                        <td class="no-print">

                                            <a
                                                href="{{ route('working-sheets.show', $sheet->id) }}"
                                                class="btn btn-sm btn-info"
                                                title="View Working Sheet"
                                            >
                                                <i class="fa fa-eye"></i>
                                                View
                                            </a>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="13" class="text-center py-4">
                                            No Working Sheet records found for this month.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection

@push('styles')
<style>
    @media (max-width: 1100px) {

        .working-report-summary {
            grid-template-columns: repeat(2,minmax(0,1fr));
        }

        .working-calendar-grid {
            gap: 6px;
        }

        .working-day,
        .working-day.is-empty {
            min-height: 125px;
            padding: 8px;
        }

    }

    @media (max-width: 767px) {

        .working-report-page .page-header {
            padding: 17px;
        }

        .working-report-page .card-box > .pd-20 {
            padding: 15px;
        }

        .working-calendar-grid {
            gap: 4px;
        }

        .working-weekday {
            padding: 8px 2px;
            font-size: 8px;
        }

        .working-day,
        .working-day.is-empty {
            min-height: 110px;
            padding: 5px;
            border-radius: 7px;
        }

        .working-day-number {
            margin-bottom: 7px;
            font-size: 10px;
        }

        .working-day-number small {
            padding: 2px 4px;
            font-size: 7px;
        }

        .working-day-status {
            gap: 3px;
            padding: 4px;
            font-size: 7px;
        }

        .working-day-detail,
        .working-day-sheet-list {
            font-size: 7px;
        }

        .working-progress-panel {
            grid-template-columns: 75px minmax(0,1fr) 55px;
            gap: 9px;
            padding: 12px;
        }

        .working-progress-percentage {
            font-size: 17px;
        }

        .working-progress-count {
            font-size: 11px;
        }

        .working-calendar-legend {
            gap: 8px 10px;
            padding: 10px;
        }

        .working-legend-item {
            font-size: 8px;
        }

    }

    @media (max-width: 420px) {

        .working-report-summary {
            gap: 8px;
        }

        .working-summary-card {
            min-height: 110px;
            padding: 11px;
        }

        .working-summary-card .summary-value {
            font-size: 21px;
        }

        .working-summary-card .summary-icon {
            width: 29px;
            height: 29px;
            font-size: 12px;
        }

        .working-calendar-grid {
            gap: 3px;
        }

        .working-day,
        .working-day.is-empty {
            min-height: 95px;
            padding: 4px;
        }

        .working-day-number {
            font-size: 9px;
        }

        .working-day-status {
            font-size: 6px;
        }

        .working-day-detail,
        .working-day-sheet-list {
            font-size: 6px;
        }

    }

    @media print {

        @page {
            size: landscape;
            margin: 10mm;
        }

        body {
            background: #fff !important;
        }

        .left-side-bar,
        .header,
        .footer-wrap,
        .report-filter-card,
        .no-print {
            display: none !important;
        }

        .working-report-page {
            width: 100%;
            padding: 0 !important;
        }

        .working-report-page .card-box,
        .working-summary-card {
            box-shadow: none !important;
            break-inside: avoid;
        }

        .working-report-page .card-box > .pd-20 {
            padding: 8px 0;
        }

        .working-calendar-grid {
            gap: 4px;
        }

        .working-day,
        .working-day.is-empty {
            min-height: 90px;
            break-inside: avoid;
        }

        .working-summary-card {
            min-height: 90px;
        }

    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function () {

        if (
            $.fn.select2 &&
            $('#working-report-driver').length
        ) {

            $('#working-report-driver').select2({
                width: '100%',
                placeholder: 'Select Driver',
                allowClear: true
            });

        }

    });
</script>
@endpush
