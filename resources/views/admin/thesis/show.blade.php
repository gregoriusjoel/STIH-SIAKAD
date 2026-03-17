@extends('layouts.admin')

@section('title', 'Detail Skripsi Mahasiswa')
@section('page-title', 'Detail Skripsi')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="relative bg-white rounded-3xl p-4 sm:p-6 border border-gray-100 shadow-sm overflow-hidden group">
        {{-- Background Accents --}}
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-red-900/[0.03] rounded-full blur-3xl transition-all duration-700 group-hover:scale-110"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-amber-500/[0.02] rounded-full blur-3xl transition-all duration-700 group-hover:scale-110"></div>

        <div class="relative relative flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.thesis.index') }}" class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-900 transition-all group/back">
                    <span class="material-symbols-outlined text-[20px] group-hover/back:-translate-x-1 transition-transform">arrow_back</span>
                </a>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Manajemen Skripsi</span>
                        <span class="text-gray-300">/</span>
                        <span class="text-[10px] font-black text-red-900 uppercase tracking-widest">Detail Mahasiswa</span>
                    </div>
                    <h1 class="text-xl font-black text-gray-900 tracking-tight leading-none">{{ $thesis->mahasiswa?->user?->name ?? 'Mahasiswa' }}</h1>
                </div>
            </div>

            @php $color = $thesis->status->color(); @endphp
            @php $tColor = ['yellow' => 'amber', 'green' => 'emerald', 'blue' => 'blue', 'red' => 'red', 'purple' => 'purple', 'indigo' => 'indigo', 'gray' => 'gray', 'orange' => 'orange'][$color] ?? $color; @endphp
            <div class="flex items-center gap-3">
                <div class="px-4 py-2 bg-{{ $tColor }}-50 rounded-2xl border border-{{ $tColor }}-100/50 flex items-center gap-2 shadow-sm shadow-{{ $tColor }}-900/5">
                    <div class="w-2 h-2 rounded-full bg-{{ $tColor }}-500 animate-pulse"></div>
                    <span class="text-[11px] font-black text-{{ $tColor }}-700 uppercase tracking-wider">{{ $thesis->status->label() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Left Sidebar: Student Info --}}
        <div class="lg:col-span-4 space-y-6">
            {{-- Profile Card --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50 rounded-full blur-3xl -mr-16 -mt-16 group-hover:bg-red-50 transition-colors duration-500"></div>
                
                <div class="relative flex flex-col items-center text-center">
                    <div class="relative mb-4 group/avatar">
                        <div class="w-24 h-24 rounded-[2rem] bg-gradient-to-br from-gray-50 to-gray-100 p-1 shadow-inner overflow-hidden border border-gray-100">
                            @if($thesis->mahasiswa?->user?->avatar)
                                <img src="{{ Storage::url($thesis->mahasiswa->user->avatar) }}" class="w-full h-full object-cover rounded-[1.75rem]">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300 bg-white rounded-[1.75rem]">
                                    <span class="material-symbols-outlined text-4xl font-light">account_circle</span>
                                </div>
                            @endif
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-8 h-8 rounded-xl bg-white shadow-lg flex items-center justify-center text-green-500 border border-gray-50">
                            <span class="material-symbols-outlined text-[18px] fill-1">verified</span>
                        </div>
                    </div>

                    <h2 class="text-lg font-black text-gray-900 tracking-tight mb-1">{{ $thesis->mahasiswa?->user?->name ?? '-' }}</h2>
                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-4">{{ $thesis->mahasiswa?->nim ?? '-' }}</p>

                    <div class="w-full grid gap-2">
                        <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 text-left">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Program Studi</p>
                            <p class="text-[12px] font-bold text-gray-700">{{ $thesis->mahasiswa?->prodi ?? '-' }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 text-left">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Email Mahasiswa</p>
                            <p class="text-[12px] font-bold text-gray-700 truncate">{{ $thesis->mahasiswa?->user?->email ?? '-' }}</p>
                        </div>
                    </div>

                    @if($thesis->mahasiswa?->user?->phone)
                    <div class="flex gap-2 w-full mt-4">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $thesis->mahasiswa->user->phone) }}" target="_blank" class="flex-1 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center gap-2 hover:bg-green-100 transition-colors border border-green-100/50">
                            <span class="material-symbols-outlined text-[18px]">chat</span>
                            <span class="text-[10px] font-black uppercase tracking-widest">WhatsApp</span>
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Thesis Workflow Stepper --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50 rounded-full blur-3xl -mr-16 -mt-16 group-hover:bg-red-50 transition-colors duration-500"></div>
                
                <div class="relative">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-red-50 group-hover:text-red-900 transition-colors border border-gray-100 group-hover:border-red-100">
                            <span class="material-symbols-outlined text-[20px]">account_tree</span>
                        </div>
                        <h4 class="font-black text-[11px] text-gray-400 uppercase tracking-widest leading-none">Alur Kemajuan</h4>
                    </div>

                    @php
                        $currentStep = $thesis->status->step();
                        $steps = [
                            ['label' => 'Proposal', 'icon' => 'description', 'min_step' => 2],
                            ['label' => 'Bimbingan', 'icon' => 'forum', 'min_step' => 3],
                            ['label' => 'Pendaftaran', 'icon' => 'assignment_ind', 'min_step' => 4],
                            ['label' => 'Sidang', 'icon' => 'school', 'min_step' => 5],
                            ['label' => 'Selesai', 'icon' => 'verified', 'min_step' => 7],
                        ];
                    @endphp

                    <div class="space-y-0 relative">
                        {{-- Connection Line --}}
                        <div class="absolute left-[19px] top-2 bottom-6 w-0.5 bg-gray-100"></div>

                        @foreach($steps as $idx => $s)
                            @php 
                                $isDone = $currentStep >= $s['min_step'];
                                $isCurrent = $currentStep == $s['min_step'];
                                // Special logic for "Selesai" (step 7 matches self-completion)
                                if($s['label'] === 'Selesai' && $currentStep == 7) { $isDone = true; $isCurrent = true; }
                                if($s['label'] === 'Selesai' && $currentStep < 7) { $isDone = false; $isCurrent = false; }
                            @endphp
                            <div class="relative flex items-center gap-4 pb-6 last:pb-2 group/step">
                                <div class="relative z-10 w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-500 border
                                    {{ $isDone ? 'bg-red-50 text-red-900 border-red-100 shadow-sm shadow-red-900/5' : 'bg-white text-gray-300 border-gray-100' }}
                                    {{ $isCurrent ? 'ring-4 ring-red-900/5 scale-110' : '' }}">
                                    <span class="material-symbols-outlined text-[18px] {{ $isDone ? 'fill-0' : 'font-light' }}">
                                        {{ $isDone ? 'check_circle' : $s['icon'] }}
                                    </span>
                                </div>
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-[10px] font-black uppercase tracking-widest {{ $isDone ? 'text-gray-900' : 'text-gray-400' }}">
                                        {{ $s['label'] }}
                                    </span>
                                    @if($isCurrent)
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-1 h-1 rounded-full bg-red-600 animate-pulse"></div>
                                            <span class="text-[9px] font-bold text-red-900 uppercase tracking-widest">Sedang Berjalan</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
                        <p class="text-[9px] text-gray-400 uppercase tracking-widest font-black leading-none">Status Terakhir</p>
                        <span class="text-[9px] font-black text-red-900 uppercase tracking-widest bg-red-50 px-2 py-1 rounded-lg border border-red-100">{{ $thesis->status->label() }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Side: Thesis Details & Actions --}}
        <div class="lg:col-span-8 space-y-6">
            {{-- Thesis Title Card --}}
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-red-900/[0.01] rounded-full blur-3xl -mr-32 -mt-32"></div>
                
                <div class="relative">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-900">
                            <span class="material-symbols-outlined text-[18px]">menu_book</span>
                        </div>
                        <h3 class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Judul Skripsi</h3>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-gray-900 leading-tight tracking-tight mb-4 group-hover:text-red-900 transition-colors duration-500">
                        {{ $thesis->judul }}
                    </h2>
                    
                    @if($thesis->deskripsi_proposal)
                    <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100/50">
                         <p class="text-sm text-gray-500 leading-relaxed">
                            {{ $thesis->deskripsi_proposal }}
                         </p>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Pembimbing Diajukan</p>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-gray-400 border border-gray-100">
                                    <span class="material-symbols-outlined text-[16px]">person</span>
                                </div>
                                <span class="text-sm font-black text-gray-700 leading-none">{{ $thesis->requestedSupervisor?->nama ?? '-' }}</span>
                            </div>
                        </div>
                        @if($thesis->approvedSupervisor)
                        <div class="p-4 bg-emerald-50/50 rounded-2xl border border-emerald-100/50">
                            <p class="text-[9px] font-black text-emerald-600/70 uppercase tracking-widest mb-2">Pembimbing Disetujui</p>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-emerald-500 border border-emerald-100">
                                    <span class="material-symbols-outlined text-[16px] fill-1">check_circle</span>
                                </div>
                                <span class="text-sm font-black text-emerald-700 leading-none">{{ $thesis->approvedSupervisor->nama }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Proposal Actions Card --}}
            @if($thesis->status->value === 'PROPOSAL_SUBMITTED')
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-red-900/[0.01] rounded-full blur-3xl -mr-32 -mt-32"></div>
                
                <div class="relative">
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center text-red-900 shadow-sm border border-red-100/50">
                                <span class="material-symbols-outlined text-2xl">pending_actions</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-gray-900 tracking-tight leading-none mb-1 group-hover:text-red-900 transition-colors duration-500">Persetujuan Proposal</h3>
                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest leading-none">Menunggu Konfirmasi Admin</p>
                            </div>
                        </div>

                        @if($thesis->proposal_file_path)
                        <a href="{{ route('admin.thesis.download', base64_encode($thesis->proposal_file_path)) }}"
                            class="flex items-center gap-2 px-4 py-2 bg-gray-50 hover:bg-red-50 text-gray-600 hover:text-red-900 rounded-xl border border-gray-100 hover:border-red-100 transition-all text-xs font-black uppercase tracking-widest group/dl">
                            <span class="material-symbols-outlined text-[18px] group-hover/dl:translate-y-0.5 transition-transform">download</span>
                            Proposal
                        </a>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-data="{ mode: null }">
                        {{-- Approve Button --}}
                        <div x-show="mode !== 'reject'" class="contents">
                            <form action="{{ route('admin.thesis.proposal.approve', $thesis) }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="note" value="">
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyetujui proposal ini?')"
                                    class="w-full h-14 bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-emerald-500/20 hover:bg-emerald-700 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3">
                                    <span class="material-symbols-outlined text-xl">check_circle</span>
                                    Setujui Proposal
                                </button>
                            </form>
                        </div>

                        {{-- Reject Transition Logic --}}
                        <div class="w-full">
                            <button x-show="mode === null" @click="mode = 'reject'" 
                                class="w-full h-14 bg-red-50 text-red-600 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-red-100 border border-red-100 transition-all flex items-center justify-center gap-3">
                                <span class="material-symbols-outlined text-xl">cancel</span>
                                Tolak Proposal
                            </button>

                            <div x-show="mode === 'reject'" x-cloak class="space-y-3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2">
                                <form action="{{ route('admin.thesis.proposal.reject', $thesis) }}" method="POST">
                                    @csrf
                                    <div class="relative mb-3">
                                        <textarea name="reason" rows="3" placeholder="Berikan alasan penolakan agar mahasiswa dapat memperbaiki proposalnya..." required
                                            class="w-full bg-red-50/50 border-2 border-red-100 rounded-2xl px-4 py-3 text-sm text-red-900 placeholder:text-red-300 focus:outline-none focus:border-red-400 focus:ring-4 focus:ring-red-400/5 transition-all resize-none"></textarea>
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="button" @click="mode = null" class="flex-1 h-12 bg-gray-50 text-gray-500 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-100 transition-colors">
                                            Batal
                                        </button>
                                        <button type="submit" class="flex-[2] h-12 bg-red-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-red-600/20 hover:bg-red-700 transition-all">
                                            Konfirmasi Tolak
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Sidang Registration Tracking --}}
            @if($thesis->sidangRegistration)
            @php $reg = $thesis->sidangRegistration; @endphp
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="flex items-center justify-between gap-4 mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-sm border border-indigo-100/50">
                            <span class="material-symbols-outlined text-2xl">clinical_notes</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-900 tracking-tight leading-none mb-1">Pendaftaran Sidang</h3>
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full {{ $reg->status === 'verified' ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' : 'bg-amber-500' }}"></div>
                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">{{ $reg->status }}</p>
                            </div>
                        </div>
                    </div>

                    @if($reg->status === 'submitted')
                    <div class="flex gap-2" x-data="{ openReject: false }">
                        <div x-show="!openReject" class="contents">
                            <form action="{{ route('admin.thesis.sidang.verify', $reg) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Verifikasi pendaftaran sidang?')"
                                    class="h-10 px-6 bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-600/10 transition-all">
                                    Verifikasi
                                </button>
                            </form>
                            <button @click="openReject = true" class="h-10 px-6 bg-red-50 text-red-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-100 border border-red-100 transition-all">
                                Tolak
                            </button>
                        </div>

                        {{-- Reject Registration Modal --}}
                        <div x-show="openReject" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0">
                            <div class="bg-white w-full max-w-md rounded-[2rem] p-8 shadow-2xl relative" @click.away="openReject = false">
                                <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center mb-6 mx-auto">
                                    <span class="material-symbols-outlined text-3xl">cancel</span>
                                </div>
                                <h3 class="text-xl font-black text-gray-900 text-center mb-2 tracking-tight">Tolak Pendaftaran</h3>
                                <p class="text-sm text-gray-500 text-center mb-6 px-4">Berikan alasan mengapa pendaftaran sidang mahasiswa ini ditolak agar mereka dapat memperbaikinya.</p>
                                
                                <form action="{{ route('admin.thesis.sidang.reject', $reg) }}" method="POST">
                                    @csrf
                                    <textarea name="reason" rows="4" placeholder="Alasan penolakan..." required class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:border-red-400/50 transition-all resize-none mb-6"></textarea>
                                    <div class="grid grid-cols-2 gap-3">
                                        <button type="button" @click="openReject = false" class="h-12 bg-gray-50 text-gray-500 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-100 transition-colors">Batal</button>
                                        <button type="submit" class="h-12 bg-red-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-red-600/20 hover:bg-red-700 transition-all">Kirim Penolakan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @elseif($reg->status === 'verified' && $thesis->status->value === 'SIDANG_REG_SUBMITTED')
                    <a href="{{ route('admin.thesis.schedule.form', $thesis) }}"
                        class="flex items-center gap-2 h-10 px-6 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-600/10 transition-all">
                        <span class="material-symbols-outlined text-sm">calendar_month</span>
                        Tentukan Jadwal
                    </a>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($reg->files as $file)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50/30 transition-all group/file">
                        <div class="min-w-0 pr-2">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5 truncate">{{ $file->file_type->label() }}</p>
                            <p class="text-[11px] font-bold text-gray-700 truncate leading-none">{{ $file->original_name }}</p>
                        </div>
                        <a href="{{ route('admin.thesis.download', base64_encode($file->file_path)) }}" class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-gray-400 group-hover/file:text-indigo-600 transition-colors border border-gray-100">
                            <span class="material-symbols-outlined text-[18px]">download_for_offline</span>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Schedule Visualization --}}
            @if($thesis->sidangSchedule)
            @php $sch = $thesis->sidangSchedule; @endphp
            <div class="bg-gradient-to-br from-indigo-900 via-[#1e1e4a] to-[#0f172a] rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl shadow-indigo-900/20">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl"></div>

                <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 mb-8">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-3xl bg-white/10 flex items-center justify-center backdrop-blur-md border border-white/20 shadow-lg">
                            <span class="material-symbols-outlined text-white text-3xl font-light">calendar_today</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-black tracking-tight leading-none mb-2">Jadwal Sidang Skripsi</h3>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse shadow-[0_0_8px_rgba(129,140,248,0.8)]"></div>
                                <span class="text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em]">{{ $sch->tanggal->format('D, d M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    @if($thesis->status->value === 'SIDANG_SCHEDULED')
                    <form action="{{ route('admin.thesis.complete', $thesis) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Tandai sidang sebagai selesai?')"
                            class="h-12 px-8 bg-white text-indigo-900 rounded-2xl text-[10px] font-black uppercase tracking-[0.15em] hover:bg-indigo-50 shadow-xl shadow-black/20 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] fill-1">task_alt</span>
                            Selesaikan Sidang
                        </button>
                    </form>
                    @endif
                </div>

                <div class="relative grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="p-5 rounded-[2rem] bg-white/5 border border-white/10 backdrop-blur-sm group/sch hover:bg-white/10 transition-all">
                        <p class="text-[9px] font-black text-indigo-300 uppercase tracking-widest mb-3">Waktu & Lokasi</p>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-sm text-indigo-300">alarm</span>
                                <span class="text-sm font-black">{{ substr($sch->waktu_mulai, 0, 5) }} WIB</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-sm text-indigo-300">meeting_room</span>
                                <span class="text-sm font-black">{{ $sch->ruangan_label }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 rounded-[2rem] bg-white/5 border border-white/10 backdrop-blur-sm group/sch hover:bg-white/10 transition-all">
                        <p class="text-[9px] font-black text-indigo-300 uppercase tracking-widest mb-3">Tim Panel Sidang</p>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-lg bg-indigo-400/20 flex items-center justify-center text-[10px] font-black text-indigo-200">P</div>
                                <span class="text-xs font-bold truncate">{{ $sch->pembimbing?->nama ?? '-' }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-lg bg-indigo-400/20 flex items-center justify-center text-[10px] font-black text-indigo-200">U1</div>
                                <span class="text-xs font-bold truncate">{{ $sch->penguji1?->nama ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 rounded-[2rem] bg-white/5 border border-white/10 backdrop-blur-sm group/sch hover:bg-white/10 transition-all">
                        <p class="text-[9px] font-black text-indigo-300 uppercase tracking-widest mb-3 whitespace-pre"> </p>
                        <div class="space-y-3">
                             <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-lg bg-indigo-400/20 flex items-center justify-center text-[10px] font-black text-indigo-200">U2</div>
                                <span class="text-xs font-bold truncate">{{ $sch->penguji2?->nama ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Revisions Section --}}
            @if($thesis->revisions->count())
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-600 shadow-sm border border-orange-100/50">
                        <span class="material-symbols-outlined text-2xl">history_edu</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-gray-900 tracking-tight leading-none mb-1">Riwayat Revisi Skripsi</h3>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Tahap Akhir Penyelesaian</p>
                    </div>
                </div>

                <div class="relative space-y-4">
                    @foreach($thesis->revisions->sortByDesc('uploaded_at') as $rev)
                    <div class="group/rev relative pl-8 before:absolute before:left-3 before:top-4 before:bottom-0 before:w-px before:bg-gray-100 last:before:hidden">
                        <div class="absolute left-0 top-3 w-6 h-6 rounded-full bg-white border-4 border-{{ $rev->approved_at ? 'emerald' : 'orange' }}-100 flex items-center justify-center z-10 transition-transform group-hover/rev:scale-125">
                            <div class="w-1.5 h-1.5 rounded-full bg-{{ $rev->approved_at ? 'emerald' : 'orange' }}-500"></div>
                        </div>

                        <div class="bg-gray-50/50 rounded-2xl p-5 border border-gray-100 group-hover/rev:border-orange-200 group-hover/rev:bg-white group-hover/rev:shadow-xl group-hover/rev:shadow-orange-900/5 transition-all">
                            <div class="flex flex-wrap items-center justify-between gap-4 mb-3">
                                <div class="flex items-center gap-3">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $rev->uploaded_at?->format('d M Y · H:i') }}</span>
                                    @if($rev->approved_at)
                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[9px] font-black uppercase tracking-widest rounded-lg border border-emerald-100">ACC Admin</span>
                                    @endif
                                </div>
                                <a href="{{ route('admin.thesis.download', base64_encode($rev->revision_file_path)) }}" class="flex items-center gap-2 text-[10px] font-black text-red-900 uppercase tracking-widest hover:text-red-700">
                                    <span class="material-symbols-outlined text-[18px]">download</span>
                                    File Revisi
                                </a>
                            </div>

                            @if($rev->notes)
                            <p class="text-sm text-gray-600 leading-relaxed italic mb-3">"{{ $rev->notes }}"</p>
                            @endif

                            @if($rev->dosen_notes)
                            <div class="bg-white/80 rounded-xl p-3 border border-gray-200/50">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">feedback</span>
                                    Catatan Dosen Pembimbing
                                </p>
                                <p class="text-[11px] text-gray-500 italic">{{ $rev->dosen_notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Guidance History Timeline --}}
            <div x-data="{ open: false }" class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden transition-all duration-500">
                <button @click="open = !open" class="w-full flex items-center justify-between p-6 sm:p-8 hover:bg-gray-50/50 transition-colors group/btn">
                    <div class="flex items-center gap-4 text-left">
                        <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover/btn:bg-red-50 group-hover/btn:text-red-900 transition-colors">
                            <span class="material-symbols-outlined text-2xl font-light">history</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-900 tracking-tight leading-none mb-1">Riwayat Bimbingan</h3>
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Total {{ $thesis->total_bimbingan }} Sesi dari {{ $thesis->guidances->count() }} Entry</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-2xl text-gray-300 transition-transform duration-500" :class="open ? 'rotate-180 text-red-900' : ''">expand_more</span>
                </button>

                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" class="px-6 sm:px-8 pb-8 pt-2">
                    <div class="relative space-y-6 before:absolute before:left-4 before:top-2 before:bottom-2 before:w-px before:bg-gray-100">
                        @forelse($thesis->guidances->sortByDesc('tanggal_bimbingan') as $g)
                        <div class="relative pl-10">
                            <div class="absolute left-1.5 top-1 w-6 h-6 rounded-lg bg-white shadow-sm border border-gray-100 flex items-center justify-center z-10 transition-transform hover:scale-110">
                                <span class="text-[9px] font-black text-gray-400">{{ $loop->iteration }}</span>
                            </div>
                            <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100 hover:border-red-100 transition-all group/it">
                                <div class="flex flex-wrap items-center justify-between gap-4 mb-2">
                                    <h4 class="text-xs font-black text-gray-900 tracking-widest uppercase">{{ $g->tanggal_bimbingan->format('D, d M Y') }}</h4>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest 
                                        {{ $g->status->value === 'APPROVED' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-amber-50 text-amber-700 border-amber-100' }} border">
                                        {{ $g->status->label() }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 leading-relaxed mb-3">"{{ $g->catatan }}"</p>
                                
                                <div class="flex items-center justify-between gap-4 pt-3 border-t border-gray-100">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-white flex items-center justify-center text-gray-300 border border-gray-100">
                                            <span class="material-symbols-outlined text-[14px]">person</span>
                                        </div>
                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $g->dosen?->nama ?? '-' }}</span>
                                    </div>
                                    @if($g->file_path)
                                    <a href="{{ route('admin.thesis.download', base64_encode($g->file_path)) }}" class="flex items-center gap-1.5 text-[9px] font-black text-red-900 uppercase tracking-widest hover:underline">
                                        <span class="material-symbols-outlined text-[16px]">attachment</span>
                                        Download
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="flex flex-col items-center justify-center py-10 opacity-50">
                            <span class="material-symbols-outlined text-4xl font-light mb-2">history_toggle_off</span>
                            <p class="text-xs font-bold uppercase tracking-widest">Belum ada data bimbingan.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>
@endsection
