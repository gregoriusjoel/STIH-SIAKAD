<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pengajuan->jenis_label }} - {{ $mahasiswa->nim }}</title>
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
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #000;
        }

        /* Helper Classes */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .w-full { width: 100%; }
        .mt-4 { margin-top: 20px; }
        .mb-2 { margin-bottom: 10px; }

        /* Header Table */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
            margin-bottom: 30px;
        }

        .header-table td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: middle;
        }

        .header-logo {
            width: 15%;
            text-align: center;
            background-color: #8B1538; /* Maroon bg for white logo */
        }

        .header-logo img {
            max-width: 60px;
            height: auto;
        }

        .header-title {
            width: 55%;
            text-align: center;
        }

        .header-meta {
            width: 30%;
            font-size: 9pt;
        }

        .header-meta table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }
        .header-meta td {
            border: none;
            padding: 2px;
        }
        .header-meta .border-bottom {
            border-bottom: 1px solid #000;
        }

        /* Address Section */
        .address-section {
            margin-bottom: 30px;
        }

        /* Form Content */
        .form-row {
            margin-bottom: 8px;
        }

        .form-table {
            width: 100%;
            border-collapse: collapse;
        }

        .form-table td {
            padding: 5px;
            vertical-align: top;
        }

        .label-col { width: 160px; }
        .sep-col { width: 20px; text-align: center; }
        .value-col { border-bottom: 1px dotted #999; }

        /* Signature Section */
        .signature-table {
            width: 100%;
            margin-top: 50px;
            border-collapse: collapse;
        }

        .signature-table td {
            vertical-align: top;
            padding: 10px;
        }
        
        .signature-space {
            height: 80px;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            font-size: 9pt;
            font-style: italic;
        }

        ul.list-dash {
            list-style: none;
            padding-left: 20px;
        }
        ul.list-dash li:before {
            content: "~ ";
            padding-right: 5px;
        }
    </style>
</head>
<body>

    {{-- HEADER FORM TABLE --}}
    <table class="header-table">
        <tr>
            <td class="header-logo">
                {{-- Logo is white, so we put a maroon background in CSS --}}
                <img src="{{ public_path('images/logo_stih_white.png') }}" alt="Logo">
            </td>
            <td class="header-title">
                <div class="font-bold">FORMULIR AKADEMIK</div>
                <div class="font-bold uppercase mt-4">FORMULIR PENGAJUAN {{ $pengajuan->jenis === 'cuti' ? 'CUTI AKADEMIK' : 'SURAT KETERANGAN' }}</div>
            </td>
            <td class="header-meta">
                <table>
                    <tr class="border-bottom">
                        <td>PROGRAM SARJANA STIH ADHYAKSA</td>
                    </tr>
                    <tr class="border-bottom">
                        <td>PROGRAM STUDI {{ strtoupper($mahasiswa->prodi ?? 'ILMU HUKUM') }}</td>
                    </tr>
                    <tr>
                        <td class="text-center font-bold" style="padding-top: 10px;">FORM AK-01</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ADDRESS SECTION --}}
    <div class="address-section">
        <div class="font-bold">Yth. Ketua Program Studi ...........................................................</div>
        <div class="font-bold">Program Sarjana Sekolah Tinggi Ilmu Hukum Adhyaksa</div>
        <div class="font-bold">Kampus STIH Adhyaksa Baubau</div>
    </div>

    {{-- BODY TEXT --}}
    <p class="mb-2">Saya mahasiswa Program Studi .......................................................................................................... Jenjang S1</p>

    {{-- STUDENT DATA FORM --}}
    <table class="form-table">
        <tr>
            <td class="label-col">Nama Lengkap</td>
            <td class="sep-col">:</td>
            <td class="value-col">{{ strtoupper($user->name) }}</td>
        </tr>
        <tr>
            <td class="label-col">NIM</td>
            <td class="sep-col">:</td>
            <td class="value-col">{{ $mahasiswa->nim }}</td>
        </tr>
        <tr>
            <td class="label-col">Penanggung Biaya</td>
            <td class="sep-col">:</td>
            <td class="value-col">Sendiri / Beasiswa *)</td>
        </tr>
        <tr>
            <td class="label-col">Alamat Lengkap</td>
            <td class="sep-col">:</td>
            <td class="value-col" style="height: 40px;"> &nbsp; </td>
        </tr>
        <tr>
            <td class="label-col">No Telepon/HP</td>
            <td class="sep-col">:</td>
            <td class="value-col">{{ $user->phone ?? $mahasiswa->phone ?? '' }}</td>
        </tr>
        <tr>
            <td class="label-col">Alasan {{ $pengajuan->jenis === 'cuti' ? 'Cuti' : 'Pengajuan' }} **)</td>
            <td class="sep-col">:</td>
            <td class="value-col">{{ $pengajuan->keterangan }}</td>
        </tr>
    </table>

    {{-- STATEMENT --}}
    <div class="mt-4" style="text-align: justify; line-height: 1.5;">
        @if($pengajuan->jenis === 'cuti')
            Bermaksud mengajukan ijin <strong>CUTI AKADEMIK</strong> pada Semester {{ $mahasiswa->semester ?? '.......' }} (Gasal/Genap *) 
            Tahun Akademik {{ date('Y') }} / {{ date('Y') + 1 }}.
        @else
            Bermaksud mengajukan permohonan <strong>SURAT KETERANGAN {{ strtoupper($pengajuan->jenis_label) }}</strong> 
            pada Semester {{ $mahasiswa->semester ?? '.......' }} (Gasal/Genap *) Tahun Akademik {{ date('Y') }} / {{ date('Y') + 1 }}.
        @endif
    </div>

    <div class="mt-4">
        Demikian permohonan ini disampaikan, atas perhatian dan perkenannya saya ucapkan terima kasih.
    </div>

    {{-- SIGNATURES --}}
    <table class="signature-table">
        <tr>
            <td width="50%">
                <div>Mengetahui/Menyetujui,</div>
                <div class="mb-2">Ketua Program Studi</div>
                <div class="signature-space"></div>
                <div>..................................................................</div>
                <div>NIDN: ......................................................</div>
            </td>
            <td width="50%" class="text-right" style="padding-right: 30px;">
                <div class="mb-2">Hormat saya,</div>
                <div class="signature-space"></div>
                <div class="font-bold underline">{{ strtoupper($user->name) }}</div>
                <div>NIM: {{ $mahasiswa->nim }}</div>
            </td>
        </tr>
    </table>

    {{-- FOOTER NOTES --}}
    <div class="footer">
        <div><strong>Tembusan Yth:</strong></div>
        <ul class="list-dash">
            <li>Pembimbing Akademik</li>
        </ul>
        <div class="mt-4">
            <div><strong>Ket: *) Coret yang tidak perlu</strong></div>
            <div><strong>**) Jika alasan khusus, mohon lampirkan dokumen pendukung</strong></div>
        </div>
    </div>

</body>
</html>
