@extends('layouts.mahasiswa')

@section('title', 'Pembayaran')
@section('page-title', 'Pembayaran')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    
    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Total Tagihan --}}
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-invoice-dollar text-2xl"></i>
                </div>
                <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">Total</span>
            </div>
            <h3 class="text-2xl font-bold mb-1">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</h3>
            <p class="text-red-100 text-sm">Total Tagihan</p>
        </div>

        {{-- Total Dibayar --}}
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">Paid</span>
            </div>
            <h3 class="text-2xl font-bold mb-1">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</h3>
            <p class="text-green-100 text-sm">Total Dibayar</p>
        </div>

        {{-- Sisa Tagihan --}}
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
                <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">Outstanding</span>
            </div>
            <h3 class="text-2xl font-bold mb-1">Rp {{ number_format($totalSisa, 0, ',', '.') }}</h3>
            <p class="text-orange-100 text-sm">Sisa Tagihan</p>
        </div>
    </div>

    {{-- Pembayaran Semester Aktif --}}
    @if($pembayaranAktif)
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4">
            <h3 class="text-xl font-bold">Pembayaran Semester Aktif</h3>
            <p class="text-sm opacity-90">{{ $semesterAktif->nama_semester ?? '-' }}</p>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Left: Payment Details --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Jenis Pembayaran:</span>
                        <span class="font-semibold text-gray-800">{{ ucfirst($pembayaranAktif->jenis) }}</span>
                    </div>
                    <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Total Tagihan:</span>
                        <span class="font-bold text-gray-800">Rp {{ number_format($pembayaranAktif->jumlah, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Sudah Dibayar:</span>
                        <span class="font-bold text-green-600">Rp {{ number_format($pembayaranAktif->dibayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Sisa:</span>
                        <span class="font-bold text-red-600">Rp {{ number_format($pembayaranAktif->sisa, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Right: Status & Action --}}
                <div class="flex flex-col justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Status Pembayaran:</p>
                        <div class="flex items-center gap-2 mb-4">
                            @if($pembayaranAktif->status === 'lunas')
                                <span class="px-4 py-2 bg-green-100 text-green-700 rounded-lg font-bold text-lg flex items-center gap-2">
                                    <i class="fas fa-check-circle"></i>
                                    LUNAS
                                </span>
                            @elseif($pembayaranAktif->status === 'sebagian')
                                <span class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg font-bold text-lg flex items-center gap-2">
                                    <i class="fas fa-exclamation-circle"></i>
                                    SEBAGIAN
                                </span>
                            @else
                                <span class="px-4 py-2 bg-red-100 text-red-700 rounded-lg font-bold text-lg flex items-center gap-2">
                                    <i class="fas fa-times-circle"></i>
                                    BELUM BAYAR
                                </span>
                            @endif
                        </div>
                        @if($pembayaranAktif->tanggal_bayar)
                        <p class="text-sm text-gray-600">
                            Terakhir dibayar: <span class="font-semibold">{{ \Carbon\Carbon::parse($pembayaranAktif->tanggal_bayar)->format('d M Y') }}</span>
                        </p>
                        @endif
                    </div>

                    @if($pembayaranAktif->status !== 'lunas')
                    <button class="mt-4 px-6 py-3 bg-maroon text-white rounded-lg hover:bg-maroon-hover transition font-bold flex items-center justify-center gap-2">
                        <i class="fas fa-credit-card"></i>
                        Bayar Sekarang
                    </button>
                    @endif
                </div>
            </div>

            {{-- Payment Progress Bar --}}
            <div class="mt-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">Progress Pembayaran</span>
                    <span class="text-sm font-bold text-gray-800">
                        {{ $pembayaranAktif->jumlah > 0 ? number_format(($pembayaranAktif->dibayar / $pembayaranAktif->jumlah) * 100, 0) : 0 }}%
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 h-3 rounded-full transition-all duration-500" 
                         style="width: {{ $pembayaranAktif->jumlah > 0 ? ($pembayaranAktif->dibayar / $pembayaranAktif->jumlah) * 100 : 0 }}%">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Riwayat Pembayaran --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-maroon to-red-800 text-white px-6 py-4">
            <h3 class="text-xl font-bold">Riwayat Pembayaran</h3>
        </div>

        @if($pembayaranData->isEmpty())
        <div class="p-12 text-center">
            <i class="fas fa-receipt text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Riwayat Pembayaran</h3>
            <p class="text-gray-500">Riwayat pembayaran Anda akan muncul di sini.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Semester</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Jenis</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase">Tagihan</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase">Dibayar</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase">Sisa</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase">Tanggal Bayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($pembayaranData as $pembayaran)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">
                            {{ $pembayaran->semester->nama_semester ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ ucfirst($pembayaran->jenis) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-right font-semibold text-gray-800">
                            Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-right font-semibold text-green-600">
                            Rp {{ number_format($pembayaran->dibayar, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-right font-semibold text-red-600">
                            Rp {{ number_format($pembayaran->sisa, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($pembayaran->status === 'lunas')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg font-semibold text-xs">
                                    LUNAS
                                </span>
                            @elseif($pembayaran->status === 'sebagian')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg font-semibold text-xs">
                                    SEBAGIAN
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-lg font-semibold text-xs">
                                    BELUM BAYAR
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-center text-gray-700">
                            {{ $pembayaran->tanggal_bayar ? \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y') : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Info Card --}}
    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-5">
        <div class="flex items-start gap-3">
            <i class="fas fa-info-circle text-blue-600 text-2xl"></i>
            <div>
                <h4 class="font-bold text-blue-900 mb-2">Informasi Pembayaran</h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Pembayaran dapat dilakukan melalui transfer bank atau langsung ke bagian keuangan</li>
                    <li>• Pastikan Anda mencantumkan NPM saat melakukan transfer</li>
                    <li>• Bukti pembayaran dapat di-upload melalui sistem atau diserahkan ke bagian keuangan</li>
                    <li>• Untuk informasi lebih lanjut, hubungi bagian keuangan kampus</li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection
