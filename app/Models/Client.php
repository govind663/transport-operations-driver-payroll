<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    protected $table = 'clients';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignable
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        // Basic Information
        'client_code',
        'category',
        'company_name',
        'contact_person',
        'company_logo',

        // Contact Information
        'mobile',
        'alternate_mobile',
        'email',
        'website',

        // Tax Information
        'gst_number',
        'pan_number',

        // Address Information
        'address',
        'city',
        'state',
        'country',
        'pincode',

        // Billing Address
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_country',
        'billing_pincode',

        // Status
        'status',

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

        'status' => 'boolean',

        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',

        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Company Logo URL
     */
    public function getCompanyLogoUrlAttribute(): string
    {
        /*
        |--------------------------------------------------------------------------
        | Default Logo
        |--------------------------------------------------------------------------
        */

        if (empty($this->company_logo)) {
            return asset(
                'backend/assets/img/logo/company.png'
            );
        }

        $logo = ltrim($this->company_logo, '/');

        /*
        |--------------------------------------------------------------------------
        | New FileUploadService Path
        |
        | Example:
        | client/company-logo/filename.webp
        |--------------------------------------------------------------------------
        */

        if (str_starts_with($logo, 'client/')) {

            return asset(
                'storage/' . $logo
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Already Storage Path
        |
        | Example:
        | storage/client/company-logo/filename.webp
        |--------------------------------------------------------------------------
        */

        if (str_starts_with($logo, 'storage/')) {

            return asset($logo);
        }

        /*
        |--------------------------------------------------------------------------
        | Legacy Logo Path
        |
        | Example:
        | filename.webp
        |--------------------------------------------------------------------------
        */

        return asset(
            'backend/assets/uploads/client/' . $logo
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', false);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return (bool) $this->status;
    }

    public function isInactive(): bool
    {
        return ! $this->status;
    }
}