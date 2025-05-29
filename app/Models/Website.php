<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;

    protected $primaryKey = 'website_id'; // Set custom primary key
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name', // Added name to fillable
        'domain',
        'url', // Added url to fillable
        'niche',
        'website_type',
        'license_id', // Added license_id here as it's likely needed for the relationship
    ];

    /**
     * Get the user that owns the website.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the search metrics for the website.
     */
    public function searchMetrics()
    {
        return $this->hasMany(\App\Models\SearchMetric::class, 'website_id');
    }

    /**
     * Get the website metrics associated with the website.
     */
    public function metrics()
    {
        return $this->hasOne(WebsiteMetric::class, 'website_id'); // Assuming WebsiteMetric model exists
    }

    /**
     * Get the license associated with the website.
     */
    public function license()
    {
        // Assuming a license_id foreign key exists on the websites table
        // and relates to the primary key of the License model.
        // Adjust if the relationship or keys are different.
        return $this->belongsTo(License::class, 'license_id');
    }
}

