
@extends('backend.layouts.master')

@section('title')
    Attendance Report
@endsection

@push('styles')
<style>
    /* =========================================================
       ATTENDANCE REPORT - PREMIUM DESIGN
    ========================================================= */

    .attendance-report-page {
        --ar-primary: #2457a7;
        --ar-primary-dark: #193f7d;
        --ar-text: #26364b;
        --ar-muted: #718096;
        --ar-border: #e7edf5;
        --ar-bg: #f7f9fc;
        --ar-success: #16834a;
        --ar-danger: #c43c48;
        --ar-warning: #a66b13;
        --ar-purple: #7154c8;
        --ar-info: #1684a5;
    }

    .attendance-report-page .page-header {
        margin-bottom: 20px;
        padding: 24px 26px;
        border: 1px solid #e1eaf7;
        border-radius: 15px;
        background: linear-gradient(115deg, #ffffff, #f0f6ff);
        box-shadow: 0 6px 22px rgba(26, 47, 84, .045);
    }

    .attendance-report-page .page-header h4 {
        margin-bottom: 7px;
        color: var(--ar-primary);
        font-size: 22px;
        font-weight: 800;
        letter-spacing: -.4px;
    }

    .attendance-report-page .page-header p {
        margin: 0;
        color: var(--ar-muted);
        font-size: 12px;
        line-height: 1.6;
    }

    .attendance-report-page .card-box {
        overflow: hidden;
        border: 1px solid var(--ar-border);
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 7px 24px rgba(26, 47, 84, .05);
    }

    .attendance-report-page .card-box > .pd-20 {
        padding: 22px 24px;
    }

    .attendance-report-page .section-heading {
        margin-bottom: 17px;
        color: var(--ar-primary);
        font-size: 15px;
        font-weight: 800;
    }

    .attendance-report-page .section-heading i {
        margin-right: 6px;
    }

    /* FILTERS */

    .attendance-report-page .report-filter-form {
        padding: 20px;
        border: 1px solid #e2eaf6;
        border-radius: 11px;
        background: #f8faff;
    }

    .attendance-report-page .report-filter-form label {
        display: block;
        margin-bottom: 7px;
        color: #344054;
        font-size: 11px;
        font-weight: 750;
    }

    .attendance-report-page .form-control {
        min-height: 42px;
        border: 1px solid #dce4ef;
        border-radius: 8px;
        background: #fff;
        box-shadow: none;
        color: #344054;
        font-size: 12px;
    }

    .attendance-report-page .form-control:focus {
        border-color: #78a1e5;
        box-shadow: 0 0 0 3px rgba(36, 87, 167, .09);
    }

    .attendance-report-page .form-control[readonly] {
        background: #f0f4fa;
        color: #526176;
        cursor: default;
    }

    .attendance-report-page .btn {
        border-radius: 8px;
        font-size: 11px;
        font-weight: 750;
        transition: all .2s ease;
    }

    .attendance-report-page .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 12px rgba(31, 55, 92, .12);
    }

    .attendance-report-page .btn-primary {
        background: var(--ar-primary);
        border-color: var(--ar-primary);
    }

    /* DRIVER INFORMATION */

    .attendance-driver-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 19px;
        padding: 17px 20px;
        border: 1px solid #dce8f9;
        border-radius: 12px;
        background: linear-gradient(110deg, #f1f6ff, #ffffff);
    }

    .attendance-driver-info .driver-main {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .attendance-driver-info .driver-avatar {
        display: flex;
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #e1edff;
        color: var(--ar-primary);
        font-size: 17px;
    }

    .attendance-driver-info .driver-title {
        color: var(--ar-text);
        font-size: 14px;
        font-weight: 800;
    }

    .attendance-driver-info .driver-subtitle {
        margin-top: 4px;
        color: var(--ar-muted);
        font-size: 10px;
    }

    .attendance-driver-info .month-label {
        padding: 9px 13px;
        border-radius: 8px;
        background: #e8f0ff;
        color: var(--ar-primary);
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    /* SUMMARY CARDS */

    .attendance-report-summary {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 20px;
    }

    .attendance-report-summary-card {
        position: relative;
        min-height: 125px;
        padding: 17px 18px;
        overflow: hidden;
        border: 1px solid var(--ar-border);
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(26, 47, 84, .035);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .attendance-report-summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 9px 20px rgba(26, 47, 84, .09);
    }

    .attendance-report-summary-card::after {
        position: absolute;
        right: -24px;
        bottom: -35px;
        width: 95px;
        height: 95px;
        border-radius: 50%;
        background: rgba(36, 87, 167, .035);
        content: "";
        pointer-events: none;
    }

    .attendance-report-summary-card .summary-icon {
        display: flex;
        width: 36px;
        height: 36px;
        align-items: center;
        justify-content: center;
        float: right;
        border-radius: 10px;
        background: #edf4ff;
        color: var(--ar-primary);
        font-size: 15px;
    }

    .attendance-report-summary-card .summary-label {
        display: block;
        padding-top: 3px;
        color: #667085;
        font-size: 10px;
        font-weight: 750;
    }

    .attendance-report-summary-card .summary-value {
        display: block;
        margin-top: 12px;
        color: var(--ar-text);
        font-size: 27px;
        font-weight: 850;
        line-height: 1.1;
    }

    .attendance-report-summary-card .summary-caption {
        display: block;
        margin-top: 7px;
        color: #98a2b3;
        font-size: 9px;
    }

    .summary-total .summary-icon {
        background: #edf4ff;
        color: #2457a7;
    }

    .summary-marked .summary-icon {
        background: #eaf8f0;
        color: #16834a;
    }

    .summary-unmarked .summary-icon {
        background: #fff0f1;
        color: #c43c48;
    }

    .summary-rate .summary-icon {
        background: #eaf8fb;
        color: #1684a5;
    }

    /* CALENDAR CARD */

    .attendance-report-calendar-card {
        margin-bottom: 25px;
    }

    .attendance-report-calendar-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 13px;
        margin-bottom: 17px;
    }

    .attendance-report-calendar-toolbar h4 {
        margin-bottom: 5px;
        color: var(--ar-primary);
        font-size: 17px;
        font-weight: 850;
    }

    .attendance-report-calendar-toolbar p {
        margin: 0;
        color: var(--ar-muted);
        font-size: 10px;
    }

    /* ATTENDANCE PROGRESS */

    .attendance-progress-panel {
        display: grid;
        grid-template-columns: 105px minmax(0, 1fr) 75px;
        align-items: center;
        gap: 16px;
        margin-bottom: 16px;
        padding: 15px 17px;
        border: 1px solid #e3ebf7;
        border-radius: 11px;
        background: linear-gradient(110deg, #f7faff, #fbfdff);
    }

    .attendance-progress-panel .progress-percentage {
        color: var(--ar-primary);
        font-size: 22px;
        font-weight: 850;
        line-height: 1.2;
    }

    .attendance-progress-panel .progress-caption {
        display: block;
        margin-top: 4px;
        color: #8290a5;
        font-size: 9px;
    }

    .attendance-progress-panel .progress {
        height: 9px;
        overflow: hidden;
        border-radius: 20px;
        background: #e6ebf2;
    }

    .attendance-progress-panel .progress-bar {
        border-radius: 20px;
        background: linear-gradient(90deg, #2457a7, #36b887);
        transition: width .5s ease;
    }

    .attendance-progress-panel .progress-count {
        color: var(--ar-primary);
        text-align: right;
        font-size: 13px;
        font-weight: 850;
    }

    .attendance-progress-panel .progress-count small {
        display: block;
        margin-top: 4px;
        color: #8290a5;
        font-size: 9px;
        font-weight: 500;
    }

    /* LEGEND */

    .attendance-report-legend {
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

    .attendance-report-legend-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #526176;
        font-size: 9px;
        font-weight: 750;
    }

    .attendance-report-legend-dot {
        width: 9px;
        height: 9px;
        flex: 0 0 9px;
        border-radius: 3px;
    }

    .legend-present { background: #20a36a; }
    .legend-absent { background: #dc5661; }
    .legend-leave { background: #8063d5; }
    .legend-holiday { background: #368ac5; }
    .legend-pending { background: #d69a2d; }
    .legend-other { background: #64748b; }
    .legend-unmarked { background: #b8c0cc; }

    /* CALENDAR GRID */

    .attendance-report-calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 9px;
    }

    .attendance-report-weekday {
        padding: 11px 5px;
        border: 1px solid #edf1f7;
        border-radius: 8px;
        background: #f3f6fb;
        color: #64748b;
        text-align: center;
        font-size: 9px;
        font-weight: 850;
        letter-spacing: .45px;
        text-transform: uppercase;
    }

    .attendance-report-day {
        position: relative;
        min-width: 0;
        min-height: 132px;
        padding: 11px;
        overflow: hidden;
        border: 1px solid #e7ecf3;
        border-top: 3px solid #b8c0cc;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 2px 7px rgba(26, 47, 84, .025);
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .attendance-report-day:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 16px rgba(26, 47, 84, .08);
    }

    .attendance-report-day.is-empty {
        min-height: 132px;
        border: 1px dashed #e7ecf3;
        background: #f8f9fb;
        box-shadow: none;
    }

    .attendance-report-day.is-today {
        outline: 2px solid #3978d4;
        outline-offset: -2px;
        background: #f7faff;
    }

    .attendance-report-day.is-present {
        border-top-color: #20a36a;
        background: #fbfffc;
    }

    .attendance-report-day.is-absent {
        border-top-color: #dc5661;
        background: #fffafa;
    }

    .attendance-report-day.is-leave {
        border-top-color: #8063d5;
        background: #fdfcff;
    }

    .attendance-report-day.is-holiday {
        border-top-color: #368ac5;
        background: #fafdff;
    }

    .attendance-report-day.is-pending {
        border-top-color: #d69a2d;
        background: #fffdf8;
    }

    .attendance-report-day.is-other {
        border-top-color: #64748b;
        background: #fcfdff;
    }

    .attendance-report-day.is-unmarked {
        border-top-color: #b8c0cc;
        background: #fafbfc;
    }

    .attendance-report-day-number {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 5px;
        margin-bottom: 10px;
        color: #344054;
        font-size: 13px;
        font-weight: 850;
    }

    .attendance-report-day-number small {
        padding: 3px 6px;
        border-radius: 20px;
        background: #eaf2ff;
        color: #2457a7;
        font-size: 8px;
        font-weight: 800;
    }

    .attendance-report-day-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        max-width: 100%;
        padding: 5px 7px;
        border-radius: 6px;
        font-size: 9px;
        font-weight: 800;
        line-height: 1.3;
        white-space: normal;
    }

    .status-present {
        background: #e7f7ee;
        color: #16834a;
    }

    .status-absent {
        background: #fff0f1;
        color: #c43c48;
    }

    .status-leave {
        background: #f0edff;
        color: #7154c8;
    }

    .status-holiday {
        background: #eaf5ff;
        color: #2877ad;
    }

    .status-pending {
        background: #fff5df;
        color: #a66b13;
    }

    .status-other {
        background: #eef2f6;
        color: #526176;
    }

    .status-unmarked {
        background: #edf0f4;
        color: #667085;
    }

    .attendance-report-day-detail {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 7px;
        color: #667085;
        font-size: 9px;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .attendance-report-day-detail i {
        width: 11px;
        color: #8b98aa;
        text-align: center;
    }

    .attendance-report-day-detail strong {
        color: #344054;
        font-weight: 750;
    }

    .attendance-report-day-hours {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-top: 7px;
        padding: 5px 7px;
        border-radius: 6px;
        background: #edf4ff;
        color: #2457a7;
        font-size: 9px;
        font-weight: 850;
    }

    /* NOTE */

    .attendance-report-note {
        margin-top: 18px;
        padding: 14px 16px;
        border: 1px solid #e3ebf7;
        border-radius: 10px;
        background: #f8faff;
        color: #667085;
        font-size: 10px;
        line-height: 1.7;
    }

    .attendance-report-note > strong {
        color: #344054;
        font-size: 11px;
    }

    .attendance-report-note ul {
        margin-bottom: 0;
        padding-left: 18px;
    }

    /* EMPTY STATE */

    .attendance-report-empty {
        padding: 55px 20px;
        text-align: center;
    }

    .attendance-report-empty i {
        display: block;
        margin-bottom: 14px;
        color: #aab4c2;
        font-size: 42px;
    }

    .attendance-report-empty h5 {
        color: #344054;
        font-size: 16px;
        font-weight: 800;
    }

    .attendance-report-empty p {
        margin: 0;
        color: #718096;
        font-size: 12px;
    }

    /* RESPONSIVE */

    @media (max-width: 1100px) {
        .attendance-report-summary {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .attendance-report-calendar-grid {
            gap: 6px;
        }

        .attendance-report-day,
        .attendance-report-day.is-empty {
            min-height: 120px;
            padding: 8px;
        }
    }

    @media (max-width: 767px) {
        .attendance-report-page .page-header {
            padding: 17px;
        }

        .attendance-report-page .card-box > .pd-20 {
            padding: 15px;
        }

        .attendance-report-calendar-grid {
            gap: 4px;
        }

        .attendance-report-weekday {
            padding: 8px 2px;
            font-size: 8px;
        }

        .attendance-report-day,
        .attendance-report-day.is-empty {
            min-height: 105px;
            padding: 5px;
            border-radius: 7px;
        }

        .attendance-report-day-number {
            margin-bottom: 7px;
            font-size: 10px;
        }

        .attendance-report-day-number small {
            padding: 2px 4px;
            font-size: 7px;
        }

        .attendance-report-day-status {
            gap: 3px;
            padding: 4px;
            font-size: 7px;
        }

        .attendance-report-day-detail {
            gap: 3px;
            margin-top: 5px;
            font-size: 7px;
        }

        .attendance-report-day-hours {
            padding: 4px;
            font-size: 7px;
        }

        .attendance-progress-panel {
            grid-template-columns: 75px minmax(0, 1fr) 55px;
            gap: 9px;
            padding: 12px;
        }

        .attendance-progress-panel .progress-percentage {
            font-size: 17px;
        }

        .attendance-progress-panel .progress-count {
            font-size: 11px;
        }

        .attendance-report-legend {
            gap: 8px 10px;
            padding: 10px;
        }

        .attendance-report-legend-item {
            font-size: 8px;
        }
    }

    @media (max-width: 420px) {
        .attendance-report-summary {
            gap: 8px;
        }

        .attendance-report-summary-card {
            min-height: 110px;
            padding: 11px;
        }

        .attendance-report-summary-card .summary-value {
            font-size: 22px;
        }

        .attendance-report-summary-card .summary-icon {
            width: 29px;
            height: 29px;
            font-size: 12px;
        }

        .attendance-report-calendar-grid {
            gap: 3px;
        }

        .attendance-report-day,
        .attendance-report-day.is-empty {
            min-height: 90px;
            padding: 4px;
        }

        .attendance-report-day-number {
            font-size: 9px;
        }

        .attendance-report-day-status {
            font-size: 6px;
        }

        .attendance-report-day-detail {
            font-size: 6px;
        }

        .attendance-report-day-hours {
            font-size: 6px;
        }
    }

    /* PRINT */

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

        .attendance-report-page {
            width: 100%;
            padding: 0 !important;
        }

        .attendance-report-page .card-box,
        .attendance-report-summary-card {
            box-shadow: none !important;
            break-inside: avoid;
        }

        .attendance-report-page .card-box > .pd-20 {
            padding: 8px 0;
        }

        .attendance-report-calendar-grid {
            gap: 4px;
        }

        .attendance-report-day,
        .attendance-report-day.is-empty {
            min-height: 90px;
            break-inside: avoid;
        }

        .attendance-report-summary-card {
            min-height: 90px;
        }

        .attendance-report-calendar-toolbar {
            margin-bottom: 10px;
        }
    }
</style>
@endpush

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATION AND ROLE ACCESS
    |--------------------------------------------------------------------------
    */

    $authUser = auth()->user();

    $isDriver = $authUser
        && $authUser->role === \App\Models\User::ROLE_DRIVER;

    $canSelectDriver = $authUser && in_array(
        $authUser->role,
        [
            \App\Models\User::ROLE_ADMIN,
            \App\Models\User::ROLE_OPERATIONS,
            \App\Models\User::ROLE_ACCOUNTANT,
        ],
        true
    );

    $canAccessReport = $isDriver || $canSelectDriver;

    abort_unless(
        $canAccessReport,
        403,
        'You are not authorized to access this report.'
    );

    /*
    |--------------------------------------------------------------------------
    | REPORT DATA
    |--------------------------------------------------------------------------
    */

    $reportDriver = $attendanceReport['driver'] ?? null;

    $reportMonth = \Illuminate\Support\Carbon::parse(
        $attendanceReport['month'] ?? now()
    );

    $reportCalendar = $attendanceReport['calendar'] ?? [];

    $reportSummary = $attendanceReport['summary'] ?? [
        'total_days' => 0,
        'marked_days' => 0,
        'not_marked_days' => 0,
        'marking_percentage' => 0,
    ];

    /*
    |--------------------------------------------------------------------------
    | FILTER VALUES
    |--------------------------------------------------------------------------
    */

    $selectedDriverId = $filters['driver_id'] ?? '';

    $selectedMonth = $filters['month']
        ?? $reportMonth->format('Y-m');

    /*
    |--------------------------------------------------------------------------
    | REPORT CALENDAR RANGE
    |--------------------------------------------------------------------------
    */

    $reportMonthStart = $reportMonth->copy()->startOfMonth();

    $reportCalendarStart = $reportMonthStart->copy()
        ->startOfWeek(\Carbon\Carbon::MONDAY);

    $reportCalendarEnd = $reportMonthStart->copy()
        ->endOfMonth()
        ->endOfWeek(\Carbon\Carbon::SUNDAY);

    /*
    |--------------------------------------------------------------------------
    | SUMMARY VALUES
    |--------------------------------------------------------------------------
    */

    $markingPercentage = (float) (
        $reportSummary['marking_percentage'] ?? 0
    );

    $markingPercentage = max(
        0,
        min(100, $markingPercentage)
    );

    /*
    |--------------------------------------------------------------------------
    | DRIVER DISPLAY VALUES
    |--------------------------------------------------------------------------
    */

    $reportDriverName = trim(
        ($reportDriver?->first_name ?? '')
        . ' '
        . ($reportDriver?->last_name ?? '')
    );

    $reportDriverCode = $reportDriver?->driver_code ?? null;

@endphp

<div class="pd-ltr-20 xs-pd-20-10 attendance-report-page">

    <div class="min-height-200px">

        {{-- PAGE HEADER --}}

        <div class="page-header">

            <div class="row align-items-center">

                <div class="col-md-8 col-sm-12">

                    <h4>
                        <i class="fa fa-calendar-check-o mr-1"></i>
                        Attendance Report
                    </h4>

                    <p>
                        Driver-wise monthly attendance marking report
                        with a detailed day-wise calendar overview.
                    </p>

                </div>

                <div class="col-md-4 col-sm-12 text-md-right mt-3 mt-md-0 no-print">

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

        {{-- FILTER CARD --}}

        <div class="card-box mb-30 report-filter-card">

            <div class="pd-20">

                <h4 class="section-heading">
                    <i class="fa fa-filter"></i>
                    Attendance Report Filters
                </h4>

                <form
                    method="GET"
                    action="{{ route('reports.attendances.index') }}"
                    class="report-filter-form"
                >

                    <div class="row">

                        {{-- DRIVER FILTER --}}

                        @if($canSelectDriver)

                            <div class="col-md-5 col-sm-6 mb-20">

                                <label for="attendance-report-driver">
                                    Select Driver
                                </label>

                                <select
                                    name="driver_id"
                                    id="attendance-report-driver"
                                    class="form-control custom-select2"
                                >

                                    <option value="">
                                        Select Driver
                                    </option>

                                    @foreach(($filterOptions['drivers'] ?? []) as $driverOption)

                                        <option
                                            value="{{ $driverOption->id }}"
                                            {{ (string) $selectedDriverId === (string) $driverOption->id ? 'selected' : '' }}
                                        >
                                            {{ $driverOption->first_name ?? '' }}
                                            {{ $driverOption->last_name ?? '' }}

                                            @if(!empty($driverOption->driver_code))
                                                - {{ $driverOption->driver_code }}
                                            @endif
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        @elseif($isDriver)

                            <div class="col-md-5 col-sm-6 mb-20">

                                <label for="attendance-report-driver">
                                    Your Driver Profile
                                </label>

                                <input
                                    type="text"
                                    id="attendance-report-driver"
                                    class="form-control"
                                    value="{{ $reportDriverName ?: 'Driver Profile' }}{{ $reportDriverCode ? ' - ' . $reportDriverCode : '' }}"
                                    readonly
                                >

                                <small class="text-muted d-block mt-1">
                                    You can view only your own attendance report.
                                </small>

                            </div>

                        @endif

                        {{-- MONTH FILTER --}}

                        <div class="col-md-3 col-sm-6 mb-20">

                            <label for="attendance-report-month">
                                Select Month
                            </label>

                            <input
                                type="month"
                                name="month"
                                id="attendance-report-month"
                                class="form-control"
                                value="{{ $selectedMonth }}"
                                required
                            >

                        </div>

                        {{-- FILTER ACTIONS --}}

                        <div class="col-md-4 col-sm-12 mb-20 d-flex align-items-end">

                            <button
                                type="submit"
                                class="btn btn-primary mr-2"
                            >
                                <i class="fa fa-search mr-1"></i>
                                Generate Report
                            </button>

                            <a
                                href="{{ route('reports.attendances.index') }}"
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

                <div class="attendance-report-empty">

                    <i class="fa fa-calendar-check-o"></i>

                    @if($isDriver)

                        <h5>Driver Profile Not Available</h5>

                        <p>
                            Your linked Driver profile is not available.
                            Please contact the administrator.
                        </p>

                    @else

                        <h5>Select a Driver to View Attendance</h5>

                        <p>
                            Choose a driver and month from the filters
                            above to view the monthly attendance report.
                        </p>

                    @endif

                </div>

            </div>

        @else

            {{-- DRIVER INFORMATION --}}

            <div class="attendance-driver-info">

                <div class="driver-main">

                    <div class="driver-avatar">
                        <i class="fa fa-user"></i>
                    </div>

                    <div>

                        <div class="driver-title">
                            {{ $reportDriverName ?: 'Driver' }}
                        </div>

                        <div class="driver-subtitle">

                            @if(!empty($reportDriverCode))

                                Driver Code: {{ $reportDriverCode }}

                            @else

                                Driver ID: {{ $reportDriver->id }}

                            @endif

                            @if($isDriver)
                                <span class="ml-2">
                                    <i class="fa fa-lock"></i>
                                    My Attendance
                                </span>
                            @endif

                        </div>

                    </div>

                </div>

                <div class="month-label">
                    <i class="fa fa-calendar mr-1"></i>
                    {{ $reportMonth->format('F Y') }}
                </div>

            </div>

            {{-- SUMMARY CARDS --}}

            <div class="attendance-report-summary">

                {{-- TOTAL DAYS --}}

                <div class="attendance-report-summary-card summary-total">

                    <span class="summary-icon">
                        <i class="fa fa-calendar"></i>
                    </span>

                    <span class="summary-label">
                        Total Days
                    </span>

                    <span class="summary-value">
                        {{ $reportSummary['total_days'] ?? 0 }}
                    </span>

                    <span class="summary-caption">
                        Calendar days in selected month
                    </span>

                </div>

                {{-- MARKED DAYS --}}

                <div class="attendance-report-summary-card summary-marked">

                    <span class="summary-icon">
                        <i class="fa fa-check-circle"></i>
                    </span>

                    <span class="summary-label">
                        Marked Days
                    </span>

                    <span class="summary-value">
                        {{ $reportSummary['marked_days'] ?? 0 }}
                    </span>

                    <span class="summary-caption">
                        Attendance records found
                    </span>

                </div>

                {{-- NOT MARKED DAYS --}}

                <div class="attendance-report-summary-card summary-unmarked">

                    <span class="summary-icon">
                        <i class="fa fa-calendar-times-o"></i>
                    </span>

                    <span class="summary-label">
                        Not Marked Days
                    </span>

                    <span class="summary-value">
                        {{ $reportSummary['not_marked_days'] ?? 0 }}
                    </span>

                    <span class="summary-caption">
                        Dates without attendance records
                    </span>

                </div>

                {{-- MARKING PERCENTAGE --}}

                <div class="attendance-report-summary-card summary-rate">

                    <span class="summary-icon">
                        <i class="fa fa-pie-chart"></i>
                    </span>

                    <span class="summary-label">
                        Marking Percentage
                    </span>

                    <span class="summary-value">
                        {{ number_format($markingPercentage, 2) }}%
                    </span>

                    <span class="summary-caption">
                        Marked days divided by total calendar days
                    </span>

                </div>

            </div>

            {{-- CALENDAR CARD --}}

            <div class="card-box mb-30 attendance-report-calendar-card">

                <div class="pd-20">

                    {{-- CALENDAR HEADER --}}

                    <div class="attendance-report-calendar-toolbar">

                        <div>

                            <h4>
                                <i class="fa fa-calendar-check-o mr-1"></i>
                                Monthly Attendance Calendar
                            </h4>

                            <p>
                                Day-wise attendance marking and working hours overview.
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

                    {{-- ATTENDANCE PROGRESS --}}

                    <div class="attendance-progress-panel">

                        <div>

                            <div class="progress-percentage">
                                {{ number_format($markingPercentage, 2) }}%
                            </div>

                            <span class="progress-caption">
                                Marking Progress
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
                                    style="width: {{ $markingPercentage }}%;"
                                ></div>

                            </div>

                        </div>

                        <div class="progress-count">

                            {{ $reportSummary['marked_days'] ?? 0 }}/{{ $reportSummary['total_days'] ?? 0 }}

                            <small>
                                Days Marked
                            </small>

                        </div>

                    </div>

                    {{-- ATTENDANCE LEGEND --}}

                    <div class="attendance-report-legend">

                        <span class="attendance-report-legend-item">
                            <span class="attendance-report-legend-dot legend-present"></span>
                            Present
                        </span>

                        <span class="attendance-report-legend-item">
                            <span class="attendance-report-legend-dot legend-absent"></span>
                            Absent
                        </span>

                        <span class="attendance-report-legend-item">
                            <span class="attendance-report-legend-dot legend-leave"></span>
                            Leave
                        </span>

                        <span class="attendance-report-legend-item">
                            <span class="attendance-report-legend-dot legend-holiday"></span>
                            Holiday / Weekly Off
                        </span>

                        <span class="attendance-report-legend-item">
                            <span class="attendance-report-legend-dot legend-pending"></span>
                            Pending / Missing Punch
                        </span>

                        <span class="attendance-report-legend-item">
                            <span class="attendance-report-legend-dot legend-other"></span>
                            Other Marked
                        </span>

                        <span class="attendance-report-legend-item">
                            <span class="attendance-report-legend-dot legend-unmarked"></span>
                            Not Marked
                        </span>

                    </div>

                    {{-- CALENDAR GRID START --}}

                    <div
                        class="attendance-report-calendar-grid"
                        aria-label="Monthly driver attendance calendar"
                    >

                        {{-- WEEKDAY HEADINGS --}}

                        @foreach(
                            [
                                'Monday',
                                'Tuesday',
                                'Wednesday',
                                'Thursday',
                                'Friday',
                                'Saturday',
                                'Sunday'
                            ] as $weekday
                        )

                            <div class="attendance-report-weekday">
                                {{ substr($weekday, 0, 3) }}
                            </div>

                        @endforeach


                        {{-- EMPTY CELLS BEFORE MONTH START --}}

                        @for(
                            $emptyDay = $reportCalendarStart->copy();
                            $emptyDay->lt($reportMonthStart);
                            $emptyDay->addDay()
                        )

                            <div class="attendance-report-day is-empty"></div>

                        @endfor

                        {{-- ACTUAL MONTH DAYS --}}

                        @foreach($reportCalendar as $calendarEntry)

                            @php
                                $calendarDate = $calendarEntry['date'];

                                $calendarIsMarked = $calendarEntry['is_marked'] ?? false;

                                $calendarIsToday = $calendarDate->isToday();

                                $calendarStatus = strtolower(
                                    (string) ($calendarEntry['status'] ?? '')
                                );

                                /*
                                |--------------------------------------------------------------------------
                                | STATUS CLASSIFICATION
                                |--------------------------------------------------------------------------
                                */

                                $calendarStatusClass = 'is-other';

                                $calendarBadgeClass = 'status-other';

                                $presentStatuses = [
                                    'present',
                                    'present_late',
                                    'present_early_exit',
                                ];

                                $absentStatuses = [
                                    'absent',
                                ];

                                $leaveStatuses = [
                                    'leave',
                                    'maternity_leave',
                                    'sick_leave',
                                    'casual_leave',
                                    'paid_leave',
                                    'unpaid_leave',
                                    'comp_off',
                                ];

                                $holidayStatuses = [
                                    'holiday',
                                    'weekly_off',
                                    'restricted_holiday',
                                ];

                                $pendingStatuses = [
                                    'pending',
                                    'missing_punch',
                                ];

                                if (!$calendarIsMarked) {

                                    $calendarStatusClass = 'is-unmarked';

                                    $calendarBadgeClass = 'status-unmarked';

                                } elseif (in_array($calendarStatus, $presentStatuses, true)) {

                                    $calendarStatusClass = 'is-present';

                                    $calendarBadgeClass = 'status-present';

                                } elseif (in_array($calendarStatus, $absentStatuses, true)) {

                                    $calendarStatusClass = 'is-absent';

                                    $calendarBadgeClass = 'status-absent';

                                } elseif (in_array($calendarStatus, $leaveStatuses, true)) {

                                    $calendarStatusClass = 'is-leave';

                                    $calendarBadgeClass = 'status-leave';

                                } elseif (in_array($calendarStatus, $holidayStatuses, true)) {

                                    $calendarStatusClass = 'is-holiday';

                                    $calendarBadgeClass = 'status-holiday';

                                } elseif (in_array($calendarStatus, $pendingStatuses, true)) {

                                    $calendarStatusClass = 'is-pending';

                                    $calendarBadgeClass = 'status-pending';

                                }

                                $calendarStatusLabel = $calendarStatus
                                    ? ucwords(str_replace('_', ' ', $calendarStatus))
                                    : 'Marked';

                                $calendarTimeIn = $calendarEntry['in_time'] ?? null;

                                $calendarTimeOut = $calendarEntry['out_time'] ?? null;

                                $calendarTotalHours = $calendarEntry['total_hours'] ?? null;
                            @endphp

                            <div
                                class="attendance-report-day {{ $calendarStatusClass }} {{ $calendarIsToday ? 'is-today' : '' }}"
                                title="{{ $calendarDate->format('d F Y') }}"
                            >

                                {{-- DAY NUMBER --}}

                                <div class="attendance-report-day-number">

                                    <span>
                                        {{ $calendarDate->day }}
                                    </span>

                                    @if($calendarIsToday)

                                        <small>
                                            Today
                                        </small>

                                    @endif

                                </div>

                                {{-- ATTENDANCE DETAILS --}}

                                @if($calendarIsMarked)

                                    <span class="attendance-report-day-status {{ $calendarBadgeClass }}">

                                        <i class="fa fa-check-circle"></i>

                                        {{ $calendarStatusLabel }}

                                    </span>

                                    {{-- IN TIME --}}

                                    @if(!empty($calendarTimeIn))

                                        <span class="attendance-report-day-detail">

                                            <i class="fa fa-sign-in"></i>

                                            <span>
                                                In:
                                                <strong>
                                                    {{ \Carbon\Carbon::parse($calendarTimeIn)->format('h:i A') }}
                                                </strong>
                                            </span>

                                        </span>

                                    @endif

                                    {{-- OUT TIME --}}

                                    @if(!empty($calendarTimeOut))

                                        <span class="attendance-report-day-detail">

                                            <i class="fa fa-sign-out"></i>

                                            <span>
                                                Out:
                                                <strong>
                                                    {{ \Carbon\Carbon::parse($calendarTimeOut)->format('h:i A') }}
                                                </strong>
                                            </span>

                                        </span>

                                    @endif

                                    {{-- TOTAL HOURS --}}

                                    @if($calendarTotalHours !== null)

                                        <span class="attendance-report-day-hours">

                                            <i class="fa fa-clock-o"></i>

                                            {{ number_format((float) $calendarTotalHours, 2) }} hrs

                                        </span>

                                    @endif

                                @else

                                    <span class="attendance-report-day-status status-unmarked">

                                        <i class="fa fa-minus-circle"></i>

                                        Not Marked

                                    </span>

                                    <span class="attendance-report-day-detail">
                                        No attendance record found.
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

                            <div class="attendance-report-day is-empty"></div>

                        @endfor

                    </div>

                    {{-- CALENDAR GRID END --}}

                    {{-- REPORT INFORMATION --}}

                    <div class="attendance-report-note">

                        <strong>
                            <i class="fa fa-info-circle mr-1"></i>
                            Attendance Report Information
                        </strong>

                        <ul class="mt-2">

                            <li>
                                <strong>Marked:</strong>
                                An attendance record exists for that date.
                            </li>

                            <li>
                                <strong>Not Marked:</strong>
                                No attendance record exists for that date.
                            </li>

                            <li>
                                Present, Absent, Leave, Holiday and other
                                attendance statuses are displayed separately.
                            </li>

                            <li>
                                Not Marked does not automatically mean Absent.
                            </li>

                            <li>
                                Monthly summary considers all calendar days
                                of the selected month.
                            </li>

                        </ul>

                    </div>

                </div>

            </div>

            {{-- CALENDAR CARD END --}}

        @endif

    </div>

</div>

@endsection

@push('scripts')

<script>
    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE REPORT PAGE
    |--------------------------------------------------------------------------
    */

    $(document).ready(function () {

        /*
        |--------------------------------------------------------------------------
        | DRIVER FILTER SELECT2
        |--------------------------------------------------------------------------
        */

        if (
            $.fn.select2 &&
            $('#attendance-report-driver').length
        ) {

            $('#attendance-report-driver').select2({
                width: '100%',
                placeholder: 'Select Driver',
                allowClear: true
            });

        }

    });
</script>

@endpush
