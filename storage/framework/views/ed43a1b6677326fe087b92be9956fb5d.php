<?php $__env->startSection('title', 'QR Sticker Labels'); ?>
<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <?php if (isset($component)) { $__componentOriginal269900abaed345884ce342681cdc99f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal269900abaed345884ce342681cdc99f6 = $attributes; } ?>
<?php $component = App\View\Components\Breadcrumb::resolve(['title' => 'QR Sticker Labels','route' => 'admin.digital-menu.index'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                    <h5 class="mb-1"><i class="bi bi-upc-scan me-2"></i> Table QR Sticker Labels</h5>
                    <p class="text-muted mb-0 small">Print these labels on sticker paper (A4, 65x40mm per label) and paste on each table.</p>
                </div>
                <button onclick="window.print()" class="btn btn-danger">
                    <i class="bi bi-printer me-1"></i> Print Stickers
                </button>
            </div>
        </div>
    </div>

    <div id="sticker-grid" class="row g-2">
        <?php $__currentLoopData = $tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-4 col-md-3 col-lg-2 sticker-cell">
            <div class="sticker-label">
                <div class="sticker-header">
                    <?php echo e($table->name); ?>

                </div>
                <div class="sticker-qr" id="qr-<?php echo e($table->id); ?>"></div>
                <div class="sticker-footer">
                    Scan to view menu
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<style>
.sticker-label {
    border: 1.5px solid #000;
    border-radius: 2px;
    background: #fff;
    padding: 6px 4px 4px;
    text-align: center;
    page-break-inside: avoid;
    break-inside: avoid;
    font-family: 'Courier New', monospace;
}
.sticker-header {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #000;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.sticker-qr {
    display: flex;
    justify-content: center;
}
.sticker-qr img, .sticker-qr canvas {
    width: 80px !important;
    height: 80px !important;
}
.sticker-footer {
    font-size: 6.5px;
    color: #555;
    margin-top: 1px;
    text-transform: uppercase;
    letter-spacing: .3px;
}
@media print {
    body { background: #fff !important; }
    .no-print, .navbar, .sidebar, #sidebar, .breadcrumb, .card:first-of-type,
    .container-fluid > .card, footer, .navbar-vertical { display: none !important; }
    .container-fluid { padding: 0 !important; max-width: 100% !important; }
    @page { size: A4; margin: 6mm; }
    #sticker-grid {
        display: flex !important;
        flex-wrap: wrap !important;
    }
    .sticker-cell {
        width: 25% !important;
        flex: 0 0 25% !important;
        max-width: 25% !important;
        padding: 2mm !important;
    }
    .sticker-label {
        border: 1.5px solid #000 !important;
        box-shadow: none !important;
        padding: 4px 3px 3px !important;
    }
    .sticker-qr img, .sticker-qr canvas {
        width: 48px !important;
        height: 48px !important;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
<script>
<?php $__currentLoopData = $tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $table): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
new QRCode(document.getElementById("qr-<?php echo e($table->id); ?>"), {
    text: "<?php echo e(str_replace('127.0.0.1:8000', '192.168.1.84:8000', route('digitalmenu-table', ['slug' => auth()->user()->tenant->slug ?? 'default', 'table' => $table->id]))); ?>",
    width: 80,
    height: 80,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.L
});
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.includes.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DMCRESTRO\singlerestro-main\resources\views/admin/digitalmenu/index.blade.php ENDPATH**/ ?>