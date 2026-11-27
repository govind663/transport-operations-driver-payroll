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

        $month = $filters['salary_month']
            ?? $filters['month']
            ?? null;

        if (!empty($month)) {

            $query->where(
                'salary_month',
                $month
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Salary Year
        |--------------------------------------------------------------------------
        */

        $year = $filters['salary_year']
            ?? $filters['year']
            ?? null;

        if (!empty($year)) {

            $query->where(
                'salary_year',
                $year
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
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
        | Processing Number / Driver Search
        |--------------------------------------------------------------------------
        |
        | Search examples:
        | SAL-202611-000123
        | Driver name
        | Driver code
        | Mobile number
        |
        */

        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            $query->where(function ($q) use ($search) {

                $q->where(
                    'processing_no',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas(
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
        | Sorting / Pagination
        |--------------------------------------------------------------------------
        */

        return $query
            ->latest('salary_year')
            ->latest('salary_month')
            ->latest('id')
            ->paginate(max(1, $perPage))
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
            | Requested Initial Status
            |--------------------------------------------------------------------------
            */

            $requestedStatus = $data['status']
                ?? SalaryProcessing::STATUS_DRAFT;

            if (!in_array(
                $requestedStatus,
                [
                    SalaryProcessing::STATUS_DRAFT,
                    SalaryProcessing::STATUS_PROCESSED,
                ],
                true
            )) {

                throw new InvalidArgumentException(
                    'New payroll can only be created as draft or processed.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Validate Salary Period
            |--------------------------------------------------------------------------
            */

            if (
                !empty($data['salary_month']) &&
                !empty($data['salary_year'])
            ) {

                $this->resolveSalaryPeriod(
                    (int) $data['salary_month'],
                    (int) $data['salary_year']
                );
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
                    ->where(
                        'driver_id',
                        $data['driver_id']
                    )
                    ->where(
                        'salary_month',
                        $data['salary_month']
                    )
                    ->where(
                        'salary_year',
                        $data['salary_year']
                    )
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
            | Role / Audit
            |--------------------------------------------------------------------------
            */

            $data['role'] =
                $data['role']
                ?? SalaryProcessing::ROLE_DRIVER;

            $data['created_by'] = $userId;

            $data['updated_by'] = $userId;

            /*
            |--------------------------------------------------------------------------
            | Lifecycle Fields
            |--------------------------------------------------------------------------
            */

            $data['status'] = SalaryProcessing::STATUS_DRAFT;

            unset(
                $data['id'],
                $data['processing_no'],
                $data['processed_by'],
                $data['approved_by'],
                $data['paid_by'],
                $data['deleted_by'],
                $data['payment_date']
            );

            /*
            |--------------------------------------------------------------------------
            | Default Attendance Values
            |--------------------------------------------------------------------------
            */

            $data['total_working_days'] =
                $data['total_working_days'] ?? 0;

            $data['present_days'] =
                $data['present_days'] ?? 0;

            $data['absent_days'] =
                $data['absent_days'] ?? 0;

            $data['paid_days'] =
                $data['paid_days'] ?? 0;

            $data['overtime_hours'] =
                $data['overtime_hours'] ?? 0;

            $data['total_duty_days'] =
                $data['total_duty_days'] ?? 0;

            $data['total_km'] =
                $data['total_km'] ?? 0;

            /*
            |--------------------------------------------------------------------------
            | Calendar-Days-Based Basic Salary
            |--------------------------------------------------------------------------
            |
            | Driver monthly_gross_salary is the authoritative monthly salary.
            |
            | Any basic_salary received from the request is ignored when
            | a driver and salary period are supplied.
            |
            | Payable Basic Salary:
            |
            | Monthly Gross Salary / Calendar Days * Paid Days
            |
            */

            if (!empty($data['driver_id'])) {

                if (
                    empty($data['salary_month']) ||
                    empty($data['salary_year'])
                ) {

                    throw new InvalidArgumentException(
                        'Salary month and year are required for calendar-days-based salary calculation.'
                    );
                }

                $driver = Driver::query()
                    ->whereKey((int) $data['driver_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $monthlyGrossSalary = (float) (
                    $driver->monthly_gross_salary ?? 0
                );

                if ($monthlyGrossSalary <= 0) {

                    throw new RuntimeException(
                        'Monthly gross salary is missing or zero in the Driver record.'
                    );
                }

                $data['basic_salary'] =
                    $this->calculateProratedBasicSalary(
                        $monthlyGrossSalary,
                        (float) $data['paid_days'],
                        (int) $data['salary_month'],
                        (int) $data['salary_year']
                    );

            } else {

                /*
                |--------------------------------------------------------------------------
                | No Driver Record
                |--------------------------------------------------------------------------
                |
                | Preserve the existing manual fallback for a record
                | created without driver_id, if such a workflow is used.
                |
                */

                $data['basic_salary'] = (float) (
                    $data['basic_salary'] ?? 0
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Calculate Salary
            |--------------------------------------------------------------------------
            */

            $data = $this->calculateSalary($data);

            /*
            |--------------------------------------------------------------------------
            | Create Payroll
            |--------------------------------------------------------------------------
            |
            | SalaryProcessing model events automatically generate:
            | SAL-YYYYMM-000001
            |
            | Temporary number is assigned before insert, and final
            | number is saved after the database ID is generated.
            |
            */

            $salaryProcessing = SalaryProcessing::create($data);

            /*
            |--------------------------------------------------------------------------
            | Process When Requested
            |--------------------------------------------------------------------------
            */

            if (
                $requestedStatus ===
                SalaryProcessing::STATUS_PROCESSED
            ) {

                $salaryProcessing = $this->process(
                    $salaryProcessing
                );
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

            /*
            |--------------------------------------------------------------------------
            | Lock Payroll Row
            |--------------------------------------------------------------------------
            */

            $salaryProcessing = SalaryProcessing::query()
                ->whereKey($salaryProcessing->id)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Existing Payroll Status Protection
            |--------------------------------------------------------------------------
            */

            if ($this->isPayrollLocked($salaryProcessing)) {

                throw new RuntimeException(
                    'Approved, paid or cancelled payroll cannot be edited.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Requested Status
            |--------------------------------------------------------------------------
            */

            $requestedStatus = $data['status']
                ?? $salaryProcessing->status;

            $allowedStatuses = [
                SalaryProcessing::STATUS_DRAFT,
                SalaryProcessing::STATUS_PROCESSED,
                SalaryProcessing::STATUS_APPROVED,
                SalaryProcessing::STATUS_CANCELLED,
            ];

            if (!in_array($requestedStatus, $allowedStatuses, true)) {

                throw new InvalidArgumentException(
                    'Invalid payroll status selected. Paid status must use the separate payment workflow.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Resolve Driver
            |--------------------------------------------------------------------------
            */

            $newDriverId = (int) (
                $data['driver_id']
                ?? $salaryProcessing->driver_id
            );

            if ($newDriverId <= 0) {

                throw new RuntimeException(
                    'A valid driver is required for salary processing.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Resolve Salary Period
            |--------------------------------------------------------------------------
            */

            $newMonth = (int) (
                $data['salary_month']
                ?? $salaryProcessing->salary_month
            );

            $newYear = (int) (
                $data['salary_year']
                ?? $salaryProcessing->salary_year
            );

            $this->resolveSalaryPeriod(
                $newMonth,
                $newYear
            );

            /*
            |--------------------------------------------------------------------------
            | Duplicate Salary Period Protection
            |--------------------------------------------------------------------------
            */

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
            | Validate Paid Days
            |--------------------------------------------------------------------------
            */

            $newPaidDays = (float) (
                $data['paid_days']
                ?? $salaryProcessing->paid_days
                ?? 0
            );

            /*
            |--------------------------------------------------------------------------
            | Lock / Load Driver
            |--------------------------------------------------------------------------
            */

            $driver = Driver::query()
                ->whereKey($newDriverId)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Original Monthly Gross Salary
            |--------------------------------------------------------------------------
            |
            | Never use the previously stored basic_salary as the
            | source for a new calculation.
            |
            | Existing basic_salary may contain an old full-month amount
            | or an earlier prorated amount.
            |
            | Always start from the Driver's original monthly salary.
            |
            */

            $monthlyGrossSalary = (float) (
                $driver->monthly_gross_salary ?? 0
            );

            if ($monthlyGrossSalary <= 0) {

                throw new RuntimeException(
                    'Monthly gross salary is missing or zero in the Driver record.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Recalculate Basic Salary On Every Editable Update
            |--------------------------------------------------------------------------
            |
            | This is intentional.
            |
            | It fixes old Draft / Processed payroll records that may have
            | stored the complete monthly salary instead of the prorated
            | payable amount.
            |
            | It also ensures that changing Paid Days immediately changes
            | Basic Salary.
            |
            | Any client-submitted basic_salary is ignored.
            |
            */

            $data['basic_salary'] =
                $this->calculateProratedBasicSalary(
                    $monthlyGrossSalary,
                    $newPaidDays,
                    $newMonth,
                    $newYear
                );

            /*
            |--------------------------------------------------------------------------
            | Protect Salary Period / Driver Values
            |--------------------------------------------------------------------------
            |
            | Keep the resolved values explicit in the update snapshot.
            |
            */

            $data['driver_id'] = $newDriverId;

            $data['salary_month'] = $newMonth;

            $data['salary_year'] = $newYear;

            /*
            |--------------------------------------------------------------------------
            | Remove Protected / System Fields
            |--------------------------------------------------------------------------
            */

            unset(
                $data['id'],
                $data['processing_no'],
                $data['status'],
                $data['processed_by'],
                $data['approved_by'],
                $data['paid_by'],
                $data['created_by'],
                $data['deleted_by'],
                $data['gross_salary'],
                $data['total_deductions'],
                $data['net_salary'],
                $data['payment_date']
            );

            $data['updated_by'] = Auth::id();

            $data['role'] =
                $data['role']
                ?? $salaryProcessing->role
                ?? SalaryProcessing::ROLE_DRIVER;

            /*
            |--------------------------------------------------------------------------
            | Fill Editable Fields
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->fill($data);

            /*
            |--------------------------------------------------------------------------
            | Recalculate Working Sheet Total
            |--------------------------------------------------------------------------
            |
            | If any breakdown field changes, calculate a new aggregate.
            | This prevents an old working_sheet_total_amount from
            | overriding newly submitted component values.
            |
            */

            $workingSheetFields = [
                'working_sheet_base_amount',
                'extra_km_amount',
                'allowance_amount',
                'overtime_amount',
                'expense_reimbursement_amount',
                'working_sheet_other_amount',
            ];

            $hasWorkingSheetInput = false;

            foreach ($workingSheetFields as $field) {

                if (array_key_exists($field, $data)) {

                    $hasWorkingSheetInput = true;

                    break;
                }
            }

            if ($hasWorkingSheetInput) {

                $salaryProcessing->working_sheet_total_amount =
                    $salaryProcessing->calculateWorkingSheetTotalAmount();
            }

            /*
            |--------------------------------------------------------------------------
            | Recalculate Salary From Latest Values
            |--------------------------------------------------------------------------
            |
            | basic_salary already contains the newly calculated payable
            | Basic Salary. calculateSalary() does not prorate it again.
            |
            */

            $calculationData = $this->calculateSalary(
                $salaryProcessing->getAttributes()
            );

            /*
            |--------------------------------------------------------------------------
            | Apply Calculated Fields
            |--------------------------------------------------------------------------
            */

            $calculatedFields = [
                'working_sheet_total_amount',
                'gross_salary',
                'total_deductions',
                'net_salary',
                'basic_salary',
                'allowance_amount',
                'overtime_amount',
                'bonus_amount',
                'other_earnings',
                'advance_deduction',
                'loan_deduction',
                'penalty_deduction',
                'other_deductions',
                'working_sheet_base_amount',
                'extra_km_amount',
                'expense_reimbursement_amount',
                'working_sheet_other_amount',
            ];

            foreach ($calculatedFields as $field) {

                if (array_key_exists($field, $calculationData)) {

                    $salaryProcessing->{$field} =
                        $calculationData[$field];
                }
            }

            $salaryProcessing->updated_by = Auth::id();

            /*
            |--------------------------------------------------------------------------
            | Save Edited Payroll Data
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->save();

            /*
            |--------------------------------------------------------------------------
            | Apply Requested Status
            |--------------------------------------------------------------------------
            */

            switch ($requestedStatus) {

                /*
                |--------------------------------------------------------------------------
                | DRAFT
                |--------------------------------------------------------------------------
                */

                case SalaryProcessing::STATUS_DRAFT:

                    $salaryProcessing->status =
                        SalaryProcessing::STATUS_DRAFT;

                    $salaryProcessing->processed_by = null;

                    $salaryProcessing->save();

                    break;


                /*
                |--------------------------------------------------------------------------
                | PROCESSED
                |--------------------------------------------------------------------------
                */

                case SalaryProcessing::STATUS_PROCESSED:

                    $salaryProcessing = $this->process(
                        $salaryProcessing
                    );

                    break;


                /*
                |--------------------------------------------------------------------------
                | APPROVED
                |--------------------------------------------------------------------------
                */

                case SalaryProcessing::STATUS_APPROVED:

                    /*
                     * First process the updated payroll.
                     */

                    $salaryProcessing = $this->process(
                        $salaryProcessing
                    );

                    /*
                     * Then approve and generate the Salary Slip.
                     */

                    $salaryProcessing = $this->approve(
                        $salaryProcessing
                    );

                    break;


                /*
                |--------------------------------------------------------------------------
                | CANCELLED
                |--------------------------------------------------------------------------
                */

                case SalaryProcessing::STATUS_CANCELLED:

                    $salaryProcessing = $this->cancel(
                        $salaryProcessing
                    );

                    break;
            }

            /*
            |--------------------------------------------------------------------------
            | Return Updated Record
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

        return DB::transaction(function () use ($salaryProcessing) {

            $salaryProcessing = SalaryProcessing::query()
                ->whereKey($salaryProcessing->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $salaryProcessing->status ===
                SalaryProcessing::STATUS_PAID
            ) {

                throw new RuntimeException(
                    'Paid payroll cannot be deleted.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Deleted By
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->deleted_by = Auth::id();

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
    |
    | IMPORTANT:
    |
    | This method does not apply calendar-day prorating.
    |
    | basic_salary must already contain the payable Basic Salary snapshot.
    |
    | Prorating is applied when:
    | - Creating payroll.
    | - Generating monthly payroll.
    | - Updating editable payroll.
    | - Previewing standalone Salary Slip.
    |
    | Keeping this method free from prorating prevents repeated
    | calculation when process() or approve() recalculates payroll.
    |
    */

    public function calculateSalary(
        array $data
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Basic Salary
        |--------------------------------------------------------------------------
        */

        $basicSalary = (float) (
            $data['basic_salary'] ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Working Sheet Breakdown
        |--------------------------------------------------------------------------
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

        /*
        |--------------------------------------------------------------------------
        | Check Working Sheet Data
        |--------------------------------------------------------------------------
        */

        $hasWorkingSheetData =
            array_key_exists('working_sheet_total_amount', $data) ||
            array_key_exists('working_sheet_base_amount', $data) ||
            array_key_exists('extra_km_amount', $data) ||
            array_key_exists('allowance_amount', $data) ||
            array_key_exists('overtime_amount', $data) ||
            array_key_exists('expense_reimbursement_amount', $data) ||
            array_key_exists('working_sheet_other_amount', $data);

        /*
        |--------------------------------------------------------------------------
        | Determine Working Sheet Total
        |--------------------------------------------------------------------------
        |
        | Use saved aggregate when present.
        | Otherwise calculate from the individual components.
        |
        */

        if ($hasWorkingSheetData) {

            $workingSheetTotal = $storedWorkingSheetTotal > 0
                ? $storedWorkingSheetTotal
                : $workingSheetBreakdown;

        } else {

            /*
             * Manual payroll without Working Sheet breakdown.
             */

            $workingSheetTotal = $allowance + $overtime;
        }

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

        /*
        |--------------------------------------------------------------------------
        | Save Working Sheet Breakdown
        |--------------------------------------------------------------------------
        */

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
    | CALCULATE PRORATED BASIC SALARY
    |--------------------------------------------------------------------------
    |
    | Formula:
    |
    | Daily Salary = Monthly Gross Salary / Calendar Days in Month
    |
    | Payable Basic Salary = Daily Salary * Paid Days
    |
    | Examples:
    | November 2026:
    | Monthly Salary = 19000
    | Calendar Days = 30
    | Paid Days = 10
    | Payable Basic Salary = 6333.33
    |
    | February automatically uses 28 or 29 days.
    |
    | Working Sheet earnings, bonus and other earnings are not prorated.
    |
    */

    protected function calculateProratedBasicSalary(
        float $monthlyGrossSalary,
        float $paidDays,
        int $month,
        int $year
    ): float {

        /*
        |--------------------------------------------------------------------------
        | Validate Monthly Salary
        |--------------------------------------------------------------------------
        */

        if (
            !is_finite($monthlyGrossSalary) ||
            $monthlyGrossSalary < 0
        ) {

            throw new InvalidArgumentException(
                'Monthly gross salary must be a valid non-negative amount.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Salary Period
        |--------------------------------------------------------------------------
        */

        if (
            $month < 1 ||
            $month > 12 ||
            $year < 2000 ||
            $year > 2100 ||
            !checkdate($month, 1, $year)
        ) {

            throw new InvalidArgumentException(
                'A valid salary month and year are required for prorated salary calculation.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Paid Days
        |--------------------------------------------------------------------------
        */

        if (
            !is_finite($paidDays) ||
            $paidDays < 0
        ) {

            throw new InvalidArgumentException(
                'Paid days must be a valid non-negative number.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Actual Calendar Days
        |--------------------------------------------------------------------------
        |
        | Automatically handles:
        | - 31-day months
        | - 30-day months
        | - February with 28 days
        | - Leap-year February with 29 days
        |
        */

        $calendarDays = Carbon::create(
            $year,
            $month,
            1
        )->daysInMonth;

        /*
        |--------------------------------------------------------------------------
        | Paid Days Cannot Exceed Calendar Days
        |--------------------------------------------------------------------------
        |
        | Do not silently cap an invalid Paid Days value.
        | Return a validation-friendly error instead.
        |
        */

        if ($paidDays > $calendarDays) {

            throw new InvalidArgumentException(
                "Paid days cannot exceed {$calendarDays} calendar days for the selected salary month."
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Normalize Paid Days
        |--------------------------------------------------------------------------
        */

        $paidDays = round(
            $paidDays,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Daily Salary
        |--------------------------------------------------------------------------
        */

        $dailySalary = $monthlyGrossSalary / $calendarDays;

        /*
        |--------------------------------------------------------------------------
        | Payable Basic Salary
        |--------------------------------------------------------------------------
        */

        $payableBasicSalary =
            $dailySalary * $paidDays;

        return round(
            $payableBasicSalary,
            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PAYROLL LOCK CHECK
    |--------------------------------------------------------------------------
    */

    protected function isPayrollLocked(
        SalaryProcessing $salaryProcessing
    ): bool {

        return in_array(
            $salaryProcessing->status,
            [
                SalaryProcessing::STATUS_APPROVED,
                SalaryProcessing::STATUS_PAID,
                SalaryProcessing::STATUS_CANCELLED,
            ],
            true
        );
    }

        /*
    |--------------------------------------------------------------------------
    | PROCESS SALARY
    |--------------------------------------------------------------------------
    */

    public function process(
        SalaryProcessing $salaryProcessing
    ): SalaryProcessing {

        return DB::transaction(function () use ($salaryProcessing) {

            /*
            |--------------------------------------------------------------------------
            | Lock Payroll
            |--------------------------------------------------------------------------
            */

            $salaryProcessing = SalaryProcessing::query()
                ->whereKey($salaryProcessing->id)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Status Protection
            |--------------------------------------------------------------------------
            */

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
            | Recalculate Working Sheet Total
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->working_sheet_total_amount =
                $salaryProcessing->calculateWorkingSheetTotalAmount();

            /*
            |--------------------------------------------------------------------------
            | Recalculate Salary
            |--------------------------------------------------------------------------
            |
            | basic_salary already contains the prorated payable amount.
            | Do not apply calendar-day prorating again here.
            |
            */

            $salaryProcessing->calculateSalary();

            /*
            |--------------------------------------------------------------------------
            | Mark Processed
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->status =
                SalaryProcessing::STATUS_PROCESSED;

            $salaryProcessing->processed_by = Auth::id();

            $salaryProcessing->updated_by = Auth::id();

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

        return DB::transaction(function () use ($salaryProcessing) {

            /*
            |--------------------------------------------------------------------------
            | Lock Payroll
            |--------------------------------------------------------------------------
            */

            $salaryProcessing = SalaryProcessing::query()
                ->whereKey($salaryProcessing->id)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Status Protection
            |--------------------------------------------------------------------------
            */

            if (
                $salaryProcessing->status ===
                SalaryProcessing::STATUS_CANCELLED
            ) {

                throw new RuntimeException(
                    'Cancelled payroll cannot be approved.'
                );
            }

            if (
                $salaryProcessing->status ===
                SalaryProcessing::STATUS_PAID
            ) {

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
            | Recalculate Salary
            |--------------------------------------------------------------------------
            |
            | Recalculate existing payroll snapshot.
            | Do not prorate basic_salary again.
            |
            */

            $salaryProcessing->working_sheet_total_amount =
                $salaryProcessing->calculateWorkingSheetTotalAmount();

            $salaryProcessing->calculateSalary();

            /*
            |--------------------------------------------------------------------------
            | Mark Approved
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->markAsApproved(
                Auth::id()
            );

            $salaryProcessing->updated_by = Auth::id();

            $salaryProcessing->save();

            /*
            |--------------------------------------------------------------------------
            | Generate Salary Slip
            |--------------------------------------------------------------------------
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

        return DB::transaction(function () use ($salaryProcessing) {

            /*
            |--------------------------------------------------------------------------
            | Lock Payroll
            |--------------------------------------------------------------------------
            */

            $salaryProcessing = SalaryProcessing::query()
                ->whereKey($salaryProcessing->id)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Status Protection
            |--------------------------------------------------------------------------
            */

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
            | Payment Date
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->payment_date =
                $salaryProcessing->payment_date
                ?? now()->toDateString();

            /*
            |--------------------------------------------------------------------------
            | Mark Payroll As Paid
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->markAsPaid(
                Auth::id()
            );

            $salaryProcessing->updated_by = Auth::id();

            $salaryProcessing->save();

            /*
            |--------------------------------------------------------------------------
            | Find Salary Slip
            |--------------------------------------------------------------------------
            */

            $salarySlip = SalarySlip::query()
                ->where(
                    'salary_processing_id',
                    $salaryProcessing->id
                )
                ->lockForUpdate()
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Generate Missing Salary Slip
            |--------------------------------------------------------------------------
            */

            if (!$salarySlip) {

                $salarySlip = $this->generateSalarySlip(
                    $salaryProcessing
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Synchronize Salary Slip Payment
            |--------------------------------------------------------------------------
            */

            if ($salarySlip) {

                if (
                    $salarySlip->status ===
                    SalarySlip::STATUS_CANCELLED
                ) {

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

        return DB::transaction(function () use ($salaryProcessing) {

            /*
            |--------------------------------------------------------------------------
            | Lock Payroll
            |--------------------------------------------------------------------------
            */

            $salaryProcessing = SalaryProcessing::query()
                ->whereKey($salaryProcessing->id)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Paid Payroll Protection
            |--------------------------------------------------------------------------
            */

            if (
                $salaryProcessing->status ===
                SalaryProcessing::STATUS_PAID
            ) {

                throw new RuntimeException(
                    'Paid payroll cannot be cancelled.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Paid Salary Slip Protection
            |--------------------------------------------------------------------------
            |
            | A payroll with an already-paid Salary Slip must not be
            | cancelled, even if the parent payroll status is inconsistent.
            |
            */

            $hasPaidSlip = SalarySlip::query()
                ->where(
                    'salary_processing_id',
                    $salaryProcessing->id
                )
                ->where(
                    'payment_status',
                    SalarySlip::PAYMENT_PAID
                )
                ->exists();

            if ($hasPaidSlip) {

                throw new RuntimeException(
                    'Payroll cannot be cancelled because its Salary Slip is already paid.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Mark Cancelled
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->markAsCancelled();

            $salaryProcessing->updated_by = Auth::id();

            $salaryProcessing->save();

            /*
            |--------------------------------------------------------------------------
            | Cancel Generated Salary Slip
            |--------------------------------------------------------------------------
            */

            SalarySlip::query()
                ->where(
                    'salary_processing_id',
                    $salaryProcessing->id
                )
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
    | Reads:
    | - Driver monthly_gross_salary
    | - Driver attendance
    | - Approved / completed Working Sheets
    | - Paid Driver Advances linked to selected period Duty Slips
    |
    | Attendance and Working Sheet source records are read only.
    |
    */

    public function generateMonthlyPayroll(
        int $month,
        int $year,
        ?int $driverId = null
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Permission Check
        |--------------------------------------------------------------------------
        */

        $this->assertPayrollManager();

        /*
        |--------------------------------------------------------------------------
        | Validate Salary Period
        |--------------------------------------------------------------------------
        */

        [$periodStart, $periodEnd] = $this->resolveSalaryPeriod(
            $month,
            $year
        );

        /*
        |--------------------------------------------------------------------------
        | Drivers Query
        |--------------------------------------------------------------------------
        */

        $driversQuery = Driver::query()
            ->orderBy('id');

        if ($driverId !== null) {

            $driversQuery->where(
                'id',
                $driverId
            );
        }

        $drivers = $driversQuery->get();

        if (
            $driverId !== null &&
            $drivers->isEmpty()
        ) {

            throw new RuntimeException(
                'Selected driver was not found or has been deleted.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Result Collections
        |--------------------------------------------------------------------------
        */

        $generated = collect();

        $skipped = [];

        /*
        |--------------------------------------------------------------------------
        | Generate Payroll Driver By Driver
        |--------------------------------------------------------------------------
        */

        foreach ($drivers as $driver) {

            /*
            |--------------------------------------------------------------------------
            | Employment Period
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Monthly Salary
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Generate Payroll
            |--------------------------------------------------------------------------
            */

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

        /*
        |--------------------------------------------------------------------------
        | Return Generation Summary
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Permission Check
        |--------------------------------------------------------------------------
        */

        $this->assertPayrollManager();

        /*
        |--------------------------------------------------------------------------
        | Validate Salary Period
        |--------------------------------------------------------------------------
        */

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

            /*
            |--------------------------------------------------------------------------
            | Lock Driver
            |--------------------------------------------------------------------------
            */

            $driver = Driver::query()
                ->whereKey($driverId)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Employment Check
            |--------------------------------------------------------------------------
            */

            if (!$this->wasDriverEmployedDuringPeriod(
                $driver,
                $periodStart,
                $periodEnd
            )) {

                throw new RuntimeException(
                    'Driver was not employed during the selected salary period.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Monthly Gross Salary
            |--------------------------------------------------------------------------
            |
            | This is the original monthly salary stored in the Driver record.
            | It is not directly saved as the payable basic_salary.
            |
            */

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

            if (
                $salaryProcessing &&
                $salaryProcessing->trashed()
            ) {

                throw new RuntimeException(
                    'A deleted payroll record already exists for this driver and period. Restore or resolve it before generating payroll again.'
                );
            }

            if (
                $salaryProcessing &&
                $salaryProcessing->status !==
                SalaryProcessing::STATUS_DRAFT
            ) {

                throw new RuntimeException(
                    'Payroll already exists and is no longer in draft status. It was not overwritten.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Attendance Aggregation
            |--------------------------------------------------------------------------
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
            */

            $workingSheetSummary = $this->getWorkingSheetSummary(
                $driverId,
                $periodStart,
                $periodEnd
            );

            /*
            |--------------------------------------------------------------------------
            | Advance Deduction
            |--------------------------------------------------------------------------
            */

            $advanceDeduction = $this->getPaidAdvanceDeduction(
                $driverId,
                $periodStart,
                $periodEnd
            );

            /*
            |--------------------------------------------------------------------------
            | Calculate Calendar-Days-Based Basic Salary
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | Monthly Gross Salary = 19000
            | November Calendar Days = 30
            | Paid Days = 10
            |
            | Daily Salary = 19000 / 30
            | Payable Basic Salary = Daily Salary * 10
            | Payable Basic Salary = 6333.33
            |
            | Paid days include:
            | - Full present day: 1.0
            | - Half day: 0.5
            | - Paid leave: 1.0
            | - Other paid attendance statuses as defined
            |   in getAttendanceSummary().
            |
            */

            $payableBasicSalary = $this->calculateProratedBasicSalary(
                $monthlyBasicSalary,
                (float) $attendanceSummary['paid_days'],
                $month,
                $year
            );

            /*
            |--------------------------------------------------------------------------
            | Preserve Manual Adjustments On Draft Regeneration
            |--------------------------------------------------------------------------
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

                    'total_working_days' =>
                        $attendanceSummary['total_working_days'],

                    'present_days' =>
                        $attendanceSummary['present_days'],

                    'absent_days' =>
                        $attendanceSummary['absent_days'],

                    'paid_days' =>
                        $attendanceSummary['paid_days'],

                    'overtime_hours' =>
                        $workingSheetSummary['overtime_hours'],

                    'total_duty_days' =>
                        $workingSheetSummary['total_duty_days'],

                    'total_km' =>
                        $workingSheetSummary['total_km'],

                    /*
                    |--------------------------------------------------------------------------
                    | Payable Basic Salary
                    |--------------------------------------------------------------------------
                    |
                    | Save the prorated amount, not the original monthly amount.
                    |
                    */

                    'basic_salary' =>
                        $payableBasicSalary,

                    /*
                    |--------------------------------------------------------------------------
                    | Working Sheet Earnings
                    |--------------------------------------------------------------------------
                    |
                    | These amounts remain separate from prorated Basic Salary.
                    | They are added to gross salary by calculateSalary().
                    |
                    */

                    'allowance_amount' =>
                        $workingSheetSummary['allowance_amount'],

                    'overtime_amount' =>
                        $workingSheetSummary['overtime_amount'],

                    'working_sheet_base_amount' =>
                        $workingSheetSummary['working_sheet_base_amount'],

                    'extra_km_amount' =>
                        $workingSheetSummary['extra_km_amount'],

                    'expense_reimbursement_amount' =>
                        $workingSheetSummary['expense_reimbursement_amount'],

                    'working_sheet_other_amount' =>
                        $workingSheetSummary['working_sheet_other_amount'],

                    'working_sheet_total_amount' =>
                        $workingSheetSummary['working_sheet_total_amount'],

                    'advance_deduction' =>
                        $advanceDeduction,

                    'status' =>
                        SalaryProcessing::STATUS_DRAFT,

                    'updated_by' =>
                        Auth::id(),
                ],
                $manualAdjustments
            );

            /*
            |--------------------------------------------------------------------------
            | Calculate Payroll Amounts
            |--------------------------------------------------------------------------
            */

            $data = $this->calculateSalary($data);

            /*
            |--------------------------------------------------------------------------
            | Update Existing Draft Payroll
            |--------------------------------------------------------------------------
            |
            | Existing processing_no is preserved.
            |
            */

            if ($salaryProcessing) {

                $salaryProcessing->fill($data);

                $salaryProcessing->updated_by = Auth::id();

                /*
                |--------------------------------------------------------------------------
                | Recalculate Using Existing Payable Snapshot
                |--------------------------------------------------------------------------
                |
                | Do not prorate again here.
                |
                */

                $salaryProcessing->calculateSalary();

                $salaryProcessing->save();

            } else {

                /*
                |--------------------------------------------------------------------------
                | Create New Payroll
                |--------------------------------------------------------------------------
                |
                | SalaryProcessing model booted events automatically
                | generate and persist processing_no using the new ID.
                |
                */

                $data['created_by'] = Auth::id();

                $data['updated_by'] = Auth::id();

                try {

                    $salaryProcessing = SalaryProcessing::create(
                        $data
                    );

                } catch (QueryException $exception) {

                    /*
                    |--------------------------------------------------------------------------
                    | Concurrent Insert Protection
                    |--------------------------------------------------------------------------
                    */

                    $salaryProcessing = SalaryProcessing::withTrashed()
                        ->where('driver_id', $driverId)
                        ->where('salary_month', $month)
                        ->where('salary_year', $year)
                        ->lockForUpdate()
                        ->first();

                    if (
                        !$salaryProcessing ||
                        $salaryProcessing->trashed()
                    ) {

                        throw $exception;
                    }

                    if (
                        $salaryProcessing->status !==
                        SalaryProcessing::STATUS_DRAFT
                    ) {

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

            /*
            |--------------------------------------------------------------------------
            | Return Generated Payroll
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
    | PREVIEW STANDALONE SALARY SLIP
    |--------------------------------------------------------------------------
    |
    | Read-only preview for the manual / standalone Salary Slip form.
    |
    | Important:
    | - Does not create or update SalaryProcessing.
    | - Uses the same Attendance, Working Sheet and Paid Advance
    |   aggregation methods used by monthly payroll generation.
    | - Does not allow a standalone slip when a payroll record already
    |   exists for the selected driver and salary period.
    | - Uses actual calendar days for prorated Basic Salary.
    |
    */

    public function previewStandaloneSalarySlip(
        int $driverId,
        int $month,
        int $year,
        ?string $periodFrom = null,
        ?string $periodTo = null
    ): array {

        $this->assertPayrollManager();

        /*
        |--------------------------------------------------------------------------
        | Resolve Salary Period
        |--------------------------------------------------------------------------
        */

        [$defaultPeriodStart, $defaultPeriodEnd] = $this->resolveSalaryPeriod(
            $month,
            $year
        );

        /*
        |--------------------------------------------------------------------------
        | Explicit Period From / To
        |--------------------------------------------------------------------------
        |
        | Use the exact dates selected in the manual Salary Slip form for
        | source queries.
        |
        | Calendar-days divisor remains based on the selected salary
        | month and year.
        |
        */

        if (($periodFrom && !$periodTo) || (!$periodFrom && $periodTo)) {

            throw new InvalidArgumentException(
                'Both Period From and Period To are required.'
            );
        }

        if ($periodFrom && $periodTo) {

            try {

                $periodStart = Carbon::parse($periodFrom)->startOfDay();

                $periodEnd = Carbon::parse($periodTo)->endOfDay();

            } catch (\Throwable $exception) {

                throw new InvalidArgumentException(
                    'The selected salary period dates are invalid.',
                    0,
                    $exception
                );
            }

            if (
                $periodStart->format('Y-m-d') !== $periodFrom ||
                $periodEnd->format('Y-m-d') !== $periodTo
            ) {

                throw new InvalidArgumentException(
                    'The selected salary period dates must use YYYY-MM-DD format.'
                );
            }

            if ($periodStart->greaterThan($periodEnd)) {

                throw new InvalidArgumentException(
                    'Period From cannot be later than Period To.'
                );
            }

        } else {

            $periodStart = $defaultPeriodStart;

            $periodEnd = $defaultPeriodEnd;
        }

        /*
        |--------------------------------------------------------------------------
        | Load Driver
        |--------------------------------------------------------------------------
        */

        $driver = Driver::query()
            ->whereKey($driverId)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Employment Validation
        |--------------------------------------------------------------------------
        */

        if (!$this->wasDriverEmployedDuringPeriod(
            $driver,
            $periodStart,
            $periodEnd
        )) {

            throw new RuntimeException(
                'Driver was not employed during the selected salary period.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Monthly Gross Salary
        |--------------------------------------------------------------------------
        */

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
        | Existing Payroll Protection
        |--------------------------------------------------------------------------
        |
        | Payroll-linked Salary Slips are generated from the approved / paid
        | payroll snapshot.
        |
        | Do not create a standalone slip for a period which already has
        | any payroll record.
        |
        */

        $existingPayroll = SalaryProcessing::withTrashed()
            ->where('driver_id', $driverId)
            ->where('salary_month', $month)
            ->where('salary_year', $year)
            ->first();

        if ($existingPayroll) {

            if ($existingPayroll->trashed()) {

                throw new RuntimeException(
                    'A deleted payroll record already exists for this driver and period. Restore or resolve it before creating a Salary Slip.'
                );
            }

            throw new RuntimeException(
                'Salary Processing already exists for this driver and period. Generate or recover the Salary Slip from Salary Processing instead.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Existing Salary Slip Protection
        |--------------------------------------------------------------------------
        */

        $existingSlip = SalarySlip::withTrashed()
            ->where('driver_id', $driverId)
            ->where('salary_month', $month)
            ->where('salary_year', $year)
            ->first();

        if ($existingSlip) {

            throw new RuntimeException(
                'A Salary Slip already exists for this driver and period. Restore or resolve the existing record instead of creating another one.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Attendance Summary
        |--------------------------------------------------------------------------
        */

        $attendanceSummary = $this->getAttendanceSummary(
            $driverId,
            $periodStart,
            $periodEnd
        );

        /*
        |--------------------------------------------------------------------------
        | Approved / Completed Working Sheet Summary
        |--------------------------------------------------------------------------
        */

        $workingSheetSummary = $this->getWorkingSheetSummary(
            $driverId,
            $periodStart,
            $periodEnd
        );

        /*
        |--------------------------------------------------------------------------
        | Paid Advance Deduction
        |--------------------------------------------------------------------------
        */

        $advanceDeduction = $this->getPaidAdvanceDeduction(
            $driverId,
            $periodStart,
            $periodEnd
        );

        /*
        |--------------------------------------------------------------------------
        | Calendar-Days-Based Basic Salary
        |--------------------------------------------------------------------------
        |
        | Use:
        | - Monthly gross salary from Driver record.
        | - Actual calendar days of selected salary month.
        | - Paid days from attendance summary.
        |
        | The preview uses the same helper as generateForDriver().
        |
        */

        $payableBasicSalary = $this->calculateProratedBasicSalary(
            $monthlyBasicSalary,
            (float) $attendanceSummary['paid_days'],
            $month,
            $year
        );

        /*
        |--------------------------------------------------------------------------
        | Build Read-Only Preview
        |--------------------------------------------------------------------------
        |
        | Working Sheet total is carried as one authoritative amount.
        | The individual components are also returned for display.
        |
        */

        $data = array_merge(
            [
                'driver_id' => $driver->id,

                'salary_month' => $month,

                'salary_year' => $year,

                'basic_salary' => $payableBasicSalary,

                'advance_deduction' => $advanceDeduction,

                'bonus_amount' => 0,

                'other_earnings' => 0,

                'loan_deduction' => 0,

                'penalty_deduction' => 0,

                'other_deductions' => 0,
            ],
            $attendanceSummary,
            $workingSheetSummary
        );

        /*
        |--------------------------------------------------------------------------
        | Calculate Preview Salary
        |--------------------------------------------------------------------------
        */

        $data = $this->calculateSalary($data);

        /*
        |--------------------------------------------------------------------------
        | Return Preview
        |--------------------------------------------------------------------------
        */

        return [
            'salary_month' => sprintf('%04d-%02d', $year, $month),

            'period_from' => $periodStart->toDateString(),

            'period_to' => $periodEnd->toDateString(),

            'driver' => [
                'id' => (int) $driver->id,

                'name' => (string) ($driver->name ?? ''),

                'driver_code' => (string) ($driver->driver_code ?? ''),

                /*
                |--------------------------------------------------------------------------
                | Original Monthly Gross Salary
                |--------------------------------------------------------------------------
                */

                'monthly_gross_salary' => round(
                    $monthlyBasicSalary,
                    2
                ),
            ],

            'data' => $data,
        ];
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

        /*
        |--------------------------------------------------------------------------
        | Fetch Attendance
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Present Statuses
        |--------------------------------------------------------------------------
        */

        $fullPresentStatuses = [
            DriverAttendance::STATUS_PRESENT,
            DriverAttendance::STATUS_LATE,
            DriverAttendance::STATUS_EARLY_EXIT,
            DriverAttendance::STATUS_PRESENT_LATE,
            DriverAttendance::STATUS_PRESENT_EARLY_EXIT,
            DriverAttendance::STATUS_ON_DUTY,
            DriverAttendance::STATUS_WORK_FROM_HOME,
        ];

        /*
        |--------------------------------------------------------------------------
        | Paid Leave Statuses
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Non-Working Statuses
        |--------------------------------------------------------------------------
        */

        $nonWorkingStatuses = [
            DriverAttendance::STATUS_HOLIDAY,
            DriverAttendance::STATUS_WEEKLY_OFF,
            DriverAttendance::STATUS_RESTRICTED_HOLIDAY,
            DriverAttendance::STATUS_COMP_OFF,
        ];

        /*
        |--------------------------------------------------------------------------
        | Initialize Counters
        |--------------------------------------------------------------------------
        */

        $totalWorkingDays = 0.0;

        $presentDays = 0.0;

        $absentDays = 0.0;

        $paidDays = 0.0;

        /*
        |--------------------------------------------------------------------------
        | Calculate Attendance
        |--------------------------------------------------------------------------
        */

        foreach ($attendances as $attendance) {

            $status = $attendance->status;

            /*
            |--------------------------------------------------------------------------
            | Total Working Days
            |--------------------------------------------------------------------------
            */

            if (!in_array($status, $nonWorkingStatuses, true)) {

                $totalWorkingDays += 1;
            }

            /*
            |--------------------------------------------------------------------------
            | Full Present
            |--------------------------------------------------------------------------
            */

            if (in_array($status, $fullPresentStatuses, true)) {

                $presentDays += 1;

                $paidDays += 1;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Half Day
            |--------------------------------------------------------------------------
            |
            | Half day counts as:
            | Present Days = 0.5
            | Absent Days = 0.5
            | Paid Days = 0.5
            |
            */

            if (
                $status ===
                DriverAttendance::STATUS_HALF_DAY
            ) {

                $presentDays += 0.5;

                $absentDays += 0.5;

                $paidDays += 0.5;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Paid Leave
            |--------------------------------------------------------------------------
            */

            if (in_array($status, $paidLeaveStatuses, true)) {

                $paidDays += 1;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Absent / Unpaid Leave
            |--------------------------------------------------------------------------
            */

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

        /*
        |--------------------------------------------------------------------------
        | Return Summary
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Fetch Approved / Completed Working Sheets
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Component Amounts
        |--------------------------------------------------------------------------
        */

        $workingSheetBase = (float) $workingSheets->sum(
            'base_amount'
        );

        $extraKm = (float) $workingSheets->sum(
            'extra_km_amount'
        );

        $overtimeAmount = (float) $workingSheets->sum(
            'overtime_amount'
        );

        $allowanceAmount = (float) $workingSheets->sum(
            'allowance_amount'
        );

        $expenseReimbursement = (float) $workingSheets->sum(
            'expense_amount'
        );

        $workingSheetOther = (float) $workingSheets->sum(
            'other_amount'
        );

        /*
        |--------------------------------------------------------------------------
        | Working Sheet Total
        |--------------------------------------------------------------------------
        |
        | Prefer each sheet's saved total_amount.
        | For older records with zero total_amount, calculate from
        | that sheet's individual component amounts.
        |
        */

        $workingSheetTotal = (float) $workingSheets->sum(
            function ($workingSheet) {

                $storedTotal = (float) (
                    $workingSheet->total_amount ?? 0
                );

                if ($storedTotal > 0) {

                    return $storedTotal;
                }

                return
                    (float) ($workingSheet->base_amount ?? 0)
                    + (float) ($workingSheet->extra_km_amount ?? 0)
                    + (float) ($workingSheet->overtime_amount ?? 0)
                    + (float) ($workingSheet->allowance_amount ?? 0)
                    + (float) ($workingSheet->expense_amount ?? 0)
                    + (float) ($workingSheet->other_amount ?? 0);
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Unique Duty Days
        |--------------------------------------------------------------------------
        */

        $totalDutyDays = $workingSheets
            ->pluck('work_date')
            ->filter()
            ->map(
                fn ($date) => Carbon::parse($date)->toDateString()
            )
            ->unique()
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Return Summary
        |--------------------------------------------------------------------------
        */

        return [

            'total_duty_days' => $totalDutyDays,

            'total_km' => round(
                (float) $workingSheets->sum('total_km'),
                2
            ),

            'overtime_hours' => round(
                (float) $workingSheets->sum('overtime_hours'),
                2
            ),

            'working_sheet_base_amount' => round(
                $workingSheetBase,
                2
            ),

            'extra_km_amount' => round(
                $extraKm,
                2
            ),

            'allowance_amount' => round(
                $allowanceAmount,
                2
            ),

            'overtime_amount' => round(
                $overtimeAmount,
                2
            ),

            'expense_reimbursement_amount' => round(
                $expenseReimbursement,
                2
            ),

            'working_sheet_other_amount' => round(
                $workingSheetOther,
                2
            ),

            'working_sheet_total_amount' => round(
                $workingSheetTotal,
                2
            ),
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

        /*
        |--------------------------------------------------------------------------
        | Only Paid Advances Are Deducted
        |--------------------------------------------------------------------------
        |
        | Advance must be linked to a Duty Slip in the selected salary
        | period. Pending / approved / rejected / cancelled advances
        | are not included.
        |
        */

        $amount = DriverAdvance::query()
            ->where('driver_id', $driverId)
            ->where(
                'status',
                DriverAdvance::STATUS_PAID
            )
            ->whereHas(
                'dutySlip',
                function ($query) use (
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
                }
            )
            ->sum('amount');

        return round(
            (float) $amount,
            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | MANUAL / AUTOMATIC SALARY SLIP GENERATION ENTRY POINT
    |--------------------------------------------------------------------------
    |
    | Allows an authorized payroll manager to generate a missing Salary Slip
    | from an approved or paid Salary Processing record.
    |
    | Existing Salary Slip is returned instead of creating a duplicate.
    |
    */

    public function generateSalarySlipForProcessing(
        int $salaryProcessingId
    ): SalarySlip {

        $this->assertPayrollManager();

        return DB::transaction(function () use ($salaryProcessingId) {

            /*
            |--------------------------------------------------------------------------
            | Lock Salary Processing
            |--------------------------------------------------------------------------
            */

            $salaryProcessing = SalaryProcessing::query()
                ->whereKey($salaryProcessingId)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Validate Payroll Status
            |--------------------------------------------------------------------------
            */

            if (!in_array(
                $salaryProcessing->status,
                [
                    SalaryProcessing::STATUS_APPROVED,
                    SalaryProcessing::STATUS_PAID,
                ],
                true
            )) {

                throw new RuntimeException(
                    'Salary Slip can only be generated for approved or paid payroll.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Generate Or Retrieve Salary Slip
            |--------------------------------------------------------------------------
            */

            $salarySlip = $this->generateSalarySlip(
                $salaryProcessing
            );

            /*
            |--------------------------------------------------------------------------
            | Synchronize Already Paid Payroll
            |--------------------------------------------------------------------------
            */

            if (
                $salaryProcessing->status ===
                SalaryProcessing::STATUS_PAID
            ) {

                if (
                    $salarySlip->status ===
                    SalarySlip::STATUS_CANCELLED
                ) {

                    throw new RuntimeException(
                        'Cancelled Salary Slip cannot be marked as paid.'
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
            | Return Fresh Salary Slip
            |--------------------------------------------------------------------------
            */

            return $salarySlip->fresh([
                'driver',
                'salaryProcessing',
                'generatedBy',
                'issuedBy',
            ]);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE SALARY SLIP
    |--------------------------------------------------------------------------
    */

    protected function generateSalarySlip(
        SalaryProcessing $salaryProcessing
    ): SalarySlip {

        /*
        |--------------------------------------------------------------------------
        | Reload Payroll
        |--------------------------------------------------------------------------
        */

        $salaryProcessing->refresh();

        /*
        |--------------------------------------------------------------------------
        | Processing Number Check
        |--------------------------------------------------------------------------
        |
        | Every SalaryProcessing must have processing_no generated
        | by its model events / existing-record migration.
        |
        */

        if (empty($salaryProcessing->processing_no)) {

            throw new RuntimeException(
                'Processing Number is missing. Please verify the Salary Processing model events and database migration.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Existing Salary Slip Check
        |--------------------------------------------------------------------------
        */

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

            if (
                $existingSlip->status ===
                SalarySlip::STATUS_CANCELLED
            ) {

                throw new RuntimeException(
                    'A cancelled salary slip cannot be regenerated automatically.'
                );
            }

            return $existingSlip;
        }

        /*
        |--------------------------------------------------------------------------
        | Salary Period
        |--------------------------------------------------------------------------
        */

        [$periodStart, $periodEnd] = $this->resolveSalaryPeriod(
            (int) $salaryProcessing->salary_month,
            (int) $salaryProcessing->salary_year
        );

        /*
        |--------------------------------------------------------------------------
        | Salary Slip Number
        |--------------------------------------------------------------------------
        */

        $slipNo = sprintf(
            'SAL-%04d%02d-%08d',
            (int) $salaryProcessing->salary_year,
            (int) $salaryProcessing->salary_month,
            (int) $salaryProcessing->id
        );

        /*
        |--------------------------------------------------------------------------
        | Unique Slip Number Check
        |--------------------------------------------------------------------------
        */

        $slipNoExists = SalarySlip::withTrashed()
            ->where('slip_no', $slipNo)
            ->exists();

        if ($slipNoExists) {

            throw new RuntimeException(
                'The generated salary slip number is already in use. Please resolve the existing slip number before approval.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Salary Slip Data
        |--------------------------------------------------------------------------
        |
        | salary_processing_id is the actual foreign key.
        | processing_no is the unique business reference on the
        | parent SalaryProcessing record.
        |
        | basic_salary is copied from SalaryProcessing. It is already
        | prorated by the calendar-days-based payroll calculation.
        |
        */

        $slipData = [

            'salary_processing_id' =>
                $salaryProcessing->id,

            'driver_id' =>
                $salaryProcessing->driver_id,

            'role' =>
                SalarySlip::ROLE_DRIVER,

            'slip_no' =>
                $slipNo,

            'salary_month' =>
                $salaryProcessing->salary_month,

            'salary_year' =>
                $salaryProcessing->salary_year,

            'period_from' =>
                $periodStart->toDateString(),

            'period_to' =>
                $periodEnd->toDateString(),

            'total_working_days' =>
                $salaryProcessing->total_working_days,

            'present_days' =>
                $salaryProcessing->present_days,

            'absent_days' =>
                $salaryProcessing->absent_days,

            'paid_days' =>
                $salaryProcessing->paid_days,

            'overtime_hours' =>
                $salaryProcessing->overtime_hours,

            'total_duty_days' =>
                $salaryProcessing->total_duty_days,

            'total_km' =>
                $salaryProcessing->total_km,

            'basic_salary' =>
                $salaryProcessing->basic_salary,

            'allowance_amount' =>
                $salaryProcessing->allowance_amount,

            'overtime_amount' =>
                $salaryProcessing->overtime_amount,

            'working_sheet_base_amount' =>
                $salaryProcessing->working_sheet_base_amount,

            'extra_km_amount' =>
                $salaryProcessing->extra_km_amount,

            'expense_reimbursement_amount' =>
                $salaryProcessing->expense_reimbursement_amount,

            'working_sheet_other_amount' =>
                $salaryProcessing->working_sheet_other_amount,

            'working_sheet_total_amount' =>
                $salaryProcessing->working_sheet_total_amount,

            'bonus_amount' =>
                $salaryProcessing->bonus_amount,

            'other_earnings' =>
                $salaryProcessing->other_earnings,

            'gross_salary' =>
                $salaryProcessing->gross_salary,

            'advance_deduction' =>
                $salaryProcessing->advance_deduction,

            'loan_deduction' =>
                $salaryProcessing->loan_deduction,

            'penalty_deduction' =>
                $salaryProcessing->penalty_deduction,

            'other_deductions' =>
                $salaryProcessing->other_deductions,

            'total_deductions' =>
                $salaryProcessing->total_deductions,

            'net_salary' =>
                $salaryProcessing->net_salary,

            'payment_date' =>
                $salaryProcessing->payment_date,

            'payment_status' =>
                SalarySlip::PAYMENT_UNPAID,

            'status' =>
                SalarySlip::STATUS_GENERATED,

            'remarks' =>
                $salaryProcessing->remarks,

            'generated_by' =>
                Auth::id(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Create Salary Slip
        |--------------------------------------------------------------------------
        */

        return SalarySlip::create($slipData);
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

        /*
        |--------------------------------------------------------------------------
        | Joining Date
        |--------------------------------------------------------------------------
        */

        if (
            $driver->joining_date &&
            Carbon::parse($driver->joining_date)
                ->startOfDay()
                ->gt($periodEnd)
        ) {

            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Employment End Date
        |--------------------------------------------------------------------------
        */

        $employmentEndDate =
            $driver->last_working_date
            ?? $driver->termination_date
            ?? $driver->resignation_date;

        if (
            $employmentEndDate &&
            Carbon::parse($employmentEndDate)
                ->startOfDay()
                ->lt($periodStart)
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

        /*
        |--------------------------------------------------------------------------
        | Validate Month / Year
        |--------------------------------------------------------------------------
        */

        if (
            $month < 1 ||
            $month > 12 ||
            $year < 2000 ||
            $year > 2100 ||
            !checkdate($month, 1, $year)
        ) {

            throw new InvalidArgumentException(
                'A valid salary month and year are required.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Period Start / End
        |--------------------------------------------------------------------------
        */

        $periodStart = Carbon::create(
            $year,
            $month,
            1
        )->startOfMonth();

        $periodEnd = $periodStart
            ->copy()
            ->endOfMonth();

        return [
            $periodStart,
            $periodEnd,
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

        if (
            !$user instanceof User ||
            (
                !$user->isAdmin() &&
                !$user->isOperations() &&
                !$user->isAccountant()
            )
        ) {

            abort(403);
        }
    }
}
