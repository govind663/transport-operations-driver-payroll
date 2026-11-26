<?php

namespace App\Services\SalarySlip;

use App\Models\Driver;
use App\Models\SalaryProcessing;
use App\Models\SalarySlip;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
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
        ?int $perPage = null
    ): Collection {

        // The Salary Slip index uses a complete Collection, not Laravel pagination.
        // Keep the optional argument for compatibility with existing callers.

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

        $search = trim((string) ($filters['search'] ?? ''));

        if ($search !== '') {

            $searchLike = '%' . $search . '%';

            $query->where(function ($slipQuery) use ($searchLike) {

                $slipQuery
                    ->where('slip_no', 'like', $searchLike)
                    ->orWhereHas(
                        'driver',
                        function ($driverQuery) use ($searchLike) {

                            /*
                            |--------------------------------------------------
                            | IMPORTANT: Driver name is not a physical
                            | database column in this project. The Driver
                            | model stores first_name and last_name separately.
                            | Search the real columns to avoid SQL errors and
                            | support full-name, code and mobile searches.
                            |--------------------------------------------------
                            */

                            $driverQuery->where(function ($detailsQuery) use ($searchLike) {

                                $detailsQuery
                                    ->where('first_name', 'like', $searchLike)
                                    ->orWhere('last_name', 'like', $searchLike)
                                    ->orWhereRaw(
                                        "CONCAT_WS(' ', first_name, last_name) LIKE ?",
                                        [$searchLike]
                                    )
                                    ->orWhere('driver_code', 'like', $searchLike)
                                    ->orWhere('mobile', 'like', $searchLike);
                            });
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
            ->orderByDesc('salary_year')
            ->orderByDesc('salary_month')
            ->orderByDesc('id')
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | FIND
    |--------------------------------------------------------------------------
    */

    public function find(
        int|string $id
    ): SalarySlip {

        $query = SalarySlip::query()
            ->with([
                'driver',
                'salaryProcessing',
                'generatedBy',
                'issuedBy',
            ]);

        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403);
        }

        if ($user->isDriver()) {
            $driver = Driver::query()
                ->where('user_id', $user->id)
                ->first();

            if (!$driver) {
                abort(404);
            }

            $query->where('driver_id', $driver->id);
        }

        return $query->findOrFail($id);
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

            $user = Auth::user();

            if (!$user instanceof User || $user->isDriver()) {
                abort(403);
            }

            $userId = $user->id;

            /*
            |--------------------------------------------------------------------------
            | Normalize Request Data
            |--------------------------------------------------------------------------
            */

            $data = $this->normalizeSalaryData($data);

            /*
            |--------------------------------------------------------------------------
            | Driver Is Required
            |--------------------------------------------------------------------------
            */

            if (empty($data['driver_id'])) {
                throw new \InvalidArgumentException(
                    'A driver is required to create a salary slip.'
                );
            }

            Driver::query()
                ->whereKey((int) $data['driver_id'])
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Driver / Salary Period Duplicate Protection
            |--------------------------------------------------------------------------
            |
            | One driver must have only one Salary Slip for a salary month.
            | Include soft-deleted slips so a deleted record cannot be silently
            | replaced by a new manual slip.
            |
            */

            $this->assertNoDuplicatePeriod(
                (int) $data['driver_id'],
                $data['salary_month'] ?? null,
                $data['salary_year'] ?? null
            );

            /*
            |--------------------------------------------------------------------------
            | Existing Payroll Protection
            |--------------------------------------------------------------------------
            |
            | A standalone Salary Slip must not be created for a period that
            | already has a Salary Processing record. Payroll-linked slips
            | follow the approved / paid payroll snapshot workflow.
            |
            */

            if (
                !empty($data['salary_month']) &&
                !empty($data['salary_year'])
            ) {

                $existingPayroll = SalaryProcessing::withTrashed()
                    ->where(
                        'driver_id',
                        (int) $data['driver_id']
                    )
                    ->where(
                        'salary_month',
                        (int) $data['salary_month']
                    )
                    ->where(
                        'salary_year',
                        (int) $data['salary_year']
                    )
                    ->lockForUpdate()
                    ->first();

                if ($existingPayroll) {

                    if ($existingPayroll->trashed()) {

                        throw new \RuntimeException(
                            'A deleted payroll record already exists for this driver and period. Restore or resolve it before creating a Salary Slip.'
                        );
                    }

                    throw new \RuntimeException(
                        'Salary Processing already exists for this driver and period. Generate or recover the Salary Slip from Salary Processing instead.'
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Payroll Link Duplicate Protection
            |--------------------------------------------------------------------------
            |
            | Payroll-linked slips must be generated through
            | SalaryProcessingService. This service only allows a standalone
            | slip when no payroll record is linked. This keeps one source of
            | truth for approved payroll snapshots.
            |
            */

            if (!empty($data['salary_processing_id'])) {
                throw new \RuntimeException(
                    'Payroll-linked Salary Slips must be generated through SalaryProcessingService.'
                );
            }

            // Do not accept payroll ownership from a manual Salary Slip form.
            unset($data['salary_processing_id']);

            /*

            |--------------------------------------------------------------------------
            | Controlled System Fields
            |--------------------------------------------------------------------------
            */

            $data['role'] = SalarySlip::ROLE_DRIVER;
            $data['generated_by'] = $userId;
            $data['status'] = SalarySlip::STATUS_GENERATED;
            $data['payment_status'] = SalarySlip::PAYMENT_UNPAID;

            unset(
                $data['issued_by'],
                $data['issued_at'],
                $data['payment_date']
            );

            /*
            |--------------------------------------------------------------------------
            | Calculate Salary
            |--------------------------------------------------------------------------
            */

            $data = $this->calculateSalary($data);

            /*
            |--------------------------------------------------------------------------
            | Generate / Validate Slip Number
            |--------------------------------------------------------------------------
            */

            if (empty($data['slip_no'])) {
                $data['slip_no'] = $this->generateSlipNumber();
            }

            $slipNoExists = SalarySlip::withTrashed()
                ->where('slip_no', $data['slip_no'])
                ->exists();

            if ($slipNoExists) {
                throw new \RuntimeException(
                    'This Salary Slip number is already in use.'
                );
            }

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

            $lockedSlip = SalarySlip::query()
                ->whereKey($salarySlip->id)
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Protect Payroll-Generated Snapshot
            |--------------------------------------------------------------------------
            */

            if ($lockedSlip->salary_processing_id) {
                throw new \RuntimeException(
                    'Payroll-generated Salary Slips are snapshots and cannot be edited here. Update the payroll through its approved workflow.'
                );
            }

            if (
                $lockedSlip->isCancelled() ||
                $lockedSlip->isPaid() ||
                $lockedSlip->isPartiallyPaid() ||
                $lockedSlip->isStatusPaid()
            ) {
                throw new \RuntimeException(
                    'Cancelled or paid Salary Slips cannot be edited.'
                );
            }

            if ($lockedSlip->isIssued()) {
                throw new \RuntimeException(
                    'An issued Salary Slip cannot be edited. Cancel it and follow the approved correction process.'
                );
            }

            $data = $this->normalizeSalaryData($data);

            if (!empty($data['driver_id'])) {
                Driver::query()
                    ->whereKey((int) $data['driver_id'])
                    ->firstOrFail();
            }

            /*
            |--------------------------------------------------------------------------
            | Immutable / System-Controlled Fields
            |--------------------------------------------------------------------------
            */

            unset(
                $data['slip_no'],
                $data['salary_processing_id'],
                $data['generated_by'],
                $data['issued_by'],
                $data['issued_at'],
                $data['payment_status'],
                $data['payment_date'],
                $data['status']
            );

            /*
            |--------------------------------------------------------------------------
            | Recalculate Using Existing Values
            |--------------------------------------------------------------------------
            |
            | Merge existing database values first so a partial update does
            | not accidentally reset omitted salary components to zero.
            |
            */

            $calculationData = array_merge(
                $lockedSlip->getAttributes(),
                $data
            );

            $this->assertNoDuplicatePeriod(
                (int) ($calculationData['driver_id'] ?? 0),
                $calculationData['salary_month'] ?? null,
                $calculationData['salary_year'] ?? null,
                (int) $lockedSlip->id
            );

            $calculated = $this->calculateSalary($calculationData);

            foreach ([
                'gross_salary',
                'total_deductions',
                'net_salary',
            ] as $calculatedField) {
                $data[$calculatedField] = $calculated[$calculatedField];
            }

            $lockedSlip->update($data);

            return $lockedSlip->fresh([
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

            $lockedSlip = SalarySlip::query()
                ->whereKey($salarySlip->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedSlip->salary_processing_id) {
                throw new \RuntimeException(
                    'Payroll-generated Salary Slips cannot be deleted from the Salary Slip module.'
                );
            }

            if (
                $lockedSlip->isIssued() ||
                $lockedSlip->isPaid() ||
                $lockedSlip->isPartiallyPaid() ||
                $lockedSlip->isStatusPaid()
            ) {
                throw new \RuntimeException(
                    'Issued or paid Salary Slips cannot be deleted.'
                );
            }

            return (bool) $lockedSlip->delete();
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
            'total_duty_days',
            'total_km',

            'basic_salary',
            'working_sheet_base_amount',
            'extra_km_amount',
            'expense_reimbursement_amount',
            'working_sheet_other_amount',
            'working_sheet_total_amount',
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
    | DUPLICATE DRIVER / SALARY PERIOD VALIDATION
    |--------------------------------------------------------------------------
    |
    | A driver can have only one Salary Slip for a salary month/year.
    | Soft-deleted records are included to prevent accidental recreation.
    |
    */

    protected function assertNoDuplicatePeriod(
        int $driverId,
        mixed $salaryMonth,
        mixed $salaryYear,
        ?int $ignoreSlipId = null
    ): void {

        if (
            $driverId <= 0 ||
            $salaryMonth === null ||
            $salaryMonth === '' ||
            $salaryYear === null ||
            $salaryYear === ''
        ) {
            return;
        }

        $query = SalarySlip::withTrashed()
            ->where('driver_id', $driverId)
            ->where('salary_month', (int) $salaryMonth)
            ->where('salary_year', (int) $salaryYear);

        if ($ignoreSlipId !== null) {
            $query->where('id', '!=', $ignoreSlipId);
        }

        if ($query->exists()) {
            throw new \RuntimeException(
                'A Salary Slip already exists for this driver and salary period. Restore or resolve the existing record instead of creating a duplicate.'
            );
        }
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
        */

        $basicSalary = (float) ($data['basic_salary'] ?? 0);

        /*
        |--------------------------------------------------------------------------
        | Working Sheet Earnings
        |--------------------------------------------------------------------------
        |
        | The stored combined Working Sheet amount is authoritative.
        | For older records where it is zero / absent, use the component
        | columns. If no Working Sheet data exists, retain the legacy
        | allowance + overtime calculation.
        |
        */

        $workingSheetTotal = (float) ($data['working_sheet_total_amount'] ?? 0);

        $workingSheetComponents =
            (float) ($data['working_sheet_base_amount'] ?? 0)
            + (float) ($data['extra_km_amount'] ?? 0)
            + (float) ($data['overtime_amount'] ?? 0)
            + (float) ($data['allowance_amount'] ?? 0)
            + (float) ($data['expense_reimbursement_amount'] ?? 0)
            + (float) ($data['working_sheet_other_amount'] ?? 0);

        $hasWorkingSheetComponents =
            $workingSheetTotal > 0 ||
            $workingSheetComponents != 0.0;

        if ($workingSheetTotal > 0) {
            $workingSheetEarnings = $workingSheetTotal;
        } elseif ($hasWorkingSheetComponents) {
            $workingSheetEarnings = $workingSheetComponents;
        } else {
            $workingSheetEarnings =
                (float) ($data['allowance_amount'] ?? 0)
                + (float) ($data['overtime_amount'] ?? 0);
        }

        $bonus = (float) ($data['bonus_amount'] ?? 0);
        $otherEarnings = (float) ($data['other_earnings'] ?? 0);

        $grossSalary =
            $basicSalary
            + $workingSheetEarnings
            + $bonus
            + $otherEarnings;

        /*
        |--------------------------------------------------------------------------
        | Deductions
        |--------------------------------------------------------------------------
        */

        $advance = (float) ($data['advance_deduction'] ?? 0);
        $loan = (float) ($data['loan_deduction'] ?? 0);
        $penalty = (float) ($data['penalty_deduction'] ?? 0);
        $otherDeductions = (float) ($data['other_deductions'] ?? 0);

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

        $netSalary = max(0, $grossSalary - $totalDeductions);

        $data['gross_salary'] = round($grossSalary, 2);
        $data['total_deductions'] = round($totalDeductions, 2);
        $data['net_salary'] = round($netSalary, 2);

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

        $lastSlip = SalarySlip::withTrashed()
            ->whereYear('created_at', $year)
            ->latest('id')
            ->first();

        $nextNumber = $lastSlip
            ? ((int) $lastSlip->id + 1)
            : 1;

        do {
            $slipNo = sprintf(
                'SAL-%s-%05d',
                $year,
                $nextNumber
            );

            $exists = SalarySlip::withTrashed()
                ->where('slip_no', $slipNo)
                ->exists();

            $nextNumber++;
        } while ($exists);

        return $slipNo;
    }


    /*
    |--------------------------------------------------------------------------
    | ISSUE SLIP
    |--------------------------------------------------------------------------
    */

    public function issue(
        SalarySlip $salarySlip
    ): SalarySlip {

        return DB::transaction(function () use ($salarySlip) {

            $userId = Auth::id();

            if (!$userId) {
                throw new \RuntimeException(
                    'Unauthenticated access. Please login before issuing a salary slip.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Read Parent Reference Before Locking
            |--------------------------------------------------------------------------
            */

            $slipReference = SalarySlip::query()
                ->select(['id', 'salary_processing_id'])
                ->findOrFail($salarySlip->getKey());

            /*
            |--------------------------------------------------------------------------
            | Lock Parent Payroll First
            |--------------------------------------------------------------------------
            */

            if ($slipReference->salary_processing_id) {

                $salaryProcessing = SalaryProcessing::query()
                    ->whereKey($slipReference->salary_processing_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (!in_array(
                    $salaryProcessing->status,
                    [
                        SalaryProcessing::STATUS_APPROVED,
                        SalaryProcessing::STATUS_PAID,
                    ],
                    true
                )) {
                    throw new \RuntimeException(
                        'Only an approved or paid payroll salary slip can be issued.'
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Lock Salary Slip
            |--------------------------------------------------------------------------
            */

            $salarySlip = SalarySlip::query()
                ->whereKey($salarySlip->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($salarySlip->isCancelled()) {
                throw new \RuntimeException(
                    'Cancelled salary slip cannot be issued.'
                );
            }

            if ($salarySlip->isPaid() || $salarySlip->isStatusPaid()) {
                throw new \RuntimeException(
                    'Paid salary slip cannot be issued again.'
                );
            }

            // Idempotent issue: preserve the original issuer and issue timestamp.
            if ($salarySlip->isIssued()) {
                return $salarySlip->fresh([
                    'driver',
                    'salaryProcessing',
                    'generatedBy',
                    'issuedBy',
                ]);
            }

            if (!$salarySlip->isGenerated()) {
                throw new \RuntimeException(
                    'Only a generated salary slip can be issued.'
                );
            }

            $salarySlip->status = SalarySlip::STATUS_ISSUED;
            $salarySlip->issued_by = $userId;
            $salarySlip->issued_at = now();
            $salarySlip->save();

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
    | MARK AS PAID
    |--------------------------------------------------------------------------
    */

    public function markAsPaid(
        SalarySlip $salarySlip,
        ?string $paymentDate = null
    ): SalarySlip {

        return DB::transaction(function () use (
            $salarySlip,
            $paymentDate
        ) {

            $userId = Auth::id();

            if (!$userId) {
                throw new \RuntimeException(
                    'Unauthenticated access. Please login before recording payment.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Read Parent Reference
            |--------------------------------------------------------------------------
            */

            $slipReference = SalarySlip::query()
                ->select(['id', 'salary_processing_id'])
                ->findOrFail($salarySlip->getKey());

            /*
            |--------------------------------------------------------------------------
            | Lock Parent Payroll First
            |--------------------------------------------------------------------------
            */

            $salaryProcessing = null;

            if ($slipReference->salary_processing_id) {

                $salaryProcessing = SalaryProcessing::query()
                    ->whereKey($slipReference->salary_processing_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (!in_array(
                    $salaryProcessing->status,
                    [
                        SalaryProcessing::STATUS_APPROVED,
                        SalaryProcessing::STATUS_PAID,
                    ],
                    true
                )) {
                    throw new \RuntimeException(
                        'Only an approved payroll can be marked as paid.'
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Lock Salary Slip
            |--------------------------------------------------------------------------
            */

            $salarySlip = SalarySlip::query()
                ->whereKey($salarySlip->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($salarySlip->isCancelled()) {
                throw new \RuntimeException(
                    'Cancelled salary slip cannot be marked as paid.'
                );
            }

            if ($salarySlip->isStatusPaid()) {
                throw new \RuntimeException(
                    'This salary slip uses the legacy paid status. Please verify it before changing its payment state.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Resolve Payment Date
            |--------------------------------------------------------------------------
            */

            if ($paymentDate !== null) {
                $effectivePaymentDate = $paymentDate;
            } elseif (
                $salarySlip->isPaid() &&
                $salarySlip->payment_date
            ) {
                $effectivePaymentDate = $salarySlip->payment_date->toDateString();
            } elseif (
                $salaryProcessing &&
                $salaryProcessing->status === SalaryProcessing::STATUS_PAID &&
                $salaryProcessing->payment_date
            ) {
                $effectivePaymentDate = $salaryProcessing->payment_date->toDateString();
            } else {
                $effectivePaymentDate = now()->toDateString();
            }

            /*
            |--------------------------------------------------------------------------
            | Synchronize Parent Payroll
            |--------------------------------------------------------------------------
            */

            if ($salaryProcessing) {

                if (
                    $salaryProcessing->status !==
                    SalaryProcessing::STATUS_PAID
                ) {
                    $salaryProcessing->payment_date = $effectivePaymentDate;
                    $salaryProcessing->markAsPaid($userId);
                } elseif (
                    !$salaryProcessing->payment_date ||
                    $paymentDate !== null
                ) {
                    // Recovery: keep a paid payroll's payment date in sync
                    // with the linked Salary Slip when the date is missing
                    // or an explicit correction date was supplied.
                    $salaryProcessing->payment_date = $effectivePaymentDate;
                }

                $salaryProcessing->updated_by = $userId;
                $salaryProcessing->save();
            }

            /*
            |--------------------------------------------------------------------------
            | Synchronize Salary Slip
            |--------------------------------------------------------------------------
            */

            $salarySlip->payment_status = SalarySlip::PAYMENT_PAID;
            $salarySlip->payment_date = $effectivePaymentDate;
            $salarySlip->save();

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
    | MARK AS PARTIALLY PAID
    |--------------------------------------------------------------------------
    */

    public function markAsPartiallyPaid(
        SalarySlip $salarySlip,
        ?string $paymentDate = null
    ): SalarySlip {

        return DB::transaction(function () use (
            $salarySlip,
            $paymentDate
        ) {

            $userId = Auth::id();

            if (!$userId) {
                throw new \RuntimeException(
                    'Unauthenticated access. Please login before recording payment.'
                );
            }

            $slipReference = SalarySlip::query()
                ->select(['id', 'salary_processing_id'])
                ->findOrFail($salarySlip->getKey());

            /*
            |--------------------------------------------------------------------------
            | Lock Parent Payroll First
            |--------------------------------------------------------------------------
            */

            if ($slipReference->salary_processing_id) {

                $salaryProcessing = SalaryProcessing::query()
                    ->whereKey($slipReference->salary_processing_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (
                    $salaryProcessing->status !==
                    SalaryProcessing::STATUS_APPROVED
                ) {
                    throw new \RuntimeException(
                        'Partial payment is allowed only while the linked payroll is approved and not fully paid.'
                    );
                }
            }

            $salarySlip = SalarySlip::query()
                ->whereKey($salarySlip->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($salarySlip->isCancelled()) {
                throw new \RuntimeException(
                    'Cancelled salary slip cannot receive a partial payment.'
                );
            }

            if ($salarySlip->isPaid() || $salarySlip->isStatusPaid()) {
                throw new \RuntimeException(
                    'Fully paid salary slip cannot be marked as partially paid.'
                );
            }

            $effectivePaymentDate = $paymentDate
                ?? (
                    $salarySlip->isPartiallyPaid() && $salarySlip->payment_date
                        ? $salarySlip->payment_date->toDateString()
                        : now()->toDateString()
                );

            $salarySlip->payment_status = SalarySlip::PAYMENT_PARTIAL;
            $salarySlip->payment_date = $effectivePaymentDate;
            $salarySlip->save();

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
    | CANCEL
    |--------------------------------------------------------------------------
    */

    public function cancel(
        SalarySlip $salarySlip
    ): SalarySlip {

        return DB::transaction(function () use ($salarySlip) {

            $userId = Auth::id();

            if (!$userId) {
                throw new \RuntimeException(
                    'Unauthenticated access. Please login before cancelling a salary slip.'
                );
            }

            $slipReference = SalarySlip::query()
                ->select(['id', 'salary_processing_id'])
                ->findOrFail($salarySlip->getKey());

            /*
            |--------------------------------------------------------------------------
            | Payroll-Generated Slip Protection
            |--------------------------------------------------------------------------
            | Payroll-generated slips must be cancelled through
            | SalaryProcessingService so both records remain synchronized.
            */

            if ($slipReference->salary_processing_id) {
                throw new \RuntimeException(
                    'This salary slip belongs to a payroll record. Cancel it through SalaryProcessingService so payroll and salary slip remain synchronized.'
                );
            }

            $salarySlip = SalarySlip::query()
                ->whereKey($salarySlip->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($salarySlip->isCancelled()) {
                return $salarySlip->fresh([
                    'driver',
                    'salaryProcessing',
                    'generatedBy',
                    'issuedBy',
                ]);
            }

            if (
                $salarySlip->isPaid() ||
                $salarySlip->isPartiallyPaid() ||
                $salarySlip->isStatusPaid()
            ) {
                throw new \RuntimeException(
                    'Paid or partially paid salary slip cannot be cancelled.'
                );
            }

            $salarySlip->status = SalarySlip::STATUS_CANCELLED;
            $salarySlip->save();

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
    | RESTORE
    |--------------------------------------------------------------------------
    */

    public function restore(
        int|string $id
    ): SalarySlip {

        return DB::transaction(function () use ($id) {

            $userId = Auth::id();

            if (!$userId) {
                throw new \RuntimeException(
                    'Unauthenticated access. Please login before restoring a salary slip.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Read Parent Reference
            |--------------------------------------------------------------------------
            */

            $slipReference = SalarySlip::withTrashed()
                ->select(['id', 'salary_processing_id'])
                ->findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | Lock Parent Payroll First
            |--------------------------------------------------------------------------
            */

            if ($slipReference->salary_processing_id) {

                $salaryProcessing = SalaryProcessing::query()
                    ->whereKey($slipReference->salary_processing_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (!in_array(
                    $salaryProcessing->status,
                    [
                        SalaryProcessing::STATUS_APPROVED,
                        SalaryProcessing::STATUS_PAID,
                    ],
                    true
                )) {
                    throw new \RuntimeException(
                        'A payroll-linked Salary Slip can only be restored when its payroll is approved or paid.'
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Lock Salary Slip Including Soft-Deleted Record
            |--------------------------------------------------------------------------
            */

            $salarySlip = SalarySlip::withTrashed()
                ->whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$salarySlip->trashed()) {
                return $salarySlip->fresh([
                    'driver',
                    'salaryProcessing',
                    'generatedBy',
                    'issuedBy',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Duplicate Payroll Link Protection
            |--------------------------------------------------------------------------
            */

            if ($salarySlip->salary_processing_id) {

                $duplicatePayrollSlip = SalarySlip::withTrashed()
                    ->where(
                        'salary_processing_id',
                        $salarySlip->salary_processing_id
                    )
                    ->where('id', '!=', $salarySlip->id)
                    ->exists();

                if ($duplicatePayrollSlip) {
                    throw new \RuntimeException(
                        'Another salary slip already exists for this payroll. The deleted slip cannot be restored.'
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Duplicate Driver / Salary Period Protection
            |--------------------------------------------------------------------------
            */

            $duplicatePeriodSlip = SalarySlip::withTrashed()
                ->where('driver_id', $salarySlip->driver_id)
                ->where('salary_month', $salarySlip->salary_month)
                ->where('salary_year', $salarySlip->salary_year)
                ->where('id', '!=', $salarySlip->id)
                ->exists();

            if ($duplicatePeriodSlip) {
                throw new \RuntimeException(
                    'Another salary slip already exists for this driver and salary period. Resolve the duplicate before restoring.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Duplicate Slip Number Protection
            |--------------------------------------------------------------------------
            */

            $duplicateSlipNumber = SalarySlip::withTrashed()
                ->where('slip_no', $salarySlip->slip_no)
                ->where('id', '!=', $salarySlip->id)
                ->exists();

            if ($duplicateSlipNumber) {
                throw new \RuntimeException(
                    'The salary slip number is already assigned to another record. The deleted slip cannot be restored.'
                );
            }

            $salarySlip->restore();

            return $salarySlip->fresh([
                'driver',
                'salaryProcessing',
                'generatedBy',
                'issuedBy',
            ]);
        });
    }


}
