<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DutySlip extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'duty_slips';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */
    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Duty Slip Information
        |--------------------------------------------------------------------------
        */
        'slip_no',
        'duty_assignment_id',
        'duty_date',


        /*
        |--------------------------------------------------------------------------
        | Trip Information
        |--------------------------------------------------------------------------
        */
        'start_time',
        'end_time',


        /*
        |--------------------------------------------------------------------------
        | Meter Information
        |--------------------------------------------------------------------------
        */
        'opening_meter',
        'closing_meter',
        'total_km',


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */
        'status',


        /*
        |--------------------------------------------------------------------------
        | Remarks
        |--------------------------------------------------------------------------
        */
        'remarks',


        /*
        |--------------------------------------------------------------------------
        | Duty Slip Documents
        |--------------------------------------------------------------------------
        */
        'duty_slip_front_file',
        'duty_slip_back_file',


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

        'duty_assignment_id' => 'integer',

        'duty_date' => 'date:Y-m-d',

        'start_time' => 'datetime',
        'end_time' => 'datetime',

        'opening_meter' => 'decimal:2',
        'closing_meter' => 'decimal:2',
        'total_km' => 'decimal:2',

        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];


    /*
    |--------------------------------------------------------------------------
    | Status Constants
    |--------------------------------------------------------------------------
    */
    public const STATUS_OPEN = 'open';

    public const STATUS_STARTED = 'started';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';


    /*
    |--------------------------------------------------------------------------
    | Status List
    |--------------------------------------------------------------------------
    */
    public const STATUSES = [

        self::STATUS_OPEN,

        self::STATUS_STARTED,

        self::STATUS_COMPLETED,

        self::STATUS_CANCELLED,

    ];


    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */
    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }


    public function isStarted(): bool
    {
        return $this->status === self::STATUS_STARTED;
    }


    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }


    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }


    /*
    |--------------------------------------------------------------------------
    | Duty Assignment
    |--------------------------------------------------------------------------
    */
    public function dutyAssignment()
    {
        return $this->belongsTo(
            DutyAssignment::class
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
    | Working Sheet
    |--------------------------------------------------------------------------
    */
    public function workingSheet()
    {
        return $this->hasOne(
            WorkingSheet::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Driver Allowances
    |--------------------------------------------------------------------------
    */
    public function driverAllowances()
    {
        return $this->hasMany(
            DriverAllowance::class,
            'duty_slip_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Driver Expenses
    |--------------------------------------------------------------------------
    */
    public function driverExpenses()
    {
        return $this->hasMany(
            DriverExpense::class,
            'duty_slip_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approved Driver Allowances
    |--------------------------------------------------------------------------
    */
    public function approvedDriverAllowances()
    {
        return $this->hasMany(
            DriverAllowance::class,
            'duty_slip_id'
        )->where(
            'status',
            DriverAllowance::STATUS_APPROVED
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approved Driver Expenses
    |--------------------------------------------------------------------------
    */
    public function approvedDriverExpenses()
    {
        return $this->hasMany(
            DriverExpense::class,
            'duty_slip_id'
        )->where(
            'status',
            DriverExpense::STATUS_APPROVED
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Allowance Total
    |--------------------------------------------------------------------------
    */
    public function getAllowanceTotalAttribute(): float
    {
        return (float) $this->driverAllowances()
            ->where(
                'status',
                DriverAllowance::STATUS_APPROVED
            )
            ->sum('amount');
    }


    /*
    |--------------------------------------------------------------------------
    | Expense Total
    |--------------------------------------------------------------------------
    */
    public function getExpenseTotalAttribute(): float
    {
        return (float) $this->driverExpenses()
            ->where(
                'status',
                DriverExpense::STATUS_APPROVED
            )
            ->sum('amount');
    }


    /*
    |--------------------------------------------------------------------------
    | Grand Total
    |--------------------------------------------------------------------------
    */
    public function getGrandTotalAttribute(): float
    {
        return round(
            $this->allowance_total
            + $this->expense_total,
            2
        );
    }
}