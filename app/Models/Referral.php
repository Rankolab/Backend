<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable = ['affiliate_id', 'referred_user_id', 'ip_address', 'browser_info', 'commission', 'converted_at'];

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }
}
