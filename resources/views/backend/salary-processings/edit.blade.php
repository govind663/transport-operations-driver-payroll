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


    <form method="POST"
          action="{{ route('salary-processing.update', $salaryProcessing->id) }}">

        @csrf

        @method('PUT')


        {{-- ========================================================= --}}
        {{-- Driver & Salary Period --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
                    Driver & Salary Period
                </h5>
            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Driver --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Driver <span class="text-danger">*</span>
                        </label>

                        <select name="driver_id"
                                class="form-control"
                                {{ $isDriver ?? false ? 'disabled' : '' }}>

                            <option value="">
                                Select Driver
                            </option>

                            @foreach ($drivers as $driver)

                                <option value="{{ $driver->id }}"
                                    {{ old('driver_id', $salaryProcessing->driver_id) == $driver->id ? 'selected' : '' }}>

                                    {{ $driver->driver_code }}
                                    -
                                    {{ $driver->first_name }}
                                    {{ $driver->last_name }}

                                </option>

                            @endforeach

                        </select>

                        @if ($isDriver ?? false)

                            <input type="hidden"
                                   name="driver_id"
                                   value="{{ $salaryProcessing->driver_id }}">

                        @endif

                        @error('driver_id')
                            <div class="text-danger small">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Salary Month --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Salary Month <span class="text-danger">*</span>
                        </label>

                        <select name="salary_month"
                                class="form-control">

                            @for ($month = 1; $month <= 12; $month++)

                                <option value="{{ $month }}"
                                    {{ old('salary_month', $salaryProcessing->salary_month) == $month ? 'selected' : '' }}>

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


                    {{-- Salary Year --}}
                    <div class="col-md-2 mb-3">

                        <label class="form-label">
                            Salary Year <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="salary_year"
                               class="form-control"
                               min="2000"
                               max="2100"
                               value="{{ old('salary_year', $salaryProcessing->salary_year) }}">

                        @error('salary_year')
                            <div class="text-danger small">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                                class="form-control">

                            @foreach ($statuses as $status)

                                <option value="{{ $status }}"
                                    {{ old('status', $salaryProcessing->status) == $status ? 'selected' : '' }}>

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


                <div class="row">

                    {{-- Period From --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Period From <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="period_from"
                               class="form-control"
                               value="{{ old('period_from', optional($salaryProcessing->period_from)->format('Y-m-d')) }}">

                        @error('period_from')
                            <div class="text-danger small">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Period To --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Period To <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="period_to"
                               class="form-control"
                               value="{{ old('period_to', optional($salaryProcessing->period_to)->format('Y-m-d')) }}">

                        @error('period_to')
                            <div class="text-danger small">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Attendance / Working --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
                    Attendance & Working
                </h5>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Total Days
                        </label>

                        <input type="number"
                               name="total_days"
                               min="0"
                               class="form-control"
                               value="{{ old('total_days', $salaryProcessing->total_days) }}">

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Present Days
                        </label>

                        <input type="number"
                               name="present_days"
                               min="0"
                               class="form-control"
                               value="{{ old('present_days', $salaryProcessing->present_days) }}">

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Absent Days
                        </label>

                        <input type="number"
                               name="absent_days"
                               min="0"
                               class="form-control"
                               value="{{ old('absent_days', $salaryProcessing->absent_days) }}">

                    </div>


                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            Leave Days
                        </label>

                        <input type="number"
                               name="leave_days"
                               min="0"
                               class="form-control"
                               value="{{ old('leave_days', $salaryProcessing->leave_days) }}">

                    </div>

                </div>


                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Total Hours
                        </label>

                        <input type="number"
                               name="total_hours"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ old('total_hours', $salaryProcessing->total_hours) }}">

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Overtime Hours
                        </label>

                        <input type="number"
                               name="overtime_hours"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ old('overtime_hours', $salaryProcessing->overtime_hours) }}">

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Earnings --}}
        {{-- ========================================================= --}}

        <div class="card mb-4">

            <div class="card-header">
                <h5 class="mb-0">
                    Earnings
                </h5>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Basic Salary <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="basic_salary"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ old('basic_salary', $salaryProcessing->basic_salary) }}">

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
                               value="{{ old('allowance_amount', $salaryProcessing->allowance_amount) }}">

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Overtime Amount
                        </label>

                        <input type="number"
                               name="overtime_amount"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ old('overtime_amount', $salaryProcessing->overtime_amount) }}">

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
                               value="{{ old('bonus_amount', $salaryProcessing->bonus_amount) }}">

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
                               value="{{ old('other_earnings', $salaryProcessing->other_earnings) }}">

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
                               value="{{ old('gross_salary', $salaryProcessing->gross_salary) }}">

                        <small class="text-muted">
                            Automatically calculated on update.
                        </small>

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
                            Advance
                        </label>

                        <input type="number"
                               name="advance_amount"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ old('advance_amount', $salaryProcessing->advance_amount) }}">

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            Deduction
                        </label>

                        <input type="number"
                               name="deduction_amount"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ old('deduction_amount', $salaryProcessing->deduction_amount) }}">

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
                               value="{{ old('other_deductions', $salaryProcessing->other_deductions) }}">

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Total Deductions
                        </label>

                        <input type="number"
                               name="total_deductions"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ old('total_deductions', $salaryProcessing->total_deductions) }}">

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Net Salary
                        </label>

                        <input type="number"
                               name="net_salary"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ old('net_salary', $salaryProcessing->net_salary) }}">

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
                          class="form-control"
                          maxlength="2000"
                          placeholder="Enter remarks">{{ old('remarks', $salaryProcessing->remarks) }}</textarea>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- Actions --}}
        {{-- ========================================================= --}}

        <div class="d-flex justify-content-end gap-2 mb-5">

            <a href="{{ route('salary-processing.index') }}"
               class="btn btn-secondary">

                Cancel

            </a>

            <button type="submit"
                    class="btn btn-primary">

                <i class="fas fa-save"></i>
                Update Salary Processing

            </button>

        </div>

    </form>

</div>

@endsection