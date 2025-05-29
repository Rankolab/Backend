<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\License;
use App\Models\Website;
use App\Models\Blog;
use App\Models\AiTool;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics for dashboard with caching
        $totalUsers = Cache::remember('dashboard.totalUsers', 3600, function () {
            return User::count();
        });
        
        $monthlyRevenue = Cache::remember('dashboard.monthlyRevenue', 3600, function () {
            return License::whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->sum('amount');
        });
        
        $activeLicenses = Cache::remember('dashboard.activeLicenses', 3600, function () {
            return License::where('status', 'active')->count();
        });
        
        // Calculate conversion rate with caching
        $totalVisitors = Cache::remember('dashboard.totalVisitors', 3600, function () {
            return DB::table('website_metrics')
                            ->whereMonth('date', now()->month)
                            ->whereYear('date', now()->year)
                            ->sum('visitors');
        });
        
        $conversionRate = $totalVisitors > 0 
            ? round(($totalUsers / $totalVisitors) * 100, 1) 
            : 0;
        
        // Get additional data for enhanced dashboard with caching
        $totalWebsites = Cache::remember('dashboard.totalWebsites', 3600, function () {
            return Website::count();
        });
        
        $totalBlogs = Cache::remember('dashboard.totalBlogs', 3600, function () {
            return Blog::count();
        });
        
        $totalAiTools = Cache::remember('dashboard.totalAiTools', 3600, function () {
            return AiTool::count();
        });
        
        // Get recent activity with caching
        $recentUsers = Cache::remember('dashboard.recentUsers', 1800, function () {
            return User::orderBy('created_at', 'desc')->take(5)->get();
        });
        
        $recentLicenses = Cache::remember('dashboard.recentLicenses', 1800, function () {
            return License::with('user')->orderBy('created_at', 'desc')->take(5)->get();
        });
        
        return view('admin.dashboard.index', compact(
            'totalUsers', 
            'monthlyRevenue', 
            'activeLicenses', 
            'conversionRate',
            'totalWebsites',
            'totalBlogs',
            'totalAiTools',
            'recentUsers',
            'recentLicenses'
        ));
    }

    public function getRevenueData()
    {
        // Get monthly revenue data for the chart with caching
        $revenueData = Cache::remember('dashboard.revenueData', 3600, function () {
            return License::select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('SUM(amount) as total')
                )
                ->whereYear('created_at', now()->year)
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
        });
            
        return response()->json($revenueData);
    }

    public function getUserGrowth()
    {
        // Get user growth data for the chart with caching
        $userGrowth = Cache::remember('dashboard.userGrowth', 3600, function () {
            return User::select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereYear('created_at', now()->year)
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
        });
            
        return response()->json($userGrowth);
    }

    public function getTrafficSources()
    {
        // Get traffic sources data for the chart with caching
        $trafficSources = Cache::remember('dashboard.trafficSources', 3600, function () {
            return DB::table('website_metrics')
                ->select(
                    'source',
                    DB::raw('SUM(visitors) as total')
                )
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->groupBy('source')
                ->orderByDesc('total')
                ->get();
        });
            
        return response()->json($trafficSources);
    }

    public function revenue()
    {
        // Revenue summary endpoint for sidebar widget or quick stat with caching
        $monthlyRevenue = Cache::remember('dashboard.currentMonthRevenue', 3600, function () {
            return License::whereMonth('created_at', now()->month)->sum('amount');
        });
        
        $lastMonthRevenue = Cache::remember('dashboard.lastMonthRevenue', 3600, function () {
            return License::whereMonth('created_at', now()->subMonth()->month)->sum('amount');
        });

        $growth = $lastMonthRevenue > 0
            ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 2)
            : 100;

        $status = $growth >= 0 ? 'increase' : 'decrease';

        return response()->json([
            'monthlyRevenue' => $monthlyRevenue,
            'growth' => abs($growth),
            'status' => $status,
        ]);
    }
}
