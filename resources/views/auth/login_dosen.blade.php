<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Portal Login Dosen - SATU ADHYAKSA. Kelola jadwal perkuliahan, absensi, dan bimbingan akademik Anda.">
    <meta name="keywords" content="Portal Dosen, SATU ADHYAKSA, Login Dosen, SIAKAD">
    <meta name="author" content="SATU ADHYAKSA">
    <meta name="robots" content="index, follow">

    <title>Login Dosen - SATU ADHYAKSA</title>

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
            height: 100vh;
            background-color: #ffffff;
            color: var(--text-main);
            overflow: hidden;
        }

        .split-container {
            display: flex;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
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
            height: 100%;
            overflow-y: auto;
            box-sizing: border-box;
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
            margin-bottom: 24px;
        }

        .brand-logo img {
            height: 36px;
            width: auto;
            object-fit: contain;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.25;
        }

        .univ-title {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--maroon);
        }

        .site-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            text-transform: uppercase;
            margin-top: 1px;
        }

        .welcome-header {
            margin-bottom: 24px;
        }

        .welcome-header h1 {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-main);
            margin: 0 0 8px;
            letter-spacing: -0.04em;
        }

        .welcome-header p {
            color: var(--text-muted);
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
        }

        /* Pill Input Fields */
        .input-group {
            margin-bottom: 16px;
            position: relative;
        }

        .input-wrapper {
            position: relative;
            width: 100%;
        }

        .input-field {
            width: 100%;
            height: 48px;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 9999px;
            padding: 0 20px 0 48px;
            font-size: 14px;
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
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 15px;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .input-field:focus ~ .input-icon {
            color: var(--maroon);
        }

        .password-toggle {
            position: absolute;
            right: 20px;
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
            margin-bottom: 20px;
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

        /* Pill Submit Button */
        .submit-btn {
            width: 100%;
            height: 48px;
            background: var(--maroon);
            color: white;
            border: none;
            border-radius: 9999px;
            font-size: 15px;
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

        /* RIGHT SIDE: Feature Panel */
        .feature-side {
            flex: 1;
            background: 
                radial-gradient(circle at 10% 20%, rgba(255, 241, 242, 0.6) 0%, rgba(253, 244, 245, 0.8) 90%),
                radial-gradient(circle, rgba(112, 26, 45, 0.05) 1.5px, transparent 1.5px);
            background-size: 100% 100%, 24px 24px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            position: relative;
            overflow: visible;
            border-radius: 36px 0 0 36px;
            z-index: 2;
        }

        .feature-side-brand {
            position: absolute;
            top: 40px;
            left: 40px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12px;
            font-weight: 800;
            color: var(--maroon);
            letter-spacing: 0.12em;
            z-index: 10;
            opacity: 0.85;
        }

        /* Mockup Card Container */
        .mockup-wrapper {
            position: relative;
            z-index: 5;
            width: 100%;
            max-width: 450px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Glowing Orbs */
        .feature-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            z-index: 0;
            opacity: 0.35;
            pointer-events: none;
        }

        .feature-orb-1 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(244, 63, 94, 0.25) 0%, transparent 70%);
            top: -50px;
            right: -50px;
        }

        .feature-orb-2 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(112, 26, 45, 0.15) 0%, transparent 70%);
            bottom: -100px;
            left: -100px;
        }

        .illustration-box {
            position: relative;
            width: 115%;
            max-width: 520px;
            height: auto;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .campus-svg-illustration {
            width: 100%;
            height: auto;
            max-height: 340px;
            transform: translateX(-40px);
            filter: drop-shadow(-20px 25px 35px rgba(112, 26, 45, 0.16));
            animation: float-illustration 6s ease-in-out infinite alternate;
            z-index: 10;
        }

        @keyframes float-illustration {
            0% { transform: translateX(-40px) translateY(0); }
            100% { transform: translateX(-40px) translateY(-12px); }
        }

        /* Floating Widgets */
        .floating-widget {
            position: absolute;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1.5px solid rgba(255, 255, 255, 0.95);
            border-radius: 18px;
            padding: 10px 16px;
            box-shadow: 
                0 15px 35px -5px rgba(112, 26, 45, 0.1),
                0 5px 15px -2px rgba(0, 0, 0, 0.05);
            z-index: 12;
            animation: float-widget 6s ease-in-out infinite alternate;
        }

        @keyframes float-widget {
            0% { transform: translateY(0); }
            100% { transform: translateY(-8px); }
        }

        /* Position and color individual widgets */
        .widget-krs {
            top: 10px;
            left: -40px;
            animation-delay: 0s;
        }
        .widget-nilai {
            top: 60px;
            right: -50px;
            animation-delay: -1.5s;
        }
        .widget-jadwal {
            bottom: 25px;
            left: -50px;
            animation-delay: -3s;
        }
        .widget-dosen {
            bottom: 80px;
            right: -40px;
            animation-delay: -4.5s;
        }

        .widget-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 15px;
            flex-shrink: 0;
        }

        .widget-krs .widget-icon { background: var(--maroon); }
        .widget-nilai .widget-icon { background: #fbbf24; color: #1e293b; }
        .widget-jadwal .widget-icon { background: var(--accent); }
        .widget-dosen .widget-icon { background: #06b6d4; }

        .widget-info {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .widget-title {
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.25;
        }

        .widget-desc {
            font-size: 10px;
            font-weight: 600;
            color: #64748b;
            margin-top: 2px;
        }

        /* Promo headline text */
        .promo-text {
            text-align: center;
            max-width: 320px;
        }

        .promo-text h3 {
            font-size: 22px;
            font-weight: 800;
            color: var(--maroon);
            margin: 0 0 12px;
            line-height: 1.35;
        }

        .promo-text p {
            font-size: 14px;
            color: #475569;
            margin: 0;
            line-height: 1.6;
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
                    </div>
                </div>

                <div class="welcome-header">
                    <h1>Portal Dosen</h1>
                    <p>Selamat Datang! Silakan masuk menggunakan kredensial Anda untuk mengakses modul akademik Dosen.</p>
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
                <form action="{{ route('login.dosen.post') }}" method="POST">
                    @csrf
                    
                    <div class="input-group">
                        <div class="input-wrapper">
                            <input type="text" name="email" value="{{ old('email') }}" class="input-field"
                                placeholder="Username atau email dosen" required autofocus>
                            <i class="fas fa-user input-icon"></i>
                        </div>
                        @error('email')
                            <p style="color: #ef4444; font-size: 13px; margin: 8px 0 0 16px; font-weight: 600;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="input-group">
                        <div class="input-wrapper">
                            <input type="password" name="password" id="password" class="input-field"
                                placeholder="Password akun" required
                                oncopy="return this.type !== 'password'" 
                                onpaste="return this.type !== 'password'" 
                                oncut="return this.type !== 'password'">
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
                    </div>

                    <button type="submit" class="submit-btn">
                        <span>Masuk</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- RIGHT PANEL: Vector illustration & Features matching reference -->
        <div class="feature-side">
            <!-- Brand watermark watermark -->
            <div class="feature-side-brand">
                <span>PORTAL DOSEN</span>
            </div>

            <div class="feature-orb feature-orb-1"></div>
            <div class="feature-orb feature-orb-2"></div>

            <div class="mockup-wrapper">
                <div class="illustration-box">
                    <!-- Loading the beautiful Teacher-cuate SVG -->
                    <img src="{{ asset('images/Teacher-cuate.svg') }}" alt="Portal Dosen" class="campus-svg-illustration">

                    <!-- Floating Widgets for Dosen -->
                    <div class="floating-widget widget-krs">
                        <div class="widget-icon"><i class="fas fa-calendar-week"></i></div>
                        <div class="widget-info">
                            <span class="widget-title">Jadwal Mengajar</span>
                            <span class="widget-desc">Perkuliahan Anda</span>
                        </div>
                    </div>
                    <div class="floating-widget widget-nilai">
                        <div class="widget-icon"><i class="fas fa-list-check"></i></div>
                        <div class="widget-info">
                            <span class="widget-title">Absensi Kelas</span>
                            <span class="widget-desc">Daftar Kehadiran</span>
                        </div>
                    </div>
                    <div class="floating-widget widget-jadwal">
                        <div class="widget-icon"><i class="fas fa-user-group"></i></div>
                        <div class="widget-info">
                            <span class="widget-title">Bimbingan PA</span>
                            <span class="widget-desc">Wali Mahasiswa</span>
                        </div>
                    </div>
                    <div class="floating-widget widget-dosen">
                        <div class="widget-icon"><i class="fas fa-pen-to-square"></i></div>
                        <div class="widget-info">
                            <span class="widget-title">Input Nilai</span>
                            <span class="widget-desc">Evaluasi Studi</span>
                        </div>
                    </div>
                </div>

                <div class="promo-text">
                    <h3>Dashboard Akademik Dosen</h3>
                    <p>Kelola seluruh kegiatan perkuliahan mulai dari absensi mahasiswa, persetujuan rencana studi (KRS), bimbingan akademik, hingga pengisian nilai secara digital dan real-time.</p>
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