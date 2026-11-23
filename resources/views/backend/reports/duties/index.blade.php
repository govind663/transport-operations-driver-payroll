
@extends('backend.layouts.master')

@section('title')

    Duty Reports

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

    .driver-code,
    .vehicle-number {
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .duty-number {
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .date-text {
        white-space: nowrap;
    }

    .time-text {
        white-space: nowrap;
        font-weight: 600;
    }

    .filter-card {
        background: #f8f9fa;
        border-radius: 6px;
    }

</style>

@endpush

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | Authenticated User
    |--------------------------------------------------------------------------
    */

    $authUser = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | Role Detection
    |--------------------------------------------------------------------------
    */

    $isDriver = $authUser && $authUser->isDriver();

    $canSelectDriver = $authUser && (
        $authUser->isAdmin()
        || $authUser->isOperations()
        || $authUser->isAccountant()
    );

    /*
    |--------------------------------------------------------------------------
    | Authenticated Driver Profile
    |--------------------------------------------------------------------------
    */

    $driverProfile = $isDriver
        ? $authUser->driver
        : null;

    /*
    |--------------------------------------------------------------------------
    | Filter Options
    |--------------------------------------------------------------------------
    */

    $drivers = $filterOptions['drivers'] ?? [];

    $vehicles = $filterOptions['vehicles'] ?? [];

    $clients = $filterOptions['clients'] ?? [];

    $statuses = $filterOptions['statuses'] ?? [];

@endphp

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

                        Duty Reports

                    </h4>

                    <p class="mb-0">

                        View date-wise duty assignment details,
                        driver, vehicle, client and duty status.

                    </p>

                </div>

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- FILTER CARD --}}
        {{-- ========================================================= --}}

        <div class="card-box mb-30">

            <div class="pd-20">

                <h4 class="text-blue h4 mb-20">

                    Duty Report Filters

                </h4>

                <form
                    method="GET"
                    action="{{ route('reports.duties.index') }}"
                    class="form-horizontal"
                    style="border: 1px solid #023a85; padding: 20px; border-radius: 6px;"
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
                                value="{{ $filters['search'] ?? '' }}"
                                class="form-control"
                                placeholder="Assignment no, driver, vehicle..."
                            >

                        </div>

                        {{-- ================================================= --}}
                        {{-- DRIVER FILTER --}}
                        {{-- ================================================= --}}

                        @if($canSelectDriver)

                            <div class="col-md-2 col-sm-6 mb-20">

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
                                            {{ (string) ($filters['driver_id'] ?? '') === (string) $driver->id ? 'selected' : '' }}
                                        >

                                            {{ $driver->first_name ?? '-' }}
                                            {{ $driver->last_name ?? '' }}

                                            @if(!empty($driver->driver_code))

                                                -
                                                {{ $driver->driver_code }}

                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        @elseif($isDriver)

                            <div class="col-md-2 col-sm-6 mb-20">

                                <label>

                                    Driver

                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $driverProfile
                                        ? trim(
                                            ($driverProfile->first_name ?? '') . ' ' .
                                            ($driverProfile->last_name ?? '')
                                        ) . (
                                            !empty($driverProfile->driver_code)
                                                ? ' - ' . $driverProfile->driver_code
                                                : ''
                                        )
                                        : 'Driver profile unavailable' }}"
                                    readonly
                                >

                            </div>

                        @endif

                        {{-- ================================================= --}}
                        {{-- VEHICLE --}}
                        {{-- ================================================= --}}

                        <div class="col-md-2 col-sm-6 mb-20">

                            <label>

                                Vehicle

                            </label>

                            <select
                                name="vehicle_id"
                                class="form-control custom-select2"
                            >

                                <option value="">

                                    All Vehicles

                                </option>

                                @foreach($vehicles as $vehicle)

                                    <option
                                        value="{{ $vehicle->id }}"
                                        {{ (string) ($filters['vehicle_id'] ?? '') === (string) $vehicle->id ? 'selected' : '' }}
                                    >

                                        {{ $vehicle->registration_number
                                            ?? $vehicle->vehicle_number
                                            ?? $vehicle->name
                                            ?? '-' }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- ================================================= --}}
                        {{-- CLIENT --}}
                        {{-- ================================================= --}}

                        <div class="col-md-2 col-sm-6 mb-20">

                            <label>

                                Client

                            </label>

                            <select
                                name="client_id"
                                class="form-control custom-select2"
                            >

                                <option value="">

                                    All Clients

                                </option>

                                @foreach($clients as $client)

                                    <option
                                        value="{{ $client->id }}"
                                        {{ (string) ($filters['client_id'] ?? '') === (string) $client->id ? 'selected' : '' }}
                                    >

                                        {{ $client->company_name ?? $client->name ?? '-' }}

                                    </option>

                                @endforeach

                            </select>

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
                                        {{ ($filters['status'] ?? '') === $status ? 'selected' : '' }}
                                    >

                                        {{ ucfirst(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $status
                                            )
                                        ) }}

                                    </option>

                                @endforeach

                            </select>

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
                                value="{{ $filters['date_from'] ?? '' }}"
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
                                value="{{ $filters['date_to'] ?? '' }}"
                                class="form-control"
                            >

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
                                href="{{ route('reports.duties.index') }}"
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
        {{-- DUTY REPORT LIST CARD --}}
        {{-- ========================================================= --}}

        <div class="card-box mb-30">

            {{-- ===================================================== --}}
            {{-- CARD HEADER --}}
            {{-- ===================================================== --}}

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">

                        All Duties

                    </h4>

                    <span class="badge badge-primary">

                        Total :

                        {{ $duties->total() }}

                    </span>

                </div>

            </div>

            {{-- ===================================================== --}}
            {{-- TABLE --}}
            {{-- ===================================================== --}}

            <div class="pb-20">

                <div class="table-responsive">

                    <table
                        class="table hover multiple-select-row data-table-export1 nowrap p-3"
                        data-title="Duty Reports"
                    >

                        {{-- ================================================= --}}
                        {{-- TABLE HEADER --}}
                        {{-- ================================================= --}}

                        <thead>

                            <tr>

                                <th class="text-wrap">
                                    Sr. No.
                                </th>

                                <th class="text-wrap">
                                    Assignment No.
                                </th>

                                <th class="text-wrap">
                                    Duty Date
                                </th>

                                <th class="text-wrap">
                                    Driver
                                </th>

                                <th class="text-wrap">
                                    Vehicle
                                </th>

                                <th class="text-wrap">
                                    Client
                                </th>

                                <th class="text-wrap">
                                    Assigned At
                                </th>

                                <th class="text-wrap">
                                    Reporting Time
                                </th>

                                <th class="text-wrap">
                                    Reporting Location
                                </th>

                                <th class="text-wrap">
                                    Status
                                </th>

                                <th class="text-wrap no-export">
                                    View
                                </th>

                            </tr>

                        </thead>

                        {{-- ================================================= --}}
                        {{-- TABLE BODY --}}
                        {{-- ================================================= --}}

                        <tbody>

                            @forelse($duties as $key => $duty)

                                @php

                                    $travelRequest = $duty->travelRequest;

                                    $travelFromDate = $travelRequest?->travel_from_date;

                                    $travelToDate = $travelRequest?->travel_to_date;

                                    $assignedAt = $duty->assigned_at;

                                    $reportingTime = $duty->reporting_time;

                                    $driverName = trim(
                                        ($duty->driver->first_name ?? '') . ' ' .
                                        ($duty->driver->last_name ?? '')
                                    );

                                    $vehicleNumber = $duty->vehicle->registration_number
                                        ?? $duty->vehicle->vehicle_number
                                        ?? $duty->vehicle->name
                                        ?? null;

                                    $client = $travelRequest?->client;

                                @endphp

                                <tr>

                                    {{-- ===================================== --}}
                                    {{-- SR NO --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        {{ ($duties->currentPage() - 1)
                                            * $duties->perPage()
                                            + $key + 1 }}

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- ASSIGNMENT NUMBER --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <strong class="duty-number text-dark">

                                            {{ $duty->assignment_no ?? '-' }}

                                        </strong>

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- DUTY DATE --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($travelFromDate)

                                            <span class="date-text">

                                                {{ \Illuminate\Support\Carbon::parse($travelFromDate)->format('d-m-Y') }}

                                                @if($travelToDate && \Illuminate\Support\Carbon::parse($travelFromDate)->toDateString() !== \Illuminate\Support\Carbon::parse($travelToDate)->toDateString())

                                                    -
                                                    {{ \Illuminate\Support\Carbon::parse($travelToDate)->format('d-m-Y') }}

                                                @endif

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- DRIVER --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($duty->driver)

                                            <strong class="text-dark">

                                                {{ $driverName !== '' ? $driverName : '-' }}

                                            </strong>

                                            @if(!empty($duty->driver->driver_code))

                                                <small class="text-muted d-block mt-1">

                                                    <i class="fa fa-id-card"></i>

                                                    {{ $duty->driver->driver_code }}

                                                </small>

                                            @endif

                                        @else

                                            -

                                        @endif

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- VEHICLE --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($duty->vehicle)

                                            <strong class="vehicle-number text-dark">

                                                {{ $vehicleNumber ?? '-' }}

                                            </strong>

                                        @else

                                            -

                                        @endif

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- CLIENT --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($client)

                                            <strong class="text-dark">

                                                {{ $client->company_name
                                                    ?? $client->name
                                                    ?? '-' }}

                                            </strong>

                                        @elseif($travelRequest && !empty($travelRequest->company_name))

                                            <strong class="text-dark">

                                                {{ $travelRequest->company_name }}

                                            </strong>

                                        @else

                                            -

                                        @endif

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- ASSIGNED AT --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($assignedAt)

                                            <span class="date-text">

                                                {{ \Illuminate\Support\Carbon::parse($assignedAt)->format('d-m-Y h:i A') }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- REPORTING TIME --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($reportingTime)

                                            <span class="time-text">

                                                {{ \Illuminate\Support\Carbon::parse($reportingTime)->format('h:i A') }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- REPORTING LOCATION --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if(!empty($duty->reporting_location))

                                            {{ $duty->reporting_location }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- STATUS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @php

                                            $status = $duty->status;

                                            $statusClass = match ($status) {

                                                'completed' =>
                                                    'badge-success',

                                                'assigned' =>
                                                    'badge-primary',

                                                'accepted' =>
                                                    'badge-info',

                                                'started' =>
                                                    'badge-info',

                                                'pending' =>
                                                    'badge-warning',

                                                'rejected' =>
                                                    'badge-danger',

                                                'cancelled' =>
                                                    'badge-danger',

                                                default =>
                                                    'badge-secondary',

                                            };

                                            $statusIcon = match ($status) {

                                                'completed' =>
                                                    'fa-check-circle',

                                                'assigned' =>
                                                    'fa-user',

                                                'accepted' =>
                                                    'fa-check',

                                                'started' =>
                                                    'fa-spinner',

                                                'pending' =>
                                                    'fa-clock-o',

                                                'rejected' =>
                                                    'fa-ban',

                                                'cancelled' =>
                                                    'fa-times-circle',

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
                                    {{-- VIEW --}}
                                    {{-- ===================================== --}}

                                    <td class="no-export">

                                        @if(Route::has('duty-assignments.show'))

                                            <a
                                                href="{{ route(
                                                    'duty-assignments.show',
                                                    $duty->id
                                                ) }}"
                                                class="btn btn-info btn-sm"
                                            >

                                                <i class="fa fa-eye"></i>

                                                View

                                            </a>

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

                                    <td
                                        colspan="11"
                                        class="text-center"
                                        style="vertical-align: middle;"
                                    >

                                        <div class="p-4">

                                            <i
                                                class="fa fa-car fa-2x text-muted mb-3"
                                            ></i>

                                            <h5 class="text-muted">

                                                No Duties Found

                                            </h5>

                                            <p class="text-muted mb-0">

                                                No duties match
                                                the selected filters.

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

    {{-- ========================================================= --}}
    {{-- FOOTER --}}
    {{-- ========================================================= --}}

    <x-backend.footer />

</div>

@endsection

@push('scripts')

<script src="{{ asset('backend/assets/datatable/js/datatable-init.js') }}"></script>

@endpush
