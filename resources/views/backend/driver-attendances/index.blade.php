@extends('backend.layouts.master')

@section('title')
    {{ $isDriver ? 'My Attendance' : 'Driver Attendance' }}
@endsection

@push('styles')

<link rel="stylesheet" href="{{ asset('backend/assets/datatable/css/dataTables-responsive.css') }}">

<style>
    /* ================================================================
       DRIVER ATTENDANCE - TABLE ONLY
    ================================================================ */

    .driver-attendance-page {
        --da-primary: #2457a7;
        --da-border: #e7edf5;
        --da-muted: #718096;
        --da-text: #344054;
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
        background: #fff;
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

    /* FILTERS */

    .attendance-filter-form {
        padding: 22px;
        border: 1px solid var(--da-border);
        border-radius: 11px;
        background: #fbfcff;
    }

    .attendance-filter-form label {
        display: block;
        margin-bottom: 7px;
        color: #344054;
        font-size: 12px;
        font-weight: 750;
    }

    /* DRIVER WELCOME */

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

    /* TABLE */

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
    .attendance-time {
        font-weight: 650;
    }

    .attendance-empty-state {
        padding: 45px 20px;
        text-align: center;
    }

    .attendance-empty-state i {
        display: block;
        margin-bottom: 14px;
        color: #aab4c2;
        font-size: 40px;
    }

    .attendance-empty-state h5 {
        color: #526176;
        font-size: 16px;
        font-weight: 750;
    }

    .attendance-empty-state p {
        margin: 0;
        color: var(--da-muted);
        font-size: 12px;
    }

    .pagination {
        flex-wrap: wrap;
        gap: 4px;
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

        .attendance-filter-form {
            padding: 15px;
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
        .no-print,
        .attendance-filter-card,
        .pagination {
            display: none !important;
        }

        .driver-attendance-page {
            width: 100%;
            padding: 0 !important;
        }

        .driver-attendance-page .card-box {
            box-shadow: none !important;
        }

        .attendance-management-table thead th,
        .attendance-management-table tbody td {
            padding: 7px;
            font-size: 9px;
        }
    }
</style>

@endpush

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | ROLE AND TABLE DATA
    |--------------------------------------------------------------------------
    */

    $isDriver = (bool) ($isDriver ?? false);

    $attendanceRows = $attendances ?? collect();

    /*
    |--------------------------------------------------------------------------
    | LOAD ONLY RELATIONSHIPS USED BY THE TABLE
    |--------------------------------------------------------------------------
    */

    if ($attendanceRows instanceof \Illuminate\Pagination\AbstractPaginator) {
        $attendanceCollection = $attendanceRows->getCollection();
    } else {
        $attendanceCollection = collect($attendanceRows);
    }

    if (method_exists($attendanceCollection, 'loadMissing')) {
        $attendanceCollection->loadMissing([
            'driver',
            'createdBy',
        ]);
    }

    $attendanceTotalRecords = method_exists($attendanceRows, 'total')
        ? $attendanceRows->total()
        : $attendanceCollection->count();

    $attendanceHasPages = method_exists($attendanceRows, 'hasPages')
        ? $attendanceRows->hasPages()
        : false;

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
        ];

        switch ($category) {
            case 'present':
                $meta['badge'] = 'success';
                $meta['icon'] = 'fa-check-circle';
                break;

            case 'absent':
                $meta['badge'] = 'danger';
                $meta['icon'] = 'fa-times-circle';
                break;

            case 'exception':
                $meta['badge'] = 'warning';
                $meta['icon'] = 'fa-exclamation-circle';
                break;

            case 'leave':
                $meta['badge'] = 'primary';
                $meta['icon'] = 'fa-calendar';
                break;

            case 'off':
                $meta['badge'] = 'info';
                $meta['icon'] = 'fa-calendar-o';
                break;
        }

        return $meta;
    };
@endphp

<div class="pd-ltr-20 xs-pd-20-10 driver-attendance-page">

    <div class="min-height-200px">

        {{-- PAGE HEADER --}}

        <div class="page-header">

            <div class="row align-items-center">

                <div class="{{ !$isDriver ? 'col-md-8' : 'col-md-12' }} col-sm-12">

                    <h4>
                        {{ $isDriver ? 'My Attendance' : 'Driver Attendance Management' }}
                    </h4>

                    <p>
                        @if($isDriver)
                            View your own attendance records and working hours.
                        @else
                            Search, filter and manage driver attendance records.
                        @endif
                    </p>

                </div>

                @if(!$isDriver)
                    <div class="col-md-4 col-sm-12 text-md-right mt-3 mt-md-0 no-print">
                        <a
                            href="{{ route('driver-attendances.create') }}"
                            class="btn btn-primary"
                        >
                            <i class="fa fa-plus mr-1"></i>
                            Add Driver Attendance
                        </a>
                    </div>
                @endif

            </div>

        </div>

        {{-- VALIDATION ERRORS --}}

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Please check the following:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- MANAGEMENT FILTERS --}}

        @if(!$isDriver)

            <div class="card-box mb-30 attendance-filter-card">

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

                        <div class="row">

                            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                <label for="attendance-search">Search</label>
                                <input
                                    type="text"
                                    id="attendance-search"
                                    name="search"
                                    value="{{ request('search') }}"
                                    class="form-control"
                                    placeholder="Driver name, code or mobile"
                                >
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                <label for="attendance-driver">Driver</label>
                                <select
                                    name="driver_id"
                                    id="attendance-driver"
                                    class="form-control custom-select2"
                                >
                                    <option value="">All Drivers</option>

                                    @foreach(($drivers ?? collect()) as $driver)
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
                                <label for="attendance-date">Attendance Date</label>
                                <input
                                    type="date"
                                    id="attendance-date"
                                    name="attendance_date"
                                    value="{{ request('attendance_date') }}"
                                    class="form-control"
                                >
                            </div>

                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                <label for="attendance-date-from">Date From</label>
                                <input
                                    type="date"
                                    id="attendance-date-from"
                                    name="date_from"
                                    value="{{ request('date_from') }}"
                                    class="form-control"
                                >
                            </div>

                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                <label for="attendance-date-to">Date To</label>
                                <input
                                    type="date"
                                    id="attendance-date-to"
                                    name="date_to"
                                    value="{{ request('date_to') }}"
                                    class="form-control"
                                >
                            </div>

                            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                <label for="attendance-status">Status</label>
                                <select
                                    name="status"
                                    id="attendance-status"
                                    class="form-control custom-select2"
                                >
                                    <option value="">All Status</option>

                                    @foreach(($statuses ?? []) as $status)
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
                                <label for="attendance-source">Source</label>
                                <select
                                    name="source"
                                    id="attendance-source"
                                    class="form-control custom-select2"
                                >
                                    <option value="">All Sources</option>

                                    @foreach(($sources ?? []) as $source)
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
                                <button type="submit" class="btn btn-primary mr-2">
                                    <i class="fa fa-search mr-1"></i>
                                    Apply Filters
                                </button>

                                <a
                                    href="{{ route('driver-attendances.index') }}"
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

        @endif

        {{-- DRIVER PERSONAL INTRO --}}

        @if($isDriver)
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
                            These are your personal attendance records.
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ATTENDANCE TABLE FOR ALL ROLES --}}

        <div class="card-box mb-30">

            <div class="pd-20">

                <div class="attendance-table-heading d-flex flex-wrap justify-content-between align-items-center">

                    <div>
                        <h4 class="text-blue h4 mb-1">
                            {{ $isDriver ? 'My Attendance Records' : 'All Driver Attendance Records' }}
                        </h4>

                        <small class="text-muted">
                            @if($isDriver)
                                Your own attendance history, working hours and status.
                            @else
                                Detailed attendance records with available management actions.
                            @endif
                        </small>
                    </div>

                    <span class="badge badge-primary px-3 py-2">
                        Total Records: {{ $attendanceTotalRecords }}
                    </span>

                </div>

            </div>

            <div class="pb-20">

                <div class="table-responsive attendance-table-wrap">

                    <table
                        class="table hover multiple-select-row data-table-export1 nowrap p-3 attendance-management-table"
                        data-title="{{ $isDriver ? 'My Attendance' : 'Driver Attendance' }}"
                    >

                        <thead>
                            <tr>
                                <th>Sr. No.</th>

                                @if(!$isDriver)
                                    <th>Driver</th>
                                @endif

                                <th>Attendance Date</th>
                                <th>In Time</th>
                                <th>Out Time</th>
                                <th>Total Hours</th>
                                <th>Status</th>
                                <th>Source</th>

                                @if(!$isDriver)
                                    <th>Created By</th>
                                    <th class="no-export">Edit</th>
                                    <th class="no-export">Delete</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($attendanceRows as $key => $attendance)

                                @php
                                    $attendanceMeta = $getAttendanceStatusMeta(
                                        $attendance->status
                                    );

                                    $attendanceSource = $attendance->source;

                                    $attendanceSourceClass = $attendanceSource === 'working_sheet'
                                        ? 'badge-info'
                                        : 'badge-secondary';

                                    $attendanceSerialNumber = method_exists($attendanceRows, 'currentPage')
                                        ? (($attendanceRows->currentPage() - 1) * $attendanceRows->perPage() + $key + 1)
                                        : ($key + 1);
                                @endphp

                                <tr>

                                    {{-- SR. NO. --}}
                                    <td>{{ $attendanceSerialNumber }}</td>

                                    {{-- DRIVER --}}
                                    @if(!$isDriver)
                                        <td class="driver-name-cell">
                                            @if($attendance->driver)
                                                <strong class="text-dark">
                                                    {{ trim(
                                                        ($attendance->driver->first_name ?? '') . ' ' .
                                                        ($attendance->driver->last_name ?? '')
                                                    ) ?: '-' }}
                                                </strong>

                                                @if(!empty($attendance->driver->driver_code))
                                                    <small class="text-muted d-block driver-code">
                                                        {{ $attendance->driver->driver_code }}
                                                    </small>
                                                @endif
                                            @else
                                                <span class="text-muted">Driver Not Available</span>
                                            @endif
                                        </td>
                                    @endif

                                    {{-- ATTENDANCE DATE --}}
                                    <td>
                                        @if($attendance->attendance_date)
                                            <strong>
                                                {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}
                                            </strong>
                                            <small class="text-muted d-block">
                                                {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('l') }}
                                            </small>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- IN TIME --}}
                                    <td>
                                        @if($attendance->in_time)
                                            <span class="attendance-time">
                                                <i class="fa fa-sign-in text-success mr-1"></i>
                                                {{ \Carbon\Carbon::parse($attendance->in_time)->format('h:i A') }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- OUT TIME --}}
                                    <td>
                                        @if($attendance->out_time)
                                            <span class="attendance-time">
                                                <i class="fa fa-sign-out text-danger mr-1"></i>
                                                {{ \Carbon\Carbon::parse($attendance->out_time)->format('h:i A') }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- TOTAL HOURS --}}
                                    <td>
                                        @if($attendance->total_hours !== null)
                                            <span class="badge badge-info px-2 py-2">
                                                {{ number_format((float) $attendance->total_hours, 2) }} hrs
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- STATUS --}}
                                    <td>
                                        <span class="badge badge-{{ $attendanceMeta['badge'] }} badge-pill px-3 py-2">
                                            <i class="fa {{ $attendanceMeta['icon'] }} mr-1"></i>
                                            {{ $attendanceMeta['label'] }}
                                        </span>
                                    </td>

                                    {{-- SOURCE --}}
                                    <td>
                                        <span class="badge {{ $attendanceSourceClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $attendanceSource ?? 'Unknown')) }}
                                        </span>
                                    </td>

                                    {{-- CREATED BY --}}
                                    @if(!$isDriver)
                                        <td>
                                            @if($attendance->createdBy)
                                                <span class="text-dark">
                                                    {{ $attendance->createdBy->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>

                                        {{-- EDIT --}}
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

                                        {{-- DELETE --}}
                                        <td class="no-export">
                                            <form
                                                action="{{ route('driver-attendances.destroy', $attendance->id) }}"
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
                                    @endif

                                </tr>

                            @empty

                                <tr>
                                    <td
                                        colspan="{{ $isDriver ? 7 : 11 }}"
                                        class="text-center py-5"
                                    >
                                        <div class="attendance-empty-state">
                                            <i class="fa fa-calendar-times-o"></i>

                                            <h5>
                                                {{ $isDriver ? 'No Attendance Records Found' : 'No Driver Attendance Found' }}
                                            </h5>

                                            <p>
                                                @if($isDriver)
                                                    No attendance records are available for your account.
                                                @else
                                                    No attendance records match the selected filters.
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <x-backend.footer />

</div>

@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('backend/assets/datatable/js/datatable-init.js') }}"></script>

@if(!$isDriver)
<script>
    document.addEventListener('DOMContentLoaded', function () {

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
@endif

@endpush
