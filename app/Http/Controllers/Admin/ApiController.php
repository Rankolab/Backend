<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Website;
use App\Models\Blog;
use App\Models\AiTool;
use App\Models\User;
use App\Models\ApiService;
use App\Models\MonitoredApi;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function monitoring()
    {
        $monitoredApis = MonitoredApi::with('apiService')->get();
        $apiLogs = DB::table('api_logs')
            ->select('api_service_id', DB::raw('COUNT(*) as total_calls'), DB::raw('AVG(response_time) as avg_response_time'))
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->groupBy('api_service_id')
            ->get();
            
        $apiServices = ApiService::all();
        
        return view('admin.api.monitoring', compact('monitoredApis', 'apiLogs', 'apiServices'));
    }
    
    public function keys()
    {
        $apiServices = ApiService::all();
        return view('admin.api.keys', compact('apiServices'));
    }
    
    public function updateKeys(Request $request)
    {
        $request->validate([
            'api_service_id' => 'required|exists:api_services,id',
            'api_key' => 'required|string',
        ]);
        
        $apiService = ApiService::findOrFail($request->api_service_id);
        $apiService->api_key = $request->api_key;
        $apiService->save();
        
        return redirect()->route('api.keys')->with('success', 'API key updated successfully');
    }
    
    public function logs()
    {
        $logs = DB::table('api_logs')
            ->join('api_services', 'api_logs.api_service_id', '=', 'api_services.id')
            ->select('api_logs.*', 'api_services.name as service_name')
            ->orderByDesc('api_logs.created_at')
            ->paginate(20);
            
        return view('admin.api.logs', compact('logs'));
    }
    
    public function analytics()
    {
        $dailyApiCalls = DB::table('api_logs')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        $serviceUsage = DB::table('api_logs')
            ->join('api_services', 'api_logs.api_service_id', '=', 'api_services.id')
            ->select('api_services.name', DB::raw('COUNT(*) as total'))
            ->whereDate('api_logs.created_at', '>=', now()->subDays(30))
            ->groupBy('api_services.name')
            ->orderByDesc('total')
            ->get();
            
        $errorRates = DB::table('api_logs')
            ->join('api_services', 'api_logs.api_service_id', '=', 'api_services.id')
            ->select(
                'api_services.name',
                DB::raw('COUNT(*) as total_calls'),
                DB::raw('SUM(CASE WHEN status_code >= 400 THEN 1 ELSE 0 END) as error_calls'),
                DB::raw('(SUM(CASE WHEN status_code >= 400 THEN 1 ELSE 0 END) / COUNT(*)) * 100 as error_rate')
            )
            ->whereDate('api_logs.created_at', '>=', now()->subDays(30))
            ->groupBy('api_services.name')
            ->orderByDesc('error_rate')
            ->get();
            
        return view('admin.api.analytics', compact('dailyApiCalls', 'serviceUsage', 'errorRates'));
    }
}
