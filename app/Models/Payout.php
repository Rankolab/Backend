<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $fillable = ['affiliate_id', 'amount', 'method', 'status'];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }
}
