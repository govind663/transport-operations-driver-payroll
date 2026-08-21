@extends('backend.layouts.app')

@section('title', 'Edit Salary Slip')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                Edit Salary Slip
            </h4>

            <p class="text-muted mb-0">
                Update salary slip details
            </p>
        </div>

        <a href="{{ route('salary-slips.index') }}"
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


    <form method="POST"
          action="{{ route('salary-slips.update', $salarySlip->id) }}">

        @csrf

        @method('PUT')


        {{-- ========================================================= --}}
        {{-- Driver & Salary Processing --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Salary Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row">

                    {{-- Driver --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Driver <span class="text-danger">*</span>
                        </label>

                        <select name="driver_id"
                                id="driver_id"
                                class="form-control"
                                {{ auth()->user()->isDriver() ? 'disabled' : '' }}>

                            <option value="">
                                Select Driver
                            </option>

                            @foreach ($drivers as $driver)

                                <option value="{{ $driver->id }}"
                                    {{ old('driver_id', $salarySlip->driver_id) == $driver->id ? 'selected' : '' }}>

                                    {{ $driver->driver_code }}
                                    -
                                    {{ $driver->first_name }}
                                    {{ $driver->last_name }}

                                </option>

                            @endforeach

                        </select>

                        @if (auth()->user()->isDriver())

                            <input type="hidden"
                                   name="driver_id"
                                   value="{{ $salarySlip->driver_id }}">

                        @endif

                        @error('driver_id')

                            <div class="text-danger small">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Salary Processing --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Salary Processing
                        </label>

                        <select name="salary_processing_id"
                                id="salary_processing_id"
                                class="form-control">

                            <option value="">
                                Select Salary Processing
                            </option>

                            @foreach ($salaryProcessings as $processing)

                                <option value="{{ $processing->id }}"
                                    {{ old('salary_processing_id', $salarySlip->salary_processing_id) == $processing->id ? 'selected' : '' }}>

                                    {{ $processing->salary_period }}

                                    -
                                    {{ $processing->driver?->driver_code }}

                                    -
                                    ₹{{ number_format((float) $processing->net_salary, 2) }}

                                </option>

                            @endforeach

                        </select>

                        @error('salary_processing_id')

                            <div class="text-danger small">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Salary Period --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Salary Period
                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Month --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Salary Month
                        </label>

                        <select name="salary_month"
                                class="form-control">

                            @for ($month = 1; $month <= 12; $month++)

                                <option value="{{ $month }}"
                                    {{ old('salary_month', $salarySlip->salary_month) == $month ? 'selected' : '' }}>

                                    {{ date('F', mktime(0, 0, 0, $month, 1)) }}

                                </option>

                            @endfor

                        </select>

                        @error('salary_month')

                            <div class="text-danger small">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Year --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Salary Year
                        </label>

                        <input type="number"
                               name="salary_year"
                               class="form-control"
                               min="2000"
                               max="2100"
                               value="{{ old('salary_year', $salarySlip->salary_year) }}">

                        @error('salary_year')

                            <div class="text-danger small">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="status"
                                class="form-control">

                            @foreach ($statuses as $status)

                                <option value="{{ $status }}"
                                    {{ old('status', $salarySlip->status) == $status ? 'selected' : '' }}>

                                    {{ ucwords(str_replace('_', ' ', $status)) }}

                                </option>

                            @endforeach

                        </select>

                        @error('status')

                            <div class="text-danger small">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Payment --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Payment Information
                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Payment Status --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Payment Status
                        </label>

                        <select name="payment_status"
                                class="form-control">

                            @foreach ($paymentStatuses as $paymentStatus)

                                <option value="{{ $paymentStatus }}"
                                    {{ old('payment_status', $salarySlip->payment_status) == $paymentStatus ? 'selected' : '' }}>

                                    {{ ucwords(str_replace('_', ' ', $paymentStatus)) }}

                                </option>

                            @endforeach

                        </select>

                        @error('payment_status')

                            <div class="text-danger small">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Payment Date --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Payment Date
                        </label>

                        <input type="date"
                               name="payment_date"
                               class="form-control"
                               value="{{ old('payment_date', optional($salarySlip->payment_date)->format('Y-m-d')) }}">

                        @error('payment_date')

                            <div class="text-danger small">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Salary Amount --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Salary Amount
                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Basic Salary
                        </label>

                        <input type="number"
                               name="basic_salary"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ old('basic_salary', $salarySlip->basic_salary) }}">

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Allowance
                        </label>

                        <input type="number"
                               name="allowance_amount"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ old('allowance_amount', $salarySlip->allowance_amount) }}">

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Overtime
                        </label>

                        <input type="number"
                               name="overtime_amount"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ old('overtime_amount', $salarySlip->overtime_amount) }}">

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Bonus
                        </label>

                        <input type="number"
                               name="bonus_amount"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ old('bonus_amount', $salarySlip->bonus_amount) }}">

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Other Earnings
                        </label>

                        <input type="number"
                               name="other_earnings"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ old('other_earnings', $salarySlip->other_earnings) }}">

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Gross Salary
                        </label>

                        <input type="number"
                               name="gross_salary"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ old('gross_salary', $salarySlip->gross_salary) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Deductions --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Deductions
                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Advance Deduction
                        </label>

                        <input type="number"
                               name="advance_deduction"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ old('advance_deduction', $salarySlip->advance_deduction) }}">

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Loan Deduction
                        </label>

                        <input type="number"
                               name="loan_deduction"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ old('loan_deduction', $salarySlip->loan_deduction) }}">

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Penalty Deduction
                        </label>

                        <input type="number"
                               name="penalty_deduction"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ old('penalty_deduction', $salarySlip->penalty_deduction) }}">

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Other Deductions
                        </label>

                        <input type="number"
                               name="other_deductions"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ old('other_deductions', $salarySlip->other_deductions) }}">

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Total Deductions
                        </label>

                        <input type="number"
                               name="total_deductions"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ old('total_deductions', $salarySlip->total_deductions) }}">

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Net Salary
                        </label>

                        <input type="number"
                               name="net_salary"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ old('net_salary', $salarySlip->net_salary) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Remarks --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Remarks
                </h5>

            </div>

            <div class="card-body">

                <textarea name="remarks"
                          rows="4"
                          maxlength="2000"
                          class="form-control"
                          placeholder="Enter remarks">{{ old('remarks', $salarySlip->remarks) }}</textarea>

                @error('remarks')

                    <div class="text-danger small">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Actions --}}
        {{-- ========================================================= --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a href="{{ route('salary-slips.index') }}"
               class="btn btn-secondary">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="fas fa-save"></i>

                Update Salary Slip

            </button>

        </div>

    </form>

</div>

@endsection