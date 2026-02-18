@extends('layouts.finance')

@section('page-title', 'Manajemen Tagihan')

@section('content')
@php
    $filterOptions = [
        ['status' => null, 'label' => 'Semua Status'],
        ['status' => 'DRAFT', 'label' => 'Draft'],
        ['status' => 'PUBLISHED', 'label' => 'Published'],
        ['status' => 'IN_INSTALLMENT', 'label' => 'Cicilan'],
        ['status' => 'LUNAS', 'label' => 'Lunas'],
    ];
@endphp
<div class="space-y-8 animate-fade-in px-4 md:px-0">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen Tagihan</h1>
            <p class="text-sm text-slate-500 font-medium">Kelola dan pantau status tagihan mahasiswa STIH Adhyaksa.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('finance.invoices.create') }}" 
               class="flex items-center gap-2 px-5 py-2.5 bg-[#8B1538] hover:bg-[#6D1029] text-white rounded-xl font-bold text-sm transition-all shadow-md shadow-red-900/10 hover:scale-105 active:scale-95">
                <i class="fas fa-plus"></i>
                <span>Buat Tagihan Baru</span>
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $statsItems = [
                ['label' => 'Total Tagihan', 'value' => $stats['total'], 'icon' => 'fa-file-invoice', 'color' => 'slate'],
                ['label' => 'Published', 'value' => $stats['published'], 'icon' => 'fa-paper-plane', 'color' => 'blue'],
                ['label' => 'Dalam Cicilan', 'value' => $stats['in_installment'], 'icon' => 'fa-clock', 'color' => 'amber'],
                ['label' => 'Total Lunas', 'value' => $stats['paid'], 'icon' => 'fa-check-circle', 'color' => 'green'],
            ];
        @endphp

        @foreach($statsItems as $item)
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all group overflow-hidden relative">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-slate-50 rounded-full group-hover:scale-110 transition-transform duration-500 opacity-50"></div>
                <div class="relative z-10 flex items-center gap-4">
                    <div class="size-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-[#8B1538] group-hover:text-white transition-all duration-300">
                        <i class="fas {{ $item['icon'] }} text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-0.5">{{ $item['label'] }}</p>
                        <h3 class="text-2xl font-black text-slate-800 leading-none">{{ number_format($item['value']) }}</h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Main Content Card --}}
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        {{-- Filters & Search --}}
        <div class="p-6 border-b border-slate-50">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                {{-- Status Dropdown Filter --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center gap-3 px-5 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-xl font-bold text-sm transition-all border border-slate-200/50">
                        <i class="fas fa-filter text-[#8B1538] text-xs"></i>
                        @php
                            $selectedLabel = 'Semua Status';
                            foreach($filterOptions as $opt) {
                                if (request('status') == $opt['status']) {
                                    $selectedLabel = $opt['label'];
                                    break;
                                }
                            }
                        @endphp
                        <span>{{ $selectedLabel }}</span>
                        <i class="fas fa-chevron-down text-[10px] transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute left-0 mt-2 w-56 bg-white rounded-2xl border border-slate-100 shadow-xl z-50 py-2">
                        @foreach($filterOptions as $opt)
                            <a href="{{ route('finance.invoices.index', ['status' => $opt['status']]) }}" 
                               class="flex items-center justify-between px-4 py-2.5 text-sm font-bold {{ request('status') == $opt['status'] ? 'text-[#8B1538] bg-[#8B1538]/5' : 'text-slate-600 hover:bg-slate-50' }} transition-colors">
                                <span>{{ $opt['label'] }}</span>
                                @if(request('status') == $opt['status'])
                                    <i class="fas fa-check text-[10px]"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="overflow-x-auto overflow-y-hidden custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50">ID</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50">Mahasiswa</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50">Semester</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50 text-right">Total Tagihan</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50 text-center">Status</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 border-b border-slate-50 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($invoices as $invoice)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <span class="text-sm font-bold text-slate-400">#{{ $invoice->id }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-slate-500 font-bold shrink-0">
                                        {{ substr($invoice->student->user->name ?? 'N', 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-800 truncate">{{ $invoice->student->user->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-slate-400 font-medium tracking-tight">{{ $invoice->student->nim ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="inline-flex flex-col">
                                    <span class="text-sm font-bold text-slate-700">Semester {{ $invoice->semester }}</span>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $invoice->tahun_ajaran }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <span class="text-sm font-black text-slate-900 tracking-tight">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @php
                                    $statusClasses = [
                                        'DRAFT' => 'bg-slate-100 text-slate-500 border-slate-200',
                                        'PUBLISHED' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'IN_INSTALLMENT' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'LUNAS' => 'bg-green-50 text-green-600 border-green-100',
                                        'CANCELLED' => 'bg-red-50 text-red-600 border-red-100',
                                    ];
                                    $statusLabels = [
                                        'DRAFT' => 'Draft',
                                        'PUBLISHED' => 'Published',
                                        'IN_INSTALLMENT' => 'Cicilan',
                                        'LUNAS' => 'Lunas',
                                        'CANCELLED' => 'Batal',
                                    ];
                                    $class = $statusClasses[$invoice->status] ?? $statusClasses['DRAFT'];
                                    $label = $statusLabels[$invoice->status] ?? 'Unknown';
                                @endphp
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border {{ $class }}">
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <a href="{{ route('finance.invoices.show', $invoice) }}" 
                                   class="inline-flex size-9 items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-[#8B1538] hover:text-white transition-all transform hover:scale-105 active:scale-95 group/btn">
                                    <i class="fas fa-arrow-right text-sm"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="size-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-200">
                                        <i class="fas fa-file-invoice text-4xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-400">Tidak ada tagihan ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($invoices->hasPages())
            <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/30">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .custom-scrollbar::-webkit-scrollbar { height: 4px; width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>
@endsection
