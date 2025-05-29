<?php

namespace App\Services\AIAgent;

use App\Models\User;
use App\Models\Website;
use App\Models\Article;
use App\Models\License;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AIAgentAutomationService
{
    /**
     * Automatically optimize database performance
     *
     * @return array
     */
    public function optimizeDatabasePerformance()
    {
        try {
            $results = [];
            
            // Run analyze tables
            $tables = DB::select('SHOW TABLES');
            foreach ($tables as $table) {
                $tableName = array_values(get_object_vars($table))[0];
                DB::statement("ANALYZE TABLE {$tableName}");
                $results[] = "Analyzed table: {$tableName}";
            }
            
            // Run optimize tables
            foreach ($tables as $table) {
                $tableName = array_values(get_object_vars($table))[0];
                DB::statement("OPTIMIZE TABLE {$tableName}");
                $results[] = "Optimized table: {$tableName}";
            }
            
            return [
                'status' => 'success',
                'message' => 'Database optimization completed successfully',
                'data' => [
                    'operations' => $results
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error optimizing database', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'status' => 'error',
                'message' => 'Error optimizing database: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Automatically clean up old logs and temporary data
     *
     * @param int $days Number of days to keep logs
     * @return array
     */
    public function cleanupOldData($days = 30)
    {
        try {
            $results = [];
            $cutoffDate = now()->subDays($days);
            
            // Clean up old logs
            $deletedLogs = DB::table('activity_logs')
                ->where('created_at', '<', $cutoffDate)
                ->delete();
            $results[] = "Deleted {$deletedLogs} old activity logs";
            
            // Clean up old failed jobs
            $deletedJobs = DB::table('failed_jobs')
                ->where('failed_at', '<', $cutoffDate)
                ->delete();
            $results[] = "Deleted {$deletedJobs} old failed jobs";
            
            // Clean up old notifications
            $deletedNotifications = DB::table('notifications')
                ->where('created_at', '<', $cutoffDate)
                ->delete();
            $results[] = "Deleted {$deletedNotifications} old notifications";
            
            // Clean up old password resets
            $deletedResets = DB::table('password_reset_tokens')
                ->where('created_at', '<', $cutoffDate)
                ->delete();
            $results[] = "Deleted {$deletedResets} old password reset tokens";
            
            return [
                'status' => 'success',
                'message' => 'Data cleanup completed successfully',
                'data' => [
                    'operations' => $results
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error cleaning up old data', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'status' => 'error',
                'message' => 'Error cleaning up old data: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Automatically generate system reports
     *
     * @param string $reportType Type of report to generate
     * @return array
     */
    public function generateSystemReport($reportType = 'all')
    {
        try {
            $data = [];
            
            if ($reportType === 'all' || $reportType === 'users') {
                $data['users'] = [
                    'total' => User::count(),
                    'active_last_30_days' => User::where('last_login_at', '>=', now()->subDays(30))->count(),
                    'new_last_30_days' => User::where('created_at', '>=', now()->subDays(30))->count(),
                    'by_role' => User::select('role', DB::raw('count(*) as count'))
                        ->groupBy('role')
                        ->get()
                ];
            }
            
            if ($reportType === 'all' || $reportType === 'content') {
                $data['content'] = [
                    'total_articles' => Article::count(),
                    'published' => Article::where('status', 'published')->count(),
                    'draft' => Article::where('status', 'draft')->count(),
                    'new_last_30_days' => Article::where('created_at', '>=', now()->subDays(30))->count()
                ];
            }
            
            if ($reportType === 'all' || $reportType === 'websites') {
                $data['websites'] = [
                    'total' => Website::count(),
                    'active' => Website::where('status', 'active')->count(),
                    'inactive' => Website::where('status', 'inactive')->count(),
                    'new_last_30_days' => Website::where('created_at', '>=', now()->subDays(30))->count()
                ];
            }
            
            if ($reportType === 'all' || $reportType === 'licenses') {
                $data['licenses'] = [
                    'total' => License::count(),
                    'active' => License::where('status', 'active')->count(),
                    'expired' => License::where('status', 'expired')->count(),
                    'revenue_last_30_days' => License::where('created_at', '>=', now()->subDays(30))->sum('amount')
                ];
            }
            
            if ($reportType === 'all' || $reportType === 'performance') {
                // Get database size
                $dbSize = DB::select('SELECT SUM(data_length + index_length) / 1024 / 1024 AS size_mb FROM information_schema.tables WHERE table_schema = DATABASE()');
                
                $data['performance'] = [
                    'database_size_mb' => round($dbSize[0]->size_mb, 2),
                    'average_query_time_ms' => DB::table('slow_query_log')
                        ->where('created_at', '>=', now()->subDays(7))
                        ->avg('execution_time') ?? 0,
                    'slow_queries_last_7_days' => DB::table('slow_query_log')
                        ->where('created_at', '>=', now()->subDays(7))
                        ->count()
                ];
            }
            
            return [
                'status' => 'success',
                'message' => 'System report generated successfully',
                'data' => $data
            ];
        } catch (\Exception $e) {
            Log::error('Error generating system report', [
                'error' => $e->getMessage(),
                'report_type' => $reportType
            ]);
            
            return [
                'status' => 'error',
                'message' => 'Error generating system report: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Automatically detect and fix common issues
     *
     * @return array
     */
    public function detectAndFixIssues()
    {
        try {
            $issues = [];
            $fixes = [];
            
            // Check for orphaned records
            $orphanedLicenses = License::whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('users')
                    ->whereRaw('users.id = licenses.user_id');
            })->count();
            
            if ($orphanedLicenses > 0) {
                $issues[] = "Found {$orphanedLicenses} orphaned licenses";
                
                // Fix orphaned licenses by setting them to expired
                License::whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('users')
                        ->whereRaw('users.id = licenses.user_id');
                })->update(['status' => 'expired']);
                
                $fixes[] = "Set {$orphanedLicenses} orphaned licenses to expired status";
            }
            
            // Check for missing user roles
            $usersWithoutRoles = User::whereNull('role')->count();
            
            if ($usersWithoutRoles > 0) {
                $issues[] = "Found {$usersWithoutRoles} users without roles";
                
                // Fix users without roles by setting default role
                User::whereNull('role')->update(['role' => 'user']);
                
                $fixes[] = "Set default role for {$usersWithoutRoles} users";
            }
            
            // Check for duplicate email addresses
            $duplicateEmails = DB::select('
                SELECT email, COUNT(*) as count
                FROM users
                GROUP BY email
                HAVING COUNT(*) > 1
            ');
            
            if (count($duplicateEmails) > 0) {
                foreach ($duplicateEmails as $duplicate) {
                    $issues[] = "Found {$duplicate->count} users with duplicate email: {$duplicate->email}";
                    
                    // Keep the oldest account and mark others as duplicates
                    $users = User::where('email', $duplicate->email)
                        ->orderBy('created_at')
                        ->get();
                    
                    $originalUser = $users->shift();
                    
                    foreach ($users as $duplicateUser) {
                        $duplicateUser->email = "duplicate_{$duplicateUser->id}_{$duplicateUser->email}";
                        $duplicateUser->save();
                        $fixes[] = "Renamed duplicate user {$duplicateUser->id} email to {$duplicateUser->email}";
                    }
                }
            }
            
            return [
                'status' => 'success',
                'message' => count($issues) > 0 ? 'Issues detected and fixed' : 'No issues detected',
                'data' => [
                    'issues_detected' => $issues,
                    'fixes_applied' => $fixes
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error detecting and fixing issues', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'status' => 'error',
                'message' => 'Error detecting and fixing issues: ' . $e->getMessage()
            ];
        }
    }
}
