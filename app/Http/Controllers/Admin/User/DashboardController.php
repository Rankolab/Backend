<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Website;
use App\Models\License;
use App\Models\Content;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_websites' => Website::where('user_id', $user->id)->count(),
            'total_licenses' => License::where('user_id', $user->id)->count(),
            'articles_generated' => Content::whereHas('website', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
        ];


        $latestLicense = License::where('user_id', $user->id)
            ->orderBy('expires_at', 'desc')
            ->first();

        $pluginConnected = Website::where('user_id', $user->id)
            ->where('is_plugin_active', true)
            ->exists();

        $stats['license_expiry'] = $latestLicense?->expires_at;
        $stats['plugin_connected'] = $pluginConnected;


        
        $websites = Website::with('metrics')
            ->where('user_id', $user->id)
            ->latest()->take(5)->get();

        return view('user.dashboard.index', compact('user', 'stats', 'websites'));
    }
}
