@extends('backend.layouts.master')

@section('title')

    Driver Reports

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

    .driver-name {
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

                        Driver Reports

                    </h4>

                    <p class="mb-0">

                        View driver details, status,
                        joining and leaving information.

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

                    Driver Report Filters

                </h4>


                <form
                    method="GET"
                    action="{{ route('reports.drivers.index') }}"
                    class="form-horizontal"
                    style="border: 1px solid #023a85; padding: 20px; border-radius: 6px;"
                >

                    <div class="row">


                        {{-- ================================================= --}}
                        {{-- SEARCH --}}
                        {{-- ================================================= --}}

                        <div class="col-md-4 col-sm-6 mb-20">

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
                                href="{{ route('reports.drivers.index') }}"
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
        {{-- DRIVER REPORT LIST CARD --}}
        {{-- ========================================================= --}}

        <div class="card-box mb-30">


            {{-- ===================================================== --}}
            {{-- CARD HEADER --}}
            {{-- ===================================================== --}}

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">

                        All Drivers

                    </h4>


                    <span class="badge badge-primary">

                        Total :

                        {{ $drivers->total() }}

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
                        data-title="Driver Reports"
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
                                    Driver Code
                                </th>


                                <th class="text-wrap">
                                    Driver Name
                                </th>


                                <th class="text-wrap">
                                    Mobile
                                </th>


                                <th class="text-wrap">
                                    Email
                                </th>


                                <th class="text-wrap">
                                    Joining Date
                                </th>


                                <th class="text-wrap">
                                    Leaving Date
                                </th>


                                <th class="text-wrap">
                                    Status
                                </th>


                                <th class="text-wrap">
                                    Created At
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

                            @forelse($drivers as $key => $driver)

                                <tr>


                                    {{-- ===================================== --}}
                                    {{-- SR NO --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        {{ ($drivers->currentPage() - 1)
                                            * $drivers->perPage()
                                            + $key + 1 }}

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- DRIVER CODE --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <strong class="driver-code text-dark">

                                            {{ $driver->driver_code ?? '-' }}

                                        </strong>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- DRIVER NAME --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <strong class="driver-name text-dark">

                                            {{ $driver->first_name . ' ' . $driver->last_name ?? '-' }}

                                        </strong>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- MOBILE --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if(!empty($driver->mobile))

                                            <span>

                                                <i class="fa fa-phone text-muted"></i>

                                                {{ $driver->mobile }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- EMAIL --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if(!empty($driver->email))

                                            <span>

                                                <i class="fa fa-envelope text-muted"></i>

                                                {{ $driver->email }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- JOINING DATE --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($driver->joining_date)

                                            <span class="date-text">

                                                {{ $driver->joining_date->format('d-m-Y') }}

                                            </span>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- LEAVING DATE --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($driver->leaving_date)

                                            <span class="date-text">

                                                {{ $driver->leaving_date->format('d-m-Y') }}

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

                                            $status = $driver->status;

                                            $statusClass = match ($status) {

                                                'active' =>
                                                    'badge-success',

                                                'inactive' =>
                                                    'badge-danger',

                                                'on_leave' =>
                                                    'badge-warning',

                                                default =>
                                                    'badge-secondary',

                                            };


                                            $statusIcon = match ($status) {

                                                'active' =>
                                                    'fa-check-circle',

                                                'inactive' =>
                                                    'fa-times-circle',

                                                'on_leave' =>
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
                                    {{-- CREATED AT --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($driver->created_at)

                                            <span class="date-text">

                                                {{ $driver->created_at->format('d-m-Y') }}

                                            </span>


                                            <small class="text-muted d-block">

                                                {{ $driver->created_at->format('h:i A') }}

                                            </small>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- VIEW --}}
                                    {{-- ===================================== --}}

                                    <td class="no-export">

                                        @if(
                                            Route::has('driver-management.show')
                                        )

                                            <a
                                                href="{{ route(
                                                    'driver-management.show',
                                                    $driver->id
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
                                        colspan="10"
                                        class="text-center"
                                        style="vertical-align: middle;"
                                    >

                                        <div class="p-4">

                                            <i
                                                class="fa fa-users fa-2x text-muted mb-3"
                                            ></i>


                                            <h5 class="text-muted">

                                                No Drivers Found

                                            </h5>


                                            <p class="text-muted mb-0">

                                                No drivers match
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