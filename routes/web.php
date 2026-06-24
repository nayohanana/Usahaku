<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Auth routes
require __DIR__.'/auth.php';

// ============================================
// 🔐 FORGOT PASSWORD WITH OTP
// ============================================
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'sendOtp'])->name('password.send-otp');
Route::get('/verify-otp', [ForgotPasswordController::class, 'showVerifyForm'])->name('password.verify');
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verify-otp');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// Protected routes
Route::middleware(['auth'])->group(function () {
    
    // ============================================
    // DASHBOARD
    // ============================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // ============================================
    // PRODUCTS
    // ============================================
    Route::resource('products', ProductController::class);
    Route::get('/products/{product}/barcode', [ProductController::class, 'barcode'])->name('products.barcode');
    
    // ============================================
    // SUPPLIERS & STOCK INS
    // ============================================
    Route::resource('suppliers', SupplierController::class);
    Route::resource('stock-ins', StockInController::class);
    Route::get('/stock-ins/{stockIn}/print', [StockInController::class, 'print'])->name('stock-ins.print');
    
    // ============================================
    // SALES
    // ============================================
    Route::resource('sales', SaleController::class);
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales/store', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');
    Route::post('/sales/search-product', [SaleController::class, 'searchProduct'])->name('sales.search-product');
    
    // ============================================
    // EXPENSES
    // ============================================
    Route::resource('expenses', ExpenseController::class);
    
    // ============================================
    // REPORTS
    // ============================================
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/expenses', [ReportController::class, 'expenses'])->name('reports.expenses');
    Route::get('/reports/profit', [ReportController::class, 'profit'])->name('reports.profit');
    
    Route::get('/reports/export/sales-pdf', [ReportController::class, 'exportSalesPdf'])->name('reports.export.sales-pdf');
    Route::get('/reports/export/expenses-pdf', [ReportController::class, 'exportExpensesPdf'])->name('reports.export.expenses-pdf');
    Route::get('/reports/export/profit-pdf', [ReportController::class, 'exportProfitPdf'])->name('reports.export.profit-pdf');
    
    Route::get('/reports/export/sales-excel', [ReportController::class, 'exportSalesExcel'])->name('reports.export.sales-excel');
    Route::get('/reports/export/expenses-excel', [ReportController::class, 'exportExpensesExcel'])->name('reports.export.expenses-excel');
    Route::get('/reports/export/profit-excel', [ReportController::class, 'exportProfitExcel'])->name('reports.export.profit-excel');

    // ============================================
    // SETTINGS
    // ============================================
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/update-profile', [SettingController::class, 'updateProfile'])->name('settings.update-profile');
    Route::get('/settings/backup', [SettingController::class, 'backup'])->name('settings.backup');
    Route::get('/settings/clear-cache', [SettingController::class, 'clearCache'])->name('settings.clear-cache');

    // ============================================
    // NOTIFICATIONS
    // ============================================
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [NotificationController::class, 'getUnread'])->name('notifications.unread');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

    // ============================================
    // PROFILE
    // ============================================
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
});