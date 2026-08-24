@extends('backend.layouts.master')

@section('title')

    Working Sheet Reports

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

    .driver-name {
        font-weight: 600;
    }

    .vehicle-number {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .client-name {
        font-weight: 600;
    }

    .hours-text {
        font-weight: 600;
        white-space: nowrap;
    }

    .amount-text {
        font-weight: 600;
        white-space: nowrap;
    }

    .date-text {
        white-space: nowrap;
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

                        Working Sheet Reports

                    </h4>

                    <p class="mb-0">

                        View working sheet details,
                        driver, vehicle, client, duty hours,
                        extra hours, allowances and expenses.

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

                    Working Sheet Report Filters

                </h4>


                <form
                    method="GET"
                    action="{{ route('reports.working-sheets.index') }}"
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
                                placeholder="Driver, vehicle, client..."
                            >

                        </div>


                        {{-- ================================================= --}}
                        {{-- DRIVER --}}
                        {{-- ================================================= --}}

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


                                @foreach(
                                    ($filterOptions['drivers'] ?? []) as $driver
                                )

                                    <option
                                        value="{{ $driver->id }}"
                                        {{ request('driver_id') == $driver->id ? 'selected' : '' }}
                                    >

                                        {{ $driver->name ?? '-' }}

                                        @if(!empty($driver->driver_code))

                                            -
                                            {{ $driver->driver_code }}

                                        @endif

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- ================================================= --}}
                        {{-- VEHICLE --}}
                        {{-- ================================================= --}}

                        <div class="col-md-3 col-sm-6 mb-20">

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

                                        {{ $vehicle->registration_number
                                            ?? $vehicle->vehicle_number
                                            ?? $vehicle->registration_no
                                            ?? '-' }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- ================================================= --}}
                        {{-- CLIENT --}}
                        {{-- ================================================= --}}

                        <div class="col-md-3 col-sm-6 mb-20">

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

                                        {{ $client->company_name
                                            ?? $client->name
                                            ?? '-' }}

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
                        {{-- STATUS --}}
                        {{-- ================================================= --}}

                        <div class="col-md-3 col-sm-6 mb-20">

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
                                href="{{ route('reports.working-sheets.index') }}"
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
        {{-- WORKING SHEET REPORT LIST --}}
        {{-- ========================================================= --}}

        <div class="card-box mb-30">


            {{-- ===================================================== --}}
            {{-- CARD HEADER --}}
            {{-- ===================================================== --}}

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">

                        All Working Sheets

                    </h4>


                    <span class="badge badge-primary">

                        Total :

                        {{ $workingSheets->total() }}

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
                        data-title="Working Sheet Reports"
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
                                    Date
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
                                    Working Hours
                                </th>

                                <th class="text-wrap">
                                    Extra Hours
                                </th>

                                <th class="text-wrap">
                                    Allowance
                                </th>

                                <th class="text-wrap">
                                    Expenses
                                </th>

                                <th class="text-wrap">
                                    Total Amount
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

                            @forelse($workingSheets as $key => $workingSheet)

                                <tr>


                                    {{-- ===================================== --}}
                                    {{-- SR NO --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        {{ ($workingSheets->currentPage() - 1)
                                            * $workingSheets->perPage()
                                            + $key + 1 }}

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- DATE --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($workingSheet->date)

                                            <span class="date-text">

                                                {{ $workingSheet->date instanceof \Carbon\Carbon
                                                    ? $workingSheet->date->format('d-m-Y')
                                                    : \Carbon\Carbon::parse($workingSheet->date)->format('d-m-Y') }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- DRIVER --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($workingSheet->driver)

                                            <strong class="driver-name text-dark">

                                                {{ $workingSheet->driver->name ?? '-' }}

                                            </strong>


                                            @if(
                                                !empty(
                                                    $workingSheet->driver->driver_code
                                                )
                                            )

                                                <small class="text-muted d-block mt-1">

                                                    <i class="fa fa-id-card"></i>

                                                    {{ $workingSheet->driver->driver_code }}

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

                                        @if($workingSheet->vehicle)

                                            <strong class="vehicle-number text-dark">

                                                {{ $workingSheet->vehicle->registration_number
                                                    ?? $workingSheet->vehicle->vehicle_number
                                                    ?? $workingSheet->vehicle->registration_no
                                                    ?? '-' }}

                                            </strong>


                                            @if(
                                                !empty(
                                                    $workingSheet->vehicle->vehicle_name
                                                )
                                            )

                                                <small class="text-muted d-block mt-1">

                                                    {{ $workingSheet->vehicle->vehicle_name }}

                                                </small>

                                            @endif

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- CLIENT --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($workingSheet->client)

                                            <strong class="client-name text-dark">

                                                {{ $workingSheet->client->company_name
                                                    ?? $workingSheet->client->name
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

                                        @if(!empty($workingSheet->duty_start))

                                            <span class="date-text">

                                                {{ $workingSheet->duty_start }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- DUTY END --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if(!empty($workingSheet->duty_end))

                                            <span class="date-text">

                                                {{ $workingSheet->duty_end }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- WORKING HOURS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="hours-text text-primary">

                                            {{ $workingSheet->working_hours
                                                ?? $workingSheet->total_hours
                                                ?? '0.00' }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- EXTRA HOURS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="hours-text text-info">

                                            {{ $workingSheet->extra_hours
                                                ?? $workingSheet->overtime_hours
                                                ?? '0.00' }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- ALLOWANCE --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="amount-text text-success">

                                            ₹{{ number_format(
                                                (float) (
                                                    $workingSheet->allowance_amount
                                                    ?? $workingSheet->allowance
                                                    ?? 0
                                                ),
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- EXPENSES --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="amount-text text-danger">

                                            ₹{{ number_format(
                                                (float) (
                                                    $workingSheet->expense_amount
                                                    ?? $workingSheet->expenses
                                                    ?? 0
                                                ),
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- TOTAL AMOUNT --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="amount-text text-primary">

                                            ₹{{ number_format(
                                                (float) (
                                                    $workingSheet->total_amount
                                                    ?? $workingSheet->net_amount
                                                    ?? 0
                                                ),
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- STATUS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @php

                                            $status =
                                                $workingSheet->status
                                                ?? null;


                                            $statusClass = match (
                                                $status
                                            ) {

                                                'completed',
                                                'approved' =>
                                                    'badge-success',

                                                'pending' =>
                                                    'badge-warning',

                                                'processing' =>
                                                    'badge-info',

                                                'rejected',
                                                'cancelled' =>
                                                    'badge-danger',

                                                default =>
                                                    'badge-secondary',

                                            };


                                            $statusIcon = match (
                                                $status
                                            ) {

                                                'completed',
                                                'approved' =>
                                                    'fa-check-circle',

                                                'pending' =>
                                                    'fa-clock-o',

                                                'processing' =>
                                                    'fa-spinner',

                                                'rejected',
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
                                                    $status
                                                    ?? 'Unknown'
                                                )
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- VIEW --}}
                                    {{-- ===================================== --}}

                                    <td class="no-export">

                                        @if(Route::has('working-sheets.show'))

                                            <a
                                                href="{{ route(
                                                    'working-sheets.show',
                                                    $workingSheet->id
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
                                        colspan="14"
                                        class="text-center"
                                        style="vertical-align: middle;"
                                    >

                                        <div class="p-4">

                                            <i
                                                class="fa fa-file-text-o fa-2x text-muted mb-3"
                                            ></i>


                                            <h5 class="text-muted">

                                                No Working Sheets Found

                                            </h5>


                                            <p class="text-muted mb-0">

                                                No working sheets match
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

                @if($workingSheets->hasPages())

                    <div class="pd-20">

                        {{ $workingSheets->links() }}

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