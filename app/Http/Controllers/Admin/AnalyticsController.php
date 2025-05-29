<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Website;
use App\Models\License;
use Illuminate\Support\Facades\DB; // Need DB facade for API calls and potentially charts
use Carbon\Carbon; // Need Carbon for date manipulation

class AnalyticsController extends Controller
{
    /**
     * Display the analytics dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch data for widgets
        $totalUsers = User::count();
        $totalWebsites = Website::count();
        $activeLicenses = License::where('status', 'active')->count(); // Assuming 'active' status
        $apiCallsLast24h = DB::table('api_logs')
                             ->where('created_at', '>=', Carbon::now()->subDay())
                             ->count();

        // Fetch data for charts (example: User Registrations last 30 days)
        $userRegistrations = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                                 ->where('created_at', '>=', Carbon::now()->subDays(30))
                                 ->groupBy('date')
                                 ->orderBy('date', 'asc')
                                 ->pluck('count', 'date');

        // Fetch data for charts (example: Website Growth last 30 days)
        $websiteGrowth = Website::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                                ->where('created_at', '>=', Carbon::now()->subDays(30))
                                ->groupBy('date')
                                ->orderBy('date', 'asc')
                                ->pluck('count', 'date');

        // Prepare chart data in a format suitable for Chart.js (or similar)
        $chartLabels = collect();
        $userRegData = collect();
        $websiteGrowthData = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels->push($date);
            $userRegData->push($userRegistrations->get($date, 0));
            $websiteGrowthData->push($websiteGrowth->get($date, 0));
        }

        $chartData = [
            'labels' => $chartLabels,
            'userRegistrations' => $userRegData,
            'websiteGrowth' => $websiteGrowthData,
        ];


        // Pass data to the view
        return view("admin.analytics.index", compact(
            'totalUsers',
            'totalWebsites',
            'activeLicenses',
            'apiCallsLast24h',
            'chartData' // Pass prepared chart data
        ));
    }
}

