<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Website;
use App\Models\License;
use App\Models\WebsiteMetric;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RefreshAnalytics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analytics:refresh {--force : Force refresh regardless of cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh analytics data and cache results';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting analytics refresh...');
        
        $force = $this->option('force');
        
        // Clear relevant caches if forced
        if ($force) {
            $this->info('Forcing cache clear...');
            $this->clearAnalyticsCaches();
        }
        
        // Refresh dashboard stats
        $this->refreshDashboardStats();
        
        // Refresh user growth data
        $this->refreshUserGrowthData();
        
        // Refresh revenue data
        $this->refreshRevenueData();
        
        // Refresh traffic sources data
        $this->refreshTrafficSourcesData();
        
        $this->info('Analytics refresh completed successfully.');
        
        return 0;
    }
    
    /**
     * Refresh dashboard stats.
     */
    private function refreshDashboardStats()
    {
        $this->info('Refreshing dashboard stats...');
        
        // Total users
        $totalUsers = User::count();
        Cache::put('dashboard.totalUsers', $totalUsers, 3600);
        
        // Monthly revenue
        $monthlyRevenue = License::whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->sum('amount');
        Cache::put('dashboard.monthlyRevenue', $monthlyRevenue, 3600);
        
        // Active licenses
        $activeLicenses = License::where('status', 'active')->count();
        Cache::put('dashboard.activeLicenses', $activeLicenses, 3600);
        
        // Total visitors
        $totalVisitors = WebsiteMetric::whereMonth('date', now()->month)
                        ->whereYear('date', now()->year)
                        ->sum('visitors');
        Cache::put('dashboard.totalVisitors', $totalVisitors, 3600);
        
        // Total websites
        $totalWebsites = Website::count();
        Cache::put('dashboard.totalWebsites', $totalWebsites, 3600);
        
        $this->info('Dashboard stats refreshed and cached.');
    }
    
    /**
     * Refresh user growth data.
     */
    private function refreshUserGrowthData()
    {
        $this->info('Refreshing user growth data...');
        
        $userGrowth = User::select(
                \DB::raw('MONTH(created_at) as month'),
                \DB::raw('YEAR(created_at) as year'),
                \DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
            
        Cache::put('dashboard.userGrowth', $userGrowth, 3600);
        
        $this->info('User growth data refreshed and cached.');
    }
    
    /**
     * Refresh revenue data.
     */
    private function refreshRevenueData()
    {
        $this->info('Refreshing revenue data...');
        
        $revenueData = License::select(
                \DB::raw('MONTH(created_at) as month'),
                \DB::raw('YEAR(created_at) as year'),
                \DB::raw('SUM(amount) as total')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
            
        Cache::put('dashboard.revenueData', $revenueData, 3600);
        
        // Current month revenue
        $currentMonthRevenue = License::whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->sum('amount');
        Cache::put('dashboard.currentMonthRevenue', $currentMonthRevenue, 3600);
        
        // Last month revenue
        $lastMonthRevenue = License::whereMonth('created_at', now()->subMonth()->month)
                            ->whereYear('created_at', now()->subMonth()->year)
                            ->sum('amount');
        Cache::put('dashboard.lastMonthRevenue', $lastMonthRevenue, 3600);
        
        $this->info('Revenue data refreshed and cached.');
    }
    
    /**
     * Refresh traffic sources data.
     */
    private function refreshTrafficSourcesData()
    {
        $this->info('Refreshing traffic sources data...');
        
        $trafficSources = WebsiteMetric::select(
                'source',
                \DB::raw('SUM(visitors) as total')
            )
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->groupBy('source')
            ->orderByDesc('total')
            ->get();
            
        Cache::put('dashboard.trafficSources', $trafficSources, 3600);
        
        $this->info('Traffic sources data refreshed and cached.');
    }
    
    /**
     * Clear analytics caches.
     */
    private function clearAnalyticsCaches()
    {
        $cacheKeys = [
            'dashboard.totalUsers',
            'dashboard.monthlyRevenue',
            'dashboard.activeLicenses',
            'dashboard.totalVisitors',
            'dashboard.totalWebsites',
            'dashboard.userGrowth',
            'dashboard.revenueData',
            'dashboard.currentMonthRevenue',
            'dashboard.lastMonthRevenue',
            'dashboard.trafficSources',
            'ai.analytics.user.growth',
            'ai.analytics.revenue.forecast',
            'ai.system.analytics',
        ];
        
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
        
        $this->info('Analytics caches cleared.');
    }
}
