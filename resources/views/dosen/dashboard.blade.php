@extends('layouts.dosen')

@section('title', 'Dashboard | Portal Dosen')
@section('header_title', 'Dashboard')

@section('content')
    <!-- Greeting Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Selamat Pagi, Dr. Handoko</h1>
        <p class="text-gray-500 mt-2 text-base">Berikut adalah ringkasan aktivitas akademik dan jadwal Anda hari ini.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Card 1: Total Mata Kuliah -->
        <a href="{{ route('dosen.kelas') }}" class="bg-white rounded-2xl p-6 border border-gray-100 flex flex-col justify-between h-[180px] hover:shadow-lg transition-all duration-300 group cursor-pointer">
            <div class="flex justify-between items-start">
                <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center text-red-600 group-hover:bg-red-600 group-hover:text-white transition-colors duration-300">
                    <i class="far fa-file-alt text-xl"></i>
                </div>
                <span class="px-2.5 py-1 text-xs font-bold rounded-md bg-green-50 text-green-600">
                    +0%
                </span>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Total Mata Kuliah</p>
                <p class="text-4xl font-bold text-gray-900">{{ $total_mata_kuliah }}</p>
            </div>
        </a>

        <!-- Card 2: Total Kelas Aktif -->
        <a href="{{ route('dosen.kelas') }}" class="bg-white rounded-2xl p-6 border border-gray-100 flex flex-col justify-between h-[180px] hover:shadow-lg transition-all duration-300 group cursor-pointer">
            <div class="flex justify-between items-start">
                <div class="w-12 h-12 rounded-xl bg-pink-50 flex items-center justify-center text-pink-600 group-hover:bg-pink-600 group-hover:text-white transition-colors duration-300">
                    <i class="fas fa-user-group text-xl"></i>
                </div>
                <span class="px-2.5 py-1 text-xs font-bold rounded-md bg-green-50 text-green-600">
                    +2 ini
                </span>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Total Mahasiswa</p>
                <p class="text-4xl font-bold text-gray-900">{{ $total_students }}</p>
            </div>
        </a>

        <!-- Card 3: Total Beban SKS -->
        <a href="{{ route('dosen.kelas') }}" class="bg-white rounded-2xl p-6 border border-gray-100 flex flex-col justify-between h-[180px] hover:shadow-lg transition-all duration-300 group cursor-pointer">
            <div class="flex justify-between items-start">
                <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors duration-300">
                    <i class="far fa-clock text-xl"></i>
                </div>
                <span class="px-2.5 py-1 text-xs font-bold rounded-md bg-gray-100 text-gray-500">
                    Tetap
                </span>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Total Beban SKS</p>
                <p class="text-4xl font-bold text-gray-900">{{ $sks_load }}</p>
            </div>
        </a>

        <!-- Card 4: KRS Approval -->
        <a href="{{ route('dosen.krs') }}" class="bg-white rounded-2xl p-6 border border-gray-100 flex flex-col justify-between h-[180px] hover:shadow-lg transition-all duration-300 group cursor-pointer">
            <div class="flex justify-between items-start">
                <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center text-red-600 group-hover:bg-red-600 group-hover:text-white transition-colors duration-300">
                    <i class="fas fa-exclamation-circle text-xl"></i>
                </div>
                <span class="px-2.5 py-1 text-xs font-bold rounded-md bg-red-50 text-red-600">
                    Urgent
                </span>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">KRS Menunggu Approval</p>
                <p class="text-4xl font-bold text-gray-900">{{ $krs_approval }}</p>
            </div>
        </a>
    </div>

    <!-- Main Content Split -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left Column: Schedule -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden min-h-[400px]">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-white">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center text-red-600">
                            <i class="far fa-clock"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Jadwal Mengajar Hari Ini (Senin)</h3>
                    </div>
                    <a href="#" class="text-sm text-gray-500 font-medium hover:text-red-700 flex items-center gap-1 transition-colors">
                        Lihat Semua <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
                <div class="p-2">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left">
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Mata Kuliah</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Ruangan</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($schedules as $schedule)
                                <tr class="group hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-5">
                                        <div class="font-bold text-gray-900 text-base">{{ $schedule['subject'] }}</div>
                                        <div class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                            {{ $schedule['code'] }} <span class="w-1 h-1 rounded-full bg-gray-300"></span> 3 SKS
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="px-3 py-1 rounded-md bg-red-50 text-red-700 text-xs font-bold border border-red-100">
                                            {{ $schedule['class'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center text-gray-600 text-sm font-medium">
                                            <i class="far fa-clock mr-2 text-gray-400"></i> {{ $schedule['time'] }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center text-gray-600 text-sm font-medium">
                                            <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i> {{ $schedule['room'] }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        @if($schedule['status'] == 'Selesai')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-600">
                                                <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span> Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500">
                                                <span class="w-2 h-2 rounded-full bg-gray-400 mr-2"></span> {{ $schedule['status'] }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Column: Sidebar Widgets -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Action Banner Removed -->

            <!-- Help Widget -->
            <div class="bg-blue-50 rounded-2xl border border-blue-100 p-5 flex items-start">
                <div class="flex-shrink-0 bg-white p-2 rounded-lg shadow-sm">
                    <i class="fas fa-headset text-blue-500 text-lg"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-bold text-blue-900">Butuh Bantuan?</h3>
                    <p class="text-xs text-blue-700 mt-1 leading-relaxed">Hubungi pusat bantuan jika Anda mengalami kendala teknis.</p>
                    <div class="mt-3 text-xs font-medium text-blue-800">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-phone-alt opacity-60"></i> Ext. 101 (IT Support)
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-envelope opacity-60"></i> it.support@stih.ac.id
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection