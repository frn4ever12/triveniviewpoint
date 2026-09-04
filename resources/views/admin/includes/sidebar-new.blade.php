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
@endphp

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
                    <span class="d-block fw-bold">Shree Foodies</span>
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

            <li class="nav-item">
                <div class="navbar-heading">MAIN</div>
            </li>
            
            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">
                    <i data-feather="grid" class="nav-icon icon-xs me-2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.orders.pos') ? 'active' : '' }}"
                   href="{{ route('admin.orders.pos') }}">
                    <i data-feather="shopping-cart" class="nav-icon icon-xs me-2"></i> POS & Billing
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.orders.*') && !request()->routeIs('admin.orders.pos') ? 'active' : '' }}"
                   href="{{ route('admin.orders.index') }}">
                    <i data-feather="layers" class="nav-icon icon-xs me-2"></i> Orders
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow" href="#!" data-bs-toggle="collapse" data-bs-target="#navDelivery"
                   aria-expanded="false" aria-controls="navDelivery">
                    <i data-feather="truck" class="nav-icon icon-xs me-2"></i> Delivery
                </a>
                <div id="navDelivery" class="collapse" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Delivery Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Delivery Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Drivers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Delivery Map</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.tables.*') ? 'active' : '' }}"
                   href="{{ route('admin.tables.index') }}">
                    <i data-feather="layout" class="nav-icon icon-xs me-2"></i> Tables
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.kitchen-display.*') ? 'active' : '' }}"
                   href="{{ route('admin.kitchen-display.index') }}">
                    <i data-feather="users" class="nav-icon icon-xs me-2"></i> Kitchen / KOT
                </a>
            </li>

            <li class="nav-item">
                <div class="navbar-heading">MENU & SALES</div>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.menu-items.*') ? 'active' : '' }}"
                   href="{{ route('admin.menu-items.index') }}">
                    <i data-feather="package" class="nav-icon icon-xs me-2"></i> Menu Management
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                   href="{{ route('admin.categories.index') }}">
                    <i data-feather="menu" class="nav-icon icon-xs me-2"></i> Categories
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow" href="#!" data-bs-toggle="collapse" data-bs-target="#navModifiers"
                   aria-expanded="false" aria-controls="navModifiers">
                    <i data-feather="plus-circle" class="nav-icon icon-xs me-2"></i> Modifiers & Add-ons
                </a>
                <div id="navModifiers" class="collapse" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Modifiers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Add-ons</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow" href="#!" data-bs-toggle="collapse" data-bs-target="#navCustomers"
                   aria-expanded="false" aria-controls="navCustomers">
                    <i data-feather="user" class="nav-icon icon-xs me-2"></i> Customers
                </a>
                <div id="navCustomers" class="collapse" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Customer List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Reservations</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <div class="navbar-heading">INVENTORY</div>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                    <i data-feather="box" class="nav-icon icon-xs me-2"></i> Products / Items
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow" href="#!" data-bs-toggle="collapse" data-bs-target="#navInventory"
                   aria-expanded="false" aria-controls="navInventory">
                    <i data-feather="archive" class="nav-icon icon-xs me-2"></i> Inventory / Stock
                </a>
                <div id="navInventory" class="collapse" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link has-arrow {{ request()->routeIs('admin.reports.stock_report') ? 'active' : '' }}" href="{{ route('admin.reports.stock_report') }}">
                                Stock Report
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Stock Ledger</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow {{ request()->routeIs('admin.stock-adjustments.*') ? 'active' : '' }}" href="{{ route('admin.stock-adjustments.index') }}">
                                Stock Adjustment
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow {{ request()->routeIs('admin.wastages.*') ? 'active' : '' }}" href="{{ route('admin.wastages.index') }}">
                                Wastage
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow {{ request()->routeIs('admin.kitchen-consumptions.*') ? 'active' : '' }}" href="{{ route('admin.kitchen-consumptions.index') }}">
                                Kitchen Consumption
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.recipes.*') ? 'active' : '' }}"
                   href="{{ route('admin.recipes.index') }}">
                    <i data-feather="book-open" class="nav-icon icon-xs me-2"></i> Recipes & Food Cost
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.purchases.*') ? 'active' : '' }}"
                   href="{{ route('admin.purchases.index') }}">
                    <i data-feather="shopping-bag" class="nav-icon icon-xs me-2"></i> Purchase
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}"
                   href="{{ route('admin.suppliers.index') }}">
                    <i data-feather="users" class="nav-icon icon-xs me-2"></i> Suppliers
                </a>
            </li>

            <li class="nav-item">
                <div class="navbar-heading">ACCOUNTING</div>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow" href="#!" data-bs-toggle="collapse" data-bs-target="#navAccounting"
                   aria-expanded="false" aria-controls="navAccounting">
                    <i data-feather="pie-chart" class="nav-icon icon-xs me-2"></i> Accounting
                </a>
                <div id="navAccounting" class="collapse" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Chart of Accounts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Journal Voucher</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Cash & Bank</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Customer Ledger</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Supplier Ledger</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}" href="{{ route('admin.expenses.index') }}">
                                Expenses
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Payments</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <div class="navbar-heading">REPORTS</div>
            </li>
            <li class="nav-item">
                <a class="nav-link has-arrow" href="#!" data-bs-toggle="collapse" data-bs-target="#navReports"
                   aria-expanded="false" aria-controls="navReports">
                    <i data-feather="file-text" class="nav-icon icon-xs me-2"></i> Reports
                </a>
                <div id="navReports" class="collapse" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <div class="navbar-subheading">Sales Reports</div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="{{ route('admin.reports.sales_report') }}">Daily Sales</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="{{ route('admin.reports.monthly_sales_report') }}">Monthly Sales</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Product Sales</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Category Sales</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Dine-In / Takeaway / Delivery</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Discount / Refund / Cancelled</a>
                        </li>
                        <li class="nav-item">
                            <div class="navbar-subheading">Inventory Reports</div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow {{ request()->routeIs('admin.reports.stock_report') ? 'active' : '' }}" href="{{ route('admin.reports.stock_report') }}">
                                Stock Summary
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Stock Ledger</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Stock Valuation</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow {{ request()->routeIs('admin.wastages.*') ? 'active' : '' }}" href="{{ route('admin.wastages.index') }}">
                                Wastage Report
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Expired / Near Expiry</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow {{ request()->routeIs('admin.recipes.*') ? 'active' : '' }}" href="{{ route('admin.recipes.index') }}">
                                Recipe Consumption
                            </a>
                        </li>
                        <li class="nav-item">
                            <div class="navbar-subheading">Accounting Reports</div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Trial Balance</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="{{ route('admin.reports.profit_loss_report') }}">Profit & Loss</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Balance Sheet</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">General Ledger</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Cash Book</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Bank Book</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Day Book</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Receivable / Payable</a>
                        </li>
                        <li class="nav-item">
                            <div class="navbar-subheading">Purchase Reports</div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="{{ route('admin.reports.purchase_report') }}">Purchase Summary</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Supplier Purchase</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Purchase Return</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Outstanding Purchase</a>
                        </li>
                        <li class="nav-item">
                            <div class="navbar-subheading">Tax Reports</div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">VAT Sales</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">VAT Purchase</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Input VAT / Output VAT</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">VAT Summary</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <div class="navbar-heading">ADMINISTRATION</div>
            </li>
            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}"
                   href="{{ route('admin.staff.index') }}">
                    <i data-feather="user-check" class="nav-icon icon-xs me-2"></i> Staff & Roles
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"
                   href="{{ route('admin.roles.index') }}">
                    <i data-feather="shield" class="nav-icon icon-xs me-2"></i> Roles & Permissions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link has-arrow" href="#!" data-bs-toggle="collapse" data-bs-target="#navRestaurantSettings"
                   aria-expanded="false" aria-controls="navRestaurantSettings">
                    <i data-feather="settings" class="nav-icon icon-xs me-2"></i> Restaurant Settings
                </a>
                <div id="navRestaurantSettings" class="collapse" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link has-arrow {{ request()->routeIs('admin.website.*') ? 'active' : '' }}" href="{{ route('admin.website.edit') }}">
                                General Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Printer Settings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Tax Settings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="#">Payment Methods</a>
                        </li>
                    </ul>
                </div>
            </li>

        </ul>
    </div>
</nav>
