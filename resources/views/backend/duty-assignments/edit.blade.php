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
    | Travel Request Information
    |--------------------------------------------------------------------------
    */

    .travel-request-info {

        background: #f8f9fa;

        border: 1px solid #dee2e6;

        border-radius: 6px;

        padding: 15px;

        margin-top: 10px;

    }


    .info-label {

        font-size: 12px;

        color: #6c757d;

        display: block;

        margin-bottom: 3px;

    }


    .info-value {

        font-weight: 600;

        color: #212529;

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
        <form action="{{ route('duty-assignments.update', $dutyAssignment->id ) }}"  method="POST" id="dutyAssignmentForm">

            @csrf
            @method('PUT')

            <div class="card-box pd-20 mb-30">

                {{-- ================================================= --}}
                {{-- ASSIGNMENT INFORMATION --}}
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
                    {{-- ASSIGNMENT NUMBER --}}
                    {{-- ================================================= --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Assignment Number
                                </b>

                            </label>


                            <input
                                type="text"
                                class="form-control"
                                value="{{ $dutyAssignment->assignment_no }}"
                                readonly>


                            <small class="text-muted">

                                Assignment number is system generated
                                and cannot be changed.

                            </small>

                        </div>

                    </div>

                    {{-- ================================================= --}}
                    {{-- TRAVEL REQUEST --}}
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

                                        data-request-no="{{ $travelRequest->request_no ?? 'TR-' . $travelRequest->id }}"

                                        data-passenger="{{ $travelRequest->passenger_name ?? '' }}"

                                        data-pickup="{{ $travelRequest->pickup_location ?? '' }}"

                                        data-drop="{{ $travelRequest->drop_location ?? '' }}"

                                        data-pickup-time="{{ $travelRequest->pickup_time ?? '' }}"

                                        data-pickup-location="{{ $travelRequest->pickup_location ?? '' }}"

                                        {{ (string) old(
                                            'travel_request_id',
                                            $dutyAssignment->travel_request_id ?? ''
                                        ) === (string) $travelRequest->id
                                            ? 'selected'
                                            : '' }}>

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


                            <small class="text-muted">

                                Select the travel request for this
                                duty assignment.

                            </small>

                        </div>

                    </div>

                    {{-- ================================================= --}}
                    {{-- DRIVER --}}
                    {{-- ================================================= --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Driver
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

                                        {{ (string) old(
                                            'driver_id',
                                            $dutyAssignment->driver_id ?? ''
                                        ) === (string) $driver->id
                                            ? 'selected'
                                            : '' }}>

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
                    {{-- VEHICLE --}}
                    {{-- ================================================= --}}
                    <div class="col-md-4 mt-3">

                        <div class="form-group">

                            <label>

                                <b>
                                    Vehicle
                                </b>

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
                                        ) === (string) $vehicle->id
                                            ? 'selected'
                                            : '' }}>

                                        {{ $vehicle->vehicle_number
                                            ?? $vehicle->registration_number
                                            ?? 'Vehicle-' . $vehicle->id }}

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

                        </div>

                    </div>

                    {{-- ================================================= --}}
                    {{-- ASSIGNMENT DATE --}}
                    {{-- ================================================= --}}
                    <div class="col-md-4 mt-3">

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
                                name="assigned_at"
                                id="assigned_at"
                                class="form-control @error('assigned_at') is-invalid @enderror"
                                value="{{ old(
                                    'assigned_at',
                                    !empty($dutyAssignment->assigned_at)
                                        ? \Carbon\Carbon::parse(
                                            $dutyAssignment->assigned_at
                                        )->format('Y-m-d')
                                        : ''
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
                    <div class="col-md-4 mt-3">

                        <div class="form-group">

                            <label>

                                <b>
                                    Reporting Time
                                </b>

                                <span class="required">
                                    *
                                </span>

                            </label>


                            <input
                                type="time"
                                name="reporting_time"
                                id="reporting_time"
                                class="form-control @error('reporting_time') is-invalid @enderror"
                                value="{{ old(
                                    'reporting_time',
                                    !empty($dutyAssignment->reporting_time)
                                        ? \Carbon\Carbon::parse(
                                            $dutyAssignment->reporting_time
                                        )->format('H:i')
                                        : ''
                                ) }}">


                            @error('reporting_time')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror


                            <small class="text-muted">

                                Auto-filled from Travel Request pickup time.

                            </small>

                        </div>

                    </div>

                    {{-- ================================================= --}}
                    {{-- REPORTING LOCATION --}}
                    {{-- ================================================= --}}
                    <div class="col-md-6 mt-3">

                        <div class="form-group">

                            <label>

                                <b>
                                    Reporting Location
                                </b>

                            </label>


                            <input
                                type="text"
                                name="reporting_location"
                                id="reporting_location"
                                maxlength="255"
                                class="form-control @error('reporting_location') is-invalid @enderror"
                                value="{{ old(
                                    'reporting_location',
                                    $dutyAssignment->reporting_location ?? ''
                                ) }}"
                                placeholder="Enter reporting location">


                            @error('reporting_location')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror


                            <small class="text-muted">

                                Auto-filled from Travel Request pickup location.

                            </small>

                        </div>

                    </div>

                    {{-- ================================================= --}}
                    {{-- REMARKS --}}
                    {{-- ================================================= --}}
                    <div class="col-md-6 mt-3">

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
                                maxlength="2000"
                                class="form-control @error('remarks') is-invalid @enderror"
                                placeholder="Enter remarks">{{ old(
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


                            <small class="text-muted">

                                Maximum 2000 characters.

                            </small>

                        </div>

                    </div>

                    {{-- ================================================= --}}
                    {{-- STATUS --}}
                    {{-- ================================================= --}}
                    <div class="col-12 mt-3">

                        <h5 class="form-section-title">

                            <i class="fa fa-info-circle mr-2"></i>

                            Assignment Status

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


                            @php

                                $currentStatus = old(
                                    'status',
                                    $dutyAssignment->status
                                        ?? \App\Models\DutyAssignment::STATUS_PENDING
                                );

                            @endphp


                            <select
                                name="status"
                                id="status"
                                class="form-control custom-select2 @error('status') is-invalid @enderror">

                                <option
                                    value="{{ \App\Models\DutyAssignment::STATUS_PENDING }}"
                                    {{ $currentStatus === \App\Models\DutyAssignment::STATUS_PENDING
                                        ? 'selected'
                                        : '' }}>

                                    Pending

                                </option>


                                <option
                                    value="{{ \App\Models\DutyAssignment::STATUS_ASSIGNED }}"
                                    {{ $currentStatus === \App\Models\DutyAssignment::STATUS_ASSIGNED
                                        ? 'selected'
                                        : '' }}>

                                    Assigned

                                </option>


                                <option
                                    value="{{ \App\Models\DutyAssignment::STATUS_ACCEPTED }}"
                                    {{ $currentStatus === \App\Models\DutyAssignment::STATUS_ACCEPTED
                                        ? 'selected'
                                        : '' }}>

                                    Accepted

                                </option>


                                <option
                                    value="{{ \App\Models\DutyAssignment::STATUS_REJECTED }}"
                                    {{ $currentStatus === \App\Models\DutyAssignment::STATUS_REJECTED
                                        ? 'selected'
                                        : '' }}>

                                    Rejected

                                </option>


                                <option
                                    value="{{ \App\Models\DutyAssignment::STATUS_STARTED }}"
                                    {{ $currentStatus === \App\Models\DutyAssignment::STATUS_STARTED
                                        ? 'selected'
                                        : '' }}>

                                    Started

                                </option>


                                <option
                                    value="{{ \App\Models\DutyAssignment::STATUS_COMPLETED }}"
                                    {{ $currentStatus === \App\Models\DutyAssignment::STATUS_COMPLETED
                                        ? 'selected'
                                        : '' }}>

                                    Completed

                                </option>


                                <option
                                    value="{{ \App\Models\DutyAssignment::STATUS_CANCELLED }}"
                                    {{ $currentStatus === \App\Models\DutyAssignment::STATUS_CANCELLED
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
                                id="updateDutyAssignmentBtn">

                                <i class="fa fa-save"></i>

                                Update Duty Assignment

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

    <script src="{{ asset('backend/assets/js/duty-assignments/edit.js') }}"></script>

@endpush