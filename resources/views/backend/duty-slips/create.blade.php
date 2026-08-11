@extends('backend.layouts.master')

@section('title')
    Create Duty Slip
@endsection

@push('styles')

<style>

    /*
    |--------------------------------------------------------------------------
    | Section Title
    |--------------------------------------------------------------------------
    */

    .form-section-title {

        color: #023a85 !important;

        font-weight: 600;

        margin-bottom: 10px;

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
    | Expense Box
    |--------------------------------------------------------------------------
    */

    .expense-box {

        background: #f8f9fa;

        border: 1px solid #dee2e6;

        border-radius: 6px;

        padding: 15px;

    }


    /*
    |--------------------------------------------------------------------------
    | Total Amount
    |--------------------------------------------------------------------------
    */

    .total-expense-box {

        background: #e9f7ef;

        border: 1px solid #28a745;

        border-radius: 6px;

        padding: 15px;

    }


    /*
    |--------------------------------------------------------------------------
    | Input
    |--------------------------------------------------------------------------
    */

    .form-control:focus {

        border-color: #023a85;

        box-shadow: 0 0 0 0.1rem rgba(2, 58, 133, .15);

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
                            Create New Duty Slip
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

                                Create Duty Slip

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
            action="{{ route('duty-slips.store') }}"
            method="POST">

            @csrf


            <div class="card-box pd-20 mb-30">


                {{-- ================================================= --}}
                {{-- DUTY SLIP INFORMATION --}}
                {{-- ================================================= --}}

                <div class="mb-4">

                    <h5 class="form-section-title">

                        Duty Slip Information

                    </h5>

                    <hr>

                </div>


                <div class="row">


                    {{-- ================================================= --}}
                    {{-- Duty Slip Number --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Duty Slip Number
                                </b>

                                <span class="required">
                                    *
                                </span>

                            </label>

                            <input
                                type="text"
                                name="slip_no"
                                id="slip_no"
                                class="form-control @error('slip_no') is-invalid @enderror"
                                value="{{ old('slip_no') }}"
                                placeholder="Enter Duty Slip Number">

                            @error('slip_no')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                            <small class="text-muted">

                                Example: DS001, DS002

                            </small>

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Duty Assignment --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Duty Assignment
                                </b>

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
                                            'duty_assignment_id'
                                        ) === (string) $assignment->id
                                            ? 'selected'
                                            : '' }}>

                                        {{ $assignment->assignment_no ?? 'DA-' . $assignment->id }}

                                        @if(!empty($assignment->driver))

                                            -
                                            {{ trim(
                                                ($assignment->driver->first_name ?? '') .
                                                ' ' .
                                                ($assignment->driver->last_name ?? '')
                                            ) }}

                                        @endif

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
                    {{-- Duty Date --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Duty Date
                                </b>

                                <span class="required">
                                    *
                                </span>

                            </label>

                            <input
                                type="date"
                                name="duty_date"
                                id="duty_date"
                                class="form-control @error('duty_date') is-invalid @enderror"
                                value="{{ old('duty_date', date('Y-m-d')) }}">

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
                    {{-- DRIVER INFORMATION --}}
                    {{-- ================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="form-section-title">

                            Driver & Vehicle Information

                        </h5>

                        <hr>

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
                                            'driver_id'
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
                                            'vehicle_id'
                                        ) === (string) $vehicle->id
                                            ? 'selected'
                                            : '' }}>

                                        {{ $vehicle->vehicle_number
                                            ?? $vehicle->registration_number
                                            ?? 'Vehicle-' . $vehicle->id }}

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
                    {{-- Vehicle Type --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

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
                                placeholder="Enter Vehicle Type">

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
                    {{-- TRIP INFORMATION --}}
                    {{-- ================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="form-section-title">

                            Trip Information

                        </h5>

                        <hr>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Start Date --}}
                    {{-- ================================================= --}}

                    <div class="col-md-3">

                        <div class="form-group">

                            <label>

                                <b>
                                    Start Date
                                </b>

                            </label>

                            <input
                                type="date"
                                name="start_date"
                                id="start_date"
                                class="form-control @error('start_date') is-invalid @enderror"
                                value="{{ old('start_date') }}">

                            @error('start_date')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Start Time --}}
                    {{-- ================================================= --}}

                    <div class="col-md-3">

                        <div class="form-group">

                            <label>

                                <b>
                                    Start Time
                                </b>

                            </label>

                            <input
                                type="time"
                                name="start_time"
                                id="start_time"
                                class="form-control @error('start_time') is-invalid @enderror"
                                value="{{ old('start_time') }}">

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
                    {{-- End Date --}}
                    {{-- ================================================= --}}

                    <div class="col-md-3">

                        <div class="form-group">

                            <label>

                                <b>
                                    End Date
                                </b>

                            </label>

                            <input
                                type="date"
                                name="end_date"
                                id="end_date"
                                class="form-control @error('end_date') is-invalid @enderror"
                                value="{{ old('end_date') }}">

                            @error('end_date')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- End Time --}}
                    {{-- ================================================= --}}

                    <div class="col-md-3">

                        <div class="form-group">

                            <label>

                                <b>
                                    End Time
                                </b>

                            </label>

                            <input
                                type="time"
                                name="end_time"
                                id="end_time"
                                class="form-control @error('end_time') is-invalid @enderror"
                                value="{{ old('end_time') }}">

                            @error('end_time')

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
                    {{-- KM INFORMATION --}}
                    {{-- ================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="form-section-title">

                            Kilometer Information

                        </h5>

                        <hr>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Opening KM --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Opening KM
                                </b>

                            </label>

                            <input
                                type="number"
                                name="opening_km"
                                id="opening_km"
                                class="form-control @error('opening_km') is-invalid @enderror"
                                value="{{ old('opening_km') }}"
                                min="0"
                                step="0.01"
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

                            <label>

                                <b>
                                    Closing KM
                                </b>

                            </label>

                            <input
                                type="number"
                                name="closing_km"
                                id="closing_km"
                                class="form-control @error('closing_km') is-invalid @enderror"
                                value="{{ old('closing_km') }}"
                                min="0"
                                step="0.01"
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

                            <label>

                                <b>
                                    Total KM
                                </b>

                            </label>

                            <input
                                type="number"
                                name="total_km"
                                id="total_km"
                                class="form-control"
                                value="{{ old('total_km') }}"
                                min="0"
                                step="0.01"
                                placeholder="Auto Calculated"
                                readonly>

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- PASSENGER INFORMATION --}}
                    {{-- ================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="form-section-title">

                            Passenger Information

                        </h5>

                        <hr>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Passenger Name --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Passenger Name
                                </b>

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
                    {{-- Passenger Mobile --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Passenger Mobile
                                </b>

                            </label>

                            <input
                                type="text"
                                name="passenger_mobile"
                                id="passenger_mobile"
                                maxlength="10"
                                class="form-control @error('passenger_mobile') is-invalid @enderror"
                                value="{{ old('passenger_mobile') }}"
                                placeholder="Enter Passenger Mobile">

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

                            <label>

                                <b>
                                    Number Of Passengers
                                </b>

                            </label>

                            <input
                                type="number"
                                name="number_of_passengers"
                                id="number_of_passengers"
                                class="form-control @error('number_of_passengers') is-invalid @enderror"
                                value="{{ old('number_of_passengers', 1) }}"
                                min="1"
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
                    {{-- EXPENSE INFORMATION --}}
                    {{-- ================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="form-section-title">

                            Expense Information

                        </h5>

                        <hr>

                    </div>



                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>Fuel Expense</b>
                            </label>

                            <input
                                type="number"
                                name="fuel_expense"
                                id="fuel_expense"
                                class="form-control expense-input @error('fuel_expense') is-invalid @enderror"
                                value="{{ old('fuel_expense', 0) }}"
                                min="0"
                                step="0.01"
                                placeholder="0.00">

                            @error('fuel_expense')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>Toll Expense</b>
                            </label>

                            <input
                                type="number"
                                name="toll_expense"
                                id="toll_expense"
                                class="form-control expense-input @error('toll_expense') is-invalid @enderror"
                                value="{{ old('toll_expense', 0) }}"
                                min="0"
                                step="0.01"
                                placeholder="0.00">

                            @error('toll_expense')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>Parking Expense</b>
                            </label>

                            <input
                                type="number"
                                name="parking_expense"
                                id="parking_expense"
                                class="form-control expense-input @error('parking_expense') is-invalid @enderror"
                                value="{{ old('parking_expense', 0) }}"
                                min="0"
                                step="0.01"
                                placeholder="0.00">

                            @error('parking_expense')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    <div class="col-md-3">

                        <div class="form-group">

                            <label>
                                <b>Other Expense</b>
                            </label>

                            <input
                                type="number"
                                name="other_expense"
                                id="other_expense"
                                class="form-control expense-input @error('other_expense') is-invalid @enderror"
                                value="{{ old('other_expense', 0) }}"
                                min="0"
                                step="0.01"
                                placeholder="0.00">

                            @error('other_expense')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Total Expense --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="total-expense-box">

                            <label>

                                <b>
                                    Total Expense
                                </b>

                            </label>

                            <input
                                type="number"
                                name="total_expense"
                                id="total_expense"
                                class="form-control"
                                value="{{ old('total_expense', 0) }}"
                                readonly
                                step="0.01">

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Driver Advance --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Driver Advance
                                </b>

                            </label>

                            <input
                                type="number"
                                name="driver_advance"
                                id="driver_advance"
                                class="form-control @error('driver_advance') is-invalid @enderror"
                                value="{{ old('driver_advance', 0) }}"
                                min="0"
                                step="0.01"
                                placeholder="0.00">

                            @error('driver_advance')

                                <span class="invalid-feedback d-block">

                                    <strong>
                                        {{ $message }}
                                    </strong>

                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- Balance Amount --}}
                    {{-- ================================================= --}}

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>
                                    Balance Amount
                                </b>

                            </label>

                            <input
                                type="number"
                                name="balance_amount"
                                id="balance_amount"
                                class="form-control"
                                value="{{ old('balance_amount', 0) }}"
                                readonly
                                step="0.01">

                        </div>

                    </div>



                    {{-- ================================================= --}}
                    {{-- REMARKS --}}
                    {{-- ================================================= --}}

                    <div class="col-12 mt-3">

                        <h5 class="form-section-title">

                            Remarks

                        </h5>

                        <hr>

                    </div>



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

                                <option
                                    value="draft"
                                    {{ old('status', 'draft') === 'draft'
                                        ? 'selected'
                                        : '' }}>
                                    Draft
                                </option>

                                <option
                                    value="open"
                                    {{ old('status') === 'open'
                                        ? 'selected'
                                        : '' }}>
                                    Open
                                </option>

                                <option
                                    value="completed"
                                    {{ old('status') === 'completed'
                                        ? 'selected'
                                        : '' }}>
                                    Completed
                                </option>

                                <option
                                    value="cancelled"
                                    {{ old('status') === 'cancelled'
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
                                href="{{ route('duty-slips.index') }}"
                                class="btn btn-danger">

                                <i class="fa fa-times"></i>

                                Cancel

                            </a>

                            <button
                                type="submit"
                                class="btn btn-success">

                                <i class="fa fa-save"></i>

                                Save Duty Slip

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
    | Duty Slip Number Formatting
    |--------------------------------------------------------------------------
    */

    $('#slip_no').on('blur', function () {

        this.value = this.value
            .trim()
            .toUpperCase()
            .replace(/\s+/g, '');

    });



    /*
    |--------------------------------------------------------------------------
    | Mobile Number Formatting
    |--------------------------------------------------------------------------
    */

    $('#passenger_mobile').on('input', function () {

        this.value = this.value
            .replace(/[^0-9]/g, '')
            .slice(0, 10);

    });



    /*
    |--------------------------------------------------------------------------
    | Number Validation
    |--------------------------------------------------------------------------
    */

    $('#opening_km, #closing_km, .expense-input, #driver_advance')
        .on('input', function () {

            if (this.value < 0) {

                this.value = 0;

            }

        });



    /*
    |--------------------------------------------------------------------------
    | Calculate Total KM
    |--------------------------------------------------------------------------
    */

    function calculateTotalKm()
    {

        const opening =
            parseFloat(
                $('#opening_km').val()
            ) || 0;

        const closing =
            parseFloat(
                $('#closing_km').val()
            ) || 0;


        if (closing >= opening) {

            $('#total_km').val(
                (closing - opening).toFixed(2)
            );

        } else {

            $('#total_km').val('0.00');

        }

    }



    $('#opening_km, #closing_km')
        .on('input change', function () {

            calculateTotalKm();

        });



    /*
    |--------------------------------------------------------------------------
    | Calculate Total Expense
    |--------------------------------------------------------------------------
    */

    function calculateTotalExpense()
    {

        const fuel =
            parseFloat(
                $('#fuel_expense').val()
            ) || 0;

        const toll =
            parseFloat(
                $('#toll_expense').val()
            ) || 0;

        const parking =
            parseFloat(
                $('#parking_expense').val()
            ) || 0;

        const other =
            parseFloat(
                $('#other_expense').val()
            ) || 0;


        const total =
            fuel +
            toll +
            parking +
            other;


        $('#total_expense').val(
            total.toFixed(2)
        );


        calculateBalance();

    }



    $('.expense-input')
        .on('input change', function () {

            calculateTotalExpense();

        });



    /*
    |--------------------------------------------------------------------------
    | Calculate Balance Amount
    |--------------------------------------------------------------------------
    */

    function calculateBalance()
    {

        const totalExpense =
            parseFloat(
                $('#total_expense').val()
            ) || 0;

        const advance =
            parseFloat(
                $('#driver_advance').val()
            ) || 0;


        const balance =
            totalExpense - advance;


        $('#balance_amount').val(
            balance.toFixed(2)
        );

    }



    $('#driver_advance')
        .on('input change', function () {

            calculateBalance();

        });



    /*
    |--------------------------------------------------------------------------
    | Date Validation
    |--------------------------------------------------------------------------
    */

    $('#start_date').on('change', function () {

        const startDate = this.value;

        const endDate =
            $('#end_date').val();


        if (
            startDate &&
            endDate
        ) {

            if (
                new Date(endDate) <
                new Date(startDate)
            ) {

                alert(
                    'End date cannot be before start date.'
                );

                $('#end_date').val('');

            }

        }

    });



    $('#end_date').on('change', function () {

        const endDate = this.value;

        const startDate =
            $('#start_date').val();


        if (
            startDate &&
            endDate
        ) {

            if (
                new Date(endDate) <
                new Date(startDate)
            ) {

                alert(
                    'End date cannot be before start date.'
                );

                this.value = '';

            }

        }

    });



    /*
    |--------------------------------------------------------------------------
    | KM Validation
    |--------------------------------------------------------------------------
    */

    $('#closing_km').on('change', function () {

        const opening =
            parseFloat(
                $('#opening_km').val()
            ) || 0;

        const closing =
            parseFloat(
                $('#closing_km').val()
            ) || 0;


        if (
            closing > 0 &&
            closing < opening
        ) {

            alert(
                'Closing KM cannot be less than Opening KM.'
            );

            this.value = '';

            $('#total_km').val('0.00');

        }

    });



    /*
    |--------------------------------------------------------------------------
    | Text Formatting
    |--------------------------------------------------------------------------
    */

    $(
        '#pickup_location, ' +
        '#drop_location, ' +
        '#passenger_name, ' +
        '#vehicle_type'
    ).on('blur', function () {

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
    | Form Submit
    |--------------------------------------------------------------------------
    */

    $('form').on('submit', function () {


        $('#slip_no').val(

            $('#slip_no')
                .val()
                .trim()
                .toUpperCase()
                .replace(/\s+/g, '')

        );


        $('#pickup_location').val(

            $('#pickup_location')
                .val()
                .replace(/\s+/g, ' ')
                .trim()

        );


        $('#drop_location').val(

            $('#drop_location')
                .val()
                .replace(/\s+/g, ' ')
                .trim()

        );


        $('#passenger_name').val(

            $('#passenger_name')
                .val()
                .replace(/\s+/g, ' ')
                .trim()

        );


        $('#remarks').val(

            $('#remarks')
                .val()
                .replace(/\s+/g, ' ')
                .trim()

        );


        /*
        |--------------------------------------------------------------------------
        | Recalculate Before Submit
        |--------------------------------------------------------------------------
        */

        calculateTotalKm();

        calculateTotalExpense();

        calculateBalance();


        /*
        |--------------------------------------------------------------------------
        | Prevent Double Submission
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
                    '<i class="fa fa-spinner fa-spin"></i> Saving Duty Slip...'
                );

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Initial Calculation
    |--------------------------------------------------------------------------
    */

    calculateTotalKm();

    calculateTotalExpense();

    calculateBalance();

});

</script>

@endpush