@extends('backend.layouts.master')

@section('title')
    Create Working Sheet
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

    .duty-slip-option {
        padding: 5px;
    }

    .allowance-expense-table th,
    .allowance-expense-table td {
        vertical-align: middle;
        white-space: nowrap;
        font-size: 13px;
    }

    .allowance-expense-table td.record-remarks {
        white-space: normal;
        min-width: 130px;
    }

    .detail-table-heading {
        font-weight: 600;
        color: #343a40;
        margin-bottom: 10px;
    }

    .record-total-row th,
    .record-total-row td {
        background: #f8f9fa;
        font-weight: 700;
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
                            Create Working Sheet
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

                                Create Working Sheet

                            </li>

                        </ol>

                    </nav>

                </div>

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- FORM --}}
        {{-- ========================================================= --}}
        <form action="{{ route('working-sheets.store') }}"
            method="POST"
            id="working-sheet-form">

            @csrf

            <div class="card-box pd-20 mb-30">

                {{-- ===================================================== --}}
                {{-- BASIC INFORMATION --}}
                {{-- ===================================================== --}}
                <div class="form-section">

                    <h5 class="section-title">
                        <b>Working Sheet Information</b>
                    </h5>

                    <hr>

                    <div class="row">

                        {{-- Working Sheet Number --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="sheet_no">
                                    <b>Working Sheet No.</b>
                                    <span class="required">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="sheet_no"
                                    id="sheet_no"
                                    class="form-control @error('sheet_no') is-invalid @enderror"
                                    value="{{ $nextSheetNo ?? old('sheet_no', '') }}"
                                    placeholder="Auto-generated Working Sheet No."
                                    maxlength="100"
                                    autocomplete="off"
                                    readonly
                                    required
                                    aria-describedby="sheet-no-help">

                                <small id="sheet-no-help" class="text-muted">
                                    Working Sheet number is generated automatically.
                                </small>

                                @error('sheet_no')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>


                        {{-- Work Date --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="work_date">
                                    <b>Work Date</b>
                                    <span class="required">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="work_date"
                                    id="work_date"
                                    class="form-control @error('work_date') is-invalid @enderror"
                                    value="{{ old('work_date', now()->format('Y-m-d')) }}"
                                    required>

                                @error('work_date')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>


                        {{-- Status --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="status">
                                    <b>Status</b>
                                    <span class="required">*</span>
                                </label>

                                <select
                                    name="status"
                                    id="status"
                                    class="form-control custom-select2 @error('status') is-invalid @enderror"
                                    required>

                                    <option value="">Select Status</option>

                                    <option
                                        value="{{ \App\Models\WorkingSheet::STATUS_DRAFT }}"
                                        {{ old('status', 'draft') === \App\Models\WorkingSheet::STATUS_DRAFT ? 'selected' : '' }}>
                                        Draft
                                    </option>

                                    <option
                                        value="{{ \App\Models\WorkingSheet::STATUS_SUBMITTED }}"
                                        {{ old('status') === \App\Models\WorkingSheet::STATUS_SUBMITTED ? 'selected' : '' }}>
                                        Submitted
                                    </option>

                                    <option
                                        value="{{ \App\Models\WorkingSheet::STATUS_APPROVED }}"
                                        {{ old('status') === \App\Models\WorkingSheet::STATUS_APPROVED ? 'selected' : '' }}>
                                        Approved
                                    </option>

                                    <option
                                        value="{{ \App\Models\WorkingSheet::STATUS_REJECTED }}"
                                        {{ old('status') === \App\Models\WorkingSheet::STATUS_REJECTED ? 'selected' : '' }}>
                                        Rejected
                                    </option>

                                    <option
                                        value="{{ \App\Models\WorkingSheet::STATUS_COMPLETED }}"
                                        {{ old('status') === \App\Models\WorkingSheet::STATUS_COMPLETED ? 'selected' : '' }}>
                                        Completed
                                    </option>

                                </select>

                                @error('status')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
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
                        <b>Duty Slip Information</b>
                    </h5>

                    <hr>

                    <div class="row">

                        {{-- Duty Slip --}}
                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="duty_slip_id">
                                    <b>Duty Slip</b>
                                    <span class="required">*</span>
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

                                            // Prefer the Vehicle assigned directly to the Duty Slip;
                                            // fall back to the Duty Assignment for legacy records.
                                            $vehicle = $dutySlip->vehicle
                                                ?? optional($assignment)->vehicle;

                                            // Prefer a driver assigned directly to the Duty Slip;
                                            // fall back to the linked Duty Assignment.
                                            $driver = $dutySlip->driver
                                                ?? optional($assignment)->driver;

                                            $driverId = $driver?->id
                                                ?? $dutySlip->getAttribute('driver_id')
                                                ?? optional($assignment)->driver_id
                                                ?? '';

                                            // If the Duty Slip has a driver_id but no loaded relation,
                                            // resolve the model so the name can still be displayed.
                                            if (!$driver && $driverId) {
                                                $driver = \App\Models\Driver::find($driverId);
                                            }

                                            $driverName = $driver
                                                ? trim(
                                                    ($driver->first_name ?? '') . ' ' .
                                                    ($driver->last_name ?? '')
                                                )
                                                : '';

                                            if ($driverName === '' && $driver) {
                                                $driverName = trim((string) ($driver->name ?? ''));
                                            }

                                            $vehicleNumber = $vehicle
                                                ? (
                                                    $vehicle->registration_number ??
                                                    $vehicle->vehicle_number ??
                                                    $vehicle->vehicle_code ??
                                                    ''
                                                )
                                                : '';

                                            $selectedDutySlip =
                                                old('duty_slip_id') == $dutySlip->id;

                                        @endphp

                                        <option
                                            value="{{ $dutySlip->id }}"

                                            data-slip-no="{{ $dutySlip->slip_no ?? $dutySlip->id }}"

                                            data-status="{{ $dutySlip->status ?? '' }}"

                                            data-driver-id="{{ $driverId }}"

                                            data-driver="{{ $driverName }}"

                                            data-vehicle="{{ $vehicleNumber }}"

                                            data-travel-request="{{ $travelRequest ? ($travelRequest->request_no ?? $travelRequest->id) : '' }}"

                                            data-allowance-total="{{ number_format((float) ($dutySlip->working_sheet_allowance_total ?? 0), 2, '.', '') }}"

                                            data-expense-total="{{ number_format((float) ($dutySlip->working_sheet_expense_total ?? 0), 2, '.', '') }}"

                                            data-allowance-records='@json($dutySlip->working_sheet_allowance_records ?? [])'

                                            data-expense-records='@json($dutySlip->working_sheet_expense_records ?? [])'

                                            data-date="{{ optional($dutySlip->duty_date)->format('Y-m-d') }}"

                                            data-opening-meter="{{ $dutySlip->opening_meter ?? '' }}"

                                            data-closing-meter="{{ $dutySlip->closing_meter ?? '' }}"

                                            data-total-km="{{ $dutySlip->total_km ?? '' }}"

                                            data-start-time="{{ optional($dutySlip->start_time)->format('Y-m-d\TH:i:s') }}"

                                            data-end-time="{{ optional($dutySlip->end_time)->format('Y-m-d\TH:i:s') }}"

                                            {{ $selectedDutySlip ? 'selected' : '' }}>

                                            {{ $dutySlip->slip_no ?? 'Duty Slip #' . $dutySlip->id }}

                                            @if($driver)
                                                -
                                                {{ $driverName }}
                                            @endif

                                            @if($vehicle)
                                                -
                                                {{ $vehicleNumber }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                                @error('duty_slip_id')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>


                        {{-- Driver --}}
                        <div class="col-md-6">

                            <div class="form-group">

                                <label for="driver_id">
                                    <b>Driver</b>
                                    <span class="required">*</span>
                                </label>

                                {{-- Driver is automatically derived from Duty Slip --}}
                                <input
                                    type="hidden"
                                    name="driver_id"
                                    id="driver_id"
                                    value="{{ old('driver_id') }}">

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


                    {{-- Duty Slip Details --}}
                    <div
                        id="duty-slip-info"
                        class="duty-slip-info"
                        style="display:none;">

                        <div class="row">

                            {{-- Duty Slip --}}
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


                            {{-- Status --}}
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


                            {{-- Driver --}}
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


                            {{-- Vehicle --}}
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


                            {{-- Travel Request --}}
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
                        <b>Meter & Working Details</b>
                    </h5>

                    <hr>

                    <div class="row">

                        {{-- Opening Meter --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="opening_meter">
                                    <b>Opening Meter</b>
                                </label>

                                <input
                                    type="number"
                                    name="opening_meter"
                                    id="opening_meter"
                                    class="form-control decimal-input @error('opening_meter') is-invalid @enderror"
                                    value="{{ old('opening_meter') }}"
                                    placeholder="Enter Opening Meter"
                                    min="0"
                                    step="0.01">

                                @error('opening_meter')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>


                        {{-- Closing Meter --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="closing_meter">
                                    <b>Closing Meter</b>
                                </label>

                                <input
                                    type="number"
                                    name="closing_meter"
                                    id="closing_meter"
                                    class="form-control decimal-input @error('closing_meter') is-invalid @enderror"
                                    value="{{ old('closing_meter') }}"
                                    placeholder="Enter Closing Meter"
                                    min="0"
                                    step="0.01">

                                @error('closing_meter')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>


                        {{-- Total KM --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="total_km">
                                    <b>Total KM</b>
                                </label>

                                <input
                                    type="number"
                                    name="total_km"
                                    id="total_km"
                                    class="form-control decimal-input @error('total_km') is-invalid @enderror"
                                    value="{{ old('total_km') }}"
                                    placeholder="Auto calculated from meter"
                                    min="0"
                                    step="0.01"
                                    readonly>

                                <small class="text-muted">
                                    Closing Meter - Opening Meter
                                </small>

                                @error('total_km')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>


                        {{-- Total Hours --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="total_hours">
                                    <b>Total Hours</b>
                                </label>

                                <input
                                    type="number"
                                    name="total_hours"
                                    id="total_hours"
                                    class="form-control decimal-input @error('total_hours') is-invalid @enderror"
                                    value="{{ old('total_hours') }}"
                                    placeholder="Enter Total Hours"
                                    min="0"
                                    step="0.01">

                                @error('total_hours')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>


                        {{-- Overtime Hours --}}
                        <div class="col-md-4">

                            <div class="form-group">

                                <label for="overtime_hours">
                                    <b>Overtime Hours</b>
                                </label>

                                <input
                                    type="number"
                                    name="overtime_hours"
                                    id="overtime_hours"
                                    class="form-control decimal-input @error('overtime_hours') is-invalid @enderror"
                                    value="{{ old('overtime_hours', 0) }}"
                                    placeholder="Enter Overtime Hours"
                                    min="0"
                                    step="0.01">

                                @error('overtime_hours')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ===================================================== --}}
                {{-- AMOUNT DETAILS --}}
                {{-- ===================================================== --}}
                <div class="form-section">

                    <h5 class="section-title">
                        <b>Amount Details</b>
                    </h5>

                    <hr>

                    {{-- ===================================================== --}}
                    {{-- DRIVER ALLOWANCE / EXPENSE RECORD BREAKDOWN --}}
                    {{-- All statuses are displayed for admin review. Only --}}
                    {{-- Approved rows contribute to the amount inputs. --}}
                    {{-- ===================================================== --}}
                    <div class="row mb-4">

                        {{-- Driver Allowance Records --}}
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
                                                Select a Duty Slip to view allowance records.
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

                        {{-- Driver Expense Records --}}
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
                                                Select a Duty Slip to view expense records.
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

                            {{-- Base Amount --}}
                            <div class="col-md-4">

                                <div class="form-group">

                                    <label for="base_amount">
                                        <b>Base Amount</b>
                                    </label>

                                    <input
                                        type="number"
                                        name="base_amount"
                                        id="base_amount"
                                        class="form-control amount-input @error('base_amount') is-invalid @enderror"
                                        value="{{ old('base_amount', 0) }}"
                                        placeholder="0.00"
                                        min="0"
                                        step="0.01">

                                    @error('base_amount')
                                        <span class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>

                            </div>


                            {{-- Extra KM Amount --}}
                            <div class="col-md-4">

                                <div class="form-group">

                                    <label for="extra_km_amount">
                                        <b>Extra KM Amount</b>
                                    </label>

                                    <input
                                        type="number"
                                        name="extra_km_amount"
                                        id="extra_km_amount"
                                        class="form-control amount-input @error('extra_km_amount') is-invalid @enderror"
                                        value="{{ old('extra_km_amount', 0) }}"
                                        placeholder="0.00"
                                        min="0"
                                        step="0.01">

                                    @error('extra_km_amount')
                                        <span class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>

                            </div>


                            {{-- Overtime Amount --}}
                            <div class="col-md-4">

                                <div class="form-group">

                                    <label for="overtime_amount">
                                        <b>Overtime Amount</b>
                                    </label>

                                    <input
                                        type="number"
                                        name="overtime_amount"
                                        id="overtime_amount"
                                        class="form-control amount-input @error('overtime_amount') is-invalid @enderror"
                                        value="{{ old('overtime_amount', 0) }}"
                                        placeholder="0.00"
                                        min="0"
                                        step="0.01">

                                    @error('overtime_amount')
                                        <span class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>

                            </div>


                            {{-- Allowance Amount --}}
                            <div class="col-md-4">

                                <div class="form-group">

                                    <label for="allowance_amount">
                                        <b>Allowance Amount</b>
                                    </label>

                                    <input
                                        type="number"
                                        name="allowance_amount"
                                        id="allowance_amount"
                                        class="form-control amount-input @error('allowance_amount') is-invalid @enderror"
                                        value="{{ old('allowance_amount', 0) }}"
                                        placeholder="0.00"
                                        min="0"
                                        step="0.01"
                                        readonly>

                                    @error('allowance_amount')
                                        <span class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>

                            </div>


                            {{-- Expense Amount --}}
                            <div class="col-md-4">

                                <div class="form-group">

                                    <label for="expense_amount">
                                        <b>Expense Amount</b>
                                    </label>

                                    <input
                                        type="number"
                                        name="expense_amount"
                                        id="expense_amount"
                                        class="form-control amount-input @error('expense_amount') is-invalid @enderror"
                                        value="{{ old('expense_amount', 0) }}"
                                        placeholder="0.00"
                                        min="0"
                                        step="0.01"
                                        readonly>

                                    @error('expense_amount')
                                        <span class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>

                            </div>


                            {{-- Other Amount --}}
                            <div class="col-md-4">

                                <div class="form-group">

                                    <label for="other_amount">
                                        <b>Other Amount</b>
                                    </label>

                                    <input
                                        type="number"
                                        name="other_amount"
                                        id="other_amount"
                                        class="form-control amount-input @error('other_amount') is-invalid @enderror"
                                        value="{{ old('other_amount', 0) }}"
                                        placeholder="0.00"
                                        min="0"
                                        step="0.01">

                                    @error('other_amount')
                                        <span class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>

                            </div>


                            {{-- Total Amount --}}
                            <div class="col-md-8">

                                <div class="form-group total-amount-box">

                                    <label for="total_amount">
                                        <b>Total Amount</b>
                                    </label>

                                    <input
                                        type="number"
                                        name="total_amount"
                                        id="total_amount"
                                        class="form-control @error('total_amount') is-invalid @enderror"
                                        value="{{ old('total_amount', 0) }}"
                                        placeholder="0.00"
                                        min="0"
                                        step="0.01"
                                        readonly>

                                    <small class="text-muted">
                                        Base + Extra KM + Overtime + Allowance + Expense + Other
                                    </small>

                                    @error('total_amount')
                                        <span class="invalid-feedback d-block">
                                            <strong>{{ $message }}</strong>
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
                        <b>Remarks</b>
                    </h5>

                    <hr>

                    <div class="row">

                        <div class="col-md-12">

                            <div class="form-group">

                                <label for="remarks">
                                    <b>Remarks</b>
                                </label>

                                <textarea
                                    name="remarks"
                                    id="remarks"
                                    rows="5"
                                    maxlength="2000"
                                    class="form-control @error('remarks') is-invalid @enderror"
                                    placeholder="Enter remarks if required">{{ old('remarks') }}</textarea>

                                <small class="text-muted">
                                    Maximum 2000 characters.
                                </small>

                                @error('remarks')
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

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

                            <button
                                type="submit"
                                id="save-working-sheet"
                                class="btn btn-success">

                                <i class="fa fa-save"></i>
                                Save Working Sheet

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

    <script src="{{ asset('backend/assets/js/working-sheets/create.js') }}" defer></script>

@endpush
