<?php $__env->startSection('content'); ?>
 <!--  preloader with animation -->
 <?php echo $__env->make('frontend.includes.pre-loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

 <?php if(isset($tenant)): ?>
    <!-- Single Tenant Mode -->
    <!-- Hero carousel with multiple banners -->
    <?php echo $__env->make('frontend.welcome.hero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- About Section -->
    <?php echo $__env->make('frontend.welcome.about', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Menu Section -->
    <?php echo $__env->make('frontend.welcome.menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- QR Code Section -->
    <?php echo $__env->make('frontend.welcome.qrcode', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
 <?php else: ?>
    <!-- Multi-Tenant Listing Mode -->
    <?php echo $__env->make('frontend.welcome.tenants', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Features & Pricing Section -->
    <?php echo $__env->make('frontend.welcome.pricing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- QR Code Section -->
    <?php echo $__env->make('frontend.welcome.qrcode', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
 <?php endif; ?>

 <!-- Contact Section (shown in both modes) -->
 <?php echo $__env->make('frontend.welcome.contact', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.includes.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DMCRESTRO\singlerestro-main\resources\views/welcome.blade.php ENDPATH**/ ?>