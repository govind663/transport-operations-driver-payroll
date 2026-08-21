@extends('backend.layouts.app')

@section('title')
    Salary Slip - {{ $salarySlip->slip_number }} - {{ $salarySlip->driver->first_name }} {{ $salarySlip->driver->last_name }}
@endsection

@push('styles')

<style>
    /* ============================================================
       SALARY SLIP PAGE
    ============================================================ */

    .salary-slip-page {
        background: #f3f5f8;
        min-height: 100vh;
    }

    .salary-slip-toolbar {
        margin-bottom: 20px;
    }

    /* ============================================================
       A4 SALARY SLIP
    ============================================================ */

    .salary-slip-paper {
        width: 210mm;
        min-height: 297mm;
        margin: 0 auto;
        background: #ffffff;
        color: #1f2937;
        box-shadow: 0 8px 30px rgba(15, 23, 42, 0.10);
        border: 1px solid #dfe3e8;
        position: relative;
        overflow: hidden;
    }

    .salary-slip-inner {
        padding: 14mm;
    }

    /* ============================================================
       HEADER
    ============================================================ */

    .salary-header {
        border-bottom: 3px solid #1d4ed8;
        padding-bottom: 12px;
        margin-bottom: 16px;
    }

    .company-logo {
        max-width: 210px;
        max-height: 75px;
        object-fit: contain;
    }

    .company-name {
        font-size: 21px;
        font-weight: 800;
        color: #0f2b78;
        letter-spacing: .3px;
        margin-bottom: 2px;
    }

    .company-address {
        font-size: 10px;
        color: #6b7280;
        line-height: 1.5;
    }

    .salary-slip-title {
        text-align: right;
    }

    .salary-slip-title h1 {
        font-size: 25px;
        font-weight: 800;
        color: #111827;
        margin: 0;
        letter-spacing: .5px;
    }

    .salary-slip-title .subtitle {
        font-size: 11px;
        color: #6b7280;
        margin-top: 4px;
    }

    .slip-number {
        display: inline-block;
        margin-top: 8px;
        padding: 5px 10px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        color: #374151;
        background: #f9fafb;
    }

    /* ============================================================
       DOCUMENT META
    ============================================================ */

    .document-meta {
        border: 1px solid #d9dee7;
        background: #f8fafc;
        margin-bottom: 14px;
    }

    .document-meta-cell {
        padding: 9px 12px;
        border-right: 1px solid #d9dee7;
    }

    .document-meta-cell:last-child {
        border-right: 0;
    }

    .meta-label {
        font-size: 9px;
        text-transform: uppercase;
        color: #6b7280;
        letter-spacing: .5px;
        margin-bottom: 2px;
    }

    .meta-value {
        font-size: 11px;
        font-weight: 700;
        color: #111827;
    }

    /* ============================================================
       SECTION TITLE
    ============================================================ */

    .slip-section {
        margin-top: 14px;
    }

    .section-heading {
        background: #0f2b78;
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        padding: 7px 10px;
        margin-bottom: 0;
    }

    /* ============================================================
       EMPLOYEE INFORMATION
    ============================================================ */

    .employee-box {
        border: 1px solid #d9dee7;
        border-top: 0;
    }

    .employee-cell {
        padding: 9px 11px;
        border-right: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
    }

    .employee-cell:nth-child(4n) {
        border-right: 0;
    }

    .employee-cell:nth-last-child(-n+4) {
        border-bottom: 0;
    }

    .field-label {
        font-size: 8.5px;
        text-transform: uppercase;
        color: #6b7280;
        letter-spacing: .4px;
        margin-bottom: 2px;
    }

    .field-value {
        font-size: 10.5px;
        font-weight: 600;
        color: #111827;
    }

    /* ============================================================
       ATTENDANCE
    ============================================================ */

    .attendance-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #d9dee7;
    }

    .attendance-table td {
        border: 1px solid #d9dee7;
        padding: 7px 9px;
    }

    .attendance-table .attendance-label {
        color: #6b7280;
        font-size: 9px;
    }

    .attendance-table .attendance-value {
        font-size: 12px;
        font-weight: 700;
        text-align: right;
    }

    /* ============================================================
       SALARY TABLE
    ============================================================ */

    .salary-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #cfd5df;
    }

    .salary-table th {
        background: #f1f5f9;
        color: #374151;
        border: 1px solid #cfd5df;
        padding: 8px 10px;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .salary-table td {
        border: 1px solid #d9dee7;
        padding: 7px 10px;
        font-size: 10px;
        vertical-align: middle;
    }

    .salary-table td.amount {
        text-align: right;
        font-weight: 600;
        white-space: nowrap;
    }

    .salary-table .total-row td {
        background: #f8fafc;
        font-weight: 800;
        font-size: 10.5px;
    }

    .salary-table .gross-row td {
        border-top: 2px solid #94a3b8;
    }

    .salary-table .deduction-row td {
        color: #991b1b;
    }

    /* ============================================================
       NET SALARY
    ============================================================ */

    .net-salary-box {
        margin-top: 14px;
        border: 2px solid #0f2b78;
        background: #f8fafc;
        padding: 13px 15px;
    }

    .net-label {
        font-size: 10px;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: .6px;
    }

    .net-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f2b78;
    }

    .net-amount {
        font-size: 23px;
        font-weight: 900;
        color: #0f2b78;
        white-space: nowrap;
    }

    /* ============================================================
       AMOUNT IN WORDS
    ============================================================ */

    .amount-words {
        border: 1px solid #d9dee7;
        border-top: 0;
        padding: 9px 11px;
        font-size: 9.5px;
        color: #374151;
        background: #ffffff;
    }

    .amount-words strong {
        color: #111827;
    }

    /* ============================================================
       PAYMENT
    ============================================================ */

    .payment-box {
        border: 1px solid #d9dee7;
    }

    .payment-cell {
        padding: 9px 11px;
        border-right: 1px solid #d9dee7;
    }

    .payment-cell:last-child {
        border-right: 0;
    }

    .status-badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 8.5px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-paid {
        background: #dcfce7;
        color: #166534;
    }

    .status-issued {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-generated {
        background: #e0f2fe;
        color: #0369a1;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-default {
        background: #e5e7eb;
        color: #374151;
    }

    /* ============================================================
       REMARKS
    ============================================================ */

    .remarks-box {
        border: 1px solid #d9dee7;
        padding: 10px;
        font-size: 9.5px;
        line-height: 1.6;
        color: #4b5563;
    }

    /* ============================================================
       SIGNATURE
    ============================================================ */

    .signature-area {
        margin-top: 45px;
    }

    .signature-line {
        border-top: 1px solid #374151;
        padding-top: 6px;
        text-align: center;
        font-size: 9px;
        color: #4b5563;
        min-width: 150px;
    }

    /* ============================================================
       FOOTER
    ============================================================ */

    .salary-footer {
        margin-top: 25px;
        padding-top: 10px;
        border-top: 1px solid #d9dee7;
        text-align: center;
        color: #6b7280;
        font-size: 8.5px;
        line-height: 1.5;
    }

    /* ============================================================
       SCREEN ONLY
    ============================================================ */

    @media (max-width: 991.98px) {

        .salary-slip-paper {
            width: 100%;
            min-height: auto;
        }

        .salary-slip-inner {
            padding: 20px;
        }

        .salary-slip-title {
            text-align: left;
            margin-top: 15px;
        }

        .document-meta-cell,
        .payment-cell {
            border-right: 0;
            border-bottom: 1px solid #d9dee7;
        }

        .employee-cell {
            border-right: 0;
        }

        .signature-area {
            margin-top: 30px;
        }
    }

    @media (max-width: 575.98px) {

        .salary-slip-inner {
            padding: 14px;
        }

        .company-logo {
            max-width: 180px;
        }

        .salary-slip-title h1 {
            font-size: 20px;
        }

        .net-amount {
            font-size: 19px;
            margin-top: 8px;
        }

        .salary-table th,
        .salary-table td {
            padding: 6px;
        }
    }

    /* ============================================================
       PRINT
    ============================================================ */

    @media print {

        @page {
            size: A4;
            margin: 0;
        }

        html,
        body {
            width: 210mm;
            min-height: 297mm;
            background: #ffffff !important;
        }

        body {
            margin: 0 !important;
            padding: 0 !important;
        }

        .salary-slip-toolbar,
        .no-print,
        nav,
        aside,
        header,
        footer,
        .sidebar,
        .navbar {
            display: none !important;
        }

        .salary-slip-page {
            background: #ffffff !important;
            min-height: auto !important;
        }

        .salary-slip-paper {
            width: 210mm !important;
            min-height: 297mm !important;
            margin: 0 !important;
            border: 0 !important;
            box-shadow: none !important;
        }

        .salary-slip-inner {
            padding: 12mm !important;
        }

        .salary-table,
        .attendance-table,
        .employee-box,
        .payment-box,
        .net-salary-box,
        .remarks-box {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .slip-section {
            break-inside: avoid;
            page-break-inside: avoid;
        }
    }
</style>

@endpush


@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | BASIC DATA
    |--------------------------------------------------------------------------
    */

    $driver = $salarySlip->driver;

    $driverName = $driver
        ? trim(
            ($driver->first_name ?? '') . ' ' .
            ($driver->last_name ?? '')
        )
        : '-';

    $salaryPeriod = $salarySlip->salaryPeriod ?? '-';

    $status = $salarySlip->status ?? 'generated';

    $statusClass = match ($status) {
        'paid' => 'status-paid',
        'issued' => 'status-issued',
        'generated' => 'status-generated',
        'cancelled' => 'status-cancelled',
        default => 'status-default',
    };

    /*
    |--------------------------------------------------------------------------
    | AMOUNTS
    |--------------------------------------------------------------------------
    */

    $basicSalary = (float) ($salarySlip->basic_salary ?? 0);
    $allowance = (float) ($salarySlip->allowance_amount ?? 0);
    $overtime = (float) ($salarySlip->overtime_amount ?? 0);
    $bonus = (float) ($salarySlip->bonus_amount ?? 0);
    $otherEarnings = (float) ($salarySlip->other_earnings ?? 0);

    $grossSalary = (float) ($salarySlip->gross_salary ?? 0);

    $advance = (float) ($salarySlip->advance_amount ?? 0);
    $deduction = (float) ($salarySlip->deduction_amount ?? 0);
    $otherDeductions = (float) ($salarySlip->other_deductions ?? 0);

    $totalDeductions = (float) ($salarySlip->total_deductions ?? 0);
    $netSalary = (float) ($salarySlip->net_salary ?? 0);

    /*
    |--------------------------------------------------------------------------
    | AMOUNT IN WORDS
    |--------------------------------------------------------------------------
    */

    $numberToWords = function ($number) {

        $number = (int) round($number);

        if ($number === 0) {
            return 'Zero Rupees Only';
        }

        $ones = [
            '',
            'One',
            'Two',
            'Three',
            'Four',
            'Five',
            'Six',
            'Seven',
            'Eight',
            'Nine',
            'Ten',
            'Eleven',
            'Twelve',
            'Thirteen',
            'Fourteen',
            'Fifteen',
            'Sixteen',
            'Seventeen',
            'Eighteen',
            'Nineteen'
        ];

        $tens = [
            '',
            '',
            'Twenty',
            'Thirty',
            'Forty',
            'Fifty',
            'Sixty',
            'Seventy',
            'Eighty',
            'Ninety'
        ];

        $convert = function ($num) use (&$convert, $ones, $tens) {

            if ($num < 20) {
                return $ones[$num];
            }

            if ($num < 100) {
                return $tens[intdiv($num, 10)] .
                    (($num % 10) ? ' ' . $ones[$num % 10] : '');
            }

            if ($num < 1000) {
                return $ones[intdiv($num, 100)] .
                    ' Hundred' .
                    (($num % 100) ? ' ' . $convert($num % 100) : '');
            }

            if ($num < 100000) {
                return $convert(intdiv($num, 1000)) .
                    ' Thousand' .
                    (($num % 1000) ? ' ' . $convert($num % 1000) : '');
            }

            if ($num < 10000000) {
                return $convert(intdiv($num, 100000)) .
                    ' Lakh' .
                    (($num % 100000) ? ' ' . $convert($num % 100000) : '');
            }

            return $convert(intdiv($num, 10000000)) .
                ' Crore' .
                (($num % 10000000) ? ' ' . $convert($num % 10000000) : '');
        };

        return $convert($number) . ' Rupees Only';
    };

    $netSalaryInWords = $numberToWords($netSalary);

@endphp


<div class="salary-slip-page">

    {{-- ============================================================
         TOOLBAR
    ============================================================= --}}

    <div class="container-fluid salary-slip-toolbar no-print">

        <div class="d-flex flex-wrap justify-content-between
                    align-items-center gap-2">

            <div>
                <h4 class="fw-bold mb-1">
                    Salary Slip
                </h4>

                <div class="text-muted small">
                    Salary Slip #{{ $salarySlip->id }}
                    <span class="mx-1">•</span>
                    {{ $salaryPeriod }}
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">

                <a href="{{ route('salary-slips.index') }}"
                   class="btn btn-light border">

                    <i class="bi bi-arrow-left me-1"></i>
                    Back

                </a>

                @if(!auth()->user()->isDriver())

                    <a href="{{ route('salary-slips.edit', $salarySlip) }}"
                       class="btn btn-outline-primary">

                        <i class="bi bi-pencil-square me-1"></i>
                        Edit

                    </a>

                @endif

                <button type="button"
                        class="btn btn-outline-dark"
                        onclick="printSalarySlip()">

                    <i class="bi bi-printer me-1"></i>
                    Print

                </button>

                <button type="button"
                        class="btn btn-primary"
                        id="downloadSalarySlipBtn">

                    <i class="bi bi-file-earmark-pdf me-1"></i>
                    Download PDF

                </button>

            </div>

        </div>

    </div>


    {{-- ============================================================
         A4 SALARY SLIP
    ============================================================= --}}

    <div class="salary-slip-paper" id="salarySlipDocument">

        <div class="salary-slip-inner">

            {{-- ====================================================
                 COMPANY HEADER
            ===================================================== --}}

            <div class="salary-header">

                <div class="row align-items-center">

                    <div class="col-7">

                        <img src="{{ asset('images/logo-300x88.png') }}"
                             alt="Mastermind Travels"
                             class="company-logo mb-2">

                        <div class="company-name">
                            MASTERMIND TRAVELS
                        </div>

                        <div class="company-address">
                            OM NIWAS, PLOT NO. 396, SECTOR 4,
                            GHANSOLI, NAVI MUMBAI - 400701
                        </div>

                    </div>

                    <div class="col-5">

                        <div class="salary-slip-title">

                            <h1>
                                SALARY SLIP
                            </h1>

                            <div class="subtitle">
                                Salary Statement
                            </div>

                            <div class="slip-number">
                                Slip No:
                                <strong>#{{ $salarySlip->id }}</strong>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================================
                 DOCUMENT META
            ===================================================== --}}

            <div class="document-meta">

                <div class="row g-0">

                    <div class="col-4 document-meta-cell">

                        <div class="meta-label">
                            Salary Month
                        </div>

                        <div class="meta-value">
                            {{ $salaryPeriod }}
                        </div>

                    </div>

                    <div class="col-4 document-meta-cell">

                        <div class="meta-label">
                            Salary Period
                        </div>

                        <div class="meta-value">

                            {{ $salarySlip->period_from
                                ? $salarySlip->period_from->format('d M Y')
                                : '-' }}

                            -

                            {{ $salarySlip->period_to
                                ? $salarySlip->period_to->format('d M Y')
                                : '-' }}

                        </div>

                    </div>

                    <div class="col-4 document-meta-cell">

                        <div class="meta-label">
                            Status
                        </div>

                        <div class="meta-value">

                            <span class="status-badge {{ $statusClass }}">
                                {{ ucwords(str_replace('_', ' ', $status)) }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================================
                 EMPLOYEE INFORMATION
            ===================================================== --}}

            <div class="slip-section">

                <div class="section-heading">
                    Employee / Driver Information
                </div>

                <div class="employee-box">

                    <div class="row g-0">

                        <div class="col-6 col-md-3 employee-cell">

                            <div class="field-label">
                                Driver Name
                            </div>

                            <div class="field-value">
                                {{ $driverName }}
                            </div>

                        </div>

                        <div class="col-6 col-md-3 employee-cell">

                            <div class="field-label">
                                Driver Code
                            </div>

                            <div class="field-value">
                                {{ $driver->driver_code ?? '-' }}
                            </div>

                        </div>

                        <div class="col-6 col-md-3 employee-cell">

                            <div class="field-label">
                                Mobile
                            </div>

                            <div class="field-value">
                                {{ $driver->mobile ?? '-' }}
                            </div>

                        </div>

                        <div class="col-6 col-md-3 employee-cell">

                            <div class="field-label">
                                Role
                            </div>

                            <div class="field-value">
                                {{ ucfirst($salarySlip->role ?? 'Driver') }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================================
                 ATTENDANCE
            ===================================================== --}}

            <div class="slip-section">

                <div class="section-heading">
                    Attendance Summary
                </div>

                <table class="attendance-table">

                    <tr>

                        <td>
                            <span class="attendance-label">
                                Total / Working Days
                            </span>
                        </td>

                        <td>
                            <span class="attendance-value">
                                {{ $salarySlip->total_days ?? 0 }}
                            </span>
                        </td>

                        <td>
                            <span class="attendance-label">
                                Present Days
                            </span>
                        </td>

                        <td>
                            <span class="attendance-value">
                                {{ $salarySlip->present_days ?? 0 }}
                            </span>
                        </td>

                    </tr>

                    <tr>

                        <td>
                            <span class="attendance-label">
                                Absent Days
                            </span>
                        </td>

                        <td>
                            <span class="attendance-value">
                                {{ $salarySlip->absent_days ?? 0 }}
                            </span>
                        </td>

                        <td>
                            <span class="attendance-label">
                                Leave Days
                            </span>
                        </td>

                        <td>
                            <span class="attendance-value">
                                {{ $salarySlip->leave_days ?? 0 }}
                            </span>
                        </td>

                    </tr>

                    <tr>

                        <td>
                            <span class="attendance-label">
                                Total Hours
                            </span>
                        </td>

                        <td>
                            <span class="attendance-value">
                                {{ number_format(
                                    (float)($salarySlip->total_hours ?? 0),
                                    2
                                ) }}
                            </span>
                        </td>

                        <td>
                            <span class="attendance-label">
                                Overtime Hours
                            </span>
                        </td>

                        <td>
                            <span class="attendance-value">
                                {{ number_format(
                                    (float)($salarySlip->overtime_hours ?? 0),
                                    2
                                ) }}
                            </span>
                        </td>

                    </tr>

                </table>

            </div>


            {{-- ====================================================
                 EARNINGS / DEDUCTIONS
            ===================================================== --}}

            <div class="slip-section">

                <div class="section-heading">
                    Salary Details
                </div>

                <table class="salary-table">

                    <thead>

                        <tr>

                            <th style="width: 55%;">
                                Earnings
                            </th>

                            <th style="width: 20%;"
                                class="text-end">
                                Amount
                            </th>

                            <th style="width: 25%;">
                                Deductions
                            </th>

                            <th style="width: 20%;"
                                class="text-end">
                                Amount
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td>
                                Basic Salary
                            </td>

                            <td class="amount">
                                ₹{{ number_format($basicSalary, 2) }}
                            </td>

                            <td>
                                Advance
                            </td>

                            <td class="amount">
                                ₹{{ number_format($advance, 2) }}
                            </td>

                        </tr>

                        <tr>

                            <td>
                                Allowance
                            </td>

                            <td class="amount">
                                ₹{{ number_format($allowance, 2) }}
                            </td>

                            <td>
                                Deduction
                            </td>

                            <td class="amount">
                                ₹{{ number_format($deduction, 2) }}
                            </td>

                        </tr>

                        <tr>

                            <td>
                                Overtime
                            </td>

                            <td class="amount">
                                ₹{{ number_format($overtime, 2) }}
                            </td>

                            <td>
                                Other Deductions
                            </td>

                            <td class="amount">
                                ₹{{ number_format($otherDeductions, 2) }}
                            </td>

                        </tr>

                        <tr>

                            <td>
                                Bonus
                            </td>

                            <td class="amount">
                                ₹{{ number_format($bonus, 2) }}
                            </td>

                            <td>
                                &nbsp;
                            </td>

                            <td class="amount">
                                &nbsp;
                            </td>

                        </tr>

                        <tr>

                            <td>
                                Other Earnings
                            </td>

                            <td class="amount">
                                ₹{{ number_format($otherEarnings, 2) }}
                            </td>

                            <td>
                                &nbsp;
                            </td>

                            <td class="amount">
                                &nbsp;
                            </td>

                        </tr>

                        <tr class="total-row gross-row">

                            <td>
                                GROSS SALARY
                            </td>

                            <td class="amount">
                                ₹{{ number_format($grossSalary, 2) }}
                            </td>

                            <td>
                                TOTAL DEDUCTIONS
                            </td>

                            <td class="amount">
                                ₹{{ number_format($totalDeductions, 2) }}
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>


            {{-- ====================================================
                 NET SALARY
            ===================================================== --}}

            <div class="net-salary-box">

                <div class="row align-items-center">

                    <div class="col-7">

                        <div class="net-label">
                            Final Payable Amount
                        </div>

                        <div class="net-title">
                            NET SALARY
                        </div>

                    </div>

                    <div class="col-5 text-end">

                        <div class="net-amount">
                            ₹{{ number_format($netSalary, 2) }}
                        </div>

                    </div>

                </div>

            </div>

            <div class="amount-words">

                <strong>
                    Amount in Words:
                </strong>

                {{ $netSalaryInWords }}

            </div>


            {{-- ====================================================
                 PAYMENT INFORMATION
            ===================================================== --}}

            <div class="slip-section">

                <div class="section-heading">
                    Payment Information
                </div>

                <div class="payment-box">

                    <div class="row g-0">

                        <div class="col-4 payment-cell">

                            <div class="field-label">
                                Payment Status
                            </div>

                            <div class="field-value">

                                @if(!empty($salarySlip->payment_status))

                                    <span class="status-badge
                                        {{ $salarySlip->payment_status === 'paid'
                                            ? 'status-paid'
                                            : 'status-default' }}">

                                        {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $salarySlip->payment_status
                                            )
                                        ) }}

                                    </span>

                                @else

                                    <span class="status-badge status-default">
                                        -
                                    </span>

                                @endif

                            </div>

                        </div>

                        <div class="col-4 payment-cell">

                            <div class="field-label">
                                Payment Date
                            </div>

                            <div class="field-value">

                                {{ $salarySlip->payment_date
                                    ? $salarySlip->payment_date->format('d M Y')
                                    : '-' }}

                            </div>

                        </div>

                        <div class="col-4 payment-cell">

                            <div class="field-label">
                                Issued Date
                            </div>

                            <div class="field-value">

                                {{ $salarySlip->issued_at
                                    ? $salarySlip->issued_at->format('d M Y h:i A')
                                    : '-' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================================
                 SALARY PROCESSING REFERENCE
            ===================================================== --}}

            @if($salarySlip->salaryProcessing)

                <div class="slip-section">

                    <div class="section-heading">
                        Salary Processing Reference
                    </div>

                    <table class="salary-table">

                        <tbody>

                            <tr>

                                <td>
                                    Processing ID
                                </td>

                                <td class="amount">
                                    #{{ $salarySlip->salaryProcessing->id }}
                                </td>

                                <td>
                                    Processing Status
                                </td>

                                <td class="amount">
                                    {{ ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $salarySlip->salaryProcessing->status
                                        )
                                    ) }}
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            @endif


            {{-- ====================================================
                 REMARKS
            ===================================================== --}}

            @if(!empty($salarySlip->remarks))

                <div class="slip-section">

                    <div class="section-heading">
                        Remarks
                    </div>

                    <div class="remarks-box">

                        {!! nl2br(e($salarySlip->remarks)) !!}

                    </div>

                </div>

            @endif


            {{-- ====================================================
                 SIGNATURES
            ===================================================== --}}

            <div class="signature-area">

                <div class="row">

                    <div class="col-4">

                        <div class="signature-line">
                            Employee / Driver Signature
                        </div>

                    </div>

                    <div class="col-4 text-center">

                        <div class="signature-line mx-auto">
                            Accounts Department
                        </div>

                    </div>

                    <div class="col-4">

                        <div class="signature-line ms-auto">
                            Authorized Signatory
                        </div>

                    </div>

                </div>

            </div>


            {{-- ====================================================
                 FOOTER
            ===================================================== --}}

            <div class="salary-footer">

                <strong>
                    MASTERMIND TRAVELS
                </strong>

                <br>

                This is a computer-generated salary slip and does not
                require a physical signature unless otherwise specified.

                <br>

                Salary Slip #{{ $salarySlip->id }}
                •
                {{ $salaryPeriod }}

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')

{{-- ============================================================
     HTML2PDF
============================================================= --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>

    /* ============================================================
       PRINT SALARY SLIP
    ============================================================= */

    function printSalarySlip() {

        window.print();

    }


    /* ============================================================
       DOWNLOAD SALARY SLIP PDF
    ============================================================= */

    document.addEventListener('DOMContentLoaded', function () {

        const downloadButton =
            document.getElementById('downloadSalarySlipBtn');

        const salarySlip =
            document.getElementById('salarySlipDocument');

        if (!downloadButton || !salarySlip) {
            return;
        }


        downloadButton.addEventListener('click', function () {

            if (typeof html2pdf === 'undefined') {

                alert(
                    'PDF library could not be loaded. Please try again.'
                );

                return;
            }


            const originalButtonHtml =
                downloadButton.innerHTML;


            /*
            |--------------------------------------------------------------------------
            | Loading State
            |--------------------------------------------------------------------------
            */

            downloadButton.disabled = true;

            downloadButton.innerHTML = `
                <span class="spinner-border spinner-border-sm me-1"
                      role="status"
                      aria-hidden="true"></span>
                Generating PDF...
            `;


            /*
            |--------------------------------------------------------------------------
            | PDF Filename
            |--------------------------------------------------------------------------
            */

            const driverCode =
                @json($driver->driver_code ?? 'driver');

            const salaryPeriod =
                @json($salaryPeriod);

            const safeDriverCode =
                String(driverCode)
                    .replace(/[^a-zA-Z0-9-_]/g, '-');

            const safePeriod =
                String(salaryPeriod)
                    .replace(/[^a-zA-Z0-9-_]/g, '-');

            const fileName =
                `Salary-Slip-${safeDriverCode}-${safePeriod}.pdf`;


            /*
            |--------------------------------------------------------------------------
            | PDF Options
            |--------------------------------------------------------------------------
            */

            const options = {

                margin: 0,

                filename: fileName,

                image: {
                    type: 'jpeg',
                    quality: 0.98
                },

                html2canvas: {

                    scale: 2,

                    useCORS: true,

                    allowTaint: false,

                    backgroundColor: '#ffffff',

                    logging: false

                },

                jsPDF: {

                    unit: 'mm',

                    format: 'a4',

                    orientation: 'portrait',

                    compress: true

                },

                pagebreak: {

                    mode: [
                        'avoid-all',
                        'css',
                        'legacy'
                    ]

                }

            };


            /*
            |--------------------------------------------------------------------------
            | Generate PDF
            |--------------------------------------------------------------------------
            */

            html2pdf()

                .set(options)

                .from(salarySlip)

                .save()

                .then(function () {

                    downloadButton.disabled = false;

                    downloadButton.innerHTML =
                        originalButtonHtml;

                })

                .catch(function (error) {

                    console.error(
                        'Salary Slip PDF Error:',
                        error
                    );

                    alert(
                        'Unable to generate PDF. Please try again.'
                    );

                    downloadButton.disabled = false;

                    downloadButton.innerHTML =
                        originalButtonHtml;

                });

        });

    });

</script>

@endpush