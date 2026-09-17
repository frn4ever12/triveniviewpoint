<?php $__env->startSection('ktd-content'); ?>
    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="ktd-card <?php echo e($order->status->value === 'pending' ? 'priority' : ($order->status->value === 'preparing' ? 'preparing' : 'ready')); ?>"
             data-status="<?php echo e($order->status->value); ?>"
             data-order-id="<?php echo e($order->id); ?>">
            <div class="ktd-card-head">
                <div>
                    <div class="order-no">#<?php echo e($order->order_no); ?></div>
                    <div class="table-name">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        <?php echo e($order->table->name ?? 'No Table'); ?>

                        <?php if($order->table && $order->table->no_of_guests): ?>
                            &middot; <?php echo e($order->table->no_of_guests); ?> guests
                        <?php endif; ?>
                    </div>
                </div>
                <span class="badge-status <?php echo e($order->status->value); ?>">
                    <?php echo e(strtoupper($order->status->value === 'pending' ? 'NEW' : $order->status->value)); ?>

                </span>
            </div>
            <div class="ktd-card-body">
                <ul class="item-list">
                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <span class="qty"><?php echo e($item->quantity); ?>x</span>
                            <span class="name"><?php echo e($item->menuItem->name ?? $item->dish->name ?? 'Unknown'); ?></span>
                            <?php if($item->size && $item->size == 0.5): ?>
                                <span class="item-status" style="color:#94a3b8;font-size:.65rem;">Half</span>
                            <?php endif; ?>
                            <span class="item-status <?php echo e($item->status ?? 'pending'); ?>">
                                <?php echo e(strtoupper($item->status ?? 'PENDING')); ?>

                            </span>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <div class="ktd-card-foot">
                <?php if(in_array($order->status->value, ['pending', 'confirmed'])): ?>
                    <button class="btn-action btn-prepare" data-order-id="<?php echo e($order->id); ?>" data-action="prepare">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                        Prepare
                    </button>
                <?php endif; ?>
                <?php if(in_array($order->status->value, ['pending', 'preparing', 'confirmed'])): ?>
                    <button class="btn-action btn-ready" data-order-id="<?php echo e($order->id); ?>" data-action="ready">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Ready
                    </button>
                <?php endif; ?>
                <?php if($order->status->value === 'ready'): ?>
                    <button class="btn-action btn-served" data-order-id="<?php echo e($order->id); ?>" data-action="served">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Served
                    </button>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="ktd-empty">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="opacity:.3;">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            <h3>All Clear</h3>
            <p>No pending orders in the kitchen</p>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.ktd.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DMCRESTRO\singlerestro-main\resources\views/admin/ktd/index.blade.php ENDPATH**/ ?>