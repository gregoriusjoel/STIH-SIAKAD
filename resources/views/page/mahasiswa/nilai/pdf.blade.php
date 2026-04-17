<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>KHS - {{ $mahasiswa->user->name ?? '' }}</title>
    <style>
        @page { margin: 10mm 8mm; }
        body { font-family: Helvetica, Arial, sans-serif; font-size: 9px; color: #111827; line-height: 1.25; }
        .container { width: 100%; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .header-table td { border: 0; vertical-align: top; }
        .center-title { text-align: center; font-weight: 700; font-size: 18px; margin-top: 0; }
        .subtitle { text-align: center; font-weight: 700; margin-top: 4px; margin-bottom: 6px; font-size: 15px; }
        .muted { color: #4b5563; font-size: 10px; }
        .meta-table { width: 100%; border-collapse: collapse; margin-top: 6px; margin-bottom: 6px; }
        .meta-table td { border: 1px solid #c7c7c7; padding: 3px 4px; font-size: 8.5px; }
        .section-title { margin-top: 8px; margin-bottom: 3px; font-weight: 700; font-size: 10.5px; }
        .khs-table { width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 4px; }
        .khs-table th, .khs-table td { border: 1px solid #bfc3c9; padding: 3px; font-size: 8.5px; }
        .khs-table th { background: #f3f4f6; font-weight: 700; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .summary-box { margin-top: 8px; }
        .summary-table { width: 100%; border-collapse: collapse; }
        .summary-table td { border: 1px solid #c7c7c7; padding: 4px; font-size: 8.5px; }
        .footer-note { margin-top: 6px; font-size: 7.5px; color: #4b5563; }
    </style>
</head>
<body>
@php
    $nama = $mahasiswa->nama ?? $mahasiswa->user->name ?? '-';
    $nim = $mahasiswa->nim ?? '-';
    $selectedSemester = $selectedSemester ?? '';
    $isSemesterFiltered = $isSemesterFiltered ?? false;
@endphp

<div class="container">
    <table class="header-table">
        <tr>
            <td style="width: 82px;">
                <img src="{{ public_path('images/logo_stih_white.png') }}" alt="logo" style="width: 72px; height: auto;">
            </td>
            <td>
                <div class="center-title">SEKOLAH TINGGI ILMU HUKUM (STIH) ADHYAKSA</div>
                <div class="muted" style="text-align:center; margin-top:4px;">
                    Jl. Margasatwa No.39, RT.1/RW.6, Jagakarsa, Jakarta Selatan, DKI Jakarta 12620
                </div>
                <div class="muted" style="text-align:center;">
                    Telepon: (021) 220 99999 | Email: info@stih-adhyaksa.ac.id
                </div>
            </td>
            <td style="width: 92px; text-align: right;">
                <div class="muted">Tanggal Cetak</div>
                <div>{{ \Carbon\Carbon::now()->format('d F Y') }}</div>
            </td>
        </tr>
    </table>

    <div class="subtitle">KARTU HASIL STUDI (KHS)</div>
    @if($isSemesterFiltered && $selectedSemester !== '')
        <div class="muted" style="text-align:center; margin-bottom:4px; font-weight:700;">
            Cetak Per Semester: {{ $selectedSemester }}
        </div>
    @endif

    <table class="meta-table">
        <tr>
            <td style="width: 14%;">NIM</td>
            <td style="width: 36%;">{{ $nim }}</td>
            <td style="width: 14%;">Program Studi</td>
            <td style="width: 36%;">{{ $mahasiswa->program_studi ?? $mahasiswa->prodi ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>{{ $nama }}</td>
            <td>{{ $isSemesterFiltered ? 'Total SKS Semester' : 'Total SKS Lulus' }}</td>
            <td>{{ $totalSks }}</td>
        </tr>
    </table>

    @forelse($nilaiPerSemester as $semesterNama => $nilaiList)
        @php
            $semTotalSks = 0;
            $semTotalMutu = 0;
        @endphp

        <div class="section-title">{{ $semesterNama }}</div>
        <table class="khs-table">
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">No</th>
                    <th style="width: 14%;">Kode MK</th>
                    <th style="width: 39%;">Mata Kuliah</th>
                    <th style="width: 7%;" class="text-center">SKS</th>
                    <th style="width: 10%;" class="text-center">Nilai</th>
                    <th style="width: 8%;" class="text-center">Grade</th>
                    <th style="width: 17%;" class="text-center">Mutu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nilaiList as $i => $krs)
                    @php
                        $mk = $krs->kelasMataKuliah?->mataKuliah ?? $krs->mataKuliah;
                        $nilai = $krs->nilai;

                        $angka = (float) ($nilai->nilai_akhir ?? $nilai->nilai ?? 0);
                        $grade = $nilai->grade ?? '-';
                        if ($grade === '-' || $grade === null || $grade === '') {
                            if ($angka >= 80) $grade = 'A';
                            elseif ($angka >= 76) $grade = 'A-';
                            elseif ($angka >= 72) $grade = 'B+';
                            elseif ($angka >= 68) $grade = 'B';
                            elseif ($angka >= 64) $grade = 'B-';
                            elseif ($angka >= 60) $grade = 'C+';
                            elseif ($angka >= 56) $grade = 'C';
                            elseif ($angka >= 45) $grade = 'D';
                            else $grade = 'E';
                        }

                        $bobot = (float) ($nilai->bobot ?? 0);
                        if ($bobot <= 0) {
                            if ($angka >= 80) $bobot = 4.00;
                            elseif ($angka >= 76) $bobot = 3.67;
                            elseif ($angka >= 72) $bobot = 3.33;
                            elseif ($angka >= 68) $bobot = 3.00;
                            elseif ($angka >= 64) $bobot = 2.67;
                            elseif ($angka >= 60) $bobot = 2.33;
                            elseif ($angka >= 56) $bobot = 2.00;
                            elseif ($angka >= 45) $bobot = 1.00;
                            else $bobot = 0.00;
                        }

                        $sks = (int) ($mk?->sks ?? 0);
                        $mutu = $bobot * $sks;
                        $semTotalSks += $sks;
                        $semTotalMutu += $mutu;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $mk?->kode_mk ?? '-' }}</td>
                        <td>{{ $mk?->nama_mk ?? '-' }}</td>
                        <td class="text-center">{{ $sks }}</td>
                        <td class="text-center">{{ rtrim(rtrim(number_format($angka, 2, '.', ''), '0'), '.') }}</td>
                        <td class="text-center">{{ $grade }}</td>
                        <td class="text-center">{{ number_format($mutu, 2) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-right"><strong>Total Semester</strong></td>
                    <td class="text-center"><strong>{{ $semTotalSks }}</strong></td>
                    <td colspan="2" class="text-center"><strong>IPS</strong></td>
                    <td class="text-center"><strong>{{ number_format($ipsPerSemester[$semesterNama]['ips'] ?? 0, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
    @empty
        <table class="khs-table">
            <tbody>
                <tr>
                    <td class="text-center">Belum ada data nilai yang dipublikasikan.</td>
                </tr>
            </tbody>
        </table>
    @endforelse

    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td style="width: 30%;"><strong>Total SKS</strong></td>
                <td style="width: 20%;" class="text-center">{{ $totalSks }}</td>
                <td style="width: 30%;"><strong>{{ $isSemesterFiltered ? 'IPS Semester' : 'IPK Kumulatif' }}</strong></td>
                <td style="width: 20%;" class="text-center">{{ number_format($ipk, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer-note">
        *KHS ini dicetak otomatis oleh sistem SIAKAD pada {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}.
    </div>
</div>
</body>
</html>
