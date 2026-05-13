@extends('surat.prestasi.layout')

@section('surat-title', 'PIAGAM PENGHARGAAN')

@section('letter-content')
    <div class="text-center mt-8">
        <p style="font-size: 14pt; font-style: italic;">Diberikan Kepada:</p>
        <h2 style="font-size: 20pt; margin: 15px 0; border-bottom: 2px solid #8B1538; display: inline-block; padding: 0 30px;">
            {{ strtoupper($user->name) }}
        </h2>
        <p style="font-size: 12pt; font-weight: bold;">{{ $pengaju->nim ?? $pengaju->nidn }}</p>
    </div>

    <div class="text-justify mt-8">
        <p class="indent" style="line-height: 1.8;">Atas prestasi luar biasa dan dedikasi tinggi yang telah ditunjukkan sebagai:</p>
        
        <div class="text-center mt-4">
            <h3 style="font-size: 16pt; color: #8B1538;">{{ strtoupper($prestasi->jenis_prestasi) }}</h3>
            <p style="font-size: 14pt; margin-top: 5px;">Pada Kegiatan:</p>
            <p style="font-size: 14pt; font-weight: bold;">{{ $prestasi->nama_kegiatan }}</p>
        </div>

        <table class="data-table mt-4" style="width: 80%; margin-left: auto; margin-right: auto;">
            <tr>
                <td style="width: 150px;">Tingkat</td>
                <td style="width: 20px;">:</td>
                <td>{{ $prestasi->tingkat_label }}</td>
            </tr>
            <tr>
                <td>Penyelenggara</td>
                <td>:</td>
                <td>{{ $prestasi->penyelenggara }}</td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($prestasi->tanggal_mulai)->translatedFormat('d F Y') }}</td>
            </tr>
        </table>

        <p class="mt-8 indent">Institusi Sekolah Tinggi Ilmu Hukum (STIH) Adhyaksa menyampaikan apresiasi yang setinggi-tingginya. Semoga prestasi ini menjadi motivasi untuk terus berkarya dan memberikan kontribusi terbaik bagi bangsa dan negara.</p>
    </div>
@endsection
