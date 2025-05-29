<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiCredential extends Model
{
    protected $fillable = [
        'service_name', 'key_type', 'value', 'status', 'scope', 'notes'
    ];
}
