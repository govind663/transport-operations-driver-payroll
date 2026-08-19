<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;


    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'expenses';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Expense Information
        |--------------------------------------------------------------------------
        */

        'expense_code',
        'name',
        'description',

        /*
        |--------------------------------------------------------------------------
        | Expense Category
        |--------------------------------------------------------------------------
        */

        'expense_type',

        /*
        |--------------------------------------------------------------------------
        | Amount
        |--------------------------------------------------------------------------
        */

        'amount',

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
        'deleted_by',
    ];


    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'id' => 'integer',

        'amount' => 'decimal:2',

        'status' => 'boolean',

        'created_by' => 'integer',

        'updated_by' => 'integer',

        'deleted_by' => 'integer',

        'created_at' => 'datetime',

        'updated_at' => 'datetime',

        'deleted_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | Expense Type Constants
    |--------------------------------------------------------------------------
    */

    public const TYPE_FUEL = 'fuel';

    public const TYPE_TOLL = 'toll';

    public const TYPE_PARKING = 'parking';

    public const TYPE_FOOD = 'food';

    public const TYPE_MAINTENANCE = 'maintenance';

    public const TYPE_REPAIR = 'repair';

    public const TYPE_MISCELLANEOUS = 'miscellaneous';


    /*
    |--------------------------------------------------------------------------
    | Expense Types
    |--------------------------------------------------------------------------
    */

    public const EXPENSE_TYPES = [

        self::TYPE_FUEL,

        self::TYPE_TOLL,

        self::TYPE_PARKING,

        self::TYPE_FOOD,

        self::TYPE_MAINTENANCE,

        self::TYPE_REPAIR,

        self::TYPE_MISCELLANEOUS,
    ];


    /*
    |--------------------------------------------------------------------------
    | Status Constants
    |--------------------------------------------------------------------------
    */

    public const STATUS_ACTIVE = true;

    public const STATUS_INACTIVE = false;


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }


    public function deletedBy()
    {
        return $this->belongsTo(
            User::class,
            'deleted_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scopes - Status
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where(
            'status',
            self::STATUS_ACTIVE
        );
    }


    public function scopeInactive($query)
    {
        return $query->where(
            'status',
            self::STATUS_INACTIVE
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scopes - Expense Type
    |--------------------------------------------------------------------------
    */

    public function scopeFuel($query)
    {
        return $query->where(
            'expense_type',
            self::TYPE_FUEL
        );
    }


    public function scopeToll($query)
    {
        return $query->where(
            'expense_type',
            self::TYPE_TOLL
        );
    }


    public function scopeParking($query)
    {
        return $query->where(
            'expense_type',
            self::TYPE_PARKING
        );
    }


    public function scopeFood($query)
    {
        return $query->where(
            'expense_type',
            self::TYPE_FOOD
        );
    }


    public function scopeMaintenance($query)
    {
        return $query->where(
            'expense_type',
            self::TYPE_MAINTENANCE
        );
    }


    public function scopeRepair($query)
    {
        return $query->where(
            'expense_type',
            self::TYPE_REPAIR
        );
    }


    public function scopeMiscellaneous($query)
    {
        return $query->where(
            'expense_type',
            self::TYPE_MISCELLANEOUS
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Helpers - Status
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }


    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }


    /*
    |--------------------------------------------------------------------------
    | Helpers - Expense Type
    |--------------------------------------------------------------------------
    */

    public function isFuel(): bool
    {
        return $this->expense_type === self::TYPE_FUEL;
    }


    public function isToll(): bool
    {
        return $this->expense_type === self::TYPE_TOLL;
    }


    public function isParking(): bool
    {
        return $this->expense_type === self::TYPE_PARKING;
    }


    public function isFood(): bool
    {
        return $this->expense_type === self::TYPE_FOOD;
    }


    public function isMaintenance(): bool
    {
        return $this->expense_type === self::TYPE_MAINTENANCE;
    }


    public function isRepair(): bool
    {
        return $this->expense_type === self::TYPE_REPAIR;
    }


    public function isMiscellaneous(): bool
    {
        return $this->expense_type === self::TYPE_MISCELLANEOUS;
    }


    /*
    |--------------------------------------------------------------------------
    | Accessor - Expense Type Label
    |--------------------------------------------------------------------------
    */

    public function getExpenseTypeLabelAttribute(): string
    {
        return match ($this->expense_type) {

            self::TYPE_FUEL =>
                'Fuel',

            self::TYPE_TOLL =>
                'Toll',

            self::TYPE_PARKING =>
                'Parking',

            self::TYPE_FOOD =>
                'Food',

            self::TYPE_MAINTENANCE =>
                'Maintenance',

            self::TYPE_REPAIR =>
                'Repair',

            self::TYPE_MISCELLANEOUS =>
                'Miscellaneous',

            default =>
                'Unknown',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | Accessor - Status Label
    |--------------------------------------------------------------------------
    */

    public function getStatusLabelAttribute(): string
    {
        return $this->status
            ? 'Active'
            : 'Inactive';
    }
}