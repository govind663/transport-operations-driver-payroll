
@extends('backend.layouts.master')

@section('title')

    Duty Assignment Details

@endsection

@push('styles')

<style>

    .duty-details-page .detail-card {
        border-radius: 6px;
        margin-bottom: 25px;
    }

    .duty-details-page .detail-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #e5e5e5;
    }

    .duty-details-page .detail-card-body {
        padding: 20px;
    }

    .duty-details-page .detail-label {
        display: block;
        color: #6c757d;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 6px;
    }

    .duty-details-page .detail-value {
        color: #212529;
        font-size: 14px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .duty-details-page .detail-item {
        margin-bottom: 22px;
    }

    .duty-details-page .detail-item:last-child {
        margin-bottom: 0;
    }

    .duty-details-page .section-title {
        color: #023a85;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 0;
    }

    .duty-details-page .assignment-number {
        font-size: 18px;
        font-weight: 700;
        letter-spacing: 0.4px;
    }

    .duty-details-page .status-badge {
        font-size: 12px;
        padding: 8px 14px;
        border-radius: 30px;
        font-weight: 600;
    }

    .duty-details-page .info-note {
        background: #f8f9fa;
        border-left: 3px solid #023a85;
        padding: 12px 15px;
        border-radius: 4px;
        color: #555;
        font-size: 13px;
    }

    .duty-details-page .detail-divider {
        border-top: 1px solid #eeeeee;
        margin: 18px 0;
    }

    .duty-details-page .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    @media (max-width: 767px) {

        .duty-details-page .detail-card-body {
            padding: 15px;
        }

        .duty-details-page .assignment-number {
            font-size: 15px;
        }

    }

    @media print {

        .no-print,
        .page-header,
        .duty-details-page .action-buttons,
        .duty-details-page .info-note {
            display: none !important;
        }

        .card-box {
            box-shadow: none !important;
        }

        .duty-details-page .detail-card {
            break-inside: avoid;
        }

    }

</style>

@endpush

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | Authenticated User
    |--------------------------------------------------------------------------
    */

    $authUser = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | Role Detection
    |--------------------------------------------------------------------------
    */

    $isDriver = $authUser && $authUser->isDriver();

    $canViewManagementDetails = $authUser && (
        $authUser->isAdmin()
        || $authUser->isOperations()
        || $authUser->isAccountant()
    );

    /*
    |--------------------------------------------------------------------------
    | Duty Assignment Data
    |--------------------------------------------------------------------------
    */

    $travelRequest = $dutyAssignment->travelRequest;

    $driver = $dutyAssignment->driver;

    $vehicle = $dutyAssignment->vehicle;

    $dutySlip = $dutyAssignment->dutySlip;

    /*
    |--------------------------------------------------------------------------
    | Driver Name
    |--------------------------------------------------------------------------
    */

    $driverName = $driver
        ? trim(
            ($driver->first_name ?? '') . ' ' .
            ($driver->last_name ?? '')
        )
        : '-';

    /*
    |--------------------------------------------------------------------------
    | Vehicle Number
    |--------------------------------------------------------------------------
    */

    $vehicleNumber = $vehicle
        ? (
            $vehicle->registration_number
            ?? $vehicle->vehicle_number
            ?? $vehicle->name
            ?? '-'
        )
        : '-';

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    $status = $dutyAssignment->status ?? 'unknown';

    $statusClass = match ($status) {

        'completed' => 'badge-success',

        'assigned' => 'badge-primary',

        'accepted' => 'badge-info',

        'started' => 'badge-info',

        'pending' => 'badge-warning',

        'rejected' => 'badge-danger',

        'cancelled' => 'badge-danger',

        default => 'badge-secondary',

    };

    $statusIcon = match ($status) {

        'completed' => 'fa-check-circle',

        'assigned' => 'fa-user',

        'accepted' => 'fa-check',

        'started' => 'fa-spinner',

        'pending' => 'fa-clock-o',

        'rejected' => 'fa-ban',

        'cancelled' => 'fa-times-circle',

        default => 'fa-info-circle',

    };

    $statusLabel = ucfirst(
        str_replace('_', ' ', $status)
    );

    /*
    |--------------------------------------------------------------------------
    | Date Values
    |--------------------------------------------------------------------------
    */

    $travelFromDate = $travelRequest?->travel_from_date;

    $travelToDate = $travelRequest?->travel_to_date;

    $assignedAt = $dutyAssignment->assigned_at;

    $reportingTime = $dutyAssignment->reporting_time;

@endphp

<div class="pd-ltr-20 xs-pd-20-10 duty-details-page">

    <div class="min-height-200px">

        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="page-header no-print">

            <div class="row align-items-center">

                <div class="col-md-8 col-sm-12">

                    <div class="title">

                        <h4 class="text-blue">

                            Duty Assignment Details

                        </h4>

                        <p class="mb-0">

                            View complete information about this duty assignment.

                        </p>

                    </div>

                </div>

                <div class="col-md-4 col-sm-12 text-md-right mt-3 mt-md-0">

                    <div class="action-buttons justify-content-md-end">

                        <a
                            href="{{ route('duty-assignments.index') }}"
                            class="btn btn-secondary"
                        >

                            <i class="fa fa-arrow-left"></i>

                            Back

                        </a>

                        <button
                            type="button"
                            onclick="window.print()"
                            class="btn btn-primary"
                        >

                            <i class="fa fa-print"></i>

                            Print

                        </button>

                    </div>

                </div>

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- ASSIGNMENT SUMMARY --}}
        {{-- ========================================================= --}}

        <div class="card-box detail-card">

            <div class="detail-card-body">

                <div class="row align-items-center">

                    <div class="col-md-8">

                        <span class="detail-label">

                            Assignment Number

                        </span>

                        <div class="assignment-number text-dark">

                            {{ $dutyAssignment->assignment_no ?? '-' }}

                        </div>

                        <small class="text-muted">

                            Duty Assignment ID:

                            {{ $dutyAssignment->id }}

                        </small>

                    </div>

                    <div class="col-md-4 text-md-right mt-3 mt-md-0">

                        <span class="badge {{ $statusClass }} status-badge">

                            <i class="fa {{ $statusIcon }}"></i>

                            {{ $statusLabel }}

                        </span>

                    </div>

                </div>

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- DUTY INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="card-box detail-card">

            <div class="detail-card-header">

                <h5 class="section-title">

                    <i class="fa fa-file-text-o mr-2"></i>

                    Duty Information

                </h5>

            </div>

            <div class="detail-card-body">

                <div class="row">

                    {{-- ASSIGNMENT NUMBER --}}

                    <div class="col-md-4 col-sm-6 detail-item">

                        <span class="detail-label">

                            Assignment Number

                        </span>

                        <div class="detail-value">

                            {{ $dutyAssignment->assignment_no ?? '-' }}

                        </div>

                    </div>

                    {{-- STATUS --}}

                    <div class="col-md-4 col-sm-6 detail-item">

                        <span class="detail-label">

                            Duty Status

                        </span>

                        <div class="detail-value">

                            <span class="badge {{ $statusClass }}">

                                {{ $statusLabel }}

                            </span>

                        </div>

                    </div>

                    {{-- ASSIGNED AT --}}

                    <div class="col-md-4 col-sm-6 detail-item">

                        <span class="detail-label">

                            Assigned At

                        </span>

                        <div class="detail-value">

                            {{ $assignedAt
                                ? $assignedAt->format('d-m-Y h:i A')
                                : '-' }}

                        </div>

                    </div>

                    {{-- REPORTING TIME --}}

                    <div class="col-md-4 col-sm-6 detail-item">

                        <span class="detail-label">

                            Reporting Time

                        </span>

                        <div class="detail-value">

                            {{ $reportingTime
                                ? \Illuminate\Support\Carbon::parse($reportingTime)->format('h:i A')
                                : '-' }}

                        </div>

                    </div>

                    {{-- REPORTING LOCATION --}}

                    <div class="col-md-8 col-sm-12 detail-item">

                        <span class="detail-label">

                            Reporting Location

                        </span>

                        <div class="detail-value">

                            {{ $dutyAssignment->reporting_location ?? '-' }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- DRIVER INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="card-box detail-card">

            <div class="detail-card-header">

                <h5 class="section-title">

                    <i class="fa fa-user mr-2"></i>

                    Driver Information

                </h5>

            </div>

            <div class="detail-card-body">

                @if($driver)

                    <div class="row">

                        {{-- DRIVER NAME --}}

                        <div class="col-md-4 col-sm-6 detail-item">

                            <span class="detail-label">

                                Driver Name

                            </span>

                            <div class="detail-value">

                                {{ $driverName !== '' ? $driverName : '-' }}

                            </div>

                        </div>

                        {{-- DRIVER CODE --}}

                        <div class="col-md-4 col-sm-6 detail-item">

                            <span class="detail-label">

                                Driver Code

                            </span>

                            <div class="detail-value">

                                {{ $driver->driver_code ?? '-' }}

                            </div>

                        </div>

                        {{-- DRIVER MOBILE --}}

                        <div class="col-md-4 col-sm-6 detail-item">

                            <span class="detail-label">

                                Mobile Number

                            </span>

                            <div class="detail-value">

                                {{ $driver->mobile ?? '-' }}

                            </div>

                        </div>

                    </div>

                @else

                    <div class="text-muted">

                        <i class="fa fa-info-circle mr-1"></i>

                        No driver is currently linked to this assignment.

                    </div>

                @endif

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- VEHICLE INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="card-box detail-card">

            <div class="detail-card-header">

                <h5 class="section-title">

                    <i class="fa fa-car mr-2"></i>

                    Vehicle Information

                </h5>

            </div>

            <div class="detail-card-body">

                @if($vehicle)

                    <div class="row">

                        {{-- REGISTRATION NUMBER --}}

                        <div class="col-md-4 col-sm-6 detail-item">

                            <span class="detail-label">

                                Vehicle Number

                            </span>

                            <div class="detail-value">

                                {{ $vehicleNumber }}

                            </div>

                        </div>

                        {{-- MANUFACTURER --}}

                        <div class="col-md-4 col-sm-6 detail-item">

                            <span class="detail-label">

                                Manufacturer

                            </span>

                            <div class="detail-value">

                                {{ $vehicle->manufacturer ?? '-' }}

                            </div>

                        </div>

                        {{-- MODEL --}}

                        <div class="col-md-4 col-sm-6 detail-item">

                            <span class="detail-label">

                                Vehicle Model

                            </span>

                            <div class="detail-value">

                                {{ $vehicle->model ?? '-' }}

                            </div>

                        </div>

                    </div>

                @else

                    <div class="text-muted">

                        <i class="fa fa-info-circle mr-1"></i>

                        No vehicle is currently linked to this assignment.

                    </div>

                @endif

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- TRAVEL REQUEST INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="card-box detail-card">

            <div class="detail-card-header">

                <h5 class="section-title">

                    <i class="fa fa-map-marker mr-2"></i>

                    Travel Request Information

                </h5>

            </div>

            <div class="detail-card-body">

                @if($travelRequest)

                    <div class="row">

                        {{-- TRAVEL REQUEST ID --}}

                        <div class="col-md-4 col-sm-6 detail-item">

                            <span class="detail-label">

                                Travel Request ID

                            </span>

                            <div class="detail-value">

                                {{ $dutyAssignment->travel_request_id ?? '-' }}

                            </div>

                        </div>

                        {{-- TRAVEL FROM DATE --}}

                        <div class="col-md-4 col-sm-6 detail-item">

                            <span class="detail-label">

                                Travel From Date

                            </span>

                            <div class="detail-value">

                                {{ $travelFromDate
                                    ? \Illuminate\Support\Carbon::parse($travelFromDate)->format('d-m-Y')
                                    : '-' }}

                            </div>

                        </div>

                        {{-- TRAVEL TO DATE --}}

                        <div class="col-md-4 col-sm-6 detail-item">

                            <span class="detail-label">

                                Travel To Date

                            </span>

                            <div class="detail-value">

                                {{ $travelToDate
                                    ? \Illuminate\Support\Carbon::parse($travelToDate)->format('d-m-Y')
                                    : '-' }}

                            </div>

                        </div>

                        {{-- COMPANY NAME --}}

                        <div class="col-md-6 col-sm-12 detail-item">

                            <span class="detail-label">

                                Client / Company

                            </span>

                            <div class="detail-value">

                                {{ $travelRequest->company_name ?? '-' }}

                            </div>

                        </div>

                        {{-- TRAVEL REQUEST STATUS --}}

                        <div class="col-md-6 col-sm-12 detail-item">

                            <span class="detail-label">

                                Travel Request Status

                            </span>

                            <div class="detail-value">

                                {{ isset($travelRequest->status)
                                    ? ucfirst(str_replace('_', ' ', $travelRequest->status))
                                    : '-' }}

                            </div>

                        </div>

                    </div>

                @else

                    <div class="text-muted">

                        <i class="fa fa-info-circle mr-1"></i>

                        No travel request is linked to this assignment.

                    </div>

                @endif

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- REMARKS --}}
        {{-- ========================================================= --}}

        <div class="card-box detail-card">

            <div class="detail-card-header">

                <h5 class="section-title">

                    <i class="fa fa-commenting-o mr-2"></i>

                    Duty Remarks

                </h5>

            </div>

            <div class="detail-card-body">

                @if(!empty($dutyAssignment->remarks))

                    <div class="detail-value" style="font-weight: 400;">

                        {{ $dutyAssignment->remarks }}

                    </div>

                @else

                    <span class="text-muted">

                        No remarks available for this duty.

                    </span>

                @endif

            </div>

        </div>

        {{-- ========================================================= --}}
        {{-- DUTY SLIP INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="card-box detail-card">

            <div class="detail-card-header">

                <h5 class="section-title">

                    <i class="fa fa-file-text mr-2"></i>

                    Duty Slip Information

                </h5>

            </div>

            <div class="detail-card-body">

                @if($dutySlip)

                    <div class="row">

                        <div class="col-md-6 col-sm-12 detail-item">

                            <span class="detail-label">

                                Duty Slip ID

                            </span>

                            <div class="detail-value">

                                {{ $dutySlip->id }}

                            </div>

                        </div>

                        <div class="col-md-6 col-sm-12 detail-item">

                            <span class="detail-label">

                                Linked Assignment ID

                            </span>

                            <div class="detail-value">

                                {{ $dutyAssignment->id }}

                            </div>

                        </div>

                    </div>

                    <div class="info-note">

                        <i class="fa fa-check-circle mr-1"></i>

                        A duty slip is linked to this assignment.

                    </div>

                @else

                    <div class="text-muted">

                        <i class="fa fa-info-circle mr-1"></i>

                        No duty slip has been created for this assignment yet.

                    </div>

                @endif

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- ASSIGNMENT AUDIT INFORMATION --}}
        {{-- ADMIN / OPERATIONS / ACCOUNTANT ONLY --}}
        {{-- ========================================================= --}}

        @if($canViewManagementDetails)

            <div class="card-box detail-card">

                <div class="detail-card-header">

                    <h5 class="section-title">

                        <i class="fa fa-history mr-2"></i>

                        Assignment Audit Information

                    </h5>

                </div>

                <div class="detail-card-body">

                    <div class="row">

                        {{-- ASSIGNED BY --}}

                        <div class="col-md-4 col-sm-6 detail-item">

                            <span class="detail-label">

                                Assigned By

                            </span>

                            <div class="detail-value">

                                {{ $dutyAssignment->assignedBy?->name ?? '-' }}

                            </div>

                        </div>

                        {{-- CREATED BY --}}

                        <div class="col-md-4 col-sm-6 detail-item">

                            <span class="detail-label">

                                Created By

                            </span>

                            <div class="detail-value">

                                {{ $dutyAssignment->createdBy?->name ?? '-' }}

                            </div>

                        </div>

                        {{-- UPDATED BY --}}

                        <div class="col-md-4 col-sm-6 detail-item">

                            <span class="detail-label">

                                Last Updated By

                            </span>

                            <div class="detail-value">

                                {{ $dutyAssignment->updatedBy?->name ?? '-' }}

                            </div>

                        </div>

                    </div>

                    <div class="detail-divider"></div>

                    <div class="row">

                        {{-- CREATED AT --}}

                        <div class="col-md-6 col-sm-6 detail-item">

                            <span class="detail-label">

                                Created At

                            </span>

                            <div class="detail-value">

                                {{ $dutyAssignment->created_at
                                    ? $dutyAssignment->created_at->format('d-m-Y h:i A')
                                    : '-' }}

                            </div>

                        </div>

                        {{-- UPDATED AT --}}

                        <div class="col-md-6 col-sm-6 detail-item">

                            <span class="detail-label">

                                Last Updated At

                            </span>

                            <div class="detail-value">

                                {{ $dutyAssignment->updated_at
                                    ? $dutyAssignment->updated_at->format('d-m-Y h:i A')
                                    : '-' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        @endif

        {{-- ========================================================= --}}
        {{-- DRIVER INFORMATION NOTE --}}
        {{-- ========================================================= --}}

        @if($isDriver)

            <div class="info-note mb-30">

                <i class="fa fa-info-circle mr-1"></i>

                You are viewing the details of your duty assignment.

            </div>

        @endif

        {{-- ========================================================= --}}
        {{-- BOTTOM ACTIONS --}}
        {{-- ========================================================= --}}

        <div class="card-box detail-card no-print">

            <div class="detail-card-body">

                <div class="d-flex justify-content-between align-items-center flex-wrap">

                    <div class="mb-2">

                        <span class="text-muted">

                            Assignment:

                        </span>

                        <strong class="text-dark">

                            {{ $dutyAssignment->assignment_no ?? '-' }}

                        </strong>

                    </div>

                    <div class="action-buttons">

                        <a
                            href="{{ route('duty-assignments.index') }}"
                            class="btn btn-secondary"
                        >

                            <i class="fa fa-arrow-left"></i>

                            Back to Duties

                        </a>

                        <button
                            type="button"
                            onclick="window.print()"
                            class="btn btn-primary"
                        >

                            <i class="fa fa-print"></i>

                            Print Details

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ========================================================= --}}
    {{-- FOOTER --}}
    {{-- ========================================================= --}}

    <x-backend.footer />

</div>

@endsection
