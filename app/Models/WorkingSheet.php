<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkingSheet extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'working_sheets';


    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */
    protected $fillable = [

        // Working Sheet
        'sheet_no',

        // Centralized Duty Slip
        'duty_slip_id',

        // Driver - For Driver Dashboard / Reports
        'driver_id',

        // Work Details
        'work_date',
        'opening_meter',
        'closing_meter',
        'total_km',
        'total_hours',
        'overtime_hours',

        // Financial Details
        'base_amount',
        'extra_km_amount',
        'overtime_amount',
        'other_amount',
        'total_amount',

        // Status
        'status',

        // Remarks
        'remarks',

        // Audit
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

        'duty_slip_id' => 'integer',

        'driver_id' => 'integer',

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


    /*
    |--------------------------------------------------------------------------
    | Status Constants
    |--------------------------------------------------------------------------
    */
    public const STATUS_DRAFT = 'draft';

    public const STATUS_SUBMITTED = 'submitted';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_COMPLETED = 'completed';


    /*
    |--------------------------------------------------------------------------
    | Status List
    |--------------------------------------------------------------------------
    */
    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_SUBMITTED,
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
            self::STATUS_COMPLETED,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Duty Slip Relationship
    |--------------------------------------------------------------------------
    |
    | Duty Slip remains the centralized master record.
    |
    */
    public function dutySlip()
    {
        return $this->belongsTo(
            DutySlip::class,
            'duty_slip_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Driver Relationship
    |--------------------------------------------------------------------------
    |
    | Direct Driver relationship is maintained for:
    | - Driver Dashboard
    | - Driver-wise Working Sheets
    | - Driver-wise KM
    | - Driver-wise Hours
    | - Driver-wise Earnings
    | - Driver-wise Reports
    |
    */
    public function driver()
    {
        return $this->belongsTo(
            Driver::class,
            'driver_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Created By
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
    | Updated By
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
    | Scope - Draft
    |--------------------------------------------------------------------------
    */
    public function scopeDraft($query)
    {
        return $query->where(
            'status',
            self::STATUS_DRAFT
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scope - Submitted
    |--------------------------------------------------------------------------
    */
    public function scopeSubmitted($query)
    {
        return $query->where(
            'status',
            self::STATUS_SUBMITTED
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scope - Approved
    |--------------------------------------------------------------------------
    */
    public function scopeApproved($query)
    {
        return $query->where(
            'status',
            self::STATUS_APPROVED
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scope - Rejected
    |--------------------------------------------------------------------------
    */
    public function scopeRejected($query)
    {
        return $query->where(
            'status',
            self::STATUS_REJECTED
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scope - Completed
    |--------------------------------------------------------------------------
    */
    public function scopeCompleted($query)
    {
        return $query->where(
            'status',
            self::STATUS_COMPLETED
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scope - Driver
    |--------------------------------------------------------------------------
    */
    public function scopeForDriver(
        $query,
        $driverId
    ) {
        return $query->where(
            'driver_id',
            $driverId
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scope - Duty Slip
    |--------------------------------------------------------------------------
    */
    public function scopeForDutySlip(
        $query,
        $dutySlipId
    ) {
        return $query->where(
            'duty_slip_id',
            $dutySlipId
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scope - Work Date
    |--------------------------------------------------------------------------
    */
    public function scopeForDate(
        $query,
        $date
    ) {
        return $query->whereDate(
            'work_date',
            $date
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scope - Latest First
    |--------------------------------------------------------------------------
    */
    public function scopeLatestFirst($query)
    {
        return $query
            ->orderByDesc('work_date')
            ->orderByDesc('id');
    }


    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }


    public function isSubmitted(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }


    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }


    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }


    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Total KM
    |--------------------------------------------------------------------------
    */
    public function calculateTotalKm(): float
    {
        $opening = (float) ($this->opening_meter ?? 0);

        $closing = (float) ($this->closing_meter ?? 0);

        if ($closing < $opening) {
            return 0;
        }

        return round(
            $closing - $opening,
            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Calculate Total Amount
    |--------------------------------------------------------------------------
    */
    public function calculateTotalAmount(): float
    {
        return round(
            (float) ($this->base_amount ?? 0)
            +
            (float) ($this->extra_km_amount ?? 0)
            +
            (float) ($this->overtime_amount ?? 0)
            +
            (float) ($this->other_amount ?? 0),
            2
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Calculate And Set Totals
    |--------------------------------------------------------------------------
    */
    public function calculateTotals(): void
    {
        $this->total_km =
            $this->calculateTotalKm();

        $this->total_amount =
            $this->calculateTotalAmount();
    }
}