@extends('backend.layouts.app')

@section('title', 'Edit Salary Processing')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Edit Salary Processing
            </h4>

            <p class="text-muted mb-0">
                Update salary processing details
            </p>
        </div>

        <a href="{{ route('salary-processing.index') }}"
           class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>

    </div>


    {{-- Validation Errors --}}
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


    {{-- Success / Error Message --}}
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



    {{-- Payroll Edit Form --}}
    @php
        $isLocked = in_array($salaryProcessing->status, [
            \App\Models\SalaryProcessing::STATUS_APPROVED,
            \App\Models\SalaryProcessing::STATUS_PAID,
            \App\Models\SalaryProcessing::STATUS_CANCELLED,
        ]);
    @endphp

    @if ($isLocked)
        <div class="alert alert-warning">
            <i class="fas fa-lock"></i>
            <strong>Payroll Locked!</strong>
            Approved, paid or cancelled payroll cannot be edited.
        </div>
    @endif

    <form method="POST"
        action="{{ route('salary-processing.update', $salaryProcessing->id) }}">

        @csrf
        @method('PUT')

        {{-- Driver & Salary Period --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Driver & Salary Period</h5>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Driver</label>
                        <input type="text" class="form-control"
                            value="{{ $salaryProcessing->driver->driver_code }} - {{ $salaryProcessing->driver->first_name }} {{ $salaryProcessing->driver->last_name }}"
                            readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Salary Month</label>
                        <input type="text" class="form-control"
                            value="{{ date('F', mktime(0, 0, 0, $salaryProcessing->salary_month, 1)) }}"
                            readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Salary Year</label>
                        <input type="text" class="form-control"
                            value="{{ $salaryProcessing->salary_year }}"
                            readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Payroll Status</label>
                        <input type="text" class="form-control"
                            value="{{ ucfirst($salaryProcessing->status) }}"
                            readonly>
                    </div>

                </div>
            </div>
        </div>

        {{-- Attendance & Working --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Attendance & Working</h5>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Total Working Days</label>
                        <input type="number" name="total_working_days"
                            min="0" step="0.01"
                            class="form-control"
                            value="{{ old('total_working_days', $salaryProcessing->total_working_days) }}"
                            {{ $isLocked ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Present Days</label>
                        <input type="number" name="present_days"
                            min="0" step="0.01"
                            class="form-control"
                            value="{{ old('present_days', $salaryProcessing->present_days) }}"
                            {{ $isLocked ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Absent Days</label>
                        <input type="number" name="absent_days"
                            min="0" step="0.01"
                            class="form-control"
                            value="{{ old('absent_days', $salaryProcessing->absent_days) }}"
                            {{ $isLocked ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Paid Days</label>
                        <input type="number" name="paid_days"
                            min="0" step="0.01"
                            class="form-control"
                            value="{{ old('paid_days', $salaryProcessing->paid_days) }}"
                            {{ $isLocked ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Total Duty Days</label>
                        <input type="number" name="total_duty_days"
                            min="0" step="0.01"
                            class="form-control"
                            value="{{ old('total_duty_days', $salaryProcessing->total_duty_days) }}"
                            {{ $isLocked ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Total KM</label>
                        <input type="number" name="total_km"
                            min="0" step="0.01"
                            class="form-control"
                            value="{{ old('total_km', $salaryProcessing->total_km) }}"
                            {{ $isLocked ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Overtime Hours</label>
                        <input type="number" name="overtime_hours"
                            min="0" step="0.01"
                            class="form-control"
                            value="{{ old('overtime_hours', $salaryProcessing->overtime_hours) }}"
                            {{ $isLocked ? 'readonly' : '' }}>
                    </div>

                </div>
            </div>
        </div>

        {{-- Earnings --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Earnings</h5>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Basic Salary</label>
                        <input type="number" name="basic_salary"
                            min="0" step="0.01"
                            class="form-control"
                            value="{{ old('basic_salary', $salaryProcessing->basic_salary) }}"
                            {{ $isLocked ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Working Sheet Base Amount</label>
                        <input type="number" name="working_sheet_base_amount"
                            min="0" step="0.01"
                            class="form-control"
                            value="{{ old('working_sheet_base_amount', $salaryProcessing->working_sheet_base_amount) }}"
                            {{ $isLocked ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Extra KM Amount</label>
                        <input type="number" name="extra_km_amount"
                            min="0" step="0.01"
                            class="form-control"
                            value="{{ old('extra_km_amount', $salaryProcessing->extra_km_amount) }}"
                            {{ $isLocked ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Allowance Amount</label>
                        <input type="number" name="allowance_amount"
                            min="0" step="0.01"
                            class="form-control"
                            value="{{ old('allowance_amount', $salaryProcessing->allowance_amount) }}"
                            {{ $isLocked ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Overtime Amount</label>
                        <input type="number" name="overtime_amount"
                            min="0" step="0.01"
                            class="form-control"
                            value="{{ old('overtime_amount', $salaryProcessing->overtime_amount) }}"
                            {{ $isLocked ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Expense Reimbursement</label>
                        <input type="number" name="expense_reimbursement_amount"
                            min="0" step="0.01"
                            class="form-control"
                            value="{{ old('expense_reimbursement_amount', $salaryProcessing->expense_reimbursement_amount) }}"
                            {{ $isLocked ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Working Sheet Other Amount</label>
                        <input type="number" name="working_sheet_other_amount"
                            min="0" step="0.01"
                            class="form-control"
                            value="{{ old('working_sheet_other_amount', $salaryProcessing->working_sheet_other_amount) }}"
                            {{ $isLocked ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Bonus</label>
                        <input type="number" name="bonus_amount"
                            min="0" step="0.01"
                            class="form-control"
                            value="{{ old('bonus_amount', $salaryProcessing->bonus_amount) }}"
                            {{ $isLocked ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Other Earnings</label>
                        <input type="number" name="other_earnings"
                            min="0" step="0.01"
                            class="form-control"
                            value="{{ old('other_earnings', $salaryProcessing->other_earnings) }}"
                            {{ $isLocked ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Gross Salary</label>
                        <input type="number" name="gross_salary"
                            class="form-control"
                            value="{{ $salaryProcessing->gross_salary }}"
                            readonly>
                        <small class="text-muted">
                            Automatically calculated by payroll service.
                        </small>
                    </div>

                </div>
            </div>
        </div>

        {{-- Deductions --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Deductions</h5>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Advance Deduction</label>
                        <input type="number" name="advance_deduction"
                            min="0" step="0.01"
                            class="form-control"
                            value="{{ old('advance_deduction', $salaryProcessing->advance_deduction) }}"
                            {{ $isLocked ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Loan Deduction</label>
                        <input type="number" name="loan_deduction"
                            min="0" step="0.01"
                            class="form-control"
                            value="{{ old('loan_deduction', $salaryProcessing->loan_deduction) }}"
                            {{ $isLocked ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Penalty Deduction</label>
                        <input type="number" name="penalty_deduction"
                            min="0" step="0.01"
                            class="form-control"
                            value="{{ old('penalty_deduction', $salaryProcessing->penalty_deduction) }}"
                            {{ $isLocked ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Other Deductions</label>
                        <input type="number" name="other_deductions"
                            min="0" step="0.01"
                            class="form-control"
                            value="{{ old('other_deductions', $salaryProcessing->other_deductions) }}"
                            {{ $isLocked ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Total Deductions</label>
                        <input type="number" name="total_deductions"
                            class="form-control"
                            value="{{ $salaryProcessing->total_deductions }}"
                            readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Net Salary</label>
                        <input type="number" name="net_salary"
                            class="form-control"
                            value="{{ $salaryProcessing->net_salary }}"
                            readonly>
                    </div>

                </div>
            </div>
        </div>

        {{-- Remarks --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Remarks</h5>
            </div>

            <div class="card-body">
                <textarea name="remarks"
                        rows="4"
                        class="form-control"
                        maxlength="2000"
                        {{ $isLocked ? 'readonly' : '' }}>{{ old('remarks', $salaryProcessing->remarks) }}</textarea>
            </div>
        </div>

        {{-- Actions --}}
        <div class="d-flex justify-content-end gap-2 mb-5">

            <a href="{{ route('salary-processing.index') }}"
            class="btn btn-secondary">
                Cancel
            </a>

            @if (!$isLocked)
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Payroll
                </button>
            @endif

        </div>

    </form>

</div>

@endsection
