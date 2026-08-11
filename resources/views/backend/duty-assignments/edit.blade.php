@extends('backend.layouts.master')

@section('title')
    Edit Duty Assignment
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

    .required {

        color: #dc3545;

    }


    /*
    |--------------------------------------------------------------------------
    | Existing Document
    |--------------------------------------------------------------------------
    */

    .existing-document {

        background: #f8f9fa;

        border: 1px solid #dee2e6;

        border-radius: 6px;

        padding: 10px;

    }


    /*
    |--------------------------------------------------------------------------
    | Table Alignment
    |--------------------------------------------------------------------------
    */

    .table td,
    .table th {

        vertical-align: middle;

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
                            Edit Duty Assignment
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

                                Edit Duty Assignment

                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- VALIDATION ERRORS --}}
        {{-- ========================================================= --}}

        @if($errors->any())

            <div class="alert alert-danger">

                <strong>
                    Please correct the following errors:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

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
            action="{{ route('duty-assignments.update', $dutyAssignment->id) }}"
            method="POST">

            @csrf

            @method('PUT')


            <div class="card-box pd-20 mb-30">


                {{-- ================================================= --}}
                {{-- ASSIGNMENT INFORMATION --}}
                {{-- ================================================= --}}

                <div class="mb-4">

                    <h5 class="form-section-title">

                        Duty Assignment Information

                    </h5>

                    <hr>

                </div>


                <div class="row">


                    {{-- ================================================= --}}
                    {{-- Assignment Number --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Assignment Number
                                </b>

                                <span class="required">
                                    *
                                </span>

                            </label>

                            <input
                                type="text"
                                name="assignment_no"
                                id="assignment_no"
                                class="form-control @error('assignment_no') is-invalid @enderror"
                                value="{{ old('assignment_no', $dutyAssignment->assignment_no ?? '') }}"
                                placeholder="Enter Assignment Number">

                            @error('assignment_no')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Travel Request --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Travel Request
                                </b>

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

                                @foreach($travelRequests ?? [] as $travelRequest)

                                    <option
                                        value="{{ $travelRequest->id }}"
                                        {{ (string) old(
                                            'travel_request_id',
                                            $dutyAssignment->travel_request_id ?? ''
                                        ) === (string) $travelRequest->id ? 'selected' : '' }}>

                                        {{ $travelRequest->request_no ?? 'TR-' . $travelRequest->id }}

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

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Driver --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Driver
                                </b>

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

                                @foreach($drivers ?? [] as $driver)

                                    <option
                                        value="{{ $driver->id }}"
                                        {{ (string) old(
                                            'driver_id',
                                            $dutyAssignment->driver_id ?? ''
                                        ) === (string) $driver->id ? 'selected' : '' }}>

                                        {{ $driver->driver_code ?? 'DRV-' . $driver->id }}

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

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Vehicle --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Vehicle
                                </b>

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

                                @foreach($vehicles ?? [] as $vehicle)

                                    <option
                                        value="{{ $vehicle->id }}"
                                        {{ (string) old(
                                            'vehicle_id',
                                            $dutyAssignment->vehicle_id ?? ''
                                        ) === (string) $vehicle->id ? 'selected' : '' }}>

                                        {{ $vehicle->vehicle_number ?? $vehicle->registration_number ?? 'Vehicle-' . $vehicle->id }}

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

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Assignment Date --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Assignment Date
                                </b>

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
                                    !empty($dutyAssignment->assignment_date)
                                        ? \Carbon\Carbon::parse(
                                            $dutyAssignment->assignment_date
                                        )->format('Y-m-d')
                                        : ''
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
                    {{-- Reporting Date --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Reporting Date
                                </b>

                            </label>

                            <input
                                type="date"
                                name="reporting_date"
                                id="reporting_date"
                                class="form-control @error('reporting_date') is-invalid @enderror"
                                value="{{ old(
                                    'reporting_date',
                                    !empty($dutyAssignment->reporting_date)
                                        ? \Carbon\Carbon::parse(
                                            $dutyAssignment->reporting_date
                                        )->format('Y-m-d')
                                        : ''
                                ) }}">

                            @error('reporting_date')

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

                            <label>

                                <b>
                                    Reporting Time
                                </b>

                            </label>

                            <input
                                type="time"
                                name="reporting_time"
                                id="reporting_time"
                                class="form-control @error('reporting_time') is-invalid @enderror"
                                value="{{ old(
                                    'reporting_time',
                                    !empty($dutyAssignment->reporting_time)
                                        ? substr(
                                            $dutyAssignment->reporting_time,
                                            0,
                                            5
                                        )
                                        : ''
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
                    {{-- Pickup Location --}}
                    {{-- ================================================= --}}

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>
                                    Pickup Location
                                </b>

                            </label>

                            <input
                                type="text"
                                name="pickup_location"
                                id="pickup_location"
                                class="form-control @error('pickup_location') is-invalid @enderror"
                                value="{{ old(
                                    'pickup_location',
                                    $dutyAssignment->pickup_location ?? ''
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
                    {{-- Drop Location --}}
                    {{-- ================================================= --}}

                    <div class="col-md-6">

                        <div class="form-group">

                            <label>

                                <b>
                                    Drop Location
                                </b>

                            </label>

                            <input
                                type="text"
                                name="drop_location"
                                id="drop_location"
                                class="form-control @error('drop_location') is-invalid @enderror"
                                value="{{ old(
                                    'drop_location',
                                    $dutyAssignment->drop_location ?? ''
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
                    {{-- Remarks --}}
                    {{-- ================================================= --}}

                    <div class="col-md-12">

                        <div class="form-group">

                            <label>

                                <b>
                                    Remarks
                                </b>

                            </label>

                            <textarea
                                name="remarks"
                                id="remarks"
                                rows="4"
                                class="form-control @error('remarks') is-invalid @enderror"
                                placeholder="Enter Remarks">{{ old(
                                    'remarks',
                                    $dutyAssignment->remarks ?? ''
                                ) }}</textarea>

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
                    {{-- STATUS --}}
                    {{-- ================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="form-section-title">

                            Status

                        </h5>

                        <hr>

                    </div>



                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Status
                                </b>

                                <span class="required">
                                    *
                                </span>

                            </label>

                            <select
                                name="status"
                                id="status"
                                class="form-control custom-select2 @error('status') is-invalid @enderror">

                                @php

                                    $currentStatus = old(
                                        'status',
                                        $dutyAssignment->status ?? 'assigned'
                                    );

                                @endphp

                                <option
                                    value="assigned"
                                    {{ $currentStatus === 'assigned' ? 'selected' : '' }}>
                                    Assigned
                                </option>

                                <option
                                    value="in_progress"
                                    {{ $currentStatus === 'in_progress' ? 'selected' : '' }}>
                                    In Progress
                                </option>

                                <option
                                    value="completed"
                                    {{ $currentStatus === 'completed' ? 'selected' : '' }}>
                                    Completed
                                </option>

                                <option
                                    value="cancelled"
                                    {{ $currentStatus === 'cancelled' ? 'selected' : '' }}>
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
                                class="btn btn-success">

                                <i class="fa fa-save"></i>

                                Update Duty Assignment

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
    | Assignment Number Formatting
    |--------------------------------------------------------------------------
    */

    $('#assignment_no').on('blur', function () {

        this.value = this.value
            .trim()
            .toUpperCase()
            .replace(/\s+/g, '');

    });



    /*
    |--------------------------------------------------------------------------
    | Location Formatting
    |--------------------------------------------------------------------------
    */

    $('#pickup_location, #drop_location').on('blur', function () {

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
    | Assignment Date Validation
    |--------------------------------------------------------------------------
    */

    $('#assignment_date').on('change', function () {

        const assignmentDate = this.value;

        if (!assignmentDate) {
            return;
        }

        const selectedDate = new Date(assignmentDate);

        const today = new Date();

        today.setHours(0, 0, 0, 0);


        /*
        |--------------------------------------------------------------------------
        | Reporting Date
        |--------------------------------------------------------------------------
        */

        const reportingDate = $('#reporting_date').val();

        if (reportingDate) {

            const selectedReportingDate =
                new Date(reportingDate);

            if (
                selectedReportingDate <
                selectedDate
            ) {

                alert(
                    'Reporting date cannot be before assignment date.'
                );

                $('#reporting_date').val('');

            }

        }

    });



    /*
    |--------------------------------------------------------------------------
    | Reporting Date Validation
    |--------------------------------------------------------------------------
    */

    $('#reporting_date').on('change', function () {

        const reportingDate = this.value;

        const assignmentDate =
            $('#assignment_date').val();

        if (
            !reportingDate ||
            !assignmentDate
        ) {

            return;

        }


        const selectedReportingDate =
            new Date(reportingDate);

        const selectedAssignmentDate =
            new Date(assignmentDate);


        if (
            selectedReportingDate <
            selectedAssignmentDate
        ) {

            alert(
                'Reporting date cannot be before assignment date.'
            );

            this.value = '';

        }

    });



    /*
    |--------------------------------------------------------------------------
    | Form Submit
    |--------------------------------------------------------------------------
    */

    $('form').on('submit', function () {


        /*
        |--------------------------------------------------------------------------
        | Assignment Number
        |--------------------------------------------------------------------------
        */

        $('#assignment_no').val(

            $('#assignment_no')
                .val()
                .trim()
                .toUpperCase()
                .replace(/\s+/g, '')

        );


        /*
        |--------------------------------------------------------------------------
        | Pickup Location
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
        | Drop Location
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

        const submitButton =
            $(this).find(
                'button[type="submit"]'
            );


        if (submitButton.length) {

            submitButton
                .prop('disabled', true)
                .html(
                    '<i class="fa fa-spinner fa-spin"></i> Updating...'
                );

        }

    });

});

</script>

@endpush