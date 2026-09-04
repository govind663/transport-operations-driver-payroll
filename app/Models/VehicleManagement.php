<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleManagement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vehicle_management';

    protected $fillable = [
        'vehicle_category_id',
        'vehicle_type_id',

        'vehicle_number',
        'registration_number',

        'chassis_number',
        'engine_number',

        'manufacturer',
        'model',
        'manufacturing_year',
        'color',

        'capacity',
        'capacity_unit',

        'status',
        'remarks',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'manufacturing_year' => 'integer',
        'capacity' => 'decimal:2',

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',

        'deleted_by' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | CATEGORY
    |--------------------------------------------------------------------------
    */

    public function vehicleCategory()
    {
        return $this->belongsTo(
            VehicleCategory::class,
            'vehicle_category_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TYPE
    |--------------------------------------------------------------------------
    */

    public function vehicleType()
    {
        return $this->belongsTo(
            VehicleType::class,
            'vehicle_type_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PRICES
    |--------------------------------------------------------------------------
    */

    public function vehiclePrices()
    {
        return $this->hasMany(
            VehiclePrice::class,
            'vehicle_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATED BY
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
    | UPDATED BY
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
    | DELETED BY
    |--------------------------------------------------------------------------
    */

    public function deletedBy()
    {
        return $this->belongsTo(
            User::class,
            'deleted_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE VEHICLES
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /*
    |--------------------------------------------------------------------------
    | MAINTENANCE VEHICLES
    |--------------------------------------------------------------------------
    */

    public function scopeMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }
}