@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .active-nav {
            background-color: var(--color-primary);
            color: white !important;
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-col gap-8 max-w-[1200px] mx-auto w-full">

        <!-- Welcome Section -->
        <div class="flex flex-col gap-1">
            <h2 class="text-2xl md:text-3xl font-black text-[#111218] dark:text-white tracking-tight">Selamat Pagi,
                {{ Auth::user()->name }}
            </h2>
            <p class="text-[#616889] dark:text-slate-400">Berikut adalah ringkasan aktivitas akademik dan jadwal Anda hari
                ini.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Stat Card 1 -->
            <div
                class="bg-white dark:bg-[#1a1d2e] p-6 rounded-xl border border-[#dbdde6] dark:border-slate-800 shadow-sm flex flex-col gap-4">
                <div class="flex justify-between items-start">
                    <div class="size-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-2xl">menu_book</span>
                    </div>
                    <span
                        class="text-[#07883f] text-xs font-bold bg-green-50 dark:bg-green-900/20 px-2 py-1 rounded">+0%</span>
                </div>
                <div>
                    <p class="text-[#616889] dark:text-slate-400 text-sm font-medium">Total Mata Kuliah</p>
                    <p class="text-3xl font-black text-[#111218] dark:text-white mt-1">{{ $total_mata_kuliah ?? 0 }}</p>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div
                class="bg-white dark:bg-[#1a1d2e] p-6 rounded-xl border border-[#dbdde6] dark:border-slate-800 shadow-sm flex flex-col gap-4">
                <div class="flex justify-between items-start">
                    <div
                        class="size-12 rounded-lg bg-red-100 dark:bg-red-900/20 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-2xl">meeting_room</span>
                    </div>
                    <span class="text-[#07883f] text-xs font-bold bg-green-50 dark:bg-green-900/20 px-2 py-1 rounded">+2
                        ini</span>
                </div>
                <div>
                    <p class="text-[#616889] dark:text-slate-400 text-sm font-medium">Total Kelas Aktif</p>
                    <p class="text-3xl font-black text-[#111218] dark:text-white mt-1">{{ $total_kelas_aktif ?? 0 }}</p>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div
                class="bg-white dark:bg-[#1a1d2e] p-6 rounded-xl border border-[#dbdde6] dark:border-slate-800 shadow-sm flex flex-col gap-4">
                <div class="flex justify-between items-start">
                    <div
                        class="size-12 rounded-lg bg-red-50 dark:bg-red-900/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-2xl">workspace_premium</span>
                    </div>
                    <span
                        class="text-[#616889] dark:text-slate-400 text-xs font-bold bg-gray-100 dark:bg-slate-800 px-2 py-1 rounded">Tetap</span>
                </div>
                <div>
                    <p class="text-[#616889] dark:text-slate-400 text-sm font-medium">Total Beban SKS</p>
                    <p class="text-3xl font-black text-[#111218] dark:text-white mt-1">{{ $sks_load ?? 0 }}</p>
                </div>
            </div>

            <!-- Stat Card 4 (Urgent) -->
            <div
                class="bg-white dark:bg-[#1a1d2e] p-6 rounded-xl border-2 border-primary/20 dark:border-primary/40 shadow-sm flex flex-col gap-4 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-2 opacity-10">
                    <span class="material-symbols-outlined text-6xl text-primary">priority_high</span>
                </div>
                <div class="flex justify-between items-start relative z-10">
                    <div
                        class="size-12 rounded-lg bg-red-100 dark:bg-red-900/40 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-2xl">assignment_late</span>
                    </div>
                    <span
                        class="text-primary text-xs font-bold bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded">Urgent</span>
                </div>
                <div class="relative z-10">
                    <p class="text-[#616889] dark:text-slate-400 text-sm font-medium">KRS Menunggu Approval</p>
                    <p class="text-3xl font-black text-primary mt-1">{{ $krs_approval ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Schedule Table -->
        <div
            class="bg-white dark:bg-[#1a1d2e] rounded-xl border border-[#dbdde6] dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-[#dbdde6] dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">event_note</span>
                    <h3 class="text-lg font-bold text-[#111218] dark:text-white">Jadwal Mengajar Minggu Ini</h3>
                </div>
                <button class="text-primary text-sm font-semibold hover:underline flex items-center gap-1">
                    Lihat Semua <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-gray-50 dark:bg-slate-800/50 text-[#616889] dark:text-slate-400 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-semibold">Mata Kuliah</th>
                            <th class="px-6 py-4 font-semibold text-center">Kelas</th>
                            <th class="px-6 py-4 font-semibold">Hari</th>
                            <th class="px-6 py-4 font-semibold">Waktu</th>
                            <th class="px-6 py-4 font-semibold">Ruangan</th>
                            <th class="px-6 py-4 font-semibold text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                        @forelse ($schedules ?? [] as $schedule)
                            <tr class="hover:bg-red-50/30 dark:hover:bg-red-900/5 transition-colors">
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-[#111218] dark:text-white">{{ $schedule['subject'] }}</span>
                                        <span class="text-xs text-[#616889] dark:text-slate-400">{{ $schedule['code'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="bg-primary/10 text-primary px-2 py-1 rounded text-xs font-bold uppercase">{{ $schedule['class'] }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-sm font-medium">Hari Ini</span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-1.5 text-sm">
                                        <span class="material-symbols-outlined text-sm text-[#616889]">schedule</span>
                                        {{ $schedule['time'] }}
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-1.5 text-sm">
                                        <span class="material-symbols-outlined text-sm text-primary/70">location_on</span>
                                        {{ $schedule['room'] }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                        {{ $schedule['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-[#616889] dark:text-slate-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="material-symbols-outlined text-4xl opacity-50">event_busy</span>
                                        <p class="text-sm font-medium">Tidak ada jadwal mengajar hari ini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-slate-800/50 flex justify-center">
                <p class="text-xs text-[#616889] dark:text-slate-400 italic">Data diperbarui otomatis setiap pergantian
                    jadwal kelas.</p>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- UTS Input -->
            <div
                class="bg-gradient-to-r from-primary to-red-900 p-6 rounded-xl text-white flex items-center justify-between shadow-lg shadow-primary/20">
                <div class="flex flex-col gap-1">
                    <h4 class="font-bold text-lg">Input Nilai UTS</h4>
                    <p class="text-red-100 text-sm opacity-90">Periode penginputan nilai UTS telah dibuka. Harap segera
                        melengkapi nilai.</p>
                </div>
                <button
                    class="bg-white text-primary px-5 py-2.5 rounded-lg font-bold text-sm hover:bg-red-50 transition-colors whitespace-nowrap">
                    Mulai Input
                </button>
            </div>

            <!-- Help Center -->
            <div
                class="bg-white dark:bg-[#1a1d2e] p-6 rounded-xl border border-[#dbdde6] dark:border-slate-800 flex items-center gap-4">
                <div
                    class="size-12 rounded-full bg-red-50 dark:bg-red-900/20 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">help</span>
                </div>
                <div class="flex flex-col gap-0.5">
                    <h4 class="font-bold text-[#111218] dark:text-white">Butuh Bantuan?</h4>
                    <p class="text-[#616889] dark:text-slate-400 text-sm">Hubungi admin IT atau Biro Akademik (BAA).</p>
                </div>
                <button class="ml-auto p-2 hover:bg-red-50 dark:hover:bg-slate-800 rounded-lg group">
                    <span
                        class="material-symbols-outlined text-[#616889] group-hover:text-primary transition-colors">chevron_right</span>
                </button>
            </div>
        </div>

    </div>
@endsection