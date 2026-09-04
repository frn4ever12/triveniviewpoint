<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#dc3545">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <title>DMC Restro - Login</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 30px 20px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            margin: 20px;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-section h1 {
            color: #dc3545;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .logo-section p {
            color: #666;
            font-size: 16px;
            margin-bottom: 0;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            color: #333;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
        }

        .input-group {
            position: relative;
        }

        .input-group-text {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-right: none;
            color: #666;
        }

        .form-control {
            border: 1px solid #e0e0e0;
            border-left: none;
            padding: 12px 15px;
            font-size: 15px;
            border-radius: 0 8px 8px 0;
        }

        .form-control:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            z-index: 10;
        }

        .password-toggle:hover {
            color: #dc3545;
        }

        .form-check {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #e0e0e0;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .form-check-label {
            color: #666;
            font-size: 14px;
            margin-left: 8px;
            cursor: pointer;
        }

        .forgot-password {
            color: #dc3545;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 20px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(220, 53, 69, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
            color: #999;
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e0e0e0;
        }

        .divider span {
            padding: 0 15px;
        }

        .fingerprint-btn {
            width: 100%;
            padding: 14px;
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            color: #333;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .fingerprint-btn:hover {
            background: #e9ecef;
            border-color: #dc3545;
            color: #dc3545;
        }

        .fingerprint-btn i {
            font-size: 20px;
        }

        .alert {
            margin-bottom: 20px;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 14px;
        }

        .alert-danger {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #fff;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .btn-login.loading .btn-text {
            display: none;
        }

        .btn-login.loading .loading-spinner {
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-section">
            <h1>DMC Restro</h1>
            <p>Welcome Back</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" id="loginForm">
            @csrf
            <input type="hidden" name="redirect_to" value="{{ route('mobile.dashboard') }}">
            <div class="form-group">
                <label class="form-label">Mobile Number / Email</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" 
                           name="email" 
                           class="form-control" 
                           placeholder="Enter email or mobile" 
                           value="{{ old('email') }}"
                           required
                           autofocus>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" 
                           name="password" 
                           class="form-control" 
                           placeholder="Enter password" 
                           required
                           id="passwordInput">
                    <button type="button" class="password-toggle" id="togglePassword">
                        <i class="bi bi-eye-slash" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="form-check">
                <div class="d-flex align-items-center">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>
                <a href="#" class="forgot-password">Forgot Password?</a>
            </div>

            <button type="submit" class="btn-login" id="loginBtn">
                <span class="btn-text">Login</span>
                <div class="loading-spinner"></div>
            </button>
        </form>

        <div class="divider">
            <span>or</span>
        </div>

        <button type="button" class="fingerprint-btn" id="fingerprintBtn">
            <i class="bi bi-fingerprint"></i>
            Use fingerprint to login
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('bi-eye-slash');
                eyeIcon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('bi-eye');
                eyeIcon.classList.add('bi-eye-slash');
            }
        });

        // Form loading state
        document.getElementById('loginForm').addEventListener('submit', function() {
            const loginBtn = document.getElementById('loginBtn');
            loginBtn.classList.add('loading');
            loginBtn.disabled = true;
        });

        // Fingerprint authentication
        document.getElementById('fingerprintBtn').addEventListener('click', function() {
            if ('credentials' in navigator) {
                navigator.credentials.get({
                    publicKey: {
                        challenge: new Uint8Array(16),
                        allowCredentials: [{
                            type: 'public-key',
                            id: new Uint8Array(16),
                        }],
                        userVerification: 'preferred'
                    }
                }).then(function(credential) {
                    // Handle successful fingerprint authentication
                    console.log('Fingerprint authentication successful', credential);
                    // Submit form with credential
                    document.getElementById('loginForm').submit();
                }).catch(function(error) {
                    console.log('Fingerprint authentication failed', error);
                    alert('Fingerprint authentication not available or failed. Please use password login.');
                });
            } else {
                alert('Fingerprint authentication is not supported on this device.');
            }
        });

        // Check for WebAuthn support
        if (!('credentials' in navigator)) {
            document.getElementById('fingerprintBtn').style.display = 'none';
        }
    </script>
</body>
</html>
