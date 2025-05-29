<?php

namespace App\Http\Controllers\Admin\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiTool;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AiToolController extends Controller
{
    public function index()
    {
        $tools = AiTool::latest()->paginate(15);
        return view('admin.aitools.index', compact('tools'));
    }

    public function create()
    {
        return view('admin.aitools.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'url' => 'required|url',
        ]);

        AiTool::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'url' => $request->url,
            'description' => $request->description,
            'category' => $request->category,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('admin.aitools.index')->with('success', 'Tool added.');
    }

    public function edit(AiTool $aitool)
    {
        return view('admin.aitools.edit', compact('aitool'));
    }

    public function update(Request $request, AiTool $aitool)
    {
        $request->validate([
            'name' => 'required',
            'url' => 'required|url',
        ]);

        $aitool->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'url' => $request->url,
            'description' => $request->description,
            'category' => $request->category,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('admin.aitools.index')->with('success', 'Tool updated.');
    }

    public function destroy(AiTool $aitool)
    {
        $aitool->delete();
        return back()->with('success', 'Tool removed.');
    }
}
