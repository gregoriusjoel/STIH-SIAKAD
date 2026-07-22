<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SATU ADHYAKSA</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .email-container {
            max-width: 560px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .email-header {
            background: linear-gradient(135deg, #701a2d 0%, #4c0519 100%);
            padding: 32px 24px;
            text-align: center;
            color: #ffffff;
        }
        .email-header img {
            height: 48px;
            margin-bottom: 12px;
        }
        .email-header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }
        .email-body {
            padding: 32px 28px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #701a2d;
            margin-bottom: 16px;
        }
        .btn-container {
            text-align: center;
            margin: 32px 0;
        }
        .btn-reset {
            display: inline-block;
            background-color: #701a2d;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 15px;
            box-shadow: 0 4px 14px rgba(112, 26, 45, 0.3);
        }
        .link-fallback {
            font-size: 12px;
            color: #64748b;
            word-break: break-all;
            background: #f1f5f9;
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .email-footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h2>UNIVERSITAS ADHYAKSA</h2>
            <div style="font-size: 12px; opacity: 0.8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px;">StudentSite Portal</div>
        </div>
        <div class="email-body">
            <div class="greeting">Halo, {{ $nama }}!</div>
            <p>Kami menerima permintaan untuk mereset kata sandi (password) akun StudentSite Anda.</p>
            <p>Silakan klik tombol di bawah ini untuk membuat password baru akun Anda:</p>
            
            <div class="btn-container">
                <a href="{{ $resetUrl }}" class="btn-reset">Reset Password Saya</a>
            </div>

            <p style="font-size: 13px; color: #64748b;">Link ini berlaku untuk 1 kali penggunaan. Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini.</p>
            
            <div class="link-fallback">
                <strong style="color: #334155;">Tidak bisa mengklik tombol?</strong><br>
                Salin dan tempel link berikut pada browser Anda:<br>
                <a href="{{ $resetUrl }}" style="color: #701a2d;">{{ $resetUrl }}</a>
            </div>
        </div>
        <div class="email-footer">
            &copy; {{ date('Y') }} Universitas Adhyaksa - SATU ADHYAKSA. All rights reserved.
        </div>
    </div>
</body>
</html>
