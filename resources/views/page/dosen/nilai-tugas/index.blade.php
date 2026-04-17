@extends('layouts.app')

@section('title', 'Nilai Tugas — ' . ($kelas->mataKuliah->nama_mk ?? $kelas->mataKuliah->nama ?? 'Kelas'))

@section('content')
<div class="space-y-6">

    {{-- ── Header Kelas ──────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400">Input Nilai Tugas</p>
                <h1 class="text-2xl font-extrabold text-gray-900 mt-1">
                    {{ $kelas->mataKuliah->nama_mk ?? $kelas->mataKuliah->nama ?? '-' }}
                    <span class="ml-2 text-lg font-semibold text-maroon">— Kelas {{ $kelas->section }}</span>
                </h1>
                <div class="flex flex-wrap gap-3 mt-2 text-sm text-gray-500">
                    <span><i class="fas fa-tag mr-1"></i>{{ $kelas->mataKuliah->kode_mk ?? '-' }}</span>
                    <span><i class="fas fa-user-tie mr-1"></i>{{ $kelas->dosen?->nama ?? 'Dosen' }}</span>
                    <span><i class="fas fa-graduation-cap mr-1"></i>{{ $kelas->mataKuliah->sks ?? '-' }} SKS</span>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('dosen.nilai-tugas.rekap', $kelas->id) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-maroon text-white rounded-xl text-sm font-medium hover:bg-red-900 transition">
                    <i class="fas fa-chart-bar"></i> Rekap & Export
                </a>
                <a href="{{ route('dosen.kelas.detail', $kelas->id) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false, 4000)"
             class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl text-sm font-medium">
            <i class="fas fa-check-circle text-green-500"></i>
            {{ session('success') }}
            <button @click="show=false" class="ml-auto text-green-600 hover:text-green-900"><i class="fas fa-times"></i></button>
        </div>
    @endif

    {{-- ── Daftar Tugas Grouped by Pertemuan ──────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-gray-800">Daftar Tugas per Pertemuan</h2>
            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full">
                Total: {{ $tugasList->count() }} tugas
            </span>
        </div>

        @if($tugasList->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                <i class="fas fa-clipboard-list text-5xl mb-4 opacity-30"></i>
                <p class="text-lg font-semibold">Belum ada tugas</p>
                <p class="text-sm">Tugas akan otomatis muncul setelah dibuat di halaman Pertemuan.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($tugasGrouped as $pertemuan => $tugasGroup)
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 flex items-center justify-between">
                        <h3 class="font-bold text-gray-800">
                            <i class="fas fa-calendar-alt text-maroon mr-2"></i>
                            Pertemuan {{ $pertemuan }}
                        </h3>
                        <span class="text-xs text-gray-500 font-medium">{{ $tugasGroup->count() }} tugas</span>
                    </div>
                    <div class="p-4 space-y-3">
                        @foreach($tugasGroup as $tugas)
                        <div class="flex items-center justify-between gap-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900">{{ $tugas->title }}</h4>
                                <div class="flex items-center gap-4 mt-1 text-xs text-gray-500">
                                    <span><i class="fas fa-star mr-1"></i>Max: {{ $tugas->max_score }}</span>
                                    @php 
                                        $graded = $tugas->submissions()->whereNotNull('score')->count();
                                        $total = \App\Models\Krs::where('kelas_id', $kelas->id)->whereIn('status', ['approved', 'disetujui', 'KRS sudah di isi'])->count();
                                    @endphp
                                    <span><i class="fas fa-check-circle mr-1"></i>Dinilai: {{ $graded }}/{{ $total }}</span>
                                    @if($tugas->deadline)
                                        <span><i class="fas fa-clock mr-1"></i>Deadline: {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y') }}</span>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('dosen.nilai-tugas.input', [$kelas->id, $tugas->id]) }}"
                               class="px-4 py-2 bg-maroon text-white rounded-lg text-sm font-bold hover:bg-red-900 transition whitespace-nowrap">
                                <i class="fas fa-pencil-alt mr-1"></i> Input Nilai
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>


</div>
@endsection
