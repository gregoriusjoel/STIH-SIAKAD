@extends('layouts.mahasiswa')

@section('content')
<div class="container mx-auto px-4 py-4 max-w-none">
    <div class="space-y-6">
        {{-- Header & Back Button --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('mahasiswa.pembayaran.index') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-[#8B1538] transition-colors group">
                <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Tagihan
            </a>
            
            <span class="px-4 py-1.5 text-xs font-bold uppercase tracking-wider rounded-full shadow-sm
                {{ $invoice->status === 'LUNAS' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : '' }}
                {{ $invoice->status === 'PUBLISHED' ? 'bg-blue-100 text-blue-700 border border-blue-200' : '' }}
                {{ $invoice->status === 'IN_INSTALLMENT' ? 'bg-amber-100 text-amber-700 border border-amber-200' : '' }}">
                {{ $invoice->status_label }}
            </span>
        </div>

        {{-- Invoice Header Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-[#8B1538] to-[#6A102B] px-8 py-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                </div>
                <h1 class="text-3xl font-bold tracking-tight mb-2">Semester {{ $invoice->semester }} - {{ $invoice->tahun_ajaran }}</h1>
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 text-white/90 text-sm">
                    <p class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        {{ $invoice->student->user->name ?? 'N/A' }} ({{ $invoice->student->nim ?? 'N/A' }})
                    </p>
                    @if($invoice->sks_ambil)
                        <span class="hidden sm:inline text-white/50">•</span>
                        <p>SKS Ambil: <span class="font-semibold">{{ $invoice->sks_ambil }}</span></p>
                        @if($invoice->paket_sks_bayar)
                            <span class="hidden sm:inline text-white/50">•</span>
                            <p>Paket Bayar: <span class="font-semibold">{{ $invoice->paket_sks_bayar }}</span></p>
                        @endif
                    @endif
                </div>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-slate-50 p-5 rounded-xl border border-slate-100">
                        <p class="text-slate-500 text-sm font-medium mb-1">Total Tagihan</p>
                        <p class="text-2xl font-bold text-slate-800">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</p>
                    </div>
                    @if($invoice->status === 'IN_INSTALLMENT' || $invoice->status === 'LUNAS')
                        <div class="bg-emerald-50 p-5 rounded-xl border border-emerald-100">
                            <p class="text-emerald-600 text-sm font-medium mb-1">Total Terbayar</p>
                            <p class="text-2xl font-bold text-emerald-700">Rp {{ number_format($invoice->total_paid, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-red-50 p-5 rounded-xl border border-red-100">
                            <p class="text-red-600 text-sm font-medium mb-1">Sisa Tagihan</p>
                            <p class="text-2xl font-bold text-red-700">Rp {{ number_format($invoice->total_tagihan - $invoice->total_paid, 0, ',', '.') }}</p>
                        </div>
                    @else
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-100 md:col-span-2 flex items-center justify-center text-slate-400 text-sm italic">
                            Belum ada pembayaran
                        </div>
                    @endif
                </div>

                @if($invoice->notes)
                    <div class="mt-6 p-4 bg-amber-50 border border-amber-100 rounded-lg flex gap-3">
                        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-amber-800">
                            <span class="font-semibold">Catatan:</span> {{ $invoice->notes }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Installment Request Status --}}
        @if($invoice->installmentRequest)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-slate-800">Status Pengajuan Cicilan</h2>
                    <span class="px-3 py-1 text-xs font-bold rounded-full uppercase tracking-wide
                        {{ $invoice->installmentRequest->status === 'SUBMITTED' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $invoice->installmentRequest->status === 'APPROVED' ? 'bg-emerald-100 text-emerald-700' : '' }}
                        {{ $invoice->installmentRequest->status === 'REJECTED' ? 'bg-red-100 text-red-700' : '' }}">
                        {{ $invoice->installmentRequest->status_label }}
                    </span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-sm text-slate-500 mb-1">Rencana Cicilan</p>
                            <div class="flex items-center gap-2">
                                <span class="text-xl font-bold text-slate-800">{{ $invoice->installmentRequest->requested_terms }}x</span>
                                <span class="text-sm text-slate-400">Diminta</span>
                                @if($invoice->installmentRequest->approved_terms)
                                    <span class="mx-2 text-slate-300">|</span>
                                    <span class="text-xl font-bold text-emerald-600">{{ $invoice->installmentRequest->approved_terms }}x</span>
                                    <span class="text-sm text-emerald-600">Disetujui</span>
                                @endif
                            </div>
                        </div>
                        @if($invoice->installmentRequest->rejection_reason)
                            <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                                <p class="text-sm font-medium text-red-800 mb-1">Alasan Penolakan</p>
                                <p class="text-sm text-red-600">{{ $invoice->installmentRequest->rejection_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Installments Table --}}
        @if($invoice->installments->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-800">Daftar Cicilan</h2>
                    <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-1 rounded-md">
                        {{ $invoice->installments->count() }} Pembayaran
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center w-16">No</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">Nominal</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Jatuh Tempo</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal Bayar</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($invoice->installments as $installment)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-center font-bold text-slate-600">
                                        {{ $installment->installment_no }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-slate-800">
                                        Rp {{ number_format($installment->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ $installment->due_date ? $installment->due_date->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                            {{ $installment->status === 'PAID' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                            {{ $installment->status === 'UNPAID' ? 'bg-slate-100 text-slate-600' : '' }}
                                            {{ $installment->status === 'WAITING_VERIFICATION' ? 'bg-amber-100 text-amber-700' : '' }}
                                            {{ $installment->status === 'REJECTED_PAYMENT' ? 'bg-red-100 text-red-700' : '' }}">
                                            @if($installment->status === 'PAID')
                                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            @endif
                                            {{ $installment->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ $installment->paid_at ? $installment->paid_at->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($installment->status === 'UNPAID' || $installment->status === 'REJECTED_PAYMENT')
                                            @if($installment->canBePaid())
                                                <a href="{{ route('mahasiswa.payment-proofs.create', $installment) }}" class="inline-flex items-center px-3 py-1.5 bg-[#8B1538] text-white text-xs font-bold rounded-lg hover:bg-[#6A102B] transition-colors shadow-sm hover:shadow">
                                                    Upload Bukti
                                                </a>
                                            @else
                                                <span class="text-xs text-slate-400 italic">Bayar cicilan sebelumnya</span>
                                            @endif
                                        @elseif($installment->status === 'WAITING_VERIFICATION')
                                            <span class="text-xs font-medium text-amber-600">Verifikasi...</span>
                                        @elseif($installment->status === 'PAID')
                                            <span class="text-xs font-bold text-emerald-600">Lunas</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Action Buttons --}}
        @if($invoice->status === 'PUBLISHED' && !$invoice->installmentRequest)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 text-center">
                <h2 class="text-lg font-bold text-slate-800 mb-2">Belum ada pengajuan cicilan</h2>
                <p class="text-slate-500 mb-6 max-w-lg mx-auto">Anda dapat mengajukan pembayaran bertahap (cicilan) untuk tagihan ini. Silakan klik tombol di bawah untuk memulai pengajuan.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('mahasiswa.installment-requests.create', $invoice) }}" class="inline-flex items-center justify-center px-6 py-3 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition-colors shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Ajukan Cicilan
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
