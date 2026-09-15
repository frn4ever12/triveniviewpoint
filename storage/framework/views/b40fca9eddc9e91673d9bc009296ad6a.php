<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <?php echo $__env->make('admin.includes.top', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldContent('title'); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
    <style>
        @media (max-width: 767.98px) {
            #db-wrapper {
                flex-direction: column;
            }
            .header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1050;
                background: white;
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
                padding-top: 70px;
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
                padding: 70px 0.5rem 1rem 0.5rem;
            }
            .container-fluid {
                padding: 0 0.5rem;
            }
        }
    </style>
</head>

<body>
    <?php
        $currentTenant = auth()->user()?->tenant;
        $trialEndsAt = $currentTenant ? $currentTenant->trial_ends_at : null;
        $daysRemaining = $trialEndsAt ? round(now()->diffInDays($trialEndsAt, false)) : 0;
        
        // Get package name from subscription
        $packageName = 'Free Trial';
        if ($currentTenant && $currentTenant->subscription) {
            $packageName = $currentTenant->subscription->plan->name ?? 'Free Trial';
        }
    ?>
    
    <div id="db-wrapper">
        <?php echo $__env->make('admin.includes.sidebar-new', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div id="sidebar-overlay" class="d-none"></div>
        <div id="page-content">
            <?php echo $__env->make('admin.includes.header-new', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="mt-10  pb-18">
                <?php echo $__env->yieldContent('content'); ?>
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

            // Initialize feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Handle sidebar collapse behavior
            const collapseElements = document.querySelectorAll('.collapse');
            collapseElements.forEach(function(collapse) {
                collapse.addEventListener('show.bs.collapse', function() {
                    // Re-initialize feather icons when submenu opens
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }

                    // Close other collapses in the same parent
                    const parent = this.closest('.navbar-nav');
                    if (parent) {
                        const otherCollapses = parent.querySelectorAll('.collapse.show');
                        otherCollapses.forEach(function(otherCollapse) {
                            if (otherCollapse !== this) {
                                const bsCollapse = bootstrap.Collapse.getInstance(otherCollapse);
                                if (bsCollapse) {
                                    bsCollapse.hide();
                                }
                            }
                        }.bind(this));
                    }
                });

                collapse.addEventListener('hidden.bs.collapse', function() {
                    // Re-initialize feather icons when submenu closes
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                });
            });

            // Ensure chevron icons are visible
            const navArrows = document.querySelectorAll('.nav-arrow');
            navArrows.forEach(function(arrow) {
                arrow.style.display = 'inline-block';
                arrow.style.width = '16px';
                arrow.style.height = '16px';
            });
        });
    </script>
    
    <?php echo $__env->make('admin.includes.bottom', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
    <?php echo $__env->make('admin.includes.toaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Subscription Modal -->
    <div class="modal fade" id="subscriptionModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; border-radius: 12px 12px 0 0;">
                    <h5 class="modal-title">Digital Profile</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if($currentTenant): ?>
                    <div class="subscription-details">
                        <div class="subscription-header-modal">
                            <div class="subscription-avatar-modal">
                                <?php if($currentTenant->logo): ?>
                                    <img src="<?php echo e($currentTenant->logo); ?>" alt="<?php echo e($currentTenant->name); ?>">
                                <?php else: ?>
                                    <span><?php echo e(substr($currentTenant->name, 0, 2)); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="subscription-info-modal">
                                <h4><?php echo e($currentTenant->name); ?></h4>
                                <p class="text-muted"><?php echo e($currentTenant->city ?? 'Unknown'); ?></p>
                                <span class="package-badge"><?php echo e($packageName); ?></span>
                            </div>
                        </div>

                        <div class="subscription-status-modal mt-4">
                            <div class="row">
                                <div class="col-6">
                                    <div class="status-box">
                                        <h6 class="text-muted small">Days Remaining</h6>
                                        <h3 class="text-primary"><?php echo e($daysRemaining > 0 ? $daysRemaining : 0); ?></h3>
                                        <p class="text-muted small">days</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="status-box">
                                        <h6 class="text-muted small">Status</h6>
                                        <h3 class="<?php echo e($daysRemaining > 0 ? 'text-success' : 'text-danger'); ?>">
                                            <?php echo e($daysRemaining > 0 ? 'Active' : 'Expired'); ?>

                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <?php if($trialEndsAt): ?>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <small class="text-muted">Active since: <?php echo e($currentTenant->created_at->format('d M Y')); ?></small>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-muted">Expires: <?php echo e($trialEndsAt->format('d M Y')); ?></small>
                                </div>
                            </div>
                            <?php endif; ?>
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
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</body>

</html><?php /**PATH D:\DMCRESTRO\singlerestro-main\resources\views/admin/includes/main.blade.php ENDPATH**/ ?>