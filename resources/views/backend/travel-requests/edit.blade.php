@extends('backend.layouts.master')

@section('title')
    Edit Travel Request
@endsection

@push('styles')

<style>

    /*
    |--------------------------------------------------------------------------
    | Section Heading
    |--------------------------------------------------------------------------
    */

    .form-section-title {
        color: #023a85;
        font-weight: 700;
        font-size: 17px;
    }


    /*
    |--------------------------------------------------------------------------
    | Form Labels
    |--------------------------------------------------------------------------
    */

    .form-group label {
        font-weight: 600;
    }


    /*
    |--------------------------------------------------------------------------
    | Required
    |--------------------------------------------------------------------------
    */

    .required {
        color: #dc3545;
    }


    /*
    |--------------------------------------------------------------------------
    | Status Badge
    |--------------------------------------------------------------------------
    */

    .status-preview {
        margin-top: 8px;
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

                <div class="col-md-8 col-sm-12">

                    <div class="title">

                        <h4>
                            Edit Travel Request
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

                                <a href="{{ route('travel-requests.index') }}">
                                    Travel Requests
                                </a>

                            </li>

                            <li class="breadcrumb-item active">

                                Edit Travel Request

                            </li>

                        </ol>

                    </nav>

                </div>


                {{-- Request Number --}}
                <div class="col-md-4 col-sm-12 text-right">

                    <span class="badge badge-primary px-3 py-2">

                        {{ $travelRequest->request_no }}

                    </span>

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- VALIDATION ERRORS --}}
        {{-- ========================================================= --}}

        @if ($errors->any())

            <div class="alert alert-danger">

                <strong>
                    Please correct the following errors:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif



        {{-- ========================================================= --}}
        {{-- FORM --}}
        {{-- ========================================================= --}}

        <form
            action="{{ route(
                'travel-requests.update',
                $travelRequest->id
            ) }}"
            method="POST">

            @csrf

            @method('PUT')


            <div class="card-box pd-20 mb-30">


                {{-- ================================================= --}}
                {{-- TRAVEL REQUEST INFORMATION --}}
                {{-- ================================================= --}}

                <div class="mb-4">

                    <h5 class="form-section-title">

                        <i class="fa fa-plane mr-2"></i>

                        Travel Request Information

                    </h5>

                    <hr>

                </div>


                <div class="row">


                    {{-- ================================================= --}}
                    {{-- REQUEST NUMBER --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                Request Number

                                <span class="required">
                                    *
                                </span>

                            </label>

                            <input
                                type="text"
                                name="request_no"
                                id="request_no"
                                class="form-control @error('request_no') is-invalid @enderror"
                                value="{{ old(
                                    'request_no',
                                    $travelRequest->request_no
                                ) }}"
                                placeholder="Enter Request Number">

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
                    {{-- CLIENT --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                Client

                                <span class="required">
                                    *
                                </span>

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
                                        {{ (string) old(
                                            'client_id',
                                            $travelRequest->client_id
                                        ) === (string) $client->id
                                            ? 'selected'
                                            : '' }}>

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
                    {{-- TRAVEL DATE --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                Travel Date

                                <span class="required">
                                    *
                                </span>

                            </label>

                            <input
                                type="date"
                                name="travel_date"
                                id="travel_date"
                                class="form-control @error('travel_date') is-invalid @enderror"
                                value="{{ old(
                                    'travel_date',
                                    optional($travelRequest->travel_date)
                                        ->format('Y-m-d')
                                ) }}">

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
                    {{-- PICKUP LOCATION --}}
                    {{-- ================================================= --}}

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                Pickup Location

                                <span class="required">
                                    *
                                </span>

                            </label>

                            <input
                                type="text"
                                name="pickup_location"
                                id="pickup_location"
                                class="form-control @error('pickup_location') is-invalid @enderror"
                                value="{{ old(
                                    'pickup_location',
                                    $travelRequest->pickup_location
                                ) }}"
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



                    {{-- ================================================= --}}
                    {{-- DROP LOCATION --}}
                    {{-- ================================================= --}}

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                Drop Location

                                <span class="required">
                                    *
                                </span>

                            </label>

                            <input
                                type="text"
                                name="drop_location"
                                id="drop_location"
                                class="form-control @error('drop_location') is-invalid @enderror"
                                value="{{ old(
                                    'drop_location',
                                    $travelRequest->drop_location
                                ) }}"
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
                    {{-- REPORTING TIME --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                Reporting Time

                            </label>

                            <input
                                type="time"
                                name="reporting_time"
                                id="reporting_time"
                                class="form-control @error('reporting_time') is-invalid @enderror"
                                value="{{ old(
                                    'reporting_time',
                                    $travelRequest->reporting_time
                                ) }}">

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
                    {{-- NUMBER OF PASSENGERS --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                Number Of Passengers

                                <span class="required">
                                    *
                                </span>

                            </label>

                            <input
                                type="number"
                                name="number_of_passengers"
                                id="number_of_passengers"
                                min="1"
                                class="form-control @error('number_of_passengers') is-invalid @enderror"
                                value="{{ old(
                                    'number_of_passengers',
                                    $travelRequest->number_of_passengers
                                ) }}"
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
                    {{-- STATUS --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                Status

                                <span class="required">
                                    *
                                </span>

                            </label>

                            <select
                                name="status"
                                id="status"
                                class="form-control custom-select2 @error('status') is-invalid @enderror">

                                <option value="">
                                    Select Status
                                </option>

                                <option
                                    value="pending"
                                    {{ old(
                                        'status',
                                        $travelRequest->status
                                    ) === 'pending'
                                        ? 'selected'
                                        : '' }}>

                                    Pending

                                </option>

                                <option
                                    value="approved"
                                    {{ old(
                                        'status',
                                        $travelRequest->status
                                    ) === 'approved'
                                        ? 'selected'
                                        : '' }}>

                                    Approved

                                </option>

                                <option
                                    value="rejected"
                                    {{ old(
                                        'status',
                                        $travelRequest->status
                                    ) === 'rejected'
                                        ? 'selected'
                                        : '' }}>

                                    Rejected

                                </option>

                                <option
                                    value="assigned"
                                    {{ old(
                                        'status',
                                        $travelRequest->status
                                    ) === 'assigned'
                                        ? 'selected'
                                        : '' }}>

                                    Assigned

                                </option>

                                <option
                                    value="in_progress"
                                    {{ old(
                                        'status',
                                        $travelRequest->status
                                    ) === 'in_progress'
                                        ? 'selected'
                                        : '' }}>

                                    In Progress

                                </option>

                                <option
                                    value="completed"
                                    {{ old(
                                        'status',
                                        $travelRequest->status
                                    ) === 'completed'
                                        ? 'selected'
                                        : '' }}>

                                    Completed

                                </option>

                                <option
                                    value="cancelled"
                                    {{ old(
                                        'status',
                                        $travelRequest->status
                                    ) === 'cancelled'
                                        ? 'selected'
                                        : '' }}>

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
                    {{-- PASSENGER DETAILS --}}
                    {{-- ================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="form-section-title">

                            <i class="fa fa-users mr-2"></i>

                            Passenger Details

                        </h5>

                        <hr>

                    </div>



                    {{-- Passenger Name --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                Passenger Name

                                <span class="required">
                                    *
                                </span>

                            </label>

                            <input
                                type="text"
                                name="passenger_name"
                                id="passenger_name"
                                class="form-control @error('passenger_name') is-invalid @enderror"
                                value="{{ old(
                                    'passenger_name',
                                    $travelRequest->passenger_name
                                ) }}"
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

                            <label>

                                Passenger Mobile

                                <span class="required">
                                    *
                                </span>

                            </label>

                            <input
                                type="text"
                                name="passenger_mobile"
                                id="passenger_mobile"
                                maxlength="10"
                                inputmode="numeric"
                                class="form-control @error('passenger_mobile') is-invalid @enderror"
                                value="{{ old(
                                    'passenger_mobile',
                                    $travelRequest->passenger_mobile
                                ) }}"
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

                            <label>
                                Passenger Email
                            </label>

                            <input
                                type="email"
                                name="passenger_email"
                                id="passenger_email"
                                class="form-control @error('passenger_email') is-invalid @enderror"
                                value="{{ old(
                                    'passenger_email',
                                    $travelRequest->passenger_email
                                ) }}"
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
                    {{-- PURPOSE --}}
                    {{-- ================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="form-section-title">

                            <i class="fa fa-info-circle mr-2"></i>

                            Travel Purpose & Remarks

                        </h5>

                        <hr>

                    </div>



                    {{-- Purpose --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                Purpose
                            </label>

                            <textarea
                                name="purpose"
                                id="purpose"
                                rows="5"
                                maxlength="1000"
                                class="form-control @error('purpose') is-invalid @enderror"
                                placeholder="Enter Travel Purpose">{{ old(
                                    'purpose',
                                    $travelRequest->purpose
                                ) }}</textarea>

                            @error('purpose')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                            <small class="text-muted">

                                Maximum 1000 characters.

                            </small>

                        </div>

                    </div>



                    {{-- Remarks --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                Remarks
                            </label>

                            <textarea
                                name="remarks"
                                id="remarks"
                                rows="5"
                                maxlength="2000"
                                class="form-control @error('remarks') is-invalid @enderror"
                                placeholder="Enter Remarks">{{ old(
                                    'remarks',
                                    $travelRequest->remarks
                                ) }}</textarea>

                            @error('remarks')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                            <small class="text-muted">

                                Maximum 2000 characters.

                            </small>

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
                                class="btn btn-success"
                                id="updateTravelRequestBtn">

                                <i class="fa fa-save"></i>

                                Update Travel Request

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </div>


    {{-- ========================================================= --}}
    {{-- FOOTER --}}
    {{-- ========================================================= --}}

    <x-backend.footer />

</div>

@endsection



@push('scripts')

<script>

$(document).ready(function () {


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
    | Number Of Passengers
    |--------------------------------------------------------------------------
    */

    $('#number_of_passengers').on('input', function () {

        let value = parseInt(this.value);

        if (isNaN(value) || value < 1) {

            this.value = 1;

        }

    });



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
    | Passenger Name
    |--------------------------------------------------------------------------
    */

    $('#passenger_name').on('blur', function () {

        this.value = this.value
            .replace(/\s+/g, ' ')
            .trim();

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
    | Pickup Location
    |--------------------------------------------------------------------------
    */

    $('#pickup_location').on('blur', function () {

        this.value = this.value
            .replace(/\s+/g, ' ')
            .trim();

    });



    /*
    |--------------------------------------------------------------------------
    | Drop Location
    |--------------------------------------------------------------------------
    */

    $('#drop_location').on('blur', function () {

        this.value = this.value
            .replace(/\s+/g, ' ')
            .trim();

    });



    /*
    |--------------------------------------------------------------------------
    | Purpose
    |--------------------------------------------------------------------------
    */

    $('#purpose').on('blur', function () {

        this.value = this.value
            .replace(/\s+/g, ' ')
            .trim();

    });



    /*
    |--------------------------------------------------------------------------
    | Remarks
    |--------------------------------------------------------------------------
    */

    $('#remarks').on('blur', function () {

        this.value = this.value
            .replace(/\s+/g, ' ')
            .trim();

    });



    /*
    |--------------------------------------------------------------------------
    | Prevent Past Travel Date
    |--------------------------------------------------------------------------
    |
    | NOTE:
    | Existing travel date ko edit karne ke liye automatically block
    | nahi kiya gaya hai. Server-side validation final authority rahegi.
    |--------------------------------------------------------------------------
    */



    /*
    |--------------------------------------------------------------------------
    | Form Submit
    |--------------------------------------------------------------------------
    */

    $('form').on('submit', function () {

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
        | Pickup Location
        |--------------------------------------------------------------------------
        */

        $('#pickup_location').val(
            $('#pickup_location').val()
                .replace(/\s+/g, ' ')
                .trim()
        );


        /*
        |--------------------------------------------------------------------------
        | Drop Location
        |--------------------------------------------------------------------------
        */

        $('#drop_location').val(
            $('#drop_location').val()
                .replace(/\s+/g, ' ')
                .trim()
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

        const button = $('#updateTravelRequestBtn');

        button
            .prop('disabled', true)
            .html(
                '<i class="fa fa-spinner fa-spin"></i> Updating...'
            );

    });


});

</script>

@endpush