<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $fillable = ['affiliate_id', 'referral_id', 'amount', 'status', 'approved_at'];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }
}
