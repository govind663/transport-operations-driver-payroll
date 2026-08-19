@extends('backend.layouts.master')

@section('title')
    Create Allowance
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
                            Create New Allowance
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

                                <a href="{{ route('allowances.index') }}">
                                    Allowances Management
                                </a>

                            </li>

                            <li class="breadcrumb-item active">

                                Create Allowance

                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>


        {{-- ================= FORM ================= --}}
        <form
            action="{{ route('allowances.store') }}"
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


                    {{-- Allowance Code --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>

                                    Allowance Code

                                    <span class="text-danger">*</span>

                                </b>

                            </label>

                            <input
                                type="text"
                                name="allowance_code"
                                id="allowance_code"
                                class="form-control @error('allowance_code') is-invalid @enderror"
                                value="{{ old('allowance_code') }}"
                                placeholder="Enter Allowance Code (e.g. ALW001)">

                            <small class="text-muted">

                                Unique allowance code.

                            </small>

                            @error('allowance_code')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                    {{-- Allowance Name --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>

                                    Allowance Name

                                    <span class="text-danger">*</span>

                                </b>

                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Enter Allowance Name">

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
                                placeholder="Enter allowance description">{{ old('description') }}</textarea>

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
                    {{-- CALCULATION INFORMATION --}}
                    {{-- ========================================================= --}}

                    <div class="col-12 mt-3">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;">

                            <b>
                                Amount / Calculation
                            </b>

                        </h5>

                        <hr>

                    </div>


                    {{-- Calculation Type --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>

                                    Calculation Type

                                    <span class="text-danger">*</span>

                                </b>

                            </label>

                            <select
                                name="calculation_type"
                                id="calculation_type"
                                class="form-control custom-select2 @error('calculation_type') is-invalid @enderror">

                                <option value="">
                                    Select Calculation Type
                                </option>

                                <option
                                    value="fixed"
                                    {{ old('calculation_type') === 'fixed' ? 'selected' : '' }}>

                                    Fixed Amount

                                </option>

                                <option
                                    value="per_day"
                                    {{ old('calculation_type') === 'per_day' ? 'selected' : '' }}>

                                    Per Day

                                </option>

                                <option
                                    value="per_km"
                                    {{ old('calculation_type') === 'per_km' ? 'selected' : '' }}>

                                    Per KM

                                </option>

                                <option
                                    value="per_hour"
                                    {{ old('calculation_type') === 'per_hour' ? 'selected' : '' }}>

                                    Per Hour

                                </option>

                            </select>

                            @error('calculation_type')

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
                                placeholder="Enter Amount">

                            <small
                                class="text-muted"
                                id="amount-help">

                                Enter allowance amount.

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
                                href="{{ route('allowances.index') }}"
                                class="btn btn-danger">

                                <i class="fa fa-times"></i>

                                Cancel

                            </a>

                            <button
                                type="submit"
                                class="btn btn-success">

                                <i class="fa fa-save"></i>

                                Save Allowance

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
| Allowance Code Formatting
|--------------------------------------------------------------------------
*/

$('#allowance_code').on('input', function () {

    this.value = this.value
        .toUpperCase()
        .replace(/\s/g, '');

});


/*
|--------------------------------------------------------------------------
| Allowance Name Formatting
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
| Calculation Type Helper
|--------------------------------------------------------------------------
*/

$('#calculation_type').on('change', function () {

    let type = $(this).val();

    let helpText = 'Enter allowance amount.';

    switch (type) {

        case 'fixed':

            helpText =
                'Enter the fixed allowance amount.';

            break;

        case 'per_day':

            helpText =
                'Amount will be calculated per working day.';

            break;

        case 'per_km':

            helpText =
                'Amount will be calculated per kilometre.';

            break;

        case 'per_hour':

            helpText =
                'Amount will be calculated per hour.';

            break;

    }

    $('#amount-help').text(helpText);

});


/*
|--------------------------------------------------------------------------
| Trigger Initial Calculation Helper
|--------------------------------------------------------------------------
*/

$('#calculation_type').trigger('change');

</script>

@endpush