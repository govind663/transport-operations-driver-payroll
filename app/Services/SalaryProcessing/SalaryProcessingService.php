<?php

namespace App\Services\SalaryProcessing;

use App\Models\SalaryProcessing;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalaryProcessingService
{
    /*
    |--------------------------------------------------------------------------
    | INDEX / LIST
    |--------------------------------------------------------------------------
    */

    public function getPaginated(
        array $filters = [],
        int $perPage = 20
    ): LengthAwarePaginator {

        $query = SalaryProcessing::query()
            ->with([
                'driver',
                'processedBy',
                'approvedBy',
                'createdBy',
                'updatedBy',
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

            $data['status'] =
                $data['status']
                ?? SalaryProcessing::STATUS_DRAFT;

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

            /*
            |--------------------------------------------------------------------------
            | Updated By
            |--------------------------------------------------------------------------
            */

            $data['updated_by'] =
                Auth::id();

            /*
            |--------------------------------------------------------------------------
            | Role
            |--------------------------------------------------------------------------
            */

            $data['role'] =
                $data['role']
                ?? $salaryProcessing->role
                ?? SalaryProcessing::ROLE_DRIVER;

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
            | Update
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->update(
                $data
            );

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
        | Earnings
        |--------------------------------------------------------------------------
        */

        $basicSalary = (float) (
            $data['basic_salary'] ?? 0
        );

        $allowance = (float) (
            $data['allowance_amount'] ?? 0
        );

        $overtime = (float) (
            $data['overtime_amount'] ?? 0
        );

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
            + $allowance
            + $overtime
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

            /*
            |--------------------------------------------------------------------------
            | Mark Approved
            |--------------------------------------------------------------------------
            */

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
            | Return Fresh Record
            |--------------------------------------------------------------------------
            */

            return $salaryProcessing->fresh([
                'driver',
                'processedBy',
                'approvedBy',
                'createdBy',
                'updatedBy',
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

            /*
            |--------------------------------------------------------------------------
            | Mark Paid
            |--------------------------------------------------------------------------
            */

            $salaryProcessing->markAsPaid();

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
            | Return Fresh Record
            |--------------------------------------------------------------------------
            */

            return $salaryProcessing->fresh([
                'driver',
                'processedBy',
                'approvedBy',
                'createdBy',
                'updatedBy',
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
            | Return Fresh Record
            |--------------------------------------------------------------------------
            */

            return $salaryProcessing->fresh([
                'driver',
                'processedBy',
                'approvedBy',
                'createdBy',
                'updatedBy',
            ]);
        });
    }
}