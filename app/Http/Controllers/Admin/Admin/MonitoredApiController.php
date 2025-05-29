<?php

namespace App\Http\Controllers\Admin\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonitoredApi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

class MonitoredApiController extends Controller
{
    public function index()
    {
        return view('admin.monitoring.index', [
            'apis' => MonitoredApi::orderBy('name')->get()
        ]);
    }

    public function check(MonitoredApi $monitoredApi)
    {
        $start = microtime(true);
        try {
            $response = Http::timeout(8)->get($monitoredApi->endpoint);
            $monitoredApi->status = $response->successful() ? 'online' : 'offline';
        } catch (\Exception $e) {
            $monitoredApi->status = 'offline';
        }
        $monitoredApi->last_checked_at = Carbon::now();
        $monitoredApi->response_time_ms = round((microtime(true) - $start) * 1000);
        $monitoredApi->save();

        return back()->with('success', 'API checked successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'endpoint' => 'required|url'
        ]);

        MonitoredApi::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'endpoint' => $request->endpoint,
        ]);

        return back()->with('success', 'API added.');
    }

    public function destroy(MonitoredApi $monitoredApi)
    {
        $monitoredApi->delete();
        return back()->with('success', 'API removed.');
    }
}
