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
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Welcome Card --}}
        <div class="bg-gradient-to-r from-red-800 to-red-900 rounded-xl shadow-lg text-white p-8"
            style="background: linear-gradient(to right, #991b1b, #7f1d1d);">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center text-3xl font-bold">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="flex-1">
                    <h1 class="text-3xl font-bold mb-2">Selamat Datang, Bapak/Ibu {{ Auth::user()->name }}</h1>
                    <p class="text-blue-100 mb-4">Memantau Perkembangan Akademik Mahasiswa:</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm bg-white/10 p-4 rounded-lg">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-id-card opacity-70"></i>
                            <div>
                                <div class="opacity-80 text-xs">Nama Mahasiswa</div>
                                <div class="font-bold text-lg leading-tight">{{ $mahasiswa->user->name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-fingerprint opacity-70"></i>
                            <div>
                                <div class="opacity-80 text-xs">NIM</div>
                                <div class="font-bold text-lg leading-tight">{{ $mahasiswa->nim }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-university opacity-70"></i>
                            <div>
                                <div class="opacity-80 text-xs">Prodi</div>
                                <div class="font-bold text-lg leading-tight">{{ $mahasiswa->prodi ?? 'Hukum' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Total SKS Semester Ini --}}
            <div class="stat-card bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book text-blue-600 text-xl"></i>
                    </div>
                    <span class="text-xs text-gray-500 font-semibold">SEMESTER INI</span>
                </div>
                <div class="text-3xl font-bold text-gray-800 mb-1">{{ $totalSks }}</div>
                <div class="text-sm text-gray-600">Total SKS Diambil</div>
            </div>

            {{-- Jumlah Mata Kuliah --}}
            <div class="stat-card bg-white rounded-xl shadow-md p-6 border-l-4 border-teal-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher text-teal-600 text-xl"></i>
                    </div>
                    <span class="text-xs text-gray-500 font-semibold">MATKUL</span>
                </div>
                <div class="text-3xl font-bold text-gray-800 mb-1">{{ $jumlahMk }}</div>
                <div class="text-sm text-gray-600">Mata Kuliah Diambil</div>
            </div>

            {{-- IPK --}}
            <div class="stat-card bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                    </div>
                    <span class="text-xs text-gray-500 font-semibold">PRESTASI</span>
                </div>
                <div class="text-3xl font-bold text-red-600 mb-1">{{ number_format($ipk, 2) }}</div>
                <div class="text-sm text-gray-600">IPK Saat Ini</div>
            </div>

            {{-- Status Pembayaran --}}
            <div class="stat-card bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-wallet text-orange-600 text-xl"></i>
                    </div>
                    <span class="text-xs text-gray-500 font-semibold">KEUANGAN</span>
                </div>
                <div class="text-lg font-bold text-gray-800 mb-1">
                    @if($statusPembayaran === 'lunas')
                        <span class="text-green-600">LUNAS</span>
                    @elseif($statusPembayaran === 'sebagian')
                        <span class="text-yellow-600">SEBAGIAN</span>
                    @else
                        <span class="text-red-600">BELUM BAYAR</span>
                    @endif
                </div>
                <div class="text-sm text-gray-600">Status Pembayaran</div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-bolt text-blue-600 mr-3"></i>
                Menu Cepat
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <a href="{{ route('parent.nilai') }}"
                    class="flex items-center gap-4 p-4 border rounded-lg hover:border-blue-500 hover:bg-blue-50 transition group">
                    <div
                        class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <div class="font-bold text-gray-800">Lihat Nilai</div>
                        <div class="text-xs text-gray-500">Pantau Transkrip</div>
                    </div>
                </a>

                <a href="{{ route('parent.jadwal') }}"
                    class="flex items-center gap-4 p-4 border rounded-lg hover:border-teal-500 hover:bg-teal-50 transition group">
                    <div
                        class="w-10 h-10 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <div class="font-bold text-gray-800">Jadwal Kuliah</div>
                        <div class="text-xs text-gray-500">Cek Jadwal Harian</div>
                    </div>
                </a>

                <a href="{{ route('parent.presensi') }}"
                    class="flex items-center gap-4 p-4 border rounded-lg hover:border-purple-500 hover:bg-purple-50 transition group">
                    <div
                        class="w-10 h-10 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        <div class="font-bold text-gray-800">Presensi</div>
                        <div class="text-xs text-gray-500">Kehadiran Mahasiswa</div>
                    </div>
                </a>

                <a href="{{ route('parent.pembayaran') }}"
                    class="flex items-center gap-4 p-4 border rounded-lg hover:border-orange-500 hover:bg-orange-50 transition group">
                    <div
                        class="w-10 h-10 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center group-hover:scale-110 transition">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <div class="font-bold text-gray-800">Pembayaran</div>
                        <div class="text-xs text-gray-500">Info Uang Kuliah</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection