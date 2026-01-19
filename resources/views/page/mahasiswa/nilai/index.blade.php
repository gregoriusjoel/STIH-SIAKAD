@extends('layouts.mahasiswa')

@section('title', 'Akademik - Nilai')
@section('page-title', 'Nilai Akademik')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- IPK Card --}}
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-trophy text-2xl"></i>
                </div>
                <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">Overall</span>
            </div>
            <h3 class="text-3xl font-bold mb-1">{{ number_format($ipk, 2) }}</h3>
            <p class="text-blue-100 text-sm">Indeks Prestasi Kumulatif</p>
        </div>

        {{-- Total SKS Card --}}
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book text-2xl"></i>
                </div>
                <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">Credits</span>
            </div>
            <h3 class="text-3xl font-bold mb-1">{{ $totalSks }}</h3>
            <p class="text-green-100 text-sm">Total SKS Diambil</p>
        </div>

        {{-- Total MK Card --}}
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-2xl"></i>
                </div>
                <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">Courses</span>
            </div>
            <h3 class="text-3xl font-bold mb-1">{{ $nilaiPerSemester->sum(function($items) { return $items->count(); }) }}</h3>
            <p class="text-purple-100 text-sm">Mata Kuliah Selesai</p>
        </div>
    </div>

    {{-- Nilai per Semester --}}
    @forelse($nilaiPerSemester as $semesterNama => $nilaiList)
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        {{-- Semester Header --}}
        <div class="bg-gradient-to-r from-maroon to-red-800 text-white px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold mb-1">{{ $semesterNama }}</h3>
                    <p class="text-sm opacity-90">
                        Total SKS: {{ $ipsPerSemester[$semesterNama]['sks'] ?? 0 }} | 
                        IPS: {{ number_format($ipsPerSemester[$semesterNama]['ips'] ?? 0, 2) }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-bold">{{ number_format($ipsPerSemester[$semesterNama]['ips'] ?? 0, 2) }}</p>
                    <p class="text-sm opacity-90">IPS</p>
                </div>
            </div>
        </div>

        {{-- Nilai Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Kode MK</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Mata Kuliah</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase">SKS</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase">Nilai</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase">Grade</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase">Bobot</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($nilaiList as $index => $krs)
                    @php
                        $mataKuliah = $krs->kelasMataKuliah->mataKuliah ?? null;
                        $nilai = $krs->nilai;
                        $nilaiAngka = $nilai->nilai ?? 0;
                        $grade = $this->getGrade($nilaiAngka);
                        $bobot = $this->getBobot($nilaiAngka);
                        
                        // Grade colors
                        $gradeColors = [
                            'A' => 'bg-green-100 text-green-800',
                            'A-' => 'bg-green-100 text-green-700',
                            'B+' => 'bg-blue-100 text-blue-800',
                            'B' => 'bg-blue-100 text-blue-700',
                            'B-' => 'bg-blue-100 text-blue-600',
                            'C+' => 'bg-yellow-100 text-yellow-800',
                            'C' => 'bg-yellow-100 text-yellow-700',
                            'C-' => 'bg-yellow-100 text-yellow-600',
                            'D' => 'bg-orange-100 text-orange-700',
                            'E' => 'bg-red-100 text-red-700',
                        ];
                        $gradeColor = $gradeColors[$grade] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ $mataKuliah->kode_mk ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $mataKuliah->nama_mk ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-center font-semibold text-gray-800">{{ $mataKuliah->sks ?? 0 }}</td>
                        <td class="px-6 py-4 text-sm text-center font-bold text-gray-900">{{ $nilaiAngka }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-lg font-bold text-sm {{ $gradeColor }}">
                                {{ $grade }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-center font-semibold text-gray-800">{{ number_format($bobot, 1) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Semester Summary --}}
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Total Mata Kuliah</p>
                        <p class="text-lg font-bold text-gray-800">{{ $nilaiList->count() }} MK</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 mb-1">Total SKS</p>
                        <p class="text-lg font-bold text-gray-800">{{ $ipsPerSemester[$semesterNama]['sks'] ?? 0 }} SKS</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-600 mb-1">Indeks Prestasi Semester</p>
                    <p class="text-2xl font-bold text-maroon">{{ number_format($ipsPerSemester[$semesterNama]['ips'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl shadow-lg p-12">
        <div class="text-center">
            <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Nilai</h3>
            <p class="text-gray-500">Nilai akademik Anda akan muncul di sini setelah dosen memasukkan nilai.</p>
        </div>
    </div>
    @endforelse

</div>

@php
    // Helper functions - duplicate dari controller untuk view
    function getGrade($nilai) {
        if ($nilai >= 85) return 'A';
        if ($nilai >= 80) return 'A-';
        if ($nilai >= 75) return 'B+';
        if ($nilai >= 70) return 'B';
        if ($nilai >= 65) return 'B-';
        if ($nilai >= 60) return 'C+';
        if ($nilai >= 55) return 'C';
        if ($nilai >= 50) return 'C-';
        if ($nilai >= 45) return 'D';
        return 'E';
    }
    
    function getBobot($nilai) {
        if ($nilai >= 85) return 4.0;
        if ($nilai >= 80) return 3.7;
        if ($nilai >= 75) return 3.3;
        if ($nilai >= 70) return 3.0;
        if ($nilai >= 65) return 2.7;
        if ($nilai >= 60) return 2.3;
        if ($nilai >= 55) return 2.0;
        if ($nilai >= 50) return 1.7;
        if ($nilai >= 45) return 1.3;
        return 0;
    }
@endphp
@endsection
