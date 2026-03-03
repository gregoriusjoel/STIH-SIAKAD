@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<!-- Header Section -->
<div class="mb-6">
    <h2 class="text-2xl font-bold text-maroon dark:text-red-500">Dashboard Admin</h2>
    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Selamat datang di Sistem Informasi Akademik STIH</p>
</div>

<!-- Statistik Cards - 3 Column Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Group 1: Mahasiswa & Orang Tua -->
    <div class="bg-[#eef2ff] dark:bg-blue-900/20 rounded-xl p-4 border border-blue-100 dark:border-blue-800/50 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-full -mr-16 -mt-16"></div>
        <div class="flex items-center gap-3 mb-4 relative z-10">
            <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center shadow-sm">
                <i class="fas fa-hourglass-half text-white text-sm"></i>
            </div>
            <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200 whitespace-nowrap">Data Mahasiswa</h3>
        </div>
        
        <div class="grid grid-cols-2 gap-3 relative z-10">
            <!-- Mahasiswa Card -->
            <a href="{{ route('admin.mahasiswa.index') }}" class="group block">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow h-full flex flex-col justify-between border border-transparent hover:border-blue-200 dark:hover:border-blue-700">
                    <div>
                        <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center mb-3">
                            <i class="fas fa-user-graduate text-white text-sm"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-[11px] font-semibold mb-1">Mahasiswa</p>
                        <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $total_mahasiswa }}</h4>
                    </div>
                    <p class="text-blue-600 dark:text-blue-400 text-xs font-bold flex items-center">
                        Detail <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                    </p>
                </div>
            </a>

            <!-- Orang Tua Card -->
            <a href="{{ route('admin.mahasiswa.index') }}" class="group block">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow h-full flex flex-col justify-between border border-transparent hover:border-orange-200 dark:hover:border-orange-700">
                    <div>
                        <div class="w-8 h-8 rounded-lg bg-[#f97316] flex items-center justify-center mb-3">
                            <i class="fas fa-users text-white text-sm"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-[11px] font-semibold mb-1">Orang Tua</p>
                        <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $total_parent }}</h4>
                    </div>
                    <p class="text-[#f97316] dark:text-orange-400 text-xs font-bold flex items-center">
                        Detail <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                    </p>
                </div>
            </a>
        </div>
    </div>

    <!-- Group 2: Data Akademik -->
    <div class="bg-[#ecfdf5] dark:bg-green-900/20 rounded-xl p-4 border border-green-100 dark:border-green-800/50 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-green-500/5 rounded-full -mr-16 -mt-16"></div>
        <div class="flex items-center gap-3 mb-4 relative z-10">
            <div class="w-8 h-8 rounded-lg bg-[#10b981] flex items-center justify-center shadow-sm">
                <i class="fas fa-chalkboard-teacher text-white text-sm"></i>
            </div>
            <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200 whitespace-nowrap">Data Akademik</h3>
        </div>
        
        <div class="grid grid-cols-2 gap-3 relative z-10">
            <!-- Dosen Card -->
            <a href="{{ route('admin.dosen.index') }}" class="group block">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow h-full flex flex-col justify-between border border-transparent hover:border-green-200 dark:hover:border-green-700">
                    <div>
                        <div class="w-8 h-8 rounded-lg bg-[#10b981] flex items-center justify-center mb-3">
                            <i class="fas fa-chalkboard-teacher text-white text-sm"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-[11px] font-semibold mb-1">Dosen</p>
                        <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $total_dosen }}</h4>
                    </div>
                    <p class="text-[#10b981] dark:text-green-400 text-xs font-bold flex items-center">
                        Detail <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                    </p>
                </div>
            </a>

            <!-- Mata Kuliah Card -->
            <a href="{{ route('admin.mata-kuliah.index') }}" class="group block">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow h-full flex flex-col justify-between border border-transparent hover:border-purple-200 dark:hover:border-purple-700">
                    <div>
                        <div class="w-8 h-8 rounded-lg bg-[#a855f7] flex items-center justify-center mb-3">
                            <i class="fas fa-book text-white text-sm"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-[11px] font-semibold mb-1">Mata Kuliah</p>
                        <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $total_mata_kuliah }}</h4>
                    </div>
                    <p class="text-[#a855f7] dark:text-purple-400 text-xs font-bold flex items-center">
                        Detail <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                    </p>
                </div>
            </a>
        </div>
    </div>

    <!-- Group 3: Manajemen Kelas -->
    <div class="bg-[#f3e8ff] dark:bg-purple-900/20 rounded-xl p-4 border border-purple-100 dark:border-purple-800/50 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500/5 rounded-full -mr-16 -mt-16"></div>
        <div class="flex items-center gap-3 mb-4 relative z-10">
            <div class="w-8 h-8 rounded-lg bg-[#6366f1] flex items-center justify-center shadow-sm">
                <i class="fas fa-door-open text-white text-sm"></i>
            </div>
            <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200 whitespace-nowrap">Manajemen Kelas</h3>
        </div>
        
        <div class="grid grid-cols-2 gap-3 relative z-10">
            <!-- Kelas Card -->
            <a href="{{ route('admin.mata-kuliah.index') }}" class="group block">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow h-full flex flex-col justify-between border border-transparent hover:border-indigo-200 dark:hover:border-indigo-700">
                    <div>
                        <div class="w-8 h-8 rounded-lg bg-[#6366f1] flex items-center justify-center mb-3">
                            <i class="fas fa-door-open text-white text-sm"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-[11px] font-semibold mb-1">Kelas</p>
                        <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $total_kelas }}</h4>
                    </div>
                    <p class="text-[#6366f1] dark:text-indigo-400 text-xs font-bold flex items-center">
                        Detail <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                    </p>
                </div>
            </a>

            <!-- Jadwal Perkuliahan Card -->
            <a href="{{ route('admin.jadwal.index') }}" class="group block">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow h-full flex flex-col justify-between border border-transparent hover:border-red-200 dark:hover:border-red-700">
                    <div>
                        <div class="w-8 h-8 rounded-lg bg-[#ef4444] flex items-center justify-center mb-3">
                            <i class="fas fa-calendar-alt text-white text-sm"></i>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-[11px] font-semibold mb-1">Jadwal Perkuliahan</p>
                        <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $total_jadwal }}</h4>
                    </div>
                    <p class="text-[#ef4444] dark:text-red-500 text-xs font-bold flex items-center">
                        Detail <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                    </p>
                </div>
            </a>
        </div>
    </div>
</div>

{{-- Periode Akademik Aktif --}}
@if(!empty($active_periods))
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 mt-2 p-4">
    <div class="flex items-center gap-2 mb-3">
        <div class="w-6 h-6 rounded bg-green-600 flex items-center justify-center">
            <i class="fas fa-clock text-white text-xs"></i>
        </div>
        <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200">Periode Aktif Saat Ini</h3>
        @if($active_semester)
            <span class="text-xs text-gray-500 ml-auto">{{ $active_semester->display_label }}</span>
        @endif
    </div>
    <div class="flex flex-wrap gap-2">
        @foreach($active_periods as $period)
            <div class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border {{ $period['colors']['bg'] }} {{ $period['colors']['text'] }} {{ $period['colors']['border'] }}">
                <i class="{{ $period['icon'] }} text-sm"></i>
                <div>
                    <span class="text-xs font-bold">{{ $period['label'] }}</span>
                    @if($period['days_left'] > 0)
                        <span class="text-[10px] opacity-75 ml-1">({{ $period['days_left'] }} hari lagi)</span>
                    @else
                        <span class="text-[10px] opacity-75 ml-1">(hari terakhir)</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- Kalender Akademik - Full Width -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 mt-2">
    <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-red-50/30 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded bg-maroon dark:bg-red-900 flex items-center justify-center">
                <i class="fas fa-calendar-alt text-white text-xs"></i>
            </div>
            <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200">Kalender Akademik</h3>
        </div>
        <a href="#" class="inline-flex items-center px-3 py-1 bg-white dark:bg-gray-800 border border-maroon text-maroon dark:text-red-500 rounded-full text-xs font-semibold hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
            Lihat Semua
            <i class="fas fa-arrow-right ml-1.5 text-[10px]"></i>
        </a>
    </div>
    <div class="p-4 min-h-[120px] flex flex-col justify-center">
        @if($academic_events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                @foreach($academic_events as $event)
                    <div class="group bg-white dark:bg-gray-700 rounded-lg p-3 border border-gray-100 dark:border-gray-600 border-l-4 border-l-blue-500 hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 mt-0.5">
                                <div class="bg-blue-100 dark:bg-blue-900/40 rounded-md p-2">
                                    <i class="fas fa-calendar-check text-blue-600 dark:text-blue-400 text-sm"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-sm text-gray-800 dark:text-gray-100 mb-1 truncate">{{ $event->title ?? $event->name }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center mb-2">
                                    <i class="fas fa-clock mr-1.5 text-blue-500/70"></i>
                                    <span class="truncate">
                                        @if($event->start_date && $event->end_date)
                                            {{ \Carbon\Carbon::parse($event->start_date)->format('d M') }}
                                        @else
                                            {{ $event->date ? \Carbon\Carbon::parse($event->date)->format('d M Y') : 'TBA' }}
                                        @endif
                                    </span>
                                </p>
                                <span class="inline-block px-2.5 py-0.5 text-[10px] font-bold tracking-wide uppercase rounded-full bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-100 dark:border-blue-800">
                                    {{ $event->type_label }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-6 flex flex-col items-center justify-center h-full">
                <div class="bg-gray-100 dark:bg-gray-700 rounded-full w-10 h-10 flex items-center justify-center mb-3">
                    <i class="fas fa-calendar text-gray-300 dark:text-gray-500"></i>
                </div>
                <p class="text-gray-500 dark:text-gray-400 font-bold text-[13px] mt-1">Belum ada acara akademik</p>
                <p class="text-gray-400 dark:text-gray-500 text-[11px] mt-0.5">Acara akan ditampilkan di sini</p>
            </div>
        @endif
    </div>
</div>
@endsection
