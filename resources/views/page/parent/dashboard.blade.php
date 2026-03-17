@extends('layouts.parent')

@section('title', 'Dashboard Orang Tua')
@section('page-title', 'Dashboard')

@push('styles')
    <style>
        :root {
            --blue-primary: #1e40af;
            --blue-hover: #1e3a8a;
        }

        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(30, 64, 175, 0.15);
        }
    </style>
@endpush

@section('content')
    <div class="w-full space-y-6">

        {{-- Welcome Card --}}
        <div class="relative bg-gradient-to-r from-maroon to-red-900 rounded-2xl shadow-xl text-white p-8 overflow-hidden"
            style="background: linear-gradient(135deg, #5a0015 0%, #800020 50%, #b22222 100%);">
            <!-- Decorative Background Elements -->
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-10 -mb-10 w-48 h-48 bg-white opacity-5 rounded-full blur-2xl"></div>

            <div class="relative flex flex-col md:flex-row items-center gap-8">
                <div class="w-24 h-24 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-4xl shadow-inner flex-shrink-0">
                    <i class="fas fa-user-graduate text-white drop-shadow-md"></i>
                </div>
                <div class="flex-1 w-full text-center md:text-left">
                    <h1 class="text-3xl font-bold mb-2 tracking-tight">Selamat Datang, Bapak/Ibu {{ Auth::user()->name }}</h1>
                    <p class="text-red-100 mb-6 font-medium text-sm md:text-base">Memantau Perkembangan Akademik Mahasiswa:</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm bg-black/20 backdrop-blur-sm border border-white/10 p-5 rounded-xl">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                                <i class="fas fa-id-card text-white/80 text-lg"></i>
                            </div>
                            <div>
                                <div class="text-white/60 text-xs font-semibold uppercase tracking-wider mb-0.5">Nama Mahasiswa</div>
                                <div class="font-bold text-lg leading-tight">{{ $mahasiswa->user->name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                                <i class="fas fa-fingerprint text-white/80 text-lg"></i>
                            </div>
                            <div>
                                <div class="text-white/60 text-xs font-semibold uppercase tracking-wider mb-0.5">NIM</div>
                                <div class="font-bold text-lg leading-tight">{{ $mahasiswa->nim }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                                <i class="fas fa-university text-white/80 text-lg"></i>
                            </div>
                            <div>
                                <div class="text-white/60 text-xs font-semibold uppercase tracking-wider mb-0.5">Prodi</div>
                                <div class="font-bold text-lg leading-tight truncate max-w-[150px]" title="{{ $mahasiswa->prodi ?? 'Hukum' }}">
                                    {{ $mahasiswa->prodi ?? 'Hukum' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Total SKS Semester Ini --}}
            <div class="stat-card relative bg-white rounded-2xl p-6 border border-gray-100 overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-bl-full -z-10 transition-transform group-hover:scale-110"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-book text-2xl"></i>
                    </div>
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider bg-gray-50 px-2 py-1 rounded-md">Semester Ini</span>
                </div>
                <div>
                    <div class="text-4xl font-extrabold text-gray-800 mb-1 tracking-tight">{{ $totalSks }}</div>
                    <div class="text-sm font-medium text-gray-500">Total SKS Diambil</div>
                </div>
            </div>

            {{-- Jumlah Mata Kuliah --}}
            <div class="stat-card relative bg-white rounded-2xl p-6 border border-gray-100 overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-teal-50 rounded-bl-full -z-10 transition-transform group-hover:scale-110"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-teal-100 rounded-xl flex items-center justify-center text-teal-600 shadow-sm group-hover:bg-teal-600 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-chalkboard-teacher text-2xl"></i>
                    </div>
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider bg-gray-50 px-2 py-1 rounded-md">Matkul</span>
                </div>
                <div>
                    <div class="text-4xl font-extrabold text-gray-800 mb-1 tracking-tight">{{ $jumlahMk }}</div>
                    <div class="text-sm font-medium text-gray-500">Mata Kuliah Diambil</div>
                </div>
            </div>

            {{-- IPK --}}
            <div class="stat-card relative bg-white rounded-2xl p-6 border border-gray-100 overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-bl-full -z-10 transition-transform group-hover:scale-110"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600 shadow-sm group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-award text-2xl"></i>
                    </div>
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider bg-gray-50 px-2 py-1 rounded-md">Prestasi</span>
                </div>
                <div>
                    <div class="text-4xl font-extrabold text-maroon mb-1 tracking-tight">{{ number_format($ipk, 2) }}</div>
                    <div class="text-sm font-medium text-gray-500">IPK Saat Ini</div>
                </div>
            </div>

            {{-- Status Pembayaran --}}
            <div class="stat-card relative bg-white rounded-2xl p-6 border border-gray-100 overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-orange-50 rounded-bl-full -z-10 transition-transform group-hover:scale-110"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600 shadow-sm group-hover:bg-orange-600 group-hover:text-white transition-colors duration-300">
                        <i class="fas fa-wallet text-2xl"></i>
                    </div>
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider bg-gray-50 px-2 py-1 rounded-md">Keuangan</span>
                </div>
                <div class="mt-2">
                    @if($statusPembayaran === 'lunas')
                        <div class="text-2xl font-black text-green-600 tracking-tight uppercase">Lunas</div>
                    @elseif($statusPembayaran === 'sebagian')
                        <div class="text-2xl font-black text-yellow-600 tracking-tight uppercase line-clamp-1">Sebagian</div>
                    @else
                        <div class="text-2xl font-black text-red-600 tracking-tight uppercase line-clamp-1">Belum Bayar</div>
                    @endif
                    <div class="text-sm font-medium text-gray-500 mt-1">Status Pembayaran</div>
                </div>
            </div>
        </div>



        {{-- Ringkasan Presensi --}}
        @if($presensiStats['total'] > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mr-3">
                    <i class="fas fa-chart-pie text-sm"></i>
                </div>
                Ringkasan Kehadiran
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex flex-col items-center justify-center">
                    <div class="text-3xl font-black text-green-500 mb-1">{{ $presensiStats['hadir'] }}</div>
                    <div class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Hadir</div>
                    @php $pctHadir = $presensiStats['total'] > 0 ? round($presensiStats['hadir'] / $presensiStats['total'] * 100) : 0; @endphp
                    <div class="text-xs mt-2 font-medium bg-green-100 text-green-700 px-2.5 py-0.5 rounded-full">{{ $pctHadir }}%</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex flex-col items-center justify-center">
                    <div class="text-3xl font-black text-blue-500 mb-1">{{ $presensiStats['sakit'] }}</div>
                    <div class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Sakit</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex flex-col items-center justify-center">
                    <div class="text-3xl font-black text-yellow-500 mb-1">{{ $presensiStats['izin'] }}</div>
                    <div class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Izin</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex flex-col items-center justify-center">
                    <div class="text-3xl font-black text-red-500 mb-1">{{ $presensiStats['alfa'] }}</div>
                    <div class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Alfa</div>
                </div>
            </div>
            
            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden flex ring-1 ring-inset ring-black/5">
                <div class="bg-green-500 h-3 hover:opacity-90 transition-opacity" style="width: {{ $pctHadir }}%" title="Hadir: {{ $pctHadir }}%"></div>
                @php $pctSakit = $presensiStats['total'] > 0 ? round($presensiStats['sakit'] / $presensiStats['total'] * 100) : 0; @endphp
                <div class="bg-blue-500 h-3 hover:opacity-90 transition-opacity opacity-90" style="width: {{ $pctSakit }}%" title="Sakit: {{ $pctSakit }}%"></div>
                @php $pctIzin = $presensiStats['total'] > 0 ? round($presensiStats['izin'] / $presensiStats['total'] * 100) : 0; @endphp
                <div class="bg-yellow-400 h-3 hover:opacity-90 transition-opacity" style="width: {{ $pctIzin }}%" title="Izin: {{ $pctIzin }}%"></div>
                <div class="bg-red-500 h-3 flex-1 hover:opacity-90 transition-opacity" title="Alfa"></div>
            </div>
            <div class="flex justify-between items-center mt-3 text-xs font-medium text-gray-400">
                <span>Total Pertemuan: {{ $presensiStats['total'] }}</span>
            </div>
        </div>
        @endif
    </div>
@endsection