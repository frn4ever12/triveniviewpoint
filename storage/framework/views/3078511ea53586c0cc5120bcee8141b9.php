<?php $__env->startSection('content'); ?>
<div class="auth-wrapper">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-brand">
                <div class="auth-logo">
                    <?php if(isset($logoUrl) && $logoUrl): ?>
                        <img src="<?php echo e($logoUrl); ?>" alt="<?php echo e($siteName ?? 'RestaurantPro'); ?>" class="auth-logo-img">
                    <?php else: ?>
                        <i class="bi bi-cup-hot-fill"></i>
                    <?php endif; ?>
                </div>
                <h1 class="auth-title"><?php echo e($siteName ?? 'RestaurantPro'); ?></h1>
                <p class="auth-subtitle">Sign in to manage your restaurant</p>
            </div>

            <form class="auth-form" action="<?php echo e(route('login')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="auth-field">
                    <label for="email" class="auth-label">Email Address</label>
                    <input
                        type="email"
                        class="auth-input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        id="email"
                        name="email"
                        value="<?php echo e(old('email')); ?>"
                        placeholder="you@example.com"
                        required
                        autofocus
                    >
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="auth-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="auth-field">
                    <label for="password" class="auth-label">Password</label>
                    <div class="auth-password-wrapper">
                        <input
                            type="password"
                            class="auth-input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            required
                        >
                        <button type="button" class="auth-password-toggle" onclick="togglePassword()" tabindex="-1">
                            <i class="bi bi-eye" id="passwordIcon"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="auth-error"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="auth-options">
                    <label class="auth-checkbox">
                        <input type="checkbox" name="remember" id="rememberMe">
                        <span class="auth-checkbox-checkmark"></span>
                        Remember me
                    </label>
                </div>

                <button type="submit" class="auth-button">
                    <span>Sign In</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="auth-footer">
                <div class="auth-footer-links">
                    <a href="<?php echo e(route('register.restaurant')); ?>" class="auth-footer-link">
                        <i class="bi bi-plus-circle me-1"></i> Register Your Restaurant
                    </a>
                </div>
                &copy; <?php echo e(date('Y')); ?> <?php echo e($siteName ?? 'RestaurantPro'); ?> &mdash; Admin Panel
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function togglePassword() {
        const password = document.getElementById('password');
        const icon = document.getElementById('passwordIcon');
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.includes.auth-main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\DMCRESTRO\singlerestro-main\resources\views/auth/login.blade.php ENDPATH**/ ?>