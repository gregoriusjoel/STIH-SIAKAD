@extends('layouts.mahasiswa')

@section('title', 'Dashboard Mahasiswa')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    :root {
        --maroon: #8B1538;
        --maroon-hover: #6B0F2A;
        --maroon-light: #FEF2F4;
    }
    
    .stat-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(139, 21, 56, 0.15);
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    {{-- Welcome Card --}}
    <div class="bg-gradient-to-r from-maroon to-maroon-hover rounded-xl shadow-lg text-white p-8">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center text-3xl font-bold">
                {{ strtoupper(substr($mahasiswa->user->name ?? 'M', 0, 1)) }}
            </div>
            <div class="flex-1">
                <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ $mahasiswa->user->name ?? 'Mahasiswa' }}!</h1>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div><span class="opacity-80">NPM:</span> <span class="font-semibold">{{ $mahasiswa->npm }}</span></div>
                    <div><span class="opacity-80">Prodi:</span> <span class="font-semibold">{{ $mahasiswa->prodi ?? 'Hukum' }}</span></div>
                    <div><span class="opacity-80">Angkatan:</span> <span class="font-semibold">{{ $mahasiswa->angkatan ?? '2023' }}</span></div>
                    <div>
                        <span class="opacity-80">Status:</span> 
                        <span class="px-2 py-1 rounded-full text-xs font-bold ml-1 {{ $mahasiswa->status === 'aktif' ? 'bg-green-400 text-green-900' : 'bg-yellow-400 text-yellow-900' }}">
                            {{ strtoupper($mahasiswa->status ?? 'AKTIF') }}
                        </span>
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
            <div class="text-sm text-gray-600">Total SKS</div>
        </div>

        {{-- Jumlah Mata Kuliah --}}
        <div class="stat-card bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-green-600 text-xl"></i>
                </div>
                <span class="text-xs text-gray-500 font-semibold">MATA KULIAH</span>
            </div>
            <div class="text-3xl font-bold text-gray-800 mb-1">{{ $jumlahMk }}</div>
            <div class="text-sm text-gray-600">Diambil</div>
        </div>

        {{-- IPK --}}
        <div class="stat-card bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
                <span class="text-xs text-gray-500 font-semibold">PRESTASI</span>
            </div>
            <div class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($ipk, 2) }}</div>
            <div class="text-sm text-gray-600">IPK ({{ $totalSksNilai }} SKS)</div>
        </div>

        {{-- Status Pembayaran --}}
        <div class="stat-card bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-wallet text-red-600 text-xl"></i>
                </div>
                <span class="text-xs text-gray-500 font-semibold">PEMBAYARAN</span>
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
            <div class="text-sm text-gray-600">Status Bayar</div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-bolt text-maroon mr-3"></i>
            Akses Cepat
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('mahasiswa.krs.index') }}" class="flex items-center gap-4 p-4 border-2 border-maroon rounded-lg hover:bg-maroon-light transition group">
                <div class="w-12 h-12 bg-maroon rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                    <i class="fas fa-clipboard-list text-white text-xl"></i>
                </div>
                <div>
                    <div class="font-bold text-gray-800">Isi KRS</div>
                    <div class="text-sm text-gray-600">Status: {{ $krsStatus }}</div>
                </div>
            </a>

            <a href="{{ route('mahasiswa.nilai.index') }}" class="flex items-center gap-4 p-4 border-2 border-blue-500 rounded-lg hover:bg-blue-50 transition group">
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                    <i class="fas fa-graduation-cap text-white text-xl"></i>
                </div>
                <div>
                    <div class="font-bold text-gray-800">Lihat Nilai</div>
                    <div class="text-sm text-gray-600">IPK: {{ number_format($ipk, 2) }}</div>
                </div>
            </a>

            <a href="{{ route('mahasiswa.jadwal.index') }}" class="flex items-center gap-4 p-4 border-2 border-green-500 rounded-lg hover:bg-green-50 transition group">
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                    <i class="fas fa-calendar-alt text-white text-xl"></i>
                </div>
                <div>
                    <div class="font-bold text-gray-800">Jadwal Kuliah</div>
                    <div class="text-sm text-gray-600">Semester Ini</div>
                </div>
            </a>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6">
        <div class="flex items-start gap-4">
            <i class="fas fa-info-circle text-blue-600 text-2xl"></i>
            <div>
                <h4 class="font-bold text-blue-900 mb-2">Informasi Penting</h4>
                <p class="text-sm text-blue-800">
                    Pastikan Anda telah mengisi KRS sebelum batas waktu yang ditentukan. 
                    Untuk informasi lebih lanjut, hubungi bagian akademik kampus.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    console.log('Dashboard Mahasiswa loaded');
</script>
@endpush
