@extends('layouts.parent')

@section('title', 'Pembayaran - Orang Tua')
@section('page-title', 'Pembayaran Uang Kuliah')

@push('styles')
    <style>
        .summary-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 36px -10px rgba(0, 0, 0, 0.1);
        }
        .invoice-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .invoice-card:hover {
            box-shadow: 0 12px 32px -8px rgba(0, 0, 0, 0.1);
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .progress-fill {
            transition: width 1.2s ease-out;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-8 animate-fade-in-up">

        {{-- Hero Header --}}
        <div class="relative overflow-hidden rounded-3xl bg-linear-to-br from-[#7a1621] via-[#9b1c2a] to-[#5a0015] shadow-2xl shadow-red-900/20 text-white">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-[0.07] rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-red-300 opacity-[0.08] rounded-full blur-2xl pointer-events-none translate-y-1/2 -translate-x-1/4"></div>

            <div class="relative p-6 md:p-8">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-2xl shadow-lg shrink-0">
                        <i class="fas fa-credit-card text-white/90"></i>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black tracking-tight">Pembayaran Uang Kuliah</h2>
                        <p class="text-red-200/80 text-sm mt-0.5">Informasi tagihan dan status pembayaran semester berjalan.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-5">
            {{-- Total Tagihan --}}
            <div class="summary-card relative bg-white dark:bg-[#1a1c23] rounded-2xl p-5 border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="absolute left-0 top-5 bottom-5 w-1 rounded-r-full bg-orange-500"></div>
                <div class="flex items-center gap-4 pl-3">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 flex items-center justify-center text-xl shadow-sm shrink-0">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Tagihan</div>
                        <div class="text-xl font-black text-gray-900 dark:text-white tracking-tight mt-0.5 tabular-nums">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            {{-- Total Terbayar --}}
            <div class="summary-card relative bg-white dark:bg-[#1a1c23] rounded-2xl p-5 border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="absolute left-0 top-5 bottom-5 w-1 rounded-r-full bg-green-500"></div>
                <div class="flex items-center gap-4 pl-3">
                    <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 flex items-center justify-center text-xl shadow-sm shrink-0">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Total Terbayar</div>
                        <div class="text-xl font-black text-green-700 dark:text-green-400 tracking-tight mt-0.5 tabular-nums">Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            {{-- Sisa Tagihan --}}
            <div class="summary-card relative bg-white dark:bg-[#1a1c23] rounded-2xl p-5 border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="absolute left-0 top-5 bottom-5 w-1 rounded-r-full {{ $sisaTagihan > 0 ? 'bg-red-500' : 'bg-green-500' }}"></div>
                <div class="flex items-center gap-4 pl-3">
                    <div class="w-12 h-12 rounded-xl {{ $sisaTagihan > 0 ? 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400' : 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400' }} flex items-center justify-center text-xl shadow-sm shrink-0">
                        <i class="fas fa-{{ $sisaTagihan > 0 ? 'exclamation-circle' : 'check-double' }}"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Sisa Tagihan</div>
                        <div class="text-xl font-black {{ $sisaTagihan > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }} tracking-tight mt-0.5 tabular-nums">
                            Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Invoice List --}}
        <div class="space-y-5">
            @forelse($invoices as $invoice)
                @php
                    $totalPaid = $invoice->total_paid ?? 0;
                    $sisa = $invoice->total_tagihan - $totalPaid;
                    $persen = $invoice->total_tagihan > 0 ? min(100, ($totalPaid / $invoice->total_tagihan) * 100) : 0;
                @endphp
                <div class="invoice-card bg-white dark:bg-[#1a1c23] rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6 overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-full h-1 {{ $invoice->status === 'LUNAS' ? 'bg-green-500' : ($invoice->status === 'PARTIAL' ? 'bg-yellow-500' : 'bg-red-500') }}"></div>
                    {{-- Top row --}}
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-11 h-11 rounded-xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 shrink-0">
                                <i class="fas fa-file-invoice-dollar text-lg"></i>
                            </div>
                            <div>
                                <div class="font-bold text-gray-900 dark:text-white text-base">
                                    Semester {{ $invoice->semester }}
                                    @if($invoice->tahun_ajaran)
                                        &bull; TA {{ $invoice->tahun_ajaran }}
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    @if($invoice->published_at)
                                        {{ $invoice->published_at->translatedFormat('d F Y') }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-1.5">
                            <div class="font-black text-gray-900 dark:text-white text-lg tabular-nums">
                                Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}
                            </div>
                            @if($invoice->status === 'LUNAS')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black tracking-wide bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> LUNAS
                                </span>
                            @elseif($invoice->status === 'PARTIAL')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black tracking-wide bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span> CICILAN
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black tracking-wide bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> BELUM LUNAS
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Progress bar --}}
                    @if($invoice->status !== 'LUNAS')
                        <div class="mt-5">
                            <div class="flex justify-between text-[11px] font-bold text-gray-500 dark:text-gray-400 mb-1.5">
                                <span>Terbayar: <span class="text-green-600 dark:text-green-400 tabular-nums">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span></span>
                                <span>Sisa: <span class="text-red-600 dark:text-red-400 tabular-nums">Rp {{ number_format($sisa, 0, ',', '.') }}</span></span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2.5 overflow-hidden shadow-inner">
                                <div class="progress-fill bg-linear-to-r from-green-500 to-emerald-400 h-2.5 rounded-full" style="width: {{ $persen }}%"></div>
                            </div>
                            <div class="text-right text-[10px] font-bold text-gray-400 dark:text-gray-500 mt-1">{{ number_format($persen, 1) }}% terbayar</div>
                        </div>
                    @endif

                    {{-- Notes --}}
                    @if($invoice->notes)
                        <div class="mt-4 p-3.5 bg-gray-50 dark:bg-white/2 rounded-xl border border-gray-100 dark:border-gray-800 text-sm text-gray-600 dark:text-gray-400 flex items-start gap-2">
                            <i class="fas fa-sticky-note text-gray-400 mt-0.5 shrink-0"></i>
                            <span>{{ $invoice->notes }}</span>
                        </div>
                    @endif

                    {{-- VA Payment Info (Hidden - set to true to enable) --}}
                    @if(false)
                    @if($invoice->bank_name && $invoice->va_number)
                        <div class="mt-4 p-5 bg-linear-to-br from-blue-50 to-indigo-50 dark:from-blue-900/10 dark:to-indigo-900/10 border border-blue-200 dark:border-blue-800 rounded-xl" x-data="{ copied: false }">
                            <div class="flex items-center gap-2 mb-3">
                                <i class="fas fa-university text-blue-600 dark:text-blue-400"></i>
                                <span class="text-sm font-bold text-slate-800 dark:text-white">Informasi Pembayaran Virtual Account</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="bg-white dark:bg-[#1a1c23] p-3 rounded-lg border border-blue-100 dark:border-blue-800">
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-blue-500 mb-0.5">Bank</p>
                                    <p class="text-base font-bold text-slate-800 dark:text-white">{{ $invoice->bank_name }}</p>
                                </div>
                                <div class="bg-white dark:bg-[#1a1c23] p-3 rounded-lg border border-blue-100 dark:border-blue-800">
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-blue-500 mb-0.5">Nomor VA</p>
                                    <div class="flex items-center gap-2">
                                        <p class="text-base font-bold text-slate-800 dark:text-white font-mono tracking-wider">{{ $invoice->va_number }}</p>
                                        <button type="button"
                                                @click="navigator.clipboard.writeText('{{ $invoice->va_number }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                                class="ml-auto px-2 py-1 rounded-md text-[10px] font-bold transition-all active:scale-90"
                                                :class="copied ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-100 text-blue-600 hover:bg-blue-200'">
                                            <span x-show="!copied"><i class="fas fa-copy"></i> Salin</span>
                                            <span x-show="copied" x-cloak><i class="fas fa-check"></i> Tersalin!</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($invoice->status !== 'LUNAS')
                        <div class="mt-4 p-3.5 bg-gray-50 dark:bg-white/2 rounded-xl border border-gray-100 dark:border-gray-800 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-info-circle text-gray-400 mt-0.5 shrink-0"></i>
                            <span>Hubungi bagian keuangan kampus untuk informasi metode pembayaran.</span>
                        </div>
                    @endif
                    @endif
                </div>
            @empty
                <div class="bg-white dark:bg-[#1a1c23] rounded-3xl border border-gray-100 dark:border-gray-800 shadow-sm p-12 md:p-16 text-center">
                    <div class="w-20 h-20 bg-gray-50 dark:bg-gray-800 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-inner">
                        <i class="fas fa-receipt text-gray-300 dark:text-gray-600 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-1">Belum Ada Tagihan</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto">Tagihan uang kuliah belum diterbitkan oleh bagian keuangan.</p>
                </div>
            @endforelse
        </div>

        {{-- Info box --}}
        <div class="bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800 rounded-2xl p-5 flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                <i class="fas fa-info-circle text-sm"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-blue-800 dark:text-blue-300 mb-0.5">Butuh Bantuan?</h4>
                <p class="text-sm text-blue-700 dark:text-blue-400">
                    Untuk informasi lebih lanjut tentang tagihan atau pembayaran, silakan hubungi bagian keuangan kampus.
                </p>
            </div>
        </div>

    </div>
@endsection