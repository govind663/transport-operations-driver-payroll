@extends('backend.layouts.master')

@section('title')
    {{ $isDriver ? 'My Salary Slips' : 'Salary Slips' }}
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

    .amount-text,
    .salary-amount {
        font-weight: 600;
        white-space: nowrap;
    }

    .salary-period {
        font-weight: 600;
    }

    .slip-number {
        font-weight: 600;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .filter-card {
        background: #f8f9fa;
        border-radius: 6px;
    }

    .payroll-table th {
        white-space: nowrap;
    }

    .payroll-table td {
        vertical-align: middle;
    }

    .status-badge {
        min-width: 100px;
        display: inline-block;
        text-align: center;
        padding: 7px 10px;
    }

    .locked-action {
        cursor: not-allowed;
        opacity: 0.65;
    }
</style>

@endpush


@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Safe List Data
    |--------------------------------------------------------------------------
    |
    | The controller/service returns an Eloquent Collection (no pagination).
    | Defaults prevent an undefined-variable error if the view is rendered
    | from another controller path during testing or customization.
    |
    */

    $salarySlips = $salarySlips ?? collect();
    $salaryProcessings = $salaryProcessings ?? collect();
    $drivers = $drivers ?? collect();
    $statuses = $statuses ?? [];
    $paymentStatuses = $paymentStatuses ?? [];

    $salarySlipCount = $salarySlips->count();

    $salarySlipFiltersApplied = collect([
        request('search'),
        request('driver_id'),
        request('salary_month'),
        request('salary_year'),
        request('status'),
        request('payment_status'),
        request('payment_date'),
    ])->contains(function ($value) {
        return $value !== null && trim((string) $value) !== '';
    });
@endphp

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">


        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="page-header">

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <h4 class="text-blue">

                        {{ $isDriver ? 'My Salary Slips' : 'Salary Slips' }}

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
        {{-- VALIDATION ERRORS --}}
        {{-- ========================================================= --}}

        @if($errors->any())

            <div class="alert alert-danger alert-dismissible fade show">

                <strong>Please fix the following errors:</strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

                <button
                    type="button"
                    class="close"
                    data-dismiss="alert"
                    aria-label="Close"
                >
                    <span>&times;</span>
                </button>

            </div>

        @endif


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


                        {{-- SEARCH --}}

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


                        {{-- DRIVER --}}

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
                                            {{ (string) request('driver_id') === (string) $driver->id ? 'selected' : '' }}
                                        >

                                            {{ $driver->first_name }}
                                            {{ $driver->last_name }}

                                            @if(!empty($driver->driver_code))
                                                - {{ $driver->driver_code }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        @endif


                        {{-- SALARY MONTH --}}

                        <div class="col-md-2 col-sm-6 mb-20">

                            <label>
                                Salary Month
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
                                        {{ (string) request('salary_month') === (string) $month ? 'selected' : '' }}
                                    >
                                        {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- SALARY YEAR --}}

                        <div class="col-md-2 col-sm-6 mb-20">

                            <label>
                                Salary Year
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

                                @for(
                                    $year = $currentYear - 5;
                                    $year <= $currentYear + 1;
                                    $year++
                                )

                                    <option
                                        value="{{ $year }}"
                                        {{ (string) request('salary_year') === (string) $year ? 'selected' : '' }}
                                    >
                                        {{ $year }}
                                    </option>

                                @endfor

                            </select>

                        </div>


                        {{-- SALARY SLIP STATUS --}}

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
                                        {{ request('status') === $status ? 'selected' : '' }}
                                    >
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- PAYMENT STATUS --}}

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
                                        {{ request('payment_status') === $paymentStatus ? 'selected' : '' }}
                                    >
                                        {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- PAYMENT DATE --}}

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


                        {{-- FILTER BUTTONS --}}

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
        {{-- SESSION SUCCESS / ERROR ALERTS --}}
        {{-- ========================================================= --}}

        @if(session('message'))

            <div class="alert alert-success alert-dismissible fade show">

                <i class="fa fa-check-circle"></i>

                {{ session('message') }}

                <button
                    type="button"
                    class="close"
                    data-dismiss="alert"
                    aria-label="Close"
                >
                    <span>&times;</span>
                </button>

            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                <i class="fa fa-exclamation-circle"></i>

                {{ session('error') }}

                <button
                    type="button"
                    class="close"
                    data-dismiss="alert"
                    aria-label="Close"
                >
                    <span>&times;</span>
                </button>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- LIST DATA STATUS --}}
        {{-- ========================================================= --}}

        @if($salarySlipCount === 0)

            <div class="alert alert-info alert-dismissible fade show">

                <strong>
                    <i class="fa fa-info-circle"></i>
                    No Salary Slip records to display.
                </strong>

                @if($salarySlipFiltersApplied)

                    <p class="mb-0 mt-2">
                        No active Salary Slip matches the selected filters.
                        Use the Reset button to display all available Salary Slips.
                    </p>

                @elseif(!$isDriver && isset($activeSalarySlipCount) && $activeSalarySlipCount > 0)

                    <p class="mb-0 mt-2">
                        {{ number_format($activeSalarySlipCount) }} active Salary Slip record(s)
                        exist, but the list query returned no rows. Please verify the
                        selected user's access and the Salary Slip service query.
                    </p>

                @elseif(!$isDriver && isset($trashedSalarySlipCount) && $trashedSalarySlipCount > 0)

                    <p class="mb-0 mt-2">
                        No active Salary Slip records were found. There are
                        {{ number_format($trashedSalarySlipCount) }} soft-deleted record(s)
                        in the trash; deleted records are intentionally excluded from this list.
                    </p>

                @else

                    <p class="mb-0 mt-2">
                        No active Salary Slips are available for this account.
                        Approved or paid payroll records without a Salary Slip can be
                        recovered from the Missing Salary Slip Generation section.
                    </p>

                @endif

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- MISSING SALARY SLIP RECOVERY --}}
        {{-- ========================================================= --}}

        @if(
            !$isDriver &&
            ($isAdmin || $isOperations || $isAccountant)
        )

            <div class="card-box mb-30">

                <div class="pd-20">

                    <div class="d-flex justify-content-between align-items-center flex-wrap">

                        <div>

                            <h4 class="text-blue h4 mb-1">

                                Missing Salary Slip Generation

                            </h4>

                            <p class="text-muted mb-0">

                                Generate a missing Salary Slip from an approved or paid Salary Processing record.
                                Existing Salary Slips are not duplicated.

                            </p>

                        </div>

                        <span class="badge badge-warning">

                            Pending: {{ $salaryProcessings->count() }}

                        </span>

                    </div>

                </div>

                <div class="pb-20">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover payroll-table mb-0">

                            <thead>

                                <tr>

                                    <th>Sr. No.</th>

                                    <th>Processing No.</th>

                                    <th>Driver</th>

                                    <th>Salary Period</th>

                                    <th>Gross Salary (₹)</th>

                                    <th>Total Deductions (₹)</th>

                                    <th>Net Salary (₹)</th>

                                    <th>Status</th>

                                    <th class="no-export">Action</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($salaryProcessings as $processingKey => $processing)

                                    @php

                                        $processingStatus = $processing->status ?? 'approved';

                                        $processingStatusClass = match ($processingStatus) {
                                            'approved' => 'badge-primary',
                                            'paid' => 'badge-success',
                                            default => 'badge-secondary',
                                        };

                                        $processingPeriod = '-';

                                        if (
                                            !empty($processing->salary_month) &&
                                            !empty($processing->salary_year) &&
                                            (int) $processing->salary_month >= 1 &&
                                            (int) $processing->salary_month <= 12
                                        ) {
                                            $processingPeriod = \Carbon\Carbon::create(
                                                (int) $processing->salary_year,
                                                (int) $processing->salary_month,
                                                1
                                            )->format('F Y');
                                        }

                                        $processingDriverName = '-';

                                        if ($processing->driver) {
                                            $processingDriverName = trim(
                                                ($processing->driver->first_name ?? '') . ' ' .
                                                ($processing->driver->last_name ?? '')
                                            );

                                            if ($processingDriverName === '') {
                                                $processingDriverName = $processing->driver->name ?? '-';
                                            }
                                        }

                                    @endphp

                                    <tr>

                                        <td>{{ $processingKey + 1 }}</td>

                                        <td>

                                            <strong class="processing-code">
                                                {{ $processing->processing_no ?? ('#' . $processing->id) }}
                                            </strong>

                                        </td>

                                        <td>

                                            <strong class="text-dark">
                                                {{ $processingDriverName }}
                                            </strong>

                                            @if(!empty($processing->driver->driver_code))
                                                <small class="text-muted d-block mt-1">
                                                    <i class="fa fa-id-card"></i>
                                                    {{ $processing->driver->driver_code }}
                                                </small>
                                            @endif

                                        </td>

                                        <td>
                                            <strong class="salary-period">
                                                {{ $processingPeriod }}
                                            </strong>
                                        </td>

                                        <td>
                                            <span class="salary-amount text-primary">
                                                {{ number_format((float) ($processing->gross_salary ?? 0), 2) }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="salary-amount text-danger">
                                                {{ number_format((float) ($processing->total_deductions ?? 0), 2) }}
                                            </span>
                                        </td>

                                        <td>
                                            <strong class="salary-amount text-success">
                                                {{ number_format((float) ($processing->net_salary ?? 0), 2) }}
                                            </strong>
                                        </td>

                                        <td>
                                            <span class="badge {{ $processingStatusClass }} badge-pill status-badge">
                                                <i class="fa {{ $processingStatus === 'paid' ? 'fa-money' : 'fa-check-circle' }}"></i>
                                                {{ ucfirst(str_replace('_', ' ', $processingStatus)) }}
                                            </span>
                                        </td>

                                        <td class="no-export">

                                            <form
                                                action="{{ route('salary-slips.generate-missing', $processing->id) }}"
                                                method="POST"
                                                class="generate-missing-slip-form d-inline"
                                                data-processing-no="{{ $processing->processing_no ?? ('#' . $processing->id) }}"
                                                data-driver-name="{{ $processingDriverName }}"
                                                data-salary-period="{{ $processingPeriod }}"
                                                data-processing-status="{{ ucfirst($processingStatus) }}"
                                            >

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="btn btn-success btn-sm generate-missing-slip-button"
                                                >
                                                    <i class="fa fa-file-text-o"></i>
                                                    Generate Slip
                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="9" class="text-center">

                                            <div class="p-4">

                                                <i class="fa fa-check-circle fa-2x text-success mb-3"></i>

                                                <h5 class="text-muted">
                                                    No Missing Salary Slips
                                                </h5>

                                                <p class="text-muted mb-0">
                                                    No eligible approved or paid payroll records are currently missing a Salary Slip.
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

        @endif


        {{-- ========================================================= --}}
        {{-- SALARY SLIP LIST --}}
        {{-- ========================================================= --}}

        <div class="card-box mb-30">

            {{-- CARD HEADER --}}

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center flex-wrap">

                    <h4 class="text-blue h4 mb-0">

                        {{ $isDriver ? 'My Salary Slips' : 'All Salary Slips' }}

                    </h4>

                    <span class="badge badge-primary">

                        Total: {{ $salarySlipCount }}

                    </span>

                </div>

            </div>


            {{-- TABLE --}}

            <div class="pb-20">

                <div class="table-responsive">

                    <table
                        id="salary-slips-table"
                        class="table hover multiple-select-row data-table-export1 nowrap p-3 payroll-table"
                        data-title="Salary Slips"
                        data-record-count="{{ $salarySlipCount }}"
                    >

                        {{-- TABLE HEADER --}}

                        <thead>

                            <tr>

                                <th class="text-wrap">Sr. No.</th>

                                <th class="text-wrap">Slip No.</th>

                                @if(!$isDriver)
                                    <th class="text-wrap">Driver</th>
                                @endif

                                <th class="text-wrap">Salary Period</th>

                                <th class="text-wrap">Working Days</th>

                                <th class="text-wrap">Present Days</th>

                                <th class="text-wrap">Paid Days</th>

                                <th class="text-wrap">Basic Salary (₹)</th>

                                <th class="text-wrap">Gross Salary (₹)</th>

                                <th class="text-wrap">Total Deductions (₹)</th>

                                <th class="text-wrap">Net Salary (₹)</th>

                                <th class="text-wrap">Payment Status</th>

                                <th class="text-wrap">Status</th>

                                <th class="text-wrap">Generated By</th>

                                <th class="no-export">View</th>

                                <th class="no-export">Edit</th>

                                <th class="no-export">Delete</th>

                            </tr>

                        </thead>


                        {{-- TABLE BODY --}}

                        <tbody>

                            @forelse($salarySlips as $key => $salarySlip)

                                @php

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Salary Slip Status
                                    |--------------------------------------------------------------------------
                                    */

                                    $status = $salarySlip->status ?? 'generated';


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Payment Status
                                    |--------------------------------------------------------------------------
                                    */

                                    $paymentStatus = $salarySlip->payment_status ?? 'unpaid';


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Payroll-Generated Slip
                                    |--------------------------------------------------------------------------
                                    */

                                    $isPayrollGenerated = !empty(
                                        $salarySlip->salary_processing_id
                                    );


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Edit / Delete Restrictions
                                    |--------------------------------------------------------------------------
                                    */

                                    $isPaidSlip =
                                        $salarySlip->isPaid() ||
                                        $salarySlip->isPartiallyPaid() ||
                                        $salarySlip->isStatusPaid();

                                    $isEditLocked =
                                        $isPayrollGenerated ||
                                        $salarySlip->isCancelled() ||
                                        $salarySlip->isIssued() ||
                                        $isPaidSlip;

                                    $isDeleteLocked =
                                        $isPayrollGenerated ||
                                        $salarySlip->isIssued() ||
                                        $isPaidSlip;


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Edit Lock Reason
                                    |--------------------------------------------------------------------------
                                    */

                                    if ($isPayrollGenerated) {
                                        $editLockReason = 'Payroll-generated Salary Slips are managed through Salary Processing.';
                                    } elseif ($salarySlip->isCancelled()) {
                                        $editLockReason = 'Cancelled Salary Slips cannot be edited.';
                                    } elseif ($salarySlip->isIssued()) {
                                        $editLockReason = 'Issued Salary Slips cannot be edited.';
                                    } elseif ($isPaidSlip) {
                                        $editLockReason = 'Paid or partially paid Salary Slips cannot be edited.';
                                    } else {
                                        $editLockReason = 'This Salary Slip cannot be edited.';
                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Delete Lock Reason
                                    |--------------------------------------------------------------------------
                                    */

                                    if ($isPayrollGenerated) {
                                        $deleteLockReason = 'Payroll-generated Salary Slips cannot be deleted from this module.';
                                    } elseif ($salarySlip->isIssued()) {
                                        $deleteLockReason = 'Issued Salary Slips cannot be deleted.';
                                    } elseif ($isPaidSlip) {
                                        $deleteLockReason = 'Paid or partially paid Salary Slips cannot be deleted.';
                                    } else {
                                        $deleteLockReason = 'This Salary Slip cannot be deleted.';
                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Status Badge
                                    |--------------------------------------------------------------------------
                                    */

                                    $statusClass = match ($status) {
                                        'generated' => 'badge-info',
                                        'issued' => 'badge-primary',
                                        'cancelled' => 'badge-danger',
                                        default => 'badge-secondary',
                                    };

                                    $statusIcon = match ($status) {
                                        'generated' => 'fa-file-text-o',
                                        'issued' => 'fa-check-circle',
                                        'cancelled' => 'fa-times-circle',
                                        default => 'fa-info-circle',
                                    };


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Payment Status Badge
                                    |--------------------------------------------------------------------------
                                    */

                                    $paymentStatusClass = match ($paymentStatus) {
                                        'paid' => 'badge-success',
                                        'partial' => 'badge-warning',
                                        'unpaid' => 'badge-danger',
                                        default => 'badge-secondary',
                                    };

                                    $paymentStatusIcon = match ($paymentStatus) {
                                        'paid' => 'fa-check-circle',
                                        'partial' => 'fa-adjust',
                                        'unpaid' => 'fa-clock-o',
                                        default => 'fa-info-circle',
                                    };


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Driver Display Name
                                    |--------------------------------------------------------------------------
                                    */

                                    $driverName = '-';

                                    if ($salarySlip->driver) {
                                        $driverName = $salarySlip->driver->name
                                            ?? trim(
                                                ($salarySlip->driver->first_name ?? '') . ' ' .
                                                ($salarySlip->driver->last_name ?? '')
                                            );

                                        if ($driverName === '') {
                                            $driverName = '-';
                                        }
                                    }

                                @endphp


                                <tr>

                                    {{-- SR NO --}}

                                    <td>
                                        {{ $key + 1 }}
                                    </td>


                                    {{-- SLIP NUMBER --}}

                                    <td>

                                        <strong class="slip-number text-dark">
                                            {{ $salarySlip->slip_no ?? '-' }}
                                        </strong>

                                    </td>


                                    {{-- DRIVER --}}

                                    @if(!$isDriver)

                                        <td>

                                            @if($salarySlip->driver)

                                                <strong class="text-dark">
                                                    {{ $driverName }}
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

                                                <span class="text-muted">Driver Not Found</span>

                                            @endif

                                        </td>

                                    @endif


                                    {{-- SALARY PERIOD --}}

                                    <td>

                                        <strong class="salary-period">
                                            {{ $salarySlip->salary_period ?? '-' }}
                                        </strong>

                                        @if($salarySlip->period_from && $salarySlip->period_to)
                                            <small class="text-muted d-block mt-1">
                                                {{ $salarySlip->period_from->format('d-m-Y') }}
                                                -
                                                {{ $salarySlip->period_to->format('d-m-Y') }}
                                            </small>
                                        @endif

                                    </td>


                                    {{-- WORKING DAYS --}}

                                    <td>
                                        <span class="amount-text">
                                            {{ number_format((float) ($salarySlip->total_working_days ?? 0), 2) }}
                                        </span>
                                    </td>


                                    {{-- PRESENT DAYS --}}

                                    <td>

                                        <span class="badge badge-success">
                                            {{ number_format((float) ($salarySlip->present_days ?? 0), 2) }}
                                        </span>

                                        @if((float) ($salarySlip->absent_days ?? 0) > 0)
                                            <small class="text-danger d-block mt-1">
                                                Absent:
                                                {{ number_format((float) $salarySlip->absent_days, 2) }}
                                            </small>
                                        @endif

                                    </td>


                                    {{-- PAID DAYS --}}

                                    <td>
                                        <span class="badge badge-info">
                                            {{ number_format((float) ($salarySlip->paid_days ?? 0), 2) }}
                                        </span>
                                    </td>


                                    {{-- BASIC SALARY --}}

                                    <td>
                                        <span class="salary-amount">
                                            {{ number_format((float) ($salarySlip->basic_salary ?? 0), 2) }}
                                        </span>
                                    </td>


                                    {{-- GROSS SALARY --}}

                                    <td>
                                        <span class="salary-amount text-primary">
                                            {{ number_format((float) ($salarySlip->gross_salary ?? 0), 2) }}
                                        </span>
                                    </td>


                                    {{-- TOTAL DEDUCTIONS --}}

                                    <td>

                                        <span class="salary-amount text-danger">
                                            {{ number_format((float) ($salarySlip->total_deductions ?? 0), 2) }}
                                        </span>

                                        @if(
                                            (float) ($salarySlip->advance_deduction ?? 0) > 0 ||
                                            (float) ($salarySlip->loan_deduction ?? 0) > 0 ||
                                            (float) ($salarySlip->penalty_deduction ?? 0) > 0 ||
                                            (float) ($salarySlip->other_deductions ?? 0) > 0
                                        )
                                            <small class="text-muted d-block mt-1">
                                                Deduction Details
                                            </small>
                                        @endif

                                    </td>


                                    {{-- NET SALARY --}}

                                    <td>
                                        <strong class="salary-amount text-success">
                                            {{ number_format((float) ($salarySlip->net_salary ?? 0), 2) }}
                                        </strong>
                                    </td>


                                    {{-- PAYMENT STATUS --}}

                                    <td>

                                        <span class="badge {{ $paymentStatusClass }} badge-pill status-badge">
                                            <i class="fa {{ $paymentStatusIcon }}"></i>
                                            {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}
                                        </span>

                                        @if($salarySlip->payment_date)
                                            <small class="text-muted d-block mt-1">
                                                {{ $salarySlip->payment_date->format('d-m-Y') }}
                                            </small>
                                        @endif

                                    </td>


                                    {{-- STATUS --}}

                                    <td>

                                        <span class="badge {{ $statusClass }} badge-pill status-badge">
                                            <i class="fa {{ $statusIcon }}"></i>
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </span>

                                    </td>


                                    {{-- GENERATED BY --}}

                                    <td>

                                        @if($salarySlip->generatedBy)
                                            {{ $salarySlip->generatedBy->name }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif

                                    </td>


                                    {{-- VIEW --}}

                                    <td class="no-export">

                                        <a
                                            href="{{ route('salary-slips.show', $salarySlip->id) }}"
                                            class="btn btn-info btn-sm"
                                            title="View Salary Slip"
                                        >
                                            <i class="fa fa-eye"></i>
                                            View
                                        </a>

                                    </td>


                                    {{-- EDIT --}}

                                    <td class="no-export">

                                        @if(!$isDriver)

                                            @if(!$isEditLocked)

                                                <a
                                                    href="{{ route('salary-slips.edit', $salarySlip->id) }}"
                                                    class="btn btn-warning btn-sm"
                                                    title="Edit Salary Slip"
                                                >
                                                    <i class="dw dw-pencil-1"></i>
                                                    Edit
                                                </a>

                                            @else

                                                <button
                                                    type="button"
                                                    class="btn btn-secondary btn-sm locked-action"
                                                    disabled
                                                    title="{{ $editLockReason }}"
                                                >
                                                    <i class="fa fa-lock"></i>
                                                    Locked
                                                </button>

                                            @endif

                                        @else

                                            <span class="text-muted">-</span>

                                        @endif

                                    </td>


                                    {{-- DELETE --}}

                                    <td class="no-export">

                                        @if(!$isDriver)

                                            @if(!$isDeleteLocked)

                                                <form
                                                    action="{{ route('salary-slips.destroy', $salarySlip->id) }}"
                                                    method="POST"
                                                    class="delete-form d-inline"
                                                    data-slip-no="{{ $salarySlip->slip_no ?? 'this salary slip' }}"
                                                >

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-danger btn-sm"
                                                        title="Delete Salary Slip"
                                                    >
                                                        <i class="dw dw-trash"></i>
                                                        Delete
                                                    </button>

                                                </form>

                                            @else

                                                <button
                                                    type="button"
                                                    class="btn btn-secondary btn-sm locked-action"
                                                    disabled
                                                    title="{{ $deleteLockReason }}"
                                                >
                                                    <i class="fa fa-lock"></i>
                                                    Locked
                                                </button>

                                            @endif

                                        @else

                                            <span class="text-muted">-</span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                {{-- NO DATA --}}

                                <tr>

                                    <td
                                        colspan="{{ $isDriver ? 16 : 17 }}"
                                        class="text-center"
                                        style="vertical-align: middle;"
                                    >

                                        <div class="p-4">

                                            <i class="fa fa-money fa-2x text-muted mb-3"></i>

                                            <h5 class="text-muted">
                                                {{ $isDriver ? 'No Salary Slips Found' : 'No Salary Slip Found' }}
                                            </h5>

                                            <p class="text-muted mb-0">
                                                No salary slips match the selected filters.
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | SALARY SLIP DELETE CONFIRMATION
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.delete-form')
        .forEach(function (form) {

            form.addEventListener('submit', function (e) {

                e.preventDefault();

                const slipNo = form.dataset.slipNo || 'this salary slip';

                Swal.fire({

                    title: 'Are you sure?',

                    text: 'Delete ' + slipNo + '? This record will be moved to trash.',

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


    /*
    |--------------------------------------------------------------------------
    | MISSING SALARY SLIP GENERATION CONFIRMATION
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.generate-missing-slip-form')
        .forEach(function (form) {

            form.addEventListener('submit', function (e) {

                e.preventDefault();

                const processingNo = form.dataset.processingNo || 'this payroll';
                const driverName = form.dataset.driverName || 'Selected Driver';
                const salaryPeriod = form.dataset.salaryPeriod || 'Selected Period';
                const processingStatus = form.dataset.processingStatus || 'Approved';

                Swal.fire({

                    title: 'Generate Missing Salary Slip?',

                    html:
                        '<div class="text-left">' +
                        '<p><strong>Processing No.:</strong> ' + processingNo + '</p>' +
                        '<p><strong>Driver:</strong> ' + driverName + '</p>' +
                        '<p><strong>Salary Period:</strong> ' + salaryPeriod + '</p>' +
                        '<p><strong>Payroll Status:</strong> ' + processingStatus + '</p>' +
                        '<hr>' +
                        '<p class="mb-0">The Salary Slip will be generated using the approved payroll snapshot. An existing Salary Slip will not be duplicated.</p>' +
                        '</div>',

                    icon: 'question',

                    showCancelButton: true,

                    confirmButtonColor: '#28a745',

                    cancelButtonColor: '#6c757d',

                    confirmButtonText: 'Yes, Generate Slip',

                    cancelButtonText: 'Cancel',

                    reverseButtons: true

                }).then(function (result) {

                    if (result.isConfirmed) {

                        const button = form.querySelector(
                            '.generate-missing-slip-button'
                        );

                        if (button) {

                            button.disabled = true;

                            button.innerHTML =
                                '<i class="fa fa-spinner fa-spin"></i> Generating...';

                        }

                        form.submit();

                    }

                });

            });

        });

});

</script>


{{-- ========================================================= --}}
{{-- DATATABLE INITIALIZATION --}}
{{-- ========================================================= --}}

<script src="{{ asset('backend/assets/datatable/js/datatable-init.js') }}"></script>

@endpush
