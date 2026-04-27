<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - STIH Adhyaksa</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo_stih_white.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --maroon: #800020;
            --maroon-light: #a31d3d;
            --maroon-dark: #4a0012;
            --accent: #d4af37;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.12);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: url('{{ asset('images/bg.png') }}') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
            position: relative;
        }

        /* Moody dark overlay */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at center, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.8) 100%);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            z-index: 1;
        }

        /* Floating background orbs */
        .orb {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 2;
            opacity: 0.3;
            animation: float 20s infinite alternate;
        }

        .orb-1 {
            background: var(--maroon);
            top: -100px;
            left: -100px;
        }

        .orb-2 {
            background: var(--maroon-light);
            bottom: -150px;
            right: -100px;
            animation-delay: -5s;
        }

        @keyframes float {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(100px, 50px);
            }
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 24px;
            position: relative;
            z-index: 10;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border: 1px solid var(--glass-border);
            border-radius: 40px;
            padding: 40px 40px;
            box-shadow: 0 40px 100px -20px rgba(0, 0, 0, 0.6);
            animation: cardAppear 1s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        @keyframes cardAppear {
            from {
                opacity: 0;
                transform: translateY(60px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto 30px;
            position: relative;
        }

        .logo-box {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-dark) 100%);
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 15px 40px rgba(128, 0, 32, 0.5);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo-shine {
            position: absolute;
            top: 0;
            left: -150%;
            width: 150%;
            height: 100%;
            background: linear-gradient(to right,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0.6) 50%,
                    rgba(255, 255, 255, 0) 100%);
            transform: skewX(-25deg);
            pointer-events: none;
        }

        .login-card:hover .logo-shine {
            animation: sweep 3s ease-in-out;
        }

        @keyframes sweep {
            0% {
                left: -150%;
            }

            20% {
                left: 150%;
            }

            100% {
                left: 150%;
            }
        }

        .logo-box img {
            width: 48px;
            height: 48px;
            object-fit: contain;
            filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.3));
        }

        .header-section {
            text-align: center;
            margin-bottom: 32px;
        }

        .header-section h1 {
            font-size: 28px;
            font-weight: 800;
            color: white;
            margin: 0 0 6px;
            letter-spacing: -0.04em;
        }

        .header-section p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            font-weight: 500;
            margin: 0;
        }

        /* Seamless Input Styles */
        .input-group {
            margin-bottom: 20px;
        }

        .input-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 8px;
            margin-left: 6px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .input-wrapper {
            position: relative;
        }

        .input-field {
            width: 100%;
            height: 54px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 18px;
            padding: 0 20px 0 54px;
            font-size: 15px;
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-sizing: border-box;
        }

        .input-field::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .input-field:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--maroon-light);
            box-shadow: 0 0 25px rgba(128, 0, 32, 0.2);
            transform: translateY(-2px);
        }

        .input-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.3);
            font-size: 18px;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .input-field:focus~.input-icon {
            color: white;
            transform: translateY(calc(-50% - 2px));
        }

        .password-toggle {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            padding: 6px;
            transition: all 0.2s ease;
        }

        .password-toggle:hover {
            color: white;
        }

        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 0 6px;
        }

        .custom-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }

        .custom-checkbox input {
            display: none;
        }

        .checkbox-box {
            width: 20px;
            height: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            background: rgba(255, 255, 255, 0.05);
        }

        .custom-checkbox input:checked+.checkbox-box {
            background: var(--maroon);
            border-color: var(--maroon);
            box-shadow: 0 0 15px rgba(128, 0, 32, 0.4);
        }

        .checkbox-box i {
            color: white;
            font-size: 11px;
            display: none;
        }

        .custom-checkbox input:checked+.checkbox-box i {
            display: block;
        }

        .submit-btn {
            width: 100%;
            height: 58px;
            background: linear-gradient(135deg, var(--maroon) 0%, var(--maroon-light) 100%);
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 20px 40px -10px rgba(128, 0, 32, 0.5);
            position: relative;
            overflow: hidden;
        }

        .btn-shine {
            position: absolute;
            top: 0;
            left: -150%;
            width: 150%;
            height: 100%;
            background: linear-gradient(to right,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0.4) 50%,
                    rgba(255, 255, 255, 0) 100%);
            transform: skewX(-25deg);
            pointer-events: none;
        }

        .submit-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px -12px rgba(128, 0, 32, 0.6);
        }

        .submit-btn:hover .btn-shine {
            animation: sweep 5s ease-in-out forwards;
        }

        .alert-box {
            padding: 14px 20px;
            border-radius: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 14px;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        .alert-error {
            background: rgba(220, 38, 38, 0.2);
            border: 1px solid rgba(220, 38, 38, 0.3);
            color: #FECACA;
        }

        .alert-success {
            background: rgba(22, 163, 74, 0.2);
            border: 1px solid rgba(22, 163, 74, 0.3);
            color: #DCFCE7;
        }

        .footer-text {
            text-align: center;
            margin-top: 48px;
            color: rgba(255, 255, 255, 0.3);
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="logo-wrapper">
                <div class="logo-box">
                    <div class="logo-shine"></div>
                    <img src="{{ asset('images/logo_stih_white.png') }}" alt="Logo">
                </div>
            </div>

            <div class="header-section">
                <h1>Selamat Datang</h1>
                <p>Silakan masuk ke akun Anda</p>
            </div>

            @if (session('error'))
                <div class="alert-box alert-error">
                    <i class="fas fa-circle-exclamation text-xl"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (session('success'))
                <div class="alert-box alert-success">
                    <i class="fas fa-circle-check text-xl"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="input-group">
                    <label class="input-label">Alamat Email</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" value="{{ old('email') }}" class="input-field"
                            placeholder="nama@stih.com" required autofocus>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                    @error('email')
                        <p style="color: #FECACA; font-size: 13px; margin: 10px 0 0 6px; font-weight: 600;">{{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="input-group">
                    <label class="input-label">Kata Sandi</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" class="input-field" placeholder="••••••••"
                            required>
                        <i class="fas fa-lock input-icon"></i>
                        <span class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="eye-icon"></i>
                        </span>
                    </div>
                </div>

                <div class="options-row">
                    <label class="custom-checkbox">
                        <input type="checkbox" name="remember">
                        <div class="checkbox-box">
                            <i class="fas fa-check"></i>
                        </div>
                        <span>Tetap masuk</span>
                    </label>
                </div>

                <button type="submit" class="submit-btn">
                    <div class="btn-shine"></div>
                    <span>Masuk ke Portal</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>

        <div class="footer-text">
            &copy; 2025 STIH Adhyaksa
        </div>
    </div>

    <script>
        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eye-icon');

            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                pwd.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Auto-hide alert boxes after 3 seconds
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.alert-box');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'all 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px) scale(0.95)';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 600);
                }, 3000);
            });
        });
    </script>
</body>

</html>