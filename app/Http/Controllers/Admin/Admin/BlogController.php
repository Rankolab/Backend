<?php

namespace App\Http\Controllers\Admin\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->paginate(10);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        Blog::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'cover_image' => $request->cover_image,
            'author' => $request->author ?? 'Rankolab',
            'content' => $request->content,
            'meta_keywords' => $request->meta_keywords,
            'meta_description' => $request->meta_description,
            'status' => $request->status ?? 'draft',
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post created.');
    }

    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $blog->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'cover_image' => $request->cover_image,
            'author' => $request->author ?? $blog->author,
            'content' => $request->content,
            'meta_keywords' => $request->meta_keywords,
            'meta_description' => $request->meta_description,
            'status' => $request->status ?? 'draft',
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog post updated.');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('admin.blogs.index')->with('success', 'Blog post deleted.');
    }
}
