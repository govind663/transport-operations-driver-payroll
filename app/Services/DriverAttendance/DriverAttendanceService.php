<?php

namespace App\Services\DriverAttendance;

use App\Models\DriverAttendance;
use App\Models\WorkingSheet;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use RuntimeException;

class DriverAttendanceService
{
    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    | These values can later be moved to config/hrms.php.
    |
    */

    /**
     * Minimum hours required for a full day.
     */
    protected const FULL_DAY_HOURS = 8.00;

    /**
     * Minimum hours required for a half day.
     */
    protected const HALF_DAY_HOURS = 4.00;


    /*
    |--------------------------------------------------------------------------
    | SYNC FROM WORKING SHEET
    |--------------------------------------------------------------------------
    |
    | Automatically creates / updates driver attendance
    | from a Working Sheet.
    |
    */

    public function syncFromWorkingSheet(
        WorkingSheet $workingSheet
    ): DriverAttendance {

        return DB::transaction(function () use (
            $workingSheet
        ) {

            /*
            |--------------------------------------------------------------------------
            | Reload Required Relationships
            |--------------------------------------------------------------------------
            */

            $workingSheet->loadMissing([
                'dutySlip.dutyAssignment',
                'driver',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Driver Validation
            |--------------------------------------------------------------------------
            */

            if (!$workingSheet->driver_id) {

                throw new RuntimeException(
                    'Driver is required to create driver attendance.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Driver Validation - Active Driver
            |--------------------------------------------------------------------------
            */

            if (!$workingSheet->driver) {

                throw new RuntimeException(
                    'Selected driver does not exist.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Attendance Date Validation
            |--------------------------------------------------------------------------
            */

            if (!$workingSheet->work_date) {

                throw new RuntimeException(
                    'Work date is required to create driver attendance.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Existing Attendance
            |--------------------------------------------------------------------------
            |
            | One driver should have only one attendance
            | record for a particular date.
            |
            */

            $driverId = (int) $workingSheet->driver_id;

            $attendanceDate = $workingSheet->work_date instanceof \DateTimeInterface
                ? $workingSheet->work_date->format('Y-m-d')
                : Carbon::parse($workingSheet->work_date)->toDateString();

            // Find this sheet's own generated attendance, including soft-deleted rows.
            // The database unique key still reserves a soft-deleted driver/date pair.
            $linkedAttendance = DriverAttendance::withTrashed()
                ->where('working_sheet_id', $workingSheet->id)
                ->lockForUpdate()
                ->first();

            if ($linkedAttendance && $linkedAttendance->source !== DriverAttendance::SOURCE_WORKING_SHEET) {

                $sameIdentity =
                    (int) $linkedAttendance->driver_id === $driverId &&
                    Carbon::parse($linkedAttendance->attendance_date)->toDateString() === $attendanceDate;

                // A manual/system/import record is authoritative; never overwrite it automatically.
                if (!$linkedAttendance->trashed() && $sameIdentity) {
                    return $linkedAttendance->fresh([
                        'driver',
                        'workingSheet',
                        'createdBy',
                        'updatedBy',
                    ]) ?? $linkedAttendance;
                }

                throw new RuntimeException(
                    'This Working Sheet is linked to a manually managed or non-Working-Sheet attendance. Resolve that attendance before changing the driver or date.'
                );
            }

            // Do not overwrite a second attendance row for the target driver/date.
            $attendanceForDate = DriverAttendance::withTrashed()
                ->where('driver_id', $driverId)
                ->whereDate('attendance_date', $attendanceDate)
                ->when(
                    $linkedAttendance,
                    fn ($query) => $query->where('id', '!=', $linkedAttendance->id)
                )
                ->lockForUpdate()
                ->first();

            if ($attendanceForDate) {

                if ($attendanceForDate->trashed()) {
                    throw new RuntimeException(
                        'A deleted attendance record already occupies this driver/date. Resolve it before syncing again.'
                    );
                }

                if ($attendanceForDate->source === DriverAttendance::SOURCE_WORKING_SHEET) {
                    throw new RuntimeException(
                        'Another Working Sheet already has attendance for this driver and date. Resolve the duplicate before saving this sheet.'
                    );
                }

                // If this sheet already owns an older generated row, do not leave it stale.
                if ($linkedAttendance) {
                    throw new RuntimeException(
                        'An attendance record already exists for the new driver/date. Resolve it before changing this Working Sheet.'
                    );
                }

                // Manual/system/import attendance takes precedence over a new auto-sync.
                return $attendanceForDate->fresh([
                    'driver',
                    'workingSheet',
                    'createdBy',
                    'updatedBy',
                ]) ?? $attendanceForDate;
            }

            $attendance = $linkedAttendance;


            /*
            |--------------------------------------------------------------------------
            | Time Information
            |--------------------------------------------------------------------------
            |
            | Working Sheet currently does not have
            | in_time / out_time fields.
            |
            | Therefore Duty Slip times are used.
            |
            */

            $inTime = null;

            $outTime = null;


            if ($workingSheet->dutySlip) {

                $inTime =
                    $this->normalizeTimeForStorage(
                        $workingSheet->dutySlip->start_time
                    );

                $outTime =
                    $this->normalizeTimeForStorage(
                        $workingSheet->dutySlip->end_time
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Total Hours
            |--------------------------------------------------------------------------
            */

            $totalHours = null;


            if (
                $workingSheet->total_hours !== null &&
                $workingSheet->total_hours !== ''
            ) {

                $totalHours = round(
                    max(
                        0,
                        (float) $workingSheet->total_hours
                    ),
                    2
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Attendance Status
            |--------------------------------------------------------------------------
            */

            $status = $this->determineStatus(
                $workingSheet,
                $inTime,
                $outTime,
                $totalHours
            );


            /*
            |--------------------------------------------------------------------------
            | Attendance Data
            |--------------------------------------------------------------------------
            */

            $attendanceData = [

                'driver_id' =>
                    $driverId,

                'attendance_date' =>
                    $attendanceDate,

                'status' =>
                    $status,

                'in_time' =>
                    $inTime,

                'out_time' =>
                    $outTime,

                'total_hours' =>
                    $totalHours,

                'working_sheet_id' =>
                    $workingSheet->id,

                'source' =>
                    DriverAttendance::SOURCE_WORKING_SHEET,

                'updated_by' =>
                    Auth::id(),

            ];


            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            $attendanceData['remarks'] =
                $this->generateRemarks(
                    $workingSheet,
                    $status
                );


            /*
            |--------------------------------------------------------------------------
            | Create / Update Attendance
            |--------------------------------------------------------------------------
            */

            if ($attendance) {

                // This is the same Working Sheet's derived record, so restoring it is safe.
                if ($attendance->trashed()) {
                    $attendance->restore();
                }

                $attendance->fill($attendanceData);
                $attendance->save();

            } else {

                $attendanceData['created_by'] =
                    Auth::id();

                try {
                    $attendance = DriverAttendance::create($attendanceData);
                } catch (QueryException $exception) {

                    // A concurrent request may have inserted the unique driver/date row first.
                    $racedAttendance = DriverAttendance::withTrashed()
                        ->where('driver_id', $driverId)
                        ->whereDate('attendance_date', $attendanceDate)
                        ->lockForUpdate()
                        ->first();

                    if (!$racedAttendance) {
                        throw $exception;
                    }

                    if ($racedAttendance->trashed()) {
                        throw new RuntimeException(
                            'A deleted attendance record already occupies this driver/date. Resolve it before retrying sync.',
                            0,
                            $exception
                        );
                    }

                    if ($racedAttendance->source === DriverAttendance::SOURCE_WORKING_SHEET) {
                        if ((int) $racedAttendance->working_sheet_id !== (int) $workingSheet->id) {
                            throw new RuntimeException(
                                'Another Working Sheet already has attendance for this driver and date.',
                                0,
                                $exception
                            );
                        }

                        unset($attendanceData['created_by']);
                        $racedAttendance->fill($attendanceData);
                        $racedAttendance->save();
                        $attendance = $racedAttendance;
                    } else {
                        // Preserve manual/system/import attendance created by the concurrent request.
                        return $racedAttendance->fresh([
                            'driver',
                            'workingSheet',
                            'createdBy',
                            'updatedBy',
                        ]) ?? $racedAttendance;
                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Return Fresh Attendance
            |--------------------------------------------------------------------------
            */

            return $attendance->fresh([
                'driver',
                'workingSheet',
                'createdBy',
                'updatedBy',
            ]);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | DETERMINE STATUS
    |--------------------------------------------------------------------------
    */

    protected function determineStatus(
        WorkingSheet $workingSheet,
        $inTime = null,
        $outTime = null,
        ?float $totalHours = null
    ): string {

        /*
        |--------------------------------------------------------------------------
        | Rejected
        |--------------------------------------------------------------------------
        |
        | Rejected Working Sheet should not create
        | a confirmed Present attendance.
        |
        */

        if (
            $workingSheet->status ===
            WorkingSheet::STATUS_REJECTED
        ) {

            return DriverAttendance::STATUS_PENDING;
        }


        /*
        |--------------------------------------------------------------------------
        | Draft
        |--------------------------------------------------------------------------
        */

        if (
            $workingSheet->status ===
            WorkingSheet::STATUS_DRAFT
        ) {

            return DriverAttendance::STATUS_PENDING;
        }


        /*
        |--------------------------------------------------------------------------
        | Valid Attendance Status
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $workingSheet->status,
                [
                    WorkingSheet::STATUS_SUBMITTED,
                    WorkingSheet::STATUS_APPROVED,
                    WorkingSheet::STATUS_COMPLETED,
                ],
                true
            )
        ) {

            return DriverAttendance::STATUS_PENDING;
        }


        /*
        |--------------------------------------------------------------------------
        | Missing Punch
        |--------------------------------------------------------------------------
        |
        | If duty timing is not available,
        | attendance cannot be completely verified.
        |
        */

        if (
            !$inTime ||
            !$outTime
        ) {

            return DriverAttendance::STATUS_MISSING_PUNCH;
        }


        /*
        |--------------------------------------------------------------------------
        | Missing Total Hours
        |--------------------------------------------------------------------------
        */

        if ($totalHours === null) {

            return DriverAttendance::STATUS_MISSING_PUNCH;
        }


        /*
        |--------------------------------------------------------------------------
        | Half Day
        |--------------------------------------------------------------------------
        */

        if ($totalHours < self::HALF_DAY_HOURS) {

            return DriverAttendance::STATUS_ABSENT;
        }

        if ($totalHours < self::FULL_DAY_HOURS) {

            return DriverAttendance::STATUS_HALF_DAY;
        }


        /*
        |--------------------------------------------------------------------------
        | Determine Late / Early Exit
        |--------------------------------------------------------------------------
        |
        | Current structure does not have separate
        | employee/driver shift timings.
        |
        | Therefore late / early exit should only be
        | calculated when reporting expectations are
        | available from Duty Slip.
        |
        */

        $late = $this->isLate(
            $workingSheet
        );

        $earlyExit = $this->isEarlyExit(
            $workingSheet
        );


        /*
        |--------------------------------------------------------------------------
        | Late + Early Exit
        |--------------------------------------------------------------------------
        */

        if (
            $late &&
            $earlyExit
        ) {

            /*
             * Current DriverAttendance status list does not
             * contain "present_late_early_exit".
             *
             * Therefore use late as primary status.
             */
            return DriverAttendance::STATUS_LATE;
        }


        /*
        |--------------------------------------------------------------------------
        | Late
        |--------------------------------------------------------------------------
        */

        if ($late) {

            return DriverAttendance::STATUS_LATE;
        }


        /*
        |--------------------------------------------------------------------------
        | Early Exit
        |--------------------------------------------------------------------------
        */

        if ($earlyExit) {

            return DriverAttendance::STATUS_EARLY_EXIT;
        }


        /*
        |--------------------------------------------------------------------------
        | Full Day Present
        |--------------------------------------------------------------------------
        */

        if (
            $totalHours >= self::FULL_DAY_HOURS
        ) {

            return DriverAttendance::STATUS_PRESENT;
        }


        /*
        |--------------------------------------------------------------------------
        | Remaining Valid Duty
        |--------------------------------------------------------------------------
        */

        if ($totalHours > 0) {

            return DriverAttendance::STATUS_PRESENT;
        }


        /*
        |--------------------------------------------------------------------------
        | Fallback
        |--------------------------------------------------------------------------
        */

        return DriverAttendance::STATUS_PENDING;
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK LATE
    |--------------------------------------------------------------------------
    */

    protected function isLate(
        WorkingSheet $workingSheet
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | Duty Slip
        |--------------------------------------------------------------------------
        */

        if (!$workingSheet->dutySlip) {

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Reporting Time
        |--------------------------------------------------------------------------
        */

        $reportingTime =
            $workingSheet
                ->dutySlip
                ->dutyAssignment
                ?->reporting_time;


        /*
        |--------------------------------------------------------------------------
        | Actual Start Time
        |--------------------------------------------------------------------------
        */

        $actualStartTime =
            $workingSheet
                ->dutySlip
                ->start_time;


        if (
            !$reportingTime ||
            !$actualStartTime
        ) {

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Compare Times
        |--------------------------------------------------------------------------
        */

        try {
            $actualSeconds = $this->timeToSeconds($actualStartTime);
            $reportingSeconds = $this->timeToSeconds($reportingTime);
        } catch (RuntimeException $exception) {
            return false;
        }

        if ($actualSeconds === null || $reportingSeconds === null) {
            return false;
        }

        return $actualSeconds > $reportingSeconds;
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK EARLY EXIT
    |--------------------------------------------------------------------------
    */

    protected function isEarlyExit(
        WorkingSheet $workingSheet
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | Duty Slip
        |--------------------------------------------------------------------------
        */

        if (!$workingSheet->dutySlip) {

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Expected End Time
        |--------------------------------------------------------------------------
        */

        $expectedEndTime =
            $workingSheet
                ->dutySlip
                ->dutyAssignment
                ?->expected_end_time;

        $actualEndTime =
            $workingSheet
                ->dutySlip
                ->end_time;


        /*
        |--------------------------------------------------------------------------
        | Actual End Time
        |--------------------------------------------------------------------------
        |
        | Current DutySlip contains the actual recorded
        | end_time according to the current model.
        |
        | Without separate expected end time,
        | early exit cannot be reliably calculated.
        |
        */

        if (!$expectedEndTime) {

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Compare Actual End With Expected End
        |--------------------------------------------------------------------------
        |
        | If Duty Assignment does not expose expected_end_time, early-exit
        | cannot be calculated and safely remains false.
        |
        */

        try {
            $actualSeconds = $this->timeToSeconds($actualEndTime);
            $expectedSeconds = $this->timeToSeconds($expectedEndTime);
        } catch (RuntimeException $exception) {
            return false;
        }

        if ($actualSeconds === null || $expectedSeconds === null) {
            return false;
        }

        return $actualSeconds < $expectedSeconds;
    }


    /**
     * Convert TIME or DATETIME input to HH:MM:SS before saving to SQL TIME columns.
     */
    protected function normalizeTimeForStorage($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return Carbon::parse($value)->format('H:i:s');
        } catch (\Throwable $exception) {
            throw new RuntimeException(
                'Duty Slip contains an invalid attendance time value.',
                0,
                $exception
            );
        }
    }


    /**
     * Convert a TIME or DATETIME input to seconds since midnight.
     */
    protected function timeToSeconds($value): ?int
    {
        $time = $this->normalizeTimeForStorage($value);

        if ($time === null) {
            return null;
        }

        [$hours, $minutes, $seconds] = array_map('intval', explode(':', $time));

        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE REMARKS
    |--------------------------------------------------------------------------
    */

    protected function generateRemarks(
        WorkingSheet $workingSheet,
        string $status
    ): ?string {

        return match ($status) {

            DriverAttendance::STATUS_PRESENT =>
                'Attendance automatically generated from Working Sheet.',

            DriverAttendance::STATUS_HALF_DAY =>
                'Half day attendance automatically calculated from Working Sheet hours.',

            DriverAttendance::STATUS_ABSENT =>
                'Attendance hours are below the minimum half-day threshold.',

            DriverAttendance::STATUS_LATE =>
                'Late attendance automatically detected from duty reporting time.',

            DriverAttendance::STATUS_EARLY_EXIT =>
                'Early exit attendance automatically detected.',

            DriverAttendance::STATUS_MISSING_PUNCH =>
                'Attendance generated but required duty timing is missing.',

            DriverAttendance::STATUS_PENDING =>
                'Attendance is pending Working Sheet processing.',

            default =>
                'Attendance automatically generated from Working Sheet.',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | GET DRIVER ATTENDANCE
    |--------------------------------------------------------------------------
    */

    public function getDriverAttendance(
        int $driverId,
        string $date
    ): ?DriverAttendance {

        return DriverAttendance::query()
            ->with([
                'driver',
                'workingSheet',
                'createdBy',
                'updatedBy',
            ])
            ->where(
                'driver_id',
                $driverId
            )
            ->whereDate(
                'attendance_date',
                $date
            )
            ->first();
    }


    /*
    |--------------------------------------------------------------------------
    | GET ATTENDANCE FOR DATE
    |--------------------------------------------------------------------------
    */

    public function getAttendanceForDate(
        string $date
    ) {

        return DriverAttendance::query()
            ->with([
                'driver',
                'workingSheet',
                'createdBy',
                'updatedBy',
            ])
            ->whereDate(
                'attendance_date',
                $date
            )
            ->orderBy(
                'driver_id'
            )
            ->orderByDesc(
                'id'
            )
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | GET DRIVER ATTENDANCE HISTORY
    |--------------------------------------------------------------------------
    */

    public function getDriverAttendanceHistory(
        int $driverId,
        ?string $fromDate = null,
        ?string $toDate = null
    ) {

        $query = DriverAttendance::query()
            ->with([
                'driver',
                'workingSheet',
                'createdBy',
                'updatedBy',
            ])
            ->where(
                'driver_id',
                $driverId
            );


        /*
        |--------------------------------------------------------------------------
        | From Date
        |--------------------------------------------------------------------------
        */

        if ($fromDate) {

            $query->whereDate(
                'attendance_date',
                '>=',
                $fromDate
            );
        }


        /*
        |--------------------------------------------------------------------------
        | To Date
        |--------------------------------------------------------------------------
        */

        if ($toDate) {

            $query->whereDate(
                'attendance_date',
                '<=',
                $toDate
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Latest First
        |--------------------------------------------------------------------------
        */

        return $query
            ->orderByDesc(
                'attendance_date'
            )
            ->orderByDesc(
                'id'
            )
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | MARK MANUAL ATTENDANCE
    |--------------------------------------------------------------------------
    |
    | Used by HR / Admin for manual attendance.
    |
    */

    public function markManualAttendance(
        array $data
    ): DriverAttendance {

        return DB::transaction(function () use ($data) {

            /*
            |--------------------------------------------------------------------------
            | Required Fields
            |--------------------------------------------------------------------------
            */

            if (
                empty($data['driver_id'])
            ) {

                throw new RuntimeException(
                    'Driver is required for manual attendance.'
                );
            }


            if (
                empty($data['attendance_date'])
            ) {

                throw new RuntimeException(
                    'Attendance date is required.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Source
            |--------------------------------------------------------------------------
            */

            $data['source'] =
                DriverAttendance::SOURCE_MANUAL;


            /*
            |--------------------------------------------------------------------------
            | Working Sheet
            |--------------------------------------------------------------------------
            |
            | Manual attendance is not linked to
            | a Working Sheet unless explicitly provided.
            |
            */

            $data['working_sheet_id'] =
                isset($data['working_sheet_id']) &&
                $data['working_sheet_id'] !== ''
                    ? (int) $data['working_sheet_id']
                    : null;


            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            $data['updated_by'] =
                Auth::id();


            /*
            |--------------------------------------------------------------------------
            | Total Hours
            |--------------------------------------------------------------------------
            */

            if (
                array_key_exists(
                    'total_hours',
                    $data
                ) &&
                $data['total_hours'] !== null &&
                $data['total_hours'] !== ''
            ) {

                $data['total_hours'] =
                    round(
                        max(
                            0,
                            (float) $data['total_hours']
                        ),
                        2
                    );
            } else {

                $data['total_hours'] = null;
            }


            /*
            |--------------------------------------------------------------------------
            | Create / Update
            |--------------------------------------------------------------------------
            */

            $attendance =
                DriverAttendance::withTrashed()
                    ->where(
                        'driver_id',
                        $data['driver_id']
                    )
                    ->whereDate(
                        'attendance_date',
                        $data['attendance_date']
                    )
                    ->lockForUpdate()
                    ->first();


            if ($attendance) {

                // An explicit manual action may restore the unique row for this driver/date.
                if ($attendance->trashed()) {
                    $attendance->restore();
                }

                // Do not rewrite the original creator when editing a row.
                unset($data['created_by']);
                $attendance->fill($data);
                $attendance->save();

            } else {

                $data['created_by'] = Auth::id();

                try {
                    $attendance = DriverAttendance::create($data);
                } catch (QueryException $exception) {
                    // Resolve a concurrent insert against the unique driver/date key.
                    $attendance = DriverAttendance::withTrashed()
                        ->where('driver_id', $data['driver_id'])
                        ->whereDate('attendance_date', $data['attendance_date'])
                        ->lockForUpdate()
                        ->first();

                    if (!$attendance) {
                        throw $exception;
                    }

                    if ($attendance->trashed()) {
                        $attendance->restore();
                    }

                    unset($data['created_by']);
                    $attendance->fill($data);
                    $attendance->save();
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Return
            |--------------------------------------------------------------------------
            */

            return $attendance->fresh([
                'driver',
                'workingSheet',
                'createdBy',
                'updatedBy',
            ]) ?? $attendance;
        });
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE / REMOVE WORKING SHEET ATTENDANCE
    |--------------------------------------------------------------------------
    |
    | Used when a Working Sheet is rejected/cancelled
    | and its automatically generated attendance should
    | no longer remain as confirmed attendance.
    |
    */

    public function removeWorkingSheetAttendance(
        WorkingSheet $workingSheet
    ): bool {

        return DB::transaction(function () use (
            $workingSheet
        ) {

            $records = DriverAttendance::withTrashed()
                ->where(
                    'working_sheet_id',
                    $workingSheet->id
                )
                ->where(
                    'source',
                    DriverAttendance::SOURCE_WORKING_SHEET
                )
                ->lockForUpdate()
                ->get();

            if ($records->isEmpty()) {
                return false;
            }

            // Soft-deleting leaves the unique (driver_id, attendance_date) key occupied.
            // Only derived Working Sheet rows are permanently removed; manual rows are untouched.
            foreach ($records as $record) {
                $record->forceDelete();
            }

            return true;
        });
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK EXISTING ATTENDANCE
    |--------------------------------------------------------------------------
    */

    public function hasAttendance(
        int $driverId,
        string $date
    ): bool {

        // Include deleted rows because the database unique key still reserves the date.
        return DriverAttendance::withTrashed()
            ->where(
                'driver_id',
                $driverId
            )
            ->whereDate(
                'attendance_date',
                $date
            )
            ->exists();
    }
}
