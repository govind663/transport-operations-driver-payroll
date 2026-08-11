@extends('backend.layouts.master')

@section('title')
    Edit Working Sheet
@endsection

@push('styles')

<style>

    /*
    |--------------------------------------------------------------------------
    | Working Sheet
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
    }

    .calculation-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 20px;
    }

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

    .required {
        color: #dc3545;
    }

    .current-value {
        font-size: 12px;
        color: #6c757d;
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
            id="working-sheet-form">

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


                        {{-- Sheet Number --}}
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
                                    class="form-control @error('sheet_no') is-invalid @enderror"
                                    value="{{ old('sheet_no', $workingSheet->sheet_no) }}"
                                    placeholder="Enter Working Sheet No."
                                    maxlength="100"
                                    autocomplete="off">

                                @error('sheet_no')

                                    <span class="invalid-feedback d-block">

                                        <strong>
                                            {{ $message }}
                                        </strong>

                                    </span>

                                @enderror

                            </div>

                        </div>


                        {{-- Work Date --}}
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
                                    value="{{ old(
                                        'work_date',
                                        optional($workingSheet->work_date)->format('Y-m-d')
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


                        {{-- Status --}}
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
                                    class="form-control custom-select2 @error('status') is-invalid @enderror">

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
                {{-- DUTY SLIP --}}
                {{-- ===================================================== --}}

                <div class="form-section">

                    <h5 class="section-title">

                        <b>
                            Duty Slip Information
                        </b>

                    </h5>

                    <hr>


                    <div class="row">


                        {{-- Duty Slip --}}
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
                                    class="form-control custom-select2 @error('duty_slip_id') is-invalid @enderror">

                                    <option value="">
                                        Select Duty Slip
                                    </option>

                                    @foreach($dutySlips as $dutySlip)

                                        @php

                                            $assignment =
                                                $dutySlip->dutyAssignment;

                                            $travelRequest =
                                                optional($assignment)->travelRequest;

                                            $driver =
                                                optional($assignment)->driver;

                                            $vehicle =
                                                optional($assignment)->vehicle;

                                            $selectedDutySlip =
                                                old(
                                                    'duty_slip_id',
                                                    $workingSheet->duty_slip_id
                                                ) == $dutySlip->id;

                                        @endphp

                                        <option
                                            value="{{ $dutySlip->id }}"

                                            data-slip-no="{{ $dutySlip->slip_no ?? $dutySlip->id }}"

                                            data-status="{{ $dutySlip->status ?? '' }}"

                                            data-driver="{{ $driver
                                                ? trim(
                                                    ($driver->first_name ?? '') .
                                                    ' ' .
                                                    ($driver->last_name ?? '')
                                                )
                                                : ''
                                            }}"

                                            data-vehicle="{{ $vehicle
                                                ? (
                                                    $vehicle->registration_number ??
                                                    $vehicle->vehicle_number ??
                                                    $vehicle->vehicle_code ??
                                                    ''
                                                )
                                                : ''
                                            }}"

                                            data-travel-request="{{ $travelRequest
                                                ? (
                                                    $travelRequest->request_no ??
                                                    $travelRequest->id
                                                )
                                                : ''
                                            }}"

                                            {{ $selectedDutySlip ? 'selected' : '' }}>

                                            {{ $dutySlip->slip_no ?? 'Duty Slip #' . $dutySlip->id }}

                                            @if($driver)

                                                -
                                                {{ trim(
                                                    ($driver->first_name ?? '') .
                                                    ' ' .
                                                    ($driver->last_name ?? '')
                                                ) }}

                                            @endif

                                            @if($vehicle)

                                                -
                                                {{
                                                    $vehicle->registration_number ??
                                                    $vehicle->vehicle_number ??
                                                    $vehicle->vehicle_code ??
                                                    ''
                                                }}

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


                    </div>


                    {{-- Duty Slip Details --}}
                    <div
                        id="duty-slip-info"
                        class="duty-slip-info">

                        <div class="row">


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
                {{-- METER INFORMATION --}}
                {{-- ===================================================== --}}

                <div class="form-section">

                    <h5 class="section-title">

                        <b>
                            Meter & Working Details
                        </b>

                    </h5>

                    <hr>


                    <div class="row">


                        {{-- Opening Meter --}}
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


                        {{-- Closing Meter --}}
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


                        {{-- Total KM --}}
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
                                    step="0.01">

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


                        {{-- Total Hours --}}
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


                        {{-- Overtime Hours --}}
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


                    <div class="calculation-box">

                        <div class="row">


                            {{-- Base Amount --}}
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


                            {{-- Extra KM Amount --}}
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


                            {{-- Overtime Amount --}}
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


                            {{-- Other Amount --}}
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


                            {{-- Total Amount --}}
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
                                        step="0.01">

                                    <small class="text-muted">

                                        Base + Extra KM + Overtime + Other

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
                {{-- AUDIT INFORMATION --}}
                {{-- ===================================================== --}}

                <div class="form-section">

                    <h5 class="section-title">

                        <b>
                            Record Information
                        </b>

                    </h5>

                    <hr>


                    <div class="row">

                        <div class="col-md-4">

                            <div class="form-group">

                                <label>
                                    <b>
                                        Created At
                                    </b>
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ optional($workingSheet->created_at)->format('d-m-Y H:i:s') }}"
                                    readonly>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="form-group">

                                <label>
                                    <b>
                                        Last Updated
                                    </b>
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ optional($workingSheet->updated_at)->format('d-m-Y H:i:s') }}"
                                    readonly>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="form-group">

                                <label>
                                    <b>
                                        Working Sheet ID
                                    </b>
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
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

                            <a
                                href="{{ route('working-sheets.index') }}"
                                class="btn btn-danger">

                                <i class="fa fa-times"></i>

                                Cancel

                            </a>


                            <a
                                href="{{ route('working-sheets.show', $workingSheet->id) }}"
                                class="btn btn-info">

                                <i class="fa fa-eye"></i>

                                View

                            </a>


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

<script>

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | Duty Slip Information
    |--------------------------------------------------------------------------
    */

    function loadDutySlipInfo()
    {
        const selected =
            $('#duty_slip_id option:selected');

        const dutySlipId =
            $('#duty_slip_id').val();


        if (!dutySlipId) {

            $('#duty-slip-info').hide();

            return;
        }


        $('#info-slip-no').text(
            selected.data('slip-no') || '-'
        );


        $('#info-status').text(
            selected.data('status') || '-'
        );


        $('#info-driver').text(
            selected.data('driver') || '-'
        );


        $('#info-vehicle').text(
            selected.data('vehicle') || '-'
        );


        $('#info-travel-request').text(
            selected.data('travel-request') || '-'
        );


        $('#duty-slip-info').slideDown(150);
    }


    $('#duty_slip_id').on(
        'change',
        loadDutySlipInfo
    );


    /*
    |--------------------------------------------------------------------------
    | Load Existing Duty Slip Information
    |--------------------------------------------------------------------------
    */

    loadDutySlipInfo();



    /*
    |--------------------------------------------------------------------------
    | Calculate Total KM
    |--------------------------------------------------------------------------
    */

    function calculateTotalKm()
    {
        const opening =
            parseFloat(
                $('#opening_meter').val()
            );


        const closing =
            parseFloat(
                $('#closing_meter').val()
            );


        if (
            !isNaN(opening) &&
            !isNaN(closing)
        ) {


            if (closing < opening) {

                $('#total_km').val('');

                return;

            }


            const total =
                closing - opening;


            $('#total_km').val(
                total.toFixed(2)
            );

        }

    }


    $('#opening_meter, #closing_meter')
        .on(
            'input change',
            calculateTotalKm
        );



    /*
    |--------------------------------------------------------------------------
    | Closing Meter Validation
    |--------------------------------------------------------------------------
    */

    $('#closing_meter').on(
        'input change',
        function () {


            const opening =
                parseFloat(
                    $('#opening_meter').val()
                );


            const closing =
                parseFloat(
                    $(this).val()
                );


            if (
                !isNaN(opening) &&
                !isNaN(closing) &&
                closing < opening
            ) {

                $(this).addClass(
                    'is-invalid'
                );

            } else {

                $(this).removeClass(
                    'is-invalid'
                );

            }

        }
    );



    /*
    |--------------------------------------------------------------------------
    | Calculate Total Amount
    |--------------------------------------------------------------------------
    */

    function calculateTotalAmount()
    {

        const base =
            parseFloat(
                $('#base_amount').val()
            ) || 0;


        const extraKm =
            parseFloat(
                $('#extra_km_amount').val()
            ) || 0;


        const overtime =
            parseFloat(
                $('#overtime_amount').val()
            ) || 0;


        const other =
            parseFloat(
                $('#other_amount').val()
            ) || 0;


        const total =
            base +
            extraKm +
            overtime +
            other;


        $('#total_amount').val(
            total.toFixed(2)
        );

    }


    $('#base_amount, #extra_km_amount, #overtime_amount, #other_amount')
        .on(
            'input change',
            calculateTotalAmount
        );



    /*
    |--------------------------------------------------------------------------
    | Calculate Total Amount on Page Load
    |--------------------------------------------------------------------------
    */

    calculateTotalAmount();



    /*
    |--------------------------------------------------------------------------
    | Numeric Input Cleanup
    |--------------------------------------------------------------------------
    */

    $('.decimal-input, .amount-input').on(
        'input',
        function () {


            let value =
                $(this).val();


            value =
                value.replace(
                    /[^0-9.]/g,
                    ''
                );


            const parts =
                value.split('.');


            /*
            |--------------------------------------------------------------------------
            | Only One Decimal Point
            |--------------------------------------------------------------------------
            */

            if (parts.length > 2) {

                value =
                    parts[0] +
                    '.' +
                    parts.slice(1).join('');

            }


            $(this).val(value);

        }
    );



    /*
    |--------------------------------------------------------------------------
    | Sheet Number Formatting
    |--------------------------------------------------------------------------
    */

    $('#sheet_no').on(
        'blur',
        function () {

            $(this).val(
                $(this)
                    .val()
                    .trim()
                    .toUpperCase()
            );

        }
    );



    /*
    |--------------------------------------------------------------------------
    | Remarks Formatting
    |--------------------------------------------------------------------------
    */

    $('#remarks').on(
        'blur',
        function () {

            $(this).val(
                $(this)
                    .val()
                    .trim()
            );

        }
    );



    /*
    |--------------------------------------------------------------------------
    | Form Submit
    |--------------------------------------------------------------------------
    */

    $('#working-sheet-form').on(
        'submit',
        function (e) {


            /*
            |--------------------------------------------------------------------------
            | Clean Sheet Number
            |--------------------------------------------------------------------------
            */

            $('#sheet_no').val(
                $('#sheet_no')
                    .val()
                    .trim()
                    .toUpperCase()
            );


            /*
            |--------------------------------------------------------------------------
            | Clean Remarks
            |--------------------------------------------------------------------------
            */

            $('#remarks').val(
                $('#remarks')
                    .val()
                    .trim()
            );


            /*
            |--------------------------------------------------------------------------
            | Meter Validation
            |--------------------------------------------------------------------------
            */

            const opening =
                parseFloat(
                    $('#opening_meter').val()
                );


            const closing =
                parseFloat(
                    $('#closing_meter').val()
                );


            if (
                !isNaN(opening) &&
                !isNaN(closing) &&
                closing < opening
            ) {

                e.preventDefault();


                $('#closing_meter')
                    .addClass(
                        'is-invalid'
                    )
                    .focus();


                alert(
                    'Closing meter must be greater than or equal to opening meter.'
                );


                return false;

            }


            /*
            |--------------------------------------------------------------------------
            | Recalculate Total KM
            |--------------------------------------------------------------------------
            */

            if (
                !isNaN(opening) &&
                !isNaN(closing) &&
                closing >= opening
            ) {

                $('#total_km').val(
                    (
                        closing -
                        opening
                    ).toFixed(2)
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Recalculate Total Amount
            |--------------------------------------------------------------------------
            */

            calculateTotalAmount();



            /*
            |--------------------------------------------------------------------------
            | Prevent Double Submission
            |--------------------------------------------------------------------------
            */

            const submitButton =
                $('#update-working-sheet');


            submitButton
                .prop(
                    'disabled',
                    true
                )
                .html(
                    '<i class="fa fa-spinner fa-spin"></i> Updating Working Sheet...'
                );

        }
    );


});

</script>

@endpush