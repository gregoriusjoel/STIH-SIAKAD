@extends('layouts.admin')
@section('title', 'Manajemen Wisuda')
@section('page-title', 'Manajemen Wisuda')

@section('content')
<div class="space-y-6">

    {{-- Premium Header --}}
    <div class="relative bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm overflow-hidden group">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-red-900/[0.03] rounded-full blur-3xl transition-all duration-700 group-hover:scale-110"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-amber-500/[0.02] rounded-full blur-3xl transition-all duration-700 group-hover:scale-110"></div>

        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-red-900 via-[#800020] to-[#5a0015] flex items-center justify-center shadow-lg shadow-red-900/20 shrink-0">
                    <span class="material-symbols-outlined text-white text-3xl font-light">school</span>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight leading-none mb-2">Pendaftaran Wisuda</h1>
                    <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">Verifikasi Dokumen Kelengkapan & Penjadwalan Wisuda</p>
                </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.wisuda.batches') }}"
                    class="inline-flex items-center justify-center gap-2 h-11 px-5 bg-gradient-to-r from-red-900 to-red-950 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-800 shadow-md hover:shadow-lg transition-all">
                    <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                    Kelola Batch Wisuda
                </a>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-emerald-600 mt-0.5">check_circle</span>
            <div>
                <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-red-600 mt-0.5">error</span>
            <div>
                <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Content Section --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        {{-- Custom Tabs --}}
        <div class="flex border-b border-gray-50 px-6 sm:px-8 bg-gray-50/50 backdrop-blur-sm overflow-x-auto">
            @foreach([
                ['tab'=>'pending',   'label'=>'Menunggu Verifikasi', 'count'=>$counts['pending'],   'icon' => 'pending_actions', 'color' => 'yellow'],
                ['tab'=>'approved',  'label'=>'Disetujui',           'count'=>$counts['approved'],  'icon' => 'verified',        'color' => 'green'],
                ['tab'=>'scheduled', 'label'=>'Terjadwal',          'count'=>$counts['scheduled'], 'icon' => 'event_available',  'color' => 'indigo'],
                ['tab'=>'rejected',  'label'=>'Ditolak',            'count'=>$counts['rejected'],  'icon' => 'cancel',          'color' => 'red'],
                ['tab'=>'all',       'label'=>'Semua',              'count'=>array_sum($counts),   'icon' => 'list',            'color' => 'gray'],
            ] as $t)
            <a href="?status={{ $t['tab'] }}"
                class="group flex items-center gap-2.5 px-6 py-5 text-sm font-black whitespace-nowrap transition-all relative
                    {{ $status === $t['tab'] ? 'text-red-900' : 'text-gray-400 hover:text-gray-700' }}">
                <span class="material-symbols-outlined text-[20px] group-hover:scale-110 transition-transform {{ $status === $t['tab'] ? 'fill-0' : 'font-light' }} text-{{ $status === $t['tab'] ? 'red-900' : 'gray-400' }}">{{ $t['icon'] }}</span>
                <span class="uppercase tracking-widest text-[11px]">{{ $t['label'] }}</span>
                @if($t['count'] > 0)
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ $status === $t['tab'] ? 'bg-red-900 text-white shadow-lg shadow-red-900/20' : 'bg-gray-100 text-gray-400 font-bold' }}">
                    {{ $t['count'] }}
                </span>
                @endif
                @if($status === $t['tab'])
                <div class="absolute bottom-0 left-6 right-6 h-0.5 bg-red-900 rounded-full shadow-[0_-2px_6px_rgba(153,27,27,0.4)]"></div>
                @endif
            </a>
            @endforeach
        </div>

        <div class="p-6 sm:p-8 min-h-[400px] bg-white">
            <div class="grid gap-4">
                @forelse($registrations as $reg)
                    @php
                        $badgeColor = $reg->status->color();
                        $student = $reg->mahasiswa;
                    @endphp
                    <div class="bg-white border border-gray-100 rounded-2xl p-4 sm:p-5 hover:border-red-100 hover:shadow-md hover:shadow-red-900/5 transition-all group flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-red-50 group-hover:text-red-900 transition-colors shrink-0 overflow-hidden relative border border-gray-100 group-hover:border-red-100">
                                <span class="material-symbols-outlined text-2xl font-light">account_circle</span>
                                @if($student && $student->foto)
                                    <img src="{{ $student->foto_url }}" class="absolute inset-0 w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1 leading-none">
                                    <h3 class="font-black text-gray-900 tracking-tight group-hover:text-red-900 transition-colors">
                                        {{ $student->nama ?? 'Mahasiswa' }}
                                    </h3>
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest truncate">
                                        {{ $student->nim ?? '-' }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 font-medium truncate italic leading-snug">
                                    "{{ $reg->skripsiSubmission->judul ?? 'Judul Skripsi' }}"
                                </p>
                                <div class="flex flex-wrap items-center gap-3 mt-2">
                                    <div class="flex items-center gap-1 px-2 py-0.5 bg-gray-50 rounded-lg border border-gray-100">
                                        <span class="material-symbols-outlined text-[13px] text-gray-400">phone</span>
                                        <span class="text-[9px] font-bold text-gray-500 uppercase tracking-tighter">{{ $reg->no_hp ?? '-' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 px-2 py-0.5 bg-gray-50 rounded-lg border border-gray-100">
                                        <span class="material-symbols-outlined text-[13px] text-gray-400">mail</span>
                                        <span class="text-[9px] font-bold text-gray-500 tracking-tighter">{{ $reg->email_aktif ?? '-' }}</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-{{ $badgeColor }}-50 text-{{ $badgeColor }}-700 border border-{{ $badgeColor }}-100/50">
                                        {{ $reg->status->label() }}
                                    </span>
                                    @if($reg->batch)
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-indigo-50 text-indigo-700 border border-indigo-100/50">
                                            {{ $reg->batch->nama_batch }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('admin.wisuda.show', $reg->id) }}"
                            class="shrink-0 flex items-center justify-center gap-2 h-10 px-6 bg-red-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-800 shadow-lg shadow-red-900/10 hover:-translate-y-0.5 transition-all">
                            Verifikasi
                            <span class="material-symbols-outlined text-sm">verified</span>
                        </a>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-20 text-center group">
                        <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4 text-gray-200 group-hover:scale-110 group-hover:text-red-900/20 transition-all duration-500">
                            <span class="material-symbols-outlined text-3xl font-light">school</span>
                        </div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tidak ada pendaftaran wisuda ditemukan.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $registrations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
