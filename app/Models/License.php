<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'key',
        'type',
        'status',
        'amount',
        'expires_at',
        'features',
        'max_websites',
        'max_users',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'features' => 'array',
    ];

    /**
     * Get the user that owns the license.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the websites associated with this license.
     */
    public function websites()
    {
        return $this->hasMany(Website::class);
    }

    /**
     * Get the activations for this license.
     */
    public function activations()
    {
        return $this->hasMany(LicenseActivation::class);
    }

    /**
     * Check if the license is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === 'active' && 
               ($this->expires_at === null || $this->expires_at->isFuture());
    }

    /**
     * Check if the license is expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    /**
     * Check if the license has reached its website limit.
     *
     * @return bool
     */
    public function hasReachedWebsiteLimit()
    {
        return $this->max_websites !== null && 
               $this->websites()->count() >= $this->max_websites;
    }

    /**
     * Check if the license has reached its activation limit.
     *
     * @return bool
     */
    public function hasReachedActivationLimit()
    {
        return $this->max_users !== null && 
               $this->activations()->where('status', 'active')->count() >= $this->max_users;
    }

    /**
     * Generate a new license key.
     *
     * @return string
     */
    public static function generateKey()
    {
        $prefix = 'RKL';
        $random = bin2hex(random_bytes(12));
        $timestamp = dechex(time());
        $key = $prefix . '-' . $random . '-' . $timestamp;
        
        // Format with dashes for readability
        $key = implode('-', str_split($key, 4));
        
        return strtoupper($key);
    }
}
