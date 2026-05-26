<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Undangan Wisuda - {{ $mahasiswa->user->name }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            background-color: #0b0f19;
            background-image: radial-gradient(circle at 50% 50%, #1e1217 0%, #020306 100%);
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #0f172a;
            box-sizing: border-box;
        }

        /* Subtle Luxury Particle Layer */
        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(rgba(223, 171, 55, 0.05) 1px, transparent 0);
            background-size: 24px 24px;
            pointer-events: none;
            z-index: 1;
        }

        .card-container {
            background-color: #ffffff;
            width: 100%;
            max-width: 650px;
            border-radius: 24px;
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.7), 0 0 50px -10px rgba(139, 21, 56, 0.2);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(223, 171, 55, 0.2);
            box-sizing: border-box;
            z-index: 2;
            animation: fadeInCard 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes fadeInCard {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Golden Luxury Frame Borders */
        .card-border {
            position: absolute;
            top: 16px;
            bottom: 16px;
            left: 16px;
            right: 16px;
            border: 2px solid transparent;
            border-image: linear-gradient(135deg, #dfab37 0%, #b4861b 50%, #dfab37 100%) 1;
            pointer-events: none;
            z-index: 5;
        }

        .card-inner-border {
            position: absolute;
            top: 22px;
            bottom: 22px;
            left: 22px;
            right: 22px;
            border: 1px solid rgba(223, 171, 55, 0.4);
            pointer-events: none;
            z-index: 5;
        }

        /* Luxury Corner Ornaments */
        .corner-ornament {
            position: absolute;
            width: 24px;
            height: 24px;
            border: 3px solid #dfab37;
            pointer-events: none;
            z-index: 6;
        }

        .corner-tl { top: 12px; left: 12px; border-right: none; border-bottom: none; }
        .corner-tr { top: 12px; right: 12px; border-left: none; border-bottom: none; }
        .corner-bl { bottom: 12px; left: 12px; border-right: none; border-top: none; }
        .corner-br { bottom: 12px; right: 12px; border-left: none; border-top: none; }

        .card-header-banner {
            background: linear-gradient(135deg, #8B1538 0%, #6D1029 100%);
            padding: 40px 35px 35px 35px;
            text-align: center;
            border-bottom: 3px solid #dfab37;
            position: relative;
        }

        .header-logo-img {
            width: 60px;
            height: 60px;
            object-contain: fit;
            margin-bottom: 12px;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.15));
        }

        .header-logo-text {
            font-family: 'Cinzel', serif;
            font-size: 24px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 1.5px;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .header-subtitle {
            font-size: 11px;
            font-weight: 700;
            color: #dfab37;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin: 6px 0 0 0;
        }

        .card-body {
            padding: 45px;
            text-align: center;
            background: radial-gradient(circle at center, #ffffff 0%, #faf9f6 100%);
            position: relative;
        }

        .title-section {
            margin-bottom: 35px;
        }

        .title {
            font-family: 'Cinzel', serif;
            font-size: 28px;
            font-weight: 900;
            color: #1a202c;
            margin: 0 0 8px 0;
            letter-spacing: 0.5px;
        }

        .title-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin: 12px 0;
        }

        .title-divider-line {
            width: 50px;
            height: 1.5px;
            background: linear-gradient(90deg, transparent, #b4861b, transparent);
        }

        .title-divider-diamond {
            width: 8px;
            height: 8px;
            background-color: #dfab37;
            transform: rotate(45deg);
        }

        .subtitle {
            font-size: 13px;
            color: #718096;
            margin: 0;
            line-height: 1.5;
            font-weight: 500;
        }

        .recipient-container {
            background-color: #faf7f0;
            background: radial-gradient(circle, #fefdfb 0%, #f8f5ee 100%);
            border: 1px dashed rgba(180, 134, 27, 0.5);
            border-radius: 20px;
            padding: 6px;
            margin-bottom: 32px;
            box-shadow: 0 15px 35px -10px rgba(139, 21, 56, 0.08);
            position: relative;
        }

        .recipient-inner-frame {
            border: 1px solid rgba(180, 134, 27, 0.25);
            border-radius: 14px;
            padding: 28px 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .recipient-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            margin-bottom: 12px;
        }

        .recipient-header-line {
            height: 1px;
            flex-grow: 1;
            max-width: 60px;
            background: linear-gradient(90deg, transparent, rgba(180, 134, 27, 0.4), transparent);
        }

        .recipient-label {
            font-size: 11px;
            font-weight: 700;
            color: #b4861b;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin: 0;
            display: inline-block;
        }

        .recipient-name {
            font-family: 'Cinzel', serif;
            font-size: 26px;
            font-weight: 800;
            color: #8B1538;
            margin: 4px 0;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.8);
        }

        .recipient-divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin: 10px 0;
            width: 100%;
        }

        .recipient-divider-line {
            width: 80px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(139, 21, 56, 0.3), transparent);
        }

        .recipient-divider-dot {
            width: 5px;
            height: 5px;
            background-color: #dfab37;
            border-radius: 50%;
        }

        .recipient-subdetails {
            font-size: 13px;
            color: #4a5568;
            font-weight: 600;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .recipient-subdetails strong {
            color: #1a202c;
            font-weight: 700;
        }

        .nim-badge-container {
            display: flex;
            justify-content: center;
            margin-top: 4px;
        }

        .nim-badge {
            background: linear-gradient(135deg, #8B1538 0%, #6D1029 100%);
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            padding: 6px 18px;
            border-radius: 20px;
            letter-spacing: 1.5px;
            box-shadow: 0 4px 12px rgba(139, 21, 56, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.15);
            display: inline-block;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 32px;
            text-align: left;
        }

        .detail-item {
            background-color: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.04);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 8px 20px -5px rgba(0, 0, 0, 0.02);
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }

        .detail-icon-wrapper {
            background: linear-gradient(135deg, rgba(139, 21, 56, 0.08) 0%, rgba(139, 21, 56, 0.02) 100%);
            color: #8B1538;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            shrink-0: 1;
            border: 1px solid rgba(139, 21, 56, 0.05);
        }

        .detail-icon-wrapper svg {
            width: 22px;
            height: 22px;
        }

        .detail-info {
            flex: 1;
        }

        .detail-label {
            font-size: 10px;
            font-weight: 800;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 4px;
        }

        .detail-value {
            font-size: 13px;
            font-weight: 800;
            color: #1a202c;
            line-height: 1.4;
        }

        .detail-subvalue {
            font-size: 11px;
            color: #718096;
            margin-top: 3px;
            font-weight: 500;
        }

        .qr-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px dashed rgba(223, 171, 55, 0.25);
        }

        .qr-box {
            background-color: #ffffff;
            border: 1px solid rgba(223, 171, 55, 0.3);
            border-radius: 16px;
            padding: 12px;
            width: 120px;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px -8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .qr-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .qr-label {
            font-size: 10px;
            font-weight: 800;
            color: #b4861b;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 12px;
        }

        .footer-note {
            font-size: 11px;
            color: #a0aec0;
            margin-top: 35px;
            line-height: 1.6;
            font-weight: 500;
        }

        /* Actions Area */
        .actions-area {
            margin-top: 30px;
            display: flex;
            gap: 16px;
            justify-content: center;
            z-index: 10;
        }

        .btn {
            background: linear-gradient(135deg, #8B1538 0%, #6D1029 100%);
            color: #ffffff;
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            box-shadow: 0 10px 20px -5px rgba(139, 21, 56, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(139, 21, 56, 0.4);
            filter: brightness(1.1);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.05);
            color: #e2e8f0;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: none;
            backdrop-filter: blur(10px);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.2);
        }

        /* SVG icon sizing */
        .btn svg {
            width: 18px;
            height: 18px;
        }

        /* Print styles */
        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }

            body {
                background: #ffffff !important;
                background-image: none !important;
                padding: 10mm !important;
                margin: 0 !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
                min-height: 277mm !important; /* 297mm A4 height - 20mm total vertical padding */
                box-sizing: border-box !important;
            }

            body::before {
                display: none !important;
            }

            .card-container,
            .card-header-banner,
            .recipient-container,
            .nim-badge,
            .detail-icon-wrapper {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .card-container {
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
                transform: none !important;
                animation: none !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            .card-header-banner {
                padding: 35px 30px 30px 30px !important;
            }

            .header-logo-img {
                width: 56px !important;
                height: 56px !important;
                margin-bottom: 10px !important;
            }

            .header-logo-text {
                font-size: 24px !important;
            }

            .card-body {
                padding: 35px 45px !important;
            }

            .title-section {
                margin-bottom: 25px !important;
            }

            .title {
                font-size: 26px !important;
            }

            .recipient-container {
                margin-bottom: 25px !important;
            }

            .recipient-inner-frame {
                padding: 22px 28px !important;
            }

            .recipient-name {
                font-size: 26px !important;
            }

            .recipient-subdetails {
                margin-bottom: 12px !important;
            }

            .details-grid {
                gap: 16px !important;
                margin-bottom: 25px !important;
            }

            .detail-item {
                padding: 16px 18px !important;
                border-radius: 14px !important;
                gap: 12px !important;
            }

            .detail-icon-wrapper {
                width: 38px !important;
                height: 38px !important;
                border-radius: 10px !important;
            }

            .detail-icon-wrapper svg {
                width: 20px !important;
                height: 20px !important;
            }

            .qr-section {
                margin-top: 25px !important;
                padding-top: 20px !important;
            }

            .qr-box {
                width: 105px !important;
                height: 105px !important;
                border-radius: 14px !important;
                padding: 10px !important;
            }

            .footer-note {
                margin-top: 25px !important;
            }

            .no-print {
                display: none !important;
            }

            .card-border {
                top: 8px !important;
                bottom: 8px !important;
                left: 8px !important;
                right: 8px !important;
            }

            .card-inner-border {
                top: 14px !important;
                bottom: 14px !important;
                left: 14px !important;
                right: 14px !important;
            }

            .corner-tl { top: 4px !important; left: 4px !important; }
            .corner-tr { top: 4px !important; right: 4px !important; }
            .corner-bl { bottom: 4px !important; left: 4px !important; }
            .corner-br { bottom: 4px !important; right: 4px !important; }
        }
    </style>
</head>
<body>

    <div class="card-container">
        <!-- Golden Decorative Corners -->
        <div class="corner-ornament corner-tl"></div>
        <div class="corner-ornament corner-tr"></div>
        <div class="corner-ornament corner-bl"></div>
        <div class="corner-ornament corner-br"></div>

        <!-- Double Frame Borders -->
        <div class="card-border"></div>
        <div class="card-border card-inner-border"></div>

        <!-- Card Header Banner -->
        <div class="card-header-banner">
            <img src="{{ asset('images/logo_stih_white.png') }}" alt="Logo STIH" class="header-logo-img">
            <h1 class="header-logo-text">STIH Adhyaksa</h1>
            <p class="header-subtitle">Biro Administrasi Akademik</p>
        </div>

        <div class="card-body">
            <!-- Title Section -->
            <div class="title-section">
                <h2 class="title">Kartu Undangan Wisuda</h2>
                <div class="title-divider">
                    <div class="title-divider-line"></div>
                    <div class="title-divider-diamond"></div>
                    <div class="title-divider-line"></div>
                </div>
                <p class="subtitle">Harap membawa kartu ini sebagai akses resmi masuk ke area upacara wisuda.</p>
            </div>

            <!-- Graduate details card -->
            <div class="recipient-container">
                <div class="recipient-inner-frame">
                    <div class="recipient-header">
                        <span class="recipient-header-line"></span>
                        <div class="recipient-label">Wisudawan / Wisudawati</div>
                        <span class="recipient-header-line"></span>
                    </div>
                    <h3 class="recipient-name">{{ $mahasiswa->user->name }}</h3>
                    <div class="recipient-divider">
                        <span class="recipient-divider-line"></span>
                        <span class="recipient-divider-dot"></span>
                        <span class="recipient-divider-line"></span>
                    </div>
                    <div class="recipient-subdetails">
                        Program Studi: <strong>{{ $mahasiswa->prodi }}</strong> &bull; Fakultas Hukum
                    </div>
                    <div class="nim-badge-container">
                        <span class="nim-badge">NIM: {{ $mahasiswa->nim }}</span>
                    </div>
                </div>
            </div>

            <!-- Event Details Grid -->
            <div class="details-grid">
                <!-- Batch Wisuda -->
                <div class="detail-item">
                    <div class="detail-icon-wrapper">
                        <!-- school/cap outline icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v7" />
                        </svg>
                    </div>
                    <div class="detail-info">
                        <div class="detail-label">Batch Wisuda</div>
                        <div class="detail-value">{{ $reg->batch->nama_batch }}</div>
                    </div>
                </div>

                <!-- Lokasi Upacara -->
                <div class="detail-item">
                    <div class="detail-icon-wrapper">
                        <!-- location marker outline icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <circle cx="12" cy="11" r="3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        </svg>
                    </div>
                    <div class="detail-info">
                        <div class="detail-label">Lokasi Upacara</div>
                        <div class="detail-value">{{ $reg->batch->lokasi }}</div>
                    </div>
                </div>

                <!-- Waktu & Tanggal -->
                <div class="detail-item" style="grid-column: span 2;">
                    <div class="detail-icon-wrapper">
                        <!-- calendar/time outline icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="detail-info">
                        <div class="detail-label">Waktu & Tanggal</div>
                        <div class="detail-value">{{ $reg->batch->tanggal->translatedFormat('l, d F Y') }}</div>
                        <div class="detail-subvalue">Pukul {{ $reg->batch->waktu_mulai->format('H:i') }} WIB s.d Selesai</div>
                    </div>
                </div>
            </div>

            <!-- Dynamic Verification Stamp -->
            <div class="qr-section">
                <div class="qr-box">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('mahasiswa.wisuda.index')) }}" alt="Kode QR Verifikasi">
                </div>
                <div class="qr-label">Kode Verifikasi Undangan</div>
            </div>

            <div class="footer-note">
                SIAKAD STIH Adhyaksa &copy; {{ date('Y') }}. Dokumen elektronik ini diterbitkan secara sah dan diakreditasi resmi oleh pihak universitas.
            </div>
        </div>
    </div>

    <!-- Actions Area (no print) -->
    <div class="actions-area no-print">
        <button onclick="window.print()" class="btn">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak Kartu Undangan
        </button>
        <a href="{{ route('mahasiswa.wisuda.index') }}" class="btn btn-outline">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Portal
        </a>
    </div>

</body>
</html>
