{{--
|--------------------------------------------------------------------------
| DRIVER MONTHLY WORKING SHEET PDF
|--------------------------------------------------------------------------
| View:
| backend.whatsapp.driver-documents.pdf.working-sheet
|
| Expected variable:
| $workingSheetReport
|
| Data source:
| App\Services\Reports\WorkingSheetReport\WorkingSheetReportService
|--------------------------------------------------------------------------
--}}

@php
/*
|--------------------------------------------------------------------------
| REPORT DATA
|--------------------------------------------------------------------------
*/

$reportDriver = $workingSheetReport['driver'] ?? null;

$reportMonth = \Illuminate\Support\Carbon::parse(
$workingSheetReport['month'] ?? now()
);

$calendar = collect($workingSheetReport['calendar'] ?? []);
$workingSheets = collect($workingSheetReport['working_sheets'] ?? []);
$summary = $workingSheetReport['summary'] ?? [];

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

$driverName = trim(
($reportDriver->first_name ?? '')
. ' '
. ($reportDriver->last_name ?? '')
);

if ($driverName === '') {
$driverName = 'Driver';
}

$driverCode = $reportDriver->driver_code ?? '—';

/*
|--------------------------------------------------------------------------
| FORMATTING HELPERS
|--------------------------------------------------------------------------
*/

$formatMoney = static function ($amount): string {
return '₹' . number_format((float) ($amount ?? 0), 2);
};

$formatNumber = static function ($number, int $decimals = 2): string {
return number_format((float) ($number ?? 0), $decimals);
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

$formatHours = static function ($hours): string {
return number_format((float) ($hours ?? 0), 2) . ' hrs';
};

/*
|--------------------------------------------------------------------------
| STATUS LABELS
|--------------------------------------------------------------------------
*/

$statusLabels = [
'draft' => 'Draft',
'submitted' => 'Submitted',
'approved' => 'Approved',
'rejected' => 'Rejected',
'completed' => 'Completed',
];

/*
|--------------------------------------------------------------------------
| SUMMARY VALUES
|--------------------------------------------------------------------------
*/

$totalDays = (int) ($summary['total_days'] ?? $reportMonth->daysInMonth);
$markedDays = (int) ($summary['marked_days'] ?? 0);
$notMarkedDays = (int) ($summary['not_marked_days'] ?? 0);
$markingPercentage = (float) ($summary['marking_percentage'] ?? 0);

$totalSheets = (int) ($summary['total_sheets'] ?? $workingSheets->count());
$totalKm = (float) ($summary['total_km'] ?? 0);
$totalHours = (float) ($summary['total_hours'] ?? 0);
$overtimeHours = (float) ($summary['overtime_hours'] ?? 0);
$totalAmount = (float) ($summary['total_amount'] ?? 0);

/*
|--------------------------------------------------------------------------
| MONTHLY AMOUNT BREAKDOWN
|--------------------------------------------------------------------------
*/

$amountBreakdown = [
[
'label' => 'Base Amount',
'amount' => $summary['base_amount'] ?? 0,
],
[
'label' => 'Extra KM Amount',
'amount' => $summary['extra_km_amount'] ?? 0,
],
[
'label' => 'Overtime Amount',
'amount' => $summary['overtime_amount'] ?? 0,
],
[
'label' => 'Allowance Amount',
'amount' => $summary['allowance_amount'] ?? 0,
],
[
'label' => 'Expense Amount',
'amount' => $summary['expense_amount'] ?? 0,
],
[
'label' => 'Other Amount',
'amount' => $summary['other_amount'] ?? 0,
],
];

/*
|--------------------------------------------------------------------------
| CALENDAR GROUPING
|--------------------------------------------------------------------------
*/

$calendarByDate = $calendar->keyBy('date_key');

$calendarStart = $reportMonth->copy()
->startOfMonth()
->startOfWeek(\Carbon\Carbon::MONDAY);

$calendarEnd = $reportMonth->copy()
->endOfMonth()
->endOfWeek(\Carbon\Carbon::SUNDAY);

$calendarDays = [];

for (
$date = $calendarStart->copy();
$date->lte($calendarEnd);
$date->addDay()
) {
$dateKey = $date->toDateString();

$calendarDays[] = [
'date' => $date->copy(),
'date_key' => $dateKey,
'is_current_month' => $date->month === $reportMonth->month,
'data' => $calendarByDate->get($dateKey),
];
}

/*
|--------------------------------------------------------------------------
| DAILY STATUS
|--------------------------------------------------------------------------
*/

$getDayStatus = static function ($day): array {
if (!$day || !$day['is_current_month']) {
return [
'label' => '',
'class' => 'empty',
];
}

$data = $day['data'];

if (!$data || empty($data['is_marked'])) {
return [
'label' => 'No Sheet',
'class' => 'unmarked',
];
}

return [
'label' => 'Working',
'class' => 'marked',
];
};
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Working Sheet Report - {{ $driverName }}</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 25px 27px 32px 27px;
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
            font-size: 9px;
            line-height: 1.45;
        }

        .page {
            width: 100%;
        }

        /* HEADER */

        .header {
            width: 100%;
            padding-bottom: 12px;
            margin-bottom: 13px;
            border-bottom: 2px solid #1d4ed8;
        }

        .header-table,
        .driver-table,
        .summary-table,
        .data-table,
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-cell {
            width: 42%;
            vertical-align: middle;
        }

        .logo {
            width: 190px;
            max-height: 58px;
        }

        .brand-fallback {
            color: #1e3a8a;
            font-size: 17px;
            font-weight: bold;
            letter-spacing: .5px;
        }

        .title-cell {
            width: 58%;
            text-align: right;
            vertical-align: middle;
        }

        .report-kicker {
            color: #64748b;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .report-title {
            margin-top: 3px;
            color: #0f172a;
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .report-month {
            margin-top: 3px;
            color: #1d4ed8;
            font-size: 13px;
            font-weight: bold;
        }

        .report-subtitle {
            margin-top: 3px;
            color: #64748b;
            font-size: 8px;
        }

        /* DRIVER INFORMATION */

        .driver-section {
            margin-bottom: 12px;
        }

        .driver-table td {
            width: 33.33%;
            padding: 8px 10px;
            border: 1px solid #dbe3ee;
            background: #f8fafc;
        }

        .field-label {
            display: block;
            margin-bottom: 3px;
            color: #64748b;
            font-size: 8px;
            text-transform: uppercase;
        }

        .field-value {
            color: #0f172a;
            font-size: 10px;
            font-weight: bold;
        }

        /* SECTION HEADING */

        .section {
            margin-top: 13px;
        }

        .section-heading {
            margin: 0 0 7px 0;
            padding: 6px 9px;
            color: #ffffff;
            background: #1e3a8a;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        /* SUMMARY */

        .summary-table {
            margin-bottom: 8px;
        }

        .summary-table td {
            width: 16.66%;
            padding: 9px 5px;
            border: 1px solid #dbeafe;
            background: #eff6ff;
            text-align: center;
        }

        .summary-label {
            display: block;
            color: #475569;
            font-size: 7px;
            text-transform: uppercase;
        }

        .summary-value {
            display: block;
            margin-top: 4px;
            color: #1e3a8a;
            font-size: 14px;
            font-weight: bold;
        }

        .summary-foot {
            display: block;
            margin-top: 2px;
            color: #64748b;
            font-size: 7px;
        }

        /* CALENDAR */

        .calendar-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 3px;
            table-layout: fixed;
        }

        .calendar-table th {
            padding: 6px 3px;
            color: #ffffff;
            background: #334155;
            font-size: 8px;
            text-align: center;
        }

        .calendar-table td {
            height: 43px;
            padding: 4px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .calendar-table td.marked {
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .calendar-table td.unmarked {
            background: #fff7ed;
            border-color: #fed7aa;
        }

        .calendar-table td.empty {
            background: #f8fafc;
            border-color: #f1f5f9;
        }

        .calendar-date {
            display: block;
            color: #0f172a;
            font-size: 9px;
            font-weight: bold;
        }

        .calendar-status {
            display: block;
            margin-top: 3px;
            font-size: 7px;
            font-weight: bold;
        }

        .marked .calendar-status {
            color: #15803d;
        }

        .unmarked .calendar-status {
            color: #c2410c;
        }

        .calendar-detail {
            display: block;
            margin-top: 2px;
            color: #475569;
            font-size: 7px;
        }

        .calendar-legend {
            margin-top: 5px;
            color: #64748b;
            font-size: 8px;
        }

        .legend-item {
            margin-right: 13px;
        }

        /* WORKING SHEET DETAILS */

        .data-table {
            table-layout: auto;
        }

        .data-table th {
            padding: 7px 5px;
            border: 1px solid #cbd5e1;
            background: #eaf0f8;
            color: #334155;
            font-size: 7px;
            text-align: left;
            text-transform: uppercase;
        }

        .data-table td {
            padding: 6px 5px;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-size: 7.5px;
            vertical-align: top;
        }

        .data-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .nowrap {
            white-space: nowrap;
        }

        .sheet-number {
            color: #1d4ed8;
            font-weight: bold;
        }

        .status {
            display: inline-block;
            padding: 3px 5px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
        }

        .status-approved,
        .status-completed {
            color: #166534;
            background: #dcfce7;
        }

        .status-submitted {
            color: #1d4ed8;
            background: #dbeafe;
        }

        .status-rejected {
            color: #991b1b;
            background: #fee2e2;
        }

        .status-draft {
            color: #475569;
            background: #e2e8f0;
        }

        /* FINANCIAL SUMMARY */

        .financial-table {
            width: 100%;
            border-collapse: collapse;
        }

        .financial-table td {
            width: 33.33%;
            padding: 7px 9px;
            border: 1px solid #e2e8f0;
        }

        .financial-label {
            color: #64748b;
            font-size: 8px;
        }

        .financial-value {
            display: block;
            margin-top: 3px;
            color: #0f172a;
            font-size: 10px;
            font-weight: bold;
        }

        .grand-total {
            margin-top: 10px;
            padding: 12px;
            border: 1px solid #bfdbfe;
            background: #eff6ff;
        }

        .grand-total-table {
            width: 100%;
            border-collapse: collapse;
        }

        .grand-total-label {
            color: #1e3a8a;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .grand-total-value {
            color: #1e3a8a;
            font-size: 19px;
            font-weight: bold;
            text-align: right;
        }

        /* EMPTY STATE */

        .empty-state {
            padding: 15px;
            border: 1px dashed #cbd5e1;
            background: #f8fafc;
            color: #64748b;
            text-align: center;
            font-size: 9px;
        }

        /* FOOTER */

        .footer {
            margin-top: 17px;
            padding-top: 8px;
            border-top: 1px solid #cbd5e1;
            color: #64748b;
            font-size: 7.5px;
        }

        .footer-right {
            text-align: right;
        }

        /* PDF PAGE BREAK */

        .avoid-break,
        .working-sheet-row {
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

                    <td class="title-cell">
                        <div class="report-kicker">
                            Driver Document · Monthly Record
                        </div>

                        <div class="report-title">
                            Working Sheet Report
                        </div>

                        <div class="report-month">
                            {{ $reportMonth->format('F Y') }}
                        </div>

                        <div class="report-subtitle">
                            Monthly duty, working hours, running and earnings statement
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- ========================================================= --}}
        {{-- DRIVER INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="driver-section">
            <table class="driver-table">
                <tr>
                    <td>
                        <span class="field-label">Driver Name</span>
                        <span class="field-value">{{ $driverName }}</span>
                    </td>

                    <td>
                        <span class="field-label">Driver Code</span>
                        <span class="field-value">{{ $driverCode }}</span>
                    </td>

                    <td>
                        <span class="field-label">Report Period</span>
                        <span class="field-value">
                            {{ $reportMonth->copy()->startOfMonth()->format('d M Y') }}
                            to
                            {{ $reportMonth->copy()->endOfMonth()->format('d M Y') }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>


        {{-- ========================================================= --}}
        {{-- MONTHLY SUMMARY --}}
        {{-- ========================================================= --}}

        <div class="section">
            <h2 class="section-heading">
                Monthly Working Summary
            </h2>

            <table class="summary-table">
                <tr>
                    <td>
                        <span class="summary-label">Total Days</span>
                        <span class="summary-value">{{ $totalDays }}</span>
                        <span class="summary-foot">Days in month</span>
                    </td>

                    <td>
                        <span class="summary-label">Working Days</span>
                        <span class="summary-value">{{ $markedDays }}</span>
                        <span class="summary-foot">Dates with sheets</span>
                    </td>

                    <td>
                        <span class="summary-label">Not Marked</span>
                        <span class="summary-value">{{ $notMarkedDays }}</span>
                        <span class="summary-foot">No working sheet</span>
                    </td>

                    <td>
                        <span class="summary-label">Total Sheets</span>
                        <span class="summary-value">{{ $totalSheets }}</span>
                        <span class="summary-foot">Monthly records</span>
                    </td>

                    <td>
                        <span class="summary-label">Total KM</span>
                        <span class="summary-value">{{ $formatNumber($totalKm) }}</span>
                        <span class="summary-foot">Kilometres</span>
                    </td>

                    <td>
                        <span class="summary-label">Total Hours</span>
                        <span class="summary-value">{{ $formatNumber($totalHours) }}</span>
                        <span class="summary-foot">Working hours</span>
                    </td>
                </tr>
            </table>

            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="width:50%; padding:7px; border:1px solid #e2e8f0;">
                        <span class="field-label">Total Overtime</span>
                        <span class="field-value">
                            {{ $formatHours($overtimeHours) }}
                        </span>
                    </td>

                    <td style="width:50%; padding:7px; border:1px solid #e2e8f0;">
                        <span class="field-label">Working Sheet Marking</span>
                        <span class="field-value">
                            {{ $formatNumber($markingPercentage, 2) }}%
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        {{-- ========================================================= --}}
        {{-- MONTHLY CALENDAR --}}
        {{-- ========================================================= --}}

        <div class="section">
            <h2 class="section-heading">
                Monthly Working Calendar
            </h2>

            <table class="calendar-table">
                <thead>
                    <tr>
                        @foreach (
                        ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']
                        as $weekday
                        )
                        <th>{{ $weekday }}</th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @foreach (array_chunk($calendarDays, 7) as $week)
                    <tr>
                        @foreach ($week as $day)
                        @php
                        $dayStatus = $getDayStatus($day);
                        $dayData = $day['data'];
                        @endphp

                        <td class="{{ $dayStatus['class'] }}">
                            @if ($day['is_current_month'])
                            <span class="calendar-date">
                                {{ $day['date']->format('d') }}
                            </span>

                            <span class="calendar-status">
                                {{ $dayStatus['label'] }}
                            </span>

                            @if ($dayData && !empty($dayData['is_marked']))
                            <span class="calendar-detail">
                                {{ $dayData['sheet_count'] ?? 0 }}
                                Sheet(s)
                            </span>

                            <span class="calendar-detail">
                                {{ $formatNumber($dayData['total_km'] ?? 0) }}
                                KM
                            </span>

                            <span class="calendar-detail">
                                {{ $formatMoney($dayData['total_amount'] ?? 0) }}
                            </span>
                            @endif
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="calendar-legend">
                <span class="legend-item">
                    <strong style="color:#15803d;">■ Working:</strong>
                    Working Sheet recorded
                </span>

                <span class="legend-item">
                    <strong style="color:#c2410c;">■ No Sheet:</strong>
                    No Working Sheet recorded
                </span>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- DAILY WORKING SHEET DETAILS --}}
        {{-- ========================================================= --}}

        <div class="section">
            <h2 class="section-heading">
                Detailed Working Sheet Records
            </h2>

            @if ($workingSheets->isNotEmpty())
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Sr.</th>
                        <th>Work Date</th>
                        <th>Sheet No.</th>
                        <th>Duty Slip</th>
                        <th>Opening Meter</th>
                        <th>Closing Meter</th>
                        <th>Total KM</th>
                        <th>Total Hours</th>
                        <th>OT Hours</th>
                        <th>Base Amount</th>
                        <th>Extra KM</th>
                        <th>OT Amount</th>
                        <th>Allowance</th>
                        <th>Expenses</th>
                        <th>Other</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($workingSheets as $index => $sheet)
                    @php
                    $sheetStatus = $statusLabels[$sheet->status]
                    ?? ucfirst((string) $sheet->status);

                    $statusClass = 'status-' . ($sheet->status ?? 'draft');

                    $dutySlip = $sheet->dutySlip;
                    @endphp

                    <tr class="working-sheet-row">
                        <td class="text-center">
                            {{ $index + 1 }}
                        </td>

                        <td class="nowrap">
                            {{ $formatDate($sheet->work_date) }}
                        </td>

                        <td class="sheet-number nowrap">
                            {{ $sheet->sheet_no ?? '—' }}
                        </td>

                        <td class="nowrap">
                            {{ $dutySlip->slip_no ?? '—' }}
                        </td>

                        <td class="text-right nowrap">
                            {{ $formatNumber($sheet->opening_meter) }}
                        </td>

                        <td class="text-right nowrap">
                            {{ $formatNumber($sheet->closing_meter) }}
                        </td>

                        <td class="text-right nowrap">
                            {{ $formatNumber($sheet->total_km) }}
                        </td>

                        <td class="text-right nowrap">
                            {{ $formatNumber($sheet->total_hours) }}
                        </td>

                        <td class="text-right nowrap">
                            {{ $formatNumber($sheet->overtime_hours) }}
                        </td>

                        <td class="text-right nowrap">
                            {{ $formatMoney($sheet->base_amount) }}
                        </td>

                        <td class="text-right nowrap">
                            {{ $formatMoney($sheet->extra_km_amount) }}
                        </td>

                        <td class="text-right nowrap">
                            {{ $formatMoney($sheet->overtime_amount) }}
                        </td>

                        <td class="text-right nowrap">
                            {{ $formatMoney($sheet->allowance_amount) }}
                        </td>

                        <td class="text-right nowrap">
                            {{ $formatMoney($sheet->expense_amount) }}
                        </td>

                        <td class="text-right nowrap">
                            {{ $formatMoney($sheet->other_amount) }}
                        </td>

                        <td class="text-right nowrap">
                            <strong>
                                {{ $formatMoney($sheet->total_amount) }}
                            </strong>
                        </td>

                        <td class="nowrap">
                            <span class="status {{ $statusClass }}">
                                {{ $sheetStatus }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr style="background:#eaf0f8; font-weight:bold;">
                        <td colspan="6" class="text-right">
                            MONTHLY TOTAL
                        </td>

                        <td class="text-right">
                            {{ $formatNumber($summary['total_km'] ?? 0) }}
                        </td>

                        <td class="text-right">
                            {{ $formatNumber($summary['total_hours'] ?? 0) }}
                        </td>

                        <td class="text-right">
                            {{ $formatNumber($summary['overtime_hours'] ?? 0) }}
                        </td>

                        <td class="text-right">
                            {{ $formatMoney($summary['base_amount'] ?? 0) }}
                        </td>

                        <td class="text-right">
                            {{ $formatMoney($summary['extra_km_amount'] ?? 0) }}
                        </td>

                        <td class="text-right">
                            {{ $formatMoney($summary['overtime_amount'] ?? 0) }}
                        </td>

                        <td class="text-right">
                            {{ $formatMoney($summary['allowance_amount'] ?? 0) }}
                        </td>

                        <td class="text-right">
                            {{ $formatMoney($summary['expense_amount'] ?? 0) }}
                        </td>

                        <td class="text-right">
                            {{ $formatMoney($summary['other_amount'] ?? 0) }}
                        </td>

                        <td class="text-right">
                            {{ $formatMoney($summary['total_amount'] ?? 0) }}
                        </td>

                        <td>—</td>
                    </tr>
                </tfoot>
            </table>
            @else
            <div class="empty-state">
                No Working Sheet records found for
                {{ $reportMonth->format('F Y') }}.
            </div>
            @endif
        </div>


        {{-- ========================================================= --}}
        {{-- FINANCIAL BREAKDOWN --}}
        {{-- ========================================================= --}}

        <div class="section">
            <h2 class="section-heading">
                Monthly Earnings Breakdown
            </h2>

            <table class="financial-table">
                @foreach (array_chunk($amountBreakdown, 3) as $row)
                <tr>
                    @foreach ($row as $item)
                    <td>
                        <span class="financial-label">
                            {{ $item['label'] }}
                        </span>

                        <span class="financial-value">
                            {{ $formatMoney($item['amount']) }}
                        </span>
                    </td>
                    @endforeach

                    @for ($i = count($row); $i < 3; $i++) <td>
                        </td>
                        @endfor
                </tr>
                @endforeach
            </table>

            <div class="grand-total">
                <table class="grand-total-table">
                    <tr>
                        <td class="grand-total-label">
                            Total Working Sheet Earnings
                        </td>

                        <td class="grand-total-value">
                            {{ $formatMoney($totalAmount) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- REMARKS --}}
        {{-- ========================================================= --}}

        @php
        $sheetsWithRemarks = $workingSheets->filter(function ($sheet) {
        return filled($sheet->remarks);
        });
        @endphp

        @if ($sheetsWithRemarks->isNotEmpty())
        <div class="section">
            <h2 class="section-heading">
                Working Sheet Remarks
            </h2>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Work Date</th>
                        <th>Sheet No.</th>
                        <th>Remarks</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($sheetsWithRemarks as $sheet)
                    <tr>
                        <td class="nowrap">
                            {{ $formatDate($sheet->work_date) }}
                        </td>

                        <td class="nowrap">
                            {{ $sheet->sheet_no ?? '—' }}
                        </td>

                        <td>
                            {{ $sheet->remarks }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- ========================================================= --}}
        {{-- DRIVER INFORMATION NOTE --}}
        {{-- ========================================================= --}}

        <div style="
        margin-top:12px;
        padding:8px 10px;
        border-left:3px solid #64748b;
        background:#f8fafc;
        color:#64748b;
        font-size:8px;
    ">
            This report contains Working Sheet records maintained for the
            selected driver and month.

            Monthly totals are based on the Working Sheet records returned
            by the report service.

            Dates marked as "No Sheet" indicate that no Working Sheet record
            was found for that date. They do not independently indicate
            absence from duty.
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
                        Confidential Driver Working Sheet Report
                    </td>

                    <td class="footer-right">
                        Generated On:
                        {{ now()->format('d M Y, h:i A') }}
                        <br>
                        Driver:
                        {{ $driverName }}
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>
</html>
