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

<nav class="navbar-vertical navbar bg-danger">
    <div>
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <span>{{ $siteName ?? 'dmcrestro' }}</span>
        </a>
    </div>
    
    <div class="nav-scroller">
        <ul class="navbar-nav flex-column" id="sideNavbar">

            @if($currentTenant)
            <li class="nav-item">
                <div class="subscription-card-simple" data-bs-toggle="modal" data-bs-target="#subscriptionModal">
                    <div class="subscription-user-info">
                        <div class="user-avatar">
                            @if($currentTenant->logo)
                                <img src="{{ $currentTenant->logo }}" alt="{{ $currentTenant->name }}">
                            @else
                                <span>{{ substr(auth()->user()->name, 0, 2) }}</span>
                            @endif
                        </div>
                        <div class="user-details">
                            <h6 class="user-name">{{ auth()->user()->name }}</h6>
                            <p class="user-package">{{ $packageName }}</p>
                        </div>
                    </div>
                </div>
            </li>
            @endif

            <li class="nav-item">
                <div class="navbar-heading">Main</div>
            </li>
            
            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">
                    <i data-feather="grid" class="nav-icon icon-xs me-2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.orders.*') && !request()->routeIs('admin.orders.checkout-dashboard') ? 'active' : '' }}"
                   href="{{ route('admin.orders.index') }}">
                    <i data-feather="layers" class="nav-icon icon-xs me-2"></i> Orders
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.orders.pos') ? 'active' : '' }}"
                   href="{{ route('admin.orders.pos') }}">
                    <i data-feather="shopping-cart" class="nav-icon icon-xs me-2"></i> POS
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.orders.checkout-dashboard') ? 'active' : '' }}"
                   href="{{ route('admin.orders.checkout-dashboard') }}">
                    <i data-feather="credit-card" class="nav-icon icon-xs me-2"></i> Checkout
                </a>
            </li>

            <li class="nav-item">
                <div class="navbar-heading">Menu Management</div>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"
                   href="{{ route('admin.categories.index') }}">
                    <i data-feather="menu" class="nav-icon icon-xs me-2"></i> Categories
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.menu-items.*') ? 'active' : '' }}"
                   href="{{ route('admin.menu-items.index') }}">
                    <i data-feather="package" class="nav-icon icon-xs me-2"></i> Menu Items
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.recipes.*') ? 'active' : '' }}"
                   href="{{ route('admin.recipes.index') }}">
                    <i data-feather="book-open" class="nav-icon icon-xs me-2"></i> Recipes
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.tables.*') ? 'active' : '' }}"
                   href="{{ route('admin.tables.index') }}">
                    <i data-feather="table" class="nav-icon icon-xs me-2"></i> Tables
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}"
                   href="{{ route('admin.rooms.index') }}">
                    <i data-feather="home" class="nav-icon icon-xs me-2"></i> Rooms
                </a>
            </li>

            <li class="nav-item">
                <div class="navbar-heading">Inventory</div>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow" href="#!" data-bs-toggle="collapse" data-bs-target="#navInventory"
                   aria-expanded="false" aria-controls="navInventory">
                    <i data-feather="box" class="nav-icon icon-xs me-2"></i> Inventory
                </a>
                <div id="navInventory" class="collapse" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link has-arrow {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                                Items
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow {{ request()->routeIs('admin.reports.stock_report') ? 'active' : '' }}" href="{{ route('admin.reports.stock_report') }}">
                                Stock Report
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow {{ request()->routeIs('admin.stock-adjustments.*') ? 'active' : '' }}" href="{{ route('admin.stock-adjustments.index') }}">
                                Stock Adjustments
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
                <div class="navbar-heading">Financials</div>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.purchases.*') ? 'active' : '' }}"
                   href="{{ route('admin.purchases.index') }}">
                    <i data-feather="shopping-bag" class="nav-icon icon-xs me-2"></i> Purchase
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}"
                   href="{{ route('admin.expenses.index') }}">
                    <i data-feather="dollar-sign" class="nav-icon icon-xs me-2"></i> Expense
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.kitchen-display.*') ? 'active' : '' }}"
                   href="{{ route('admin.kitchen-display.index') }}">
                    <i data-feather="users" class="nav-icon icon-xs me-2"></i> KTD
                </a>
            </li>

            <li class="nav-item">
                <div class="navbar-heading">Reports</div>
            </li>
            <li class="nav-item">
                <a class="nav-link has-arrow" href="#!" data-bs-toggle="collapse" data-bs-target="#navMenuLevel"
                   aria-expanded="false" aria-controls="navMenuLevel">
                    <i data-feather="clipboard" class="nav-icon icon-xs me-2"></i> Reports
                </a>
                <div id="navMenuLevel" class="collapse" data-bs-parent="#sideNavbar">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="{{ route('admin.reports.purchase_report') }}">
                                Purchase Report
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="{{ route('admin.reports.sales_report') }}">
                                Monthly Sales Report
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="{{ route('admin.reports.stock_report') }}">
                                Stock Report
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="{{ route('admin.reports.expense_report') }}">
                                Expense Report
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow" href="{{ route('admin.reports.profit_loss_report') }}">
                                P&L Report
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow {{ request()->routeIs('admin.reports.financial_track') ? 'active' : '' }}" href="{{ route('admin.reports.financial_track') }}">
                                Financial Track
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <div class="navbar-heading">Administration</div>
            </li>
            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.billing.*') ? 'active' : '' }}"
                   href="{{ route('admin.billing.index') }}">
                    <i data-feather="credit-card" class="nav-icon icon-xs me-2"></i> Billing & Subscription
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"
                   href="{{ route('admin.roles.index') }}">
                    <i data-feather="shield" class="nav-icon icon-xs me-2"></i> Roles & Permissions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}"
                   href="{{ route('admin.staff.index') }}">
                    <i data-feather="user-check" class="nav-icon icon-xs me-2"></i> Staff
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}"
                   href="{{ route('admin.suppliers.index') }}">
                    <i data-feather="user-check" class="nav-icon icon-xs me-2"></i> Suppliers
                </a>
            </li>

            <li class="nav-item">
                <div class="navbar-heading">Tools</div>
            </li>
            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.digital-menu.*') ? 'active' : '' }}"
                   href="{{ route('admin.digital-menu.index') }}">
                    <i data-feather="grid" class="nav-icon icon-xs me-2"></i> Digital Menu
                </a>
            </li>

            <li class="nav-item">
                <div class="navbar-heading">Website Settings</div>
            </li>
            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.website.*') ? 'active' : '' }}"
                   href="{{ route('admin.website.edit') }}">
                    <i data-feather="settings" class="nav-icon icon-xs me-2"></i> Settings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.units.*') ? 'active' : '' }}"
                   href="{{ route('admin.units.index') }}">
                    <i data-feather="tag" class="nav-icon icon-xs me-2"></i> Units
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.labels.*') ? 'active' : '' }}"
                   href="{{ route('admin.labels.index') }}">
                    <i data-feather="database" class="nav-icon icon-xs me-2"></i> Labels
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}"
                   href="{{ route('admin.banners.index') }}">
                    <i data-feather="image" class="nav-icon icon-xs me-2"></i> Banners
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}"
                   href="{{ route('admin.contacts.index') }}">
                    <i data-feather="message-circle" class="nav-icon icon-xs me-2"></i> Contacts
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('admin.abouts.*') ? 'active' : '' }}"
                   href="{{ route('admin.abouts.index') }}">
                    <i data-feather="user" class="nav-icon icon-xs me-2"></i> About Us
                </a>
            </li>

        </ul>
    </div>
</nav>