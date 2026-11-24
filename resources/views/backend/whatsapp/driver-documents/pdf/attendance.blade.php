<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>Monthly Attendance Sheet</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 22px 25px 24px 25px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1e293b;
            font-size: 9px;
            line-height: 1.45;
            margin: 0;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #1d4ed8;
            padding-bottom: 10px;
            margin-bottom: 13px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .brand-cell {
            width: 34%;
            vertical-align: middle;
        }

        .title-cell {
            width: 66%;
            text-align: right;
            vertical-align: middle;
        }

        .brand-logo {
            display: block;
            width: 190px;
            height: auto;
        }

        .brand-fallback {
            color: #172554;
            font-size: 17px;
            font-weight: bold;
            letter-spacing: .5px;
        }

        .report-kicker {
            color: #64748b;
            font-size: 8px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .report-title {
            color: #172554;
            font-size: 17px;
            font-weight: bold;
            margin: 0;
        }

        .month-title {
            color: #2563eb;
            font-size: 11px;
            font-weight: bold;
            margin-top: 4px;
        }

        .intro {
            color: #64748b;
            font-size: 8px;
            margin-top: 3px;
        }

        .driver-card {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .driver-card td {
            border: 1px solid #dbe3ee;
            background: #f8fafc;
            padding: 8px 10px;
            vertical-align: middle;
        }

        .info-label {
            color: #64748b;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-bottom: 3px;
        }

        .info-value {
            color: #172554;
            font-size: 10px;
            font-weight: bold;
        }

        .summary {
            width: 100%;
            border-collapse: separate;
            border-spacing: 6px 0;
            margin: 0 0 15px -6px;
        }

        .summary td {
            width: 25%;
            border: 1px solid #dbe3ee;
            background: #ffffff;
            padding: 8px 10px;
            text-align: center;
        }

        .summary-label {
            color: #64748b;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .35px;
        }

        .summary-value {
            color: #172554;
            font-size: 17px;
            font-weight: bold;
            margin-top: 3px;
        }

        .summary-foot {
            color: #64748b;
            font-size: 7px;
            margin-top: 2px;
        }

        .section-heading {
            width: 100%;
            margin-bottom: 7px;
        }

        .section-title {
            color: #172554;
            font-size: 11px;
            font-weight: bold;
        }

        .section-subtitle {
            color: #64748b;
            font-size: 7px;
            text-align: right;
        }

        .calendar {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .calendar th {
            width: 14.285%;
            background: #172554;
            color: #ffffff;
            border: 1px solid #172554;
            padding: 7px 3px;
            text-align: center;
            font-size: 8px;
            font-weight: bold;
        }

        .calendar td {
            width: 14.285%;
            height: 67px;
            padding: 5px;
            border: 1px solid #d5deea;
            vertical-align: top;
            background: #ffffff;
        }

        .calendar td.empty-day {
            background: #f8fafc;
        }

        .day-number {
            color: #172554;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .status {
            display: block;
            padding: 3px 4px;
            margin-bottom: 4px;
            border-radius: 2px;
            font-size: 7px;
            font-weight: bold;
        }

        .present {
            color: #166534;
            background: #dcfce7;
        }

        .absent {
            color: #991b1b;
            background: #fee2e2;
        }

        .leave {
            color: #92400e;
            background: #fef3c7;
        }

        .holiday {
            color: #1e40af;
            background: #dbeafe;
        }

        .pending {
            color: #854d0e;
            background: #fef9c3;
        }

        .other {
            color: #475569;
            background: #e2e8f0;
        }

        .not-marked {
            color: #64748b;
            background: #f1f5f9;
        }

        .time-details {
            color: #475569;
            font-size: 7px;
            line-height: 1.55;
        }

        .legend {
            margin-top: 10px;
            padding: 7px 9px;
            border: 1px solid #dbe3ee;
            background: #fbfdff;
        }

        .legend-title {
            color: #334155;
            font-size: 7px;
            font-weight: bold;
            margin-right: 8px;
        }

        .legend-item {
            display: inline-block;
            margin-right: 12px;
            color: #475569;
            font-size: 7px;
        }

        .legend-dot {
            display: inline-block;
            width: 7px;
            height: 7px;
            margin-right: 3px;
            border-radius: 7px;
        }

        .dot-present {
            background: #22c55e;
        }

        .dot-absent {
            background: #ef4444;
        }

        .dot-leave {
            background: #f59e0b;
        }

        .dot-holiday {
            background: #3b82f6;
        }

        .dot-pending {
            background: #eab308;
        }

        .dot-not-marked {
            background: #94a3b8;
        }

        .note {
            margin-top: 9px;
            padding: 7px 9px;
            background: #f8fafc;
            border-left: 3px solid #94a3b8;
            color: #475569;
            font-size: 7px;
        }

        .footer {
            margin-top: 12px;
            padding-top: 6px;
            border-top: 1px solid #dbe3ee;
            color: #64748b;
            font-size: 7px;
        }

        .footer-right {
            float: right;
            text-align: right;
        }

        .confidential {
            color: #334155;
            font-weight: bold;
        }
    </style>
</head>

<body>

    @php
        /*
        |--------------------------------------------------------------------------
        | REPORT DATA
        |--------------------------------------------------------------------------
        */

        $driver = $attendanceReport['driver']
            ?? ($selectedDriver ?? null);

        $summary = is_array($attendanceReport['summary'] ?? null)
            ? $attendanceReport['summary']
            : [];

        $calendar = collect(
            $attendanceReport['calendar'] ?? []
        );

        /*
        |--------------------------------------------------------------------------
        | DRIVER NAME
        |--------------------------------------------------------------------------
        */

        $driverName = trim(
            (string) ($driver->first_name ?? '') . ' ' .
            (string) ($driver->last_name ?? '')
        );

        if ($driverName === '') {
            $driverName = $driver?->user?->name ?? 'Driver';
        }

        /*
        |--------------------------------------------------------------------------
        | REPORT MONTH
        |--------------------------------------------------------------------------
        */

        $monthDate = $monthDate ?? now();

        $monthName = $monthDate->format('F Y');

        /*
        |--------------------------------------------------------------------------
        | SAFE SUMMARY VALUES
        |--------------------------------------------------------------------------
        */

        $totalDays = max(
            0,
            (int) ($summary['total_days'] ?? $monthDate->daysInMonth)
        );

        $markedDays = max(
            0,
            (int) ($summary['marked_days'] ?? 0)
        );

        $notMarkedDays = max(
            0,
            (int) ($summary['not_marked_days'] ?? 0)
        );

        $markingPercentage = max(
            0,
            min(
                100,
                (float) ($summary['marking_percentage'] ?? 0)
            )
        );

        /*
        |--------------------------------------------------------------------------
        | COMPANY LOGO - LOCAL BASE64
        |--------------------------------------------------------------------------
        |
        | Logo:
        | public/backend/assets/img/logo/mastermind-logo.png
        |
        | Embedding the logo avoids external URL dependency in DomPDF.
        */

        $logoPath = public_path(
            'backend/assets/img/logo/mastermind-logo.png'
        );

        $logoBase64 = null;

        if (is_file($logoPath) && is_readable($logoPath)) {

            $logoContents = @file_get_contents($logoPath);

            if (
                $logoContents !== false &&
                $logoContents !== ''
            ) {
                $logoBase64 = base64_encode($logoContents);
            }
        }

        $logoSrc = $logoBase64
            ? 'data:image/png;base64,' . $logoBase64
            : null;

        /*
        |--------------------------------------------------------------------------
        | CALENDAR PREPARATION
        |--------------------------------------------------------------------------
        |
        | Monday-first calendar.
        */

        $leadingBlankDays = (int) $monthDate->format('N') - 1;

        $calendarCells = array_merge(
            array_fill(0, $leadingBlankDays, null),
            $calendar->values()->all()
        );

        $trailingBlankDays = (
            7 - (count($calendarCells) % 7)
        ) % 7;

        if ($trailingBlankDays > 0) {

            $calendarCells = array_merge(
                $calendarCells,
                array_fill(0, $trailingBlankDays, null)
            );
        }

        $weeks = array_chunk($calendarCells, 7);

        /*
        |--------------------------------------------------------------------------
        | ATTENDANCE STATUS LABELS
        |--------------------------------------------------------------------------
        */

        $statusLabels = [
            'present' => 'Present',
            'present_late' => 'Present (Late)',
            'present_early_exit' => 'Present (Early Exit)',

            'absent' => 'Absent',

            'leave' => 'Leave',
            'maternity_leave' => 'Maternity Leave',
            'sick_leave' => 'Sick Leave',
            'casual_leave' => 'Casual Leave',
            'paid_leave' => 'Paid Leave',
            'unpaid_leave' => 'Unpaid Leave',
            'comp_off' => 'Comp Off',

            'holiday' => 'Holiday',
            'weekly_off' => 'Weekly Off',
            'restricted_holiday' => 'Restricted Holiday',

            'pending' => 'Pending',
            'missing_punch' => 'Missing Punch',
        ];

        $leaveStatuses = [
            'leave',
            'maternity_leave',
            'sick_leave',
            'casual_leave',
            'paid_leave',
            'unpaid_leave',
            'comp_off',
        ];

        $holidayStatuses = [
            'holiday',
            'weekly_off',
            'restricted_holiday',
        ];

        $presentStatuses = [
            'present',
            'present_late',
            'present_early_exit',
        ];

        $pendingStatuses = [
            'pending',
            'missing_punch',
        ];
    @endphp


    {{-- ============================================================
         COMPANY HEADER
    ============================================================ --}}

    <div class="header">

        <table class="header-table">

            <tr>

                <td class="brand-cell">

                    @if (!empty($logoSrc))

                        <img
                            class="brand-logo"
                            src="{{ $logoSrc }}"
                            alt="Mastermind Travels"
                        >

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
                        MONTHLY ATTENDANCE SHEET
                    </div>

                    <div class="month-title">
                        {{ $monthName }}
                    </div>

                    <div class="intro">
                        Your monthly attendance overview
                    </div>

                </td>

            </tr>

        </table>

    </div>


    {{-- ============================================================
         DRIVER INFORMATION
    ============================================================ --}}

    <table class="driver-card">

        <tr>

            <td width="46%">

                <div class="info-label">
                    Driver Name
                </div>

                <div class="info-value">
                    {{ $driverName }}
                </div>

            </td>


            <td width="27%">

                <div class="info-label">
                    Driver Code
                </div>

                <div class="info-value">
                    {{ $driver->driver_code ?? 'N/A' }}
                </div>

            </td>


            <td width="27%">

                <div class="info-label">
                    Report Period
                </div>

                <div class="info-value">
                    {{ $monthName }}
                </div>

            </td>

        </tr>

    </table>

        {{-- ============================================================
         ATTENDANCE SUMMARY
    ============================================================ --}}

    <table class="summary">

        <tr>

            <td>

                <div class="summary-label">
                    Total Days
                </div>

                <div class="summary-value">
                    {{ $totalDays }}
                </div>

                <div class="summary-foot">
                    Days in this month
                </div>

            </td>


            <td>

                <div class="summary-label">
                    Marked Days
                </div>

                <div class="summary-value">
                    {{ $markedDays }}
                </div>

                <div class="summary-foot">
                    Attendance recorded
                </div>

            </td>


            <td>

                <div class="summary-label">
                    Not Marked
                </div>

                <div class="summary-value">
                    {{ $notMarkedDays }}
                </div>

                <div class="summary-foot">
                    No attendance entry
                </div>

            </td>


            <td>

                <div class="summary-label">
                    Marking Percentage
                </div>

                <div class="summary-value">
                    {{ number_format($markingPercentage, 1) }}%
                </div>

                <div class="summary-foot">
                    Attendance records completed
                </div>

            </td>

        </tr>

    </table>


    {{-- ============================================================
         CALENDAR SECTION HEADING
    ============================================================ --}}

    <table class="section-heading">

        <tr>

            <td class="section-title">
                Monthly Attendance Calendar
            </td>

            <td class="section-subtitle">
                Monday to Sunday · One box per date
            </td>

        </tr>

    </table>


    {{-- ============================================================
         MONTHLY ATTENDANCE CALENDAR
    ============================================================ --}}

    <table class="calendar">

        <thead>

            <tr>
                <th>MONDAY</th>
                <th>TUESDAY</th>
                <th>WEDNESDAY</th>
                <th>THURSDAY</th>
                <th>FRIDAY</th>
                <th>SATURDAY</th>
                <th>SUNDAY</th>
            </tr>

        </thead>


        <tbody>

            @forelse ($weeks as $week)

                <tr>

                    @foreach ($week as $day)

                        @if ($day === null)

                            <td class="empty-day"></td>

                        @else

                            @php
                                /*
                                |--------------------------------------------------------------------------
                                | DAY DATA
                                |--------------------------------------------------------------------------
                                */

                                $dayStatus = strtolower(
                                    trim((string) ($day['status'] ?? ''))
                                );

                                $isMarked = (bool) ($day['is_marked'] ?? false);

                                $dayNumber = $day['day'] ?? '';

                                /*
                                |--------------------------------------------------------------------------
                                | STATUS CLASS
                                |--------------------------------------------------------------------------
                                */

                                $statusClass = 'other';

                                if (!$isMarked) {

                                    $statusClass = 'not-marked';

                                } elseif (in_array($dayStatus, $presentStatuses, true)) {

                                    $statusClass = 'present';

                                } elseif ($dayStatus === 'absent') {

                                    $statusClass = 'absent';

                                } elseif (in_array($dayStatus, $leaveStatuses, true)) {

                                    $statusClass = 'leave';

                                } elseif (in_array($dayStatus, $holidayStatuses, true)) {

                                    $statusClass = 'holiday';

                                } elseif (in_array($dayStatus, $pendingStatuses, true)) {

                                    $statusClass = 'pending';
                                }

                                /*
                                |--------------------------------------------------------------------------
                                | STATUS LABEL
                                |--------------------------------------------------------------------------
                                */

                                if (!$isMarked) {

                                    $statusLabel = 'Not Marked';

                                } else {

                                    $statusLabel = $statusLabels[$dayStatus]
                                        ?? ucwords(str_replace('_', ' ', $dayStatus));

                                    if ($statusLabel === '') {
                                        $statusLabel = 'Unknown Status';
                                    }
                                }

                                /*
                                |--------------------------------------------------------------------------
                                | TIME DETAILS
                                |--------------------------------------------------------------------------
                                */

                                $inTime = $day['in_time'] ?? null;

                                $outTime = $day['out_time'] ?? null;

                                $totalHours = $day['total_hours'] ?? null;
                            @endphp


                            <td>

                                <div class="day-number">
                                    {{ $dayNumber }}
                                </div>


                                <div class="status {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </div>


                                @if ($isMarked)

                                    <div class="time-details">

                                        @if (!empty($inTime))

                                            In: {{ $inTime }}<br>

                                        @endif


                                        @if (!empty($outTime))

                                            Out: {{ $outTime }}<br>

                                        @endif


                                        @if (!empty($totalHours))

                                            Hours: {{ $totalHours }}

                                        @endif

                                    </div>

                                @endif

                            </td>

                        @endif

                    @endforeach

                </tr>

            @empty

                <tr>

                    <td colspan="7" class="empty-day">
                        No attendance calendar data available.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- ============================================================
         ATTENDANCE LEGEND
    ============================================================ --}}

    <div class="legend">

        <span class="legend-title">
            ATTENDANCE LEGEND
        </span>


        <span class="legend-item">
            <span class="legend-dot dot-present"></span>
            Present
        </span>


        <span class="legend-item">
            <span class="legend-dot dot-absent"></span>
            Absent
        </span>


        <span class="legend-item">
            <span class="legend-dot dot-leave"></span>
            Leave
        </span>


        <span class="legend-item">
            <span class="legend-dot dot-holiday"></span>
            Holiday / Weekly Off
        </span>


        <span class="legend-item">
            <span class="legend-dot dot-pending"></span>
            Pending / Missing Punch
        </span>


        <span class="legend-item">
            <span class="legend-dot dot-not-marked"></span>
            Not Marked
        </span>

    </div>

        {{-- ============================================================
         IMPORTANT NOTE
    ============================================================ --}}

    <div class="note">

        <strong>Please note:</strong>

        “Not Marked” means attendance has not been recorded
        for that date. It does not automatically mean Absent.

        This report reflects the attendance records available
        when the PDF was generated.

    </div>


    {{-- ============================================================
         FOOTER
    ============================================================ --}}

    <div class="footer">

        Generated on:
        {{ now()->format('d M Y, h:i A') }}


        <span class="footer-right">

            <span class="confidential">
                Mastermind Travels
            </span>

            &nbsp;|&nbsp;

            Confidential Driver Attendance Record

        </span>

    </div>


</body>

</html>
