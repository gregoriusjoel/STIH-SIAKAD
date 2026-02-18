@extends('layouts.app')

@section('title', 'Menunggu Approval Jadwal')

@section('navbar_breadcrumb')
    <nav class="flex items-center gap-3 text-sm text-white/60 font-bold tracking-tight">
        <a class="hover:text-white transition-all duration-300 flex items-center group" href="{{ route('dosen.dashboard') }}">
            <span class="material-symbols-outlined text-[19px] group-hover:scale-110 opacity-80">home</span>
        </a>
        <span class="material-symbols-outlined text-[10px] text-white/20 font-normal">play_arrow</span>
        <a href="{{ route('dosen.jadwal') }}" class="hover:text-white transition-all duration-300">Jadwal Mengajar</a>
        <span class="material-symbols-outlined text-[10px] text-white/20 font-normal">play_arrow</span>
        <span class="text-white font-black text-[13px] uppercase tracking-wider">Menunggu Approval</span>
    </nav>
@endsection


@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    @keyframes pulse-slow {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }
    .animate-pulse-slow {
        animation: pulse-slow 2s ease-in-out infinite;
    }
</style>
@endpush

@section('content')
<div class="flex flex-col min-w-0 h-full">
    <div class="p-0 md:p-8 max-w-[900px] mx-auto w-full flex flex-col gap-0">
        <div class="bg-white dark:bg-[#1a1d2e] rounded-xl border border-[#dbdde6] dark:border-slate-800 shadow-sm w-full flex flex-col gap-6 p-6 md:p-10">
            <!-- Header -->
            <div class="text-center mb-4">
                <div class="size-16 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center bg-white mx-auto mb-4 animate-pulse-slow">
                    <span class="material-symbols-outlined text-3xl text-black">hourglass_top</span>
                </div>
                <h1 class="text-2xl font-black text-[#111218] dark:text-white">Menunggu Approval</h1>
                <p class="text-[#616889] dark:text-slate-400 text-sm mt-1">Jadwal Anda sedang dalam proses review oleh admin.</p>
            </div>

            <!-- Status Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex gap-3">
                <span class="material-symbols-outlined text-blue-600">info</span>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Proses Approval</p>
                    <p>Admin akan mereview jadwal yang Anda ajukan dan menetapkan ruangan. Anda akan melihat jadwal lengkap setelah proses selesai.</p>
                </div>
            </div>

            <!-- Pending Jadwals List -->
            <div class="bg-white dark:bg-[#1a1d2e] rounded-xl border border-[#dbdde6] dark:border-slate-800 overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-800">
                    <h2 class="font-bold text-[#111218] dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#8B1538]">pending_actions</span>
                        Jadwal yang Diajukan
                    </h2>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-slate-800">
                    @foreach($pendingJadwals as $jadwal)
                    <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-bold text-[#111218] dark:text-white">
                                    {{ $jadwal->kelas->mataKuliah->nama_mk }}
                                </h3>
                                <span class="bg-red-50 text-red-700 px-2 py-0.5 rounded text-xs font-bold">
                                    {{ $jadwal->kelas->section }}
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-4 text-sm text-[#616889] dark:text-slate-400">
                                <div class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                                    {{ $jadwal->hari }}
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">schedule</span>
                                    {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">school</span>
                                    {{ $jadwal->kelas->mataKuliah->sks ?? 3 }} SKS
                                </div>
                            </div>
                            @if($jadwal->catatan_dosen)
                            <p class="text-xs text-[#616889] mt-2 italic">"{{ $jadwal->catatan_dosen }}"</p>
                            @endif
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            @if($jadwal->status === 'pending')
                                <span class="px-3 py-1.5 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">hourglass_empty</span>
                                    Menunggu Review
                                </span>
                            @elseif($jadwal->status === 'approved')
                                <span class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">check_circle</span>
                                    Disetujui - Menunggu Ruangan
                                </span>
                            @elseif($jadwal->status === 'rejected')
                                <span class="px-3 py-1.5 bg-red-100 text-red-700 rounded-full text-xs font-semibold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">cancel</span>
                                    Ditolak
                                </span>
                                @if($jadwal->catatan_admin)
                                <p class="text-xs text-red-600">{{ $jadwal->catatan_admin }}</p>
                                @endif
                            @endif
                            <span class="text-xs text-[#616889]">Diajukan {{ $jadwal->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Add More Button -->
            <div class="text-center">
                <a href="{{ route('dosen.jadwal.create') }}" 
                    class="inline-flex items-center gap-2 px-6 py-3 border border-[#8B1538] text-[#8B1538] font-semibold rounded-lg hover:bg-[#FEF2F2] transition-colors">
                    <span class="material-symbols-outlined">add</span>
                    Ajukan Jadwal Lain
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
