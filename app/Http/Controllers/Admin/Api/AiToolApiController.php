<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\AiTool;
use Illuminate\Http\Resources\Json\JsonResource;

class AiToolApiController extends Controller
{
    public function index()
    {
        return JsonResource::collection(
            AiTool::where('status', 'active')->orderBy('name')->get()
        );
    }
}
