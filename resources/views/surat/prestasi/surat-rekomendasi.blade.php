@extends('surat.prestasi.layout')

@section('surat-title', 'SURAT REKOMENDASI')

@section('letter-content')
    <div class="text-justify">
        <p class="indent">Ketua Sekolah Tinggi Ilmu Hukum (STIH) Adhyaksa dengan ini memberikan rekomendasi kepada:</p>

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
        </table>

        <p class="mt-4">Untuk mengikuti/berpartisipasi dalam kegiatan:</p>

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
        </table>

        <p class="mt-4 indent">Berdasarkan rekam jejak akademik dan prestasi yang bersangkutan, kami menilai bahwa yang bersangkutan memiliki kompetensi dan dedikasi yang baik untuk mengikuti kegiatan tersebut. Kami memberikan dukungan penuh atas partisipasi yang bersangkutan.</p>

        <p class="mt-4 indent">Demikian surat rekomendasi ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>
@endsection
