<section id="digital" class="digital-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="digital-card" data-aos="fade-up" data-aos-duration="1000">
                    <span class="section-tag justify-content-center">Digital Experience</span>
                    <h3>Download Our App</h3>
                    <p>Install dmcrestro as a Progressive Web App (PWA) on your device for the best experience, or scan to access our digital menu.</p>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="qr-card app-qr">
                                <div class="app-badge">
                                    <i class="bi bi-download"></i> Our App
                                </div>
                                <div class="digital-qr-box">
                                    <div id="qrcode-app"></div>
                                </div>
                                <p class="text-center small text-muted mt-2">Scan to install dmcrestro app</p>
                                <div class="app-features">
                                    <span><i class="bi bi-check-circle"></i> Offline Access</span>
                                    <span><i class="bi bi-check-circle"></i> Fast Performance</span>
                                    <span><i class="bi bi-check-circle"></i> Push Notifications</span>
                                </div>
                            </div>
                        </div>
                        <?php if(isset($tenant)): ?>
                        <div class="col-md-6">
                            <div class="qr-card menu-qr">
                                <div class="menu-badge">
                                    <i class="bi bi-grid"></i> Digital Menu
                                </div>
                                <div class="digital-qr-box">
                                    <div id="qrcode-menu"></div>
                                </div>
                                <p class="text-center small text-muted mt-2">Scan to view menu</p>
                                <div class="menu-features">
                                    <span><i class="bi bi-check-circle"></i> Browse Categories</span>
                                    <span><i class="bi bi-check-circle"></i> View Dishes</span>
                                    <span><i class="bi bi-check-circle"></i> Order Online</span>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="digital-steps mt-4">
                        <div class="digital-step">
                            <div class="digital-step-icon">
                                <i class="bi bi-camera"></i>
                            </div>
                            <h5>Open Camera</h5>
                            <p>Launch your phone's camera app</p>
                        </div>
                        <div class="digital-step">
                            <div class="digital-step-icon">
                                <i class="bi bi-qr-code-scan"></i>
                            </div>
                            <h5>Scan QR Code</h5>
                            <p>Point your camera at the code</p>
                        </div>
                        <div class="digital-step">
                            <div class="digital-step-icon">
                                <i class="bi bi-download"></i>
                            </div>
                            <h5>Install Our App</h5>
                            <p>Add to home screen for quick access</p>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4 justify-content-center">
                        <a href="/" class="hero-btn-primary d-inline-flex">
                            <i class="bi bi-download me-2"></i> Install Our App
                        </a>
                        <?php if(isset($tenant)): ?>
                        <a href="<?php echo e(route('digitalmenu', $tenant->slug)); ?>" class="hero-btn-secondary d-inline-flex">
                            <i class="bi bi-grid me-2"></i> Digital Menu
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
<script>
    // QR Code for PWA App Installation
    new QRCode(document.getElementById("qrcode-app"), {
        text: window.location.href,
        width: 180,
        height: 180
    });

    <?php if(isset($tenant)): ?>
    // QR Code for Digital Menu
    new QRCode(document.getElementById("qrcode-menu"), {
        text: "<?php echo e(route('digitalmenu', $tenant->slug)); ?>",
        width: 180,
        height: 180
    });
    <?php endif; ?>
</script>

<style>
.qr-card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.1);
    position: relative;
}

.app-qr {
    border: 2px solid #dc3545;
    background: rgba(220, 53, 69, 0.1);
}

.menu-qr {
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.app-badge {
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: #fff;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
}

.menu-badge {
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
}

.qr-card h5 {
    color: #fff;
    margin-bottom: 15px;
    font-weight: 600;
    margin-top: 10px;
}

.app-features, .menu-features {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 15px;
    text-align: left;
}

.app-features span, .menu-features span {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    gap: 6px;
}

.app-features i, .menu-features i {
    color: #28a745;
    font-size: 10px;
}

.hero-btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    padding: 12px 28px;
    border-radius: 30px;
    text-decoration: none;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s;
}

.hero-btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    text-decoration: none;
}
</style>
<?php /**PATH D:\DMCRESTRO\singlerestro-main\resources\views/frontend/welcome/qrcode.blade.php ENDPATH**/ ?>