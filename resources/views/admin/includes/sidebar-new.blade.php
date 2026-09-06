@php
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
@endphp

<style>
    .nav-arrow {
        transition: transform 0.2s ease;
        opacity: 1 !important;
        display: inline-block !important;
        width: 16px !important;
        height: 16px !important;
        visibility: visible !important;
    }
    .nav-link[aria-expanded="true"] .nav-arrow {
        transform: rotate(180deg);
    }
    .nav-link.has-arrow {
        position: relative;
    }
    .nav-link.has-arrow .nav-arrow {
        display: inline-block !important;
        width: 16px !important;
        height: 16px !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    .nav-link.has-arrow i[data-feather="chevron-down"] {
        display: inline-block !important;
        visibility: visible !important;
        opacity: 1 !important;
        width: 16px !important;
        height: 16px !important;
    }
    /* Ensure all feather icons in sidebar are visible */
    .navbar-vertical i[data-feather] {
        display: inline-block !important;
        visibility: visible !important;
        opacity: 1 !important;
        width: 16px !important;
        height: 16px !important;
        vertical-align: middle !important;
    }
    .navbar-vertical .nav-icon {
        display: inline-block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    @media (max-width: 767.98px) {
        .nav-arrow {
            display: inline-block !important;
            visibility: visible !important;
        }
    }
</style>

<nav class="navbar-vertical navbar bg-dark">
    <div>
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <div class="d-flex align-items-center">
                @if($currentTenant && $currentTenant->logo)
                    <img src="{{ $currentTenant->logo }}" alt="{{ $currentTenant->name }}" class="me-2" style="height: 32px;">
                @else
                    <i data-feather="utensils" class="me-2"></i>
                @endif
                <div>
                    <span class="d-block fw-bold">{{ $currentTenant->name ?? 'Restaurant' }}</span>
                    <span class="d-block fs-6 opacity-75">Restaurant Management</span>
                </div>
            </div>
        </a>
    </div>
    
    <div class="nav-scroller">
        <ul class="navbar-nav flex-column" id="sideNavbar">

            @if($currentTenant)
            <li class="nav-item mb-3">
                <div class="restaurant-info-card">
                    <div class="d-flex align-items-center">
                        @if($currentTenant->logo)
                            <img src="{{ $currentTenant->logo }}" alt="{{ $currentTenant->name }}" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                        @else
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                <span class="text-white fw-bold">{{ substr(auth()->user()->name, 0, 2) }}</span>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-0">{{ $currentTenant->name }}</h6>
                            <small class="text-muted">{{ $packageName }}</small>
                        </div>
                    </div>
                </div>
            </li>
            @endif

            <!-- 1. Dashboard -->
            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navDashboard"
                   aria-expanded="{{ request()->routeIs('dashboard') ? 'true' : 'false' }}" aria-controls="navDashboard">
                    <i data-feather="grid" class="nav-icon icon-xs me-2"></i> Dashboard
                    <span class="nav-arrow ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </span>
                </a>
                <div id="navDashboard" class="collapse {{ request()->routeIs('dashboard') ? 'show' : '' }}">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i data-feather="home" class="icon-xs me-2"></i> Main Dashboard
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 2. POS -->
            <li class="nav-item">
                <a class="nav-link has-arrow {{ $isSubmenuActive(['admin.orders.pos']) ? 'active' : '' }}"
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navPOS"
                   aria-expanded="{{ $isSubmenuActive(['admin.orders.pos']) ? 'true' : 'false' }}" aria-controls="navPOS">
                    <i data-feather="shopping-cart" class="nav-icon icon-xs me-2"></i> POS
                    <span class="nav-arrow ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </span>
                </a>
                <div id="navPOS" class="collapse {{ $isSubmenuActive(['admin.orders.pos']) ? 'show' : '' }}">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.orders.pos') ? 'active' : '' }}" href="{{ route('admin.orders.pos') }}">
                                <i data-feather="circle" class="icon-xs me-2"></i> POS Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.orders.pos') }}">
                                <i data-feather="circle" class="icon-xs me-2"></i> New POS Order
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                                <i data-feather="circle" class="icon-xs me-2"></i> POS Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Held Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.pos-settings.*') ? 'active' : '' }}" href="{{ route('admin.pos-settings.index') }}">
                                <i data-feather="circle" class="icon-xs me-2"></i> POS Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 3. Orders -->
            <li class="nav-item">
                <a class="nav-link has-arrow {{ $isSubmenuActive(['admin.orders.*']) && !$isSubmenuActive(['admin.orders.pos']) ? 'active' : '' }}"
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navOrders"
                   aria-expanded="{{ $isSubmenuActive(['admin.orders.*']) && !$isSubmenuActive(['admin.orders.pos']) ? 'true' : 'false' }}" aria-controls="navOrders">
                    <i data-feather="layers" class="nav-icon icon-xs me-2"></i> Orders
                    <span class="nav-arrow ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </span>
                </a>
                <div id="navOrders" class="collapse {{ $isSubmenuActive(['admin.orders.*']) && !$isSubmenuActive(['admin.orders.pos']) ? 'show' : '' }}">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                                <i data-feather="list" class="icon-xs me-2"></i> All Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.orders.dine-in') ? 'active' : '' }}" href="{{ route('admin.orders.dine-in') }}">
                                <i data-feather="users" class="icon-xs me-2"></i> Dine In Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.orders.takeaway') ? 'active' : '' }}" href="{{ route('admin.orders.takeaway') }}">
                                <i data-feather="shopping-bag" class="icon-xs me-2"></i> Take Away Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.orders.delivery') ? 'active' : '' }}" href="{{ route('admin.orders.delivery') }}">
                                <i data-feather="truck" class="icon-xs me-2"></i> Delivery Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.orders.online') ? 'active' : '' }}" href="{{ route('admin.orders.online') }}">
                                <i data-feather="globe" class="icon-xs me-2"></i> Online Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.orders.cancelled') ? 'active' : '' }}" href="{{ route('admin.orders.cancelled') }}">
                                <i data-feather="x-circle" class="icon-xs me-2"></i> Cancelled Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.orders.history') ? 'active' : '' }}" href="{{ route('admin.orders.history') }}">
                                <i data-feather="clock" class="icon-xs me-2"></i> Order History
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 4. Tables -->
            <li class="nav-item">
                <a class="nav-link has-arrow {{ $isSubmenuActive(['admin.tables.*']) ? 'active' : '' }}" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navTables"
                   aria-expanded="{{ $isSubmenuActive(['admin.tables.*']) ? 'true' : 'false' }}" aria-controls="navTables">
                    <i data-feather="layout" class="nav-icon icon-xs me-2"></i> Tables
                    <span class="nav-arrow ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </span>
                </a>
                <div id="navTables" class="collapse {{ $isSubmenuActive(['admin.tables.*']) ? 'show' : '' }}">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.tables.index') ? 'active' : '' }}" href="{{ route('admin.tables.index') }}">
                                <i data-feather="grid" class="icon-xs me-2"></i> Table Layout
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.tables.index') }}">
                                <i data-feather="square" class="icon-xs me-2"></i> Tables
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.digital-menu.*') ? 'active' : '' }}" href="{{ route('admin.digital-menu.index') }}">
                                <i data-feather="smartphone" class="icon-xs me-2"></i> Digital QR Menu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="calendar" class="icon-xs me-2"></i> Table Reservations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="activity" class="icon-xs me-2"></i> Table Status
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="map" class="icon-xs me-2"></i> Floor / Sections
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 5. KOT / Kitchen -->
            <li class="nav-item">
                <a class="nav-link has-arrow {{ $isSubmenuActive(['admin.kitchen-display.*']) ? 'active' : '' }}" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navKitchen"
                   aria-expanded="{{ $isSubmenuActive(['admin.kitchen-display.*']) ? 'true' : 'false' }}" aria-controls="navKitchen">
                    <i data-feather="users" class="nav-icon icon-xs me-2"></i> KOT / Kitchen
                    <span class="nav-arrow ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </span>
                </a>
                <div id="navKitchen" class="collapse {{ $isSubmenuActive(['admin.kitchen-display.*']) ? 'show' : '' }}">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.kitchen-display.index') ? 'active' : '' }}" href="{{ route('admin.kitchen-display.index') }}">
                                <i data-feather="circle" class="icon-xs me-2"></i> Kitchen Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.kitchen-display.index') }}">
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
                    <span class="nav-arrow ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </span>
                </a>
                <div id="navDelivery" class="collapse">
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
                <a class="nav-link has-arrow {{ $isSubmenuActive(['admin.menu-items.*', 'admin.categories.*']) ? 'active' : '' }}" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navMenu"
                   aria-expanded="{{ $isSubmenuActive(['admin.menu-items.*', 'admin.categories.*']) ? 'true' : 'false' }}" aria-controls="navMenu">
                    <i data-feather="package" class="nav-icon icon-xs me-2"></i> Menu
                    <span class="nav-arrow ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </span>
                </a>
                <div id="navMenu" class="collapse {{ $isSubmenuActive(['admin.menu-items.*', 'admin.categories.*']) ? 'show' : '' }}">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Menu Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                                <i data-feather="folder" class="icon-xs me-2"></i> Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="circle" class="icon-xs me-2"></i> Sub Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.menu-items.index') ? 'active' : '' }}" href="{{ route('admin.menu-items.index') }}">
                                <i data-feather="coffee" class="icon-xs me-2"></i> Products / Food Items
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.menu-items.create') }}">
                                <i data-feather="plus" class="icon-xs me-2"></i> Add Product
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.addons.index') }}">
                                <i data-feather="plus-circle" class="icon-xs me-2"></i> Add-ons / Extras
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.variations.index') ? 'active' : '' }}" href="{{ route('admin.variations.index') }}">
                                <i data-feather="sliders" class="icon-xs me-2"></i> Variations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.modifiers.index') ? 'active' : '' }}" href="{{ route('admin.modifiers.index') }}">
                                <i data-feather="edit-2" class="icon-xs me-2"></i> Modifiers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.combos.index') ? 'active' : '' }}" href="{{ route('admin.combos.index') }}">
                                <i data-feather="package" class="icon-xs me-2"></i> Combo / Packages
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.recipes.index') ? 'active' : '' }}" href="{{ route('admin.recipes.index') }}">
                                <i data-feather="book" class="icon-xs me-2"></i> Recipes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.menu-availability.index') ? 'active' : '' }}" href="{{ route('admin.menu-availability.index') }}">
                                <i data-feather="toggle-right" class="icon-xs me-2"></i> Menu Availability
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 8. Inventory -->
            <li class="nav-item">
                <a class="nav-link has-arrow {{ $isSubmenuActive(['admin.products.*', 'admin.stock-adjustments.*', 'admin.wastages.*', 'admin.kitchen-consumptions.*']) ? 'active' : '' }}" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navInventory"
                   aria-expanded="{{ $isSubmenuActive(['admin.products.*', 'admin.stock-adjustments.*', 'admin.wastages.*', 'admin.kitchen-consumptions.*']) ? 'true' : 'false' }}" aria-controls="navInventory">
                    <i data-feather="archive" class="nav-icon icon-xs me-2"></i> Inventory
                    <span class="nav-arrow ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </span>
                </a>
                <div id="navInventory" class="collapse {{ $isSubmenuActive(['admin.products.*', 'admin.stock-adjustments.*', 'admin.wastages.*', 'admin.kitchen-consumptions.*']) ? 'show' : '' }}">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="bar-chart-2" class="icon-xs me-2"></i> Inventory Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                                <i data-feather="box" class="icon-xs me-2"></i> Products / Ingredients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="package" class="icon-xs me-2"></i> Stock
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.stock-adjustments.index') ? 'active' : '' }}" href="{{ route('admin.stock-adjustments.index') }}">
                                <i data-feather="refresh-cw" class="icon-xs me-2"></i> Stock Adjustment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="shuffle" class="icon-xs me-2"></i> Stock Transfer
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="arrow-down-circle" class="icon-xs me-2"></i> Stock In
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="arrow-up-circle" class="icon-xs me-2"></i> Stock Out
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.wastages.index') ? 'active' : '' }}" href="{{ route('admin.wastages.index') }}">
                                <i data-feather="trash-2" class="icon-xs me-2"></i> Wastage
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="alert-triangle" class="icon-xs me-2"></i> Low Stock
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="clock" class="icon-xs me-2"></i> Expiry Items
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="file-text" class="icon-xs me-2"></i> Inventory Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 9. Purchases -->
            <li class="nav-item">
                <a class="nav-link has-arrow {{ $isSubmenuActive(['admin.purchases.*']) ? 'active' : '' }}" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navPurchases"
                   aria-expanded="{{ $isSubmenuActive(['admin.purchases.*']) ? 'true' : 'false' }}" aria-controls="navPurchases">
                    <i data-feather="shopping-bag" class="nav-icon icon-xs me-2"></i> Purchases
                    <span class="nav-arrow ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </span>
                </a>
                <div id="navPurchases" class="collapse {{ $isSubmenuActive(['admin.purchases.*']) ? 'show' : '' }}">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="pie-chart" class="icon-xs me-2"></i> Purchase Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="plus-square" class="icon-xs me-2"></i> New Purchase
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.purchases.index') ? 'active' : '' }}" href="{{ route('admin.purchases.index') }}">
                                <i data-feather="list" class="icon-xs me-2"></i> All Purchases
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="rotate-ccw" class="icon-xs me-2"></i> Purchase Returns
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="credit-card" class="icon-xs me-2"></i> Purchase Payments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="file-text" class="icon-xs me-2"></i> Purchase Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 10. Suppliers -->
            <li class="nav-item">
                <a class="nav-link has-arrow {{ $isSubmenuActive(['admin.suppliers.*']) ? 'active' : '' }}" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navSuppliers"
                   aria-expanded="{{ $isSubmenuActive(['admin.suppliers.*']) ? 'true' : 'false' }}" aria-controls="navSuppliers">
                    <i data-feather="users" class="nav-icon icon-xs me-2"></i> Suppliers
                    <span class="nav-arrow ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </span>
                </a>
                <div id="navSuppliers" class="collapse {{ $isSubmenuActive(['admin.suppliers.*']) ? 'show' : '' }}">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.suppliers.index') ? 'active' : '' }}" href="{{ route('admin.suppliers.index') }}">
                                <i data-feather="users" class="icon-xs me-2"></i> All Suppliers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.suppliers.create') }}">
                                <i data-feather="user-plus" class="icon-xs me-2"></i> Add Supplier
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="book-open" class="icon-xs me-2"></i> Supplier Ledger
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="dollar-sign" class="icon-xs me-2"></i> Supplier Payments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="alert-circle" class="icon-xs me-2"></i> Supplier Due
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="file-text" class="icon-xs me-2"></i> Supplier Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 11. Expenses -->
            <li class="nav-item">
                <a class="nav-link has-arrow {{ $isSubmenuActive(['admin.expenses.*']) ? 'active' : '' }}" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navExpenses"
                   aria-expanded="{{ $isSubmenuActive(['admin.expenses.*']) ? 'true' : 'false' }}" aria-controls="navExpenses">
                    <i data-feather="dollar-sign" class="nav-icon icon-xs me-2"></i> Expenses
                    <span class="nav-arrow ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </span>
                </a>
                <div id="navExpenses" class="collapse {{ $isSubmenuActive(['admin.expenses.*']) ? 'show' : '' }}">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="pie-chart" class="icon-xs me-2"></i> Expense Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.expenses.index') ? 'active' : '' }}" href="{{ route('admin.expenses.index') }}">
                                <i data-feather="list" class="icon-xs me-2"></i> All Expenses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.expenses.create') }}">
                                <i data-feather="plus-square" class="icon-xs me-2"></i> Add Expense
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="tag" class="icon-xs me-2"></i> Expense Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="credit-card" class="icon-xs me-2"></i> Expense Payments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="file-text" class="icon-xs me-2"></i> Expense Reports
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
                    <span class="nav-arrow ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </span>
                </a>
                <div id="navCustomers" class="collapse">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="pie-chart" class="icon-xs me-2"></i> Customer Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="users" class="icon-xs me-2"></i> All Customers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="user-plus" class="icon-xs me-2"></i> Add Customer
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="users" class="icon-xs me-2"></i> Customer Groups
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="book-open" class="icon-xs me-2"></i> Customer Ledger
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="alert-circle" class="icon-xs me-2"></i> Customer Due
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="file-text" class="icon-xs me-2"></i> Customer Reports
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
                    <span class="nav-arrow ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </span>
                </a>
                <div id="navReservations" class="collapse">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="pie-chart" class="icon-xs me-2"></i> Reservation Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="list" class="icon-xs me-2"></i> All Reservations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="plus-square" class="icon-xs me-2"></i> New Reservation
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="clock" class="icon-xs me-2"></i> Pending Reservations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="check-circle" class="icon-xs me-2"></i> Confirmed
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="check" class="icon-xs me-2"></i> Completed
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="x-circle" class="icon-xs me-2"></i> Cancelled
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="settings" class="icon-xs me-2"></i> Reservation Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 14. Staff -->
            <li class="nav-item">
                <a class="nav-link has-arrow {{ $isSubmenuActive(['admin.staff.*', 'admin.roles.*']) ? 'active' : '' }}" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navStaff"
                   aria-expanded="{{ $isSubmenuActive(['admin.staff.*', 'admin.roles.*']) ? 'true' : 'false' }}" aria-controls="navStaff">
                    <i data-feather="user-check" class="nav-icon icon-xs me-2"></i> Staff
                    <span class="nav-arrow ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </span>
                </a>
                <div id="navStaff" class="collapse {{ $isSubmenuActive(['admin.staff.*', 'admin.roles.*']) ? 'show' : '' }}">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="pie-chart" class="icon-xs me-2"></i> Staff Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.staff.index') ? 'active' : '' }}" href="{{ route('admin.staff.index') }}">
                                <i data-feather="users" class="icon-xs me-2"></i> All Staff
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.staff.create') }}">
                                <i data-feather="user-plus" class="icon-xs me-2"></i> Add Staff
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.roles.index') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                                <i data-feather="shield" class="icon-xs me-2"></i> Roles & Permissions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="calendar" class="icon-xs me-2"></i> Attendance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="clock" class="icon-xs me-2"></i> Staff Shifts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="dollar-sign" class="icon-xs me-2"></i> Salaries / Payroll
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="trending-up" class="icon-xs me-2"></i> Staff Performance
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
                    <span class="nav-arrow ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </span>
                </a>
                <div id="navAccounting" class="collapse">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="pie-chart" class="icon-xs me-2"></i> Accounting Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="layers" class="icon-xs me-2"></i> Chart of Accounts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="credit-card" class="icon-xs me-2"></i> Cash & Bank
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="dollar-sign" class="icon-xs me-2"></i> Cash Transactions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="credit-card" class="icon-xs me-2"></i> Bank Transactions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="trending-up" class="icon-xs me-2"></i> Income
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.expenses.index') ? 'active' : '' }}" href="{{ route('admin.expenses.index') }}">
                                <i data-feather="trending-down" class="icon-xs me-2"></i> Expenses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="arrow-down-left" class="icon-xs me-2"></i> Receivables
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="arrow-up-right" class="icon-xs me-2"></i> Payables
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="book" class="icon-xs me-2"></i> Journal Entries
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="file-text" class="icon-xs me-2"></i> General Ledger
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="bar-chart-2" class="icon-xs me-2"></i> Trial Balance
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.reports.profit_loss_report') }}">
                                <i data-feather="pie-chart" class="icon-xs me-2"></i> Profit & Loss
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="bar-chart" class="icon-xs me-2"></i> Balance Sheet
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="activity" class="icon-xs me-2"></i> Cash Flow
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="book-open" class="icon-xs me-2"></i> Day Book
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="file-text" class="icon-xs me-2"></i> Accounting Reports
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
                    <span class="nav-arrow ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </span>
                </a>
                <div id="navReports" class="collapse">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.reports.sales_report') }}">
                                <i data-feather="trending-up" class="icon-xs me-2"></i> Sales Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="shopping-bag" class="icon-xs me-2"></i> Order Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="monitor" class="icon-xs me-2"></i> POS Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="file-text" class="icon-xs me-2"></i> KOT Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.reports.purchase_report') }}">
                                <i data-feather="shopping-bag" class="icon-xs me-2"></i> Purchase Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.reports.stock_report') }}">
                                <i data-feather="archive" class="icon-xs me-2"></i> Inventory Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="dollar-sign" class="icon-xs me-2"></i> Expense Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="users" class="icon-xs me-2"></i> Customer Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="truck" class="icon-xs me-2"></i> Supplier Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="map-pin" class="icon-xs me-2"></i> Delivery Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="user-check" class="icon-xs me-2"></i> Staff Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="percent" class="icon-xs me-2"></i> Tax Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.reports.profit_loss_report') }}">
                                <i data-feather="pie-chart" class="icon-xs me-2"></i> Profit & Loss
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="bar-chart" class="icon-xs me-2"></i> Financial Reports
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- 17. Settings -->
            <li class="nav-item">
                <a class="nav-link has-arrow {{ $isSubmenuActive(['admin.website.*']) ? 'active' : '' }}" 
                   href="#!" data-bs-toggle="collapse" data-bs-target="#navSettings"
                   aria-expanded="{{ $isSubmenuActive(['admin.website.*']) ? 'true' : 'false' }}" aria-controls="navSettings">
                    <i data-feather="settings" class="nav-icon icon-xs me-2"></i> Settings
                    <span class="nav-arrow ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </span>
                </a>
                <div id="navSettings" class="collapse {{ $isSubmenuActive(['admin.website.*']) ? 'show' : '' }}">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="home" class="icon-xs me-2"></i> Restaurant Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="map" class="icon-xs me-2"></i> Branch Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.website.edit') ? 'active' : '' }}" href="{{ route('admin.website.edit') }}">
                                <i data-feather="settings" class="icon-xs me-2"></i> General Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="percent" class="icon-xs me-2"></i> Tax Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="credit-card" class="icon-xs me-2"></i> Payment Methods
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="printer" class="icon-xs me-2"></i> Printer Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="file-text" class="icon-xs me-2"></i> Receipt Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="file" class="icon-xs me-2"></i> Invoice Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="bell" class="icon-xs me-2"></i> Notification Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="truck" class="icon-xs me-2"></i> Delivery Settings
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
                    <span class="nav-arrow ms-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </span>
                </a>
                <div id="navSystemSettings" class="collapse">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="users" class="icon-xs me-2"></i> Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.roles.index') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                                <i data-feather="shield" class="icon-xs me-2"></i> Roles & Permissions
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="map" class="icon-xs me-2"></i> Branches
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="grid" class="icon-xs me-2"></i> Modules
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="database" class="icon-xs me-2"></i> Database Backup
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="activity" class="icon-xs me-2"></i> Activity Logs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="file-text" class="icon-xs me-2"></i> Audit Logs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i data-feather="sliders" class="icon-xs me-2"></i> System Configuration
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

        </ul>
    </div>
</nav>

<script>
    // Initialize feather icons with multiple attempts
    function initFeatherIcons() {
        if (typeof feather !== 'undefined') {
            feather.replace();
            return true;
        }
        return false;
    }

    // Try to initialize feather icons immediately
    initFeatherIcons();

    // Try again after DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(initFeatherIcons, 100);
        setTimeout(initFeatherIcons, 500);

        // Handle collapse behavior - allow independent collapsing
        const collapseElements = document.querySelectorAll('.collapse');
        collapseElements.forEach(function(collapse) {
            collapse.addEventListener('show.bs.collapse', function() {
                // Re-initialize feather icons after collapse animation
                setTimeout(initFeatherIcons, 100);
            });
            collapse.addEventListener('shown.bs.collapse', function() {
                // Re-initialize feather icons after collapse is fully shown
                setTimeout(initFeatherIcons, 50);
            });
        });

        // Ensure active submenu is shown on page load
        const activeSubmenu = document.querySelector('.collapse.show');
        if (activeSubmenu) {
            const trigger = document.querySelector('[data-bs-target="#' + activeSubmenu.id + '"]');
            if (trigger) {
                trigger.setAttribute('aria-expanded', 'true');
                trigger.classList.add('active');
            }
            // Re-initialize feather icons for active submenu
            setTimeout(initFeatherIcons, 200);
        }
    });

    // Final attempt after window load
    window.addEventListener('load', function() {
        setTimeout(initFeatherIcons, 200);
        setTimeout(initFeatherIcons, 500);
    });

    // Also initialize on Turbo/HTMX navigation if using those
    document.addEventListener('turbo:load', initFeatherIcons);
    document.addEventListener('htmx:afterSettle', initFeatherIcons);
</script>
