<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DutyAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'duty_assignments';

    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'assignment_no',
        'travel_request_id',
        'driver_id',
        'vehicle_id',
        'assigned_at',
        'reporting_time',
        'reporting_location',
        'status',
        'remarks',
        'assigned_by',
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

        'travel_request_id' => 'integer',
        'driver_id' => 'integer',
        'vehicle_id' => 'integer',

        'assigned_by' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',

        // Database: DATETIME
        'assigned_at' => 'datetime',

        // Database: TIME
        'reporting_time' => 'string',
    ];

    /*
    |--------------------------------------------------------------------------
    | Status Constants
    |--------------------------------------------------------------------------
    */

    public const STATUS_PENDING = 'pending';

    public const STATUS_ASSIGNED = 'assigned';

    public const STATUS_ACCEPTED = 'accepted';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_STARTED = 'started';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function travelRequest()
    {
        return $this->belongsTo(
            TravelRequest::class,
            'travel_request_id',
            'id'
        );
    }

    public function driver()
    {
        return $this->belongsTo(
            Driver::class,
            'driver_id',
            'id'
        );
    }

    public function vehicle()
    {
        return $this->belongsTo(
            VehicleManagement::class,
            'vehicle_id',
            'id'
        );
    }

    public function assignedBy()
    {
        return $this->belongsTo(
            User::class,
            'assigned_by',
            'id'
        );
    }

    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'id'
        );
    }

    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by',
            'id'
        );
    }

    public function dutySlip()
    {
        return $this->hasOne(
            DutySlip::class,
            'duty_assignment_id',
            'id'
        );
    }
}