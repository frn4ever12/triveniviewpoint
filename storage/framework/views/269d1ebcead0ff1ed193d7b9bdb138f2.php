<?php
    $currentTenant = auth()->user()?->tenant;
    $enabledModules = $currentTenant ? $currentTenant->enabled_modules : [];
    $trialEndsAt = $currentTenant ? $currentTenant->trial_ends_at : null;
    $daysRemaining = $trialEndsAt ? round(now()->diffInDays($trialEndsAt, false)) : 0;
    
    // Get package name from subscription
    $packageName = 'Free Trial';
    if ($currentTenant && $currentTenant->subscription) {
        $packageName = $currentTenant->subscription->plan->name ?? 'Free Trial';
    }
    
    // Helper function to check if a submenu should be expanded
    $isSubmenuActive = function($routes) {
        foreach ($routes as $route) {
            if (request()->routeIs($route)) {
                return true;
            }
        }
        return false;
    };
?>

<nav class="navbar-vertical navbar bg-dark">
    <div>
        <a class="navbar-brand" href="<?php echo e(route('dashboard')); ?>">
            <div class="d-flex align-items-center">
                <?php if($currentTenant && $currentTenant->logo): ?>
                    <img src="<?php echo e($currentTenant->logo); ?>" alt="<?php echo e($currentTenant->name); ?>" class="me-2" style="height: 32px;">
                <?php else: ?>
                    <i data-feather="utensils" class="me-2"></i>
                <?php endif; ?>
                <div>
                    <span class="d-block fw-bold"><?php echo e($currentTenant->name ?? 'Restaurant'); ?></span>
                    <span class="d-block fs-6 opacity-75">Restaurant Management</span>
                </div>
            </div>
        </a>
    </div>
    
    <div class="nav-scroller">
        <ul class="navbar-nav flex-column" id="sideNavbar">

            <?php if($currentTenant): ?>
            <li class="nav-item mb-3">
                <div class="restaurant-info-card">
                    <div class="d-flex align-items-center">
                        <?php if($currentTenant->logo): ?>
                            <img src="<?php echo e($currentTenant->logo); ?>" alt="<?php echo e($currentTenant->name); ?>" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                        <?php else: ?>
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                <span class="text-white fw-bold"><?php echo e(substr(auth()->user()->name, 0, 2)); ?></span>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h6 class="mb-0"><?php echo e($currentTenant->name); ?></h6>
                            <small class="text-muted"><?php echo e($packageName); ?></small>
                        </div>
                    </div>
                </div>
            </li>
            <?php endif; ?>

            <!-- 1. Dashboard -->
            <li class="nav-item">
                <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>"
                    href="<?php echo e(route('dashboard')); ?>">
                    <i data-feather="grid" class="nav-icon icon-xs me-2"></i> Dashboard
                </a>
            </li>

            <!-- 2. POS -->
            <li class="nav-item">
                <a class="nav-link has-arrow <?php echo e($isSubmenuActive(['admin.orders.pos']) ? 'active' : ''); ?>" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navPOS"
                   aria-expanded="<?php echo e($isSubmenuActive(['admin.orders.pos']) ? 'true' : 'false'); ?>" aria-controls="navPOS">
                    <i data-feather="shopping-cart" class="nav-icon icon-xs me-2"></i> POS
                    <i data-feather="chevron-down" class="nav-arrow ms-auto icon-xs"></i>
                </a>
                <div id="navPOS" class="collapse <?php echo e($isSubmenuActive(['admin.orders.pos']) ? 'show' : ''); ?>" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.orders.pos') ? 'active' : ''); ?>" href="<?php echo e(route('admin.orders.pos')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> POS Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.orders.pos')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> New POS Order
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.orders.index') ? 'active' : ''); ?>" href="<?php echo e(route('admin.orders.index')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> POS Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Held Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> POS Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 3. Orders -->
            <li class="nav-item">
                <a class="nav-link has-arrow <?php echo e($isSubmenuActive(['admin.orders.*']) && !$isSubmenuActive(['admin.orders.pos']) ? 'active' : ''); ?>" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navOrders"
                   aria-expanded="<?php echo e($isSubmenuActive(['admin.orders.*']) && !$isSubmenuActive(['admin.orders.pos']) ? 'true' : 'false'); ?>" aria-controls="navOrders">
                    <i data-feather="layers" class="nav-icon icon-xs me-2"></i> Orders
                    <i data-feather="chevron-down" class="nav-arrow ms-auto icon-xs"></i>
                </a>
                <div id="navOrders" class="collapse <?php echo e($isSubmenuActive(['admin.orders.*']) && !$isSubmenuActive(['admin.orders.pos']) ? 'show' : ''); ?>" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.orders.index') ? 'active' : ''); ?>" href="<?php echo e(route('admin.orders.index')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> All Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Dine In Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Take Away Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Delivery Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Online Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Cancelled Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Order History
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 4. Tables -->
            <li class="nav-item">
                <a class="nav-link has-arrow <?php echo e($isSubmenuActive(['admin.tables.*']) ? 'active' : ''); ?>" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navTables"
                   aria-expanded="<?php echo e($isSubmenuActive(['admin.tables.*']) ? 'true' : 'false'); ?>" aria-controls="navTables">
                    <i data-feather="layout" class="nav-icon icon-xs me-2"></i> Tables
                    <i data-feather="chevron-down" class="nav-arrow ms-auto icon-xs"></i>
                </a>
                <div id="navTables" class="collapse <?php echo e($isSubmenuActive(['admin.tables.*']) ? 'show' : ''); ?>" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.tables.index') ? 'active' : ''); ?>" href="<?php echo e(route('admin.tables.index')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Table Layout
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.tables.index')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Tables
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Table Reservations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Table Status
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Floor / Sections
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 5. KOT / Kitchen -->
            <li class="nav-item">
                <a class="nav-link has-arrow <?php echo e($isSubmenuActive(['admin.kitchen-display.*']) ? 'active' : ''); ?>" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navKitchen"
                   aria-expanded="<?php echo e($isSubmenuActive(['admin.kitchen-display.*']) ? 'true' : 'false'); ?>" aria-controls="navKitchen">
                    <i data-feather="users" class="nav-icon icon-xs me-2"></i> KOT / Kitchen
                    <i data-feather="chevron-down" class="nav-arrow ms-auto icon-xs"></i>
                </a>
                <div id="navKitchen" class="collapse <?php echo e($isSubmenuActive(['admin.kitchen-display.*']) ? 'show' : ''); ?>" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.kitchen-display.index') ? 'active' : ''); ?>" href="<?php echo e(route('admin.kitchen-display.index')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Kitchen Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.kitchen-display.index')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> KOT Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> New KOT
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Pending KOT
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Preparing
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Ready
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Served
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Kitchen Display
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> KOT Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 6. Delivery -->
            <li class="nav-item">
                <a class="nav-link has-arrow" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navDelivery"
                   aria-expanded="false" aria-controls="navDelivery">
                    <i data-feather="truck" class="nav-icon icon-xs me-2"></i> Delivery
                    <i data-feather="chevron-down" class="nav-arrow ms-auto icon-xs"></i>
                </a>
                <div id="navDelivery" class="collapse" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Delivery Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> New Delivery Order
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> All Deliveries
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Pending
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> On the Way
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Delivered
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Cancelled
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Delivery Riders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Delivery Areas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Delivery Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 7. Menu -->
            <li class="nav-item">
                <a class="nav-link has-arrow <?php echo e($isSubmenuActive(['admin.menu-items.*', 'admin.categories.*']) ? 'active' : ''); ?>" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navMenu"
                   aria-expanded="<?php echo e($isSubmenuActive(['admin.menu-items.*', 'admin.categories.*']) ? 'true' : 'false'); ?>" aria-controls="navMenu">
                    <i data-feather="package" class="nav-icon icon-xs me-2"></i> Menu
                    <i data-feather="chevron-down" class="nav-arrow ms-auto icon-xs"></i>
                </a>
                <div id="navMenu" class="collapse <?php echo e($isSubmenuActive(['admin.menu-items.*', 'admin.categories.*']) ? 'show' : ''); ?>" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Menu Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.categories.index') ? 'active' : ''); ?>" href="<?php echo e(route('admin.categories.index')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Sub Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.menu-items.index') ? 'active' : ''); ?>" href="<?php echo e(route('admin.menu-items.index')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Products / Food Items
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.menu-items.create')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Add Product
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Add-ons / Extras
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Variations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Modifiers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Combo / Packages
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.recipes.index') ? 'active' : ''); ?>" href="<?php echo e(route('admin.recipes.index')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Recipes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Menu Availability
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 8. Inventory -->
            <li class="nav-item">
                <a class="nav-link has-arrow <?php echo e($isSubmenuActive(['admin.products.*', 'admin.stock-adjustments.*', 'admin.wastages.*', 'admin.kitchen-consumptions.*']) ? 'active' : ''); ?>" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navInventory"
                   aria-expanded="<?php echo e($isSubmenuActive(['admin.products.*', 'admin.stock-adjustments.*', 'admin.wastages.*', 'admin.kitchen-consumptions.*']) ? 'true' : 'false'); ?>" aria-controls="navInventory">
                    <i data-feather="archive" class="nav-icon icon-xs me-2"></i> Inventory
                    <i data-feather="chevron-down" class="nav-arrow ms-auto icon-xs"></i>
                </a>
                <div id="navInventory" class="collapse <?php echo e($isSubmenuActive(['admin.products.*', 'admin.stock-adjustments.*', 'admin.wastages.*', 'admin.kitchen-consumptions.*']) ? 'show' : ''); ?>" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Inventory Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.products.index') ? 'active' : ''); ?>" href="<?php echo e(route('admin.products.index')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Products / Ingredients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Stock
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.stock-adjustments.index') ? 'active' : ''); ?>" href="<?php echo e(route('admin.stock-adjustments.index')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Stock Adjustment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Stock Transfer
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Stock In
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Stock Out
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.wastages.index') ? 'active' : ''); ?>" href="<?php echo e(route('admin.wastages.index')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Wastage
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Low Stock
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Expiry Items
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Inventory Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 9. Purchases -->
            <li class="nav-item">
                <a class="nav-link has-arrow <?php echo e($isSubmenuActive(['admin.purchases.*']) ? 'active' : ''); ?>" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navPurchases"
                   aria-expanded="<?php echo e($isSubmenuActive(['admin.purchases.*']) ? 'true' : 'false'); ?>" aria-controls="navPurchases">
                    <i data-feather="shopping-bag" class="nav-icon icon-xs me-2"></i> Purchases
                    <i data-feather="chevron-down" class="nav-arrow ms-auto icon-xs"></i>
                </a>
                <div id="navPurchases" class="collapse <?php echo e($isSubmenuActive(['admin.purchases.*']) ? 'show' : ''); ?>" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Purchase Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> New Purchase
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.purchases.index') ? 'active' : ''); ?>" href="<?php echo e(route('admin.purchases.index')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> All Purchases
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Purchase Returns
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Purchase Payments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Purchase Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 10. Suppliers -->
            <li class="nav-item">
                <a class="nav-link has-arrow <?php echo e($isSubmenuActive(['admin.suppliers.*']) ? 'active' : ''); ?>" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navSuppliers"
                   aria-expanded="<?php echo e($isSubmenuActive(['admin.suppliers.*']) ? 'true' : 'false'); ?>" aria-controls="navSuppliers">
                    <i data-feather="users" class="nav-icon icon-xs me-2"></i> Suppliers
                    <i data-feather="chevron-down" class="nav-arrow ms-auto icon-xs"></i>
                </a>
                <div id="navSuppliers" class="collapse <?php echo e($isSubmenuActive(['admin.suppliers.*']) ? 'show' : ''); ?>" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.suppliers.index') ? 'active' : ''); ?>" href="<?php echo e(route('admin.suppliers.index')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> All Suppliers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.suppliers.create')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Add Supplier
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Supplier Ledger
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Supplier Payments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Supplier Due
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Supplier Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 11. Expenses -->
            <li class="nav-item">
                <a class="nav-link has-arrow <?php echo e($isSubmenuActive(['admin.expenses.*']) ? 'active' : ''); ?>" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navExpenses"
                   aria-expanded="<?php echo e($isSubmenuActive(['admin.expenses.*']) ? 'true' : 'false'); ?>" aria-controls="navExpenses">
                    <i data-feather="dollar-sign" class="nav-icon icon-xs me-2"></i> Expenses
                    <i data-feather="chevron-down" class="nav-arrow ms-auto icon-xs"></i>
                </a>
                <div id="navExpenses" class="collapse <?php echo e($isSubmenuActive(['admin.expenses.*']) ? 'show' : ''); ?>" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Expense Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.expenses.index') ? 'active' : ''); ?>" href="<?php echo e(route('admin.expenses.index')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> All Expenses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.expenses.create')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Add Expense
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Expense Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Expense Payments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Expense Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 12. Customers -->
            <li class="nav-item">
                <a class="nav-link has-arrow" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navCustomers"
                   aria-expanded="false" aria-controls="navCustomers">
                    <i data-feather="user" class="nav-icon icon-xs me-2"></i> Customers
                    <i data-feather="chevron-down" class="nav-arrow ms-auto icon-xs"></i>
                </a>
                <div id="navCustomers" class="collapse" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Customer Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> All Customers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Add Customer
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Customer Groups
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Customer Ledger
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Customer Due
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Customer Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 13. Reservations -->
            <li class="nav-item">
                <a class="nav-link has-arrow" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navReservations"
                   aria-expanded="false" aria-controls="navReservations">
                    <i data-feather="calendar" class="nav-icon icon-xs me-2"></i> Reservations
                    <i data-feather="chevron-down" class="nav-arrow ms-auto icon-xs"></i>
                </a>
                <div id="navReservations" class="collapse" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Reservation Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> All Reservations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> New Reservation
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Pending Reservations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Confirmed
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Completed
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Cancelled
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Reservation Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 14. Staff -->
            <li class="nav-item">
                <a class="nav-link has-arrow <?php echo e($isSubmenuActive(['admin.staff.*', 'admin.roles.*']) ? 'active' : ''); ?>" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navStaff"
                   aria-expanded="<?php echo e($isSubmenuActive(['admin.staff.*', 'admin.roles.*']) ? 'true' : 'false'); ?>" aria-controls="navStaff">
                    <i data-feather="user-check" class="nav-icon icon-xs me-2"></i> Staff
                    <i data-feather="chevron-down" class="nav-arrow ms-auto icon-xs"></i>
                </a>
                <div id="navStaff" class="collapse <?php echo e($isSubmenuActive(['admin.staff.*', 'admin.roles.*']) ? 'show' : ''); ?>" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Staff Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.staff.index') ? 'active' : ''); ?>" href="<?php echo e(route('admin.staff.index')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> All Staff
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.staff.create')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Add Staff
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.roles.index') ? 'active' : ''); ?>" href="<?php echo e(route('admin.roles.index')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Roles & Permissions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Attendance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Staff Shifts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Salaries / Payroll
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Staff Performance
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 15. Accounting -->
            <li class="nav-item">
                <a class="nav-link has-arrow" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navAccounting"
                   aria-expanded="false" aria-controls="navAccounting">
                    <i data-feather="pie-chart" class="nav-icon icon-xs me-2"></i> Accounting
                    <i data-feather="chevron-down" class="nav-arrow ms-auto icon-xs"></i>
                </a>
                <div id="navAccounting" class="collapse" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Accounting Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Chart of Accounts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Cash & Bank
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Cash Transactions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Bank Transactions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Income
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.expenses.index') ? 'active' : ''); ?>" href="<?php echo e(route('admin.expenses.index')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Expenses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Receivables
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Payables
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Journal Entries
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> General Ledger
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Trial Balance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.reports.profit_loss_report')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Profit & Loss
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Balance Sheet
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Cash Flow
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Day Book
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Accounting Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 16. Reports -->
            <li class="nav-item">
                <a class="nav-link has-arrow" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navReports"
                   aria-expanded="false" aria-controls="navReports">
                    <i data-feather="file-text" class="nav-icon icon-xs me-2"></i> Reports
                    <i data-feather="chevron-down" class="nav-arrow ms-auto icon-xs"></i>
                </a>
                <div id="navReports" class="collapse" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.reports.sales_report')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Sales Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Order Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> POS Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> KOT Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.reports.purchase_report')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Purchase Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.reports.stock_report')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Inventory Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Expense Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Customer Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Supplier Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Delivery Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Staff Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Tax Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.reports.profit_loss_report')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Profit & Loss
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Financial Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 17. Settings -->
            <li class="nav-item">
                <a class="nav-link has-arrow <?php echo e($isSubmenuActive(['admin.website.*']) ? 'active' : ''); ?>" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navSettings"
                   aria-expanded="<?php echo e($isSubmenuActive(['admin.website.*']) ? 'true' : 'false'); ?>" aria-controls="navSettings">
                    <i data-feather="settings" class="nav-icon icon-xs me-2"></i> Settings
                    <i data-feather="chevron-down" class="nav-arrow ms-auto icon-xs"></i>
                </a>
                <div id="navSettings" class="collapse <?php echo e($isSubmenuActive(['admin.website.*']) ? 'show' : ''); ?>" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Restaurant Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Branch Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.website.edit') ? 'active' : ''); ?>" href="<?php echo e(route('admin.website.edit')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> General Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Tax Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Payment Methods
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Printer Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Receipt Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Invoice Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Notification Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Delivery Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 18. System Settings -->
            <li class="nav-item">
                <a class="nav-link has-arrow" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navSystemSettings"
                   aria-expanded="false" aria-controls="navSystemSettings">
                    <i data-feather="tool" class="nav-icon icon-xs me-2"></i> System Settings
                    <i data-feather="chevron-down" class="nav-arrow ms-auto icon-xs"></i>
                </a>
                <div id="navSystemSettings" class="collapse" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('admin.roles.index') ? 'active' : ''); ?>" href="<?php echo e(route('admin.roles.index')); ?>">
                                <i data-feather="circle" class="icon-xs me-2"></i> Roles & Permissions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Branches
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Modules
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Database Backup
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Activity Logs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Audit Logs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> System Configuration
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

        </ul>
    </div>
</nav>
<?php /**PATH D:\DMCRESTRO\singlerestro-main\resources\views/admin/includes/sidebar-new.blade.php ENDPATH**/ ?>