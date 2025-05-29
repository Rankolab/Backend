<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AiTool;

class AiToolApiController extends Controller
{
    public function index()
    {
        $tools = AiTool::orderBy('name')->get()->map(function($tool) {
            return [
                'id' => $tool->id,
                'name' => $tool->name,
                'description' => $tool->description,
                'url' => $tool->url,
                'category' => $tool->category,
                'is_premium' => $tool->is_premium,
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $tools
        ]);
    }
}
