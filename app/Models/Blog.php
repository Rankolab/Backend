<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'slug', 
        'excerpt', 
        'content', // Changed from 'body' to 'content'
        'status', 
        'author_id', 
        'category_id', // Added category_id
        'featured_image', // Added featured_image (nullable)
        'published_at', // Added published_at (nullable)
        'is_featured', // Added is_featured
        'cover_image', // Kept for potential other uses
        'meta_keywords', // Keep for SEO
        'meta_description' // Keep for SEO
    ];

    /**
     * Get the author of the blog post.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * The categories that belong to the blog post.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'blog_category');
    }

    /**
     * The tags that belong to the blog post.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tag');
    }

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
        });

        static::updating(function ($blog) {
             // Optionally update slug if title changes and you want slugs to reflect title changes
             // Be cautious with this in production as it can break existing links.
             // if ($blog->isDirty('title')) {
             //     $blog->slug = Str::slug($blog->title);
             // }
        });
    }
}

