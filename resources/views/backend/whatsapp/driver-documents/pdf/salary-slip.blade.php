{{--
|--------------------------------------------------------------------------
| DRIVER SALARY SLIP PDF
|--------------------------------------------------------------------------
| View:
| backend.whatsapp.driver-documents.pdf.salary-slip
|
| Expected variable:
|   $salarySlip
|
| Important:
| Salary calculations are not performed in this Blade.
| All final amounts are displayed from the saved SalarySlip record.
|--------------------------------------------------------------------------
--}}

@php
/*
|--------------------------------------------------------------------------
| COMPANY LOGO - LOCAL PNG
|--------------------------------------------------------------------------
*/

$logoPath = public_path(
'backend/assets/img/logo/mastermind-logo.png'
);

$logoBase64 = null;

if (is_file($logoPath) && is_readable($logoPath)) {
$logoContents = file_get_contents($logoPath);

if ($logoContents !== false && $logoContents !== '') {
$logoBase64 = base64_encode($logoContents);
}
}

$logoSrc = $logoBase64
? 'data:image/png;base64,' . $logoBase64
: null;

/*
|--------------------------------------------------------------------------
| DRIVER DETAILS
|--------------------------------------------------------------------------
*/

$driver = $salarySlip->driver;

$driverName = trim(
(string) ($driver->first_name ?? '')
. ' '
. (string) ($driver->last_name ?? '')
);

if ($driverName === '') {
$driverName = 'Driver';
}

$driverCode = $driver->driver_code ?? '—';

$salaryPeriod = $salarySlip->salary_period
?? (
!empty($salarySlip->salary_month)
&& !empty($salarySlip->salary_year)
? \Illuminate\Support\Carbon::create(
(int) $salarySlip->salary_year,
(int) $salarySlip->salary_month,
1
)->format('F Y')
: '—'
);

/*
|--------------------------------------------------------------------------
| FORMATTING HELPERS
|--------------------------------------------------------------------------
*/

$formatMoney = static function ($amount): string {
return '₹' . number_format((float) ($amount ?? 0), 2);
};

$formatNumber = static function ($number): string {
return number_format((float) ($number ?? 0), 2);
};

$formatDate = static function ($date): string {
if (empty($date)) {
return '—';
}

try {
return \Illuminate\Support\Carbon::parse($date)->format('d M Y');
} catch (\Throwable $exception) {
return '—';
}
};

/*
|--------------------------------------------------------------------------
| STATUS LABELS
|--------------------------------------------------------------------------
*/

$statusLabels = [
'generated' => 'Generated',
'issued' => 'Issued',
'cancelled' => 'Cancelled',
'paid' => 'Paid',
];

$paymentStatusLabels = [
'unpaid' => 'Unpaid',
'partial' => 'Partially Paid',
'paid' => 'Paid',
];

$slipStatus = $statusLabels[$salarySlip->status]
?? ucfirst((string) $salarySlip->status);

$paymentStatus = $paymentStatusLabels[$salarySlip->payment_status]
?? ucfirst((string) $salarySlip->payment_status);

/*
|--------------------------------------------------------------------------
| SAVED SALARY AMOUNTS
|--------------------------------------------------------------------------
| Do not calculate or modify salary amounts in this Blade.
| Display the values already saved in the SalarySlip record.
|--------------------------------------------------------------------------
*/

$workingSheetTotal = (float) (
$salarySlip->working_sheet_total_amount ?? 0
);

$grossSalary = (float) ($salarySlip->gross_salary ?? 0);
$totalDeductions = (float) ($salarySlip->total_deductions ?? 0);
$netSalary = (float) ($salarySlip->net_salary ?? 0);

/*
|--------------------------------------------------------------------------
| EARNINGS
|--------------------------------------------------------------------------
*/

$earnings = [
[
'label' => 'Basic Salary',
'amount' => $salarySlip->basic_salary,
],
[
'label' => 'Working Sheet Earnings',
'amount' => $workingSheetTotal,
],
[
'label' => 'Bonus',
'amount' => $salarySlip->bonus_amount,
],
[
'label' => 'Other Earnings',
'amount' => $salarySlip->other_earnings,
],
];

/*
|--------------------------------------------------------------------------
| DEDUCTIONS
|--------------------------------------------------------------------------
*/

$deductions = [
[
'label' => 'Advance Deduction',
'amount' => $salarySlip->advance_deduction,
],
[
'label' => 'Loan Deduction',
'amount' => $salarySlip->loan_deduction,
],
[
'label' => 'Penalty Deduction',
'amount' => $salarySlip->penalty_deduction,
],
[
'label' => 'Other Deductions',
'amount' => $salarySlip->other_deductions,
],
];

/*
|--------------------------------------------------------------------------
| WORKING SHEET BREAKDOWN VISIBILITY
|--------------------------------------------------------------------------
*/

$hasWorkingSheetBreakdown =
(float) ($salarySlip->working_sheet_base_amount ?? 0) != 0
|| (float) ($salarySlip->extra_km_amount ?? 0) != 0
|| (float) ($salarySlip->overtime_amount ?? 0) != 0
|| (float) ($salarySlip->allowance_amount ?? 0) != 0
|| (float) ($salarySlip->expense_reimbursement_amount ?? 0) != 0
|| (float) ($salarySlip->working_sheet_other_amount ?? 0) != 0;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>
        Salary Slip - {{ $salarySlip->slip_no ?? 'Driver' }}
    </title>

    <style>
        @page {
            margin: 26px 30px 34px 30px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            color: #1e293b;
            background: #ffffff;
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            line-height: 1.45;
        }

        .page {
            width: 100%;
        }

        /* HEADER */

        .header {
            width: 100%;
            border-bottom: 2px solid #1d4ed8;
            padding-bottom: 13px;
            margin-bottom: 15px;
        }

        .header-table,
        .details-table,
        .summary-table,
        .data-table,
        .footer-table,
        .columns-table,
        .net-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-cell {
            width: 42%;
            vertical-align: middle;
        }

        .logo {
            display: block;
            width: 190px;
            max-height: 58px;
        }

        .brand-fallback {
            color: #1e3a8a;
            font-size: 17px;
            font-weight: bold;
            letter-spacing: .5px;
        }

        .document-cell {
            width: 58%;
            text-align: right;
            vertical-align: middle;
        }

        .document-title {
            margin: 0;
            color: #0f172a;
            font-size: 22px;
            font-weight: bold;
            letter-spacing: .7px;
            text-transform: uppercase;
        }

        .document-subtitle {
            margin-top: 4px;
            color: #64748b;
            font-size: 9px;
        }

        .slip-meta {
            margin-top: 8px;
            color: #334155;
            font-size: 9px;
        }

        .slip-meta strong {
            color: #0f172a;
        }

        /* SECTIONS */

        .section {
            margin-top: 14px;
        }

        .section-heading {
            margin: 0 0 7px 0;
            padding: 6px 9px;
            color: #ffffff;
            background: #1e3a8a;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: .45px;
            text-transform: uppercase;
        }

        /* DRIVER DETAILS */

        .details-table td {
            width: 50%;
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .field-label {
            display: block;
            margin-bottom: 2px;
            color: #64748b;
            font-size: 8px;
            text-transform: uppercase;
        }

        .field-value {
            color: #0f172a;
            font-size: 10px;
            font-weight: bold;
        }

        /* ATTENDANCE SUMMARY */

        .summary-table {
            margin-top: 12px;
        }

        .summary-table td {
            width: 25%;
            padding: 9px 8px;
            border: 1px solid #dbeafe;
            background: #eff6ff;
            text-align: center;
            vertical-align: middle;
        }

        .summary-label {
            display: block;
            margin-bottom: 4px;
            color: #475569;
            font-size: 8px;
            text-transform: uppercase;
        }

        .summary-value {
            display: block;
            color: #1e3a8a;
            font-size: 13px;
            font-weight: bold;
        }

        /* EARNINGS AND DEDUCTIONS */

        .columns-table {
            table-layout: fixed;
        }

        .columns-table>tbody>tr>td {
            width: 50%;
            vertical-align: top;
        }

        .columns-table>tbody>tr>td:first-child {
            padding-right: 7px;
        }

        .columns-table>tbody>tr>td:last-child {
            padding-left: 7px;
        }

        .data-table th {
            padding: 7px 8px;
            color: #334155;
            background: #f1f5f9;
            border: 1px solid #dbe3ee;
            font-size: 8px;
            text-align: left;
            text-transform: uppercase;
        }

        .data-table td {
            padding: 7px 8px;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-size: 9px;
        }

        .amount {
            text-align: right !important;
            white-space: nowrap;
        }

        .total-row td {
            color: #0f172a;
            background: #f8fafc;
            font-weight: bold;
        }

        /* WORKING SHEET BREAKDOWN */

        .working-breakdown {
            margin-top: 9px;
        }

        .working-breakdown-title {
            margin: 0 0 5px 0;
            color: #334155;
            font-size: 9px;
            font-weight: bold;
        }

        /* NET SALARY */

        .net-box {
            margin-top: 14px;
            padding: 12px 14px;
            border: 1px solid #bfdbfe;
            background: #eff6ff;
        }

        .net-label {
            color: #1e3a8a;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .net-amount {
            color: #1e3a8a;
            font-size: 21px;
            font-weight: bold;
            text-align: right;
            white-space: nowrap;
        }

        .payment-note {
            margin-top: 5px;
            color: #475569;
            font-size: 8px;
        }

        /* REMARKS AND NOTICE */

        .remarks {
            padding: 8px 9px;
            border: 1px solid #e2e8f0;
            color: #475569;
            min-height: 30px;
        }

        .notice {
            margin-top: 12px;
            padding: 7px 9px;
            border-left: 3px solid #64748b;
            background: #f8fafc;
            color: #64748b;
            font-size: 8px;
        }

        /* FOOTER */

        .footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid #cbd5e1;
            color: #64748b;
            font-size: 8px;
        }

        .footer-right {
            text-align: right;
        }

        .muted {
            color: #64748b;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

    </style>
</head>

<body>
    <div class="page">

        {{-- ========================================================= --}}
        {{-- COMPANY HEADER --}}
        {{-- ========================================================= --}}

        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="logo-cell">
                        @if (!empty($logoSrc))
                        <img src="{{ $logoSrc }}" class="logo" alt="Mastermind Travels">
                        @else
                        <div class="brand-fallback">
                            MASTERMIND TRAVELS
                        </div>
                        @endif
                    </td>

                    <td class="document-cell">
                        <h1 class="document-title">
                            Salary Slip
                        </h1>

                        <div class="document-subtitle">
                            Driver Salary Statement
                        </div>

                        <div class="slip-meta">
                            <strong>Salary Month:</strong>
                            {{ $salaryPeriod }}

                            <br>

                            <strong>Slip No:</strong>
                            {{ $salarySlip->slip_no ?: '—' }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- ========================================================= --}}
        {{-- DRIVER INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="section">
            <h2 class="section-heading">
                Driver &amp; Salary Information
            </h2>

            <table class="details-table">
                <tr>
                    <td>
                        <span class="field-label">Driver Name</span>
                        <span class="field-value">{{ $driverName }}</span>
                    </td>

                    <td>
                        <span class="field-label">Driver Code</span>
                        <span class="field-value">{{ $driverCode }}</span>
                    </td>
                </tr>

                <tr>
                    <td>
                        <span class="field-label">Salary Period</span>
                        <span class="field-value">{{ $salaryPeriod }}</span>
                    </td>

                    <td>
                        <span class="field-label">Period Covered</span>
                        <span class="field-value">
                            {{ $formatDate($salarySlip->period_from) }}
                            to
                            {{ $formatDate($salarySlip->period_to) }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>
                        <span class="field-label">Slip Status</span>
                        <span class="field-value">{{ $slipStatus }}</span>
                    </td>

                    <td>
                        <span class="field-label">Payment Status</span>
                        <span class="field-value">{{ $paymentStatus }}</span>
                    </td>
                </tr>
            </table>
        </div>


        {{-- ========================================================= --}}
        {{-- ATTENDANCE SUMMARY --}}
        {{-- ========================================================= --}}

        <div class="section">
            <h2 class="section-heading">
                Attendance &amp; Duty Summary
            </h2>

            <table class="summary-table">
                <tr>
                    <td>
                        <span class="summary-label">Working Days</span>
                        <span class="summary-value">
                            {{ $formatNumber($salarySlip->total_working_days) }}
                        </span>
                    </td>

                    <td>
                        <span class="summary-label">Present Days</span>
                        <span class="summary-value">
                            {{ $formatNumber($salarySlip->present_days) }}
                        </span>
                    </td>

                    <td>
                        <span class="summary-label">Absent Days</span>
                        <span class="summary-value">
                            {{ $formatNumber($salarySlip->absent_days) }}
                        </span>
                    </td>

                    <td>
                        <span class="summary-label">Paid Days</span>
                        <span class="summary-value">
                            {{ $formatNumber($salarySlip->paid_days) }}
                        </span>
                    </td>
                </tr>
            </table>

            <table class="details-table" style="margin-top: 7px;">
                <tr>
                    <td>
                        <span class="field-label">Total Duty Days</span>
                        <span class="field-value">
                            {{ $formatNumber($salarySlip->total_duty_days) }}
                        </span>
                    </td>

                    <td>
                        <span class="field-label">Total Running (KM)</span>
                        <span class="field-value">
                            {{ $formatNumber($salarySlip->total_km) }} KM
                        </span>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <span class="field-label">Overtime Hours</span>
                        <span class="field-value">
                            {{ $formatNumber($salarySlip->overtime_hours) }}
                            Hours
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        {{-- ========================================================= --}}
        {{-- EARNINGS AND DEDUCTIONS --}}
        {{-- ========================================================= --}}

        <div class="section">
            <h2 class="section-heading">
                Salary Calculation
            </h2>

            <table class="columns-table">
                <tr>
                    {{-- EARNINGS --}}
                    <td>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Earnings</th>
                                    <th class="amount">Amount</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($earnings as $earning)
                                <tr>
                                    <td>{{ $earning['label'] }}</td>

                                    <td class="amount">
                                        {{ $formatMoney($earning['amount']) }}
                                    </td>
                                </tr>
                                @endforeach

                                <tr class="total-row">
                                    <td>Gross Salary</td>

                                    <td class="amount">
                                        {{ $formatMoney($grossSalary) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>

                    {{-- DEDUCTIONS --}}
                    <td>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Deductions</th>
                                    <th class="amount">Amount</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($deductions as $deduction)
                                <tr>
                                    <td>{{ $deduction['label'] }}</td>

                                    <td class="amount">
                                        {{ $formatMoney($deduction['amount']) }}
                                    </td>
                                </tr>
                                @endforeach

                                <tr class="total-row">
                                    <td>Total Deductions</td>

                                    <td class="amount">
                                        {{ $formatMoney($totalDeductions) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>

            {{-- ===================================================== --}}
            {{-- WORKING SHEET BREAKDOWN --}}
            {{-- ===================================================== --}}

            @if ($hasWorkingSheetBreakdown)
            <div class="working-breakdown">
                <p class="working-breakdown-title">
                    Working Sheet Amount Breakdown

                    <span class="muted">
                        (Included in Working Sheet Earnings above)
                    </span>
                </p>

                <table class="data-table">
                    <tbody>
                        <tr>
                            <td>Working Sheet Base Amount</td>

                            <td class="amount">
                                {{ $formatMoney($salarySlip->working_sheet_base_amount) }}
                            </td>

                            <td>Extra KM Amount</td>

                            <td class="amount">
                                {{ $formatMoney($salarySlip->extra_km_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <td>Overtime Amount</td>

                            <td class="amount">
                                {{ $formatMoney($salarySlip->overtime_amount) }}
                            </td>

                            <td>Allowance Amount</td>

                            <td class="amount">
                                {{ $formatMoney($salarySlip->allowance_amount) }}
                            </td>
                        </tr>

                        <tr>
                            <td>Expense Reimbursement</td>

                            <td class="amount">
                                {{ $formatMoney($salarySlip->expense_reimbursement_amount) }}
                            </td>

                            <td>Other Working Sheet Amount</td>

                            <td class="amount">
                                {{ $formatMoney($salarySlip->working_sheet_other_amount) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endif
        </div>


        {{-- ========================================================= --}}
        {{-- NET SALARY --}}
        {{-- ========================================================= --}}

        <div class="net-box">
            <table class="net-table">
                <tr>
                    <td class="net-label">
                        Net Salary Payable
                    </td>

                    <td class="net-amount">
                        {{ $formatMoney($netSalary) }}
                    </td>
                </tr>
            </table>

            <div class="payment-note">
                Payment Date:
                {{ $formatDate($salarySlip->payment_date) }}

                &nbsp; | &nbsp;

                Payment Status:
                {{ $paymentStatus }}
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- REMARKS --}}
        {{-- ========================================================= --}}

        @if (filled($salarySlip->remarks))
        <div class="section">
            <h2 class="section-heading">
                Remarks
            </h2>

            <div class="remarks">
                {{ $salarySlip->remarks }}
            </div>
        </div>
        @endif

        {{-- ========================================================= --}}
        {{-- IMPORTANT NOTICE --}}
        {{-- ========================================================= --}}

        <div class="notice">
            This salary slip is generated from the salary record maintained
            by the company.

            The salary amounts displayed above are the saved values of this
            salary slip.

            Please contact the accounts department if you have any query
            regarding this statement.
        </div>

        {{-- ========================================================= --}}
        {{-- FOOTER --}}
        {{-- ========================================================= --}}

        <div class="footer">
            <table class="footer-table">
                <tr>
                    <td>
                        Mastermind Travels
                        <br>
                        Confidential Document — For the concerned driver only.
                    </td>

                    <td class="footer-right">
                        Generated on:
                        {{ now()->format('d M Y, h:i A') }}
                        <br>
                        Salary Slip:
                        {{ $salarySlip->slip_no ?? '—' }}
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>
</html>
