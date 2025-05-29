<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "plan_id",
        "stripe_id",
        "stripe_status",
        "trial_ends_at",
        "ends_at",
    ];

    protected $casts = [
        "trial_ends_at" => "datetime",
        "ends_at" => "datetime",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Helper method to check if the subscription is active
    public function isActive()
    {
        return in_array($this->stripe_status, ["active", "trialing"]);
    }

    // Helper method to check if the subscription is canceled
    public function isCanceled()
    {
        return $this->stripe_status === "canceled" || !is_null($this->ends_at);
    }
}
