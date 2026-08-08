<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    |
    | Database ENUM values ke saath ye values same honi chahiye.
    |
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
    | Fillable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

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
        | Status
        |--------------------------------------------------------------------------
        */

        'status',


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

        'date_of_birth' => 'date:Y-m-d',

        'license_issue_date' => 'date:Y-m-d',

        'license_expiry_date' => 'date:Y-m-d',

        'status' => 'boolean',

        'created_by' => 'integer',

        'updated_by' => 'integer',

        'deleted_by' => 'integer',

    ];

    /*
    |--------------------------------------------------------------------------
    | Driver Types
    |--------------------------------------------------------------------------
    */

    public const DRIVER_FIXED_DUTY = 'fixed_duty';

    public const DRIVER_GENERAL_DUTY = 'general_duty';

    public const DRIVER_TYPES = [
        self::DRIVER_FIXED_DUTY,
        self::DRIVER_GENERAL_DUTY,
    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */


    /**
     * Created By
     */
    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    /**
     * Updated By
     */
    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }


    /**
     * Deleted By
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
    | Driver Photo URL
    |--------------------------------------------------------------------------
    */

    /**
     * Driver Photo URL
     */
    public function getDriverPhotoUrlAttribute(): string
    {
        /*
        |--------------------------------------------------------------------------
        | No Driver Photo
        |--------------------------------------------------------------------------
        */
        if (empty($this->driver_photo)) {

            return asset(
                'backend/assets/img/logo/user.png'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | New Storage Path
        |--------------------------------------------------------------------------
        |
        | Example:
        | driver/1786103680_xxxxx.webp
        |
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


    /**
     * Driving Licence Document URL
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
        |
        | Example:
        | driver/license/filename.webp
        |
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with(
                $this->driving_license_document,
                'driver/'
            )
        ) {

            return asset(
                'storage/' . $this->driving_license_document
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
        |
        | Example:
        | driver/aadhar/filename.webp
        |
        |--------------------------------------------------------------------------
        */
        if (
            str_starts_with(
                $this->aadhar_document,
                'driver/'
            )
        ) {

            return asset(
                'storage/' . $this->aadhar_document
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
        |
        | Example:
        | driver/pan/filename.webp
        |
        |--------------------------------------------------------------------------
        */
        if (
            str_starts_with(
                $this->pan_document,
                'driver/'
            )
        ) {

            return asset(
                'storage/' . $this->pan_document
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
    |
    | Example:
    | LMV_TRANSPORT -> LMV Transport
    |
    */
    public static function getLicenseTypeLabel(string $licenseType): string
    {
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
    | Scopes
    |--------------------------------------------------------------------------
    */


    /**
     * Active Drivers
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }


    /**
     * Inactive Drivers
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }


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