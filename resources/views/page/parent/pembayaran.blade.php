@extends('layouts.parent')

@section('title', 'Pembayaran - Orang Tua')
@section('page-title', 'Pembayaran Uang Kuliah')

@section('content')
    <div class="space-y-6">

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center text-orange-600 text-xl flex-shrink-0">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div>
                    <div class="text-xs text-gray-500 font-medium uppercase">Total Tagihan</div>
                    <div class="text-xl font-bold text-gray-800">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600 text-xl flex-shrink-0">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="text-xs text-gray-500 font-medium uppercase">Total Terbayar</div>
                    <div class="text-xl font-bold text-green-700">Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 {{ $sisaTagihan > 0 ? 'bg-red-100' : 'bg-green-100' }} rounded-lg flex items-center justify-center {{ $sisaTagihan > 0 ? 'text-red-600' : 'text-green-600' }} text-xl flex-shrink-0">
                    <i class="fas fa-{{ $sisaTagihan > 0 ? 'exclamation-circle' : 'check-double' }}"></i>
                </div>
                <div>
                    <div class="text-xs text-gray-500 font-medium uppercase">Sisa Tagihan</div>
                    <div class="text-xl font-bold {{ $sisaTagihan > 0 ? 'text-red-600' : 'text-green-600' }}">
                        Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Invoice List --}}
        <div class="space-y-4">
            @forelse($invoices as $invoice)
                @php
                    $totalPaid = $invoice->total_paid ?? 0;
                    $sisa = $invoice->total_tagihan - $totalPaid;
                    $persen = $invoice->total_tagihan > 0 ? min(100, ($totalPaid / $invoice->total_tagihan) * 100) : 0;
                @endphp
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    {{-- Top row --}}
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 flex-shrink-0">
                                <i class="fas fa-file-invoice-dollar text-xl"></i>
                            </div>
                            <div>
                                <div class="font-bold text-gray-800 text-lg">
                                    Semester {{ $invoice->semester }}
                                    @if($invoice->tahun_ajaran)
                                        &bull; TA {{ $invoice->tahun_ajaran }}
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500">
                                    ID: <span class="font-mono">{{ $invoice->id }}</span>
                                    @if($invoice->published_at)
                                        &bull; Diterbitkan: {{ $invoice->published_at->translatedFormat('d F Y') }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-1">
                            <div class="font-bold text-gray-800 text-xl">
                                Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}
                            </div>
                            @if($invoice->status === 'LUNAS')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">LUNAS</span>
                            @elseif($invoice->status === 'PARTIAL')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">CICILAN</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">BELUM LUNAS</span>
                            @endif
                        </div>
                    </div>

                    {{-- Progress bar --}}
                    @if($invoice->status !== 'LUNAS')
                        <div class="mb-3">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Terbayar: Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
                                <span>Sisa: Rp {{ number_format($sisa, 0, ',', '.') }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full transition-all" style="width: {{ $persen }}%"></div>
                            </div>
                            <div class="text-right text-xs text-gray-500 mt-0.5">{{ number_format($persen, 1) }}% terbayar</div>
                        </div>
                    @endif

                    {{-- Notes --}}
                    @if($invoice->notes)
                        <div class="mt-2 p-3 bg-gray-50 rounded-lg text-sm text-gray-600">
                            <i class="fas fa-sticky-note text-gray-400 mr-1"></i> {{ $invoice->notes }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-receipt text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Tagihan</h3>
                    <p class="text-gray-500">Tagihan uang kuliah belum diterbitkan oleh bagian keuangan.</p>
                </div>
            @endforelse
        </div>

        {{-- Info box --}}
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-start gap-3">
            <i class="fas fa-info-circle text-blue-500 mt-0.5 flex-shrink-0"></i>
            <p class="text-sm text-blue-700">
                Untuk informasi lebih lanjut tentang tagihan atau pembayaran, silakan hubungi bagian keuangan kampus.
            </p>
        </div>

    </div>
@endsection