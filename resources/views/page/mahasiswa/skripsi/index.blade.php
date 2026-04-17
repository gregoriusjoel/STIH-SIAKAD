@extends('layouts.mahasiswa')
@section('title', 'Skripsi - Progress')
@section('page-title', 'Skripsi')

@section('content')
@php
    $steps = [
        1 => 'Syarat SKS',
        2 => 'Pengajuan Skripsi',
        3 => 'Bimbingan',
        4 => 'Pendaftaran Sidang',
        5 => 'Jadwal Sidang',
        6 => 'Revisi',
        7 => 'Selesai',
    ];
    $sksEligible = $summary['sks_eligible'] ?? false;
    $currentStep = $sksEligible ? ($submission?->status->step() ?? 1) : 0;

    // Contextual guide per step
    $stepGuide = [
        0 => [
            'icon'  => 'school',
            'color' => 'text-red-600',
            'bg'    => 'bg-red-50',
            'title' => 'Belum Memenuhi Syarat SKS',
            'body'  => 'Selesaikan perkuliahan hingga memiliki minimal ' . $summary['min_sks'] . ' SKS untuk membuka jalur skripsi. Saat ini kamu memiliki ' . $summary['total_sks'] . ' SKS.',
            'tip'   => 'Ambil SKS maksimal setiap semester dan pastikan nilai lulus.',
        ],
        1 => [
            'icon'  => 'description',
            'color' => 'text-[#8B1538]',
            'bg'    => 'bg-red-50',
            'title' => 'Langkah: Ajukan Skripsi',
            'body'  => 'Siapkan judul penelitian dan pilih dosen pembimbing. Skripsi akan diverifikasi oleh admin sebelum melanjutkan.',
            'tip'   => 'Pilih topik yang relevan dan konsultasikan dengan dosen terlebih dahulu.',
        ],
        2 => [
            'icon'  => 'edit_note',
            'color' => 'text-purple-600',
            'bg'    => 'bg-purple-50',
            'title' => 'Langkah: Menunggu Review Skripsi',
            'body'  => 'Skripsi telah diajukan. Admin akan meninjau dan memberikan keputusan. Harap tunggu notifikasi selanjutnya.',
            'tip'   => 'Pantau halaman ini secara berkala untuk update status skripsi.',
        ],
        3 => [
            'icon'  => 'menu_book',
            'color' => 'text-blue-600',
            'bg'    => 'bg-blue-50',
            'title' => 'Langkah: Upload Logbook Bimbingan',
            'body'  => 'Download template logbook, isi setiap sesi bimbingan dengan dosen pembimbing, minta tanda tangan, lalu upload dalam format PDF.',
            'tip'   => 'Pastikan logbook sudah diisi lengkap dan ditandatangani dosen sebelum diupload.',
        ],
        4 => [
            'icon'  => 'assignment',
            'color' => 'text-teal-600',
            'bg'    => 'bg-teal-50',
            'title' => 'Langkah: Daftar Sidang',
            'body'  => 'Upload berkas persyaratan pendaftaran sidang melalui form yang tersedia. Admin akan memverifikasi kelengkapan berkas.',
            'tip'   => 'Pastikan semua dokumen lengkap sebelum mendaftar agar tidak ditolak.',
        ],
        5 => [
            'icon'  => 'event_available',
            'color' => 'text-indigo-600',
            'bg'    => 'bg-indigo-50',
            'title' => 'Langkah: Menunggu Jadwal Sidang',
            'body'  => 'Berkas pendaftaran telah disetujui. Admin akan menjadwalkan sidang skripsi kamu. Pantau halaman ini untuk info jadwal.',
            'tip'   => 'Persiapkan presentasi dan bahan sidang sejak dini.',
        ],
        6 => [
            'icon'  => 'upload_file',
            'color' => 'text-orange-600',
            'bg'    => 'bg-orange-50',
            'title' => 'Langkah: Upload Revisi',
            'body'  => 'Sidang telah selesai. Upload dokumen revisi skripsi sesuai catatan dari dosen penguji dan pembimbing.',
            'tip'   => 'Revisi harus diselesaikan dengan teliti sesuai catatan penguji.',
        ],
        7 => [
            'icon'  => 'military_tech',
            'color' => 'text-emerald-600',
            'bg'    => 'bg-emerald-50',
            'title' => 'Skripsi Selesai! <span class="material-symbols-outlined text-lg align-text-bottom text-emerald-600 ml-1">school</span>',
            'body'  => 'Selamat! Skripsi kamu telah disetujui. Proses skripsi selesai secara resmi.',
            'tip'   => 'Jangan lupa urus administrasi yudisium dan wisuda.',
        ],
    ];
    $guide = $stepGuide[$currentStep] ?? $stepGuide[1];
    $color = $submission?->status->color() ?? 'gray';
@endphp

<div class="px-4 py-4 max-w-[1600px] mx-auto space-y-3">

    {{-- ══════════════════════════════════════════════════════════════
         HEADER CARD
    ════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm relative overflow-hidden flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-gradient-to-br from-[#8B1538]/5 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 bg-gradient-to-tr from-[#8B1538]/5 to-transparent rounded-full blur-2xl"></div>

        <div class="relative flex items-center gap-4 z-10">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-br from-[#8B1538] to-[#6D1029] text-white flex items-center justify-center shadow-lg shadow-red-900/20 shrink-0">
                <span class="material-symbols-outlined text-2xl sm:text-3xl">school</span>
            </div>
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight leading-none mb-1">Skripsi</h1>
                <p class="text-xs text-gray-500 font-medium">Kelola dan pantau progres skripsi Anda secara real-time.</p>
            </div>
        </div>

        <div class="relative z-10 flex flex-wrap items-center gap-2">
            <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl">
                <span class="material-symbols-outlined text-[16px] text-[#8B1538]">steps</span>
                <div>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider leading-none">Tahap</p>
                    <p class="text-sm font-black text-gray-800 leading-tight">{{ $sksEligible ? $currentStep : 0 }} / 7</p>
                </div>
            </div>
            <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl">
                <span class="material-symbols-outlined text-[16px] text-[#8B1538]">verified</span>
                <div>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider leading-none">SKS</p>
                    <p class="text-sm font-black {{ $sksEligible ? 'text-emerald-700' : 'text-red-700' }} leading-tight">{{ $summary['total_sks'] }}/{{ $summary['min_sks'] }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl">
                <span class="material-symbols-outlined text-[16px] {{ ($summary['has_logbook'] ?? false) ? 'text-emerald-500' : 'text-amber-500' }}">menu_book</span>
                <div>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider leading-none">Logbook</p>
                    @if($summary['has_logbook'] ?? false)
                    <p class="text-sm font-black text-emerald-700 leading-tight">✓ Diupload</p>
                    @else
                    <p class="text-sm font-black text-amber-600 leading-tight">Belum</p>
                    @endif
                </div>
            </div>
            @if($sksEligible && $submission)
            <span class="px-3 py-2 rounded-xl text-xs font-bold bg-{{ $color }}-50 border border-{{ $color }}-200 text-{{ $color }}-700">
                {{ $submission->status->label() }}
            </span>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         NOT ELIGIBLE STATE
    ════════════════════════════════════════════════════════════════ --}}
    @if(!$sksEligible)
    <div class="bg-red-50/80 border border-red-200/60 rounded-2xl p-4 flex items-start gap-3">
        <span class="material-symbols-outlined text-red-600 mt-0.5">error</span>
        <div>
            <h4 class="text-sm font-bold text-red-800">Belum Memenuhi Syarat Skripsi</h4>
            <p class="text-sm text-red-600 font-medium mt-0.5">
                Anda memiliki <strong>{{ $summary['total_sks'] }} SKS</strong> dari minimal
                <strong>{{ $summary['min_sks'] }} SKS</strong>. Masih kurang
                <strong>{{ $summary['sks_shortage'] }} SKS</strong>.
            </p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="font-bold text-gray-700 mb-4 flex items-center gap-2 text-sm">
            <span class="material-symbols-outlined text-[16px] text-gray-400">lock</span>
            Tahapan Skripsi <span class="text-xs font-normal text-gray-400 ml-1">(terkunci)</span>
        </h2>
        <ol class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            @foreach([
                'Syarat SKS (min. 120 SKS)',
                'Pengajuan Judul & Skripsi',
                'Bimbingan (min. 8x)',
                'Pendaftaran Sidang',
                'Jadwal Sidang',
                'Revisi',
                'Skripsi Selesai',
            ] as $i => $label)
            <li class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ $i === 0 ? 'bg-red-50 border border-red-100' : 'bg-gray-50' }}">
                <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 text-xs font-bold
                    {{ $i === 0 ? 'bg-[#8B1538] text-white' : 'bg-gray-200 text-gray-400' }}">{{ $i + 1 }}</div>
                <span class="text-sm font-medium {{ $i === 0 ? 'text-red-800' : 'text-gray-400' }}">{{ $label }}</span>
                @if($i !== 0)
                <span class="material-symbols-outlined text-[13px] text-gray-300 ml-auto">lock</span>
                @endif
            </li>
            @endforeach
        </ol>
    </div>

    @else

    {{-- ══════════════════════════════════════════════════════════════
         ELIGIBLE: ALERTS
    ════════════════════════════════════════════════════════════════ --}}
    @if($submission && in_array($submission->status->value, ['PROPOSAL_SUBMITTED','SIDANG_REG_SUBMITTED','REVISION_UPLOADED']))
    @php
        $alertMsg = match($submission->status->value) {
            'PROPOSAL_SUBMITTED'   => 'Skripsi Anda sedang dalam review admin. Harap tunggu.',
            'SIDANG_REG_SUBMITTED' => 'Berkas pendaftaran sidang sedang diverifikasi admin.',
            'REVISION_UPLOADED'    => 'Revisi sudah dikirim. Menunggu ACC dari dosen pembimbing.',
            default                => '',
        };
    @endphp
    <div class="bg-amber-50/80 border border-amber-200/60 rounded-2xl p-4 flex items-start gap-3">
        <span class="material-symbols-outlined text-amber-600 mt-0.5">schedule</span>
        <div>
            <h4 class="text-sm font-bold text-amber-800">Sedang Diproses</h4>
            <p class="text-sm text-amber-600 font-medium mt-0.5">{{ $alertMsg }}</p>
        </div>
    </div>
    @endif

    @if($submission && $submission->status->value === 'THESIS_COMPLETED')
    <div class="rounded-2xl p-4 flex items-center gap-4 border border-emerald-200" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5)">
        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm text-emerald-600 shrink-0">
            <span class="material-symbols-outlined text-[28px]">school</span>
        </div>
        <div>
            <p class="font-black text-emerald-800">Selamat! Skripsi Selesai!</p>
            <p class="text-xs text-emerald-600 mt-0.5">Revisi disetujui pada {{ $submission->revision_approved_at?->format('d F Y') }}</p>
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════
         SIDE-BY-SIDE LAYOUT
    ════════════════════════════════════════════════════════════════ --}}
    <div class="flex flex-col lg:flex-row gap-3 items-start">

        {{-- LEFT: Step Tracker 30% --}}
        <div class="w-full lg:w-[30%] shrink-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-4">
                <div class="px-4 py-3 border-b border-gray-50 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-800 text-sm">Tahapan Skripsi</h2>
                    <span class="text-xs font-bold text-[#8B1538]">{{ $currentStep }}/7</span>
                </div>
                <div class="px-4 py-2 bg-gray-50 border-b border-gray-100">
                    @php
                        $isGlobalError = $submission && in_array($submission->status->value, ['PROPOSAL_REJECTED', 'SIDANG_REG_REJECTED', 'REVISION_PENDING']);
                        $barColor = $isGlobalError ? 'linear-gradient(90deg,#f97316,#ea580c)' : 'linear-gradient(90deg,#8B1538,#c0392b)';
                        $textColor = $isGlobalError ? 'text-orange-600' : 'text-[#8B1538]';
                    @endphp
                    <div class="flex justify-between text-[11px] mb-1">
                        <span class="text-gray-400 font-medium">Progress</span>
                        <span class="font-bold {{ $textColor }}">{{ round(($currentStep/7)*100) }}%</span>
                    </div>
                    <div class="bg-gray-200 rounded-full h-1.5 overflow-hidden">
                        <div class="rounded-full h-full transition-all"
                            style="width:{{ round(($currentStep/7)*100) }}%; background:{{ $barColor }}"></div>
                    </div>
                </div>
                <ol class="px-2 py-2 flex flex-col gap-1">
                    @foreach($steps as $step => $label)
                    @php 
                        $done = $step < $currentStep; 
                        $active = $step === $currentStep; 
                        $isErrorState = $active && $isGlobalError;
                    @endphp
                    <li class="flex items-center gap-3 px-3 py-3 rounded-xl transition-colors
                        {{ $active ? ($isErrorState ? 'bg-orange-50 border border-orange-200' : 'bg-red-50 border border-red-100') : ($done ? 'hover:bg-gray-50' : '') }}">
                        <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center shrink-0 text-xs font-black
                            {{ $done ? 'bg-[#8B1538] border-[#8B1538] text-white' :
                               ($active ? ($isErrorState ? 'bg-white border-orange-500 text-orange-500' : 'bg-white border-[#8B1538] text-[#8B1538]') : 'bg-white border-gray-200 text-gray-300') }}">
                            @if($done)
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            @else
                                @if($isErrorState)
                                <span class="material-symbols-outlined text-[16px]">priority_high</span>
                                @else
                                {{ $step }}
                                @endif
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="text-sm font-semibold block truncate
                                {{ $active ? ($isErrorState ? 'text-orange-700' : 'text-[#8B1538]') : ($done ? 'text-gray-600' : 'text-gray-300') }}">
                                {{ $label }}
                            </span>
                            @if($isErrorState)
                            <span class="text-[9px] font-black text-orange-500 uppercase tracking-widest mt-0.5 block">Perlu Perhatian</span>
                            @endif
                        </div>
                        @if($active)
                            <div class="w-2 h-2 rounded-full shrink-0 {{ $isErrorState ? 'bg-orange-500 animate-pulse' : 'bg-[#8B1538]' }}"></div>
                        @endif
                    </li>
                    @endforeach
                </ol>
            </div>
        </div>

        {{-- RIGHT: 70% --}}
        <div class="flex-1 min-w-0 space-y-3">

            {{-- Action Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                {{-- Skripsi --}}
                @if(!$submission || in_array($submission?->status->value, ['PROPOSAL_DRAFT','PROPOSAL_REJECTED']))
                @php $rejected = $submission?->status->value === 'PROPOSAL_REJECTED'; @endphp
                <a href="{{ route('mahasiswa.skripsi.proposal') }}"
                    class="group block bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl hover:shadow-red-900/5 hover:-translate-y-0.5 transition-all duration-200 border border-gray-100 hover:border-red-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-6 -mr-6 w-28 h-28 bg-gradient-to-br from-[#8B1538]/5 to-transparent rounded-full blur-xl"></div>
                    <div class="flex items-start gap-4 relative z-10">
                        <div class="w-13 h-13 rounded-xl {{ $rejected ? 'bg-red-100' : 'bg-gradient-to-br from-[#8B1538] to-[#6D1029]' }} text-white flex items-center justify-center shadow-sm shrink-0" style="width:52px;height:52px">
                            <span class="material-symbols-outlined text-2xl {{ $rejected ? 'text-red-600' : '' }}">description</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                <h3 class="font-bold text-gray-900 group-hover:text-[#8B1538] transition-colors">
                                    {{ $rejected ? 'Revisi Skripsi' : 'Ajukan Skripsi' }}
                                </h3>
                                @if($rejected)
                                <span class="text-[10px] bg-red-50 text-red-600 font-bold px-2 py-0.5 rounded-full border border-red-100">Ditolak</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 leading-relaxed">
                                {{ $rejected ? ($submission->admin_note ?? 'Perbaiki dan kirim ulang skripsi') : 'Pilih judul penelitian & dosen pembimbing Anda untuk memulai proses skripsi.' }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 pt-4 border-t border-gray-50 flex items-center justify-between">
                        <span class="text-xs font-bold text-[#8B1538] flex items-center gap-1 group-hover:gap-2 transition-all">
                            {{ $rejected ? 'Perbaiki Sekarang' : 'Mulai Sekarang' }}
                            <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                        </span>
                        <span class="text-[10px] text-gray-300 font-medium">Langkah 2</span>
                    </div>
                </a>
                @endif

                {{-- Logbook Bimbingan --}}
                @if($submission && in_array($submission->status->value, ['BIMBINGAN_ACTIVE','ELIGIBLE_SIDANG','SIDANG_REG_DRAFT','SIDANG_REG_REJECTED']))
                @php $hasLogbook = !empty($submission->logbook_file_path); @endphp
                <a href="{{ route('mahasiswa.skripsi.bimbingan') }}"
                    class="group block bg-white rounded-2xl p-5 shadow-sm hover:shadow-xl hover:shadow-blue-900/5 hover:-translate-y-0.5 transition-all duration-200 border border-gray-100 hover:border-blue-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-6 -mr-6 w-20 h-20 bg-gradient-to-br from-blue-500/5 to-transparent rounded-full blur-xl"></div>
                    <div class="flex items-start gap-3 relative z-10">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 text-white flex items-center justify-center shadow-sm shrink-0">
                            <span class="material-symbols-outlined text-xl">menu_book</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-900 group-hover:text-blue-800 transition-colors text-sm mb-1">Logbook Bimbingan</h3>
                            @if($hasLogbook)
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <p class="text-xs text-emerald-600 font-medium truncate">{{ $submission->logbook_original_name }}</p>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-0.5">Upload: {{ $submission->logbook_uploaded_at?->format('d M Y') }}</p>
                            @else
                            <p class="text-xs text-amber-600 font-medium flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">warning</span>
                                Belum upload logbook
                            </p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-4 pt-3.5 border-t border-gray-50 flex items-center justify-between">
                        <span class="text-xs font-bold text-blue-700 flex items-center gap-1 group-hover:gap-2 transition-all">
                            {{ $hasLogbook ? 'Lihat Detail' : 'Upload Sekarang' }} <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                        </span>
                        <span class="text-[10px] text-gray-300 font-medium">Langkah 3</span>
                    </div>
                </a>
                @endif

                {{-- Daftar Sidang --}}
                @if($submission && in_array($submission->status->value, ['ELIGIBLE_SIDANG','SIDANG_REG_DRAFT','SIDANG_REG_REJECTED']))
                @php $regRej = $submission->status->value === 'SIDANG_REG_REJECTED'; @endphp
                <a href="{{ route('mahasiswa.skripsi.sidang.registration') }}"
                    class="group block bg-white rounded-2xl p-5 shadow-sm hover:shadow-xl hover:shadow-teal-900/5 hover:-translate-y-0.5 transition-all duration-200 border border-gray-100 hover:border-teal-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-6 -mr-6 w-20 h-20 bg-gradient-to-br from-teal-500/5 to-transparent rounded-full blur-xl"></div>
                    <div class="flex items-start gap-3 relative z-10">
                        <div class="w-11 h-11 rounded-xl {{ $regRej ? 'bg-orange-100' : 'bg-gradient-to-br from-teal-500 to-teal-700' }} text-white flex items-center justify-center shadow-sm shrink-0">
                            <span class="material-symbols-outlined text-xl {{ $regRej ? 'text-orange-600' : '' }}">assignment</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <h3 class="font-bold text-gray-900 group-hover:text-teal-800 transition-colors text-sm">Pendaftaran Sidang</h3>
                                @if($regRej)
                                <span class="text-[10px] bg-orange-50 text-orange-600 font-bold px-2 py-0.5 rounded-full border border-orange-100">Ditolak</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500">{{ $regRej ? 'Lengkapi dan kirim ulang berkas' : 'Upload berkas persyaratan sidang' }}</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-3.5 border-t border-gray-50 flex items-center justify-between">
                        <span class="text-xs font-bold text-teal-700 flex items-center gap-1 group-hover:gap-2 transition-all">
                            {{ $regRej ? 'Perbaiki Berkas' : 'Daftar Sekarang' }}
                            <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                        </span>
                        <span class="text-[10px] text-gray-300 font-medium">Langkah 4</span>
                    </div>
                </a>
                @endif

                {{-- Upload Revisi --}}
                @if($submission && $submission->status->value === 'REVISION_PENDING')
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-orange-100 md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 text-white flex items-center justify-center shadow-sm shrink-0">
                            <span class="material-symbols-outlined text-xl">upload_file</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-sm">Upload Revisi Skripsi</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Format: PDF, DOC, DOCX</p>
                        </div>
                    </div>
                    <form action="{{ route('mahasiswa.skripsi.revision.upload') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row gap-3">
                        @csrf
                        <div class="flex-1 space-y-2">
                            <input type="file" name="revision_file" accept=".pdf,.doc,.docx"
                                class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                            <textarea name="notes" placeholder="Catatan revisi (opsional)"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-xs resize-none h-14 focus:outline-none focus:ring-2 focus:ring-orange-200 focus:border-orange-300"></textarea>
                        </div>
                        <button type="submit" class="sm:self-end px-5 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all whitespace-nowrap">
                            Upload Revisi
                        </button>
                    </form>
                </div>
                @endif

                {{-- Detail Sidang --}}
                @if($submission?->sidangSchedule)
                @php $sch = $submission->sidangSchedule; @endphp
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-indigo-100 flex flex-col justify-between">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-700 text-white flex items-center justify-center shadow-sm shrink-0">
                            <span class="material-symbols-outlined text-xl">event_available</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-sm">Detail Sidang</h3>
                            <p class="text-xs text-indigo-500 mt-0.5 font-medium">{{ $sch->tanggal->format('d F Y') }}</p>
                        </div>
                    </div>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-auto">
                        @foreach([
                            ['label' => 'Waktu',      'value' => \Carbon\Carbon::parse($sch->waktu_mulai)->format('H:i').($sch->waktu_selesai ? ' - '.\Carbon\Carbon::parse($sch->waktu_selesai)->format('H:i') : '').' WIB', 'icon' => 'schedule',    'color' => 'text-indigo-400'],
                            ['label' => 'Ruangan',    'value' => $sch->ruangan_label,            'icon' => 'meeting_room', 'color' => 'text-purple-400'],
                            ['label' => 'Pembimbing', 'value' => $sch->pembimbing?->nama ?? '-', 'icon' => 'person',       'color' => 'text-blue-400'],
                            ['label' => 'Penguji 1',  'value' => $sch->penguji1?->nama ?? '-',  'icon' => 'person_check', 'color' => 'text-teal-400'],
                            ['label' => 'Penguji 2',  'value' => $sch->penguji2?->nama ?? '-',  'icon' => 'person_check', 'color' => 'text-teal-400'],
                        ] as $item)
                        <div class="bg-gray-50 border border-gray-100 rounded-xl px-3 py-2.5">
                            <div class="flex items-center gap-1 mb-1">
                                <span class="material-symbols-outlined text-[13px] {{ $item['color'] }}">{{ $item['icon'] }}</span>
                                <dt class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">{{ $item['label'] }}</dt>
                            </div>
                            <dd class="text-xs font-semibold text-gray-800 truncate">{{ $item['value'] }}</dd>
                        </div>
                        @endforeach
                    </dl>
                </div>
                @endif

                {{-- Contextual Guide: inside grid = sejajar dengan action card --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between">
                    <div class="flex items-start gap-4">
                        <div class="w-[52px] h-[52px] rounded-xl {{ $guide['bg'] }} flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-2xl {{ $guide['color'] }}">{{ $guide['icon'] }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-800 mb-1.5 flex items-center">{!! $guide['title'] !!}</h3>
                            <p class="text-sm text-gray-500 leading-relaxed">{{ $guide['body'] }}</p>
                        </div>
                    </div>
                    <div class="mt-5 flex items-start gap-2 bg-amber-50 border border-amber-100 rounded-xl px-3 py-3">
                        <span class="material-symbols-outlined text-[15px] text-amber-500 mt-0.5 shrink-0">tips_and_updates</span>
                        <p class="text-sm text-amber-700 font-medium leading-relaxed">{{ $guide['tip'] }}</p>
                    </div>
                </div>

                {{-- Bantuan / Kontak --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col justify-between md:col-span-2">
                    <div class="flex items-start gap-4">
                        <div class="w-[52px] h-[52px] rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-2xl text-purple-600">support_agent</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-gray-800 mb-1.5">Butuh Bantuan?</h3>
                            <p class="text-sm text-gray-500 leading-relaxed">Jika ada kendala dalam proses skripsi, jangan ragu untuk menghubungi pihak akademik atau Dosen PA Anda.</p>
                        </div>
                    </div>
                </div>

            </div>{{-- end action cards grid --}}

        </div>{{-- end right --}}
    </div>{{-- end side-by-side --}}

    @endif {{-- end sks_eligible --}}

</div>
@endsection
