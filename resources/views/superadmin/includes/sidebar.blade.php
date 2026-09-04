<nav class="navbar-vertical navbar bg-danger">
    <div>
        <a class="navbar-brand" href="{{ route('superadmin.dashboard') }}">
            <span>Superadmin Panel</span>
        </a>
    </div>
    <div class="nav-scroller">
        <ul class="navbar-nav flex-column" id="sideNavbar">

            <li class="nav-item">
                <div class="navbar-heading">Main</div>
            </li>
            
            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}"
                    href="{{ route('superadmin.dashboard') }}">
                    <i data-feather="grid" class="nav-icon icon-xs me-2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <div class="navbar-heading">Tenant Management</div>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('superadmin.tenants.*') ? 'active' : '' }}"
                    href="{{ route('superadmin.tenants.index') }}">
                    <i data-feather="building" class="nav-icon icon-xs me-2"></i> Tenants
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('superadmin.subscriptions.*') ? 'active' : '' }}"
                    href="{{ route('superadmin.subscriptions.index') }}">
                    <i data-feather="credit-card" class="nav-icon icon-xs me-2"></i> Subscriptions
                </a>
            </li>

            <li class="nav-item">
                <div class="navbar-heading">Subscription Plans</div>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('superadmin.subscription-plans.*') ? 'active' : '' }}"
                    href="{{ route('superadmin.subscription-plans.index') }}">
                    <i data-feather="layers" class="nav-icon icon-xs me-2"></i> Plans
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('superadmin.plan-features.*') ? 'active' : '' }}"
                    href="#">
                    <i data-feather="check-square" class="nav-icon icon-xs me-2"></i> Plan Features
                </a>
            </li>

            <li class="nav-item">
                <div class="navbar-heading">User Management</div>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('superadmin.roles.*') ? 'active' : '' }}"
                    href="{{ route('superadmin.roles.index') }}">
                    <i data-feather="shield" class="nav-icon icon-xs me-2"></i> Roles & Permissions
                </a>
            </li>

            <li class="nav-item">
                <div class="navbar-heading">Homepage CMS</div>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('superadmin.abouts.*') ? 'active' : '' }}"
                    href="{{ route('superadmin.abouts.index') }}">
                    <i data-feather="file-text" class="nav-icon icon-xs me-2"></i> About Section
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('superadmin.banners.*') ? 'active' : '' }}"
                    href="{{ route('superadmin.banners.index') }}">
                    <i data-feather="image" class="nav-icon icon-xs me-2"></i> Banners
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link has-arrow {{ request()->routeIs('superadmin.website.*') ? 'active' : '' }}"
                    href="{{ route('superadmin.website.edit') }}">
                    <i data-feather="settings" class="nav-icon icon-xs me-2"></i> Website Settings
                </a>
            </li>

        </ul>
    </div>
</nav>
