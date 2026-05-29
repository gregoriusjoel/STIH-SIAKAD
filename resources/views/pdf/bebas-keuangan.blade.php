<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Surat Keterangan Bebas Keuangan - {{ $pengajuan->mahasiswa->nim }}</title>
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
            margin-bottom: 25px;
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

        /* ===== Document Title ===== */
        .doc-title-container {
            text-align: center;
            margin-bottom: 25px;
        }

        .doc-title {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .doc-number {
            font-size: 11pt;
            margin-top: 2px;
        }

        /* ===== Content ===== */
        p {
            text-align: justify;
            margin-bottom: 15px;
            text-indent: 40px;
        }

        .no-indent {
            text-indent: 0 !important;
        }

        .font-bold {
            font-weight: bold;
        }

        /* ===== Data Table ===== */
        .data-table {
            width: 90%;
            margin: 15px auto;
            border-collapse: collapse;
        }

        .data-table td {
            padding: 4px 0;
            vertical-align: top;
            font-size: 12pt;
        }

        .label-col {
            width: 180px;
        }

        .sep-col {
            width: 20px;
            text-align: center;
        }

        .value-col {
            font-weight: bold;
        }

        /* ===== Signature ===== */
        .signature-section {
            margin-top: 40px;
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
            width: 55%;
        }

        .signature-right {
            width: 45%;
        }

        .signature-space {
            height: 80px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .footer-note {
            margin-top: 50px;
            font-size: 8pt;
            color: #777;
            text-align: center;
            border-top: 1px dashed #ccc;
            padding-top: 8px;
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
                        $logoPath = public_path('images/logo_stih_color.png');
                        $logoBase64 = '';
                        if (file_exists($logoPath)) {
                            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
                        }
                    @endphp
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" style="width: 75px; height: auto;">
                    @endif
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

    {{-- ===== JUDUL DOKUMEN ===== --}}
    <div class="doc-title-container">
        <div class="doc-title">Surat Keterangan Bebas Keuangan</div>
        <div class="doc-number">Nomor: {{ $pengajuan->nomor_surat }}</div>
    </div>

    {{-- ===== ISI DOKUMEN ===== --}}
    <div class="content-area">
        <p class="no-indent">Yang bertanda tangan di bawah ini Kepala Bagian Keuangan / Staf Keuangan Sekolah Tinggi Ilmu Hukum (STIH) Adhyaksa, dengan ini menerangkan bahwa:</p>

        <table class="data-table">
            <tr>
                <td class="label-col">Nama Lengkap</td>
                <td class="sep-col">:</td>
                <td class="value-col">{{ $pengajuan->mahasiswa->user->name }}</td>
            </tr>
            <tr>
                <td class="label-col">NIM</td>
                <td class="sep-col">:</td>
                <td class="value-col">{{ $pengajuan->mahasiswa->nim }}</td>
            </tr>
            <tr>
                <td class="label-col">Program Studi</td>
                <td class="sep-col">:</td>
                <td class="value-col">{{ $pengajuan->mahasiswa->prodi ?? 'Ilmu Hukum' }}</td>
            </tr>
            <tr>
                <td class="label-col">Fakultas</td>
                <td class="sep-col">:</td>
                <td class="value-col">Fakultas Hukum</td>
            </tr>
            <tr>
                <td class="label-col">Semester Sekarang</td>
                <td class="sep-col">:</td>
                <td class="value-col">Semester {{ $pengajuan->mahasiswa->semester ?? '-' }}</td>
            </tr>
        </table>

        <p>Menyatakan bahwa mahasiswa tersebut di atas <strong>telah menyelesaikan dan melunasi seluruh kewajiban administrasi keuangan perkuliahan</strong> di Sekolah Tinggi Ilmu Hukum (STIH) Adhyaksa sampai dengan semester yang bersangkutan.</p>

        <p>Demikian surat keterangan ini diberikan kepada yang bersangkutan untuk dapat dipergunakan sebagaimana mestinya, khususnya sebagai salah satu kelengkapan persyaratan pendaftaran kelulusan / wisuda.</p>
    </div>

    {{-- ===== TANDA TANGAN ===== --}}
    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td class="signature-left">&nbsp;</td>
                <td class="signature-right">
                    <div>Baubau, {{ \Carbon\Carbon::parse($pengajuan->approved_at)->translatedFormat('d F Y') }}</div>
                    <div>Staf Bagian Keuangan,</div>
                    <div class="signature-space"></div>
                    <div class="signature-name">{{ $pengajuan->approver->name ?? 'Staf Keuangan' }}</div>
                    <div>STIH Adhyaksa</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ===== FOOTER NOTE ===== --}}
    <div class="footer-note">
        Dokumen ini diterbitkan secara resmi oleh Sistem Informasi Akademik STIH Adhyaksa pada {{ \Carbon\Carbon::parse($pengajuan->approved_at)->format('d/m/Y H:i') }}.
    </div>

</body>
</html>
