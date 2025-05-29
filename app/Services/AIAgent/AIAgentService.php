<?php

namespace App\Services\AIAgent;

use App\Models\User;
use App\Models\License;
use App\Models\Website;
use App\Models\Article;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AIAgentService
{
    /**
     * Check if the current user has Super Admin access
     *
     * @return bool
     */
    public function hasSuperAdminAccess()
    {
        $user = Auth::user();
        return $user && $user->role === 'superadmin';
    }

    /**
     * Process natural language command and route to appropriate handler
     *
     * @param string $command
     * @return array
     */
    public function processCommand($command)
    {
        if (!$this->hasSuperAdminAccess()) {
            Log::warning('Unauthorized access attempt to AI Agent', [
                'user_id' => Auth::id(),
                'command' => $command
            ]);
            
            return [
                'status' => 'error',
                'message' => 'Access denied. Super Admin privileges required.'
            ];
        }

        // Log the command for audit purposes
        Log::info('AI Agent command received', [
            'user_id' => Auth::id(),
            'command' => $command
        ]);

        // Process the command using NLP
        $intent = $this->detectIntent($command);
        $entities = $this->extractEntities($command);

        // Route to appropriate handler based on intent
        switch ($intent) {
            case 'get_system_status':
                return $this->getSystemStatus();
            case 'user_management':
                return $this->handleUserManagement($entities);
            case 'content_management':
                return $this->handleContentManagement($entities);
            case 'license_management':
                return $this->handleLicenseManagement($entities);
            case 'system_analytics':
                return $this->getSystemAnalytics($entities);
            case 'security_audit':
                return $this->performSecurityAudit();
            default:
                return [
                    'status' => 'error',
                    'message' => 'Command not recognized. Please try again with a valid command.'
                ];
        }
    }

    /**
     * Detect the intent of the command
     *
     * @param string $command
     * @return string
     */
    private function detectIntent($command)
    {
        $command = strtolower($command);
        
        if (strpos($command, 'status') !== false || strpos($command, 'health') !== false) {
            return 'get_system_status';
        }
        
        if (strpos($command, 'user') !== false || strpos($command, 'account') !== false) {
            return 'user_management';
        }
        
        if (strpos($command, 'content') !== false || strpos($command, 'article') !== false || strpos($command, 'blog') !== false) {
            return 'content_management';
        }
        
        if (strpos($command, 'license') !== false || strpos($command, 'subscription') !== false) {
            return 'license_management';
        }
        
        if (strpos($command, 'analytics') !== false || strpos($command, 'report') !== false || strpos($command, 'stats') !== false) {
            return 'system_analytics';
        }
        
        if (strpos($command, 'security') !== false || strpos($command, 'audit') !== false) {
            return 'security_audit';
        }
        
        return 'unknown';
    }

    /**
     * Extract entities from the command
     *
     * @param string $command
     * @return array
     */
    private function extractEntities($command)
    {
        $entities = [];
        
        // Extract user IDs
        if (preg_match('/user (\d+)/i', $command, $matches)) {
            $entities['user_id'] = $matches[1];
        }
        
        // Extract date ranges
        if (preg_match('/from (\d{4}-\d{2}-\d{2}) to (\d{4}-\d{2}-\d{2})/i', $command, $matches)) {
            $entities['date_range'] = [
                'from' => $matches[1],
                'to' => $matches[2]
            ];
        }
        
        // Extract content IDs
        if (preg_match('/article (\d+)/i', $command, $matches)) {
            $entities['article_id'] = $matches[1];
        }
        
        // Extract license IDs
        if (preg_match('/license (\d+)/i', $command, $matches)) {
            $entities['license_id'] = $matches[1];
        }
        
        return $entities;
    }

    /**
     * Get system status information
     *
     * @return array
     */
    private function getSystemStatus()
    {
        try {
            $userCount = User::count();
            $activeUserCount = User::where('last_login_at', '>=', now()->subDays(30))->count();
            $licenseCount = License::count();
            $activeLicenseCount = License::where('status', 'active')->count();
            $websiteCount = Website::count();
            $articleCount = Article::count();
            
            // Get server load
            $load = sys_getloadavg();
            
            // Get database status
            $dbStatus = DB::select('SHOW STATUS');
            $dbConnections = collect($dbStatus)->firstWhere('Variable_name', 'Threads_connected')->Value;
            
            return [
                'status' => 'success',
                'data' => [
                    'users' => [
                        'total' => $userCount,
                        'active' => $activeUserCount
                    ],
                    'licenses' => [
                        'total' => $licenseCount,
                        'active' => $activeLicenseCount
                    ],
                    'content' => [
                        'websites' => $websiteCount,
                        'articles' => $articleCount
                    ],
                    'system' => [
                        'server_load' => $load,
                        'db_connections' => $dbConnections
                    ]
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error getting system status', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'status' => 'error',
                'message' => 'Error retrieving system status: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Handle user management commands
     *
     * @param array $entities
     * @return array
     */
    private function handleUserManagement($entities)
    {
        try {
            if (isset($entities['user_id'])) {
                // Get specific user details
                $user = User::find($entities['user_id']);
                
                if (!$user) {
                    return [
                        'status' => 'error',
                        'message' => 'User not found'
                    ];
                }
                
                return [
                    'status' => 'success',
                    'data' => [
                        'user' => $user,
                        'licenses' => $user->licenses,
                        'activity' => $user->activities()->latest()->take(10)->get()
                    ]
                ];
            } else {
                // Get overall user statistics
                $totalUsers = User::count();
                $newUsers = User::where('created_at', '>=', now()->subDays(30))->count();
                $usersByRole = User::select('role', DB::raw('count(*) as count'))
                    ->groupBy('role')
                    ->get();
                
                return [
                    'status' => 'success',
                    'data' => [
                        'total_users' => $totalUsers,
                        'new_users_30d' => $newUsers,
                        'users_by_role' => $usersByRole,
                        'recent_users' => User::latest()->take(10)->get()
                    ]
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error in user management', [
                'error' => $e->getMessage(),
                'entities' => $entities
            ]);
            
            return [
                'status' => 'error',
                'message' => 'Error processing user management command: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Handle content management commands
     *
     * @param array $entities
     * @return array
     */
    private function handleContentManagement($entities)
    {
        try {
            if (isset($entities['article_id'])) {
                // Get specific article details
                $article = Article::find($entities['article_id']);
                
                if (!$article) {
                    return [
                        'status' => 'error',
                        'message' => 'Article not found'
                    ];
                }
                
                return [
                    'status' => 'success',
                    'data' => [
                        'article' => $article,
                        'metrics' => $article->metrics,
                        'author' => $article->author
                    ]
                ];
            } else {
                // Get overall content statistics
                $totalArticles = Article::count();
                $publishedArticles = Article::where('status', 'published')->count();
                $draftArticles = Article::where('status', 'draft')->count();
                $topArticles = Article::orderBy('views', 'desc')->take(5)->get();
                
                return [
                    'status' => 'success',
                    'data' => [
                        'total_articles' => $totalArticles,
                        'published_articles' => $publishedArticles,
                        'draft_articles' => $draftArticles,
                        'top_articles' => $topArticles,
                        'recent_articles' => Article::latest()->take(10)->get()
                    ]
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error in content management', [
                'error' => $e->getMessage(),
                'entities' => $entities
            ]);
            
            return [
                'status' => 'error',
                'message' => 'Error processing content management command: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Handle license management commands
     *
     * @param array $entities
     * @return array
     */
    private function handleLicenseManagement($entities)
    {
        try {
            if (isset($entities['license_id'])) {
                // Get specific license details
                $license = License::find($entities['license_id']);
                
                if (!$license) {
                    return [
                        'status' => 'error',
                        'message' => 'License not found'
                    ];
                }
                
                return [
                    'status' => 'success',
                    'data' => [
                        'license' => $license,
                        'user' => $license->user,
                        'transactions' => $license->transactions
                    ]
                ];
            } else {
                // Get overall license statistics
                $totalLicenses = License::count();
                $activeLicenses = License::where('status', 'active')->count();
                $expiredLicenses = License::where('status', 'expired')->count();
                $revenueThisMonth = License::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('amount');
                
                return [
                    'status' => 'success',
                    'data' => [
                        'total_licenses' => $totalLicenses,
                        'active_licenses' => $activeLicenses,
                        'expired_licenses' => $expiredLicenses,
                        'revenue_this_month' => $revenueThisMonth,
                        'recent_licenses' => License::latest()->take(10)->get()
                    ]
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error in license management', [
                'error' => $e->getMessage(),
                'entities' => $entities
            ]);
            
            return [
                'status' => 'error',
                'message' => 'Error processing license management command: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get system analytics
     *
     * @param array $entities
     * @return array
     */
    private function getSystemAnalytics($entities)
    {
        try {
            $dateFrom = isset($entities['date_range']['from']) 
                ? $entities['date_range']['from'] 
                : now()->subDays(30)->format('Y-m-d');
                
            $dateTo = isset($entities['date_range']['to']) 
                ? $entities['date_range']['to'] 
                : now()->format('Y-m-d');
            
            // Get user growth
            $userGrowth = User::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->groupBy('date')
                ->get();
            
            // Get revenue data
            $revenueData = License::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(amount) as total')
                )
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->groupBy('date')
                ->get();
            
            // Get content metrics
            $contentMetrics = Article::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereBetween('created_at', [$dateFrom, $dateTo])
                ->groupBy('date')
                ->get();
            
            // Calculate conversion rates
            $totalVisitors = DB::table('website_metrics')
                ->whereBetween('date', [$dateFrom, $dateTo])
                ->sum('visitors');
                
            $newUsers = User::whereBetween('created_at', [$dateFrom, $dateTo])->count();
            $conversionRate = $totalVisitors > 0 ? ($newUsers / $totalVisitors) * 100 : 0;
            
            return [
                'status' => 'success',
                'data' => [
                    'date_range' => [
                        'from' => $dateFrom,
                        'to' => $dateTo
                    ],
                    'user_growth' => $userGrowth,
                    'revenue_data' => $revenueData,
                    'content_metrics' => $contentMetrics,
                    'conversion' => [
                        'visitors' => $totalVisitors,
                        'new_users' => $newUsers,
                        'rate' => round($conversionRate, 2)
                    ]
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error getting system analytics', [
                'error' => $e->getMessage(),
                'entities' => $entities
            ]);
            
            return [
                'status' => 'error',
                'message' => 'Error retrieving system analytics: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Perform security audit
     *
     * @return array
     */
    private function performSecurityAudit()
    {
        try {
            // Check for failed login attempts
            $failedLogins = DB::table('login_attempts')
                ->where('status', 'failed')
                ->where('created_at', '>=', now()->subDays(7))
                ->count();
            
            // Check for suspicious activities
            $suspiciousActivities = DB::table('activity_logs')
                ->where('type', 'suspicious')
                ->where('created_at', '>=', now()->subDays(7))
                ->get();
            
            // Check for admin access
            $adminAccesses = DB::table('activity_logs')
                ->where('action', 'admin_login')
                ->where('created_at', '>=', now()->subDays(7))
                ->get();
            
            // Check for permission changes
            $permissionChanges = DB::table('activity_logs')
                ->where('action', 'permission_change')
                ->where('created_at', '>=', now()->subDays(7))
                ->get();
            
            return [
                'status' => 'success',
                'data' => [
                    'failed_logins' => $failedLogins,
                    'suspicious_activities' => $suspiciousActivities,
                    'admin_accesses' => $adminAccesses,
                    'permission_changes' => $permissionChanges,
                    'recommendations' => [
                        'Enable two-factor authentication for all admin users',
                        'Review user permissions regularly',
                        'Monitor failed login attempts',
                        'Implement IP-based access restrictions for admin panel'
                    ]
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error performing security audit', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'status' => 'error',
                'message' => 'Error performing security audit: ' . $e->getMessage()
            ];
        }
    }
}
