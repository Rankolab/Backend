<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchMetric extends Model
{
    protected $fillable = [
        'website_id', 'date', 'clicks', 'impressions', 'ctr', 'query'
    ];

    public function website()
    {
        return $this->belongsTo(Website::class);
    }
}
