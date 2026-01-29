<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>KRS - {{ $mahasiswa->user->name ?? '' }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #222; }
        .container { width: 100%; margin: 0 auto; }
        .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
        h1 { font-size:16px; margin:0; }
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8" />
            <title>KRS - {{ $mahasiswa->user->name ?? '' }}</title>
            <style>
                @page { margin: 25mm 20mm; }
                body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #222; }
                .container { width: 100%; margin: 0 auto; }
                .brand { display:flex; align-items:center; gap:12px; }
                .brand img { width:64px; height:64px; object-fit:contain; }
                .univ { font-weight:700; color:#7a1621; font-size:16px; }
                .title { text-align:center; margin-top:6px; margin-bottom:6px; }
                .title h2 { margin:0; font-size:14px; }
                .meta { display:flex; justify-content:space-between; margin-top:10px; }
                .info { border:1px solid #ddd; padding:8px; width:60%; }
                .info .row { display:flex; gap:8px; margin-bottom:4px; }
                .info .label { width:110px; color:#444; }
                .info .value { font-weight:600; }
                .summary { border:1px solid #ddd; padding:8px; width:35%; }
                table { width:100%; border-collapse: collapse; margin-top:12px; }
                th, td { border:1px solid #bbb; padding:8px 6px; vertical-align:top; }
                th { background:#f7f5f6; color:#333; font-weight:700; }
                tbody tr:nth-child(even) td { background: #fbfbfb; }
                .right { text-align:right; }
                .center { text-align:center; }
                .signatures { margin-top:28px; display:flex; justify-content:space-between; }
                .sign-block { width:40%; text-align:center; }
                .sign-line { margin-top:48px; border-top:1px solid #000; width:70%; margin-left:auto; margin-right:auto; }
                .small { font-size:11px; color:#666; }
            </style>
        </head>
        <body>
            <div class="container">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <div style="width:100%; display:flex; align-items:flex-start; gap:12px;">
                        <div style="width:110px;">
                            <img src="{{ public_path('images/logo_stih_white.png') }}" alt="logo" style="width:100px; height:auto;">
                        </div>
                        <div style="flex:1; text-align:center;">
                            <div style="font-weight:800; font-size:18px;">SEKOLAH TINGGI ILMU HUKUM (STIH) ADHYAKSA</div>
                            <div style="font-size:11px; margin-top:6px;">Jl. Margasatwa No.39, RT.1/RW.6, Kec. Jagakarsa, Kota Jakarta Selatan, DKI Jakarta 12620</div>
                            <div style="font-size:11px;">Telepon : (021) 220 99999 / Email : info@stih-adhyaksa.ac.id</div>
                        </div>
                        <div style="width:160px; text-align:right;">
                            <div style="font-size:12px;">Tanggal Cetak</div>
                            <div style="font-weight:600">{{ \Carbon\Carbon::now()->format('d F Y') }}</div>
                        </div>
                    </div>
                </div>

                <div class="title">
                    <h2>KARTU RENCANA STUDI (KRS)</h2>
                    <div class="small">{{ $semesterAktif->nama_semester ?? '-' }} — {{ $semesterAktif->tahun_ajaran ?? '-' }}</div>
                </div>

                <div style="display:flex; gap:12px; margin-top:10px;">
                    <div style="flex:1; border:0;">
                        <table style="width:100%; border-collapse:collapse; font-size:12px;">
                            <tr>
                                <td style="width:90px;">NIM</td>
                                <td style="width:6px;">:</td>
                                <td>{{ $mahasiswa->nim }}</td>
                                <td style="width:120px;"></td>
                                <td style="width:120px;">Tahun Ajaran</td>
                                <td style="width:6px;">:</td>
                                <td>{{ $semesterAktif->tahun_ajaran ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td>{{ $mahasiswa->user->name }}</td>
                                <td></td>
                                <td>Semester</td>
                                <td>:</td>
                                <td>{{ $mahasiswa->getCurrentSemester() }} — {{ ($mahasiswa->getCurrentSemester() % 2 == 1) ? 'Ganjil' : 'Genap' }}</td>
                            </tr>
                            <tr>
                                <td>Dosen PA</td>
                                <td>:</td>
                                <td>{{ optional($mahasiswa->dosen_pa)->user->name ?? '-' }}</td>
                                <td></td>
                                <td>Prodi</td>
                                <td>:</td>
                                <td>{{ $mahasiswa->program_studi ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th style="width:40px">No</th>
                            <th style="width:100px">Kode MK</th>
                            <th>Mata Kuliah</th>
                            <th style="width:50px" class="center">SKS</th>
                            <th style="width:60px">Hari</th>
                            <th style="width:70px">Jam</th>
                            <th style="width:180px">Ruangan</th>
                            <th style="width:160px">Dosen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($existingKrs as $i => $k)
                        <tr>
                            <td class="center">{{ $i + 1 }}</td>
                            <td>{{ optional(optional($k->kelasMataKuliah)->mataKuliah)->kode_mk ?? optional($k->mataKuliah)->kode_mk ?? '-' }}</td>
                            <td>{{ optional(optional($k->kelasMataKuliah)->mataKuliah)->nama_mk ?? optional($k->mataKuliah)->nama_mk ?? '-' }}</td>
                            <td class="center">{{ optional(optional($k->kelasMataKuliah)->mataKuliah)->sks ?? optional($k->mataKuliah)->sks ?? 0 }}</td>
                            <td class="center">{{ optional($k->kelasMataKuliah)->hari ?? '-' }}</td>
                            <td class="center">@if(optional($k->kelasMataKuliah)->jam_mulai){{ substr(optional($k->kelasMataKuliah)->jam_mulai,0,5) }} - {{ substr(optional($k->kelasMataKuliah)->jam_selesai,0,5) }}@else-@endif</td>
                            <td>{{ optional($k->kelasMataKuliah)->ruang ?? '-' }} {{ optional($k->kelasMataKuliah)->kode_kelas ? '• ' . optional($k->kelasMataKuliah)->kode_kelas : '' }}</td>
                            <td>{{ optional(optional(optional($k->kelasMataKuliah)->dosen)->user)->name ?? '-' }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" style="text-align:right; font-weight:700;">Total SKS</td>
                            <td class="center" style="font-weight:700">{{ $existingKrs->sum(function($k){ return optional(optional($k->kelasMataKuliah)->mataKuliah)->sks ?? optional($k->mataKuliah)->sks ?? 0; }) }}</td>
                            <td colspan="4"></td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-top:18px; display:flex; justify-content:space-between;">
                    <div style="width:40%; text-align:center;">
                        <div class="small">Dosen Pembimbing Akademik</div>
                        <div class="sign-line"></div>
                        <div class="small">({{ optional($mahasiswa->dosen_pa)->user->name ?? '................' }})</div>
                    </div>
                    <div style="width:40%; text-align:center;">
                        <div class="small">Jakarta, {{ \Carbon\Carbon::now()->format('d F Y') }}</div>
                        <div class="small">Mahasiswa</div>
                        <div class="sign-line"></div>
                        <div class="small">({{ $mahasiswa->user->name }})</div>
                    </div>
                </div>

                <div style="margin-top:18px; font-size:10px; color:#444;">
                    *KHS ini dicetak secara otomatis oleh sistem SINKAD pada tanggal {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}
                </div>

            </div>
        </body>
        </html>
