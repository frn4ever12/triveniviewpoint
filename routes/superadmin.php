<?php

use App\Http\Controllers\Superadmin\DashboardController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\PlanFeatureController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\WebsiteSettingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Tenant Management
    Route::resource('tenants', TenantController::class);
    Route::post('tenants/{tenant}/approve', [TenantController::class, 'approve'])->name('tenants.approve');
    Route::post('tenants/{tenant}/reject', [TenantController::class, 'reject'])->name('tenants.reject');
    Route::post('tenants/{tenant}/suspend', [TenantController::class, 'suspend'])->name('tenants.suspend');
    Route::post('tenants/{tenant}/activate', [TenantController::class, 'activate'])->name('tenants.activate');
    
    // Subscription Plans
    Route::resource('subscription-plans', SubscriptionPlanController::class);
    
    // Subscriptions
    Route::resource('subscriptions', SubscriptionController::class);
    Route::post('subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::post('subscriptions/{subscription}/renew', [SubscriptionController::class, 'renew'])->name('subscriptions.renew');
    
    // Plan Features
    Route::resource('plan-features', PlanFeatureController::class)->except(['index']);
    Route::get('subscription-plans/{planId}/plan-features', [PlanFeatureController::class, 'index'])->name('plan-features.index');
    
    // User Roles
    Route::resource('roles', RoleController::class);
    
    // Homepage CMS
    Route::resource('abouts', AboutController::class);
    Route::resource('banners', BannerController::class);
    
    Route::prefix('website-settings')->name('website.')->group(function () {
        Route::get('/', [WebsiteSettingController::class, 'edit'])->name('edit');
        Route::put('/identity', [WebsiteSettingController::class, 'updateIdentity'])->name('identity.update');
        Route::put('/contact', [WebsiteSettingController::class, 'updateContact'])->name('contact.update');
        Route::put('/social', [WebsiteSettingController::class, 'updateSocial'])->name('social.update');
        Route::put('/seo', [WebsiteSettingController::class, 'updateSeo'])->name('seo.update');
        Route::put('/update', [WebsiteSettingController::class, 'update'])->name('update');
    });
});
