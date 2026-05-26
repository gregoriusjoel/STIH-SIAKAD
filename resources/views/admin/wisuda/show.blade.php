@extends('layouts.admin')

@section('title', 'Detail Registrasi Wisuda')
@section('page-title', 'Detail Registrasi Wisuda')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="relative bg-white rounded-3xl p-4 sm:p-6 border border-gray-100 shadow-sm overflow-hidden group">
        {{-- Background Accents --}}
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-red-900/[0.03] rounded-full blur-3xl transition-all duration-700 group-hover:scale-110"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-amber-500/[0.02] rounded-full blur-3xl transition-all duration-700 group-hover:scale-110"></div>

        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.wisuda.index') }}" class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-900 transition-all group/back">
                    <span class="material-symbols-outlined text-[20px] group-hover/back:-translate-x-1 transition-transform">arrow_back</span>
                </a>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Manajemen Wisuda</span>
                        <span class="text-gray-300">/</span>
                        <span class="text-[10px] font-black text-red-900 uppercase tracking-widest">Detail Pendaftaran</span>
                    </div>
                    <h1 class="text-xl font-black text-gray-900 tracking-tight leading-none">{{ $registration->mahasiswa?->nama ?? 'Mahasiswa' }}</h1>
                </div>
            </div>

            @php
                $color = $registration->status->color();
                $tColor = ['yellow' => 'amber', 'green' => 'emerald', 'blue' => 'blue', 'red' => 'red', 'purple' => 'purple', 'indigo' => 'indigo', 'gray' => 'gray', 'orange' => 'orange'][$color] ?? $color;
            @endphp
            <div class="flex items-center gap-3">
                <div class="px-4 py-2 bg-{{ $tColor }}-50 rounded-2xl border border-{{ $tColor }}-100/50 flex items-center gap-2 shadow-sm shadow-{{ $tColor }}-900/5">
                    <div class="w-2 h-2 rounded-full bg-{{ $tColor }}-500 animate-pulse"></div>
                    <span class="text-[11px] font-black text-{{ $tColor }}-700 uppercase tracking-wider">{{ $registration->status->label() }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-emerald-600 mt-0.5">check_circle</span>
            <div>
                <p class="text-sm font-bold text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-red-600 mt-0.5">error</span>
            <div>
                <p class="text-sm font-bold text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Left Sidebar: Student Info --}}
        <div class="lg:col-span-4 space-y-6">
            {{-- Profile Card --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50 rounded-full blur-3xl -mr-16 -mt-16 group-hover:bg-red-50 transition-colors duration-500"></div>
                
                <div class="relative flex flex-col items-center text-center">
                    <div class="relative mb-4 group/avatar">
                        <div class="w-24 h-24 rounded-[2rem] bg-gradient-to-br from-gray-50 to-gray-100 p-1 shadow-inner overflow-hidden border border-gray-100 flex items-center justify-center">
                            @if($registration->mahasiswa?->foto)
                                <img src="{{ $registration->mahasiswa->foto_url }}" class="w-full h-full object-cover rounded-[1.75rem]">
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

                    <h2 class="text-lg font-black text-gray-900 tracking-tight mb-1">{{ $registration->mahasiswa?->nama ?? '-' }}</h2>
                    <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-4">{{ $registration->mahasiswa?->nim ?? '-' }}</p>

                    <div class="w-full grid gap-2">
                        <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 text-left">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Program Studi</p>
                            <p class="text-[12px] font-bold text-gray-700">{{ $registration->mahasiswa?->prodiData?->nama ?? $registration->mahasiswa?->prodi ?? '-' }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 text-left">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Email Aktif</p>
                            <p class="text-[12px] font-bold text-gray-700 truncate">{{ $registration->email_aktif ?? '-' }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-2xl border border-gray-100 text-left">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Nomor Telepon/WA</p>
                            <p class="text-[12px] font-bold text-gray-700 truncate">{{ $registration->no_hp ?? '-' }}</p>
                        </div>
                    </div>

                    @if($registration->no_hp)
                    <div class="flex gap-2 w-full mt-4">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $registration->no_hp) }}" target="_blank" class="flex-1 h-10 rounded-xl bg-green-50 text-green-600 flex items-center justify-center gap-2 hover:bg-green-100 transition-colors border border-green-100/50">
                            <span class="material-symbols-outlined text-[18px]">chat</span>
                            <span class="text-[10px] font-black uppercase tracking-widest">WhatsApp</span>
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Graduation Steps --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50 rounded-full blur-3xl -mr-16 -mt-16 group-hover:bg-red-50 transition-colors duration-500"></div>
                
                <div class="relative">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-red-50 group-hover:text-red-900 transition-colors border border-gray-100 group-hover:border-red-100">
                            <span class="material-symbols-outlined text-[20px]">account_tree</span>
                        </div>
                        <h4 class="font-black text-[11px] text-gray-400 uppercase tracking-widest leading-none">Status Alur Wisuda</h4>
                    </div>

                    @php
                        $statusValue = $registration->status->value;
                        $flow = [
                            ['label' => 'Diajukan', 'icon' => 'assignment', 'active' => true],
                            ['label' => 'Verifikasi Dokumen', 'icon' => 'verified', 'active' => in_array($statusValue, ['approved', 'scheduled'])],
                            ['label' => 'Jadwal Ditentukan', 'icon' => 'event_available', 'active' => $statusValue === 'scheduled'],
                        ];
                    @endphp

                    <div class="space-y-0 relative">
                        {{-- Connection Line --}}
                        <div class="absolute left-[19px] top-2 bottom-6 w-0.5 bg-gray-100"></div>

                        @foreach($flow as $idx => $step)
                            @php
                                $isDone = $step['active'];
                                $isCurrent = ($idx === 0 && $statusValue === 'pending') ||
                                             ($idx === 1 && $statusValue === 'approved') ||
                                             ($idx === 2 && $statusValue === 'scheduled');
                            @endphp
                            <div class="relative flex items-center gap-4 pb-6 last:pb-2 group/step">
                                <div class="relative z-10 w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-500 border
                                    {{ $isDone ? 'bg-red-50 text-red-900 border-red-100 shadow-sm shadow-red-900/5' : 'bg-white text-gray-300 border-gray-100' }}
                                    {{ $isCurrent ? 'ring-4 ring-red-900/5 scale-110' : '' }}">
                                    <span class="material-symbols-outlined text-[18px] {{ $isDone ? 'fill-0' : 'font-light' }}">
                                        {{ $isDone ? ($isCurrent && $statusValue === 'pending' ? 'pending_actions' : 'check_circle') : $step['icon'] }}
                                    </span>
                                </div>
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-[10px] font-black uppercase tracking-widest {{ $isDone ? 'text-gray-900' : 'text-gray-400' }}">
                                        {{ $step['label'] }}
                                    </span>
                                    @if($isCurrent)
                                        <div class="flex items-center gap-1.5">
                                            <div class="w-1 h-1 rounded-full bg-red-600 animate-pulse"></div>
                                            <span class="text-[9px] font-bold text-red-900 uppercase tracking-widest">Status Aktif</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($registration->reviewer)
                        <div class="mt-4 pt-4 border-t border-gray-50 flex flex-col gap-2">
                            <div class="flex items-center justify-between">
                                <p class="text-[9px] text-gray-400 uppercase tracking-widest font-black leading-none">Diverifikasi Oleh</p>
                                <span class="text-[10px] font-bold text-gray-700 leading-none">{{ $registration->reviewer->name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-[9px] text-gray-400 uppercase tracking-widest font-black leading-none">Pada Tanggal</p>
                                <span class="text-[10px] font-bold text-gray-500 leading-none">{{ $registration->reviewed_at?->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Side: Graduation Details & Documents & Actions --}}
        <div class="lg:col-span-8 space-y-6">
            {{-- Skripsi Info Card --}}
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-red-900/[0.01] rounded-full blur-3xl -mr-32 -mt-32"></div>
                
                <div class="relative">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-900 border border-red-100/50">
                            <span class="material-symbols-outlined text-[18px]">menu_book</span>
                        </div>
                        <h3 class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Judul Skripsi Diselesaikan</h3>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-gray-900 leading-tight tracking-tight mb-4 group-hover:text-red-900 transition-colors duration-500">
                        {{ $registration->skripsiSubmission->judul ?? '-' }}
                    </h2>
                    
                    @if($registration->skripsiSubmission?->approvedSupervisor)
                    <div class="flex items-center gap-3 mt-4 p-4 bg-gray-50 rounded-2xl border border-gray-100 w-fit">
                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-gray-400 border border-gray-100">
                            <span class="material-symbols-outlined text-[16px]">person</span>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Dosen Pembimbing</p>
                            <span class="text-sm font-black text-gray-700 leading-none">{{ $registration->skripsiSubmission->approvedSupervisor->nama }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Verification Action Form (Only for Pending registrations) --}}
            @if($statusValue === 'pending')
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-red-900/[0.01] rounded-full blur-3xl -mr-32 -mt-32"></div>
                
                <div class="relative" x-data="{ mode: null }">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-red-50 flex items-center justify-center text-red-900 shadow-sm border border-red-100/50">
                            <span class="material-symbols-outlined text-2xl font-light">verified_user</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-900 tracking-tight leading-none mb-1">Keputusan Verifikasi</h3>
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest leading-none">Pastikan seluruh dokumen di bawah ini valid dan lengkap</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Approve Button/Form --}}
                        <div x-show="mode !== 'reject'" class="contents">
                            <form action="{{ route('admin.wisuda.approve', $registration->id) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyetujui pendaftaran wisuda ini?')"
                                    class="w-full h-14 bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-emerald-500/20 hover:bg-emerald-700 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3">
                                    <span class="material-symbols-outlined text-xl">check_circle</span>
                                    Setujui Pendaftaran
                                </button>
                            </form>
                        </div>

                        {{-- Reject Transition --}}
                        <div class="w-full col-span-1 sm:col-span-1" x-show="mode === null">
                            <button @click="mode = 'reject'" 
                                class="w-full h-14 bg-red-50 text-red-600 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-red-100 border border-red-100 transition-all flex items-center justify-center gap-3">
                                <span class="material-symbols-outlined text-xl">cancel</span>
                                Tolak Pendaftaran
                            </button>
                        </div>

                        {{-- Rejection Form Field --}}
                        <div class="col-span-1 sm:col-span-2 w-full" x-show="mode === 'reject'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2">
                            <form action="{{ route('admin.wisuda.reject', $registration->id) }}" method="POST">
                                @csrf
                                <div class="relative mb-4">
                                    <textarea name="rejection_note" rows="4" placeholder="Berikan alasan penolakan yang rinci agar mahasiswa mengetahui dokumen apa saja yang perlu diperbaiki..." required
                                        class="w-full bg-red-50/50 border-2 border-red-100 rounded-2xl px-4 py-3 text-sm text-red-900 placeholder:text-red-300 focus:outline-none focus:border-red-400 focus:ring-4 focus:ring-red-400/5 transition-all resize-none"></textarea>
                                </div>
                                <div class="flex gap-3">
                                    <button type="button" @click="mode = null" class="flex-1 h-12 bg-gray-50 text-gray-500 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-100 transition-colors border border-gray-100">
                                        Batal
                                    </button>
                                    <button type="submit" class="flex-[2] h-12 bg-red-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-red-600/20 hover:bg-red-700 transition-all">
                                        Konfirmasi Tolak Pendaftaran
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Rejection Info --}}
            @if($statusValue === 'rejected')
                <div class="bg-red-50 border border-red-200 rounded-3xl p-6 sm:p-8 flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-2xl font-light">info</span>
                    </div>
                    <div>
                        <h4 class="text-sm font-black text-red-900 uppercase tracking-widest mb-2 leading-none">Alasan Penolakan</h4>
                        <p class="text-sm text-red-800 leading-relaxed font-semibold italic">"{{ $registration->rejection_note ?? '-' }}"</p>
                    </div>
                </div>
            @endif

            {{-- Scheduled Batch Info --}}
            @if($statusValue === 'scheduled' && $registration->batch)
                <div class="bg-gradient-to-br from-[#4A0A1A] via-[#6D1029] to-[#8B1538] rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl shadow-[#8B1538]/20">
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-rose-500/10 rounded-full blur-3xl"></div>

                    <div class="relative flex items-center gap-5 mb-6">
                        <div class="w-14 h-14 rounded-3xl bg-white/10 flex items-center justify-center backdrop-blur-md border border-white/20 shadow-lg">
                            <span class="material-symbols-outlined text-white text-3xl font-light">school</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-black tracking-tight leading-none mb-2">Informasi Jadwal Wisuda</h3>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-rose-400 animate-pulse shadow-[0_0_8px_rgba(251,113,133,0.8)]"></div>
                                <span class="text-[10px] font-black text-rose-200 uppercase tracking-[0.2em]">{{ $registration->batch->nama_batch }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="relative grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-5 rounded-[2rem] bg-white/5 border border-white/10 backdrop-blur-sm">
                            <p class="text-[9px] font-black text-rose-200 uppercase tracking-widest mb-3">Waktu Pelaksanaan</p>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-sm text-rose-200">calendar_month</span>
                                    <span class="text-sm font-black">{{ $registration->batch->tanggal->locale('id')->isoFormat('dddd, DD MMMM Y') }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-sm text-rose-200">alarm</span>
                                    <span class="text-sm font-black">{{ \Carbon\Carbon::parse($registration->batch->waktu_mulai)->format('H:i') }} WIB</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 rounded-[2rem] bg-white/5 border border-white/10 backdrop-blur-sm">
                            <p class="text-[9px] font-black text-rose-200 uppercase tracking-widest mb-3">Lokasi Acara</p>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-sm text-rose-200">location_on</span>
                                    <span class="text-sm font-black leading-snug">{{ $registration->batch->lokasi }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($registration->batch->catatan)
                        <div class="relative mt-4 p-4 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-sm">
                            <p class="text-[9px] font-black text-rose-200 uppercase tracking-widest mb-1.5">Catatan Tambahan</p>
                            <p class="text-xs text-rose-50 leading-relaxed">{{ $registration->batch->catatan }}</p>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Graduation Documents Uploaded --}}
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-64 h-64 bg-red-900/[0.01] rounded-full blur-3xl -mr-32 -mt-32"></div>
                
                <div class="relative">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-900 border border-red-100/50">
                            <span class="material-symbols-outlined text-[18px]">folder_zip</span>
                        </div>
                        <h3 class="text-lg font-black text-gray-900 tracking-tight leading-none">Dokumen Persyaratan Wisuda</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4"
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
                        @php
                            $existingDocs = $registration->documents->keyBy(fn($d) => $d->file_type->value);
                        @endphp

                        @foreach(\App\Domain\Wisuda\Enums\WisudaDocumentType::cases() as $docType)
                            @php
                                $file = $existingDocs->get($docType->value);
                                $hasFile = !is_null($file);
                            @endphp

                            @if($hasFile)
                                @php
                                    $dlUrl = route('admin.wisuda.download', base64_encode($file->file_path));
                                    $pvUrl = route('admin.wisuda.preview', base64_encode($file->file_path));
                                    $ext = strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION));
                                    $isPreviewable = in_array($ext, ['pdf', 'png', 'jpg', 'jpeg']);
                                    $previewUrl = $isPreviewable ? $pvUrl : '';
                                @endphp
                                <button type="button"
                                    @click="openPreview('{{ $previewUrl }}', '{{ addslashes($file->original_name) }}', '{{ $ext }}', '{{ $dlUrl }}')"
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-red-200 hover:bg-[#8B1538]/5 hover:shadow-md hover:-translate-y-0.5 transition-all group/file cursor-pointer text-left w-full">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                                            {{ in_array($ext, ['pdf']) ? 'bg-red-50 text-red-500' : 'bg-purple-50 text-purple-500' }}">
                                            <span class="material-symbols-outlined text-xl">
                                                {{ in_array($ext, ['pdf']) ? 'picture_as_pdf' : 'image' }}
                                            </span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5 truncate">{{ $docType->label() }}</p>
                                            <p class="text-[11px] font-bold text-gray-700 truncate leading-none mb-1">{{ $file->original_name }}</p>
                                            <span class="text-[9px] font-black text-gray-400 uppercase">{{ $file->file_size_human }}</span>
                                        </div>
                                    </div>
                                    <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-gray-400 group-hover/file:text-[#8B1538] transition-colors border border-gray-100 shrink-0 ml-2">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </div>
                                </button>
                            @else
                                <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-2xl border border-gray-100/50 opacity-60">
                                    <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-300 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-xl">help_outline</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5 truncate">{{ $docType->label() }}</p>
                                        <p class="text-[11px] font-bold text-gray-400 italic leading-none">Belum diunggah</p>
                                    </div>
                                </div>
                            @endif
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
                                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Preview Dokumen Wisuda</p>
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
                                        <template x-if="['png','jpg','jpeg','webp'].includes(previewType) && previewUrl">
                                            <div class="flex items-center justify-center p-8 h-full" style="min-height: 70vh;">
                                                <img :src="previewUrl" :alt="previewName" class="max-w-full max-h-[75vh] rounded-xl shadow-lg object-contain">
                                            </div>
                                        </template>

                                        {{-- Non-previewable file --}}
                                        <template x-if="!previewUrl || !['pdf','png','jpg','jpeg','webp'].includes(previewType)">
                                            <div class="flex flex-col items-center justify-center py-20" style="min-height: 40vh;">
                                                <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mb-6">
                                                    <span class="material-symbols-outlined text-4xl text-gray-300">visibility_off</span>
                                                </div>
                                                <p class="text-base font-bold text-gray-700 mb-1">Preview Tidak Tersedia</p>
                                                <p class="text-sm text-gray-400 mb-6">Format file ini tidak dapat dipratinjau secara langsung.</p>
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
            </div>
        </div>
    </div>
</div>
@endsection
