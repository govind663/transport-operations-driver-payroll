@extends('backend.layouts.master')

@section('title')
    Create Expense
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
                            Create New Expense
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

                                <a href="{{ route('expenses.index') }}">
                                    Expenses Management
                                </a>

                            </li>

                            <li class="breadcrumb-item active">

                                Create Expense

                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>


        {{-- ================= FORM ================= --}}

        <form
            action="{{ route('expenses.store') }}"
            method="POST">

            @csrf

            <div class="card-box pd-20 mb-30">

                <div class="row">


                    {{-- ========================================================= --}}
                    {{-- BASIC INFORMATION --}}
                    {{-- ========================================================= --}}

                    <div class="col-12">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;">

                            <b>
                                Basic Information
                            </b>

                        </h5>

                        <hr>

                    </div>


                    {{-- Expense Code --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>

                                    Expense Code

                                    <span class="text-danger">*</span>

                                </b>

                            </label>

                            <input
                                type="text"
                                name="expense_code"
                                id="expense_code"
                                class="form-control @error('expense_code') is-invalid @enderror"
                                value="{{ old('expense_code') }}"
                                placeholder="Enter Expense Code (e.g. EXP001)">

                            <small class="text-muted">

                                Unique expense code.

                            </small>

                            @error('expense_code')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Expense Name --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>

                                    Expense Name

                                    <span class="text-danger">*</span>

                                </b>

                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Enter Expense Name">

                            @error('name')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

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
                                class="form-control custom-select2 @error('status') is-invalid @enderror">

                                <option
                                    value="1"
                                    {{ old('status', 1) == 1 ? 'selected' : '' }}>

                                    Active

                                </option>

                                <option
                                    value="0"
                                    {{ old('status') === '0' ? 'selected' : '' }}>

                                    Inactive

                                </option>

                            </select>

                            @error('status')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Description --}}
                    <div class="col-md-12">

                        <div class="form-group">

                            <label>

                                <b>
                                    Description
                                </b>

                            </label>

                            <textarea
                                name="description"
                                id="description"
                                rows="4"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Enter expense description">{{ old('description') }}</textarea>

                            @error('description')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- EXPENSE INFORMATION --}}
                    {{-- ========================================================= --}}

                    <div class="col-12 mt-3">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;">

                            <b>
                                Expense Information
                            </b>

                        </h5>

                        <hr>

                    </div>


                    {{-- Expense Type --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>

                                    Expense Type

                                    <span class="text-danger">*</span>

                                </b>

                            </label>

                            <select
                                name="expense_type"
                                id="expense_type"
                                class="form-control custom-select2 @error('expense_type') is-invalid @enderror">

                                <option value="">
                                    Select Expense Type
                                </option>

                                <option
                                    value="fuel"
                                    {{ old('expense_type') === 'fuel' ? 'selected' : '' }}>

                                    Fuel

                                </option>

                                <option
                                    value="toll"
                                    {{ old('expense_type') === 'toll' ? 'selected' : '' }}>

                                    Toll

                                </option>

                                <option
                                    value="parking"
                                    {{ old('expense_type') === 'parking' ? 'selected' : '' }}>

                                    Parking

                                </option>

                                <option
                                    value="food"
                                    {{ old('expense_type') === 'food' ? 'selected' : '' }}>

                                    Food

                                </option>

                                <option
                                    value="maintenance"
                                    {{ old('expense_type') === 'maintenance' ? 'selected' : '' }}>

                                    Maintenance

                                </option>

                                <option
                                    value="repair"
                                    {{ old('expense_type') === 'repair' ? 'selected' : '' }}>

                                    Repair

                                </option>

                                <option
                                    value="miscellaneous"
                                    {{ old('expense_type') === 'miscellaneous' ? 'selected' : '' }}>

                                    Miscellaneous

                                </option>

                            </select>

                            @error('expense_type')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Amount --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>

                                    Amount

                                    <span class="text-danger">*</span>

                                </b>

                            </label>

                            <input
                                type="number"
                                name="amount"
                                id="amount"
                                step="0.01"
                                min="0"
                                class="form-control @error('amount') is-invalid @enderror"
                                value="{{ old('amount', '0.00') }}"
                                placeholder="Enter Expense Amount">

                            <small class="text-muted">

                                Enter the default expense amount.

                            </small>

                            @error('amount')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- ACTION BUTTONS --}}
                    {{-- ========================================================= --}}

                    <div class="col-12">

                        <div class="text-right mt-4">

                            <a
                                href="{{ route('expenses.index') }}"
                                class="btn btn-danger">

                                <i class="fa fa-times"></i>

                                Cancel

                            </a>

                            <button
                                type="submit"
                                class="btn btn-success">

                                <i class="fa fa-save"></i>

                                Save Expense

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
| Expense Code Formatting
|--------------------------------------------------------------------------
*/

$('#expense_code').on('input', function () {

    this.value = this.value
        .toUpperCase()
        .replace(/\s/g, '');

});


/*
|--------------------------------------------------------------------------
| Expense Name Formatting
|--------------------------------------------------------------------------
*/

$('#name').on('blur', function () {

    let value = $(this).val();

    value = value
        .replace(/\s+/g, ' ')
        .trim();

    $(this).val(value);

});


/*
|--------------------------------------------------------------------------
| Amount Validation
|--------------------------------------------------------------------------
*/

$('#amount').on('input', function () {

    let value = this.value;

    if (value < 0) {

        this.value = 0;

    }

});


/*
|--------------------------------------------------------------------------
| Expense Type Change
|--------------------------------------------------------------------------
*/

$('#expense_type').on('change', function () {

    let type = $(this).val();

    let amountPlaceholder =
        'Enter Expense Amount';

    switch (type) {

        case 'fuel':

            amountPlaceholder =
                'Enter Fuel Expense Amount';

            break;

        case 'toll':

            amountPlaceholder =
                'Enter Toll Expense Amount';

            break;

        case 'parking':

            amountPlaceholder =
                'Enter Parking Expense Amount';

            break;

        case 'food':

            amountPlaceholder =
                'Enter Food Expense Amount';

            break;

        case 'maintenance':

            amountPlaceholder =
                'Enter Maintenance Expense Amount';

            break;

        case 'repair':

            amountPlaceholder =
                'Enter Repair Expense Amount';

            break;

        case 'miscellaneous':

            amountPlaceholder =
                'Enter Miscellaneous Expense Amount';

            break;

    }

    $('#amount').attr(
        'placeholder',
        amountPlaceholder
    );

});


/*
|--------------------------------------------------------------------------
| Trigger Initial Expense Type
|--------------------------------------------------------------------------
*/

$('#expense_type').trigger('change');

</script>

@endpush