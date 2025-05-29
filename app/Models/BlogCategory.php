<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    /**
     * Get the blogs for the category.
     */
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'category_id');
    }
}
