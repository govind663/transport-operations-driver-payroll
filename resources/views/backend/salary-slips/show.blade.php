@extends('backend.layouts.master')

@section('title')
    Salary Slip - {{ $salarySlip->slip_no ?: $salarySlip->id }}
@endsection

@push('styles')
<style>
    /* =========================================================
       1. GLOBAL SALARY SLIP LAYOUT
       SCREEN: FULL AVAILABLE WIDTH
    ========================================================= */

    .salary-slip-page {
        width: 100%;
        color: #1e293b;
        font-family: Arial, Helvetica, sans-serif;
    }

    .salary-slip-toolbar {
        width: 100%;
        margin-bottom: 20px;
    }

    .salary-slip-paper {
        box-sizing: border-box;
        width: 100%;
        max-width: none;
        min-height: auto;
        margin: 0;
        overflow: visible;
        background: #ffffff;
        color: #1e293b;
        border: 1px solid #dbe2ea;
        border-radius: 7px;
        box-shadow: 0 8px 28px rgba(15, 23, 42, .07);
    }

    .salary-slip-inner {
        box-sizing: border-box;
        width: 100%;
        padding: 28px;
    }

    .salary-slip-paper *,
    .salary-slip-paper *::before,
    .salary-slip-paper *::after {
        box-sizing: border-box;
    }


    /* =========================================================
       2. COMPANY HEADER
    ========================================================= */

    .salary-sheet-header {
        padding-bottom: 18px;
        margin-bottom: 18px;
        border-bottom: 3px solid #173b82;
    }

    .salary-sheet-brand {
        display: flex;
        align-items: center;
        gap: 16px;
        min-width: 0;
    }

    .salary-sheet-brand > div {
        min-width: 0;
    }

    .salary-sheet-logo {
        display: block;
        width: 150px;
        max-width: 100%;
        max-height: 62px;
        object-fit: contain;
        object-position: left center;
    }

    .salary-sheet-logo-fallback {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 125px;
        min-height: 48px;
        padding: 5px 10px;
        border: 2px solid #173b82;
        border-radius: 4px;
        background: #f8fafc;
        color: #173b82;
        font-size: 14px;
        font-weight: 900;
        line-height: 1.2;
        letter-spacing: .5px;
        text-align: center;
    }

    .salary-sheet-logo-fallback[hidden] {
        display: none !important;
    }

    .salary-sheet-company-name {
        margin: 0 0 5px;
        color: #173b82;
        font-size: 19px;
        font-weight: 900;
        letter-spacing: .4px;
        line-height: 1.3;
    }

    .salary-sheet-company-address {
        color: #64748b;
        font-size: 10px;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .salary-sheet-title {
        text-align: right;
    }

    .salary-sheet-title h2 {
        margin: 0;
        color: #172554;
        font-size: 28px;
        font-weight: 900;
        letter-spacing: 1px;
        line-height: 1.3;
    }

    .salary-sheet-title small {
        display: block;
        margin-top: 4px;
        color: #64748b;
        font-size: 10px;
    }

    .salary-sheet-number {
        display: inline-block;
        max-width: 100%;
        margin-top: 9px;
        padding: 7px 12px;
        overflow-wrap: anywhere;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 5px;
        color: #1e40af;
        font-size: 11px;
        font-weight: 800;
    }

    .salary-sheet-copy {
        display: block;
        margin-top: 5px;
        color: #94a3b8;
        font-size: 8px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .7px;
    }


    /* =========================================================
       3. SALARY PERIOD AND STATUS
    ========================================================= */

    .salary-sheet-meta {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0;
        margin-bottom: 18px;
        overflow: hidden;
        background: #f8fafc;
        border: 1px solid #dbe2ea;
        border-radius: 6px;
    }

    .salary-sheet-meta-item {
        min-width: 0;
        padding: 13px 15px;
        border-right: 1px solid #dbe2ea;
    }

    .salary-sheet-meta-item:last-child {
        border-right: 0;
    }

    .salary-sheet-label {
        display: block;
        margin-bottom: 6px;
        color: #64748b;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .45px;
    }

    .salary-sheet-value {
        display: block;
        color: #172554;
        font-size: 12px;
        font-weight: 800;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .salary-sheet-status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        background: #e0e7ff;
        color: #3730a3;
        font-size: 9px;
        font-weight: 800;
        text-transform: capitalize;
    }


    /* =========================================================
       4. COMMON SECTION DESIGN
    ========================================================= */

    .salary-sheet-section {
        min-width: 0;
        margin-top: 18px;
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .salary-sheet-section-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 38px;
        padding: 10px 14px;
        background: #173b82;
        border: 1px solid #173b82;
        border-radius: 5px 5px 0 0;
        color: #ffffff;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .salary-sheet-section-body {
        padding: 16px;
        background: #ffffff;
        border: 1px solid #dbe2ea;
        border-top: 0;
        border-radius: 0 0 5px 5px;
    }


    /* =========================================================
       5. EMPLOYEE INFORMATION
    ========================================================= */

    .salary-sheet-employee-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px 18px;
    }

    .salary-sheet-employee-item {
        min-width: 0;
    }

    .salary-sheet-employee-value {
        color: #1e293b;
        font-size: 12px;
        font-weight: 800;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }


    /* =========================================================
       6. ATTENDANCE SUMMARY
    ========================================================= */

    .salary-sheet-attendance-grid {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 10px;
    }

    .salary-sheet-attendance-item {
        min-width: 0;
        padding: 13px 11px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
    }

    .salary-sheet-attendance-value {
        display: block;
        color: #173b82;
        font-size: 18px;
        font-weight: 900;
        line-height: 1.3;
        font-variant-numeric: tabular-nums;
        overflow-wrap: anywhere;
    }

    .salary-sheet-attendance-label {
        display: block;
        margin-top: 5px;
        color: #64748b;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
    }


    /* =========================================================
       7. EARNINGS AND DEDUCTIONS LAYOUT
    ========================================================= */

    .salary-sheet-financial-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        margin-top: 18px;
        align-items: start;
    }

    .salary-sheet-financial-card {
        min-width: 0;
    }

    .salary-sheet-financial-card .salary-sheet-section {
        margin-top: 0;
    }

    .salary-sheet-financial-card .salary-sheet-section > div:not(.salary-sheet-section-title) {
        min-width: 0;
    }


    /* =========================================================
       8. FINANCIAL TABLES
    ========================================================= */

    .salary-sheet-table {
        width: 100%;
        margin: 0;
        border-collapse: collapse;
        table-layout: auto;
    }

    .salary-sheet-table th {
        padding: 11px 10px;
        background: #f1f5f9;
        border: 1px solid #dbe2ea;
        color: #475569;
        font-size: 9px;
        font-weight: 800;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .2px;
    }

    .salary-sheet-table th:last-child {
        text-align: right;
    }

    .salary-sheet-table td {
        padding: 11px 10px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #334155;
        font-size: 11px;
        line-height: 1.5;
        vertical-align: middle;
        overflow-wrap: anywhere;
    }

    .salary-sheet-table .salary-sheet-amount {
        width: 42%;
        text-align: right;
        white-space: nowrap;
        font-weight: 800;
        font-variant-numeric: tabular-nums;
    }

    .salary-sheet-table .salary-sheet-total td {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1e40af;
        font-weight: 900;
    }

    .salary-sheet-table .salary-sheet-deduction td {
        color: #991b1b;
    }

    .salary-sheet-table .salary-sheet-grand-total td {
        background: #f1f5f9;
        border-color: #cbd5e1;
        color: #172554;
        font-weight: 900;
    }


    /* =========================================================
       9. NET PAYABLE AMOUNT
    ========================================================= */

    .salary-sheet-net {
        margin-top: 18px;
        padding: 20px;
        background: #f8fafc;
        border: 2px solid #173b82;
        border-radius: 6px;
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .salary-sheet-net-label {
        display: block;
        color: #64748b;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .salary-sheet-net-title {
        display: block;
        margin-top: 5px;
        color: #173b82;
        font-size: 16px;
        font-weight: 900;
    }

    .salary-sheet-net-amount {
        color: #173b82;
        font-size: 28px;
        font-weight: 900;
        line-height: 1.3;
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }

    .salary-sheet-words {
        margin-top: 0;
        padding: 13px 15px;
        background: #f8fafc;
        border: 1px solid #dbe2ea;
        border-top: 0;
        border-radius: 0 0 5px 5px;
        color: #475569;
        font-size: 10px;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }


    /* =========================================================
       10. PAYMENT INFORMATION
    ========================================================= */

    .salary-sheet-payment-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px 20px;
    }

    .salary-sheet-payment-item {
        min-width: 0;
    }

    .salary-sheet-payment-value {
        color: #1e293b;
        font-size: 11px;
        font-weight: 800;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .salary-sheet-payment-badge {
        display: inline-block;
        max-width: 100%;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 9px;
        font-weight: 800;
        text-transform: capitalize;
    }

    .payment-paid {
        background: #dcfce7;
        color: #166534;
    }

    .payment-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .payment-default {
        background: #e5e7eb;
        color: #374151;
    }


    /* =========================================================
       11. SIGNATURE SECTION
    ========================================================= */

    .salary-sheet-signatures {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 30px;
        margin-top: 55px;
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .salary-sheet-signature {
        min-width: 0;
        padding-top: 8px;
        border-top: 1px solid #64748b;
        color: #475569;
        font-size: 9px;
        font-weight: 700;
        text-align: center;
    }


    /* =========================================================
       12. DOCUMENT FOOTER
    ========================================================= */

    .salary-sheet-footer {
        margin-top: 25px;
        padding-top: 12px;
        border-top: 1px solid #dbe2ea;
        color: #64748b;
        font-size: 9px;
        line-height: 1.7;
        text-align: center;
    }

    .salary-sheet-footer strong {
        color: #173b82;
        font-size: 10px;
        letter-spacing: .4px;
    }


    /* =========================================================
       13. PDF MODE
       Only activated while generating the PDF.
    ========================================================= */

    .salary-slip-paper.salary-slip-pdf-mode {
        width: 210mm !important;
        max-width: 210mm !important;
        min-height: 297mm !important;
        margin: 0 auto !important;
        overflow: visible !important;
        border: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
    }

    .salary-slip-pdf-mode .salary-slip-inner {
        width: 100% !important;
        padding: 12mm !important;
    }

    .salary-slip-pdf-mode .salary-sheet-financial-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 10px !important;
    }

    .salary-slip-pdf-mode .salary-sheet-employee-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
    }

    .salary-slip-pdf-mode .salary-sheet-attendance-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    }

    .salary-slip-pdf-mode .salary-sheet-payment-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
    }

    .salary-slip-pdf-mode .salary-sheet-signatures {
        grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
    }


    /* =========================================================
       14. TABLET RESPONSIVE
    ========================================================= */

    @media (max-width: 1100px) {

        .salary-sheet-attendance-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .salary-sheet-payment-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

    }


    @media (max-width: 991.98px) {

        .salary-slip-inner {
            padding: 22px;
        }

        .salary-sheet-employee-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .salary-sheet-title h2 {
            font-size: 24px;
        }

    }


    /* =========================================================
       15. MOBILE RESPONSIVE
    ========================================================= */

    @media (max-width: 767.98px) {

        .salary-sheet-title {
            margin-top: 16px;
            text-align: left;
        }

        .salary-sheet-financial-grid {
            grid-template-columns: 1fr;
        }

        .salary-sheet-attendance-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .salary-sheet-payment-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .salary-sheet-signatures {
            gap: 22px;
        }

    }


    @media (max-width: 575.98px) {

        .salary-slip-inner {
            padding: 14px;
        }

        .salary-sheet-brand {
            align-items: flex-start;
            gap: 8px;
        }

        .salary-sheet-logo {
            width: 105px;
            max-height: 45px;
        }

        .salary-sheet-logo-fallback {
            min-width: 95px;
            min-height: 42px;
            font-size: 11px;
        }

        .salary-sheet-company-name {
            font-size: 14px;
        }

        .salary-sheet-company-address {
            font-size: 8px;
        }

        .salary-sheet-title h2 {
            font-size: 20px;
        }

        .salary-sheet-meta {
            grid-template-columns: 1fr;
        }

        .salary-sheet-meta-item {
            border-right: 0;
            border-bottom: 1px solid #dbe2ea;
        }

        .salary-sheet-meta-item:last-child {
            border-bottom: 0;
        }

        .salary-sheet-employee-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .salary-sheet-attendance-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .salary-sheet-payment-grid {
            grid-template-columns: 1fr;
        }

        .salary-sheet-signatures {
            grid-template-columns: 1fr;
            gap: 28px;
        }

        .salary-sheet-net-amount {
            font-size: 19px;
        }

        .salary-sheet-section-title {
            font-size: 10px;
        }

        .salary-sheet-section-body {
            padding: 11px;
        }

        .salary-sheet-table th,
        .salary-sheet-table td {
            padding: 8px 6px;
            font-size: 9px;
        }

    }


    /* =========================================================
       16. PRINT SETTINGS - A4 PORTRAIT
    ========================================================= */

    @media print {

        @page {
            size: A4 portrait;
            margin: 0;
        }

        html,
        body {
            width: 210mm !important;
            min-width: 210mm !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
        }

        body * {
            visibility: hidden !important;
        }

        #salarySlipDocument,
        #salarySlipDocument * {
            visibility: visible !important;
        }

        #salarySlipDocument {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 210mm !important;
            max-width: 210mm !important;
            min-height: 297mm !important;
            height: auto !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: visible !important;
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            background: #ffffff !important;
        }

        .salary-slip-page {
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
        }

        .salary-slip-inner {
            width: 100% !important;
            padding: 12mm !important;
        }

        .salary-slip-toolbar,
        .no-print {
            display: none !important;
            visibility: hidden !important;
        }

        .salary-sheet-financial-grid {
            display: grid !important;
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 10px !important;
        }

        .salary-sheet-employee-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr)) !important;
        }

        .salary-sheet-attendance-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        }

        .salary-sheet-payment-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }

        .salary-sheet-signatures {
            grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
        }

        .salary-sheet-header,
        .salary-sheet-meta,
        .salary-sheet-section,
        .salary-sheet-net,
        .salary-sheet-words,
        .salary-sheet-signatures,
        .salary-sheet-footer {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .salary-sheet-section,
        .salary-sheet-meta,
        .salary-sheet-net,
        .salary-sheet-words,
        .salary-sheet-signatures {
            break-inside: avoid !important;
            page-break-inside: avoid !important;
        }

        .salary-sheet-logo {
            max-height: 58px !important;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

    }

</style>
@endpush

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | DRIVER AND SLIP DETAILS
    |--------------------------------------------------------------------------
    */

    $driver = $salarySlip->driver;

    $driverName = $driver
        ? trim(($driver->first_name ?? '') . ' ' . ($driver->last_name ?? ''))
        : '-';

    $driverName = $driverName !== '' ? $driverName : '-';

    $slipNumber = $salarySlip->slip_no
        ?: ('#' . $salarySlip->id);

    $salaryPeriod = $salarySlip->salaryPeriod ?? '-';

    $status = $salarySlip->status ?? 'generated';

    $paymentStatus = $salarySlip->payment_status ?? 'pending';

    $paymentStatusClass = match ($paymentStatus) {
        'paid' => 'payment-paid',
        'pending' => 'payment-pending',
        default => 'payment-default',
    };


    /*
    |--------------------------------------------------------------------------
    | SAVED SALARY AMOUNTS
    |--------------------------------------------------------------------------
    | This page displays the stored Salary Slip snapshot only.
    | It does not recalculate or modify salary values.
    */

    $basicSalary = (float) ($salarySlip->basic_salary ?? 0);

    $workingSheetTotal = (float) (
        $salarySlip->working_sheet_total_amount ?? 0
    );

    $workingSheetBase = (float) (
        $salarySlip->working_sheet_base_amount ?? 0
    );

    $extraKm = (float) ($salarySlip->extra_km_amount ?? 0);

    $allowance = (float) ($salarySlip->allowance_amount ?? 0);

    $overtime = (float) ($salarySlip->overtime_amount ?? 0);

    $expenseReimbursement = (float) (
        $salarySlip->expense_reimbursement_amount ?? 0
    );

    $workingSheetOther = (float) (
        $salarySlip->working_sheet_other_amount ?? 0
    );

    $workingSheetComponents =
        $workingSheetBase
        + $extraKm
        + $overtime
        + $allowance
        + $expenseReimbursement
        + $workingSheetOther;

    if ($workingSheetTotal > 0) {
        $workingSheetEarnings = $workingSheetTotal;
    } elseif ($workingSheetComponents != 0.0) {
        $workingSheetEarnings = $workingSheetComponents;
    } else {
        $workingSheetEarnings = $allowance + $overtime;
    }

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
    | ATTENDANCE SNAPSHOT
    |--------------------------------------------------------------------------
    */

    $totalDays = (float) ($salarySlip->total_days ?? 0);

    $presentDays = (float) ($salarySlip->present_days ?? 0);

    $absentDays = (float) ($salarySlip->absent_days ?? 0);

    $leaveDays = (float) ($salarySlip->leave_days ?? 0);

    $paidDays = (float) ($salarySlip->paid_days ?? 0);

    $formatAmount = fn ($amount) => number_format((float) $amount, 2);


    /*
    |--------------------------------------------------------------------------
    | AMOUNT IN WORDS
    |--------------------------------------------------------------------------
    */

    $numberToWords = function ($number) {
        $number = (int) round(abs((float) $number));

        if ($number === 0) {
            return 'Zero Rupees Only';
        }

        $ones = [
            '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven',
            'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen',
            'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen',
            'Nineteen'
        ];

        $tens = [
            '', '', 'Twenty', 'Thirty', 'Forty', 'Fifty',
            'Sixty', 'Seventy', 'Eighty', 'Ninety'
        ];

        $convert = function ($num) use (&$convert, $ones, $tens) {

            if ($num < 20) {
                return $ones[$num];
            }

            if ($num < 100) {
                return $tens[intdiv($num, 10)]
                    . (($num % 10) ? ' ' . $ones[$num % 10] : '');
            }

            if ($num < 1000) {
                return $ones[intdiv($num, 100)] . ' Hundred'
                    . (($num % 100) ? ' ' . $convert($num % 100) : '');
            }

            if ($num < 100000) {
                return $convert(intdiv($num, 1000)) . ' Thousand'
                    . (($num % 1000) ? ' ' . $convert($num % 1000) : '');
            }

            if ($num < 10000000) {
                return $convert(intdiv($num, 100000)) . ' Lakh'
                    . (($num % 100000) ? ' ' . $convert($num % 100000) : '');
            }

            return $convert(intdiv($num, 10000000)) . ' Crore'
                . (($num % 10000000) ? ' ' . $convert($num % 10000000) : '');
        };

        return $convert($number) . ' Rupees Only';
    };

    $netSalaryInWords = $numberToWords($netSalary);
@endphp

<div class="salary-slip-page">
    <div class="pd-ltr-20 xs-pd-20-10">

        {{-- =========================================================
             PAGE ACTIONS
        ========================================================= --}}

        <div class="salary-slip-toolbar no-print">

            <div class="page-header mb-30">

                <div class="row align-items-center">

                    <div class="col-md-7 col-sm-12">

                        <div class="title">
                            <h4 class="text-blue">
                                Salary Statement
                            </h4>
                        </div>

                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">

                                <li class="breadcrumb-item">
                                    <a href="{{ route('salary-slips.index') }}">
                                        Salary Slips
                                    </a>
                                </li>

                                <li class="breadcrumb-item active"
                                    aria-current="page">
                                    {{ $slipNumber }}
                                </li>

                            </ol>
                        </nav>

                    </div>

                    <div class="col-md-5 col-sm-12 text-md-right mt-3 mt-md-0">

                        <div class="d-flex flex-wrap justify-content-md-end"
                             style="gap:8px;">

                            <a href="{{ route('salary-slips.index') }}"
                               class="btn btn-outline-secondary btn-sm">

                                <i class="fa fa-arrow-left"></i>
                                Back

                            </a>

                            <button type="button"
                                    class="btn btn-outline-dark btn-sm"
                                    onclick="printSalarySlip()">

                                <i class="fa fa-print"></i>
                                Print

                            </button>

                            <button type="button"
                                    class="btn btn-primary btn-sm"
                                    id="downloadSalarySlipBtn">

                                <i class="fa fa-file-pdf-o"></i>
                                Download PDF

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             PRINTABLE SALARY STATEMENT
             SCREEN: FULL WIDTH
             PRINT/PDF: A4 PORTRAIT
        ========================================================= --}}

        <div class="salary-slip-paper salary-sheet"
             id="salarySlipDocument">

            <div class="salary-slip-inner">


                {{-- =====================================================
                     COMPANY HEADER
                ====================================================== --}}

                <div class="salary-sheet-header">

                    <div class="row align-items-center">

                        <div class="col-7">

                            <div class="salary-sheet-brand">

                                <div>

                                    <img
                                        src="{{ asset('backend/assets/img/logo/mastermind-logo.png') }}"
                                        alt="Mastermind Travels"
                                        class="salary-sheet-logo"
                                        data-no-optimize="1"
                                        loading="eager"
                                        onerror="this.hidden=true; this.nextElementSibling.hidden=false;"
                                    >

                                    <span class="salary-sheet-logo-fallback" hidden>
                                        MASTERMIND
                                        <br>
                                        TRAVELS
                                    </span>

                                </div>

                                <div>

                                    <h3 class="salary-sheet-company-name">
                                        MASTERMIND TRAVELS
                                    </h3>

                                    <div class="salary-sheet-company-address">
                                        OM NIWAS, PLOT NO. 396, SECTOR 4,
                                        GHANSOLI, NAVI MUMBAI - 400701
                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-5">

                            <div class="salary-sheet-title">

                                <h2>
                                    SALARY SLIP
                                </h2>

                                <small>
                                    Official Salary Statement
                                </small>

                                <div>

                                    <span class="salary-sheet-number">
                                        {{ $slipNumber }}
                                    </span>

                                </div>

                                <span class="salary-sheet-copy">
                                    Employee Copy
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =====================================================
                     SALARY PERIOD AND SLIP STATUS
                ====================================================== --}}

                <div class="salary-sheet-meta">

                    <div class="salary-sheet-meta-item">

                        <span class="salary-sheet-label">
                            Salary Month
                        </span>

                        <span class="salary-sheet-value">
                            {{ $salaryPeriod }}
                        </span>

                    </div>

                    <div class="salary-sheet-meta-item">

                        <span class="salary-sheet-label">
                            Salary Period
                        </span>

                        <span class="salary-sheet-value">

                            {{ $salarySlip->period_from
                                ? $salarySlip->period_from->format('d M Y')
                                : '-' }}

                            -

                            {{ $salarySlip->period_to
                                ? $salarySlip->period_to->format('d M Y')
                                : '-' }}

                        </span>

                    </div>

                    <div class="salary-sheet-meta-item">

                        <span class="salary-sheet-label">
                            Slip Status
                        </span>

                        <span class="salary-sheet-status">
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </span>

                    </div>

                </div>


                {{-- =====================================================
                     EMPLOYEE INFORMATION
                ====================================================== --}}

                <section class="salary-sheet-section">

                    <div class="salary-sheet-section-title">
                        <span>Employee Information</span>
                    </div>

                    <div class="salary-sheet-section-body">

                        <div class="salary-sheet-employee-grid">

                            <div class="salary-sheet-employee-item">

                                <span class="salary-sheet-label">
                                    Driver Name
                                </span>

                                <div class="salary-sheet-employee-value">
                                    {{ $driverName }}
                                </div>

                            </div>

                            <div class="salary-sheet-employee-item">

                                <span class="salary-sheet-label">
                                    Driver Code
                                </span>

                                <div class="salary-sheet-employee-value">
                                    {{ $driver->driver_code ?? '-' }}
                                </div>

                            </div>

                            <div class="salary-sheet-employee-item">

                                <span class="salary-sheet-label">
                                    Mobile Number
                                </span>

                                <div class="salary-sheet-employee-value">
                                    {{ $driver->mobile ?? '-' }}
                                </div>

                            </div>

                            <div class="salary-sheet-employee-item">

                                <span class="salary-sheet-label">
                                    Designation
                                </span>

                                <div class="salary-sheet-employee-value">
                                    {{ ucfirst(str_replace('_', ' ', $salarySlip->role ?? 'Driver')) }}
                                </div>

                            </div>

                        </div>

                    </div>

                </section>


                {{-- =====================================================
                     ATTENDANCE SUMMARY
                ====================================================== --}}

                <section class="salary-sheet-section">

                    <div class="salary-sheet-section-title">
                        <span>Attendance Summary</span>
                    </div>

                    <div class="salary-sheet-section-body">

                        <div class="salary-sheet-attendance-grid">

                            <div class="salary-sheet-attendance-item">

                                <span class="salary-sheet-attendance-value">
                                    {{ $formatAmount($totalDays) }}
                                </span>

                                <span class="salary-sheet-attendance-label">
                                    Total Days
                                </span>

                            </div>

                            <div class="salary-sheet-attendance-item">

                                <span class="salary-sheet-attendance-value">
                                    {{ $formatAmount($presentDays) }}
                                </span>

                                <span class="salary-sheet-attendance-label">
                                    Present Days
                                </span>

                            </div>

                            <div class="salary-sheet-attendance-item">

                                <span class="salary-sheet-attendance-value">
                                    {{ $formatAmount($absentDays) }}
                                </span>

                                <span class="salary-sheet-attendance-label">
                                    Absent Days
                                </span>

                            </div>

                            <div class="salary-sheet-attendance-item">

                                <span class="salary-sheet-attendance-value">
                                    {{ $formatAmount($leaveDays) }}
                                </span>

                                <span class="salary-sheet-attendance-label">
                                    Leave Days
                                </span>

                            </div>

                            <div class="salary-sheet-attendance-item">

                                <span class="salary-sheet-attendance-value">
                                    {{ $formatAmount($paidDays) }}
                                </span>

                                <span class="salary-sheet-attendance-label">
                                    Paid Days
                                </span>

                            </div>

                            <div class="salary-sheet-attendance-item">

                                <span class="salary-sheet-attendance-value">
                                    {{ $formatAmount($salarySlip->overtime_hours ?? 0) }}
                                </span>

                                <span class="salary-sheet-attendance-label">
                                    Overtime Hours
                                </span>

                            </div>

                        </div>

                    </div>

                </section>


                {{-- =====================================================
                     EARNINGS AND DEDUCTIONS
                ====================================================== --}}

                <div class="salary-sheet-financial-grid">


                    {{-- EARNINGS --}}

                    <div class="salary-sheet-financial-card">

                        <section class="salary-sheet-section">

                            <div class="salary-sheet-section-title">
                                <span>Earnings</span>
                            </div>

                            <div>

                                <table class="salary-sheet-table">

                                    <thead>

                                        <tr>

                                            <th>
                                                Description
                                            </th>

                                            <th class="text-right">
                                                Amount
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <tr>

                                            <td>
                                                Basic Salary
                                            </td>

                                            <td class="salary-sheet-amount">
                                                ₹{{ $formatAmount($basicSalary) }}
                                            </td>

                                        </tr>


                                        @if ($workingSheetEarnings != 0)

                                            <tr>

                                                <td>
                                                    Working Sheet Earnings
                                                </td>

                                                <td class="salary-sheet-amount">
                                                    ₹{{ $formatAmount($workingSheetEarnings) }}
                                                </td>

                                            </tr>

                                        @endif


                                        @if ($bonus != 0)

                                            <tr>

                                                <td>
                                                    Bonus
                                                </td>

                                                <td class="salary-sheet-amount">
                                                    ₹{{ $formatAmount($bonus) }}
                                                </td>

                                            </tr>

                                        @endif


                                        @if ($otherEarnings != 0)

                                            <tr>

                                                <td>
                                                    Other Earnings
                                                </td>

                                                <td class="salary-sheet-amount">
                                                    ₹{{ $formatAmount($otherEarnings) }}
                                                </td>

                                            </tr>

                                        @endif


                                        <tr class="salary-sheet-total">

                                            <td>
                                                GROSS SALARY
                                            </td>

                                            <td class="salary-sheet-amount">
                                                ₹{{ $formatAmount($grossSalary) }}
                                            </td>

                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                        </section>

                    </div>


                    {{-- DEDUCTIONS --}}

                    <div class="salary-sheet-financial-card">

                        <section class="salary-sheet-section">

                            <div class="salary-sheet-section-title">
                                <span>Deductions</span>
                            </div>

                            <div>

                                <table class="salary-sheet-table">

                                    <thead>

                                        <tr>

                                            <th>
                                                Description
                                            </th>

                                            <th class="text-right">
                                                Amount
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody>


                                        @if ($advance != 0)

                                            <tr class="salary-sheet-deduction">

                                                <td>
                                                    Advance
                                                </td>

                                                <td class="salary-sheet-amount">
                                                    ₹{{ $formatAmount($advance) }}
                                                </td>

                                            </tr>

                                        @endif


                                        @if ($deduction != 0)

                                            <tr class="salary-sheet-deduction">

                                                <td>
                                                    Deduction / Penalty
                                                </td>

                                                <td class="salary-sheet-amount">
                                                    ₹{{ $formatAmount($deduction) }}
                                                </td>

                                            </tr>

                                        @endif


                                        @if ($otherDeductions != 0)

                                            <tr class="salary-sheet-deduction">

                                                <td>
                                                    Other Deductions
                                                </td>

                                                <td class="salary-sheet-amount">
                                                    ₹{{ $formatAmount($otherDeductions) }}
                                                </td>

                                            </tr>

                                        @endif


                                        <tr class="salary-sheet-grand-total">

                                            <td>
                                                TOTAL DEDUCTIONS
                                            </td>

                                            <td class="salary-sheet-amount">
                                                ₹{{ $formatAmount($totalDeductions) }}
                                            </td>

                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                        </section>

                    </div>

                </div>


                {{-- =====================================================
                     NET PAYABLE AMOUNT
                ====================================================== --}}

                <div class="salary-sheet-net">

                    <div class="row align-items-center">

                        <div class="col-7">

                            <span class="salary-sheet-net-label">
                                Final Payable Amount
                            </span>

                            <span class="salary-sheet-net-title">
                                NET SALARY
                            </span>

                        </div>

                        <div class="col-5 text-right">

                            <div class="salary-sheet-net-amount">
                                ₹{{ $formatAmount($netSalary) }}
                            </div>

                        </div>

                    </div>

                </div>

                <div class="salary-sheet-words">

                    <strong>Amount in Words:</strong>

                    {{ $netSalaryInWords }}

                </div>

                                {{-- =====================================================
                     PAYMENT INFORMATION
                ====================================================== --}}

                <section class="salary-sheet-section">

                    <div class="salary-sheet-section-title">
                        <span>Payment Information</span>
                    </div>

                    <div class="salary-sheet-section-body">

                        <div class="salary-sheet-payment-grid">

                            <div class="salary-sheet-payment-item">

                                <span class="salary-sheet-label">
                                    Payment Status
                                </span>

                                <div>

                                    <span class="salary-sheet-payment-badge {{ $paymentStatusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}
                                    </span>

                                </div>

                            </div>

                            <div class="salary-sheet-payment-item">

                                <span class="salary-sheet-label">
                                    Payment Date
                                </span>

                                <div class="salary-sheet-payment-value">

                                    {{ $salarySlip->payment_date
                                        ? $salarySlip->payment_date->format('d M Y')
                                        : 'Not available' }}

                                </div>

                            </div>

                            <div class="salary-sheet-payment-item">

                                <span class="salary-sheet-label">
                                    Issued Date
                                </span>

                                <div class="salary-sheet-payment-value">

                                    {{ $salarySlip->issued_at
                                        ? $salarySlip->issued_at->format('d M Y h:i A')
                                        : 'Not issued' }}

                                </div>

                            </div>

                            <div class="salary-sheet-payment-item">

                                <span class="salary-sheet-label">
                                    Salary Slip Number
                                </span>

                                <div class="salary-sheet-payment-value">
                                    {{ $slipNumber }}
                                </div>

                            </div>

                        </div>

                    </div>

                </section>


                {{-- =====================================================
                     SIGNATURE SECTION
                ====================================================== --}}

                <div class="salary-sheet-signatures">

                    <div class="salary-sheet-signature">
                        Employee / Driver Signature
                    </div>

                    <div class="salary-sheet-signature">
                        Accounts Department
                    </div>

                    <div class="salary-sheet-signature">
                        Authorized Signatory
                    </div>

                </div>


                {{-- =====================================================
                     DOCUMENT FOOTER
                ====================================================== --}}

                <div class="salary-sheet-footer">

                    <strong>MASTERMIND TRAVELS</strong>

                    <br>

                    This is a computer-generated salary statement.

                    <br>

                    Salary Slip: {{ $slipNumber }}

                    <span style="padding:0 5px;">|</span>

                    Salary Period: {{ $salaryPeriod }}

                </div>

            </div>

        </div>

    </div>
</div>

@endsection


{{-- =============================================================
     SALARY SLIP SCRIPTS
============================================================= --}}

@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    /*
    |--------------------------------------------------------------------------
    | PRINT SALARY SLIP
    |--------------------------------------------------------------------------
    */

    function printSalarySlip() {
        window.print();
    }


    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD SALARY SLIP PDF
    |--------------------------------------------------------------------------
    */

    document.addEventListener('DOMContentLoaded', function () {

        const downloadButton = document.getElementById(
            'downloadSalarySlipBtn'
        );

        const salarySlipDocument = document.getElementById(
            'salarySlipDocument'
        );

        if (!downloadButton || !salarySlipDocument) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | RESTORE PDF BUTTON AND DOCUMENT LAYOUT
        |--------------------------------------------------------------------------
        */

        function restorePdfState(originalButtonHtml) {

            salarySlipDocument.classList.remove(
                'salary-slip-pdf-mode'
            );

            downloadButton.disabled = false;

            downloadButton.innerHTML = originalButtonHtml;
        }


        /*
        |--------------------------------------------------------------------------
        | PDF DOWNLOAD EVENT
        |--------------------------------------------------------------------------
        */

        downloadButton.addEventListener('click', function () {

            if (typeof html2pdf === 'undefined') {

                alert(
                    'PDF library could not be loaded. Please try again.'
                );

                return;
            }


            const originalButtonHtml = downloadButton.innerHTML;


            /*
            |--------------------------------------------------------------------------
            | BUTTON LOADING STATE
            |--------------------------------------------------------------------------
            */

            downloadButton.disabled = true;

            downloadButton.innerHTML = `
                <span
                    class="spinner-border spinner-border-sm mr-1"
                    role="status"
                    aria-hidden="true">
                </span>
                Generating PDF...
            `;


            /*
            |--------------------------------------------------------------------------
            | TEMPORARILY APPLY A4 DOCUMENT LAYOUT
            |--------------------------------------------------------------------------
            | Screen layout remains full width.
            | A4 layout is activated only for PDF generation.
            */

            salarySlipDocument.classList.add(
                'salary-slip-pdf-mode'
            );


            /*
            |--------------------------------------------------------------------------
            | FILE NAME DETAILS
            |--------------------------------------------------------------------------
            */

            const driverCode = @json($driver->driver_code ?? 'driver');

            const salaryPeriod = @json($salaryPeriod);

            const slipNumber = @json($slipNumber);


            const safeDriverCode = String(driverCode)
                .replace(/[^a-zA-Z0-9-_]/g, '-');

            const safePeriod = String(salaryPeriod)
                .replace(/[^a-zA-Z0-9-_]/g, '-');

            const safeSlipNumber = String(slipNumber)
                .replace(/[^a-zA-Z0-9-_]/g, '-');


            const fileName =
                `Salary-Slip-${safeDriverCode}-${safePeriod}-${safeSlipNumber}.pdf`;


            /*
            |--------------------------------------------------------------------------
            | PDF CONFIGURATION
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

                    logging: false,

                    scrollY: 0

                },

                jsPDF: {

                    unit: 'mm',

                    format: 'a4',

                    orientation: 'portrait',

                    compress: true

                },

                pagebreak: {

                    mode: ['css', 'legacy'],

                    avoid: [
                        '.salary-sheet-section',
                        '.salary-sheet-meta',
                        '.salary-sheet-net',
                        '.salary-sheet-words',
                        '.salary-sheet-signatures'
                    ]

                }

            };


            /*
            |--------------------------------------------------------------------------
            | GENERATE AND DOWNLOAD PDF
            |--------------------------------------------------------------------------
            */

            html2pdf()

                .set(options)

                .from(salarySlipDocument)

                .save()

                .then(function () {

                    restorePdfState(originalButtonHtml);

                })

                .catch(function (error) {

                    console.error(
                        'Salary Slip PDF Error:',
                        error
                    );

                    alert(
                        'Unable to generate PDF. Please try again.'
                    );

                    restorePdfState(originalButtonHtml);

                });

        });

    });
</script>

@endpush
