<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverExpense extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'driver_expenses';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | References
        |--------------------------------------------------------------------------
        */

        'driver_id',
        'duty_slip_id',
        'expense_id',

        /*
        |--------------------------------------------------------------------------
        | Amount Information
        |--------------------------------------------------------------------------
        */

        'quantity',
        'rate',
        'amount',

        /*
        |--------------------------------------------------------------------------
        | Remarks
        |--------------------------------------------------------------------------
        */

        'remarks',

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        'status',

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */

        'created_by',
        'updated_by',
    ];


    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'id' => 'integer',

        'driver_id' => 'integer',

        'duty_slip_id' => 'integer',

        'expense_id' => 'integer',

        'quantity' => 'decimal:2',

        'rate' => 'decimal:2',

        'amount' => 'decimal:2',

        'created_by' => 'integer',

        'updated_by' => 'integer',
    ];


    /*
    |--------------------------------------------------------------------------
    | Status Constants
    |--------------------------------------------------------------------------
    */

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_PAID = 'paid';

    public const STATUS_CANCELLED = 'cancelled';


    /*
    |--------------------------------------------------------------------------
    | Status List
    |--------------------------------------------------------------------------
    */

    public const STATUSES = [

        self::STATUS_PENDING,

        self::STATUS_APPROVED,

        self::STATUS_REJECTED,

        self::STATUS_PAID,

        self::STATUS_CANCELLED,
    ];


    /*
    |--------------------------------------------------------------------------
    | Driver
    |--------------------------------------------------------------------------
    */

    public function driver()
    {
        return $this->belongsTo(
            Driver::class,
            'driver_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Duty Slip
    |--------------------------------------------------------------------------
    */

    public function dutySlip()
    {
        return $this->belongsTo(
            DutySlip::class,
            'duty_slip_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Expense Master
    |--------------------------------------------------------------------------
    */

    public function expense()
    {
        return $this->belongsTo(
            Expense::class,
            'expense_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Updated By
    |--------------------------------------------------------------------------
    */

    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }


    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }


    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
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
    | Active Status
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return in_array(
            $this->status,
            [
                self::STATUS_PENDING,
                self::STATUS_APPROVED,
                self::STATUS_PAID,
            ],
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Calculated Amount
    |--------------------------------------------------------------------------
    */

    public function getCalculatedAmountAttribute(): float
    {
        return round(
            (float) $this->quantity *
            (float) $this->rate,
            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePending($query)
    {
        return $query->where(
            'status',
            self::STATUS_PENDING
        );
    }


    public function scopeApproved($query)
    {
        return $query->where(
            'status',
            self::STATUS_APPROVED
        );
    }


    public function scopeRejected($query)
    {
        return $query->where(
            'status',
            self::STATUS_REJECTED
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
    | Active Scope
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->whereIn(
            'status',
            [
                self::STATUS_PENDING,
                self::STATUS_APPROVED,
                self::STATUS_PAID,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Duty Slip Scope
    |--------------------------------------------------------------------------
    */

    public function scopeForDutySlip(
        $query,
        int $dutySlipId
    ) {
        return $query->where(
            'duty_slip_id',
            $dutySlipId
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
}