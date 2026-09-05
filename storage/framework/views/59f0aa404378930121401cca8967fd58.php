<!-- Modern Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <span class="hero-badge">Restaurant POS Platform</span>
                    <h1 class="hero-title">Manage Your Restaurant with <span class="text-primary">Smart POS</span></h1>
                    <p class="hero-description">
                        The complete restaurant management solution for multi-location restaurants. Streamline operations, boost efficiency, and grow your business with our powerful SaaS platform.
                    </p>
                    <div class="hero-buttons">
                        <a href="<?php echo e(route('register.restaurant')); ?>" class="btn btn-primary btn-lg">
                            <i class="bi bi-rocket-takeoff me-2"></i>Start Free Trial
                        </a>
                        <a href="#restaurants" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-eye me-2"></i>View Restaurants
                        </a>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number"><?php echo e($tenants->count()); ?>+</div>
                            <div class="stat-label">Restaurants</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">500+</div>
                            <div class="stat-label">Happy Clients</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">99%</div>
                            <div class="stat-label">Uptime</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <div class="hero-card">
                        <div class="hero-card-header">
                            <div class="hero-card-icon">
                                <i class="bi bi-cup-hot"></i>
                            </div>
                            <div class="hero-card-title">RestaurantPro</div>
                        </div>
                        <div class="hero-card-body">
                            <div class="hero-card-row">
                                <div class="hero-card-label">Today's Orders</div>
                                <div class="hero-card-value">156</div>
                            </div>
                            <div class="hero-card-row">
                                <div class="hero-card-label">Revenue</div>
                                <div class="hero-card-value text-success">Rs. 45,230</div>
                            </div>
                            <div class="hero-card-row">
                                <div class="hero-card-label">Active Tables</div>
                                <div class="hero-card-value">12/20</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-badge">Why Choose Us</span>
            <h2 class="section-title">Powerful Features for Modern Restaurants</h2>
            <p class="section-subtitle">Everything you need to run your restaurant efficiently</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <h4 class="feature-title">Fast Ordering</h4>
                    <p class="feature-description">Streamline order processing with our intuitive POS system</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-qr-code"></i>
                    </div>
                    <h4 class="feature-title">Digital Menu</h4>
                    <p class="feature-description">QR code-based digital menus for contactless ordering</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h4 class="feature-title">Analytics</h4>
                    <p class="feature-description">Real-time insights and detailed business reports</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h4 class="feature-title">Secure</h4>
                    <p class="feature-description">Bank-grade security for your data and transactions</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Restaurant Listing Section -->
<section id="restaurants" class="restaurants-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-badge">Our Partners</span>
            <h2 class="section-title">Discover Amazing Restaurants</h2>
            <p class="section-subtitle">Explore restaurants using our platform</p>
        </div>

        <?php if($tenants->count() > 0): ?>
            <div class="row g-4">
                <?php $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-lg-4">
                    <div class="restaurant-card">
                        <div class="restaurant-card-header">
                            <?php if($tenant->logo): ?>
                                <img src="<?php echo e($tenant->getFirstMediaUrl('logo')); ?>" alt="<?php echo e($tenant->name); ?>" class="restaurant-logo">
                            <?php else: ?>
                                <div class="restaurant-logo-placeholder">
                                    <span><?php echo e(substr($tenant->name, 0, 1)); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if($tenant->subscription && $tenant->subscription->plan): ?>
                                <span class="restaurant-badge"><?php echo e($tenant->subscription->plan->name); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="restaurant-card-body">
                            <h4 class="restaurant-name"><?php echo e($tenant->name); ?></h4>
                            <?php if($tenant->company_name): ?>
                                <p class="restaurant-company"><?php echo e($tenant->company_name); ?></p>
                            <?php endif; ?>
                            <div class="restaurant-location">
                                <i class="bi bi-geo-alt"></i>
                                <?php echo e($tenant->city ?? 'Nepal'); ?>, <?php echo e($tenant->country ?? 'Nepal'); ?>

                            </div>
                            <div class="restaurant-actions">
                                <a href="<?php echo e(route('tenant.show', $tenant->slug)); ?>" class="btn btn-primary">
                                    <i class="bi bi-eye me-1"></i> View Menu
                                </a>
                                <a href="<?php echo e(route('digitalmenu', $tenant->slug)); ?>" class="btn btn-outline-primary">
                                    <i class="bi bi-qr-code me-1"></i> Digital Menu
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <div class="empty-state">
                    <i class="bi bi-shop"></i>
                    <h3>No restaurants available</h3>
                    <p>Check back later for new restaurant listings</p>
                    <a href="<?php echo e(route('register.restaurant')); ?>" class="btn btn-primary mt-3">
                        Register Your Restaurant
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5">
    <div class="container">
        <div class="cta-card">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="cta-title">Ready to Transform Your Restaurant?</h2>
                    <p class="cta-description">Join hundreds of restaurants already using our platform to grow their business</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="<?php echo e(route('register.restaurant')); ?>" class="btn btn-light btn-lg">
                        <i class="bi bi-rocket-takeoff me-2"></i>Get Started Free
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Hero Section */
    .hero-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    }
    
    .hero-badge {
        display: inline-block;
        padding: 8px 16px;
        background: rgba(255,255,255,0.2);
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 20px;
    }
    
    .hero-title {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 20px;
        line-height: 1.2;
    }
    
    .hero-description {
        font-size: 18px;
        margin-bottom: 30px;
        opacity: 0.9;
    }
    
    .hero-buttons {
        display: flex;
        gap: 15px;
        margin-bottom: 40px;
    }
    
    .hero-stats {
        display: flex;
        gap: 40px;
    }
    
    .stat-number {
        font-size: 32px;
        font-weight: 700;
    }
    
    .stat-label {
        font-size: 14px;
        opacity: 0.8;
    }
    
    .hero-image {
        position: relative;
    }
    
    .hero-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        transform: rotate(-5deg);
    }
    
    .hero-card-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .hero-card-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }
    
    .hero-card-title {
        font-size: 20px;
        font-weight: 600;
        color: #333;
    }
    
    .hero-card-row {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    
    .hero-card-row:last-child {
        border-bottom: none;
    }
    
    .hero-card-label {
        color: #666;
        font-size: 14px;
    }
    
    .hero-card-value {
        font-weight: 600;
        font-size: 16px;
        color: #333;
    }
    
    /* Features Section */
    .features-section {
        background: white;
    }
    
    .section-badge {
        display: inline-block;
        padding: 8px 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 15px;
    }
    
    .section-title {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 15px;
        color: #333;
    }
    
    .section-subtitle {
        font-size: 18px;
        color: #666;
        margin-bottom: 40px;
    }
    
    .feature-card {
        padding: 30px;
        border-radius: 15px;
        background: #f8f9fa;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        background: white;
    }
    
    .feature-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 28px;
        margin-bottom: 20px;
    }
    
    .feature-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
    }
    
    .feature-description {
        color: #666;
        font-size: 14px;
        line-height: 1.6;
    }
    
    /* Restaurant Section */
    .restaurants-section {
        background: #f8f9fa;
    }
    
    .restaurant-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .restaurant-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }
    
    .restaurant-card-header {
        position: relative;
        padding: 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 150px;
    }
    
    .restaurant-logo {
        width: 80px;
        height: 80px;
        border-radius: 15px;
        object-fit: cover;
        background: white;
        padding: 10px;
    }
    
    .restaurant-logo-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 15px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 700;
        color: #667eea;
    }
    
    .restaurant-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 6px 12px;
        background: rgba(255,255,255,0.2);
        color: white;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .restaurant-card-body {
        padding: 25px;
    }
    
    .restaurant-name {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 5px;
        color: #333;
    }
    
    .restaurant-company {
        color: #666;
        font-size: 14px;
        margin-bottom: 15px;
    }
    
    .restaurant-location {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #888;
        font-size: 14px;
        margin-bottom: 20px;
    }
    
    .restaurant-actions {
        display: flex;
        gap: 10px;
    }
    
    .restaurant-actions .btn {
        flex: 1;
        font-size: 14px;
    }
    
    .empty-state {
        padding: 60px 20px;
    }
    
    .empty-state i {
        font-size: 64px;
        color: #ddd;
        margin-bottom: 20px;
    }
    
    .empty-state h3 {
        color: #666;
        margin-bottom: 10px;
    }
    
    .empty-state p {
        color: #888;
        margin-bottom: 20px;
    }
    
    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .cta-card {
        background: rgba(255,255,255,0.1);
        border-radius: 20px;
        padding: 40px;
        backdrop-filter: blur(10px);
    }
    
    .cta-title {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 15px;
    }
    
    .cta-description {
        font-size: 18px;
        opacity: 0.9;
        margin-bottom: 0;
    }
    
    .cta-section .btn-light {
        background: white;
        color: #667eea;
        border: none;
        font-weight: 600;
    }
    
    .cta-section .btn-light:hover {
        background: #f8f9fa;
        color: #764ba2;
    }
</style>
<?php /**PATH D:\DMCRESTRO\singlerestro-main\resources\views/frontend/welcome/tenants.blade.php ENDPATH**/ ?>