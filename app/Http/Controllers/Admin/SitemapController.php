<?php

namespace App\Http\Controllers\Admin;

use App\Models\Content;
use App\Models\AiTool;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [];

        // Static pages
        $urls[] = url('/');
        $urls[] = url('/pricing');
        $urls[] = url('/features');
        $urls[] = url('/contact');

        // Blogs
        $blogs = Content::where('type', 'blog')->where('status', 'published')->get();
        foreach ($blogs as $post) {
            $urls[] = url('/blog/' . $post->slug);
        }

        // AI tools
        $tools = AiTool::where('status', 'active')->get();
        foreach ($tools as $tool) {
            $urls[] = url('/tools/' . $tool->slug);
        }

        $xml = view('sitemap.xml', ['urls' => $urls])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
