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
        | Form Control
        |--------------------------------------------------------------------------
        */

        .form-control:focus {
            border-color: #023a85;
            box-shadow: 0 0 0 0.1rem rgba(2, 58, 133, .15);
        }

        /*
        |--------------------------------------------------------------------------
        | Tables
        |--------------------------------------------------------------------------
        */

        .table-bordered,
        .table-bordered td,
        .table-bordered th {
            border: 2px solid #023a85;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        /*
        |--------------------------------------------------------------------------
        | File Preview
        |--------------------------------------------------------------------------
        */

        .duty-slip-file-preview {
            margin-top: 12px;
        }

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        .financial-summary-box {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background: #f8f9fa;
        }

        /*
        |--------------------------------------------------------------------------
        | Readonly
        |--------------------------------------------------------------------------
        */

        input[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }

        /*
        |--------------------------------------------------------------------------
        | Helper
        |--------------------------------------------------------------------------
        */

        .form-helper-text {
            font-size: 12px;
            color: #6c757d;
        }

        /*
        |--------------------------------------------------------------------------
        | Auto Number
        |--------------------------------------------------------------------------
        */

        .auto-number-wrapper {
            position: relative;
        }

        .auto-number-badge {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: #023a85;
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 4px 7px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: .4px;
            pointer-events: none;
        }

        /*
        |--------------------------------------------------------------------------
        | Empty Row
        |--------------------------------------------------------------------------
        */

        .child-row-empty {
            opacity: .85;
        }

        /*
        |--------------------------------------------------------------------------
        | Template
        |--------------------------------------------------------------------------
        */

        template {
            display: none !important;
        }
    </style>
@endpush

@section('content')

    @php

        /*
    |--------------------------------------------------------------------------
    | OLD ALLOWANCES
    |--------------------------------------------------------------------------
    */

        $oldAllowances = old('driver_allowances');

        if ($oldAllowances === null) {
            $oldAllowances = old('allowances', []);
        }

        if (!is_array($oldAllowances)) {
            $oldAllowances = [];
        }

        if (empty($oldAllowances)) {
            $oldAllowances = [
                [
                    'allowance_id' => '',
                    'quantity' => 1,
                    'rate' => 0,
                    'amount' => 0,
                    'remarks' => '',
                    'status' => 'pending',
                ],
            ];
        }

        /*
    |--------------------------------------------------------------------------
    | OLD EXPENSES
    |--------------------------------------------------------------------------
    */

        $oldExpenses = old('driver_expenses');

        if ($oldExpenses === null) {
            $oldExpenses = old('expenses', []);
        }

        if (!is_array($oldExpenses)) {
            $oldExpenses = [];
        }

        if (empty($oldExpenses)) {
            $oldExpenses = [
                [
                    'expense_id' => '',
                    'quantity' => 1,
                    'rate' => 0,
                    'amount' => 0,
                    'remarks' => '',
                    'status' => 'pending',
                ],
            ];
        }

        /*
    |--------------------------------------------------------------------------
    | AUTO DUTY SLIP NUMBER
    |--------------------------------------------------------------------------
    */

        $generatedSlipNo = old('slip_no', $nextSlipNo ?? 'DS000001');

    @endphp

    <div class="pd-ltr-20 xs-pd-20-10">

        <div class="min-height-200px">

            {{-- ================================================================ --}}
            {{-- PAGE HEADER                                                       --}}
            {{-- ================================================================ --}}

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

            {{-- ================================================================ --}}
            {{-- ERROR SUMMARY                                                     --}}
            {{-- ================================================================ --}}

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

            {{-- ================================================================ --}}
            {{-- FORM                                                              --}}
            {{-- ================================================================ --}}

            <form id="duty-slip-form" action="{{ route('duty-slips.store') }}" method="POST" enctype="multipart/form-data"
                novalidate>

                @csrf

                <div class="card-box pd-20 mb-30">

                    <div class="row">

                        {{-- ==================================================== --}}
                        {{-- DUTY SLIP INFORMATION                                --}}
                        {{-- ==================================================== --}}

                        <div class="col-12">

                            <h5 class="form-section-title">
                                Duty Slip Information
                            </h5>

                            <hr>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- DUTY SLIP NUMBER                                     --}}
                        {{-- ==================================================== --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="slip_no">

                                    <b>
                                        Duty Slip Number
                                    </b>

                                    <span class="required">*</span>

                                </label>

                                <div class="auto-number-wrapper">

                                    <input type="text" name="slip_no" id="slip_no"
                                        class="form-control @error('slip_no') is-invalid @enderror"
                                        value="{{ $generatedSlipNo }}" maxlength="100" autocomplete="off" required>

                                    <span class="auto-number-badge">
                                        Auto
                                    </span>

                                </div>

                                @error('slip_no')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <small class="form-helper-text">
                                    Duty Slip number is automatically generated in sequence.
                                </small>

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- DUTY ASSIGNMENT                                      --}}
                        {{-- ==================================================== --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="duty_assignment_id">

                                    <b>
                                        Duty Assignment
                                    </b>

                                    <span class="required">*</span>

                                </label>

                                <select name="duty_assignment_id" id="duty_assignment_id"
                                    class="form-control custom-select2 @error('duty_assignment_id') is-invalid @enderror"
                                    required>

                                    <option value="">
                                        Select Duty Assignment
                                    </option>

                                    @foreach ($dutyAssignments ?? collect() as $assignment)
                                        @php

                                            $assignmentDriverName = trim(
                                                ($assignment->driver->first_name ?? '') .
                                                    ' ' .
                                                    ($assignment->driver->last_name ?? ''),
                                            );

                                            $assignmentVehicleNumber =
                                                $assignment->vehicle->vehicle_number ??
                                                ($assignment->vehicle->registration_number ?? '');
                                        @endphp

                                        <option value="{{ $assignment->id }}"
                                            {{ (string) old('duty_assignment_id') === (string) $assignment->id ? 'selected' : '' }}>

                                            {{ $assignment->assignment_no ?? 'DA-' . $assignment->id }}

                                            @if ($assignmentDriverName !== '')
                                                - {{ $assignmentDriverName }}
                                            @endif

                                            @if ($assignmentVehicleNumber !== '')
                                                - {{ $assignmentVehicleNumber }}
                                            @endif

                                        </option>
                                    @endforeach

                                </select>

                                @error('duty_assignment_id')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <small class="form-helper-text">
                                    Duty Assignment is a separate reference.
                                    It will not change Driver, Vehicle or Vehicle Type.
                                </small>

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- DUTY DATE                                             --}}
                        {{-- ==================================================== --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="duty_date">

                                    <b>
                                        Duty Date
                                    </b>

                                    <span class="required">*</span>

                                </label>

                                <input type="date" name="duty_date" id="duty_date"
                                    class="form-control @error('duty_date') is-invalid @enderror"
                                    value="{{ old('duty_date', date('Y-m-d')) }}" required>

                                @error('duty_date')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- DRIVER / VEHICLE INFORMATION                         --}}
                        {{-- ==================================================== --}}

                        <div class="col-12 mt-3">

                            <h5 class="form-section-title">
                                Driver & Vehicle Information
                            </h5>

                            <hr>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- DRIVER                                                 --}}
                        {{-- ==================================================== --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="driver_id">

                                    <b>
                                        Driver
                                    </b>

                                    <span class="required">*</span>

                                </label>

                                <select name="driver_id" id="driver_id"
                                    class="form-control custom-select2 @error('driver_id') is-invalid @enderror" required>

                                    <option value="">
                                        Select Driver
                                    </option>

                                    @foreach ($drivers ?? collect() as $driver)
                                        @php

                                            $driverName = trim(
                                                ($driver->first_name ?? '') . ' ' . ($driver->last_name ?? ''),
                                            );

                                            $driverCode = $driver->driver_code ?? 'DRV-' . $driver->id;
                                        @endphp

                                        <option value="{{ $driver->id }}"
                                            {{ (string) old('driver_id') === (string) $driver->id ? 'selected' : '' }}>

                                            {{ $driverCode }}

                                            @if ($driverName !== '')
                                                - {{ $driverName }}
                                            @endif

                                        </option>
                                    @endforeach

                                </select>

                                @error('driver_id')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <small class="form-helper-text">
                                    Select the Driver manually.
                                </small>

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- VEHICLE                                                --}}
                        {{-- ==================================================== --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="vehicle_id">

                                    <b>
                                        Vehicle
                                    </b>

                                    <span class="required">*</span>

                                </label>

                                <select name="vehicle_id" id="vehicle_id"
                                    class="form-control custom-select2 @error('vehicle_id') is-invalid @enderror" required>

                                    <option value="">
                                        Select Vehicle
                                    </option>

                                    @foreach ($vehicles ?? collect() as $vehicle)
                                        @php

                                            $vehicleNumber =
                                                $vehicle->vehicle_number ??
                                                ($vehicle->registration_number ?? 'Vehicle-' . $vehicle->id);

                                            $vehicleRegistration = $vehicle->registration_number ?? '';

                                        @endphp

                                        <option value="{{ $vehicle->id }}"
                                            {{ (string) old('vehicle_id') === (string) $vehicle->id ? 'selected' : '' }}>

                                            {{ $vehicleNumber }}

                                            @if ($vehicleRegistration !== '' && $vehicleRegistration !== $vehicleNumber)
                                                - {{ $vehicleRegistration }}
                                            @endif

                                        </option>
                                    @endforeach

                                </select>

                                @error('vehicle_id')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <small class="form-helper-text">
                                    Select the Vehicle manually.
                                </small>

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- VEHICLE TYPE                                           --}}
                        {{-- ==================================================== --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="vehicle_type_id">

                                    <b>
                                        Vehicle Type
                                    </b>

                                    <span class="required">*</span>

                                </label>

                                <select name="vehicle_type_id" id="vehicle_type_id"
                                    class="form-control custom-select2 @error('vehicle_type_id') is-invalid @enderror"
                                    required>

                                    <option value="">
                                        Select Vehicle Type
                                    </option>

                                    @foreach ($vehicleTypes ?? collect() as $vehicleType)
                                        <option value="{{ $vehicleType->id }}"
                                            {{ (string) old('vehicle_type_id') === (string) $vehicleType->id ? 'selected' : '' }}>

                                            {{ $vehicleType->name }}

                                            @if ($vehicleType->code)
                                                ({{ $vehicleType->code }})
                                            @endif

                                        </option>
                                    @endforeach

                                </select>

                                @error('vehicle_type_id')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <small class="form-helper-text">
                                    Select the Vehicle Type manually.
                                </small>

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- DOCUMENT SECTION                                      --}}
                        {{-- ==================================================== --}}

                        <div class="col-12 mt-3">

                            <h5 class="form-section-title">
                                Duty Slip Document
                            </h5>

                            <hr>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- FRONT FILE                                             --}}
                        {{-- ==================================================== --}}

                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="duty_slip_front_file">

                                    <b>
                                        Duty Slip Front
                                    </b>

                                </label>

                                <input type="file" name="duty_slip_front_file" id="duty_slip_front_file"
                                    class="form-control @error('duty_slip_front_file') is-invalid @enderror"
                                    accept=".pdf,.jpg,.jpeg,.png">

                                <small class="form-helper-text">
                                    Allowed: PDF, JPG, JPEG & PNG (Maximum 5 MB)
                                </small>

                                @error('duty_slip_front_file')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <div id="duty-slip-front-file-preview" class="duty-slip-file-preview"></div>

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- BACK FILE                                              --}}
                        {{-- ==================================================== --}}

                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="duty_slip_back_file">

                                    <b>
                                        Duty Slip Back
                                    </b>

                                </label>

                                <input type="file" name="duty_slip_back_file" id="duty_slip_back_file"
                                    class="form-control @error('duty_slip_back_file') is-invalid @enderror"
                                    accept=".pdf,.jpg,.jpeg,.png">

                                <small class="form-helper-text">
                                    Allowed: PDF, JPG, JPEG & PNG (Maximum 5 MB)
                                </small>

                                @error('duty_slip_back_file')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <div id="duty-slip-back-file-preview" class="duty-slip-file-preview"></div>

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- TRIP INFORMATION                                      --}}
                        {{-- ==================================================== --}}

                        <div class="col-12 mt-3">

                            <h5 class="form-section-title">
                                Trip Information
                            </h5>

                            <hr>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- START DATE                                             --}}
                        {{-- ==================================================== --}}

                        <div class="col-md-3">

                            <div class="form-group">

                                <label for="start_date">
                                    <b>Start Date</b>
                                </label>

                                <input type="date" name="start_date" id="start_date"
                                    class="form-control @error('start_date') is-invalid @enderror"
                                    value="{{ old('start_date') }}">

                                @error('start_date')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- START TIME                                             --}}
                        {{-- ==================================================== --}}

                        <div class="col-md-3">

                            <div class="form-group">

                                <label for="start_time">
                                    <b>Start Time</b>
                                </label>

                                <input type="time" name="start_time" id="start_time"
                                    class="form-control @error('start_time') is-invalid @enderror"
                                    value="{{ old('start_time') }}">

                                @error('start_time')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- END DATE                                               --}}
                        {{-- ==================================================== --}}

                        <div class="col-md-3">

                            <div class="form-group">

                                <label for="end_date">
                                    <b>End Date</b>
                                </label>

                                <input type="date" name="end_date" id="end_date"
                                    class="form-control @error('end_date') is-invalid @enderror"
                                    value="{{ old('end_date') }}">

                                @error('end_date')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- END TIME                                               --}}
                        {{-- ==================================================== --}}

                        <div class="col-md-3">

                            <div class="form-group">

                                <label for="end_time">
                                    <b>End Time</b>
                                </label>

                                <input type="time" name="end_time" id="end_time"
                                    class="form-control @error('end_time') is-invalid @enderror"
                                    value="{{ old('end_time') }}">

                                @error('end_time')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- PICKUP LOCATION                                        --}}
                        {{-- ==================================================== --}}

                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="pickup_location">
                                    <b>Pickup Location</b>
                                </label>

                                <input type="text" name="pickup_location" id="pickup_location"
                                    class="form-control @error('pickup_location') is-invalid @enderror"
                                    value="{{ old('pickup_location') }}" placeholder="Enter Pickup Location"
                                    maxlength="500">

                                @error('pickup_location')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- DROP LOCATION                                          --}}
                        {{-- ==================================================== --}}

                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="drop_location">
                                    <b>Drop Location</b>
                                </label>

                                <input type="text" name="drop_location" id="drop_location"
                                    class="form-control @error('drop_location') is-invalid @enderror"
                                    value="{{ old('drop_location') }}" placeholder="Enter Drop Location"
                                    maxlength="500">

                                @error('drop_location')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- KM SECTION                                             --}}
                        {{-- ==================================================== --}}

                        <div class="col-12 mt-3">

                            <h5 class="form-section-title">
                                Kilometer Information
                            </h5>

                            <hr>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- OPENING KM                                              --}}
                        {{-- ==================================================== --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="opening_km">
                                    <b>Opening KM</b>
                                </label>

                                <input type="number" name="opening_km" id="opening_km"
                                    class="form-control @error('opening_km') is-invalid @enderror"
                                    value="{{ old('opening_km') }}" min="0" step="0.01"
                                    placeholder="Enter Opening KM">

                                @error('opening_km')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- CLOSING KM                                              --}}
                        {{-- ==================================================== --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="closing_km">
                                    <b>Closing KM</b>
                                </label>

                                <input type="number" name="closing_km" id="closing_km"
                                    class="form-control @error('closing_km') is-invalid @enderror"
                                    value="{{ old('closing_km') }}" min="0" step="0.01"
                                    placeholder="Enter Closing KM">

                                @error('closing_km')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- TOTAL KM                                                --}}
                        {{-- ==================================================== --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="total_km">
                                    <b>Total KM</b>
                                </label>

                                <input type="number" name="total_km" id="total_km" class="form-control"
                                    value="{{ old('total_km', '0.00') }}" min="0" step="0.01"
                                    placeholder="Auto Calculated" readonly>

                                <small class="form-helper-text">
                                    Closing KM minus Opening KM.
                                </small>

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- PASSENGER SECTION                                      --}}
                        {{-- ==================================================== --}}

                        <div class="col-12 mt-3">

                            <h5 class="form-section-title">
                                Passenger Information
                            </h5>

                            <hr>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- PASSENGER NAME                                          --}}
                        {{-- ==================================================== --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="passenger_name">
                                    <b>Passenger Name</b>
                                </label>

                                <input type="text" name="passenger_name" id="passenger_name"
                                    class="form-control @error('passenger_name') is-invalid @enderror"
                                    value="{{ old('passenger_name') }}" placeholder="Enter Passenger Name"
                                    maxlength="150">

                                @error('passenger_name')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- PASSENGER MOBILE                                       --}}
                        {{-- ==================================================== --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="passenger_mobile">
                                    <b>Passenger Mobile</b>
                                </label>

                                <input type="text" name="passenger_mobile" id="passenger_mobile" maxlength="10"
                                    inputmode="numeric"
                                    class="form-control @error('passenger_mobile') is-invalid @enderror"
                                    value="{{ old('passenger_mobile') }}" placeholder="Enter Passenger Mobile">

                                @error('passenger_mobile')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- PASSENGER COUNT                                        --}}
                        {{-- ==================================================== --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="number_of_passengers">
                                    <b>Number Of Passengers</b>
                                </label>

                                <input type="number" name="number_of_passengers" id="number_of_passengers"
                                    class="form-control @error('number_of_passengers') is-invalid @enderror"
                                    value="{{ old('number_of_passengers', 1) }}" min="1" step="1">

                                @error('number_of_passengers')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- ALLOWANCE SECTION                                      --}}
                        {{-- ==================================================== --}}

                        <div class="col-12 mt-4">

                            <h5 class="form-section-title">
                                Driver Allowance
                            </h5>

                            <hr>

                        </div>

                        <div class="col-12">

                            <div class="table-responsive">

                                <table class="table table-bordered table-striped" id="allowance-table">

                                    <thead>

                                        <tr>

                                            <th style="width:26%;">
                                                Allowance
                                            </th>

                                            <th style="width:13%;">
                                                Quantity
                                            </th>

                                            <th style="width:13%;">
                                                Rate
                                            </th>

                                            <th style="width:13%;">
                                                Amount
                                            </th>

                                            <th style="width:17%;">
                                                Remarks
                                            </th>

                                            <th style="width:10%;">
                                                Status
                                            </th>

                                            <th style="width:8%;">
                                                Action
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody id="allowance-wrapper">

                                        @foreach ($oldAllowances as $index => $row)
                                            @php

                                                $selectedAllowanceId = $row['allowance_id'] ?? '';

                                                $quantity = $row['quantity'] ?? 1;

                                                $rate = $row['rate'] ?? 0;

                                                $amount = $row['amount'] ?? 0;

                                                $remarks = $row['remarks'] ?? '';

                                                $rowStatus = $row['status'] ?? 'pending';

                                            @endphp

                                            <tr class="allowance-row" data-index="{{ $index }}">

                                                <td>

                                                    <select name="driver_allowances[{{ $index }}][allowance_id]"
                                                        class="form-control custom-select2 allowance-select">

                                                        <option value="">
                                                            Select Allowance
                                                        </option>

                                                        @foreach ($allowances ?? collect() as $allowance)
                                                            <option value="{{ $allowance->id }}"
                                                                data-rate="{{ $allowance->amount ?? 0 }}"
                                                                data-calculation-type="{{ $allowance->calculation_type ?? 'fixed' }}"
                                                                {{ (string) $selectedAllowanceId === (string) $allowance->id ? 'selected' : '' }}>
                                                                {{ $allowance->name }}
                                                            </option>
                                                        @endforeach

                                                    </select>

                                                </td>

                                                <td>

                                                    <input type="number"
                                                        name="driver_allowances[{{ $index }}][quantity]"
                                                        class="form-control allowance-quantity"
                                                        value="{{ $quantity }}" min="0.01" step="0.01">

                                                </td>

                                                <td>

                                                    <input type="number"
                                                        name="driver_allowances[{{ $index }}][rate]"
                                                        class="form-control allowance-rate" value="{{ $rate }}"
                                                        min="0" step="0.01" readonly>

                                                </td>

                                                <td>

                                                    <input type="number"
                                                        name="driver_allowances[{{ $index }}][amount]"
                                                        class="form-control allowance-amount" value="{{ $amount }}"
                                                        min="0" step="0.01" readonly>

                                                </td>

                                                <td>

                                                    <input type="text"
                                                        name="driver_allowances[{{ $index }}][remarks]"
                                                        class="form-control" value="{{ $remarks }}"
                                                        placeholder="Remarks" maxlength="1000">

                                                </td>

                                                <td>

                                                    <select name="driver_allowances[{{ $index }}][status]"
                                                        class="form-control custom-select2">

                                                        @foreach ([
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'paid' => 'Paid',
            'cancelled' => 'Cancelled',
        ] as $statusValue => $statusLabel)
                                                            <option value="{{ $statusValue }}"
                                                                {{ $rowStatus === $statusValue ? 'selected' : '' }}>
                                                                {{ $statusLabel }}
                                                            </option>
                                                        @endforeach

                                                    </select>

                                                </td>

                                                <td class="text-center">

                                                    <button type="button" class="btn btn-danger btn-sm remove-allowance"
                                                        title="Remove">
                                                        <i class="fa fa-trash"></i>
                                                    </button>

                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                            <div class="mt-2">

                                <button type="button" id="add-allowance" class="btn btn-primary btn-sm">

                                    <i class="fa fa-plus"></i>
                                    Add More Allowance

                                </button>

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- EXPENSE SECTION                                        --}}
                        {{-- ==================================================== --}}

                        <div class="col-12 mt-4">

                            <h5 class="form-section-title">
                                Driver Expense
                            </h5>

                            <hr>

                        </div>

                        <div class="col-12">

                            <div class="table-responsive">

                                <table class="table table-bordered table-striped" id="expense-table">

                                    <thead>

                                        <tr>

                                            <th style="width:26%;">
                                                Expense
                                            </th>

                                            <th style="width:13%;">
                                                Quantity
                                            </th>

                                            <th style="width:13%;">
                                                Rate
                                            </th>

                                            <th style="width:13%;">
                                                Amount
                                            </th>

                                            <th style="width:17%;">
                                                Remarks
                                            </th>

                                            <th style="width:10%;">
                                                Status
                                            </th>

                                            <th style="width:8%;">
                                                Action
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody id="expense-wrapper">

                                        @foreach ($oldExpenses as $index => $row)
                                            @php

                                                $selectedExpenseId = $row['expense_id'] ?? '';

                                                $quantity = $row['quantity'] ?? 1;

                                                $rate = $row['rate'] ?? 0;

                                                $amount = $row['amount'] ?? 0;

                                                $remarks = $row['remarks'] ?? '';

                                                $rowStatus = $row['status'] ?? 'pending';

                                            @endphp

                                            <tr class="expense-row" data-index="{{ $index }}">

                                                <td>

                                                    <select name="driver_expenses[{{ $index }}][expense_id]"
                                                        class="form-control custom-select2 expense-select">

                                                        <option value="">
                                                            Select Expense
                                                        </option>

                                                        @foreach ($expenses ?? collect() as $expense)
                                                            <option value="{{ $expense->id }}"
                                                                data-rate="{{ $expense->amount ?? 0 }}"
                                                                {{ (string) $selectedExpenseId === (string) $expense->id ? 'selected' : '' }}>
                                                                {{ $expense->name }}
                                                            </option>
                                                        @endforeach

                                                    </select>

                                                </td>

                                                <td>

                                                    <input type="number"
                                                        name="driver_expenses[{{ $index }}][quantity]"
                                                        class="form-control expense-quantity" value="{{ $quantity }}"
                                                        min="0.01" step="0.01">

                                                </td>

                                                <td>

                                                    <input type="number"
                                                        name="driver_expenses[{{ $index }}][rate]"
                                                        class="form-control expense-rate" value="{{ $rate }}"
                                                        min="0" step="0.01" readonly>

                                                </td>

                                                <td>

                                                    <input type="number"
                                                        name="driver_expenses[{{ $index }}][amount]"
                                                        class="form-control expense-amount" value="{{ $amount }}"
                                                        min="0" step="0.01" readonly>

                                                </td>

                                                <td>

                                                    <input type="text"
                                                        name="driver_expenses[{{ $index }}][remarks]"
                                                        class="form-control" value="{{ $remarks }}"
                                                        placeholder="Remarks" maxlength="1000">

                                                </td>

                                                <td>

                                                    <select name="driver_expenses[{{ $index }}][status]"
                                                        class="form-control custom-select2">

                                                        @foreach ([
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'paid' => 'Paid',
            'cancelled' => 'Cancelled',
        ] as $statusValue => $statusLabel)
                                                            <option value="{{ $statusValue }}"
                                                                {{ $rowStatus === $statusValue ? 'selected' : '' }}>
                                                                {{ $statusLabel }}
                                                            </option>
                                                        @endforeach

                                                    </select>

                                                </td>

                                                <td class="text-center">

                                                    <button type="button" class="btn btn-danger btn-sm remove-expense"
                                                        title="Remove">
                                                        <i class="fa fa-trash"></i>
                                                    </button>

                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                            <div class="mt-2">

                                <button type="button" id="add-expense" class="btn btn-primary btn-sm">

                                    <i class="fa fa-plus"></i>
                                    Add More Expense

                                </button>

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- FINANCIAL SUMMARY                                      --}}
                        {{-- ==================================================== --}}

                        <div class="col-12 mt-4">

                            <div class="card financial-summary-box">

                                <div class="card-body">

                                    <h6 class="text-primary" style="color:#023a85 !important;">
                                        <b>Financial Summary</b>
                                    </h6>

                                    <small class="form-helper-text">
                                        Totals are for display only.
                                        Final financial values are calculated by the backend service.
                                    </small>

                                    <hr>

                                    <div class="row">

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label>
                                                    Total Allowance
                                                </label>

                                                <input type="text" id="total-allowance" class="form-control"
                                                    value="0.00" readonly>

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label>
                                                    Total Expense
                                                </label>

                                                <input type="text" id="total-expense" class="form-control"
                                                    value="0.00" readonly>

                                            </div>

                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <label>
                                                    <b>Grand Total</b>
                                                </label>

                                                <input type="text" id="grand-total" class="form-control"
                                                    value="0.00" readonly>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- REMARKS                                               --}}
                        {{-- ==================================================== --}}

                        <div class="col-12 mt-3">

                            <h5 class="form-section-title">
                                Remarks
                            </h5>

                            <hr>

                        </div>

                        <div class="col-12">

                            <div class="form-group">

                                <label for="remarks">
                                    <b>Remarks</b>
                                </label>

                                <textarea name="remarks" id="remarks" rows="4" class="form-control @error('remarks') is-invalid @enderror"
                                    placeholder="Enter Remarks" maxlength="2000">{{ old('remarks') }}</textarea>

                                @error('remarks')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- STATUS                                                --}}
                        {{-- ==================================================== --}}

                        <div class="col-12 mt-3">

                            <h5 class="form-section-title">
                                Status
                            </h5>

                            <hr>

                        </div>

                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="status">

                                    <b>
                                        Status
                                    </b>

                                    <span class="required">*</span>

                                </label>

                                <select name="status" id="status"
                                    class="form-control custom-select2 @error('status') is-invalid @enderror" required>

                                    <option value="open" {{ old('status', 'open') === 'open' ? 'selected' : '' }}>
                                        Open
                                    </option>

                                    <option value="started" {{ old('status') === 'started' ? 'selected' : '' }}>
                                        Started
                                    </option>

                                    <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>
                                        Completed
                                    </option>

                                    <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>
                                        Cancelled
                                    </option>

                                </select>

                                @error('status')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>

                        {{-- ==================================================== --}}
                        {{-- ACTIONS                                                --}}
                        {{-- ==================================================== --}}

                        <div class="col-12">

                            <div class="text-right mt-4">

                                <a href="{{ route('duty-slips.index') }}" class="btn btn-danger">
                                    <i class="fa fa-times"></i>
                                    Cancel
                                </a>

                                <button type="submit" id="save-duty-slip" class="btn btn-success">
                                    <i class="fa fa-save"></i>
                                    Save Duty Slip
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </form>

            {{-- ================================================================= --}}
            {{-- ALLOWANCE TEMPLATE                                                 --}}
            {{-- ================================================================= --}}

            <template id="allowance-row-template">

                <tr class="allowance-row" data-index="__INDEX__">

                    <td>

                        <select name="driver_allowances[__INDEX__][allowance_id]"
                            class="form-control custom-select2 allowance-select">

                            <option value="">
                                Select Allowance
                            </option>

                            @foreach ($allowances ?? collect() as $allowance)
                                <option value="{{ $allowance->id }}" data-rate="{{ $allowance->amount ?? 0 }}"
                                    data-calculation-type="{{ $allowance->calculation_type ?? 'fixed' }}">
                                    {{ $allowance->name }}
                                </option>
                            @endforeach

                        </select>

                    </td>

                    <td>

                        <input type="number" name="driver_allowances[__INDEX__][quantity]"
                            class="form-control allowance-quantity" value="1" min="0.01" step="0.01">

                    </td>

                    <td>

                        <input type="number" name="driver_allowances[__INDEX__][rate]"
                            class="form-control allowance-rate" value="0.00" min="0" step="0.01" readonly>

                    </td>

                    <td>

                        <input type="number" name="driver_allowances[__INDEX__][amount]"
                            class="form-control allowance-amount" value="0.00" min="0" step="0.01" readonly>

                    </td>

                    <td>

                        <input type="text" name="driver_allowances[__INDEX__][remarks]" class="form-control"
                            placeholder="Remarks" maxlength="1000">

                    </td>

                    <td>

                        <select name="driver_allowances[__INDEX__][status]" class="form-control custom-select2">

                            <option value="pending" selected>
                                Pending
                            </option>

                            <option value="approved">
                                Approved
                            </option>

                            <option value="rejected">
                                Rejected
                            </option>

                            <option value="paid">
                                Paid
                            </option>

                            <option value="cancelled">
                                Cancelled
                            </option>

                        </select>

                    </td>

                    <td class="text-center">

                        <button type="button" class="btn btn-danger btn-sm remove-allowance" title="Remove">
                            <i class="fa fa-trash"></i>
                        </button>

                    </td>

                </tr>

            </template>

            {{-- ================================================================= --}}
            {{-- EXPENSE TEMPLATE                                                   --}}
            {{-- ================================================================= --}}

            <template id="expense-row-template">

                <tr class="expense-row" data-index="__INDEX__">

                    <td>

                        <select name="driver_expenses[__INDEX__][expense_id]"
                            class="form-control custom-select2 expense-select">

                            <option value="">
                                Select Expense
                            </option>

                            @foreach ($expenses ?? collect() as $expense)
                                <option value="{{ $expense->id }}" data-rate="{{ $expense->amount ?? 0 }}">
                                    {{ $expense->name }}
                                </option>
                            @endforeach

                        </select>

                    </td>

                    <td>

                        <input type="number" name="driver_expenses[__INDEX__][quantity]"
                            class="form-control expense-quantity" value="1" min="0.01" step="0.01">

                    </td>

                    <td>

                        <input type="number" name="driver_expenses[__INDEX__][rate]" class="form-control expense-rate"
                            value="0.00" min="0" step="0.01" readonly>

                    </td>

                    <td>

                        <input type="number" name="driver_expenses[__INDEX__][amount]"
                            class="form-control expense-amount" value="0.00" min="0" step="0.01" readonly>

                    </td>

                    <td>

                        <input type="text" name="driver_expenses[__INDEX__][remarks]" class="form-control"
                            placeholder="Remarks" maxlength="1000">

                    </td>

                    <td>

                        <select name="driver_expenses[__INDEX__][status]" class="form-control custom-select2">

                            <option value="pending" selected>
                                Pending
                            </option>

                            <option value="approved">
                                Approved
                            </option>

                            <option value="rejected">
                                Rejected
                            </option>

                            <option value="paid">
                                Paid
                            </option>

                            <option value="cancelled">
                                Cancelled
                            </option>

                        </select>

                    </td>

                    <td class="text-center">

                        <button type="button" class="btn btn-danger btn-sm remove-expense" title="Remove">
                            <i class="fa fa-trash"></i>
                        </button>

                    </td>

                </tr>

            </template>

        </div>

        <x-backend.footer />

    </div>

@endsection

@push('scripts')
    <script src="{{ asset('backend/assets/js/duty-slips/create.js') }}"></script>
@endpush