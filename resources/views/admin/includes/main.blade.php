<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.includes.top')
    @yield('title')
    @stack('styles')
    <style>
        @media (max-width: 767.98px) {
            #db-wrapper {
                flex-direction: column;
            }
            .navbar-vertical {
                position: fixed;
                left: -280px;
                top: 0;
                height: 100vh;
                width: 280px;
                z-index: 1040;
                transition: left 0.3s ease;
                overflow-y: auto;
            }
            .navbar-vertical.show {
                left: 0;
            }
            #sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1039;
            }
            #sidebar-overlay.d-none {
                display: none !important;
            }
            #page-content {
                margin-left: 0 !important;
                padding-top: 60px;
                width: 100%;
            }
            .mt-10 {
                margin-top: 1rem !important;
            }
            .pb-18 {
                padding-bottom: 1rem !important;
            }
            .container-fluid {
                padding: 0 0.75rem;
            }
        }

        @media (max-width: 575.98px) {
            .navbar-vertical {
                width: 260px;
                left: -260px;
            }
            #page-content {
                padding: 60px 0.5rem 1rem 0.5rem;
            }
            .container-fluid {
                padding: 0 0.5rem;
            }
        }
    </style>
</head>

<body>
    @php
        $currentTenant = auth()->user()?->tenant;
        $trialEndsAt = $currentTenant ? $currentTenant->trial_ends_at : null;
        $daysRemaining = $trialEndsAt ? round(now()->diffInDays($trialEndsAt, false)) : 0;
        
        // Get package name from subscription
        $packageName = 'Free Trial';
        if ($currentTenant && $currentTenant->subscription) {
            $packageName = $currentTenant->subscription->plan->name ?? 'Free Trial';
        }
    @endphp
    
    <div id="db-wrapper">
        @include('admin.includes.sidebar-new')
        <div id="sidebar-overlay" class="d-none"></div>
        <div id="page-content">
            @include('admin.includes.header-new')
            <div class="mt-10  pb-18">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navToggle = document.getElementById('nav-toggle');
            const sidebar = document.querySelector('.navbar-vertical');
            const overlay = document.getElementById('sidebar-overlay');

            if (navToggle && sidebar && overlay) {
                navToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('d-none');
                });

                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.add('d-none');
                });
            }
        });
    </script>
    
    @include('admin.includes.bottom')
    @stack('scripts')
    @include('admin.includes.toaster')

    <!-- Subscription Modal -->
    <div class="modal fade" id="subscriptionModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 12px 12px 0 0;">
                    <h5 class="modal-title">Digital Profile</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($currentTenant)
                    <div class="subscription-details">
                        <div class="subscription-header-modal">
                            <div class="subscription-avatar-modal">
                                @if($currentTenant->logo)
                                    <img src="{{ $currentTenant->logo }}" alt="{{ $currentTenant->name }}">
                                @else
                                    <span>{{ substr($currentTenant->name, 0, 2) }}</span>
                                @endif
                            </div>
                            <div class="subscription-info-modal">
                                <h4>{{ $currentTenant->name }}</h4>
                                <p class="text-muted">{{ $currentTenant->city ?? 'Unknown' }}</p>
                                <span class="package-badge">{{ $packageName }}</span>
                            </div>
                        </div>

                        <div class="subscription-status-modal mt-4">
                            <div class="row">
                                <div class="col-6">
                                    <div class="status-box">
                                        <h6 class="text-muted small">Days Remaining</h6>
                                        <h3 class="text-primary">{{ $daysRemaining > 0 ? $daysRemaining : 0 }}</h3>
                                        <p class="text-muted small">days</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="status-box">
                                        <h6 class="text-muted small">Status</h6>
                                        <h3 class="{{ $daysRemaining > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $daysRemaining > 0 ? 'Active' : 'Expired' }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            @if($trialEndsAt)
                            <div class="row mt-3">
                                <div class="col-6">
                                    <small class="text-muted">Active since: {{ $currentTenant->created_at->format('d M Y') }}</small>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-muted">Expires: {{ $trialEndsAt->format('d M Y') }}</small>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="packages-section mt-4">
                            <h5 class="mb-3">Upgrade Your Plan</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="package-card package-basic">
                                        <h6>Basic</h6>
                                        <h3 class="text-primary">$29<span class="small text-muted">/mo</span></h3>
                                        <ul class="package-features">
                                            <li><i class="bi bi-check2 text-success me-2"></i>1 Location</li>
                                            <li><i class="bi bi-check2 text-success me-2"></i>Basic POS</li>
                                            <li><i class="bi bi-check2 text-success me-2"></i>50 Orders/mo</li>
                                        </ul>
                                        <button class="btn btn-outline-primary btn-sm w-100">Choose Plan</button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="package-card package-pro">
                                        <div class="popular-badge">Popular</div>
                                        <h6>Pro</h6>
                                        <h3 class="text-primary">$79<span class="small text-muted">/mo</span></h3>
                                        <ul class="package-features">
                                            <li><i class="bi bi-check2 text-success me-2"></i>5 Locations</li>
                                            <li><i class="bi bi-check2 text-success me-2"></i>Advanced POS</li>
                                            <li><i class="bi bi-check2 text-success me-2"></i>Unlimited Orders</li>
                                            <li><i class="bi bi-check2 text-success me-2"></i>Digital Menu</li>
                                        </ul>
                                        <button class="btn btn-primary btn-sm w-100">Choose Plan</button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="package-card package-enterprise">
                                        <h6>Enterprise</h6>
                                        <h3 class="text-primary">$199<span class="small text-muted">/mo</span></h3>
                                        <ul class="package-features">
                                            <li><i class="bi bi-check2 text-success me-2"></i>Unlimited Locations</li>
                                            <li><i class="bi bi-check2 text-success me-2"></i>Full Suite</li>
                                            <li><i class="bi bi-check2 text-success me-2"></i>Priority Support</li>
                                            <li><i class="bi bi-check2 text-success me-2"></i>Custom Integration</li>
                                        </ul>
                                        <button class="btn btn-outline-primary btn-sm w-100">Contact Sales</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</body>

</html>