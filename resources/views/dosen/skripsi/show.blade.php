@extends('layouts.app')

@section('title', 'Detail Skripsi Mahasiswa')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="relative bg-white rounded-3xl p-4 sm:p-6 border border-gray-100 shadow-sm overflow-hidden group">
        {{-- Background Accents --}}
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-[#8B1538]/[0.02] rounded-full blur-3xl transition-all duration-700 group-hover:scale-110"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-amber-500/[0.02] rounded-full blur-3xl transition-all duration-700 group-hover:scale-110"></div>

        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#8B1538] to-[#6d102c] flex items-center justify-center shadow-lg shadow-[#8B1538]/20 shrink-0">
                    <span class="material-symbols-outlined text-white text-3xl font-light">school</span>
                </div>
                <div>
                    <nav class="flex items-center gap-2 mb-1.5 px-0.5">
                        <a href="{{ route('dosen.skripsi.index') }}" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-[#8B1538] transition-colors">Bimbingan Skripsi</a>
                        <span class="material-symbols-outlined text-[14px] text-gray-300">chevron_right</span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Detail Skripsi</span>
                    </nav>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight leading-none mb-1">{{ $skripsi->mahasiswa?->user?->name }}</h1>
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="flex items-center gap-1.5 px-2.5 py-1 bg-gray-50 rounded-full border border-gray-100">
                            <span class="material-symbols-outlined text-[16px] text-gray-400 font-light">badge</span>
                            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-tight">{{ $skripsi->mahasiswa?->nim }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 px-2.5 py-1 bg-gray-50 rounded-full border border-gray-100">
                            <span class="material-symbols-outlined text-[16px] text-gray-400 font-light">account_balance</span>
                            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-tight">{{ $skripsi->mahasiswa?->prodi }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 md:self-end">
                @php 
                    $color = $skripsi->status->color(); 
                    $colorMap = [
                        'yellow' => 'amber',
                        'green' => 'emerald',
                        'blue' => 'blue',
                        'red' => 'red',
                        'purple' => 'purple',
                        'indigo' => 'indigo',
                        'gray' => 'gray',
                        'orange' => 'orange'
                    ];
                    $tColor = $colorMap[$color] ?? $color;
                    $isPendingAdmin = $skripsi->status === \App\Domain\Thesis\Enums\ThesisStatus::PROPOSAL_SUBMITTED;
                @endphp
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 pr-1">Status Progres</span>
                    <div class="px-4 py-2 rounded-2xl text-[11px] font-black uppercase tracking-wider bg-{{ $tColor }}-50 text-{{ $tColor }}-700 border border-{{ $tColor }}-100/50 shadow-sm flex items-center gap-2 {{ $isPendingAdmin ? 'animate-pulse' : '' }}">
                        <div class="w-1.5 h-1.5 rounded-full bg-{{ $tColor }}-500"></div>
                        {{ $skripsi->status->label() }}
                    </div>
                </div>
                <a href="{{ route('dosen.skripsi.index') }}" class="h-10 px-4 bg-gray-50 hover:bg-gray-100 text-gray-600 rounded-xl flex items-center gap-2 transition-all border border-gray-100 text-xs font-bold mt-4 shadow-sm">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Sidebar (Left) --}}
        <div class="lg:w-[32%] space-y-6 shrink-0">
            {{-- Profile Detail Card --}}
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden p-6 hover:shadow-md transition-all group">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 rounded-3xl bg-gray-50 flex items-center justify-center text-gray-300 group-hover:scale-105 group-hover:text-[#8B1538]/20 transition-all duration-500 overflow-hidden relative border border-gray-50 shadow-inner">
                        <span class="material-symbols-outlined text-4xl font-light">account_circle</span>
                        @if($skripsi->mahasiswa?->user?->avatar)
                        <img src="{{ Storage::url($skripsi->mahasiswa->user->avatar) }}" class="absolute inset-0 w-full h-full object-cover">
                        @endif
                    </div>
                    <div>
                        <h3 class="font-black text-gray-900 tracking-tight">Informasi Mahasiswa</h3>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest font-black mt-0.5">Sudah diverifikasi Akademik</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100/50 hover:bg-white transition-colors duration-300">
                        <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-1.5 ml-1">Nama Lengkap</p>
                        <p class="text-sm font-bold text-gray-900 leading-tight">{{ $skripsi->mahasiswa?->user?->name }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100/50 hover:bg-white transition-colors duration-300">
                        <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-1.5 ml-1">NIM / ID Mahasiswa</p>
                        <p class="text-sm font-black text-[#8B1538] leading-tight">{{ $skripsi->mahasiswa?->nim }}</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100/50 hover:bg-white transition-colors duration-300">
                        <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-1.5 ml-1">Program Studi</p>
                        <p class="text-sm font-bold text-gray-900 leading-tight">{{ $skripsi->mahasiswa?->prodi }}</p>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-dashed border-gray-100">
                    <div class="flex items-center gap-2 mb-4 text-[#8B1538]">
                        <span class="material-symbols-outlined text-[18px]">contact_support</span>
                        <p class="text-[11px] font-black uppercase tracking-widest">Hubungi Mahasiswa</p>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $skripsi->mahasiswa?->user?->phone ?? '') }}" target="_blank"
                            class="flex flex-col items-center justify-center p-3 rounded-2xl bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors gap-1 shadow-sm border border-emerald-100/50">
                            <span class="material-symbols-outlined text-[20px]">chat</span>
                            <span class="text-[10px] font-black uppercase">WhatsApp</span>
                        </a>
                        <a href="mailto:{{ $skripsi->mahasiswa?->user?->email }}"
                            class="flex flex-col items-center justify-center p-3 rounded-2xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors gap-1 shadow-sm border border-blue-100/50">
                            <span class="material-symbols-outlined text-[20px]">mail</span>
                            <span class="text-[10px] font-black uppercase">Email</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Status Card --}}
            <div class="bg-gradient-to-br from-[#8B1538] to-[#6d102c] rounded-3xl p-6 text-white shadow-xl shadow-[#8B1538]/20 relative overflow-hidden group">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/5 rounded-full blur-2xl group-hover:scale-150 transition-all duration-700"></div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center backdrop-blur-sm border border-white/20">
                            <span class="material-symbols-outlined text-white text-[20px]">analytics</span>
                        </div>
                        <h4 class="font-black text-[13px] uppercase tracking-widest">Tahapan Sekarang</h4>
                    </div>

                    @php
                        $step = $skripsi->status->step();
                        $totalSteps = 7;
                        $progress = ($step / $totalSteps) * 100;
                    @endphp

                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-[11px] font-bold text-red-100/80">Progres Langkah</p>
                            <p class="text-[11px] font-black text-red-100 uppercase tracking-widest">Langkah {{ $step }} Dari 7</p>
                        </div>
                        <div class="h-2 w-full bg-white/10 rounded-full overflow-hidden border border-white/5">
                            <div class="h-full bg-white rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(255,255,255,0.3)]" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                    
                    <p class="text-sm font-black tracking-tight leading-snug mb-2">{{ $skripsi->status->label() }}</p>
                    <p class="text-[11px] text-red-100/70 leading-relaxed font-medium">Bantu mahasiswa melalui bimbingan rutin untuk mencapai target penyelesaian skripsi tepat waktu.</p>
                </div>
            </div>
        </div>

        {{-- Main Content (Right) --}}
        <div class="flex-1 space-y-8">
            {{-- Thesis Title Card --}}
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden p-8 hover:shadow-md transition-all group relative">
                <div class="absolute top-0 right-0 p-8 text-gray-50/50 group-hover:text-amber-500/5 transition-all">
                    <span class="material-symbols-outlined text-7xl font-light">book_5</span>
                </div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-5">
                        <span class="material-symbols-outlined text-amber-500 text-[22px]">auto_stories</span>
                        <h2 class="text-xs font-black text-gray-400 uppercase tracking-widest mt-0.5">Judul Skripsi Yang Diajukan</h2>
                    </div>
                    <h2 class="text-xl font-black text-gray-900 tracking-tight leading-normal italic"> "{{ $skripsi->judul }}" </h2>
                </div>
            </div>

            {{-- Bimbingan Section (Supervisor Only) --}}
            @if($isSupervisor)

            {{-- Logbook PDF Upload Card --}}
            <div class="bg-gradient-to-br from-white to-blue-50/30 border border-blue-100/50 rounded-3xl p-6 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-6 text-blue-500/5 group-hover:text-blue-500/10 transition-all">
                    <span class="material-symbols-outlined text-6xl font-light">menu_book</span>
                </div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100">
                            <span class="material-symbols-outlined text-[24px]">picture_as_pdf</span>
                        </div>
                        <div>
                            <h2 class="text-sm font-black text-blue-900 tracking-tight leading-none mb-1 uppercase tracking-widest">Logbook Bimbingan (PDF)</h2>
                            <p class="text-[10px] text-blue-400 font-bold uppercase tracking-widest">Dokumen logbook bimbingan yang diupload mahasiswa</p>
                        </div>
                    </div>

                    @if($skripsi->logbook_file_path)
                    <div class="flex items-center justify-between bg-white/60 backdrop-blur-sm border border-blue-100/30 rounded-2xl px-5 py-4 hover:bg-white transition-all group/doc">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center text-red-500 group-hover/doc:bg-[#8B1538]/5 group-hover/doc:text-[#8B1538] transition-colors shrink-0">
                                <span class="material-symbols-outlined text-[28px]">picture_as_pdf</span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-black text-gray-700 leading-tight group-hover/doc:text-[#8B1538] transition-colors truncate">{{ $skripsi->logbook_original_name }}</p>
                                <p class="text-[10px] text-gray-400 mt-1">Diupload pada {{ $skripsi->logbook_uploaded_at?->format('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>
                        <a href="{{ route('dosen.skripsi.download', base64_encode($skripsi->logbook_file_path)) }}"
                            class="flex items-center gap-2 px-4 py-2.5 bg-[#8B1538] text-white rounded-xl text-xs font-black uppercase tracking-wider hover:bg-[#6D1029] transition-all shadow-sm shrink-0 ml-4">
                            <span class="material-symbols-outlined text-[18px]">download</span>
                            Download
                        </a>
                    </div>
                    @else
                    <div class="bg-amber-50/50 border border-amber-100 rounded-2xl p-5 text-center">
                        <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center mx-auto mb-3 text-amber-500">
                            <span class="material-symbols-outlined text-2xl">upload_file</span>
                        </div>
                        <p class="text-sm font-bold text-amber-800 mb-1">Logbook Belum Diupload</p>
                        <p class="text-xs text-amber-600">Mahasiswa belum mengupload logbook bimbingan dalam bentuk PDF.</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-[#8B1538]/5 flex items-center justify-center text-[#8B1538]">
                            <span class="material-symbols-outlined text-[24px]">history_edu</span>
                        </div>
                        <div>
                            <h2 class="text-lg font-black text-gray-900 tracking-tight leading-none mb-1">Riwayat Bimbingan</h2>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Catatan bimbingan dari sistem lama</p>
                        </div>
                    </div>
                </div>

                @if($skripsi->guidances->count())
                <div class="grid gap-4">
                    @foreach($skripsi->guidances->sortByDesc('tanggal_bimbingan') as $g)
                    @php 
                        $gc = $g->status->color(); 
                        $gcTheme = $colorMap[$gc] ?? $gc;
                    @endphp
                    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group overflow-hidden relative">
                        @if($g->status->value === 'pending')
                        <div class="absolute top-0 left-0 w-1 h-full bg-amber-400"></div>
                        @else
                        <div class="absolute top-0 left-0 w-1 h-full bg-{{ $gcTheme }}-500"></div>
                        @endif

                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-5">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-3 mb-3">
                                    <div class="flex items-center gap-1.5 text-gray-400 group-hover:text-gray-600 transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">calendar_month</span>
                                        <p class="text-[11px] font-black uppercase tracking-wider">{{ $g->tanggal_bimbingan->format('d M Y') }}</p>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-{{ $gcTheme }}-50 text-{{ $gcTheme }}-700 border border-{{ $gcTheme }}-100/50">
                                        {{ $g->status->label() }}
                                    </span>
                                </div>
                                <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100 group-hover:bg-white transition-all">
                                    <p class="text-sm font-medium text-gray-700 leading-relaxed">{{ $g->catatan }}</p>
                                </div>
                                
                                @if($g->file_path)
                                <div class="mt-4 flex items-center">
                                    <a href="{{ route('dosen.skripsi.download', base64_encode($g->file_path)) }}"
                                        class="inline-flex items-center gap-2 group/file px-3 py-2 bg-[#8B1538]/5 text-[#8B1538] rounded-xl text-xs font-black uppercase tracking-wider hover:bg-[#8B1538] hover:text-white transition-all shadow-sm">
                                        <span class="material-symbols-outlined text-[18px]">cloud_download</span>
                                        File Bimbingan
                                    </a>
                                </div>
                                @endif
                            </div>

                            @if($g->status->value === 'pending')
                            <div class="flex sm:flex-col gap-2 shrink-0 sm:pt-1">
                                <form action="{{ route('dosen.skripsi.guidance.approve', $g) }}" method="POST">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Setujui bimbingan ini?')"
                                        class="w-full sm:min-w-[100px] flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 text-white text-[11px] font-black uppercase tracking-wider rounded-xl hover:bg-emerald-700 shadow-md shadow-emerald-600/10 hover:-translate-y-0.5 transition-all">
                                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                        Setujui
                                    </button>
                                </form>
                                <div x-data="{ open: false }" class="relative w-full">
                                    <button type="button" @click="open = !open"
                                        class="w-full h-10 flex items-center justify-center gap-2 px-4 bg-red-50 text-red-600 text-[11px] font-black uppercase tracking-wider rounded-xl hover:bg-red-100 border border-red-100 transition-all">
                                        <span class="material-symbols-outlined text-[16px]">cancel</span>
                                        Tolak
                                    </button>
                                    <div x-show="open" 
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 translate-y-2"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        @click.away="open = false"
                                        class="absolute right-0 mt-3 p-4 bg-white border border-gray-100 shadow-2xl rounded-2xl min-w-[300px] z-20 shadow-[#8B1538]/10">
                                        <div class="flex items-center gap-2 mb-3 text-red-600">
                                            <span class="material-symbols-outlined text-[18px]">rate_review</span>
                                            <p class="text-[11px] font-black uppercase tracking-widest">Berikan Alasan Penolakan</p>
                                        </div>
                                        <form action="{{ route('dosen.skripsi.guidance.reject', $g) }}" method="POST">
                                            @csrf
                                            <textarea name="note" rows="3" placeholder="Tulis masukan agar mahasiswa dapat memperbaiki catatan ini..." required
                                                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-xs font-medium focus:ring-1 focus:ring-[#8B1538] focus:border-[#8B1538] transition-all bg-gray-50 mb-3"></textarea>
                                            <div class="flex gap-2">
                                                <button type="submit" class="flex-1 bg-red-600 text-white py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md shadow-red-600/10 hover:bg-red-700 transition-all">Kirim Feedback</button>
                                                <button type="button" @click="open = false" class="px-4 bg-gray-50 text-gray-400 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest border border-gray-100">Batal</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="bg-white border border-gray-100 rounded-3xl p-12 text-center group">
                    <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-4 text-gray-200 group-hover:scale-110 group-hover:text-[#8B1538]/20 transition-all duration-500">
                        <span class="material-symbols-outlined text-3xl font-light">edit_note</span>
                    </div>
                    <p class="text-xs font-bold text-gray-400 italic">Belum ada catatan bimbingan yang diajukan oleh mahasiswa.</p>
                </div>
                @endif
            </div>
            @endif

            {{-- Sidang & Documents Section --}}
            @if($skripsi->sidangSchedule && in_array($skripsi->status->value, ['SIDANG_SCHEDULED', 'SIDANG_COMPLETED', 'REVISION_UPLOADED', 'REVISION_APPROVED', 'THESIS_COMPLETED']))
            <div class="bg-gradient-to-br from-white to-indigo-50/30 border border-indigo-100/50 rounded-3xl p-6 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-6 text-indigo-500/5 group-hover:text-indigo-500/10 transition-all">
                    <span class="material-symbols-outlined text-6xl font-light">assignment</span>
                </div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100">
                            <span class="material-symbols-outlined text-[24px]">event_available</span>
                        </div>
                        <div>
                            <h2 class="text-sm font-black text-indigo-900 tracking-tight leading-none mb-1 uppercase tracking-widest">Jadwal & Dokumen Sidang</h2>
                            <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest">Informasi pendaftaran sidang mahasiswa</p>
                        </div>
                    </div>

                    @php $sch = $skripsi->sidangSchedule; @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-8">
                        <div class="p-4 bg-white rounded-2xl border border-indigo-100/50 flex items-center gap-3 group-hover:shadow-sm transition-all">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-500 shrink-0">
                                <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-indigo-300 uppercase tracking-widest leading-none mb-1">Tanggal</p>
                                <p class="text-xs font-black text-indigo-900">{{ $sch->tanggal->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="p-4 bg-white rounded-2xl border border-indigo-100/50 flex items-center gap-3 group-hover:shadow-sm transition-all text-left">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-500 shrink-0">
                                <span class="material-symbols-outlined text-[20px]">schedule</span>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-indigo-300 uppercase tracking-widest leading-none mb-1">Waktu</p>
                                <p class="text-xs font-black text-indigo-900">{{ \Carbon\Carbon::parse($sch->waktu_mulai)->format('H:i') }}{{ $sch->waktu_selesai ? ' – ' . \Carbon\Carbon::parse($sch->waktu_selesai)->format('H:i') : '' }}</p>
                            </div>
                        </div>
                        <div class="p-4 bg-white rounded-2xl border border-indigo-100/50 flex items-center gap-3 group-hover:shadow-sm transition-all text-left">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-500 shrink-0">
                                <span class="material-symbols-outlined text-[20px]">meeting_room</span>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-indigo-300 uppercase tracking-widest leading-none mb-1">Ruangan</p>
                                <p class="text-xs font-black text-indigo-900">{{ $sch->ruangan_label }}</p>
                            </div>
                        </div>
                    </div>

                    @if($skripsi->sidangRegistration)
                    <div class="space-y-3">
                        <p class="text-[10px] font-black text-indigo-300 uppercase tracking-widest ml-1 mb-1">Berkas Sidang Terlampir</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($skripsi->sidangRegistration->files->filter(fn($f) => $f->file_type->isSharedWithDosen()) as $file)
                            <div class="flex items-center justify-between bg-white/60 backdrop-blur-sm border border-indigo-100/30 rounded-2xl px-4 py-3 hover:bg-white transition-all group/doc">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-400 group-hover/doc:bg-[#8B1538]/5 group-hover/doc:text-[#8B1538] transition-colors shrink-0">
                                        <span class="material-symbols-outlined text-[18px]">description</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-black text-gray-700 leading-tight group-hover/doc:text-[#8B1538] transition-colors truncate">{{ $file->file_type->label() }}</p>
                                        <p class="text-[9px] text-gray-400 truncate mt-0.5">{{ $file->original_name }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('dosen.skripsi.download', base64_encode($file->file_path)) }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-full text-indigo-200 hover:text-indigo-600 hover:bg-indigo-50 lg:opacity-0 group-hover:opacity-100 transition-all">
                                    <span class="material-symbols-outlined text-[20px]">file_download</span>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Revision Section (Supervisor Only) --}}
            @if($isSupervisor && in_array($skripsi->status->value, ['REVISION_UPLOADED', 'REVISION_APPROVED', 'THESIS_COMPLETED']) && $skripsi->latestRevision)
            @php $rev = $skripsi->latestRevision; @endphp
            <div class="bg-gradient-to-br from-white to-amber-50/30 border border-amber-100/50 rounded-3xl p-6 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-6 text-amber-500/5 group-hover:text-amber-500/10 transition-all">
                    <span class="material-symbols-outlined text-6xl font-light">task</span>
                </div>
                <div class="relative">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 border border-amber-100">
                            <span class="material-symbols-outlined text-[24px]">new_releases</span>
                        </div>
                        <div>
                            <h2 class="text-sm font-black text-amber-900 tracking-tight leading-none mb-1 uppercase tracking-widest">Revisi Final Skripsi</h2>
                            <p class="text-[10px] text-amber-400 font-bold uppercase tracking-widest">Review dan ACC revisi akhir mahasiswa</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-5 border border-amber-100/50 mb-6">
                        <p class="text-[10px] font-black text-amber-300 uppercase tracking-widest mb-3 ml-1">Catatan Revisi dari Mahasiswa</p>
                        <p class="text-sm font-medium text-gray-700 leading-relaxed italic">"{{ $rev->notes ?? 'Tidak ada catatan tambahan.' }}"</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-4">
                        <a href="{{ route('dosen.skripsi.download', base64_encode($rev->revision_file_path)) }}"
                            class="flex-1 flex items-center justify-center gap-3 h-12 bg-white text-indigo-600 border border-indigo-100 rounded-2xl shadow-sm hover:bg-indigo-50 hover:-translate-y-0.5 transition-all text-xs font-black uppercase tracking-widest">
                            <span class="material-symbols-outlined text-[20px]">download_for_offline</span>
                            Download Berkas Revisi
                        </a>
                        
                        @if($skripsi->status->value === 'REVISION_UPLOADED')
                        <form action="{{ route('dosen.skripsi.revision.approve', $rev) }}" method="POST" class="flex-[1.5]">
                            @csrf
                            <button type="submit" onclick="return confirm('ACC revisi dan selesaikan skripsi?')"
                                class="w-full h-12 bg-emerald-600 text-white rounded-2xl shadow-lg shadow-emerald-600/10 hover:bg-emerald-700 hover:-translate-y-0.5 transition-all text-xs font-black uppercase tracking-widest flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">verified</span>
                                ACC Revisi & Selesaikan Skripsi
                            </button>
                        </form>
                        @else
                        <div class="flex-[1.5] h-12 px-6 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-2xl flex items-center justify-center gap-3">
                            <span class="material-symbols-outlined text-[20px]">check_circle</span>
                            <span class="text-xs font-black uppercase tracking-widest">Revisi Telah Disetujui</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Empty State (if no content sections active) --}}
            @if(!$isSupervisor && !$skripsi->sidangSchedule)
            <div class="bg-white border border-gray-100 rounded-3xl p-16 text-center group">
                <div class="w-20 h-20 rounded-3xl bg-gray-50 flex items-center justify-center mx-auto mb-6 text-gray-200 group-hover:scale-110 group-hover:text-[#8B1538]/20 transition-all duration-500">
                    <span class="material-symbols-outlined text-4xl font-light">visibility_off</span>
                </div>
                <h3 class="text-xl font-black text-gray-900 tracking-tight">Menunggu Proses Berlanjut</h3>
                <p class="text-sm text-gray-400 mt-3 max-w-sm mx-auto leading-relaxed font-medium">Anda belum memiliki aksi yang diperlukan untuk mahasiswa ini. Silakan cek kembali setelah mahasiswa melakukan progres atau Admin menjadwalkan sidang.</p>
                <div class="mt-8 flex items-center justify-center gap-2 text-indigo-600 bg-indigo-50 rounded-2xl px-6 py-3 w-fit mx-auto border border-indigo-100/50">
                    <span class="material-symbols-outlined text-[20px]">info</span>
                    <p class="text-[11px] font-black uppercase tracking-widest">Estimasi Sidang Akan Segera Muncul</p>
                </div>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
