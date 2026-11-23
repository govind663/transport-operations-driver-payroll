<?php

namespace App\Services\WorkingSheet;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use App\Models\DriverAllowance;
use App\Models\DriverExpense;
use App\Models\DutySlip;
use App\Models\WorkingSheet;
use App\Services\DriverAttendance\DriverAttendanceService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class WorkingSheetService
{
    /*
    |--------------------------------------------------------------------------
    | Driver Attendance Service
    |--------------------------------------------------------------------------
    */
    protected DriverAttendanceService $driverAttendanceService;

    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */
    public function __construct(
        DriverAttendanceService $driverAttendanceService
    ) {
        $this->driverAttendanceService =
            $driverAttendanceService;
    }

    /*
    |--------------------------------------------------------------------------
    | GET WORKING SHEETS
    |--------------------------------------------------------------------------
    |
    | Admin / Operations / Accountant:
    | - Can view all Working Sheets.
    | - Can filter by Driver, Status, Date Range and Search.
    |
    | Driver:
    | - Can view only their own Working Sheets.
    | - Cannot access another driver's records through filters.
    |
    */
    public function getWorkingSheets(
        int $perPage = 10000000000000,
        array $filters = []
    ): LengthAwarePaginator {

        $user = Auth::user();

        abort_unless(
            $user instanceof User,
            403,
            'Unauthenticated access.'
        );

        $isManagementUser =
            $user->isAdmin()
            || $user->isOperations()
            || $user->isAccountant();

        $query = WorkingSheet::query()
            ->with([
                'dutySlip',
                'driver',
                'createdBy',
                'updatedBy',
            ]);

        /*
        |--------------------------------------------------------------------------
        | ROLE-WISE ACCESS
        |--------------------------------------------------------------------------
        */

        if ($user->isDriver()) {

            $driver = $user->driver;

            abort_unless(
                $driver instanceof Driver,
                403,
                'Driver profile not found.'
            );

            $query->where(
                'driver_id',
                $driver->id
            );

        } else {

            abort_unless(
                $isManagementUser,
                403,
                'Unauthorized access.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SEARCH FILTER
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            $query->where(function (Builder $builder) use ($search) {

                $builder->where(
                    'sheet_no',
                    'like',
                    '%' . $search . '%'
                );

                $builder->orWhere(
                    'remarks',
                    'like',
                    '%' . $search . '%'
                );

                $builder->orWhereHas(
                    'dutySlip',
                    function (Builder $dutySlipQuery) use ($search) {

                        $dutySlipQuery->where(
                            'slip_no',
                            'like',
                            '%' . $search . '%'
                        );
                    }
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | DRIVER FILTER
        |--------------------------------------------------------------------------
        */

        if (
            $isManagementUser
            && !empty($filters['driver_id'])
        ) {

            $query->where(
                'driver_id',
                (int) $filters['driver_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['status'])) {

            $query->where(
                'status',
                $filters['status']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DATE FROM FILTER
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['date_from'])) {

            $query->whereDate(
                'work_date',
                '>=',
                $filters['date_from']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DATE TO FILTER
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['date_to'])) {

            $query->whereDate(
                'work_date',
                '<=',
                $filters['date_to']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | RESULT
        |--------------------------------------------------------------------------
        */

        return $query
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /*
    |--------------------------------------------------------------------------
    | GET FILTER OPTIONS
    |--------------------------------------------------------------------------
    |
    | Management users receive all active driver options.
    | Drivers receive only their own driver profile.
    |
    */
    public function getFilterOptions(): array
    {
        $user = Auth::user();

        abort_unless(
            $user instanceof User,
            403,
            'Unauthenticated access.'
        );

        $isManagementUser =
            $user->isAdmin()
            || $user->isOperations()
            || $user->isAccountant();

        if ($user->isDriver()) {

            $driver = $user->driver;

            abort_unless(
                $driver instanceof Driver,
                403,
                'Driver profile not found.'
            );

            $drivers = Driver::query()
                ->whereKey($driver->id)
                ->get();

        } else {

            abort_unless(
                $isManagementUser,
                403,
                'Unauthorized access.'
            );

            $drivers = Driver::query()
                ->orderBy('id')
                ->get();
        }

        return [
            'drivers' => $drivers,

            'statuses' => [
                'draft' => 'Draft',
                'submitted' => 'Submitted',
                'approved' => 'Approved',
                'rejected' => 'Rejected',
                'completed' => 'Completed',
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | FIND WORKING SHEET
    |--------------------------------------------------------------------------
    */
    public function findById(
        string|int $id
    ): WorkingSheet {

        return WorkingSheet::query()
            ->with([
                'dutySlip',
                'driver',
                'createdBy',
                'updatedBy',
            ])
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE NEXT WORKING SHEET NUMBER
    |--------------------------------------------------------------------------
    */
    /**
     * Generate a number such as WS-2026-0001.
     * Soft-deleted records are included so their numbers are never reused.
     */
    public function generateNextSheetNumber(?int $year = null): string
    {
        $year = $year ?? (int) now()->format('Y');
        $prefix = 'WS-' . $year . '-';
        $pattern = '/^' . preg_quote($prefix, '/') . '(\d+)$/';

        $existingNumbers = WorkingSheet::withTrashed()
            ->where('sheet_no', 'like', $prefix . '%')
            ->pluck('sheet_no');

        $highestNumber = 0;

        foreach ($existingNumbers as $existingNumber) {
            if (preg_match($pattern, (string) $existingNumber, $matches) === 1) {
                $highestNumber = max($highestNumber, (int) $matches[1]);
            }
        }

        $nextNumber = $highestNumber + 1;

        do {
            $candidate = $prefix . str_pad(
                (string) $nextNumber,
                4,
                '0',
                STR_PAD_LEFT
            );

            $nextNumber++;

            // Check the exact candidate too, including records with unusual
            // numbering formats or records inserted after the first query.
            $alreadyExists = WorkingSheet::withTrashed()
                ->where('sheet_no', $candidate)
                ->exists();
        } while ($alreadyExists);

        return $candidate;
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(
        array $data
    ): WorkingSheet {

        try {

            return DB::transaction(function () use ($data) {

                /*
                |--------------------------------------------------------------------------
                | Working Sheet Number
                |--------------------------------------------------------------------------
                | The form value is only a preview. Generate the authoritative
                | number on the server immediately before storing the record.
                */

                $data['sheet_no'] = $this->generateNextSheetNumber();


                /*
                |--------------------------------------------------------------------------
                | Created By
                |--------------------------------------------------------------------------
                */

                $data['created_by'] = Auth::id();


                /*
                |--------------------------------------------------------------------------
                | Status
                |--------------------------------------------------------------------------
                */

                $data['status'] =
                    $data['status']
                    ?? WorkingSheet::STATUS_SUBMITTED;


                /*
                |--------------------------------------------------------------------------
                | Driver / Duty Slip
                |--------------------------------------------------------------------------
                |
                | Resolve the driver from the selected Duty Slip on the server.
                | Do not rely on the readonly/hidden form fields for ownership.
                |
                */

                $resolvedDutySlip = $this->resolveDutySlipDriver(
                    $data['duty_slip_id'] ?? null
                );

                $data['duty_slip_id'] = $resolvedDutySlip['duty_slip_id'];
                $data['driver_id'] = $resolvedDutySlip['driver_id'];

                // Enforce approved allowance/expense totals on the server, rather
                // than trusting amounts submitted by the browser.
                $this->applyApprovedAllowanceExpenseTotals(
                    $data,
                    $data['driver_id'],
                    $data['duty_slip_id']
                );


                /*
                |--------------------------------------------------------------------------
                | Calculate Total KM
                |--------------------------------------------------------------------------
                */

                $openingMeter =
                    $data['opening_meter'] ?? null;

                $closingMeter =
                    $data['closing_meter'] ?? null;


                if (
                    $openingMeter !== null &&
                    $closingMeter !== null &&
                    $openingMeter !== '' &&
                    $closingMeter !== ''
                ) {

                    if ((float) $closingMeter < (float) $openingMeter) {
                        throw ValidationException::withMessages([
                            'closing_meter' => 'Closing meter must be greater than or equal to opening meter.',
                        ]);
                    }

                    $data['total_km'] =
                        round(
                            (float) $closingMeter
                            - (float) $openingMeter,
                            2
                        );

                } else {

                    $data['total_km'] = null;
                }


                /*
                |--------------------------------------------------------------------------
                | Normalize Total Hours
                |--------------------------------------------------------------------------
                */

                $data['total_hours'] =
                    isset($data['total_hours']) &&
                    $data['total_hours'] !== ''
                        ? round(
                            max(
                                0,
                                (float) $data['total_hours']
                            ),
                            2
                        )
                        : null;


                /*
                |--------------------------------------------------------------------------
                | Normalize Overtime Hours
                |--------------------------------------------------------------------------
                */

                $data['overtime_hours'] =
                    round(
                        max(
                            0,
                            (float) (
                                $data['overtime_hours']
                                ?? 0
                            )
                        ),
                        2
                    );


                /*
                |--------------------------------------------------------------------------
                | Financial Amounts
                |--------------------------------------------------------------------------
                */

                $baseAmount =
                    max(
                        0,
                        (float) (
                            $data['base_amount']
                            ?? 0
                        )
                    );


                $extraKmAmount =
                    max(
                        0,
                        (float) (
                            $data['extra_km_amount']
                            ?? 0
                        )
                    );


                $overtimeAmount =
                    max(
                        0,
                        (float) (
                            $data['overtime_amount']
                            ?? 0
                        )
                    );


                $allowanceAmount =
                    max(
                        0,
                        (float) (
                            $data['allowance_amount']
                            ?? 0
                        )
                    );


                $expenseAmount =
                    max(
                        0,
                        (float) (
                            $data['expense_amount']
                            ?? 0
                        )
                    );


                $otherAmount =
                    max(
                        0,
                        (float) (
                            $data['other_amount']
                            ?? 0
                        )
                    );


                /*
                |--------------------------------------------------------------------------
                | Store Normalized Amounts
                |--------------------------------------------------------------------------
                */

                $data['base_amount'] =
                    round($baseAmount, 2);

                $data['extra_km_amount'] =
                    round($extraKmAmount, 2);

                $data['overtime_amount'] =
                    round($overtimeAmount, 2);

                $data['allowance_amount'] =
                    round($allowanceAmount, 2);

                $data['expense_amount'] =
                    round($expenseAmount, 2);

                $data['other_amount'] =
                    round($otherAmount, 2);


                /*
                |--------------------------------------------------------------------------
                | Total Amount
                |--------------------------------------------------------------------------
                */

                $data['total_amount'] =
                    round(
                        $baseAmount
                        + $extraKmAmount
                        + $overtimeAmount
                        + $allowanceAmount
                        + $expenseAmount
                        + $otherAmount,
                        2
                    );


                /*
                |--------------------------------------------------------------------------
                | Create Working Sheet
                |--------------------------------------------------------------------------
                */

                $workingSheet =
                    WorkingSheet::create($data);


                /*
                |--------------------------------------------------------------------------
                | Driver Attendance Sync
                |--------------------------------------------------------------------------
                */

                $workingSheet->load([
                    'dutySlip',
                ]);

                $this->syncDriverAttendance(
                    $workingSheet
                );


                /*
                |--------------------------------------------------------------------------
                | Return With Relationships
                |--------------------------------------------------------------------------
                */

                return $workingSheet->fresh([
                    'dutySlip',
                    'driver',
                    'createdBy',
                    'updatedBy',
                ]);

            });

        } catch (RuntimeException $exception) {

            /*
            |--------------------------------------------------------------------------
            | USER-FRIENDLY ATTENDANCE / WORKING SHEET ERROR
            |--------------------------------------------------------------------------
            |
            | Convert RuntimeException into a Laravel validation error.
            | The failed database transaction has already been rolled back.
            |
            */

            throw ValidationException::withMessages([
                'attendance' => $exception->getMessage(),
            ]);
        }
    }



    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(
        WorkingSheet $workingSheet,
        array $data
    ): WorkingSheet {

        try {

            return DB::transaction(function () use (
                $workingSheet,
                $data
            ) {

                /*
                |--------------------------------------------------------------------------
                | Updated By
                |--------------------------------------------------------------------------
                */

                $data['updated_by'] = Auth::id();

                // Working Sheet numbers are generated during creation and remain immutable.
                // Ignore any client-submitted sheet_no value during updates.
                $data['sheet_no'] = $workingSheet->sheet_no;


                /*
                |--------------------------------------------------------------------------
                | Driver / Duty Slip
                |--------------------------------------------------------------------------
                |
                | Derive the driver from the selected Duty Slip, even if the
                | client did not submit driver_id or submitted a stale value.
                |
                */

                $dutySlipId = array_key_exists('duty_slip_id', $data)
                    && $data['duty_slip_id'] !== ''
                        ? $data['duty_slip_id']
                        : $workingSheet->duty_slip_id;

                $resolvedDutySlip = $this->resolveDutySlipDriver($dutySlipId);

                $data['duty_slip_id'] = $resolvedDutySlip['duty_slip_id'];
                $data['driver_id'] = $resolvedDutySlip['driver_id'];

                // Keep edits aligned with the current approved records as well.
                $this->applyApprovedAllowanceExpenseTotals(
                    $data,
                    $data['driver_id'],
                    $data['duty_slip_id']
                );


                /*
                |--------------------------------------------------------------------------
                | Opening Meter
                |--------------------------------------------------------------------------
                */

                $openingMeter =
                    array_key_exists(
                        'opening_meter',
                        $data
                    )
                        ? $data['opening_meter']
                        : $workingSheet->opening_meter;


                /*
                |--------------------------------------------------------------------------
                | Closing Meter
                |--------------------------------------------------------------------------
                */

                $closingMeter =
                    array_key_exists(
                        'closing_meter',
                        $data
                    )
                        ? $data['closing_meter']
                        : $workingSheet->closing_meter;


                /*
                |--------------------------------------------------------------------------
                | Calculate Total KM
                |--------------------------------------------------------------------------
                */

                if (
                    $openingMeter !== null &&
                    $closingMeter !== null &&
                    $openingMeter !== '' &&
                    $closingMeter !== ''
                ) {

                    if ((float) $closingMeter < (float) $openingMeter) {
                        throw ValidationException::withMessages([
                            'closing_meter' => 'Closing meter must be greater than or equal to opening meter.',
                        ]);
                    }

                    $data['total_km'] =
                        round(
                            (float) $closingMeter
                            - (float) $openingMeter,
                            2
                        );

                } else {

                    $data['total_km'] = null;
                }


                /*
                |--------------------------------------------------------------------------
                | Total Hours
                |--------------------------------------------------------------------------
                */

                if (
                    array_key_exists(
                        'total_hours',
                        $data
                    )
                ) {

                    $data['total_hours'] =
                        $data['total_hours'] !== ''
                            ? round(
                                max(
                                    0,
                                    (float) $data['total_hours']
                                ),
                                2
                            )
                            : null;
                }


                /*
                |--------------------------------------------------------------------------
                | Overtime Hours
                |--------------------------------------------------------------------------
                */

                if (
                    array_key_exists(
                        'overtime_hours',
                        $data
                    )
                ) {

                    $data['overtime_hours'] =
                        round(
                            max(
                                0,
                                (float) (
                                    $data['overtime_hours']
                                    ?? 0
                                )
                            ),
                            2
                        );
                }


                /*
                |--------------------------------------------------------------------------
                | Financial Amounts
                |--------------------------------------------------------------------------
                */

                $baseAmount =
                    array_key_exists(
                        'base_amount',
                        $data
                    )
                        ? (float) $data['base_amount']
                        : (float) (
                            $workingSheet->base_amount
                            ?? 0
                        );


                $extraKmAmount =
                    array_key_exists(
                        'extra_km_amount',
                        $data
                    )
                        ? (float) $data['extra_km_amount']
                        : (float) (
                            $workingSheet->extra_km_amount
                            ?? 0
                        );


                $overtimeAmount =
                    array_key_exists(
                        'overtime_amount',
                        $data
                    )
                        ? (float) $data['overtime_amount']
                        : (float) (
                            $workingSheet->overtime_amount
                            ?? 0
                        );


                $allowanceAmount =
                    array_key_exists(
                        'allowance_amount',
                        $data
                    )
                        ? (float) $data['allowance_amount']
                        : (float) (
                            $workingSheet->allowance_amount
                            ?? 0
                        );


                $expenseAmount =
                    array_key_exists(
                        'expense_amount',
                        $data
                    )
                        ? (float) $data['expense_amount']
                        : (float) (
                            $workingSheet->expense_amount
                            ?? 0
                        );


                $otherAmount =
                    array_key_exists(
                        'other_amount',
                        $data
                    )
                        ? (float) $data['other_amount']
                        : (float) (
                            $workingSheet->other_amount
                            ?? 0
                        );


                /*
                |--------------------------------------------------------------------------
                | Prevent Negative Amounts
                |--------------------------------------------------------------------------
                */

                $baseAmount =
                    max(0, $baseAmount);

                $extraKmAmount =
                    max(0, $extraKmAmount);

                $overtimeAmount =
                    max(0, $overtimeAmount);

                $allowanceAmount =
                    max(0, $allowanceAmount);

                $expenseAmount =
                    max(0, $expenseAmount);

                $otherAmount =
                    max(0, $otherAmount);


                /*
                |--------------------------------------------------------------------------
                | Update Individual Amounts
                |--------------------------------------------------------------------------
                */

                $data['base_amount'] =
                    round($baseAmount, 2);

                $data['extra_km_amount'] =
                    round($extraKmAmount, 2);

                $data['overtime_amount'] =
                    round($overtimeAmount, 2);

                $data['allowance_amount'] =
                    round($allowanceAmount, 2);

                $data['expense_amount'] =
                    round($expenseAmount, 2);

                $data['other_amount'] =
                    round($otherAmount, 2);


                /*
                |--------------------------------------------------------------------------
                | Recalculate Total Amount
                |--------------------------------------------------------------------------
                */

                $data['total_amount'] =
                    round(
                        $baseAmount
                        + $extraKmAmount
                        + $overtimeAmount
                        + $allowanceAmount
                        + $expenseAmount
                        + $otherAmount,
                        2
                    );


                /*
                |--------------------------------------------------------------------------
                | Update Working Sheet
                |--------------------------------------------------------------------------
                */

                $workingSheet->update($data);


                /*
                |--------------------------------------------------------------------------
                | Refresh Working Sheet
                |--------------------------------------------------------------------------
                */

                $workingSheet->refresh();


                /*
                |--------------------------------------------------------------------------
                | Driver Attendance Sync
                |--------------------------------------------------------------------------
                |
                | Automatically synchronize attendance after updating
                | the Working Sheet.
                |
                | Duplicate attendance will prevent the transaction
                | from being committed.
                |
                */

                $this->syncDriverAttendance(
                    $workingSheet
                );


                /*
                |--------------------------------------------------------------------------
                | Return Fresh Model
                |--------------------------------------------------------------------------
                */

                return $workingSheet->fresh([
                    'dutySlip',
                    'driver',
                    'createdBy',
                    'updatedBy',
                ]);

            });

        } catch (RuntimeException $exception) {

            /*
            |--------------------------------------------------------------------------
            | USER-FRIENDLY ATTENDANCE / WORKING SHEET ERROR
            |--------------------------------------------------------------------------
            |
            | Convert RuntimeException into a Laravel validation error.
            | The failed database transaction has already been rolled back.
            |
            */

            throw ValidationException::withMessages([
                'attendance' => $exception->getMessage(),
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SYNC DRIVER ATTENDANCE
    |--------------------------------------------------------------------------
    |
    | Attendance is generated only when the working sheet
    | reaches a valid attendance state.
    |
    */
    protected function syncDriverAttendance(
        WorkingSheet $workingSheet
    ): void {

        try {

            /*
            |--------------------------------------------------------------------------
            | Valid Working Sheet Statuses
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

                $this->driverAttendanceService
                    ->removeWorkingSheetAttendance($workingSheet);

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Driver Required
            |--------------------------------------------------------------------------
            */

            if (!$workingSheet->driver_id) {

                $this->driverAttendanceService
                    ->removeWorkingSheetAttendance($workingSheet);

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Work Date Required
            |--------------------------------------------------------------------------
            */

            if (!$workingSheet->work_date) {

                $this->driverAttendanceService
                    ->removeWorkingSheetAttendance($workingSheet);

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Sync Driver Attendance
            |--------------------------------------------------------------------------
            */

            $this->driverAttendanceService
                ->syncFromWorkingSheet(
                    $workingSheet
                );

        } catch (\RuntimeException $exception) {

            /*
            |--------------------------------------------------------------------------
            | USER-FRIENDLY ATTENDANCE ERROR
            |--------------------------------------------------------------------------
            */

            throw ValidationException::withMessages([
                'attendance' => $exception->getMessage(),
            ]);

        } catch (\Illuminate\Database\QueryException $exception) {

            /*
            |--------------------------------------------------------------------------
            | DATABASE ERROR
            |--------------------------------------------------------------------------
            */

            report($exception);

            throw ValidationException::withMessages([
                'attendance' => 'We could not synchronize driver attendance right now. Please try again. If the problem continues, contact your administrator.',
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function delete(
        WorkingSheet $workingSheet
    ): bool {

        try {

            return DB::transaction(function () use (
                $workingSheet
            ) {

                /*
                |--------------------------------------------------------------------------
                | Set Deleted By
                |--------------------------------------------------------------------------
                |
                | Store the user ID who is deleting this Working Sheet.
                |
                */

                $workingSheet->deleted_by = Auth::id();
                $workingSheet->save();


                /*
                |--------------------------------------------------------------------------
                | Remove Linked Attendance
                |--------------------------------------------------------------------------
                |
                | Do not leave automatically generated attendance behind when
                | its source Working Sheet is soft-deleted.
                |
                */

                $this->driverAttendanceService
                    ->removeWorkingSheetAttendance($workingSheet);


                /*
                |--------------------------------------------------------------------------
                | Soft Delete
                |--------------------------------------------------------------------------
                |
                | WorkingSheet uses SoftDeletes.
                |
                */

                return $workingSheet->delete();
            });

        } catch (\RuntimeException $exception) {

            /*
            |--------------------------------------------------------------------------
            | USER-FRIENDLY DELETE ERROR
            |--------------------------------------------------------------------------
            */

            throw ValidationException::withMessages([
                'working_sheet' => $exception->getMessage(),
            ]);

        } catch (\Illuminate\Database\QueryException $exception) {

            /*
            |--------------------------------------------------------------------------
            | DATABASE DELETE ERROR
            |--------------------------------------------------------------------------
            */

            report($exception);

            throw ValidationException::withMessages([
                'working_sheet' => 'We could not delete this Working Sheet right now. Please try again. If the problem continues, contact your administrator.',
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RESOLVE DRIVER FROM DUTY SLIP
    |--------------------------------------------------------------------------
    |
    | The selected Duty Slip is the authoritative source. The direct
    | duty_slips.driver_id is preferred; Duty Assignment is the fallback.
    |
    */
    /**
     * Set financial totals from approved records for the exact driver/Duty Slip.
     * These values are authoritative; client-submitted totals are ignored.
     */
    private function applyApprovedAllowanceExpenseTotals(
        array &$data,
        int $driverId,
        int $dutySlipId
    ): void {
        $data['allowance_amount'] = round((float) DriverAllowance::query()
            ->where('driver_id', $driverId)
            ->where('duty_slip_id', $dutySlipId)
            ->where('status', 'approved')
            ->sum('amount'), 2);

        $data['expense_amount'] = round((float) DriverExpense::query()
            ->where('driver_id', $driverId)
            ->where('duty_slip_id', $dutySlipId)
            ->where('status', 'approved')
            ->sum('amount'), 2);
    }

    protected function resolveDutySlipDriver(mixed $dutySlipId): array
    {
        if (
            !is_numeric($dutySlipId) ||
            (int) $dutySlipId < 1
        ) {
            throw ValidationException::withMessages([
                'duty_slip_id' => 'Please select a valid Duty Slip.',
            ]);
        }

        $dutySlip = DutySlip::query()
            ->with(['dutyAssignment.driver'])
            ->findOrFail((int) $dutySlipId);

        $driverId = $dutySlip->driver_id
            ?? $dutySlip->dutyAssignment?->driver_id
            ?? $dutySlip->dutyAssignment?->driver?->id;

        if (!$driverId) {
            throw ValidationException::withMessages([
                'duty_slip_id' => 'The selected Duty Slip does not have an assigned driver.',
            ]);
        }

        return [
            'duty_slip_id' => (int) $dutySlip->id,
            'driver_id' => (int) $driverId,
        ];
    }

}
