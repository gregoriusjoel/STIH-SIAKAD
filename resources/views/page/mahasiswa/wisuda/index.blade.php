@extends('layouts.mahasiswa')
@section('title', 'Pendaftaran Wisuda')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500;600;700;800&family=Crimson+Text:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    .font-garamond { font-family: 'EB Garamond', 'Crimson Text', Georgia, serif; }
    .wisuda-hero {
        background: #ffffff;
        position: relative;
    }
    .wisuda-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(139,21,56,0.04) 0%, transparent 70%);
        border-radius: 50%;
    }
    .wisuda-hero::after {
        content: '';
        position: absolute;
        bottom: -40px;
        left: -40px;
        width: 160px;
        height: 160px;
        background: radial-gradient(circle, rgba(139,21,56,0.03) 0%, transparent 70%);
        border-radius: 50%;
    }
    .gold-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #8B1538 20%, #6D1029 50%, #8B1538 80%, transparent);
    }
    .schedule-card {
        background: linear-gradient(145deg, #ffffff 0%, #fefdfb 100%);
        transition: box-shadow 0.2s ease-out, transform 0.2s ease-out;
    }
    .schedule-card:hover {
        box-shadow: 0 8px 30px rgba(139,21,56,0.08);
    }
    .preview-card-wrapper {
        background: linear-gradient(145deg, #faf7f8 0%, #f5f0f2 100%);
    }
    .invitation-shimmer {
        position: relative;
        overflow: hidden;
    }
    .invitation-shimmer::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 50%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(139,21,56,0.06), transparent);
        animation: shimmer 4s ease-in-out infinite;
    }
    @keyframes shimmer {
        0%, 100% { left: -100%; }
        50% { left: 100%; }
    }
    .doc-card {
        transition: all 0.2s ease-out;
    }
    .doc-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }
    @media (prefers-reduced-motion: reduce) {
        .invitation-shimmer::after { animation: none; }
        .doc-card:hover { transform: none; }
        .schedule-card:hover { transform: none; }
    }
</style>
@endpush

@section('content')
<div class="px-4 py-6 space-y-5 max-w-[1600px] mx-auto">

    {{-- ══════════════════════════════════════════════════════════════
         HEADER
    ════════════════════════════════════════════════════════════════ --}}
    <div class="wisuda-hero rounded-2xl border border-gray-100/80 shadow-sm p-5 sm:p-6 overflow-hidden">
        <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-4 z-10">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-gradient-to-br from-[#8B1538] to-[#6D1029] text-white flex items-center justify-center shadow-lg shadow-[#8B1538]/15 shrink-0">
                    <span class="material-symbols-outlined text-2xl sm:text-[28px]">school</span>
                </div>
                <div>
                    <h1 class="font-garamond text-xl sm:text-2xl font-bold text-gray-900 tracking-tight leading-tight">Pendaftaran Wisuda</h1>
                    <p class="text-xs text-gray-500 mt-0.5 font-medium">Pantau status pendaftaran dan jadwal wisuda Anda secara real-time.</p>
                </div>
            </div>

            @if(isset($summary['active_registration']))
                @php
                    $reg = $summary['active_registration'];
                    $status = $reg->status;
                @endphp
                @php
                    $badgeColors = match($status->value) {
                        'pending' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-200', 'dot' => 'bg-yellow-500', 'text' => 'text-yellow-700'],
                        'approved' => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'dot' => 'bg-green-500', 'text' => 'text-green-700'],
                        'rejected' => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'dot' => 'bg-red-500', 'text' => 'text-red-700'],
                        'scheduled' => ['bg' => 'bg-[#8B1538]/5', 'border' => 'border-[#8B1538]/20', 'dot' => 'bg-[#8B1538]', 'text' => 'text-[#8B1538]'],
                        default => ['bg' => 'bg-gray-50', 'border' => 'border-gray-200', 'dot' => 'bg-gray-500', 'text' => 'text-gray-700'],
                    };
                @endphp
                <div class="flex items-center gap-2.5 px-4 py-2.5 {{ $badgeColors['bg'] }} border {{ $badgeColors['border'] }} rounded-xl shrink-0">
                    <span class="w-2 h-2 rounded-full {{ $badgeColors['dot'] }} animate-pulse"></span>
                    <span class="text-xs font-bold {{ $badgeColors['text'] }} tracking-wide">{{ $status->label() }}</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200/60 rounded-2xl p-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-emerald-600 mt-0.5 shrink-0">check_circle</span>
            <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200/60 rounded-2xl p-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-red-600 mt-0.5 shrink-0">error</span>
            <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════
         STATE 1: NO ACTIVE REGISTRATION
    ════════════════════════════════════════════════════════════════ --}}
    @if(!isset($summary['active_registration']))
        @if(!empty($summary['unpaid_semesters']))
            {{-- Unpaid Semesters Warning --}}
            <div class="bg-red-50 border border-red-200 rounded-2xl p-6 shadow-sm mb-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-white border border-red-200 rounded-xl flex items-center justify-center shrink-0 text-red-600 shadow-sm">
                        <span class="material-symbols-outlined text-2xl">credit_card_off</span>
                    </div>
                    <div>
                        <h3 class="font-garamond text-lg font-bold text-red-900">Pembayaran Belum Lengkap</h3>
                        <p class="text-sm text-red-700/95 mt-1 leading-relaxed">
                            Anda belum melunasi tagihan uang kuliah pada semester: <strong class="text-red-950 font-black">{{ implode(', ', $summary['unpaid_semesters']) }}</strong>.
                        </p>
                        <p class="text-xs text-red-600 mt-2 font-medium">
                            Silakan selesaikan semua tunggakan Anda di menu <a href="{{ route('mahasiswa.pembayaran.index') }}" class="underline font-bold hover:text-red-800">Pembayaran Kuliah</a> terlebih dahulu untuk membuka akses pendaftaran wisuda.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if($summary['is_eligible'])
            {{-- Eligible --}}
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-50/50 border border-emerald-200/50 rounded-2xl p-6 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-white border border-emerald-200/60 rounded-xl flex items-center justify-center shrink-0 text-emerald-600 shadow-sm">
                            <span class="material-symbols-outlined text-2xl">verified</span>
                        </div>
                        <div>
                            <h3 class="font-garamond text-lg font-bold text-emerald-900">Anda Memenuhi Syarat Pendaftaran Wisuda</h3>
                            <p class="text-sm text-emerald-700/80 mt-1 leading-relaxed max-w-xl">
                                Skripsi Anda telah disetujui secara resmi dengan status <strong class="text-emerald-800">ACC Revisi</strong>. Silakan isi form pendaftaran wisuda untuk memulai proses pengunggahan berkas kelengkapan.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('mahasiswa.wisuda.register') }}"
                        class="shrink-0 inline-flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-6 py-3 rounded-xl font-bold text-sm hover:from-emerald-700 hover:to-emerald-800 transition-colors duration-200 shadow-md cursor-pointer">
                        <span class="material-symbols-outlined text-[20px]">how_to_reg</span>
                        Daftar Sekarang
                    </a>
                </div>
            </div>
        @else
            @if(empty($summary['unpaid_semesters']) || !$summary['submission'])
                {{-- Not eligible --}}
                <div class="bg-red-50/60 border border-red-200/50 rounded-2xl p-5 flex items-start gap-3">
                    <span class="material-symbols-outlined text-red-500 mt-0.5 shrink-0">lock</span>
                    <div>
                        <h4 class="text-sm font-bold text-red-800">Pendaftaran Terkunci</h4>
                        <p class="text-xs text-red-600/90 font-medium mt-1 leading-relaxed max-w-2xl">
                            Anda belum memenuhi syarat untuk mendaftar wisuda. Jalur ini hanya terbuka untuk mahasiswa yang skripsinya telah dinyatakan selesai dan disetujui revisinya oleh dosen penguji & pembimbing (status skripsi: <strong>ACC Revisi</strong>).
                        </p>
                    </div>
                </div>
            @endif

            {{-- Locked steps --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-garamond font-bold text-gray-700 mb-4 flex items-center gap-2 text-base">
                    <span class="material-symbols-outlined text-[18px] text-gray-400">task_alt</span>
                    Syarat Kelulusan & Wisuda
                </h2>
                @php
                    $latestSubmission = \App\Models\SkripsiSubmission::where('mahasiswa_id', $mahasiswa->id)->latest()->first();
                    $hasPassedExam = false;
                    $hasFinishedRevision = false;
                    $isAccRevision = false;

                    if ($latestSubmission) {
                        $status = $latestSubmission->status;

                        $hasPassedExam = in_array($status, [
                            \App\Domain\Skripsi\Enums\SkripsiStatus::SIDANG_COMPLETED,
                            \App\Domain\Skripsi\Enums\SkripsiStatus::REVISION_PENDING,
                            \App\Domain\Skripsi\Enums\SkripsiStatus::REVISION_UPLOADED,
                            \App\Domain\Skripsi\Enums\SkripsiStatus::REVISION_APPROVED,
                            \App\Domain\Skripsi\Enums\SkripsiStatus::SKRIPSI_COMPLETED,
                        ], true);

                        $hasFinishedRevision = in_array($status, [
                            \App\Domain\Skripsi\Enums\SkripsiStatus::REVISION_APPROVED,
                            \App\Domain\Skripsi\Enums\SkripsiStatus::SKRIPSI_COMPLETED,
                        ], true);

                        $isAccRevision = in_array($status, [
                            \App\Domain\Skripsi\Enums\SkripsiStatus::REVISION_APPROVED,
                            \App\Domain\Skripsi\Enums\SkripsiStatus::SKRIPSI_COMPLETED,
                        ], true);
                    }
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach([
                        ['done' => $hasPassedExam, 'label' => 'Lulus Ujian Skripsi', 'icon' => 'gavel'],
                        ['done' => $hasFinishedRevision, 'label' => 'Revisi Skripsi Selesai', 'icon' => 'edit_note'],
                        ['done' => $isAccRevision, 'label' => 'Status: ACC Revisi', 'icon' => 'verified'],
                    ] as $req)
                        <div class="flex items-center gap-3 px-4 py-3.5 rounded-xl border transition-colors duration-200
                            {{ $req['done'] ? 'bg-emerald-50/50 border-emerald-100' : 'bg-red-50/50 border-red-100' }}">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0
                                {{ $req['done'] ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-500' }}">
                                <span class="material-symbols-outlined text-[18px]">{{ $req['done'] ? 'check_circle' : 'cancel' }}</span>
                            </div>
                            <span class="text-xs font-semibold {{ $req['done'] ? 'text-gray-700' : 'text-red-700' }}">{{ $req['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    @else
        {{-- ══════════════════════════════════════════════════════════════
             STATE 2: HAS ACTIVE REGISTRATION
        ════════════════════════════════════════════════════════════════ --}}
        @php
            $reg = $summary['active_registration'];
            $status = $reg->status;
            $hasAllDocs = $reg->hasRequiredDocuments();
        @endphp

        {{-- ──────────────────────────────────────────────────────────
             SCHEDULED STATE - Premium Two-Column Layout
        ────────────────────────────────────────────────────────────── --}}
        @if($status->value === 'scheduled' && $reg->batch)
            @php $batch = $reg->batch; @endphp
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

                {{-- Left: Schedule Details (3/5 on lg) --}}
                <div class="lg:col-span-3 space-y-5">
                    {{-- Celebration Banner --}}
                    <div class="schedule-card rounded-2xl border border-[#8B1538]/10 p-6 relative overflow-hidden">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#8B1538] via-[#a91d47] to-[#8B1538]"></div>

                        <div class="flex items-start gap-4 mb-5">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#8B1538] to-[#6D1029] rounded-xl flex items-center justify-center shadow-md shadow-[#8B1538]/15 shrink-0">
                                <span class="material-symbols-outlined text-white text-2xl">celebration</span>
                            </div>
                            <div>
                                <h2 class="font-garamond text-xl font-bold text-gray-900 leading-tight">Selamat! Jadwal Wisuda Anda Telah Rilis</h2>
                                <p class="text-xs text-gray-500 mt-1 font-medium">Harap hadir tepat waktu sesuai jadwal dan lokasi yang tertera di bawah.</p>
                            </div>
                        </div>

                        <div class="gold-divider mb-5"></div>

                        {{-- Schedule Details Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach([
                                ['icon' => 'workspace_premium', 'label' => 'Batch Wisuda', 'value' => $batch->nama_batch, 'accent' => 'text-[#8B1538]'],
                                ['icon' => 'calendar_month', 'label' => 'Tanggal Pelaksanaan', 'value' => $batch->tanggal->translatedFormat('l, d F Y'), 'accent' => 'text-[#8B1538]'],
                                ['icon' => 'schedule', 'label' => 'Waktu', 'value' => $batch->waktu_mulai->format('H:i') . ' WIB', 'accent' => 'text-[#8B1538]'],
                                ['icon' => 'location_on', 'label' => 'Lokasi Acara', 'value' => $batch->lokasi, 'accent' => 'text-[#8B1538]'],
                            ] as $detail)
                                <div class="bg-gray-50/60 border border-gray-100 rounded-xl p-4 transition-colors duration-200 hover:bg-gray-50">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="material-symbols-outlined text-[16px] {{ $detail['accent'] }}">{{ $detail['icon'] }}</span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">{{ $detail['label'] }}</span>
                                    </div>
                                    <p class="text-sm font-bold text-gray-800 leading-snug" title="{{ $detail['value'] }}">{{ $detail['value'] }}</p>
                                </div>
                            @endforeach
                        </div>

                        @if($batch->catatan)
                            <div class="mt-4 p-4 bg-amber-50/50 border border-amber-100/60 rounded-xl">
                                <p class="text-xs font-bold text-amber-800 mb-1 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[15px]">info</span> Catatan Tambahan
                                </p>
                                <p class="text-xs text-gray-600 leading-relaxed">{{ $batch->catatan }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Quick Info Cards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#8B1538]/5 rounded-lg flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[20px] text-[#8B1538]">badge</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Wisudawan/i</p>
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ $mahasiswa->user->name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#8B1538]/5 rounded-lg flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[20px] text-[#8B1538]">fingerprint</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">NIM / Prodi</p>
                                    <p class="text-sm font-bold text-gray-800">{{ $mahasiswa->nim }} &middot; {{ $mahasiswa->prodi }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: Invitation Preview (2/5 on lg) --}}
                <div class="lg:col-span-2">
                    <div class="preview-card-wrapper rounded-2xl border border-[#8B1538]/10 shadow-sm p-4 sticky top-6">
                        <h3 class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-[#8B1538]">preview</span>
                            Preview Undangan Digital
                        </h3>

                        {{-- Mini Certificate Card --}}
                        <div class="invitation-shimmer relative overflow-hidden rounded-lg border border-[#d4a843]/30 bg-white shadow-md">
                            {{-- Decorative Double Border --}}
                            <div class="absolute inset-1 border-2 border-transparent pointer-events-none z-10" style="border-image: linear-gradient(135deg, #d4a843 0%, #b4861b 50%, #d4a843 100%) 1;"></div>
                            <div class="absolute inset-[8px] border border-[#d4a843]/30 pointer-events-none z-10"></div>

                            {{-- Card Header --}}
                            <div class="bg-gradient-to-br from-[#8B1538] to-[#5a0e25] py-4 px-3 text-center border-b-2 border-[#d4a843] relative">
                                <img src="{{ asset('images/logo_stih_white.png') }}" alt="Logo STIH Adhyaksa" class="w-8 h-8 mx-auto mb-1.5 drop-shadow-sm">
                                <h4 class="font-garamond text-[12px] font-bold text-white tracking-[3px] leading-none uppercase">STIH Adhyaksa</h4>
                                <p class="text-[6px] text-[#d4a843] uppercase tracking-[3px] mt-1.5 font-semibold">Biro Administrasi Akademik</p>
                            </div>

                            {{-- Card Body --}}
                            <div class="bg-gradient-to-b from-[#fefdfb] to-[#f7f3ea] p-4 text-center">
                                <p class="font-garamond text-[10px] font-bold text-gray-700 tracking-[2px] uppercase">Kartu Undangan Wisuda</p>
                                <div class="flex items-center justify-center gap-2 my-1.5">
                                    <div class="w-10 h-[1px] bg-gradient-to-r from-transparent to-[#b4861b]"></div>
                                    <div class="w-1.5 h-1.5 bg-[#d4a843] transform rotate-45"></div>
                                    <div class="w-10 h-[1px] bg-gradient-to-l from-transparent to-[#b4861b]"></div>
                                </div>

                                {{-- Recipient --}}
                                <div class="my-3 bg-white/80 border border-dashed border-[#d4a843]/40 rounded-lg p-3 shadow-sm">
                                    <p class="text-[6px] text-[#b4861b] font-bold uppercase tracking-[2px]">Wisudawan / Wisudawati</p>
                                    <h5 class="font-garamond text-[13px] font-bold text-[#8B1538] mt-1 leading-tight truncate uppercase">{{ $mahasiswa->user->name }}</h5>
                                    <p class="text-[7px] text-gray-500 mt-1">Prodi: <strong class="text-gray-800">{{ $mahasiswa->prodi }}</strong> &bull; Fakultas Hukum</p>
                                    <div class="mt-1.5">
                                        <span class="inline-block bg-gradient-to-r from-[#8B1538] to-[#6D1029] text-white text-[7px] font-bold px-2.5 py-0.5 rounded-full shadow-sm">NIM: {{ $mahasiswa->nim }}</span>
                                    </div>
                                </div>

                                {{-- Event Summary --}}
                                <div class="grid grid-cols-2 gap-2 text-left text-[8px] bg-white border border-gray-100 p-2.5 rounded-lg shadow-sm">
                                    <div>
                                        <span class="text-[5.5px] font-bold uppercase text-gray-400 block tracking-wider">Tanggal & Waktu</span>
                                        <span class="font-bold text-gray-800 leading-tight block truncate mt-0.5">{{ $batch->tanggal->translatedFormat('d M Y') }}</span>
                                        <span class="text-gray-500 font-medium block mt-0.5">{{ $batch->waktu_mulai->format('H:i') }} WIB s.d Selesai</span>
                                    </div>
                                    <div>
                                        <span class="text-[5.5px] font-bold uppercase text-gray-400 block tracking-wider">Lokasi</span>
                                        <span class="font-bold text-gray-800 leading-tight block truncate mt-0.5" title="{{ $batch->lokasi }}">{{ $batch->lokasi }}</span>
                                        <span class="text-[#8B1538] font-bold block mt-0.5 truncate text-[7px]">{{ $batch->nama_batch }}</span>
                                    </div>
                                </div>

                                {{-- QR --}}
                                <div class="mt-3 flex flex-col items-center">
                                    <div class="w-14 h-14 bg-white border border-gray-100 rounded-lg p-1 shadow-sm flex items-center justify-center">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('mahasiswa.wisuda.index')) }}" alt="QR Code Verifikasi" class="w-full h-full object-contain">
                                    </div>
                                    <span class="text-[6px] text-[#b4861b] font-bold uppercase tracking-[2px] mt-1.5">Verifikasi Undangan</span>
                                </div>
                            </div>
                        </div>

                        {{-- Print Button --}}
                        <a href="{{ route('mahasiswa.wisuda.print-card') }}" target="_blank"
                            class="mt-4 w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#8B1538] to-[#6D1029] text-white py-3 rounded-xl font-bold text-sm hover:from-[#6D1029] hover:to-[#540B1E] transition-colors duration-200 shadow-md cursor-pointer">
                            <span class="material-symbols-outlined text-[18px]">print</span>
                            Cetak Kartu Undangan
                        </a>
                    </div>
                </div>

            </div>
        @endif

        {{-- ──────────────────────────────────────────────────────────
             STATUS ALERTS (pending / approved)
        ────────────────────────────────────────────────────────────── --}}
        @if($status->value === 'pending')
            @if($reg->submitted_at !== null)
                <div class="bg-blue-50/60 border border-blue-200/50 rounded-2xl p-5 flex items-start gap-4 shadow-sm">
                    <div class="w-10 h-10 bg-white border border-blue-200/50 rounded-xl flex items-center justify-center shrink-0 text-blue-600 shadow-sm">
                        <span class="material-symbols-outlined text-xl">hourglass_top</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-blue-800 mb-0.5">Pendaftaran Wisuda Menunggu Verifikasi</p>
                        <p class="text-xs text-blue-600/80 leading-relaxed">Pendaftaran wisuda Anda telah berhasil dikirim dan saat ini sedang dalam proses verifikasi oleh Admin Akademik. Harap periksa halaman ini secara berkala.</p>
                    </div>
                </div>
            @elseif(!$hasAllDocs)
                <div class="bg-amber-50/60 border border-amber-200/50 rounded-2xl p-5 flex items-start gap-4">
                    <div class="w-10 h-10 bg-white border border-amber-200/50 rounded-xl flex items-center justify-center shrink-0 text-amber-600 shadow-sm">
                        <span class="material-symbols-outlined text-xl">upload_file</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-amber-800 mb-0.5">Pendaftaran Wisuda Berhasil Dibuat</p>
                        <p class="text-xs text-amber-600/80 leading-relaxed">Silakan unggah semua dokumen wajib di bawah. Setelah lengkap, klik tombol finalisasi pendaftaran.</p>
                    </div>
                </div>
            @else
                <div class="bg-emerald-50/60 border border-emerald-200/50 rounded-2xl p-5 flex items-start gap-4 shadow-sm">
                    <div class="w-10 h-10 bg-white border border-emerald-200/50 rounded-xl flex items-center justify-center shrink-0 text-emerald-600 shadow-sm">
                        <span class="material-symbols-outlined text-xl">verified</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-emerald-800">Semua Dokumen Kelengkapan Telah Terunggah!</p>
                        <p class="text-xs text-emerald-600/80 mt-0.5 leading-relaxed">Klik tombol di bawah ini untuk mengirim pendaftaran wisuda ke Admin Akademik.</p>

                        <form action="{{ route('mahasiswa.wisuda.submit', $reg->id) }}" method="POST" class="mt-3">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Kirim pendaftaran wisuda sekarang?')"
                                class="inline-flex items-center gap-2 bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-emerald-700 transition-colors duration-200 shadow-sm cursor-pointer">
                                <span class="material-symbols-outlined text-[16px]">send</span>
                                Kirim Pendaftaran Wisuda
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        @elseif($status->value === 'approved')
            <div class="bg-green-50/60 border border-green-200/50 rounded-2xl p-5 flex items-start gap-4">
                <div class="w-10 h-10 bg-white border border-green-200/50 rounded-xl flex items-center justify-center shrink-0 text-green-600 shadow-sm">
                    <span class="material-symbols-outlined text-xl">verified_user</span>
                </div>
                <div>
                    <p class="text-sm font-bold text-green-800 mb-0.5">Pendaftaran Wisuda Disetujui</p>
                    <p class="text-xs text-green-600/80 leading-relaxed">Dokumen Anda telah diverifikasi oleh Admin. Harap tunggu admin menjadwalkan batch wisuda Anda.</p>
                </div>
            </div>
        @endif

        {{-- ──────────────────────────────────────────────────────────
             DOCUMENT UPLOAD CARDS
        ────────────────────────────────────────────────────────────── --}}
        @if($status->value !== 'scheduled')
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <h2 class="font-garamond text-base font-bold text-gray-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px] text-gray-400">folder_open</span>
                    Dokumen Kelengkapan Wisuda
                </h2>
                @php
                    $totalDocs = count(\App\Domain\Wisuda\Enums\WisudaDocumentType::cases());
                    $uploadedCount = $reg->documents()->count();
                @endphp
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                    {{ $uploadedCount === $totalDocs ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $uploadedCount }}/{{ $totalDocs }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach(\App\Domain\Wisuda\Enums\WisudaDocumentType::cases() as $type)
                    @php
                        $uploadedFile = $reg->getDocumentByType($type);
                        $isRequired = $type->isRequired();
                        $isLocked = !in_array($status->value, ['pending']) || $reg->submitted_at !== null;
                    @endphp

                    <div class="doc-card bg-white border rounded-2xl shadow-sm overflow-hidden
                        {{ $isRequired && !$uploadedFile ? 'border-red-100' : ($uploadedFile ? 'border-emerald-100' : 'border-gray-100') }}">

                        {{-- Card Header --}}
                        <div class="px-5 py-4 flex items-start justify-between gap-3 border-b
                            {{ $uploadedFile ? 'border-emerald-50 bg-emerald-50/30' : 'border-gray-50' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                                    {{ $uploadedFile ? 'bg-emerald-100 text-emerald-600' : ($isRequired ? 'bg-red-50 text-red-400' : 'bg-gray-100 text-gray-400') }}">
                                    <span class="material-symbols-outlined text-xl">{{ $uploadedFile ? 'check_circle' : 'description' }}</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-gray-800">
                                        {{ $type->label() }}
                                        @if($isRequired)<span class="text-red-500 ml-0.5">*</span>@endif
                                    </p>
                                    @if($uploadedFile)
                                        <p class="text-xs text-emerald-600 mt-0.5 flex items-center gap-1 truncate max-w-[220px]" title="{{ $uploadedFile->original_name }}">
                                            <span class="material-symbols-outlined text-[13px]">attach_file</span>
                                            {{ $uploadedFile->original_name }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            @if($uploadedFile)
                                <span class="text-[9px] bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-md font-bold uppercase tracking-wider shrink-0">
                                    Uploaded
                                </span>
                            @elseif($isRequired)
                                <span class="text-[9px] bg-red-50 text-red-600 px-2.5 py-1 rounded-md font-bold uppercase tracking-wider shrink-0">Wajib</span>
                            @endif
                        </div>

                        {{-- Upload Form / Locked --}}
                        @if(!$isLocked)
                            <form action="{{ route('mahasiswa.wisuda.upload', $reg->id) }}" method="POST" enctype="multipart/form-data" class="px-5 py-4">
                                @csrf
                                <input type="hidden" name="file_type" value="{{ $type->value }}">
                                <div class="flex items-center gap-3">
                                    <label class="flex-1 relative cursor-pointer">
                                        <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png"
                                            class="block w-full text-xs text-gray-500
                                                file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                                                file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700
                                                hover:file:bg-gray-200 file:cursor-pointer file:transition-colors file:duration-200"
                                            required>
                                    </label>
                                    <button type="submit"
                                        class="shrink-0 inline-flex items-center gap-1 bg-[#8B1538] text-white px-3.5 py-2 rounded-lg text-xs font-bold hover:bg-[#6D1029] transition-colors duration-200 shadow-sm cursor-pointer">
                                        <span class="material-symbols-outlined text-[14px]">upload</span>
                                        {{ $uploadedFile ? 'Ganti' : 'Upload' }}
                                    </button>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-2 font-medium">Format: {{ $type->acceptedMimes() }} (Maks. 5MB)</p>
                            </form>
                        @else
                            <div class="px-5 py-4 bg-gray-50/50 flex items-center justify-between text-xs text-gray-500 font-medium">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[14px] text-gray-400">lock</span>
                                    Dokumen telah dikunci untuk verifikasi.
                                </span>
                                @if($uploadedFile)
                                    <span class="text-[11px] text-gray-400 font-normal">{{ $uploadedFile->file_size_human }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    @endif

    {{-- ══════════════════════════════════════════════════════════════
         REJECTION HISTORY
    ════════════════════════════════════════════════════════════════ --}}
    @if((!isset($status) || $status->value !== 'scheduled') && isset($summary['rejected_registrations']) && $summary['rejected_registrations']->isNotEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
            <h3 class="font-garamond font-bold text-gray-800 text-base flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px] text-red-400">history</span>
                Riwayat Penolakan Pendaftaran
            </h3>

            <div class="divide-y divide-gray-100">
                @foreach($summary['rejected_registrations'] as $pastReg)
                    <div class="py-3 flex flex-col sm:flex-row justify-between gap-3 text-xs">
                        <div>
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="bg-red-50 text-red-600 font-bold px-2 py-0.5 rounded border border-red-100 uppercase tracking-wide text-[10px]">Ditolak</span>
                                <span class="text-gray-400 font-medium">Diajukan pada {{ $pastReg->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            @if($pastReg->rejection_note)
                                <p class="text-gray-600 leading-relaxed bg-red-50/30 border border-red-50 rounded-lg p-3 mt-1">
                                    <strong>Alasan Penolakan:</strong> {{ $pastReg->rejection_note }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
