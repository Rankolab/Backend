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

// ✅ Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// 📈 Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('websites', WebsiteController::class);
    Route::resource('blogs', BlogController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('tags', TagController::class);
    Route::resource('plans', PlanController::class);
    Route::resource('subscriptions', SubscriptionController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('licenses', LicenseController::class);
    Route::resource('affiliates', AffiliateController::class);
    Route::resource('commissions', CommissionController::class);
    Route::resource('referrals', ReferralController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('purchases', PurchaseController::class);

    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');

    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity_logs.index');
    Route::get('api-logs', [ApiLogController::class, 'index'])->name('apilogs.index');
});

// 🧠 Super Admin Routes
Route::middleware(['auth', 'superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('super-agent', [SuperAdminController::class, 'superAgentView'])->name('super.agent');
    Route::post('super-agent/ask', [SuperAdminController::class, 'handleSuperAgentRequest'])->name('super.agent.ask');
});

// 🤖 AI Agent Routes
Route::prefix('ai-agent')->name('aiagent.')->middleware(['auth', 'superadmin'])->group(function () {
    Route::get('/', [AIAgentController::class, 'index'])->name('index');
    Route::get('/licenses', [AIAgentController::class, 'licenseManagement'])->name('licenses');
});

// 🧾 Stripe webhook
Route::post('/stripe/webhook', [AdminStripeWebhookController::class, 'handle']);

// 🔐 Auth
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.perform');

// 👤 User Dashboard
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard.index');
});

// 🌐 Frontend Routes
Route::get('/blog', [FrontendBlogController::class, 'index'])->name('blog.index');
Route::get('/tools', [FrontendAiToolController::class, 'index'])->name('tools.index');
Route::get('/sitemap.xml', [SitemapController::class, 'index']);

Auth::routes();

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
