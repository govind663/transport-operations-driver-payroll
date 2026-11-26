
@extends('backend.layouts.master')

@section('title')
Edit Salary Processing
@endsection

@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">

        {{-- ================= PAGE HEADER ================= --}}
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


        {{-- ================= ALERT MESSAGES ================= --}}

        @if ($errors->any())

            <div class="alert alert-danger">

                <strong>Please fix the following errors:</strong>

                <ul class="mb-0 mt-2">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif

        @if (session('message'))

            <div class="alert alert-success">
                {{ session('message') }}
            </div>

        @endif

        @if (session('error'))

            <div class="alert alert-danger">
                {{ session('error') }}
            </div>

        @endif


        {{-- ================= PAYROLL LOCK CHECK ================= --}}

        @php

            $isLocked = in_array($salaryProcessing->status, [
                \App\Models\SalaryProcessing::STATUS_APPROVED,
                \App\Models\SalaryProcessing::STATUS_PAID,
                \App\Models\SalaryProcessing::STATUS_CANCELLED,
            ]);

        @endphp


        @if ($isLocked)

            <div class="alert alert-warning">

                <i class="fa fa-lock"></i>

                <strong>Payroll Locked!</strong>

                Approved, paid or cancelled payroll cannot be edited.

            </div>

        @endif


        {{-- ================= EDIT FORM ================= --}}

        <form
            action="{{ route('salary-processing.update', $salaryProcessing->id) }}"
            method="POST">

            @csrf
            @method('PUT')


            <div class="card-box pd-20 mb-30">


                {{-- ================= DRIVER & SALARY PERIOD ================= --}}

                <div class="mb-4">

                    <h5 class="text-primary" style="color:#023a85 !important;">
                        <b>Driver & Salary Period</b>
                    </h5>

                    <hr>

                </div>


                <div class="row">

                    {{-- Driver --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Driver</b>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $salaryProcessing->driver->driver_code }} - {{ $salaryProcessing->driver->first_name }} {{ $salaryProcessing->driver->last_name }}"
                                readonly>

                        </div>

                    </div>


                    {{-- Salary Month --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Salary Month</b>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ date('F', mktime(0, 0, 0, $salaryProcessing->salary_month, 1)) }}"
                                readonly>

                        </div>

                    </div>


                    {{-- Salary Year --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Salary Year</b>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $salaryProcessing->salary_year }}"
                                readonly>

                        </div>

                    </div>

                    {{-- Payroll Status --}}
                    <div class="col-md-4">
                        <div class="form-group">

                            <label>
                                <b>Payroll Status</b>
                            </label>

                            @if ($isLocked)

                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ ucfirst($salaryProcessing->status) }}"
                                    readonly>

                            @else

                                <select
                                    name="status"
                                    id="status"
                                    class="form-control custom-select2 @error('status') is-invalid @enderror"
                                    required>

                                    <option value="draft"
                                        {{ old('status', $salaryProcessing->status) === 'draft' ? 'selected' : '' }}>
                                        Draft
                                    </option>

                                    <option value="processed"
                                        {{ old('status', $salaryProcessing->status) === 'processed' ? 'selected' : '' }}>
                                        Processed
                                    </option>

                                    <option value="approved"
                                        {{ old('status', $salaryProcessing->status) === 'approved' ? 'selected' : '' }}>
                                        Approved
                                    </option>

                                    <option value="paid"
                                        {{ old('status', $salaryProcessing->status) === 'paid' ? 'selected' : '' }}>
                                        Paid
                                    </option>

                                    <option value="cancelled"
                                        {{ old('status', $salaryProcessing->status) === 'cancelled' ? 'selected' : '' }}>
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

                    {{-- ================= ATTENDANCE & WORKING ================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Attendance & Working</b>
                        </h5>

                        <hr>

                    </div>


                    {{-- Total Working Days --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label><b>Total Working Days</b></label>

                            <input
                                type="number"
                                name="total_working_days"
                                min="0"
                                step="0.01"
                                class="form-control @error('total_working_days') is-invalid @enderror"
                                value="{{ old('total_working_days', $salaryProcessing->total_working_days) }}"
                                {{ $isLocked ? 'readonly' : '' }}>

                            @error('total_working_days')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
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
                                min="0"
                                step="0.01"
                                class="form-control @error('present_days') is-invalid @enderror"
                                value="{{ old('present_days', $salaryProcessing->present_days) }}"
                                {{ $isLocked ? 'readonly' : '' }}>

                            @error('present_days')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
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
                                min="0"
                                step="0.01"
                                class="form-control @error('absent_days') is-invalid @enderror"
                                value="{{ old('absent_days', $salaryProcessing->absent_days) }}"
                                {{ $isLocked ? 'readonly' : '' }}>

                            @error('absent_days')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
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
                                min="0"
                                step="0.01"
                                class="form-control @error('paid_days') is-invalid @enderror"
                                value="{{ old('paid_days', $salaryProcessing->paid_days) }}"
                                {{ $isLocked ? 'readonly' : '' }}>

                            @error('paid_days')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- Total Duty Days --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label><b>Total Duty Days</b></label>

                            <input
                                type="number"
                                name="total_duty_days"
                                min="0"
                                step="0.01"
                                class="form-control @error('total_duty_days') is-invalid @enderror"
                                value="{{ old('total_duty_days', $salaryProcessing->total_duty_days) }}"
                                {{ $isLocked ? 'readonly' : '' }}>

                            @error('total_duty_days')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- Total KM --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label><b>Total KM</b></label>

                            <input
                                type="number"
                                name="total_km"
                                min="0"
                                step="0.01"
                                class="form-control @error('total_km') is-invalid @enderror"
                                value="{{ old('total_km', $salaryProcessing->total_km) }}"
                                {{ $isLocked ? 'readonly' : '' }}>

                            @error('total_km')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- Overtime Hours --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label><b>Overtime Hours</b></label>

                            <input
                                type="number"
                                name="overtime_hours"
                                min="0"
                                step="0.01"
                                class="form-control @error('overtime_hours') is-invalid @enderror"
                                value="{{ old('overtime_hours', $salaryProcessing->overtime_hours) }}"
                                {{ $isLocked ? 'readonly' : '' }}>

                            @error('overtime_hours')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- ================= EARNINGS ================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Earnings</b>
                        </h5>

                        <hr>

                    </div>


                    {{-- Basic Salary --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label><b>Basic Salary</b></label>

                            <input
                                type="number"
                                name="basic_salary"
                                min="0"
                                step="0.01"
                                class="form-control @error('basic_salary') is-invalid @enderror"
                                value="{{ old('basic_salary', $salaryProcessing->basic_salary) }}"
                                {{ $isLocked ? 'readonly' : '' }}>

                            @error('basic_salary')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- Working Sheet Base Amount --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label><b>Working Sheet Base Amount</b></label>

                            <input
                                type="number"
                                name="working_sheet_base_amount"
                                min="0"
                                step="0.01"
                                class="form-control @error('working_sheet_base_amount') is-invalid @enderror"
                                value="{{ old('working_sheet_base_amount', $salaryProcessing->working_sheet_base_amount) }}"
                                {{ $isLocked ? 'readonly' : '' }}>

                            @error('working_sheet_base_amount')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- Extra KM Amount --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label><b>Extra KM Amount</b></label>

                            <input
                                type="number"
                                name="extra_km_amount"
                                min="0"
                                step="0.01"
                                class="form-control @error('extra_km_amount') is-invalid @enderror"
                                value="{{ old('extra_km_amount', $salaryProcessing->extra_km_amount) }}"
                                {{ $isLocked ? 'readonly' : '' }}>

                            @error('extra_km_amount')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- Allowance Amount --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label><b>Allowance Amount</b></label>

                            <input
                                type="number"
                                name="allowance_amount"
                                min="0"
                                step="0.01"
                                class="form-control @error('allowance_amount') is-invalid @enderror"
                                value="{{ old('allowance_amount', $salaryProcessing->allowance_amount) }}"
                                {{ $isLocked ? 'readonly' : '' }}>

                            @error('allowance_amount')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
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
                                min="0"
                                step="0.01"
                                class="form-control @error('overtime_amount') is-invalid @enderror"
                                value="{{ old('overtime_amount', $salaryProcessing->overtime_amount) }}"
                                {{ $isLocked ? 'readonly' : '' }}>

                            @error('overtime_amount')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
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
                                min="0"
                                step="0.01"
                                class="form-control @error('expense_reimbursement_amount') is-invalid @enderror"
                                value="{{ old('expense_reimbursement_amount', $salaryProcessing->expense_reimbursement_amount) }}"
                                {{ $isLocked ? 'readonly' : '' }}>

                            @error('expense_reimbursement_amount')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- Working Sheet Other Amount --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label><b>Working Sheet Other Amount</b></label>

                            <input
                                type="number"
                                name="working_sheet_other_amount"
                                min="0"
                                step="0.01"
                                class="form-control @error('working_sheet_other_amount') is-invalid @enderror"
                                value="{{ old('working_sheet_other_amount', $salaryProcessing->working_sheet_other_amount) }}"
                                {{ $isLocked ? 'readonly' : '' }}>

                            @error('working_sheet_other_amount')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- Bonus --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label><b>Bonus</b></label>

                            <input
                                type="number"
                                name="bonus_amount"
                                min="0"
                                step="0.01"
                                class="form-control @error('bonus_amount') is-invalid @enderror"
                                value="{{ old('bonus_amount', $salaryProcessing->bonus_amount) }}"
                                {{ $isLocked ? 'readonly' : '' }}>

                            @error('bonus_amount')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
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
                                min="0"
                                step="0.01"
                                class="form-control @error('other_earnings') is-invalid @enderror"
                                value="{{ old('other_earnings', $salaryProcessing->other_earnings) }}"
                                {{ $isLocked ? 'readonly' : '' }}>

                            @error('other_earnings')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
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
                                class="form-control"
                                value="{{ $salaryProcessing->gross_salary }}"
                                readonly>

                            <small class="text-muted">
                                Automatically calculated by payroll service.
                            </small>

                        </div>

                    </div>


                    {{-- ================= DEDUCTIONS ================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Deductions</b>
                        </h5>

                        <hr>

                    </div>


                    {{-- Advance Deduction --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label><b>Advance Deduction</b></label>

                            <input
                                type="number"
                                name="advance_deduction"
                                min="0"
                                step="0.01"
                                class="form-control @error('advance_deduction') is-invalid @enderror"
                                value="{{ old('advance_deduction', $salaryProcessing->advance_deduction) }}"
                                {{ $isLocked ? 'readonly' : '' }}>

                            @error('advance_deduction')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
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
                                min="0"
                                step="0.01"
                                class="form-control @error('loan_deduction') is-invalid @enderror"
                                value="{{ old('loan_deduction', $salaryProcessing->loan_deduction) }}"
                                {{ $isLocked ? 'readonly' : '' }}>

                            @error('loan_deduction')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
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
                                min="0"
                                step="0.01"
                                class="form-control @error('penalty_deduction') is-invalid @enderror"
                                value="{{ old('penalty_deduction', $salaryProcessing->penalty_deduction) }}"
                                {{ $isLocked ? 'readonly' : '' }}>

                            @error('penalty_deduction')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
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
                                min="0"
                                step="0.01"
                                class="form-control @error('other_deductions') is-invalid @enderror"
                                value="{{ old('other_deductions', $salaryProcessing->other_deductions) }}"
                                {{ $isLocked ? 'readonly' : '' }}>

                            @error('other_deductions')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
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
                                class="form-control"
                                value="{{ $salaryProcessing->total_deductions }}"
                                readonly>

                        </div>

                    </div>


                    {{-- Net Salary --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label><b>Net Salary</b></label>

                            <input
                                type="number"
                                name="net_salary"
                                class="form-control"
                                value="{{ $salaryProcessing->net_salary }}"
                                readonly>

                        </div>

                    </div>


                    {{-- ================= REMARKS ================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="text-primary" style="color:#023a85 !important;">
                            <b>Remarks</b>
                        </h5>

                        <hr>

                    </div>


                    <div class="col-md-12">

                        <div class="form-group">

                            <label><b>Remarks</b></label>

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


                    {{-- ================= ACTION BUTTONS ================= --}}

                    <div class="col-12">

                        <div class="text-right mt-4">

                            <a
                                href="{{ route('salary-processing.index') }}"
                                class="btn btn-danger">

                                <i class="fa fa-times"></i>

                                Cancel

                            </a>

                            @if (!$isLocked)

                                <button
                                    type="submit"
                                    class="btn btn-success">

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
