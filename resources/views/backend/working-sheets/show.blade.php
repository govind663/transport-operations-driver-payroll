
@extends('backend.layouts.master')

@section('title')
    Working Sheet Details
@endsection

@push('styles')
<style>
    .working-sheet-detail-page {
        color: #344054;
    }

    .working-sheet-detail-page .detail-header {
        padding: 22px 24px;
        margin-bottom: 20px;
        border: 1px solid #e1eaf7;
        border-radius: 14px;
        background: linear-gradient(115deg, #fff, #f0f6ff);
    }

    .working-sheet-detail-page .detail-header h4 {
        margin-bottom: 6px;
        color: #2457a7;
        font-size: 21px;
        font-weight: 800;
    }

    .working-sheet-detail-page .detail-header p {
        margin: 0;
        color: #718096;
        font-size: 12px;
    }

    .working-sheet-detail-page .detail-card {
        margin-bottom: 20px;
        overflow: hidden;
        border: 1px solid #e7edf5;
        border-radius: 13px;
        background: #fff;
        box-shadow: 0 5px 18px rgba(26, 47, 84, .045);
    }

    .working-sheet-detail-page .detail-card-header {
        padding: 15px 20px;
        border-bottom: 1px solid #e7edf5;
        background: #f8faff;
        color: #2457a7;
        font-size: 14px;
        font-weight: 800;
    }

    .working-sheet-detail-page .detail-card-body {
        padding: 20px;
    }

    .working-sheet-detail-page .detail-item {
        margin-bottom: 18px;
    }

    .working-sheet-detail-page .detail-item label {
        display: block;
        margin-bottom: 5px;
        color: #8491a5;
        font-size: 10px;
        font-weight: 750;
        text-transform: uppercase;
    }

    .working-sheet-detail-page .detail-item strong {
        display: block;
        color: #344054;
        font-size: 13px;
        font-weight: 750;
        overflow-wrap: anywhere;
    }

    .working-sheet-detail-page .detail-total {
        padding: 18px;
        border: 1px solid #dce8f9;
        border-radius: 10px;
        background: #f1f6ff;
    }

    .working-sheet-detail-page .detail-total span {
        display: block;
        color: #667085;
        font-size: 11px;
    }

    .working-sheet-detail-page .detail-total strong {
        display: block;
        margin-top: 7px;
        color: #2457a7;
        font-size: 23px;
        font-weight: 850;
    }

    .working-sheet-detail-page .detail-table th {
        color: #475467;
        font-weight: 700;
        font-size: 13px;
    }

    .working-sheet-detail-page .detail-table td {
        color: #344054;
        font-size: 13px;
    }

    .working-sheet-detail-page .remarks-content {
        color: #475467;
        font-size: 13px;
        line-height: 1.7;
        overflow-wrap: anywhere;
    }

    @media (max-width: 767px) {
        .working-sheet-detail-page .detail-header {
            padding: 18px;
        }

        .working-sheet-detail-page .detail-card-body {
            padding: 15px;
        }
    }

    @media print {
        .left-side-bar,
        .header,
        .footer-wrap,
        .no-print {
            display: none !important;
        }

        .working-sheet-detail-page .detail-card {
            box-shadow: none !important;
            break-inside: avoid;
        }

        .working-sheet-detail-page .detail-header {
            background: #fff !important;
        }

        body {
            background: #fff !important;
        }
    }
</style>
@endpush

@section('content')

@php
    $authUser = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | Role Access
    |--------------------------------------------------------------------------
    */

    $isDriver = $authUser && $authUser->isDriver();

    $canViewWorkingSheet = $authUser && (
        $authUser->isAdmin()
        || $authUser->isOperations()
        || $authUser->isAccountant()
        || $isDriver
    );

    abort_unless($canViewWorkingSheet, 403, 'You are not authorized to view this working sheet.');

    /*
    |--------------------------------------------------------------------------
    | Related Records
    |--------------------------------------------------------------------------
    */

    $dutySlip = $workingSheet->dutySlip;

    $assignment = $dutySlip?->dutyAssignment;

    $driver = $workingSheet->driver
        ?? $dutySlip?->driver
        ?? $assignment?->driver;

    $vehicle = $dutySlip?->vehicle
        ?? $assignment?->vehicle;

    $travelRequest = $assignment?->travelRequest;

    $client = $travelRequest?->client;

    /*
    |--------------------------------------------------------------------------
    | Driver Ownership Verification
    |--------------------------------------------------------------------------
    */

    if ($isDriver) {
        $authenticatedDriver = $authUser->driver;

        abort_unless(
            $authenticatedDriver
                && $driver
                && (int) $authenticatedDriver->id === (int) $driver->id,
            403,
            'You are not authorized to view this working sheet.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Date Formatting
    |--------------------------------------------------------------------------
    */

    $workDate = $workingSheet->work_date
        ? \Illuminate\Support\Carbon::parse($workingSheet->work_date)->format('d-m-Y')
        : '-';

    $dutyStartTime = $dutySlip?->start_time
        ? \Illuminate\Support\Carbon::parse($dutySlip->start_time)->format('d-m-Y h:i A')
        : '-';

    $dutyEndTime = $dutySlip?->end_time
        ? \Illuminate\Support\Carbon::parse($dutySlip->end_time)->format('d-m-Y h:i A')
        : '-';

    /*
    |--------------------------------------------------------------------------
    | Display Values
    |--------------------------------------------------------------------------
    */

    $driverName = trim(
        ($driver?->first_name ?? '') . ' ' . ($driver?->last_name ?? '')
    );

    $vehicleNumber = $vehicle?->vehicle_number
        ?? $vehicle?->registration_number
        ?? '-';

    $clientName = $client?->company_name ?? '-';

    $status = ucfirst($workingSheet->status ?? 'N/A');

    $currency = function ($amount) {
        return '₹' . number_format((float) ($amount ?? 0), 2);
    };

    $number = function ($value) {
        return number_format((float) ($value ?? 0), 2);
    };
@endphp

<div class="pd-ltr-20 xs-pd-20-10 working-sheet-detail-page">

    <div class="min-height-200px">

        {{-- HEADER --}}

        <div class="detail-header">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h4>
                        <i class="fa fa-file-text-o mr-1"></i>
                        Working Sheet Details
                    </h4>

                    <p>
                        Complete driver-wise working sheet and duty information.
                    </p>

                </div>

                <div class="col-md-4 text-md-right mt-3 mt-md-0 no-print">

                    <button
                        type="button"
                        class="btn btn-dark btn-sm"
                        onclick="window.print()"
                    >
                        <i class="fa fa-print mr-1"></i>
                        Print
                    </button>

                    <a
                        href="{{ route('working-sheets.index') }}"
                        class="btn btn-secondary btn-sm"
                    >
                        <i class="fa fa-arrow-left mr-1"></i>
                        Back
                    </a>

                </div>

            </div>

        </div>

        {{-- WORKING SHEET INFORMATION --}}

        <div class="detail-card">

            <div class="detail-card-header">
                <i class="fa fa-info-circle mr-1"></i>
                Working Sheet Information
            </div>

            <div class="detail-card-body">

                <div class="row">

                    <div class="col-md-3 col-sm-6">
                        <div class="detail-item">
                            <label>Sheet Number</label>
                            <strong>{{ $workingSheet->sheet_no ?? '-' }}</strong>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="detail-item">
                            <label>Work Date</label>
                            <strong>{{ $workDate }}</strong>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="detail-item">
                            <label>Duty Slip Number</label>
                            <strong>{{ $dutySlip?->slip_no ?? '-' }}</strong>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="detail-item">
                            <label>Status</label>
                            <strong>
                                <span class="badge badge-secondary">
                                    {{ $status }}
                                </span>
                            </strong>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        {{-- DRIVER INFORMATION --}}

        <div class="detail-card">

            <div class="detail-card-header">
                <i class="fa fa-user mr-1"></i>
                Driver Information
            </div>

            <div class="detail-card-body">

                <div class="row">

                    <div class="col-md-4 col-sm-6">
                        <div class="detail-item">
                            <label>Driver Name</label>
                            <strong>
                                {{ $driverName ?: '-' }}
                            </strong>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="detail-item">
                            <label>Driver Code</label>
                            <strong>{{ $driver?->driver_code ?? '-' }}</strong>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="detail-item">
                            <label>Driver Mobile</label>
                            <strong>{{ $driver?->mobile ?? '-' }}</strong>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        {{-- VEHICLE AND CLIENT INFORMATION --}}

        <div class="detail-card">

            <div class="detail-card-header">
                <i class="fa fa-car mr-1"></i>
                Vehicle and Client Information
            </div>

            <div class="detail-card-body">

                <div class="row">

                    <div class="col-md-3 col-sm-6">
                        <div class="detail-item">
                            <label>Vehicle Number</label>
                            <strong>{{ $vehicleNumber }}</strong>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="detail-item">
                            <label>Manufacturer</label>
                            <strong>{{ $vehicle?->manufacturer ?? '-' }}</strong>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="detail-item">
                            <label>Vehicle Model</label>
                            <strong>{{ $vehicle?->model ?? '-' }}</strong>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="detail-item">
                            <label>Client</label>
                            <strong>{{ $clientName }}</strong>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="detail-item">
                            <label>Pickup Location</label>
                            <strong>
                                {{ $dutySlip?->pickup_location ?? '-' }}
                            </strong>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-item">
                            <label>Drop Location</label>
                            <strong>
                                {{ $dutySlip?->drop_location ?? '-' }}
                            </strong>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        {{-- METER AND WORKING DETAILS --}}

        <div class="detail-card">

            <div class="detail-card-header">
                <i class="fa fa-tachometer mr-1"></i>
                Meter and Working Details
            </div>

            <div class="detail-card-body">

                <div class="row">

                    <div class="col-md-3 col-sm-6">
                        <div class="detail-item">
                            <label>Opening Meter</label>
                            <strong>
                                {{ $number($workingSheet->opening_meter) }}
                            </strong>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="detail-item">
                            <label>Closing Meter</label>
                            <strong>
                                {{ $number($workingSheet->closing_meter) }}
                            </strong>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="detail-item">
                            <label>Total KM</label>
                            <strong>
                                {{ $number($workingSheet->total_km) }}
                            </strong>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="detail-item">
                            <label>Total Hours</label>
                            <strong>
                                {{ $number($workingSheet->total_hours) }}
                            </strong>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="detail-item">
                            <label>Overtime Hours</label>
                            <strong>
                                {{ $number($workingSheet->overtime_hours) }}
                            </strong>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="detail-item">
                            <label>Duty Start Time</label>
                            <strong>{{ $dutyStartTime }}</strong>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="detail-item">
                            <label>Duty End Time</label>
                            <strong>{{ $dutyEndTime }}</strong>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        {{-- FINANCIAL DETAILS --}}

        <div class="detail-card">

            <div class="detail-card-header">
                <i class="fa fa-money mr-1"></i>
                Financial Details
            </div>

            <div class="detail-card-body">

                <div class="table-responsive">

                    <table class="table table-bordered detail-table">

                        <tbody>

                            <tr>
                                <th>Base Amount</th>
                                <td class="text-right">
                                    {{ $currency($workingSheet->base_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th>Extra KM Amount</th>
                                <td class="text-right">
                                    {{ $currency($workingSheet->extra_km_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th>Overtime Amount</th>
                                <td class="text-right">
                                    {{ $currency($workingSheet->overtime_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th>Allowance Amount</th>
                                <td class="text-right">
                                    {{ $currency($workingSheet->allowance_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th>Expense Amount</th>
                                <td class="text-right">
                                    {{ $currency($workingSheet->expense_amount) }}
                                </td>
                            </tr>

                            <tr>
                                <th>Other Amount</th>
                                <td class="text-right">
                                    {{ $currency($workingSheet->other_amount) }}
                                </td>
                            </tr>

                            <tr class="font-weight-bold">
                                <th>Total Amount</th>
                                <td class="text-right">
                                    {{ $currency($workingSheet->total_amount) }}
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

                <div class="detail-total">

                    <span>Working Sheet Total Amount</span>

                    <strong>
                        {{ $currency($workingSheet->total_amount) }}
                    </strong>

                </div>

            </div>

        </div>

        {{-- REMARKS --}}

        <div class="detail-card">

            <div class="detail-card-header">
                <i class="fa fa-commenting-o mr-1"></i>
                Remarks
            </div>

            <div class="detail-card-body">

                <div class="remarks-content">
                    {{ $workingSheet->remarks ?: 'No remarks available.' }}
                </div>

            </div>

        </div>

    </div>

</div>

@endsection
