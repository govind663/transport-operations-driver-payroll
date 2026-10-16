<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserHistory extends Model
{
    use HasFactory;

    /**
     * Database table associated with this model.
     */
    protected $table = 'user_histories';

    /**
     * Attributes that can be mass assigned.
     */
    protected $fillable = [
        'user_id',
        'activity',
        'ip_address',
        'city',
        'country',
        'device',
        'user_agent',
        'activity_time',

        // Optional browser location data
        'latitude',
        'longitude',
        'location_accuracy',
        'location_captured_at',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'activity_time'        => 'datetime',
        'location_captured_at' => 'datetime',

        'latitude'             => 'float',
        'longitude'            => 'float',
        'location_accuracy'    => 'float',

        'created_at'           => 'datetime',
        'updated_at'           => 'datetime',
    ];

    /**
     * User associated with this activity history.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Check whether valid coordinates are available.
     */
    public function hasLocation(): bool
    {
        return $this->latitude !== null
            && $this->longitude !== null;
    }
}
