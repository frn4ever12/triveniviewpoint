<nav class="navbar-restaurant" id="mainNav">
    <div class="container">
        <div class="nav-inner">
            <!-- Brand -->
            <a href="/" class="nav-brand">
                <?php if(isset($logoUrl) && $logoUrl): ?>
                    <img src="<?php echo e($logoUrl); ?>" alt="<?php echo e($siteName ?? 'dmcrestro'); ?>">
                <?php else: ?>
                    <i class="bi bi-cup-hot-fill"></i>
                <?php endif; ?>
                <span class="brand-text"><?php echo e($siteName ?? 'dmcrestro'); ?></span>
            </a>

            <!-- Desktop nav links (centered) -->
            <ul class="nav-links" id="navLinks">
                <li><a href="/" class="<?php echo e(request()->is('/') ? 'active' : ''); ?>">Home</a></li>
                <?php if(isset($tenant)): ?>
                    <li><a href="#about">About</a></li>
                    <li><a href="#menu">Menu</a></li>
                    <li><a href="<?php echo e(route('digitalmenu', $tenant->slug)); ?>">Digital Menu</a></li>
                <?php else: ?>
                    <li><a href="#about">About</a></li>
                    <li><a href="#pricing">Features & Pricing</a></li>
                <?php endif; ?>
            </ul>

            <!-- Right: CTA Button + Toggle -->
            <div class="nav-actions">
                <?php if(!isset($tenant)): ?>
                    <a href="<?php echo e(route('register.restaurant')); ?>" class="nav-cta-btn bg-primary">
                        <i class="bi bi-plus-circle"></i>
                        <span class="cta-text">Register Restaurant</span>
                    </a>
                <?php else: ?>
                    <a href="#contact" class="nav-cta-btn">
                        <i class="bi bi-envelope"></i>
                        <span class="cta-text">Contact</span>
                    </a>
                <?php endif; ?>
                <a href="<?php echo e(route('login')); ?>" class="nav-cta-btn">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span class="cta-text">Login</span>
                </a>
                <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile menu overlay -->
<div class="mobile-menu-overlay" id="mobileOverlay"></div>

<!-- Mobile menu -->
<div class="mobile-menu" id="mobileMenu">
    <div class="mobile-menu-header">
        <span class="mobile-menu-brand"><?php echo e($siteName ?? 'dmcrestro'); ?></span>
        <button class="mobile-menu-close" id="mobileMenuClose">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <ul class="mobile-menu-links">
        <li><a href="/"><i class="bi bi-house"></i> Home</a></li>
        <?php if(isset($tenant)): ?>
            <li><a href="#about"><i class="bi bi-info-circle"></i> About</a></li>
            <li><a href="#menu"><i class="bi bi-grid"></i> Menu</a></li>
            <li><a href="<?php echo e(route('digitalmenu', $tenant->slug)); ?>"><i class="bi bi-phone"></i> Digital Menu</a></li>
        <?php else: ?>
            <li><a href="#about"><i class="bi bi-info-circle"></i> About</a></li>
            <li><a href="#pricing"><i class="bi bi-tag"></i> Features & Pricing</a></li>
        <?php endif; ?>
        <li><a href="<?php echo e(route('login')); ?>"><i class="bi bi-box-arrow-in-right"></i> Login</a></li>
        <li><a href="<?php echo e(route('register.restaurant')); ?>"><i class="bi bi-plus-circle"></i> Register Restaurant</a></li>
        <li class="mobile-menu-cta">
            <a href="#contact" class="mobile-reserve-btn">
                <i class="bi bi-envelope"></i> Contact Us
            </a>
        </li>
    </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // ── Mobile menu ──
    const toggle = document.getElementById('navToggle');
    const menu = document.getElementById('mobileMenu');
    const overlay = document.getElementById('mobileOverlay');
    const closeBtn = document.getElementById('mobileMenuClose');

    function openMobile() {
        menu.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMobile() {
        menu.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (toggle) toggle.addEventListener('click', openMobile);
    if (closeBtn) closeBtn.addEventListener('click', closeMobile);
    if (overlay) overlay.addEventListener('click', closeMobile);
    document.querySelectorAll('.mobile-menu-links a').forEach(l => l.addEventListener('click', closeMobile));

    // ── Navbar scroll effect ──
    const nav = document.getElementById('mainNav');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 60) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });
});
</script>
<?php /**PATH D:\DMCRESTRO\singlerestro-main\resources\views/frontend/includes/navbar.blade.php ENDPATH**/ ?>