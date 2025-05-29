<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::latest()->paginate(15);
        // Assuming view exists at resources/views/admin/tags/index.blade.php
        return view("admin.tags.index", compact("tags"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Assuming view exists at resources/views/admin/tags/create.blade.php
        return view("admin.tags.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255|unique:tags,name",
        ]);

        Tag::create([
            "name" => $request->name,
            // Slug is handled by the model's boot method
        ]);

        return redirect()->route("admin.tags.index")->with("success", "Tag created successfully.");
    }

    /**
     * Display the specified resource.
     * Typically not needed for admin CRUD, redirect to edit or index.
     */
    public function show(Tag $tag)
    {
        return redirect()->route("admin.tags.edit", $tag);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        // Assuming view exists at resources/views/admin/tags/edit.blade.php
        return view("admin.tags.edit", compact("tag"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            "name" => "required|string|max:255|unique:tags,name," . $tag->id,
        ]);

        $tag->update([
            "name" => $request->name,
            // Slug update can be handled by model's boot method if needed
        ]);

        return redirect()->route("admin.tags.index")->with("success", "Tag updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        // Optional: Check if tag is associated with any blogs before deleting
        // if ($tag->blogs()->count() > 0) {
        //     return back()->with("error", "Cannot delete tag with associated blog posts.");
        // }
        
        $tag->delete();
        return redirect()->route("admin.tags.index")->with("success", "Tag deleted successfully.");
    }
}

