@extends('layouts.mahasiswa')

@section('title', 'Jadwal Kelas')
@section('page-title', 'Jadwal Kelas')

@section('content')
    <div class="space-y-6">

        {{-- Header Card --}}
        <div class="bg-white dark:bg-[#1a1d2e] rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-1">Jadwal Kelas</h2>
                    <p class="text-gray-600 dark:text-slate-400">Semester: <span
                            class="font-semibold">{{ Auth::user()->mahasiswa->getCurrentSemester() ?? 'Tidak ada semester aktif' }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="window.print()"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                        <i class="fas fa-print"></i>
                        <span>Print</span>
                    </button>
                    <button onclick="downloadICS()"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
                        <i class="fas fa-download"></i>
                        <span>Export</span>
                    </button>
                </div>
            </div>
        </div>

        @if($krsData->isEmpty())
            <div class="bg-white dark:bg-[#1a1d2e] rounded-xl shadow-lg p-12">
                <div class="text-center">
                    <i class="fas fa-calendar-times text-6xl text-gray-300 dark:text-slate-700 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-700 dark:text-white mb-2">Tidak Ada Jadwal</h3>
                    <p class="text-gray-500 dark:text-slate-500 mb-4">KRS Anda belum disetujui atau belum ada mata kuliah yang diambil.</p>
                    <a href="{{ route('mahasiswa.krs.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-maroon text-white rounded-lg hover:bg-maroon-hover transition">
                        <i class="fas fa-file-alt"></i>
                        Lihat KRS
                    </a>
                </div>
            </div>
        @else
            {{-- Jadwal per Hari (Grid Layout 3 Columns) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($jadwalPerHari as $hari => $jadwals)
                    <div class="bg-white dark:bg-[#1a1d2e] rounded-xl shadow-lg overflow-hidden h-full border border-gray-100 dark:border-slate-800 flex flex-col">
                        {{-- Day Header --}}
                        <div class="bg-gradient-to-r from-maroon to-red-800 text-white px-5 py-3 flex-shrink-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-calendar-day text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold">{{ $hari }}</h3>
                                        <p class="text-xs opacity-90">{{ count($jadwals) }} Mata Kuliah</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Schedule Cards (Vertical Scroll) --}}
                        @if(count($jadwals) > 0)
                            <div class="p-4 space-y-3 bg-gray-50 dark:bg-slate-900 flex-grow max-h-[400px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-slate-600 scrollbar-track-gray-100 dark:scrollbar-track-slate-900">
                                @foreach($jadwals as $jadwal)
                                    <div class="bg-white dark:bg-[#1a1d2e] border border-gray-200 dark:border-slate-700 rounded-lg p-4 shadow-sm hover:shadow-md transition-all hover:border-maroon group">
                                        {{-- Time Badge --}}
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="bg-maroon/10 rounded-md px-2 py-1 flex items-center gap-1.5">
                                                <i class="fas fa-clock text-maroon text-xs"></i>
                                                <span class="font-bold text-maroon text-xs">
                                                    {{ substr($jadwal['jam_mulai'], 0, 5) }} - {{ substr($jadwal['jam_selesai'], 0, 5) }}
                                                </span>
                                            </div>
                                            <div class="text-xs text-gray-500 flex items-center gap-1">
                                                <i class="fas fa-door-open"></i> {{ $jadwal['ruangan'] }}
                                            </div>
                                        </div>

                                        {{-- Course Title --}}
                                        <h4 class="font-bold text-gray-800 text-sm mb-1 leading-snug group-hover:text-maroon transition-colors">
                                            {{ $jadwal['mata_kuliah'] }}
                                        </h4>
                                        
                                        {{-- Dosen & SKS --}}
                                        <div class="text-xs text-gray-600 mb-3 space-y-1">
                                            <p class="flex items-center gap-1.5">
                                                <i class="fas fa-chalkboard-teacher text-maroon/70"></i>
                                                <span class="truncate">{{ $jadwal['dosen'] }}</span>
                                            </p>
                                            <div class="flex items-center gap-2 text-gray-400">
                                                <span>{{ $jadwal['kode_mk'] }}</span>
                                                <span>•</span>
                                                <span>{{ $jadwal['sks'] }} SKS</span>
                                            </div>
                                        </div>

                                        {{-- Footer --}}
                                        <div class="pt-2 border-t border-gray-100 dark:border-slate-700 flex justify-between items-center">
                                            <span class="text-[10px] text-gray-400 dark:text-slate-500 font-medium uppercase tracking-wider">Kelas</span>
                                            <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded text-xs font-bold border border-blue-100">
                                                {{ $jadwal['kelas'] }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-8 text-center text-gray-400 flex flex-col items-center justify-center flex-grow min-h-[200px] bg-gray-50 dark:bg-slate-900">
                                <i class="fas fa-mug-hot text-3xl mb-2 opacity-50"></i>
                                <p class="text-sm">Libur</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Summary Card --}}
            <div class="bg-white dark:bg-[#1a1d2e] rounded-xl shadow-lg p-6 mt-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Ringkasan Jadwal</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-slate-400 mb-1">Total MK</p>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $krsData->count() }}</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-slate-400 mb-1">Total SKS</p>
                        <p class="text-2xl font-bold text-green-600">
                            {{ $krsData->sum(function ($krs) {
                                return $krs->kelas->mataKuliah->sks ?? 0; 
                            }) }}</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-slate-400 mb-1">Hari Kuliah</p>
                        <p class="text-2xl font-bold text-purple-600">
                            {{ count(array_filter($jadwalPerHari, function ($j) {
                return count($j) > 0; })) }}</p>
                    </div>
                    <div class="text-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Semester</p>
                        <p class="text-2xl font-bold text-orange-600">{{ Auth::user()->mahasiswa->semester ?? '-' }}</p>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection

@push('scripts')
    <script>
        function downloadICS() {
            showInfo('Informasi', 'Fitur export ke kalender akan segera tersedia!');
        }
    </script>
@endpush