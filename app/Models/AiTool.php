<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiTool extends Model
{
    protected $fillable = ['name', 'slug', 'url', 'description', 'category', 'status'];
}
