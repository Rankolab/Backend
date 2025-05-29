<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AIAgent\AIAgentService;
use App\Services\AIAgent\AIAgentAnalyticsService;
use App\Services\AIAgent\AIAgentAutomationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Http\Responses\ApiResponse;

class AIAgentController extends Controller
{
    protected $aiAgentService;
    protected $aiAgentAnalyticsService;
    protected $aiAgentAutomationService;
    
    /**
     * Create a new controller instance.
     *
     * @param AIAgentService $aiAgentService
     * @param AIAgentAnalyticsService $aiAgentAnalyticsService
     * @param AIAgentAutomationService $aiAgentAutomationService
     * @return void
     */
    public function __construct(
        AIAgentService $aiAgentService,
        AIAgentAnalyticsService $aiAgentAnalyticsService,
        AIAgentAutomationService $aiAgentAutomationService
    ) {
        $this->aiAgentService = $aiAgentService;
        $this->aiAgentAnalyticsService = $aiAgentAnalyticsService;
        $this->aiAgentAutomationService = $aiAgentAutomationService;
        $this->middleware('auth');
        $this->middleware('superadmin');
        $this->middleware('throttle:60,1');
    }
    
    /**
     * Display the AI Agent dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!$this->aiAgentService->hasSuperAdminAccess()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Access denied. Super Admin privileges required.');
        }
        
        // Get system status for initial dashboard display with caching
        $systemStatus = Cache::remember('ai.system.status', 3600, function () {
            return $this->aiAgentService->processCommand('get system status');
        });
        
        // Get predictive analytics with caching
        $userGrowthPrediction = Cache::remember('ai.analytics.user.growth', 3600, function () {
            return $this->aiAgentAnalyticsService->predictUserGrowth(3);
        });
        
        $revenueForecast = Cache::remember('ai.analytics.revenue.forecast', 3600, function () {
            return $this->aiAgentAnalyticsService->forecastRevenue(3);
        });
        
        return view('admin.aiagent.index', [
            'systemStatus' => $systemStatus['data'] ?? [],
            'userGrowthPrediction' => $userGrowthPrediction['data'] ?? [],
            'revenueForecast' => $revenueForecast['data'] ?? []
        ]);
    }
    
    /**
     * Process an AI Agent command.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processCommand(Request $request)
    {
        if (!$this->aiAgentService->hasSuperAdminAccess()) {
            return ApiResponse::forbidden('Access denied. Super Admin privileges required.');
        }
        
        $validator = Validator::make($request->all(), [
            'command' => 'required|string|max:500',
        ]);
        
        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }
        
        // Whitelist of allowed commands
        $allowedCommands = [
            'get system status',
            'get user management data',
            'get content management data',
            'get license management data',
            'get system analytics',
            'perform security audit'
        ];
        
        $command = $request->input('command');
        
        // Check if command is in the whitelist or starts with an allowed prefix
        $isAllowed = false;
        foreach ($allowedCommands as $allowedCommand) {
            if ($command === $allowedCommand || strpos($command, $allowedCommand . ' ') === 0) {
                $isAllowed = true;
                break;
            }
        }
        
        if (!$isAllowed) {
            return ApiResponse::forbidden('Command not allowed. Please use one of the predefined commands.');
        }
        
        $result = $this->aiAgentService->processCommand($command);
        
        // Log the command and result
        Log::info('AI Agent command processed', [
            'user_id' => Auth::id(),
            'command' => $command,
            'status' => $result['status']
        ]);
        
        return response()->json($result);
    }
    
    /**
     * Display the user management interface.
     *
     * @return \Illuminate\Http\Response
     */
    public function userManagement()
    {
        if (!$this->aiAgentService->hasSuperAdminAccess()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Access denied. Super Admin privileges required.');
        }
        
        // Cache user management data
        $userData = Cache::remember('ai.user.management.data', 1800, function () {
            return $this->aiAgentService->processCommand('get user management data');
        });
        
        $userGrowthPrediction = Cache::remember('ai.analytics.user.growth.6months', 3600, function () {
            return $this->aiAgentAnalyticsService->predictUserGrowth(6);
        });
        
        return view('admin.aiagent.users', [
            'userData' => $userData['data'] ?? [],
            'userGrowthPrediction' => $userGrowthPrediction['data'] ?? []
        ]);
    }
    
    /**
     * Display the content management interface.
     *
     * @return \Illuminate\Http\Response
     */
    public function contentManagement()
    {
        if (!$this->aiAgentService->hasSuperAdminAccess()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Access denied. Super Admin privileges required.');
        }
        
        // Cache content management data
        $contentData = Cache::remember('ai.content.management.data', 1800, function () {
            return $this->aiAgentService->processCommand('get content management data');
        });
        
        return view('admin.aiagent.content', [
            'contentData' => $contentData['data'] ?? []
        ]);
    }
    
    /**
     * Display the license management interface.
     *
     * @return \Illuminate\Http\Response
     */
    public function licenseManagement()
    {
        if (!$this->aiAgentService->hasSuperAdminAccess()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Access denied. Super Admin privileges required.');
        }
        
        // Cache license management data
        $licenseData = Cache::remember('ai.license.management.data', 1800, function () {
            return $this->aiAgentService->processCommand('get license management data');
        });
        
        $revenueForecast = Cache::remember('ai.analytics.revenue.forecast.6months', 3600, function () {
            return $this->aiAgentAnalyticsService->forecastRevenue(6);
        });
        
        return view('admin.aiagent.licenses', [
            'licenseData' => $licenseData['data'] ?? [],
            'revenueForecast' => $revenueForecast['data'] ?? []
        ]);
    }
    
    /**
     * Display the analytics dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function analytics()
    {
        if (!$this->aiAgentService->hasSuperAdminAccess()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Access denied. Super Admin privileges required.');
        }
        
        // Cache analytics data
        $analyticsData = Cache::remember('ai.system.analytics', 1800, function () {
            return $this->aiAgentService->processCommand('get system analytics');
        });
        
        $userGrowthPrediction = Cache::remember('ai.analytics.user.growth.6months', 3600, function () {
            return $this->aiAgentAnalyticsService->predictUserGrowth(6);
        });
        
        $revenueForecast = Cache::remember('ai.analytics.revenue.forecast.6months', 3600, function () {
            return $this->aiAgentAnalyticsService->forecastRevenue(6);
        });
        
        $healthReport = Cache::remember('ai.health.report', 3600, function () {
            return $this->aiAgentAnalyticsService->generateHealthReport();
        });
        
        return view('admin.aiagent.analytics', [
            'analyticsData' => $analyticsData['data'] ?? [],
            'userGrowthPrediction' => $userGrowthPrediction['data'] ?? [],
            'revenueForecast' => $revenueForecast['data'] ?? [],
            'healthReport' => $healthReport['data'] ?? []
        ]);
    }
    
    /**
     * Display the security audit interface.
     *
     * @return \Illuminate\Http\Response
     */
    public function securityAudit()
    {
        if (!$this->aiAgentService->hasSuperAdminAccess()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Access denied. Super Admin privileges required.');
        }
        
        // Cache security audit data
        $securityData = Cache::remember('ai.security.audit', 3600, function () {
            return $this->aiAgentService->processCommand('perform security audit');
        });
        
        $anomalies = Cache::remember('ai.security.anomalies', 1800, function () {
            return $this->aiAgentAnalyticsService->detectAnomalies();
        });
        
        return view('admin.aiagent.security', [
            'securityData' => $securityData['data'] ?? [],
            'anomalies' => $anomalies['data'] ?? []
        ]);
    }
    
    /**
     * Run system optimization.
     *
     * @return \Illuminate\Http\Response
     */
    public function runOptimization(Request $request)
    {
        if (!$this->aiAgentService->hasSuperAdminAccess()) {
            return ApiResponse::forbidden('Access denied. Super Admin privileges required.');
        }
        
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:database,cleanup,issues',
            'days' => 'sometimes|integer|min:1|max:365',
        ]);
        
        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }
        
        $type = $request->input('type', 'database');
        
        switch ($type) {
            case 'database':
                $result = $this->aiAgentAutomationService->optimizeDatabasePerformance();
                break;
            case 'cleanup':
                $days = $request->input('days', 30);
                $result = $this->aiAgentAutomationService->cleanupOldData($days);
                break;
            case 'issues':
                $result = $this->aiAgentAutomationService->detectAndFixIssues();
                break;
            default:
                return ApiResponse::error('Invalid optimization type');
        }
        
        // Log the optimization
        Log::info('AI Agent optimization run', [
            'user_id' => Auth::id(),
            'type' => $type,
            'status' => $result['status']
        ]);
        
        // Clear relevant caches
        Cache::forget('ai.system.status');
        Cache::forget('ai.health.report');
        
        return response()->json($result);
    }
    
    /**
     * Generate system report.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateReport(Request $request)
    {
        if (!$this->aiAgentService->hasSuperAdminAccess()) {
            return ApiResponse::forbidden('Access denied. Super Admin privileges required.');
        }
        
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:all,users,content,licenses,security',
        ]);
        
        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }
        
        $reportType = $request->input('type', 'all');
        $result = $this->aiAgentAutomationService->generateSystemReport($reportType);
        
        // Log the report generation
        Log::info('AI Agent report generated', [
            'user_id' => Auth::id(),
            'type' => $reportType,
            'status' => $result['status']
        ]);
        
        return response()->json($result);
    }
}
