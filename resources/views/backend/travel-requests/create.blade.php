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
    | Table / Form Alignment
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
    | Status Badge Preview
    |--------------------------------------------------------------------------
    */

    .status-preview {

        margin-top: 5px;

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

                {{-- Page Title --}}
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
                {{-- TRAVEL REQUEST INFORMATION --}}
                {{-- ===================================================== --}}

                <div class="mb-4">

                    <h5 class="form-section-title">

                        <b>
                            Travel Request Information
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

                                    <span class="required-star">
                                        *
                                    </span>

                                </b>

                            </label>


                            <input
                                type="text"
                                name="request_no"
                                id="request_no"
                                class="form-control @error('request_no') is-invalid @enderror"
                                value="{{ old('request_no') }}"
                                maxlength="100"
                                placeholder="Enter Travel Request Number">


                            <small class="text-muted">

                                Example:
                                TRV001, TRV002

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
                    {{-- Client --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="client_id">

                                <b>

                                    Client

                                    <span class="required-star">
                                        *
                                    </span>

                                </b>

                            </label>


                            <select
                                name="client_id"
                                id="client_id"
                                class="form-control custom-select2 @error('client_id') is-invalid @enderror">

                                <option value="">

                                    Select Client

                                </option>


                                @foreach($clients as $client)

                                    <option
                                        value="{{ $client->id }}"
                                        {{ old('client_id') == $client->id ? 'selected' : '' }}>

                                        {{ $client->company_name }} ({{ $client->client_code }})

                                    </option>

                                @endforeach

                            </select>


                            @error('client_id')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Travel Date --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="travel_date">

                                <b>

                                    Travel Date

                                    <span class="required-star">
                                        *
                                    </span>

                                </b>

                            </label>


                            <input
                                type="date"
                                name="travel_date"
                                id="travel_date"
                                class="form-control @error('travel_date') is-invalid @enderror"
                                value="{{ old('travel_date') }}">


                            @error('travel_date')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- PICKUP / DROP --}}
                    {{-- ================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="form-section-title">

                            <b>
                                Travel Details
                            </b>

                        </h5>

                        <hr>

                    </div>



                    {{-- Pickup Location --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="pickup_location">

                                <b>

                                    Pickup Location

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
                                placeholder="Enter Pickup Location">


                            @error('pickup_location')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- Drop Location --}}
                    <div class="col-md-6">

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
                    {{-- Reporting Time --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="reporting_time">

                                <b>
                                    Reporting Time
                                </b>

                            </label>


                            <input
                                type="time"
                                name="reporting_time"
                                id="reporting_time"
                                class="form-control @error('reporting_time') is-invalid @enderror"
                                value="{{ old('reporting_time') }}">


                            <small class="text-muted">

                                Format: HH:MM

                            </small>


                            @error('reporting_time')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Passenger Information --}}
                    {{-- ================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="form-section-title">

                            <b>
                                Passenger Information
                            </b>

                        </h5>

                        <hr>

                    </div>



                    {{-- Passenger Name --}}
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



                    {{-- Passenger Mobile --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="passenger_mobile">

                                <b>

                                    Passenger Mobile

                                    <span class="required-star">
                                        *
                                    </span>

                                </b>

                            </label>


                            <input
                                type="text"
                                name="passenger_mobile"
                                id="passenger_mobile"
                                class="form-control @error('passenger_mobile') is-invalid @enderror"
                                value="{{ old('passenger_mobile') }}"
                                maxlength="10"
                                inputmode="numeric"
                                placeholder="Enter 10 Digit Mobile Number">


                            @error('passenger_mobile')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- Passenger Email --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="passenger_email">

                                <b>
                                    Passenger Email
                                </b>

                            </label>


                            <input
                                type="email"
                                name="passenger_email"
                                id="passenger_email"
                                class="form-control @error('passenger_email') is-invalid @enderror"
                                value="{{ old('passenger_email') }}"
                                maxlength="255"
                                placeholder="Enter Passenger Email">


                            @error('passenger_email')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Number Of Passengers --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="number_of_passengers">

                                <b>

                                    Number Of Passengers

                                    <span class="required-star">
                                        *
                                    </span>

                                </b>

                            </label>


                            <input
                                type="number"
                                name="number_of_passengers"
                                id="number_of_passengers"
                                class="form-control @error('number_of_passengers') is-invalid @enderror"
                                value="{{ old('number_of_passengers', 1) }}"
                                min="1"
                                step="1"
                                placeholder="Enter Number Of Passengers">


                            @error('number_of_passengers')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Purpose --}}
                    {{-- ================================================= --}}

                    <div class="col-md-8">

                        <div class="form-group">

                            <label for="purpose">

                                <b>
                                    Purpose
                                </b>

                            </label>


                            <textarea
                                name="purpose"
                                id="purpose"
                                rows="3"
                                maxlength="1000"
                                class="form-control @error('purpose') is-invalid @enderror"
                                placeholder="Enter Travel Purpose">{{ old('purpose') }}</textarea>


                            <small class="text-muted">

                                Maximum 1000 characters.

                            </small>


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
                    {{-- STATUS --}}
                    {{-- ================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="form-section-title">

                            <b>
                                Request Status
                            </b>

                        </h5>

                        <hr>

                    </div>



                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="status">

                                <b>

                                    Status

                                    <span class="required-star">
                                        *
                                    </span>

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
                                    value="in_progress"
                                    {{ old('status') === 'in_progress' ? 'selected' : '' }}>

                                    In Progress

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
                    {{-- REMARKS --}}
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
                                maxlength="2000"
                                class="form-control @error('remarks') is-invalid @enderror"
                                placeholder="Enter Remarks">{{ old('remarks') }}</textarea>


                            <small class="text-muted">

                                Maximum 2000 characters.

                            </small>


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
    | Request Number Formatting
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
    | Passenger Name Formatting
    |--------------------------------------------------------------------------
    */

    $('#passenger_name').on('blur', function () {

        this.value = this.value
            .replace(/\s+/g, ' ')
            .trim();

    });



    /*
    |--------------------------------------------------------------------------
    | Pickup Location Formatting
    |--------------------------------------------------------------------------
    */

    $('#pickup_location').on('blur', function () {

        this.value = this.value
            .replace(/\s+/g, ' ')
            .trim();

    });



    /*
    |--------------------------------------------------------------------------
    | Drop Location Formatting
    |--------------------------------------------------------------------------
    */

    $('#drop_location').on('blur', function () {

        this.value = this.value
            .replace(/\s+/g, ' ')
            .trim();

    });



    /*
    |--------------------------------------------------------------------------
    | Passenger Mobile
    |--------------------------------------------------------------------------
    */

    $('#passenger_mobile').on('input', function () {

        this.value = this.value
            .replace(/[^0-9]/g, '')
            .slice(0, 10);

    });



    /*
    |--------------------------------------------------------------------------
    | Passenger Email
    |--------------------------------------------------------------------------
    */

    $('#passenger_email').on('blur', function () {

        this.value = this.value
            .trim()
            .toLowerCase();

    });



    /*
    |--------------------------------------------------------------------------
    | Number Of Passengers
    |--------------------------------------------------------------------------
    */

    $('#number_of_passengers').on('input', function () {

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

        this.value = value;

    });



    /*
    |--------------------------------------------------------------------------
    | Purpose Formatting
    |--------------------------------------------------------------------------
    */

    $('#purpose').on('blur', function () {

        this.value = this.value
            .replace(/\s+/g, ' ')
            .trim();

    });



    /*
    |--------------------------------------------------------------------------
    | Remarks Formatting
    |--------------------------------------------------------------------------
    */

    $('#remarks').on('blur', function () {

        this.value = this.value
            .replace(/\s+/g, ' ')
            .trim();

    });



    /*
    |--------------------------------------------------------------------------
    | Travel Date Validation
    |--------------------------------------------------------------------------
    */

    $('#travel_date').on('change', function () {

        const travelDate = this.value;

        if (!travelDate) {

            return;

        }

        const selectedDate = new Date(travelDate);

        selectedDate.setHours(0, 0, 0, 0);


        /*
        |--------------------------------------------------------------------------
        | Current Date
        |--------------------------------------------------------------------------
        */

        const today = new Date();

        today.setHours(0, 0, 0, 0);


        /*
        |--------------------------------------------------------------------------
        | Travel date is allowed for today/future.
        |--------------------------------------------------------------------------
        */

        if (selectedDate < today) {

            alert(
                'Travel date cannot be before today.'
            );

            this.value = '';

        }

    });



    /*
    |--------------------------------------------------------------------------
    | Trim Before Submit
    |--------------------------------------------------------------------------
    */

    $('#travelRequestForm').on('submit', function () {


        /*
        |--------------------------------------------------------------------------
        | Request Number
        |--------------------------------------------------------------------------
        */

        $('#request_no').val(

            $('#request_no').val()
                .trim()
                .toUpperCase()
                .replace(/\s+/g, '')

        );


        /*
        |--------------------------------------------------------------------------
        | Passenger Name
        |--------------------------------------------------------------------------
        */

        $('#passenger_name').val(

            $('#passenger_name').val()
                .replace(/\s+/g, ' ')
                .trim()

        );


        /*
        |--------------------------------------------------------------------------
        | Pickup
        |--------------------------------------------------------------------------
        */

        $('#pickup_location').val(

            $('#pickup_location').val()
                .replace(/\s+/g, ' ')
                .trim()

        );


        /*
        |--------------------------------------------------------------------------
        | Drop
        |--------------------------------------------------------------------------
        */

        $('#drop_location').val(

            $('#drop_location').val()
                .replace(/\s+/g, ' ')
                .trim()

        );


        /*
        |--------------------------------------------------------------------------
        | Passenger Mobile
        |--------------------------------------------------------------------------
        */

        $('#passenger_mobile').val(

            $('#passenger_mobile').val()
                .replace(/[^0-9]/g, '')
                .slice(0, 10)

        );


        /*
        |--------------------------------------------------------------------------
        | Passenger Email
        |--------------------------------------------------------------------------
        */

        $('#passenger_email').val(

            $('#passenger_email').val()
                .trim()
                .toLowerCase()

        );


        /*
        |--------------------------------------------------------------------------
        | Purpose
        |--------------------------------------------------------------------------
        */

        $('#purpose').val(

            $('#purpose').val()
                .replace(/\s+/g, ' ')
                .trim()

        );


        /*
        |--------------------------------------------------------------------------
        | Remarks
        |--------------------------------------------------------------------------
        */

        $('#remarks').val(

            $('#remarks').val()
                .replace(/\s+/g, ' ')
                .trim()

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