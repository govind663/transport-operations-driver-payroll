@extends('backend.layouts.master')

@section('title')
Create Salary Slip
@endsection

@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">

        {{-- ================= PAGE HEADER ================= --}}
        <div class="page-header">

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <div class="title">

                        <h4>
                            Salary Slips
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

                            <li class="breadcrumb-item active">
                                Create Salary Slip
                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>


        {{-- ================= FORM ================= --}}
        <form
            action="{{ route('salary-slips.store') }}"
            method="POST"
            id="salarySlipForm"
            autocomplete="off">

            @csrf

            {{-- Working Sheet total is authoritative for gross salary calculation. --}}
            <input type="hidden" id="working_sheet_total_amount" value="0">
            <input type="hidden" id="working_sheet_base_amount_value" value="0">
            <input type="hidden" id="extra_km_amount_value" value="0">
            <input type="hidden" id="expense_reimbursement_amount_value" value="0">
            <input type="hidden" id="working_sheet_other_amount_value" value="0">

            <div class="alert alert-info mb-30">
                <strong><i class="fa fa-info-circle"></i> Automatic Salary Preview</strong>
                <p class="mb-1 mt-1">
                    Select Driver, Salary Month, Period From and Period To. Attendance,
                    approved/completed Working Sheets, monthly salary and paid advance
                    details will be fetched automatically for the selected period.
                </p>
                <small>
                    If Salary Processing already exists for this Driver and Salary Month,
                    generate or recover the Salary Slip from Salary Processing instead.
                </small>
            </div>

            <div class="card-box pd-20 mb-30">


                {{-- ================= BASIC INFORMATION ================= --}}

                <div class="mb-4">

                    <h5
                        class="text-primary"
                        style="color:#023a85 !important;">

                        <b>Salary Slip Information</b>

                    </h5>

                    <hr>

                </div>


                <div class="row">

                    {{-- Driver --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>
                                    Driver
                                    <span class="text-danger">*</span>
                                </b>

                            </label>

                            <select
                                name="driver_id"
                                id="driver_id"
                                class="form-control custom-select2 @error('driver_id') is-invalid @enderror"
                                required>

                                <option value="">
                                    Select Driver
                                </option>

                                @foreach($drivers ?? [] as $driver)

                                    <option
                                        value="{{ $driver->id }}"
                                        {{ old('driver_id') == $driver->id ? 'selected' : '' }}>

                                        {{ $driver->first_name }} {{ $driver->last_name }}

                                        @if(!empty($driver->driver_code))
                                            ({{ $driver->driver_code }})
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            @error('driver_id')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Salary Month --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Salary Month
                                    <span class="text-danger">*</span>
                                </b>

                            </label>

                            <input
                                type="month"
                                name="salary_month"
                                id="salary_month"
                                class="form-control @error('salary_month') is-invalid @enderror"
                                value="{{ old('salary_month', now()->format('Y-m')) }}"
                                required>

                            @error('salary_month')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Period From --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Period From
                                    <span class="text-danger">*</span>
                                </b>

                            </label>

                            <input
                                type="date"
                                name="period_from"
                                id="period_from"
                                class="form-control @error('period_from') is-invalid @enderror"
                                value="{{ old('period_from') }}"
                                required>

                            @error('period_from')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Period To --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Period To
                                    <span class="text-danger">*</span>
                                </b>

                            </label>

                            <input
                                type="date"
                                name="period_to"
                                id="period_to"
                                class="form-control @error('period_to') is-invalid @enderror"
                                value="{{ old('period_to') }}"
                                required>

                            @error('period_to')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ================= AUTOMATIC PREVIEW STATUS ================= --}}

                    <div class="col-12 mb-2">

                        <div id="previewLoading" class="alert alert-info d-none mb-2" role="status">
                            <i class="fa fa-spinner fa-spin"></i>
                            Fetching Attendance, Working Sheets and paid Advance details...
                        </div>

                        <div id="previewError" class="alert alert-danger d-none mb-2" role="alert"></div>

                        <div id="previewSuccess" class="alert alert-success d-none mb-2" role="status">
                            <i class="fa fa-check-circle"></i>
                            Source data loaded successfully. Please review the salary totals before generating the slip.
                        </div>

                    </div>


                    {{-- Source-data fields are shown only after the server preview succeeds. --}}
                    <div id="salaryPreviewPanel" class="col-12 d-none">
                        <div class="row">

                    {{-- ================= ATTENDANCE ================= --}}

                    <div class="col-12 mt-3">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;">

                            <b>Attendance Information</b>

                        </h5>

                        <hr>

                    </div>


                    {{-- Total Working Days --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>Total Working Days</b>
                            </label>

                            <input
                                type="number"
                                name="total_working_days"
                                id="total_working_days"
                                class="form-control @error('total_working_days') is-invalid @enderror"
                                value="{{ old('total_working_days', 0) }}"
                                min="0"
                                step="0.01"
                                readonly>

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

                            <label>
                                <b>Present Days</b>
                            </label>

                            <input
                                type="number"
                                name="present_days"
                                id="present_days"
                                class="form-control @error('present_days') is-invalid @enderror"
                                value="{{ old('present_days', 0) }}"
                                min="0"
                                step="0.01"
                                readonly>

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

                            <label>
                                <b>Absent Days</b>
                            </label>

                            <input
                                type="number"
                                name="absent_days"
                                id="absent_days"
                                class="form-control @error('absent_days') is-invalid @enderror"
                                value="{{ old('absent_days', 0) }}"
                                min="0"
                                step="0.01"
                                readonly>

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

                            <label>
                                <b>Paid Days</b>
                            </label>

                            <input
                                type="number"
                                name="paid_days"
                                id="paid_days"
                                class="form-control @error('paid_days') is-invalid @enderror"
                                value="{{ old('paid_days', 0) }}"
                                min="0"
                                step="0.01"
                                readonly>

                            @error('paid_days')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Overtime Hours --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Overtime Hours</b>
                            </label>

                            <input
                                type="number"
                                name="overtime_hours"
                                id="overtime_hours"
                                class="form-control @error('overtime_hours') is-invalid @enderror"
                                value="{{ old('overtime_hours', 0) }}"
                                min="0"
                                step="0.01"
                                readonly
                                placeholder="Example: 12.50">

                            @error('overtime_hours')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ================= WORKING SHEET SUMMARY ================= --}}

                    <div class="col-12 mt-3">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;">

                            <b>Working Sheet Information</b>

                        </h5>

                        <hr>

                    </div>


                    {{-- Total Duty Days --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><b>Total Duty Days</b></label>
                            <input type="number" id="total_duty_days" class="form-control" value="0" min="0" step="0.01" readonly>
                        </div>
                    </div>


                    {{-- Total KM --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><b>Total KM</b></label>
                            <input type="number" id="total_km" class="form-control" value="0" min="0" step="0.01" readonly>
                        </div>
                    </div>


                    {{-- Working Sheet Base Amount --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><b>Working Sheet Base Amount (₹)</b></label>
                            <input type="number" id="working_sheet_base_amount_display" class="form-control" value="0.00" min="0" step="0.01" readonly>
                        </div>
                    </div>


                    {{-- Extra KM Amount --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><b>Extra KM Amount (₹)</b></label>
                            <input type="number" id="extra_km_amount_display" class="form-control" value="0.00" min="0" step="0.01" readonly>
                        </div>
                    </div>


                    {{-- Expense Reimbursement --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><b>Expense Reimbursement (₹)</b></label>
                            <input type="number" id="expense_reimbursement_amount_display" class="form-control" value="0.00" min="0" step="0.01" readonly>
                        </div>
                    </div>


                    {{-- Working Sheet Other Amount --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><b>Working Sheet Other Amount (₹)</b></label>
                            <input type="number" id="working_sheet_other_amount_display" class="form-control" value="0.00" min="0" step="0.01" readonly>
                        </div>
                    </div>


                    {{-- Working Sheet Total --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><b>Working Sheet Total (₹)</b></label>
                            <input type="number" id="working_sheet_total_amount_display" class="form-control font-weight-bold" value="0.00" min="0" step="0.01" readonly>
                        </div>
                    </div>


                    {{-- ================= EARNINGS ================= --}}

                    <div class="col-12 mt-3">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;">

                            <b>Earnings</b>

                        </h5>

                        <hr>

                    </div>


                    {{-- Basic Salary --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>
                                    Basic Salary
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <input
                                type="number"
                                name="basic_salary"
                                id="basic_salary"
                                class="form-control salary-field @error('basic_salary') is-invalid @enderror"
                                value="{{ old('basic_salary', 0) }}"
                                min="0"
                                step="0.01"
                                readonly
                                placeholder="0.00">

                            @error('basic_salary')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Allowance --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Allowance Amount</b>
                            </label>

                            <input
                                type="number"
                                name="allowance_amount"
                                id="allowance_amount"
                                class="form-control salary-field @error('allowance_amount') is-invalid @enderror"
                                value="{{ old('allowance_amount', 0) }}"
                                min="0"
                                step="0.01"
                                readonly>

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

                            <label>
                                <b>Overtime Amount</b>
                            </label>

                            <input
                                type="number"
                                name="overtime_amount"
                                id="overtime_amount"
                                class="form-control salary-field @error('overtime_amount') is-invalid @enderror"
                                value="{{ old('overtime_amount', 0) }}"
                                min="0"
                                step="0.01"
                                readonly>

                            @error('overtime_amount')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Bonus --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Bonus Amount</b>
                            </label>

                            <input
                                type="number"
                                name="bonus_amount"
                                id="bonus_amount"
                                class="form-control salary-field @error('bonus_amount') is-invalid @enderror"
                                value="{{ old('bonus_amount', 0) }}"
                                min="0"
                                step="0.01">

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

                            <label>
                                <b>Other Earnings</b>
                            </label>

                            <input
                                type="number"
                                name="other_earnings"
                                id="other_earnings"
                                class="form-control salary-field @error('other_earnings') is-invalid @enderror"
                                value="{{ old('other_earnings', 0) }}"
                                min="0"
                                step="0.01">

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

                            <label>
                                <b>Gross Salary</b>
                            </label>

                            <input
                                type="number"
                                name="gross_salary"
                                id="gross_salary"
                                class="form-control"
                                value="{{ old('gross_salary', 0) }}"
                                min="0"
                                step="0.01"
                                readonly>

                        </div>

                    </div>


                    {{-- ================= DEDUCTIONS ================= --}}

                    <div class="col-12 mt-3">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;">

                            <b>Deductions</b>

                        </h5>

                        <hr>

                    </div>


                    {{-- Advance --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>Advance Deduction</b>
                            </label>

                            <input
                                type="number"
                                name="advance_deduction"
                                id="advance_deduction"
                                class="form-control deduction-field @error('advance_deduction') is-invalid @enderror"
                                value="{{ old('advance_deduction', 0) }}"
                                min="0"
                                step="0.01"
                                readonly>

                            @error('advance_deduction')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Loan --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>Loan Deduction</b>
                            </label>

                            <input
                                type="number"
                                name="loan_deduction"
                                id="loan_deduction"
                                class="form-control deduction-field @error('loan_deduction') is-invalid @enderror"
                                value="{{ old('loan_deduction', 0) }}"
                                min="0"
                                step="0.01">

                            @error('loan_deduction')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Penalty --}}
                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>Penalty Deduction</b>
                            </label>

                            <input
                                type="number"
                                name="penalty_deduction"
                                id="penalty_deduction"
                                class="form-control deduction-field @error('penalty_deduction') is-invalid @enderror"
                                value="{{ old('penalty_deduction', 0) }}"
                                min="0"
                                step="0.01">

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

                            <label>
                                <b>Other Deductions</b>
                            </label>

                            <input
                                type="number"
                                name="other_deductions"
                                id="other_deductions"
                                class="form-control deduction-field @error('other_deductions') is-invalid @enderror"
                                value="{{ old('other_deductions', 0) }}"
                                min="0"
                                step="0.01">

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

                            <label>
                                <b>Total Deductions</b>
                            </label>

                            <input
                                type="number"
                                name="total_deductions"
                                id="total_deductions"
                                class="form-control"
                                value="{{ old('total_deductions', 0) }}"
                                min="0"
                                step="0.01"
                                readonly>

                        </div>

                    </div>


                    {{-- Net Salary --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                <b>Net Salary</b>
                            </label>

                            <input
                                type="number"
                                name="net_salary"
                                id="net_salary"
                                class="form-control"
                                value="{{ old('net_salary', 0) }}"
                                min="0"
                                step="0.01"
                                readonly>

                        </div>

                    </div>


                    {{-- ================= STATUS / PAYMENT ================= --}}

                    <div class="col-12 mt-3">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;">

                            <b>Status & Payment</b>

                        </h5>

                        <hr>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>
                                    Status
                                    <span class="text-danger">*</span>
                                </b>
                            </label>

                            <select
                                name="status"
                                id="status"
                                class="form-control custom-select2 @error('status') is-invalid @enderror">

                                <option value="">
                                    Select Status
                                </option>

                                @foreach($statuses ?? [] as $status)

                                    <option
                                        value="{{ $status }}"
                                        {{ old('status', 'generated') == $status ? 'selected' : '' }}>

                                        {{ ucfirst(str_replace('_', ' ', $status)) }}

                                    </option>

                                @endforeach

                            </select>

                            @error('status')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Payment Status --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Payment Status</b>
                            </label>

                            <select
                                name="payment_status"
                                id="payment_status"
                                class="form-control custom-select2 @error('payment_status') is-invalid @enderror">

                                @foreach($paymentStatuses ?? [] as $paymentStatus)

                                    <option
                                        value="{{ $paymentStatus }}"
                                        {{ old('payment_status', 'unpaid') == $paymentStatus ? 'selected' : '' }}>

                                        {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}

                                    </option>

                                @endforeach

                            </select>

                            @error('payment_status')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Payment Date --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Payment Date</b>
                            </label>

                            <input
                                type="date"
                                name="payment_date"
                                id="payment_date"
                                class="form-control @error('payment_date') is-invalid @enderror"
                                value="{{ old('payment_date') }}">

                            @error('payment_date')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ================= REMARKS ================= --}}

                    <div class="col-12 mt-3">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;">

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
                                class="form-control @error('remarks') is-invalid @enderror"
                                placeholder="Enter remarks">{{ old('remarks') }}</textarea>

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
                                href="{{ route('salary-slips.index') }}"
                                class="btn btn-danger">

                                <i class="fa fa-times"></i>

                                Cancel

                            </a>

                            <button
                                type="submit"
                                class="btn btn-success">

                                <i class="fa fa-save"></i>

                                Generate Salary Slip

                            </button>

                        </div>

                    </div>

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
(function ($) {
    'use strict';

    var previewUrl = @json(route('salary-slips.preview'));
    var previewRequest = null;
    var requestSequence = 0;
    var previewLoaded = false;

    function numberValue(selector) {
        var value = parseFloat($(selector).val());
        return Number.isFinite(value) ? value : 0;
    }

    function money(value) {
        var parsed = parseFloat(value);
        return (Number.isFinite(parsed) ? parsed : 0).toFixed(2);
    }

    function setValue(selector, value) {
        $(selector).val(money(value));
    }

    function calculateSalary() {
        var basic = numberValue('#basic_salary');
        var allowance = numberValue('#allowance_amount');
        var overtime = numberValue('#overtime_amount');
        var bonus = numberValue('#bonus_amount');
        var otherEarnings = numberValue('#other_earnings');

        var workingSheetTotal = numberValue('#working_sheet_total_amount');
        var workingSheetComponents =
            numberValue('#working_sheet_base_amount_value') +
            numberValue('#extra_km_amount_value') +
            overtime +
            allowance +
            numberValue('#expense_reimbursement_amount_value') +
            numberValue('#working_sheet_other_amount_value');

        var hasWorkingSheetData =
            workingSheetTotal > 0 || Math.abs(workingSheetComponents) > 0.00001;

        // Match SalarySlipService: stored Working Sheet total takes precedence;
        // otherwise use the component sum; legacy allowance/overtime is fallback.
        var workingSheetEarnings = workingSheetTotal > 0
            ? workingSheetTotal
            : (hasWorkingSheetData ? workingSheetComponents : allowance + overtime);

        var gross = basic + workingSheetEarnings + bonus + otherEarnings;

        var deductions =
            numberValue('#advance_deduction') +
            numberValue('#loan_deduction') +
            numberValue('#penalty_deduction') +
            numberValue('#other_deductions');

        var net = Math.max(0, gross - deductions);

        setValue('#gross_salary', gross);
        setValue('#total_deductions', deductions);
        setValue('#net_salary', net);
    }

    function formatDate(date) {
        var year = date.getFullYear();
        var month = String(date.getMonth() + 1).padStart(2, '0');
        var day = String(date.getDate()).padStart(2, '0');
        return year + '-' + month + '-' + day;
    }

    function setMonthDateRange(monthValue, onlyWhenEmpty) {
        if (!monthValue) return;

        var parts = monthValue.split('-');
        if (parts.length !== 2) return;

        var year = parseInt(parts[0], 10);
        var month = parseInt(parts[1], 10);
        if (!year || month < 1 || month > 12) return;

        var firstDay = formatDate(new Date(year, month - 1, 1));
        var lastDay = formatDate(new Date(year, month, 0));

        if (!onlyWhenEmpty || !$('#period_from').val()) {
            $('#period_from').val(firstDay);
        }
        if (!onlyWhenEmpty || !$('#period_to').val()) {
            $('#period_to').val(lastDay);
        }
    }

    function clearSourceValues() {
        // Prevent an older response from repopulating data for a different selection.
        requestSequence++;
        if (previewRequest && previewRequest.readyState !== 4) {
            previewRequest.abort();
        }

        [
            '#total_working_days', '#present_days', '#absent_days', '#paid_days',
            '#overtime_hours', '#basic_salary', '#allowance_amount',
            '#overtime_amount', '#advance_deduction', '#total_duty_days', '#total_km',
            '#working_sheet_base_amount_display', '#extra_km_amount_display',
            '#expense_reimbursement_amount_display', '#working_sheet_other_amount_display',
            '#working_sheet_total_amount_display', '#working_sheet_total_amount',
            '#working_sheet_base_amount_value', '#extra_km_amount_value',
            '#expense_reimbursement_amount_value', '#working_sheet_other_amount_value'
        ].forEach(function (selector) {
            $(selector).val('0');
        });

        previewLoaded = false;
        $('#salaryPreviewPanel').addClass('d-none');
        $('#previewSuccess').addClass('d-none');
        calculateSalary();
    }

    function showPreviewError(message) {
        $('#previewLoading').addClass('d-none');
        $('#previewSuccess').addClass('d-none');
        $('#previewError').removeClass('d-none').text(message || 'Unable to load salary data. Please check the selected details.');
        $('#salaryPreviewPanel').addClass('d-none');
        previewLoaded = false;
    }

    function populatePreview(response) {
        var preview = response.data || {};
        var data = preview.data || {};

        $('#period_from').val(preview.period_from || $('#period_from').val());
        $('#period_to').val(preview.period_to || $('#period_to').val());

        setValue('#total_working_days', data.total_working_days);
        setValue('#present_days', data.present_days);
        setValue('#absent_days', data.absent_days);
        setValue('#paid_days', data.paid_days);
        setValue('#overtime_hours', data.overtime_hours);

        setValue('#basic_salary', data.basic_salary);
        setValue('#allowance_amount', data.allowance_amount);
        setValue('#overtime_amount', data.overtime_amount);
        setValue('#advance_deduction', data.advance_deduction);

        setValue('#total_duty_days', data.total_duty_days);
        setValue('#total_km', data.total_km);
        setValue('#working_sheet_base_amount_display', data.working_sheet_base_amount);
        setValue('#extra_km_amount_display', data.extra_km_amount);
        setValue('#expense_reimbursement_amount_display', data.expense_reimbursement_amount);
        setValue('#working_sheet_other_amount_display', data.working_sheet_other_amount);
        setValue('#working_sheet_total_amount_display', data.working_sheet_total_amount);

        setValue('#working_sheet_total_amount', data.working_sheet_total_amount);
        setValue('#working_sheet_base_amount_value', data.working_sheet_base_amount);
        setValue('#extra_km_amount_value', data.extra_km_amount);
        setValue('#expense_reimbursement_amount_value', data.expense_reimbursement_amount);
        setValue('#working_sheet_other_amount_value', data.working_sheet_other_amount);

        previewLoaded = true;
        $('#previewLoading').addClass('d-none');
        $('#previewError').addClass('d-none');
        $('#previewSuccess').removeClass('d-none');
        $('#salaryPreviewPanel').removeClass('d-none');

        calculateSalary();
    }

    function loadSalaryPreview() {
        var driverId = $('#driver_id').val();
        var salaryMonth = $('#salary_month').val();
        var periodFrom = $('#period_from').val();
        var periodTo = $('#period_to').val();

        // Wait until all four required selections have been made.
        if (!driverId || !salaryMonth || !periodFrom || !periodTo) {
            return;
        }

        if (periodFrom > periodTo) {
            showPreviewError('Period From cannot be later than Period To.');
            $('#period_to').focus();
            return;
        }

        if (previewRequest && previewRequest.readyState !== 4) {
            previewRequest.abort();
        }

        var currentSequence = ++requestSequence;

        $('#previewError').addClass('d-none').text('');
        $('#previewSuccess').addClass('d-none');
        $('#previewLoading').removeClass('d-none');
        $('#salaryPreviewPanel').addClass('d-none');
        previewLoaded = false;

        previewRequest = $.ajax({
            url: previewUrl,
            method: 'GET',
            dataType: 'json',
            data: {
                driver_id: driverId,
                salary_month: salaryMonth,
                period_from: periodFrom,
                period_to: periodTo
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .done(function (response) {
            if (currentSequence !== requestSequence) return;

            if (!response || response.success !== true) {
                showPreviewError(response && response.message ? response.message : 'Salary preview could not be loaded.');
                return;
            }

            populatePreview(response);
        })
        .fail(function (xhr, status) {
            if (status === 'abort' || currentSequence !== requestSequence) return;

            var message = 'Unable to load salary details. Please try again.';
            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseJSON.errors) {
                    var firstError = Object.values(xhr.responseJSON.errors)[0];
                    if (firstError && firstError.length) message = firstError[0];
                }
            }
            showPreviewError(message);
        });
    }

    // Every one of the four selection controls triggers a fresh server preview.
    $('#driver_id, #salary_month, #period_from, #period_to').on('change', function () {
        if (this.id === 'salary_month') {
            setMonthDateRange($(this).val(), false);
            $('#period_to').attr('min', $('#period_from').val() || '');
            $('#period_from').attr('max', $('#period_to').val() || '');
        }

        clearSourceValues();
        loadSalaryPreview();
    });

    $(document).on('input change', '.salary-field, .deduction-field', calculateSalary);

    $('#period_from').on('change', function () {
        $('#period_to').attr('min', $(this).val() || '');
    });

    $('#period_to').on('change', function () {
        $('#period_from').attr('max', $(this).val() || '');
    });

    $('#salarySlipForm').on('submit', function (event) {
        if (!previewLoaded) {
            event.preventDefault();
            alert('Please select Driver, Salary Month, Period From and Period To and wait for the salary data to load.');
            return false;
        }

        var from = $('#period_from').val();
        var to = $('#period_to').val();

        if (!from || !to || from > to) {
            event.preventDefault();
            alert('Please select a valid salary period. Period From cannot be later than Period To.');
            return false;
        }

        $(this).find('button[type="submit"]')
            .prop('disabled', true)
            .html('<i class="fa fa-spinner fa-spin"></i> Generating...');
    });

    $(function () {
        setMonthDateRange($('#salary_month').val(), true);
        $('#period_to').attr('min', $('#period_from').val() || '');
        $('#period_from').attr('max', $('#period_to').val() || '');

        // Re-fetch automatically after validation redirects when old selections exist.
        if ($('#driver_id').val() && $('#salary_month').val() && $('#period_from').val() && $('#period_to').val()) {
            loadSalaryPreview();
        }

        calculateSalary();
    });
})(jQuery);
</script>

@endpush
