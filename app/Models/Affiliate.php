<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    protected $fillable = [
        'user_id', 'referral_code', 'clicks', 'referred_count',
        'total_earnings', 'payout_requested', 'payout_completed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
