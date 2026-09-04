<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehiclePrice extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vehicle_prices';

    protected $fillable = [
        'vehicle_id',
        'price',
        'effective_date',
        'remarks',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'effective_date' => 'date',

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',

        'vehicle_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | VEHICLE
    |--------------------------------------------------------------------------
    */

    public function vehicle()
    {
        return $this->belongsTo(
            VehicleManagement::class,
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
}