@extends('backend.layouts.master')

@section('title')
    Create Duty Assignment
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
    | Required Field
    |--------------------------------------------------------------------------
    */

    .required {

        color: #dc3545;

    }


    /*
    |--------------------------------------------------------------------------
    | Travel Request Information Box
    |--------------------------------------------------------------------------
    */

    .travel-request-info {

        background: #f8f9fa;

        border: 1px solid #dee2e6;

        border-radius: 6px;

        padding: 15px;

        margin-top: 10px;

    }


    /*
    |--------------------------------------------------------------------------
    | Info Label
    |--------------------------------------------------------------------------
    */

    .info-label {

        font-size: 12px;

        color: #6c757d;

        display: block;

        margin-bottom: 3px;

    }


    /*
    |--------------------------------------------------------------------------
    | Info Value
    |--------------------------------------------------------------------------
    */

    .info-value {

        font-weight: 600;

        color: #212529;

    }


    /*
    |--------------------------------------------------------------------------
    | Driver / Vehicle Preview
    |--------------------------------------------------------------------------
    */

    .selection-info {

        margin-top: 10px;

        padding: 10px 12px;

        border-radius: 5px;

        background: #f8f9fa;

        border: 1px solid #e9ecef;

        display: none;

    }


    /*
    |--------------------------------------------------------------------------
    | Textarea
    |--------------------------------------------------------------------------
    */

    textarea.form-control {

        resize: vertical;

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

                            Create New Duty Assignment

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

                                <a href="{{ route('duty-assignments.index') }}">

                                    Duty Assignments

                                </a>

                            </li>


                            <li class="breadcrumb-item active">

                                Create Duty Assignment

                            </li>

                        </ol>

                    </nav>

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
            action="{{ route('duty-assignments.store') }}"
            method="POST"
            id="dutyAssignmentForm">

            @csrf


            <div class="card-box pd-20 mb-30">


                {{-- ================================================= --}}
                {{-- DUTY ASSIGNMENT INFORMATION --}}
                {{-- ================================================= --}}

                <div class="mb-4">

                    <h5 class="form-section-title">

                        <i class="fa fa-tasks mr-2"></i>

                        Duty Assignment Information

                    </h5>

                    <hr>

                </div>


                <div class="row">


                    {{-- ================================================= --}}
                    {{-- TRAVEL REQUEST --}}
                    {{-- ================================================= --}}

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                Travel Request

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <select
                                name="travel_request_id"
                                id="travel_request_id"
                                class="form-control custom-select2 @error('travel_request_id') is-invalid @enderror">

                                <option value="">

                                    Select Travel Request

                                </option>


                                @foreach($travelRequests as $travelRequest)

                                    <option
                                        value="{{ $travelRequest->id }}"
                                        {{ (string) old(
                                            'travel_request_id',
                                            request('travel_request_id')
                                        ) === (string) $travelRequest->id
                                            ? 'selected'
                                            : '' }}>

                                        {{ $travelRequest->request_no }}

                                        @if(!empty($travelRequest->passenger_name))

                                            -
                                            {{ $travelRequest->passenger_name }}

                                        @endif

                                    </option>

                                @endforeach

                            </select>


                            @error('travel_request_id')

                                <span class="invalid-feedback d-block">

                                    <strong>

                                        {{ $message }}

                                    </strong>

                                </span>

                            @enderror


                            <small class="text-muted">

                                Select the approved travel request for this duty.

                            </small>

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- ASSIGNMENT DATE --}}
                    {{-- ================================================= --}}

                    <div class="col-md-3">

                        <div class="form-group">

                            <label>

                                Assignment Date

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <input
                                type="date"
                                name="assignment_date"
                                id="assignment_date"
                                class="form-control @error('assignment_date') is-invalid @enderror"
                                value="{{ old(
                                    'assignment_date',
                                    now()->format('Y-m-d')
                                ) }}">


                            @error('assignment_date')

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

                    <div class="col-md-3">

                        <div class="form-group">

                            <label>

                                Reporting Time

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <input
                                type="time"
                                name="reporting_time"
                                id="reporting_time"
                                class="form-control @error('reporting_time') is-invalid @enderror"
                                value="{{ old('reporting_time') }}">


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
                    {{-- TRAVEL REQUEST PREVIEW --}}
                    {{-- ================================================= --}}

                    <div class="col-12">

                        <div
                            id="travel-request-preview"
                            class="travel-request-info"
                            style="display:none;">

                            <div class="row">

                                <div class="col-md-3">

                                    <span class="info-label">

                                        Request Number

                                    </span>

                                    <span
                                        class="info-value"
                                        id="preview-request-no">

                                        -

                                    </span>

                                </div>


                                <div class="col-md-3">

                                    <span class="info-label">

                                        Passenger

                                    </span>

                                    <span
                                        class="info-value"
                                        id="preview-passenger-name">

                                        -

                                    </span>

                                </div>


                                <div class="col-md-3">

                                    <span class="info-label">

                                        Pickup

                                    </span>

                                    <span
                                        class="info-value"
                                        id="preview-pickup">

                                        -

                                    </span>

                                </div>


                                <div class="col-md-3">

                                    <span class="info-label">

                                        Drop

                                    </span>

                                    <span
                                        class="info-value"
                                        id="preview-drop">

                                        -

                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- DRIVER & VEHICLE --}}
                    {{-- ================================================= --}}

                    <div class="col-12 mt-4">

                        <h5 class="form-section-title">

                            <i class="fa fa-user mr-2"></i>

                            Driver & Vehicle

                        </h5>

                        <hr>

                    </div>



                    {{-- ================================================= --}}
                    {{-- DRIVER --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                Driver

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <select
                                name="driver_id"
                                id="driver_id"
                                class="form-control custom-select2 @error('driver_id') is-invalid @enderror">

                                <option value="">

                                    Select Driver

                                </option>


                                @foreach($drivers as $driver)

                                    <option
                                        value="{{ $driver->id }}"
                                        {{ (string) old(
                                            'driver_id'
                                        ) === (string) $driver->id
                                            ? 'selected'
                                            : '' }}>

                                        {{ $driver->driver_code }}

                                        -

                                        {{ trim(
                                            ($driver->first_name ?? '') .
                                            ' ' .
                                            ($driver->last_name ?? '')
                                        ) }}

                                    </option>

                                @endforeach

                            </select>


                            @error('driver_id')

                                <span class="invalid-feedback d-block">

                                    <strong>

                                        {{ $message }}

                                    </strong>

                                </span>

                            @enderror


                            <div
                                id="driver-preview"
                                class="selection-info">

                                <strong>

                                    Selected Driver

                                </strong>

                                <br>

                                <span id="driver-preview-text">

                                    -

                                </span>

                            </div>

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- VEHICLE --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                Vehicle

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <select
                                name="vehicle_id"
                                id="vehicle_id"
                                class="form-control custom-select2 @error('vehicle_id') is-invalid @enderror">

                                <option value="">

                                    Select Vehicle

                                </option>


                                @foreach($vehicles as $vehicle)

                                    <option
                                        value="{{ $vehicle->id }}"
                                        {{ (string) old(
                                            'vehicle_id'
                                        ) === (string) $vehicle->id
                                            ? 'selected'
                                            : '' }}>

                                        {{ $vehicle->vehicle_number }}

                                        @if(!empty($vehicle->vehicle_model))

                                            -
                                            {{ $vehicle->vehicle_model }}

                                        @endif

                                    </option>

                                @endforeach

                            </select>


                            @error('vehicle_id')

                                <span class="invalid-feedback d-block">

                                    <strong>

                                        {{ $message }}

                                    </strong>

                                </span>

                            @enderror


                            <div
                                id="vehicle-preview"
                                class="selection-info">

                                <strong>

                                    Selected Vehicle

                                </strong>

                                <br>

                                <span id="vehicle-preview-text">

                                    -

                                </span>

                            </div>

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

                                <option
                                    value="assigned"
                                    {{ old(
                                        'status',
                                        'assigned'
                                    ) === 'assigned'
                                        ? 'selected'
                                        : '' }}>

                                    Assigned

                                </option>


                                <option
                                    value="pending"
                                    {{ old(
                                        'status'
                                    ) === 'pending'
                                        ? 'selected'
                                        : '' }}>

                                    Pending

                                </option>


                                <option
                                    value="in_progress"
                                    {{ old(
                                        'status'
                                    ) === 'in_progress'
                                        ? 'selected'
                                        : '' }}>

                                    In Progress

                                </option>


                                <option
                                    value="completed"
                                    {{ old(
                                        'status'
                                    ) === 'completed'
                                        ? 'selected'
                                        : '' }}>

                                    Completed

                                </option>


                                <option
                                    value="cancelled"
                                    {{ old(
                                        'status'
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
                    {{-- TRAVEL LOCATIONS --}}
                    {{-- ================================================= --}}

                    <div class="col-12 mt-4">

                        <h5 class="form-section-title">

                            <i class="fa fa-map-marker mr-2"></i>

                            Travel Locations

                        </h5>

                        <hr>

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
                                value="{{ old('pickup_location') }}"
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
                                value="{{ old('drop_location') }}"
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
                    {{-- PASSENGER INFORMATION --}}
                    {{-- ================================================= --}}

                    <div class="col-12 mt-4">

                        <h5 class="form-section-title">

                            <i class="fa fa-users mr-2"></i>

                            Passenger Information

                        </h5>

                        <hr>

                    </div>



                    {{-- ================================================= --}}
                    {{-- PASSENGER NAME --}}
                    {{-- ================================================= --}}

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
                                value="{{ old('passenger_name') }}"
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
                    {{-- PASSENGER MOBILE --}}
                    {{-- ================================================= --}}

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
                                value="{{ old('passenger_mobile') }}"
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



                    {{-- ================================================= --}}
                    {{-- PASSENGER COUNT --}}
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
                                    1
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
                    {{-- DUTY INSTRUCTIONS --}}
                    {{-- ================================================= --}}

                    <div class="col-12 mt-4">

                        <h5 class="form-section-title">

                            <i class="fa fa-info-circle mr-2"></i>

                            Duty Instructions & Remarks

                        </h5>

                        <hr>

                    </div>



                    {{-- ================================================= --}}
                    {{-- INSTRUCTIONS --}}
                    {{-- ================================================= --}}

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                Duty Instructions

                            </label>


                            <textarea
                                name="instructions"
                                id="instructions"
                                rows="5"
                                maxlength="2000"
                                class="form-control @error('instructions') is-invalid @enderror"
                                placeholder="Enter duty instructions">{{ old('instructions') }}</textarea>


                            @error('instructions')

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
                    {{-- REMARKS --}}
                    {{-- ================================================= --}}

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
                                placeholder="Enter remarks">{{ old('remarks') }}</textarea>


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
                                href="{{ route('duty-assignments.index') }}"
                                class="btn btn-danger">

                                <i class="fa fa-times"></i>

                                Cancel

                            </a>


                            <button
                                type="submit"
                                class="btn btn-success"
                                id="saveDutyAssignmentBtn">

                                <i class="fa fa-save"></i>

                                Save Duty Assignment

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
    | Passenger Mobile Validation
    |--------------------------------------------------------------------------
    */

    $('#passenger_mobile').on('input', function () {

        this.value = this.value
            .replace(/[^0-9]/g, '')
            .slice(0, 10);

    });



    /*
    |--------------------------------------------------------------------------
    | Passenger Count Validation
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
    | Instructions Formatting
    |--------------------------------------------------------------------------
    */

    $('#instructions').on('blur', function () {

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
    | Travel Request Change
    |--------------------------------------------------------------------------
    */

    $('#travel_request_id').on('change', function () {

        const selectedOption = $(this).find('option:selected');

        if (!this.value) {

            $('#travel-request-preview')
                .hide();

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | If server-rendered data attributes are available
        |--------------------------------------------------------------------------
        */

        const requestNo =
            selectedOption.data('request-no') || selectedOption.text().trim();


        $('#preview-request-no')
            .text(requestNo);


        $('#travel-request-preview')
            .show();

    });



    /*
    |--------------------------------------------------------------------------
    | Driver Selection Preview
    |--------------------------------------------------------------------------
    */

    $('#driver_id').on('change', function () {

        const selectedText =
            $(this)
                .find('option:selected')
                .text()
                .trim();


        if (!this.value) {

            $('#driver-preview')
                .hide();

            return;

        }


        $('#driver-preview-text')
            .text(selectedText);


        $('#driver-preview')
            .show();

    });



    /*
    |--------------------------------------------------------------------------
    | Vehicle Selection Preview
    |--------------------------------------------------------------------------
    */

    $('#vehicle_id').on('change', function () {

        const selectedText =
            $(this)
                .find('option:selected')
                .text()
                .trim();


        if (!this.value) {

            $('#vehicle-preview')
                .hide();

            return;

        }


        $('#vehicle-preview-text')
            .text(selectedText);


        $('#vehicle-preview')
            .show();

    });



    /*
    |--------------------------------------------------------------------------
    | Trigger Existing Selections
    |--------------------------------------------------------------------------
    */

    $('#travel_request_id').trigger('change');

    $('#driver_id').trigger('change');

    $('#vehicle_id').trigger('change');



    /*
    |--------------------------------------------------------------------------
    | Form Submit
    |--------------------------------------------------------------------------
    */

    $('#dutyAssignmentForm').on('submit', function () {


        /*
        |--------------------------------------------------------------------------
        | Passenger Name
        |--------------------------------------------------------------------------
        */

        $('#passenger_name').val(

            $('#passenger_name')
                .val()
                .replace(/\s+/g, ' ')
                .trim()

        );


        /*
        |--------------------------------------------------------------------------
        | Passenger Mobile
        |--------------------------------------------------------------------------
        */

        $('#passenger_mobile').val(

            $('#passenger_mobile')
                .val()
                .replace(/[^0-9]/g, '')
                .slice(0, 10)

        );


        /*
        |--------------------------------------------------------------------------
        | Pickup
        |--------------------------------------------------------------------------
        */

        $('#pickup_location').val(

            $('#pickup_location')
                .val()
                .replace(/\s+/g, ' ')
                .trim()

        );


        /*
        |--------------------------------------------------------------------------
        | Drop
        |--------------------------------------------------------------------------
        */

        $('#drop_location').val(

            $('#drop_location')
                .val()
                .replace(/\s+/g, ' ')
                .trim()

        );


        /*
        |--------------------------------------------------------------------------
        | Instructions
        |--------------------------------------------------------------------------
        */

        $('#instructions').val(

            $('#instructions')
                .val()
                .replace(/\s+/g, ' ')
                .trim()

        );


        /*
        |--------------------------------------------------------------------------
        | Remarks
        |--------------------------------------------------------------------------
        */

        $('#remarks').val(

            $('#remarks')
                .val()
                .replace(/\s+/g, ' ')
                .trim()

        );


        /*
        |--------------------------------------------------------------------------
        | Prevent Double Submit
        |--------------------------------------------------------------------------
        */

        const button =
            $('#saveDutyAssignmentBtn');


        button
            .prop('disabled', true)
            .html(
                '<i class="fa fa-spinner fa-spin"></i> Saving...'
            );

    });

});

</script>

@endpush