<?php

namespace App\Http\Controllers\Admin;

use App\Models\AiTool;

class FrontendAiToolController extends Controller
{
    public function index()
    {
        $tools = AiTool::where('is_active', true)->orderBy('name')->get();
        $grouped = $tools->groupBy('type');
        return view('tools.index', compact('grouped'));
    }
}
