@extends('backend.layouts.master')

@section('title')
Edit Salary Slip
@endsection

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Salary Slip State
    |--------------------------------------------------------------------------
    |
    | The SalarySlipService protects payroll-generated, issued, paid,
    | partially-paid and cancelled slips from salary-detail edits.
    | Reflect the same lifecycle restrictions in the UI.
    |
    */

    $isDriver = auth()->user() && auth()->user()->isDriver();

    $isPayrollGenerated = !empty($salarySlip->salary_processing_id);
    $isCancelled = $salarySlip->isCancelled();
    $isIssued = $salarySlip->isIssued();
    $isPaid = $salarySlip->isPaid();
    $isPartiallyPaid = $salarySlip->isPartiallyPaid();
    $isLegacyPaid = $salarySlip->isStatusPaid();

    $isLocked =
        $isPayrollGenerated ||
        $isCancelled ||
        $isIssued ||
        $isPaid ||
        $isPartiallyPaid ||
        $isLegacyPaid;

    $lockMessage = 'This Salary Slip is locked and cannot be edited.';

    if ($isPayrollGenerated) {
        $lockMessage = 'This Salary Slip was generated from Salary Processing. Its payroll snapshot cannot be edited here. Manage it through the Salary Processing workflow.';
    } elseif ($isCancelled) {
        $lockMessage = 'This Salary Slip has been cancelled and cannot be edited.';
    } elseif ($isIssued) {
        $lockMessage = 'This Salary Slip has already been issued. Its salary details are locked.';
    } elseif ($isPaid || $isLegacyPaid) {
        $lockMessage = 'This Salary Slip has been paid. Its salary details are locked.';
    } elseif ($isPartiallyPaid) {
        $lockMessage = 'This Salary Slip has received a partial payment. Its salary details are locked.';
    }

    $salaryMonth = (int) old('salary_month', $salarySlip->salary_month);
    $salaryYear = (int) old('salary_year', $salarySlip->salary_year);
@endphp

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">

        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="page-header">

            <div class="row align-items-center">

                <div class="col-md-8 col-sm-12">

                    <div class="title">

                        <h4>
                            Edit Salary Slip
                        </h4>

                    </div>

                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">
                                    Dashboard
                                </a>
                            </li>

                            <li class="breadcrumb-item">
                                <a href="{{ route('salary-slips.index') }}">
                                    Salary Slips
                                </a>
                            </li>

                            <li class="breadcrumb-item active" aria-current="page">
                                Edit {{ $salarySlip->slip_no ?? 'Salary Slip' }}
                            </li>

                        </ol>

                    </nav>

                </div>

                <div class="col-md-4 col-sm-12 text-md-right mt-2 mt-md-0">

                    <a href="{{ route('salary-slips.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i>
                        Back to Salary Slips
                    </a>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- FLASH MESSAGES --}}
        {{-- ========================================================= --}}

        @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle"></i>
                {{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle"></i>
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Please correct the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- LOCKED SALARY SLIP --}}
        {{-- ========================================================= --}}

        @if ($isLocked)

            <div class="alert alert-warning">
                <div class="d-flex align-items-start">
                    <i class="fa fa-lock mr-2 mt-1"></i>
                    <div>
                        <strong>Editing Restricted</strong>
                        <div>{{ $lockMessage }}</div>
                    </div>
                </div>
            </div>

            <div class="card-box pd-20 mb-30">

                <div class="d-flex justify-content-between align-items-start flex-wrap">

                    <div>
                        <h5 class="text-primary mb-1" style="color:#023a85 !important;">
                            Salary Slip Information
                        </h5>
                        <div class="text-muted">
                            Slip No: <strong>{{ $salarySlip->slip_no ?? '-' }}</strong>
                        </div>
                    </div>

                    <span class="badge badge-secondary mt-2">
                        {{ ucwords(str_replace('_', ' ', $salarySlip->status ?? 'generated')) }}
                    </span>

                </div>

                <hr>

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <small class="text-muted d-block">Driver</small>
                        <strong>{{ $salarySlip->driver?->name ?? '-' }}</strong>
                        @if (!empty($salarySlip->driver?->driver_code))
                            <small class="text-muted d-block">{{ $salarySlip->driver->driver_code }}</small>
                        @endif
                    </div>

                    <div class="col-md-4 mb-3">
                        <small class="text-muted d-block">Salary Period</small>
                        <strong>
                            {{ $salarySlip->salary_month ? date('F', mktime(0, 0, 0, (int) $salarySlip->salary_month, 1)) : '-' }}
                            {{ $salarySlip->salary_year ?? '' }}
                        </strong>
                        <small class="text-muted d-block">
                            {{ optional($salarySlip->period_from)->format('d-m-Y') ?? '-' }}
                            to
                            {{ optional($salarySlip->period_to)->format('d-m-Y') ?? '-' }}
                        </small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <small class="text-muted d-block">Payment Status</small>
                        <strong>{{ ucwords(str_replace('_', ' ', $salarySlip->payment_status ?? 'unpaid')) }}</strong>
                        @if ($salarySlip->payment_date)
                            <small class="text-muted d-block">
                                Payment Date: {{ $salarySlip->payment_date->format('d-m-Y') }}
                            </small>
                        @endif
                    </div>

                    @if ($isPayrollGenerated)
                        <div class="col-md-12 mb-3">
                            <small class="text-muted d-block">Linked Salary Processing</small>
                            <strong>
                                #{{ $salarySlip->salary_processing_id }}
                                @if ($salarySlip->salaryProcessing?->status)
                                    - {{ ucwords(str_replace('_', ' ', $salarySlip->salaryProcessing->status)) }}
                                @endif
                            </strong>
                        </div>
                    @endif

                </div>

                <hr>

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <small class="text-muted d-block">Basic Salary</small>
                        <h5 class="mb-0">₹{{ number_format((float) $salarySlip->basic_salary, 2) }}</h5>
                    </div>

                    <div class="col-md-4 mb-3">
                        <small class="text-muted d-block">Gross Salary</small>
                        <h5 class="mb-0">₹{{ number_format((float) $salarySlip->gross_salary, 2) }}</h5>
                    </div>

                    <div class="col-md-4 mb-3">
                        <small class="text-muted d-block">Total Deductions</small>
                        <h5 class="mb-0">₹{{ number_format((float) $salarySlip->total_deductions, 2) }}</h5>
                    </div>

                    <div class="col-md-12">
                        <div class="p-3 bg-light rounded">
                            <small class="text-muted d-block">Net Salary</small>
                            <h3 class="mb-0 text-success">
                                ₹{{ number_format((float) $salarySlip->net_salary, 2) }}
                            </h3>
                        </div>
                    </div>

                </div>

                @if ($salarySlip->remarks)
                    <hr>
                    <strong>Remarks</strong>
                    <p class="mb-0">{{ $salarySlip->remarks }}</p>
                @endif

                <div class="text-right mt-4">
                    <a href="{{ route('salary-slips.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i>
                        Back to Salary Slips
                    </a>
                </div>

            </div>

        @else

            {{-- ========================================================= --}}
            {{-- EDIT FORM: MANUAL GENERATED / UNPAID SLIP ONLY --}}
            {{-- ========================================================= --}}

            <form
                id="editSalarySlipForm"
                action="{{ route('salary-slips.update', $salarySlip->id) }}"
                method="POST"
            >

                @csrf
                @method('PUT')

                <div class="card-box pd-20 mb-30">

                    {{-- ================================================= --}}
                    {{-- SALARY SLIP INFORMATION --}}
                    {{-- ================================================= --}}

                    <div class="mb-4">
                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Salary Slip Information</b>
                        </h5>
                        <hr>
                    </div>

                    <div class="row">

                        {{-- Slip Number --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Salary Slip Number</b></label>
                                <input type="text" class="form-control" value="{{ $salarySlip->slip_no ?? '-' }}" readonly>
                            </div>
                        </div>

                        {{-- Driver --}}
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>
                                    <b>Driver <span class="text-danger">*</span></b>
                                </label>

                                <select
                                    name="driver_id"
                                    id="driver_id"
                                    class="form-control custom-select2 @error('driver_id') is-invalid @enderror"
                                    {{ $isDriver ? 'disabled' : '' }}
                                >
                                    <option value="">Select Driver</option>

                                    @foreach ($drivers ?? [] as $driver)
                                        <option
                                            value="{{ $driver->id }}"
                                            {{ (string) old('driver_id', $salarySlip->driver_id) === (string) $driver->id ? 'selected' : '' }}
                                        >
                                            {{ $driver->driver_code ?? '' }}
                                            @if (!empty($driver->driver_code)) - @endif
                                            {{ $driver->name ?? trim(($driver->first_name ?? '') . ' ' . ($driver->last_name ?? '')) }}
                                        </option>
                                    @endforeach
                                </select>

                                @if ($isDriver)
                                    <input type="hidden" name="driver_id" value="{{ $salarySlip->driver_id }}">
                                @endif

                                @error('driver_id')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror

                            </div>
                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- SALARY PERIOD --}}
                    {{-- ================================================= --}}

                    <div class="mt-3">
                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Salary Period</b>
                        </h5>
                        <hr>
                    </div>

                    <div class="row">

                        {{-- Salary Month --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Salary Month <span class="text-danger">*</span></b></label>

                                <select
                                    name="salary_month"
                                    id="salary_month"
                                    class="form-control custom-select2 @error('salary_month') is-invalid @enderror"
                                >
                                    <option value="">Select Month</option>
                                    @for ($month = 1; $month <= 12; $month++)
                                        <option value="{{ $month }}" {{ $salaryMonth === $month ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                        </option>
                                    @endfor
                                </select>

                                @error('salary_month')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Salary Year --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Salary Year <span class="text-danger">*</span></b></label>

                                <input
                                    type="number"
                                    name="salary_year"
                                    id="salary_year"
                                    min="2000"
                                    max="2100"
                                    step="1"
                                    class="form-control @error('salary_year') is-invalid @enderror"
                                    value="{{ old('salary_year', $salarySlip->salary_year) }}"
                                >

                                @error('salary_year')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Period From --}}
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><b>Period From</b></label>

                                <input
                                    type="date"
                                    name="period_from"
                                    id="period_from"
                                    class="form-control @error('period_from') is-invalid @enderror"
                                    value="{{ old('period_from', optional($salarySlip->period_from)->format('Y-m-d')) }}"
                                >

                                @error('period_from')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Period To --}}
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><b>Period To</b></label>

                                <input
                                    type="date"
                                    name="period_to"
                                    id="period_to"
                                    class="form-control @error('period_to') is-invalid @enderror"
                                    value="{{ old('period_to', optional($salarySlip->period_to)->format('Y-m-d')) }}"
                                >

                                @error('period_to')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- ATTENDANCE INFORMATION --}}
                    {{-- ================================================= --}}

                    <div class="mt-3">
                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Attendance & Working Information</b>
                        </h5>
                        <hr>
                    </div>

                    <div class="row">

                        {{-- Total Working Days --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><b>Total Working Days</b></label>
                                <input
                                    type="number"
                                    name="total_working_days"
                                    id="total_working_days"
                                    min="0"
                                    step="0.01"
                                    class="form-control @error('total_working_days') is-invalid @enderror"
                                    value="{{ old('total_working_days', $salarySlip->total_working_days ?? 0) }}"
                                >
                                @error('total_working_days')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Present Days --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><b>Present Days</b></label>
                                <input
                                    type="number"
                                    name="present_days"
                                    id="present_days"
                                    min="0"
                                    step="0.01"
                                    class="form-control @error('present_days') is-invalid @enderror"
                                    value="{{ old('present_days', $salarySlip->present_days ?? 0) }}"
                                >
                                @error('present_days')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Absent Days --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><b>Absent Days</b></label>
                                <input
                                    type="number"
                                    name="absent_days"
                                    id="absent_days"
                                    min="0"
                                    step="0.01"
                                    class="form-control @error('absent_days') is-invalid @enderror"
                                    value="{{ old('absent_days', $salarySlip->absent_days ?? 0) }}"
                                >
                                @error('absent_days')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Paid Days --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><b>Paid Days</b></label>
                                <input
                                    type="number"
                                    name="paid_days"
                                    id="paid_days"
                                    min="0"
                                    step="0.01"
                                    class="form-control @error('paid_days') is-invalid @enderror"
                                    value="{{ old('paid_days', $salarySlip->paid_days ?? 0) }}"
                                >
                                @error('paid_days')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Overtime Hours --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><b>Overtime Hours</b></label>
                                <input
                                    type="number"
                                    name="overtime_hours"
                                    id="overtime_hours"
                                    min="0"
                                    step="0.01"
                                    class="form-control @error('overtime_hours') is-invalid @enderror"
                                    value="{{ old('overtime_hours', $salarySlip->overtime_hours ?? 0) }}"
                                >
                                @error('overtime_hours')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Total Duty Days --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><b>Total Duty Days</b></label>
                                <input
                                    type="number"
                                    name="total_duty_days"
                                    id="total_duty_days"
                                    min="0"
                                    step="0.01"
                                    class="form-control @error('total_duty_days') is-invalid @enderror"
                                    value="{{ old('total_duty_days', $salarySlip->total_duty_days ?? 0) }}"
                                >
                                @error('total_duty_days')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Total KM --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><b>Total KM</b></label>
                                <input
                                    type="number"
                                    name="total_km"
                                    id="total_km"
                                    min="0"
                                    step="0.01"
                                    class="form-control @error('total_km') is-invalid @enderror"
                                    value="{{ old('total_km', $salarySlip->total_km ?? 0) }}"
                                >
                                @error('total_km')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- EARNINGS --}}
                    {{-- ================================================= --}}

                    <div class="mt-3">
                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Earnings</b>
                        </h5>
                        <hr>
                    </div>

                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i>
                        Working Sheet Total is authoritative when it is greater than zero. In that case, its individual components are shown as a breakdown and are not added again to Gross Salary. When no combined total is saved, the component amounts are used, matching SalarySlipService calculation rules.
                    </div>

                    <div class="row">

                        {{-- Basic Salary --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Basic Salary <span class="text-danger">*</span></b></label>
                                <input
                                    type="number"
                                    name="basic_salary"
                                    id="basic_salary"
                                    min="0"
                                    step="0.01"
                                    class="form-control salary-calc-field @error('basic_salary') is-invalid @enderror"
                                    value="{{ old('basic_salary', $salarySlip->basic_salary ?? 0) }}"
                                >
                                @error('basic_salary')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Working Sheet Base --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Working Sheet Base Amount</b></label>
                                <input
                                    type="number"
                                    name="working_sheet_base_amount"
                                    id="working_sheet_base_amount"
                                    min="0"
                                    step="0.01"
                                    class="form-control salary-calc-field @error('working_sheet_base_amount') is-invalid @enderror"
                                    value="{{ old('working_sheet_base_amount', $salarySlip->working_sheet_base_amount ?? 0) }}"
                                >
                                @error('working_sheet_base_amount')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Extra KM --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Extra KM Amount</b></label>
                                <input
                                    type="number"
                                    name="extra_km_amount"
                                    id="extra_km_amount"
                                    min="0"
                                    step="0.01"
                                    class="form-control salary-calc-field @error('extra_km_amount') is-invalid @enderror"
                                    value="{{ old('extra_km_amount', $salarySlip->extra_km_amount ?? 0) }}"
                                >
                                @error('extra_km_amount')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Expense Reimbursement --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Expense Reimbursement</b></label>
                                <input
                                    type="number"
                                    name="expense_reimbursement_amount"
                                    id="expense_reimbursement_amount"
                                    min="0"
                                    step="0.01"
                                    class="form-control salary-calc-field @error('expense_reimbursement_amount') is-invalid @enderror"
                                    value="{{ old('expense_reimbursement_amount', $salarySlip->expense_reimbursement_amount ?? 0) }}"
                                >
                                @error('expense_reimbursement_amount')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Working Sheet Other --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Working Sheet Other Amount</b></label>
                                <input
                                    type="number"
                                    name="working_sheet_other_amount"
                                    id="working_sheet_other_amount"
                                    min="0"
                                    step="0.01"
                                    class="form-control salary-calc-field @error('working_sheet_other_amount') is-invalid @enderror"
                                    value="{{ old('working_sheet_other_amount', $salarySlip->working_sheet_other_amount ?? 0) }}"
                                >
                                @error('working_sheet_other_amount')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Allowance --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Allowance Amount</b></label>
                                <input
                                    type="number"
                                    name="allowance_amount"
                                    id="allowance_amount"
                                    min="0"
                                    step="0.01"
                                    class="form-control salary-calc-field @error('allowance_amount') is-invalid @enderror"
                                    value="{{ old('allowance_amount', $salarySlip->allowance_amount ?? 0) }}"
                                >
                                @error('allowance_amount')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Overtime Amount --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Overtime Amount</b></label>
                                <input
                                    type="number"
                                    name="overtime_amount"
                                    id="overtime_amount"
                                    min="0"
                                    step="0.01"
                                    class="form-control salary-calc-field @error('overtime_amount') is-invalid @enderror"
                                    value="{{ old('overtime_amount', $salarySlip->overtime_amount ?? 0) }}"
                                >
                                @error('overtime_amount')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Working Sheet Total --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Working Sheet Total Amount</b></label>
                                <input
                                    type="number"
                                    name="working_sheet_total_amount"
                                    id="working_sheet_total_amount"
                                    min="0"
                                    step="0.01"
                                    class="form-control salary-calc-field @error('working_sheet_total_amount') is-invalid @enderror"
                                    value="{{ old('working_sheet_total_amount', $salarySlip->working_sheet_total_amount ?? 0) }}"
                                >
                                @error('working_sheet_total_amount')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="text-muted">When greater than zero, this value is used instead of adding the components separately.</small>
                            </div>
                        </div>

                        {{-- Bonus --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Bonus Amount</b></label>
                                <input
                                    type="number"
                                    name="bonus_amount"
                                    id="bonus_amount"
                                    min="0"
                                    step="0.01"
                                    class="form-control salary-calc-field @error('bonus_amount') is-invalid @enderror"
                                    value="{{ old('bonus_amount', $salarySlip->bonus_amount ?? 0) }}"
                                >
                                @error('bonus_amount')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Other Earnings --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Other Earnings</b></label>
                                <input
                                    type="number"
                                    name="other_earnings"
                                    id="other_earnings"
                                    min="0"
                                    step="0.01"
                                    class="form-control salary-calc-field @error('other_earnings') is-invalid @enderror"
                                    value="{{ old('other_earnings', $salarySlip->other_earnings ?? 0) }}"
                                >
                                @error('other_earnings')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Gross Salary --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Gross Salary</b></label>
                                <input
                                    type="number"
                                    name="gross_salary"
                                    id="gross_salary"
                                    min="0"
                                    step="0.01"
                                    class="form-control font-weight-bold"
                                    value="{{ old('gross_salary', $salarySlip->gross_salary ?? 0) }}"
                                    readonly
                                >
                                <small class="text-muted">Calculated automatically.</small>
                            </div>
                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- DEDUCTIONS --}}
                    {{-- ================================================= --}}

                    <div class="mt-3">
                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Deductions</b>
                        </h5>
                        <hr>
                    </div>

                    <div class="row">

                        {{-- Advance Deduction --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><b>Advance Deduction</b></label>
                                <input
                                    type="number"
                                    name="advance_deduction"
                                    id="advance_deduction"
                                    min="0"
                                    step="0.01"
                                    class="form-control deduction-calc-field @error('advance_deduction') is-invalid @enderror"
                                    value="{{ old('advance_deduction', $salarySlip->advance_deduction ?? 0) }}"
                                >
                                @error('advance_deduction')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Loan Deduction --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><b>Loan Deduction</b></label>
                                <input
                                    type="number"
                                    name="loan_deduction"
                                    id="loan_deduction"
                                    min="0"
                                    step="0.01"
                                    class="form-control deduction-calc-field @error('loan_deduction') is-invalid @enderror"
                                    value="{{ old('loan_deduction', $salarySlip->loan_deduction ?? 0) }}"
                                >
                                @error('loan_deduction')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Penalty Deduction --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><b>Penalty Deduction</b></label>
                                <input
                                    type="number"
                                    name="penalty_deduction"
                                    id="penalty_deduction"
                                    min="0"
                                    step="0.01"
                                    class="form-control deduction-calc-field @error('penalty_deduction') is-invalid @enderror"
                                    value="{{ old('penalty_deduction', $salarySlip->penalty_deduction ?? 0) }}"
                                >
                                @error('penalty_deduction')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Other Deductions --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><b>Other Deductions</b></label>
                                <input
                                    type="number"
                                    name="other_deductions"
                                    id="other_deductions"
                                    min="0"
                                    step="0.01"
                                    class="form-control deduction-calc-field @error('other_deductions') is-invalid @enderror"
                                    value="{{ old('other_deductions', $salarySlip->other_deductions ?? 0) }}"
                                >
                                @error('other_deductions')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Total Deductions --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><b>Total Deductions</b></label>
                                <input
                                    type="number"
                                    name="total_deductions"
                                    id="total_deductions"
                                    min="0"
                                    step="0.01"
                                    class="form-control font-weight-bold"
                                    value="{{ old('total_deductions', $salarySlip->total_deductions ?? 0) }}"
                                    readonly
                                >
                            </div>
                        </div>

                        {{-- Net Salary --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><b>Net Salary</b></label>
                                <input
                                    type="number"
                                    name="net_salary"
                                    id="net_salary"
                                    min="0"
                                    step="0.01"
                                    class="form-control font-weight-bold text-success"
                                    value="{{ old('net_salary', $salarySlip->net_salary ?? 0) }}"
                                    readonly
                                >
                            </div>
                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- STATUS & PAYMENT --}}
                    {{-- ================================================= --}}

                    <div class="mt-3">
                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Status & Payment Information</b>
                        </h5>
                        <hr>
                    </div>

                    <div class="row">

                        {{-- Status --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Status <span class="text-danger">*</span></b></label>
                                <select
                                    name="status"
                                    id="status"
                                    class="form-control custom-select2 @error('status') is-invalid @enderror"
                                >
                                    @foreach ($statuses ?? [] as $status)
                                        <option
                                            value="{{ $status }}"
                                            {{ old('status', $salarySlip->status) === $status ? 'selected' : '' }}
                                        >
                                            {{ ucwords(str_replace('_', ' ', $status)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="text-muted">Issuing or cancelling a slip uses the Salary Slip lifecycle workflow.</small>
                            </div>
                        </div>

                        {{-- Payment Status --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Payment Status</b></label>
                                <select
                                    name="payment_status"
                                    id="payment_status"
                                    class="form-control custom-select2 @error('payment_status') is-invalid @enderror"
                                >
                                    @foreach ($paymentStatuses ?? [] as $paymentStatus)
                                        <option
                                            value="{{ $paymentStatus }}"
                                            {{ old('payment_status', $salarySlip->payment_status) === $paymentStatus ? 'selected' : '' }}
                                        >
                                            {{ ucwords(str_replace('_', ' ', $paymentStatus)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('payment_status')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Payment Date --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><b>Payment Date</b></label>
                                <input
                                    type="date"
                                    name="payment_date"
                                    id="payment_date"
                                    class="form-control @error('payment_date') is-invalid @enderror"
                                    value="{{ old('payment_date', optional($salarySlip->payment_date)->format('Y-m-d')) }}"
                                >
                                @error('payment_date')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- REMARKS --}}
                    {{-- ================================================= --}}

                    <div class="mt-3">
                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Remarks</b>
                        </h5>
                        <hr>
                    </div>

                    <div class="form-group">
                        <label><b>Remarks</b></label>
                        <textarea
                            name="remarks"
                            id="remarks"
                            rows="4"
                            maxlength="2000"
                            class="form-control @error('remarks') is-invalid @enderror"
                            placeholder="Enter remarks"
                        >{{ old('remarks', $salarySlip->remarks) }}</textarea>
                        @error('remarks')
                            <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>


                    {{-- ================================================= --}}
                    {{-- ACTION BUTTONS --}}
                    {{-- ================================================= --}}

                    <div class="d-flex justify-content-end flex-wrap mt-4">

                        <a href="{{ route('salary-slips.index') }}" class="btn btn-danger mr-2 mb-2">
                            <i class="fa fa-times"></i>
                            Cancel
                        </a>

                        <button
                            type="submit"
                            id="updateSalarySlipBtn"
                            class="btn btn-success mb-2"
                        >
                            <i class="fa fa-save"></i>
                            Update Salary Slip
                        </button>

                    </div>

                </div>

            </form>

        @endif

    </div>

    <x-backend.footer />

</div>

@endsection


@if (!$isLocked)

    @push('scripts')

    <script>
    (function ($) {
        'use strict';

        /*
        |--------------------------------------------------------------------------
        | Safe Numeric Reader
        |--------------------------------------------------------------------------
        */
        function numberValue(selector) {
            var value = parseFloat($(selector).val());
            return Number.isFinite(value) ? value : 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Salary - Matches SalarySlipService::calculateSalary()
        |--------------------------------------------------------------------------
        */
        function calculateSalaryTotals() {
            var basicSalary = numberValue('#basic_salary');

            var workingSheetTotal = numberValue('#working_sheet_total_amount');

            var workingSheetComponents =
                numberValue('#working_sheet_base_amount') +
                numberValue('#extra_km_amount') +
                numberValue('#overtime_amount') +
                numberValue('#allowance_amount') +
                numberValue('#expense_reimbursement_amount') +
                numberValue('#working_sheet_other_amount');

            var hasWorkingSheetComponents =
                workingSheetTotal > 0 || workingSheetComponents !== 0;

            var workingSheetEarnings;

            if (workingSheetTotal > 0) {
                workingSheetEarnings = workingSheetTotal;
            } else if (hasWorkingSheetComponents) {
                workingSheetEarnings = workingSheetComponents;
            } else {
                // Legacy slips: allowance + overtime are used when no WS data exists.
                workingSheetEarnings =
                    numberValue('#allowance_amount') +
                    numberValue('#overtime_amount');
            }

            var grossSalary =
                basicSalary +
                workingSheetEarnings +
                numberValue('#bonus_amount') +
                numberValue('#other_earnings');

            var totalDeductions =
                numberValue('#advance_deduction') +
                numberValue('#loan_deduction') +
                numberValue('#penalty_deduction') +
                numberValue('#other_deductions');

            var netSalary = Math.max(0, grossSalary - totalDeductions);

            $('#gross_salary').val(grossSalary.toFixed(2));
            $('#total_deductions').val(totalDeductions.toFixed(2));
            $('#net_salary').val(netSalary.toFixed(2));
        }

        /*
        |--------------------------------------------------------------------------
        | Salary Month / Year -> Default Period
        |--------------------------------------------------------------------------
        */
        function formatDate(date) {
            var year = date.getFullYear();
            var month = String(date.getMonth() + 1).padStart(2, '0');
            var day = String(date.getDate()).padStart(2, '0');
            return year + '-' + month + '-' + day;
        }

        function updateSalaryPeriod(forceUpdate) {
            var month = parseInt($('#salary_month').val(), 10);
            var year = parseInt($('#salary_year').val(), 10);

            if (!month || month < 1 || month > 12 || !year) {
                return;
            }

            var firstDay = new Date(year, month - 1, 1);
            var lastDay = new Date(year, month, 0);

            if (forceUpdate || !$('#period_from').val()) {
                $('#period_from').val(formatDate(firstDay));
            }

            if (forceUpdate || !$('#period_to').val()) {
                $('#period_to').val(formatDate(lastDay));
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Recalculate When Salary Components Change
        |--------------------------------------------------------------------------
        */
        $(document).on(
            'input change',
            '.salary-calc-field, .deduction-calc-field',
            calculateSalaryTotals
        );

        /*
        |--------------------------------------------------------------------------
        | Period Defaults
        |--------------------------------------------------------------------------
        */
        $('#salary_month, #salary_year').on('change', function () {
            updateSalaryPeriod(true);
        });

        /*
        |--------------------------------------------------------------------------
        | Form Validation / Double Submit Protection
        |--------------------------------------------------------------------------
        */
        $('#editSalarySlipForm').on('submit', function (event) {
            var periodFrom = $('#period_from').val();
            var periodTo = $('#period_to').val();
            var month = parseInt($('#salary_month').val(), 10);
            var year = parseInt($('#salary_year').val(), 10);

            if (!month || month < 1 || month > 12) {
                event.preventDefault();
                alert('Please select a valid Salary Month.');
                $('#salary_month').focus();
                return false;
            }

            if (!year || year < 2000 || year > 2100) {
                event.preventDefault();
                alert('Please enter a valid Salary Year.');
                $('#salary_year').focus();
                return false;
            }

            if (periodFrom && periodTo && periodFrom > periodTo) {
                event.preventDefault();
                alert('Period From date cannot be later than Period To date.');
                $('#period_from').focus();
                return false;
            }

            calculateSalaryTotals();

            $('#updateSalarySlipBtn')
                .prop('disabled', true)
                .html('<i class="fa fa-spinner fa-spin"></i> Updating...');
        });

        /*
        |--------------------------------------------------------------------------
        | Initial Calculation
        |--------------------------------------------------------------------------
        */
        $(function () {
            calculateSalaryTotals();
        });

    })(jQuery);
    </script>

    @endpush

@endif
