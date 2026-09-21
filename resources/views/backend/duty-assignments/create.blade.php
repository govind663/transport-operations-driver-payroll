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

                                <span class="required">*</span>

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
                                        data-request-no="{{ $travelRequest->request_no }}"
                                        data-passenger="{{ $travelRequest->passenger_name ?? '' }}"
                                        data-pickup="{{ $travelRequest->pickup_location ?? '' }}"
                                        data-drop="{{ $travelRequest->drop_location ?? '' }}"
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

                                Select the travel request for this duty assignment.

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

                                <span class="required">*</span>

                            </label>


                            <input
                                type="date"
                                name="assigned_at"
                                id="assigned_at"
                                class="form-control @error('assigned_at') is-invalid @enderror"
                                value="{{ old(
                                    'assigned_at',
                                    now()->format('Y-m-d')
                                ) }}">


                            @error('assigned_at')

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

                                <span class="required">*</span>

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

                                <span class="required">*</span>

                            </label>


                            <select
                                name="status"
                                id="status"
                                class="form-control custom-select2 @error('status') is-invalid @enderror">

                                <option
                                    value="{{ \App\Models\DutyAssignment::STATUS_PENDING }}"
                                    {{ old(
                                        'status',
                                        \App\Models\DutyAssignment::STATUS_PENDING
                                    ) === \App\Models\DutyAssignment::STATUS_PENDING
                                        ? 'selected'
                                        : '' }}>

                                    Pending

                                </option>


                                <option
                                    value="{{ \App\Models\DutyAssignment::STATUS_ASSIGNED }}"
                                    {{ old('status') === \App\Models\DutyAssignment::STATUS_ASSIGNED
                                        ? 'selected'
                                        : '' }}>

                                    Assigned

                                </option>


                                <option
                                    value="{{ \App\Models\DutyAssignment::STATUS_ACCEPTED }}"
                                    {{ old('status') === \App\Models\DutyAssignment::STATUS_ACCEPTED
                                        ? 'selected'
                                        : '' }}>

                                    Accepted

                                </option>


                                <option
                                    value="{{ \App\Models\DutyAssignment::STATUS_REJECTED }}"
                                    {{ old('status') === \App\Models\DutyAssignment::STATUS_REJECTED
                                        ? 'selected'
                                        : '' }}>

                                    Rejected

                                </option>


                                <option
                                    value="{{ \App\Models\DutyAssignment::STATUS_STARTED }}"
                                    {{ old('status') === \App\Models\DutyAssignment::STATUS_STARTED
                                        ? 'selected'
                                        : '' }}>

                                    Started

                                </option>


                                <option
                                    value="{{ \App\Models\DutyAssignment::STATUS_COMPLETED }}"
                                    {{ old('status') === \App\Models\DutyAssignment::STATUS_COMPLETED
                                        ? 'selected'
                                        : '' }}>

                                    Completed

                                </option>


                                <option
                                    value="{{ \App\Models\DutyAssignment::STATUS_CANCELLED }}"
                                    {{ old('status') === \App\Models\DutyAssignment::STATUS_CANCELLED
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
                    {{-- REPORTING LOCATION --}}
                    {{-- ================================================= --}}

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                Reporting Location
                            </label>


                            <input
                                type="text"
                                name="reporting_location"
                                id="reporting_location"
                                maxlength="255"
                                class="form-control @error('reporting_location') is-invalid @enderror"
                                value="{{ old('reporting_location') }}"
                                placeholder="Enter reporting location">


                            @error('reporting_location')

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

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                Remarks
                            </label>


                            <textarea
                                name="remarks"
                                id="remarks"
                                rows="4"
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
    | Travel Request Change
    |--------------------------------------------------------------------------
    */

    $('#travel_request_id').on('change', function () {

        const selectedOption =
            $(this).find('option:selected');

        if (!this.value) {

            $('#travel-request-preview').hide();

            $('#preview-request-no').text('-');
            $('#preview-passenger-name').text('-');
            $('#preview-pickup').text('-');
            $('#preview-drop').text('-');

            return;
        }


        const requestNo =
            selectedOption.data('request-no') || '-';

        const passenger =
            selectedOption.data('passenger') || '-';

        const pickup =
            selectedOption.data('pickup') || '-';

        const drop =
            selectedOption.data('drop') || '-';


        $('#preview-request-no')
            .text(requestNo);

        $('#preview-passenger-name')
            .text(passenger);

        $('#preview-pickup')
            .text(pickup);

        $('#preview-drop')
            .text(drop);


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

            $('#driver-preview').hide();

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

            $('#vehicle-preview').hide();

            return;
        }


        $('#vehicle-preview-text')
            .text(selectedText);

        $('#vehicle-preview')
            .show();

    });


    /*
    |--------------------------------------------------------------------------
    | Reporting Location Formatting
    |--------------------------------------------------------------------------
    */

    $('#reporting_location').on('blur', function () {

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
        | Reporting Location
        |--------------------------------------------------------------------------
        */

        $('#reporting_location').val(

            $('#reporting_location')
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