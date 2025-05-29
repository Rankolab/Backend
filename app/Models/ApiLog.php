<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $fillable = [
        'source', 'endpoint', 'method',
        'request_data', 'response',
        'status_code', 'response_time'
    ];
}
