<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoredApi extends Model
{
    protected $fillable = [
        'name', 'slug', 'endpoint', 'status', 'last_checked_at', 'response_time_ms'
    ];

    protected $casts = [
        'last_checked_at' => 'datetime',
    ];
}
