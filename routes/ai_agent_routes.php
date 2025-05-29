<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AIAgentController;

// AI Agent Routes - Super Admin Only
Route::prefix('admin/ai-agent')->name('admin.aiagent.')->middleware(['auth', 'superadmin'])->group(function () {
    Route::get('/', [AIAgentController::class, 'index'])->name('index');
    Route::post('/process-command', [AIAgentController::class, 'processCommand'])->name('process-command');
    Route::get('/users', [AIAgentController::class, 'userManagement'])->name('users');
    Route::get('/content', [AIAgentController::class, 'contentManagement'])->name('content');
    Route::get('/licenses', [AIAgentController::class, 'licenseManagement'])->name('licenses');
    Route::get('/analytics', [AIAgentController::class, 'analytics'])->name('analytics');
    Route::get('/security', [AIAgentController::class, 'securityAudit'])->name('security');
});
