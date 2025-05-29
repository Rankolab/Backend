<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\License;
use App\Models\Website;
use App\Models\Content;
use App\Models\AiTool;
use Illuminate\Support\Facades\Auth;

class UserDashboard extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $stats = [
            'websites' => Website::where('user_id', $user->id)->count(),
            'content' => Content::whereHas('website', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count(),
            'licenses' => License::where('user_id', $user->id)->where('status', 'active')->count(),
        ];
        
        $recentWebsites = Website::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $recentContent = Content::whereHas('website', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();
        
        $activeLicenses = License::where('user_id', $user->id)
            ->where('status', 'active')
            ->orderBy('expires_at', 'asc')
            ->get();
            
        $recommendedTools = AiTool::where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        return view('user.dashboard', compact(
            'stats', 
            'recentWebsites', 
            'recentContent', 
            'activeLicenses',
            'recommendedTools'
        ));
    }
}
