@extends('surat.prestasi.layout')

@section('surat-perihal', 'Piagam Penghargaan')

@section('letter-content')
    <div class="text-justify">
        <p class="indent">Yang bertanda tangan di bawah ini, {{ $penandatangan_jabatan ?? 'Ketua' }} Sekolah Tinggi Ilmu Hukum (STIH) Adhyaksa, dengan ini memberikan penghargaan kepada:</p>

        <table class="data-table">
            <tr>
                <td class="label-col">Nama</td>
                <td class="sep-col">:</td>
                <td class="value-col">{{ $user->name }}</td>
            </tr>
            <tr>
                <td class="label-col">NIM/NIDN</td>
                <td class="sep-col">:</td>
                <td class="value-col">{{ $pengaju->nim ?? $pengaju->nidn }}</td>
            </tr>
            <tr>
                <td class="label-col">Program Studi</td>
                <td class="sep-col">:</td>
                <td class="value-col">{{ $pengaju->prodi ?? 'Ilmu Hukum' }}</td>
            </tr>
        </table>

        <p>Atas prestasi luar biasa dan dedikasi tinggi yang telah ditunjukkan sebagai:</p>

        <table class="data-table">
            <tr>
                <td class="label-col">Capaian/Prestasi</td>
                <td class="sep-col">:</td>
                <td class="value-col font-bold">{{ $prestasi->jenis_prestasi }}</td>
            </tr>
            <tr>
                <td class="label-col">Nama Kegiatan</td>
                <td class="sep-col">:</td>
                <td class="value-col">{{ $prestasi->nama_kegiatan }}</td>
            </tr>
            <tr>
                <td class="label-col">Tingkat</td>
                <td class="sep-col">:</td>
                <td class="value-col">{{ $prestasi->tingkat_label }}</td>
            </tr>
            <tr>
                <td class="label-col">Penyelenggara</td>
                <td class="sep-col">:</td>
                <td class="value-col">{{ $prestasi->penyelenggara }}</td>
            </tr>
            <tr>
                <td class="label-col">Waktu</td>
                <td class="sep-col">:</td>
                <td class="value-col">
                    {{ \Carbon\Carbon::parse($prestasi->tanggal_mulai)->translatedFormat('d F Y') }}
                    @if($prestasi->tanggal_selesai && \Carbon\Carbon::parse($prestasi->tanggal_mulai)->format('Y-m-d') !== \Carbon\Carbon::parse($prestasi->tanggal_selesai)->format('Y-m-d'))
                        sampai dengan {{ \Carbon\Carbon::parse($prestasi->tanggal_selesai)->translatedFormat('d F Y') }}
                    @endif
                </td>
            </tr>
        </table>

        <p class="mt-4 indent">Institusi Sekolah Tinggi Ilmu Hukum (STIH) Adhyaksa menyampaikan apresiasi yang setinggi-tingginya. Semoga prestasi ini menjadi motivasi untuk terus berkarya dan memberikan kontribusi terbaik bagi bangsa dan negara.</p>

        <p class="mt-2 indent">Demikian piagam penghargaan ini diberikan untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>
@endsection
