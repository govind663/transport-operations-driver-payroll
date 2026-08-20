<?php

namespace App\Services\DriverAttendance;

use App\Models\DriverAttendance;
use App\Models\WorkingSheet;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
                'dutySlip',
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

            $attendance = DriverAttendance::query()
                ->where(
                    'driver_id',
                    $workingSheet->driver_id
                )
                ->whereDate(
                    'attendance_date',
                    $workingSheet->work_date
                )
                ->first();


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
                    $workingSheet->dutySlip->start_time;

                $outTime =
                    $workingSheet->dutySlip->end_time;
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
                    $workingSheet->driver_id,

                'attendance_date' =>
                    $workingSheet->work_date,

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

                $attendance->update(
                    $attendanceData
                );

            } else {

                $attendanceData['created_by'] =
                    Auth::id();

                $attendance =
                    DriverAttendance::create(
                        $attendanceData
                    );
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

        if (
            $totalHours > 0 &&
            $totalHours < self::HALF_DAY_HOURS
        ) {

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

        return Carbon::parse(
            $actualStartTime
        )->gt(
            Carbon::parse(
                $reportingTime
            )
        );
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
        | No Reliable Expected End
        |--------------------------------------------------------------------------
        */

        return false;
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
                $data['working_sheet_id']
                ?? null;


            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            $data['created_by'] =
                Auth::id();

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
            }


            /*
            |--------------------------------------------------------------------------
            | Create / Update
            |--------------------------------------------------------------------------
            */

            $attendance =
                DriverAttendance::query()
                    ->where(
                        'driver_id',
                        $data['driver_id']
                    )
                    ->whereDate(
                        'attendance_date',
                        $data['attendance_date']
                    )
                    ->first();


            if ($attendance) {

                $attendance->update(
                    $data
                );

            } else {

                $attendance =
                    DriverAttendance::create(
                        $data
                    );
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
            ]);
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

            return DriverAttendance::query()
                ->where(
                    'working_sheet_id',
                    $workingSheet->id
                )
                ->where(
                    'source',
                    DriverAttendance::SOURCE_WORKING_SHEET
                )
                ->delete();
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

        return DriverAttendance::query()
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