<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo $__env->make('admin.includes.top', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldContent('title'); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
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
        <div id="page-content">
            <?php echo $__env->make('admin.includes.header-new', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="mt-10  pb-18">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>
    
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