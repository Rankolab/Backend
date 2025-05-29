<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenseDomain extends Model
{
    protected $fillable = ['license_id', 'domain', 'ip'];

    public function license()
    {
        return $this->belongsTo(License::class);
    }
}
