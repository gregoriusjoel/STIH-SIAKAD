@extends('surat.prestasi.layout')

@section('surat-title', 'SURAT TUGAS')

@section('letter-content')
    <div class="text-justify">
        <p class="indent">Ketua Sekolah Tinggi Ilmu Hukum (STIH) Adhyaksa dengan ini memberikan tugas kepada:</p>

        <table class="data-table">
            <tr>
                <td class="label-col">Nama</td>
                <td class="sep-col">:</td>
                <td class="font-bold">{{ $user->name }}</td>
            </tr>
            <tr>
                <td class="label-col">NIM/NIDN</td>
                <td class="sep-col">:</td>
                <td>{{ $pengaju->nim ?? $pengaju->nidn }}</td>
            </tr>
            <tr>
                <td class="label-col">Program Studi</td>
                <td class="sep-col">:</td>
                <td>{{ $pengaju->prodi ?? 'Ilmu Hukum' }}</td>
            </tr>
            <tr>
                <td class="label-col">Jabatan/Status</td>
                <td class="sep-col">:</td>
                <td>{{ $prestasi->pengaju_type === 'App\Models\Mahasiswa' ? 'Mahasiswa' : 'Dosen' }}</td>
            </tr>
        </table>

        <p class="mt-4">Untuk melaksanakan kegiatan sebagai berikut:</p>

        <table class="data-table">
            <tr>
                <td class="label-col">Nama Kegiatan</td>
                <td class="sep-col">:</td>
                <td class="font-bold">{{ $prestasi->nama_kegiatan }}</td>
            </tr>
            <tr>
                <td class="label-col">Tingkat</td>
                <td class="sep-col">:</td>
                <td>{{ $prestasi->tingkat_label }}</td>
            </tr>
            <tr>
                <td class="label-col">Penyelenggara</td>
                <td class="sep-col">:</td>
                <td>{{ $prestasi->penyelenggara }}</td>
            </tr>
            <tr>
                <td class="label-col">Waktu Pelaksanaan</td>
                <td class="sep-col">:</td>
                <td>{{ \Carbon\Carbon::parse($prestasi->tanggal_mulai)->translatedFormat('d F Y') }} 
                    @if($prestasi->tanggal_selesai)
                        s.d. {{ \Carbon\Carbon::parse($prestasi->tanggal_selesai)->translatedFormat('d F Y') }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label-col">Tempat/Lokasi</td>
                <td class="sep-col">:</td>
                <td>{{ $prestasi->tempat_kegiatan }}</td>
            </tr>
        </table>

        <p class="mt-4 indent">Demikian surat tugas ini diberikan untuk dilaksanakan dengan penuh tanggung jawab dan setelah selesai melaksanakan tugas agar memberikan laporan kepada pimpinan.</p>
    </div>
@endsection
