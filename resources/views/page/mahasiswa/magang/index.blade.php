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
<div class="px-4 py-6 max-w-[1600px] mx-auto space-y-6" x-data="{ showTypeModal: false, selectedType: '{{ $internshipTypes->first()?->id ?? '' }}' }">

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
                    <button @click="showTypeModal = true"
                       class="group w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-[#8B1538] to-[#6D1029] text-white rounded-xl font-bold transition-all hover:shadow-xl hover:shadow-red-900/30 overflow-hidden relative">
                        <span class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></span>
                        <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">add_circle</span>
                        <span class="relative">Ajukan Magang</span>
                    </button>
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
            <button @click="showTypeModal = true" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white rounded-xl text-sm font-bold hover:bg-gray-800 transition-colors shadow-lg shadow-gray-900/20">
                <span class="material-symbols-outlined text-lg">add</span>
                Buat Pengajuan Pertama
            </button>
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
                            <div class="mt-1.5">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wide {{ $internship->type && $internship->type->is_conversion ? 'bg-purple-100 text-purple-700 ring-1 ring-inset ring-purple-200' : 'bg-teal-50 text-teal-700 ring-1 ring-inset ring-teal-200' }}">
                                    {{ $internship->type?->name ?? 'Magang Berdampak (MBKM)' }}
                                </span>
                            </div>
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

    {{-- Modal Pilihan Tipe Magang --}}
    <div x-show="showTypeModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity" @click="showTypeModal = false"></div>

        {{-- Content --}}
        <div class="relative bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-lg p-8 z-10 border border-gray-100 dark:border-gray-700 overflow-hidden transform transition-all">
            <!-- Decorative Accent -->
            <div class="absolute top-0 right-0 -mt-8 -mr-8 w-28 h-28 bg-gradient-to-br from-[#8B1538]/10 to-transparent rounded-full blur-2xl pointer-events-none"></div>

            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#8B1538]/5 border border-[#8B1538]/10 flex items-center justify-center text-[#8B1538]">
                        <span class="material-symbols-outlined text-[22px]">assignment</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white">Pilih Program Magang</h3>
                        <p class="text-xs text-gray-400 font-medium">Tentukan tipe program magang yang ingin Anda ajukan</p>
                    </div>
                </div>
                <button @click="showTypeModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <div class="space-y-4">
                @foreach($internshipTypes as $type)
                    <div @click="selectedType = '{{ $type->id }}'"
                         :class="selectedType === '{{ $type->id }}'
                            ? 'border-[#8B1538] bg-[#8B1538]/5 ring-2 ring-[#8B1538]/20'
                            : 'border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-red-200 hover:bg-red-50/20'"
                         class="p-5 border rounded-2xl cursor-pointer transition-all duration-200 flex items-start gap-4 group">
                        
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors"
                             :class="selectedType === '{{ $type->id }}' ? 'bg-[#8B1538] text-white' : 'bg-gray-50 dark:bg-gray-700 text-gray-400 group-hover:text-[#8B1538] group-hover:bg-[#8B1538]/5'">
                            <span class="material-symbols-outlined text-[20px]">{{ $type->is_conversion ? 'school' : 'work' }}</span>
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-[#8B1538] transition-colors">{{ $type->name }}</h4>
                                <div class="w-5 h-5 rounded-full border flex items-center justify-center shrink-0 transition-all"
                                     :class="selectedType === '{{ $type->id }}' ? 'border-[#8B1538] bg-[#8B1538]' : 'border-gray-200'">
                                    <div x-show="selectedType === '{{ $type->id }}'" class="w-2.5 h-2.5 rounded-full bg-white"></div>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 font-medium mt-1 leading-relaxed">{{ $type->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex gap-3 mt-8">
                <button type="button" @click="showTypeModal = false"
                        class="flex-1 px-5 py-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-sm font-bold text-gray-600 transition-colors text-center">
                    Batal
                </button>
                <a :href="'{{ route('mahasiswa.magang.create') }}?internship_type_id=' + selectedType"
                   :class="selectedType ? 'bg-[#8B1538] hover:bg-[#6D1029] text-white shadow-lg shadow-red-900/20' : 'bg-gray-100 text-gray-400 cursor-not-allowed pointer-events-none'"
                   class="flex-1 px-5 py-3 rounded-xl text-sm font-bold transition-all text-center flex items-center justify-center gap-1.5">
                    Lanjutkan <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
