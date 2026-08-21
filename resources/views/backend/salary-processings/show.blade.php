@extends('backend.layouts.master')

@section('title')
    Salary Processing Details
@endsection

@push('styles')
    <style>
        .salary-header-card {
            border: 0;
            overflow: hidden;
            border-radius: 8px;
        }

        .salary-header {
            padding: 25px;
            background: linear-gradient(135deg, #1b00ff 0%, #3b28cc 100%);
            color: #ffffff;
        }

        .salary-header h4,
        .salary-header p {
            color: #ffffff;
        }

        .salary-header .badge {
            font-size: 12px;
            padding: 7px 12px;
        }

        .summary-card {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 18px;
            height: 100%;
            background: #ffffff;
        }

        .summary-card .summary-icon {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f0f3ff;
            color: #1b00ff;
            font-size: 18px;
        }

        .summary-card .summary-label {
            color: #6b7280;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .summary-card .summary-value {
            color: #111827;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 0;
        }

        .section-card {
            border: 0;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        }

        .section-title {
            padding: 18px 20px;
            border-bottom: 1px solid #eeeeee;
        }

        .section-title h4 {
            margin-bottom: 0;
            font-size: 16px;
            font-weight: 600;
            color: #1b00ff;
        }

        .section-body {
            padding: 20px;
        }

        .detail-item {
            margin-bottom: 18px;
        }

        .detail-item:last-child {
            margin-bottom: 0;
        }

        .detail-label {
            color: #7b8191;
            font-size: 12px;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        .detail-value {
            color: #1f2937;
            font-size: 14px;
            font-weight: 600;
            word-break: break-word;
        }

        .driver-avatar {
            width: 62px;
            height: 62px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #eef1f6;
        }

        .driver-placeholder {
            width: 62px;
            height: 62px;
            border-radius: 50%;
            background: #f0f3ff;
            color: #1b00ff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            border: 3px solid #eef1f6;
        }

        .driver-name {
            font-size: 17px;
            font-weight: 700;
            color: #1f2937;
        }

        .driver-code {
            font-size: 12px;
            color: #7b8191;
        }

        .amount-table td {
            padding: 11px 8px;
            vertical-align: middle;
        }

        .amount-table .amount-label {
            color: #6b7280;
        }

        .amount-table .amount-value {
            text-align: right;
            font-weight: 600;
            color: #1f2937;
            white-space: nowrap;
        }

        .amount-table .total-row {
            border-top: 1px solid #dee2e6;
            font-weight: 700;
        }

        .amount-table .gross-row {
            background: #f7f9ff;
        }

        .amount-table .net-row {
            background: #f0fff5;
        }

        .net-salary-box {
            padding: 20px;
            border-radius: 8px;
            background: #f0fff5;
            border: 1px solid #c9f0d8;
        }

        .net-salary-label {
            color: #4b6352;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .net-salary-value {
            color: #16833b;
            font-size: 28px;
            font-weight: 800;
        }

        .status-badge {
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-draft {
            background: #fff4d6;
            color: #9a6700;
        }

        .status-processed {
            background: #e8f1ff;
            color: #1d5dbf;
        }

        .status-approved {
            background: #e9e7ff;
            color: #5848c7;
        }

        .status-paid {
            background: #e6f8ed;
            color: #16833b;
        }

        .status-cancelled {
            background: #ffe8e8;
            color: #c62828;
        }

        .timeline-item {
            position: relative;
            padding-left: 32px;
            padding-bottom: 20px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 7px;
            top: 18px;
            bottom: -2px;
            width: 1px;
            background: #dee2e6;
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-dot {
            position: absolute;
            left: 0;
            top: 3px;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background: #1b00ff;
            border: 3px solid #e7e9ff;
        }

        .timeline-title {
            font-weight: 600;
            color: #1f2937;
            font-size: 13px;
        }

        .timeline-meta {
            color: #7b8191;
            font-size: 12px;
        }

        .remarks-box {
            background: #f8f9fa;
            border-left: 4px solid #1b00ff;
            padding: 15px;
            border-radius: 4px;
            color: #4b5563;
            line-height: 1.7;
        }

        .action-buttons .btn {
            margin-left: 5px;
        }

        @media (max-width: 767px) {
            .salary-header {
                padding: 18px;
            }

            .action-buttons {
                margin-top: 15px;
            }

            .action-buttons .btn {
                margin-left: 0;
                margin-right: 5px;
                margin-bottom: 5px;
            }

            .net-salary-value {
                font-size: 24px;
            }
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

            <div class="row align-items-center">

                <div class="col-md-7 col-sm-12">

                    <h4 class="text-blue">
                        Salary Processing Details
                    </h4>

                    <p class="mb-0">
                        View complete monthly salary processing information.
                    </p>

                </div>

                <div class="col-md-5 col-sm-12 text-right action-buttons">

                    <a href="{{ route('salary-processing.index') }}"
                       class="btn btn-secondary">

                        <i class="fa fa-arrow-left"></i>

                        Back
                    </a>

                    <a href="{{ route('salary-processing.edit', $salaryProcessing->id) }}"
                       class="btn btn-warning">

                        <i class="dw dw-pencil-1"></i>

                        Edit
                    </a>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- MAIN HEADER CARD --}}
        {{-- ========================================================= --}}

        <div class="card-box salary-header-card mb-30">

            <div class="salary-header">

                <div class="row align-items-center">

                    <div class="col-md-8">

                        <div class="d-flex align-items-center">

                            @php

                                $driver = $salaryProcessing->driver;

                                $driverName = trim(
                                    ($driver->first_name ?? '') . ' ' .
                                    ($driver->last_name ?? '')
                                );

                                if ($driverName === '') {
                                    $driverName = $driver->name ?? 'Driver';
                                }

                            @endphp


                            @if(!empty($driver?->profile_image))

                                <img
                                    src="{{ asset('storage/' . $driver->profile_image) }}"
                                    alt="{{ $driverName }}"
                                    class="driver-avatar mr-3"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';"
                                >

                                <span class="driver-placeholder mr-3"
                                      style="display:none;">

                                    <i class="fa fa-user"></i>

                                </span>

                            @else

                                <span class="driver-placeholder mr-3">

                                    <i class="fa fa-user"></i>

                                </span>

                            @endif


                            <div>

                                <h4 class="mb-1">
                                    {{ $driverName }}
                                </h4>

                                <p class="mb-1">

                                    Driver Code:

                                    <strong>
                                        {{ $driver->driver_code ?? '-' }}
                                    </strong>

                                </p>

                                <p class="mb-0">

                                    Salary Period:

                                    <strong>
                                        {{ $salaryProcessing->salary_period ?? '-' }}
                                    </strong>

                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-4 text-md-right mt-3 mt-md-0">

                        @php

                            $status = $salaryProcessing->status ?? 'draft';

                            $statusClass = match ($status) {

                                'draft' =>
                                    'status-draft',

                                'processed' =>
                                    'status-processed',

                                'approved' =>
                                    'status-approved',

                                'paid' =>
                                    'status-paid',

                                'cancelled' =>
                                    'status-cancelled',

                                default =>
                                    'status-draft',

                            };

                        @endphp

                        <span class="status-badge {{ $statusClass }}">

                            <i class="fa fa-circle mr-1"
                               style="font-size:7px;"></i>

                            {{ ucfirst($status) }}

                        </span>

                        <div class="mt-2">

                            <small>
                                Processing ID:
                                <strong>#{{ $salaryProcessing->id }}</strong>
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- SALARY SUMMARY --}}
        {{-- ========================================================= --}}

        <div class="row mb-30">

            {{-- Gross Salary --}}
            <div class="col-lg-3 col-md-6 mb-20">

                <div class="summary-card">

                    <div class="d-flex align-items-center mb-3">

                        <span class="summary-icon mr-3">
                            <i class="fa fa-money"></i>
                        </span>

                        <div>
                            <div class="summary-label">
                                Gross Salary
                            </div>

                            <p class="summary-value">
                                ₹{{ number_format((float) ($salaryProcessing->gross_salary ?? 0), 2) }}
                            </p>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Deductions --}}
            <div class="col-lg-3 col-md-6 mb-20">

                <div class="summary-card">

                    <div class="d-flex align-items-center mb-3">

                        <span class="summary-icon mr-3">
                            <i class="fa fa-minus-circle"></i>
                        </span>

                        <div>
                            <div class="summary-label">
                                Total Deductions
                            </div>

                            <p class="summary-value">
                                ₹{{ number_format((float) ($salaryProcessing->total_deductions ?? 0), 2) }}
                            </p>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Net Salary --}}
            <div class="col-lg-3 col-md-6 mb-20">

                <div class="summary-card">

                    <div class="d-flex align-items-center mb-3">

                        <span class="summary-icon mr-3">
                            <i class="fa fa-check-circle"></i>
                        </span>

                        <div>
                            <div class="summary-label">
                                Net Salary
                            </div>

                            <p class="summary-value text-success">
                                ₹{{ number_format((float) ($salaryProcessing->net_salary ?? 0), 2) }}
                            </p>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Overtime --}}
            <div class="col-lg-3 col-md-6 mb-20">

                <div class="summary-card">

                    <div class="d-flex align-items-center mb-3">

                        <span class="summary-icon mr-3">
                            <i class="fa fa-clock-o"></i>
                        </span>

                        <div>
                            <div class="summary-label">
                                Overtime Hours
                            </div>

                            <p class="summary-value">
                                {{ number_format((float) ($salaryProcessing->overtime_hours ?? 0), 2) }}
                                <small class="text-muted">hrs</small>
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- DRIVER + SALARY PERIOD --}}
        {{-- ========================================================= --}}

        <div class="row">

            <div class="col-lg-6">

                <div class="card-box section-card">

                    <div class="section-title">

                        <h4>
                            <i class="fa fa-user mr-2"></i>
                            Driver Information
                        </h4>

                    </div>

                    <div class="section-body">

                        <div class="row">

                            <div class="col-md-6">

                                <div class="detail-item">

                                    <div class="detail-label">
                                        Driver Name
                                    </div>

                                    <div class="detail-value">
                                        {{ $driverName }}
                                    </div>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="detail-item">

                                    <div class="detail-label">
                                        Driver Code
                                    </div>

                                    <div class="detail-value">
                                        {{ $driver->driver_code ?? '-' }}
                                    </div>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="detail-item">

                                    <div class="detail-label">
                                        Mobile
                                    </div>

                                    <div class="detail-value">
                                        {{ $driver->mobile ?? '-' }}
                                    </div>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="detail-item">

                                    <div class="detail-label">
                                        Role
                                    </div>

                                    <div class="detail-value">

                                        <span class="badge badge-primary">
                                            {{ ucfirst($salaryProcessing->role ?? 'driver') }}
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-lg-6">

                <div class="card-box section-card">

                    <div class="section-title">

                        <h4>
                            <i class="fa fa-calendar mr-2"></i>
                            Salary Period
                        </h4>

                    </div>

                    <div class="section-body">

                        <div class="row">

                            <div class="col-md-6">

                                <div class="detail-item">

                                    <div class="detail-label">
                                        Salary Month
                                    </div>

                                    <div class="detail-value">
                                        {{ $salaryProcessing->salary_period ?? '-' }}
                                    </div>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="detail-item">

                                    <div class="detail-label">
                                        Salary Year
                                    </div>

                                    <div class="detail-value">
                                        {{ $salaryProcessing->salary_year ?? '-' }}
                                    </div>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="detail-item">

                                    <div class="detail-label">
                                        Period From
                                    </div>

                                    <div class="detail-value">
                                        {{ $salaryProcessing->period_from?->format('d M Y') ?? '-' }}
                                    </div>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="detail-item">

                                    <div class="detail-label">
                                        Period To
                                    </div>

                                    <div class="detail-value">
                                        {{ $salaryProcessing->period_to?->format('d M Y') ?? '-' }}
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- ATTENDANCE / WORKING --}}
        {{-- ========================================================= --}}

        <div class="card-box section-card">

            <div class="section-title">

                <h4>
                    <i class="fa fa-calendar-check-o mr-2"></i>
                    Attendance & Working Summary
                </h4>

            </div>

            <div class="section-body">

                <div class="row">

                    <div class="col-lg-3 col-md-6">

                        <div class="detail-item">

                            <div class="detail-label">
                                Total Working Days
                            </div>

                            <div class="detail-value">
                                {{ number_format((float) ($salaryProcessing->total_days ?? 0), 2) }}
                            </div>

                        </div>

                    </div>


                    <div class="col-lg-3 col-md-6">

                        <div class="detail-item">

                            <div class="detail-label">
                                Present Days
                            </div>

                            <div class="detail-value text-success">
                                {{ number_format((float) ($salaryProcessing->present_days ?? 0), 2) }}
                            </div>

                        </div>

                    </div>


                    <div class="col-lg-3 col-md-6">

                        <div class="detail-item">

                            <div class="detail-label">
                                Absent Days
                            </div>

                            <div class="detail-value text-danger">
                                {{ number_format((float) ($salaryProcessing->absent_days ?? 0), 2) }}
                            </div>

                        </div>

                    </div>


                    <div class="col-lg-3 col-md-6">

                        <div class="detail-item">

                            <div class="detail-label">
                                Leave Days
                            </div>

                            <div class="detail-value text-warning">
                                {{ number_format((float) ($salaryProcessing->leave_days ?? 0), 2) }}
                            </div>

                        </div>

                    </div>


                    <div class="col-lg-6">

                        <div class="detail-item">

                            <div class="detail-label">
                                Total Working Hours
                            </div>

                            <div class="detail-value">
                                {{ number_format((float) ($salaryProcessing->total_hours ?? 0), 2) }}
                                Hours
                            </div>

                        </div>

                    </div>


                    <div class="col-lg-6">

                        <div class="detail-item">

                            <div class="detail-label">
                                Overtime Hours
                            </div>

                            <div class="detail-value">
                                {{ number_format((float) ($salaryProcessing->overtime_hours ?? 0), 2) }}
                                Hours
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- EARNINGS + DEDUCTIONS --}}
        {{-- ========================================================= --}}

        <div class="row">

            {{-- Earnings --}}
            <div class="col-lg-6">

                <div class="card-box section-card">

                    <div class="section-title">

                        <h4>
                            <i class="fa fa-plus-circle mr-2"></i>
                            Earnings
                        </h4>

                    </div>

                    <div class="section-body">

                        <table class="table table-borderless amount-table mb-0">

                            <tbody>

                                <tr>
                                    <td class="amount-label">
                                        Basic Salary
                                    </td>

                                    <td class="amount-value">
                                        ₹{{ number_format((float) ($salaryProcessing->basic_salary ?? 0), 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="amount-label">
                                        Allowance
                                    </td>

                                    <td class="amount-value">
                                        ₹{{ number_format((float) ($salaryProcessing->allowance_amount ?? 0), 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="amount-label">
                                        Overtime
                                    </td>

                                    <td class="amount-value">
                                        ₹{{ number_format((float) ($salaryProcessing->overtime_amount ?? 0), 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="amount-label">
                                        Bonus
                                    </td>

                                    <td class="amount-value">
                                        ₹{{ number_format((float) ($salaryProcessing->bonus_amount ?? 0), 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="amount-label">
                                        Other Earnings
                                    </td>

                                    <td class="amount-value">
                                        ₹{{ number_format((float) ($salaryProcessing->other_earnings ?? 0), 2) }}
                                    </td>
                                </tr>

                                <tr class="total-row gross-row">

                                    <td>
                                        Gross Salary
                                    </td>

                                    <td class="amount-value text-primary">
                                        ₹{{ number_format((float) ($salaryProcessing->gross_salary ?? 0), 2) }}
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- Deductions --}}
            <div class="col-lg-6">

                <div class="card-box section-card">

                    <div class="section-title">

                        <h4>
                            <i class="fa fa-minus-circle mr-2"></i>
                            Deductions
                        </h4>

                    </div>

                    <div class="section-body">

                        <table class="table table-borderless amount-table mb-0">

                            <tbody>

                                <tr>
                                    <td class="amount-label">
                                        Advance
                                    </td>

                                    <td class="amount-value">
                                        ₹{{ number_format((float) ($salaryProcessing->advance_amount ?? 0), 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="amount-label">
                                        Other Deduction
                                    </td>

                                    <td class="amount-value">
                                        ₹{{ number_format((float) ($salaryProcessing->deduction_amount ?? 0), 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="amount-label">
                                        Other Deductions
                                    </td>

                                    <td class="amount-value">
                                        ₹{{ number_format((float) ($salaryProcessing->other_deductions ?? 0), 2) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class="amount-label">
                                        Total Deductions
                                    </td>

                                    <td class="amount-value text-danger">
                                        ₹{{ number_format((float) ($salaryProcessing->total_deductions ?? 0), 2) }}
                                    </td>
                                </tr>

                                <tr class="total-row net-row">

                                    <td>
                                        Net Salary
                                    </td>

                                    <td class="amount-value text-success">

                                        ₹{{ number_format((float) ($salaryProcessing->net_salary ?? 0), 2) }}

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- NET PAYABLE --}}
        {{-- ========================================================= --}}

        <div class="card-box section-card">

            <div class="section-body">

                <div class="row align-items-center">

                    <div class="col-md-7">

                        <h4 class="mb-2 text-blue">
                            Final Salary Payable
                        </h4>

                        <p class="mb-0 text-muted">

                            Final payable amount after all earnings
                            and applicable deductions.

                        </p>

                    </div>

                    <div class="col-md-5 mt-3 mt-md-0">

                        <div class="net-salary-box text-md-right">

                            <div class="net-salary-label">
                                Net Salary
                            </div>

                            <div class="net-salary-value">

                                ₹{{ number_format((float) ($salaryProcessing->net_salary ?? 0), 2) }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- REMARKS --}}
        {{-- ========================================================= --}}

        @if(!empty($salaryProcessing->remarks))

            <div class="card-box section-card">

                <div class="section-title">

                    <h4>
                        <i class="fa fa-commenting-o mr-2"></i>
                        Remarks
                    </h4>

                </div>

                <div class="section-body">

                    <div class="remarks-box">

                        {!! nl2br(e($salaryProcessing->remarks)) !!}

                    </div>

                </div>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- AUDIT / PROCESSING HISTORY --}}
        {{-- ========================================================= --}}

        <div class="card-box section-card">

            <div class="section-title">

                <h4>
                    <i class="fa fa-history mr-2"></i>
                    Processing & Audit Information
                </h4>

            </div>

            <div class="section-body">

                <div class="timeline">

                    {{-- Created --}}
                    <div class="timeline-item">

                        <span class="timeline-dot"></span>

                        <div class="timeline-title">
                            Salary Processing Created
                        </div>

                        <div class="timeline-meta">

                            {{ $salaryProcessing->createdBy?->name ?? 'System' }}

                            @if($salaryProcessing->created_at)
                                ·
                                {{ $salaryProcessing->created_at->format('d M Y, h:i A') }}
                            @endif

                        </div>

                    </div>


                    {{-- Processed --}}
                    @if($salaryProcessing->processedBy)

                        <div class="timeline-item">

                            <span class="timeline-dot"></span>

                            <div class="timeline-title">
                                Salary Processed
                            </div>

                            <div class="timeline-meta">

                                {{ $salaryProcessing->processedBy->name }}

                                @if($salaryProcessing->updated_at)
                                    ·
                                    {{ $salaryProcessing->updated_at->format('d M Y, h:i A') }}
                                @endif

                            </div>

                        </div>

                    @endif


                    {{-- Approved --}}
                    @if($salaryProcessing->approvedBy)

                        <div class="timeline-item">

                            <span class="timeline-dot"></span>

                            <div class="timeline-title">
                                Salary Approved
                            </div>

                            <div class="timeline-meta">

                                {{ $salaryProcessing->approvedBy->name }}

                            </div>

                        </div>

                    @endif


                    {{-- Updated --}}
                    @if($salaryProcessing->updatedBy)

                        <div class="timeline-item">

                            <span class="timeline-dot"></span>

                            <div class="timeline-title">
                                Last Updated
                            </div>

                            <div class="timeline-meta">

                                {{ $salaryProcessing->updatedBy->name }}

                                @if($salaryProcessing->updated_at)
                                    ·
                                    {{ $salaryProcessing->updated_at->format('d M Y, h:i A') }}
                                @endif

                            </div>

                        </div>

                    @endif


                    {{-- Deleted --}}
                    @if($salaryProcessing->deletedBy)

                        <div class="timeline-item">

                            <span class="timeline-dot"
                                  style="background:#dc3545;"></span>

                            <div class="timeline-title text-danger">
                                Salary Processing Deleted
                            </div>

                            <div class="timeline-meta">

                                {{ $salaryProcessing->deletedBy->name }}

                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- FOOTER ACTIONS --}}
        {{-- ========================================================= --}}

        <div class="card-box section-card">

            <div class="section-body">

                <div class="d-flex justify-content-between align-items-center flex-wrap">

                    <div>

                        <strong>
                            Salary Processing #{{ $salaryProcessing->id }}
                        </strong>

                        <span class="text-muted ml-2">
                            {{ $salaryProcessing->salary_period ?? '-' }}
                        </span>

                    </div>

                    <div class="mt-2 mt-md-0">

                        <a href="{{ route('salary-processing.index') }}"
                           class="btn btn-secondary">

                            <i class="fa fa-list"></i>

                            Salary Processing List

                        </a>

                        <a href="{{ route('salary-processing.edit', $salaryProcessing->id) }}"
                           class="btn btn-warning">

                            <i class="dw dw-pencil-1"></i>

                            Edit Processing

                        </a>

                    </div>

                </div>

            </div>

        </div>


        <x-backend.footer />

    </div>

</div>

@endsection