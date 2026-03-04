<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>Berita Acara Perkuliahan - {{ $mataKuliah }} {{ $kelas }}</title>
    <style>
        @page {
            margin: 10mm 15mm;
            size: A4 landscape;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -60%);
            width: 400px;
            opacity: 0.15;
            z-index: -100;
        }

        /* Header */
        .header {
            width: 100%;
            height: 100px;
            margin-bottom: 0;
            display: block;
        }
        .header img {
            width: 110px;
            height: auto;
        }

        .fixed-footer {
            position: fixed;
            bottom: 0px;
            left: 0;
            width: 100%;
            height: 100px;
        }
        .footer-logo {
            position: absolute;
            left: 0;
            bottom: 0;
        }
        .footer-logo img {
            height: 100px;
            width: auto;
        }
        .footer-text {
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 11px;
            width: 100%;
            padding-top: 25px;
            line-height: 1.2;
        }

        /* Content Container */
        .content {
            margin-top: 10px;
            margin-bottom: 55px;
        }

        /* Title */
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            margin: 0 0 10px 0;
        }

        /* Metadata */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3px;
        }
        .meta-table td {
            border: none;
            padding: 4px 0;
            font-size: 11px;
            vertical-align: top;
        }

        /* Main table */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 10px;
            table-layout: fixed;
        }
        .main-table th,
        .main-table td {
            border: 1px solid #000;
            padding: 8px 4px;
            vertical-align: middle;
            text-align: center;
        }
        .main-table th {
            background: #ff0000;
            color: #000;
            font-weight: bold;
            font-size: 11px;
        }
        .main-table td.pokok-bahasan {
            text-align: center;
        }
        .ujian-row td {
            background: #ff0000;
            color: #000;
            font-weight: bold;
            font-size: 11px;
            padding: 5px;
        }

        /* Column widths */
        .pert-col { width: 5%; }
        .hari-col { width: 22%; }
        .waktu-col { width: 7%; }
        .pokok-col { width: 36%; }
        .hadir-col { width: 10%; }
        .tidak-col { width: 10%; }
        .ttd-col { width: 10%; }

        /* Catatan & Signature */
        .catatan {
            margin-top: 15px;
            font-size: 11px;
        }
        .catatan p {
            margin: 2px 0;
            text-indent: 20px;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: -20px;
        }
        .signature-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }
        .sign-right {
            text-align: center;
            width: 250px;
        }
        .sign-name {
            margin-top: 70px;
            font-weight: bold;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <img src="{{ public_path('images/logo_stih_white-clear.png') }}" class="watermark">

    {{-- HEADER (Only on Page 1) --}}
    <div class="header">
        <img src="{{ public_path('images/logo_stih_white-clear.png') }}" alt="Logo STIH">
    </div>

    {{-- FIXED FOOTER --}}
    <div class="fixed-footer">
        <div class="footer-logo">
            <img src="{{ public_path('images/certified.png') }}" alt="ISO Certified">
        </div>
        <div class="footer-text">
            Jl. Margasatwa No. 39, Jagakarsa, Kota Jakarta Selatan, DKI Jakarta<br>
            Telepon : (021) 220 99999 / Email: info@stih-adhyaksa.ac.id
        </div>
    </div>

    <div class="content">
        {{-- TITLE --}}
        <div class="title">BERITA ACARA PERKULIAHAN</div>

        {{-- METADATA --}}
        <table class="meta-table">
            <tr>
                <td style="width: 90px;">Program Studi</td>
                <td style="width: 10px;">:</td>
                <td>{{ $prodi }}</td>
                <td style="width: 250px;"></td>
                <td style="width: 90px;">Tahun Akademik</td>
                <td style="width: 10px;">:</td>
                <td>{{ $tahunAkademik }}</td>
            </tr>
            <tr>
                <td>Mata Kuliah</td>
                <td>:</td>
                <td>{{ $mataKuliah }}</td>
                <td></td>
                <td>Semester</td>
                <td>:</td>
                <td>{{ $semester }}</td>
            </tr>
            <tr>
                <td>Pengampu M.K</td>
                <td>:</td>
                <td>{{ $kodeMK }}</td>
                <td></td>
                <td>Kelas</td>
                <td>:</td>
                <td>{{ $kelas }}</td>
            </tr>
        </table>

        {{-- MAIN TABLE --}}
        <table class="main-table">
            <thead>
                <tr>
                    <th class="pert-col">PERT.<br>KE-</th>
                    <th class="hari-col">HARI/TGL</th>
                    <th class="waktu-col">WAKTU</th>
                    <th class="pokok-col">POKOK BAHASAN</th>
                    <th class="hadir-col">MAHASISWA<br>YG HADIR</th>
                    <th class="tidak-col">MAHASISWA<br>TDK HADIR</th>
                    <th class="ttd-col">TTD DOSEN</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    @if($row['is_exam'])
                        <tr class="ujian-row">
                            <td class="pert-col">{{ $row['no'] }}</td>
                            <td colspan="6">{{ $row['exam_label'] }}</td>
                        </tr>
                    @else
                        <tr>
                            <td class="pert-col">{{ $row['no'] }}</td>
                            <td class="hari-col">{{ $row['hari_tgl'] }}</td>
                            <td class="waktu-col">{{ $row['waktu'] }}</td>
                            <td class="pokok-col pokok-bahasan">{{ $row['pokok_bahasan'] }}</td>
                            <td class="hadir-col">{{ $row['hadir'] }}</td>
                            <td class="tidak-col">{{ $row['tidak_hadir'] }}</td>
                            <td class="ttd-col"></td>
                        </tr>
                    @endif
                    
                    @if($row['no'] == 9)
                        </tbody>
                        </table>
                        
                        <div class="page-break"></div>
                        
                        {{-- IDENTITAS HALAMAN 2 UNTUK MENCEGAH NABRAK --}}
                        <div class="header" style="margin-top: 10px; margin-bottom: 30px;">
                            <img src="{{ public_path('images/logo_stih_white-clear.png') }}" alt="Logo STIH">
                        </div>

                        <table class="main-table">
                            <tbody>
                    @endif
                @endforeach
            </tbody>
        </table>

        {{-- CATATAN --}}
        <div class="catatan">
            <p><strong>Catatan:</strong></p>
            <p style="text-indent: 10px;">- Setiap selesai perkuliahan Dosen wajib mengisi berita acara ini.</p>
        </div>

        {{-- SIGNATURE --}}
        <table class="signature-table">
            <tr>
                <td></td>
                <td class="sign-right">
                    <div style="margin-bottom: 2px;">Mengetahui,</div>
                    <div>Ketua Program Studi</div>
                    <div class="sign-name">Adilla Meytiara I., S.H., LL.M.</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
