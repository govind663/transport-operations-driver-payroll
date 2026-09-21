@extends('backend.layouts.master')

@section('title')
    Edit Duty Slip
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
        | Form Controls
        |--------------------------------------------------------------------------
        */

        .form-control:focus {
            border-color: #023a85;
            box-shadow: 0 0 0 0.1rem rgba(2, 58, 133, .15);
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
        | Financial Summary
        |--------------------------------------------------------------------------
        */

        .financial-summary-box {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background: #f8f9fa;
        }

        /*
        |--------------------------------------------------------------------------
        | Helper Text
        |--------------------------------------------------------------------------
        */

        .form-helper-text {
            font-size: 12px;
            color: #6c757d;
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
| EXISTING / OLD VALUES
|--------------------------------------------------------------------------
*/

        $selectedDutyAssignmentId = old('duty_assignment_id', $dutySlip->duty_assignment_id);

        $selectedDriverId = old('driver_id', $dutySlip->driver_id);

        $selectedVehicleId = old('vehicle_id', $dutySlip->vehicle_id);

        $selectedVehicleTypeId = old('vehicle_type_id', $dutySlip->vehicle_type_id);

        /*
|--------------------------------------------------------------------------
| START DATE / TIME
|--------------------------------------------------------------------------
*/

        $startDate = old('start_date', optional($dutySlip->start_time)->format('Y-m-d'));

        $startTime = old('start_time', optional($dutySlip->start_time)->format('H:i'));

        /*
|--------------------------------------------------------------------------
| END DATE / TIME
|--------------------------------------------------------------------------
*/

        $endDate = old('end_date', optional($dutySlip->end_time)->format('Y-m-d'));

        $endTime = old('end_time', optional($dutySlip->end_time)->format('H:i'));
    @endphp

    <div class="pd-ltr-20 xs-pd-20-10">

        <div class="min-height-200px">


            {{-- ================================================================= --}}
            {{-- PAGE HEADER                                                        --}}
            {{-- ================================================================= --}}

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


            {{-- ================================================================= --}}
            {{-- VALIDATION ERRORS                                                  --}}
            {{-- ================================================================= --}}

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


            {{-- ================================================================= --}}
            {{-- SUCCESS MESSAGE                                                    --}}
            {{-- ================================================================= --}}

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif


            {{-- ================================================================= --}}
            {{-- FORM                                                               --}}
            {{-- ================================================================= --}}

            <form id="duty-slip-form" action="{{ route('duty-slips.update', $dutySlip->id) }}" method="POST"
                enctype="multipart/form-data" novalidate>

                @csrf

                @method('PUT')


                <div class="card-box pd-20 mb-30">

                    <div class="row">


                        {{-- ===================================================== --}}
                        {{-- DUTY SLIP INFORMATION                                 --}}
                        {{-- ===================================================== --}}

                        <div class="col-12">

                            <h5 class="form-section-title">
                                Duty Slip Information
                            </h5>

                            <hr>

                        </div>


                        {{-- ===================================================== --}}
                        {{-- SLIP NUMBER                                            --}}
                        {{-- ===================================================== --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="slip_no">

                                    <b>
                                        Duty Slip Number
                                    </b>

                                    <span class="required">*</span>

                                </label>


                                <input type="text" name="slip_no" id="slip_no"
                                    class="form-control @error('slip_no') is-invalid @enderror"
                                    value="{{ old('slip_no', $dutySlip->slip_no) }}" maxlength="100" autocomplete="off"
                                    readonly required>


                                @error('slip_no')
                                    <span class="invalid-feedback d-block">

                                        <strong>
                                            {{ $message }}
                                        </strong>

                                    </span>
                                @enderror


                                <small class="form-helper-text">
                                    Existing Duty Slip number cannot be changed.
                                </small>

                            </div>

                        </div>


                        {{-- ===================================================== --}}
                        {{-- DUTY ASSIGNMENT                                       --}}
                        {{-- ===================================================== --}}

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
                                            {{ (string) $selectedDutyAssignmentId === (string) $assignment->id ? 'selected' : '' }}>

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


                        {{-- ===================================================== --}}
                        {{-- DUTY DATE                                               --}}
                        {{-- ===================================================== --}}

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
                                    value="{{ old('duty_date', optional($dutySlip->duty_date)->format('Y-m-d')) }}"
                                    required>


                                @error('duty_date')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>


                        {{-- ===================================================== --}}
                        {{-- DRIVER / VEHICLE SECTION                              --}}
                        {{-- ===================================================== --}}

                        <div class="col-12 mt-3">

                            <h5 class="form-section-title">
                                Driver & Vehicle Information
                            </h5>

                            <hr>

                        </div>


                        {{-- ===================================================== --}}
                        {{-- DRIVER                                                  --}}
                        {{-- ===================================================== --}}

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
                                            {{ (string) $selectedDriverId === (string) $driver->id ? 'selected' : '' }}>

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
                                    Driver is manually selected.
                                </small>

                            </div>

                        </div>


                        {{-- ===================================================== --}}
                        {{-- VEHICLE                                                 --}}
                        {{-- ===================================================== --}}

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
                                            {{ (string) $selectedVehicleId === (string) $vehicle->id ? 'selected' : '' }}>

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
                                    Vehicle is manually selected.
                                </small>

                            </div>

                        </div>


                        {{-- ===================================================== --}}
                        {{-- VEHICLE TYPE                                            --}}
                        {{-- ===================================================== --}}

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
                                            {{ (string) $selectedVehicleTypeId === (string) $vehicleType->id ? 'selected' : '' }}>

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
                                    Vehicle Type is manually selected.
                                </small>

                            </div>

                        </div>


                        {{-- ===================================================== --}}
                        {{-- DOCUMENT SECTION                                       --}}
                        {{-- ===================================================== --}}
                        <div class="col-12 mt-3">

                            <h5 class="form-section-title">
                                Duty Slip Document
                            </h5>

                            <hr>

                        </div>

                        {{-- ===================================================== --}}
                        {{-- FRONT FILE --}}
                        {{-- ===================================================== --}}
                        <div class="col-md-6">
                            <div class="form-group">

                                <label for="duty_slip_front_file">
                                    <b>Duty Slip Front</b>
                                </label>

                                <input
                                    type="file"
                                    name="duty_slip_front_file"
                                    id="duty_slip_front_file"
                                    class="form-control @error('duty_slip_front_file') is-invalid @enderror"
                                    accept=".pdf,.jpg,.jpeg,.png,.webp"
                                >

                                <small class="form-helper-text">
                                    Allowed: PDF, JPG, JPEG, PNG & WEBP (Maximum 5 MB)
                                </small>

                                @error('duty_slip_front_file')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror


                                <div
                                    id="duty-slip-front-file-preview"
                                    class="duty-slip-file-preview mt-2"
                                >

                                    @if (!empty($dutySlip->duty_slip_front_file))

                                        @php

                                            /*
                                            |--------------------------------------------------------------------------
                                            | Existing Front File
                                            |--------------------------------------------------------------------------
                                            */

                                            $frontFilePath = trim(
                                                (string) $dutySlip->duty_slip_front_file
                                            );

                                            $frontFilePath = ltrim(
                                                $frontFilePath,
                                                '/'
                                            );


                                            /*
                                            |--------------------------------------------------------------------------
                                            | File Extension
                                            |--------------------------------------------------------------------------
                                            */

                                            $frontFileExtension = strtolower(
                                                pathinfo(
                                                    $frontFilePath,
                                                    PATHINFO_EXTENSION
                                                )
                                            );


                                            /*
                                            |--------------------------------------------------------------------------
                                            | File URL
                                            |--------------------------------------------------------------------------
                                            |
                                            | Same working logic as Driver Management.
                                            |
                                            */

                                            if (
                                                str_starts_with(
                                                    $frontFilePath,
                                                    'duty-slip/'
                                                )
                                            ) {

                                                $frontFileUrl = asset(
                                                    'storage/' . $frontFilePath
                                                );

                                            } else {

                                                $frontFileUrl = asset(
                                                    'backend/assets/uploads/duty-slip/' .
                                                    $frontFilePath
                                                );

                                            }


                                            /*
                                            |--------------------------------------------------------------------------
                                            | Image Extensions
                                            |--------------------------------------------------------------------------
                                            */

                                            $frontImageExtensions = [
                                                'jpg',
                                                'jpeg',
                                                'png',
                                                'webp',
                                            ];

                                        @endphp


                                        {{-- ================================================= --}}
                                        {{-- IMAGE --}}
                                        {{-- ================================================= --}}
                                        @if (
                                            in_array(
                                                $frontFileExtension,
                                                $frontImageExtensions,
                                                true
                                            )
                                        )

                                            <div>

                                                <img
                                                    src="{{ $frontFileUrl }}"
                                                    alt="Duty Slip Front"
                                                    loading="lazy"
                                                    decoding="async"
                                                    class="img-thumbnail"
                                                    data-no-optimize="1"
                                                    style="
                                                        width:220px;
                                                        max-width:100%;
                                                        max-height:300px;
                                                        object-fit:contain;
                                                        display:block;
                                                        background:#fff;
                                                        border:2px solid #dee2e6;
                                                        border-radius:10px;
                                                        box-shadow:0 2px 10px rgba(0,0,0,.15);
                                                    "
                                                    onerror="
                                                        this.onerror=null;
                                                        this.src='{{ asset('backend/assets/img/logo/user.png') }}';
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


                                        {{-- ================================================= --}}
                                        {{-- PDF --}}
                                        {{-- ================================================= --}}

                                        @elseif ($frontFileExtension === 'pdf')

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
                                                        style="font-size:36px;"
                                                    ></i>

                                                </div>


                                                <div>

                                                    <strong class="d-block">
                                                        Existing Duty Slip Front PDF
                                                    </strong>

                                                    <small class="text-muted d-block mt-1">
                                                        {{ basename($frontFilePath) }}
                                                    </small>


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


                                        {{-- ================================================= --}}
                                        {{-- OTHER --}}
                                        {{-- ================================================= --}}

                                        @else

                                            <div
                                                class="alert alert-light border"
                                                style="border-radius:10px; max-width:450px;"
                                            >

                                                <strong>
                                                    Existing Duty Slip Front
                                                </strong>

                                                <div class="small text-muted mt-1">
                                                    {{ basename($frontFilePath) }}
                                                </div>


                                                <div class="mt-2">

                                                    <a
                                                        href="{{ $frontFileUrl }}"
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="btn btn-sm btn-primary"
                                                    >
                                                        <i class="fa fa-eye"></i>
                                                        Open File
                                                    </a>

                                                </div>

                                            </div>

                                        @endif

                                    @else

                                        <div class="text-muted small">
                                            No front file uploaded.
                                        </div>

                                    @endif

                                </div>

                            </div>
                        </div>

                        {{-- ===================================================== --}}
                        {{-- BACK FILE --}}
                        {{-- ===================================================== --}}
                        <div class="col-md-6">
                            <div class="form-group">

                                <label for="duty_slip_back_file">
                                    <b>Duty Slip Back</b>
                                </label>

                                <input
                                    type="file"
                                    name="duty_slip_back_file"
                                    id="duty_slip_back_file"
                                    class="form-control @error('duty_slip_back_file') is-invalid @enderror"
                                    accept=".pdf,.jpg,.jpeg,.png,.webp"
                                >

                                <small class="form-helper-text">
                                    Allowed: PDF, JPG, JPEG, PNG & WEBP (Maximum 5 MB)
                                </small>

                                @error('duty_slip_back_file')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror


                                <div
                                    id="duty-slip-back-file-preview"
                                    class="duty-slip-file-preview mt-2"
                                >

                                    @if (!empty($dutySlip->duty_slip_back_file))

                                        @php

                                            /*
                                            |--------------------------------------------------------------------------
                                            | Existing Back File
                                            |--------------------------------------------------------------------------
                                            */

                                            $backFilePath = trim(
                                                (string) $dutySlip->duty_slip_back_file
                                            );

                                            $backFilePath = ltrim(
                                                $backFilePath,
                                                '/'
                                            );


                                            /*
                                            |--------------------------------------------------------------------------
                                            | File Extension
                                            |--------------------------------------------------------------------------
                                            */

                                            $backFileExtension = strtolower(
                                                pathinfo(
                                                    $backFilePath,
                                                    PATHINFO_EXTENSION
                                                )
                                            );


                                            /*
                                            |--------------------------------------------------------------------------
                                            | File URL
                                            |--------------------------------------------------------------------------
                                            |
                                            | Same working logic as Driver Management.
                                            |
                                            */

                                            if (
                                                str_starts_with(
                                                    $backFilePath,
                                                    'duty-slip/'
                                                )
                                            ) {

                                                $backFileUrl = asset(
                                                    'storage/' . $backFilePath
                                                );

                                            } else {

                                                $backFileUrl = asset(
                                                    'backend/assets/uploads/duty-slip/' .
                                                    $backFilePath
                                                );

                                            }


                                            /*
                                            |--------------------------------------------------------------------------
                                            | Image Extensions
                                            |--------------------------------------------------------------------------
                                            */

                                            $backImageExtensions = [
                                                'jpg',
                                                'jpeg',
                                                'png',
                                                'webp',
                                            ];

                                        @endphp


                                        {{-- ================================================= --}}
                                        {{-- IMAGE --}}
                                        {{-- ================================================= --}}

                                        @if (
                                            in_array(
                                                $backFileExtension,
                                                $backImageExtensions,
                                                true
                                            )
                                        )

                                            <div>

                                                <img
                                                    src="{{ $backFileUrl }}"
                                                    alt="Duty Slip Back"
                                                    loading="lazy"
                                                    decoding="async"
                                                    class="img-thumbnail"
                                                    data-no-optimize="1"
                                                    style="
                                                        width:220px;
                                                        max-width:100%;
                                                        max-height:300px;
                                                        object-fit:contain;
                                                        display:block;
                                                        background:#fff;
                                                        border:2px solid #dee2e6;
                                                        border-radius:10px;
                                                        box-shadow:0 2px 10px rgba(0,0,0,.15);
                                                    "
                                                    onerror="
                                                        this.onerror=null;
                                                        this.src='{{ asset('backend/assets/img/logo/user.png') }}';
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


                                        {{-- ================================================= --}}
                                        {{-- PDF --}}
                                        {{-- ================================================= --}}

                                        @elseif ($backFileExtension === 'pdf')

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
                                                        style="font-size:36px;"
                                                    ></i>

                                                </div>


                                                <div>

                                                    <strong class="d-block">
                                                        Existing Duty Slip Back PDF
                                                    </strong>

                                                    <small class="text-muted d-block mt-1">
                                                        {{ basename($backFilePath) }}
                                                    </small>


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


                                        {{-- ================================================= --}}
                                        {{-- OTHER --}}
                                        {{-- ================================================= --}}

                                        @else

                                            <div
                                                class="alert alert-light border"
                                                style="border-radius:10px; max-width:450px;"
                                            >

                                                <strong>
                                                    Existing Duty Slip Back
                                                </strong>

                                                <div class="small text-muted mt-1">
                                                    {{ basename($backFilePath) }}
                                                </div>


                                                <div class="mt-2">

                                                    <a
                                                        href="{{ $backFileUrl }}"
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="btn btn-sm btn-primary"
                                                    >
                                                        <i class="fa fa-eye"></i>
                                                        Open File
                                                    </a>

                                                </div>

                                            </div>

                                        @endif

                                    @else

                                        <div class="text-muted small">
                                            No back file uploaded.
                                        </div>

                                    @endif

                                </div>

                            </div>
                        </div>                        

                        {{-- ===================================================== --}}
                        {{-- TRIP INFORMATION                                       --}}
                        {{-- ===================================================== --}}
                        <div class="col-12 mt-3">

                            <h5 class="form-section-title">
                                Trip Information
                            </h5>

                            <hr>

                        </div>

                        {{-- ===================================================== --}}
                        {{-- START DATE                                             --}}
                        {{-- ===================================================== --}}
                        <div class="col-md-3">

                            <div class="form-group">

                                <label for="start_date">
                                    <b>Start Date</b>
                                </label>


                                <input type="date" name="start_date" id="start_date"
                                    class="form-control @error('start_date') is-invalid @enderror"
                                    value="{{ $startDate }}">


                                @error('start_date')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>


                        {{-- ===================================================== --}}
                        {{-- START TIME                                             --}}
                        {{-- ===================================================== --}}

                        <div class="col-md-3">

                            <div class="form-group">

                                <label for="start_time">
                                    <b>Start Time</b>
                                </label>


                                <input type="time" name="start_time" id="start_time"
                                    class="form-control @error('start_time') is-invalid @enderror"
                                    value="{{ $startTime }}">


                                @error('start_time')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>


                        {{-- ===================================================== --}}
                        {{-- END DATE                                               --}}
                        {{-- ===================================================== --}}

                        <div class="col-md-3">

                            <div class="form-group">

                                <label for="end_date">
                                    <b>End Date</b>
                                </label>


                                <input type="date" name="end_date" id="end_date"
                                    class="form-control @error('end_date') is-invalid @enderror"
                                    value="{{ $endDate }}">


                                @error('end_date')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>


                        {{-- ===================================================== --}}
                        {{-- END TIME                                               --}}
                        {{-- ===================================================== --}}

                        <div class="col-md-3">

                            <div class="form-group">

                                <label for="end_time">
                                    <b>End Time</b>
                                </label>


                                <input type="time" name="end_time" id="end_time"
                                    class="form-control @error('end_time') is-invalid @enderror"
                                    value="{{ $endTime }}">


                                @error('end_time')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>


                        {{-- ===================================================== --}}
                        {{-- KILOMETER INFORMATION                                  --}}
                        {{-- ===================================================== --}}

                        <div class="col-12 mt-3">

                            <h5 class="form-section-title">
                                Kilometer Information
                            </h5>

                            <hr>

                        </div>


                        {{-- ===================================================== --}}
                        {{-- OPENING KM                                             --}}
                        {{-- ===================================================== --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="opening_km">
                                    <b>Opening KM</b>
                                </label>


                                <input type="number" name="opening_km" id="opening_km"
                                    class="form-control @error('opening_km') is-invalid @enderror"
                                    value="{{ old('opening_km', $dutySlip->opening_meter) }}"
                                    min="0" step="0.01" placeholder="Enter Opening KM">


                                @error('opening_km')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>


                        {{-- ===================================================== --}}
                        {{-- CLOSING KM                                             --}}
                        {{-- ===================================================== --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="closing_km">
                                    <b>Closing KM</b>
                                </label>


                                <input type="number" name="closing_km" id="closing_km"
                                    class="form-control @error('closing_km') is-invalid @enderror"
                                    value="{{ old('closing_km', $dutySlip->closing_meter) }}"
                                    min="0" step="0.01" placeholder="Enter Closing KM">


                                @error('closing_km')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>


                        {{-- ===================================================== --}}
                        {{-- TOTAL KM                                               --}}
                        {{-- ===================================================== --}}

                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="total_km">
                                    <b>Total KM</b>
                                </label>


                                <input type="number" name="total_km" id="total_km" class="form-control"
                                    value="{{ old('total_km', $dutySlip->total_km ?? 0) }}"
                                    min="0" step="0.01" readonly>


                                <small class="form-helper-text">
                                    Total KM is calculated as Closing KM minus Opening KM.
                                </small>

                            </div>

                        </div>


                        {{-- ===================================================== --}}
                        {{-- DRIVER ALLOWANCE                                      --}}
                        {{-- ===================================================== --}}

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

                                        @forelse(($dutySlip->driverAllowances ?? collect()) as $index => $driverAllowance)
                                            <tr class="allowance-row" data-index="{{ $index }}">

                                                <td>

                                                    <input type="hidden"
                                                        name="driver_allowances[{{ $index }}][id]"
                                                        value="{{ $driverAllowance->id }}">

                                                    <select name="driver_allowances[{{ $index }}][allowance_id]"
                                                        class="form-control custom-select2 allowance-select">

                                                        <option value="">
                                                            Select Allowance
                                                        </option>


                                                        @foreach ($allowances ?? collect() as $allowance)
                                                            <option value="{{ $allowance->id }}"
                                                                data-rate="{{ $allowance->amount ?? 0 }}"
                                                                data-calculation-type="{{ $allowance->calculation_type ?? 'fixed' }}"
                                                                {{ (string) $driverAllowance->allowance_id === (string) $allowance->id ? 'selected' : '' }}>
                                                                {{ $allowance->name }}
                                                            </option>
                                                        @endforeach

                                                    </select>


                                                    @error("driver_allowances.$index.allowance_id")
                                                        <span class="text-danger d-block small mt-1">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror

                                                </td>


                                                <td>

                                                    <input type="number"
                                                        name="driver_allowances[{{ $index }}][quantity]"
                                                        class="form-control allowance-quantity"
                                                        value="{{ old("driver_allowances.$index.quantity", $driverAllowance->quantity) }}"
                                                        min="0.01" step="0.01">

                                                </td>


                                                <td>

                                                    <input type="number"
                                                        name="driver_allowances[{{ $index }}][rate]"
                                                        class="form-control allowance-rate"
                                                        value="{{ old("driver_allowances.$index.rate", $driverAllowance->rate) }}"
                                                        min="0" step="0.01" readonly>

                                                </td>


                                                <td>

                                                    <input type="number"
                                                        name="driver_allowances[{{ $index }}][amount]"
                                                        class="form-control allowance-amount"
                                                        value="{{ old("driver_allowances.$index.amount", $driverAllowance->amount) }}"
                                                        min="0" step="0.01" readonly>

                                                </td>


                                                <td>

                                                    <input type="text"
                                                        name="driver_allowances[{{ $index }}][remarks]"
                                                        class="form-control"
                                                        value="{{ old("driver_allowances.$index.remarks", $driverAllowance->remarks) }}"
                                                        maxlength="1000" placeholder="Remarks">

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
                                                                {{ old("driver_allowances.$index.status", $driverAllowance->status) === $statusValue ? 'selected' : '' }}>
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

                                        @empty

                                            <tr class="allowance-row" data-index="0">

                                                <td>

                                                    <select name="driver_allowances[0][allowance_id]"
                                                        class="form-control custom-select2 allowance-select">

                                                        <option value="">
                                                            Select Allowance
                                                        </option>


                                                        @foreach ($allowances ?? collect() as $allowance)
                                                            <option value="{{ $allowance->id }}"
                                                                data-rate="{{ $allowance->amount ?? 0 }}"
                                                                data-calculation-type="{{ $allowance->calculation_type ?? 'fixed' }}">
                                                                {{ $allowance->name }}
                                                            </option>
                                                        @endforeach

                                                    </select>

                                                </td>


                                                <td>

                                                    <input type="number" name="driver_allowances[0][quantity]"
                                                        class="form-control allowance-quantity" value="1"
                                                        min="0.01" step="0.01">

                                                </td>


                                                <td>

                                                    <input type="number" name="driver_allowances[0][rate]"
                                                        class="form-control allowance-rate" value="0.00" min="0"
                                                        step="0.01" readonly>

                                                </td>


                                                <td>

                                                    <input type="number" name="driver_allowances[0][amount]"
                                                        class="form-control allowance-amount" value="0.00"
                                                        min="0" step="0.01" readonly>

                                                </td>


                                                <td>

                                                    <input type="text" name="driver_allowances[0][remarks]"
                                                        class="form-control" maxlength="1000" placeholder="Remarks">

                                                </td>


                                                <td>

                                                    <select name="driver_allowances[0][status]"
                                                        class="form-control custom-select2">

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

                                                    <button type="button" class="btn btn-danger btn-sm remove-allowance"
                                                        disabled title="Remove">
                                                        <i class="fa fa-trash"></i>
                                                    </button>

                                                </td>

                                            </tr>
                                        @endforelse

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


                        {{-- ===================================================== --}}
                        {{-- DRIVER EXPENSE                                        --}}
                        {{-- ===================================================== --}}

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

                                        @forelse(($dutySlip->driverExpenses ?? collect()) as $index => $driverExpense)
                                            <tr class="expense-row" data-index="{{ $index }}">

                                                <td>

                                                    <input type="hidden" name="driver_expenses[{{ $index }}][id]"
                                                        value="{{ $driverExpense->id }}">

                                                    <select name="driver_expenses[{{ $index }}][expense_id]"
                                                        class="form-control custom-select2 expense-select">

                                                        <option value="">
                                                            Select Expense
                                                        </option>


                                                        @foreach ($expenses ?? collect() as $expense)
                                                            <option value="{{ $expense->id }}"
                                                                data-rate="{{ $expense->amount ?? 0 }}"
                                                                {{ (string) $driverExpense->expense_id === (string) $expense->id ? 'selected' : '' }}>
                                                                {{ $expense->name }}
                                                            </option>
                                                        @endforeach

                                                    </select>


                                                    @error("driver_expenses.$index.expense_id")
                                                        <span class="text-danger d-block small mt-1">
                                                            {{ $message }}
                                                        </span>
                                                    @enderror

                                                </td>


                                                <td>

                                                    <input type="number"
                                                        name="driver_expenses[{{ $index }}][quantity]"
                                                        class="form-control expense-quantity"
                                                        value="{{ old("driver_expenses.$index.quantity", $driverExpense->quantity) }}"
                                                        min="0.01" step="0.01">

                                                </td>


                                                <td>

                                                    <input type="number"
                                                        name="driver_expenses[{{ $index }}][rate]"
                                                        class="form-control expense-rate"
                                                        value="{{ old("driver_expenses.$index.rate", $driverExpense->rate) }}"
                                                        min="0" step="0.01" readonly>

                                                </td>


                                                <td>

                                                    <input type="number"
                                                        name="driver_expenses[{{ $index }}][amount]"
                                                        class="form-control expense-amount"
                                                        value="{{ old("driver_expenses.$index.amount", $driverExpense->amount) }}"
                                                        min="0" step="0.01" readonly>

                                                </td>


                                                <td>

                                                    <input type="text"
                                                        name="driver_expenses[{{ $index }}][remarks]"
                                                        class="form-control"
                                                        value="{{ old("driver_expenses.$index.remarks", $driverExpense->remarks) }}"
                                                        maxlength="1000" placeholder="Remarks">

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
                                                                {{ old("driver_expenses.$index.status", $driverExpense->status) === $statusValue ? 'selected' : '' }}>
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

                                        @empty

                                            <tr class="expense-row" data-index="0">

                                                <td>

                                                    <select name="driver_expenses[0][expense_id]"
                                                        class="form-control custom-select2 expense-select">

                                                        <option value="">
                                                            Select Expense
                                                        </option>


                                                        @foreach ($expenses ?? collect() as $expense)
                                                            <option value="{{ $expense->id }}"
                                                                data-rate="{{ $expense->amount ?? 0 }}">
                                                                {{ $expense->name }}
                                                            </option>
                                                        @endforeach

                                                    </select>

                                                </td>


                                                <td>

                                                    <input type="number" name="driver_expenses[0][quantity]"
                                                        class="form-control expense-quantity" value="1"
                                                        min="0.01" step="0.01">

                                                </td>


                                                <td>

                                                    <input type="number" name="driver_expenses[0][rate]"
                                                        class="form-control expense-rate" value="0.00" min="0"
                                                        step="0.01" readonly>

                                                </td>


                                                <td>

                                                    <input type="number" name="driver_expenses[0][amount]"
                                                        class="form-control expense-amount" value="0.00" min="0"
                                                        step="0.01" readonly>

                                                </td>


                                                <td>

                                                    <input type="text" name="driver_expenses[0][remarks]"
                                                        class="form-control" maxlength="1000" placeholder="Remarks">

                                                </td>


                                                <td>

                                                    <select name="driver_expenses[0][status]"
                                                        class="form-control custom-select2">

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

                                                    <button type="button" class="btn btn-danger btn-sm remove-expense"
                                                        disabled title="Remove">
                                                        <i class="fa fa-trash"></i>
                                                    </button>

                                                </td>

                                            </tr>
                                        @endforelse

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


                        {{-- ===================================================== --}}
                        {{-- FINANCIAL SUMMARY                                      --}}
                        {{-- ===================================================== --}}

                        <div class="col-12 mt-4">

                            <div class="card financial-summary-box">

                                <div class="card-body">

                                    <h6 class="text-primary" style="color:#023a85 !important;">
                                        <b>Financial Summary</b>
                                    </h6>


                                    <small class="form-helper-text">
                                        Totals are for display only.
                                        Final values are calculated by the backend service.
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


                        {{-- ===================================================== --}}
                        {{-- REMARKS                                               --}}
                        {{-- ===================================================== --}}

                        <div class="col-12 mt-3">

                            <h5 class="form-section-title">
                                Remarks
                            </h5>

                            <hr>

                        </div>


                        <div class="col-12">

                            <div class="form-group">

                                <label for="remarks">

                                    <b>
                                        Remarks
                                    </b>

                                </label>


                                <textarea name="remarks" id="remarks" rows="4" maxlength="2000"
                                    class="form-control @error('remarks') is-invalid @enderror" placeholder="Enter Remarks">{{ old('remarks', $dutySlip->remarks) }}</textarea>


                                @error('remarks')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>


                        {{-- ===================================================== --}}
                        {{-- STATUS                                                --}}
                        {{-- ===================================================== --}}

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

                                    @foreach ([
                                        'open' => 'Open',
                                        'started' => 'Started',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                    ] as $statusValue => $statusLabel)
                                        <option value="{{ $statusValue }}"
                                            {{ old('status', $dutySlip->status) === $statusValue ? 'selected' : '' }}>

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


                        {{-- ===================================================== --}}
                        {{-- ACTION BUTTONS                                         --}}
                        {{-- ===================================================== --}}

                        <div class="col-12">

                            <div class="text-right mt-4">

                                <a href="{{ route('duty-slips.index') }}" class="btn btn-danger">

                                    <i class="fa fa-times"></i>
                                    Cancel

                                </a>


                                <button type="submit" id="update-duty-slip-btn" class="btn btn-success">

                                    <i class="fa fa-save"></i>
                                    Update Duty Slip

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
                            maxlength="1000" placeholder="Remarks">

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
                            maxlength="1000" placeholder="Remarks">

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
    <script src="{{ asset('backend/js/duty-slips/edit.js') }}"></script>
@endpush
