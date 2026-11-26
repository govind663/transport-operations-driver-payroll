
@extends('backend.layouts.master')

@section('title')
    {{ $isDriver ? 'My Salary Processing' : 'Salary Processing' }}
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

    .salary-period {
        font-weight: 600;
    }

    .processing-code {
        font-weight: 600;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .filter-card {
        background: #f8f9fa;
        border-radius: 6px;
    }

    .payroll-section-title {
        color: #023a85;
        font-weight: 600;
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

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">


        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="page-header">

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <h4 class="text-blue">

                        {{ $isDriver ? 'My Salary Processing' : 'Salary Processing' }}

                    </h4>

                    <p class="mb-0">

                        @if($isDriver)

                            View your salary processing records,
                            attendance and salary calculation details.

                        @else

                            Manage driver salary processing,
                            attendance and salary calculations.

                        @endif

                    </p>

                </div>

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

                    Salary Processing Filters

                </h4>

                <form
                    method="GET"
                    action="{{ route('salary-processing.index') }}"
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
                                placeholder="Processing no, driver name or code"
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

                                @foreach($months as $monthNumber => $monthName)

                                    <option
                                        value="{{ $monthNumber }}"
                                        {{ (string) request('salary_month', request('month')) === (string) $monthNumber ? 'selected' : '' }}
                                    >
                                        {{ $monthName }}
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

                                @for(
                                    $year = $currentYear - 5;
                                    $year <= $currentYear + 1;
                                    $year++
                                )

                                    <option
                                        value="{{ $year }}"
                                        {{ (string) request('salary_year', request('year')) === (string) $year ? 'selected' : '' }}
                                    >
                                        {{ $year }}
                                    </option>

                                @endfor

                            </select>

                        </div>


                        {{-- STATUS --}}

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
                                href="{{ route('salary-processing.index') }}"
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
        {{-- AUTOMATIC MONTHLY PAYROLL GENERATION --}}
        {{-- ========================================================= --}}

        @if($canGeneratePayroll)

            <div class="card-box mb-30">

                <div class="pd-20">

                    <h4 class="text-blue h4 mb-2">

                        <i class="fa fa-calculator"></i>

                        Automatic Monthly Payroll Generation

                    </h4>

                    <p class="text-muted mb-20">

                        Select salary month, year and optionally a driver.
                        Leave driver selection empty to generate payroll
                        for all eligible drivers.

                    </p>

                    <form
                        action="{{ route('salary-processing.generate-monthly') }}"
                        method="POST"
                        id="generateMonthlyPayrollForm"
                        class="form-horizontal"
                        style="border: 1px solid #023a85; padding: 20px; border-radius: 6px;"
                    >

                        @csrf

                        <div class="row">


                            {{-- SALARY MONTH --}}

                            <div class="col-md-3 col-sm-6 mb-20">

                                <label>
                                    Salary Month <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="salary_month"
                                    class="form-control custom-select2"
                                    required
                                >

                                    @foreach($months as $monthNumber => $monthName)

                                        <option
                                            value="{{ $monthNumber }}"
                                            {{ old('salary_month', $currentMonth) == $monthNumber ? 'selected' : '' }}
                                        >

                                            {{ $monthName }}

                                        </option>

                                    @endforeach

                                </select>

                                @error('salary_month')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>


                            {{-- SALARY YEAR --}}

                            <div class="col-md-3 col-sm-6 mb-20">

                                <label>
                                    Salary Year <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="salary_year"
                                    class="form-control custom-select2"
                                    required
                                >

                                    @for(
                                        $year = $currentYear - 5;
                                        $year <= $currentYear + 1;
                                        $year++
                                    )

                                        <option
                                            value="{{ $year }}"
                                            {{ old('salary_year', $currentYear) == $year ? 'selected' : '' }}
                                        >

                                            {{ $year }}

                                        </option>

                                    @endfor

                                </select>

                                @error('salary_year')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>


                            {{-- OPTIONAL DRIVER --}}

                            <div class="col-md-3 col-sm-6 mb-20">

                                <label>
                                    Driver (Optional)
                                </label>

                                <select
                                    name="driver_id"
                                    class="form-control custom-select2"
                                >

                                    <option value="">
                                        All Eligible Drivers
                                    </option>

                                    @foreach($drivers as $driver)

                                        <option
                                            value="{{ $driver->id }}"
                                            {{ (string) old('driver_id') === (string) $driver->id ? 'selected' : '' }}
                                        >

                                            {{ $driver->first_name }}
                                            {{ $driver->last_name }}

                                            @if(!empty($driver->driver_code))
                                                - {{ $driver->driver_code }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                @error('driver_id')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror

                            </div>


                            {{-- GENERATE BUTTON --}}

                            <div class="col-md-3 col-sm-6 mb-20 d-flex align-items-end">

                                <button
                                    type="submit"
                                    class="btn btn-success btn-block"
                                    id="generatePayrollButton"
                                >

                                    <i class="fa fa-cogs"></i>

                                    Generate Monthly Payroll

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        @endif


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
        {{-- PAYROLL GENERATION RESULT --}}
        {{-- ========================================================= --}}

        @if(session('payroll_generation_result'))

            @php
                $generationResult = session('payroll_generation_result');
            @endphp

            <div class="card-box mb-30">

                <div class="pd-20">

                    <h5 class="text-blue">
                        Payroll Generation Summary
                    </h5>

                    <div class="row">

                        <div class="col-md-6">

                            <div class="alert alert-success mb-2">

                                <strong>Generated:</strong>

                                {{ $generationResult['generated_count'] ?? 0 }}

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="alert alert-warning mb-2">

                                <strong>Skipped:</strong>

                                {{ $generationResult['skipped_count'] ?? 0 }}

                            </div>

                        </div>

                    </div>

                    @if(!empty($generationResult['skipped']))

                        <h6 class="mt-3">
                            Skipped Drivers
                        </h6>

                        <ul class="mb-0">

                            @foreach($generationResult['skipped'] as $skipped)

                                <li>

                                    @if(is_array($skipped))

                                        {{ $skipped['driver_name'] ?? ('Driver ID: ' . ($skipped['driver_id'] ?? '-')) }}

                                        -

                                        {{ $skipped['reason'] ?? 'Unknown reason' }}

                                    @else

                                        {{ $skipped }}

                                    @endif

                                </li>

                            @endforeach

                        </ul>

                    @endif

                </div>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- SALARY PROCESSING LIST --}}
        {{-- ========================================================= --}}

        <div class="card-box mb-30">

            {{-- CARD HEADER --}}

            <div class="pd-20">

                <div class="d-flex justify-content-between align-items-center flex-wrap">

                    <h4 class="text-blue h4 mb-0">

                        {{ $isDriver ? 'My Salary Processing' : 'All Salary Processing' }}

                    </h4>

                    <span class="badge badge-primary">

                        Total: {{ $payrolls->total() }}

                    </span>

                </div>

            </div>


            {{-- TABLE --}}

            <div class="pb-20">

                <div class="table-responsive">

                    <table
                        class="table hover multiple-select-row data-table-export1 nowrap p-3 payroll-table"
                        data-title="Salary Processing"
                    >

                        {{-- TABLE HEADER --}}

                        <thead>

                            <tr>

                                <th class="text-wrap">Sr. No.</th>

                                @if(!$isDriver)
                                    <th class="text-wrap">Driver</th>
                                @endif

                                <th class="text-wrap">Processing No.</th>

                                <th class="text-wrap">Salary Period</th>

                                <th class="text-wrap">Working Days</th>

                                <th class="text-wrap">Present Days</th>

                                <th class="text-wrap">Absent Days</th>

                                <th class="text-wrap">Paid Days</th>

                                <th class="text-wrap">Gross Salary (₹)</th>

                                <th class="text-wrap">Total Deductions (₹)</th>

                                <th class="text-wrap">Net Salary (₹)</th>

                                <th class="text-wrap">Status</th>

                                <th class="text-wrap">Processed By</th>

                                <th class="no-export">Edit</th>

                                <th class="no-export">Delete</th>

                            </tr>

                        </thead>


                        {{-- TABLE BODY --}}

                        <tbody>

                            @forelse($payrolls as $key => $processing)

                                @php

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Payroll Status
                                    |--------------------------------------------------------------------------
                                    */

                                    $status = $processing->status ?? 'draft';


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Locked Payroll
                                    |--------------------------------------------------------------------------
                                    */

                                    $isPayrollLocked = in_array(
                                        $status,
                                        [
                                            \App\Models\SalaryProcessing::STATUS_APPROVED,
                                            \App\Models\SalaryProcessing::STATUS_PAID,
                                            \App\Models\SalaryProcessing::STATUS_CANCELLED,
                                        ],
                                        true
                                    );


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Status Badge
                                    |--------------------------------------------------------------------------
                                    */

                                    $statusClass = match ($status) {

                                        'draft' => 'badge-secondary',

                                        'processed' => 'badge-info',

                                        'approved' => 'badge-primary',

                                        'paid' => 'badge-success',

                                        'cancelled' => 'badge-danger',

                                        default => 'badge-secondary',

                                    };


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Status Icon
                                    |--------------------------------------------------------------------------
                                    */

                                    $statusIcon = match ($status) {

                                        'draft' => 'fa-file-text-o',

                                        'processed' => 'fa-cogs',

                                        'approved' => 'fa-check-circle',

                                        'paid' => 'fa-money',

                                        'cancelled' => 'fa-times-circle',

                                        default => 'fa-info-circle',

                                    };


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Salary Period
                                    |--------------------------------------------------------------------------
                                    */

                                    $salaryPeriod = '-';

                                    if (
                                        !empty($processing->salary_month) &&
                                        !empty($processing->salary_year) &&
                                        (int) $processing->salary_month >= 1 &&
                                        (int) $processing->salary_month <= 12
                                    ) {

                                        $salaryPeriod = \Carbon\Carbon::create(
                                            (int) $processing->salary_year,
                                            (int) $processing->salary_month,
                                            1
                                        )->format('F Y');

                                    }


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Working Days
                                    |--------------------------------------------------------------------------
                                    */

                                    $workingDays =
                                        $processing->total_working_days ?? 0;


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Processing Number
                                    |--------------------------------------------------------------------------
                                    */

                                    $processingNumber =
                                        $processing->processing_no ?? null;

                                @endphp


                                <tr>

                                    {{-- ===================================== --}}
                                    {{-- SR NO --}}
                                    {{-- ===================================== --}}
                                    <td>

                                        {{
                                            ($payrolls->currentPage() - 1)
                                            * $payrolls->perPage()
                                            + $key
                                            + 1
                                        }}

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- DRIVER --}}
                                    {{-- ===================================== --}}
                                    @if(!$isDriver)

                                        <td>

                                            @if($processing->driver)

                                                <strong class="text-dark">

                                                    {{ $processing->driver->first_name ?? '-' }}

                                                    {{ $processing->driver->last_name ?? '' }}

                                                </strong>


                                                @if(!empty($processing->driver->driver_code))

                                                    <small class="text-muted d-block mt-1">

                                                        <i class="fa fa-id-card"></i>

                                                        {{ $processing->driver->driver_code }}

                                                    </small>

                                                @endif


                                                @if(!empty($processing->driver->mobile))

                                                    <small class="text-muted d-block">

                                                        <i class="fa fa-phone"></i>

                                                        {{ $processing->driver->mobile }}

                                                    </small>

                                                @endif

                                            @else

                                                <span class="text-muted">
                                                    Driver Not Found
                                                </span>

                                            @endif

                                        </td>

                                    @endif

                                    {{-- ===================================== --}}
                                    {{-- PROCESSING NO --}}
                                    {{-- ===================================== --}}
                                    <td>

                                        @if(!empty($processingNumber))

                                            <strong class="processing-code text-dark">

                                                {{ $processingNumber }}

                                            </strong>

                                        @else

                                            <span class="text-danger">

                                                Processing No Missing

                                            </span>

                                        @endif

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- SALARY PERIOD --}}
                                    {{-- ===================================== --}}
                                    <td>

                                        <strong class="salary-period">

                                            {{ $salaryPeriod }}

                                        </strong>

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- WORKING DAYS --}}
                                    {{-- ===================================== --}}
                                    <td>

                                        <span class="text-dark font-weight-bold">

                                            {{
                                                number_format(
                                                    (float) $workingDays,
                                                    2
                                                )
                                            }}

                                        </span>

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- PRESENT DAYS --}}
                                    {{-- ===================================== --}}
                                    <td>
                                        <span class="text-dark font-weight-bold">

                                            {{
                                                number_format(
                                                    (float) (
                                                        $processing->present_days ?? 0
                                                    ),
                                                    2
                                                )
                                            }}

                                        </span>

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- ABSENT DAYS --}}
                                    {{-- ===================================== --}}
                                    <td>
                                        <span class="text-dark font-weight-bold">

                                            {{
                                                number_format(
                                                    (float) (
                                                        $processing->absent_days ?? 0
                                                    ),
                                                    2
                                                )
                                            }}

                                        </span>

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- PAID DAYS --}}
                                    {{-- ===================================== --}}
                                    <td>

                                        <span class="text-dark font-weight-bold">

                                            {{
                                                number_format(
                                                    (float) (
                                                        $processing->paid_days ?? 0
                                                    ),
                                                    2
                                                )
                                            }}

                                        </span>

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- GROSS SALARY --}}
                                    {{-- ===================================== --}}
                                    <td>

                                        <span class="amount-text">

                                            {{
                                                number_format(
                                                    (float) (
                                                        $processing->gross_salary ?? 0
                                                    ),
                                                    2
                                                )
                                            }}

                                        </span>

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- TOTAL DEDUCTIONS --}}
                                    {{-- ===================================== --}}
                                    <td>

                                        <span class="amount-text text-danger">

                                            {{
                                                number_format(
                                                    (float) (
                                                        $processing->total_deductions ?? 0
                                                    ),
                                                    2
                                                )
                                            }}

                                        </span>

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- NET SALARY --}}
                                    {{-- ===================================== --}}
                                    <td>

                                        <strong class="amount-text text-success">

                                            {{
                                                number_format(
                                                    (float) (
                                                        $processing->net_salary ?? 0
                                                    ),
                                                    2
                                                )
                                            }}

                                        </strong>

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- STATUS --}}
                                    {{-- ===================================== --}}
                                    <td>

                                        <span class="badge {{ $statusClass }} badge-pill status-badge">

                                            <i class="fa {{ $statusIcon }}"></i>

                                            {{ ucfirst(str_replace('_', ' ', $status)) }}

                                        </span>

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- PROCESSED BY --}}
                                    {{-- ===================================== --}}
                                    <td>

                                        @if($processing->processedBy)

                                            {{ $processing->processedBy->name }}

                                        @elseif($processing->createdBy)

                                            {{ $processing->createdBy->name }}

                                        @else

                                            <span class="text-muted">-</span>

                                        @endif

                                    </td>

                                    {{-- ===================================== --}}
                                    {{-- EDIT --}}
                                    {{-- ===================================== --}}
                                    <td class="no-export">

                                        @if(!$isDriver)

                                            @if(!$isPayrollLocked)

                                                <a
                                                    href="{{ route(
                                                        'salary-processing.edit',
                                                        $processing->id
                                                    ) }}"
                                                    class="btn btn-warning btn-sm"
                                                    title="Edit Payroll"
                                                >

                                                    <i class="dw dw-pencil-1"></i>

                                                    Edit

                                                </a>

                                            @else

                                                <button
                                                    type="button"
                                                    class="btn btn-secondary btn-sm locked-action"
                                                    disabled
                                                    title="Approved, Paid or Cancelled payroll cannot be edited"
                                                >

                                                    <i class="fa fa-lock"></i>

                                                    Locked

                                                </button>

                                            @endif

                                        @else

                                            <a
                                                href="{{ route(
                                                    'salary-processing.show',
                                                    $processing->id
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

                                            @if($status !== \App\Models\SalaryProcessing::STATUS_PAID)

                                                <form
                                                    action="{{ route(
                                                        'salary-processing.destroy',
                                                        $processing->id
                                                    ) }}"
                                                    method="POST"
                                                    class="delete-form d-inline"
                                                    data-processing-no="{{ $processingNumber }}"
                                                >

                                                    @csrf

                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-danger btn-sm"
                                                        title="Delete Payroll"
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
                                                    title="Paid payroll cannot be deleted"
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

                                {{-- ========================================= --}}
                                {{-- NO DATA --}}
                                {{-- ========================================= --}}
                                <tr>

                                    <td
                                        colspan="{{ $isDriver ? 14 : 15 }}"
                                        class="text-center"
                                        style="vertical-align: middle;"
                                    >

                                        <div class="p-4">

                                            <i class="fa fa-calculator fa-2x text-muted mb-3"></i>

                                            <h5 class="text-muted">

                                                No Salary Processing Found

                                            </h5>

                                            <p class="text-muted mb-0">

                                                No salary processing records
                                                match the selected filters.

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
    | SALARY PROCESSING DELETE CONFIRMATION
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.delete-form')
        .forEach(function (form) {

            form.addEventListener('submit', function (e) {

                e.preventDefault();

                const processingNo =
                    form.dataset.processingNo || 'this payroll';

                Swal.fire({

                    title: 'Are you sure?',

                    text: 'Delete ' + processingNo + '? This record will be moved to trash.',

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
    | MONTHLY PAYROLL GENERATION CONFIRMATION
    |--------------------------------------------------------------------------
    */

    const generateForm = document.getElementById(
        'generateMonthlyPayrollForm'
    );

    if (generateForm) {

        generateForm.addEventListener('submit', function (e) {

            e.preventDefault();

            const monthSelect = generateForm.querySelector(
                '[name="salary_month"]'
            );

            const yearSelect = generateForm.querySelector(
                '[name="salary_year"]'
            );

            const driverSelect = generateForm.querySelector(
                '[name="driver_id"]'
            );

            const monthText =
                monthSelect.options[monthSelect.selectedIndex].text;

            const yearText = yearSelect.value;

            const driverText = driverSelect.value
                ? driverSelect.options[driverSelect.selectedIndex].text
                : 'All Eligible Drivers';

            Swal.fire({

                title: 'Generate Monthly Payroll?',

                html:
                    '<div class="text-left">' +
                    '<p><strong>Salary Month:</strong> ' + monthText + '</p>' +
                    '<p><strong>Salary Year:</strong> ' + yearText + '</p>' +
                    '<p><strong>Driver:</strong> ' + driverText + '</p>' +
                    '<hr>' +
                    '<p>Existing non-draft payroll records will not be overwritten.</p>' +
                    '</div>',

                icon: 'question',

                showCancelButton: true,

                confirmButtonColor: '#28a745',

                cancelButtonColor: '#6c757d',

                confirmButtonText: 'Yes, Generate',

                cancelButtonText: 'Cancel',

                reverseButtons: true

            }).then(function (result) {

                if (result.isConfirmed) {

                    const button = document.getElementById(
                        'generatePayrollButton'
                    );

                    if (button) {

                        button.disabled = true;

                        button.innerHTML =
                            '<i class="fa fa-spinner fa-spin"></i> Generating...';

                    }

                    generateForm.submit();

                }

            });

        });

    }

});


</script>


{{-- ========================================================= --}}
{{-- DATATABLE INITIALIZATION --}}
{{-- ========================================================= --}}

<script src="{{ asset('backend/assets/datatable/js/datatable-init.js') }}"></script>

@endpush
