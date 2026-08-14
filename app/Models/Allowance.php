<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Allowance extends Model
{
    use HasFactory, SoftDeletes;


    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'allowances';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Basic Information
        |--------------------------------------------------------------------------
        */

        'allowance_code',
        'allowance_name',
        'description',


        /*
        |--------------------------------------------------------------------------
        | Calculation
        |--------------------------------------------------------------------------
        */

        'calculation_type',
        'amount',
        'percentage',


        /*
        |--------------------------------------------------------------------------
        | Tax
        |--------------------------------------------------------------------------
        */

        'is_taxable',


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

        'amount' => 'decimal:2',

        'percentage' => 'decimal:2',

        'is_taxable' => 'boolean',

        'status' => 'boolean',

        'created_by' => 'integer',

        'updated_by' => 'integer',

        'created_at' => 'datetime',

        'updated_at' => 'datetime',

        'deleted_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | Calculation Type Constants
    |--------------------------------------------------------------------------
    */

    public const CALCULATION_FIXED = 'fixed';

    public const CALCULATION_PERCENTAGE = 'percentage';


    /*
    |--------------------------------------------------------------------------
    | Calculation Types
    |--------------------------------------------------------------------------
    */

    public const CALCULATION_TYPES = [

        self::CALCULATION_FIXED,

        self::CALCULATION_PERCENTAGE,
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
    | Created By Relationship
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
    | Updated By Relationship
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
    | Scope - Active
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where(
            'status',
            self::STATUS_ACTIVE
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scope - Inactive
    |--------------------------------------------------------------------------
    */

    public function scopeInactive($query)
    {
        return $query->where(
            'status',
            self::STATUS_INACTIVE
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scope - Fixed
    |--------------------------------------------------------------------------
    */

    public function scopeFixed($query)
    {
        return $query->where(
            'calculation_type',
            self::CALCULATION_FIXED
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scope - Percentage
    |--------------------------------------------------------------------------
    */

    public function scopePercentage($query)
    {
        return $query->where(
            'calculation_type',
            self::CALCULATION_PERCENTAGE
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Helper - Is Fixed
    |--------------------------------------------------------------------------
    */

    public function isFixed(): bool
    {
        return $this->calculation_type ===
            self::CALCULATION_FIXED;
    }


    /*
    |--------------------------------------------------------------------------
    | Helper - Is Percentage
    |--------------------------------------------------------------------------
    */

    public function isPercentage(): bool
    {
        return $this->calculation_type ===
            self::CALCULATION_PERCENTAGE;
    }


    /*
    |--------------------------------------------------------------------------
    | Helper - Is Active
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return $this->status ===
            self::STATUS_ACTIVE;
    }


    /*
    |--------------------------------------------------------------------------
    | Helper - Is Taxable
    |--------------------------------------------------------------------------
    */

    public function isTaxable(): bool
    {
        return $this->is_taxable === true;
    }


    /*
    |--------------------------------------------------------------------------
    | Helper - Calculation Type Label
    |--------------------------------------------------------------------------
    */

    public function getCalculationTypeLabelAttribute(): string
    {
        return match ($this->calculation_type) {

            self::CALCULATION_FIXED =>
                'Fixed Amount',

            self::CALCULATION_PERCENTAGE =>
                'Percentage',

            default =>
                'Unknown',
        };
    }
}