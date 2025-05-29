<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\License;
use App\Models\Website;
use App\Models\Blog;
use App\Models\AiTool;
use App\Models\Notification; // Assuming a Notification model exists for alerts
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Added for getting authenticated user

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dashboardSettings = $user->dashboard_settings ?? $this->getDefaultDashboardSettings();

        // Get statistics for dashboard
        $totalUsers = User::count();
        $monthlyRevenue = License::whereMonth("created_at", now()->month)
                            ->whereYear("created_at", now()->year)
                            ->sum("amount");
        $activeLicenses = License::where("status", "active")->count();

        // Calculate conversion rate (Using total websites as a proxy for visitors for now - Needs real visitor tracking)
        $totalVisitors = Website::count(); // Placeholder: Replace with actual visitor tracking data source
        $conversionRate = $totalVisitors > 0
            ? round(($totalUsers / $totalVisitors) * 100, 1)
            : 0;

        // Get additional data for enhanced dashboard
        $totalWebsites = Website::count();
        $publishedArticles = Blog::where("status", "published")->count();
        $totalAiTools = AiTool::count();

        // Get recent activity
        $recentUsers = User::orderBy("created_at", "desc")->take(5)->get();
        $recentLicenses = License::with("user")->orderBy("created_at", "desc")->take(5)->get();

        // --- System Health Metrics ---
        $loadAverage = ["N/A", "N/A", "N/A"];
        if (function_exists("sys_getloadavg")) {
            $loadAverage = sys_getloadavg();
        }
        $memoryStats = $this->getMemoryStats();
        list($dbStatus, $dbError) = $this->getDbStatus();
        // --- End System Health Metrics ---

        // --- Fetch Critical Alerts (Placeholder) ---
        // Replace with actual logic to fetch important, unread notifications/alerts
        $criticalAlerts = []; // Default to empty array
        if (class_exists(Notification::class)) { // Check if Notification model exists before querying
             $criticalAlerts = Notification::where("type", "critical") // Example filter
                                      ->whereNull("read_at")      // Example filter
                                      ->orderBy("created_at", "desc")
                                      ->take(3) // Limit alerts shown on dashboard
                                      ->get();
        } else {
            Log::warning("Notification model not found, skipping critical alerts fetch.");
        }
        // --- End Fetch Critical Alerts ---


        return view("admin.dashboard.index", compact(
            "dashboardSettings", // Added
            "totalUsers",
            "monthlyRevenue",
            "activeLicenses",
            "conversionRate",
            "totalWebsites",
            "publishedArticles",
            "totalAiTools",
            "recentUsers",
            "recentLicenses",
            "loadAverage",
            "memoryStats",
            "dbStatus",
            "dbError",
            "criticalAlerts" // Added
        ));
    }

    private function getDefaultDashboardSettings()
    {
        // Define the default widget visibility and order
        return [
            "visible_widgets" => [
                "performance_overview",
                "revenue_trends",
                "system_health",
                "user_distribution",
                "recent_activity"
            ],
            "widget_order" => [
                "performance_overview",
                "revenue_trends",
                "system_health",
                "user_distribution",
                "recent_activity"
            ]
        ];
    }

    private function getMemoryStats()
    {
        $memoryStats = ["total" => "N/A", "used" => "N/A", "percentage" => "N/A"];
        try {
            $freeOutput = shell_exec("free -m");
            if ($freeOutput) {
                if (preg_match("/^Mem:\s+(\d+)\s+(\d+)\s+(\d+)/m", $freeOutput, $matches)) {
                    if (count($matches) === 4 && $matches[1] > 0) {
                        $memoryStats["total"] = $matches[1];
                        $memoryStats["used"] = $matches[2];
                        $memoryStats["percentage"] = round(($matches[2] / $matches[1]) * 100);
                    }
                } else {
                    Log::warning("Could not parse `free -m` output for memory stats.");
                }
            } else {
                 Log::warning("`free -m` command execution failed or returned empty.");
            }
        } catch (\Throwable $e) {
            Log::error("Error executing or parsing `free -m`: " . $e->getMessage());
        }
        return $memoryStats;
    }

    private function getDbStatus()
    {
        $dbStatus = "Offline";
        $dbError = null;
        try {
            // Use a simpler connection check that should work with SQLite
            DB::connection()->getPdo();
            $dbStatus = "Online";
        } catch (\Exception $e) {
            $dbError = $e->getMessage();
            Log::error("Database connection check failed: " . $dbError);
        }
        return [$dbStatus, $dbError];
    }

    public function getSystemHealthData()
    {
        $loadAverage = ["N/A", "N/A", "N/A"];
        if (function_exists("sys_getloadavg")) {
            $loadAverage = sys_getloadavg();
        }
        $memoryStats = $this->getMemoryStats();
        list($dbStatus, $dbError) = $this->getDbStatus();

        return response()->json([
            "load_1m" => number_format($loadAverage[0] ?? 0, 2),
            "load_5m" => number_format($loadAverage[1] ?? 0, 2),
            "load_15m" => number_format($loadAverage[2] ?? 0, 2),
            "memory_used" => $memoryStats["used"],
            "memory_total" => $memoryStats["total"],
            "memory_percentage" => $memoryStats["percentage"],
            "db_status" => $dbStatus,
        ]);
    }

    public function getRevenueData()
    {
        $revenueData = License::select(
                DB::raw("MONTH(created_at) as month"),
                DB::raw("YEAR(created_at) as year"),
                DB::raw("SUM(amount) as total")
            )
            ->whereYear("created_at", now()->year)
            ->groupBy("year", "month")
            ->orderBy("year")
            ->orderBy("month")
            ->get();
        return response()->json($revenueData);
    }

    public function getUserGrowth()
    {
        $userGrowth = User::select(
                DB::raw("MONTH(created_at) as month"),
                DB::raw("YEAR(created_at) as year"),
                DB::raw("COUNT(*) as total")
            )
            ->whereYear("created_at", now()->year)
            ->groupBy("year", "month")
            ->orderBy("year")
            ->orderBy("month")
            ->get();
        return response()->json($userGrowth);
    }

    public function getTrafficSources()
    {
        // Placeholder: Replace with actual traffic data integration (e.g., Google Analytics API)
        $trafficSources = collect([
            ["source" => "Organic Search", "total" => 0],
            ["source" => "Direct", "total" => 0],
            ["source" => "Referral", "total" => 0],
            ["source" => "Social Media", "total" => 0],
            ["source" => "Other", "total" => 0], // Added Other category
        ]);
        // Log::info("Traffic source data requested, returning placeholder data."); // Optional logging
        return response()->json($trafficSources);
    }

    // New method to save dashboard settings
    public function saveDashboardSettings(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            "visible_widgets" => "required|array",
            "widget_order" => "required|array",
        ]);

        $user->dashboard_settings = $validated;
        $user->save();

        return response()->json(["message" => "Dashboard settings saved successfully."]);
    }
}

