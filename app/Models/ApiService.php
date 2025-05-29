<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiService extends Model
{
    protected $fillable = [
        'name', 'category', 'base_url', 'status', 'last_checked_at',
        'error_count', 'response_time_ms'
    ];
}
