<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHistory extends Model
{
    use HasFactory;

    protected $table = 'user_histories';

    protected $fillable = [
        'user_id',
        'activity',
        'ip_address',
        'city',
        'country',
        'device',
        'user_agent',
        'activity_time',
    ];

    protected $casts = [
        'activity_time' => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    /**
     * User relationship
     */
    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }
}