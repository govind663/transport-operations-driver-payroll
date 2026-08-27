@extends('backend.layouts.master')

@section('title')

    @if($isDriver)

        My Attendance

    @else

        Driver Attendance

    @endif

@endsection


@push('styles')

<link
    rel="stylesheet"
    href="{{ asset('backend/assets/datatable/css/dataTables-responsive.css') }}"
>

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

</style>

@endpush


@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

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
                    class="form-horizontal"
                    style="border: 1px solid #023a85; padding: 20px; border-radius: 6px;"
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

                                            {{ $driver->name }}

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

        {{-- ========================================================= --}}
        {{-- ATTENDANCE LIST CARD --}}
        {{-- ========================================================= --}}
        <div class="card-box mb-30">

            {{-- ===================================================== --}}
            {{-- CARD HEADER --}}
            {{-- ===================================================== --}}
            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

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

                <div class="table-responsive">

                    <table class="table hover multiple-select-row data-table-export1 nowrap p-3" data-title="Driver Attendance">

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
                                    Working Sheet
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

                                                    {{ $attendance->driver->name ?? '-' }}

                                                </strong>


                                                @if(!empty($attendance->driver->driver_code))

                                                    <small class="text-muted d-block mt-1">

                                                        <i class="fa fa-id-card"></i>

                                                        {{ $attendance->driver->driver_code }}

                                                    </small>

                                                @endif


                                                @if(!empty($attendance->driver->mobile))

                                                    <small class="text-muted d-block">

                                                        <i class="fa fa-phone"></i>

                                                        {{ $attendance->driver->mobile }}

                                                    </small>

                                                @endif

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
                                    {{-- WORKING SHEET --}}
                                    {{-- ===================================== --}}
                                    <td>

                                        @if($attendance->workingSheet)

                                            <strong class="text-dark">

                                                {{ $attendance->workingSheet->sheet_no ?? '-' }}

                                            </strong>


                                            @if($attendance->workingSheet->total_km !== null)

                                                <small class="text-muted d-block mt-1">

                                                    <i class="fa fa-road"></i>

                                                    {{ number_format(
                                                        (float) $attendance->workingSheet->total_km,
                                                        2
                                                    ) }}

                                                    KM

                                                </small>

                                            @endif

                                        @else

                                            <span class="text-muted">

                                                Manual

                                            </span>

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
                                                href="{{ route(
                                                    'driver-attendances.edit',
                                                    $attendance->id
                                                ) }}"
                                                class="btn btn-warning btn-sm"
                                            >

                                                <i class="dw dw-pencil-1"></i>

                                                Edit

                                            </a>

                                        @else

                                            <a
                                                href="{{ route(
                                                    'driver-attendances.show',
                                                    $attendance->id
                                                ) }}"
                                                class="btn btn-info btn-sm"
                                            >

                                                <i class="fa fa-eye"></i>

                                                View

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

                                    <td colspan="{{ $isDriver ? 10 : 12 }}" class="text-center" style="vertical-align: middle;">

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

    </div>


    {{-- ========================================================= --}}
    {{-- FOOTER --}}
    {{-- ========================================================= --}}

    <x-backend.footer />

</div>

@endsection



@push('scripts')

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

@endpush