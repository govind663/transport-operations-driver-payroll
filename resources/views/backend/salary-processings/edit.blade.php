
@extends('backend.layouts.master')

@section('title')
Edit Salary Processing
@endsection

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | SALARY PERIOD
    |--------------------------------------------------------------------------
    */

    $salaryMonth = (int) $salaryProcessing->salary_month;

    $salaryYear = (int) $salaryProcessing->salary_year;

    $calendarDays = \Carbon\Carbon::create(
        $salaryYear,
        $salaryMonth,
        1
    )->daysInMonth;


    /*
    |--------------------------------------------------------------------------
    | PAYROLL LOCK CHECK
    |--------------------------------------------------------------------------
    */

    $isLocked = in_array(
        $salaryProcessing->status,
        [
            \App\Models\SalaryProcessing::STATUS_APPROVED,
            \App\Models\SalaryProcessing::STATUS_PAID,
            \App\Models\SalaryProcessing::STATUS_CANCELLED,
        ],
        true
    );


    /*
    |--------------------------------------------------------------------------
    | DRIVER MONTHLY SALARY
    |--------------------------------------------------------------------------
    */

    $monthlyGrossSalary = (float) (
        $salaryProcessing->driver?->monthly_gross_salary ?? 0
    );


    /*
    |--------------------------------------------------------------------------
    | EXISTING PAYROLL VALUES
    |--------------------------------------------------------------------------
    */

    $storedBasicSalary = (float) (
        $salaryProcessing->basic_salary ?? 0
    );

    $storedGrossSalary = (float) (
        $salaryProcessing->gross_salary ?? 0
    );

    $storedTotalDeductions = (float) (
        $salaryProcessing->total_deductions ?? 0
    );

    $storedNetSalary = (float) (
        $salaryProcessing->net_salary ?? 0
    );


    /*
    |--------------------------------------------------------------------------
    | CURRENT PAID DAYS
    |--------------------------------------------------------------------------
    */

    $paidDays = (float) old(
        'paid_days',
        $salaryProcessing->paid_days ?? 0
    );


    /*
    |--------------------------------------------------------------------------
    | CALCULATE BASIC SALARY PREVIEW
    |--------------------------------------------------------------------------
    |
    | Formula:
    |
    | Monthly Gross Salary / Calendar Days * Paid Days
    |
    | Locked payroll always displays its saved salary snapshot.
    |
    */

    if ($isLocked) {

        $previewBasicSalary = $storedBasicSalary;

        $previewGrossSalary = $storedGrossSalary;

        $previewNetSalary = $storedNetSalary;

    } elseif ($monthlyGrossSalary > 0) {

        $calculationPaidDays = min(
            max($paidDays, 0),
            (float) $calendarDays
        );

        $dailySalary = $monthlyGrossSalary / $calendarDays;

        $previewBasicSalary = round(
            $dailySalary * $calculationPaidDays,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Adjust Existing Gross Salary
        |--------------------------------------------------------------------------
        |
        | Replace the previously saved Basic Salary with the new
        | prorated Basic Salary while keeping other saved earnings.
        |
        */

        $previewGrossSalary = round(
            max(
                0,
                $storedGrossSalary
                - $storedBasicSalary
                + $previewBasicSalary
            ),
            2
        );

        $previewNetSalary = round(
            max(
                0,
                $previewGrossSalary - $storedTotalDeductions
            ),
            2
        );

    } else {

        /*
        |--------------------------------------------------------------------------
        | Missing Monthly Salary Fallback
        |--------------------------------------------------------------------------
        */

        $previewBasicSalary = $storedBasicSalary;

        $previewGrossSalary = $storedGrossSalary;

        $previewNetSalary = $storedNetSalary;
    }

@endphp


<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">


        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="page-header">

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <div class="title">

                        <h4>Salary Processing</h4>

                    </div>

                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item">

                                <a href="{{ route('admin.dashboard') }}">
                                    Dashboard
                                </a>

                            </li>

                            <li class="breadcrumb-item">

                                <a href="{{ route('salary-processing.index') }}">
                                    Salary Processing
                                </a>

                            </li>

                            <li class="breadcrumb-item active">

                                Edit Salary Processing

                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- ALERT MESSAGES --}}
        {{-- ========================================================= --}}

        @if ($errors->any())

            <div class="alert alert-danger alert-dismissible fade show">

                <strong>Please fix the following errors:</strong>

                <ul class="mb-0 mt-2">

                    @foreach ($errors->all() as $error)

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


        @if (session('message'))

            <div class="alert alert-success alert-dismissible fade show">

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


        @if (session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

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
        {{-- PAYROLL LOCK CHECK --}}
        {{-- ========================================================= --}}

        @if ($isLocked)

            <div class="alert alert-warning">

                <i class="fa fa-lock"></i>

                <strong>Payroll Locked!</strong>

                Approved, paid or cancelled payroll cannot be edited.

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- SALARY CALCULATION INFORMATION --}}
        {{-- ========================================================= --}}

        @if (!$isLocked)

            @if ($monthlyGrossSalary > 0)

                <div class="alert alert-info">

                    <h6 class="mb-2">

                        <i class="fa fa-calculator"></i>

                        Calendar Days Based Salary Calculation

                    </h6>

                    <p class="mb-1">

                        Monthly Gross Salary:

                        <strong>

                            ₹{{ number_format($monthlyGrossSalary, 2) }}

                        </strong>

                    </p>

                    <p class="mb-1">

                        Calendar Days in {{ date('F', mktime(0, 0, 0, $salaryMonth, 1)) }}:

                        <strong>
                            {{ $calendarDays }}
                        </strong>

                    </p>

                    <p class="mb-0">

                        Basic Salary = Monthly Gross Salary / Calendar Days
                        × Paid Days.

                    </p>

                    <small class="d-block mt-2">

                        Basic Salary will be calculated automatically.
                        Working Sheet earnings, bonus and other earnings
                        will be added separately.

                    </small>

                </div>

            @else

                <div class="alert alert-danger">

                    <i class="fa fa-exclamation-triangle"></i>

                    Driver's Monthly Gross Salary is missing or zero.
                    Please verify the Driver record before updating payroll.

                </div>

            @endif

        @endif


        {{-- ========================================================= --}}
        {{-- EDIT FORM --}}
        {{-- ========================================================= --}}

        <form
            action="{{ route('salary-processing.update', $salaryProcessing->id) }}"
            method="POST"
            id="salaryProcessingEditForm"
        >

            @csrf

            @method('PUT')


            <div class="card-box pd-20 mb-30">


                {{-- ================================================= --}}
                {{-- DRIVER & SALARY PERIOD --}}
                {{-- ================================================= --}}

                <div class="mb-4">

                    <h5 class="text-primary" style="color:#023a85 !important;">

                        <b>Driver & Salary Period</b>

                    </h5>

                    <hr>

                </div>


                <div class="row">


                    {{-- DRIVER --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Driver</b>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $salaryProcessing->driver
                                    ? trim(
                                        ($salaryProcessing->driver->driver_code ?? '')
                                        . ' - '
                                        . ($salaryProcessing->driver->first_name ?? '')
                                        . ' '
                                        . ($salaryProcessing->driver->last_name ?? '')
                                    )
                                    : 'Driver Not Found'
                                }}"
                                readonly
                            >

                        </div>

                    </div>


                    {{-- SALARY MONTH --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Salary Month</b>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ date('F', mktime(0, 0, 0, $salaryMonth, 1)) }}"
                                readonly
                            >

                        </div>

                    </div>


                    {{-- SALARY YEAR --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Salary Year</b>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $salaryYear }}"
                                readonly
                            >

                        </div>

                    </div>


                    {{-- PROCESSING NUMBER --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Processing Number</b>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $salaryProcessing->processing_no ?? '-' }}"
                                readonly
                            >

                        </div>

                    </div>


                    {{-- PAYROLL STATUS --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Payroll Status</b>
                            </label>

                            @if ($isLocked)

                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ ucfirst(str_replace('_', ' ', $salaryProcessing->status)) }}"
                                    readonly
                                >

                            @else

                                <select
                                    name="status"
                                    id="status"
                                    class="form-control custom-select2 @error('status') is-invalid @enderror"
                                    required
                                >

                                    <option
                                        value="draft"
                                        {{ old('status', $salaryProcessing->status) === 'draft' ? 'selected' : '' }}
                                    >
                                        Draft
                                    </option>

                                    <option
                                        value="processed"
                                        {{ old('status', $salaryProcessing->status) === 'processed' ? 'selected' : '' }}
                                    >
                                        Processed
                                    </option>

                                    <option
                                        value="approved"
                                        {{ old('status', $salaryProcessing->status) === 'approved' ? 'selected' : '' }}
                                    >
                                        Approved
                                    </option>

                                    <option
                                        value="cancelled"
                                        {{ old('status', $salaryProcessing->status) === 'cancelled' ? 'selected' : '' }}
                                    >
                                        Cancelled
                                    </option>

                                </select>

                                @error('status')

                                    <span class="invalid-feedback d-block">

                                        <strong>{{ $message }}</strong>

                                    </span>

                                @enderror

                            @endif

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- ATTENDANCE & WORKING --}}
                    {{-- ================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="text-primary" style="color:#023a85 !important;">

                            <b>Attendance & Working</b>

                        </h5>

                        <hr>

                    </div>


                    {{-- TOTAL WORKING DAYS --}}

                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>Total Working Days</b>
                            </label>

                            <input
                                type="number"
                                name="total_working_days"
                                id="total_working_days"
                                min="0"
                                step="0.01"
                                class="form-control @error('total_working_days') is-invalid @enderror"
                                value="{{ old('total_working_days', $salaryProcessing->total_working_days) }}"
                                {{ $isLocked ? 'readonly' : '' }}
                            >

                            @error('total_working_days')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- PRESENT DAYS --}}

                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>Present Days</b>
                            </label>

                            <input
                                type="number"
                                name="present_days"
                                id="present_days"
                                min="0"
                                step="0.01"
                                class="form-control @error('present_days') is-invalid @enderror"
                                value="{{ old('present_days', $salaryProcessing->present_days) }}"
                                {{ $isLocked ? 'readonly' : '' }}
                            >

                            @error('present_days')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ABSENT DAYS --}}

                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>Absent Days</b>
                            </label>

                            <input
                                type="number"
                                name="absent_days"
                                id="absent_days"
                                min="0"
                                step="0.01"
                                class="form-control @error('absent_days') is-invalid @enderror"
                                value="{{ old('absent_days', $salaryProcessing->absent_days) }}"
                                {{ $isLocked ? 'readonly' : '' }}
                            >

                            @error('absent_days')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- PAID DAYS --}}

                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>Paid Days</b>

                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="number"
                                name="paid_days"
                                id="paid_days"
                                min="0"
                                max="{{ $calendarDays }}"
                                step="0.01"
                                class="form-control @error('paid_days') is-invalid @enderror"
                                value="{{ old('paid_days', $salaryProcessing->paid_days) }}"
                                data-monthly-salary="{{ $monthlyGrossSalary }}"
                                data-calendar-days="{{ $calendarDays }}"
                                data-stored-basic="{{ $storedBasicSalary }}"
                                data-stored-gross="{{ $storedGrossSalary }}"
                                data-total-deductions="{{ $storedTotalDeductions }}"
                                {{ $isLocked ? 'readonly' : '' }}
                                required
                            >

                            <small class="text-muted">

                                Maximum {{ $calendarDays }} paid days for this month.
                                Half-day can be entered as 0.50.

                            </small>

                            @error('paid_days')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- TOTAL DUTY DAYS --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Total Duty Days</b>
                            </label>

                            <input
                                type="number"
                                name="total_duty_days"
                                min="0"
                                step="0.01"
                                class="form-control @error('total_duty_days') is-invalid @enderror"
                                value="{{ old('total_duty_days', $salaryProcessing->total_duty_days) }}"
                                {{ $isLocked ? 'readonly' : '' }}
                            >

                            @error('total_duty_days')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- TOTAL KM --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Total KM</b>
                            </label>

                            <input
                                type="number"
                                name="total_km"
                                min="0"
                                step="0.01"
                                class="form-control @error('total_km') is-invalid @enderror"
                                value="{{ old('total_km', $salaryProcessing->total_km) }}"
                                {{ $isLocked ? 'readonly' : '' }}
                            >

                            @error('total_km')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- OVERTIME HOURS --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Overtime Hours</b>
                            </label>

                            <input
                                type="number"
                                name="overtime_hours"
                                min="0"
                                step="0.01"
                                class="form-control @error('overtime_hours') is-invalid @enderror"
                                value="{{ old('overtime_hours', $salaryProcessing->overtime_hours) }}"
                                {{ $isLocked ? 'readonly' : '' }}
                            >

                            @error('overtime_hours')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- EARNINGS --}}
                    {{-- ================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="text-primary" style="color:#023a85 !important;">

                            <b>Earnings</b>

                        </h5>

                        <hr>

                    </div>


                    {{-- MONTHLY GROSS SALARY --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Driver Monthly Gross Salary</b>
                            </label>

                            <input
                                type="number"
                                class="form-control"
                                value="{{ number_format($monthlyGrossSalary, 2, '.', '') }}"
                                readonly
                            >

                            <small class="text-muted">

                                Original monthly salary from Driver record.

                            </small>

                        </div>

                    </div>


                    {{-- BASIC SALARY --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>Payable Basic Salary</b>

                            </label>

                            <input
                                type="number"
                                id="basic_salary_preview"
                                class="form-control font-weight-bold"
                                value="{{ number_format($previewBasicSalary, 2, '.', '') }}"
                                readonly
                            >

                            <small class="text-success">

                                Automatically calculated using actual calendar days and Paid Days.

                            </small>

                        </div>

                    </div>


                    {{-- DAILY SALARY --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Daily Salary</b>
                            </label>

                            <input
                                type="number"
                                id="daily_salary_preview"
                                class="form-control"
                                value="{{ number_format($monthlyGrossSalary / $calendarDays, 2, '.', '') }}"
                                readonly
                            >

                            <small class="text-muted">

                                Monthly salary divided by {{ $calendarDays }} calendar days.

                            </small>

                        </div>

                    </div>


                    {{-- WORKING SHEET BASE AMOUNT --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Working Sheet Base Amount</b>
                            </label>

                            <input
                                type="number"
                                name="working_sheet_base_amount"
                                min="0"
                                step="0.01"
                                class="form-control @error('working_sheet_base_amount') is-invalid @enderror"
                                value="{{ old('working_sheet_base_amount', $salaryProcessing->working_sheet_base_amount) }}"
                                {{ $isLocked ? 'readonly' : '' }}
                            >

                            @error('working_sheet_base_amount')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- EXTRA KM AMOUNT --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Extra KM Amount</b>
                            </label>

                            <input
                                type="number"
                                name="extra_km_amount"
                                min="0"
                                step="0.01"
                                class="form-control @error('extra_km_amount') is-invalid @enderror"
                                value="{{ old('extra_km_amount', $salaryProcessing->extra_km_amount) }}"
                                {{ $isLocked ? 'readonly' : '' }}
                            >

                            @error('extra_km_amount')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ALLOWANCE AMOUNT --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Allowance Amount</b>
                            </label>

                            <input
                                type="number"
                                name="allowance_amount"
                                min="0"
                                step="0.01"
                                class="form-control @error('allowance_amount') is-invalid @enderror"
                                value="{{ old('allowance_amount', $salaryProcessing->allowance_amount) }}"
                                {{ $isLocked ? 'readonly' : '' }}
                            >

                            @error('allowance_amount')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- OVERTIME AMOUNT --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Overtime Amount</b>
                            </label>

                            <input
                                type="number"
                                name="overtime_amount"
                                min="0"
                                step="0.01"
                                class="form-control @error('overtime_amount') is-invalid @enderror"
                                value="{{ old('overtime_amount', $salaryProcessing->overtime_amount) }}"
                                {{ $isLocked ? 'readonly' : '' }}
                            >

                            @error('overtime_amount')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- EXPENSE REIMBURSEMENT --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Expense Reimbursement</b>
                            </label>

                            <input
                                type="number"
                                name="expense_reimbursement_amount"
                                min="0"
                                step="0.01"
                                class="form-control @error('expense_reimbursement_amount') is-invalid @enderror"
                                value="{{ old('expense_reimbursement_amount', $salaryProcessing->expense_reimbursement_amount) }}"
                                {{ $isLocked ? 'readonly' : '' }}
                            >

                            @error('expense_reimbursement_amount')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- WORKING SHEET OTHER AMOUNT --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Working Sheet Other Amount</b>
                            </label>

                            <input
                                type="number"
                                name="working_sheet_other_amount"
                                min="0"
                                step="0.01"
                                class="form-control @error('working_sheet_other_amount') is-invalid @enderror"
                                value="{{ old('working_sheet_other_amount', $salaryProcessing->working_sheet_other_amount) }}"
                                {{ $isLocked ? 'readonly' : '' }}
                            >

                            @error('working_sheet_other_amount')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- BONUS --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Bonus</b>
                            </label>

                            <input
                                type="number"
                                name="bonus_amount"
                                min="0"
                                step="0.01"
                                class="form-control @error('bonus_amount') is-invalid @enderror"
                                value="{{ old('bonus_amount', $salaryProcessing->bonus_amount) }}"
                                {{ $isLocked ? 'readonly' : '' }}
                            >

                            @error('bonus_amount')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- OTHER EARNINGS --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Other Earnings</b>
                            </label>

                            <input
                                type="number"
                                name="other_earnings"
                                min="0"
                                step="0.01"
                                class="form-control @error('other_earnings') is-invalid @enderror"
                                value="{{ old('other_earnings', $salaryProcessing->other_earnings) }}"
                                {{ $isLocked ? 'readonly' : '' }}
                            >

                            @error('other_earnings')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- GROSS SALARY --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Gross Salary (Preview)</b>
                            </label>

                            <input
                                type="number"
                                id="gross_salary_preview"
                                class="form-control font-weight-bold"
                                value="{{ number_format($previewGrossSalary, 2, '.', '') }}"
                                readonly
                            >

                            <small class="text-muted">

                                Basic Salary + Working Sheet Earnings + Bonus + Other Earnings.

                            </small>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- DEDUCTIONS --}}
                    {{-- ================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="text-primary" style="color:#023a85 !important;">

                            <b>Deductions</b>

                        </h5>

                        <hr>

                    </div>


                    {{-- ADVANCE DEDUCTION --}}

                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>Advance Deduction</b>
                            </label>

                            <input
                                type="number"
                                name="advance_deduction"
                                min="0"
                                step="0.01"
                                class="form-control @error('advance_deduction') is-invalid @enderror"
                                value="{{ old('advance_deduction', $salaryProcessing->advance_deduction) }}"
                                {{ $isLocked ? 'readonly' : '' }}
                            >

                            @error('advance_deduction')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- LOAN DEDUCTION --}}

                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>Loan Deduction</b>
                            </label>

                            <input
                                type="number"
                                name="loan_deduction"
                                min="0"
                                step="0.01"
                                class="form-control @error('loan_deduction') is-invalid @enderror"
                                value="{{ old('loan_deduction', $salaryProcessing->loan_deduction) }}"
                                {{ $isLocked ? 'readonly' : '' }}
                            >

                            @error('loan_deduction')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- PENALTY DEDUCTION --}}

                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>Penalty Deduction</b>
                            </label>

                            <input
                                type="number"
                                name="penalty_deduction"
                                min="0"
                                step="0.01"
                                class="form-control @error('penalty_deduction') is-invalid @enderror"
                                value="{{ old('penalty_deduction', $salaryProcessing->penalty_deduction) }}"
                                {{ $isLocked ? 'readonly' : '' }}
                            >

                            @error('penalty_deduction')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- OTHER DEDUCTIONS --}}

                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>Other Deductions</b>
                            </label>

                            <input
                                type="number"
                                name="other_deductions"
                                min="0"
                                step="0.01"
                                class="form-control @error('other_deductions') is-invalid @enderror"
                                value="{{ old('other_deductions', $salaryProcessing->other_deductions) }}"
                                {{ $isLocked ? 'readonly' : '' }}
                            >

                            @error('other_deductions')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- TOTAL DEDUCTIONS --}}

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                <b>Total Deductions</b>
                            </label>

                            <input
                                type="number"
                                class="form-control"
                                value="{{ number_format($storedTotalDeductions, 2, '.', '') }}"
                                readonly
                            >

                        </div>

                    </div>


                    {{-- NET SALARY --}}

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                <b>Net Salary (Preview)</b>
                            </label>

                            <input
                                type="number"
                                id="net_salary_preview"
                                class="form-control font-weight-bold text-success"
                                value="{{ number_format($previewNetSalary, 2, '.', '') }}"
                                readonly
                            >

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- REMARKS --}}
                    {{-- ================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="text-primary" style="color:#023a85 !important;">

                            <b>Remarks</b>

                        </h5>

                        <hr>

                    </div>


                    <div class="col-md-12">

                        <div class="form-group">

                            <label>
                                <b>Remarks</b>
                            </label>

                            <textarea
                                name="remarks"
                                id="remarks"
                                rows="4"
                                maxlength="2000"
                                class="form-control @error('remarks') is-invalid @enderror"
                                placeholder="Enter remarks"
                                {{ $isLocked ? 'readonly' : '' }}>{{ old('remarks', $salaryProcessing->remarks) }}</textarea>

                            @error('remarks')

                                <span class="invalid-feedback d-block">

                                    <strong>{{ $message }}</strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- ACTION BUTTONS --}}
                    {{-- ================================================= --}}

                    <div class="col-12">

                        <div class="text-right mt-4">

                            <a
                                href="{{ route('salary-processing.index') }}"
                                class="btn btn-danger"
                            >

                                <i class="fa fa-times"></i>

                                Cancel

                            </a>

                            @if (!$isLocked)

                                <button
                                    type="submit"
                                    class="btn btn-success"
                                    id="updatePayrollButton"
                                >

                                    <i class="fa fa-save"></i>

                                    Update Payroll

                                </button>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>

    <x-backend.footer />

</div>

@endsection


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | PAID DAYS BASED BASIC SALARY PREVIEW
    |--------------------------------------------------------------------------
    |
    | This is a user-interface preview only.
    |
    | The authoritative calculation and database update must always
    | happen inside SalaryProcessingService.
    |
    */

    const paidDaysInput = document.getElementById('paid_days');

    const basicSalaryPreview = document.getElementById(
        'basic_salary_preview'
    );

    const dailySalaryPreview = document.getElementById(
        'daily_salary_preview'
    );

    const grossSalaryPreview = document.getElementById(
        'gross_salary_preview'
    );

    const netSalaryPreview = document.getElementById(
        'net_salary_preview'
    );

    if (
        paidDaysInput &&
        basicSalaryPreview &&
        grossSalaryPreview &&
        netSalaryPreview
    ) {

        const monthlySalary = parseFloat(
            paidDaysInput.dataset.monthlySalary || 0
        );

        const calendarDays = parseFloat(
            paidDaysInput.dataset.calendarDays || 0
        );

        const storedBasicSalary = parseFloat(
            paidDaysInput.dataset.storedBasic || 0
        );

        const storedGrossSalary = parseFloat(
            paidDaysInput.dataset.storedGross || 0
        );

        const totalDeductions = parseFloat(
            paidDaysInput.dataset.totalDeductions || 0
        );


        /*
        |--------------------------------------------------------------------------
        | CURRENCY FORMAT
        |--------------------------------------------------------------------------
        */

        const formatAmount = function (amount) {

            return Math.max(0, amount).toFixed(2);

        };


        /*
        |--------------------------------------------------------------------------
        | UPDATE SALARY PREVIEW
        |--------------------------------------------------------------------------
        */

        const updateSalaryPreview = function () {

            if (
                monthlySalary <= 0 ||
                calendarDays <= 0
            ) {

                return;
            }

            let paidDays = parseFloat(
                paidDaysInput.value || 0
            );

            if (!Number.isFinite(paidDays)) {

                paidDays = 0;
            }

            /*
            |--------------------------------------------------------------------------
            | Prevent Calculation Above Calendar Days
            |--------------------------------------------------------------------------
            */

            paidDays = Math.min(
                Math.max(paidDays, 0),
                calendarDays
            );


            /*
            |--------------------------------------------------------------------------
            | DAILY SALARY
            |--------------------------------------------------------------------------
            */

            const dailySalary =
                monthlySalary / calendarDays;


            /*
            |--------------------------------------------------------------------------
            | PRORATED BASIC SALARY
            |--------------------------------------------------------------------------
            */

            const payableBasicSalary = Math.round(
                (dailySalary * paidDays + Number.EPSILON) * 100
            ) / 100;


            /*
            |--------------------------------------------------------------------------
            | UPDATED GROSS SALARY
            |--------------------------------------------------------------------------
            |
            | Replace the original saved Basic Salary with the newly
            | calculated prorated Basic Salary.
            |
            | Other saved earnings are preserved in this preview.
            |
            */

            const grossSalary = Math.max(
                0,
                storedGrossSalary
                - storedBasicSalary
                + payableBasicSalary
            );


            /*
            |--------------------------------------------------------------------------
            | UPDATED NET SALARY
            |--------------------------------------------------------------------------
            */

            const netSalary = Math.max(
                0,
                grossSalary - totalDeductions
            );


            /*
            |--------------------------------------------------------------------------
            | DISPLAY VALUES
            |--------------------------------------------------------------------------
            */

            basicSalaryPreview.value = formatAmount(
                payableBasicSalary
            );

            if (dailySalaryPreview) {

                dailySalaryPreview.value = formatAmount(
                    dailySalary
                );
            }

            grossSalaryPreview.value = formatAmount(
                grossSalary
            );

            netSalaryPreview.value = formatAmount(
                netSalary
            );

        };


        /*
        |--------------------------------------------------------------------------
        | PAID DAYS CHANGE
        |--------------------------------------------------------------------------
        */

        paidDaysInput.addEventListener(
            'input',
            updateSalaryPreview
        );

        paidDaysInput.addEventListener(
            'change',
            updateSalaryPreview
        );

    }


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMISSION PROTECTION
    |--------------------------------------------------------------------------
    |
    | Prevent accidental multiple submissions.
    |
    */

    const editForm = document.getElementById(
        'salaryProcessingEditForm'
    );

    if (editForm) {

        editForm.addEventListener('submit', function () {

            const updateButton = document.getElementById(
                'updatePayrollButton'
            );

            if (updateButton) {

                updateButton.disabled = true;

                updateButton.innerHTML =
                    '<i class="fa fa-spinner fa-spin"></i> Updating Payroll...';
            }

        });

    }

});

</script>

@endpush
