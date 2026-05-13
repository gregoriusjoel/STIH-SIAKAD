<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $surat->jenis_surat_label }} - {{ $prestasi->nama_kegiatan }}</title>
    <style>
        @page {
            margin: 1.5cm 2cm;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
        }

        /* Kop Surat */
        .kop-surat {
            width: 100%;
            border-bottom: 3px solid #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop-logo {
            width: 80px;
            text-align: left;
            vertical-align: middle;
        }

        .kop-logo img {
            width: 70px;
            height: auto;
        }

        .kop-content {
            text-align: center;
            vertical-align: middle;
        }

        .kop-yayasan {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .kop-instansi {
            font-size: 16pt;
            font-weight: bold;
            color: #8B1538;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .kop-address {
            font-size: 10pt;
            font-style: italic;
            line-height: 1.2;
        }

        /* Content Styles */
        .content {
            padding: 0 10px;
        }

        .title-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .surat-title {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .surat-number {
            font-size: 12pt;
            margin-top: 5px;
        }

        .text-justify { text-align: justify; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mt-4 { margin-top: 1rem; }
        .mt-8 { margin-top: 2rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .indent { text-indent: 40px; }

        .data-table {
            width: 100%;
            margin: 15px 0;
            border-collapse: collapse;
        }

        .data-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .label-col { width: 180px; }
        .sep-col { width: 20px; text-align: center; }

        /* Signature Section */
        .signature-section {
            margin-top: 50px;
            width: 100%;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 50%;
            vertical-align: top;
        }

        .signature-space {
            height: 100px;
        }

        /* QR Code Placeholder */
        .qr-placeholder {
            margin-top: 10px;
            width: 80px;
            height: 80px;
            border: 1px solid #ddd;
            display: inline-block;
        }

        .footer-note {
            margin-top: 50px;
            font-size: 10pt;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="kop-surat">
        <table class="kop-table">
            <tr>
                <td class="kop-logo">
                    {{-- Using public_path for DomPDF --}}
                    <img src="{{ public_path('images/logo_stih_white.png') }}" style="background-color: #8B1538; padding: 5px; border-radius: 5px;" alt="Logo">
                </td>
                <td class="kop-content">
                    <div class="kop-yayasan">Yayasan Adhyaksa</div>
                    <div class="kop-instansi">Sekolah Tinggi Ilmu Hukum Adhyaksa</div>
                    <div class="kop-address">
                        Alamat: Jl. Raya Baubau No. 123, Kota Baubau, Sulawesi Tenggara<br>
                        Email: info@stih-adhyaksa.ac.id | Website: www.stih-adhyaksa.ac.id
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="content">
        <div class="title-section">
            <div class="surat-title">@yield('surat-title')</div>
            <div class="surat-number">Nomor: {{ $surat->nomor_surat }}</div>
        </div>

        @yield('letter-content')

        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td></td>
                    <td class="text-left" style="padding-left: 50px;">
                        <div>Ditetapkan di: Baubau</div>
                        <div>Pada tanggal: {{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}</div>
                        <div class="mt-4">
                            Ketua STIH Adhyaksa,
                        </div>
                        <div class="signature-space"></div>
                        <div class="font-bold underline">Prof. Dr. Nama Ketua, S.H., M.H.</div>
                        <div>NIDN. 1234567890</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    @if($surat->is_backdate)
    <div class="footer-note">
        * Surat ini diterbitkan melalui sistem Manajemen Prestasi STIH Adhyaksa.
    </div>
    @endif

</body>
</html>
