@extends('layouts.mahasiswa')

@section('title', 'Magang')
@section('page-title', 'Magang / Internship')

@section('content')
@php
    $mahasiswaSemester = (int) (Auth::user()->mahasiswa->semester ?? 0);
    $canApplyMagang = $mahasiswaSemester >= 5;

    $blockedStatuses = [
        \App\Models\Internship::STATUS_APPROVED,
        \App\Models\Internship::STATUS_SENT_TO_STUDENT,
        \App\Models\Internship::STATUS_SUPERVISOR_ASSIGNED,
        \App\Models\Internship::STATUS_ACCEPTANCE_LETTER_READY,
        \App\Models\Internship::STATUS_ONGOING,
    ];
    $hasApprovedInternship = \App\Models\Internship::where('mahasiswa_id', Auth::user()->mahasiswa->id)
        ->whereIn('status', $blockedStatuses)
        ->exists();
@endphp
<div class="px-4 py-6 max-w-[1600px] mx-auto space-y-6">

    {{-- Premium Header Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 sm:p-8 shadow-sm relative overflow-hidden flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <!-- Background Accents -->
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-gradient-to-br from-[#8B1538]/5 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 bg-gradient-to-tr from-[#8B1538]/5 to-transparent rounded-full blur-2xl"></div>

        <div class="relative flex items-start sm:items-center gap-5 z-10">
            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-br from-[#8B1538] to-[#6D1029] text-white flex items-center justify-center shadow-lg shadow-red-900/20 shrink-0">
                <span class="material-symbols-outlined text-3xl sm:text-4xl">work</span>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight leading-none mb-2">Magang / Internship</h1>
                <p class="text-sm text-gray-500 font-medium">Kelola pengajuan dan pantau progres program magang Anda.</p>
            </div>
        </div>
        <div class="relative z-10 w-full sm:w-auto mt-2 sm:mt-0">
            @if($hasApprovedInternship)
                {{-- Sudah punya magang aktif/acc --}}
                <div class="inline-flex items-center gap-2 px-5 py-3 bg-gray-100 border border-gray-300 text-gray-500 rounded-xl text-sm font-semibold cursor-not-allowed" title="Anda sudah memiliki magang yang disetujui">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    Magang Sudah Disetujui
                </div>
            @elseif($canApplyMagang)
                <div class="shadow-lg shadow-[#8B1538]/10 rounded-xl">
                    <a href="{{ route('mahasiswa.magang.create') }}"
                       class="group w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-[#8B1538] to-[#6D1029] text-white rounded-xl font-bold transition-all hover:shadow-xl hover:shadow-red-900/30 overflow-hidden relative">
                        <span class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></span>
                        <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">add_circle</span>
                        <span class="relative">Ajukan Magang</span>
                    </a>
                </div>
            @else
                <div class="inline-flex items-center gap-2 px-5 py-3 bg-amber-50 border border-amber-200 text-amber-700 rounded-xl text-sm font-semibold cursor-not-allowed" title="Anda belum memenuhi syarat semester untuk mendaftar magang">
                    <span class="material-symbols-outlined text-[18px]">lock</span>
                    Ajukan Magang (Tersedia di Semester 5)
                </div>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50/80 backdrop-blur-sm border border-green-200/60 rounded-2xl flex items-start gap-3 shadow-sm">
            <span class="material-symbols-outlined text-green-600 mt-0.5">check_circle</span>
            <div>
                <h4 class="text-sm font-bold text-green-800">Berhasil!</h4>
                <p class="text-sm text-green-600 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50/80 backdrop-blur-sm border border-red-200/60 rounded-2xl flex items-start gap-3 shadow-sm">
            <span class="material-symbols-outlined text-red-600 mt-0.5">error</span>
            <div>
                <h4 class="text-sm font-bold text-red-800">Gagal!</h4>
                <p class="text-sm text-red-600 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- List --}}
    @if($internships->isEmpty())
        <div class="bg-white rounded-3xl shadow-sm p-12 text-center border border-dashed border-gray-200">
            <div class="w-24 h-24 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-5xl text-gray-300">work_off</span>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Belum Ada Pengajuan Magang</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto text-sm">Anda belum memiliki riwayat pengajuan magang aktif. Silakan buat pengajuan baru untuk memulai proses magang Anda.</p>
            <a href="{{ route('mahasiswa.magang.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white rounded-xl text-sm font-bold hover:bg-gray-800 transition-colors shadow-lg shadow-gray-900/20">
                <span class="material-symbols-outlined text-lg">add</span>
                Buat Pengajuan Pertama
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($internships as $internship)
                <a href="{{ route('mahasiswa.magang.show', $internship) }}"
                   class="group block bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl hover:shadow-red-900/5 hover:-translate-y-1 transition-all duration-300 border border-gray-100 hover:border-red-100 relative overflow-hidden">
                    
                    {{-- Status Badge (Top Right Absolute) --}}
                    <div class="absolute top-4 right-4 z-10 scale-90 origin-top-right">
                        {!! $internship->status_badge !!}
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0 group-hover:bg-red-50 group-hover:border-red-100 group-hover:text-red-600 text-gray-400 transition-colors">
                            <span class="material-symbols-outlined text-2xl">corporate_fare</span>
                        </div>
                        <div class="flex-1 min-w-0 pt-0.5 pr-20">
                            <h3 class="text-lg font-bold text-gray-900 truncate group-hover:text-[#8B1538] transition-colors">
                                {{ $internship->instansi }}
                            </h3>
                            <p class="text-sm font-bold text-gray-500 mt-0.5 line-clamp-1">
                                {{ $internship->posisi ?? 'Posisi belum ditentukan' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-5 border-t border-gray-50 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-start gap-2.5">
                            <span class="material-symbols-outlined text-[18px] text-gray-400 mt-0.5">calendar_month</span>
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Periode</p>
                                <p class="text-xs font-semibold text-gray-700">
                                    {{ $internship->periode_mulai?->format('d M y') ?? '?' }} - {{ $internship->periode_selesai?->format('d M y') ?? '?' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <span class="material-symbols-outlined text-[18px] text-purple-400 mt-0.5">school</span>
                            <div>
                                <p class="text-[10px] font-bold text-purple-400/80 uppercase tracking-widest leading-none mb-1">Semester</p>
                                <p class="text-xs font-semibold text-gray-700">
                                    {{ $internship->semester_mahasiswa ? 'Semester ' . $internship->semester_mahasiswa : '-' }}
                                </p>
                            </div>
                        </div>
                        @if($internship->supervisorDosen)
                        <div class="flex items-start gap-2.5 sm:col-span-2 mt-2">
                            <span class="material-symbols-outlined text-[18px] text-blue-400 mt-0.5">supervisor_account</span>
                            <div>
                                <p class="text-[10px] font-bold text-blue-400/80 uppercase tracking-widest leading-none mb-1">Dosen Pembimbing</p>
                                <p class="text-xs font-semibold text-gray-700">
                                    {{ $internship->supervisorDosen->user->name ?? '-' }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
