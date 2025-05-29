<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AiTool;
use App\Models\AiToolCategory;

class FrontendAiToolController extends Controller
{
    public function index()
    {
        $tools = AiTool::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        $categories = AiToolCategory::withCount('tools')->get();
        $featured = AiTool::where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
            
        return view('frontend.tools.index', compact('tools', 'categories', 'featured'));
    }
    
    public function show($slug)
    {
        $tool = AiTool::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
            
        $related = AiTool::where('is_active', true)
            ->where('id', '!=', $tool->id)
            ->where('category_id', $tool->category_id)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
            
        return view('frontend.tools.show', compact('tool', 'related'));
    }
    
    public function category($slug)
    {
        $category = AiToolCategory::where('slug', $slug)->firstOrFail();
        
        $tools = AiTool::where('is_active', true)
            ->where('category_id', $category->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        return view('frontend.tools.category', compact('tools', 'category'));
    }
    
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        $tools = AiTool::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        return view('frontend.tools.search', compact('tools', 'query'));
    }
}
