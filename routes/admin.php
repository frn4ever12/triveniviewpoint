<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DigitalMenuController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\KotController;
use App\Http\Controllers\Admin\KtdController;
use App\Http\Controllers\Admin\LabelController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\userController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\WebsiteSettingController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\PlanFeatureController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\CashierDashboardController;

Route::middleware('auth')->group(function () {
    // Super Admin only routes for SaaS management
    Route::middleware('role:superadmin')->group(function () {
        Route::resource('tenants', TenantController::class);
        Route::resource('subscription-plans', SubscriptionPlanController::class);
        Route::resource('subscriptions', SubscriptionController::class);
        Route::post('subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
        Route::post('subscriptions/{subscription}/renew', [SubscriptionController::class, 'renew'])->name('subscriptions.renew');
        Route::resource('plan-features', PlanFeatureController::class)->except(['index']);
        Route::get('subscription-plans/{planId}/plan-features', [PlanFeatureController::class, 'index'])->name('plan-features.index');
    });

    Route::middleware('role:superadmin|admin')->group(function () {

        Route::resource('staff', StaffController::class);
        Route::post('staff/toggle-login', [StaffController::class, 'toggleLogin'])->name('staff.toggle-login');

        Route::resource('suppliers', SupplierController::class);

        Route::resource('units', UnitController::class);

        Route::resource('labels', LabelController::class);

        Route::resource('banners', BannerController::class);

        Route::resource('contacts', ContactController::class)->except(['create', 'edit', 'update']);
        Route::put('Contact/{contact}/update', [ContactController::class, 'updateStatus'])
            ->name('contacts.updateStatus');

        Route::resource('abouts', AboutController::class);

        Route::prefix('website-settings')->name('website.')->group(function () {
            Route::get('/', [WebsiteSettingController::class, 'edit'])->name('edit');
            Route::put('/identity', [WebsiteSettingController::class, 'updateIdentity'])->name('identity.update');
            Route::put('/contact', [WebsiteSettingController::class, 'updateContact'])->name('contact.update');
            Route::put('/social', [WebsiteSettingController::class, 'updateSocial'])->name('social.update');
            Route::put('/seo', [WebsiteSettingController::class, 'updateSeo'])->name('seo.update');
            Route::put('/update', [WebsiteSettingController::class, 'update'])->name('update');
        });

        Route::resource('roles', RoleController::class);

        Route::resource('rooms', RoomController::class);

        Route::resource('categories', CategoryController::class)
            ->parameters(['categories' => 'category'])
            ->except(['index', 'show']);
        Route::resource('menu-items', MenuItemController::class)->except(['index', 'show']);
        Route::resource('tables', TableController::class)->except(['index', 'show']);
        Route::resource('products', ProductController::class)->except(['index', 'show']);
        Route::resource('purchases', PurchaseController::class)->except(['index', 'show']);
        Route::resource('expenses', ExpenseController::class)->except(['index', 'show']);

        Route::resource('users', userController::class);
    });

    Route::middleware('can:menus.view')->group(function () {
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    });

    Route::middleware('can:dishes.view')->group(function () {
        Route::get('menu-items', [MenuItemController::class, 'index'])->name('menu-items.index');
        Route::get('menu-items/{menu_item}', [MenuItemController::class, 'show'])->name('menu-items.show');
    });

    Route::middleware('can:tables.view')->group(function () {
        Route::get('tables', [TableController::class, 'index'])->name('tables.index');
        Route::get('tables/{table}', [TableController::class, 'show'])->name('tables.show');
    });

    Route::middleware('can:products.view')->group(function () {
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
    });

    Route::middleware('can:purchases.view')->group(function () {
        Route::get('purchases', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');
    });

    Route::middleware('can:expenses.view')->group(function () {
        Route::get('expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::get('expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');
    });

    Route::middleware('can:orders.view')->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/recent', [OrderController::class, 'getRecentOrders'])->name('orders.recent');
        Route::get('/orders/active', [OrderController::class, 'getActiveOrders'])->name('orders.active');
        Route::get('/orders/list', [OrderController::class, 'orderdetailtable'])->name('orders.details');
        Route::get('/orders/today', [OrderController::class, 'todayorders'])->name('orders.today');

        Route::post('/admin/tables/{table}/toggle-vat', [OrderController::class, 'toggleVat'])
            ->name('tables.toggle-vat');

        Route::get('/orders/today/count', [OrderController::class, 'getTodayOrdersCount']);

        Route::post('/kots/{id}/reprint', [KotController::class, 'reprintKOT'])->name('orders.kot-reprint');

        Route::get('/orders/table/{table}/edit', [OrderController::class, 'editTable'])
            ->name('orders.table.edit');
        Route::post('/orders/table/{table}/add-items', [OrderController::class, 'addItemsToTable'])
            ->name('orders.table.add-items');

        Route::get('/orders/table/{table}/checkout', [OrderController::class, 'showCheckout'])
            ->name('orders.table.checkout')
            ->middleware('can:checkout-view');
        Route::post('/orders/table/{table}/checkout', [OrderController::class, 'checkoutTable'])
            ->name('orders.table.process-checkout')
            ->middleware('can:checkout-process');

        Route::get('/orders/{id}/checkout', [OrderController::class, 'showQuickCheckout'])
            ->name('orders.checkout')
            ->middleware('can:checkout-view');
        Route::post('/orders/{id}/checkout', [OrderController::class, 'quickCheckout'])
            ->name('orders.process-checkout')
            ->middleware('can:checkout-process');
        Route::put('/order-items/{orderItem}/status', [OrderController::class, 'updateOrderItemStatus'])
            ->name('order-items.status');

        Route::get('/orders/delivery', [OrderController::class, 'getDeliveryOrders'])
            ->name('orders.delivery');
        Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
        Route::post('/orders/{id}/cancel', [OrderController::class, 'cancelOrder'])
            ->name('orders.cancel');
        Route::post('/order-items/{id}/cancel', [OrderController::class, 'cancelOrderItem'])
            ->name('order-items.cancel');

        Route::get('/orders/{id}/details', [OrderController::class, 'getOrderDetails'])
            ->name('onlineorders.details');
        Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/update-delivery-status', [OrderController::class, 'updateDeliveryStatus'])
            ->name('orders.updateDeliveryStatus');
    });

    Route::middleware('can:pos.access')->group(function () {
        Route::get('/pos', [OrderController::class, 'pos'])->name('orders.pos');
        Route::post('/pos/orders', [OrderController::class, 'storePOSOrder'])->name('orders.pos.store');
        Route::get('/pos/category/{categoryId}/menu-items', [OrderController::class, 'getMenusForCategory']);
    });

    Route::middleware('can:reports.view')->group(function () {
        Route::get('reports/purchase-report', [ReportController::class, 'purchaseReport'])
            ->name('reports.purchase_report');
        Route::get('reports/stock-report', [ReportController::class, 'stockReport'])
            ->name('reports.stock_report');
        Route::get('/stock/summary', [ReportController::class, 'stockSummary'])
            ->name('reports.stock.summary');
        Route::get('/stock/expiring', [ReportController::class, 'stockExpiringItems'])
            ->name('reports.stock.expiring');
        Route::post('/admin/reports/stock/update', [ReportController::class, 'updateStock'])
            ->name('reports.stock.update');
        Route::post('/admin/reports/stock/restock', [ReportController::class, 'restockItem'])
            ->name('reports.stock.restock');
        Route::get('reports/expense-report', [ReportController::class, 'expenseReport'])
            ->name('reports.expense_report');
        Route::get('reports/profit-loss-report', [ReportController::class, 'profitLossReport'])
            ->name('reports.profit_loss_report');
        Route::get('reports/sales-report', [ReportController::class, 'salesReport'])
            ->name('reports.sales_report');
        Route::get('reports/financial-track', [ReportController::class, 'financialTrackReport'])
            ->name('reports.financial_track');
    });

    Route::get('revenue/today-dishes', [DashboardController::class, 'getTodaysDishRevenue'])
        ->name('revenue.today-dishes');

    Route::middleware('can:kot.view')->group(function () {
        Route::get('/kitchen-display', [KtdController::class, 'index'])->name('kitchen-display.index');
        Route::put('/kitchen-display/order/{order}/status', [KtdController::class, 'updateOrderStatus'])
            ->name('kitchen-display.update-status');
    });

    Route::middleware('can:digital-menu.view')->group(function () {
        Route::get('/digital-menu', [DigitalMenuController::class, 'index'])->name('digital-menu.index');
    });

    Route::get('/checkout-dashboard', [CashierDashboardController::class, 'index'])
        ->name('orders.checkout-dashboard');
});
