@extends('layouts.parent')

@section('title', 'Dashboard Orang Tua')
@section('page-title', 'Dashboard')

@push('styles')
    <style>
        .stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.1);
        }
        .hero-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.15;
            pointer-events: none;
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .animate-fade-in-up-delay-1 { animation-delay: 0.1s; opacity: 0; }
        .animate-fade-in-up-delay-2 { animation-delay: 0.2s; opacity: 0; }
        .animate-fade-in-up-delay-3 { animation-delay: 0.3s; opacity: 0; }
        .animate-fade-in-up-delay-4 { animation-delay: 0.4s; opacity: 0; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .glass-strip {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .progress-segment {
            transition: width 1s ease-out;
        }
    </style>
@endpush

@section('content')
    <div class="w-full space-y-8 animate-fade-in-up">

        {{-- Welcome Hero --}}
        <div class="relative overflow-hidden rounded-3xl bg-linear-to-br from-[#7a1621] via-[#9b1c2a] to-[#5a0015] shadow-2xl shadow-red-900/20 text-white">
            <!-- Decorative glows -->
            <div class="hero-glow top-0 right-0 w-72 h-72 bg-white"></div>
            <div class="hero-glow bottom-0 left-20 w-56 h-56 bg-red-300"></div>

            <!-- Subtle grid pattern -->
            <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 24px 24px;"></div>

            <div class="relative p-6 md:p-10">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6 md:gap-8">
                    <!-- Avatar -->
                    <div class="relative shrink-0">
                        <div class="w-20 h-20 md:w-24 md:h-24 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-4xl shadow-lg">
                            <i class="fas fa-user-graduate text-white/90"></i>
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-7 h-7 bg-emerald-400 rounded-full border-3 border-[#7a1621] flex items-center justify-center shadow-lg">
                            <i class="fas fa-check text-[10px] text-white font-bold"></i>
                        </div>
                    </div>

                    <!-- Text & Info -->
                    <div class="flex-1 text-center md:text-left space-y-3">
                        <div>
                            <p class="text-red-200 text-xs md:text-sm font-semibold uppercase tracking-widest mb-1">Portal Orang Tua / Wali</p>
                            <h1 class="text-2xl md:text-4xl font-black tracking-tight">Selamat Datang, Bapak/Ibu {{ Auth::user()->name }}</h1>
                        </div>
                        <p class="text-red-100/80 text-sm md:text-base max-w-xl leading-relaxed">
                            Memantau perkembangan akademik mahasiswa secara real-time. Semua informasi nilai, jadwal, dan keuangan tersedia di sini.
                        </p>
                    </div>
                </div>

                <!-- Info Mahasiswa -->
                <div class="mt-8 glass-strip rounded-2xl p-4 md:p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                                <i class="fas fa-user text-white/80"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] text-red-200/70 font-bold uppercase tracking-wider">Nama Mahasiswa</div>
                                <div class="font-bold text-base md:text-lg truncate">{{ $mahasiswa->user->name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                                <i class="fas fa-fingerprint text-white/80"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] text-red-200/70 font-bold uppercase tracking-wider">NIM</div>
                                <div class="font-bold text-base md:text-lg truncate">{{ $mahasiswa->nim }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                                <i class="fas fa-university text-white/80"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[10px] text-red-200/70 font-bold uppercase tracking-wider">Program Studi</div>
                                <div class="font-bold text-base md:text-lg truncate" title="{{ $mahasiswa->prodi ?? 'Hukum' }}">{{ $mahasiswa->prodi ?? 'Hukum' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            {{-- SKS --}}
            <div class="stat-card animate-fade-in-up animate-fade-in-up-delay-1 relative bg-white dark:bg-[#1a1c23] rounded-2xl p-5 md:p-6 border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden group">
                <div class="absolute left-0 top-6 bottom-6 w-1 rounded-r-full bg-blue-500"></div>
                <div class="absolute inset-0 bg-linear-to-br from-blue-500/3 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative pl-3">
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-11 h-11 md:w-12 md:h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg md:text-xl shadow-sm group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <span class="text-[10px] font-bold text-blue-600/70 dark:text-blue-400/70 uppercase tracking-wider bg-blue-50 dark:bg-blue-900/20 px-2.5 py-1 rounded-lg">Semester Ini</span>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white tracking-tight">{{ $totalSks }}</div>
                        <div class="text-xs md:text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Total SKS Diambil</div>
                    </div>
                </div>
            </div>

            {{-- Mata Kuliah --}}
            <div class="stat-card animate-fade-in-up animate-fade-in-up-delay-2 relative bg-white dark:bg-[#1a1c23] rounded-2xl p-5 md:p-6 border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden group">
                <div class="absolute left-0 top-6 bottom-6 w-1 rounded-r-full bg-teal-500"></div>
                <div class="absolute inset-0 bg-linear-to-br from-teal-500/3 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative pl-3">
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-11 h-11 md:w-12 md:h-12 rounded-xl bg-teal-50 dark:bg-teal-900/20 text-teal-600 dark:text-teal-400 flex items-center justify-center text-lg md:text-xl shadow-sm group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <span class="text-[10px] font-bold text-teal-600/70 dark:text-teal-400/70 uppercase tracking-wider bg-teal-50 dark:bg-teal-900/20 px-2.5 py-1 rounded-lg">Matkul</span>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white tracking-tight">{{ $jumlahMk }}</div>
                        <div class="text-xs md:text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Mata Kuliah Diambil</div>
                    </div>
                </div>
            </div>

            {{-- IPK --}}
            <div class="stat-card animate-fade-in-up animate-fade-in-up-delay-3 relative bg-white dark:bg-[#1a1c23] rounded-2xl p-5 md:p-6 border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden group">
                <div class="absolute left-0 top-6 bottom-6 w-1 rounded-r-full bg-purple-500"></div>
                <div class="absolute inset-0 bg-linear-to-br from-purple-500/3 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative pl-3">
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-11 h-11 md:w-12 md:h-12 rounded-xl bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 flex items-center justify-center text-lg md:text-xl shadow-sm group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-award"></i>
                        </div>
                        <span class="text-[10px] font-bold text-purple-600/70 dark:text-purple-400/70 uppercase tracking-wider bg-purple-50 dark:bg-purple-900/20 px-2.5 py-1 rounded-lg">Prestasi</span>
                    </div>
                    <div>
                        <div class="text-3xl md:text-4xl font-black text-maroon dark:text-red-400 tracking-tight">{{ number_format($ipk, 2) }}</div>
                        <div class="text-xs md:text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">IPK Saat Ini</div>
                    </div>
                </div>
            </div>

            {{-- Pembayaran --}}
            <div class="stat-card animate-fade-in-up animate-fade-in-up-delay-4 relative bg-white dark:bg-[#1a1c23] rounded-2xl p-5 md:p-6 border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden group">
                <div class="absolute left-0 top-6 bottom-6 w-1 rounded-r-full bg-orange-500"></div>
                <div class="absolute inset-0 bg-linear-to-br from-orange-500/3 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative pl-3">
                    <div class="flex items-center justify-between mb-5">
                        <div class="w-11 h-11 md:w-12 md:h-12 rounded-xl bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 flex items-center justify-center text-lg md:text-xl shadow-sm group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <span class="text-[10px] font-bold text-orange-600/70 dark:text-orange-400/70 uppercase tracking-wider bg-orange-50 dark:bg-orange-900/20 px-2.5 py-1 rounded-lg">Keuangan</span>
                    </div>
                    <div class="mt-1">
                        @if($statusPembayaran === 'lunas')
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                <span class="text-sm md:text-base font-black text-green-600 dark:text-green-400 uppercase tracking-wide">Lunas</span>
                            </div>
                        @elseif($statusPembayaran === 'sebagian')
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800">
                                <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                                <span class="text-sm md:text-base font-black text-yellow-600 dark:text-yellow-400 uppercase tracking-wide">Sebagian</span>
                            </div>
                        @else
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                <span class="text-sm md:text-base font-black text-red-600 dark:text-red-400 uppercase tracking-wide">Belum Bayar</span>
                            </div>
                        @endif
                        <div class="text-xs md:text-sm font-medium text-gray-500 dark:text-gray-400 mt-3">Status Pembayaran</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ringkasan Presensi --}}
        @if($presensiStats['total'] > 0)
        @php
            $pctHadir = $presensiStats['total'] > 0 ? round($presensiStats['hadir'] / $presensiStats['total'] * 100) : 0;
            $pctSakit = $presensiStats['total'] > 0 ? round($presensiStats['sakit'] / $presensiStats['total'] * 100) : 0;
            $pctIzin  = $presensiStats['total'] > 0 ? round($presensiStats['izin'] / $presensiStats['total'] * 100) : 0;
            $pctAlfa  = $presensiStats['total'] > 0 ? max(0, 100 - $pctHadir - $pctSakit - $pctIzin) : 0;
        @endphp
        <div class="bg-white dark:bg-[#1a1c23] rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 md:p-8 animate-fade-in-up animate-fade-in-up-delay-4">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg shadow-sm">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white">Ringkasan Kehadiran</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Data presensi semester berjalan</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="relative bg-green-50 dark:bg-green-900/10 rounded-2xl p-5 border border-green-100 dark:border-green-900/20 flex flex-col items-center justify-center text-center group hover:shadow-md transition-shadow">
                    <div class="text-3xl md:text-4xl font-black text-green-600 dark:text-green-400 mb-1">{{ $presensiStats['hadir'] }}</div>
                    <div class="text-xs font-bold text-green-700 dark:text-green-300 uppercase tracking-wider">Hadir</div>
                    <div class="mt-2 text-[10px] font-bold bg-green-200 dark:bg-green-800 text-green-800 dark:text-green-200 px-2.5 py-1 rounded-full">{{ $pctHadir }}%</div>
                </div>
                <div class="relative bg-blue-50 dark:bg-blue-900/10 rounded-2xl p-5 border border-blue-100 dark:border-blue-900/20 flex flex-col items-center justify-center text-center group hover:shadow-md transition-shadow">
                    <div class="text-3xl md:text-4xl font-black text-blue-600 dark:text-blue-400 mb-1">{{ $presensiStats['sakit'] }}</div>
                    <div class="text-xs font-bold text-blue-700 dark:text-blue-300 uppercase tracking-wider">Sakit</div>
                    <div class="mt-2 text-[10px] font-bold bg-blue-200 dark:bg-blue-800 text-blue-800 dark:text-blue-200 px-2.5 py-1 rounded-full">{{ $pctSakit }}%</div>
                </div>
                <div class="relative bg-yellow-50 dark:bg-yellow-900/10 rounded-2xl p-5 border border-yellow-100 dark:border-yellow-900/20 flex flex-col items-center justify-center text-center group hover:shadow-md transition-shadow">
                    <div class="text-3xl md:text-4xl font-black text-yellow-600 dark:text-yellow-400 mb-1">{{ $presensiStats['izin'] }}</div>
                    <div class="text-xs font-bold text-yellow-700 dark:text-yellow-300 uppercase tracking-wider">Izin</div>
                    <div class="mt-2 text-[10px] font-bold bg-yellow-200 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200 px-2.5 py-1 rounded-full">{{ $pctIzin }}%</div>
                </div>
                <div class="relative bg-red-50 dark:bg-red-900/10 rounded-2xl p-5 border border-red-100 dark:border-red-900/20 flex flex-col items-center justify-center text-center group hover:shadow-md transition-shadow">
                    <div class="text-3xl md:text-4xl font-black text-red-600 dark:text-red-400 mb-1">{{ $presensiStats['alfa'] }}</div>
                    <div class="text-xs font-bold text-red-700 dark:text-red-300 uppercase tracking-wider">Alfa</div>
                    <div class="mt-2 text-[10px] font-bold bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 px-2.5 py-1 rounded-full">{{ $pctAlfa }}%</div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="space-y-3">
                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-4 overflow-hidden flex ring-1 ring-inset ring-black/5 dark:ring-white/5 shadow-inner">
                    <div class="progress-segment bg-green-500 h-full" style="width: {{ $pctHadir }}%" title="Hadir: {{ $pctHadir }}%"></div>
                    <div class="progress-segment bg-blue-500 h-full opacity-90" style="width: {{ $pctSakit }}%" title="Sakit: {{ $pctSakit }}%"></div>
                    <div class="progress-segment bg-yellow-400 h-full" style="width: {{ $pctIzin }}%" title="Izin: {{ $pctIzin }}%"></div>
                    <div class="progress-segment bg-red-500 h-full flex-1" title="Alfa: {{ $pctAlfa }}%"></div>
                </div>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                    <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                        <div class="w-2.5 h-2.5 rounded-full bg-green-500"></div>
                        <span>Hadir</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                        <div class="w-2.5 h-2.5 rounded-full bg-blue-500"></div>
                        <span>Sakit</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                        <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
                        <span>Izin</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                        <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                        <span>Alfa</span>
                    </div>
                    <div class="ml-auto text-xs font-bold text-gray-400 dark:text-gray-500">Total Pertemuan: {{ $presensiStats['total'] }}</div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection