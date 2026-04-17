<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rangkuman Nilai - {{ $mahasiswa->nim ?? '' }}</title>
    <style>
        @page { margin: 10mm 8mm; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #111; margin: 0; }
        .page { width: 100%; }
        .header { width: 100%; border-collapse: collapse; }
        .header td { vertical-align: top; }
        .logo { width: 88px; }
        .logo img { width: 72px; height: auto; }
        .institution { text-align: right; }
        .institution h1 {
            margin: 0;
            font-size: 18px;
            line-height: 1.1;
            letter-spacing: 0;
            font-weight: 700;
            white-space: nowrap;
        }
        .institution p { margin: 2px 0 0; font-size: 10px; }
        .title { margin: 10px 0 10px; text-align: center; font-weight: 700; font-size: 11px; }
        .meta-wrap { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        .meta-wrap td { width: 50%; vertical-align: top; }
        .meta { width: 100%; border-collapse: collapse; }
        .meta td { padding: 0; line-height: 1.3; font-size: 11px; }
        .meta .label { width: 92px; }
        .meta .sep { width: 10px; text-align: center; }
        .nilai-table { width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 11px; }
        .nilai-table th,
        .nilai-table td {
            border: 1px solid #222;
            padding: 2px 4px;
            line-height: 1.1;
        }
        .nilai-table th { text-align: center; font-weight: 700; }
        .nilai-table .summary-label-row { text-align: right; padding-right: 12px; font-weight: 700; }
        .center { text-align: center; }
        .right { text-align: right; }
        .signature {
            margin-top: 14px;
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        .signature td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            padding: 0;
        }
        .city-date {
            text-align: center;
            font-size: 11px;
            margin-top: 0;
            margin-bottom: 8px;
        }
        .sign-space {
            height: 46px;
        }
        .print-note {
            margin-top: 24px;
            font-size: 10px;
        }
    </style>
</head>
<body>
@php
    $nim = $mahasiswa->nim ?? '-';
    $nama = $mahasiswa->nama ?? $mahasiswa->user->name ?? '-';
    $kotaCetak = 'Jakarta';
    $tanggalCetak = \Carbon\Carbon::now('Asia/Jakarta');
    $tanggalLabel = $tanggalCetak->translatedFormat('d F Y');
    $waktuLabel = $tanggalCetak->format('H:i:s');
    $kepalaBagianAkademik = 'Ahmad Ikraam, S.H.,M.H.';
@endphp

<div class="page">
    <table class="header">
        <tr>
            <td class="logo">
                <img src="{{ public_path('images/logo_stih_white-clear.png') }}" alt="Logo STIH">
            </td>
            <td class="institution">
                <h1>SEKOLAH TINGGI ILMU HUKUM (STIH) ADHYAKSA</h1>
                <p>Jl. Margasatwa No.39, RT.1/RW.6, Kec. Jagakarsa,</p>
                <p>Kota Jakarta Selatan, DKI Jakarta 12620</p>
                <p>Telepon : (021) 220 99999 / Email : info@stih-adhyaksa.ac.id</p>
            </td>
        </tr>
    </table>

    <div class="title">KARTU HASIL STUDI MAHASISWA</div>

    <table class="meta-wrap">
        <tr>
            <td>
                <table class="meta">
                    <tr>
                        <td class="label">NIM</td>
                        <td class="sep">:</td>
                        <td>{{ $nim }}</td>
                    </tr>
                    <tr>
                        <td class="label">Nama</td>
                        <td class="sep">:</td>
                        <td>{{ $nama }}</td>
                    </tr>
                    <tr>
                        <td class="label">Dosen PA</td>
                        <td class="sep">:</td>
                        <td>{{ $dosenPa }}</td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="meta">
                    <tr>
                        <td class="label">Tahun Ajaran</td>
                        <td class="sep">:</td>
                        <td>{{ $tahunAjaran }}</td>
                    </tr>
                    <tr>
                        <td class="label">Semester</td>
                        <td class="sep">:</td>
                        <td>{{ $semesterLabel }}</td>
                    </tr>
                    <tr>
                        <td class="label">Prodi</td>
                        <td class="sep">:</td>
                        <td>{{ $prodi }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="nilai-table">
        <thead>
            <tr>
                <th style="width: 6%;">No</th>
                <th style="width: 16%;">Kode</th>
                <th style="width: 43%;">Mata Kuliah</th>
                <th style="width: 7%;">SKS</th>
                <th style="width: 8%;">Nilai</th>
                <th style="width: 8%;">Mutu</th>
                <th style="width: 12%;">Bobot</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                @php
                    $mutuText = fmod((float) $row['mutu'], 1.0) === 0.0
                        ? number_format((float) $row['mutu'], 0)
                        : number_format((float) $row['mutu'], 2);
                @endphp
                <tr>
                    <td class="center">{{ $row['no'] }}</td>
                    <td class="center">{{ $row['kode_mk'] }}</td>
                    <td>{{ $row['nama_mk'] }}</td>
                    <td class="center">{{ $row['sks'] }}</td>
                    <td class="center">{{ $row['nilai_huruf'] }}</td>
                    <td class="center">{{ $mutuText }}</td>
                    <td class="right">{{ number_format((float) $row['bobot'], 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center">Belum ada data nilai yang dipublikasikan.</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="3" class="right"><strong>Total SKS {{ $semesterNumber ?? '' }}</strong></td>
                <td class="center"><strong>{{ $totalSks }}</strong></td>
                <td class="center"><strong></strong></td>
                <td class="center"><strong>{{ number_format($totalMutu, 2) }}</strong></td>
                <td class="center"><strong></strong></td>
            </tr>
            <tr>
                <td colspan="6" class="summary-label-row">Indeks Prestasi Semester (IPS)</td>
                <td class="center"><strong>{{ number_format($ips, 2) }}</strong></td>
            </tr>
            <tr>
                <td colspan="6" class="summary-label-row">Indeks Prestasi Kumulatif (IPK)</td>
                <td class="center"><strong>{{ number_format($ipk, 2) }}</strong></td>
            </tr>
            <tr>
                <td colspan="6" class="summary-label-row">SKS Maksimal yang bisa diambil semester depan</td>
                <td class="center"><strong>{{ $sksMaksimalSemesterDepan }}</strong></td>
            </tr>
        </tbody>
    </table>

    <table class="signature">
        <tr>
            <td></td>
            <td class="city-date">{{ $kotaCetak }}, {{ $tanggalLabel }}</td>
        </tr>
        <tr>
            <td>Dosen Pembimbing Akademik</td>
            <td>Mengetahui<br>Kepala Bagian Akademik</td>
        </tr>
        <tr>
            <td class="sign-space"></td>
            <td class="sign-space"></td>
        </tr>
        <tr>
            <td>({{ $dosenPa }})</td>
            <td>({{ $kepalaBagianAkademik }})</td>
        </tr>
    </table>

    <div class="print-note">
        *KHS ini dicetak secara otomatis oleh sistem SINKAD pada tanggal {{ $tanggalCetak->format('d-m-Y') }} Pukul {{ $waktuLabel }}
    </div>
</div>
</body>
</html>
