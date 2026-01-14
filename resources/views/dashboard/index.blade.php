@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
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
<div class="flex bg-background-light dark:bg-background-dark">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-white dark:bg-[#1f1616] border-r border-[#e6dbdb] dark:border-slate-800 hidden lg:flex flex-col">
        <div class="p-6 flex flex-col gap-8 h-full overflow-y-auto">
            
            <!-- Navigation -->
            <nav class="flex flex-col gap-1 grow">
                <a class="active-nav flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors" href="#">
                    <span class="material-symbols-outlined text-[22px]">dashboard</span>
                    <p class="text-sm font-medium">Dashboard</p>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#616889] dark:text-slate-300 hover:bg-red-50 dark:hover:bg-red-900/10 hover:text-primary transition-colors" href="#">
                    <span class="material-symbols-outlined text-[22px]">groups</span>
                    <p class="text-sm font-medium">Kelas</p>
                </a>
                <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[#616889] dark:text-slate-300 hover:bg-red-50 dark:hover:bg-red-900/10 hover:text-primary transition-colors" href="#">
                    <span class="material-symbols-outlined text-[22px]">calendar_today</span>
                    <p class="text-sm font-medium">Jadwal</p>
                </a>
            </nav>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        
        <div class="p-4 md:p-8 flex flex-col gap-8 max-w-[1200px] mx-auto w-full">
            
             <!-- Welcome Section -->
            <div class="flex flex-col gap-1">
                <h2 class="text-2xl md:text-3xl font-black text-[#111218] dark:text-white tracking-tight">Selamat Pagi, {{ Auth::user()->name }}</h2>
                <p class="text-[#616889] dark:text-slate-400">Berikut adalah ringkasan aktivitas akademik dan jadwal Anda hari ini.</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Stat Card 1 -->
                <div class="bg-white dark:bg-[#1a1d2e] p-6 rounded-xl border border-[#dbdde6] dark:border-slate-800 shadow-sm flex flex-col gap-4">
                    <div class="flex justify-between items-start">
                        <div class="size-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-2xl">menu_book</span>
                        </div>
                        <span class="text-[#07883f] text-xs font-bold bg-green-50 dark:bg-green-900/20 px-2 py-1 rounded">+0%</span>
                    </div>
                    <div>
                        <p class="text-[#616889] dark:text-slate-400 text-sm font-medium">Total Mata Kuliah</p>
                        <p class="text-3xl font-black text-[#111218] dark:text-white mt-1">4</p>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div class="bg-white dark:bg-[#1a1d2e] p-6 rounded-xl border border-[#dbdde6] dark:border-slate-800 shadow-sm flex flex-col gap-4">
                    <div class="flex justify-between items-start">
                        <div class="size-12 rounded-lg bg-red-100 dark:bg-red-900/20 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-2xl">meeting_room</span>
                        </div>
                        <span class="text-[#07883f] text-xs font-bold bg-green-50 dark:bg-green-900/20 px-2 py-1 rounded">+2 ini</span>
                    </div>
                    <div>
                        <p class="text-[#616889] dark:text-slate-400 text-sm font-medium">Total Kelas Aktif</p>
                        <p class="text-3xl font-black text-[#111218] dark:text-white mt-1">8</p>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="bg-white dark:bg-[#1a1d2e] p-6 rounded-xl border border-[#dbdde6] dark:border-slate-800 shadow-sm flex flex-col gap-4">
                    <div class="flex justify-between items-start">
                        <div class="size-12 rounded-lg bg-red-50 dark:bg-red-900/10 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-2xl">workspace_premium</span>
                        </div>
                        <span class="text-[#616889] dark:text-slate-400 text-xs font-bold bg-gray-100 dark:bg-slate-800 px-2 py-1 rounded">Tetap</span>
                    </div>
                    <div>
                        <p class="text-[#616889] dark:text-slate-400 text-sm font-medium">Total Beban SKS</p>
                        <p class="text-3xl font-black text-[#111218] dark:text-white mt-1">12</p>
                    </div>
                </div>

                <!-- Stat Card 4 (Urgent) -->
                <div class="bg-white dark:bg-[#1a1d2e] p-6 rounded-xl border-2 border-primary/20 dark:border-primary/40 shadow-sm flex flex-col gap-4 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-2 opacity-10">
                        <span class="material-symbols-outlined text-6xl text-primary">priority_high</span>
                    </div>
                    <div class="flex justify-between items-start relative z-10">
                        <div class="size-12 rounded-lg bg-red-100 dark:bg-red-900/40 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined text-2xl">assignment_late</span>
                        </div>
                        <span class="text-primary text-xs font-bold bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded">Urgent</span>
                    </div>
                    <div class="relative z-10">
                        <p class="text-[#616889] dark:text-slate-400 text-sm font-medium">KRS Menunggu Approval</p>
                        <p class="text-3xl font-black text-primary mt-1">15</p>
                    </div>
                </div>
            </div>

            <!-- Schedule Table -->
            <div class="bg-white dark:bg-[#1a1d2e] rounded-xl border border-[#dbdde6] dark:border-slate-800 shadow-sm overflow-hidden">
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
                            <tr class="bg-gray-50 dark:bg-slate-800/50 text-[#616889] dark:text-slate-400 text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-semibold">Mata Kuliah</th>
                                <th class="px-6 py-4 font-semibold text-center">Kelas</th>
                                <th class="px-6 py-4 font-semibold">Hari</th>
                                <th class="px-6 py-4 font-semibold">Waktu</th>
                                <th class="px-6 py-4 font-semibold">Ruangan</th>
                                <th class="px-6 py-4 font-semibold text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                            <!-- Row 1 -->
                            <tr class="hover:bg-red-50/30 dark:hover:bg-red-900/5 transition-colors">
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-[#111218] dark:text-white">Pemrograman Web</span>
                                        <span class="text-xs text-[#616889] dark:text-slate-400">IT-402 • 3 SKS</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="bg-primary/10 text-primary px-2 py-1 rounded text-xs font-bold uppercase">IF-A</span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-sm font-medium">Senin</span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-1.5 text-sm">
                                        <span class="material-symbols-outlined text-sm text-[#616889]">schedule</span>
                                        08:00 - 10:30
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-1.5 text-sm">
                                        <span class="material-symbols-outlined text-sm text-primary/70">location_on</span>
                                        Lab Komputer 1
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        Selesai
                                    </span>
                                </td>
                            </tr>
                            
                            <!-- Row 2 -->
                            <tr class="hover:bg-red-50/30 dark:hover:bg-red-900/5 transition-colors">
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-[#111218] dark:text-white">Struktur Data</span>
                                        <span class="text-xs text-[#616889] dark:text-slate-400">IT-201 • 3 SKS</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="bg-primary/10 text-primary px-2 py-1 rounded text-xs font-bold uppercase">IF-B</span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-sm font-medium">Selasa</span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-1.5 text-sm">
                                        <span class="material-symbols-outlined text-sm text-[#616889]">schedule</span>
                                        13:00 - 15:30
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-1.5 text-sm">
                                        <span class="material-symbols-outlined text-sm text-primary/70">location_on</span>
                                        R. Teori 302
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-primary dark:bg-red-900/30 dark:text-red-400">
                                        Mendatang
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-slate-800/50 flex justify-center">
                    <p class="text-xs text-[#616889] dark:text-slate-400 italic">Data diperbarui otomatis setiap pergantian jadwal kelas.</p>
                </div>
            </div>

            <!-- Action Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- UTS Input -->
                <div class="bg-gradient-to-r from-primary to-red-900 p-6 rounded-xl text-white flex items-center justify-between shadow-lg shadow-primary/20">
                    <div class="flex flex-col gap-1">
                        <h4 class="font-bold text-lg">Input Nilai UTS</h4>
                        <p class="text-red-100 text-sm opacity-90">Periode penginputan nilai UTS telah dibuka. Harap segera melengkapi nilai.</p>
                    </div>
                    <button class="bg-white text-primary px-5 py-2.5 rounded-lg font-bold text-sm hover:bg-red-50 transition-colors whitespace-nowrap">
                        Mulai Input
                    </button>
                </div>
                
                <!-- Help Center -->
                <div class="bg-white dark:bg-[#1a1d2e] p-6 rounded-xl border border-[#dbdde6] dark:border-slate-800 flex items-center gap-4">
                    <div class="size-12 rounded-full bg-red-50 dark:bg-red-900/20 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">help</span>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <h4 class="font-bold text-[#111218] dark:text-white">Butuh Bantuan?</h4>
                        <p class="text-[#616889] dark:text-slate-400 text-sm">Hubungi admin IT atau Biro Akademik (BAA).</p>
                    </div>
                    <button class="ml-auto p-2 hover:bg-red-50 dark:hover:bg-slate-800 rounded-lg group">
                        <span class="material-symbols-outlined text-[#616889] group-hover:text-primary transition-colors">chevron_right</span>
                    </button>
                </div>
            </div>

        </div>
        @include('layouts.partials.footer')
    </div>
</div>
@endsection
