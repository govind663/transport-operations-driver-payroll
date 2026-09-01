@extends('backend.layouts.master')

@section('title')

    @if($isDriver)

        My Salary Slips

    @else

        Salary Slips

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

    .amount-text {
        font-weight: 600;
    }

    .filter-card {
        background: #f8f9fa;
        border-radius: 6px;
    }

    .salary-amount {
        font-weight: 600;
        white-space: nowrap;
    }

    .slip-number {
        font-weight: 600;
        letter-spacing: 0.3px;
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

                            My Salary Slips

                        @else

                            Salary Slips

                        @endif

                    </h4>

                    <p class="mb-0">

                        @if($isDriver)

                            View your salary slips, earnings,
                            deductions and payment details.

                        @else

                            Manage driver salary slips,
                            earnings, deductions and payments.

                        @endif

                    </p>

                </div>


                {{-- ================================================= --}}
                {{-- CREATE SALARY SLIP --}}
                {{-- ================================================= --}}

                @if(!$isDriver)

                    <div class="col-md-6 col-sm-12 text-right">

                        <a
                            href="{{ route('salary-slips.create') }}"
                            class="btn btn-primary"
                        >

                            <i class="fa fa-plus"></i>

                            Create Salary Slip

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

                    Salary Slip Filters

                </h4>


                <form
                    method="GET"
                    action="{{ route('salary-slips.index') }}"
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
                                placeholder="Slip no, driver name, code or mobile"
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

                                            {{ $driver->first_name }} {{ $driver->last_name }}

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
                        {{-- SALARY MONTH --}}
                        {{-- ================================================= --}}

                        <div class="col-md-2 col-sm-6 mb-20">

                            <label>

                                Month

                            </label>

                            <select
                                name="salary_month"
                                class="form-control custom-select2"
                            >

                                <option value="">

                                    All Months

                                </option>


                                @foreach(range(1, 12) as $month)

                                    <option
                                        value="{{ $month }}"
                                        {{ request('salary_month') == $month ? 'selected' : '' }}
                                    >

                                        {{ \Carbon\Carbon::create()->month($month)->format('F') }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- ================================================= --}}
                        {{-- SALARY YEAR --}}
                        {{-- ================================================= --}}

                        <div class="col-md-2 col-sm-6 mb-20">

                            <label>

                                Year

                            </label>

                            <select
                                name="salary_year"
                                class="form-control custom-select2"
                            >

                                <option value="">

                                    All Years

                                </option>


                                @php

                                    $currentYear = now()->year;

                                @endphp


                                @foreach(range($currentYear - 5, $currentYear + 1) as $year)

                                    <option
                                        value="{{ $year }}"
                                        {{ request('salary_year') == $year ? 'selected' : '' }}
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
                        {{-- PAYMENT STATUS --}}
                        {{-- ================================================= --}}

                        <div class="col-md-2 col-sm-6 mb-20">

                            <label>

                                Payment Status

                            </label>

                            <select
                                name="payment_status"
                                class="form-control custom-select2"
                            >

                                <option value="">

                                    All Payment Status

                                </option>


                                @foreach($paymentStatuses as $paymentStatus)

                                    <option
                                        value="{{ $paymentStatus }}"
                                        {{ request('payment_status') == $paymentStatus ? 'selected' : '' }}
                                    >

                                        {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- ================================================= --}}
                        {{-- PAYMENT DATE --}}
                        {{-- ================================================= --}}

                        <div class="col-md-2 col-sm-6 mb-20">

                            <label>

                                Payment Date

                            </label>

                            <input
                                type="date"
                                name="payment_date"
                                value="{{ request('payment_date') }}"
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
                                href="{{ route('salary-slips.index') }}"
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
        {{-- SALARY SLIP LIST CARD --}}
        {{-- ========================================================= --}}

        <div class="card-box mb-30">


            {{-- ===================================================== --}}
            {{-- CARD HEADER --}}
            {{-- ===================================================== --}}

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="text-blue h4 mb-0">

                        @if($isDriver)

                            My Salary Slips

                        @else

                            All Salary Slips

                        @endif

                    </h4>


                    <span class="badge badge-primary">

                        Total :

                        {{ $salarySlips->total() }}

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
                        data-title="Salary Slips"
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
                                    Slip No.
                                </th>


                                @if(!$isDriver)

                                    <th class="text-wrap">
                                        Driver
                                    </th>

                                @endif


                                <th class="text-wrap">
                                    Salary Period
                                </th>


                                <th class="text-wrap">
                                    Working Days
                                </th>


                                <th class="text-wrap">
                                    Present
                                </th>


                                <th class="text-wrap">
                                    Paid Days
                                </th>


                                <th class="text-wrap">
                                    Basic Salary
                                </th>


                                <th class="text-wrap">
                                    Gross Salary
                                </th>


                                <th class="text-wrap">
                                    Deductions
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


                                <th class="text-wrap">
                                    Generated By
                                </th>


                                <th class="text-wrap no-export">
                                    View
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

                            @forelse($salarySlips as $key => $salarySlip)

                                <tr>


                                    {{-- ===================================== --}}
                                    {{-- SR NO --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        {{ ($salarySlips->currentPage() - 1)
                                            * $salarySlips->perPage()
                                            + $key + 1 }}

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- SLIP NUMBER --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <strong class="slip-number text-dark">

                                            {{ $salarySlip->slip_no ?? '-' }}

                                        </strong>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- DRIVER --}}
                                    {{-- ===================================== --}}

                                    @if(!$isDriver)

                                        <td>

                                            @if($salarySlip->driver)

                                                <strong class="text-dark">

                                                    {{ $salarySlip->driver->name ?? '-' }}

                                                </strong>


                                                @if(!empty($salarySlip->driver->driver_code))

                                                    <small class="text-muted d-block mt-1">

                                                        <i class="fa fa-id-card"></i>

                                                        {{ $salarySlip->driver->driver_code }}

                                                    </small>

                                                @endif


                                                @if(!empty($salarySlip->driver->mobile))

                                                    <small class="text-muted d-block">

                                                        <i class="fa fa-phone"></i>

                                                        {{ $salarySlip->driver->mobile }}

                                                    </small>

                                                @endif

                                            @else

                                                -

                                            @endif

                                        </td>

                                    @endif


                                    {{-- ===================================== --}}
                                    {{-- SALARY PERIOD --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <strong>

                                            {{ $salarySlip->salary_period }}

                                        </strong>


                                        @if($salarySlip->period_from && $salarySlip->period_to)

                                            <small class="text-muted d-block mt-1">

                                                {{ $salarySlip->period_from->format('d-m-Y') }}

                                                -

                                                {{ $salarySlip->period_to->format('d-m-Y') }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- WORKING DAYS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        {{ number_format(
                                            (float) $salarySlip->total_working_days,
                                            2
                                        ) }}

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- PRESENT DAYS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="badge badge-success">

                                            {{ number_format(
                                                (float) $salarySlip->present_days,
                                                2
                                            ) }}

                                        </span>


                                        @if((float) $salarySlip->absent_days > 0)

                                            <small class="text-danger d-block mt-1">

                                                Absent:

                                                {{ number_format(
                                                    (float) $salarySlip->absent_days,
                                                    2
                                                ) }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- PAID DAYS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="badge badge-info">

                                            {{ number_format(
                                                (float) $salarySlip->paid_days,
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- BASIC SALARY --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="salary-amount">

                                            ₹{{ number_format(
                                                (float) $salarySlip->basic_salary,
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
                                                (float) $salarySlip->gross_salary,
                                                2
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- TOTAL DEDUCTIONS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="salary-amount text-danger">

                                            ₹{{ number_format(
                                                (float) $salarySlip->total_deductions,
                                                2
                                            ) }}

                                        </span>


                                        @if(
                                            (float) $salarySlip->advance_deduction > 0
                                            ||
                                            (float) $salarySlip->loan_deduction > 0
                                            ||
                                            (float) $salarySlip->penalty_deduction > 0
                                            ||
                                            (float) $salarySlip->other_deductions > 0
                                        )

                                            <small class="text-muted d-block mt-1">

                                                Deduction Details

                                            </small>

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- NET SALARY --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        <span class="salary-amount text-success">

                                            ₹{{ number_format(
                                                (float) $salarySlip->net_salary,
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
                                                $salarySlip->payment_status;

                                            $paymentStatusClass = match (
                                                $paymentStatus
                                            ) {

                                                'paid' =>
                                                    'badge-success',

                                                'partial' =>
                                                    'badge-warning',

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
                                                    $paymentStatus ?? 'Unknown'
                                                )
                                            ) }}

                                        </span>


                                        @if($salarySlip->payment_date)

                                            <small class="text-muted d-block mt-1">

                                                {{ $salarySlip->payment_date->format('d-m-Y') }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- STATUS --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @php

                                            $status =
                                                $salarySlip->status;

                                            $statusClass = match ($status) {

                                                'generated' =>
                                                    'badge-info',

                                                'issued' =>
                                                    'badge-primary',

                                                'cancelled' =>
                                                    'badge-danger',

                                                default =>
                                                    'badge-secondary',

                                            };


                                            $statusIcon = match ($status) {

                                                'generated' =>
                                                    'fa-file-text-o',

                                                'issued' =>
                                                    'fa-check-circle',

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
                                    {{-- GENERATED BY --}}
                                    {{-- ===================================== --}}

                                    <td>

                                        @if($salarySlip->generatedBy)

                                            {{ $salarySlip->generatedBy->name }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- VIEW --}}
                                    {{-- ===================================== --}}

                                    <td class="no-export">

                                        <a
                                            href="{{ route(
                                                'salary-slips.show',
                                                $salarySlip->id
                                            ) }}"
                                            class="btn btn-info btn-sm"
                                        >

                                            <i class="fa fa-eye"></i>

                                            View

                                        </a>

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- EDIT --}}
                                    {{-- ===================================== --}}

                                    <td class="no-export">

                                        @if(!$isDriver)

                                            <a
                                                href="{{ route(
                                                    'salary-slips.edit',
                                                    $salarySlip->id
                                                ) }}"
                                                class="btn btn-warning btn-sm"
                                            >

                                                <i class="dw dw-pencil-1"></i>

                                                Edit

                                            </a>

                                        @else

                                            -

                                        @endif

                                    </td>


                                    {{-- ===================================== --}}
                                    {{-- DELETE --}}
                                    {{-- ===================================== --}}

                                    <td class="no-export">

                                        @if(!$isDriver)

                                            <form
                                                action="{{ route(
                                                    'salary-slips.destroy',
                                                    $salarySlip->id
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

                                    <td
                                        colspan="{{ $isDriver ? 16 : 17 }}"
                                        class="text-center"
                                        style="vertical-align: middle;"
                                    >

                                        <div class="p-4">

                                            <i
                                                class="fa fa-money fa-2x text-muted mb-3"
                                            ></i>


                                            <h5 class="text-muted">

                                                @if($isDriver)

                                                    No Salary Slips Found

                                                @else

                                                    No Salary Slip Found

                                                @endif

                                            </h5>


                                            <p class="text-muted mb-0">

                                                No salary slips match
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

                @if($salarySlips->hasPages())

                    <div class="pd-20">

                        {{ $salarySlips->links() }}

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
    | Salary Slip Delete Confirmation
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.delete-form')
        .forEach(function (form) {

            form.addEventListener('submit', function (e) {

                e.preventDefault();


                Swal.fire({

                    title: 'Are you sure?',

                    text: 'This salary slip will be moved to trash.',

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