<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DutySlip extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'duty_slips';

    protected $fillable = [
        'slip_no',
        'duty_assignment_id',
        'duty_date',
        'start_time',
        'end_time',
        'opening_meter',
        'closing_meter',
        'total_km',
        'fuel_quantity',
        'fuel_amount',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'id' => 'integer',
        'duty_assignment_id' => 'integer',
        'duty_date' => 'date:Y-m-d',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'opening_meter' => 'decimal:2',
        'closing_meter' => 'decimal:2',
        'total_km' => 'decimal:2',
        'fuel_quantity' => 'decimal:2',
        'fuel_amount' => 'decimal:2',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public const STATUS_OPEN = 'open';
    public const STATUS_STARTED = 'started';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public function dutyAssignment()
    {
        return $this->belongsTo(
            DutyAssignment::class
        );
    }

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

    public function workingSheet()
    {
        return $this->hasOne(
            WorkingSheet::class
        );
    }
}