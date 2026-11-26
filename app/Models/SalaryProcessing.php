<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SalaryProcessing extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'salary_processings';


    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        // Driver / Role
        'driver_id',
        'role',

        // Salary Period
        'salary_month',
        'salary_year',

        // Attendance / Working
        'total_working_days',
        'present_days',
        'absent_days',
        'paid_days',
        'overtime_hours',
        'total_duty_days',
        'total_km',

        // Earnings
        'basic_salary',
        'allowance_amount',
        'overtime_amount',
        'working_sheet_base_amount',
        'extra_km_amount',
        'expense_reimbursement_amount',
        'working_sheet_other_amount',
        'working_sheet_total_amount',
        'bonus_amount',
        'other_earnings',
        'gross_salary',

        // Deductions
        'advance_deduction',
        'loan_deduction',
        'penalty_deduction',
        'other_deductions',
        'total_deductions',

        // Net Salary
        'net_salary',

        // Status
        'status',

        // Payment
        'payment_date',

        // Remarks
        'remarks',

        // Audit
        'processed_by',
        'approved_by',
        'paid_by',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [

            // IDs
            'id' => 'integer',
            'driver_id' => 'integer',
            'processed_by' => 'integer',
            'approved_by' => 'integer',
            'paid_by' => 'integer',
            'created_by' => 'integer',
            'updated_by' => 'integer',
            'deleted_by' => 'integer',

            // Processing Number
            'processing_no' => 'string',

            // Role / Status
            'role' => 'string',
            'status' => 'string',

            // Salary Period
            'salary_month' => 'integer',
            'salary_year' => 'integer',

            // Attendance / Working
            'total_working_days' => 'decimal:2',
            'present_days' => 'decimal:2',
            'absent_days' => 'decimal:2',
            'paid_days' => 'decimal:2',
            'overtime_hours' => 'decimal:2',
            'total_duty_days' => 'decimal:2',
            'total_km' => 'decimal:2',

            // Earnings
            'basic_salary' => 'decimal:2',
            'allowance_amount' => 'decimal:2',
            'overtime_amount' => 'decimal:2',
            'working_sheet_base_amount' => 'decimal:2',
            'extra_km_amount' => 'decimal:2',
            'expense_reimbursement_amount' => 'decimal:2',
            'working_sheet_other_amount' => 'decimal:2',
            'working_sheet_total_amount' => 'decimal:2',
            'bonus_amount' => 'decimal:2',
            'other_earnings' => 'decimal:2',
            'gross_salary' => 'decimal:2',

            // Deductions
            'advance_deduction' => 'decimal:2',
            'loan_deduction' => 'decimal:2',
            'penalty_deduction' => 'decimal:2',
            'other_deductions' => 'decimal:2',
            'total_deductions' => 'decimal:2',

            // Net Salary
            'net_salary' => 'decimal:2',

            // Payment
            'payment_date' => 'date:Y-m-d',

            // Timestamps
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | MODEL BOOTED
    |--------------------------------------------------------------------------
    |
    | Automatically generate Processing Number.
    |
    | Step 1:
    | Before insert, assign a temporary unique number because the
    | database processing_no column is NOT NULL.
    |
    | Step 2:
    | After insert, the auto-increment ID is available.
    | Generate the final processing number using that ID.
    |
    */

    protected static function booted(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Creating
        |--------------------------------------------------------------------------
        */

        static::creating(function (self $salaryProcessing): void {

            $salaryProcessing->setAttribute(
                'processing_no',
                'TMP-' . Str::uuid()->toString()
            );

        });


        /*
        |--------------------------------------------------------------------------
        | Created
        |--------------------------------------------------------------------------
        */

        static::created(function (self $salaryProcessing): void {

            $salaryProcessing->forceFill([
                'processing_no' => $salaryProcessing
                    ->generateProcessingNumber(),
            ])->saveQuietly();

        });
    }


    /*
    |--------------------------------------------------------------------------
    | ROLE CONSTANTS
    |--------------------------------------------------------------------------
    */

    public const ROLE_DRIVER = 'driver';


    /*
    |--------------------------------------------------------------------------
    | STATUS CONSTANTS
    |--------------------------------------------------------------------------
    */

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PROCESSED = 'processed';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_PAID = 'paid';

    public const STATUS_CANCELLED = 'cancelled';


    /*
    |--------------------------------------------------------------------------
    | STATUS LIST
    |--------------------------------------------------------------------------
    */

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_PROCESSED,
        self::STATUS_APPROVED,
        self::STATUS_PAID,
        self::STATUS_CANCELLED,
    ];


    /*
    |--------------------------------------------------------------------------
    | PROCESSING NUMBER GENERATOR
    |--------------------------------------------------------------------------
    |
    | Format:
    | SAL-YYYYMM-000001
    |
    | Example:
    | SAL-202611-000123
    |
    */

    public function generateProcessingNumber(): string
    {
        if (!$this->exists || !$this->id) {

            throw new \RuntimeException(
                'Processing ID is required to generate Processing Number.'
            );
        }

        if (
            !$this->salary_year ||
            !$this->salary_month ||
            $this->salary_month < 1 ||
            $this->salary_month > 12
        ) {

            throw new \RuntimeException(
                'Valid salary month and year are required to generate Processing Number.'
            );
        }

        return sprintf(
            'SAL-%04d%02d-%06d',
            (int) $this->salary_year,
            (int) $this->salary_month,
            (int) $this->id
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORMATTED PROCESSING NUMBER
    |--------------------------------------------------------------------------
    */

    public function getFormattedProcessingNoAttribute(): string
    {
        return $this->processing_no
            ?? (
                $this->exists
                    ? $this->generateProcessingNumber()
                    : '-'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function driver(): BelongsTo
    {
        return $this->belongsTo(
            Driver::class,
            'driver_id'
        );
    }


    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'processed_by'
        );
    }


    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }


    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'paid_by'
        );
    }


    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }


    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'deleted_by'
        );
    }


    public function salarySlips(): HasMany
    {
        return $this->hasMany(
            SalarySlip::class,
            'salary_processing_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeDraft($query)
    {
        return $query->where(
            'status',
            self::STATUS_DRAFT
        );
    }


    public function scopeProcessed($query)
    {
        return $query->where(
            'status',
            self::STATUS_PROCESSED
        );
    }


    public function scopeApproved($query)
    {
        return $query->where(
            'status',
            self::STATUS_APPROVED
        );
    }


    public function scopePaid($query)
    {
        return $query->where(
            'status',
            self::STATUS_PAID
        );
    }


    public function scopeCancelled($query)
    {
        return $query->where(
            'status',
            self::STATUS_CANCELLED
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DRIVER SCOPE
    |--------------------------------------------------------------------------
    */

    public function scopeForDriver($query, int $driverId)
    {
        return $query->where(
            'driver_id',
            $driverId
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SALARY PERIOD SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeForPeriod(
        $query,
        int $month,
        int $year
    ) {
        return $query
            ->where('salary_month', $month)
            ->where('salary_year', $year);
    }


    public function scopeForYear($query, int $year)
    {
        return $query->where(
            'salary_year',
            $year
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LATEST FIRST
    |--------------------------------------------------------------------------
    */

    public function scopeLatestFirst($query)
    {
        return $query
            ->latest('salary_year')
            ->latest('salary_month')
            ->latest('id');
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS HELPERS
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }


    public function isProcessed(): bool
    {
        return $this->status === self::STATUS_PROCESSED;
    }


    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }


    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }


    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }


    /*
    |--------------------------------------------------------------------------
    | WORKING SHEET CALCULATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Calculate Working Sheet total from its breakdown.
     */

    public function calculateWorkingSheetTotalAmount(): float
    {
        return round(
            (float) ($this->working_sheet_base_amount ?? 0)
            + (float) ($this->extra_km_amount ?? 0)
            + (float) ($this->allowance_amount ?? 0)
            + (float) ($this->overtime_amount ?? 0)
            + (float) ($this->expense_reimbursement_amount ?? 0)
            + (float) ($this->working_sheet_other_amount ?? 0),
            2
        );
    }


    /**
     * Return saved Working Sheet total when available.
     * Otherwise calculate it from breakdown fields.
     */

    public function getCalculatedWorkingSheetTotalAmountAttribute(): float
    {
        $savedTotal = (float) (
            $this->working_sheet_total_amount ?? 0
        );

        return $savedTotal > 0
            ? round($savedTotal, 2)
            : $this->calculateWorkingSheetTotalAmount();
    }


    /*
    |--------------------------------------------------------------------------
    | SALARY CALCULATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Calculate Gross Salary.
     *
     * Working Sheet total is included only once.
     */

    public function calculateGrossSalary(): float
    {
        return round(
            (float) ($this->basic_salary ?? 0)
            + $this->calculated_working_sheet_total_amount
            + (float) ($this->bonus_amount ?? 0)
            + (float) ($this->other_earnings ?? 0),
            2
        );
    }


    /**
     * Calculate Total Deductions.
     */

    public function calculateTotalDeductions(): float
    {
        return round(
            (float) ($this->advance_deduction ?? 0)
            + (float) ($this->loan_deduction ?? 0)
            + (float) ($this->penalty_deduction ?? 0)
            + (float) ($this->other_deductions ?? 0),
            2
        );
    }


    /**
     * Calculate Net Salary.
     */

    public function calculateNetSalary(): float
    {
        return round(
            max(
                0,
                $this->calculateGrossSalary()
                - $this->calculateTotalDeductions()
            ),
            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CALCULATED SALARY ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getCalculatedGrossSalaryAttribute(): float
    {
        return $this->calculateGrossSalary();
    }


    public function getCalculatedTotalDeductionsAttribute(): float
    {
        return $this->calculateTotalDeductions();
    }


    public function getCalculatedNetSalaryAttribute(): float
    {
        return $this->calculateNetSalary();
    }


    /*
    |--------------------------------------------------------------------------
    | SALARY PERIOD ACCESSOR
    |--------------------------------------------------------------------------
    */

    public function getSalaryPeriodAttribute(): string
    {
        if (
            empty($this->salary_month) ||
            empty($this->salary_year)
        ) {
            return '-';
        }

        return date(
            'F Y',
            mktime(
                0,
                0,
                0,
                $this->salary_month,
                1,
                $this->salary_year
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORMATTED SALARY ATTRIBUTES
    |--------------------------------------------------------------------------
    */

    public function getFormattedBasicSalaryAttribute(): string
    {
        return '₹' . number_format(
            (float) ($this->basic_salary ?? 0),
            2
        );
    }


    public function getFormattedGrossSalaryAttribute(): string
    {
        return '₹' . number_format(
            (float) ($this->gross_salary ?? 0),
            2
        );
    }


    public function getFormattedTotalDeductionsAttribute(): string
    {
        return '₹' . number_format(
            (float) ($this->total_deductions ?? 0),
            2
        );
    }


    public function getFormattedNetSalaryAttribute(): string
    {
        return '₹' . number_format(
            (float) ($this->net_salary ?? 0),
            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SALARY PROCESSING HELPER
    |--------------------------------------------------------------------------
    */

    /**
     * Fill calculated salary values.
     * Does not save automatically.
     */

    public function calculateSalary(): self
    {
        $this->gross_salary =
            $this->calculateGrossSalary();

        $this->total_deductions =
            $this->calculateTotalDeductions();

        $this->net_salary = max(
            0,
            (float) $this->gross_salary
            - (float) $this->total_deductions
        );

        return $this;
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS UPDATE HELPERS
    |--------------------------------------------------------------------------
    */

    public function markAsProcessed(?int $userId = null): bool
    {
        $this->status = self::STATUS_PROCESSED;

        if ($userId !== null) {
            $this->processed_by = $userId;
        }

        return $this->save();
    }


    public function markAsApproved(?int $userId = null): bool
    {
        $this->status = self::STATUS_APPROVED;

        if ($userId !== null) {
            $this->approved_by = $userId;
        }

        return $this->save();
    }


    public function markAsPaid(?int $userId = null): bool
    {
        $this->status = self::STATUS_PAID;

        if ($userId !== null) {
            $this->paid_by = $userId;
        }

        if (!$this->payment_date) {
            $this->payment_date = now()->toDateString();
        }

        return $this->save();
    }


    public function markAsCancelled(): bool
    {
        $this->status = self::STATUS_CANCELLED;

        return $this->save();
    }
}
