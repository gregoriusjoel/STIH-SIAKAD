@extends('layouts.mahasiswa')

@section('content')
@php
    $renderTime = defined('LARAVEL_START') ? round(microtime(true) - LARAVEL_START, 3) : '0.02';

    // Combine and sort data
    $unifiedData = collect();
    
    foreach($invoices as $invoice) {
        $unifiedData->push([
            'type' => 'new',
            'id' => $invoice->id,
            'semester' => $invoice->semester,
            'tahun_ajaran' => $invoice->tahun_ajaran,
            'total_tagihan' => $invoice->total_tagihan,
            'total_bayar' => $invoice->total_paid,
            'sks_bayar' => $invoice->paket_sks_bayar ?? '-',
            'sks_ambil' => $invoice->sks_ambil ?? '-',
            'status' => $invoice->status,
            'payments' => $invoice->payments()->with('installment')->orderBy('paid_date')->get(),
            'raw' => $invoice
        ]);
    }

    foreach($existingPayments as $pay) {
        preg_match('/\d+/', $pay->semester->nama_semester ?? '', $matches);
        $semNum = $matches[0] ?? 0;

        $unifiedData->push([
            'type' => 'legacy',
            'id' => $pay->id,
            'semester' => $semNum,
            'tahun_ajaran' => $pay->semester->tahun_ajaran ?? '',
            'total_tagihan' => $pay->jumlah,
            'total_bayar' => $pay->dibayar,
            'sks_bayar' => '-',
            'sks_ambil' => '-',
            'status' => $pay->status === 'lunas' ? 'LUNAS' : strtoupper($pay->status),
            'tanggal_bayar' => $pay->tanggal_bayar,
            'raw' => $pay
        ]);
    }

    $unifiedData = $unifiedData->sortByDesc('semester');

    // Calculate max payments to determine columns
    $maxPayments = $unifiedData->map(function($item) {
        if($item['type'] === 'new') {
            return $item['payments']->count();
        }
        return $item['total_bayar'] > 0 ? 1 : 0;
    })->max();

    $displayCols = max(1, $maxPayments);
@endphp

<div class="w-full">
    <div class="w-full">
        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800">Pembayaran Kuliah</h1>
            <p class="text-xs text-slate-400 mt-1 italic">takes {{ $renderTime }} seconds to display the data on this page</p>
        </div>

        @if(session('success'))
            <div class="mb-6 px-4 py-3 bg-green-100 border border-green-200 text-green-700 rounded-md text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        {{-- Payment Status Banner --}}
        {{-- Payment Status Banner --}}
        @if(isset($upcomingPayment) && $upcomingPayment)
             @php
                $daysLeft = now()->diffInDays($upcomingPayment->due_date, false);
                $daysText = $daysLeft == 0 ? 'Hari ini' : ($daysLeft == 1 ? 'Besok' : $daysLeft . ' hari lagi');
             @endphp
            <div class="mb-8 p-4 bg-amber-50 border border-amber-200 rounded-lg flex flex-col sm:flex-row items-center justify-center gap-3 text-center sm:text-left">
                <i class="fas fa-exclamation-triangle text-amber-600 text-xl animate-pulse"></i>
                <div>
                    <span class="text-amber-800 font-bold uppercase tracking-wide text-sm block">Pembayaran Selanjutnya Segera Jatuh Tempo</span>
                    <span class="text-amber-700 text-xs mt-1 block font-medium">
                        Cicilan ke-{{ $upcomingPayment->installment_no }} (Rp {{ number_format($upcomingPayment->amount, 0, ',', '.') }}) 
                        jatuh tempo pada {{ \Carbon\Carbon::parse($upcomingPayment->due_date)->translatedFormat('d F Y') }} 
                        <span class="font-bold underline">({{ $daysText }})</span>
                    </span>
                </div>
                <a href="{{ route('mahasiswa.invoices.show', $upcomingPayment->invoice_id) }}" class="mt-2 sm:mt-0 sm:ml-4 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold uppercase rounded shadow-sm transition-colors">
                    Bayar Sekarang
                </a>
            </div>
        @else
            <div class="mb-8 p-4 bg-blue-50 border border-blue-100 rounded-lg flex items-center justify-center gap-2">
                <i class="fas fa-check-circle text-blue-600"></i>
                <span class="text-blue-700 font-bold uppercase tracking-wide text-sm">Status Pembayaran Kuliah Lancar</span>
            </div>
        @endif

        {{-- Main Table Container --}}
        <div class="bg-white rounded-xl shadow-lg shadow-slate-200/50 overflow-hidden border border-slate-200">
            <div class="overflow-x-auto">
                <table class="w-full min-w-max text-left border-collapse">
                    <thead>
                        <tr class="bg-[#8B1538] text-white">
                            <th rowspan="2" class="px-4 py-4 text-xs font-bold uppercase tracking-wider text-center border-r border-white/50 relative md:sticky md:left-0 z-20 bg-[#8B1538] align-middle">Sem</th>
                            <th rowspan="2" class="px-4 py-4 text-xs font-bold uppercase tracking-wider text-center border-r border-white/50 relative md:sticky md:left-[45px] z-20 bg-[#8B1538] min-w-[120px] align-middle whitespace-nowrap">Total Tagihan</th>
                            <th rowspan="2" class="px-4 py-4 text-xs font-bold uppercase tracking-wider text-center border-r border-slate-200 relative md:sticky md:left-[165px] z-20 bg-[#8B1538] shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] min-w-[120px] align-middle whitespace-nowrap">Total Pembayaran</th>
                            <th colspan="{{ $displayCols * 2 }}" class="px-3 py-2 text-xs font-bold uppercase tracking-wider text-center border-b border-white/50 border-r border-white/50 align-middle">Pembayaran</th>
                            <th rowspan="2" class="px-3 py-4 text-xs font-bold uppercase tracking-wider text-center border-l border-slate-200 relative md:sticky md:right-[200px] z-20 bg-[#8B1538] shadow-[-2px_0_5px_-2px_rgba(0,0,0,0.1)] leading-tight align-middle whitespace-nowrap">Cicilan Ke</th>
                            <th rowspan="2" class="px-3 py-4 text-xs font-bold uppercase tracking-wider text-center border-r border-white/50 relative md:sticky md:right-[100px] z-20 bg-[#8B1538] leading-tight align-middle whitespace-nowrap">Total Cicilan</th>
                            <th rowspan="2" class="px-3 py-4 text-xs font-bold uppercase tracking-wider text-center relative md:sticky md:right-0 z-20 bg-[#8B1538] align-middle">Ket</th>
                        </tr>
                        <tr class="bg-[#8B1538] text-white">
                            {{-- Payment sub-headers --}}
                            @for($i = 1; $i <= $displayCols; $i++)
                                <th class="px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-center border-r border-white/50 align-middle">Tanggal {{ $i }}</th>
                                <th class="px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-center border-r border-white/50 align-middle">Bayar {{ $i }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($unifiedData as $item)
                        <tr class="hover:bg-slate-50 transition-colors group cursor-pointer border-b border-slate-100"
                            onclick="window.location='{{ $item['type'] === 'new' ? route('mahasiswa.invoices.show', $item['id']) : '#' }}'">
                            <td class="px-4 py-4 text-center font-bold text-slate-700 border-r border-slate-100 relative md:sticky md:left-0 z-10 bg-white group-hover:bg-slate-50">
                                {{ $item['semester'] }}
                            </td>
                            <td class="px-4 py-4 text-right font-medium text-slate-700 border-r border-slate-100 relative md:sticky md:left-[45px] z-10 bg-white group-hover:bg-slate-50 min-w-[120px]">
                                {{ number_format($item['total_tagihan'], 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-right font-medium text-slate-700 border-r border-slate-200 relative md:sticky md:left-[165px] z-10 bg-white group-hover:bg-slate-50 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)] min-w-[120px]">
                                {{ number_format($item['total_bayar'], 0, ',', '.') }}
                            </td>
                            
                            {{-- Payments Detail --}}
                            @for($i = 0; $i < $displayCols; $i++)
                                @php
                                    $p_date = '-';
                                    $p_amount = '0';

                                    if($item['type'] === 'new') {
                                        $p = $item['payments']->get($i);
                                        if($p) {
                                            $p_date = $p->paid_date ? $p->paid_date->format('d-m-Y') : '-';
                                            $p_amount = number_format($p->amount_approved, 0, ',', '.');
                                        }
                                    } elseif($i === 0 && $item['total_bayar'] > 0) { // Support legacy payment in the first slot
                                        $p_date = $item['tanggal_bayar'] ? $item['tanggal_bayar']->format('d-m-Y') : '-';
                                        $p_amount = number_format($item['total_bayar'], 0, ',', '.');
                                    }
                                @endphp
                                <td class="px-3 py-4 text-center text-slate-600 border-r border-slate-100 min-w-[100px]">{{ $p_date }}</td>
                                <td class="px-3 py-4 text-right text-slate-600 border-r border-slate-100 min-w-[110px]">{{ $p_amount !== '0' ? $p_amount : '-' }}</td>
                            @endfor

                            <td class="px-3 py-4 text-center font-bold text-slate-700 border-l border-slate-200 relative md:sticky md:right-[200px] z-10 bg-white group-hover:bg-slate-50 shadow-[-2px_0_5px_-2px_rgba(0,0,0,0.05)] w-[100px]">
                                {{-- Cicilan Ke (Count of payments made) --}}
                                @if($item['type'] === 'new')
                                    {{ $item['payments']->count() }}
                                @else
                                    {{ $item['total_bayar'] > 0 ? 1 : 0 }}
                                @endif
                            </td>
                            <td class="px-3 py-4 text-center font-bold text-slate-700 border-r border-slate-100 relative md:sticky md:right-[100px] z-10 bg-white group-hover:bg-slate-50 w-[100px]">
                                {{-- Total Cicilan (Plan) --}}
                                @if($item['type'] === 'new')
                                    {{ $item['raw']->installmentRequest->approved_terms ?? ($item['status'] === 'LUNAS' ? 1 : '-') }}
                                @else
                                    {{ $item['status'] === 'LUNAS' ? 1 : '-' }}
                                @endif
                            </td>
                            <td class="px-3 py-4 text-center relative md:sticky md:right-0 z-10 bg-white group-hover:bg-slate-50 w-[100px]">
                                <span class="px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider
                                    {{ $item['status'] === 'LUNAS' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                    {{ $item['status'] === 'LUNAS' ? 'Lunas' : ($item['status'] === 'PUBLISHED' ? 'Tagihan' : 'Cicilan') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ 6 + ($displayCols * 2) }}" class="px-6 py-20 text-center">
                                <i class="fas fa-file-invoice-dollar text-5xl text-slate-200 mb-4 block"></i>
                                <p class="text-slate-400 font-medium italic">Belum ada data pembayaran</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Call to Action: Unpaid / New Invoices --}}
        @php
            $unpaidInvoices = collect($unifiedData)->where('status', 'PUBLISHED');
        @endphp

        @if($unpaidInvoices->isNotEmpty())
            <div class="mt-8 p-6 bg-gradient-to-br from-[#8B1538] to-[#6b102b] rounded-xl text-white shadow-lg shadow-red-900/20">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <h3 class="text-lg font-bold uppercase tracking-wide mb-2">Ada Tagihan Belum Terbayar</h3>
                        <p class="text-white/80 text-sm max-w-xl">
                            Segera lakukan pembayaran atau ajukan cicilan untuk semester terbaru Anda agar proses pengisian KRS berjalan lancar.
                        </p>
                    </div>
                    <a href="{{ route('mahasiswa.invoices.show', $unpaidInvoices->first()['id']) }}" 
                       class="px-6 py-3 bg-white text-[#8B1538] rounded-lg font-bold uppercase tracking-wide text-xs hover:bg-slate-50 transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0">
                        Bayar / Ajukan Cicilan
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    /* Custom scrollbar for horizontal table */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection
