<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Webhook
use App\Http\Controllers\Webhook\UserStripeWebhookController;

// Public API Controllers
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogApiController;
use App\Http\Controllers\Api\AiToolApiController;

// Website & Content Modules
use App\Http\Controllers\Api\LicenseController;
use App\Http\Controllers\Api\WebsiteController;
use App\Http\Controllers\Api\WebsiteDesignController;
use App\Http\Controllers\Api\ContentPlanController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\RssFeedController;
use App\Http\Controllers\Api\PerformanceController;
use App\Http\Controllers\Api\LinkBuildingController;
use App\Http\Controllers\Api\SocialMediaController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\Api\MonitoredApiController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\LicenseController as AdminLicenseController;
use App\Http\Controllers\Admin\WebsiteController as AdminWebsiteController;
use App\Http\Controllers\Admin\ContentController as AdminContentController;
use App\Http\Controllers\Admin\MonitoringController as AdminMonitoringController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Authentication Routes
Route::post("/auth/register", [AuthController::class, "register"]);
Route::post("/auth/login", [AuthController::class, "login"]);

// Protected Routes (Authenticated via Sanctum)
Route::middleware("auth:sanctum")->group(function () {
    Route::post("/auth/logout", [AuthController::class, "logout"]);

    Route::get("/user", function (Request $request) {
        return $request->user();
    });

    // License
    Route::post("/license/validate", [LicenseController::class, "validateLicense"]);
    Route::get("/license/status", [LicenseController::class, "getStatus"]);

    // Website
    Route::post("/websites", [WebsiteController::class, "store"]);
    Route::get("/websites/{website_id}/metrics", [WebsiteController::class, "getMetrics"]);

    // Website Design
    Route::post("/websites/{website_id}/design", [WebsiteDesignController::class, "storeOrUpdate"]);

    // Content Plan
    Route::post("/websites/{website_id}/content-plan", [ContentPlanController::class, "store"]);

    // Content
    Route::post("/websites/{website_id}/content", [ContentController::class, "store"]);
    Route::post("/websites/{website_id}/content/publish", [ContentController::class, "publish"]);

    // RSS Feeds
    Route::post("/websites/{website_id}/rss-feeds", [RssFeedController::class, "store"]);

    // Performance
    Route::get("/websites/{website_id}/performance", [PerformanceController::class, "getPerformance"]);

    // Link Building
    Route::post("/websites/{website_id}/links", [LinkBuildingController::class, "store"]);

    // Social Media
    Route::post("/websites/{website_id}/social-posts", [SocialMediaController::class, "store"]);

    // Newsletters
    Route::post("/websites/{website_id}/newsletters", [NewsletterController::class, "store"]);

    // Chatbot
    Route::post("/websites/{website_id}/chatbot/log", [ChatbotController::class, "logInteraction"]);

    // Monitored API routes
    Route::get("/monitoring/api-check", [MonitoredApiController::class, "index"]);
});

// Admin Routes
Route::prefix("admin")->middleware(["auth:sanctum", "admin"])->group(function () {
    // Dashboard
    Route::get("/dashboard/stats", [AdminDashboardController::class, "getStats"]);
    Route::get("/dashboard/health", [AdminDashboardController::class, "getHealth"]);

    // Users
    Route::apiResource("users", AdminUserController::class);

    // Licenses
    Route::apiResource("licenses", AdminLicenseController::class);

    // Websites
    Route::get("websites", [AdminWebsiteController::class, "index"]);
    Route::get("websites/{website}", [AdminWebsiteController::class, "show"]);

    // Content
    Route::get("content", [AdminContentController::class, "index"]);
    Route::get("content/{content}", [AdminContentController::class, "show"]);

    // Blog Management
    Route::get("blogs", [AdminContentController::class, "indexBlogs"]);
    Route::post("blogs", [AdminContentController::class, "storeBlog"]);
    Route::get("blogs/{content}", [AdminContentController::class, "show"]);
    Route::put("blogs/{content}", [AdminContentController::class, "update"]);
    Route::delete("blogs/{content}", [AdminContentController::class, "destroy"]);

    // Monitoring
    Route::get("monitoring/logs", [AdminMonitoringController::class, "getApiLogs"]);
    Route::get("monitoring/jobs", [AdminMonitoringController::class, "getJobQueueStatus"]);
    Route::post("monitoring/jobs/retry/{failedJobId}", [AdminMonitoringController::class, "retryJob"]);
    Route::delete("monitoring/jobs/delete/{failedJobId}", [AdminMonitoringController::class, "deleteJob"]);
});

// AI Tool (Public)
Route::get('/ai-tools', [AiToolApiController::class, 'index']);

// Stripe Webhook
Route::post('/stripe/webhook', [UserStripeWebhookController::class, 'handle']);

// Blog Public
Route::get('/blogs', [BlogApiController::class, 'index']);
Route::get('/blogs/{slug}', [BlogApiController::class, 'show']);
