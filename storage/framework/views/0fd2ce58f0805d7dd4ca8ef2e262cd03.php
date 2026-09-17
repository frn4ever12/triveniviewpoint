<?php $__env->startSection('title', 'Create Menu Item'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'Create New Menu Item','route' => 'admin.menu-items.index','button' => 'Back to List','icon' => 'bi-arrow-left'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Breadcrumb::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal269900abaed345884ce342681cdc99f6)): ?>
<?php $attributes = $__attributesOriginal269900abaed345884ce342681cdc99f6; ?>
<?php unset($__attributesOriginal269900abaed345884ce342681cdc99f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal269900abaed345884ce342681cdc99f6)): ?>
<?php $component = $__componentOriginal269900abaed345884ce342681cdc99f6; ?>
<?php unset($__componentOriginal269900abaed345884ce342681cdc99f6); ?>
<?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form action="<?php echo e(route('admin.menu-items.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>

                    <h5 class="mb-3">Basic Information</h5>
                    <div class="row g-3">
                        <div class="col-md-6 col-sm-12">
                            <label for="name" class="form-label">Item Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name"
                                name="name" value="<?php echo e(old('name')); ?>" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">Select Category</option>
                                <?php $__currentLoopData = \App\Models\Category::get(['id', 'name']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat->id); ?>" <?php echo e(old('category_id') == $cat->id ? 'selected' : ''); ?>>
                                        <?php echo e($cat->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description"
                                name="description" rows="3"><?php echo e(old('description')); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Financial Information</h5>
                    <div class="row g-3">
                        <div class="col-md-4 col-sm-12">
                            <label for="price" class="form-label">Selling Price <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="price" name="price" value="<?php echo e(old('price')); ?>" required oninput="calculateFinancials()">
                            <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <label class="form-label">Final Price</label>
                            <input type="text" class="form-control" id="final_price_display" readonly>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Discount Information</h5>
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-12">
                            <label for="discount_type" class="form-label">Discount Type</label>
                            <select class="form-select <?php $__errorArgs = ['discount_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="discount_type" name="discount_type" onchange="calculateFinancials()">
                                <option value="">No Discount</option>
                                <option value="amount" <?php echo e(old('discount_type') == 'amount' ? 'selected' : ''); ?>>Fixed Amount</option>
                                <option value="percentage" <?php echo e(old('discount_type') == 'percentage' ? 'selected' : ''); ?>>Percentage</option>
                            </select>
                            <?php $__errorArgs = ['discount_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <label for="discount_value" class="form-label">Discount Value</label>
                            <input type="number" step="0.01" class="form-control <?php $__errorArgs = ['discount_value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="discount_value" name="discount_value" value="<?php echo e(old('discount_value')); ?>" oninput="calculateFinancials()">
                            <?php $__errorArgs = ['discount_value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Additional Information</h5>
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-12">
                            <label for="preparation_time" class="form-label">Preparation Time</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['preparation_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="preparation_time" name="preparation_time" value="<?php echo e(old('preparation_time')); ?>" placeholder="e.g., 15 mins">
                            <?php $__errorArgs = ['preparation_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <label for="image" class="form-label">Item Image</label>
                            <input type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="image"
                                name="image" accept="image/*">
                            <div class="form-text">Max size: 2MB. Recommended: 800x600px</div>
                            <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_vegetarian" name="is_vegetarian"
                                    value="1" <?php echo e(old('is_vegetarian') ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="is_vegetarian">Vegetarian</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                    value="1" <?php echo e(old('is_featured') ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="is_featured">Featured Item</label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6 col-sm-12">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="status" name="status" required>
                                <?php $__currentLoopData = \App\Enums\CommonStatusEnum::cases(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($status->value); ?>"
                                        <?php echo e(old('status') == $status->value ? 'selected' : ''); ?>>
                                        <?php echo e($status->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i> Create Menu Item
                        </button>
                        <a href="<?php echo e(route('admin.menu-items.index')); ?>" class="btn btn-danger">
                            <i class="bi bi-x-lg me-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function calculateFinancials() {
        const price = parseFloat(document.getElementById('price').value) || 0;
        const discountType = document.getElementById('discount_type').value;
        const discountValue = parseFloat(document.getElementById('discount_value').value) || 0;

        let finalPrice = price;
        let discountAmount = 0;
        if (discountType && discountValue > 0) {
            if (discountType === 'percentage') {
                discountAmount = (finalPrice * discountValue) / 100;
            } else {
                discountAmount = discountValue;
            }
        }
        finalPrice = finalPrice - discountAmount;
        document.getElementById('final_price_display').value = finalPrice.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', function() {
        calculateFinancials();
        document.getElementById('price').addEventListener('input', calculateFinancials);
        document.getElementById('discount_type').addEventListener('change', calculateFinancials);
        document.getElementById('discount_value').addEventListener('input', calculateFinancials);
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.includes.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DMCRESTRO\singlerestro-main\resources\views/admin/menu-item/create.blade.php ENDPATH**/ ?>