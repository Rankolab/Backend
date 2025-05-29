<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;

class BlogApiController extends Controller
{
    public function index()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->get()->map(function($blog) {
            return [
                'id' => $blog->id,
                'title' => $blog->title,
                'slug' => $blog->slug,
                'excerpt' => $blog->excerpt,
                'featured_image' => $blog->featured_image,
                'created_at' => $blog->created_at->format('Y-m-d'),
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $blogs
        ]);
    }
    
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        
        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $blog->id,
                'title' => $blog->title,
                'slug' => $blog->slug,
                'content' => $blog->content,
                'featured_image' => $blog->featured_image,
                'created_at' => $blog->created_at->format('Y-m-d'),
                'updated_at' => $blog->updated_at->format('Y-m-d'),
            ]
        ]);
    }
}
