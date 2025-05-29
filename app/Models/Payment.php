<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "subscription_id",
        "stripe_payment_intent_id",
        "amount",
        "currency",
        "status",
        "description",
        "payment_method_details",
        "stripe_invoice_id",
    ];

    protected $casts = [
        "amount" => "decimal:2",
        "payment_method_details" => "array",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
