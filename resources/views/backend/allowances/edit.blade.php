@extends('backend.layouts.master')

@section('title')
    Edit Allowance
@endsection

@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">

        {{-- ================= PAGE HEADER ================= --}}
        <div class="page-header">

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <div class="title">

                        <h4>Edit Allowance</h4>

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
                                Edit Allowance
                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>


        {{-- ================= FORM ================= --}}
        <form
            action="{{ route('allowances.update', $allowance->id) }}"
            method="POST">

            @csrf

            @method('PUT')


            <div class="card-box pd-20 mb-30">


                {{-- ========================================================= --}}
                {{-- BASIC INFORMATION --}}
                {{-- ========================================================= --}}

                <div class="mb-4">

                    <h5
                        class="text-primary"
                        style="color:#023a85 !important;">

                        <b>Basic Information</b>

                    </h5>

                    <hr>

                </div>


                <div class="row">


                    {{-- Allowance Code --}}
                    <div class="col-md-6">

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
                                value="{{ old('allowance_code', $allowance->allowance_code) }}"
                                placeholder="Enter Allowance Code">

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
                    <div class="col-md-6">

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
                                value="{{ old('name', $allowance->name) }}"
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
                                placeholder="Enter Allowance Description">{{ old('description', $allowance->description) }}</textarea>

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

                            <b>Amount / Calculation</b>

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
                                    {{ old('calculation_type', $allowance->calculation_type) === 'fixed' ? 'selected' : '' }}>

                                    Fixed Amount

                                </option>

                                <option
                                    value="per_day"
                                    {{ old('calculation_type', $allowance->calculation_type) === 'per_day' ? 'selected' : '' }}>

                                    Per Day

                                </option>

                                <option
                                    value="per_km"
                                    {{ old('calculation_type', $allowance->calculation_type) === 'per_km' ? 'selected' : '' }}>

                                    Per KM

                                </option>

                                <option
                                    value="per_hour"
                                    {{ old('calculation_type', $allowance->calculation_type) === 'per_hour' ? 'selected' : '' }}>

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
                                value="{{ old('amount', $allowance->amount) }}"
                                placeholder="Enter Amount">

                            <small class="text-muted">
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
                    {{-- STATUS --}}
                    {{-- ========================================================= --}}

                    <div class="col-12 mt-3">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;">

                            <b>Status</b>

                        </h5>

                        <hr>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6">

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

                                <option
                                    value="1"
                                    {{ old('status', $allowance->status ? '1' : '0') == '1' ? 'selected' : '' }}>

                                    Active

                                </option>

                                <option
                                    value="0"
                                    {{ old('status', $allowance->status ? '1' : '0') == '0' ? 'selected' : '' }}>

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

                                Update Allowance

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

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | Allowance Code Formatting
    |--------------------------------------------------------------------------
    */

    $('#allowance_code').on('input', function () {

        this.value = this.value
            .toUpperCase()
            .replace(/\s+/g, '')
            .trim();

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

        let value = $(this).val();

        if (value < 0) {
            $(this).val(0);
        }

    });


});

</script>

@endpush