<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class DutySlip extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

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
        'driver_id',
        'vehicle_id',
        'vehicle_type_id',
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
        'deleted_by',
    ];


    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        /*
        |--------------------------------------------------------------------------
        | IDs
        |--------------------------------------------------------------------------
        */

        'id' =>
            'integer',

        'duty_assignment_id' =>
            'integer',

        'driver_id' =>
            'integer',

        'vehicle_id' =>
            'integer',

        'vehicle_type_id' =>
            'integer',


        /*
        |--------------------------------------------------------------------------
        | Date
        |--------------------------------------------------------------------------
        */

        'duty_date' =>
            'date:Y-m-d',


        /*
        |--------------------------------------------------------------------------
        | Datetime
        |--------------------------------------------------------------------------
        */

        'start_time' =>
            'datetime',

        'end_time' =>
            'datetime',


        /*
        |--------------------------------------------------------------------------
        | Decimal
        |--------------------------------------------------------------------------
        */

        'opening_meter' =>
            'decimal:2',

        'closing_meter' =>
            'decimal:2',

        'total_km' =>
            'decimal:2',


        /*
        |--------------------------------------------------------------------------
        | Audit IDs
        |--------------------------------------------------------------------------
        */

        'created_by' =>
            'integer',

        'updated_by' =>
            'integer',

        'deleted_by' =>
            'integer',
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
    | Final Status
    |--------------------------------------------------------------------------
    */

    public function isFinal(): bool
    {
        return in_array(
            $this->status,
            [
                self::STATUS_COMPLETED,
                self::STATUS_CANCELLED,
            ],
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Duty Assignment
    |--------------------------------------------------------------------------
    */

    public function dutyAssignment(): BelongsTo
    {
        return $this->belongsTo(
            DutyAssignment::class,
            'duty_assignment_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Driver
    |--------------------------------------------------------------------------
    */

    public function driver(): BelongsTo
    {
        return $this->belongsTo(
            Driver::class,
            'driver_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Vehicle
    |--------------------------------------------------------------------------
    */

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(
            VehicleManagement::class,
            'vehicle_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Vehicle Type
    |--------------------------------------------------------------------------
    */

    public function vehicleType(): BelongsTo
    {
        return $this->belongsTo(
            VehicleType::class,
            'vehicle_type_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Updated By
    |--------------------------------------------------------------------------
    */

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Deleted By
    |--------------------------------------------------------------------------
    */

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'deleted_by',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Working Sheet
    |--------------------------------------------------------------------------
    */

    public function workingSheet(): HasOne
    {
        return $this->hasOne(
            WorkingSheet::class,
            'duty_slip_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Driver Allowances
    |--------------------------------------------------------------------------
    */

    public function driverAllowances(): HasMany
    {
        return $this->hasMany(
            DriverAllowance::class,
            'duty_slip_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Driver Expenses
    |--------------------------------------------------------------------------
    */

    public function driverExpenses(): HasMany
    {
        return $this->hasMany(
            DriverExpense::class,
            'duty_slip_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approved Driver Allowances
    |--------------------------------------------------------------------------
    */

    public function approvedDriverAllowances(): HasMany
    {
        return $this->hasMany(
            DriverAllowance::class,
            'duty_slip_id',
            'id'
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

    public function approvedDriverExpenses(): HasMany
    {
        return $this->hasMany(
            DriverExpense::class,
            'duty_slip_id',
            'id'
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
        if (
            $this->relationLoaded(
                'driverAllowances'
            )
        ) {

            return round(
                (float) $this->driverAllowances
                    ->where(
                        'status',
                        DriverAllowance::STATUS_APPROVED
                    )
                    ->sum('amount'),
                2
            );
        }


        return round(
            (float) $this->driverAllowances()
                ->where(
                    'status',
                    DriverAllowance::STATUS_APPROVED
                )
                ->sum('amount'),
            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Expense Total
    |--------------------------------------------------------------------------
    */

    public function getExpenseTotalAttribute(): float
    {
        if (
            $this->relationLoaded(
                'driverExpenses'
            )
        ) {

            return round(
                (float) $this->driverExpenses
                    ->where(
                        'status',
                        DriverExpense::STATUS_APPROVED
                    )
                    ->sum('amount'),
                2
            );
        }


        return round(
            (float) $this->driverExpenses()
                ->where(
                    'status',
                    DriverExpense::STATUS_APPROVED
                )
                ->sum('amount'),
            2
        );
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