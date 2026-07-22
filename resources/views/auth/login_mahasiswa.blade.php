<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Portal Login Mahasiswa - SATU ADHYAKSA. Akses KRS, jadwal perkuliahan, dan nilai akademik Anda.">
    <meta name="keywords" content="Portal Mahasiswa, SATU ADHYAKSA, Login Mahasiswa, SIAKAD">
    <meta name="author" content="SATU ADHYAKSA">
    <meta name="robots" content="index, follow">

    <title>Login Mahasiswa - SATU ADHYAKSA</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo_stih_white.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --maroon: #701a2d;
            --maroon-light: #9f1239;
            --maroon-dark: #4c0519;
            --accent: #f43f5e;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-color: #ffffff;
            color: var(--text-main);
            overflow-x: hidden;
        }

        .split-container {
            display: flex;
            min-height: 100vh;
            width: 100vw;
        }

        /* LEFT SIDE: Form */
        .form-side {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 60px;
            background: #ffffff;
            position: relative;
        }

        .form-wrapper {
            width: 100%;
            max-width: 400px;
            display: flex;
            flex-direction: column;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
        }

        .brand-logo img {
            height: 40px;
            width: auto;
            object-fit: contain;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.25;
        }

        .univ-title {
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--maroon);
        }

        .site-title {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            text-transform: uppercase;
            margin-top: 1px;
        }

        .welcome-header {
            margin-bottom: 32px;
        }

        .welcome-header h1 {
            font-size: 36px;
            font-weight: 800;
            color: var(--text-main);
            margin: 0 0 10px;
            letter-spacing: -0.04em;
        }

        .welcome-header p {
            color: var(--text-muted);
            font-size: 15px;
            line-height: 1.5;
            margin: 0;
        }

        /* Pill Input Fields */
        .input-group {
            margin-bottom: 20px;
            position: relative;
        }

        .input-wrapper {
            position: relative;
            width: 100%;
        }

        .input-field {
            width: 100%;
            height: 56px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 9999px;
            padding: 0 24px 0 54px;
            font-size: 15px;
            color: var(--text-main);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-sizing: border-box;
        }

        .input-field::placeholder {
            color: #94a3b8;
        }

        .input-field:focus {
            outline: none;
            border-color: var(--maroon);
            box-shadow: 0 0 0 4px rgba(112, 26, 45, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 22px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 16px;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .input-field:focus ~ .input-icon {
            color: var(--maroon);
        }

        .password-toggle {
            position: absolute;
            right: 22px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            transition: all 0.2s ease;
        }

        .password-toggle:hover {
            color: var(--maroon);
        }

        .options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            font-size: 14px;
            padding: 0 4px;
        }

        .custom-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            color: var(--text-muted);
            font-weight: 500;
        }

        .custom-checkbox input {
            display: none;
        }

        .checkbox-box {
            width: 20px;
            height: 20px;
            border: 1.5px solid #cbd5e1;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .custom-checkbox input:checked + .checkbox-box {
            background: var(--maroon);
            border-color: var(--maroon);
        }

        .checkbox-box i {
            color: white;
            font-size: 10px;
            display: none;
        }

        .custom-checkbox input:checked + .checkbox-box i {
            display: block;
        }

        .forgot-link {
            color: var(--maroon);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: var(--maroon-light);
        }

        /* Pill Submit Button */
        .submit-btn {
            width: 100%;
            height: 56px;
            background: var(--maroon);
            color: white;
            border: none;
            border-radius: 9999px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 8px 20px -4px rgba(112, 26, 45, 0.3);
        }

        .submit-btn:hover {
            background: var(--maroon-light);
            transform: translateY(-2px);
            box-shadow: 0 12px 25px -4px rgba(112, 26, 45, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Divider & Socials */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 30px 0;
            color: #94a3b8;
            font-size: 13px;
            font-weight: 500;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }

        .divider:not(:empty)::before {
            margin-right: 15px;
        }

        .divider:not(:empty)::after {
            margin-left: 15px;
        }

        .social-row {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-bottom: 40px;
        }

        .social-btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: var(--text-main);
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .social-btn:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: var(--maroon);
            transform: translateY(-2px);
        }

        .register-prompt {
            text-align: center;
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .register-prompt a {
            color: var(--maroon);
            text-decoration: none;
            font-weight: 700;
        }

        .register-prompt a:hover {
            text-decoration: underline;
        }

        /* RIGHT SIDE: Feature Panel with Campus Background Image */
        .feature-side {
            flex: 1;
            background: url('{{ asset('images/bg.png') }}') no-repeat center center;
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
            border-radius: 36px 0 0 36px;
        }

        /* Dark premium overlay for the campus background image */
        .feature-side::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(76, 5, 25, 0.65) 0%, rgba(28, 0, 8, 0.75) 100%);
            z-index: 1;
        }

        /* Mockup Card Container */
        .mockup-wrapper {
            position: relative;
            z-index: 5;
            width: 100%;
            max-width: 440px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Main Illustration container in CSS */
        .illustration-box {
            position: relative;
            width: 280px;
            height: 280px;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Rotating thought/energy swirls from reference */
        .swirls-bg {
            position: absolute;
            inset: -20px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }

        .rotating-swirl {
            width: 100%;
            height: 100%;
            animation: rotate-slow 22s linear infinite;
        }

        @keyframes rotate-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Main Student Illustration styling */
        .student-vector {
            width: 90%;
            height: 90%;
            position: relative;
            z-index: 2;
            animation: float-student 6s infinite alternate ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Sleek central illustration icon badges for Slide 2 and 3 */
        .main-illustration-icon {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 56px;
            position: relative;
            z-index: 2;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            animation: float-student 6s infinite alternate ease-in-out;
        }

        .main-illustration-icon.gold-theme {
            background: rgba(251, 191, 36, 0.15);
            color: #fbbf24;
            border: 2px solid rgba(251, 191, 36, 0.35);
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.3),
                0 0 40px rgba(251, 191, 36, 0.25);
        }

        .main-illustration-icon.rose-theme {
            background: rgba(244, 63, 94, 0.15);
            color: #f43f5e;
            border: 2px solid rgba(244, 63, 94, 0.35);
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.3),
                0 0 40px rgba(244, 63, 94, 0.25);
        }

        @keyframes float-student {
            0% { transform: translateY(0); }
            100% { transform: translateY(-12px); }
        }

        /* Floating avatars/badges on the sides */
        .avatar-float {
            position: absolute;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #ffffff;
            z-index: 10;
            border: 2px solid #ffffff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
        }

        .avatar-left {
            left: -20px;
            top: 60px;
            animation: float-left 5s infinite alternate ease-in-out;
        }

        .avatar-right {
            right: -20px;
            bottom: 80px;
            animation: float-right 5.5s infinite alternate ease-in-out;
        }

        @keyframes float-left {
            0% { transform: translateY(0) rotate(0deg); }
            100% { transform: translateY(-15px) rotate(-8deg); }
        }

        @keyframes float-right {
            0% { transform: translateY(0) rotate(0deg); }
            100% { transform: translateY(-12px) rotate(8deg); }
        }

        /* Dots Carousel Indicator */
        .dots-row {
            display: flex;
            gap: 8px;
            margin-bottom: 24px;
        }

        .dot {
            width: 8px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.35);
            border: none;
            padding: 0;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dot.active {
            width: 24px;
            border-radius: 6px;
            background: #ffffff;
        }

        .dot:hover {
            background: rgba(255, 255, 255, 0.8);
        }

        /* Slideshow Container and slide items */
        .slides-container {
            width: 100%;
            position: relative;
            min-height: 420px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .slide-item {
            display: none;
            flex-direction: column;
            align-items: center;
            width: 100%;
            animation: slide-fade 0.5s ease-in-out forwards;
        }

        .slide-item.active {
            display: flex;
        }

        @keyframes slide-fade {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Promo headline text */
        .promo-text {
            text-align: center;
            max-width: 320px;
        }

        .promo-text h3 {
            font-size: 18px;
            font-weight: 800;
            color: #ffffff;
            margin: 0 0 10px;
            line-height: 1.4;
        }

        .promo-text p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
            line-height: 1.5;
            font-weight: 500;
        }

        /* Alert boxes */
        .alert-box {
            padding: 14px 20px;
            border-radius: 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 600;
            box-sizing: border-box;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            color: #991b1b;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #dcfce7;
            color: #166534;
        }

        /* Responsive Breakpoints */
        @media (max-width: 1023px) {
            .feature-side {
                display: none;
            }
            .form-side {
                padding: 40px 24px;
            }
        }
    </style>
</head>

<body>
    <div class="split-container">
        <!-- LEFT PANEL: Unified Clean Login Form -->
        <div class="form-side">
            <div class="form-wrapper">
                <!-- Brand logo STIH -->
                <div class="brand-logo">
                    <img src="{{ asset('images/logo_stih_white.png') }}" alt="Logo" style="filter: brightness(0.2) sepia(1) hue-rotate(-50deg) saturate(5);">
                    <div class="brand-text">
                        <span class="univ-title">Universitas Adhyaksa</span>
                        <span class="site-title">StudentSite</span>
                    </div>
                </div>

                <div class="welcome-header">
                    <h1>Selamat Datang!</h1>
                    <p>Silakan masuk ke Portal Mahasiswa SATU ADHYAKSA untuk mengakses informasi akademik Anda.</p>
                </div>

                <!-- Session Alert Messages -->
                @if (session('error'))
                    <div class="alert-box alert-error">
                        <i class="fas fa-circle-exclamation text-lg"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert-box alert-success">
                        <i class="fas fa-circle-check text-lg"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Auth login form submission -->
                <form action="{{ route('login.mahasiswa.post') }}" method="POST">
                    @csrf
                    
                    <div class="input-group">
                        <div class="input-wrapper">
                            <input type="email" name="email" value="{{ old('email') }}" class="input-field"
                                placeholder="Username atau email mahasiswa" required autofocus>
                            <i class="fas fa-user input-icon"></i>
                        </div>
                        @error('email')
                            <p style="color: #ef4444; font-size: 13px; margin: 8px 0 0 16px; font-weight: 600;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="input-group">
                        <div class="input-wrapper">
                            <input type="password" name="password" id="password" class="input-field"
                                placeholder="Password akun" required>
                            <i class="fas fa-lock input-icon"></i>
                            <i class="fas fa-eye password-toggle" onclick="togglePassword()"></i>
                        </div>
                    </div>

                    <div class="options-row">
                        <label class="custom-checkbox">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <div class="checkbox-box">
                                <i class="fas fa-check"></i>
                            </div>
                            <span>Tetap masuk</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                    </div>

                    <button type="submit" class="submit-btn">
                        <span>Login</span>
                    </button>
                </form>


            </div>
        </div>

        <!-- RIGHT PANEL: Vector illustration & Features matching reference -->
        <div class="feature-side">
            <div class="feature-orb feature-orb-1"></div>
            <div class="feature-orb feature-orb-2"></div>

            <div class="mockup-wrapper">
                <div class="illustration-box">
                    <!-- Rotating energy swirls -->
                    <div class="swirls-bg">
                        <svg viewBox="0 0 200 200" class="rotating-swirl">
                            <path d="M100 10 C 130 10, 190 70, 190 100 C 190 130, 130 190, 100 190 C 70 190, 10 130, 10 100 C 10 70, 70 10, 100 10" fill="none" stroke="rgba(255, 255, 255, 0.35)" stroke-width="2.5" stroke-dasharray="8 8" />
                            <path d="M100 25 C 120 25, 175 75, 175 100 C 175 125, 120 175, 100 175 C 80 175, 25 125, 25 100 C 25 75, 80 25, 100 25" fill="none" stroke="rgba(255, 255, 255, 0.2)" stroke-width="1.5" />
                        </svg>
                    </div>
                    
                    <!-- Central student vector -->
                    <div class="student-vector">
                        <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M50 160 C55 140, 145 140, 150 160 C135 170, 65 170, 50 160 Z" fill="rgba(255, 255, 255, 0.15)" stroke="rgba(255, 255, 255, 0.5)" stroke-width="2" />
                            <path d="M80 110 L120 110 L115 150 L85 150 Z" fill="#9f1239" stroke="rgba(255, 255, 255, 0.6)" stroke-width="1.5" />
                            <rect x="96" y="95" width="8" height="16" rx="2" fill="#fbcfe8" />
                            <circle cx="100" cy="80" r="18" fill="#fbcfe8" />
                            <path d="M82 80 C82 65, 118 65, 118 80 C118 88, 112 95, 100 95 C88 95, 82 88, 82 80 Z" fill="#1c0008" />
                            <path d="M100 124 C98 120, 92 120, 92 125 C92 130, 100 135, 100 135 C100 135, 108 130, 108 125 C108 120, 102 120, 100 124 Z" fill="#f43f5e" />
                            <circle cx="60" cy="130" r="5" fill="#fbcfe8" />
                            <circle cx="140" cy="130" r="5" fill="#fbcfe8" />
                            <path d="M60 130 C70 120, 75 115, 80 115" stroke="#fbcfe8" stroke-width="2" stroke-linecap="round" fill="none" />
                            <path d="M140 130 C130 120, 125 115, 120 115" stroke="#fbcfe8" stroke-width="2" stroke-linecap="round" fill="none" />
                        </svg>
                    </div>

                    <!-- 4 floating badges representing key student activities -->
                    <!-- Top-Left: Book (KRS & Materi) -->
                    <div class="avatar-float" style="left: -24px; top: 12px; background: var(--maroon-light); animation: float-left 5s infinite alternate ease-in-out;">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <!-- Top-Right: Graduation Cap (Nilai & Prestasi) -->
                    <div class="avatar-float" style="right: -24px; top: 12px; background: #fbbf24; color: #1e293b; animation: float-right 5.5s infinite alternate ease-in-out;">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <!-- Bottom-Left: Calendar (Jadwal & Absensi) -->
                    <div class="avatar-float" style="left: -24px; bottom: 24px; background: var(--accent); animation: float-right 5s infinite alternate ease-in-out; animation-delay: -1.5s;">
                        <i class="fas fa-calendar-days"></i>
                    </div>
                    <!-- Bottom-Right: Laptop (SIAKAD & E-learning) -->
                    <div class="avatar-float" style="right: -24px; bottom: 24px; background: #06b6d4; color: white; animation: float-left 5.5s infinite alternate ease-in-out; animation-delay: -2s;">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                </div>

                <div class="promo-text">
                    <h3>StudentSite Portal Akademik</h3>
                    <p>Satu portal terintegrasi untuk mengakses KRS, kehadiran, materi kuliah, nilai akademik, dan administrasi perkuliahan Anda secara real-time.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Toggle Password Visibility JS -->
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.querySelector('.password-toggle');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>