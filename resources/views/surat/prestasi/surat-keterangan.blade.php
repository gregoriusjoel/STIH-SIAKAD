@extends('surat.prestasi.layout')

@section('surat-perihal', 'Surat Keterangan Prestasi')

@section('letter-content')
    <div class="text-justify">
        <p class="indent">Yang bertanda tangan di bawah ini, {{ $penandatangan_jabatan ?? 'Ketua' }} Sekolah Tinggi Ilmu Hukum (STIH) Adhyaksa, dengan ini menerangkan bahwa:</p>

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

        <p>Adalah benar telah meraih prestasi/berpartisipasi aktif dalam kegiatan:</p>

        <table class="data-table">
            <tr>
                <td class="label-col">Nama Kegiatan</td>
                <td class="sep-col">:</td>
                <td class="value-col">{{ $prestasi->nama_kegiatan }}</td>
            </tr>
            <tr>
                <td class="label-col">Capaian/Prestasi</td>
                <td class="sep-col">:</td>
                <td class="value-col">{{ $prestasi->jenis_prestasi }}</td>
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
        </table>

        <p class="mt-4 indent">Surat keterangan ini diberikan sebagai bentuk apresiasi dan pengakuan atas dedikasi yang bersangkutan dalam mengharumkan nama institusi STIH Adhyaksa.</p>

        <p class="mt-2 indent">Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>
@endsection
