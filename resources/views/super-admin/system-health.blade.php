@extends('layouts.super-admin')

@section('title', 'System Health')
@section('page-title', 'System Health')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-[#7a1621] font-bold">favorite</span>
                System Health Monitor
            </h2>
            <p class="text-sm text-slate-500 mt-0.5">Pantau status koneksi basis data, penggunaan media penyimpanan, serta antrean sistem</p>
        </div>
    </div>

    {{-- Health Metrics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($health as $service => $status)
        @php
            $isHealthy = $status['status'] === 'healthy';
            $isWarning = $status['status'] === 'warning';
            $bgColor = $isHealthy ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : ($isWarning ? 'bg-amber-100 text-amber-700 border-amber-200' : 'bg-rose-100 text-rose-700 border-rose-200');
            $iconColor = $isHealthy ? 'text-emerald-500' : ($isWarning ? 'text-amber-500' : 'text-rose-500');
            $icon = $isHealthy ? 'check_circle' : ($isWarning ? 'warning' : 'cancel');
        @endphp
        <div class="glass-card p-6 flex flex-col justify-between border-l-4 border-l-{{ $isHealthy ? 'emerald-500' : ($isWarning ? 'amber-500' : 'rose-500') }}">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-3xl {{ $iconColor }}">{{ $icon }}</span>
                    <div>
                        <h3 class="font-bold text-slate-800 text-base capitalize">{{ $service === 'database' ? 'Basis Data (MySQL)' : ($service === 'cache' ? 'Sistem Cache' : ($service === 'storage' ? 'Media Penyimpanan' : 'Antrean Tugas (Queue)')) }}</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Service: {{ $service }}</p>
                    </div>
                </div>
                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold border uppercase {{ $bgColor }}">
                    {{ $status['status'] }}
                </span>
            </div>

            <div class="mt-6 space-y-3">
                @if($service === 'storage')
                    {{-- Storage visual indicator --}}
                    <div>
                        <div class="flex items-center justify-between text-xs text-slate-600 mb-1">
                            <span>Kapasitas Digunakan: <strong class="text-slate-800">{{ $status['used_pct'] }}%</strong></span>
                            <span>{{ $status['free_gb'] }} GB Tersedia dari {{ $status['total_gb'] }} GB</span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $status['used_pct'] > 90 ? 'bg-rose-500' : ($status['used_pct'] > 75 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                                style="width: {{ $status['used_pct'] }}%"></div>
                        </div>
                    </div>
                @elseif($service === 'queue')
                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <span>Pekerjaan Gagal (Failed Jobs):</span>
                        <span class="font-bold {{ $status['failed_jobs'] > 0 ? 'text-amber-600' : 'text-slate-800' }}">
                            {{ $status['failed_jobs'] }} tugas
                        </span>
                    </div>
                @else
                    <p class="text-xs text-slate-650 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                        {{ $status['message'] ?? 'Service berjalan normal dan merespon dalam batas waktu yang ditentukan.' }}
                    </p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
