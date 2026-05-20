<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $surat->jenis_surat_label }} - {{ $prestasi->nama_kegiatan }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin-top: 20mm;
            margin-bottom: 20mm;
            margin-left: 25mm;
            margin-right: 25mm;
        }



        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* ===== Kop Surat ===== */
        .kop-surat {
            width: 100%;
            padding-bottom: 8px;
            border-bottom: 3px solid #000;
            margin-bottom: 15px;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop-table td {
            vertical-align: middle;
        }

        .kop-logo-cell {
            width: 90px;
            text-align: center;
            padding-right: 12px;
        }

        .kop-text-cell {
            text-align: left;
        }

        .kop-instansi {
            font-size: 14pt;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
        }

        .kop-alamat {
            font-size: 10pt;
            line-height: 1.4;
            margin-top: 2px;
        }

        /* ===== Header Info ===== */
        .header-info {
            margin-bottom: 15px;
        }

        .header-tanggal {
            text-align: right;
            margin-bottom: 10px;
        }

        .header-meta-table {
            border-collapse: collapse;
        }

        .header-meta-table td {
            padding: 1px 0;
            vertical-align: top;
            font-size: 11pt;
        }

        .header-meta-label {
            width: 80px;
        }

        .header-meta-sep {
            width: 15px;
            text-align: center;
        }

        /* ===== Content ===== */
        .content-area {
            margin-top: 5px;
        }

        p {
            text-align: justify;
            margin: 0;
            padding: 0;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .underline {
            text-decoration: underline;
        }

        .indent {
            text-indent: 40px;
        }

        .mt-2 { margin-top: 5px; }
        .mt-4 { margin-top: 10px; }
        .mt-6 { margin-top: 15px; }
        .mt-8 { margin-top: 20px; }

        .mb-2 {
            margin-bottom: 8px;
        }

        /* ===== Data Table ===== */
        .data-table {
            width: 85%;
            margin: 8px 0 8px 40px;
            border-collapse: collapse;
        }

        .data-table td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 12pt;
            line-height: 1.5;
        }

        .label-col {
            width: 140px;
        }

        .sep-col {
            width: 15px;
        }

        .value-col {
            padding-left: 5px;
        }

        /* ===== Signature ===== */
        .signature-section {
            margin-top: 15px;
            width: 100%;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            vertical-align: top;
        }

        .signature-left {
            width: 50%;
        }

        .signature-right {
            width: 50%;
            text-align: right;
        }

        .signature-space {
            height: 70px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        /* ===== Footer ===== */
        .footer-note {
            margin-top: 30px;
            font-size: 9pt;
            border-top: 1px solid #ccc;
            padding-top: 6px;
            color: #555;
            font-style: italic;
        }
    </style>
</head>

<body>

    {{-- ===== KOP SURAT ===== --}}
    <div class="kop-surat">
        <table class="kop-table">
            <tr>
                <td class="kop-logo-cell">
                    @php
                        $logoPath = public_path('images/logo_stih_white.png');
                        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
                    @endphp
                    <img src="{{ $logoBase64 }}" style="width: 75px; height: auto;">
                </td>
                <td class="kop-text-cell">
                    <div class="kop-instansi">Sekolah Tinggi Ilmu Hukum (STIH) Adhyaksa</div>
                    <div class="kop-alamat">
                        Jl. Raya Baubau No. 123, Kota Baubau, Sulawesi Tenggara<br>
                        Telepon: (0402) 123456 / Email: info@stih-adhyaksa.ac.id
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ===== HEADER INFO ===== --}}
    <div class="header-info">
        <div class="header-tanggal">
            {{ $lokasi_ttd }}, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}
        </div>

        <table class="header-meta-table">
            <tr>
                <td class="header-meta-label">Nomor</td>
                <td class="header-meta-sep">:</td>
                <td>{{ $surat->nomor_surat }}</td>
            </tr>
            <tr>
                <td class="header-meta-label">Lampiran</td>
                <td class="header-meta-sep">:</td>
                <td>-</td>
            </tr>
            <tr>
                <td class="header-meta-label">Perihal</td>
                <td class="header-meta-sep">:</td>
                <td class="font-bold">@yield('surat-perihal', 'Surat Tugas')</td>
            </tr>
        </table>
    </div>

    {{-- ===== ISI SURAT ===== --}}
    <div class="content-area">
        @yield('letter-content')
    </div>

    {{-- ===== TANDA TANGAN ===== --}}
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td class="signature-left">&nbsp;</td>
                <td class="signature-right">
                    <div>Sekolah Tinggi Ilmu Hukum</div>
                    <div>(STIH) Adhyaksa,</div>
                    <div>{{ $penandatangan_jabatan ?? 'Ketua' }},</div>
                    <div class="signature-space"></div>
                    <div class="signature-name">{{ $penandatangan_nama ?? 'Prof. Dr. Nama Ketua, S.H., M.H.' }}</div>
                    @if($penandatangan_nip)
                        <div>NIP. {{ $penandatangan_nip }}</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    @if($surat->is_backdate)
        <div class="footer-note">
            * Surat ini diterbitkan melalui sistem Manajemen Prestasi STIH Adhyaksa.
        </div>
    @endif

</body>

</html>