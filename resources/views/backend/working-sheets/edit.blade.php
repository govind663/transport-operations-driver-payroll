@extends('backend.layouts.master')

@section('title')
    Edit Working Sheet
@endsection


@push('styles')

<style>

    /*
    |--------------------------------------------------------------------------
    | WORKING SHEET
    |--------------------------------------------------------------------------
    */

    .section-title {
        color: #023a85 !important;
        font-weight: 600;
        margin-bottom: 10px;
    }


    .form-section {
        margin-bottom: 30px;
    }


    .form-section hr {
        margin-top: 8px;
        margin-bottom: 20px;
    }


    /*
    |--------------------------------------------------------------------------
    | DUTY SLIP INFORMATION
    |--------------------------------------------------------------------------
    */

    .duty-slip-info {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 15px;
        margin-top: 10px;
        display: none;
    }


    .duty-slip-info .info-label {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 2px;
    }


    .duty-slip-info .info-value {
        font-weight: 600;
        color: #343a40;
        word-break: break-word;
    }


    /*
    |--------------------------------------------------------------------------
    | CALCULATION BOX
    |--------------------------------------------------------------------------
    */

    .calculation-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 20px;
    }


    /*
    |--------------------------------------------------------------------------
    | TOTAL AMOUNT
    |--------------------------------------------------------------------------
    */

    .total-amount-box {
        background: #e8f5e9;
        border: 1px solid #c8e6c9;
        border-radius: 6px;
        padding: 15px;
    }


    .total-amount-box label {
        color: #2e7d32;
        font-weight: 600;
    }


    .total-amount-box input {
        font-size: 20px;
        font-weight: 700;
        color: #2e7d32;
    }


    /*
    |--------------------------------------------------------------------------
    | REQUIRED
    |--------------------------------------------------------------------------
    */

    .required {
        color: #dc3545;
    }


    /*
    |--------------------------------------------------------------------------
    | CURRENT VALUE
    |--------------------------------------------------------------------------
    */

    .current-value {
        font-size: 12px;
        color: #6c757d;
    }


    /*
    |--------------------------------------------------------------------------
    | READONLY AUDIT FIELDS
    |--------------------------------------------------------------------------
    */

    .audit-field {
        background-color: #f8f9fa !important;
        cursor: not-allowed;
    }

    /* Allowance / Expense breakdown tables */
    .detail-table-heading {
        color: #343a40;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .allowance-expense-table {
        min-width: 640px;
        margin-bottom: 0;
        background: #fff;
    }

    .allowance-expense-table th,
    .allowance-expense-table td {
        vertical-align: middle;
        font-size: 12px;
    }

    .allowance-expense-table .record-remarks {
        min-width: 140px;
        white-space: normal;
        overflow-wrap: anywhere;
    }

    .record-total-row {
        background: #f8f9fa;
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


                {{-- PAGE TITLE --}}
                <div class="col-md-8 col-sm-12">

                    <div class="title">

                        <h4>
                            Edit Working Sheet
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

                                <a href="{{ route('working-sheets.index') }}">
                                    Working Sheets
                                </a>

                            </li>


                            <li class="breadcrumb-item active">

                                Edit Working Sheet

                            </li>

                        </ol>

                    </nav>

                </div>


                {{-- HEADER ACTIONS --}}
                <div class="col-md-4 col-sm-12 text-right">

                    <a
                        href="{{ route('working-sheets.show', $workingSheet->id) }}"
                        class="btn btn-info">

                        <i class="fa fa-eye"></i>

                        View

                    </a>


                    <a
                        href="{{ route('working-sheets.index') }}"
                        class="btn btn-secondary">

                        <i class="fa fa-list"></i>

                        All Working Sheets

                    </a>

                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- FORM --}}
        {{-- ========================================================= --}}

        <form
            action="{{ route('working-sheets.update', $workingSheet->id) }}"
            method="POST"
            id="working-sheet-form"
            data-has-old-input="{{ session()->hasOldInput() ? '1' : '0' }}">

            @csrf

            @method('PUT')


            <div class="card-box pd-20 mb-30">


                {{-- ===================================================== --}}
                {{-- BASIC INFORMATION --}}
                {{-- ===================================================== --}}

                <div class="form-section">

                    <h5 class="section-title">

                        <b>
                            Working Sheet Information
                        </b>

                    </h5>


                    <hr>


                    <div class="row">


                        {{-- SHEET NUMBER --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="sheet_no">

                                    <b>
                                        Working Sheet No.
                                    </b>

                                    <span class="required">
                                        *
                                    </span>

                                </label>


                                <input
                                    type="text"
                                    name="sheet_no"
                                    id="sheet_no"
                                    class="form-control audit-field @error('sheet_no') is-invalid @enderror"
                                    required
                                    value="{{ $workingSheet->sheet_no }}"
                                    placeholder="Auto-generated Working Sheet No."
                                    maxlength="100"
                                    autocomplete="off"
                                    readonly
                                    aria-describedby="sheet-no-help">

                                <small id="sheet-no-help" class="text-muted">
                                    This number was generated when the Working Sheet was created and cannot be changed.
                                </small>


                                @error('sheet_no')

                                    <span class="invalid-feedback d-block">

                                        <strong>
                                            {{ $message }}
                                        </strong>

                                    </span>

                                @enderror

                            </div>

                        </div>



                        {{-- WORK DATE --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="work_date">

                                    <b>
                                        Work Date
                                    </b>

                                    <span class="required">
                                        *
                                    </span>

                                </label>


                                <input
                                    type="date"
                                    name="work_date"
                                    id="work_date"
                                    class="form-control @error('work_date') is-invalid @enderror"
                                    required
                                    value="{{ old(
                                        'work_date',
                                        $workingSheet->work_date
                                            ? \Illuminate\Support\Carbon::parse($workingSheet->work_date)->format('Y-m-d')
                                            : ''
                                    ) }}">


                                @error('work_date')

                                    <span class="invalid-feedback d-block">

                                        <strong>
                                            {{ $message }}
                                        </strong>

                                    </span>

                                @enderror

                            </div>

                        </div>



                        {{-- STATUS --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="status">

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
                                    class="form-control custom-select2 @error('status') is-invalid @enderror"
                                    required>

                                    <option value="">
                                        Select Status
                                    </option>


                                    <option
                                        value="{{ \App\Models\WorkingSheet::STATUS_DRAFT }}"
                                        {{ old(
                                            'status',
                                            $workingSheet->status
                                        ) === \App\Models\WorkingSheet::STATUS_DRAFT ? 'selected' : '' }}>

                                        Draft

                                    </option>


                                    <option
                                        value="{{ \App\Models\WorkingSheet::STATUS_SUBMITTED }}"
                                        {{ old(
                                            'status',
                                            $workingSheet->status
                                        ) === \App\Models\WorkingSheet::STATUS_SUBMITTED ? 'selected' : '' }}>

                                        Submitted

                                    </option>


                                    <option
                                        value="{{ \App\Models\WorkingSheet::STATUS_APPROVED }}"
                                        {{ old(
                                            'status',
                                            $workingSheet->status
                                        ) === \App\Models\WorkingSheet::STATUS_APPROVED ? 'selected' : '' }}>

                                        Approved

                                    </option>


                                    <option
                                        value="{{ \App\Models\WorkingSheet::STATUS_REJECTED }}"
                                        {{ old(
                                            'status',
                                            $workingSheet->status
                                        ) === \App\Models\WorkingSheet::STATUS_REJECTED ? 'selected' : '' }}>

                                        Rejected

                                    </option>


                                    <option
                                        value="{{ \App\Models\WorkingSheet::STATUS_COMPLETED }}"
                                        {{ old(
                                            'status',
                                            $workingSheet->status
                                        ) === \App\Models\WorkingSheet::STATUS_COMPLETED ? 'selected' : '' }}>

                                        Completed

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

                    </div>

                </div>



                {{-- ===================================================== --}}
                {{-- DUTY SLIP INFORMATION --}}
                {{-- ===================================================== --}}

                <div class="form-section">

                    <h5 class="section-title">

                        <b>
                            Duty Slip Information
                        </b>

                    </h5>


                    <hr>


                    <div class="row">


                        {{-- DUTY SLIP --}}
                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="duty_slip_id">

                                    <b>
                                        Duty Slip
                                    </b>

                                    <span class="required">
                                        *
                                    </span>

                                </label>


                                <select
                                    name="duty_slip_id"
                                    id="duty_slip_id"
                                    class="form-control custom-select2 @error('duty_slip_id') is-invalid @enderror"
                                    required>

                                    <option value="">
                                        Select Duty Slip
                                    </option>


                                    @foreach($dutySlips as $dutySlip)

                                        @php

                                            $assignment = $dutySlip->dutyAssignment;

                                            $travelRequest = optional($assignment)->travelRequest;

                                            // Prefer the Driver assigned directly to the Duty Slip;
                                            // fall back to the Duty Assignment for legacy records.
                                            $directDriver = method_exists($dutySlip, 'driver')
                                                ? $dutySlip->driver
                                                : null;

                                            $assignmentDriver = optional($assignment)->driver;

                                            $driverId = $dutySlip->getAttribute('driver_id')
                                                ?: ($directDriver?->id
                                                    ?: (optional($assignment)->driver_id
                                                        ?: ($assignmentDriver?->id ?? '')));

                                            $driver = $directDriver ?? $assignmentDriver;

                                            if (
                                                !$driver
                                                && $driverId
                                                && isset($drivers)
                                                && $drivers instanceof \Illuminate\Support\Collection
                                            ) {
                                                $driver = $drivers->firstWhere('id', (int) $driverId);
                                            }

                                            if (!$driverId && $driver) {
                                                $driverId = $driver->id;
                                            }

                                            // Prefer the vehicle stored directly on duty_slips;
                                            // keep the assignment fallback for legacy records.
                                            $directVehicle = method_exists($dutySlip, 'vehicle')
                                                ? $dutySlip->vehicle
                                                : null;

                                            $assignmentVehicle = optional($assignment)->vehicle;
                                            $vehicle = $directVehicle ?? $assignmentVehicle;

                                            $selectedDutySlip = (string) old(
                                                'duty_slip_id',
                                                $workingSheet->duty_slip_id
                                            ) === (string) $dutySlip->id;

                                            // Support common Driver name fields without assuming
                                            // every installation uses the same display attribute.
                                            $driverName = '';

                                            if ($driver) {
                                                $driverName = trim(
                                                    (string) ($driver->first_name ?? '') . ' ' .
                                                    (string) ($driver->last_name ?? '')
                                                );

                                                if ($driverName === '') {
                                                    $driverName = trim((string) (
                                                        $driver->name ?? $driver->full_name ?? ''
                                                    ));
                                                }
                                            }

                                            // Vehicle schemas can use different registration column names.
                                            $vehicleNumber = $vehicle
                                                ? (string) (
                                                    $vehicle->registration_number ??
                                                    $vehicle->registration_no ??
                                                    $vehicle->vehicle_number ??
                                                    $vehicle->vehicle_no ??
                                                    $vehicle->vehicle_code ??
                                                    ''
                                                )
                                                : '';

                                            $travelRequestNumber = $travelRequest
                                                ? (string) (
                                                    $travelRequest->request_no ??
                                                    $travelRequest->request_number ??
                                                    $travelRequest->travel_request_no ??
                                                    $travelRequest->id
                                                )
                                                : '';

                                            // Duty Slip work details used to auto-fill the edit form
                                            // when the user selects a different Duty Slip.
                                            $openingMeterValue = $dutySlip->opening_meter !== null
                                                ? number_format((float) $dutySlip->opening_meter, 2, '.', '')
                                                : '';

                                            $closingMeterValue = $dutySlip->closing_meter !== null
                                                ? number_format((float) $dutySlip->closing_meter, 2, '.', '')
                                                : '';

                                            $dutySlipTotalKm = $dutySlip->total_km;

                                            if (
                                                $dutySlipTotalKm === null
                                                && $dutySlip->opening_meter !== null
                                                && $dutySlip->closing_meter !== null
                                                && (float) $dutySlip->closing_meter >= (float) $dutySlip->opening_meter
                                            ) {
                                                $dutySlipTotalKm = (float) $dutySlip->closing_meter
                                                    - (float) $dutySlip->opening_meter;
                                            }

                                            $totalKmValue = $dutySlipTotalKm !== null
                                                ? number_format((float) $dutySlipTotalKm, 2, '.', '')
                                                : '';

                                            // Parse the timestamps explicitly so this view also works
                                            // if the model returns a date string instead of a Carbon instance.
                                            $startTime = $dutySlip->getAttribute('start_time');
                                            $endTime = $dutySlip->getAttribute('end_time');

                                            $startTimeCarbon = $startTime
                                                ? \Illuminate\Support\Carbon::parse($startTime)
                                                : null;

                                            $endTimeCarbon = $endTime
                                                ? \Illuminate\Support\Carbon::parse($endTime)
                                                : null;

                                            $startTimeValue = $startTimeCarbon
                                                ? $startTimeCarbon->format('Y-m-d\TH:i:s')
                                                : '';

                                            $endTimeValue = $endTimeCarbon
                                                ? $endTimeCarbon->format('Y-m-d\TH:i:s')
                                                : '';

                                            $totalHoursValue = '';

                                            if (
                                                $startTimeCarbon
                                                && $endTimeCarbon
                                                && $endTimeCarbon->greaterThanOrEqualTo($startTimeCarbon)
                                            ) {
                                                $totalHoursValue = number_format(
                                                    $startTimeCarbon->diffInSeconds($endTimeCarbon) / 3600,
                                                    2,
                                                    '.',
                                                    ''
                                                );
                                            }

                                            // overtime_hours does not exist in the supplied duty_slips schema.
                                            // Keep support for it if a future schema adds this attribute.
                                            $overtimeHoursValue = $dutySlip->getAttribute('overtime_hours');
                                            $overtimeHoursValue = $overtimeHoursValue !== null
                                                ? number_format((float) $overtimeHoursValue, 2, '.', '')
                                                : '';

                                        @endphp


                                        <option
                                            value="{{ $dutySlip->id }}"

                                            data-slip-no="{{ $dutySlip->slip_no ?? $dutySlip->id }}"

                                            data-status="{{ $dutySlip->status ?? '' }}"

                                            data-driver-id="{{ $driver?->id ?? $driverId ?? '' }}"

                                            data-driver="{{ $driverName }}"

                                            data-vehicle="{{ $vehicleNumber }}"

                                            data-travel-request="{{ $travelRequestNumber }}"


                                            data-allowance-total="{{ number_format((float) ($dutySlip->working_sheet_allowance_total ?? 0), 2, '.', '') }}"

                                            data-expense-total="{{ number_format((float) ($dutySlip->working_sheet_expense_total ?? 0), 2, '.', '') }}"

                                            data-allowance-records="{{ json_encode($dutySlip->working_sheet_allowance_records ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}"

                                            data-expense-records="{{ json_encode($dutySlip->working_sheet_expense_records ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}"

                                            data-date="{{ $dutySlip->duty_date ? \Illuminate\Support\Carbon::parse($dutySlip->duty_date)->format('Y-m-d') : '' }}"

                                            data-opening-meter="{{ $openingMeterValue }}"

                                            data-closing-meter="{{ $closingMeterValue }}"

                                            data-total-km="{{ $totalKmValue }}"

                                            data-start-time="{{ $startTimeValue }}"

                                            data-end-time="{{ $endTimeValue }}"

                                            data-total-hours="{{ $totalHoursValue }}"

                                            data-overtime-hours="{{ $overtimeHoursValue }}"

                                            {{ $selectedDutySlip ? 'selected' : '' }}>


                                            {{ $dutySlip->slip_no ?? 'Duty Slip #' . $dutySlip->id }}


                                            @if($driverName)

                                                -
                                                {{ $driverName }}

                                            @endif


                                            @if($vehicleNumber)

                                                -
                                                {{ $vehicleNumber }}

                                            @endif

                                        </option>

                                    @endforeach

                                </select>


                                @error('duty_slip_id')

                                    <span class="invalid-feedback d-block">

                                        <strong>
                                            {{ $message }}
                                        </strong>

                                    </span>

                                @enderror

                            </div>

                        </div>


                        {{-- DRIVER DERIVED FROM DUTY SLIP --}}
                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="driver_name">
                                    <b>Driver</b>
                                    <span class="required">*</span>
                                </label>

                                <input
                                    type="hidden"
                                    name="driver_id"
                                    id="driver_id"
                                    value="{{ old('driver_id', $workingSheet->driver_id ?? '') }}">

                                <input
                                    type="text"
                                    id="driver_name"
                                    class="form-control"
                                    value=""
                                    placeholder="Driver will be selected from Duty Slip"
                                    readonly>

                                <small class="text-muted">
                                    Driver is automatically linked from the selected Duty Slip.
                                </small>

                                @error('driver_id')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>

                    </div>



                    {{-- DUTY SLIP DETAILS --}}
                    <div
                        id="duty-slip-info"
                        class="duty-slip-info">

                        <div class="row">


                            {{-- SLIP NO --}}
                            <div class="col-md-2">

                                <div class="info-label">
                                    Duty Slip
                                </div>


                                <div
                                    class="info-value"
                                    id="info-slip-no">

                                    -

                                </div>

                            </div>



                            {{-- STATUS --}}
                            <div class="col-md-2">

                                <div class="info-label">
                                    Status
                                </div>


                                <div
                                    class="info-value"
                                    id="info-status">

                                    -

                                </div>

                            </div>



                            {{-- DRIVER --}}
                            <div class="col-md-3">

                                <div class="info-label">
                                    Driver
                                </div>


                                <div
                                    class="info-value"
                                    id="info-driver">

                                    -

                                </div>

                            </div>



                            {{-- VEHICLE --}}
                            <div class="col-md-3">

                                <div class="info-label">
                                    Vehicle
                                </div>


                                <div
                                    class="info-value"
                                    id="info-vehicle">

                                    -

                                </div>

                            </div>



                            {{-- TRAVEL REQUEST --}}
                            <div class="col-md-2">

                                <div class="info-label">
                                    Travel Request
                                </div>


                                <div
                                    class="info-value"
                                    id="info-travel-request">

                                    -

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- ===================================================== --}}
                {{-- METER & WORKING DETAILS --}}
                {{-- ===================================================== --}}

                <div class="form-section">

                    <h5 class="section-title">

                        <b>
                            Meter & Working Details
                        </b>

                    </h5>


                    <hr>


                    <div class="row">


                        {{-- OPENING METER --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="opening_meter">

                                    <b>
                                        Opening Meter
                                    </b>

                                </label>


                                <input
                                    type="number"
                                    name="opening_meter"
                                    id="opening_meter"
                                    class="form-control decimal-input @error('opening_meter') is-invalid @enderror"
                                    value="{{ old(
                                        'opening_meter',
                                        $workingSheet->opening_meter
                                    ) }}"
                                    placeholder="Enter Opening Meter"
                                    min="0"
                                    step="0.01">


                                @error('opening_meter')

                                    <span class="invalid-feedback d-block">

                                        <strong>
                                            {{ $message }}
                                        </strong>

                                    </span>

                                @enderror

                            </div>

                        </div>



                        {{-- CLOSING METER --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="closing_meter">

                                    <b>
                                        Closing Meter
                                    </b>

                                </label>


                                <input
                                    type="number"
                                    name="closing_meter"
                                    id="closing_meter"
                                    class="form-control decimal-input @error('closing_meter') is-invalid @enderror"
                                    value="{{ old(
                                        'closing_meter',
                                        $workingSheet->closing_meter
                                    ) }}"
                                    placeholder="Enter Closing Meter"
                                    min="0"
                                    step="0.01">


                                @error('closing_meter')

                                    <span class="invalid-feedback d-block">

                                        <strong>
                                            {{ $message }}
                                        </strong>

                                    </span>

                                @enderror

                            </div>

                        </div>



                        {{-- TOTAL KM --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="total_km">

                                    <b>
                                        Total KM
                                    </b>

                                </label>


                                <input
                                    type="number"
                                    name="total_km"
                                    id="total_km"
                                    class="form-control decimal-input @error('total_km') is-invalid @enderror"
                                    value="{{ old(
                                        'total_km',
                                        $workingSheet->total_km
                                    ) }}"
                                    placeholder="Auto calculated from meter"
                                    min="0"
                                    step="0.01"
                                    readonly>


                                <small class="text-muted">

                                    Closing Meter - Opening Meter

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



                        {{-- TOTAL HOURS --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="total_hours">

                                    <b>
                                        Total Hours
                                    </b>

                                </label>


                                <input
                                    type="number"
                                    name="total_hours"
                                    id="total_hours"
                                    class="form-control decimal-input @error('total_hours') is-invalid @enderror"
                                    value="{{ old(
                                        'total_hours',
                                        $workingSheet->total_hours
                                    ) }}"
                                    placeholder="Enter Total Hours"
                                    min="0"
                                    step="0.01">


                                @error('total_hours')

                                    <span class="invalid-feedback d-block">

                                        <strong>
                                            {{ $message }}
                                        </strong>

                                    </span>

                                @enderror

                            </div>

                        </div>



                        {{-- OVERTIME HOURS --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="overtime_hours">

                                    <b>
                                        Overtime Hours
                                    </b>

                                </label>


                                <input
                                    type="number"
                                    name="overtime_hours"
                                    id="overtime_hours"
                                    class="form-control decimal-input @error('overtime_hours') is-invalid @enderror"
                                    value="{{ old(
                                        'overtime_hours',
                                        $workingSheet->overtime_hours
                                    ) }}"
                                    placeholder="Enter Overtime Hours"
                                    min="0"
                                    step="0.01">


                                @error('overtime_hours')

                                    <span class="invalid-feedback d-block">

                                        <strong>
                                            {{ $message }}
                                        </strong>

                                    </span>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>



                {{-- ===================================================== --}}
                {{-- AMOUNT INFORMATION --}}
                {{-- ===================================================== --}}

                <div class="form-section">

                    <h5 class="section-title">

                        <b>
                            Amount Details
                        </b>

                    </h5>


                    <hr>


                    {{-- ===================================================== --}}
                    {{-- DRIVER ALLOWANCE / EXPENSE RECORD BREAKDOWN --}}
                    {{-- All statuses are shown; only Approved rows count in totals. --}}
                    {{-- ===================================================== --}}
                    <div class="row mb-4">

                        {{-- DRIVER ALLOWANCE RECORDS --}}
                        <div class="col-lg-6 mb-4">

                            <h6 class="detail-table-heading">Driver Allowance Records</h6>

                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-striped allowance-expense-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Allowance</th>
                                            <th>Qty</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody id="allowance-records-body">
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                Loading allowance records for this Duty Slip...
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="record-total-row">
                                            <th colspan="4">Approved Allowance Total</th>
                                            <th id="allowance-approved-total">0.00</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>

                        {{-- DRIVER EXPENSE RECORDS --}}
                        <div class="col-lg-6 mb-4">

                            <h6 class="detail-table-heading">Driver Expense Records</h6>

                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-striped allowance-expense-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Expense</th>
                                            <th>Qty</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Remarks / Document</th>
                                        </tr>
                                    </thead>
                                    <tbody id="expense-records-body">
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                Loading expense records for this Duty Slip...
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="record-total-row">
                                            <th colspan="4">Approved Expense Total</th>
                                            <th id="expense-approved-total">0.00</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>

                    </div>

                    <div class="calculation-box">

                        <div class="row">


                            {{-- BASE AMOUNT --}}
                            <div class="col-md-4">

                                <div class="form-group">

                                    <label for="base_amount">

                                        <b>
                                            Base Amount
                                        </b>

                                    </label>


                                    <input
                                        type="number"
                                        name="base_amount"
                                        id="base_amount"
                                        class="form-control amount-input @error('base_amount') is-invalid @enderror"
                                        value="{{ old(
                                            'base_amount',
                                            $workingSheet->base_amount
                                        ) }}"
                                        placeholder="0.00"
                                        min="0"
                                        step="0.01">


                                    @error('base_amount')

                                        <span class="invalid-feedback d-block">

                                            <strong>
                                                {{ $message }}
                                            </strong>

                                        </span>

                                    @enderror

                                </div>

                            </div>



                            {{-- EXTRA KM AMOUNT --}}
                            <div class="col-md-4">

                                <div class="form-group">

                                    <label for="extra_km_amount">

                                        <b>
                                            Extra KM Amount
                                        </b>

                                    </label>


                                    <input
                                        type="number"
                                        name="extra_km_amount"
                                        id="extra_km_amount"
                                        class="form-control amount-input @error('extra_km_amount') is-invalid @enderror"
                                        value="{{ old(
                                            'extra_km_amount',
                                            $workingSheet->extra_km_amount
                                        ) }}"
                                        placeholder="0.00"
                                        min="0"
                                        step="0.01">


                                    @error('extra_km_amount')

                                        <span class="invalid-feedback d-block">

                                            <strong>
                                                {{ $message }}
                                            </strong>

                                        </span>

                                    @enderror

                                </div>

                            </div>



                            {{-- OVERTIME AMOUNT --}}
                            <div class="col-md-4">

                                <div class="form-group">

                                    <label for="overtime_amount">

                                        <b>
                                            Overtime Amount
                                        </b>

                                    </label>


                                    <input
                                        type="number"
                                        name="overtime_amount"
                                        id="overtime_amount"
                                        class="form-control amount-input @error('overtime_amount') is-invalid @enderror"
                                        value="{{ old(
                                            'overtime_amount',
                                            $workingSheet->overtime_amount
                                        ) }}"
                                        placeholder="0.00"
                                        min="0"
                                        step="0.01">


                                    @error('overtime_amount')

                                        <span class="invalid-feedback d-block">

                                            <strong>
                                                {{ $message }}
                                            </strong>

                                        </span>

                                    @enderror

                                </div>

                            </div>



                            {{-- ALLOWANCE AMOUNT --}}
                            <div class="col-md-4">

                                <div class="form-group">

                                    <label for="allowance_amount"><b>Allowance Amount</b></label>

                                    <input
                                        type="number"
                                        name="allowance_amount"
                                        id="allowance_amount"
                                        class="form-control amount-input @error('allowance_amount') is-invalid @enderror"
                                        value="{{ old('allowance_amount', $workingSheet->allowance_amount ?? 0) }}"
                                        placeholder="0.00"
                                        min="0"
                                        step="0.01"
                                        readonly>

                                    @error('allowance_amount')
                                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                    @enderror

                                </div>

                            </div>


                            {{-- EXPENSE AMOUNT --}}
                            <div class="col-md-4">

                                <div class="form-group">

                                    <label for="expense_amount"><b>Expense Amount</b></label>

                                    <input
                                        type="number"
                                        name="expense_amount"
                                        id="expense_amount"
                                        class="form-control amount-input @error('expense_amount') is-invalid @enderror"
                                        value="{{ old('expense_amount', $workingSheet->expense_amount ?? 0) }}"
                                        placeholder="0.00"
                                        min="0"
                                        step="0.01"
                                        readonly>

                                    @error('expense_amount')
                                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                    @enderror

                                </div>

                            </div>


                            {{-- OTHER AMOUNT --}}
                            <div class="col-md-4">

                                <div class="form-group">

                                    <label for="other_amount">

                                        <b>
                                            Other Amount
                                        </b>

                                    </label>


                                    <input
                                        type="number"
                                        name="other_amount"
                                        id="other_amount"
                                        class="form-control amount-input @error('other_amount') is-invalid @enderror"
                                        value="{{ old(
                                            'other_amount',
                                            $workingSheet->other_amount
                                        ) }}"
                                        placeholder="0.00"
                                        min="0"
                                        step="0.01">


                                    @error('other_amount')

                                        <span class="invalid-feedback d-block">

                                            <strong>
                                                {{ $message }}
                                            </strong>

                                        </span>

                                    @enderror

                                </div>

                            </div>



                            {{-- TOTAL AMOUNT --}}
                            <div class="col-md-8">

                                <div class="form-group total-amount-box">

                                    <label for="total_amount">

                                        <b>
                                            Total Amount
                                        </b>

                                    </label>


                                    <input
                                        type="number"
                                        name="total_amount"
                                        id="total_amount"
                                        class="form-control @error('total_amount') is-invalid @enderror"
                                        value="{{ old(
                                            'total_amount',
                                            $workingSheet->total_amount
                                        ) }}"
                                        placeholder="0.00"
                                        min="0"
                                        step="0.01"
                                        readonly>


                                    <small class="text-muted">

                                        Base + Extra KM + Overtime + Allowance + Expense + Other

                                    </small>


                                    @error('total_amount')

                                        <span class="invalid-feedback d-block">

                                            <strong>
                                                {{ $message }}
                                            </strong>

                                        </span>

                                    @enderror

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- ===================================================== --}}
                {{-- REMARKS --}}
                {{-- ===================================================== --}}

                <div class="form-section">

                    <h5 class="section-title">

                        <b>
                            Remarks
                        </b>

                    </h5>


                    <hr>


                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label for="remarks">

                                    <b>
                                        Remarks
                                    </b>

                                </label>


                                <textarea
                                    name="remarks"
                                    id="remarks"
                                    rows="5"
                                    maxlength="2000"
                                    class="form-control @error('remarks') is-invalid @enderror"
                                    placeholder="Enter remarks if required">{{ old(
                                        'remarks',
                                        $workingSheet->remarks
                                    ) }}</textarea>


                                <small class="text-muted">

                                    Maximum 2000 characters.

                                </small>


                                @error('remarks')

                                    <span class="invalid-feedback d-block">

                                        <strong>
                                            {{ $message }}
                                        </strong>

                                    </span>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>



                {{-- ===================================================== --}}
                {{-- RECORD INFORMATION --}}
                {{-- ===================================================== --}}

                <div class="form-section">

                    <h5 class="section-title">

                        <b>
                            Record Information
                        </b>

                    </h5>


                    <hr>


                    <div class="row">


                        {{-- CREATED --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label>

                                    <b>
                                        Created At
                                    </b>

                                </label>


                                <input
                                    type="text"
                                    class="form-control audit-field"
                                    value="{{ optional($workingSheet->created_at)->format('d-m-Y H:i:s') }}"
                                    readonly>

                            </div>

                        </div>



                        {{-- UPDATED --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label>

                                    <b>
                                        Last Updated
                                    </b>

                                </label>


                                <input
                                    type="text"
                                    class="form-control audit-field"
                                    value="{{ optional($workingSheet->updated_at)->format('d-m-Y H:i:s') }}"
                                    readonly>

                            </div>

                        </div>



                        {{-- ID --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label>

                                    <b>
                                        Working Sheet ID
                                    </b>

                                </label>


                                <input
                                    type="text"
                                    class="form-control audit-field"
                                    value="{{ $workingSheet->id }}"
                                    readonly>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- ===================================================== --}}
                {{-- ACTION BUTTONS --}}
                {{-- ===================================================== --}}

                <div class="row">

                    <div class="col-12">

                        <div class="text-right mt-3">


                            {{-- CANCEL --}}
                            <a
                                href="{{ route('working-sheets.index') }}"
                                class="btn btn-danger">

                                <i class="fa fa-times"></i>

                                Cancel

                            </a>



                            {{-- VIEW --}}
                            <a
                                href="{{ route('working-sheets.show', $workingSheet->id) }}"
                                class="btn btn-info">

                                <i class="fa fa-eye"></i>

                                View

                            </a>



                            {{-- UPDATE --}}
                            <button
                                type="submit"
                                id="update-working-sheet"
                                class="btn btn-success">

                                <i class="fa fa-save"></i>

                                Update Working Sheet

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
    <script src="{{ asset('backend/assets/js/working-sheets/edit.js') }}" defer></script>
@endpush
