<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use Illuminate\Http\Request;

class FrontendBlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::where('status', 'published')
                     ->orderByDesc('published_at')
                     ->paginate(10);
        return view('blog.index', compact('blogs'));
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->where('status', 'published')->firstOrFail();
        return view('blog.show', compact('blog'));
    }
}
