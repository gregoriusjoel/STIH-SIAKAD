<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Lupa Password - Portal Mahasiswa SATU ADHYAKSA.">
    <meta name="keywords" content="Lupa Password, Reset Password, Portal Mahasiswa, SATU ADHYAKSA">
    <meta name="author" content="SATU ADHYAKSA">

    <title>Lupa Password - StudentSite Universitas Adhyaksa</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo_stih_white.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
            margin-bottom: 36px;
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
            font-size: 28px;
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
            font-size: 32px;
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
            margin-bottom: 24px;
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
            text-decoration: none;
        }

        .submit-btn:hover {
            background: var(--maroon-light);
            transform: translateY(-2px);
            box-shadow: 0 12px 25px -4px rgba(112, 26, 45, 0.4);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
            color: var(--maroon);
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .back-link:hover {
            color: var(--maroon-light);
            transform: translateX(-3px);
        }

        /* Alert boxes */
        .alert-box {
            padding: 16px 20px;
            border-radius: 20px;
            margin-bottom: 24px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            font-size: 14px;
            font-weight: 500;
            box-sizing: border-box;
            line-height: 1.5;
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

        .dev-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 16px;
            padding: 16px;
            margin-top: 16px;
            text-align: center;
        }

        .dev-box p {
            margin: 0 0 12px;
            font-size: 13px;
            color: #1e40af;
            font-weight: 600;
        }

        .dev-btn {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 10px 20px;
            border-radius: 9999px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        /* RIGHT SIDE: Campus Background */
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

        /* Removed dark premium overlay for light style */
        .feature-side::before {
            display: none;
        }

        /* Brand Label at the Top Left of Right Side */
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


        .mockup-wrapper {
            position: relative;
            z-index: 5;
            width: 100%;
            max-width: 450px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Glowing Orbs for modern feel */
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

        .promo-text {
            text-align: center;
            max-width: 340px;
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

        @media (max-width: 1023px) {
            .feature-side { display: none; }
            .form-side { padding: 40px 24px; }
        }
    </style>
</head>

<body>
    <div class="split-container">
        <!-- LEFT PANEL: Email Confirmation Form -->
        <div class="form-side">
            <div class="form-wrapper">
                <div class="brand-logo">
                    <img src="{{ asset('images/logo_stih_white.png') }}" alt="Logo" style="filter: brightness(0.2) sepia(1) hue-rotate(-50deg) saturate(5);">
                    <div class="brand-text">
                        <span class="univ-title">Universitas Adhyaksa</span>
                    </div>
                </div>

                <div class="welcome-header">
                    <h1>Konfirmasi Email</h1>
                    <p>Masukkan alamat email pribadi Anda untuk mengonfirmasi identitas & menerima link reset password.</p>
                </div>

                <!-- Session Alert Messages -->
                @if (session('error'))
                    <div class="alert-box alert-error">
                        <div style="display: flex; align-items: center; gap: 10px; font-weight: 700;">
                            <i class="fas fa-circle-exclamation text-lg"></i>
                            <span>Email Tidak Ditemukan</span>
                        </div>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert-box alert-success">
                        <div style="display: flex; align-items: center; gap: 10px; font-weight: 700;">
                            <i class="fas fa-circle-check text-lg"></i>
                            <span>Link Terkirim!</span>
                        </div>
                        <div>{{ session('success') }}</div>

                        <!-- Local Dev Environment Direct Link Helper -->
                        @if (session('dev_reset_url'))
                            <div class="dev-box">
                                <p><i class="fas fa-vial"></i> Mode Pengujian Lokal / Simulasi:</p>
                                <a href="{{ session('dev_reset_url') }}" class="dev-btn">
                                    <i class="fas fa-arrow-right"></i> Buka Link Reset Password
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    
                    <div class="input-group">
                        <div class="input-wrapper">
                            <input type="email" name="email" value="{{ old('email') }}" class="input-field"
                                placeholder="Masukkan email pribadi Anda" required autofocus>
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                        @error('email')
                            <p style="color: #ef4444; font-size: 13px; margin: 8px 0 0 16px; font-weight: 600;">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="submit-btn">
                        <span>Kirim Link Reset Password</span>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>

                <a href="{{ route('login.mahasiswa') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Halaman Login</span>
                </a>
            </div>
        </div>

        <!-- RIGHT PANEL: Campus Visuals -->
        <div class="feature-side">
            <!-- Brand watermark watermark -->
            <div class="feature-side-brand">
                <span>STUDENTSITE</span>
            </div>

            <div class="feature-orb feature-orb-1"></div>
            <div class="feature-orb feature-orb-2"></div>

            <div class="mockup-wrapper">
                <div class="illustration-box">
                    <img src="{{ asset('images/college campus-amico.svg') }}" alt="Kampus Universitas Adhyaksa" class="campus-svg-illustration">

                    <!-- Floating Widgets -->
                    <div class="floating-widget widget-krs">
                        <div class="widget-icon"><i class="fas fa-book-open"></i></div>
                        <div class="widget-info">
                            <span class="widget-title">KRS Online</span>
                            <span class="widget-desc">Pengisian Cepat</span>
                        </div>
                    </div>
                    <div class="floating-widget widget-nilai">
                        <div class="widget-icon"><i class="fas fa-graduation-cap"></i></div>
                        <div class="widget-info">
                            <span class="widget-title">KHS & Nilai</span>
                            <span class="widget-desc">Hasil Studi</span>
                        </div>
                    </div>
                    <div class="floating-widget widget-jadwal">
                        <div class="widget-icon"><i class="fas fa-calendar-days"></i></div>
                        <div class="widget-info">
                            <span class="widget-title">Jadwal Kuliah</span>
                            <span class="widget-desc">Real-time</span>
                        </div>
                    </div>
                    <div class="floating-widget widget-dosen">
                        <div class="widget-icon"><i class="fas fa-laptop-code"></i></div>
                        <div class="widget-info">
                            <span class="widget-title">E-learning</span>
                            <span class="widget-desc">Materi Kuliah</span>
                        </div>
                    </div>
                </div>
                <div class="promo-text">
                    <h3>Keamanan Akun Mahasiswa</h3>
                    <p>Link verifikasi reset password akan dikirimkan langsung ke email pribadi terdaftar untuk menjaga keamanan akun Anda.</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
