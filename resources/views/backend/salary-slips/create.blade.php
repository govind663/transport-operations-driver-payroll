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
            method="POST">

            @csrf

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


                    {{-- Salary Processing --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>
                                    Salary Processing
                                    <span class="text-danger">*</span>
                                </b>

                            </label>

                            <select
                                name="salary_processing_id"
                                id="salary_processing_id"
                                class="form-control custom-select2 @error('salary_processing_id') is-invalid @enderror">

                                <option value="">
                                    Select Salary Processing
                                </option>

                                @foreach($salaryProcessings ?? [] as $processing)

                                    <option
                                        value="{{ $processing->id }}"
                                        {{ old('salary_processing_id') == $processing->id ? 'selected' : '' }}>

                                        #{{ $processing->id }}

                                        @if(!empty($processing->period_from))
                                            -
                                            {{ \Carbon\Carbon::parse($processing->period_from)->format('d-m-Y') }}
                                        @endif

                                        @if(!empty($processing->period_to))
                                            to
                                            {{ \Carbon\Carbon::parse($processing->period_to)->format('d-m-Y') }}
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            @error('salary_processing_id')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


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
                                class="form-control custom-select2 @error('driver_id') is-invalid @enderror">

                                <option value="">
                                    Select Driver
                                </option>

                                @foreach($drivers ?? [] as $driver)

                                    <option
                                        value="{{ $driver->id }}"
                                        {{ old('driver_id') == $driver->id ? 'selected' : '' }}>

                                        {{ $driver->name }}

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
                                value="{{ old('salary_month', now()->format('Y-m')) }}">

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
                                value="{{ old('period_from') }}">

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
                                value="{{ old('period_to') }}">

                            @error('period_to')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>


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
                                step="0.01">

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
                                step="0.01">

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
                                step="0.01">

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
                                step="0.01">

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
                                placeholder="Example: 12.50">

                            @error('overtime_hours')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

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
                                step="0.01">

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
                                step="0.01">

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
                                step="0.01">

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

        </form>

    </div>

    <x-backend.footer />

</div>

@endsection


@push('scripts')

<script>

/*
|--------------------------------------------------------------------------
| Calculate Salary Totals
|--------------------------------------------------------------------------
*/

function calculateSalary()
{
    let basic =
        parseFloat($('#basic_salary').val()) || 0;

    let allowance =
        parseFloat($('#allowance_amount').val()) || 0;

    let overtime =
        parseFloat($('#overtime_amount').val()) || 0;

    let bonus =
        parseFloat($('#bonus_amount').val()) || 0;

    let otherEarnings =
        parseFloat($('#other_earnings').val()) || 0;


    /*
    |--------------------------------------------------------------------------
    | Gross Salary
    |--------------------------------------------------------------------------
    */

    let gross =
        basic +
        allowance +
        overtime +
        bonus +
        otherEarnings;


    $('#gross_salary').val(
        gross.toFixed(2)
    );


    /*
    |--------------------------------------------------------------------------
    | Deductions
    |--------------------------------------------------------------------------
    */

    let advance =
        parseFloat($('#advance_deduction').val()) || 0;

    let loan =
        parseFloat($('#loan_deduction').val()) || 0;

    let penalty =
        parseFloat($('#penalty_deduction').val()) || 0;

    let otherDeductions =
        parseFloat($('#other_deductions').val()) || 0;


    let totalDeductions =
        advance +
        loan +
        penalty +
        otherDeductions;


    $('#total_deductions').val(
        totalDeductions.toFixed(2)
    );


    /*
    |--------------------------------------------------------------------------
    | Net Salary
    |--------------------------------------------------------------------------
    */

    let netSalary =
        Math.max(
            0,
            gross - totalDeductions
        );


    $('#net_salary').val(
        netSalary.toFixed(2)
    );
}


/*
|--------------------------------------------------------------------------
| Salary Calculation Events
|--------------------------------------------------------------------------
*/

$(document).on(
    'input',
    '.salary-field, .deduction-field',
    calculateSalary
);


/*
|--------------------------------------------------------------------------
| Calculate On Page Load
|--------------------------------------------------------------------------
*/

$(document).ready(function () {

    calculateSalary();

});


/*
|--------------------------------------------------------------------------
| Salary Month -> Period
|--------------------------------------------------------------------------
*/

$('#salary_month').on('change', function () {

    let value = $(this).val();

    if (!value) {
        return;
    }

    let parts = value.split('-');

    let year = parseInt(parts[0]);
    let month = parseInt(parts[1]);

    let firstDay =
        new Date(year, month - 1, 1);

    let lastDay =
        new Date(year, month, 0);


    let formatDate = function (date) {

        let day =
            String(date.getDate()).padStart(2, '0');

        let month =
            String(date.getMonth() + 1).padStart(2, '0');

        let year =
            date.getFullYear();

        return year + '-' + month + '-' + day;
    };


    $('#period_from').val(
        formatDate(firstDay)
    );

    $('#period_to').val(
        formatDate(lastDay)
    );

});

</script>

@endpush