@extends('frontend.includes.auth-main')

@section('content')
<div class="auth-wrapper">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-brand">
                <div class="auth-logo">
                    @if(isset($logoUrl) && $logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $siteName ?? 'RestaurantPro' }}" class="auth-logo-img">
                    @else
                        <i class="bi bi-cup-hot-fill"></i>
                    @endif
                </div>
                <h1 class="auth-title">{{ $siteName ?? 'RestaurantPro' }}</h1>
                <p class="auth-subtitle">Sign in to manage your restaurant</p>
            </div>

            <form class="auth-form" action="{{ route('login') }}" method="POST">
                @csrf

                <div class="auth-field">
                    <label for="email" class="auth-label">Email Address</label>
                    <input
                        type="email"
                        class="auth-input @error('email') is-invalid @enderror"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="you@example.com"
                        required
                        autofocus
                    >
                    @error('email')
                        <div class="auth-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="auth-field">
                    <label for="password" class="auth-label">Password</label>
                    <div class="auth-password-wrapper">
                        <input
                            type="password"
                            class="auth-input @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            required
                        >
                        <button type="button" class="auth-password-toggle" onclick="togglePassword()" tabindex="-1">
                            <i class="bi bi-eye" id="passwordIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="auth-error">{{ $message }}</div>
                    @enderror
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
                    <a href="{{ route('register.restaurant') }}" class="auth-footer-link">
                        <i class="bi bi-plus-circle me-1"></i> Register Your Restaurant
                    </a>
                </div>
                &copy; {{ date('Y') }} {{ $siteName ?? 'RestaurantPro' }} &mdash; Admin Panel
            </div>
        </div>
    </div>
</div>

@push('scripts')
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
@endpush
@endsection
