<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Category; // Corrected namespace
use Illuminate\Support\Facades\DB; // Added for atomic updates

class FrontendBlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::where("status", "published")->with(["author", "categories", "tags"]);

        // Handle category filtering if category slug is provided
        if ($request->has("category")) {
            $categorySlug = $request->query("category");
            $query->whereHas("categories", function ($q) use ($categorySlug) {
                $q->where("slug", $categorySlug);
            });
        }
        
        // Handle tag filtering if tag slug is provided
        if ($request->has("tag")) {
            $tagSlug = $request->query("tag");
            $query->whereHas("tags", function ($q) use ($tagSlug) {
                $q->where("slug", $tagSlug);
            });
        }

        // Handle search query
        if ($request->has("search")) {
            $searchQuery = $request->query("search");
            $query->where(function($q) use ($searchQuery) {
                $q->where("title", "like", "%{$searchQuery}%")
                  ->orWhere("body", "like", "%{$searchQuery}%")
                  ->orWhereHas("categories", function ($catQ) use ($searchQuery) {
                      $catQ->where("name", "like", "%{$searchQuery}%");
                  })
                  ->orWhereHas("tags", function ($tagQ) use ($searchQuery) {
                      $tagQ->where("name", "like", "%{$searchQuery}%");
                  });
            });
        }

        $blogs = $query->latest()->paginate(9);
            
        // Fetch categories and tags for sidebar/filtering options
        $categories = Category::withCount("blogs")->orderBy("name")->get();
        // $tags = Tag::withCount("blogs")->orderBy("name")->get(); // Uncomment if needed
        
        $featured = Blog::where("status", "published")
            // ->where("is_featured", true) // Assuming an is_featured flag exists
            ->orderBy("view_count", "desc") // Feature by views for now
            ->take(3)
            ->get();
            
        // Pass request query params to view for search persistence etc.
        return view("frontend.blog.index", compact("blogs", "categories", "featured"))->withInput($request->query());
    }
    
    public function show($slug)
    {
        $blog = Blog::where("slug", $slug)
            ->where("status", "published")
            ->with(["author", "categories", "tags"])
            ->firstOrFail();
            
        // Increment view count atomically
        // This is a simple approach. For high traffic, consider queues or dedicated counters.
        try {
            $blog->increment("view_count");
        } catch (\Exception $e) {
            // Log error but don"t prevent page view
            Log::error("Failed to increment view count for blog ID: {$blog->id} - " . $e->getMessage());
        }

        // Fetch related posts (example: same category, excluding current post)
        $related = collect(); // Initialize as empty collection
        if ($blog->categories->isNotEmpty()) {
            $firstCategoryId = $blog->categories->first()->id;
            $related = Blog::where("status", "published")
                ->where("id", "!=", $blog->id)
                ->whereHas("categories", function ($q) use ($firstCategoryId) {
                    $q->where("category_id", $firstCategoryId);
                })
                ->with("author") // Eager load author for related posts
                ->latest()
                ->take(3)
                ->get();
        }
            
        return view("frontend.blog.show", compact("blog", "related"));
    }
    
    // Note: Category and Search routes might be handled by index() now with query parameters
    // Keep them if dedicated views/logic are needed
    /*
    public function category($slug)
    {
        $category = Category::where("slug", $slug)->firstOrFail();
        
        $blogs = Blog::where("status", "published")
            ->whereHas("categories", function ($q) use ($category) {
                $q->where("category_id", $category->id);
            })
            ->latest()
            ->paginate(9);
            
        $categories = Category::withCount("blogs")->orderBy("name")->get();
            
        return view("frontend.blog.index", compact("blogs", "category", "categories")); // Reuse index view
    }
    
    public function search(Request $request)
    {
        $searchQuery = $request->input("query");
        
        $blogs = Blog::where("status", "published")
            ->where(function($q) use ($searchQuery) {
                $q->where("title", "like", "%{$searchQuery}%")
                  ->orWhere("body", "like", "%{$searchQuery}%");
            })
            ->latest()
            ->paginate(9);
            
        $categories = Category::withCount("blogs")->orderBy("name")->get();
            
        return view("frontend.blog.index", compact("blogs", "searchQuery", "categories")); // Reuse index view
    }
    */
}

