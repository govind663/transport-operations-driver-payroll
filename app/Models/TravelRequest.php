<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TravelRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'travel_requests';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Fields
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        // Request Information
        'request_no',
        'company_name',
        'requested_by',

        // Employee / Travel Information
        'employee_email',
        'travel_id',
        'trip_id',

        // Vendor / Vehicle
        'vendor_name',
        'vehicle_type',

        // Travel Date & Time
        'travel_from_date',
        'travel_to_date',
        'pickup_time',
        'travel_date_time',

        // Location
        'from_city',
        'pickup_location',
        'drop_location',
        'release_location',

        // Passenger Information
        'passenger_name',
        'passenger_phone',
        'traveler_mobile',
        'passenger_count',

        // Employee Information
        'employee_id',
        'cost_center',

        // Car Hire / Usage
        'car_hire_type',
        'for_use',

        // Tax Information
        'gst_number',

        // Address Information
        'reporting_address',
        'release_address',
        'release_time',

        // Purpose / Instructions
        'purpose',
        'specific_instruction',

        // Status / Remarks
        'status',
        'remarks',

        // Audit
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'id' => 'integer',

        'company_name' => 'string',
        'requested_by' => 'string',
        'employee_email' => 'string',
        'travel_id' => 'string',
        'trip_id' => 'string',
        'vendor_name' => 'string',
        'vehicle_type' => 'string',

        'travel_from_date' => 'date',
        'travel_to_date' => 'date',
        'travel_date_time' => 'datetime',

        'passenger_count' => 'integer',

        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Status Constants
    |--------------------------------------------------------------------------
    */

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_ASSIGNED = 'assigned';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * User who created the travel request.
     */
    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /**
     * User who last updated the travel request.
     */
    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    /**
     * User who deleted the travel request.
     */
    public function deletedBy()
    {
        return $this->belongsTo(
            User::class,
            'deleted_by'
        );
    }

    /**
     * Duty assignment associated with this travel request.
     */
    public function dutyAssignment()
    {
        return $this->hasOne(
            DutyAssignment::class,
            'travel_request_id'
        );
    }
}