<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PaymentManagementController;

// Payment Management Routes (Super Admin Only)
Route::middleware(['auth', 'superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/payments', [PaymentManagementController::class, 'index'])->name('payments.index');
    Route::get('/payments/{id}', [PaymentManagementController::class, 'show'])->name('payments.show');
    Route::post('/payments/{id}/update-status', [PaymentManagementController::class, 'updateStatus'])->name('payments.update-status');
    Route::post('/payments/{id}/refund', [PaymentManagementController::class, 'processRefund'])->name('payments.refund');
    Route::get('/payments-analytics', [PaymentManagementController::class, 'analytics'])->name('payments.analytics');
    Route::post('/payments-export', [PaymentManagementController::class, 'export'])->name('payments.export');
});
