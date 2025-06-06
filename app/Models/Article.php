<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'body',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
