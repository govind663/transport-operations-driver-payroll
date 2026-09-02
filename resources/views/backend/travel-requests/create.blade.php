@extends('backend.layouts.master')

@section('title')
    Create Travel Request
@endsection

@push('styles')

<style>

    /*
    |--------------------------------------------------------------------------
    | Form Section
    |--------------------------------------------------------------------------
    */

    .form-section-title {
        color: #023a85 !important;
        font-weight: 600;
        margin-bottom: 10px;
    }


    /*
    |--------------------------------------------------------------------------
    | Required Star
    |--------------------------------------------------------------------------
    */

    .required-star {
        color: #dc3545;
    }


    /*
    |--------------------------------------------------------------------------
    | Form Labels
    |--------------------------------------------------------------------------
    */

    .form-group label {
        margin-bottom: 6px;
    }


    /*
    |--------------------------------------------------------------------------
    | Card
    |--------------------------------------------------------------------------
    */

    .card-box {
        border-radius: 4px;
    }


    /*
    |--------------------------------------------------------------------------
    | Textarea
    |--------------------------------------------------------------------------
    */

    textarea.form-control {
        resize: vertical;
    }


    /*
    |--------------------------------------------------------------------------
    | Readonly Field
    |--------------------------------------------------------------------------
    */

    .readonly-field {
        background-color: #f5f5f5;
        cursor: not-allowed;
    }

</style>

@endpush


@section('content')

<div class="pd-ltr-20 xs-pd-20-10">

    <div class="min-height-200px">


        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="page-header">

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <div class="title">

                        <h4>
                            Create Travel Request
                        </h4>

                    </div>


                    {{-- Breadcrumb --}}

                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item">

                                <a href="{{ route('admin.dashboard') }}">
                                    Dashboard
                                </a>

                            </li>


                            <li class="breadcrumb-item">

                                <a href="{{ route('travel-requests.index') }}">
                                    Travel Requests
                                </a>

                            </li>


                            <li class="breadcrumb-item active">

                                Create Travel Request

                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- FORM --}}
        {{-- ========================================================= --}}

        <form
            action="{{ route('travel-requests.store') }}"
            method="POST"
            id="travelRequestForm">

            @csrf


            <div class="card-box pd-20 mb-30">


                {{-- ===================================================== --}}
                {{-- PART 1 : REQUEST INFORMATION --}}
                {{-- ===================================================== --}}

                <div class="mb-4">

                    <h5 class="form-section-title">

                        <b>
                            1. Travel Request Information
                        </b>

                    </h5>

                    <hr>

                </div>


                <div class="row">


                    {{-- ================================================= --}}
                    {{-- Request Number --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="request_no">

                                <b>
                                    Request Number
                                </b>

                               <span class="required-star">
                                    *
                                </span>

                            </label>


                            <input
                                type="text"
                                name="request_no"
                                id="request_no"
                                class="form-control @error('request_no') is-invalid @enderror"
                                value="{{ old('request_no') }}"
                                maxlength="255"
                                placeholder="Auto Generated if blank">


                            <small class="text-muted">

                                Leave blank to auto-generate.

                                Example:
                                TRV-20260902-ABC123

                            </small>


                            @error('request_no')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- ================================================= --}}
                    {{-- Company Name --}}
                    {{-- ================================================= --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="company_name">

                                <b>

                                    Company Name

                                    <span class="required-star">
                                        *
                                    </span>

                                </b>

                            </label>

                            <input
                                type="text"
                                name="company_name"
                                id="company_name"
                                value="{{ old('company_name') }}"
                                class="form-control @error('company_name') is-invalid @enderror"
                                placeholder="Enter Client / Company Name"
                                maxlength="255"
                            >

                            @error('company_name')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- ================================================= --}}
                    {{-- Requested By --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="requested_by">

                                <b>
                                    Requested By
                                </b>

                            </label>


                            <input
                                name="requested_by"
                                id="requested_by"
                                type="text"
                                class="form-control @error('requested_by') is-invalid @enderror"
                                value="{{ old('requested_by') }}"
                                placeholder="Enter Requester Name">


                            <small class="text-muted">

                                Example: John Doe
                            </small>


                            @error('requested_by')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Employee Email --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="employee_email">

                                <b>
                                    Employee Email
                                </b>

                            </label>


                            <input
                                type="email"
                                name="employee_email"
                                id="employee_email"
                                class="form-control @error('employee_email') is-invalid @enderror"
                                value="{{ old('employee_email') }}"
                                maxlength="255"
                                placeholder="Enter Employee Email">


                            @error('employee_email')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Travel ID --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="travel_id">

                                <b>
                                    Travel ID
                                </b>

                            </label>


                            <input
                                type="text"
                                name="travel_id"
                                id="travel_id"
                                class="form-control @error('travel_id') is-invalid @enderror"
                                value="{{ old('travel_id') }}"
                                maxlength="100"
                                placeholder="Enter Travel ID">


                            @error('travel_id')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Trip ID --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="trip_id">

                                <b>
                                    Trip ID
                                </b>

                            </label>


                            <input
                                type="text"
                                name="trip_id"
                                id="trip_id"
                                class="form-control @error('trip_id') is-invalid @enderror"
                                value="{{ old('trip_id') }}"
                                maxlength="100"
                                placeholder="Enter Trip ID">


                            @error('trip_id')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Vendor --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="vendor_name">

                                <b>
                                    Vendor Name
                                </b>

                            </label>


                            <input
                                type="text"
                                name="vendor_name"
                                id="vendor_name"
                                class="form-control @error('vendor_name') is-invalid @enderror"
                                value="{{ old('vendor_name') }}"
                                maxlength="255"
                                placeholder="Enter Vendor Name">


                            @error('vendor_name')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Vehicle Type --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="vehicle_type">

                                <b>
                                    Vehicle Type
                                </b>

                            </label>


                            <input
                                type="text"
                                name="vehicle_type"
                                id="vehicle_type"
                                class="form-control @error('vehicle_type') is-invalid @enderror"
                                value="{{ old('vehicle_type') }}"
                                maxlength="100"
                                placeholder="Enter Vehicle Type">


                            <small class="text-muted">

                                Example:
                                Sedan, SUV, Innova, Bus

                            </small>


                            @error('vehicle_type')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Employee ID --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="employee_id">

                                <b>
                                    Employee ID
                                </b>

                            </label>


                            <input
                                type="text"
                                name="employee_id"
                                id="employee_id"
                                class="form-control @error('employee_id') is-invalid @enderror"
                                value="{{ old('employee_id') }}"
                                maxlength="100"
                                placeholder="Enter Employee ID">


                            @error('employee_id')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Cost Center --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="cost_center">

                                <b>
                                    Cost Center
                                </b>

                            </label>


                            <input
                                type="text"
                                name="cost_center"
                                id="cost_center"
                                class="form-control @error('cost_center') is-invalid @enderror"
                                value="{{ old('cost_center') }}"
                                maxlength="100"
                                placeholder="Enter Cost Center">


                            @error('cost_center')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                </div>

                                {{-- ===================================================== --}}
                {{-- PART 2 : TRAVEL DATES & LOCATION --}}
                {{-- ===================================================== --}}

                <div class="col-12 mt-4">

                    <h5 class="form-section-title">

                        <b>
                            2. Travel Dates &amp; Location
                        </b>

                    </h5>

                    <hr>

                </div>


                <div class="row">


                    {{-- ================================================= --}}
                    {{-- Travel From Date --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="travel_from_date">

                                <b>
                                    From Date
                                </b>

                            </label>


                            <input
                                type="date"
                                name="travel_from_date"
                                id="travel_from_date"
                                class="form-control @error('travel_from_date') is-invalid @enderror"
                                value="{{ old('travel_from_date') }}">


                            @error('travel_from_date')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Travel To Date --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="travel_to_date">

                                <b>
                                    To Date
                                </b>

                            </label>


                            <input
                                type="date"
                                name="travel_to_date"
                                id="travel_to_date"
                                class="form-control @error('travel_to_date') is-invalid @enderror"
                                value="{{ old('travel_to_date') }}">


                            @error('travel_to_date')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Pickup Time --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="pickup_time">

                                <b>
                                    Pick-up Time
                                </b>

                            </label>


                            <input
                                type="time"
                                name="pickup_time"
                                id="pickup_time"
                                class="form-control @error('pickup_time') is-invalid @enderror"
                                value="{{ old('pickup_time') }}">


                            <small class="text-muted">
                                Format: HH:MM
                            </small>


                            @error('pickup_time')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- From City --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="from_city">

                                <b>
                                    From City
                                </b>

                            </label>


                            <input
                                type="text"
                                name="from_city"
                                id="from_city"
                                class="form-control @error('from_city') is-invalid @enderror"
                                value="{{ old('from_city') }}"
                                maxlength="255"
                                placeholder="Enter From City">


                            @error('from_city')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Pickup Location --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="pickup_location">

                                <b>

                                    Pick-up Location

                                    <span class="required-star">
                                        *
                                    </span>

                                </b>

                            </label>


                            <input
                                type="text"
                                name="pickup_location"
                                id="pickup_location"
                                class="form-control @error('pickup_location') is-invalid @enderror"
                                value="{{ old('pickup_location') }}"
                                maxlength="255"
                                placeholder="Enter Pick-up Location">


                            @error('pickup_location')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Drop Location --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="drop_location">

                                <b>

                                    Drop Location

                                    <span class="required-star">
                                        *
                                    </span>

                                </b>

                            </label>


                            <input
                                type="text"
                                name="drop_location"
                                id="drop_location"
                                class="form-control @error('drop_location') is-invalid @enderror"
                                value="{{ old('drop_location') }}"
                                maxlength="255"
                                placeholder="Enter Drop Location">


                            @error('drop_location')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Release Location --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="release_location">

                                <b>
                                    Release Location
                                </b>

                            </label>


                            <input
                                type="text"
                                name="release_location"
                                id="release_location"
                                class="form-control @error('release_location') is-invalid @enderror"
                                value="{{ old('release_location') }}"
                                maxlength="255"
                                placeholder="Enter Release Location">


                            @error('release_location')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Reporting Address --}}
                    {{-- ================================================= --}}

                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="reporting_address">

                                <b>
                                    Reporting Address
                                </b>

                            </label>


                            <textarea
                                name="reporting_address"
                                id="reporting_address"
                                rows="3"
                                class="form-control @error('reporting_address') is-invalid @enderror"
                                placeholder="Enter Reporting Address">{{ old('reporting_address') }}</textarea>


                            @error('reporting_address')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Release Address --}}
                    {{-- ================================================= --}}

                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="release_address">

                                <b>
                                    Release Address
                                </b>

                            </label>


                            <textarea
                                name="release_address"
                                id="release_address"
                                rows="3"
                                class="form-control @error('release_address') is-invalid @enderror"
                                placeholder="Enter Release Address">{{ old('release_address') }}</textarea>


                            @error('release_address')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Release Time --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="release_time">

                                <b>
                                    Release Time
                                </b>

                            </label>


                            <input
                                type="time"
                                name="release_time"
                                id="release_time"
                                class="form-control @error('release_time') is-invalid @enderror"
                                value="{{ old('release_time') }}">


                            <small class="text-muted">
                                Format: HH:MM
                            </small>


                            @error('release_time')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                </div>

                                {{-- ===================================================== --}}
                {{-- PART 3 : PASSENGER INFORMATION --}}
                {{-- ===================================================== --}}

                <div class="col-12 mt-4">

                    <h5 class="form-section-title">

                        <b>
                            3. Passenger Information
                        </b>

                    </h5>

                    <hr>

                </div>


                <div class="row">


                    {{-- ================================================= --}}
                    {{-- Passenger Name --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="passenger_name">

                                <b>

                                    Passenger Name

                                    <span class="required-star">
                                        *
                                    </span>

                                </b>

                            </label>


                            <input
                                type="text"
                                name="passenger_name"
                                id="passenger_name"
                                class="form-control @error('passenger_name') is-invalid @enderror"
                                value="{{ old('passenger_name') }}"
                                maxlength="255"
                                placeholder="Enter Passenger Name">


                            @error('passenger_name')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Passenger Phone --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="passenger_phone">

                                <b>
                                    Passenger Phone
                                </b>

                            </label>


                            <input
                                type="text"
                                name="passenger_phone"
                                id="passenger_phone"
                                class="form-control @error('passenger_phone') is-invalid @enderror"
                                value="{{ old('passenger_phone') }}"
                                maxlength="255"
                                inputmode="tel"
                                placeholder="Enter Passenger Phone">


                            @error('passenger_phone')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Traveler Mobile --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="traveler_mobile">

                                <b>
                                    Traveler's Mobile
                                </b>

                            </label>


                            <input
                                type="text"
                                name="traveler_mobile"
                                id="traveler_mobile"
                                class="form-control @error('traveler_mobile') is-invalid @enderror"
                                value="{{ old('traveler_mobile') }}"
                                maxlength="20"
                                inputmode="tel"
                                placeholder="Enter Traveler Mobile">


                            @error('traveler_mobile')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Passenger Count --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="passenger_count">

                                <b>

                                    Number of Passengers

                                    <span class="required-star">
                                        *
                                    </span>

                                </b>

                            </label>


                            <input
                                type="number"
                                name="passenger_count"
                                id="passenger_count"
                                class="form-control @error('passenger_count') is-invalid @enderror"
                                value="{{ old('passenger_count', 1) }}"
                                min="1"
                                max="1000"
                                step="1"
                                placeholder="Enter Number of Passengers">


                            @error('passenger_count')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Car Hire Type --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="car_hire_type">

                                <b>
                                    Car Hire Type
                                </b>

                            </label>


                            <input
                                type="text"
                                name="car_hire_type"
                                id="car_hire_type"
                                class="form-control @error('car_hire_type') is-invalid @enderror"
                                value="{{ old('car_hire_type') }}"
                                maxlength="50"
                                placeholder="Enter Car Hire Type">


                            @error('car_hire_type')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- For Use --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="for_use">

                                <b>
                                    For Use
                                </b>

                            </label>


                            <input
                                type="text"
                                name="for_use"
                                id="for_use"
                                class="form-control @error('for_use') is-invalid @enderror"
                                value="{{ old('for_use') }}"
                                maxlength="100"
                                placeholder="Enter Usage Details">


                            @error('for_use')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- GST Number --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="gst_number">

                                <b>
                                    GST Number
                                </b>

                            </label>


                            <input
                                type="text"
                                name="gst_number"
                                id="gst_number"
                                class="form-control @error('gst_number') is-invalid @enderror"
                                value="{{ old('gst_number') }}"
                                maxlength="20"
                                placeholder="Enter GST Number">


                            @error('gst_number')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>


                </div>

                                {{-- ===================================================== --}}
                {{-- PART 4 : INSTRUCTIONS, STATUS & ACTION --}}
                {{-- ===================================================== --}}

                <div class="col-12 mt-4">

                    <h5 class="form-section-title">

                        <b>
                            4. Instructions &amp; Request Status
                        </b>

                    </h5>

                    <hr>

                </div>


                <div class="row">


                    {{-- ================================================= --}}
                    {{-- Purpose --}}
                    {{-- ================================================= --}}

                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="purpose">

                                <b>
                                    Purpose
                                </b>

                            </label>


                            <textarea
                                name="purpose"
                                id="purpose"
                                rows="4"
                                class="form-control @error('purpose') is-invalid @enderror"
                                placeholder="Enter Travel Purpose">{{ old('purpose') }}</textarea>


                            @error('purpose')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Specific Instruction --}}
                    {{-- ================================================= --}}

                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="specific_instruction">

                                <b>
                                    Specific Instruction
                                </b>

                            </label>


                            <textarea
                                name="specific_instruction"
                                id="specific_instruction"
                                rows="4"
                                class="form-control @error('specific_instruction') is-invalid @enderror"
                                placeholder="Enter Specific Instruction">{{ old('specific_instruction') }}</textarea>


                            @error('specific_instruction')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Status --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="status">

                                <b>
                                    Status
                                </b>

                            </label>


                            <select
                                name="status"
                                id="status"
                                class="form-control custom-select2 @error('status') is-invalid @enderror">

                                <option
                                    value="pending"
                                    {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>

                                    Pending

                                </option>


                                <option
                                    value="approved"
                                    {{ old('status') === 'approved' ? 'selected' : '' }}>

                                    Approved

                                </option>


                                <option
                                    value="rejected"
                                    {{ old('status') === 'rejected' ? 'selected' : '' }}>

                                    Rejected

                                </option>


                                <option
                                    value="assigned"
                                    {{ old('status') === 'assigned' ? 'selected' : '' }}>

                                    Assigned

                                </option>


                                <option
                                    value="completed"
                                    {{ old('status') === 'completed' ? 'selected' : '' }}>

                                    Completed

                                </option>


                                <option
                                    value="cancelled"
                                    {{ old('status') === 'cancelled' ? 'selected' : '' }}>

                                    Cancelled

                                </option>

                            </select>


                            <small class="text-muted">

                                Default status is Pending.

                            </small>


                            @error('status')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Remarks --}}
                    {{-- ================================================= --}}

                    <div class="col-md-8">

                        <div class="form-group">

                            <label for="remarks">

                                <b>
                                    Remarks
                                </b>

                            </label>


                            <textarea
                                name="remarks"
                                id="remarks"
                                rows="3"
                                class="form-control @error('remarks') is-invalid @enderror"
                                placeholder="Enter Remarks">{{ old('remarks') }}</textarea>


                            @error('remarks')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- ACTION BUTTONS --}}
                    {{-- ================================================= --}}

                    <div class="col-12">

                        <div class="text-right mt-4">


                            <a
                                href="{{ route('travel-requests.index') }}"
                                class="btn btn-danger">

                                <i class="fa fa-times"></i>

                                Cancel

                            </a>


                            <button
                                type="submit"
                                id="saveTravelRequest"
                                class="btn btn-success">

                                <i class="fa fa-save"></i>

                                Save Travel Request

                            </button>

                        </div>

                    </div>


                </div>

            </div>

        </form>

    </div>


    {{-- Footer --}}

    <x-backend.footer />

</div>

@endsection


@push('scripts')

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Request Number
    |--------------------------------------------------------------------------
    */

    $('#request_no').on('blur', function () {

        this.value = this.value
            .trim()
            .toUpperCase()
            .replace(/\s+/g, '');

    });


    /*
    |--------------------------------------------------------------------------
    | Travel ID
    |--------------------------------------------------------------------------
    */

    $('#travel_id').on('blur', function () {

        this.value = this.value
            .trim()
            .toUpperCase();

    });


    /*
    |--------------------------------------------------------------------------
    | Trip ID
    |--------------------------------------------------------------------------
    */

    $('#trip_id').on('blur', function () {

        this.value = this.value
            .trim()
            .toUpperCase();

    });


    /*
    |--------------------------------------------------------------------------
    | Employee Email
    |--------------------------------------------------------------------------
    */

    $('#employee_email').on('blur', function () {

        this.value = this.value
            .trim()
            .toLowerCase();

    });


    /*
    |--------------------------------------------------------------------------
    | Text Fields
    |--------------------------------------------------------------------------
    */

    const textFields = [
        '#vendor_name',
        '#vehicle_type',
        '#employee_id',
        '#cost_center',
        '#from_city',
        '#pickup_location',
        '#drop_location',
        '#release_location',
        '#passenger_name',
        '#car_hire_type',
        '#for_use'
    ];


    textFields.forEach(function (selector) {

        $(selector).on('blur', function () {

            this.value = this.value
                .replace(/\s+/g, ' ')
                .trim();

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Passenger Phone
    |--------------------------------------------------------------------------
    */

    $('#passenger_phone').on('input', function () {

        this.value = this.value
            .replace(/[^0-9+\-\s()]/g, '')
            .slice(0, 255);

    });


    /*
    |--------------------------------------------------------------------------
    | Traveler Mobile
    |--------------------------------------------------------------------------
    */

    $('#traveler_mobile').on('input', function () {

        this.value = this.value
            .replace(/[^0-9+\-\s()]/g, '')
            .slice(0, 20);

    });


    /*
    |--------------------------------------------------------------------------
    | Passenger Count
    |--------------------------------------------------------------------------
    */

    $('#passenger_count').on('input', function () {

        let value = this.value
            .replace(/[^0-9]/g, '');


        if (value === '') {

            this.value = '';

            return;

        }


        value = parseInt(value, 10);


        if (value < 1) {

            value = 1;

        }


        if (value > 1000) {

            value = 1000;

        }


        this.value = value;

    });


    /*
    |--------------------------------------------------------------------------
    | GST Number
    |--------------------------------------------------------------------------
    */

    $('#gst_number').on('blur', function () {

        this.value = this.value
            .trim()
            .toUpperCase();

    });


    /*
    |--------------------------------------------------------------------------
    | Date Validation
    |--------------------------------------------------------------------------
    */

    $('#travel_from_date, #travel_to_date').on('change', function () {

        const fromDate = $('#travel_from_date').val();
        const toDate = $('#travel_to_date').val();


        if (fromDate && toDate) {

            if (toDate < fromDate) {

                alert(
                    'To Date cannot be before From Date.'
                );

                $('#travel_to_date').val('');

            }

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Release / Reporting Address
    |--------------------------------------------------------------------------
    */

    $('#reporting_address, #release_address').on('blur', function () {

        this.value = this.value
            .replace(/[ \t]+/g, ' ')
            .trim();

    });


    /*
    |--------------------------------------------------------------------------
    | Purpose
    |--------------------------------------------------------------------------
    */

    $('#purpose').on('blur', function () {

        this.value = this.value
            .trim();

    });


    /*
    |--------------------------------------------------------------------------
    | Specific Instruction
    |--------------------------------------------------------------------------
    */

    $('#specific_instruction').on('blur', function () {

        this.value = this.value
            .trim();

    });


    /*
    |--------------------------------------------------------------------------
    | Remarks
    |--------------------------------------------------------------------------
    */

    $('#remarks').on('blur', function () {

        this.value = this.value
            .trim();

    });


    /*
    |--------------------------------------------------------------------------
    | Submit Formatting
    |--------------------------------------------------------------------------
    */

    $('#travelRequestForm').on('submit', function () {


        $('#request_no').val(
            $('#request_no').val()
                .trim()
                .toUpperCase()
                .replace(/\s+/g, '')
        );


        $('#employee_email').val(
            $('#employee_email').val()
                .trim()
                .toLowerCase()
        );


        $('#travel_id').val(
            $('#travel_id').val()
                .trim()
                .toUpperCase()
        );


        $('#trip_id').val(
            $('#trip_id').val()
                .trim()
                .toUpperCase()
        );


        /*
        |--------------------------------------------------------------------------
        | Prevent Double Submission
        |--------------------------------------------------------------------------
        */

        const submitButton = $('#saveTravelRequest');


        if (submitButton.length) {

            submitButton
                .prop('disabled', true)
                .html(
                    '<i class="fa fa-spinner fa-spin"></i> Saving Travel Request...'
                );

        }

    });

});

</script>

@endpush
