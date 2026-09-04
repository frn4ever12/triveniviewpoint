# Multi-Tenant SaaS Architecture Documentation

## Overview

This document describes the multi-tenant SaaS architecture implemented for the DMCRESTRO restaurant POS system. The system now supports multiple restaurant tenants with subscription-based access to features.

## Architecture Components

### 1. Database Schema

#### Core Tables

- **tenants**: Stores tenant/restaurant information
  - `id`, `name`, `slug`, `company_name`, `email`, `phone`, `address`, `city`, `country`, `pan_no`, `domain`, `logo`, `status`, `trial_ends_at`
  - Soft deletes enabled
  - Relationships: hasMany Users, hasMany Subscriptions

- **subscription_plans**: Defines available subscription tiers
  - `id`, `name`, `slug`, `description`, `monthly_price`, `yearly_price`, `trial_days`, `max_users`, `max_menu_items`, `max_orders_per_month`, `is_active`, `is_popular`, `features`, `sort_order`
  - Relationships: hasMany Subscriptions, hasMany PlanFeatures

- **subscriptions**: Links tenants to subscription plans
  - `id`, `tenant_id`, `plan_id`, `billing_cycle`, `amount`, `starts_at`, `ends_at`, `next_billing_at`, `status`, `payment_method`, `payment_id`, `notes`
  - Status values: active, trialing, past_due, cancelled, expired
  - Relationships: belongsTo Tenant, belongsTo SubscriptionPlan

- **plan_features**: Defines features available in each plan
  - `id`, `plan_id`, `code`, `name`, `description`, `is_enabled`, `value`, `sort_order`
  - Unique constraint: (plan_id, code)
  - Relationships: belongsTo SubscriptionPlan

#### Modified Tables

All core business tables now include `tenant_id` for data isolation:
- users
- categories
- menu_items
- tables
- orders
- order_items
- kots
- products
- purchases
- purchase_items
- expenses
- suppliers
- units
- labels
- invoices
- rooms
- stock_usages

### 2. Models

#### Tenant Model
- **Location**: `app/Models/Tenant.php`
- **Traits**: HasSlug, InteractsWithMedia, SoftDeletes
- **Key Methods**:
  - `isActive()`: Check if tenant is active
  - `isOnTrial()`: Check if tenant is in trial period
  - `hasActiveSubscription()`: Check if tenant has active subscription
  - `users()`: Relationship to User model
  - `subscription()`: Latest subscription relationship
  - `subscriptions()`: All subscriptions relationship

#### SubscriptionPlan Model
- **Location**: `app/Models/SubscriptionPlan.php`
- **Traits**: HasSlug
- **Key Methods**:
  - `hasFeature(string $featureCode)`: Check if plan has specific feature
  - `getFeatureValue(string $featureCode)`: Get feature value
  - `scopeActive()`: Filter active plans
  - `scopePopular()`: Filter popular plans
  - `scopeOrdered()`: Order by sort_order and price

#### Subscription Model
- **Location**: `app/Models/Subscription.php`
- **Key Methods**:
  - `isActive()`: Check if subscription is active or trialing
  - `isExpired()`: Check if subscription is expired
  - `isOnTrial()`: Check if subscription is in trial
  - `isCancelled()`: Check if subscription is cancelled
  - `daysRemaining()`: Get days until expiration
  - `scopeActive()`: Filter active subscriptions
  - `scopeExpired()`: Filter expired subscriptions
  - `scopeByTenant($tenantId)`: Filter by tenant

#### PlanFeature Model
- **Location**: `app/Models/PlanFeature.php`
- **Key Methods**:
  - `scopeEnabled()`: Filter enabled features
  - `scopeOrdered()`: Order by sort_order

### 3. Middleware

#### SetTenant Middleware
- **Location**: `app/Http/Middleware/SetTenant.php`
- **Purpose**: Resolves tenant from authenticated user and sets it in session/request
- **Behavior**:
  - Skips tenant resolution for superadmin routes
  - Gets tenant from authenticated user's tenant_id
  - Sets tenant in session for easy access
  - Adds tenant to request for controllers
- **Registration**: Registered as `set.tenant` in `bootstrap/app.php`

### 4. Controllers

#### TenantController
- **Location**: `app/Http/Controllers/Admin/TenantController.php`
- **Routes**: `/admin/tenants`
- **Methods**: index, create, store, show, edit, update, destroy
- **Features**:
  - Creates tenant with initial subscription
  - Creates admin user for tenant
  - Handles trial period setup

#### SubscriptionPlanController
- **Location**: `app/Http/Controllers/Admin/SubscriptionPlanController.php`
- **Routes**: `/admin/subscription-plans`
- **Methods**: index, create, store, show, edit, update, destroy
- **Access**: Superadmin only

#### SubscriptionController
- **Location**: `app/Http/Controllers/Admin/SubscriptionController.php`
- **Routes**: `/admin/subscriptions`
- **Methods**: index, create, store, show, edit, update, cancel, renew
- **Features**:
  - Subscription lifecycle management
  - Billing cycle handling (monthly/yearly)
  - Renewal and cancellation

#### PlanFeatureController
- **Location**: `app/Http/Controllers/Admin/PlanFeatureController.php`
- **Routes**: `/admin/plan-features`
- **Methods**: index (scoped to plan), create, store, edit, update, destroy
- **Access**: Superadmin only

### 5. Routes

All subscription management routes are restricted to superadmin role:

```php
Route::middleware('role:superadmin')->group(function () {
    Route::resource('tenants', TenantController::class);
    Route::resource('subscription-plans', SubscriptionPlanController::class);
    Route::resource('subscriptions', SubscriptionController::class);
    Route::post('subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel']);
    Route::post('subscriptions/{subscription}/renew', [SubscriptionController::class, 'renew']);
    Route::resource('plan-features', PlanFeatureController::class)->except(['index']);
    Route::get('subscription-plans/{planId}/plan-features', [PlanFeatureController::class, 'index']);
});
```

### 6. Seeders

#### SubscriptionPlanSeeder
- **Location**: `database/seeders/SubscriptionPlanSeeder.php`
- **Plans Created**:
  1. **Starter** (Rs. 1,500/month)
     - 2 users, 50 menu items, 500 orders/month
     - 14-day trial
  2. **Professional** (Rs. 3,500/month) - Popular
     - 5 users, 200 menu items, 2,000 orders/month
     - 14-day trial
  3. **Enterprise** (Rs. 7,500/month)
     - 15 users, 1,000 menu items, unlimited orders
     - 30-day trial

#### PlanFeatureSeeder
- **Location**: `database/seeders/PlanFeatureSeeder.php`
- **Features by Plan**:

**Starter Plan Features**:
- Basic POS
- Menu Management
- Order Management
- Basic Reports
- Kitchen Display
- Basic Inventory
- Email Support

**Professional Plan Features** (includes Starter +):
- Advanced POS (split bills)
- Advanced Reports
- Advanced Inventory (with alerts)
- Digital Menu (QR code)
- Priority Support

**Enterprise Plan Features** (includes Professional +):
- Multi Location support
- API Access
- Dedicated Support (24/7)
- Custom Integrations
- White Label branding

## Usage Guide

### Creating a New Tenant

1. Navigate to `/admin/tenants/create` (superadmin only)
2. Fill in tenant details:
   - Name, Company Name, Email, Phone, Address
   - Select Subscription Plan
   - Create Admin User (name, email, password)
3. Submit - this creates:
   - Tenant record
   - Subscription (trial or active based on plan)
   - Admin user with tenant_id and admin role

### Managing Subscriptions

1. View all subscriptions at `/admin/subscriptions`
2. Create new subscription for existing tenant
3. Edit subscription details (plan, billing cycle, status)
4. Cancel subscription (sets status to 'cancelled')
5. Renew subscription (extends end date and reactivates)

### Managing Subscription Plans

1. View all plans at `/admin/subscription-plans`
2. Create new plan with pricing and limits
3. Edit plan details
4. Manage plan features at `/admin/subscription-plans/{id}/plan-features`

### Tenant Data Isolation

All tenant-specific data is automatically isolated via `tenant_id` foreign keys. When querying data, always scope to the current tenant:

```php
// In controllers, use tenant from session or request
$tenantId = session('tenant_id');
$menuItems = MenuItem::where('tenant_id', $tenantId)->get();
```

Or use the scope method on User model:
```php
$menuItems = MenuItem::whereHas('users', function($query) {
    $query->where('tenant_id', auth()->user()->tenant_id);
})->get();
```

## Feature Access Control

To check if a tenant has access to a specific feature:

```php
$tenant = auth()->user()->tenant;
$subscription = $tenant->subscription;
$plan = $subscription->plan;

if ($plan->hasFeature('digital_menu')) {
    // Allow access to digital menu feature
}
```

## Subscription Status Flow

1. **Trial**: New subscription with trial period
2. **Active**: Paid subscription within valid period
3. **Past Due**: Payment failed but within grace period
4. **Cancelled**: User cancelled, access until end date
5. **Expired**: Subscription ended, no access

## Security Considerations

1. **Superadmin Isolation**: Superadmin routes skip tenant resolution
2. **Data Isolation**: All core tables have tenant_id foreign keys
3. **Middleware**: SetTenant middleware ensures tenant context
4. **Role-Based Access**: Subscription management restricted to superadmin
5. **Soft Deletes**: Tenants use soft deletes for data recovery

## Future Enhancements

1. **Payment Gateway Integration**: Connect to payment providers (Khalti, eSewa, etc.)
2. **Usage Tracking**: Track actual usage against plan limits
3. **Auto-Renewal**: Automatic subscription renewal on payment
4. **Upgrade/Downgrade**: Allow tenants to change plans
5. **Tenant Dashboard**: Tenant-specific admin dashboard
6. **Multi-Location Support**: Full multi-location per tenant
7. **API Rate Limiting**: Per-tenant API rate limits
8. **Audit Logging**: Track tenant actions for compliance

## Troubleshooting

### Tenant Not Found
- Ensure user has tenant_id set
- Check SetTenant middleware is applied
- Verify tenant status is 'active'

### Subscription Expired
- Check subscription ends_at date
- Verify subscription status is 'active'
- Use renew method to extend subscription

### Feature Not Available
- Verify plan has the feature enabled
- Check plan_features table for feature code
- Ensure subscription is active

## Database Migrations

Run migrations in order:
```bash
php artisan migrate
```

Seed default plans and features:
```bash
php artisan db:seed --class=SubscriptionPlanSeeder
php artisan db:seed --class=PlanFeatureSeeder
```

## Support

For issues or questions about the multi-tenant architecture, contact the development team.
