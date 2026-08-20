<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverAttendance extends Model
{
    use HasFactory, SoftDeletes;


    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'driver_attendances';


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | Driver
        |--------------------------------------------------------------------------
        */
        'driver_id',

        /*
        |--------------------------------------------------------------------------
        | Attendance
        |--------------------------------------------------------------------------
        */
        'attendance_date',
        'status',

        /*
        |--------------------------------------------------------------------------
        | Working Time
        |--------------------------------------------------------------------------
        */
        'in_time',
        'out_time',
        'total_hours',

        /*
        |--------------------------------------------------------------------------
        | Source
        |--------------------------------------------------------------------------
        */
        'working_sheet_id',
        'source',

        /*
        |--------------------------------------------------------------------------
        | Remarks
        |--------------------------------------------------------------------------
        */
        'remarks',

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

        'driver_id' => 'integer',

        'attendance_date' => 'date:Y-m-d',

        'in_time' => 'datetime:H:i:s',

        'out_time' => 'datetime:H:i:s',

        'total_hours' => 'decimal:2',

        'working_sheet_id' => 'integer',

        'created_by' => 'integer',

        'updated_by' => 'integer',

    ];


    /*
    |--------------------------------------------------------------------------
    | Attendance Status Constants
    |--------------------------------------------------------------------------
    */

    public const STATUS_PRESENT = 'present';

    public const STATUS_ABSENT = 'absent';

    public const STATUS_HALF_DAY = 'half_day';

    public const STATUS_LEAVE = 'leave';

    public const STATUS_WEEKLY_OFF = 'weekly_off';

    public const STATUS_HOLIDAY = 'holiday';

    public const STATUS_ON_DUTY = 'on_duty';

    public const STATUS_COMP_OFF = 'comp_off';

    public const STATUS_LATE = 'late';

    public const STATUS_EARLY_EXIT = 'early_exit';

    public const STATUS_MISSING_PUNCH = 'missing_punch';

    public const STATUS_PENDING = 'pending';


    /*
    |--------------------------------------------------------------------------
    | All Attendance Statuses
    |--------------------------------------------------------------------------
    */

    public const STATUSES = [

        self::STATUS_PRESENT,

        self::STATUS_ABSENT,

        self::STATUS_HALF_DAY,

        self::STATUS_LEAVE,

        self::STATUS_WEEKLY_OFF,

        self::STATUS_HOLIDAY,

        self::STATUS_ON_DUTY,

        self::STATUS_COMP_OFF,

        self::STATUS_LATE,

        self::STATUS_EARLY_EXIT,

        self::STATUS_MISSING_PUNCH,

        self::STATUS_PENDING,

    ];


    /*
    |--------------------------------------------------------------------------
    | Source Constants
    |--------------------------------------------------------------------------
    */

    public const SOURCE_WORKING_SHEET = 'working_sheet';

    public const SOURCE_MANUAL = 'manual';

    public const SOURCE_SYSTEM = 'system';

    public const SOURCE_IMPORT = 'import';


    /*
    |--------------------------------------------------------------------------
    | Sources
    |--------------------------------------------------------------------------
    */

    public const SOURCES = [

        self::SOURCE_WORKING_SHEET,

        self::SOURCE_MANUAL,

        self::SOURCE_SYSTEM,

        self::SOURCE_IMPORT,

    ];


    /*
    |--------------------------------------------------------------------------
    | Driver Relationship
    |--------------------------------------------------------------------------
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
    | Working Sheet Relationship
    |--------------------------------------------------------------------------
    */

    public function workingSheet()
    {
        return $this->belongsTo(
            WorkingSheet::class,
            'working_sheet_id'
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
    | Status Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePresent($query)
    {
        return $query->where(
            'status',
            self::STATUS_PRESENT
        );
    }


    public function scopeAbsent($query)
    {
        return $query->where(
            'status',
            self::STATUS_ABSENT
        );
    }


    public function scopeHalfDay($query)
    {
        return $query->where(
            'status',
            self::STATUS_HALF_DAY
        );
    }


    public function scopeLeave($query)
    {
        return $query->where(
            'status',
            self::STATUS_LEAVE
        );
    }


    public function scopeWeeklyOff($query)
    {
        return $query->where(
            'status',
            self::STATUS_WEEKLY_OFF
        );
    }


    public function scopeHoliday($query)
    {
        return $query->where(
            'status',
            self::STATUS_HOLIDAY
        );
    }


    public function scopeOnDuty($query)
    {
        return $query->where(
            'status',
            self::STATUS_ON_DUTY
        );
    }


    public function scopeCompOff($query)
    {
        return $query->where(
            'status',
            self::STATUS_COMP_OFF
        );
    }


    public function scopeLate($query)
    {
        return $query->where(
            'status',
            self::STATUS_LATE
        );
    }


    public function scopeEarlyExit($query)
    {
        return $query->where(
            'status',
            self::STATUS_EARLY_EXIT
        );
    }


    public function scopeMissingPunch($query)
    {
        return $query->where(
            'status',
            self::STATUS_MISSING_PUNCH
        );
    }


    public function scopePending($query)
    {
        return $query->where(
            'status',
            self::STATUS_PENDING
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Driver Scope
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
    | Date Scope
    |--------------------------------------------------------------------------
    */

    public function scopeForDate(
        $query,
        $date
    ) {
        return $query->whereDate(
            'attendance_date',
            $date
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isPresent(): bool
    {
        return $this->status === self::STATUS_PRESENT;
    }


    public function isAbsent(): bool
    {
        return $this->status === self::STATUS_ABSENT;
    }


    public function isHalfDay(): bool
    {
        return $this->status === self::STATUS_HALF_DAY;
    }


    public function isLeave(): bool
    {
        return $this->status === self::STATUS_LEAVE;
    }


    public function isWeeklyOff(): bool
    {
        return $this->status === self::STATUS_WEEKLY_OFF;
    }


    public function isHoliday(): bool
    {
        return $this->status === self::STATUS_HOLIDAY;
    }


    public function isOnDuty(): bool
    {
        return $this->status === self::STATUS_ON_DUTY;
    }


    public function isCompOff(): bool
    {
        return $this->status === self::STATUS_COMP_OFF;
    }


    public function isLate(): bool
    {
        return $this->status === self::STATUS_LATE;
    }


    public function isEarlyExit(): bool
    {
        return $this->status === self::STATUS_EARLY_EXIT;
    }


    public function isMissingPunch(): bool
    {
        return $this->status === self::STATUS_MISSING_PUNCH;
    }


    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }


    /*
    |--------------------------------------------------------------------------
    | Status Label
    |--------------------------------------------------------------------------
    */

    public function getStatusLabelAttribute(): string
    {
        return ucwords(
            str_replace(
                '_',
                ' ',
                $this->status ?? ''
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Source Label
    |--------------------------------------------------------------------------
    */

    public function getSourceLabelAttribute(): string
    {
        return ucwords(
            str_replace(
                '_',
                ' ',
                $this->source ?? ''
            )
        );
    }
}