@extends('surat.prestasi.layout')

@section('surat-perihal', 'Surat Tugas')

@section('letter-content')
    <div class="text-justify">
        <p class="indent">Dalam rangka mendukung kegiatan {{ $prestasi->nama_kegiatan }} tingkat {{ $prestasi->tingkat_label }} yang diselenggarakan oleh {{ $prestasi->penyelenggara }}, dengan ini kami memberikan tugas kepada {{ $prestasi->pengaju_type === 'App\Models\Mahasiswa' ? 'mahasiswa' : 'dosen' }} Sekolah Tinggi Ilmu Hukum Adhyaksa yang akan melaksanakan kegiatan tersebut:</p>

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
            <tr>
                <td class="label-col">Jabatan/Status</td>
                <td class="sep-col">:</td>
                <td class="value-col">{{ $prestasi->pengaju_type === 'App\Models\Mahasiswa' ? 'Mahasiswa' : 'Dosen' }}</td>
            </tr>
        </table>

        <p>Untuk melaksanakan kegiatan sebagai berikut:</p>

        <table class="data-table">
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
                <td class="label-col">Waktu Pelaksanaan</td>
                <td class="sep-col">:</td>
                <td class="value-col">
                    {{ \Carbon\Carbon::parse($prestasi->tanggal_mulai)->translatedFormat('d F Y') }}
                    @if($prestasi->tanggal_selesai && \Carbon\Carbon::parse($prestasi->tanggal_mulai)->format('Y-m-d') !== \Carbon\Carbon::parse($prestasi->tanggal_selesai)->format('Y-m-d'))
                        sampai dengan {{ \Carbon\Carbon::parse($prestasi->tanggal_selesai)->translatedFormat('d F Y') }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label-col">Tempat/Lokasi</td>
                <td class="sep-col">:</td>
                <td class="value-col">{{ $prestasi->tempat_kegiatan }}</td>
            </tr>
        </table>

        <p class="mt-4 indent">Demikian surat tugas ini kami sampaikan untuk dapat dilaksanakan dengan penuh tanggung jawab. Setelah selesai melaksanakan tugas agar memberikan laporan kepada pimpinan.</p>

        <p class="mt-2 indent">Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.</p>
    </div>
@endsection
