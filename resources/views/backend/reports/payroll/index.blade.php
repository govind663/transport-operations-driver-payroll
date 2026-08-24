@extends('backend.layouts.master')

@section('title')

    Payroll Reports

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

    .salary-amount {
        font-weight: 600;
        white-space: nowrap;
    }

    .period-text {
        font-weight: 600;
        white-space: nowrap;
    }

    .date-text {
        white-space: nowrap;
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

                        Payroll Reports

                    </h4>

                    <p class="mb-0">

                        View driver salary processing,
                        allowances, deductions and net salary details.

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

                    Payroll Report Filters

                </h4>


                <form
                    method="GET"
                    action="{{ route('reports.payroll.index') }}"
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
                                placeholder="Driver name, code..."
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
                        {{-- MONTH --}}
                        {{-- ================================================= --}}

                        <div class="col-md-2 col-sm-6 mb-20">

                            <label>

                                Month

                            </label>

                            <select
                                name="month"
                                class="form-control custom-select2"
                            >

                                <option value="">

                                    All Months

                                </option>


                                @foreach(range(1, 12) as $month)

                                    <option
                                        value="{{ $month }}"
                                        {{ request('month') == $month ? 'selected' : '' }}
                                    >

                                        {{ \Carbon\Carbon::create()
                                            ->month($month)
                                            ->format('F') }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- ================================================= --}}
                        {{-- YEAR --}}
                        {{-- ================================================= --}}

                        <div class="col-md-2 col-sm-6 mb-20">

                            <label>

                                Year

                            </label>

                            <select
                                name="year"
                                class="form-control custom-select2"
                            >

                                <option value="">

                                    All Years

                                </option>


                                @php

                                    $currentYear = now()->year;

                                @endphp


                                @foreach(
                                    range(
                                        $currentYear - 5,
                                        $currentYear + 1
                                    ) as $year
                                )

                                    <option
                                        value="{{ $year }}"
                                        {{ request('year') == $year ? 'selected' : '' }}
                                    >

                                        {{ $year }}

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
                                href="{{ route('reports.payroll.index') }}"
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
        {{-- PAYROLL REPORT LIST CARD --}}
        {{-- ========================================================= --}}

        <div class="card-box mb-30">


            {{-- ===================================================== --}}
            {{-- CARD HEADER --}}
            {{-- ===================================================== --}}

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">

                        All Payroll Records

                    </h4>


                    <span class="badge badge-primary">

                        Total :

                        {{ $payrolls->total() }}

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
                        data-title="Payroll Reports"
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
                                    Payroll No.
                                </th>


                                <th class="text-wrap">
                                    Driver
                                </th>


                                <th class="text-wrap">
                                    Salary Period
                                </th>


                                <th class="text-wrap">
                                    Basic Salary
                                </th>


                                <th class="text-wrap">
                                    Allowances
                                </th>


                                <th class="text-wrap">
                                    Overtime
                                </th>


                                <th class="text-wrap">
                                    Bonus
                                </th>


                                <th class="text-wrap">
                                    Deductions
                                </th>


                                <th class="text-wrap">
                                    Gross Salary
                                </th>


                                <th class="text-wrap">
                                    Net Salary
                                </th>


                                <th class="text-wrap">
                                    Payment Status
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

                            @forelse($payrolls as $key => $payroll)

                                <tr>


                                    {{-- ===================================== --}}
                                    {{-- SR NO --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        {{ ($payrolls->currentPage() - 1)
                                            * $payrolls->perPage()
                                            + $key + 1 }}

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- PAYROLL NUMBER --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <strong class="text-dark">

                                            {{ $payroll->payroll_no
                                                ?? $payroll->salary_no
                                                ?? $payroll->id
                                                ?? '-' }}

                                        </strong>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- DRIVER --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($payroll->driver)

                                            <strong class="driver-name text-dark">

                                                {{ $payroll->driver->name ?? '-' }}

                                            </strong>


                                            @if(
                                                !empty(
                                                    $payroll->driver->driver_code
                                                )
                                            )

                                                <small class="text-muted d-block mt-1">

                                                    <i class="fa fa-id-card"></i>

                                                    {{ $payroll->driver->driver_code }}

                                                </small>

                                            @endif


                                            @if(
                                                !empty(
                                                    $payroll->driver->mobile
                                                )
                                            )

                                                <small class="text-muted d-block">

                                                    <i class="fa fa-phone"></i>

                                                    {{ $payroll->driver->mobile }}

                                                </small>

                                            @endif

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- SALARY PERIOD --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if(
                                            !empty($payroll->salary_period)
                                        )

                                            <strong class="period-text">

                                                {{ $payroll->salary_period }}

                                            </strong>

                                        @elseif(
                                            !empty($payroll->month)
                                            &&
                                            !empty($payroll->year)
                                        )

                                            <strong class="period-text">

                                                {{ \Carbon\Carbon::create()
                                                    ->month($payroll->month)
                                                    ->format('F') }}

                                                {{ $payroll->year }}

                                            </strong>

                                        @else

                                            -

                                        @endif


                                        @if(
                                            !empty($payroll->period_from)
                                            &&
                                            !empty($payroll->period_to)
                                        )

                                            <small class="text-muted d-block mt-1">

                                                {{ $payroll->period_from instanceof \Carbon\Carbon
                                                    ? $payroll->period_from->format('d-m-Y')
                                                    : \Carbon\Carbon::parse($payroll->period_from)->format('d-m-Y') }}

                                                -

                                                {{ $payroll->period_to instanceof \Carbon\Carbon
                                                    ? $payroll->period_to->format('d-m-Y')
                                                    : \Carbon\Carbon::parse($payroll->period_to)->format('d-m-Y') }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- BASIC SALARY --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="salary-amount">

                                            ₹{{ number_format(
                                                (float) (
                                                    $payroll->basic_salary
                                                    ?? 0
                                                ),
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- ALLOWANCES --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="salary-amount text-success">

                                            ₹{{ number_format(
                                                (float) (
                                                    $payroll->total_allowances
                                                    ?? $payroll->allowances
                                                    ?? 0
                                                ),
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- OVERTIME --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="salary-amount text-info">

                                            ₹{{ number_format(
                                                (float) (
                                                    $payroll->overtime_amount
                                                    ?? $payroll->overtime
                                                    ?? 0
                                                ),
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- BONUS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="salary-amount text-primary">

                                            ₹{{ number_format(
                                                (float) (
                                                    $payroll->bonus
                                                    ?? $payroll->bonus_amount
                                                    ?? 0
                                                ),
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- DEDUCTIONS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="salary-amount text-danger">

                                            ₹{{ number_format(
                                                (float) (
                                                    $payroll->total_deductions
                                                    ?? $payroll->deductions
                                                    ?? 0
                                                ),
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- GROSS SALARY --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="salary-amount text-primary">

                                            ₹{{ number_format(
                                                (float) (
                                                    $payroll->gross_salary
                                                    ?? 0
                                                ),
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- NET SALARY --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="salary-amount text-success">

                                            ₹{{ number_format(
                                                (float) (
                                                    $payroll->net_salary
                                                    ?? 0
                                                ),
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- PAYMENT STATUS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @php

                                            $paymentStatus =
                                                $payroll->payment_status
                                                ?? null;


                                            $paymentStatusClass = match (
                                                $paymentStatus
                                            ) {

                                                'paid' =>
                                                    'badge-success',

                                                'partial' =>
                                                    'badge-warning',

                                                'pending',
                                                'unpaid' =>
                                                    'badge-danger',

                                                default =>
                                                    'badge-secondary',

                                            };


                                            $paymentStatusIcon = match (
                                                $paymentStatus
                                            ) {

                                                'paid' =>
                                                    'fa-check-circle',

                                                'partial' =>
                                                    'fa-adjust',

                                                'pending',
                                                'unpaid' =>
                                                    'fa-clock-o',

                                                default =>
                                                    'fa-info-circle',

                                            };

                                        @endphp


                                        <span
                                            class="badge {{ $paymentStatusClass }} badge-pill px-3 py-2"
                                        >

                                            <i class="fa {{ $paymentStatusIcon }}"></i>

                                            {{ ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $paymentStatus
                                                    ?? 'Unknown'
                                                )
                                            ) }}

                                        </span>


                                        @if(
                                            !empty($payroll->payment_date)
                                        )

                                            <small class="text-muted d-block mt-1">

                                                {{ $payroll->payment_date instanceof \Carbon\Carbon
                                                    ? $payroll->payment_date->format('d-m-Y')
                                                    : \Carbon\Carbon::parse($payroll->payment_date)->format('d-m-Y') }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- STATUS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @php

                                            $status =
                                                $payroll->status
                                                ?? null;


                                            $statusClass = match ($status) {

                                                'processed',
                                                'completed' =>
                                                    'badge-success',

                                                'pending' =>
                                                    'badge-warning',

                                                'cancelled' =>
                                                    'badge-danger',

                                                'processing' =>
                                                    'badge-info',

                                                default =>
                                                    'badge-secondary',

                                            };


                                            $statusIcon = match ($status) {

                                                'processed',
                                                'completed' =>
                                                    'fa-check-circle',

                                                'pending' =>
                                                    'fa-clock-o',

                                                'cancelled' =>
                                                    'fa-times-circle',

                                                'processing' =>
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

                                        @if(
                                            Route::has('salary-processing.show')
                                        )

                                            <a
                                                href="{{ route(
                                                    'salary-processing.show',
                                                    $payroll->id
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
                                                class="fa fa-money fa-2x text-muted mb-3"
                                            ></i>


                                            <h5 class="text-muted">

                                                No Payroll Records Found

                                            </h5>


                                            <p class="text-muted mb-0">

                                                No payroll records match
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

                @if($payrolls->hasPages())

                    <div class="pd-20">

                        {{ $payrolls->links() }}

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