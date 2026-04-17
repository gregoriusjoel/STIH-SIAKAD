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
                <a href="{{ route('admin.skripsi.index') }}" class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-900 transition-all group/back">
                    <span class="material-symbols-outlined text-[20px] group-hover/back:-translate-x-1 transition-transform">arrow_back</span>
                </a>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Manajemen Skripsi</span>
                        <span class="text-gray-300">/</span>
                        <span class="text-[10px] font-black text-red-900 uppercase tracking-widest">Detail Mahasiswa</span>
                    </div>
                    <h1 class="text-xl font-black text-gray-900 tracking-tight leading-none">{{ $skripsi->mahasiswa?->user?->name ?? 'Mahasiswa' }}</h1>
                </div>
            </div>

            @php $color = $skripsi->status->color(); @endphp
            @php $tColor = ['yellow' => 'amber', 'green' => 'emerald', 'blue' => 'blue', 'red' => 'red', 'purple' => 'purple', 'indigo' => 'indigo', 'gray' => 'gray', 'orange' => 'orange'][$color] ?? $color; @endphp
            <div class="flex items-center gap-3">
                <div class="px-4 py-2 bg-{{ $tColor }}-50 rounded-2xl border border-{{ $tColor }}-100/50 flex items-center gap-2 shadow-sm shadow-{{ $tColor }}-900/5">
                    <div class="w-2 h-2 rounded-full bg-{{ $tColor }}-500 animate-pulse"></div>
                    <span class="text-[11px] font-black text-{{ $tColor }}-700 uppercase tracking-wider">{{ $skripsi->status->label() }}</span>
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
                            @if($skripsi->mahasiswa?->user?->avatar)
                                <img src="{{ Storage::url($skripsi->mahasiswa->user->avatar) }}" class="w-full h-full object-cover rounded-[1.75rem]">
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

                    <h2 class="text-lg font-black text-gray-900 tracking-tight mb-1">{{ $skripsi->mahasiswa?->user?->name ?? '-' }}</h2>
                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-4">{{ $skripsi->mahasiswa?->nim ?? '-' }}</p>

                    <div class="w-full grid gap-2">
                        <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 text-left">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Program Studi</p>
                            <p class="text-[12px] font-bold text-gray-700">{{ $skripsi->mahasiswa?->prodi ?? '-' }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 text-left">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Email Mahasiswa</p>
                            <p class="text-[12px] font-bold text-gray-700 truncate">{{ $skripsi->mahasiswa?->user?->email ?? '-' }}</p>
                        </div>
                    </div>

                    @if($skripsi->mahasiswa?->user?->phone)
                    <div class="flex gap-2 w-full mt-4">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $skripsi->mahasiswa->user->phone) }}" target="_blank" class="flex-1 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center gap-2 hover:bg-green-100 transition-colors border border-green-100/50">
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
                        $currentStep = $skripsi->status->step();
                        $steps = [
                            ['label' => 'Skripsi', 'icon' => 'description', 'min_step' => 2],
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
                        <span class="text-[9px] font-black text-red-900 uppercase tracking-widest bg-red-50 px-2 py-1 rounded-lg border border-red-100">{{ $skripsi->status->label() }}</span>
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
                        {{ $skripsi->judul }}
                    </h2>
                    
                    @if($skripsi->deskripsi_proposal)
                    <div class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100/50">
                         <p class="text-sm text-gray-500 leading-relaxed">
                            {{ $skripsi->deskripsi_proposal }}
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
                                <span class="text-sm font-black text-gray-700 leading-none">{{ $skripsi->requestedSupervisor?->nama ?? '-' }}</span>
                            </div>
                        </div>
                        @if($skripsi->approvedSupervisor)
                        <div class="p-4 bg-emerald-50/50 rounded-2xl border border-emerald-100/50">
                            <p class="text-[9px] font-black text-emerald-600/70 uppercase tracking-widest mb-2">Pembimbing Disetujui</p>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-emerald-500 border border-emerald-100">
                                    <span class="material-symbols-outlined text-[16px] fill-1">check_circle</span>
                                </div>
                                <span class="text-sm font-black text-emerald-700 leading-none">{{ $skripsi->approvedSupervisor->nama }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Skripsi Actions Card --}}
            @if($skripsi->status->value === 'PROPOSAL_SUBMITTED')
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-red-900/[0.01] rounded-full blur-3xl -mr-32 -mt-32"></div>
                
                <div class="relative">
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center text-red-900 shadow-sm border border-red-100/50">
                                <span class="material-symbols-outlined text-2xl">pending_actions</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-gray-900 tracking-tight leading-none mb-1 group-hover:text-red-900 transition-colors duration-500">Persetujuan Skripsi</h3>
                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest leading-none">Menunggu Konfirmasi Admin</p>
                            </div>
                        </div>

                        @if($skripsi->proposal_file_path)
                        <a href="{{ route('admin.skripsi.download', base64_encode($skripsi->proposal_file_path)) }}"
                            class="flex items-center gap-2 px-4 py-2 bg-gray-50 hover:bg-red-50 text-gray-600 hover:text-red-900 rounded-xl border border-gray-100 hover:border-red-100 transition-all text-xs font-black uppercase tracking-widest group/dl">
                            <span class="material-symbols-outlined text-[18px] group-hover/dl:translate-y-0.5 transition-transform">download</span>
                            Skripsi
                        </a>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" x-data="{ mode: null }">
                        {{-- Approve Button --}}
                        <div x-show="mode !== 'reject'" class="contents">
                            <form action="{{ route('admin.skripsi.proposal.approve', $skripsi) }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="note" value="">
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyetujui pengajuan skripsi ini?')"
                                    class="w-full h-14 bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-emerald-500/20 hover:bg-emerald-700 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3">
                                    <span class="material-symbols-outlined text-xl">check_circle</span>
                                    Setujui Skripsi
                                </button>
                            </form>
                        </div>

                        {{-- Reject Transition Logic --}}
                        <div class="w-full">
                            <button x-show="mode === null" @click="mode = 'reject'" 
                                class="w-full h-14 bg-red-50 text-red-600 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-red-100 border border-red-100 transition-all flex items-center justify-center gap-3">
                                <span class="material-symbols-outlined text-xl">cancel</span>
                                Tolak Skripsi
                            </button>

                            <div x-show="mode === 'reject'" x-cloak class="space-y-3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2">
                                <form action="{{ route('admin.skripsi.proposal.reject', $skripsi) }}" method="POST">
                                    @csrf
                                    <div class="relative mb-3">
                                        <textarea name="reason" rows="3" placeholder="Berikan alasan penolakan agar mahasiswa dapat memperbaiki skripsinya..." required
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
            @if($skripsi->sidangRegistration)
            @php $reg = $skripsi->sidangRegistration; @endphp
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="flex items-center justify-between gap-4 mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#8B1538]/5 flex items-center justify-center text-[#8B1538] shadow-sm border border-red-100/50">
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
                            <form action="{{ route('admin.skripsi.sidang.verify', $reg) }}" method="POST">
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
                                
                                <form action="{{ route('admin.skripsi.sidang.reject', $reg) }}" method="POST">
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
                    @elseif($reg->status === 'verified' && $skripsi->status->value === 'SIDANG_REG_SUBMITTED')
                    <a href="{{ route('admin.skripsi.schedule.form', $skripsi) }}"
                        class="flex items-center gap-2 h-10 px-6 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-600/10 transition-all">
                        <span class="material-symbols-outlined text-sm">calendar_month</span>
                        Tentukan Jadwal
                    </a>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3"
                    x-data="{
                        previewOpen: false,
                        previewUrl: '',
                        previewName: '',
                        previewType: '',
                        downloadUrl: '',
                        openPreview(url, name, type, dlUrl) {
                            this.previewUrl = url;
                            this.previewName = name;
                            this.previewType = type;
                            this.downloadUrl = dlUrl;
                            this.previewOpen = true;
                        }
                    }">
                    @foreach($reg->files as $file)
                    @php
                        $dlUrl = route('admin.skripsi.download', base64_encode($file->file_path));
                        $pvUrl = route('admin.skripsi.preview', base64_encode($file->file_path));
                        $ext = strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION));
                        $isPreviewable = in_array($ext, ['pdf', 'png', 'jpg', 'jpeg', 'gif', 'webp']);
                        $previewUrl = $isPreviewable ? $pvUrl : '';
                    @endphp
                    <button type="button"
                        @click="openPreview('{{ $previewUrl }}', '{{ addslashes($file->original_name) }}', '{{ $ext }}', '{{ $dlUrl }}')"
                        class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-red-200 hover:bg-[#8B1538]/5 hover:shadow-md hover:-translate-y-0.5 transition-all group/file cursor-pointer text-left w-full">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                                {{ in_array($ext, ['pdf']) ? 'bg-red-50 text-red-500' : (in_array($ext, ['doc','docx']) ? 'bg-blue-50 text-blue-500' : (in_array($ext, ['ppt','pptx']) ? 'bg-orange-50 text-orange-500' : (in_array($ext, ['png','jpg','jpeg','gif','webp']) ? 'bg-purple-50 text-purple-500' : 'bg-gray-100 text-gray-400'))) }}">
                                <span class="material-symbols-outlined text-xl">
                                    {{ in_array($ext, ['pdf']) ? 'picture_as_pdf' : (in_array($ext, ['png','jpg','jpeg','gif','webp']) ? 'image' : (in_array($ext, ['ppt','pptx']) ? 'slideshow' : 'description')) }}
                                </span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5 truncate">{{ $file->file_type->label() }}</p>
                                <p class="text-[11px] font-bold text-gray-700 truncate leading-none">{{ $file->original_name }}</p>
                            </div>
                        </div>
                        <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-gray-400 group-hover/file:text-[#8B1538] transition-colors border border-gray-100 shrink-0 ml-2">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                        </div>
                    </button>
                    @endforeach

                    {{-- Preview Modal --}}
                    <template x-teleport="body">
                        <div x-show="previewOpen" x-cloak
                            class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-gray-900/70 backdrop-blur-sm"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            @keydown.escape.window="previewOpen = false">

                            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl max-h-[92vh] flex flex-col overflow-hidden"
                                @click.away="previewOpen = false"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                                {{-- Modal Header --}}
                                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-[#8B1538]/5 flex items-center justify-center text-[#8B1538] shrink-0">
                                            <span class="material-symbols-outlined text-xl">preview</span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Preview Dokumen</p>
                                            <p class="text-sm font-bold text-gray-800 truncate" x-text="previewName"></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <a :href="downloadUrl"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-[#8B1538] text-white rounded-xl text-xs font-bold hover:bg-[#6D1029] transition-all shadow-sm">
                                            <span class="material-symbols-outlined text-[16px]">download</span>
                                            Download
                                        </a>
                                        <button @click="previewOpen = false"
                                            class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-xl flex items-center justify-center text-gray-500 transition-colors">
                                            <span class="material-symbols-outlined text-xl">close</span>
                                        </button>
                                    </div>
                                </div>

                                {{-- Modal Body --}}
                                <div class="flex-1 overflow-hidden bg-gray-50 min-h-0">
                                    {{-- PDF Preview --}}
                                    <template x-if="previewType === 'pdf' && previewUrl">
                                        <iframe :src="previewUrl" class="w-full h-full border-0" style="min-height: 70vh;"></iframe>
                                    </template>

                                    {{-- Image Preview --}}
                                    <template x-if="['png','jpg','jpeg','gif','webp'].includes(previewType) && previewUrl">
                                        <div class="flex items-center justify-center p-8 h-full" style="min-height: 70vh;">
                                            <img :src="previewUrl" :alt="previewName" class="max-w-full max-h-[75vh] rounded-xl shadow-lg object-contain">
                                        </div>
                                    </template>

                                    {{-- Non-previewable file --}}
                                    <template x-if="!previewUrl || !['pdf','png','jpg','jpeg','gif','webp'].includes(previewType)">
                                        <div class="flex flex-col items-center justify-center py-20" style="min-height: 40vh;">
                                            <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mb-6">
                                                <span class="material-symbols-outlined text-4xl text-gray-300">visibility_off</span>
                                            </div>
                                            <p class="text-base font-bold text-gray-700 mb-1">Preview Tidak Tersedia</p>
                                            <p class="text-sm text-gray-400 mb-6">Format file ini tidak dapat dipratinjau di browser.</p>
                                            <a :href="downloadUrl"
                                                class="inline-flex items-center gap-2 px-6 py-3 bg-[#8B1538] text-white rounded-xl font-bold text-sm hover:bg-[#6D1029] transition-all shadow-md">
                                                <span class="material-symbols-outlined text-[18px]">download</span>
                                                Download File
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            @endif

            {{-- Schedule Visualization --}}
            @if($skripsi->sidangSchedule)
            @php $sch = $skripsi->sidangSchedule; @endphp
            <div class="bg-gradient-to-br from-[#4A0A1A] via-[#6D1029] to-[#8B1538] rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl shadow-[#8B1538]/20">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-rose-500/10 rounded-full blur-3xl"></div>

                <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 mb-8">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-3xl bg-white/10 flex items-center justify-center backdrop-blur-md border border-white/20 shadow-lg">
                            <span class="material-symbols-outlined text-white text-3xl font-light">calendar_today</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-black tracking-tight leading-none mb-2">Jadwal Sidang Skripsi</h3>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-rose-400 animate-pulse shadow-[0_0_8px_rgba(251,113,133,0.8)]"></div>
                                <span class="text-[10px] font-black text-rose-200 uppercase tracking-[0.2em]">{{ $sch->tanggal->locale('id')->isoFormat('dddd, DD MMMM Y') }}</span>
                            </div>
                        </div>
                    </div>

                    @if($skripsi->status->value === 'SIDANG_SCHEDULED')
                    <form action="{{ route('admin.skripsi.complete', $skripsi) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Tandai sidang sebagai selesai?')"
                            class="h-12 px-8 bg-white text-[#8B1538] rounded-2xl text-[10px] font-black uppercase tracking-[0.15em] hover:bg-rose-50 shadow-xl shadow-black/20 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px] fill-1">task_alt</span>
                            Selesaikan Sidang
                        </button>
                    </form>
                    @endif
                </div>

                <div class="relative grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="p-5 rounded-[2rem] bg-white/5 border border-white/10 backdrop-blur-sm group/sch hover:bg-white/10 transition-all">
                        <p class="text-[9px] font-black text-rose-200 uppercase tracking-widest mb-3">Waktu & Lokasi</p>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-sm text-rose-200">alarm</span>
                                <span class="text-sm font-black">{{ \Carbon\Carbon::parse($sch->waktu_mulai)->format('H:i') }}{{ $sch->waktu_selesai ? ' - ' . \Carbon\Carbon::parse($sch->waktu_selesai)->format('H:i') : '' }} WIB</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-sm text-rose-200">meeting_room</span>
                                <span class="text-sm font-black">{{ $sch->ruangan_label }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 rounded-[2rem] bg-white/5 border border-white/10 backdrop-blur-sm group/sch hover:bg-white/10 transition-all">
                        <p class="text-[9px] font-black text-rose-200 uppercase tracking-widest mb-3">Tim Panel Sidang</p>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-lg bg-rose-400/20 flex items-center justify-center text-[10px] font-black text-rose-200">P</div>
                                <span class="text-xs font-bold truncate">{{ $sch->pembimbing?->nama ?? '-' }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-lg bg-rose-400/20 flex items-center justify-center text-[10px] font-black text-rose-200">U1</div>
                                <span class="text-xs font-bold truncate">{{ $sch->penguji1?->nama ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 rounded-[2rem] bg-white/5 border border-white/10 backdrop-blur-sm group/sch hover:bg-white/10 transition-all">
                        <p class="text-[9px] font-black text-rose-200 uppercase tracking-widest mb-3 whitespace-pre"> </p>
                        <div class="space-y-3">
                             <div class="flex items-center gap-3">
                                <div class="w-6 h-6 rounded-lg bg-rose-400/20 flex items-center justify-center text-[10px] font-black text-rose-200">U2</div>
                                <span class="text-xs font-bold truncate">{{ $sch->penguji2?->nama ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                {{-- Left Side: Revisions --}}
                <div class="h-full">
                    @if($skripsi->revisions->count())
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm relative overflow-hidden group h-full">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 rounded-2xl bg-[#8B1538]/5 flex items-center justify-center text-[#8B1538] shadow-sm border border-red-100/50">
                        <span class="material-symbols-outlined text-2xl">history_edu</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-gray-900 tracking-tight leading-none mb-1">Riwayat Revisi Skripsi</h3>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Tahap Akhir Penyelesaian</p>
                    </div>
                </div>

                <div class="relative space-y-4">
                    @foreach($skripsi->revisions->sortByDesc('uploaded_at') as $rev)
                    <div class="group/rev relative pl-8 before:absolute before:left-3 before:top-4 before:bottom-0 before:w-px before:bg-gray-100 last:before:hidden">
                        <div class="absolute left-0 top-3 w-6 h-6 rounded-full bg-white border-4 border-{{ $rev->approved_at ? 'emerald' : 'orange' }}-100 flex items-center justify-center z-10 transition-transform group-hover/rev:scale-125">
                            <div class="w-1.5 h-1.5 rounded-full bg-{{ $rev->approved_at ? 'emerald' : 'orange' }}-500"></div>
                        </div>

                        <div class="bg-gray-50/50 rounded-2xl p-5 border border-gray-100 group-hover/rev:border-red-200 group-hover/rev:bg-white group-hover/rev:shadow-xl group-hover/rev:shadow-[#8B1538]/5 transition-all">
                            <div class="flex flex-wrap items-center justify-between gap-4 mb-3">
                                <div class="flex items-center gap-3">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $rev->uploaded_at?->format('d M Y · H:i') }}</span>
                                    @if($rev->approved_at)
                                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[9px] font-black uppercase tracking-widest rounded-lg border border-emerald-100">ACC Admin</span>
                                    @endif
                                </div>
                                <a href="{{ route('admin.skripsi.download', base64_encode($rev->revision_file_path)) }}" class="flex items-center gap-2 text-[10px] font-black text-red-900 uppercase tracking-widest hover:text-red-700">
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
                </div>

                {{-- Right Side: Riwayat Bimbingan --}}
                <div class="h-full">
                    @if($skripsi->logbook_file_path)
                    @php
                        $logbookDlUrl = route('admin.skripsi.download', base64_encode($skripsi->logbook_file_path));
                        $logbookPvUrl = route('admin.skripsi.preview', base64_encode($skripsi->logbook_file_path));
                    @endphp
                    <div x-data="{ openLogbook: false }" class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden transition-all h-full flex flex-col justify-center">
                    <div class="flex flex-col sm:flex-row items-center justify-between p-6 hover:bg-gray-50/50 transition-colors gap-4 group/lb border-b border-gray-50 flex-wrap">
                        <div class="flex items-center gap-4 text-left w-full">
                            <div class="w-12 h-12 rounded-2xl bg-[#8B1538]/5 text-[#8B1538] border border-red-100/50 flex items-center justify-center shrink-0 shadow-sm">
                                <span class="material-symbols-outlined text-2xl">history</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="text-lg font-black text-gray-900 tracking-tight leading-none mb-1">Riwayat Bimbingan</h3>
                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest truncate">{{ $skripsi->logbook_original_name }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2 shrink-0 w-full sm:w-auto mt-2">
                            <button @click="openLogbook = true" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 bg-[#8B1538]/5 text-[#8B1538] rounded-xl text-[10px] font-black uppercase tracking-wider hover:bg-[#8B1538] hover:text-white transition-all shadow-sm">
                                <span class="material-symbols-outlined text-[16px]">visibility</span>
                                Preview
                            </button>
                            <a href="{{ $logbookDlUrl }}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 bg-white text-gray-700 border border-gray-200 rounded-xl text-[10px] font-black uppercase tracking-wider hover:bg-gray-50 transition-all shadow-sm">
                                <span class="material-symbols-outlined text-[16px]">download</span>
                                Download
                            </a>
                        </div>
                </div>

                <template x-teleport="body">
                    <div x-show="openLogbook" x-cloak
                        class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-gray-900/70 backdrop-blur-sm"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        @keydown.escape.window="openLogbook = false">

                        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl max-h-[92vh] flex flex-col overflow-hidden"
                            @click.away="openLogbook = false"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-xl bg-[#8B1538]/5 flex items-center justify-center text-[#8B1538] shrink-0">
                                        <span class="material-symbols-outlined text-xl">menu_book</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Preview Logbook</p>
                                        <p class="text-sm font-bold text-gray-800 truncate">{{ $skripsi->logbook_original_name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="{{ $logbookDlUrl }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#8B1538] text-white rounded-xl text-xs font-bold hover:bg-[#6D1029] transition-all shadow-sm">
                                        <span class="material-symbols-outlined text-[16px]">download</span>
                                        Download
                                    </a>
                                    <button @click="openLogbook = false"
                                        class="w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-xl flex items-center justify-center text-gray-500 transition-colors">
                                        <span class="material-symbols-outlined text-xl">close</span>
                                    </button>
                                </div>
                            </div>
                            <div class="flex-1 overflow-hidden bg-gray-50 min-h-0">
                                <iframe src="{{ $logbookPvUrl }}" class="w-full h-full border-0" style="min-height: 70vh;"></iframe>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
                    </div>
                    @else
                    <div class="bg-gray-50 border border-gray-100 rounded-3xl p-8 flex flex-col items-center justify-center text-center h-full w-full">
                        <div class="w-16 h-16 rounded-2xl bg-white shadow-sm flex items-center justify-center text-gray-300 mb-4">
                            <span class="material-symbols-outlined text-3xl font-light">history_toggle_off</span>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 tracking-tight leading-none mb-2">Belum Ada Riwayat Bimbingan</h3>
                        <p class="text-[11px] text-gray-500 max-w-[200px] leading-tight mt-1">File logbook PDF belum diunggah.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>
@endsection
