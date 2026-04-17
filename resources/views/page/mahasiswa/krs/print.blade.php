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
            <p class="text-sm">NIM: <strong>{{ $mahasiswa->nim }}</strong></p>
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
                @php
                    $mk = $k->kelasMataKuliah?->mataKuliah ?? $k->kelas?->mataKuliah ?? $k->mataKuliah;
                    $kelasLabel = $k->kelasMataKuliah?->nama_kelas
                        ?? $k->kelasMataKuliah?->kode_kelas
                        ?? $k->kelas?->section
                        ?? '-';
                    $dosenName = $k->kelasMataKuliah?->dosen?->user?->name
                        ?? $k->kelas?->dosen?->user?->name
                        ?? '-';
                @endphp
                <tr>
                    <td class="border px-4 py-2">{{ $i + 1 }}</td>
                    <td class="border px-4 py-2">{{ $mk?->kode_mk ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ $mk?->nama_mk ?? '-' }}</td>
                    <td class="border px-4 py-2 text-center">{{ $mk?->sks ?? 0 }}</td>
                    <td class="border px-4 py-2">{{ $kelasLabel }}</td>
                    <td class="border px-4 py-2">{{ $dosenName }}</td>
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
