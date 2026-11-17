<?php

namespace App\Services\SalaryProcessing;

use App\Models\SalaryProcessing;
use App\Models\SalarySlip;
use App\Models\Driver;
use App\Models\DriverAdvance;
use App\Models\DriverAttendance;
use App\Models\WorkingSheet;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class SalaryProcessingService
{
    /*
    |--------------------------------------------------------------------------
    | INDEX / LIST
    |--------------------------------------------------------------------------
    */

    public function getPaginated(
        array $filters = [],
        int $perPage = 10000000000000
    ): LengthAwarePaginator {

        $query = SalaryProcessing::query()
            ->with([
                'driver',
                'processedBy',
                'approvedBy',
                'createdBy',
                'updatedBy',
                'salarySlips',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Auth User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Driver Restriction
        |--------------------------------------------------------------------------
        */

        if ($user->isDriver()) {

            $driver = Driver::query()
                ->where('user_id', $user->id)
                ->first();

            if ($driver) {

                $query->where(
                    'driver_id',
                    $driver->id
                );

            } else {

                $query->whereRaw('1 = 0');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Driver Filter
        |--------------------------------------------------------------------------
        */

        if (
            !$user->isDriver() &&
            !empty($filters['driver_id'])
        ) {

            $query->where(
                'driver_id',
                $filters['driver_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Salary Month
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['salary_month'])) {

            $query->where(
                'salary_month',
                $filters['salary_month']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Salary Year
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['salary_year'])) {

            $query->where(
                'salary_year',
                $filters['salary_year']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status
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
        | Payment Date
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['payment_date'])) {

            $query->whereDate(
                'payment_date',
                $filters['payment_date']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = trim(
                $filters['search']
            );

            $query->where(function ($q) use ($search) {

                $q->whereHas(
                    'driver',
                    function ($driverQuery) use ($search) {

                        $driverQuery
                            ->where(
                                'first_name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'last_name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'driver_code',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'mobile',
                                'like',
                                "%{$search}%"
                            );
                    }
                );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | period_from does NOT exist in database.
        | Therefore order by salary_year/month only.
        |
        */

        return $query
            ->latest('salary_year')
            ->latest('salary_month')
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        array $data
    ): SalaryProcessing {

        return DB::transaction(function () use ($data) {

            $userId = Auth::id();

            /*
            |--------------------------------------------------------------------------
            | Driver Basic Salary
            |--------------------------------------------------------------------------
            |
            | Driver monthly_gross_salary is the monthly base amount used
            | by payroll as basic_salary. Existing explicit values are
            | preserved for compatibility with manual payroll entry.
            |
            */

            if (
                !empty($data['driver_id']) &&
                (
                    !array_key_exists('basic_salary', $data) ||
                    $data['basic_salary'] === null ||
                    $data['basic_salary'] === ''
                )
            ) {

                $driver = Driver::query()->findOrFail(
                    (int) $data['driver_id']
                );

                $data['basic_salary'] =
                    $driver->monthly_gross_salary ?? 0;
            }

            /*
            |--------------------------------------------------------------------------
            | Duplicate Salary Period Protection
            |--------------------------------------------------------------------------
            */

            if (
                !empty($data['driver_id']) &&
                !empty($data['salary_month']) &&
                !empty($data['salary_year'])
            ) {

                $existing = SalaryProcessing::withTrashed()
                    ->where('driver_id', $data['driver_id'])
                    ->where('salary_month', $data['salary_month'])
                    ->where('salary_year', $data['salary_year'])
                    ->lockForUpdate()
                    ->first();

                if ($existing) {

                    throw new RuntimeException(
                        $existing->trashed()
                            ? 'A deleted payroll record already exists for this driver and period. Restore or resolve it before creating another record.'
                            : 'Payroll already exists for this driver and salary period.'
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Role
            |--------------------------------------------------------------------------
            */

            $data['role'] =
                $data['role']
                ?? SalaryProcessing::ROLE_DRIVER;

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            $data['created_by'] =
                $data['created_by']
                ?? $userId;

            $data['updated_by'] =
                $data['updated_by']
                ?? $userId;

            /*
            |--------------------------------------------------------------------------
            | Default Status
            |--------------------------------------------------------------------------
            */

            // Payroll lifecycle changes must use process(), approve(), markAsPaid() or cancel().
            $data['status'] = SalaryProcessing::STATUS_DRAFT;

            unset(
                $data['processed_by'],
                $data['approved_by'],
                $data['paid_by'],
                $data['deleted_by']
            );

            /*
            |--------------------------------------------------------------------------
            | Default Values
            |--------------------------------------------------------------------------
            */

            $data['total_working_days'] =
                $data['total_working_days']
                ?? 0;

            $data['present_days'] =
                $data['present_days']
                ?? 0;

            $data['absent_days'] =
                $data['absent_days']
                ?? 0;

            $data['paid_days'] =
                $data['paid_days']
                ?? 0;

            $data['overtime_hours'] =
                $data['overtime_hours']
                ?? 0;

            /*
            |--------------------------------------------------------------------------
            | Calculate Salary
            |--------------------------------------------------------------------------
            */

            $data = $this->calculateSalary(
                $data
            );

            /*
            |--------------------------------------------------------------------------
            | Create Record
            |--------------------------------------------------------------------------
            */

            return SalaryProcessing::create(
                $data
            );
        });
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        SalaryProcessing $salaryProcessing,
        array $data
    ): SalaryProcessing {

        return DB::transaction(function () use (
            $salaryProcessing,
            $data
        ) {

            $salaryProcessing->refresh();

            /*
            |--------------------------------------------------------------------------
            | Payroll Status Protection
            |--------------------------------------------------------------------------
            */

            if (in_array(
                $salaryProcessing->status,
                [
                    SalaryProcessing::STATUS_APPROVED,
                    SalaryProcessing::STATUS_PAID,
                    SalaryProcessing::STATUS_CANCELLED,
                ],
                true
            )) {

                throw new RuntimeException(
                    'Approved, paid or cancelled payroll cannot be edited.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Driver Change / Basic Salary
            |--------------------------------------------------------------------------
            */

            $newDriverId = (int) (
                $data['driver_id'] ?? $salaryProcessing->driver_id
            );

            if (
                $newDriverId !== (int) $salaryProcessing->driver_id &&
                !array_key_exists('basic_salary', $data)
            ) {

                $driver = Driver::query()->findOrFail($newDriverId);

                $data['basic_salary'] =
                    $driver->monthly_gross_salary ?? 0;
            }

            /*
            |--------------------------------------------------------------------------
            | Duplicate Salary Period Protection
            |--------------------------------------------------------------------------
            */

            $newMonth = (int) (
                $data['salary_month'] ?? $salaryProcessing->salary_month
            );

            $newYear = (int) (
                $data['salary_year'] ?? $salaryProcessing->salary_year
            );

            $duplicate = SalaryProcessing::withTrashed()
                ->where('driver_id', $newDriverId)
                ->where('salary_month', $newMonth)
                ->where('salary_year', $newYear)
                ->where('id', '!=', $salaryProcessing->id)
                ->lockForUpdate()
                ->exists();

            if ($duplicate) {

                throw new RuntimeException(
                    'Another payroll record already exists for this driver and salary period.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Updated By / Role
            |--------------------------------------------------------------------------
            */

            unset(
                $data['status'],
                $data['processed_by'],
                $data['approved_by'],
                $data['paid_by'],
                $data['created_by'],
                $data['deleted_by']
            );

            $data['updated_by'] = Auth::id();

            $data['role'] =
                $data['role']
                ?? $salaryProcessing->role
                ?? SalaryProcessing::ROLE_DRIVER;

            /*
            |--------------------------------------------------------------------------
            | Fill Existing Record
            |--------------------------------------------------------------------------
            |
            | Filling the existing model first preserves untouched values
            | such as working-sheet totals and manual adjustments.
            |
            */

            $salaryProcessing->fill($data);

            /*
            |--------------------------------------------------------------------------
            | Recalculate Salary
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->calculateSalary();

            /*
            |--------------------------------------------------------------------------
            | Re-process Required After Edit
            |--------------------------------------------------------------------------
            */

            if ($salaryProcessing->status === SalaryProcessing::STATUS_PROCESSED) {

                $salaryProcessing->status =
                    SalaryProcessing::STATUS_DRAFT;

                $salaryProcessing->processed_by = null;
            }

            $salaryProcessing->save();

            /*
            |--------------------------------------------------------------------------
            | Fresh Record
            |--------------------------------------------------------------------------
            */

            return $salaryProcessing->fresh([
                'driver',
                'processedBy',
                'approvedBy',
                'createdBy',
                'updatedBy',
                'salarySlips',
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(
        SalaryProcessing $salaryProcessing
    ): bool {

        return DB::transaction(function () use (
            $salaryProcessing
        ) {

            $salaryProcessing->refresh();

            if ($salaryProcessing->status === SalaryProcessing::STATUS_PAID) {

                throw new RuntimeException(
                    'Paid payroll cannot be deleted.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Deleted By
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->deleted_by =
                Auth::id();

            /*
            |--------------------------------------------------------------------------
            | Save Audit
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->save();

            /*
            |--------------------------------------------------------------------------
            | Soft Delete
            |--------------------------------------------------------------------------
            */

            return $salaryProcessing->delete();
        });
    }


    /*
    |--------------------------------------------------------------------------
    | CALCULATE SALARY
    |--------------------------------------------------------------------------
    */

    public function calculateSalary(
        array $data
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Basic Salary
        |--------------------------------------------------------------------------
        |
        | For automated driver payroll this value comes from
        | drivers.monthly_gross_salary.
        |
        */

        $basicSalary = (float) (
            $data['basic_salary'] ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Working Sheet Breakdown
        |--------------------------------------------------------------------------
        |
        | Working Sheet total_amount already includes its component
        | amounts. Use that total once; never add the same components
        | a second time to gross salary.
        |
        */

        $workingSheetBase = (float) (
            $data['working_sheet_base_amount'] ?? 0
        );

        $extraKm = (float) (
            $data['extra_km_amount'] ?? 0
        );

        $allowance = (float) (
            $data['allowance_amount'] ?? 0
        );

        $overtime = (float) (
            $data['overtime_amount'] ?? 0
        );

        $expenseReimbursement = (float) (
            $data['expense_reimbursement_amount'] ?? 0
        );

        $workingSheetOther = (float) (
            $data['working_sheet_other_amount'] ?? 0
        );

        $workingSheetBreakdown =
            $workingSheetBase
            + $extraKm
            + $allowance
            + $overtime
            + $expenseReimbursement
            + $workingSheetOther;

        $storedWorkingSheetTotal = (float) (
            $data['working_sheet_total_amount'] ?? 0
        );

        $hasWorkingSheetData =
            array_key_exists('working_sheet_total_amount', $data) ||
            array_key_exists('working_sheet_base_amount', $data) ||
            array_key_exists('extra_km_amount', $data) ||
            array_key_exists('expense_reimbursement_amount', $data) ||
            array_key_exists('working_sheet_other_amount', $data);

        $workingSheetTotal = $hasWorkingSheetData
            ? ($storedWorkingSheetTotal > 0
                ? $storedWorkingSheetTotal
                : $workingSheetBreakdown)
            : ($allowance + $overtime);

        /*
        |--------------------------------------------------------------------------
        | Other Earnings
        |--------------------------------------------------------------------------
        */

        $bonus = (float) (
            $data['bonus_amount'] ?? 0
        );

        $otherEarnings = (float) (
            $data['other_earnings'] ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Gross Salary
        |--------------------------------------------------------------------------
        */

        $grossSalary =
            $basicSalary
            + $workingSheetTotal
            + $bonus
            + $otherEarnings;

        /*
        |--------------------------------------------------------------------------
        | Deductions
        |--------------------------------------------------------------------------
        */

        $advanceDeduction = (float) (
            $data['advance_deduction'] ?? 0
        );

        $loanDeduction = (float) (
            $data['loan_deduction'] ?? 0
        );

        $penaltyDeduction = (float) (
            $data['penalty_deduction'] ?? 0
        );

        $otherDeductions = (float) (
            $data['other_deductions'] ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Total Deductions
        |--------------------------------------------------------------------------
        */

        $totalDeductions =
            $advanceDeduction
            + $loanDeduction
            + $penaltyDeduction
            + $otherDeductions;

        /*
        |--------------------------------------------------------------------------
        | Net Salary
        |--------------------------------------------------------------------------
        */

        $netSalary = max(
            0,
            $grossSalary - $totalDeductions
        );

        /*
        |--------------------------------------------------------------------------
        | Assign Calculated Values
        |--------------------------------------------------------------------------
        */

        $data['gross_salary'] =
            round($grossSalary, 2);

        $data['total_deductions'] =
            round($totalDeductions, 2);

        $data['net_salary'] =
            round($netSalary, 2);

        /*
        |--------------------------------------------------------------------------
        | Normalize Decimal Fields
        |--------------------------------------------------------------------------
        */

        $data['basic_salary'] =
            round($basicSalary, 2);

        $data['allowance_amount'] =
            round($allowance, 2);

        $data['overtime_amount'] =
            round($overtime, 2);

        $data['bonus_amount'] =
            round($bonus, 2);

        $data['other_earnings'] =
            round($otherEarnings, 2);

        $data['advance_deduction'] =
            round($advanceDeduction, 2);

        $data['loan_deduction'] =
            round($loanDeduction, 2);

        $data['penalty_deduction'] =
            round($penaltyDeduction, 2);

        $data['other_deductions'] =
            round($otherDeductions, 2);

        if ($hasWorkingSheetData) {

            $data['working_sheet_base_amount'] =
                round($workingSheetBase, 2);

            $data['extra_km_amount'] =
                round($extraKm, 2);

            $data['expense_reimbursement_amount'] =
                round($expenseReimbursement, 2);

            $data['working_sheet_other_amount'] =
                round($workingSheetOther, 2);

            $data['working_sheet_total_amount'] =
                round($workingSheetTotal, 2);
        }

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | PROCESS SALARY
    |--------------------------------------------------------------------------
    */

    public function process(
        SalaryProcessing $salaryProcessing
    ): SalaryProcessing {

        return DB::transaction(function () use (
            $salaryProcessing
        ) {

            $salaryProcessing->refresh();

            if (!in_array(
                $salaryProcessing->status,
                [
                    SalaryProcessing::STATUS_DRAFT,
                    SalaryProcessing::STATUS_PROCESSED,
                ],
                true
            )) {

                throw new RuntimeException(
                    'Only draft or processed payroll can be processed.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Recalculate Salary
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->calculateSalary();

            /*
            |--------------------------------------------------------------------------
            | Mark Processed
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->status =
                SalaryProcessing::STATUS_PROCESSED;

            $salaryProcessing->processed_by =
                Auth::id();

            $salaryProcessing->updated_by =
                Auth::id();

            $salaryProcessing->save();

            /*
            |--------------------------------------------------------------------------
            | Return Fresh Record
            |--------------------------------------------------------------------------
            */

            return $salaryProcessing->fresh([
                'driver',
                'processedBy',
                'approvedBy',
                'createdBy',
                'updatedBy',
                'salarySlips',
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVE SALARY
    |--------------------------------------------------------------------------
    */

    public function approve(
        SalaryProcessing $salaryProcessing
    ): SalaryProcessing {

        return DB::transaction(function () use (
            $salaryProcessing
        ) {

            $salaryProcessing->refresh();

            if ($salaryProcessing->status === SalaryProcessing::STATUS_CANCELLED) {

                throw new RuntimeException(
                    'Cancelled payroll cannot be approved.'
                );
            }

            if ($salaryProcessing->status === SalaryProcessing::STATUS_PAID) {

                throw new RuntimeException(
                    'Paid payroll is already finalized.'
                );
            }

            if (!in_array(
                $salaryProcessing->status,
                [
                    SalaryProcessing::STATUS_PROCESSED,
                    SalaryProcessing::STATUS_APPROVED,
                ],
                true
            )) {

                throw new RuntimeException(
                    'Payroll must be processed before approval.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Mark Approved
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->calculateSalary();

            $salaryProcessing->markAsApproved(
                Auth::id()
            );

            /*
            |--------------------------------------------------------------------------
            | Updated By
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->updated_by =
                Auth::id();

            $salaryProcessing->save();

            /*
            |--------------------------------------------------------------------------
            | Generate Salary Slip
            |--------------------------------------------------------------------------
            |
            | One approved SalaryProcessing produces exactly one
            | generated SalarySlip. Existing issued slips are never
            | overwritten by this operation.
            |
            */

            $this->generateSalarySlip(
                $salaryProcessing
            );

            /*
            |--------------------------------------------------------------------------
            | Return Fresh Record
            |--------------------------------------------------------------------------
            */

            return $salaryProcessing->fresh([
                'driver',
                'processedBy',
                'approvedBy',
                'createdBy',
                'updatedBy',
                'salarySlips',
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | MARK AS PAID
    |--------------------------------------------------------------------------
    */

    public function markAsPaid(
        SalaryProcessing $salaryProcessing
    ): SalaryProcessing {

        return DB::transaction(function () use (
            $salaryProcessing
        ) {

            $salaryProcessing->refresh();

            if (!in_array(
                $salaryProcessing->status,
                [
                    SalaryProcessing::STATUS_APPROVED,
                    SalaryProcessing::STATUS_PAID,
                ],
                true
            )) {

                throw new RuntimeException(
                    'Only approved payroll can be marked as paid.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Mark Paid
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->markAsPaid(
                Auth::id()
            );

            /*
            |--------------------------------------------------------------------------
            | Payment Date
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->payment_date =
                $salaryProcessing->payment_date
                ?? now()->toDateString();

            /*
            |--------------------------------------------------------------------------
            | Updated By
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->updated_by =
                Auth::id();

            $salaryProcessing->save();

            /*
            |--------------------------------------------------------------------------
            | Synchronize Salary Slip Payment
            |--------------------------------------------------------------------------
            */

            $salarySlip = SalarySlip::query()
                ->where('salary_processing_id', $salaryProcessing->id)
                ->lockForUpdate()
                ->first();

            if (!$salarySlip) {

                $salarySlip = $this->generateSalarySlip(
                    $salaryProcessing
                );
            }

            if ($salarySlip) {

                if ($salarySlip->status === SalarySlip::STATUS_CANCELLED) {

                    throw new RuntimeException(
                        'Cancelled salary slip cannot be marked as paid.'
                    );
                }

                $salarySlip->payment_status =
                    SalarySlip::PAYMENT_PAID;

                $salarySlip->payment_date =
                    $salaryProcessing->payment_date;

                $salarySlip->save();
            }

            /*
            |--------------------------------------------------------------------------
            | Return Fresh Record
            |--------------------------------------------------------------------------
            */

            return $salaryProcessing->fresh([
                'driver',
                'processedBy',
                'approvedBy',
                'createdBy',
                'updatedBy',
                'salarySlips',
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | CANCEL
    |--------------------------------------------------------------------------
    */

    public function cancel(
        SalaryProcessing $salaryProcessing
    ): SalaryProcessing {

        return DB::transaction(function () use (
            $salaryProcessing
        ) {

            $salaryProcessing->refresh();

            if ($salaryProcessing->status === SalaryProcessing::STATUS_PAID) {

                throw new RuntimeException(
                    'Paid payroll cannot be cancelled.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Mark Cancelled
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->markAsCancelled();

            /*
            |--------------------------------------------------------------------------
            | Updated By
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->updated_by =
                Auth::id();

            $salaryProcessing->save();

            /*
            |--------------------------------------------------------------------------
            | Cancel Generated Salary Slip
            |--------------------------------------------------------------------------
            */

            SalarySlip::query()
                ->where('salary_processing_id', $salaryProcessing->id)
                ->where('payment_status', '!=', SalarySlip::PAYMENT_PAID)
                ->update([
                    'status' => SalarySlip::STATUS_CANCELLED,
                    'updated_at' => now(),
                ]);

            /*
            |--------------------------------------------------------------------------
            | Return Fresh Record
            |--------------------------------------------------------------------------
            */

            return $salaryProcessing->fresh([
                'driver',
                'processedBy',
                'approvedBy',
                'createdBy',
                'updatedBy',
                'salarySlips',
            ]);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | AUTOMATIC MONTHLY PAYROLL GENERATION
    |--------------------------------------------------------------------------
    |
    | Admin selects Month / Year. The service then reads:
    | - Driver monthly_gross_salary for the monthly basic salary.
    | - Final driver_attendances for attendance figures.
    | - Approved / completed Working Sheets for duty and earnings.
    | - Paid Driver Advances linked to Duty Slips in the selected month.
    |
    | Attendance and Working Sheet source rows are read only. This service
    | never creates, edits or deletes attendance / Working Sheet records.
    |
    */

    public function generateMonthlyPayroll(
        int $month,
        int $year,
        ?int $driverId = null
    ): array {

        $this->assertPayrollManager();

        [$periodStart, $periodEnd] = $this->resolveSalaryPeriod(
            $month,
            $year
        );

        $driversQuery = Driver::query()
            ->orderBy('id');

        if ($driverId !== null) {

            $driversQuery->where(
                'id',
                $driverId
            );
        }

        $drivers = $driversQuery->get();

        if ($driverId !== null && $drivers->isEmpty()) {

            throw new RuntimeException(
                'Selected driver was not found or has been deleted.'
            );
        }

        $generated = collect();
        $skipped = [];

        foreach ($drivers as $driver) {

            if (!$this->wasDriverEmployedDuringPeriod(
                $driver,
                $periodStart,
                $periodEnd
            )) {

                $skipped[] = [
                    'driver_id' => $driver->id,
                    'driver_name' => $driver->full_name,
                    'reason' => 'Driver was not employed during the selected salary period.',
                ];

                continue;
            }

            if (
                $driver->monthly_gross_salary === null ||
                (float) $driver->monthly_gross_salary <= 0
            ) {

                $skipped[] = [
                    'driver_id' => $driver->id,
                    'driver_name' => $driver->full_name,
                    'reason' => 'Monthly gross salary is missing or zero in the Driver record.',
                ];

                continue;
            }

            try {

                $generated->push(
                    $this->generateForDriver(
                        (int) $driver->id,
                        $month,
                        $year
                    )
                );

            } catch (RuntimeException $exception) {

                $skipped[] = [
                    'driver_id' => $driver->id,
                    'driver_name' => $driver->full_name,
                    'reason' => $exception->getMessage(),
                ];
            }
        }

        return [
            'salary_month' => $month,
            'salary_year' => $year,
            'generated_count' => $generated->count(),
            'skipped_count' => count($skipped),
            'generated' => $generated,
            'skipped' => $skipped,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE PAYROLL FOR ONE DRIVER
    |--------------------------------------------------------------------------
    */

    public function generateForDriver(
        int $driverId,
        int $month,
        int $year
    ): SalaryProcessing {

        $this->assertPayrollManager();

        [$periodStart, $periodEnd] = $this->resolveSalaryPeriod(
            $month,
            $year
        );

        return DB::transaction(function () use (
            $driverId,
            $month,
            $year,
            $periodStart,
            $periodEnd
        ) {

            $driver = Driver::query()
                ->whereKey($driverId)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$this->wasDriverEmployedDuringPeriod(
                $driver,
                $periodStart,
                $periodEnd
            )) {

                throw new RuntimeException(
                    'Driver was not employed during the selected salary period.'
                );
            }

            $monthlyBasicSalary = (float) (
                $driver->monthly_gross_salary ?? 0
            );

            if ($monthlyBasicSalary <= 0) {

                throw new RuntimeException(
                    'Monthly gross salary is missing or zero in the Driver record.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Existing Payroll / Unique Period Protection
            |--------------------------------------------------------------------------
            */

            $salaryProcessing = SalaryProcessing::withTrashed()
                ->where('driver_id', $driverId)
                ->where('salary_month', $month)
                ->where('salary_year', $year)
                ->lockForUpdate()
                ->first();

            if ($salaryProcessing && $salaryProcessing->trashed()) {

                throw new RuntimeException(
                    'A deleted payroll record already exists for this driver and period. Restore or resolve it before generating payroll again.'
                );
            }

            if (
                $salaryProcessing &&
                $salaryProcessing->status !== SalaryProcessing::STATUS_DRAFT
            ) {

                throw new RuntimeException(
                    'Payroll already exists and is no longer in draft status. It was not overwritten.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Attendance Aggregation
            |--------------------------------------------------------------------------
            |
            | Final attendance records are consumed as saved. Their source
            | (manual / Working Sheet / system / import) is not rewritten.
            |
            */

            $attendanceSummary = $this->getAttendanceSummary(
                $driverId,
                $periodStart,
                $periodEnd
            );

            /*
            |--------------------------------------------------------------------------
            | Working Sheet Aggregation
            |--------------------------------------------------------------------------
            |
            | Only approved and completed Working Sheets are payroll-ready.
            | Draft, submitted and rejected sheets are intentionally excluded.
            |
            */

            $workingSheetSummary = $this->getWorkingSheetSummary(
                $driverId,
                $periodStart,
                $periodEnd
            );

            /*
            |--------------------------------------------------------------------------
            | Driver Advance Deduction
            |--------------------------------------------------------------------------
            |
            | Only advances marked paid are deducted, and only when their
            | linked Duty Slip belongs to the selected salary period.
            | Pending / approved / rejected / cancelled advances are excluded.
            |
            */

            $advanceDeduction = $this->getPaidAdvanceDeduction(
                $driverId,
                $periodStart,
                $periodEnd
            );

            /*
            |--------------------------------------------------------------------------
            | Preserve Admin Adjustments On Draft Regeneration
            |--------------------------------------------------------------------------
            |
            | Bonus, other earnings and discretionary deductions are kept
            | when a draft payroll is generated again. Source-driven values
            | are refreshed from attendance / Working Sheets / advances.
            |
            */

            $manualAdjustments = [
                'bonus_amount' => (float) (
                    $salaryProcessing->bonus_amount ?? 0
                ),
                'other_earnings' => (float) (
                    $salaryProcessing->other_earnings ?? 0
                ),
                'loan_deduction' => (float) (
                    $salaryProcessing->loan_deduction ?? 0
                ),
                'penalty_deduction' => (float) (
                    $salaryProcessing->penalty_deduction ?? 0
                ),
                'other_deductions' => (float) (
                    $salaryProcessing->other_deductions ?? 0
                ),
                'remarks' => $salaryProcessing?->remarks,
            ];

            /*
            |--------------------------------------------------------------------------
            | Build Payroll Snapshot
            |--------------------------------------------------------------------------
            */

            $data = array_merge(
                [
                    'driver_id' => $driver->id,
                    'role' => SalaryProcessing::ROLE_DRIVER,
                    'salary_month' => $month,
                    'salary_year' => $year,
                    'total_working_days' => $attendanceSummary['total_working_days'],
                    'present_days' => $attendanceSummary['present_days'],
                    'absent_days' => $attendanceSummary['absent_days'],
                    'paid_days' => $attendanceSummary['paid_days'],
                    'overtime_hours' => $workingSheetSummary['overtime_hours'],
                    'total_duty_days' => $workingSheetSummary['total_duty_days'],
                    'total_km' => $workingSheetSummary['total_km'],
                    'basic_salary' => $monthlyBasicSalary,
                    'allowance_amount' => $workingSheetSummary['allowance_amount'],
                    'overtime_amount' => $workingSheetSummary['overtime_amount'],
                    'working_sheet_base_amount' => $workingSheetSummary['working_sheet_base_amount'],
                    'extra_km_amount' => $workingSheetSummary['extra_km_amount'],
                    'expense_reimbursement_amount' => $workingSheetSummary['expense_reimbursement_amount'],
                    'working_sheet_other_amount' => $workingSheetSummary['working_sheet_other_amount'],
                    'working_sheet_total_amount' => $workingSheetSummary['working_sheet_total_amount'],
                    'advance_deduction' => $advanceDeduction,
                    'status' => SalaryProcessing::STATUS_DRAFT,
                    'updated_by' => Auth::id(),
                ],
                $manualAdjustments
            );

            $data = $this->calculateSalary($data);

            if ($salaryProcessing) {

                $salaryProcessing->fill($data);
                $salaryProcessing->updated_by = Auth::id();
                $salaryProcessing->calculateSalary();
                $salaryProcessing->save();

            } else {

                $data['created_by'] = Auth::id();
                $data['updated_by'] = Auth::id();

                try {

                    $salaryProcessing = SalaryProcessing::create(
                        $data
                    );

                } catch (QueryException $exception) {

                    // Re-read a concurrently inserted unique driver/month/year row.
                    $salaryProcessing = SalaryProcessing::withTrashed()
                        ->where('driver_id', $driverId)
                        ->where('salary_month', $month)
                        ->where('salary_year', $year)
                        ->lockForUpdate()
                        ->first();

                    if (!$salaryProcessing || $salaryProcessing->trashed()) {
                        throw $exception;
                    }

                    if ($salaryProcessing->status !== SalaryProcessing::STATUS_DRAFT) {

                        throw new RuntimeException(
                            'Payroll was generated concurrently and is no longer in draft status.'
                        );
                    }

                    $salaryProcessing->fill($data);
                    $salaryProcessing->updated_by = Auth::id();
                    $salaryProcessing->calculateSalary();
                    $salaryProcessing->save();
                }
            }

            return $salaryProcessing->fresh([
                'driver',
                'processedBy',
                'approvedBy',
                'createdBy',
                'updatedBy',
                'salarySlips',
            ]);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | GET ATTENDANCE SUMMARY
    |--------------------------------------------------------------------------
    */

    protected function getAttendanceSummary(
        int $driverId,
        Carbon $periodStart,
        Carbon $periodEnd
    ): array {

        $attendances = DriverAttendance::query()
            ->where('driver_id', $driverId)
            ->whereBetween(
                'attendance_date',
                [
                    $periodStart->toDateString(),
                    $periodEnd->toDateString(),
                ]
            )
            ->get([
                'status',
                'attendance_date',
            ]);

        $fullPresentStatuses = [
            DriverAttendance::STATUS_PRESENT,
            DriverAttendance::STATUS_LATE,
            DriverAttendance::STATUS_EARLY_EXIT,
            DriverAttendance::STATUS_PRESENT_LATE,
            DriverAttendance::STATUS_PRESENT_EARLY_EXIT,
            DriverAttendance::STATUS_ON_DUTY,
            DriverAttendance::STATUS_WORK_FROM_HOME,
        ];

        $paidLeaveStatuses = [
            DriverAttendance::STATUS_LEAVE,
            DriverAttendance::STATUS_HOLIDAY,
            DriverAttendance::STATUS_WEEKLY_OFF,
            DriverAttendance::STATUS_RESTRICTED_HOLIDAY,
            DriverAttendance::STATUS_COMP_OFF,
            DriverAttendance::STATUS_MATERNITY_LEAVE,
            DriverAttendance::STATUS_SICK_LEAVE,
            DriverAttendance::STATUS_CASUAL_LEAVE,
            DriverAttendance::STATUS_PAID_LEAVE,
        ];

        $nonWorkingStatuses = [
            DriverAttendance::STATUS_HOLIDAY,
            DriverAttendance::STATUS_WEEKLY_OFF,
            DriverAttendance::STATUS_RESTRICTED_HOLIDAY,
            DriverAttendance::STATUS_COMP_OFF,
        ];

        $totalWorkingDays = 0.0;
        $presentDays = 0.0;
        $absentDays = 0.0;
        $paidDays = 0.0;

        foreach ($attendances as $attendance) {

            $status = $attendance->status;

            if (!in_array($status, $nonWorkingStatuses, true)) {
                $totalWorkingDays += 1;
            }

            if (in_array($status, $fullPresentStatuses, true)) {

                $presentDays += 1;
                $paidDays += 1;

                continue;
            }

            if ($status === DriverAttendance::STATUS_HALF_DAY) {

                $presentDays += 0.5;
                $absentDays += 0.5;
                $paidDays += 0.5;

                continue;
            }

            if (in_array($status, $paidLeaveStatuses, true)) {

                $paidDays += 1;

                continue;
            }

            if (in_array(
                $status,
                [
                    DriverAttendance::STATUS_ABSENT,
                    DriverAttendance::STATUS_UNPAID_LEAVE,
                ],
                true
            )) {

                $absentDays += 1;
            }
        }

        return [
            'total_working_days' => round($totalWorkingDays, 2),
            'present_days' => round($presentDays, 2),
            'absent_days' => round($absentDays, 2),
            'paid_days' => round($paidDays, 2),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | GET WORKING SHEET SUMMARY
    |--------------------------------------------------------------------------
    */

    protected function getWorkingSheetSummary(
        int $driverId,
        Carbon $periodStart,
        Carbon $periodEnd
    ): array {

        $workingSheets = WorkingSheet::query()
            ->where('driver_id', $driverId)
            ->whereBetween(
                'work_date',
                [
                    $periodStart->toDateString(),
                    $periodEnd->toDateString(),
                ]
            )
            ->whereIn(
                'status',
                [
                    WorkingSheet::STATUS_APPROVED,
                    WorkingSheet::STATUS_COMPLETED,
                ]
            )
            ->get([
                'work_date',
                'total_km',
                'overtime_hours',
                'base_amount',
                'extra_km_amount',
                'overtime_amount',
                'allowance_amount',
                'expense_amount',
                'other_amount',
                'total_amount',
            ]);

        $workingSheetBase = (float) $workingSheets->sum('base_amount');
        $extraKm = (float) $workingSheets->sum('extra_km_amount');
        $overtimeAmount = (float) $workingSheets->sum('overtime_amount');
        $allowanceAmount = (float) $workingSheets->sum('allowance_amount');
        $expenseReimbursement = (float) $workingSheets->sum('expense_amount');
        $workingSheetOther = (float) $workingSheets->sum('other_amount');

        $workingSheetTotal = (float) $workingSheets->sum(
            function ($workingSheet) {

                $storedTotal = (float) (
                    $workingSheet->total_amount ?? 0
                );

                if ($storedTotal > 0) {
                    return $storedTotal;
                }

                // Fallback per sheet for older rows whose total_amount was not populated.
                return
                    (float) ($workingSheet->base_amount ?? 0)
                    + (float) ($workingSheet->extra_km_amount ?? 0)
                    + (float) ($workingSheet->overtime_amount ?? 0)
                    + (float) ($workingSheet->allowance_amount ?? 0)
                    + (float) ($workingSheet->expense_amount ?? 0)
                    + (float) ($workingSheet->other_amount ?? 0);
            }
        );

        return [
            'total_duty_days' => $workingSheets
                ->pluck('work_date')
                ->filter()
                ->map(fn ($date) => Carbon::parse($date)->toDateString())
                ->unique()
                ->count(),
            'total_km' => round((float) $workingSheets->sum('total_km'), 2),
            'overtime_hours' => round((float) $workingSheets->sum('overtime_hours'), 2),
            'working_sheet_base_amount' => round($workingSheetBase, 2),
            'extra_km_amount' => round($extraKm, 2),
            'allowance_amount' => round($allowanceAmount, 2),
            'overtime_amount' => round($overtimeAmount, 2),
            'expense_reimbursement_amount' => round($expenseReimbursement, 2),
            'working_sheet_other_amount' => round($workingSheetOther, 2),
            'working_sheet_total_amount' => round($workingSheetTotal, 2),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | GET PAID ADVANCE DEDUCTION
    |--------------------------------------------------------------------------
    */

    protected function getPaidAdvanceDeduction(
        int $driverId,
        Carbon $periodStart,
        Carbon $periodEnd
    ): float {

        return round(
            (float) DriverAdvance::query()
                ->where('driver_id', $driverId)
                ->where('status', DriverAdvance::STATUS_PAID)
                ->whereHas('dutySlip', function ($query) use (
                    $periodStart,
                    $periodEnd
                ) {

                    $query->whereBetween(
                        'duty_date',
                        [
                            $periodStart->toDateString(),
                            $periodEnd->toDateString(),
                        ]
                    );
                })
                ->sum('amount'),
            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE SALARY SLIP
    |--------------------------------------------------------------------------
    */

    protected function generateSalarySlip(
        SalaryProcessing $salaryProcessing
    ): SalarySlip {

        $existingSlip = SalarySlip::withTrashed()
            ->where(
                'salary_processing_id',
                $salaryProcessing->id
            )
            ->lockForUpdate()
            ->first();

        if ($existingSlip) {

            if ($existingSlip->trashed()) {

                throw new RuntimeException(
                    'A deleted salary slip already exists for this payroll record. Resolve it before generating another slip.'
                );
            }

            if ($existingSlip->status === SalarySlip::STATUS_CANCELLED) {

                throw new RuntimeException(
                    'A cancelled salary slip cannot be regenerated automatically.'
                );
            }

            return $existingSlip;
        }

        $periodStart = Carbon::create(
            (int) $salaryProcessing->salary_year,
            (int) $salaryProcessing->salary_month,
            1
        )->startOfMonth();

        $periodEnd = $periodStart->copy()->endOfMonth();

        $slipNo = sprintf(
            'SAL-%04d%02d-%08d',
            (int) $salaryProcessing->salary_year,
            (int) $salaryProcessing->salary_month,
            (int) $salaryProcessing->id
        );

        $slipNoExists = SalarySlip::withTrashed()
            ->where('slip_no', $slipNo)
            ->exists();

        if ($slipNoExists) {

            throw new RuntimeException(
                'The generated salary slip number is already in use. Please resolve the existing slip number before approval.'
            );
        }

        $slipData = [
            'salary_processing_id' => $salaryProcessing->id,
            'driver_id' => $salaryProcessing->driver_id,
            'role' => SalarySlip::ROLE_DRIVER,
            'slip_no' => $slipNo,
            'salary_month' => $salaryProcessing->salary_month,
            'salary_year' => $salaryProcessing->salary_year,
            'period_from' => $periodStart->toDateString(),
            'period_to' => $periodEnd->toDateString(),
            'total_working_days' => $salaryProcessing->total_working_days,
            'present_days' => $salaryProcessing->present_days,
            'absent_days' => $salaryProcessing->absent_days,
            'paid_days' => $salaryProcessing->paid_days,
            'overtime_hours' => $salaryProcessing->overtime_hours,
            'total_duty_days' => $salaryProcessing->total_duty_days,
            'total_km' => $salaryProcessing->total_km,
            'basic_salary' => $salaryProcessing->basic_salary,
            'allowance_amount' => $salaryProcessing->allowance_amount,
            'overtime_amount' => $salaryProcessing->overtime_amount,
            'working_sheet_base_amount' => $salaryProcessing->working_sheet_base_amount,
            'extra_km_amount' => $salaryProcessing->extra_km_amount,
            'expense_reimbursement_amount' => $salaryProcessing->expense_reimbursement_amount,
            'working_sheet_other_amount' => $salaryProcessing->working_sheet_other_amount,
            'working_sheet_total_amount' => $salaryProcessing->working_sheet_total_amount,
            'bonus_amount' => $salaryProcessing->bonus_amount,
            'other_earnings' => $salaryProcessing->other_earnings,
            'gross_salary' => $salaryProcessing->gross_salary,
            'advance_deduction' => $salaryProcessing->advance_deduction,
            'loan_deduction' => $salaryProcessing->loan_deduction,
            'penalty_deduction' => $salaryProcessing->penalty_deduction,
            'other_deductions' => $salaryProcessing->other_deductions,
            'total_deductions' => $salaryProcessing->total_deductions,
            'net_salary' => $salaryProcessing->net_salary,
            'payment_date' => $salaryProcessing->payment_date,
            'payment_status' => SalarySlip::PAYMENT_UNPAID,
            'status' => SalarySlip::STATUS_GENERATED,
            'remarks' => $salaryProcessing->remarks,
            'generated_by' => Auth::id(),
        ];

        return SalarySlip::create(
            $slipData
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK DRIVER EMPLOYMENT PERIOD
    |--------------------------------------------------------------------------
    */

    protected function wasDriverEmployedDuringPeriod(
        Driver $driver,
        Carbon $periodStart,
        Carbon $periodEnd
    ): bool {

        if (
            $driver->joining_date &&
            Carbon::parse($driver->joining_date)->startOfDay()->gt($periodEnd)
        ) {

            return false;
        }

        $employmentEndDate =
            $driver->last_working_date
            ?? $driver->termination_date
            ?? $driver->resignation_date;

        if (
            $employmentEndDate &&
            Carbon::parse($employmentEndDate)->startOfDay()->lt($periodStart)
        ) {

            return false;
        }

        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDATE SALARY PERIOD
    |--------------------------------------------------------------------------
    */

    protected function resolveSalaryPeriod(
        int $month,
        int $year
    ): array {

        if (!checkdate($month, 1, $year)) {

            throw new InvalidArgumentException(
                'A valid salary month and year are required.'
            );
        }

        $periodStart = Carbon::create(
            $year,
            $month,
            1
        )->startOfMonth();

        return [
            $periodStart,
            $periodStart->copy()->endOfMonth(),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | PAYROLL ACCESS CHECK
    |--------------------------------------------------------------------------
    */

    protected function assertPayrollManager(): void
    {

        $user = Auth::user();

        if (!$user instanceof User || $user->isDriver()) {

            abort(403);
        }
    }
}
