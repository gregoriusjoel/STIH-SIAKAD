@extends('layouts.mahasiswa')

@section('title', 'Cetak KRS')
@section('page-title', 'Cetak / Download KRS')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold">Kartu Rencana Studi</h2>
            <p class="text-sm text-gray-600">{{ $semesterAktif->nama_semester ?? '-' }} — {{ $semesterAktif->tahun_ajaran ?? '-' }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm">Nama: <strong>{{ $mahasiswa->user->name }}</strong></p>
            <p class="text-sm">NPM: <strong>{{ $mahasiswa->npm }}</strong></p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2 text-left">No</th>
                    <th class="border px-4 py-2 text-left">Kode MK</th>
                    <th class="border px-4 py-2 text-left">Mata Kuliah</th>
                    <th class="border px-4 py-2 text-center">SKS</th>
                    <th class="border px-4 py-2">Kelas</th>
                    <th class="border px-4 py-2">Dosen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($existingKrs as $i => $k)
                <tr>
                    <td class="border px-4 py-2">{{ $i + 1 }}</td>
                    <td class="border px-4 py-2">{{ $k->kelasMataKuliah->mataKuliah->kode_mk ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ $k->kelasMataKuliah->mataKuliah->nama_mk ?? '-' }}</td>
                    <td class="border px-4 py-2 text-center">{{ $k->kelasMataKuliah->mataKuliah->sks ?? 0 }}</td>
                    <td class="border px-4 py-2">{{ $k->kelasMataKuliah->nama_kelas ?? $k->kelasMataKuliah->kode_kelas }}</td>
                    <td class="border px-4 py-2">{{ $k->kelasMataKuliah->dosen->user->name ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-between">
        <button onclick="window.print()" class="px-6 py-2 bg-blue-600 text-white rounded-lg">Cetak / Print</button>
        <a href="{{ route('mahasiswa.krs.index') }}" class="px-6 py-2 bg-gray-200 rounded-lg">Kembali</a>
    </div>
</div>
@endsection
