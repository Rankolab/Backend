<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MonitoredApi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class MonitoredApiController extends Controller
{
    public function index()
    {
        $apis = MonitoredApi::orderBy('name')->get()->map(function($api) {
            return [
                'id' => $api->id,
                'name' => $api->name,
                'endpoint' => $api->endpoint,
                'status' => $api->status,
                'response_time_ms' => $api->response_time_ms,
                'last_checked_at' => $api->last_checked_at ? $api->last_checked_at->diffForHumans() : 'Never',
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $apis
        ]);
    }
}
