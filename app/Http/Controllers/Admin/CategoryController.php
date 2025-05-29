<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(15);
        // Assuming view exists at resources/views/admin/categories/index.blade.php
        return view("admin.categories.index", compact("categories"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Assuming view exists at resources/views/admin/categories/create.blade.php
        return view("admin.categories.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255|unique:categories,name",
        ]);

        Category::create([
            "name" => $request->name,
            // Slug is handled by the model's boot method
        ]);

        return redirect()->route("admin.categories.index")->with("success", "Category created successfully.");
    }

    /**
     * Display the specified resource.
     * Typically not needed for admin CRUD, redirect to edit or index.
     */
    public function show(Category $category)
    {
        return redirect()->route("admin.categories.edit", $category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        // Assuming view exists at resources/views/admin/categories/edit.blade.php
        return view("admin.categories.edit", compact("category"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            "name" => "required|string|max:255|unique:categories,name," . $category->id,
        ]);

        $category->update([
            "name" => $request->name,
            // Slug update can be handled by model's boot method if needed
        ]);

        return redirect()->route("admin.categories.index")->with("success", "Category updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Optional: Check if category is associated with any blogs before deleting
        // if ($category->blogs()->count() > 0) {
        //     return back()->with("error", "Cannot delete category with associated blog posts.");
        // }
        
        $category->delete();
        return redirect()->route("admin.categories.index")->with("success", "Category deleted successfully.");
    }
}

