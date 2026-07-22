<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Reset Password Baru - Portal Mahasiswa SATU ADHYAKSA.">
    <meta name="keywords" content="Reset Password, Portal Mahasiswa, SATU ADHYAKSA">
    <meta name="author" content="SATU ADHYAKSA">

    <title>Reset Password Baru - StudentSite Universitas Adhyaksa</title>

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
            font-size: 22px;
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
            padding: 0 54px 0 54px;
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
            margin-top: 10px;
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

        /* RIGHT SIDE: Campus Visuals */
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

        .feature-side::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(76, 5, 25, 0.65) 0%, rgba(28, 0, 8, 0.75) 100%);
            z-index: 1;
        }

        .mockup-wrapper {
            position: relative;
            z-index: 5;
            width: 100%;
            max-width: 440px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .illustration-box {
            position: relative;
            width: 240px;
            height: 240px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

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
            background: rgba(251, 191, 36, 0.15);
            color: #fbbf24;
            border: 2px solid rgba(251, 191, 36, 0.35);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 40px rgba(251, 191, 36, 0.25);
            backdrop-filter: blur(12px);
            animation: float-icon 6s infinite alternate ease-in-out;
        }

        @keyframes float-icon {
            0% { transform: translateY(0); }
            100% { transform: translateY(-12px); }
        }

        .promo-text {
            text-align: center;
            max-width: 340px;
        }

        .promo-text h3 {
            font-size: 20px;
            font-weight: 800;
            color: #ffffff;
            margin: 0 0 10px;
            line-height: 1.4;
        }

        .promo-text p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.75);
            margin: 0;
            line-height: 1.5;
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
        <!-- LEFT PANEL: Reset Password Form -->
        <div class="form-side">
            <div class="form-wrapper">
                <div class="brand-logo">
                    <img src="{{ asset('images/logo_stih_white.png') }}" alt="Logo" style="filter: brightness(0.2) sepia(1) hue-rotate(-50deg) saturate(5);">
                    <div class="brand-text">
                        <span class="univ-title">Universitas Adhyaksa</span>
                        <span class="site-title">StudentSite</span>
                    </div>
                </div>

                <div class="welcome-header">
                    <h1>Reset Password</h1>
                    <p>Buat kata sandi baru untuk akun StudentSite Anda. Minimal 8 karakter.</p>
                </div>

                <!-- Session Alert Messages -->
                @if (session('error'))
                    <div class="alert-box alert-error">
                        <i class="fas fa-circle-exclamation text-lg"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    
                    <!-- Hidden Token & Email -->
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="input-group">
                        <div class="input-wrapper">
                            <input type="password" name="password" id="password" class="input-field"
                                placeholder="Password baru" required autofocus>
                            <i class="fas fa-lock input-icon"></i>
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('password', this)"></i>
                        </div>
                        @error('password')
                            <p style="color: #ef4444; font-size: 13px; margin: 8px 0 0 16px; font-weight: 600;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="input-group">
                        <div class="input-wrapper">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="input-field"
                                placeholder="Konfirmasi password baru" required>
                            <i class="fas fa-shield-halved input-icon"></i>
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('password_confirmation', this)"></i>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">
                        <span>Perbarui Password</span>
                        <i class="fas fa-check-circle"></i>
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
            <div class="mockup-wrapper">
                <div class="illustration-box">
                    <div class="main-illustration-icon">
                        <i class="fas fa-shield-cat"></i>
                    </div>
                </div>
                <div class="promo-text">
                    <h3>Password Berhasil Diverifikasi</h3>
                    <p>Setelah password diperbarui, Anda dapat langsung masuk kembali ke portal dengan kredensial baru Anda.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Toggle Password Visibility JS -->
    <script>
        function togglePassword(inputId, toggleIcon) {
            const passwordField = document.getElementById(inputId);
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
