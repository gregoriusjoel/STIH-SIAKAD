@extends('layouts.guest')

@section('title', 'Absensi Berhasil')

@section('content')
<style>
    @keyframes popIn {
        0% { transform: scale(0); opacity: 0; }
        80% { transform: scale(1.1); opacity: 1; }
        100% { transform: scale(1); }
    }
    .animate-pop-in {
        animation: popIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        animation-iteration-count: 1;
    }
    .check-icon {
        opacity: 0;
        animation: fadeIn 0.3s ease-in-out 0.4s forwards;
        animation-iteration-count: 1;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.5); }
        to { opacity: 1; transform: scale(1); }
    }
</style>

<div class="max-w-md w-full bg-white dark:bg-[#1a1d2e] rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-slate-800 relative">
    
    {{-- Header Content in Card --}}
    <div class="pt-8 px-8 pb-4 text-center">
        {{-- Logo --}}
        <div class="flex justify-center mb-6">
            <div class="p-3 bg-white dark:bg-transparent rounded-lg mx-auto">
               <img src="{{ asset('images/logo_stih_white-clear.png') }}" class="h-16 w-auto block dark:hidden filter brightness-0" alt="Logo STIH">
               <img src="{{ asset('images/logo_stih_white-clear.png') }}" class="h-16 w-auto hidden dark:block" alt="Logo STIH">
            </div>
        </div>

        {{-- Animated Checkmark --}}
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center text-white shadow-lg shadow-green-500/30 animate-pop-in">
                <i class="fas fa-check text-3xl check-icon"></i>
            </div>
        </div>

        <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-2">
            Terima Kasih
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Absensi Anda telah berhasil dicatat oleh sistem.
        </p>
    </div>

    <div class="px-8 pb-8 pt-2">
        {{-- Details --}}
        <div class="space-y-4">
             {{-- Nama --}}
             <div class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-slate-800/50 rounded-xl border border-gray-100 dark:border-slate-700">
                <div class="flex-shrink-0 mt-0.5">
                    <div class="w-8 h-8 rounded-full bg-[#8B1538]/10 flex items-center justify-center text-[#8B1538]">
                        <i class="fas fa-user text-xs"></i>
                    </div>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-0.5">Nama Mahasiswa</p>
                    <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $mahasiswa->user->name ?? '-' }}</p>
                </div>
             </div>

             {{-- MK --}}
             <div class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-slate-800/50 rounded-xl border border-gray-100 dark:border-slate-700">
                <div class="flex-shrink-0 mt-0.5">
                    <div class="w-8 h-8 rounded-full bg-[#8B1538]/10 flex items-center justify-center text-[#8B1538]">
                        <i class="fas fa-book text-xs"></i>
                    </div>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-0.5">Mata Kuliah</p>
                    <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $mataKuliah ?? '-' }}</p>
                </div>
             </div>

             {{-- Waktu --}}
             <div class="grid grid-cols-2 gap-4">
                <div class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-slate-800/50 rounded-xl border border-gray-100 dark:border-slate-700">
                    <div class="flex-shrink-0 mt-0.5">
                        <div class="w-8 h-8 rounded-full bg-[#8B1538]/10 flex items-center justify-center text-[#8B1538]">
                            <i class="fas fa-calendar-alt text-xs"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-0.5">Tanggal</p>
                        <p class="font-bold text-gray-900 dark:text-white text-sm">
                            @if(!empty($presensi))
                                {{ \Carbon\Carbon::parse($presensi->tanggal)->translatedFormat('d M Y') }}
                            @else
                                {{ now()->translatedFormat('d M Y') }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-start space-x-3 p-3 bg-gray-50 dark:bg-slate-800/50 rounded-xl border border-gray-100 dark:border-slate-700">
                    <div class="flex-shrink-0 mt-0.5">
                        <div class="w-8 h-8 rounded-full bg-[#8B1538]/10 flex items-center justify-center text-[#8B1538]">
                            <i class="fas fa-clock text-xs"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-0.5">Jam</p>
                        <p class="font-bold text-gray-900 dark:text-white text-sm">
                            @if(!empty($presensi))
                                {{ $presensi->waktu ? \Carbon\Carbon::parse($presensi->waktu)->format('H:i') : '-' }}
                            @else
                                {{ now()->format('H:i') }}
                            @endif
                        </p>
                    </div>
                </div>
             </div>
        </div>
    </div>
</div>

{{-- Footer --}}
<div class="mt-8 text-center text-xs text-gray-400 dark:text-gray-500">
    &copy; {{ date('Y') }} STIH Adhyaksa
</div>
@endsection
