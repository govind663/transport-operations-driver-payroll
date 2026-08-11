<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkingSheet extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'working_sheets';

    protected $fillable = [
        'sheet_no',
        'duty_slip_id',
        'work_date',
        'opening_meter',
        'closing_meter',
        'total_km',
        'total_hours',
        'overtime_hours',
        'base_amount',
        'extra_km_amount',
        'overtime_amount',
        'other_amount',
        'total_amount',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'id' => 'integer',
        'duty_slip_id' => 'integer',
        'work_date' => 'date:Y-m-d',
        'opening_meter' => 'decimal:2',
        'closing_meter' => 'decimal:2',
        'total_km' => 'decimal:2',
        'total_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'base_amount' => 'decimal:2',
        'extra_km_amount' => 'decimal:2',
        'overtime_amount' => 'decimal:2',
        'other_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_COMPLETED = 'completed';

    public function dutySlip()
    {
        return $this->belongsTo(
            DutySlip::class
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
}