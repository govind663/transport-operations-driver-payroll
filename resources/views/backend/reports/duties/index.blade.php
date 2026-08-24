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

                        View date-wise duty details,
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
                                value="{{ request('search') }}"
                                class="form-control"
                                placeholder="Duty no, driver, vehicle..."
                            >

                        </div>


                        {{-- ================================================= --}}
                        {{-- DRIVER --}}
                        {{-- ================================================= --}}

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


                                @foreach(
                                    ($filterOptions['drivers'] ?? []) as $driver
                                )

                                    <option
                                        value="{{ $driver->id }}"
                                        {{ request('driver_id') == $driver->id ? 'selected' : '' }}
                                    >

                                        {{ $driver->name ?? '-' }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


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


                                @foreach(
                                    ($filterOptions['vehicles'] ?? []) as $vehicle
                                )

                                    <option
                                        value="{{ $vehicle->id }}"
                                        {{ request('vehicle_id') == $vehicle->id ? 'selected' : '' }}
                                    >

                                        {{ $vehicle->registration_number ?? $vehicle->vehicle_number ?? $vehicle->name ?? '-' }}

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


                                @foreach(
                                    ($filterOptions['clients'] ?? []) as $client
                                )

                                    <option
                                        value="{{ $client->id }}"
                                        {{ request('client_id') == $client->id ? 'selected' : '' }}
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


                                @foreach(
                                    ($filterOptions['statuses'] ?? []) as $status
                                )

                                    <option
                                        value="{{ $status }}"
                                        {{ request('status') == $status ? 'selected' : '' }}
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
                                    Duty No.
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
                                    Duty Start
                                </th>


                                <th class="text-wrap">
                                    Duty End
                                </th>


                                <th class="text-wrap">
                                    Duty Hours
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
                                    {{-- DUTY NUMBER --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <strong class="duty-number text-dark">

                                            {{ $duty->duty_no
                                                ?? $duty->duty_number
                                                ?? '-' }}

                                        </strong>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- DUTY DATE --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($duty->duty_date)

                                            <span class="date-text">

                                                {{ $duty->duty_date->format('d-m-Y') }}

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

                                                {{ $duty->driver->name ?? '-' }}

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

                                                {{ $duty->vehicle->registration_number
                                                    ?? $duty->vehicle->vehicle_number
                                                    ?? $duty->vehicle->name
                                                    ?? '-' }}

                                            </strong>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- CLIENT --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($duty->client)

                                            <strong class="text-dark">

                                                {{ $duty->client->company_name
                                                    ?? $duty->client->name
                                                    ?? '-' }}

                                            </strong>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- DUTY START --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($duty->duty_start)

                                            <span class="time-text">

                                                {{ $duty->duty_start }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- DUTY END --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($duty->duty_end)

                                            <span class="time-text">

                                                {{ $duty->duty_end }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- DUTY HOURS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if(
                                            isset($duty->duty_hours)
                                            && $duty->duty_hours !== null
                                        )

                                            <span class="badge badge-info">

                                                {{ $duty->duty_hours }}

                                            </span>

                                        @elseif(
                                            isset($duty->total_hours)
                                            && $duty->total_hours !== null
                                        )

                                            <span class="badge badge-info">

                                                {{ $duty->total_hours }}

                                            </span>

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

                                                'pending' =>
                                                    'badge-warning',

                                                'cancelled' =>
                                                    'badge-danger',

                                                'in_progress' =>
                                                    'badge-info',

                                                default =>
                                                    'badge-secondary',

                                            };


                                            $statusIcon = match ($status) {

                                                'completed' =>
                                                    'fa-check-circle',

                                                'assigned' =>
                                                    'fa-user',

                                                'pending' =>
                                                    'fa-clock-o',

                                                'cancelled' =>
                                                    'fa-times-circle',

                                                'in_progress' =>
                                                    'fa-spinner',

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

                                        @if(
                                            Route::has('duty-assignments.show')
                                        )

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


                {{-- ===================================================== --}}
                {{-- PAGINATION --}}
                {{-- ===================================================== --}}

                @if($duties->hasPages())

                    <div class="pd-20">

                        {{ $duties->links() }}

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

<script src="{{ asset('backend/assets/datatable/js/datatable-init.js') }}"></script>

@endpush