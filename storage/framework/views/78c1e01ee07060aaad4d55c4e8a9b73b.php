<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 no-print">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e293b;font-size:1.25rem;"><?php echo e($title); ?></h4>
        <?php if($slot->isNotEmpty()): ?>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0" style="background:transparent;padding:0;font-size:0.82rem;">
                    <?php echo e($slot); ?>

                </ol>
            </nav>
        <?php endif; ?>
    </div>
    <?php if($route): ?>
        <a href="<?php echo e(route($route)); ?>" class="btn btn-danger d-inline-flex align-items-center gap-2 shadow-sm" style="border-radius:10px;font-weight:600;font-size:0.85rem;padding:0.5rem 1.25rem;">
            <?php if($icon): ?>
                <i class="<?php echo e($icon); ?>"></i>
            <?php endif; ?>
            <span class="d-none d-sm-inline"><?php echo e($button); ?></span>
        </a>
    <?php endif; ?>
</div>
<?php /**PATH D:\DMCRESTRO\singlerestro-main\resources\views/components/breadcrumb.blade.php ENDPATH**/ ?>