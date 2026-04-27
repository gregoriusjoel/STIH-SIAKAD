@extends('layouts.admin')

@section('title', 'Histori Notifikasi')
@section('page-title', 'Histori Notifikasi')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Histori Notifikasi</h2>
    <p class="text-gray-500 dark:text-gray-400 mt-2 font-medium">Pantau seluruh aktivitas dan permintaan yang membutuhkan perhatian Anda</p>
</div>

<div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-8">
        @if($notifications->count() > 0)
            <div class="space-y-4">
                @foreach($notifications as $notif)
                    <div class="group relative flex items-start gap-6 p-6 rounded-2xl transition-all duration-300 {{ $notif['needs_action'] ? 'bg-maroon/[0.03] border border-maroon/10' : 'bg-gray-50/50 hover:bg-gray-100/50 border border-transparent' }}">
                        <!-- Icon -->
                        <div class="flex-shrink-0 w-14 h-14 rounded-2xl flex items-center justify-center transition-all duration-500 {{ $notif['needs_action'] ? 'bg-maroon text-white shadow-lg shadow-maroon/20' : 'bg-white dark:bg-gray-900 text-gray-400 border border-gray-100 dark:border-gray-700' }}">
                            <span class="material-symbols-outlined text-2xl">{{ $notif['icon'] }}</span>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0 pt-1">
                            <div class="flex items-center justify-between gap-4 mb-1">
                                <h3 class="text-lg font-extrabold {{ $notif['needs_action'] ? 'text-maroon' : 'text-gray-900 dark:text-white' }}">
                                    {{ $notif['title'] }}
                                </h3>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">
                                    {{ $notif['human_time'] }}
                                </span>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 font-medium leading-relaxed mb-4">
                                {{ $notif['message'] }}
                            </p>
                            
                            <div class="flex items-center gap-3">
                                <a href="{{ $notif['url'] }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-bold transition-all {{ $notif['needs_action'] ? 'bg-maroon text-white hover:bg-red-900 shadow-md shadow-maroon/10' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-600 hover:border-maroon hover:text-maroon' }}">
                                    Lihat Detail
                                    <span class="material-symbols-outlined text-sm ml-2">arrow_forward</span>
                                </a>
                                
                                @if($notif['needs_action'])
                                    <span class="inline-flex items-center px-3 py-1 bg-amber-50 text-amber-600 rounded-lg text-[10px] font-black uppercase tracking-widest border border-amber-100">
                                        Menunggu Tindakan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 bg-green-50 text-green-600 rounded-lg text-[10px] font-black uppercase tracking-widest border border-green-100">
                                        Sudah Ditangani
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Status Indicator Dot -->
                        @if($notif['needs_action'])
                            <div class="absolute top-6 right-6">
                                <span class="flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-maroon opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-maroon"></span>
                                </span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-20">
                <div class="w-24 h-24 bg-gray-50 dark:bg-gray-900/50 rounded-full flex items-center justify-center mb-6">
                    <span class="material-symbols-outlined text-4xl text-gray-300">notifications_off</span>
                </div>
                <h4 class="text-xl font-bold text-gray-900 dark:text-white">Tidak Ada Notifikasi</h4>
                <p class="text-gray-500 mt-2 max-w-sm text-center">Belum ada riwayat aktivitas atau permintaan masuk saat ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection
