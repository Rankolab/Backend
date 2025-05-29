<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\Content;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogApiController extends Controller
{
    public function index()
    {
        $posts = Content::where('type', 'blog')->where('status', 'published')
                    ->latest()->select('title', 'slug', 'excerpt', 'thumbnail_url', 'created_at')->paginate(10);

        return JsonResource::collection($posts);
    }

    public function show($slug)
    {
        $post = Content::where('type', 'blog')->where('slug', $slug)->where('status', 'published')->firstOrFail();
        return new JsonResource($post);
    }
}
