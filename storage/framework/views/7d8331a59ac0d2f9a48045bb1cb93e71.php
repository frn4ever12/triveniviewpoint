<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<title><?php echo e($siteName??'RestaurantPro'); ?></title>

<?php if($faviconUrl): ?>
    <link rel="icon" href="<?php echo e($faviconUrl); ?>" type="image/x-icon"/>
<?php endif; ?>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars/css/OverlayScrollbars.min.css">
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars/js/OverlayScrollbars.min.js"></script>


<link rel="stylesheet" href="<?php echo e(asset('assets/css/theme.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('assets/css/datatables.css')); ?>"><?php /**PATH D:\DMCRESTRO\singlerestro-main\resources\views/admin/includes/top.blade.php ENDPATH**/ ?>