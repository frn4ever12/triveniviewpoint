<div class="preloader" id="preloader">
    <div class="preloader-logo">
        <?php if(isset($logoUrl) && $logoUrl): ?>
            <img src="<?php echo e($logoUrl); ?>" alt="<?php echo e($siteName ?? ''); ?>" style="height:48px;">
        <?php else: ?>
            <i class="bi bi-cup-hot-fill"></i>
        <?php endif; ?>
    </div>
    <div class="preloader-spinner"></div>
</div>
<?php /**PATH D:\DMCRESTRO\singlerestro-main\resources\views/frontend/includes/pre-loader.blade.php ENDPATH**/ ?>