<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Webhook
use App\Http\Controllers\Webhook\UserStripeWebhookController;

// Public API Controllers
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogApiController;
use App\Http\Controllers\Api\AiToolApiController;
use App\Http\Controllers\API\SubscriptionController;

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
use App\Http\Controllers\Api\DomainAnalysisController; // Added Domain Analysis Controller

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

// 🔓 Public Authentication Routes
Route::post("/auth/register", [AuthController::class, "register"]);
Route::post("/auth/login", [AuthController::class, "login"]);
Route::post("/auth/forgot-password", [AuthController::class, "forgotPassword"]);
Route::post("/auth/reset-password", [AuthController::class, "resetPassword"]);

// 🔓 Public Subscription Routes
Route::get("/plans", [SubscriptionController::class, "listPlans"]);

// 🔓 Public AI Tools & Blog
Route::get("/ai-tools", [AiToolApiController::class, "index"]);
Route::get("/blogs", [BlogApiController::class, "index"]);
Route::get("/blogs/{slug}", [BlogApiController::class, "show"]);

// 🔒 Protected Routes (Authenticated via Sanctum)
Route::middleware("auth:sanctum")->group(function () {
    // Auth
    Route::post("/auth/logout", [AuthController::class, "logout"]);
    Route::get("/user/profile", [AuthController::class, "getProfile"]);
    Route::put("/user/profile", [AuthController::class, "updateProfile"]);
    Route::get("/user", fn(Request $request) => $request->user());

    // Subscriptions
    Route::get("/subscription", [SubscriptionController::class, "getStatus"]);
    Route::post("/subscription", [SubscriptionController::class, "create"]);
    Route::delete("/subscription", [SubscriptionController::class, "cancel"]);

    // License
    Route::post("/license/validate", [LicenseController::class, "validateLicense"]);
    Route::get("/license/status", [LicenseController::class, "getStatus"]);

    // Website
    Route::post("/websites", [WebsiteController::class, "store"]);
    Route::get("/websites/{website}/metrics", [WebsiteController::class, "getMetrics"]); // Changed {website_id} to {website} for model binding

    // Domain Analysis (New)
    Route::get("/websites/{website}/analysis", [DomainAnalysisController::class, "getAnalysis"]);

    // Website Design
    Route::post("/websites/{website}/design", [WebsiteDesignController::class, "storeOrUpdate"]); // Changed {website_id} to {website}

    // Content Plan
    Route::post("/websites/{website}/content-plan", [ContentPlanController::class, "store"]); // Changed {website_id} to {website}

    // Content
    Route::post("/websites/{website}/content", [ContentController::class, "store"]); // Changed {website_id} to {website}
    Route::post("/websites/{website}/content/publish", [ContentController::class, "publish"]); // Changed {website_id} to {website}

    // RSS Feeds
    Route::post("/websites/{website}/rss-feeds", [RssFeedController::class, "store"]); // Changed {website_id} to {website}

    // Performance
    Route::get("/websites/{website}/performance", [PerformanceController::class, "getPerformance"]); // Changed {website_id} to {website}

    // Link Building
    Route::post("/websites/{website}/links", [LinkBuildingController::class, "store"]); // Changed {website_id} to {website}

    // Social Media
    Route::post("/websites/{website}/social-posts", [SocialMediaController::class, "store"]); // Changed {website_id} to {website}

    // Newsletters
    Route::post("/websites/{website}/newsletters", [NewsletterController::class, "store"]); // Changed {website_id} to {website}

    // Chatbot
    Route::post("/websites/{website}/chatbot/log", [ChatbotController::class, "logInteraction"]); // Changed {website_id} to {website}

    // Monitored API
    Route::get("/monitoring/api-check", [MonitoredApiController::class, "index"]);
});

// 🛡️ Admin Routes
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

    // Blogs
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

// 💳 Stripe Webhook
Route::post("/stripe/webhook", [UserStripeWebhookController::class, "handle"]);




    // External API Keys (User-Managed)
    Route::get("/user/api-keys", [\App\Http\Controllers\Api\ExternalApiKeyController::class, "index"]);
    Route::post("/user/api-keys", [\App\Http\Controllers\Api\ExternalApiKeyController::class, "store"]);
    Route::delete("/user/api-keys/{provider}", [\App\Http\Controllers\Api\ExternalApiKeyController::class, "destroy"]);




