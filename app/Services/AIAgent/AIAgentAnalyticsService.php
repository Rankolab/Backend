<?php

namespace App\Services\AIAgent;

use App\Models\User;
use App\Models\Website;
use App\Models\Article;
use App\Models\License;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AIAgentAnalyticsService
{
    /**
     * Generate predictive analytics for user growth
     *
     * @param int $months Number of months to predict
     * @return array
     */
    public function predictUserGrowth($months = 3)
    {
        try {
            // Get historical user growth data
            $historicalData = User::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as count')
            )
            ->whereDate('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
            
            // Calculate average growth rate
            $growthRates = [];
            $prevCount = null;
            
            foreach ($historicalData as $data) {
                if ($prevCount !== null) {
                    $growthRate = ($data->count - $prevCount) / $prevCount;
                    $growthRates[] = $growthRate;
                }
                $prevCount = $data->count;
            }
            
            // Calculate average growth rate
            $avgGrowthRate = count($growthRates) > 0 ? array_sum($growthRates) / count($growthRates) : 0.1;
            
            // Get current user count
            $currentCount = User::count();
            
            // Generate predictions
            $predictions = [];
            $predictedCount = $currentCount;
            
            for ($i = 1; $i <= $months; $i++) {
                $month = now()->addMonths($i)->format('M Y');
                $predictedCount = round($predictedCount * (1 + $avgGrowthRate));
                
                $predictions[] = [
                    'month' => $month,
                    'predicted_count' => $predictedCount
                ];
            }
            
            return [
                'status' => 'success',
                'data' => [
                    'historical' => $historicalData,
                    'predictions' => $predictions,
                    'growth_rate' => round($avgGrowthRate * 100, 2) . '%'
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error in predictive analytics', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'status' => 'error',
                'message' => 'Error generating predictive analytics: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate revenue forecast
     *
     * @param int $months Number of months to forecast
     * @return array
     */
    public function forecastRevenue($months = 3)
    {
        try {
            // Get historical revenue data
            $historicalData = License::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(amount) as total')
            )
            ->whereDate('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
            
            // Calculate average growth rate
            $growthRates = [];
            $prevTotal = null;
            
            foreach ($historicalData as $data) {
                if ($prevTotal !== null && $prevTotal > 0) {
                    $growthRate = ($data->total - $prevTotal) / $prevTotal;
                    $growthRates[] = $growthRate;
                }
                $prevTotal = $data->total;
            }
            
            // Calculate average growth rate
            $avgGrowthRate = count($growthRates) > 0 ? array_sum($growthRates) / count($growthRates) : 0.05;
            
            // Get current month's revenue
            $currentMonthRevenue = License::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount') ?: 1000; // Default if no data
            
            // Generate forecast
            $forecast = [];
            $predictedRevenue = $currentMonthRevenue;
            
            for ($i = 1; $i <= $months; $i++) {
                $month = now()->addMonths($i)->format('M Y');
                $predictedRevenue = round($predictedRevenue * (1 + $avgGrowthRate), 2);
                
                $forecast[] = [
                    'month' => $month,
                    'predicted_revenue' => $predictedRevenue
                ];
            }
            
            return [
                'status' => 'success',
                'data' => [
                    'historical' => $historicalData,
                    'forecast' => $forecast,
                    'growth_rate' => round($avgGrowthRate * 100, 2) . '%'
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error in revenue forecast', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'status' => 'error',
                'message' => 'Error generating revenue forecast: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Identify anomalies in system activity
     *
     * @return array
     */
    public function detectAnomalies()
    {
        try {
            $anomalies = [];
            
            // Check for unusual login patterns
            $loginAttempts = DB::table('login_attempts')
                ->select('user_id', DB::raw('COUNT(*) as attempts'))
                ->where('created_at', '>=', now()->subDay())
                ->groupBy('user_id')
                ->having('attempts', '>', 5)
                ->get();
                
            if (count($loginAttempts) > 0) {
                foreach ($loginAttempts as $attempt) {
                    $user = User::find($attempt->user_id);
                    $anomalies[] = [
                        'type' => 'login_attempts',
                        'severity' => 'high',
                        'message' => "User {$user->name} ({$user->email}) has {$attempt->attempts} login attempts in the last 24 hours"
                    ];
                }
            }
            
            // Check for unusual API usage
            $apiUsage = DB::table('api_logs')
                ->select('user_id', DB::raw('COUNT(*) as requests'))
                ->where('created_at', '>=', now()->subDay())
                ->groupBy('user_id')
                ->having('requests', '>', 100)
                ->get();
                
            if (count($apiUsage) > 0) {
                foreach ($apiUsage as $usage) {
                    $user = User::find($usage->user_id);
                    $anomalies[] = [
                        'type' => 'api_usage',
                        'severity' => 'medium',
                        'message' => "User {$user->name} ({$user->email}) has {$usage->requests} API requests in the last 24 hours"
                    ];
                }
            }
            
            // Check for unusual content creation
            $contentCreation = Article::where('created_at', '>=', now()->subDay())
                ->select('user_id', DB::raw('COUNT(*) as articles'))
                ->groupBy('user_id')
                ->having('articles', '>', 10)
                ->get();
                
            if (count($contentCreation) > 0) {
                foreach ($contentCreation as $creation) {
                    $user = User::find($creation->user_id);
                    $anomalies[] = [
                        'type' => 'content_creation',
                        'severity' => 'low',
                        'message' => "User {$user->name} ({$user->email}) has created {$creation->articles} articles in the last 24 hours"
                    ];
                }
            }
            
            return [
                'status' => 'success',
                'data' => [
                    'anomalies' => $anomalies,
                    'count' => count($anomalies)
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error in anomaly detection', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'status' => 'error',
                'message' => 'Error detecting anomalies: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate system health report
     *
     * @return array
     */
    public function generateHealthReport()
    {
        try {
            // Get system metrics
            $metrics = [
                'users' => [
                    'total' => User::count(),
                    'active' => User::where('last_login_at', '>=', now()->subDays(30))->count(),
                    'inactive' => User::where('last_login_at', '<', now()->subDays(30))->orWhereNull('last_login_at')->count()
                ],
                'content' => [
                    'total_articles' => Article::count(),
                    'published' => Article::where('status', 'published')->count(),
                    'draft' => Article::where('status', 'draft')->count()
                ],
                'websites' => [
                    'total' => Website::count(),
                    'active' => Website::where('status', 'active')->count(),
                    'inactive' => Website::where('status', 'inactive')->count()
                ],
                'licenses' => [
                    'total' => License::count(),
                    'active' => License::where('status', 'active')->count(),
                    'expired' => License::where('status', 'expired')->count()
                ]
            ];
            
            // Calculate health scores
            $userHealthScore = $metrics['users']['total'] > 0 
                ? ($metrics['users']['active'] / $metrics['users']['total']) * 100 
                : 0;
                
            $contentHealthScore = $metrics['content']['total_articles'] > 0 
                ? ($metrics['content']['published'] / $metrics['content']['total_articles']) * 100 
                : 0;
                
            $websiteHealthScore = $metrics['websites']['total'] > 0 
                ? ($metrics['websites']['active'] / $metrics['websites']['total']) * 100 
                : 0;
                
            $licenseHealthScore = $metrics['licenses']['total'] > 0 
                ? ($metrics['licenses']['active'] / $metrics['licenses']['total']) * 100 
                : 0;
                
            $overallHealthScore = ($userHealthScore + $contentHealthScore + $websiteHealthScore + $licenseHealthScore) / 4;
            
            // Generate recommendations
            $recommendations = [];
            
            if ($userHealthScore < 70) {
                $recommendations[] = "Improve user engagement to increase active user percentage";
            }
            
            if ($contentHealthScore < 70) {
                $recommendations[] = "Increase content publication rate";
            }
            
            if ($websiteHealthScore < 70) {
                $recommendations[] = "Review inactive websites and take appropriate action";
            }
            
            if ($licenseHealthScore < 70) {
                $recommendations[] = "Follow up on expired licenses";
            }
            
            return [
                'status' => 'success',
                'data' => [
                    'metrics' => $metrics,
                    'health_scores' => [
                        'user_health' => round($userHealthScore, 1),
                        'content_health' => round($contentHealthScore, 1),
                        'website_health' => round($websiteHealthScore, 1),
                        'license_health' => round($licenseHealthScore, 1),
                        'overall_health' => round($overallHealthScore, 1)
                    ],
                    'recommendations' => $recommendations
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error generating health report', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'status' => 'error',
                'message' => 'Error generating health report: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get performance optimization recommendations
     *
     * @return array
     */
    public function getOptimizationRecommendations()
    {
        try {
            $recommendations = [];
            
            // Check database query performance
            $slowQueries = Cache::remember('slow_queries', 3600, function () {
                return DB::table('slow_query_log')
                    ->where('created_at', '>=', now()->subDays(7))
                    ->orderBy('execution_time', 'desc')
                    ->take(5)
                    ->get();
            });
            
            if (count($slowQueries) > 0) {
                foreach ($slowQueries as $query) {
                    $recommendations[] = [
                        'type' => 'database',
                        'priority' => 'high',
                        'message' => "Optimize slow query: {$query->query} (Execution time: {$query->execution_time}ms)"
                    ];
                }
            }
            
            // Check for large tables
            $largeTableThreshold = 1000000; // 1 million rows
            $largeTables = Cache::remember('large_tables', 3600, function () use ($largeTableThreshold) {
                return DB::select("
                    SELECT 
                        table_name, 
                        table_rows
                    FROM 
                        information_schema.tables
                    WHERE 
                        table_schema = DATABASE()
                        AND table_rows > ?
                    ORDER BY 
                        table_rows DESC
                ", [$largeTableThreshold]);
            });
            
            if (count($largeTables) > 0) {
                foreach ($largeTables as $table) {
                    $recommendations[] = [
                        'type' => 'database',
                        'priority' => 'medium',
                        'message' => "Consider optimizing large table: {$table->table_name} ({$table->table_rows} rows)"
                    ];
                }
            }
            
            // Check for missing indexes
            $missingIndexes = Cache::remember('missing_indexes', 3600, function () {
                return DB::select("
                    SELECT
                        t.table_name,
                        c.column_name
                    FROM
                        information_schema.tables t
                    JOIN
                        information_schema.columns c ON t.table_name = c.table_name
                    LEFT JOIN
                        information_schema.statistics s ON 
                            t.table_name = s.table_name AND
                            c.column_name = s.column_name
                    WHERE
                        t.table_schema = DATABASE() AND
                        s.index_name IS NULL AND
                        c.column_name LIKE '%_id' AND
                        t.table_rows > 1000
                ");
            });
            
            if (count($missingIndexes) > 0) {
                foreach ($missingIndexes as $index) {
                    $recommendations[] = [
                        'type' => 'database',
                        'priority' => 'medium',
                        'message' => "Consider adding index on {$index->table_name}.{$index->column_name}"
                    ];
                }
            }
            
            // Add general recommendations
            $recommendations[] = [
                'type' => 'cache',
                'priority' => 'medium',
                'message' => "Implement Redis cache for frequently accessed data"
            ];
            
            $recommendations[] = [
                'type' => 'api',
                'priority' => 'medium',
                'message' => "Implement API rate limiting to prevent abuse"
            ];
            
            $recommendations[] = [
                'type' => 'security',
                'priority' => 'high',
                'message' => "Implement two-factor authentication for admin users"
            ];
            
            return [
                'status' => 'success',
                'data' => [
                    'recommendations' => $recommendations,
                    'count' => count($recommendations)
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error generating optimization recommendations', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'status' => 'error',
                'message' => 'Error generating optimization recommendations: ' . $e->getMessage()
            ];
        }
    }
}
