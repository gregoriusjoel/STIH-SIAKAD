@extends('layouts.mahasiswa')

@section('title', 'Konfirmasi KRS')
@section('page-title', 'Konfirmasi Pengisian KRS')

@section('content')
<div class="min-h-screen flex items-start pt-12">
    <div class="w-full max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-lg p-8 text-center">
            <h2 class="text-2xl font-bold mb-4">Konfirmasi Pengisian KRS</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start mb-6">
                <div class="col-span-1 bg-white border rounded-lg shadow-sm p-4 text-center">
                    <div class="w-24 h-24 mx-auto mb-3 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
                        @if($mahasiswa->foto)
                            <img src="{{ asset('storage/' . $mahasiswa->foto) }}" alt="foto" class="w-full h-full object-cover">
                        @else
                            <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8V22h19.2v-2.8c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                        @endif
                    </div>
                    <div class="text-sm text-gray-500">Nama</div>
                    <div class="font-semibold">{{ $mahasiswa->user->name ?? '-' }}</div>
                    <div class="text-xs text-gray-500 mt-2">NPM</div>
                    <div class="text-sm">{{ $mahasiswa->npm ?? '-' }}</div>
                </div>

                <div class="col-span-2 bg-white border rounded-lg shadow-sm p-4">
                    <h3 class="text-sm text-gray-500">Pengisian KRS {{ $semesterAktif->tahun_ajaran ?? '' }}</h3>
                    <h2 class="text-lg font-semibold">{{ $semesterAktif->nama_semester ?? '-' }} / {{ $semesterAktif->tahun_ajaran ?? '-' }}</h2>

                    <div class="mt-4 p-3 bg-gray-50 rounded">
                        <p class="text-sm text-gray-700">Terdapat <strong>{{ $availableCount }}</strong> kelas tersedia untuk semester ini.</p>
                        <p class="text-sm text-gray-600 mt-2">Silakan klik <strong>Isi KRS Sekarang</strong> untuk memulai pengisian. Setelah menyelesaikan pengisian dan mengajukan KRS, Anda dapat mengunduh/cetak KRS untuk semester terkait.</p>
                    </div>

                    <div class="mt-4 flex gap-3">
                        <a href="{{ route('mahasiswa.krs.index', ['start' => 1]) }}" class="px-5 py-2 bg-indigo-600 text-white rounded-lg">Isi KRS Sekarang</a>
                        <a href="{{ route('mahasiswa.dashboard') }}" class="px-5 py-2 bg-gray-200 rounded-lg">Kembali ke Dashboard</a>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-left">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Download KRS Semester Lalu</h4>
                <div class="bg-white border rounded-lg p-3">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs text-gray-500"><th>Semester</th><th>Tahun Ajaran</th><th class="text-center">Aksi</th></tr>
                        </thead>
                        <tbody>
                            @foreach($semesterList as $s)
                            <tr class="border-t">
                                <td class="py-2">{{ $s->nama_semester }}</td>
                                <td class="py-2">{{ $s->tahun_ajaran }}</td>
                                <td class="py-2 text-center">
                                    @if(in_array($s->id, $downloadable))
                                        <a href="{{ route('mahasiswa.krs.print') }}?semester_id={{ $s->id }}" class="inline-block px-3 py-1 bg-yellow-400 text-white rounded">Download/Cetak KRS</a>
                                    @else
                                        <button class="inline-block px-3 py-1 bg-gray-200 text-gray-600 rounded cursor-not-allowed" disabled>Download/Cetak KRS</button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
