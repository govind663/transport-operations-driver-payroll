@extends('backend.layouts.master')

@section('title')
    Edit Duty Slip
@endsection

@push('styles')

<style>

    .form-section-title {
        color: #023a85 !important;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .required {
        color: #dc3545;
    }

    .expense-box {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 15px;
    }

    .total-expense-box {
        background: #e9f7ef;
        border: 1px solid #28a745;
        border-radius: 6px;
        padding: 15px;
    }

    .form-control:focus {
        border-color: #023a85;
        box-shadow: 0 0 0 0.1rem rgba(2, 58, 133, .15);
    }

</style>

<style>
    .table-bordered, .table-bordered td, .table-bordered th {
        border: 2px solid #023a85;
    }

    .table td,
    .table th {
        vertical-align: middle;
    }

    .vehicle-type-code {
        font-weight: 600;
        letter-spacing: 0.5px;
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

        <form action="{{ route('duty-slips.update', $dutySlip->id) }}" method="POST" enctype="multipart/form-data" id="duty-slip-form">

            @csrf
            @method('PUT')

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
                                value="{{ old('slip_no', $dutySlip->slip_no) }}"
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

                                {{-- <span class="required">
                                    *
                                </span> --}}

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
                    {{-- DUTY SLIP DOCUMENT --}}
                    {{-- ================================================= --}}
                    <div class="col-12 mt-3">

                        <h5 class="form-section-title">
                            Duty Slip Document
                        </h5>

                        <hr>

                    </div>

                    {{-- ================================================= --}}
                    {{-- DUTY SLIP FRONT --}}
                    {{-- ================================================= --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="duty_slip_front_file">
                                <b>
                                    Upload Duty Slip Front
                                </b>
                            </label>

                            <input
                                type="file"
                                name="duty_slip_front_file"
                                id="duty_slip_front_file"
                                class="form-control @error('duty_slip_front_file') is-invalid @enderror"
                                accept=".pdf,.jpg,.jpeg,.png"
                                onchange="previewDutySlipFile(
                                    'duty_slip_front_file',
                                    'duty-slip-front-file-preview'
                                )"
                            >

                            <small class="text-muted">
                                Allowed: PDF, JPG, JPEG & PNG (Maximum 5 MB)
                            </small>

                            @error('duty_slip_front_file')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror


                            {{-- ================================================= --}}
                            {{-- EXISTING / NEW FRONT FILE PREVIEW --}}
                            {{-- ================================================= --}}

                            <div
                                id="duty-slip-front-file-preview"
                                class="mt-3"
                            >

                                @if(!empty($dutySlip->duty_slip_front_file))

                                    @php

                                        $frontFilePath =
                                            $dutySlip->duty_slip_front_file;

                                        $frontFileUrl =
                                            asset(
                                                'storage/' .
                                                ltrim(
                                                    $frontFilePath,
                                                    '/'
                                                )
                                            );

                                        $frontFileExtension =
                                            strtolower(
                                                pathinfo(
                                                    $frontFilePath,
                                                    PATHINFO_EXTENSION
                                                )
                                            );

                                    @endphp


                                    {{-- FRONT IMAGE --}}

                                    @if(
                                        in_array(
                                            $frontFileExtension,
                                            ['jpg', 'jpeg', 'png']
                                        )
                                    )

                                        <div>

                                            <img
                                                src="{{ $frontFileUrl }}"
                                                alt="Duty Slip Front"
                                                class="img-thumbnail"
                                                style="
                                                    width:220px;
                                                    max-height:220px;
                                                    object-fit:contain;
                                                    border-radius:10px;
                                                    border:2px solid #dee2e6;
                                                    box-shadow:
                                                        0 2px 10px
                                                        rgba(0,0,0,.15);
                                                    background:#fff;
                                                "
                                            >


                                            <div class="mt-2">

                                                <a
                                                    href="{{ $frontFileUrl }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="btn btn-sm btn-primary"
                                                >

                                                    <i class="fa fa-eye"></i>

                                                    View Duty Slip Front

                                                </a>

                                            </div>

                                        </div>


                                    {{-- FRONT PDF --}}

                                    @elseif(
                                        $frontFileExtension === 'pdf'
                                    )

                                        <div
                                            class="alert alert-light border d-flex align-items-center"
                                            style="
                                                border-radius:10px;
                                                padding:12px 15px;
                                                max-width:450px;
                                            "
                                        >

                                            <div
                                                class="mr-3"
                                                style="
                                                    min-width:45px;
                                                    text-align:center;
                                                "
                                            >

                                                <i
                                                    class="fa fa-file-pdf-o text-danger"
                                                    style="
                                                        font-size:36px;
                                                    "
                                                ></i>

                                            </div>


                                            <div>

                                                <strong class="d-block">
                                                    Duty Slip Front PDF
                                                </strong>


                                                <a
                                                    href="{{ $frontFileUrl }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="btn btn-sm btn-primary mt-2"
                                                >

                                                    <i class="fa fa-eye"></i>

                                                    View Front PDF

                                                </a>

                                            </div>

                                        </div>

                                    @endif

                                @endif

                            </div>

                        </div>

                    </div>

                    {{-- ================================================= --}}
                    {{-- DUTY SLIP BACK --}}
                    {{-- ================================================= --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label for="duty_slip_back_file">
                                <b>
                                    Upload Duty Slip Back
                                </b>
                            </label>

                            <input
                                type="file"
                                name="duty_slip_back_file"
                                id="duty_slip_back_file"
                                class="form-control @error('duty_slip_back_file') is-invalid @enderror"
                                accept=".pdf,.jpg,.jpeg,.png"
                                onchange="previewDutySlipFile(
                                    'duty_slip_back_file',
                                    'duty-slip-back-file-preview'
                                )"
                            >

                            <small class="text-muted">
                                Allowed: PDF, JPG, JPEG & PNG (Maximum 5 MB)
                            </small>

                            @error('duty_slip_back_file')
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror


                            {{-- ================================================= --}}
                            {{-- EXISTING / NEW BACK FILE PREVIEW --}}
                            {{-- ================================================= --}}

                            <div
                                id="duty-slip-back-file-preview"
                                class="mt-3"
                            >

                                @if(!empty($dutySlip->duty_slip_back_file))

                                    @php

                                        $backFilePath =
                                            $dutySlip->duty_slip_back_file;

                                        $backFileUrl =
                                            asset(
                                                'storage/' .
                                                ltrim(
                                                    $backFilePath,
                                                    '/'
                                                )
                                            );

                                        $backFileExtension =
                                            strtolower(
                                                pathinfo(
                                                    $backFilePath,
                                                    PATHINFO_EXTENSION
                                                )
                                            );

                                    @endphp


                                    {{-- BACK IMAGE --}}

                                    @if(
                                        in_array(
                                            $backFileExtension,
                                            ['jpg', 'jpeg', 'png']
                                        )
                                    )

                                        <div>

                                            <img
                                                src="{{ $backFileUrl }}"
                                                alt="Duty Slip Back"
                                                class="img-thumbnail"
                                                style="
                                                    width:220px;
                                                    max-height:220px;
                                                    object-fit:contain;
                                                    border-radius:10px;
                                                    border:2px solid #dee2e6;
                                                    box-shadow:
                                                        0 2px 10px
                                                        rgba(0,0,0,.15);
                                                    background:#fff;
                                                "
                                            >


                                            <div class="mt-2">

                                                <a
                                                    href="{{ $backFileUrl }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="btn btn-sm btn-primary"
                                                >

                                                    <i class="fa fa-eye"></i>

                                                    View Duty Slip Back

                                                </a>

                                            </div>

                                        </div>


                                    {{-- BACK PDF --}}

                                    @elseif(
                                        $backFileExtension === 'pdf'
                                    )

                                        <div
                                            class="alert alert-light border d-flex align-items-center"
                                            style="
                                                border-radius:10px;
                                                padding:12px 15px;
                                                max-width:450px;
                                            "
                                        >

                                            <div
                                                class="mr-3"
                                                style="
                                                    min-width:45px;
                                                    text-align:center;
                                                "
                                            >

                                                <i
                                                    class="fa fa-file-pdf-o text-danger"
                                                    style="
                                                        font-size:36px;
                                                    "
                                                ></i>

                                            </div>


                                            <div>

                                                <strong class="d-block">
                                                    Duty Slip Back PDF
                                                </strong>


                                                <a
                                                    href="{{ $backFileUrl }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="btn btn-sm btn-primary mt-2"
                                                >

                                                    <i class="fa fa-eye"></i>

                                                    View Back PDF

                                                </a>

                                            </div>

                                        </div>

                                    @endif

                                @endif

                            </div>

                        </div>

                    </div>

                    {{-- ================================================= --}}
                    {{-- DRIVER & VEHICLE INFORMATION --}}
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
                                            'driver_id',
                                            $dutySlip->driver_id
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
                                            'vehicle_id',
                                            $dutySlip->vehicle_id
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
                                value="{{ old(
                                    'vehicle_type',
                                    $dutySlip->vehicle_type
                                ) }}"
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
                                <b>Start Date</b>
                            </label>

                            <input
                                type="date"
                                name="start_date"
                                id="start_date"
                                class="form-control @error('start_date') is-invalid @enderror"
                                value="{{ old(
                                    'start_date',
                                    optional($dutySlip->start_date)->format('Y-m-d')
                                ) }}">

                            @error('start_date')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
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
                                <b>Start Time</b>
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
                                    <strong>{{ $message }}</strong>
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
                                <b>End Date</b>
                            </label>

                            <input
                                type="date"
                                name="end_date"
                                id="end_date"
                                class="form-control @error('end_date') is-invalid @enderror"
                                value="{{ old(
                                    'end_date',
                                    optional($dutySlip->end_date)->format('Y-m-d')
                                ) }}">

                            @error('end_date')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
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
                                <b>End Time</b>
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
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                    </div>

                    {{-- ================================================= --}}
                    {{-- Pickup --}}
                    {{-- ================================================= --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                <b>Pickup Location</b>
                            </label>

                            <input
                                type="text"
                                name="pickup_location"
                                id="pickup_location"
                                class="form-control"
                                value="{{ old(
                                    'pickup_location',
                                    $dutySlip->pickup_location
                                ) }}"
                                placeholder="Enter Pickup Location">

                        </div>

                    </div>

                    {{-- ================================================= --}}
                    {{-- Drop --}}
                    {{-- ================================================= --}}
                    <div class="col-md-6">

                        <div class="form-group">

                            <label>
                                <b>Drop Location</b>
                            </label>

                            <input
                                type="text"
                                name="drop_location"
                                id="drop_location"
                                class="form-control"
                                value="{{ old(
                                    'drop_location',
                                    $dutySlip->drop_location
                                ) }}"
                                placeholder="Enter Drop Location">

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

                    {{-- Opening KM --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Opening KM</b>
                            </label>

                            <input
                                type="number"
                                name="opening_km"
                                id="opening_km"
                                class="form-control @error('opening_km') is-invalid @enderror"
                                value="{{ old(
                                    'opening_km',
                                    $dutySlip->opening_km
                                ) }}"
                                min="0"
                                step="0.01"
                                placeholder="Enter Opening KM">

                            @error('opening_km')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- Closing KM --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Closing KM</b>
                            </label>

                            <input
                                type="number"
                                name="closing_km"
                                id="closing_km"
                                class="form-control @error('closing_km') is-invalid @enderror"
                                value="{{ old(
                                    'closing_km',
                                    $dutySlip->closing_km
                                ) }}"
                                min="0"
                                step="0.01"
                                placeholder="Enter Closing KM">

                            @error('closing_km')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- Total KM --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Total KM</b>
                            </label>

                            <input
                                type="number"
                                name="total_km"
                                id="total_km"
                                class="form-control"
                                value="{{ old(
                                    'total_km',
                                    $dutySlip->total_km
                                ) }}"
                                min="0"
                                step="0.01"
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

                    {{-- Passenger Name --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Passenger Name</b>
                            </label>

                            <input
                                type="text"
                                name="passenger_name"
                                id="passenger_name"
                                class="form-control"
                                value="{{ old(
                                    'passenger_name',
                                    $dutySlip->passenger_name
                                ) }}"
                                placeholder="Enter Passenger Name">

                        </div>

                    </div>

                    {{-- Passenger Mobile --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Passenger Mobile</b>
                            </label>

                            <input
                                type="text"
                                name="passenger_mobile"
                                id="passenger_mobile"
                                maxlength="10"
                                class="form-control"
                                value="{{ old(
                                    'passenger_mobile',
                                    $dutySlip->passenger_mobile
                                ) }}"
                                placeholder="Enter Passenger Mobile">

                        </div>

                    </div>

                    {{-- Number Of Passengers --}}
                    <div class="col-md-4">

                        <div class="form-group">

                            <label>
                                <b>Number Of Passengers</b>
                            </label>

                            <input
                                type="number"
                                name="number_of_passengers"
                                id="number_of_passengers"
                                class="form-control"
                                value="{{ old(
                                    'number_of_passengers',
                                    $dutySlip->number_of_passengers ?? 1
                                ) }}"
                                min="1"
                                placeholder="Enter Number Of Passengers">

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- DRIVER ALLOWANCE --}}
                    {{-- ========================================================= --}}
                    <div class="col-12 mt-4">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;"
                        >
                            <b>Driver Allowance</b>
                        </h5>

                        <hr>

                    </div>

                    <div class="col-12">

                        <div class="table-responsive">

                            <table
                                class="table table-bordered table-striped"
                                id="allowance-table"
                            >

                                <thead>

                                    <tr>

                                        <th style="width:30%;">
                                            Allowance
                                        </th>

                                        <th style="width:15%;">
                                            Quantity
                                        </th>

                                        <th style="width:15%;">
                                            Rate
                                        </th>

                                        <th style="width:15%;">
                                            Amount
                                        </th>

                                        <th style="width:15%;">
                                            Remarks
                                        </th>

                                        <th style="width:12%;">
                                            Status
                                        </th>

                                        <th style="width:10%;">
                                            Action
                                        </th>

                                    </tr>

                                </thead>


                                <tbody id="allowance-wrapper">


                                    @forelse(
                                        $dutySlip->allowances ?? []
                                        as $index => $driverAllowance
                                    )

                                        <tr
                                            class="allowance-row"
                                            data-index="{{ $index }}"
                                        >


                                            {{-- Allowance --}}

                                            <td>

                                                <select
                                                    name="allowances[{{ $index }}][allowance_id]"
                                                    class="form-control custom-select2 allowance-select"
                                                >

                                                    <option value="">
                                                        Select Allowance
                                                    </option>


                                                    @foreach(
                                                        $allowances ?? []
                                                        as $allowance
                                                    )

                                                        <option
                                                            value="{{ $allowance->id }}"
                                                            data-rate="{{ $allowance->amount ?? 0 }}"
                                                            data-calculation-type="{{ $allowance->calculation_type }}"
                                                            {{ (string) $driverAllowance->allowance_id === (string) $allowance->id
                                                                ? 'selected'
                                                                : '' }}
                                                        >

                                                            {{ $allowance->name }}

                                                        </option>

                                                    @endforeach

                                                </select>

                                            </td>


                                            {{-- Quantity --}}

                                            <td>

                                                <input
                                                    type="number"
                                                    name="allowances[{{ $index }}][quantity]"
                                                    class="form-control allowance-quantity"
                                                    value="{{ old(
                                                        "allowances.$index.quantity",
                                                        $driverAllowance->quantity
                                                    ) }}"
                                                    min="0"
                                                    step="0.01"
                                                >

                                            </td>


                                            {{-- Rate --}}

                                            <td>

                                                <input
                                                    type="number"
                                                    name="allowances[{{ $index }}][rate]"
                                                    class="form-control allowance-rate"
                                                    value="{{ old(
                                                        "allowances.$index.rate",
                                                        $driverAllowance->rate
                                                    ) }}"
                                                    min="0"
                                                    step="0.01"
                                                    readonly
                                                >

                                            </td>


                                            {{-- Amount --}}

                                            <td>

                                                <input
                                                    type="number"
                                                    name="allowances[{{ $index }}][amount]"
                                                    class="form-control allowance-amount"
                                                    value="{{ old(
                                                        "allowances.$index.amount",
                                                        $driverAllowance->amount
                                                    ) }}"
                                                    min="0"
                                                    step="0.01"
                                                    readonly
                                                >

                                            </td>


                                            {{-- Remarks --}}

                                            <td>

                                                <input
                                                    type="text"
                                                    name="allowances[{{ $index }}][remarks]"
                                                    class="form-control"
                                                    value="{{ old(
                                                        "allowances.$index.remarks",
                                                        $driverAllowance->remarks
                                                    ) }}"
                                                    placeholder="Remarks"
                                                >

                                            </td>


                                            {{-- Status --}}

                                            <td>

                                                <select
                                                    name="allowances[{{ $index }}][status]"
                                                    class="form-control custom-select2"
                                                >

                                                    @foreach([
                                                        'pending' => 'Pending',
                                                        'approved' => 'Approved',
                                                        'rejected' => 'Rejected',
                                                        'paid' => 'Paid',
                                                        'cancelled' => 'Cancelled'
                                                    ] as $statusValue => $statusLabel)

                                                        <option
                                                            value="{{ $statusValue }}"
                                                            {{ old(
                                                                "allowances.$index.status",
                                                                $driverAllowance->status
                                                            ) === $statusValue
                                                                ? 'selected'
                                                                : '' }}
                                                        >

                                                            {{ $statusLabel }}

                                                        </option>

                                                    @endforeach

                                                </select>

                                            </td>


                                            {{-- Action --}}

                                            <td class="text-center">

                                                <button
                                                    type="button"
                                                    class="btn btn-danger btn-sm remove-allowance"
                                                    title="Remove"
                                                >

                                                    <i class="fa fa-trash"></i>

                                                </button>

                                            </td>


                                        </tr>

                                    @empty


                                        {{-- ================================================= --}}
                                        {{-- NO EXISTING ALLOWANCE --}}
                                        {{-- ================================================= --}}

                                        <tr
                                            class="allowance-row"
                                            data-index="0"
                                        >

                                            <td>

                                                <select
                                                    name="allowances[0][allowance_id]"
                                                    class="form-control custom-select2 allowance-select"
                                                >

                                                    <option value="">
                                                        Select Allowance
                                                    </option>

                                                    @foreach(
                                                        $allowances ?? []
                                                        as $allowance
                                                    )

                                                        <option
                                                            value="{{ $allowance->id }}"
                                                            data-rate="{{ $allowance->amount ?? 0 }}"
                                                            data-calculation-type="{{ $allowance->calculation_type }}"
                                                        >

                                                            {{ $allowance->name }}

                                                        </option>

                                                    @endforeach

                                                </select>

                                            </td>


                                            <td>

                                                <input
                                                    type="number"
                                                    name="allowances[0][quantity]"
                                                    class="form-control allowance-quantity"
                                                    value="1"
                                                    min="0"
                                                    step="0.01"
                                                >

                                            </td>


                                            <td>

                                                <input
                                                    type="number"
                                                    name="allowances[0][rate]"
                                                    class="form-control allowance-rate"
                                                    value="0.00"
                                                    min="0"
                                                    step="0.01"
                                                    readonly
                                                >

                                            </td>


                                            <td>

                                                <input
                                                    type="number"
                                                    name="allowances[0][amount]"
                                                    class="form-control allowance-amount"
                                                    value="0.00"
                                                    min="0"
                                                    step="0.01"
                                                    readonly
                                                >

                                            </td>


                                            <td>

                                                <input
                                                    type="text"
                                                    name="allowances[0][remarks]"
                                                    class="form-control"
                                                    placeholder="Remarks"
                                                >

                                            </td>


                                            <td>

                                                <select
                                                    name="allowances[0][status]"
                                                    class="form-control custom-select2"
                                                >

                                                    <option
                                                        value="pending"
                                                        selected
                                                    >
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

                                                <button
                                                    type="button"
                                                    class="btn btn-danger btn-sm remove-allowance"
                                                    disabled
                                                    title="Remove"
                                                >

                                                    <i class="fa fa-trash"></i>

                                                </button>

                                            </td>

                                        </tr>


                                    @endforelse

                                </tbody>

                            </table>

                        </div>


                        <div class="mt-2">

                            <button
                                type="button"
                                id="add-allowance"
                                class="btn btn-primary btn-sm"
                            >

                                <i class="fa fa-plus"></i>

                                Add More Allowance

                            </button>

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- DRIVER EXPENSE --}}
                    {{-- ========================================================= --}}
                    <div class="col-12 mt-4">

                        <h5
                            class="text-primary"
                            style="color:#023a85 !important;"
                        >
                            <b>Driver Expense</b>
                        </h5>

                        <hr>

                    </div>

                    <div class="col-12">

                        <div class="table-responsive">

                            <table
                                class="table table-bordered table-striped"
                                id="expense-table"
                            >

                                <thead>

                                    <tr>

                                        <th style="width:30%;">
                                            Expense
                                        </th>

                                        <th style="width:15%;">
                                            Quantity
                                        </th>

                                        <th style="width:15%;">
                                            Rate
                                        </th>

                                        <th style="width:15%;">
                                            Amount
                                        </th>

                                        <th style="width:15%;">
                                            Remarks
                                        </th>

                                        <th style="width:10%;">
                                            Status
                                        </th>

                                        <th style="width:10%;">
                                            Action
                                        </th>

                                    </tr>

                                </thead>


                                <tbody id="expense-wrapper">


                                    @forelse(
                                        $dutySlip->expenses ?? []
                                        as $index => $driverExpense
                                    )

                                        <tr
                                            class="expense-row"
                                            data-index="{{ $index }}"
                                        >


                                            {{-- Expense --}}

                                            <td>

                                                <select
                                                    name="expenses[{{ $index }}][expense_id]"
                                                    class="form-control custom-select2 expense-select"
                                                >

                                                    <option value="">
                                                        Select Expense
                                                    </option>


                                                    @foreach(
                                                        $expenses ?? []
                                                        as $expense
                                                    )

                                                        <option
                                                            value="{{ $expense->id }}"
                                                            data-rate="{{ $expense->amount ?? 0 }}"
                                                            {{ (string) $driverExpense->expense_id === (string) $expense->id
                                                                ? 'selected'
                                                                : '' }}
                                                        >

                                                            {{ $expense->name }}

                                                        </option>

                                                    @endforeach

                                                </select>

                                            </td>


                                            {{-- Quantity --}}

                                            <td>

                                                <input
                                                    type="number"
                                                    name="expenses[{{ $index }}][quantity]"
                                                    class="form-control expense-quantity"
                                                    value="{{ old(
                                                        "expenses.$index.quantity",
                                                        $driverExpense->quantity
                                                    ) }}"
                                                    min="0"
                                                    step="0.01"
                                                >

                                            </td>


                                            {{-- Rate --}}

                                            <td>

                                                <input
                                                    type="number"
                                                    name="expenses[{{ $index }}][rate]"
                                                    class="form-control expense-rate"
                                                    value="{{ old(
                                                        "expenses.$index.rate",
                                                        $driverExpense->rate
                                                    ) }}"
                                                    min="0"
                                                    step="0.01"
                                                    readonly
                                                >

                                            </td>


                                            {{-- Amount --}}

                                            <td>

                                                <input
                                                    type="number"
                                                    name="expenses[{{ $index }}][amount]"
                                                    class="form-control expense-amount"
                                                    value="{{ old(
                                                        "expenses.$index.amount",
                                                        $driverExpense->amount
                                                    ) }}"
                                                    min="0"
                                                    step="0.01"
                                                    readonly
                                                >

                                            </td>


                                            {{-- Remarks --}}

                                            <td>

                                                <input
                                                    type="text"
                                                    name="expenses[{{ $index }}][remarks]"
                                                    class="form-control"
                                                    value="{{ old(
                                                        "expenses.$index.remarks",
                                                        $driverExpense->remarks
                                                    ) }}"
                                                    placeholder="Remarks"
                                                >

                                            </td>


                                            {{-- Status --}}

                                            <td>

                                                <select
                                                    name="expenses[{{ $index }}][status]"
                                                    class="form-control custom-select2"
                                                >

                                                    @foreach([
                                                        'pending' => 'Pending',
                                                        'approved' => 'Approved',
                                                        'rejected' => 'Rejected',
                                                        'paid' => 'Paid',
                                                        'cancelled' => 'Cancelled'
                                                    ] as $statusValue => $statusLabel)

                                                        <option
                                                            value="{{ $statusValue }}"
                                                            {{ old(
                                                                "expenses.$index.status",
                                                                $driverExpense->status
                                                            ) === $statusValue
                                                                ? 'selected'
                                                                : '' }}
                                                        >

                                                            {{ $statusLabel }}

                                                        </option>

                                                    @endforeach

                                                </select>

                                            </td>


                                            {{-- Action --}}

                                            <td class="text-center">

                                                <button
                                                    type="button"
                                                    class="btn btn-danger btn-sm remove-expense"
                                                    title="Remove"
                                                >

                                                    <i class="fa fa-trash"></i>

                                                </button>

                                            </td>

                                        </tr>


                                    @empty


                                        {{-- Empty Expense Row --}}

                                        <tr
                                            class="expense-row"
                                            data-index="0"
                                        >

                                            <td>

                                                <select
                                                    name="expenses[0][expense_id]"
                                                    class="form-control custom-select2 expense-select"
                                                >

                                                    <option value="">
                                                        Select Expense
                                                    </option>

                                                    @foreach(
                                                        $expenses ?? []
                                                        as $expense
                                                    )

                                                        <option
                                                            value="{{ $expense->id }}"
                                                            data-rate="{{ $expense->amount ?? 0 }}"
                                                        >

                                                            {{ $expense->name }}

                                                        </option>

                                                    @endforeach

                                                </select>

                                            </td>


                                            <td>

                                                <input
                                                    type="number"
                                                    name="expenses[0][quantity]"
                                                    class="form-control expense-quantity"
                                                    value="1"
                                                    min="0"
                                                    step="0.01"
                                                >

                                            </td>


                                            <td>

                                                <input
                                                    type="number"
                                                    name="expenses[0][rate]"
                                                    class="form-control expense-rate"
                                                    value="0.00"
                                                    min="0"
                                                    step="0.01"
                                                    readonly
                                                >

                                            </td>


                                            <td>

                                                <input
                                                    type="number"
                                                    name="expenses[0][amount]"
                                                    class="form-control expense-amount"
                                                    value="0.00"
                                                    min="0"
                                                    step="0.01"
                                                    readonly
                                                >

                                            </td>


                                            <td>

                                                <input
                                                    type="text"
                                                    name="expenses[0][remarks]"
                                                    class="form-control"
                                                    placeholder="Remarks"
                                                >

                                            </td>


                                            <td>

                                                <select
                                                    name="expenses[0][status]"
                                                    class="form-control custom-select2"
                                                >

                                                    <option
                                                        value="pending"
                                                        selected
                                                    >
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

                                                <button
                                                    type="button"
                                                    class="btn btn-danger btn-sm remove-expense"
                                                    disabled
                                                    title="Remove"
                                                >

                                                    <i class="fa fa-trash"></i>

                                                </button>

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>


                        <div class="mt-2">

                            <button
                                type="button"
                                id="add-expense"
                                class="btn btn-primary btn-sm"
                            >

                                <i class="fa fa-plus"></i>

                                Add More Expense

                            </button>

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- FINANCIAL SUMMARY --}}
                    {{-- ========================================================= --}}
                    <div class="col-12 mt-4">

                        <div class="card">

                            <div class="card-body">

                                <h6
                                    class="text-primary"
                                    style="color:#023a85 !important;"
                                >
                                    <b>Financial Summary</b>
                                </h6>

                                <hr>


                                <div class="row">


                                    {{-- Total Allowance --}}

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label>
                                                Total Allowance
                                            </label>

                                            <input
                                                type="text"
                                                id="total-allowance"
                                                class="form-control"
                                                value="{{ number_format(
                                                    $dutySlip->total_allowance ?? 0,
                                                    2,
                                                    '.',
                                                    ''
                                                ) }}"
                                                readonly
                                            >

                                            <input
                                                type="hidden"
                                                name="total_allowance"
                                                id="total_allowance"
                                                value="{{ $dutySlip->total_allowance ?? 0 }}"
                                            >

                                        </div>

                                    </div>


                                    {{-- Total Expense --}}

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label>
                                                Total Expense
                                            </label>

                                            <input
                                                type="text"
                                                id="total-expense"
                                                class="form-control"
                                                value="{{ number_format(
                                                    $dutySlip->total_expense ?? 0,
                                                    2,
                                                    '.',
                                                    ''
                                                ) }}"
                                                readonly
                                            >

                                            <input
                                                type="hidden"
                                                name="total_expense"
                                                id="total_expense"
                                                value="{{ $dutySlip->total_expense ?? 0 }}"
                                            >

                                        </div>

                                    </div>


                                    {{-- Grand Total --}}

                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <label>
                                                <b>Grand Total</b>
                                            </label>

                                            <input
                                                type="text"
                                                id="grand-total"
                                                class="form-control"
                                                value="{{ number_format(
                                                    $dutySlip->grand_total ?? 0,
                                                    2,
                                                    '.',
                                                    ''
                                                ) }}"
                                                readonly
                                            >

                                            <input
                                                type="hidden"
                                                name="grand_total"
                                                id="grand_total"
                                                value="{{ $dutySlip->grand_total ?? 0 }}"
                                            >

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- REMARKS --}}
                    {{-- ========================================================= --}}
                    <div class="col-12 mt-3">

                        <h5 class="form-section-title">
                            Remarks
                        </h5>

                        <hr>

                    </div>

                    <div class="col-md-12">

                        <div class="form-group">

                            <label>
                                <b>Remarks</b>
                            </label>

                            <textarea
                                name="remarks"
                                id="remarks"
                                rows="4"
                                class="form-control @error('remarks') is-invalid @enderror"
                                placeholder="Enter Remarks">{{ old(
                                    'remarks',
                                    $dutySlip->remarks
                                ) }}</textarea>

                            @error('remarks')

                                <span class="invalid-feedback d-block">
                                    <strong>{{ $message }}</strong>
                                </span>

                            @enderror

                        </div>

                    </div>

                    {{-- ========================================================= --}}
                    {{-- STATUS --}}
                    {{-- ========================================================= --}}
                    <div class="col-12 mt-3">

                        <h5 class="form-section-title">
                            Status
                        </h5>

                        <hr>

                    </div>

                    <div class="col-md-4">

                        <div class="form-group">

                            <label>

                                <b>Status</b>

                                <span class="required">
                                    *
                                </span>

                            </label>


                            <select
                                name="status"
                                id="status"
                                class="form-control custom-select2 @error('status') is-invalid @enderror">


                                @foreach([
                                    'draft' => 'Draft',
                                    'open' => 'Open',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled'
                                ] as $statusValue => $statusLabel)

                                    <option
                                        value="{{ $statusValue }}"
                                        {{ old(
                                            'status',
                                            $dutySlip->status
                                        ) === $statusValue
                                            ? 'selected'
                                            : '' }}
                                    >

                                        {{ $statusLabel }}

                                    </option>

                                @endforeach

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
                    {{-- ACTION BUTTONS --}}
                    {{-- ========================================================= --}}
                    <div class="col-12">

                        <div class="text-right mt-4">

                            <a
                                href="{{ route('duty-slips.index') }}"
                                class="btn btn-danger"
                            >

                                <i class="fa fa-times"></i>

                                Cancel

                            </a>


                            <button
                                type="submit"
                                class="btn btn-success"
                                id="update-duty-slip-btn"
                            >

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
    | HELPERS
    |--------------------------------------------------------------------------
    */

    function numberValue(value)
    {
        const number = parseFloat(value);

        return isNaN(number) || number < 0
            ? 0
            : number;
    }


    function formatAmount(value)
    {
        return numberValue(value).toFixed(2);
    }


    /*
    |--------------------------------------------------------------------------
    | DUTY SLIP FRONT / BACK FILE PREVIEW
    |--------------------------------------------------------------------------
    */

    window.previewDutySlipFile = function (
        inputId,
        previewId
    ) {

        const input =
            document.getElementById(inputId);

        const preview =
            document.getElementById(previewId);


        if (!input || !preview) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | No New File Selected
        |--------------------------------------------------------------------------
        |
        | Existing file preview remains visible.
        |
        */

        if (
            !input.files ||
            !input.files[0]
        ) {
            return;
        }


        const file =
            input.files[0];


        /*
        |--------------------------------------------------------------------------
        | Document Label
        |--------------------------------------------------------------------------
        */

        const documentLabel =
            inputId === 'duty_slip_front_file'
                ? 'Duty Slip Front'
                : 'Duty Slip Back';


        /*
        |--------------------------------------------------------------------------
        | Allowed MIME Types
        |--------------------------------------------------------------------------
        */

        const allowedTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png'
        ];


        /*
        |--------------------------------------------------------------------------
        | FILE TYPE VALIDATION
        |--------------------------------------------------------------------------
        */

        if (
            !allowedTypes.includes(
                file.type
            )
        ) {

            alert(
                `${documentLabel} must be a valid PDF, JPG, JPEG, or PNG file.`
            );

            input.value = '';

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | FILE SIZE VALIDATION - 5 MB
        |--------------------------------------------------------------------------
        */

        const maxSize =
            5 * 1024 * 1024;


        if (
            file.size > maxSize
        ) {

            alert(
                `${documentLabel} size must not exceed 5 MB.`
            );

            input.value = '';

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | REPLACE EXISTING PREVIEW
        |--------------------------------------------------------------------------
        */

        preview.innerHTML = '';


        /*
        |--------------------------------------------------------------------------
        | FILE SIZE
        |--------------------------------------------------------------------------
        */

        const fileSize =
            (
                file.size /
                1024 /
                1024
            ).toFixed(2);


        /*
        |--------------------------------------------------------------------------
        | PDF PREVIEW
        |--------------------------------------------------------------------------
        */

        if (
            file.type ===
            'application/pdf'
        ) {

            const fileUrl =
                URL.createObjectURL(file);


            preview.innerHTML = `

                <div
                    class="alert alert-light border d-flex align-items-center"
                    style="
                        border-radius:10px;
                        padding:12px 15px;
                        max-width:450px;
                    "
                >

                    <div
                        class="mr-3"
                        style="
                            min-width:45px;
                            text-align:center;
                        "
                    >

                        <i
                            class="fa fa-file-pdf-o text-danger"
                            style="
                                font-size:36px;
                            "
                        ></i>

                    </div>


                    <div>

                        <strong
                            class="d-block"
                            style="
                                word-break:break-word;
                            "
                        >
                            ${file.name}
                        </strong>


                        <small class="text-muted d-block">

                            ${documentLabel}
                            &nbsp;•&nbsp;
                            PDF
                            &nbsp;•&nbsp;
                            ${fileSize} MB

                        </small>


                        <a
                            href="${fileUrl}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-sm btn-primary mt-2"
                        >

                            <i class="fa fa-eye"></i>

                            Preview PDF

                        </a>

                    </div>

                </div>

            `;

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | IMAGE PREVIEW
        |--------------------------------------------------------------------------
        */

        const reader =
            new FileReader();


        reader.onload = function (e) {

            preview.innerHTML = `

                <div>

                    <img
                        src="${e.target.result}"
                        alt="${documentLabel} Preview"
                        class="img-thumbnail"
                        style="
                            width:220px;
                            max-height:220px;
                            object-fit:contain;
                            border-radius:10px;
                            border:2px solid #dee2e6;
                            box-shadow:
                                0 2px 10px
                                rgba(0,0,0,.15);
                            background:#fff;
                        "
                    >


                    <div class="mt-2">

                        <strong
                            class="d-block"
                            style="
                                word-break:break-word;
                            "
                        >
                            ${file.name}
                        </strong>


                        <small class="text-muted d-block">

                            ${documentLabel}
                            &nbsp;•&nbsp;
                            Image
                            &nbsp;•&nbsp;
                            ${fileSize} MB

                        </small>

                    </div>

                </div>

            `;
        };


        reader.readAsDataURL(file);
    };


    /*
    |--------------------------------------------------------------------------
    | SLIP NUMBER
    |--------------------------------------------------------------------------
    */

    $('#slip_no').on(
        'blur',
        function () {

            this.value =
                $(this)
                    .val()
                    .trim()
                    .toUpperCase()
                    .replace(
                        /\s+/g,
                        ''
                    );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | MOBILE
    |--------------------------------------------------------------------------
    */

    $('#passenger_mobile').on(
        'input',
        function () {

            this.value =
                $(this)
                    .val()
                    .replace(
                        /[^0-9]/g,
                        ''
                    )
                    .slice(
                        0,
                        10
                    );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | NUMBER VALIDATION - LIVE
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'input',
        '#opening_km, #closing_km, ' +
        '.allowance-quantity, .allowance-rate, ' +
        '.expense-quantity, .expense-rate',
        function () {

            let value =
                this.value;


            if (
                value === ''
            ) {
                return;
            }


            value =
                parseFloat(value);


            if (
                isNaN(value) ||
                value < 0
            ) {

                this.value = 0;

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | TOTAL KM
    |--------------------------------------------------------------------------
    */

    function calculateTotalKm()
    {

        const opening =
            numberValue(
                $('#opening_km').val()
            );


        const closing =
            numberValue(
                $('#closing_km').val()
            );


        let totalKm = 0;


        if (
            closing >= opening
        ) {

            totalKm =
                closing -
                opening;

        }


        $('#total_km').val(
            formatAmount(
                totalKm
            )
        );


        /*
        |--------------------------------------------------------------------------
        | PER KM ALLOWANCE
        |--------------------------------------------------------------------------
        */

        $('#allowance-wrapper .allowance-row')
            .each(function () {

                const row =
                    $(this);


                const option =
                    row.find(
                        '.allowance-select option:selected'
                    );


                const calculationType =
                    option.attr(
                        'data-calculation-type'
                    );


                if (
                    calculationType === 'per_km'
                ) {

                    row.find(
                        '.allowance-quantity'
                    ).val(
                        formatAmount(
                            totalKm
                        )
                    );


                    calculateAllowanceRow(
                        row
                    );

                }

            });


        calculateFinancialSummary();

    }


    /*
    |--------------------------------------------------------------------------
    | OPENING / CLOSING KM
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'input',
        '#opening_km, #closing_km',
        function () {

            calculateTotalKm();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | CLOSING KM VALIDATION
    |--------------------------------------------------------------------------
    */

    $('#closing_km').on(
        'change',
        function () {

            const opening =
                numberValue(
                    $('#opening_km').val()
                );


            const closing =
                numberValue(
                    $('#closing_km').val()
                );


            if (
                closing > 0 &&
                closing < opening
            ) {

                alert(
                    'Closing KM cannot be less than Opening KM.'
                );


                $(this).val('');


                calculateTotalKm();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | ALLOWANCE RATE
    |--------------------------------------------------------------------------
    */

    function setAllowanceRate(row)
    {

        const option =
            row.find(
                '.allowance-select option:selected'
            );


        const rate =
            numberValue(
                option.attr(
                    'data-rate'
                )
            );


        const calculationType =
            option.attr(
                'data-calculation-type'
            );


        row.find(
            '.allowance-rate'
        ).val(
            formatAmount(
                rate
            )
        );


        /*
        |--------------------------------------------------------------------------
        | PER KM
        |--------------------------------------------------------------------------
        */

        if (
            calculationType === 'per_km'
        ) {

            const totalKm =
                numberValue(
                    $('#total_km').val()
                );


            row.find(
                '.allowance-quantity'
            ).val(
                formatAmount(
                    totalKm
                )
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FIXED
        |--------------------------------------------------------------------------
        */

        if (
            calculationType === 'fixed'
        ) {

            const quantity =
                row.find(
                    '.allowance-quantity'
                ).val();


            if (
                quantity === '' ||
                numberValue(quantity) <= 0
            ) {

                row.find(
                    '.allowance-quantity'
                ).val('1');

            }

        }


        calculateAllowanceRow(
            row
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ALLOWANCE CALCULATION
    |--------------------------------------------------------------------------
    */

    function calculateAllowanceRow(row)
    {

        const quantity =
            numberValue(
                row.find(
                    '.allowance-quantity'
                ).val()
            );


        const rate =
            numberValue(
                row.find(
                    '.allowance-rate'
                ).val()
            );


        const amount =
            quantity *
            rate;


        row.find(
            '.allowance-amount'
        ).val(
            formatAmount(
                amount
            )
        );


        calculateFinancialSummary();

    }


    /*
    |--------------------------------------------------------------------------
    | ALLOWANCE CHANGE
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'change',
        '.allowance-select',
        function () {

            const row =
                $(this).closest(
                    '.allowance-row'
                );


            setAllowanceRate(
                row
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | ALLOWANCE QUANTITY / RATE
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'input',
        '.allowance-quantity, .allowance-rate',
        function () {

            calculateAllowanceRow(
                $(this).closest(
                    '.allowance-row'
                )
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | ADD ALLOWANCE
    |--------------------------------------------------------------------------
    */

    $('#add-allowance').on(
        'click',
        function () {

            const wrapper =
                $('#allowance-wrapper');


            const index =
                wrapper.find(
                    '.allowance-row'
                ).length;


            const row = `

                <tr
                    class="allowance-row"
                    data-index="${index}"
                >

                    <td>

                        <select
                            name="allowances[${index}][allowance_id]"
                            class="form-control custom-select2 allowance-select"
                        >

                            <option value="">
                                Select Allowance
                            </option>

                            @foreach($allowances ?? [] as $allowance)

                                <option
                                    value="{{ $allowance->id }}"
                                    data-rate="{{ $allowance->amount ?? 0 }}"
                                    data-calculation-type="{{ $allowance->calculation_type }}"
                                >

                                    {{ $allowance->name }}

                                </option>

                            @endforeach

                        </select>

                    </td>


                    <td>

                        <input
                            type="number"
                            name="allowances[${index}][quantity]"
                            class="form-control allowance-quantity"
                            value="1"
                            min="0"
                            step="0.01"
                        >

                    </td>


                    <td>

                        <input
                            type="number"
                            name="allowances[${index}][rate]"
                            class="form-control allowance-rate"
                            value="0.00"
                            readonly
                        >

                    </td>


                    <td>

                        <input
                            type="number"
                            name="allowances[${index}][amount]"
                            class="form-control allowance-amount"
                            value="0.00"
                            readonly
                        >

                    </td>


                    <td>

                        <input
                            type="text"
                            name="allowances[${index}][remarks]"
                            class="form-control"
                            placeholder="Remarks"
                        >

                    </td>


                    <td>

                        <select
                            name="allowances[${index}][status]"
                            class="form-control custom-select2"
                        >

                            <option
                                value="pending"
                                selected
                            >
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

                        <button
                            type="button"
                            class="btn btn-danger btn-sm remove-allowance"
                            title="Remove"
                        >

                            <i class="fa fa-trash"></i>

                        </button>

                    </td>

                </tr>

            `;


            wrapper.append(row);


            initializeSelect2(
                wrapper.find(
                    '.allowance-row:last .custom-select2'
                )
            );


            updateAllowanceIndexes();

            updateAllowanceRemoveButtons();

            calculateFinancialSummary();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | REMOVE ALLOWANCE
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.remove-allowance',
        function () {

            $(this)
                .closest(
                    '.allowance-row'
                )
                .remove();


            updateAllowanceIndexes();

            updateAllowanceRemoveButtons();

            calculateFinancialSummary();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | ALLOWANCE INDEX
    |--------------------------------------------------------------------------
    */

    function updateAllowanceIndexes()
    {

        $('#allowance-wrapper .allowance-row')
            .each(function (index) {

                $(this).attr(
                    'data-index',
                    index
                );


                $(this)
                    .find('[name]')
                    .each(function () {

                        const name =
                            $(this).attr(
                                'name'
                            );


                        if (!name) {
                            return;
                        }


                        $(this).attr(
                            'name',
                            name.replace(
                                /allowances\[\d+\]/,
                                `allowances[${index}]`
                            )
                        );

                    });

            });

    }


    /*
    |--------------------------------------------------------------------------
    | ALLOWANCE REMOVE BUTTON
    |--------------------------------------------------------------------------
    */

    function updateAllowanceRemoveButtons()
    {

        const rows =
            $('#allowance-wrapper .allowance-row');


        rows
            .find(
                '.remove-allowance'
            )
            .prop(
                'disabled',
                rows.length <= 1
            );

    }


    /*
    |--------------------------------------------------------------------------
    | EXPENSE RATE
    |--------------------------------------------------------------------------
    */

    function setExpenseRate(row)
    {

        const option =
            row.find(
                '.expense-select option:selected'
            );


        const rate =
            numberValue(
                option.attr(
                    'data-rate'
                )
            );


        row.find(
            '.expense-rate'
        ).val(
            formatAmount(
                rate
            )
        );


        calculateExpenseRow(
            row
        );

    }


    /*
    |--------------------------------------------------------------------------
    | EXPENSE CALCULATION
    |--------------------------------------------------------------------------
    */

    function calculateExpenseRow(row)
    {

        const quantity =
            numberValue(
                row.find(
                    '.expense-quantity'
                ).val()
            );


        const rate =
            numberValue(
                row.find(
                    '.expense-rate'
                ).val()
            );


        const amount =
            quantity *
            rate;


        row.find(
            '.expense-amount'
        ).val(
            formatAmount(
                amount
            )
        );


        calculateFinancialSummary();

    }


    /*
    |--------------------------------------------------------------------------
    | EXPENSE SELECT
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'change',
        '.expense-select',
        function () {

            setExpenseRate(
                $(this).closest(
                    '.expense-row'
                )
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | EXPENSE QUANTITY / RATE
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'input',
        '.expense-quantity, .expense-rate',
        function () {

            calculateExpenseRow(
                $(this).closest(
                    '.expense-row'
                )
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | ADD EXPENSE
    |--------------------------------------------------------------------------
    */

    $('#add-expense').on(
        'click',
        function () {

            const wrapper =
                $('#expense-wrapper');


            const index =
                wrapper.find(
                    '.expense-row'
                ).length;


            const row = `

                <tr
                    class="expense-row"
                    data-index="${index}"
                >

                    <td>

                        <select
                            name="expenses[${index}][expense_id]"
                            class="form-control custom-select2 expense-select"
                        >

                            <option value="">
                                Select Expense
                            </option>

                            @foreach($expenses ?? [] as $expense)

                                <option
                                    value="{{ $expense->id }}"
                                    data-rate="{{ $expense->amount ?? 0 }}"
                                >

                                    {{ $expense->name }}

                                </option>

                            @endforeach

                        </select>

                    </td>


                    <td>

                        <input
                            type="number"
                            name="expenses[${index}][quantity]"
                            class="form-control expense-quantity"
                            value="1"
                            min="0"
                            step="0.01"
                        >

                    </td>


                    <td>

                        <input
                            type="number"
                            name="expenses[${index}][rate]"
                            class="form-control expense-rate"
                            value="0.00"
                            readonly
                        >

                    </td>


                    <td>

                        <input
                            type="number"
                            name="expenses[${index}][amount]"
                            class="form-control expense-amount"
                            value="0.00"
                            readonly
                        >

                    </td>


                    <td>

                        <input
                            type="text"
                            name="expenses[${index}][remarks]"
                            class="form-control"
                            placeholder="Remarks"
                        >

                    </td>


                    <td>

                        <select
                            name="expenses[${index}][status]"
                            class="form-control custom-select2"
                        >

                            <option
                                value="pending"
                                selected
                            >
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

                        <button
                            type="button"
                            class="btn btn-danger btn-sm remove-expense"
                            title="Remove"
                        >

                            <i class="fa fa-trash"></i>

                        </button>

                    </td>

                </tr>

            `;


            wrapper.append(row);


            initializeSelect2(
                wrapper.find(
                    '.expense-row:last .custom-select2'
                )
            );


            updateExpenseIndexes();

            updateExpenseRemoveButtons();

            calculateFinancialSummary();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | REMOVE EXPENSE
    |--------------------------------------------------------------------------
    */

    $(document).on(
        'click',
        '.remove-expense',
        function () {

            $(this)
                .closest(
                    '.expense-row'
                )
                .remove();


            updateExpenseIndexes();

            updateExpenseRemoveButtons();

            calculateFinancialSummary();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | EXPENSE INDEX
    |--------------------------------------------------------------------------
    */

    function updateExpenseIndexes()
    {

        $('#expense-wrapper .expense-row')
            .each(function (index) {

                $(this).attr(
                    'data-index',
                    index
                );


                $(this)
                    .find('[name]')
                    .each(function () {

                        const name =
                            $(this).attr(
                                'name'
                            );


                        if (!name) {
                            return;
                        }


                        $(this).attr(
                            'name',
                            name.replace(
                                /expenses\[\d+\]/,
                                `expenses[${index}]`
                            )
                        );

                    });

            });

    }


    /*
    |--------------------------------------------------------------------------
    | EXPENSE REMOVE BUTTON
    |--------------------------------------------------------------------------
    */

    function updateExpenseRemoveButtons()
    {

        const rows =
            $('#expense-wrapper .expense-row');


        rows
            .find(
                '.remove-expense'
            )
            .prop(
                'disabled',
                rows.length <= 1
            );

    }


    /*
    |--------------------------------------------------------------------------
    | FINANCIAL SUMMARY
    |--------------------------------------------------------------------------
    */

    function calculateFinancialSummary()
    {

        let allowanceTotal = 0;

        let expenseTotal = 0;


        /*
        |--------------------------------------------------------------------------
        | ALLOWANCE TOTAL
        |--------------------------------------------------------------------------
        */

        $('#allowance-wrapper .allowance-row')
            .each(function () {

                allowanceTotal +=
                    numberValue(
                        $(this)
                            .find(
                                '.allowance-amount'
                            )
                            .val()
                    );

            });


        /*
        |--------------------------------------------------------------------------
        | EXPENSE TOTAL
        |--------------------------------------------------------------------------
        */

        $('#expense-wrapper .expense-row')
            .each(function () {

                expenseTotal +=
                    numberValue(
                        $(this)
                            .find(
                                '.expense-amount'
                            )
                            .val()
                    );

            });


        /*
        |--------------------------------------------------------------------------
        | GRAND TOTAL
        |--------------------------------------------------------------------------
        */

        const grandTotal =
            allowanceTotal +
            expenseTotal;


        /*
        |--------------------------------------------------------------------------
        | DISPLAY VALUES
        |--------------------------------------------------------------------------
        */

        $('#total-allowance').val(
            formatAmount(
                allowanceTotal
            )
        );


        $('#total-expense').val(
            formatAmount(
                expenseTotal
            )
        );


        $('#grand-total').val(
            formatAmount(
                grandTotal
            )
        );


        /*
        |--------------------------------------------------------------------------
        | HIDDEN VALUES
        |--------------------------------------------------------------------------
        */

        $('#total_allowance').val(
            formatAmount(
                allowanceTotal
            )
        );


        $('#total_expense').val(
            formatAmount(
                expenseTotal
            )
        );


        $('#grand_total').val(
            formatAmount(
                grandTotal
            )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SELECT2
    |--------------------------------------------------------------------------
    */

    function initializeSelect2(element)
    {

        if (
            element &&
            element.length &&
            $.fn.select2
        ) {

            element.select2({

                width: '100%',

                placeholder: 'Select',

                allowClear: true

            });

        }

    }


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE EXISTING SELECT2
    |--------------------------------------------------------------------------
    */

    $('.custom-select2').each(
        function () {

            initializeSelect2(
                $(this)
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE EXISTING ALLOWANCES
    |--------------------------------------------------------------------------
    */

    $('#allowance-wrapper .allowance-row')
        .each(function () {

            const row =
                $(this);


            if (
                row.find(
                    '.allowance-select'
                ).val()
            ) {

                setAllowanceRate(
                    row
                );

            } else {

                calculateAllowanceRow(
                    row
                );

            }

        });


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE EXISTING EXPENSES
    |--------------------------------------------------------------------------
    */

    $('#expense-wrapper .expense-row')
        .each(function () {

            const row =
                $(this);


            if (
                row.find(
                    '.expense-select'
                ).val()
            ) {

                setExpenseRate(
                    row
                );

            } else {

                calculateExpenseRow(
                    row
                );

            }

        });


    /*
    |--------------------------------------------------------------------------
    | INITIALIZATION
    |--------------------------------------------------------------------------
    */

    updateAllowanceIndexes();

    updateExpenseIndexes();

    updateAllowanceRemoveButtons();

    updateExpenseRemoveButtons();

    calculateTotalKm();

    calculateFinancialSummary();


    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    $('#duty-slip-form').on(
        'submit',
        function () {

            const form =
                $(this);


            /*
            |--------------------------------------------------------------------------
            | UPDATE INDEXES
            |--------------------------------------------------------------------------
            */

            updateAllowanceIndexes();

            updateExpenseIndexes();


            /*
            |--------------------------------------------------------------------------
            | FINAL TOTAL KM
            |--------------------------------------------------------------------------
            */

            calculateTotalKm();


            /*
            |--------------------------------------------------------------------------
            | FINAL ALLOWANCE CALCULATION
            |--------------------------------------------------------------------------
            */

            $('#allowance-wrapper .allowance-row')
                .each(function () {

                    calculateAllowanceRow(
                        $(this)
                    );

                });


            /*
            |--------------------------------------------------------------------------
            | FINAL EXPENSE CALCULATION
            |--------------------------------------------------------------------------
            */

            $('#expense-wrapper .expense-row')
                .each(function () {

                    calculateExpenseRow(
                        $(this)
                    );

                });


            /*
            |--------------------------------------------------------------------------
            | FINAL FINANCIAL SUMMARY
            |--------------------------------------------------------------------------
            */

            calculateFinancialSummary();


            /*
            |--------------------------------------------------------------------------
            | FORMAT SLIP NUMBER
            |--------------------------------------------------------------------------
            */

            $('#slip_no').val(

                $('#slip_no')
                    .val()
                    .trim()
                    .toUpperCase()
                    .replace(
                        /\s+/g,
                        ''
                    )

            );


            /*
            |--------------------------------------------------------------------------
            | PREVENT DUPLICATE SUBMIT
            |--------------------------------------------------------------------------
            */

            const submitButton =
                form.find(
                    'button[type="submit"]'
                );


            if (
                submitButton.length
            ) {

                submitButton
                    .prop(
                        'disabled',
                        true
                    )
                    .html(
                        '<i class="fa fa-spinner fa-spin"></i> Updating Duty Slip...'
                    );

            }

        }
    );

});

</script>

@endpush