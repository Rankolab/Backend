<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\AiTool;
use App\Models\Website;

class SitemapController extends Controller
{
    public function index()
    {
        $blogs = Blog::where('status', 'published')->get();
        $tools = AiTool::where('is_active', true)->get();
        
        return response()->view('sitemap.index', [
            'blogs' => $blogs,
            'tools' => $tools,
        ])->header('Content-Type', 'text/xml');
    }
    
    public function blogs()
    {
        $blogs = Blog::where('status', 'published')->get();
        
        return response()->view('sitemap.blogs', [
            'blogs' => $blogs,
        ])->header('Content-Type', 'text/xml');
    }
    
    public function tools()
    {
        $tools = AiTool::where('is_active', true)->get();
        
        return response()->view('sitemap.tools', [
            'tools' => $tools,
        ])->header('Content-Type', 'text/xml');
    }
}
