<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Mass Assignable Attributes
     */
    protected $fillable = [
        'name',
        'phone',
        'profile_image',
        'role',
        'status',
        'email',
        'password',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


    /**
     * Hidden Attributes
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Attribute Casting
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'status'            => 'boolean',
            'created_at'        => 'datetime',
            'updated_at'        => 'datetime',
            'deleted_at'        => 'datetime',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Role Constants
    |--------------------------------------------------------------------------
    */

    public const ROLE_ADMIN = 'admin';

    public const ROLE_OPERATIONS = 'operations';

    public const ROLE_ACCOUNTANT = 'accountant';

    public const ROLE_DRIVER = 'driver';


    /*
    |--------------------------------------------------------------------------
    | Status Constants
    |--------------------------------------------------------------------------
    */

    public const STATUS_ACTIVE = true;

    public const STATUS_INACTIVE = false;


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Get the Driver record mapped with this User.
     *
     * Only Driver role users are expected to have
     * a corresponding Driver record.
     */
    public function driver()
    {
        return $this->hasOne(
            Driver::class,
            'user_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getProfileImageUrlAttribute(): string
    {
        return $this->profile_image
            ? asset(
                'storage/' . $this->profile_image
            )
            : asset(
                'backend/assets/img/logo/trade_bo_icon.webp'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Role Helpers
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }


    public function isOperations(): bool
    {
        return $this->role === self::ROLE_OPERATIONS;
    }


    public function isAccountant(): bool
    {
        return $this->role === self::ROLE_ACCOUNTANT;
    }


    public function isDriver(): bool
    {
        return $this->role === self::ROLE_DRIVER;
    }


    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }


    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }


    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where(
            'status',
            self::STATUS_ACTIVE
        );
    }


    public function scopeInactive($query)
    {
        return $query->where(
            'status',
            self::STATUS_INACTIVE
        );
    }


    public function scopeRole(
        $query,
        string $role
    ) {
        return $query->where(
            'role',
            $role
        );
    }


    public function scopeAdmin($query)
    {
        return $query->where(
            'role',
            self::ROLE_ADMIN
        );
    }


    public function scopeOperations($query)
    {
        return $query->where(
            'role',
            self::ROLE_OPERATIONS
        );
    }


    public function scopeAccountant($query)
    {
        return $query->where(
            'role',
            self::ROLE_ACCOUNTANT
        );
    }


    public function scopeDriver($query)
    {
        return $query->where(
            'role',
            self::ROLE_DRIVER
        );
    }
}