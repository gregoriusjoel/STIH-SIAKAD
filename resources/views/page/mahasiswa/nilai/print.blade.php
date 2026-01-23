@extends('layouts.mahasiswa')

@section('title', 'Cetak Rangkuman Nilai')
@section('page-title', 'Cetak Rangkuman Nilai')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 bg-white p-6 rounded-lg">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold">Rangkuman Nilai - {{ $mahasiswa->nama ?? $mahasiswa->user->name }}</h2>
            <p class="text-sm text-gray-600">NPM: {{ $mahasiswa->npm }}</p>
        </div>
        <div>
            <button onclick="window.print()" class="px-4 py-2 bg-maroon text-white rounded-lg">Cetak</button>
        </div>
    </div>

    @foreach($nilaiPerSemester as $semesterNama => $nilaiList)
        <h3 class="text-lg font-semibold mt-4">{{ $semesterNama }}</h3>
        <div class="overflow-x-auto mt-2">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr>
                        <th class="border px-3 py-2 text-left">No</th>
                        <th class="border px-3 py-2 text-left">Kode MK</th>
                        <th class="border px-3 py-2 text-left">Mata Kuliah</th>
                        <th class="border px-3 py-2 text-center">SKS</th>
                        <th class="border px-3 py-2 text-center">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($nilaiList as $index => $krs)
                        @php
                            $mataKuliah = $krs->kelasMataKuliah->mataKuliah ?? null;
                            $nilai = $krs->nilai;
                            $nilaiAngka = $nilai->nilai ?? 0;
                        @endphp
                        <tr>
                            <td class="border px-3 py-2">{{ $index + 1 }}</td>
                            <td class="border px-3 py-2">{{ $mataKuliah->kode_mk ?? '-' }}</td>
                            <td class="border px-3 py-2">{{ $mataKuliah->nama_mk ?? '-' }}</td>
                            <td class="border px-3 py-2 text-center">{{ $mataKuliah->sks ?? 0 }}</td>
                            <td class="border px-3 py-2 text-center">{{ $nilaiAngka }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</div>
@endsection
