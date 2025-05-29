<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\Website;

class ContentController extends Controller
{
    public function index()
    {
        $content = Content::with('website')->orderByDesc('created_at')->paginate(10);
        return view('admin.content.index', compact('content'));
    }

    public function show(Content $content)
    {
        return view('admin.content.show', compact('content'));
    }

    public function create()
    {
        $websites = Website::all();
        return view('admin.content.create', compact('websites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'website_id' => 'required|exists:websites,id',
            'status' => 'required',
            'body' => 'required',
        ]);

        Content::create($request->all());

        return redirect()->route('admin.content.index')->with('success', 'Content created.');
    }

    public function edit(Content $content)
    {
        $websites = Website::all();
        return view('admin.content.edit', compact('content', 'websites'));
    }

    public function update(Request $request, Content $content)
    {
        $request->validate([
            'title' => 'required',
            'website_id' => 'required|exists:websites,id',
            'status' => 'required',
            'body' => 'required',
        ]);

        $content->update($request->all());

        return redirect()->route('admin.content.index')->with('success', 'Content updated.');
    }

    public function destroy(Content $content)
    {
        $content->delete();
        return redirect()->route('admin.content.index')->with('success', 'Content deleted.');
    }

    public function publish(Content $content)
    {
        $content->status = 'published';
        $content->published_at = now();
        $content->save();

        return redirect()->back()->with('success', 'Content published.');
    }
}
