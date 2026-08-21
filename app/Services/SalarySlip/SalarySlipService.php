<?php

namespace App\Services\SalarySlip;

use App\Models\Driver;
use App\Models\SalarySlip;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalarySlipService
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

        $query = SalarySlip::query()
            ->with([
                'driver',
                'salaryProcessing',
                'generatedBy',
                'issuedBy',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Current User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | DRIVER RESTRICTION
        |--------------------------------------------------------------------------
        |
        | Driver can only see his own salary slips.
        |
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
        | DRIVER FILTER
        |--------------------------------------------------------------------------
        */

        if (
            !$user->isDriver() &&
            !empty($filters['driver_id'])
        ) {

            $query->where(
                'driver_id',
                (int) $filters['driver_id']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SALARY MONTH
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['salary_month'])) {

            $query->where(
                'salary_month',
                (int) $filters['salary_month']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SALARY YEAR
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['salary_year'])) {

            $query->where(
                'salary_year',
                (int) $filters['salary_year']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
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
        | PAYMENT STATUS
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['payment_status'])) {

            $query->where(
                'payment_status',
                $filters['payment_status']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENT DATE
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
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {

            $search = trim($filters['search']);

            $query->where(function ($query) use ($search) {

                $query
                    ->where(
                        'slip_no',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhereHas(
                        'driver',
                        function ($driverQuery) use ($search) {

                            $driverQuery
                                ->where(
                                    'name',
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
        | RESULT
        |--------------------------------------------------------------------------
        */

        return $query
            ->latest('period_from')
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();
    }


    /*
    |--------------------------------------------------------------------------
    | FIND
    |--------------------------------------------------------------------------
    */

    public function find(
        int|string $id
    ): SalarySlip {

        return SalarySlip::query()
            ->with([
                'driver',
                'salaryProcessing',
                'generatedBy',
                'issuedBy',
            ])
            ->findOrFail($id);
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        array $data
    ): SalarySlip {

        return DB::transaction(function () use ($data) {

            $userId = Auth::id();

            /*
            |--------------------------------------------------------------------------
            | Normalize Request Data
            |--------------------------------------------------------------------------
            */

            $data = $this->normalizeSalaryData($data);

            /*
            |--------------------------------------------------------------------------
            | Role
            |--------------------------------------------------------------------------
            */

            $data['role'] =
                $data['role']
                ?? SalarySlip::ROLE_DRIVER;

            /*
            |--------------------------------------------------------------------------
            | Generated By
            |--------------------------------------------------------------------------
            */

            $data['generated_by'] =
                $data['generated_by']
                ?? $userId;

            /*
            |--------------------------------------------------------------------------
            | Default Status
            |--------------------------------------------------------------------------
            */

            $data['status'] =
                $data['status']
                ?? SalarySlip::STATUS_GENERATED;

            /*
            |--------------------------------------------------------------------------
            | Default Payment Status
            |--------------------------------------------------------------------------
            */

            $data['payment_status'] =
                $data['payment_status']
                ?? SalarySlip::PAYMENT_UNPAID;

            /*
            |--------------------------------------------------------------------------
            | Calculate Salary
            |--------------------------------------------------------------------------
            */

            $data = $this->calculateSalary($data);

            /*
            |--------------------------------------------------------------------------
            | Slip Number
            |--------------------------------------------------------------------------
            */

            if (empty($data['slip_no'])) {

                $data['slip_no'] =
                    $this->generateSlipNumber();
            }

            /*
            |--------------------------------------------------------------------------
            | Create Salary Slip
            |--------------------------------------------------------------------------
            */

            return SalarySlip::create($data);
        });
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        SalarySlip $salarySlip,
        array $data
    ): SalarySlip {

        return DB::transaction(function () use (
            $salarySlip,
            $data
        ) {

            /*
            |--------------------------------------------------------------------------
            | Normalize Request Data
            |--------------------------------------------------------------------------
            */

            $data = $this->normalizeSalaryData($data);

            /*
            |--------------------------------------------------------------------------
            | Slip Number Must Not Change
            |--------------------------------------------------------------------------
            */

            unset($data['slip_no']);

            /*
            |--------------------------------------------------------------------------
            | Calculate Salary
            |--------------------------------------------------------------------------
            */

            $data = $this->calculateSalary($data);

            /*
            |--------------------------------------------------------------------------
            | Update
            |--------------------------------------------------------------------------
            */

            $salarySlip->update($data);

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
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(
        SalarySlip $salarySlip
    ): bool {

        return DB::transaction(function () use ($salarySlip) {

            return $salarySlip->delete();
        });
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZE SALARY DATA
    |--------------------------------------------------------------------------
    |
    | Converts request field names into actual database field names.
    |
    */

    protected function normalizeSalaryData(
        array $data
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Salary Month: YYYY-MM -> salary_month + salary_year
        |--------------------------------------------------------------------------
        */

        if (
            !empty($data['salary_month']) &&
            is_string($data['salary_month']) &&
            preg_match(
                '/^(\d{4})-(\d{2})$/',
                $data['salary_month'],
                $matches
            )
        ) {

            $data['salary_year'] =
                (int) $matches[1];

            $data['salary_month'] =
                (int) $matches[2];
        }

        /*
        |--------------------------------------------------------------------------
        | Working Days
        |--------------------------------------------------------------------------
        |
        | Request:
        | working_days
        |
        | Database:
        | total_working_days
        |
        */

        if (
            array_key_exists(
                'working_days',
                $data
            )
        ) {

            $data['total_working_days'] =
                $data['working_days'];

            unset($data['working_days']);
        }

        /*
        |--------------------------------------------------------------------------
        | Late + Absent Deduction
        |--------------------------------------------------------------------------
        |
        | Database does not have:
        | late_deduction
        | absent_deduction
        |
        | Both are combined into penalty_deduction.
        |
        */

        $lateDeduction =
            (float) ($data['late_deduction'] ?? 0);

        $absentDeduction =
            (float) ($data['absent_deduction'] ?? 0);

        if (
            array_key_exists(
                'late_deduction',
                $data
            ) ||
            array_key_exists(
                'absent_deduction',
                $data
            )
        ) {

            $data['penalty_deduction'] =
                $lateDeduction +
                $absentDeduction;
        }

        unset(
            $data['late_deduction'],
            $data['absent_deduction']
        );

        /*
        |--------------------------------------------------------------------------
        | Payment Reference
        |--------------------------------------------------------------------------
        |
        | payment_reference does not exist in salary_slips table.
        |
        */

        unset($data['payment_reference']);

        /*
        |--------------------------------------------------------------------------
        | Remove Non-Database Fields
        |--------------------------------------------------------------------------
        */

        unset($data['role']);

        /*
        |--------------------------------------------------------------------------
        | Numeric Normalization
        |--------------------------------------------------------------------------
        */

        $numericFields = [

            'salary_month',
            'salary_year',

            'total_working_days',
            'present_days',
            'absent_days',
            'paid_days',
            'overtime_hours',

            'basic_salary',
            'allowance_amount',
            'overtime_amount',
            'bonus_amount',
            'other_earnings',

            'advance_deduction',
            'loan_deduction',
            'penalty_deduction',
            'other_deductions',

            'gross_salary',
            'total_deductions',
            'net_salary',
        ];

        foreach ($numericFields as $field) {

            if (
                array_key_exists(
                    $field,
                    $data
                ) &&
                $data[$field] !== null &&
                $data[$field] !== ''
            ) {

                $data[$field] =
                    (float) $data[$field];
            }
        }

        return $data;
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

        $basicSalary =
            (float) ($data['basic_salary'] ?? 0);

        $allowance =
            (float) ($data['allowance_amount'] ?? 0);

        $overtime =
            (float) ($data['overtime_amount'] ?? 0);

        $bonus =
            (float) ($data['bonus_amount'] ?? 0);

        $otherEarnings =
            (float) ($data['other_earnings'] ?? 0);


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

        $advance =
            (float) ($data['advance_deduction'] ?? 0);

        $loan =
            (float) ($data['loan_deduction'] ?? 0);

        $penalty =
            (float) ($data['penalty_deduction'] ?? 0);

        $otherDeductions =
            (float) ($data['other_deductions'] ?? 0);


        /*
        |--------------------------------------------------------------------------
        | Total Deductions
        |--------------------------------------------------------------------------
        */

        $totalDeductions =
            $advance
            + $loan
            + $penalty
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

        return $data;
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE SLIP NUMBER
    |--------------------------------------------------------------------------
    */

    public function generateSlipNumber(): string
    {
        $year = now()->format('Y');

        $lastSlip = SalarySlip::query()
            ->whereYear(
                'created_at',
                $year
            )
            ->latest('id')
            ->first();

        $nextNumber = $lastSlip
            ? $lastSlip->id + 1
            : 1;

        return sprintf(
            'SAL-%s-%05d',
            $year,
            $nextNumber
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ISSUE SLIP
    |--------------------------------------------------------------------------
    */

    public function issue(
        SalarySlip $salarySlip
    ): SalarySlip {

        $salarySlip->update([

            'status' =>
                SalarySlip::STATUS_ISSUED,

            'issued_by' =>
                Auth::id(),

            'issued_at' =>
                now(),

        ]);

        return $salarySlip->fresh([
            'driver',
            'salaryProcessing',
            'generatedBy',
            'issuedBy',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | MARK AS PAID
    |--------------------------------------------------------------------------
    */

    public function markAsPaid(
        SalarySlip $salarySlip,
        ?string $paymentDate = null
    ): SalarySlip {

        $salarySlip->update([

            'payment_status' =>
                SalarySlip::PAYMENT_PAID,

            'payment_date' =>
                $paymentDate
                ?? now()->toDateString(),

        ]);

        return $salarySlip->fresh([
            'driver',
            'salaryProcessing',
            'generatedBy',
            'issuedBy',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | MARK AS PARTIALLY PAID
    |--------------------------------------------------------------------------
    */

    public function markAsPartiallyPaid(
        SalarySlip $salarySlip,
        ?string $paymentDate = null
    ): SalarySlip {

        $salarySlip->update([

            'payment_status' =>
                SalarySlip::PAYMENT_PARTIAL,

            'payment_date' =>
                $paymentDate
                ?? now()->toDateString(),

        ]);

        return $salarySlip->fresh([
            'driver',
            'salaryProcessing',
            'generatedBy',
            'issuedBy',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CANCEL
    |--------------------------------------------------------------------------
    */

    public function cancel(
        SalarySlip $salarySlip
    ): SalarySlip {

        $salarySlip->update([

            'status' =>
                SalarySlip::STATUS_CANCELLED,

        ]);

        return $salarySlip->fresh([
            'driver',
            'salaryProcessing',
            'generatedBy',
            'issuedBy',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | RESTORE
    |--------------------------------------------------------------------------
    */

    public function restore(
        int|string $id
    ): SalarySlip {

        $salarySlip = SalarySlip::withTrashed()
            ->findOrFail($id);

        $salarySlip->restore();

        return $salarySlip->fresh([
            'driver',
            'salaryProcessing',
            'generatedBy',
            'issuedBy',
        ]);
    }
}