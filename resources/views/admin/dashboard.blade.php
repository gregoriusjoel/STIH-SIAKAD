@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<!-- Header Section -->
<div class="mb-4">
    <h2 class="text-2xl font-bold bg-gradient-to-r from-maroon via-red-600 to-red-700 bg-clip-text text-transparent dark:from-red-400 dark:to-red-600">Dashboard Admin</h2>
    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Selamat datang di Sistem Informasi Akademik STIH</p>
</div>

<!-- Statistik Cards - 3 Column Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
    <!-- Group 1: Mahasiswa & Orang Tua -->
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-500/5 via-blue-400/5 to-indigo-500/5 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl shadow-md hover:shadow-lg transition-shadow p-4 border border-blue-200/50 dark:border-blue-800/50">
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 dark:bg-blue-500/10 rounded-full -mr-16 -mt-16"></div>
        <div class="relative">
            <div class="flex items-center mb-3">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-2 mr-2 shadow-sm">
                    <i class="fas fa-user-graduate text-white text-sm"></i>
                </div>
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200">Data Mahasiswa</h3>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <!-- Mahasiswa Card -->
                <a href="{{ route('admin.mahasiswa.index') }}" class="group block">
                    <div class="relative bg-white dark:bg-gray-700/60 rounded-lg p-3 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 border border-blue-100 dark:border-blue-900/30 hover:border-blue-400 dark:hover:border-blue-600">
                        <div class="flex items-start justify-between mb-2">
                            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-md p-2 shadow-sm">
                                <i class="fas fa-user-graduate text-base text-white"></i>
                            </div>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Mahasiswa</p>
                        <h4 class="text-2xl font-bold text-gray-800 dark:text-gray-100 my-1">{{ $total_mahasiswa }}</h4>
                        <p class="text-blue-600 dark:text-blue-400 text-sm font-semibold flex items-center">
                            Detail <i class="fas fa-arrow-right ml-1 text-xs group-hover:translate-x-0.5 transition-transform"></i>
                        </p>
                    </div>
                </a>

                <!-- Orang Tua Card -->
                <a href="{{ route('admin.mahasiswa.index') }}" class="group block">
                    <div class="relative bg-white dark:bg-gray-700/60 rounded-lg p-3 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 border border-orange-100 dark:border-orange-900/30 hover:border-orange-400 dark:hover:border-orange-600">
                        <div class="flex items-start justify-between mb-2">
                            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-md p-2 shadow-sm">
                                <i class="fas fa-users text-base text-white"></i>
                            </div>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Orang Tua</p>
                        <h4 class="text-2xl font-bold text-gray-800 dark:text-gray-100 my-1">{{ $total_parent }}</h4>
                        <p class="text-orange-600 dark:text-orange-400 text-sm font-semibold flex items-center">
                            Detail <i class="fas fa-arrow-right ml-1 text-xs group-hover:translate-x-0.5 transition-transform"></i>
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Group 2: Dosen & Mata Kuliah -->
    <div class="relative overflow-hidden bg-gradient-to-br from-green-500/5 via-green-400/5 to-emerald-500/5 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl shadow-md hover:shadow-lg transition-shadow p-4 border border-green-200/50 dark:border-green-800/50">
        <div class="absolute top-0 right-0 w-32 h-32 bg-green-500/5 dark:bg-green-500/10 rounded-full -mr-16 -mt-16"></div>
        <div class="relative">
            <div class="flex items-center mb-3">
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-2 mr-2 shadow-sm">
                    <i class="fas fa-chalkboard-teacher text-white text-sm"></i>
                </div>
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200">Data Akademik</h3>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <!-- Dosen Card -->
                <a href="{{ route('admin.dosen.index') }}" class="group block">
                    <div class="relative bg-white dark:bg-gray-700/60 rounded-lg p-3 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 border border-green-100 dark:border-green-900/30 hover:border-green-400 dark:hover:border-green-600">
                        <div class="flex items-start justify-between mb-2">
                            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-md p-2 shadow-sm">
                                <i class="fas fa-chalkboard-teacher text-base text-white"></i>
                            </div>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Dosen</p>
                        <h4 class="text-2xl font-bold text-gray-800 dark:text-gray-100 my-1">{{ $total_dosen }}</h4>
                        <p class="text-green-600 dark:text-green-400 text-sm font-semibold flex items-center">
                            Detail <i class="fas fa-arrow-right ml-1 text-xs group-hover:translate-x-0.5 transition-transform"></i>
                        </p>
                    </div>
                </a>

                <!-- Mata Kuliah Card -->
                <a href="{{ route('admin.mata-kuliah.index') }}" class="group block">
                    <div class="relative bg-white dark:bg-gray-700/60 rounded-lg p-3 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 border border-purple-100 dark:border-purple-900/30 hover:border-purple-400 dark:hover:border-purple-600">
                        <div class="flex items-start justify-between mb-2">
                            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-md p-2 shadow-sm">
                                <i class="fas fa-book text-base text-white"></i>
                            </div>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Mata Kuliah</p>
                        <h4 class="text-2xl font-bold text-gray-800 dark:text-gray-100 my-1">{{ $total_mata_kuliah }}</h4>
                        <p class="text-purple-600 dark:text-purple-400 text-sm font-semibold flex items-center">
                            Detail <i class="fas fa-arrow-right ml-1 text-xs group-hover:translate-x-0.5 transition-transform"></i>
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Group 3: Kelas & KRS Pending -->
    <div class="relative overflow-hidden bg-gradient-to-br from-purple-500/5 via-purple-400/5 to-pink-500/5 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl shadow-md hover:shadow-lg transition-shadow p-4 border border-purple-200/50 dark:border-purple-800/50">
        <div class="absolute top-0 right-0 w-32 h-32 bg-purple-500/5 dark:bg-purple-500/10 rounded-full -mr-16 -mt-16"></div>
        <div class="relative">
            <div class="flex items-center mb-3">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg p-2 mr-2 shadow-sm">
                    <i class="fas fa-door-open text-white text-sm"></i>
                </div>
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200">Manajemen Kelas</h3>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <!-- Kelas Card -->
                <a href="{{ route('admin.mata-kuliah.index') }}" class="group block">
                    <div class="relative bg-white dark:bg-gray-700/60 rounded-lg p-3 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 border border-indigo-100 dark:border-indigo-900/30 hover:border-indigo-400 dark:hover:border-indigo-600">
                        <div class="flex items-start justify-between mb-2">
                            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-md p-2 shadow-sm">
                                <i class="fas fa-door-open text-base text-white"></i>
                            </div>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Kelas</p>
                        <h4 class="text-2xl font-bold text-gray-800 dark:text-gray-100 my-1">{{ $total_kelas }}</h4>
                        <p class="text-indigo-600 dark:text-indigo-400 text-sm font-semibold flex items-center">
                            Detail <i class="fas fa-arrow-right ml-1 text-xs group-hover:translate-x-0.5 transition-transform"></i>
                        </p>
                    </div>
                </a>

                <!-- KRS Pending Card -->
                <a href="{{ route('admin.krs.index', ['status' => 'pending']) }}" class="group block">
                    <div class="relative bg-white dark:bg-gray-700/60 rounded-lg p-3 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 border border-red-100 dark:border-red-900/30 hover:border-red-400 dark:hover:border-red-600">
                        <div class="flex items-start justify-between mb-2">
                            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-md p-2 shadow-sm">
                                <i class="fas fa-clock text-base text-white"></i>
                            </div>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">KRS Pending</p>
                        <h4 class="text-2xl font-bold text-gray-800 dark:text-gray-100 my-1">{{ $krs_pending }}</h4>
                        <p class="text-red-600 dark:text-red-400 text-sm font-semibold flex items-center">
                            Detail <i class="fas fa-arrow-right ml-1 text-xs group-hover:translate-x-0.5 transition-transform"></i>
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Menu Cepat - Full Width -->
<div class="relative overflow-hidden bg-gradient-to-br from-slate-500/5 via-gray-400/5 to-slate-500/5 dark:from-gray-900/40 dark:to-gray-800/40 rounded-xl shadow-md hover:shadow-lg transition-shadow p-4 border border-gray-200/50 dark:border-gray-700/50 mb-4">
    <div class="absolute top-0 right-0 w-32 h-32 bg-maroon/5 dark:bg-maroon/10 rounded-full -mr-16 -mt-16"></div>
    <div class="relative">
        <div class="flex items-center mb-3">
            <div class="bg-gradient-to-br from-maroon to-red-700 rounded-lg p-2 mr-2 shadow-sm">
                <i class="fas fa-bolt text-white text-sm"></i>
            </div>
            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-200">Menu Cepat</h3>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="{{ route('admin.mahasiswa.create') }}" class="group block bg-white dark:bg-gray-700/60 rounded-lg p-3 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 border border-maroon/20 hover:border-maroon dark:border-maroon/30">
                <div class="bg-gradient-to-br from-maroon/10 to-red-600/10 dark:from-maroon/20 dark:to-red-600/20 rounded-md p-2 mb-2 w-fit">
                    <i class="fas fa-user-plus text-base text-maroon dark:text-red-400"></i>
                </div>
                <h4 class="font-bold text-sm text-gray-800 dark:text-gray-100">Mahasiswa</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-tight">Daftar baru</p>
            </a>

            <a href="{{ route('admin.dosen.create') }}" class="group block bg-white dark:bg-gray-700/60 rounded-lg p-3 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 border border-green-500/20 hover:border-green-500 dark:border-green-500/30">
                <div class="bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/40 dark:to-green-800/40 rounded-md p-2 mb-2 w-fit">
                    <i class="fas fa-chalkboard-teacher text-base text-green-600 dark:text-green-400"></i>
                </div>
                <h4 class="font-bold text-sm text-gray-800 dark:text-gray-100">Dosen</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-tight">Daftar baru</p>
            </a>

            <a href="{{ route('admin.mata-kuliah.create') }}" class="group block bg-white dark:bg-gray-700/60 rounded-lg p-3 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 border border-purple-500/20 hover:border-purple-500 dark:border-purple-500/30">
                <div class="bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900/40 dark:to-purple-800/40 rounded-md p-2 mb-2 w-fit">
                    <i class="fas fa-book text-base text-purple-600 dark:text-purple-400"></i>
                </div>
                <h4 class="font-bold text-sm text-gray-800 dark:text-gray-100">Mata Kuliah</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-tight">Buat baru</p>
            </a>

            <a href="{{ route('admin.krs.index', ['status' => 'pending']) }}" class="group block bg-white dark:bg-gray-700/60 rounded-lg p-3 shadow-sm hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 border border-blue-500/20 hover:border-blue-500 dark:border-blue-500/30">
                <div class="bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/40 dark:to-blue-800/40 rounded-md p-2 mb-2 w-fit">
                    <i class="fas fa-tasks text-base text-blue-600 dark:text-blue-400"></i>
                </div>
                <h4 class="font-bold text-sm text-gray-800 dark:text-gray-100">Verifikasi</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-tight">KRS pending</p>
            </a>
        </div>
    </div>
</div>

<!-- Kalender Akademik - Full Width -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-shadow border border-gray-200 dark:border-gray-700">
    <div class="p-3 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-maroon/5 to-red-600/5 dark:from-maroon/10 dark:to-red-600/10">
        <div class="flex items-center">
            <div class="bg-gradient-to-br from-maroon to-red-700 rounded-lg p-1.5 mr-2 shadow-sm">
                <i class="fas fa-calendar-alt text-white text-xs"></i>
            </div>
            <h3 class="text-xs font-bold text-gray-700 dark:text-gray-200">Kalender Akademik</h3>
        </div>
    </div>
    <div class="p-3">
        @if($academic_events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2.5">
                @foreach($academic_events as $event)
                    <div class="group bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-700/50 dark:to-gray-700/30 rounded-lg p-2.5 border-l-4 border-blue-500 hover:shadow-md transition-all duration-200 hover:-translate-y-0.5">
                        <div class="flex items-start gap-2">
                            <div class="flex-shrink-0">
                                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-md p-1.5 shadow-sm">
                                    <i class="fas fa-calendar-check text-white text-xs"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-xs text-gray-800 dark:text-gray-100 mb-1 truncate">{{ $event->title ?? $event->name }}</h4>
                                <p class="text-xs text-gray-600 dark:text-gray-400 flex items-center mb-1">
                                    <i class="fas fa-clock mr-1 text-blue-500 text-xs"></i>
                                    <span class="truncate">
                                        @if($event->start_date && $event->end_date)
                                            {{ \Carbon\Carbon::parse($event->start_date)->format('d M') }}
                                        @else
                                            {{ $event->date ? \Carbon\Carbon::parse($event->date)->format('d M Y') : 'TBA' }}
                                        @endif
                                    </span>
                                </p>
                                <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-500 text-white">
                                    {{ $event->category ?? 'Akademik' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-3 text-center">
                <a href="#" class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-maroon to-red-700 text-white rounded-lg text-xs font-semibold hover:from-red-800 hover:to-red-900 transition-all shadow-sm hover:shadow">
                    Lihat Semua
                    <i class="fas fa-arrow-right ml-1.5 text-xs"></i>
                </a>
            </div>
        @else
            <div class="text-center py-6">
                <div class="bg-gray-100 dark:bg-gray-700/50 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-2">
                    <i class="fas fa-calendar text-2xl text-gray-300 dark:text-gray-600"></i>
                </div>
                <p class="text-gray-500 dark:text-gray-400 font-semibold text-xs">Belum ada acara akademik</p>
                <p class="text-gray-400 dark:text-gray-500 text-xs mt-0.5">Acara akan ditampilkan di sini</p>
            </div>
        @endif
    </div>
</div>
@endsection
