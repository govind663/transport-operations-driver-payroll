@extends('backend.layouts.master')

@section('title')
    Edit Duty Slip
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
        font-weight: 600;
        margin-bottom: 10px;
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
    | Required Star
    |--------------------------------------------------------------------------
    */

    .required {
        color: #dc3545;
    }


    /*
    |--------------------------------------------------------------------------
    | Card
    |--------------------------------------------------------------------------
    */

    .duty-slip-card {
        border-radius: 8px;
    }


    /*
    |--------------------------------------------------------------------------
    | Readonly Field
    |--------------------------------------------------------------------------
    */

    .readonly-field {
        background-color: #f8f9fa;
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

                <div class="col-md-8 col-sm-12">

                    <div class="title">

                        <h4>
                            Edit Duty Slip
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

                                <a href="{{ route('duty-slips.index') }}">
                                    Duty Slips
                                </a>

                            </li>


                            <li class="breadcrumb-item active">

                                Edit Duty Slip

                            </li>

                        </ol>

                    </nav>

                </div>


                {{-- Back Button --}}
                <div class="col-md-4 col-sm-12 text-right">

                    <a
                        href="{{ route('duty-slips.index') }}"
                        class="btn btn-secondary">

                        <i class="fa fa-arrow-left"></i>

                        Back

                    </a>

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- VALIDATION ERRORS --}}
        {{-- ========================================================= --}}

        @if($errors->any())

            <div class="alert alert-danger">

                <strong>
                    Please fix the following errors:
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
        {{-- SUCCESS MESSAGE --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

        @endif



        {{-- ========================================================= --}}
        {{-- FORM --}}
        {{-- ========================================================= --}}

        <form
            action="{{ route('duty-slips.update', $dutySlip->id) }}"
            method="POST">

            @csrf

            @method('PUT')


            <div class="card-box pd-20 mb-30 duty-slip-card">


                {{-- ===================================================== --}}
                {{-- DUTY SLIP INFORMATION --}}
                {{-- ===================================================== --}}

                <div class="mb-4">

                    <h5 class="form-section-title">

                        <b>
                            Duty Slip Information
                        </b>

                    </h5>

                    <hr>

                </div>


                <div class="row">


                    {{-- ================================================= --}}
                    {{-- Duty Slip Number --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="slip_no">

                                Duty Slip Number

                                <span class="required">
                                    *
                                </span>

                            </label>


                            <input
                                type="text"
                                name="slip_no"
                                id="slip_no"
                                class="form-control @error('slip_no') is-invalid @enderror"
                                value="{{ old('slip_no', $dutySlip->slip_no) }}"
                                placeholder="Enter Duty Slip Number">


                            @error('slip_no')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Duty Assignment --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="duty_assignment_id">

                                Duty Assignment

                                <span class="required">
                                    *
                                </span>

                            </label>


                            <select
                                name="duty_assignment_id"
                                id="duty_assignment_id"
                                class="form-control custom-select2 @error('duty_assignment_id') is-invalid @enderror">

                                <option value="">
                                    Select Duty Assignment
                                </option>


                                @foreach($dutyAssignments ?? [] as $assignment)

                                    <option
                                        value="{{ $assignment->id }}"
                                        {{ (string) old(
                                            'duty_assignment_id',
                                            $dutySlip->duty_assignment_id
                                        ) === (string) $assignment->id
                                            ? 'selected'
                                            : '' }}>

                                        {{ $assignment->assignment_no
                                            ?? $assignment->id }}

                                    </option>

                                @endforeach

                            </select>


                            @error('duty_assignment_id')

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

                            <label for="travel_request_id">

                                Travel Request

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
                                            $dutySlip->travel_request_id
                                        ) === (string) $travelRequest->id
                                            ? 'selected'
                                            : '' }}>

                                        {{ $travelRequest->request_no
                                            ?? $travelRequest->id }}

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
                    {{-- Duty Date --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="duty_date">

                                Duty Date

                                <span class="required">
                                    *
                                </span>

                            </label>


                            <input
                                type="date"
                                name="duty_date"
                                id="duty_date"
                                class="form-control @error('duty_date') is-invalid @enderror"
                                value="{{ old(
                                    'duty_date',
                                    optional($dutySlip->duty_date)->format('Y-m-d')
                                ) }}">


                            @error('duty_date')

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

                                Reporting Time

                            </label>


                            <input
                                type="time"
                                name="reporting_time"
                                id="reporting_time"
                                class="form-control @error('reporting_time') is-invalid @enderror"
                                value="{{ old(
                                    'reporting_time',
                                    $dutySlip->reporting_time
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
                    {{-- Duty Start Time --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="start_time">

                                Start Time

                            </label>


                            <input
                                type="time"
                                name="start_time"
                                id="start_time"
                                class="form-control @error('start_time') is-invalid @enderror"
                                value="{{ old(
                                    'start_time',
                                    $dutySlip->start_time
                                ) }}">


                            @error('start_time')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Duty End Time --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="end_time">

                                End Time

                            </label>


                            <input
                                type="time"
                                name="end_time"
                                id="end_time"
                                class="form-control @error('end_time') is-invalid @enderror"
                                value="{{ old(
                                    'end_time',
                                    $dutySlip->end_time
                                ) }}">


                            @error('end_time')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ========================================================= --}}
                    {{-- DRIVER INFORMATION --}}
                    {{-- ========================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="form-section-title">

                            <b>
                                Driver Information
                            </b>

                        </h5>

                        <hr>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Driver --}}
                    {{-- ================================================= --}}

                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="driver_id">

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


                                @foreach($drivers ?? [] as $driver)

                                    <option
                                        value="{{ $driver->id }}"
                                        {{ (string) old(
                                            'driver_id',
                                            $dutySlip->driver_id
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

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Vehicle --}}
                    {{-- ================================================= --}}

                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="vehicle_id">

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


                                @foreach($vehicles ?? [] as $vehicle)

                                    <option
                                        value="{{ $vehicle->id }}"
                                        {{ (string) old(
                                            'vehicle_id',
                                            $dutySlip->vehicle_id
                                        ) === (string) $vehicle->id
                                            ? 'selected'
                                            : '' }}>

                                        {{ $vehicle->vehicle_number
                                            ?? $vehicle->registration_number
                                            ?? $vehicle->id }}

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



                    {{-- ========================================================= --}}
                    {{-- PASSENGER INFORMATION --}}
                    {{-- ========================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="form-section-title">

                            <b>
                                Passenger Information
                            </b>

                        </h5>

                        <hr>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Passenger Name --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="passenger_name">

                                Passenger Name

                            </label>


                            <input
                                type="text"
                                name="passenger_name"
                                id="passenger_name"
                                class="form-control @error('passenger_name') is-invalid @enderror"
                                value="{{ old(
                                    'passenger_name',
                                    $dutySlip->passenger_name
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



                    {{-- ================================================= --}}
                    {{-- Passenger Mobile --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="passenger_mobile">

                                Passenger Mobile

                            </label>


                            <input
                                type="text"
                                name="passenger_mobile"
                                id="passenger_mobile"
                                maxlength="10"
                                class="form-control @error('passenger_mobile') is-invalid @enderror"
                                value="{{ old(
                                    'passenger_mobile',
                                    $dutySlip->passenger_mobile
                                ) }}"
                                placeholder="Enter Mobile Number">


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
                    {{-- Number Of Passengers --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="number_of_passengers">

                                Number Of Passengers

                            </label>


                            <input
                                type="number"
                                name="number_of_passengers"
                                id="number_of_passengers"
                                min="1"
                                class="form-control @error('number_of_passengers') is-invalid @enderror"
                                value="{{ old(
                                    'number_of_passengers',
                                    $dutySlip->number_of_passengers
                                ) }}"
                                placeholder="Enter Number">


                            @error('number_of_passengers')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ========================================================= --}}
                    {{-- ROUTE INFORMATION --}}
                    {{-- ========================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="form-section-title">

                            <b>
                                Route Information
                            </b>

                        </h5>

                        <hr>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Pickup Location --}}
                    {{-- ================================================= --}}

                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="pickup_location">

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
                                    $dutySlip->pickup_location
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

                            <label for="drop_location">

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
                                    $dutySlip->drop_location
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
                    {{-- Opening KM --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="opening_km">

                                Opening KM

                            </label>


                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="opening_km"
                                id="opening_km"
                                class="form-control @error('opening_km') is-invalid @enderror"
                                value="{{ old(
                                    'opening_km',
                                    $dutySlip->opening_km
                                ) }}"
                                placeholder="Enter Opening KM">


                            @error('opening_km')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Closing KM --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="closing_km">

                                Closing KM

                            </label>


                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="closing_km"
                                id="closing_km"
                                class="form-control @error('closing_km') is-invalid @enderror"
                                value="{{ old(
                                    'closing_km',
                                    $dutySlip->closing_km
                                ) }}"
                                placeholder="Enter Closing KM">


                            @error('closing_km')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Total KM --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="total_km">

                                Total KM

                            </label>


                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="total_km"
                                id="total_km"
                                class="form-control readonly-field @error('total_km') is-invalid @enderror"
                                value="{{ old(
                                    'total_km',
                                    $dutySlip->total_km
                                ) }}"
                                placeholder="Calculated automatically"
                                readonly>


                            <small class="text-muted">

                                Calculated from Closing KM − Opening KM.

                            </small>


                            @error('total_km')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ========================================================= --}}
                    {{-- FINANCIAL INFORMATION --}}
                    {{-- ========================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="form-section-title">

                            <b>
                                Financial Information
                            </b>

                        </h5>

                        <hr>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Rate --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="rate">

                                Rate

                            </label>


                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="rate"
                                id="rate"
                                class="form-control @error('rate') is-invalid @enderror"
                                value="{{ old(
                                    'rate',
                                    $dutySlip->rate
                                ) }}"
                                placeholder="Enter Rate">


                            @error('rate')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Amount --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="amount">

                                Amount

                            </label>


                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="amount"
                                id="amount"
                                class="form-control @error('amount') is-invalid @enderror"
                                value="{{ old(
                                    'amount',
                                    $dutySlip->amount
                                ) }}"
                                placeholder="Enter Amount">


                            @error('amount')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Driver Amount --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="driver_amount">

                                Driver Amount

                            </label>


                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="driver_amount"
                                id="driver_amount"
                                class="form-control @error('driver_amount') is-invalid @enderror"
                                value="{{ old(
                                    'driver_amount',
                                    $dutySlip->driver_amount
                                ) }}"
                                placeholder="Enter Driver Amount">


                            @error('driver_amount')

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

                        <h5 class="form-section-title">

                            <b>
                                Status
                            </b>

                        </h5>

                        <hr>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Status --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label for="status">

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
                                    value="draft"
                                    {{ old(
                                        'status',
                                        $dutySlip->status
                                    ) === 'draft'
                                        ? 'selected'
                                        : '' }}>

                                    Draft

                                </option>


                                <option
                                    value="open"
                                    {{ old(
                                        'status',
                                        $dutySlip->status
                                    ) === 'open'
                                        ? 'selected'
                                        : '' }}>

                                    Open

                                </option>


                                <option
                                    value="in_progress"
                                    {{ old(
                                        'status',
                                        $dutySlip->status
                                    ) === 'in_progress'
                                        ? 'selected'
                                        : '' }}>

                                    In Progress

                                </option>


                                <option
                                    value="completed"
                                    {{ old(
                                        'status',
                                        $dutySlip->status
                                    ) === 'completed'
                                        ? 'selected'
                                        : '' }}>

                                    Completed

                                </option>


                                <option
                                    value="cancelled"
                                    {{ old(
                                        'status',
                                        $dutySlip->status
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



                    {{-- ========================================================= --}}
                    {{-- REMARKS --}}
                    {{-- ========================================================= --}}

                    <div class="col-md-8">

                        <div class="form-group">

                            <label for="remarks">

                                Remarks

                            </label>


                            <textarea
                                name="remarks"
                                id="remarks"
                                rows="3"
                                class="form-control @error('remarks') is-invalid @enderror"
                                placeholder="Enter remarks">{{ old(
                                    'remarks',
                                    $dutySlip->remarks
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



                    {{-- ========================================================= --}}
                    {{-- ACTION BUTTONS --}}
                    {{-- ========================================================= --}}

                    <div class="col-12">

                        <div class="text-right mt-4">


                            <a
                                href="{{ route('duty-slips.index') }}"
                                class="btn btn-danger">

                                <i class="fa fa-times"></i>

                                Cancel

                            </a>


                            <button
                                type="submit"
                                class="btn btn-success">

                                <i class="fa fa-save"></i>

                                Update Duty Slip

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
    | Mobile Number
    |--------------------------------------------------------------------------
    */

    $('#passenger_mobile').on('input', function () {

        this.value = this.value
            .replace(/[^0-9]/g, '')
            .slice(0, 10);

    });


    /*
    |--------------------------------------------------------------------------
    | Text Formatting
    |--------------------------------------------------------------------------
    */

    $('#slip_no, #passenger_name, #pickup_location, #drop_location')
        .on('blur', function () {

            this.value = this.value
                .replace(/\s+/g, ' ')
                .trim();

        });


    /*
    |--------------------------------------------------------------------------
    | Calculate Total KM
    |--------------------------------------------------------------------------
    */

    function calculateTotalKm()
    {

        const opening =
            parseFloat($('#opening_km').val());

        const closing =
            parseFloat($('#closing_km').val());


        if (
            !isNaN(opening) &&
            !isNaN(closing) &&
            closing >= opening
        ) {

            $('#total_km').val(
                (closing - opening).toFixed(2)
            );

        } else {

            $('#total_km').val('');

        }

    }


    $('#opening_km, #closing_km')
        .on('input change', calculateTotalKm);


    /*
    |--------------------------------------------------------------------------
    | Prevent Invalid Closing KM
    |--------------------------------------------------------------------------
    */

    $('#closing_km').on('change', function () {

        const opening =
            parseFloat($('#opening_km').val());

        const closing =
            parseFloat($(this).val());


        if (
            !isNaN(opening) &&
            !isNaN(closing) &&
            closing < opening
        ) {

            alert(
                'Closing KM cannot be less than Opening KM.'
            );

            $(this).val('');

            $('#total_km').val('');

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Time Validation
    |--------------------------------------------------------------------------
    */

    $('#end_time').on('change', function () {

        const startTime =
            $('#start_time').val();

        const endTime =
            $(this).val();


        if (
            startTime &&
            endTime &&
            endTime < startTime
        ) {

            alert(
                'End Time cannot be before Start Time.'
            );

            $(this).val('');

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Prevent Double Submit
    |--------------------------------------------------------------------------
    */

    $('form').on('submit', function () {

        const form = this;

        const submitButton =
            $(form).find(
                'button[type="submit"]'
            );


        if (submitButton.length) {

            submitButton
                .prop('disabled', true)
                .html(
                    '<i class="fa fa-spinner fa-spin"></i> Updating Duty Slip...'
                );

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Initial Total KM
    |--------------------------------------------------------------------------
    */

    calculateTotalKm();

});

</script>

@endpush