<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */
    protected $table = 'drivers';


    /*
    |--------------------------------------------------------------------------
    | License Type Constants
    |--------------------------------------------------------------------------
    */
    public const LICENSE_LMV = 'LMV';

    public const LICENSE_HMV = 'HMV';

    public const LICENSE_TRANSPORT = 'TRANSPORT';

    public const LICENSE_LMV_TRANSPORT = 'LMV_TRANSPORT';

    public const LICENSE_HMV_TRANSPORT = 'HMV_TRANSPORT';

    public const LICENSE_MCWG = 'MCWG';

    public const LICENSE_MC_WITHOUT_GEAR = 'MCWOG';

    public const LICENSE_OTHER = 'OTHER';


    /*
    |--------------------------------------------------------------------------
    | License Types
    |--------------------------------------------------------------------------
    */
    public const LICENSE_TYPES = [

        self::LICENSE_LMV,

        self::LICENSE_HMV,

        self::LICENSE_TRANSPORT,

        self::LICENSE_LMV_TRANSPORT,

        self::LICENSE_HMV_TRANSPORT,

        self::LICENSE_MCWG,

        self::LICENSE_MC_WITHOUT_GEAR,

        self::LICENSE_OTHER,

    ];


    /*
    |--------------------------------------------------------------------------
    | Driver Type Constants
    |--------------------------------------------------------------------------
    */
    public const DRIVER_FIXED_DUTY = 'fixed_duty';

    public const DRIVER_GENERAL_DUTY = 'general_duty';


    /*
    |--------------------------------------------------------------------------
    | Driver Types
    |--------------------------------------------------------------------------
    */
    public const DRIVER_TYPES = [

        self::DRIVER_FIXED_DUTY,

        self::DRIVER_GENERAL_DUTY,

    ];


    /*
    |--------------------------------------------------------------------------
    | Employment Status Constants
    |--------------------------------------------------------------------------
    */
    public const STATUS_ACTIVE = 'active';

    public const STATUS_ON_LEAVE = 'on_leave';

    public const STATUS_NOTICE_PERIOD = 'notice_period';

    public const STATUS_RESIGNED = 'resigned';

    public const STATUS_TERMINATED = 'terminated';

    public const STATUS_INACTIVE = 'inactive';


    /*
    |--------------------------------------------------------------------------
    | Employment Statuses
    |--------------------------------------------------------------------------
    */
    public const EMPLOYMENT_STATUSES = [

        self::STATUS_ACTIVE,

        self::STATUS_ON_LEAVE,

        self::STATUS_NOTICE_PERIOD,

        self::STATUS_RESIGNED,

        self::STATUS_TERMINATED,

        self::STATUS_INACTIVE,

    ];

    /*
    |--------------------------------------------------------------------------
    | PF Status Constants
    |--------------------------------------------------------------------------
    */
    public const PF_YES = 'yes';

    public const PF_NO = 'no';

    public const PF_STATUSES = [
        self::PF_YES,
        self::PF_NO,
    ];


    /*
    |--------------------------------------------------------------------------
    | Document Status Constants
    |--------------------------------------------------------------------------
    */
    public const DOCUMENT_RECEIVED = 'received';

    public const DOCUMENT_PENDING = 'pending';

    public const DOCUMENT_REJECTED = 'rejected';

    public const DOCUMENT_STATUSES = [
        self::DOCUMENT_RECEIVED,
        self::DOCUMENT_PENDING,
        self::DOCUMENT_REJECTED,
    ];


    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */
    protected $fillable = [

        /*
        |--------------------------------------------------------------------------
        | User Credential
        |--------------------------------------------------------------------------
        */
        'user_id',

        /*
        |--------------------------------------------------------------------------
        | Driver Basic Information
        |--------------------------------------------------------------------------
        */
        'driver_code',
        'driver_type',
        'first_name',
        'last_name',
        'father_name',
        'date_of_birth',
        'gender',
        'marital_status',

        /*
        |--------------------------------------------------------------------------
        | Employment Information
        |--------------------------------------------------------------------------
        */
        'joining_date',
        'resignation_date',
        'last_working_date',
        'termination_date',
        'status',
        'pf_status',
        'document_status',

        /*
        |--------------------------------------------------------------------------
        | Contact Information
        |--------------------------------------------------------------------------
        */ 
        'mobile',
        'alternate_mobile',
        'email',

        /*
        |--------------------------------------------------------------------------
        | Address Information
        |--------------------------------------------------------------------------
        */
        'address',
        'city',
        'state',
        'country',
        'pincode',

        /*
        |--------------------------------------------------------------------------
        | Driving Licence Information
        |--------------------------------------------------------------------------
        */
        'license_number',
        'license_type',
        'license_issue_date',
        'license_expiry_date',
        'license_issuing_authority',

        /*
        |--------------------------------------------------------------------------
        | Driver Qualification, Nominee and Bank Details
        |--------------------------------------------------------------------------
        */
        'driver_qualifications',
        'driver_nominees',
        'driver_bank_details',

        /*
        |--------------------------------------------------------------------------
        | Documents
        |--------------------------------------------------------------------------
        */
        'driver_photo',
        'driving_license_document',
        'aadhar_number',
        'aadhar_document',
        'pan_number',
        'pan_document',

        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */
        'created_by',
        'updated_by',
        'deleted_by',

    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */
    protected $casts = [

        'id' => 'integer',

        /*
        |--------------------------------------------------------------------------
        | Personal Dates
        |--------------------------------------------------------------------------
        */
        'date_of_birth' => 'date:Y-m-d',


        /*
        |--------------------------------------------------------------------------
        | Employment Dates
        |--------------------------------------------------------------------------
        */
        'joining_date' => 'date:Y-m-d',
        'resignation_date' => 'date:Y-m-d',
        'last_working_date' => 'date:Y-m-d',
        'termination_date' => 'date:Y-m-d',


        /*
        |--------------------------------------------------------------------------
        | License Dates
        |--------------------------------------------------------------------------
        */
        'license_issue_date' => 'date:Y-m-d',
        'license_expiry_date' => 'date:Y-m-d',

        /*
        |--------------------------------------------------------------------------
        | JSON Columns Arrays or Objects
        |--------------------------------------------------------------------------
        */
        'driver_qualifications' => 'array',
        'driver_nominees' => 'array',
        'driver_bank_details' => 'array',


        /*
        |--------------------------------------------------------------------------
        | Audit
        |--------------------------------------------------------------------------
        */
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships (created_by, updated_by, deleted_by)
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
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

    public function deletedBy()
    {
        return $this->belongsTo(
            User::class,
            'deleted_by'
        );
    }

    public function dutySlipAllowances()
    {
        return $this->hasMany(
            DriverAllowance::class
        );
    }

    public function dutySlipExpenses()
    {
        return $this->hasMany(
            DriverExpense::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Full Driver Name
     */
    public function getFullNameAttribute(): string
    {
        return trim(
            $this->first_name . ' ' . $this->last_name
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Employment Status Label
    |--------------------------------------------------------------------------
    */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {

            self::STATUS_ACTIVE =>
                'Active',

            self::STATUS_ON_LEAVE =>
                'On Leave',

            self::STATUS_NOTICE_PERIOD =>
                'Notice Period',

            self::STATUS_RESIGNED =>
                'Resigned',

            self::STATUS_TERMINATED =>
                'Terminated',

            self::STATUS_INACTIVE =>
                'Inactive',

            default =>
                ucfirst(
                    str_replace(
                        '_',
                        ' ',
                        $this->status ?? ''
                    )
                ),

        };
    }

    /*
    |--------------------------------------------------------------------------
    | Driver Photo URL
    |--------------------------------------------------------------------------
    */
    public function getDriverPhotoUrlAttribute(): string
    {
        if (empty($this->driver_photo)) {

            return asset(
                'backend/assets/img/logo/user.png'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | New Storage Path
        |--------------------------------------------------------------------------
        */
        if (
            str_starts_with(
                $this->driver_photo,
                'driver/'
            )
        ) {

            return asset(
                'storage/' . $this->driver_photo
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Legacy Driver Upload Path
        |--------------------------------------------------------------------------
        */
        return asset(
            'backend/assets/uploads/driver/' .
            $this->driver_photo
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Driving Licence Document URL
    |--------------------------------------------------------------------------
    */
    public function getDrivingLicenseDocumentUrlAttribute(): ?string
    {
        if (empty($this->driving_license_document)) {

            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | New Storage Path
        |--------------------------------------------------------------------------
        */
        if (
            str_starts_with(
                $this->driving_license_document,
                'driver/'
            )
        ) {

            return asset(
                'storage/' .
                $this->driving_license_document
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Legacy Driver Upload Path
        |--------------------------------------------------------------------------
        */
        return asset(
            'backend/assets/uploads/driver/' .
            $this->driving_license_document
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Aadhar Document URL
    |--------------------------------------------------------------------------
    */
    public function getAadharDocumentUrlAttribute(): ?string
    {
        if (empty($this->aadhar_document)) {

            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | New Storage Path
        |--------------------------------------------------------------------------
        */
        if (
            str_starts_with(
                $this->aadhar_document,
                'driver/'
            )
        ) {

            return asset(
                'storage/' .
                $this->aadhar_document
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Legacy Driver Upload Path
        |--------------------------------------------------------------------------
        */
        return asset(
            'backend/assets/uploads/driver/' .
            $this->aadhar_document
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PAN Document URL
    |--------------------------------------------------------------------------
    */
    public function getPanDocumentUrlAttribute(): ?string
    {
        if (empty($this->pan_document)) {

            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | New Storage Path
        |--------------------------------------------------------------------------
        */
        if (
            str_starts_with(
                $this->pan_document,
                'driver/'
            )
        ) {

            return asset(
                'storage/' .
                $this->pan_document
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Legacy Driver Upload Path
        |--------------------------------------------------------------------------
        */
        return asset(
            'backend/assets/uploads/driver/' .
            $this->pan_document
        );
    }

    /*
    |--------------------------------------------------------------------------
    | License Status Accessor
    |--------------------------------------------------------------------------
    */
    public function getLicenseStatusAttribute(): string
    {
        if (!$this->license_expiry_date) {

            return 'Not Available';
        }


        if ($this->license_expiry_date->isPast()) {

            return 'Expired';
        }


        if (
            $this->license_expiry_date
                ->lte(now()->addDays(30))
        ) {

            return 'Expiring Soon';
        }


        return 'Valid';
    }


    /*
    |--------------------------------------------------------------------------
    | License Type Label Accessor
    |--------------------------------------------------------------------------
    */
    public static function getLicenseTypeLabel(
        string $licenseType
    ): string {

        return match ($licenseType) {

            self::LICENSE_LMV =>
                'Light Motor Vehicle (LMV)',

            self::LICENSE_HMV =>
                'Heavy Motor Vehicle (HMV)',

            self::LICENSE_TRANSPORT =>
                'Transport Vehicle',

            self::LICENSE_LMV_TRANSPORT =>
                'Light Motor Vehicle - Transport (LMV Transport)',

            self::LICENSE_HMV_TRANSPORT =>
                'Heavy Motor Vehicle - Transport (HMV Transport)',

            self::LICENSE_MCWG =>
                'Motorcycle With Gear (MCWG)',

            self::LICENSE_MC_WITHOUT_GEAR =>
                'Motorcycle Without Gear (MCWOG)',

            self::LICENSE_OTHER =>
                'Other',

            default =>
                ucfirst(
                    str_replace(
                        '_',
                        ' ',
                        strtolower($licenseType)
                    )
                ),

        };
    }

    /**
    * Get Driver Type Label
    */
    public static function getDriverTypeLabel(
        string $driverType
    ): string {

        return match ($driverType) {

            self::DRIVER_FIXED_DUTY =>
                'Fixed Duty Driver',

            self::DRIVER_GENERAL_DUTY =>
                'General Duty Driver',

            default =>
                ucwords(str_replace('_', ' ', $driverType)),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | License Helper
    |--------------------------------------------------------------------------
    */

    /**
     * Check whether license type is LMV
     */
    public function isLmvLicense(): bool
    {
        return $this->license_type === self::LICENSE_LMV;
    }

    /**
     * Check whether license type is HMV
     */
    public function isHmvLicense(): bool
    {
        return $this->license_type === self::LICENSE_HMV;
    }

    /**
     * Check whether license is Transport
     */
    public function isTransportLicense(): bool
    {
        return in_array(
            $this->license_type,
            [
                self::LICENSE_TRANSPORT,
                self::LICENSE_LMV_TRANSPORT,
                self::LICENSE_HMV_TRANSPORT,
            ],
            true
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Employment Status Scopes
    |--------------------------------------------------------------------------
    */

    /**
    * Active Drivers
    */
    public function scopeActive($query)
    {
        return $query->where(
            'status',
            self::STATUS_ACTIVE
        );
    }

    /**
    * Inactive Drivers
    */
    public function scopeInactive($query)
    {
        return $query->where(
            'status',
            self::STATUS_INACTIVE
        );
    }

    /**
    * Drivers On Leave
    */
    public function scopeOnLeave($query)
    {
        return $query->where(
            'status',
            self::STATUS_ON_LEAVE
        );
    }

    /**
    * Drivers In Notice Period
    */
    public function scopeNoticePeriod($query)
    {
        return $query->where(
            'status',
            self::STATUS_NOTICE_PERIOD
        );
    }

    /**
    * Resigned Drivers
    */
    public function scopeResigned($query)
    {
        return $query->where(
            'status',
            self::STATUS_RESIGNED
        );
    }

    /**
    * Terminated Drivers
    */
    public function scopeTerminated($query)
    {
        return $query->where(
            'status',
            self::STATUS_TERMINATED
        );
    }

    /*
    |--------------------------------------------------------------------------
    | License Scopes
    |--------------------------------------------------------------------------
    */

    /**
    * Drivers with Valid License
    */
    public function scopeLicenseValid($query)
    {
        return $query
            ->whereNotNull('license_expiry_date')
            ->whereDate(
                'license_expiry_date',
                '>=',
                now()->toDateString()
            );
    }

    /**
    * Drivers with Expired License
    */
    public function scopeLicenseExpired($query)
    {
        return $query
            ->whereNotNull('license_expiry_date')
            ->whereDate(
                'license_expiry_date',
                '<',
                now()->toDateString()
            );
    }

    /**
    * Drivers whose license expires
    * within given days
    */
    public function scopeLicenseExpiringWithin(
        $query,
        int $days = 30
    ) {
        return $query
            ->whereNotNull('license_expiry_date')
            ->whereBetween(
                'license_expiry_date',
                [
                    now()->toDateString(),

                    now()
                        ->addDays($days)
                        ->toDateString(),
                ]
            );
    }

    /**
    * Filter by License Type
    */
    public function scopeLicenseType(
        $query,
        string $licenseType
    ) {
        return $query->where(
            'license_type',
            $licenseType
        );
    }

    /**
    * Filter Transport License Drivers
    */
    public function scopeTransportLicense($query)
    {
        return $query->whereIn(
            'license_type',
            [
                self::LICENSE_TRANSPORT,
                self::LICENSE_LMV_TRANSPORT,
                self::LICENSE_HMV_TRANSPORT,
            ]
        );
    }

    /**
    * Filter LMV Drivers
    */
    public function scopeLmv($query)
    {
        return $query->where(
            'license_type',
            self::LICENSE_LMV
        );
    }

    /**
    * Filter HMV Drivers
    */
    public function scopeHmv($query)
    {
        return $query->where(
            'license_type',
            self::LICENSE_HMV
        );
    }
}