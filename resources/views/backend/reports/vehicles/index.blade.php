@extends('backend.layouts.master')

@section('title')

    Vehicle Reports

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

    .vehicle-number {
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .vehicle-name {
        font-weight: 600;
    }

    .filter-card {
        background: #f8f9fa;
        border-radius: 6px;
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

                        Vehicle Reports

                    </h4>

                    <p class="mb-0">

                        View vehicle details, category, type,
                        driver assignment, client assignment and status.

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

                    Vehicle Report Filters

                </h4>


                <form
                    method="GET"
                    action="{{ route('reports.vehicles.index') }}"
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
                                placeholder="Vehicle no, name, model..."
                            >

                        </div>


                        {{-- ================================================= --}}
                        {{-- VEHICLE CATEGORY --}}
                        {{-- ================================================= --}}

                        <div class="col-md-3 col-sm-6 mb-20">

                            <label>

                                Vehicle Category

                            </label>

                            <select
                                name="vehicle_category_id"
                                class="form-control custom-select2"
                            >

                                <option value="">

                                    All Categories

                                </option>


                                @foreach(
                                    ($filterOptions['categories'] ?? []) as $category
                                )

                                    <option
                                        value="{{ $category->id }}"
                                        {{ request('vehicle_category_id') == $category->id ? 'selected' : '' }}
                                    >

                                        {{ $category->name ?? '-' }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- ================================================= --}}
                        {{-- VEHICLE TYPE --}}
                        {{-- ================================================= --}}

                        <div class="col-md-3 col-sm-6 mb-20">

                            <label>

                                Vehicle Type

                            </label>

                            <select
                                name="vehicle_type_id"
                                class="form-control custom-select2"
                            >

                                <option value="">

                                    All Types

                                </option>


                                @foreach(
                                    ($filterOptions['types'] ?? []) as $type
                                )

                                    <option
                                        value="{{ $type->id }}"
                                        {{ request('vehicle_type_id') == $type->id ? 'selected' : '' }}
                                    >

                                        {{ $type->name ?? '-' }}

                                    </option>

                                @endforeach

                            </select>

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
                                href="{{ route('reports.vehicles.index') }}"
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
        {{-- VEHICLE REPORT LIST CARD --}}
        {{-- ========================================================= --}}

        <div class="card-box mb-30">


            {{-- ===================================================== --}}
            {{-- CARD HEADER --}}
            {{-- ===================================================== --}}

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">

                        All Vehicles

                    </h4>


                    <span class="badge badge-primary">

                        Total :

                        {{ $vehicles->total() }}

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
                        data-title="Vehicle Reports"
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
                                    Registration No.
                                </th>


                                <th class="text-wrap">
                                    Vehicle
                                </th>


                                <th class="text-wrap">
                                    Category
                                </th>


                                <th class="text-wrap">
                                    Type
                                </th>


                                <th class="text-wrap">
                                    Driver
                                </th>


                                <th class="text-wrap">
                                    Client
                                </th>


                                <th class="text-wrap">
                                    Model
                                </th>


                                <th class="text-wrap">
                                    Fuel Type
                                </th>


                                <th class="text-wrap">
                                    Status
                                </th>


                                <th class="text-wrap">
                                    Added Date
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

                            @forelse($vehicles as $key => $vehicle)

                                <tr>


                                    {{-- ===================================== --}}
                                    {{-- SR NO --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        {{ ($vehicles->currentPage() - 1)
                                            * $vehicles->perPage()
                                            + $key + 1 }}

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- REGISTRATION NUMBER --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <strong class="vehicle-number text-dark">

                                            {{ $vehicle->registration_number
                                                ?? $vehicle->vehicle_number
                                                ?? $vehicle->registration_no
                                                ?? '-' }}

                                        </strong>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- VEHICLE --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <strong class="vehicle-name text-dark">

                                            {{ $vehicle->vehicle_name
                                                ?? $vehicle->name
                                                ?? '-' }}

                                        </strong>


                                        @if(!empty($vehicle->vehicle_code))

                                            <small class="text-muted d-block mt-1">

                                                <i class="fa fa-id-card"></i>

                                                {{ $vehicle->vehicle_code }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- CATEGORY --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($vehicle->category)

                                            {{ $vehicle->category->name ?? '-' }}

                                        @elseif($vehicle->vehicleCategory)

                                            {{ $vehicle->vehicleCategory->name ?? '-' }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- TYPE --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($vehicle->type)

                                            {{ $vehicle->type->name ?? '-' }}

                                        @elseif($vehicle->vehicleType)

                                            {{ $vehicle->vehicleType->name ?? '-' }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- DRIVER --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($vehicle->driver)

                                            <strong class="text-dark">

                                                {{ $vehicle->driver->name ?? '-' }}

                                            </strong>


                                            @if(
                                                !empty(
                                                    $vehicle->driver->driver_code
                                                )
                                            )

                                                <small class="text-muted d-block mt-1">

                                                    <i class="fa fa-id-card"></i>

                                                    {{ $vehicle->driver->driver_code }}

                                                </small>

                                            @endif

                                        @else

                                            <span class="text-muted">

                                                Not Assigned

                                            </span>

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- CLIENT --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($vehicle->client)

                                            {{ $vehicle->client->company_name
                                                ?? $vehicle->client->name
                                                ?? '-' }}

                                        @else

                                            <span class="text-muted">

                                                Not Assigned

                                            </span>

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- MODEL --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        {{ $vehicle->model
                                            ?? $vehicle->vehicle_model
                                            ?? '-' }}

                                        @if(!empty($vehicle->model_year))

                                            <small class="text-muted d-block">

                                                {{ $vehicle->model_year }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- FUEL TYPE --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if(!empty($vehicle->fuel_type))

                                            {{ ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $vehicle->fuel_type
                                                )
                                            ) }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- STATUS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @php

                                            $status =
                                                $vehicle->status
                                                ?? null;

                                            $statusClass = match (
                                                $status
                                            ) {

                                                'active',
                                                'available' =>
                                                    'badge-success',

                                                'inactive' =>
                                                    'badge-secondary',

                                                'assigned' =>
                                                    'badge-primary',

                                                'maintenance' =>
                                                    'badge-warning',

                                                'sold',
                                                'disposed' =>
                                                    'badge-danger',

                                                default =>
                                                    'badge-secondary',

                                            };


                                            $statusIcon = match (
                                                $status
                                            ) {

                                                'active',
                                                'available' =>
                                                    'fa-check-circle',

                                                'inactive' =>
                                                    'fa-ban',

                                                'assigned' =>
                                                    'fa-user',

                                                'maintenance' =>
                                                    'fa-wrench',

                                                'sold',
                                                'disposed' =>
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
                                    {{-- ADDED DATE --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($vehicle->created_at)

                                            <span class="date-text">

                                                {{ $vehicle->created_at->format(
                                                    'd-m-Y'
                                                ) }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- VIEW --}}
                                    {{-- ===================================== --}}

                                    <td class="no-export">

                                        @if(Route::has('vehicle-management.show'))

                                            <a
                                                href="{{ route(
                                                    'vehicle-management.show',
                                                    $vehicle->id
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
                                        colspan="12"
                                        class="text-center"
                                        style="vertical-align: middle;"
                                    >

                                        <div class="p-4">

                                            <i
                                                class="fa fa-car fa-2x text-muted mb-3"
                                            ></i>


                                            <h5 class="text-muted">

                                                No Vehicles Found

                                            </h5>


                                            <p class="text-muted mb-0">

                                                No vehicles match
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