<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ["name", "slug"];

    /**
     * The blogs that belong to the category.
     */
    public function blogs()
    {
        return $this->belongsToMany(Blog::class, "blog_category");
    }

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            // Optionally update slug if name changes
            // if ($category->isDirty("name")) {
            //     $category->slug = Str::slug($category->name);
            // }
        });
    }
}

