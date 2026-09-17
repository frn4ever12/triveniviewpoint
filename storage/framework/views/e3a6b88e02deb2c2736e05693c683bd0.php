<div class="btn-group" role="group">
    <a href="<?php echo e(route('admin.categories.edit', $category->id)); ?>" class="btn btn-sm btn-warning" title="Edit">
        <i class="bi bi-pencil"></i>
    </a>
    <button type="button" class="btn btn-sm btn-danger delete-btn" title="Delete"
         data-name="<?php echo e($category->name); ?>"
        data-route="<?php echo e(route('admin.categories.destroy', $category->id)); ?>"> <i class="bi bi-trash"></i>
    </button>
</div>
<?php /**PATH D:\DMCRESTRO\singlerestro-main\resources\views/admin/category/datatables-actions.blade.php ENDPATH**/ ?>