<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ["name", "slug"];

    /**
     * The blogs that belong to the tag.
     */
    public function blogs()
    {
        return $this->belongsToMany(Blog::class, "blog_tag");
    }

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });

        static::updating(function ($tag) {
            // Optionally update slug if name changes
            // if ($tag->isDirty("name")) {
            //     $tag->slug = Str::slug($tag->name);
            // }
        });
    }
}

