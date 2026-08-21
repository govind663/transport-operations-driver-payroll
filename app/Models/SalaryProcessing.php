<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

        /*
        |--------------------------------------------------------------------------
        | Driver / Role
        |--------------------------------------------------------------------------
        */

        'driver_id',
        'role',

        /*
        |--------------------------------------------------------------------------
        | Salary Period
        |--------------------------------------------------------------------------
        */

        'salary_month',
        'salary_year',

        /*
        |--------------------------------------------------------------------------
        | Attendance / Working
        |--------------------------------------------------------------------------
        */

        'total_working_days',
        'present_days',
        'absent_days',
        'paid_days',
        'overtime_hours',

        /*
        |--------------------------------------------------------------------------
        | Earnings
        |--------------------------------------------------------------------------
        */

        'basic_salary',
        'allowance_amount',
        'overtime_amount',
        'bonus_amount',
        'other_earnings',
        'gross_salary',

        /*
        |--------------------------------------------------------------------------
        | Deductions
        |--------------------------------------------------------------------------
        */

        'advance_deduction',
        'loan_deduction',
        'penalty_deduction',
        'other_deductions',
        'total_deductions',

        /*
        |--------------------------------------------------------------------------
        | Final Salary
        |--------------------------------------------------------------------------
        */

        'net_salary',

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        'status',

        /*
        |--------------------------------------------------------------------------
        | Payment
        |--------------------------------------------------------------------------
        */

        'payment_date',

        /*
        |--------------------------------------------------------------------------
        | Remarks
        |--------------------------------------------------------------------------
        */

        'remarks',

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        'processed_by',
        'approved_by',
        'paid_by',
    ];


    /*
    |--------------------------------------------------------------------------
    | Hidden
    |--------------------------------------------------------------------------
    */

    protected $hidden = [];


    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Salary Period
            |--------------------------------------------------------------------------
            */

            'salary_month' => 'integer',
            'salary_year'  => 'integer',

            /*
            |--------------------------------------------------------------------------
            | Attendance / Working
            |--------------------------------------------------------------------------
            */

            'total_working_days' => 'decimal:2',
            'present_days'       => 'decimal:2',
            'absent_days'        => 'decimal:2',
            'paid_days'          => 'decimal:2',

            'overtime_hours' => 'decimal:2',

            /*
            |--------------------------------------------------------------------------
            | Earnings
            |--------------------------------------------------------------------------
            */

            'basic_salary'     => 'decimal:2',
            'allowance_amount' => 'decimal:2',
            'overtime_amount'  => 'decimal:2',
            'bonus_amount'     => 'decimal:2',
            'other_earnings'   => 'decimal:2',
            'gross_salary'     => 'decimal:2',

            /*
            |--------------------------------------------------------------------------
            | Deductions
            |--------------------------------------------------------------------------
            */

            'advance_deduction' => 'decimal:2',
            'loan_deduction'    => 'decimal:2',
            'penalty_deduction' => 'decimal:2',
            'other_deductions'  => 'decimal:2',
            'total_deductions'  => 'decimal:2',

            /*
            |--------------------------------------------------------------------------
            | Net Salary
            |--------------------------------------------------------------------------
            */

            'net_salary' => 'decimal:2',

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            'payment_date' => 'date:Y-m-d',

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Role Constants
    |--------------------------------------------------------------------------
    */

    public const ROLE_DRIVER = 'driver';


    /*
    |--------------------------------------------------------------------------
    | Status Constants
    |--------------------------------------------------------------------------
    */

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PROCESSED = 'processed';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_PAID = 'paid';

    public const STATUS_CANCELLED = 'cancelled';


    /*
    |--------------------------------------------------------------------------
    | Status List
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
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Salary processing belongs to Driver.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(
            Driver::class,
            'driver_id'
        );
    }


    /**
     * User who processed the salary.
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'processed_by'
        );
    }


    /**
     * User who approved the salary.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }


    /**
     * User who paid the salary.
     */
    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'paid_by'
        );
    }


    /**
     * Salary slips generated from this processing.
     */
    public function salarySlips(): HasMany
    {
        return $this->hasMany(
            SalarySlip::class,
            'salary_processing_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Status Scopes
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
    | Driver Scope
    |--------------------------------------------------------------------------
    */

    public function scopeForDriver(
        $query,
        int $driverId
    ) {
        return $query->where(
            'driver_id',
            $driverId
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Salary Period Scope
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


    /*
    |--------------------------------------------------------------------------
    | Salary Year Scope
    |--------------------------------------------------------------------------
    */

    public function scopeForYear(
        $query,
        int $year
    ) {
        return $query->where(
            'salary_year',
            $year
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Latest First
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
    | Status Helpers
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
    | Salary Calculations
    |--------------------------------------------------------------------------
    */

    /**
     * Calculate gross salary.
     */
    public function calculateGrossSalary(): float
    {
        return (float) (

            (float) ($this->basic_salary ?? 0)

            + (float) ($this->allowance_amount ?? 0)

            + (float) ($this->overtime_amount ?? 0)

            + (float) ($this->bonus_amount ?? 0)

            + (float) ($this->other_earnings ?? 0)

        );
    }


    /**
     * Calculate total deductions.
     */
    public function calculateTotalDeductions(): float
    {
        return (float) (

            (float) ($this->advance_deduction ?? 0)

            + (float) ($this->loan_deduction ?? 0)

            + (float) ($this->penalty_deduction ?? 0)

            + (float) ($this->other_deductions ?? 0)

        );
    }


    /**
     * Calculate net salary.
     */
    public function calculateNetSalary(): float
    {
        return max(

            0,

            $this->calculateGrossSalary()

            - $this->calculateTotalDeductions()

        );
    }


    /*
    |--------------------------------------------------------------------------
    | Salary Accessors
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
    | Salary Period Accessor
    |--------------------------------------------------------------------------
    */

    /**
     * Example: August 2026
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
    | Formatted Salary Attributes
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
    | Processing Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Fill calculated salary values.
     *
     * This does not save the model.
     */
    public function calculateSalary(): self
    {
        $this->gross_salary =
            $this->calculateGrossSalary();

        $this->total_deductions =
            $this->calculateTotalDeductions();

        $this->net_salary =
            $this->calculateNetSalary();

        return $this;
    }


    /**
     * Mark salary as processed.
     */
    public function markAsProcessed(
        ?int $userId = null
    ): bool {

        $this->status =
            self::STATUS_PROCESSED;

        if ($userId !== null) {

            $this->processed_by =
                $userId;
        }

        return $this->save();
    }


    /**
     * Mark salary as approved.
     */
    public function markAsApproved(
        ?int $userId = null
    ): bool {

        $this->status =
            self::STATUS_APPROVED;

        if ($userId !== null) {

            $this->approved_by =
                $userId;
        }

        return $this->save();
    }


    /**
     * Mark salary as paid.
     */
    public function markAsPaid(
        ?int $userId = null
    ): bool {

        $this->status =
            self::STATUS_PAID;

        if ($userId !== null) {

            $this->paid_by =
                $userId;
        }

        if (!$this->payment_date) {

            $this->payment_date =
                now()->toDateString();
        }

        return $this->save();
    }


    /**
     * Mark salary as cancelled.
     */
    public function markAsCancelled(): bool
    {
        $this->status =
            self::STATUS_CANCELLED;

        return $this->save();
    }
}