@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Dashboard Admin</h2>
    <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Selamat datang di Sistem Informasi Akademik STIH</p>
</div>

<!-- Statistik Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Total Mahasiswa -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border-l-4 border-blue-500 p-6 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Mahasiswa</p>
                <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $total_mahasiswa }}</h3>
                <p class="text-gray-400 dark:text-gray-500 text-xs mt-2">Mahasiswa Aktif STIH</p>
                <a href="{{ route('admin.mahasiswa.index') }}" class="text-blue-600 dark:text-blue-400 text-sm font-semibold mt-3 inline-flex items-center hover:text-blue-700 dark:hover:text-blue-300">
                    Lihat Detail 
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            <div class="bg-blue-100 dark:bg-blue-900/30 rounded-full p-4 w-16 h-16 flex items-center justify-center">
                <i class="fas fa-user-graduate text-3xl text-blue-600 dark:text-blue-400"></i>
            </div>
        </div>
    </div>

    <!-- Total Dosen -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border-l-4 border-green-500 p-6 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Dosen</p>
                <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $total_dosen }}</h3>
                <p class="text-gray-400 dark:text-gray-500 text-xs mt-2">Dosen Pengajar</p>
                <a href="{{ route('admin.dosen.index') }}" class="text-green-600 dark:text-green-400 text-sm font-semibold mt-3 inline-flex items-center hover:text-green-700 dark:hover:text-green-300">
                    Lihat Detail 
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            <div class="bg-green-100 dark:bg-green-900/30 rounded-full p-4 w-16 h-16 flex items-center justify-center">
                <i class="fas fa-chalkboard-teacher text-3xl text-green-600 dark:text-green-400"></i>
            </div>
        </div>
    </div>

    <!-- Total Mata Kuliah -->
    <div class="bg-white rounded-xl shadow-md border-l-4 border-purple-500 p-6 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Total Mata Kuliah</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $total_mata_kuliah }}</h3>
                <p class="text-gray-400 text-xs mt-2">Mata Kuliah Tersedia</p>
                <a href="{{ route('admin.mata-kuliah.index') }}" class="text-purple-600 text-sm font-semibold mt-3 inline-flex items-center hover:text-purple-700">
                    Lihat Detail 
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            <div class="bg-purple-100 rounded-full p-4 w-16 h-16 flex items-center justify-center">
                <i class="fas fa-book text-3xl text-purple-600"></i>
            </div>
        </div>
    </div>

    <!-- Total Parent -->
    <div class="bg-white rounded-xl shadow-md border-l-4 border-orange-500 p-6 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Total Orang Tua</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $total_parent }}</h3>
                <p class="text-gray-400 text-xs mt-2">Wali Mahasiswa</p>
                <a href="{{ route('admin.mahasiswa.index') }}" class="text-orange-600 text-sm font-semibold mt-3 inline-flex items-center hover:text-orange-700">
                    Lihat Detail 
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            <div class="bg-orange-100 rounded-full p-4 w-16 h-16 flex items-center justify-center">
                <i class="fas fa-users text-3xl text-orange-600"></i>
            </div>
        </div>
    </div>

    <!-- Total Kelas -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border-l-4 border-indigo-500 p-6 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Kelas</p>
                <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $total_kelas }}</h3>
                <p class="text-gray-400 dark:text-gray-500 text-xs mt-2">Kelas Mata Kuliah</p>
                <a href="{{ route('admin.mata-kuliah.index') }}" class="text-indigo-600 dark:text-indigo-400 text-sm font-semibold mt-3 inline-flex items-center hover:text-indigo-700 dark:hover:text-indigo-300">
                    Lihat Detail 
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            <div class="bg-indigo-100 dark:bg-indigo-900/30 rounded-full p-4 w-16 h-16 flex items-center justify-center">
                <i class="fas fa-door-open text-3xl text-indigo-600 dark:text-indigo-400"></i>
            </div>
        </div>
    </div>

    <!-- KRS Pending -->
    <div class="bg-white rounded-xl shadow-md border-l-4 border-red-500 p-6 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">KRS Pending</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $krs_pending }}</h3>
                <p class="text-gray-400 text-xs mt-2">Menunggu Persetujuan</p>
                <a href="{{ route('admin.krs.index', ['status' => 'pending']) }}" class="text-red-600 text-sm font-semibold mt-3 inline-flex items-center hover:text-red-700">
                    Lihat Detail 
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            <div class="bg-red-100 rounded-full p-4 w-16 h-16 flex items-center justify-center">
                <i class="fas fa-clock text-3xl text-red-600"></i>
            </div>
        </div>
    </div>
</div>

<!-- KRS Terbaru & Menu Cepat -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kalender Akademik -->
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-md border-t-4 border-maroon">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center">
                <i class="fas fa-calendar-alt text-maroon dark:text-red-500 mr-2"></i>
                Kalender Akademik
            </h3>
        </div>
        <div class="p-6">
            @if($academic_events->count() > 0)
                <div class="space-y-3">
                    @foreach($academic_events as $event)
                        <div class="flex items-start p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border-l-4 border-blue-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <div class="flex-shrink-0 mr-4">
                                <div class="flex items-center justify-center h-12 w-12 rounded-lg bg-blue-100 dark:bg-blue-900/30">
                                    <i class="fas fa-calendar-check text-blue-600 dark:text-blue-400 text-lg"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800">{{ $event->title ?? $event->name }}</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-clock mr-1"></i>
                                    @if($event->start_date && $event->end_date)
                                        {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}
                                    @else
                                        {{ $event->date ? \Carbon\Carbon::parse($event->date)->format('d M Y') : 'Tanggal tidak ditentukan' }}
                                    @endif
                                </p>
                                @if($event->description)
                                    <p class="text-xs text-gray-500 mt-2">{{ Str::limit($event->description, 80) }}</p>
                                @endif
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 inline-block">
                                    {{ $event->category ?? 'Akademik' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="#" class="text-maroon font-semibold hover:underline inline-flex items-center">
                        Lihat Semua Acara
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-calendar text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 font-medium">Belum ada acara akademik</p>
                    <p class="text-gray-400 text-sm mt-1">Acara akademik akan ditampilkan di sini</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Menu Cepat -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border-t-4 border-maroon">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center">
                <i class="fas fa-bolt text-maroon dark:text-red-500 mr-2"></i>
                Menu Cepat
            </h3>
        </div>
        <div class="p-6 space-y-3">
            <a href="{{ route('admin.mahasiswa.create') }}" 
                class="flex items-center p-4 bg-white dark:bg-gray-800 border-2 border-maroon rounded-lg hover:bg-maroon hover:text-white transition group">
                            <div class="bg-maroon text-white rounded-full p-3 mr-4 transition">
                                <i class="fas fa-user-plus text-xl"></i>
                            </div>
                <div>
                    <h4 class="font-semibold dark:text-white">Tambah Mahasiswa</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-white">Daftarkan mahasiswa baru</p>
                </div>
            </a>

            <a href="{{ route('admin.dosen.create') }}" 
                class="flex items-center p-4 bg-white border-2 border-green-500 rounded-lg hover:bg-green-500 hover:text-white transition group">
                <div class="bg-green-500 text-white group-hover:bg-white group-hover:text-green-500 rounded-full p-3 mr-4 transition">
                    <i class="fas fa-chalkboard-teacher text-xl"></i>
                </div>
                <div>
                    <h4 class="font-semibold">Tambah Dosen</h4>
                    <p class="text-xs text-gray-500 group-hover:text-white">Daftarkan dosen baru</p>
                </div>
            </a>

            <a href="{{ route('admin.mata-kuliah.create') }}" 
                class="flex items-center p-4 bg-white border-2 border-purple-500 rounded-lg hover:bg-purple-500 hover:text-white transition group">
                <div class="bg-purple-500 text-white group-hover:bg-white group-hover:text-purple-500 rounded-full p-3 mr-4 transition">
                    <i class="fas fa-book text-xl"></i>
                </div>
                <div>
                    <h4 class="font-semibold">Tambah Mata Kuliah</h4>
                    <p class="text-xs text-gray-500 group-hover:text-white">Buat mata kuliah baru</p>
                </div>
            </a>

            <a href="{{ route('admin.krs.index', ['status' => 'pending']) }}" 
                class="flex items-center p-4 bg-white border-2 border-blue-500 rounded-lg hover:bg-blue-500 hover:text-white transition group">
                <div class="bg-blue-500 text-white group-hover:bg-white group-hover:text-blue-500 rounded-full p-3 mr-4 transition">
                    <i class="fas fa-tasks text-xl"></i>
                </div>
                <div>
                    <h4 class="font-semibold">Verifikasi KRS</h4>
                    <p class="text-xs text-gray-500 group-hover:text-white">Setujui KRS pending</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
