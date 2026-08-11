<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TravelRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'travel_requests';

    protected $fillable = [
        'request_no',
        'client_id',
        'requested_by',
        'passenger_name',
        'passenger_phone',
        'pickup_location',
        'drop_location',
        'travel_date_time',
        'passenger_count',
        'purpose',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'id' => 'integer',
        'client_id' => 'integer',
        'requested_by' => 'integer',
        'passenger_count' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'travel_date_time' => 'datetime',
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

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function dutyAssignment()
    {
        return $this->hasOne(DutyAssignment::class);
    }
}