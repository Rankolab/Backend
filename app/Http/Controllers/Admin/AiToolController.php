<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AiTool;
use App\Models\ToolCategory;

class AiToolController extends Controller
{
    public function index()
    {
        $tools = AiTool::with('category')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.aitools.index', compact('tools'));
    }
    
    public function create()
    {
        $categories = ToolCategory::all();
        return view('admin.aitools.create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:tool_categories,id',
            'url' => 'required|url',
            'icon' => 'nullable|string|max:50',
            'is_premium' => 'boolean',
            'status' => 'required|in:active,inactive,beta',
        ]);
        
        AiTool::create($request->all());
        
        return redirect()->route('admin.aitools.index')->with('success', 'AI Tool created successfully');
    }
    
    public function show(AiTool $aitool)
    {
        $aitool->load('category');
        return view('admin.aitools.show', compact('aitool'));
    }
    
    public function edit(AiTool $aitool)
    {
        $categories = ToolCategory::all();
        return view('admin.aitools.edit', compact('aitool', 'categories'));
    }
    
    public function update(Request $request, AiTool $aitool)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:tool_categories,id',
            'url' => 'required|url',
            'icon' => 'nullable|string|max:50',
            'is_premium' => 'boolean',
            'status' => 'required|in:active,inactive,beta',
        ]);
        
        $aitool->update($request->all());
        
        return redirect()->route('admin.aitools.index')->with('success', 'AI Tool updated successfully');
    }
    
    public function destroy(AiTool $aitool)
    {
        $aitool->delete();
        return redirect()->route('admin.aitools.index')->with('success', 'AI Tool deleted successfully');
    }
}
