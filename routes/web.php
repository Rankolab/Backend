<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\WebsiteController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\AiToolController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\PayoutController;
use App\Http\Controllers\Admin\ApiLogController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Admin\AIAgentController;
use App\Http\Controllers\Admin\ApiController;
use App\Http\Controllers\Admin\AffiliateController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\ReferralController;
use App\Http\Controllers\Admin\AdminStripeWebhookController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\User\UserAffiliateController;
use App\Http\Controllers\User\LicensePurchaseController;
use App\Http\Controllers\User\UserDashboard;
use App\Http\Controllers\Frontend\FrontendBlogController;
use App\Http\Controllers\Frontend\FrontendAiToolController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ActivityLogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// Redirect root to login page
Route::get("/", function () {
    return redirect()->route("login");
});

// Admin Routes
Route::middleware(["auth", "admin"])->prefix("admin")->name("admin.")->group(function () {
    Route::get("/dashboard", [DashboardController::class, "index"])->name("dashboard");
    Route::get("/dashboard/revenue", [DashboardController::class, "getRevenueData"])->name("dashboard.revenue");
    Route::get("/dashboard/users", [DashboardController::class, "getUserGrowth"])->name("dashboard.users.growth");
    Route::get("/dashboard/traffic", [DashboardController::class, "getTrafficSources"])->name("dashboard.traffic");
    Route::get("/dashboard/system-health", [DashboardController::class, "getSystemHealthData"])->name("dashboard.system_health");
    Route::post("/dashboard/settings", [DashboardController::class, "saveDashboardSettings"])->name("dashboard.settings.save");

    // User Management with Bulk Action
    Route::post("users/bulk-action", [UserController::class, "bulkAction"])->name("users.bulk_action");
    Route::resource("users", UserController::class);

    Route::resource("websites", WebsiteController::class);
    
    // Blog Management Routes
    Route::post("blogs/{blog}/generate-image", [BlogController::class, "generateImage"])->name("blogs.generate_image"); // Added route for image generation
    Route::resource("blogs", BlogController::class);
    Route::resource("categories", CategoryController::class); 
    Route::resource("tags", TagController::class); 

    Route::resource("purchases", PurchaseController::class);
    Route::resource("aitools", AiToolController::class);
    // Route::resource("articles", ArticleController::class);
    Route::get("payouts", [PayoutController::class, "index"])->name("payouts.index");
    Route::post("payouts/{payout}/update", [PayoutController::class, "updateStatus"])->name("payouts.update");
    Route::get("api-logs", [ApiLogController::class, "index"])->name("apilogs.index");
    Route::get("notifications", [NotificationController::class, "index"])->name("notifications.index");
    Route::post("notifications/{notification}/read", [NotificationController::class, "markAsRead"])->name("notifications.read");
    Route::resource("licenses", LicenseController::class);

    // Roles & Permissions Routes
    Route::resource("roles", RoleController::class);
    Route::resource("permissions", PermissionController::class)->only(["index", "create", "store", "destroy"]);
    Route::post("roles/{role}/permissions", [RoleController::class, "assignPermissions"])->name("roles.permissions.assign");

    // Activity Log Routes
    Route::get("activity-logs", [ActivityLogController::class, "index"])->name("activity_logs.index");
    Route::get("activity-logs/{activity}", [ActivityLogController::class, "show"])->name("activity_logs.show");

    // Analytics
    Route::get("/analytics", [AnalyticsController::class, "index"])->name("analytics.index");

    // Settings
    Route::get("/settings", [SettingsController::class, "index"])->name("settings.index");
    Route::post("/settings", [SettingsController::class, "update"])->name("settings.update");

    // Admin API Settings (Nested under /admin/api)
    Route::prefix("api")->name("api.")->group(function () {
        Route::get("/monitoring", [ApiController::class, "monitoring"])->name("monitoring");
        Route::get("/keys", [ApiController::class, "keys"])->name("keys");
        Route::post("/keys/update", [ApiController::class, "updateKeys"])->name("keys.update");
        Route::get("/logs", [ApiController::class, "logs"])->name("logs");
        Route::get("/analytics", [ApiController::class, "analytics"])->name("analytics");
    });

    // Payment Management Routes
    Route::resource("plans", PlanController::class);
    Route::resource("subscriptions", SubscriptionController::class)->only(["index", "show", "destroy"]);
    Route::resource("payments", PaymentController::class)->only(["index", "show"]);

    // Affiliate Management Routes
    Route::resource("affiliates", AffiliateController::class);
    Route::resource("commissions", CommissionController::class)->only(["index", "show", "update"]); // Typically approve/reject/mark paid
    Route::resource("referrals", ReferralController::class)->only(["index", "show"]); // Typically read-only

}); // End of admin route group

// Super Admin Routes
Route::middleware(["auth", "superadmin"])->prefix("admin")->name("admin.")->group(function () {
    Route::get("/super-agent", [SuperAdminController::class, "superAgentView"])->name("super.agent");
    Route::post("/super-agent/ask", [SuperAdminController::class, "handleSuperAgentRequest"])->name("super.agent.ask");
    Route::get("/delegation", [SuperAdminController::class, "delegationView"])->name("delegation");
    Route::patch("/delegation/{id}", [SuperAdminController::class, "updateAdminRole"])->name("updateRole");
});

// AI Agent Routes
Route::prefix("ai-agent")->name("aiagent.")->middleware(["auth", "superadmin"])->group(function () {
    Route::get("/", [AIAgentController::class, "index"])->name("index");
    Route::post("/process-command", [AIAgentController::class, "processCommand"])->name("process-command");
    Route::get("/users", [AIAgentController::class, "userManagement"])->name("users");
    Route::get("/content", [AIAgentController::class, "contentManagement"])->name("content");
    Route::get("/licenses", [AIAgentController::class, "licenseManagement"])->name("licenses");
    Route::get("/analytics", [AIAgentController::class, "analytics"])->name("analytics");
    Route::get("/security", [AIAgentController::class, "securityAudit"])->name("security");
    Route::post("/optimization", [AIAgentController::class, "runOptimization"])->name("optimization");
    Route::post("/report", [AIAgentController::class, "generateReport"])->name("report");
});

// Auth & Registration
Route::get("/register", [RegisterController::class, "showForm"])->name("register");
Route::post("/register", [RegisterController::class, "register"])->name("register.perform");

// Stripe Webhook
Route::post("/stripe/webhook", [AdminStripeWebhookController::class, "handle"]);

// Authenticated User Routes
Route::middleware(["auth"])->group(function () {
    Route::get("/user/affiliate", [UserAffiliateController::class, "index"])->name("user.affiliate");
    Route::get("/licenses/plans", [LicensePurchaseController::class, "showPlans"])->name("licenses.plans");
    Route::get("/licenses/success", [LicensePurchaseController::class, "success"])->name("license.success");
    Route::get("/licenses/cancel", [LicensePurchaseController::class, "cancel"])->name("license.cancel");
    Route::post("/licenses/checkout", [LicensePurchaseController::class, "createCheckout"])->name("license.checkout");

    // User Dashboard
    Route::prefix("user")->name("user.")->group(function () {
        Route::get("/dashboard", [UserDashboard::class, "index"])->name("dashboard.index");
    });
});

// Frontend Blog
Route::get("/blog", [FrontendBlogController::class, "index"])->name("blog.index");
Route::get("/blog/{slug}", [FrontendBlogController::class, "show"])->name("blog.show");

// Frontend Tools
Route::get("/tools", [FrontendAiToolController::class, "index"])->name("tools.index");

// Sitemap
Route::get("/sitemap.xml", [SitemapController::class, "index"]);

Auth::routes();





Route::middleware(["auth", "admin"])->prefix("admin")->name("admin.")->group(function () {
    // ... other admin routes ...

    // Setup Wizard Routes
    Route::get("setup-wizard", [\App\Http\Controllers\Admin\SetupWizardController::class, "index"])->name("setup.wizard.index");
    Route::post("setup-wizard/step", [\App\Http\Controllers\Admin\SetupWizardController::class, "processStep"])->name("setup.wizard.step");
    Route::get("setup-wizard/complete", [\App\Http\Controllers\Admin\SetupWizardController::class, "complete"])->name("setup.wizard.complete");

    // ... other admin routes ...
});




Route::middleware(["auth", "admin"])->prefix("admin")->name("admin.")->group(function () {
    // ... other admin routes ...

    // Domain Analysis Routes
    Route::get("domain-analysis", [\App\Http\Controllers\Admin\DomainAnalysisController::class, "index"])->name("domain.analysis.index");
    Route::post("domain-analysis/analyze", [\App\Http\Controllers\Admin\DomainAnalysisController::class, "analyze"])->name("domain.analysis.analyze");
    Route::get("domain-analysis/results/{website_id}", [\App\Http\Controllers\Admin\DomainAnalysisController::class, "showResults"])->name("domain.analysis.results"); // Example route for showing results

    // ... other admin routes ...
});




Route::middleware(["auth", "admin"])->prefix("admin")->name("admin.")->group(function () {
    // ... other admin routes ...

    // Content Generation Routes
    Route::get("content-generation", [\App\Http\Controllers\Admin\ContentGenerationController::class, "index"])->name("content.generation.index");
    Route::post("content-generation/generate", [\App\Http\Controllers\Admin\ContentGenerationController::class, "generate"])->name("content.generation.generate");
    // Add routes for viewing history or specific generated content if needed

    // ... other admin routes ...
});




Route::middleware(["auth", "admin"])->prefix("admin")->name("admin.")->group(function () {
    // ... other admin routes ...

    // SEO Optimization Routes
    Route::get("seo-optimization", [\App\Http\Controllers\Admin\SeoOptimizationController::class, "index"])->name("seo.optimization.index");
    Route::post("seo-optimization/analyze", [\App\Http\Controllers\Admin\SeoOptimizationController::class, "analyze"])->name("seo.optimization.analyze");
    // Add routes for specific reports or actions

    // ... other admin routes ...
});




Route::middleware(["auth", "admin"])->prefix("admin")->name("admin.")->group(function () {
    // ... other admin routes ...

    // Niche Suggestion Routes
    Route::get("niche-suggestion", [\App\Http\Controllers\Admin\NicheSuggestionController::class, "index"])->name("niche.suggestion.index");
    Route::post("niche-suggestion/suggest", [\App\Http\Controllers\Admin\NicheSuggestionController::class, "suggest"])->name("niche.suggestion.suggest");
    // Add routes for viewing specific suggestions or history if needed

    // ... other admin routes ...
});

