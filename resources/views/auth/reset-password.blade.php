<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ __('reset_password') }} - {{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(45deg, #ff9a9e 0%, #fecfef 50%, #fecfef 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><pattern id="hearts" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M50,30 C50,10 30,10 30,30 C30,10 10,10 10,30 C10,50 30,70 50,90 C70,70 90,50 90,30 C90,10 70,10 70,30 C70,10 50,10 50,30 Z" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23hearts)"/></svg>');
            animation: float 20s linear infinite;
            z-index: 1;
        }

        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-50px, -50px) rotate(360deg); }
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            box-shadow: 0 30px 80px rgba(255, 105, 180, 0.3);
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 10;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transform: translateY(0);
            transition: all 0.3s ease;
        }

        .login-container:hover {
            transform: translateY(-10px);
            box-shadow: 0 40px 100px rgba(255, 105, 180, 0.4);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .logo-circle {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #ff6b9d 0%, #c44569 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 15px 40px rgba(255, 107, 157, 0.4);
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

        .logo-circle i {
            font-size: 3rem;
            color: white;
        }

        .welcome-title {
            color: #2d3436;
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .welcome-subtitle {
            color: #636e72;
            font-size: 1.1rem;
            font-weight: 400;
            margin-bottom: 1rem;
        }

        .description-text {
            color: #636e72;
            font-size: 0.95rem;
            text-align: center;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .form-container {
            margin-top: 2rem;
        }

        .input-wrapper {
            margin-bottom: 1.8rem;
            position: relative;
        }

        .input-label {
            display: block;
            color: #2d3436;
            font-weight: 600;
            margin-bottom: 0.8rem;
            font-size: 1rem;
        }

        .input-field {
            width: 100%;
            padding: 1.2rem 3.5rem 1.2rem 1.5rem;
            border: 3px solid #ddd6fe;
            border-radius: 20px;
            font-size: 1rem;
            background: #f8f9fa;
            transition: all 0.3s ease;
            outline: none;
            font-family: 'Cairo', sans-serif;
        }

        .input-field:focus {
            border-color: #ff6b9d;
            background: white;
            box-shadow: 0 0 0 4px rgba(255, 107, 157, 0.1);
            transform: translateY(-2px);
        }

        .input-field::placeholder {
            color: #a0a0a0;
        }

        .icon-wrapper {
            position: absolute;
            right: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: #ff6b9d;
            font-size: 1.2rem;
            pointer-events: none;
            z-index: 2;
        }

        .input-wrapper:focus-within .icon-wrapper {
            color: #c44569;
        }

        .login-btn {
            width: 100%;
            padding: 1.2rem 2rem;
            background: linear-gradient(135deg, #ff6b9d 0%, #c44569 100%);
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Cairo', sans-serif;
            position: relative;
            overflow: hidden;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(255, 107, 157, 0.4);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .back-link {
            text-align: center;
            margin-top: 2rem;
        }

        .back-link a {
            color: #ff6b9d;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .back-link a:hover {
            color: #c44569;
            text-decoration: none;
            transform: translateY(-1px);
        }

        .error-message {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            display: block;
        }

        .success-message {
            background: linear-gradient(135deg, #00b894 0%, #00a085 100%);
            color: white;
            padding: 1rem;
            border-radius: 15px;
            margin-bottom: 1.5rem;
            text-align: center;
            font-weight: 600;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                margin: 1rem;
                padding: 2rem;
            }

            .welcome-title {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <div class="logo-circle">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <h1 class="welcome-title">{{ __('reset_password') }}</h1>
            <p class="welcome-subtitle">{{ __('create_a_new_secure_password') }}</p>
            <p class="description-text">
                {{ __('enter_the_new_password_and_confirm_it_correctly') }}
            </p>
        </div>

        <!-- Reset Password Form -->
        <form method="POST" action="{{ route('password.store') }}" class="form-container">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Field -->
            <div class="input-wrapper">
                <label for="email" class="input-label">{{ __('email') }}</label>
                <div style="position: relative;">
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email', $request->email) }}"
                           placeholder="{{ __('email') }}"
                           class="input-field @error('email') is-invalid @enderror"
                           required
                           autofocus
                           autocomplete="username">
                    <div class="icon-wrapper">
                        <i class="bi bi-envelope"></i>
                    </div>
                </div>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="input-wrapper">
                <label for="password" class="input-label">{{ __('new_password') }}</label>
                <div style="position: relative;">
                    <input type="password"
                           id="password"
                           name="password"
                           placeholder="{{ __('new_password') }}"
                           class="input-field @error('password') is-invalid @enderror"
                           required
                           autocomplete="new-password">
                    <div class="icon-wrapper">
                        <i class="bi bi-lock"></i>
                    </div>
                </div>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password Field -->
            <div class="input-wrapper">
                <label for="password_confirmation" class="input-label">{{ __('confirm_password') }}</label>
                <div style="position: relative;">
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           placeholder="{{ __('new_password') }}"
                           class="input-field @error('password_confirmation') is-invalid @enderror"
                           required
                           autocomplete="new-password">
                    <div class="icon-wrapper">
                        <i class="bi bi-lock-fill"></i>
                    </div>
                </div>
                @error('password_confirmation')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="login-btn">
                <i class="bi bi-check-circle me-2"></i>
                {{ __('reset_password') }}
            </button>

            <!-- Back to Login -->
            <div class="back-link">
                <a href="{{ route('login') }}">
                    <i class="bi bi-arrow-right me-1"></i>
                    {{ __('back_to_login') }}
                </a>
            </div>
        </form>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // Add floating animation to input fields
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.input-field');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'translateY(-2px)';
                });
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html>
