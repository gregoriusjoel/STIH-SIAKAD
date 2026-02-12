@extends('layouts.mahasiswa')

@section('title', 'KRS Ditutup')
@section('page-title', 'Pengisian KRS')

@section('content')
<div class="py-12 flex justify-center min-h-[60vh] items-center">
    <div class="w-full max-w-lg mx-auto px-4">
        <div class="relative bg-white dark:bg-[#1a1c23] rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-800">
            <!-- Decorative Top Bar -->
            <div class="h-2 w-full bg-gradient-to-r from-[#8B1538] to-[#6D1029]"></div>

            <div class="p-8 text-center">
                <!-- Icon with Glow Effect -->
                <div class="relative mb-6 mx-auto w-24 h-24">
                    <div class="absolute inset-0 bg-orange-100 dark:bg-orange-900/30 rounded-full animate-pulse opacity-50"></div>
                    <div class="relative w-full h-full bg-orange-50 dark:bg-orange-900/20 rounded-full flex items-center justify-center border-4 border-white dark:border-[#1a1c23] shadow-inner">
                        <i class="fas fa-lock text-orange-500 text-4xl"></i>
                    </div>
                    <div class="absolute bottom-0 right-0 bg-[#8B1538] text-white w-8 h-8 rounded-full flex items-center justify-center border-2 border-white dark:border-[#1a1c23] shadow-sm">
                        <i class="fas fa-clock text-xs"></i>
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Pengisian KRS Ditutup</h2>
                <p class="text-gray-500 dark:text-gray-400 leading-relaxed mb-8">
                    {{ $message ?? 'Saat ini bukan periode pengisian Kartu Rencana Studi (KRS). Silakan cek kembali jadwal akademik.' }}
                </p>

                @if($semesterAktif)
                @php
                    $hasDates = isset($semesterAktif->krs_mulai) && isset($semesterAktif->krs_selesai) && $semesterAktif->krs_mulai && $semesterAktif->krs_selesai;
                @endphp
                <div class="bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-100 dark:border-gray-700/50 p-5 mb-8 text-left">
                    <div class="flex items-center gap-3 {{ $hasDates ? 'mb-4 pb-4 border-b border-gray-100 dark:border-white/10' : '' }}">
                        <div class="w-10 h-10 rounded-lg bg-[#8B1538]/10 text-[#8B1538] flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Periode Akademik</p>
                            <h4 class="font-bold text-gray-900 dark:text-white text-sm">
                                Semester {{ $semesterAktif->nama_semester ?? '-' }} {{ $semesterAktif->tahun_ajaran ?? '' }}
                            </h4>
                        </div>
                    </div>
                    
                    @if($hasDates)
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Mulai Pengisian</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($semesterAktif->krs_mulai)->translatedFormat('d F Y') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Batas Akhir</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($semesterAktif->krs_selesai)->translatedFormat('d F Y') }}
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <div class="space-y-4">
                    <div class="flex items-start gap-3 p-4 bg-blue-50 dark:bg-blue-900/10 rounded-lg text-left">
                         <i class="fas fa-info-circle text-blue-500 mt-0.5 shrink-0"></i>
                         <p class="text-xs text-blue-700 dark:text-blue-300 leading-relaxed">
                            Jika Anda mengalami kendala atau membutuhkan dispensasi pengisian KRS, silakan hubungi Bagian Akademik.
                         </p>
                    </div>

                    <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center justify-center w-full px-6 py-3.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-xl font-semibold hover:bg-gray-800 dark:hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
