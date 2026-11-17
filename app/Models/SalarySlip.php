<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalarySlip extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'salary_slips';


    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        // References
        'salary_processing_id',
        'driver_id',
        'role',

        // Slip Information
        'slip_no',

        // Salary Period
        'salary_month',
        'salary_year',
        'period_from',
        'period_to',

        // Attendance Details
        'total_working_days',
        'present_days',
        'absent_days',
        'paid_days',

        // Working Details
        'overtime_hours',
        'total_duty_days',
        'total_km',

        // Salary / Working Sheet Earnings
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

        // Final Salary
        'net_salary',

        // Payment
        'payment_date',
        'payment_status',

        // Slip Status
        'status',

        // Remarks
        'remarks',

        // Audit
        'generated_by',
        'issued_by',
        'issued_at',
    ];


    /*
    |--------------------------------------------------------------------------
    | Hidden Attributes
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

            // IDs
            'id' => 'integer',
            'salary_processing_id' => 'integer',
            'driver_id' => 'integer',
            'generated_by' => 'integer',
            'issued_by' => 'integer',

            // Dates / Timestamps
            'period_from' => 'date:Y-m-d',
            'period_to' => 'date:Y-m-d',
            'payment_date' => 'date:Y-m-d',
            'issued_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',

            // Salary Period
            'salary_month' => 'integer',
            'salary_year' => 'integer',

            // Attendance / Working Details
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
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Status Constants
    |--------------------------------------------------------------------------
    */

    public const STATUS_GENERATED = 'generated';

    public const STATUS_ISSUED = 'issued';

    public const STATUS_CANCELLED = 'cancelled';

    /*
    |--------------------------------------------------------------------------
    | Legacy / Compatibility Status
    |--------------------------------------------------------------------------
    |
    | Kept for backward compatibility.
    | Payment should normally be tracked through payment_status.
    |
    */

    public const STATUS_PAID = 'paid';


    /*
    |--------------------------------------------------------------------------
    | Payment Status Constants
    |--------------------------------------------------------------------------
    */

    public const PAYMENT_UNPAID = 'unpaid';

    public const PAYMENT_PARTIAL = 'partial';

    public const PAYMENT_PAID = 'paid';


    /*
    |--------------------------------------------------------------------------
    | Role Constants
    |--------------------------------------------------------------------------
    */

    public const ROLE_DRIVER = 'driver';


    /*
    |--------------------------------------------------------------------------
    | Status Lists
    |--------------------------------------------------------------------------
    */

    public const STATUSES = [
        self::STATUS_GENERATED,
        self::STATUS_ISSUED,
        self::STATUS_CANCELLED,
    ];


    /*
    |--------------------------------------------------------------------------
    | Payment Status Lists
    |--------------------------------------------------------------------------
    */

    public const PAYMENT_STATUSES = [
        self::PAYMENT_UNPAID,
        self::PAYMENT_PARTIAL,
        self::PAYMENT_PAID,
    ];


    /*
    |--------------------------------------------------------------------------
    | Role Lists
    |--------------------------------------------------------------------------
    */

    public const ROLES = [
        self::ROLE_DRIVER,
    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Salary Slip belongs to Salary Processing.
     */
    public function salaryProcessing(): BelongsTo
    {
        return $this->belongsTo(
            SalaryProcessing::class,
            'salary_processing_id'
        );
    }


    /**
     * Salary Slip belongs to Driver.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(
            Driver::class,
            'driver_id'
        );
    }


    /**
     * User who generated the salary slip.
     */
    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'generated_by'
        );
    }


    /**
     * User who issued the salary slip.
     */
    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'issued_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isGenerated(): bool
    {
        return $this->status === self::STATUS_GENERATED;
    }


    public function isIssued(): bool
    {
        return $this->status === self::STATUS_ISSUED;
    }


    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }


    /**
     * Legacy status check.
     */
    public function isStatusPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }


    /*
    |--------------------------------------------------------------------------
    | Payment Helpers
    |--------------------------------------------------------------------------
    */

    public function isUnpaid(): bool
    {
        return $this->payment_status === self::PAYMENT_UNPAID;
    }


    public function isPartiallyPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PARTIAL;
    }


    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }


    /*
    |--------------------------------------------------------------------------
    | Role Helpers
    |--------------------------------------------------------------------------
    */

    public function isDriver(): bool
    {
        return $this->role === self::ROLE_DRIVER;
    }


    /*
    |--------------------------------------------------------------------------
    | Salary Calculations
    |--------------------------------------------------------------------------
    */

    /**
     * Return the Working Sheet amount stored in the salary slip.
     *
     * The combined amount is authoritative when it is populated. When it is
     * zero, the component columns are used as a fallback for older or
     * partially populated records.
     */
    public function calculateWorkingSheetTotalAmount(): float
    {
        $storedTotal = (float) ($this->working_sheet_total_amount ?? 0);

        if ($storedTotal != 0.0) {
            return round($storedTotal, 2);
        }

        return round(
            (float) ($this->working_sheet_base_amount ?? 0)
            + (float) ($this->extra_km_amount ?? 0)
            + (float) ($this->overtime_amount ?? 0)
            + (float) ($this->allowance_amount ?? 0)
            + (float) ($this->expense_reimbursement_amount ?? 0)
            + (float) ($this->working_sheet_other_amount ?? 0),
            2
        );
    }


    /**
     * Total additional earnings excluding basic salary.
     *
     * Working Sheet component amounts are already represented by
     * working_sheet_total_amount, so they are not added again individually.
     */
    public function getTotalEarningsAttribute(): float
    {
        return round(
            $this->calculateWorkingSheetTotalAmount()
            + (float) ($this->bonus_amount ?? 0)
            + (float) ($this->other_earnings ?? 0),
            2
        );
    }


    /**
     * Calculate gross salary without double-counting Working Sheet items.
     */
    public function getCalculatedGrossSalaryAttribute(): float
    {
        return round(
            (float) ($this->basic_salary ?? 0)
            + $this->calculateWorkingSheetTotalAmount()
            + (float) ($this->bonus_amount ?? 0)
            + (float) ($this->other_earnings ?? 0),
            2
        );
    }


    /**
     * Calculate total deductions.
     */
    public function getCalculatedTotalDeductionsAttribute(): float
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
     * Calculate net salary from calculated gross and deductions.
     */
    public function getCalculatedNetSalaryAttribute(): float
    {
        return max(
            0,
            round(
                $this->calculated_gross_salary
                - $this->calculated_total_deductions,
                2
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
    | Salary Period
    |--------------------------------------------------------------------------
    */

    public function getSalaryPeriodAttribute(): string
    {
        if (
            !$this->salary_month ||
            !$this->salary_year
        ) {
            return '-';
        }

        return date(
            'F Y',
            mktime(
                0,
                0,
                0,
                (int) $this->salary_month,
                1,
                (int) $this->salary_year
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Status Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeGenerated($query)
    {
        return $query->where(
            'status',
            self::STATUS_GENERATED
        );
    }


    public function scopeIssued($query)
    {
        return $query->where(
            'status',
            self::STATUS_ISSUED
        );
    }


    public function scopeCancelled($query)
    {
        return $query->where(
            'status',
            self::STATUS_CANCELLED
        );
    }


    /**
     * Legacy status scope.
     */
    public function scopeStatusPaid($query)
    {
        return $query->where(
            'status',
            self::STATUS_PAID
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Payment Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeUnpaid($query)
    {
        return $query->where(
            'payment_status',
            self::PAYMENT_UNPAID
        );
    }


    public function scopePartial($query)
    {
        return $query->where(
            'payment_status',
            self::PAYMENT_PARTIAL
        );
    }


    public function scopePaid($query)
    {
        return $query->where(
            'payment_status',
            self::PAYMENT_PAID
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Role Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeDriver($query)
    {
        return $query->where(
            'role',
            self::ROLE_DRIVER
        );
    }


    public function scopeRole(
        $query,
        string $role
    ) {
        return $query->where(
            'role',
            $role
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
    | Salary Period Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeForMonth(
        $query,
        int $month,
        int $year
    ) {
        return $query
            ->where('salary_month', $month)
            ->where('salary_year', $year);
    }


    /**
     * Alias for period-based filtering.
     */
    public function scopeForPeriod(
        $query,
        int $month,
        int $year
    ) {
        return $this->scopeForMonth($query, $month, $year);
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
    | Payment Date Scope
    |--------------------------------------------------------------------------
    */

    public function scopePaidOn(
        $query,
        string $date
    ) {
        return $query->whereDate(
            'payment_date',
            $date
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Latest Scope
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
    | Issue Helper
    |--------------------------------------------------------------------------
    */

    /**
     * Mark the generated salary slip as issued.
     */
    public function markAsIssued(?int $userId = null): bool
    {
        $this->status = self::STATUS_ISSUED;

        if ($userId !== null) {
            $this->issued_by = $userId;
        }

        if (!$this->issued_at) {
            $this->issued_at = now();
        }

        return $this->save();
    }
}
