@extends('layouts.parent')

@section('title', 'Pembayaran - Orang Tua')
@section('page-title', 'Pembayaran Uang Kuliah')

@section('content')
    <div class="space-y-6">

        {{-- Header Summary --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">Riwayat Pembayaran</h3>
                    <p class="text-sm text-gray-500">Daftar transaksi pembayaran uang kuliah mahasiswa</p>
                </div>
                <a href="#"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition flex items-center gap-2"
                    onclick="alert('Silakan hubungi bagian keuangan untuk informasi tagihan lebih lanjut.')">
                    <i class="fas fa-info-circle"></i> Info Tagihan
                </a>
            </div>
        </div>

        {{-- Payment History List --}}
        <div class="space-y-4">
            @forelse($pembayaranData as $item)
                <div
                    class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-600">
                            <i class="fas fa-file-invoice-dollar text-xl"></i>
                        </div>
                        <div>
                            <div class="font-bold text-gray-800 text-lg">Pembayaran Semester
                                {{ $item->semester->nama_semester ?? '-' }}</div>
                            <div class="text-sm text-gray-500">
                                ID Transaksi: <span class="font-mono text-gray-700">{{ $item->id }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col md:items-end gap-1">
                        <div class="font-bold text-gray-800 text-xl">Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}
                        </div>
                        <div class="flex items-center gap-2">
                            <span
                                class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($item->tanggal_bayar)->translatedFormat('d F Y, H:i') }}</span>
                            @if($item->status == 'lunas')
                                <span class="px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-700">LUNAS</span>
                            @elseif($item->status == 'sebagian')
                                <span class="px-2 py-0.5 rounded text-xs font-bold bg-yellow-100 text-yellow-700">SEBAGIAN</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700">BELUM LUNAS</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-receipt text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Riwayat Pembayaran</h3>
                    <p class="text-gray-500">Mahasiswa belum melakukan transaksi pembayaran tercatat.</p>
                </div>
            @endforelse
        </div>

    </div>
@endsection 