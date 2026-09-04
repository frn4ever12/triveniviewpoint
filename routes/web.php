<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Mobile\DashboardController as MobileDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Mobile/PWA Login Route
Route::get('/mobile/login', function() {
    if (auth()->check()) {
        return redirect()->route('mobile.dashboard');
    }
    return view('auth.mobile-login');
})->name('mobile.login');

// Mobile Dashboard Route
Route::get('/mobile/dashboard', [MobileDashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('mobile.dashboard');

// Multi-tenant routes
Route::get('/restaurant/{slug}', [HomeController::class, 'tenant'])->name('tenant.show');
Route::get('/digitalmenu/{slug}', [HomeController::class, 'digitalmenu'])->name('digitalmenu');
Route::get('/digitalmenu/{slug}/{table}', [HomeController::class, 'digitalmenuTable'])->name('digitalmenu-table');

// Checkout Route
Route::post('/checkout/process/{table?}', [CheckoutController::class, 'process'])->name('checkout.process');
// Order Routes
Route::prefix('order')->name('order.')->group(function () {
    Route::get('/{order}/confirmation', [CheckoutController::class, 'confirmation'])->name('confirmation');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified','can:dashboard.view'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/admin/tables/json', [App\Http\Controllers\Admin\TableController::class, 'getTablesJson'])->name('admin.tables.json');

Route::get('/admin/billing', [App\Http\Controllers\Admin\BillingController::class, 'index'])->name('admin.billing.index');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

require __DIR__.'/auth.php';
