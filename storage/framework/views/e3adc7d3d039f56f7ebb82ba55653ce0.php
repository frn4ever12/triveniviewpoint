<?php if(session('success')): ?>
    <script>
        showToast('success', '<?php echo e(session('success')); ?>');
    </script>
<?php endif; ?>

<?php if(session('error')): ?>
    <script>
        showToast('error', '<?php echo e(session('error')); ?>');
    </script>
<?php endif; ?><?php /**PATH D:\DMCRESTRO\singlerestro-main\resources\views/admin/includes/toaster.blade.php ENDPATH**/ ?>