@extends('layouts.admin')

@section('title', 'Detail Batch Wisuda')
@section('page-title', 'Detail Batch Wisuda')

@section('content')
<div class="space-y-6">
    {{-- Header Section --}}
    <div class="relative bg-white rounded-3xl p-4 sm:p-6 border border-gray-100 shadow-sm overflow-hidden group">
        {{-- Background Accents --}}
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-red-900/[0.03] rounded-full blur-3xl transition-all duration-700 group-hover:scale-110"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-amber-500/[0.02] rounded-full blur-3xl transition-all duration-700 group-hover:scale-110"></div>

        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.wisuda.batches') }}" class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-900 transition-all group/back">
                    <span class="material-symbols-outlined text-[20px] group-hover/back:-translate-x-1 transition-transform">arrow_back</span>
                </a>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Manajemen Wisuda</span>
                        <span class="text-gray-300">/</span>
                        <span class="text-[10px] font-black text-red-900 uppercase tracking-widest">Detail Batch</span>
                    </div>
                    <h1 class="text-xl font-black text-gray-900 tracking-tight leading-none">{{ $batch->nama_batch }}</h1>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 bg-red-50 text-red-900 rounded-2xl border border-red-100/50 text-[11px] font-black uppercase tracking-wider shadow-sm">
                    {{ $batch->registrations->count() }} Mahasiswa Terjadwal
                </span>
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

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6" x-data="{ isEditing: false }">
        {{-- Left Column: Batch Info & Edit Form --}}
        <div class="lg:col-span-4 space-y-6">
            {{-- Info Card (Default View) --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden group" x-show="!isEditing">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50 rounded-full blur-3xl -mr-16 -mt-16 group-hover:bg-red-50 transition-colors duration-500"></div>
                
                <div class="relative space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-900 border border-red-100/50">
                                <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                            </div>
                            <h4 class="font-black text-[11px] text-gray-400 uppercase tracking-widest leading-none">Rincian Acara</h4>
                        </div>
                        <button @click="isEditing = true" class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-900 transition-colors border border-gray-100">
                            <span class="material-symbols-outlined text-[16px]">edit</span>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Tanggal Wisuda</p>
                            <p class="text-sm font-black text-gray-700 leading-none">
                                {{ $batch->tanggal->locale('id')->isoFormat('dddd, DD MMMM Y') }}
                            </p>
                        </div>
                        <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Waktu Pelaksanaan</p>
                            <p class="text-sm font-black text-gray-700 leading-none">
                                {{ \Carbon\Carbon::parse($batch->waktu_mulai)->format('H:i') }} WIB
                            </p>
                        </div>
                        <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Tempat / Lokasi</p>
                            <p class="text-sm font-bold text-gray-700 leading-snug">
                                {{ $batch->lokasi }}
                            </p>
                        </div>
                        @if($batch->catatan)
                        <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Catatan Tambahan</p>
                            <p class="text-xs text-gray-500 leading-relaxed font-semibold">
                                {{ $batch->catatan }}
                            </p>
                        </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-gray-50 flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <p class="text-[9px] text-gray-400 uppercase tracking-widest font-black leading-none">Dibuat Oleh</p>
                            <span class="text-[10px] font-bold text-gray-700 leading-none">{{ $batch->creator->name ?? 'Admin' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-[9px] text-gray-400 uppercase tracking-widest font-black leading-none">Pada Tanggal</p>
                            <span class="text-[10px] font-bold text-gray-500 leading-none">{{ $batch->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Edit Card (Toggleable View) --}}
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden group" x-show="isEditing" x-cloak>
                <div class="absolute top-0 right-0 w-32 h-32 bg-gray-50 rounded-full blur-3xl -mr-16 -mt-16"></div>
                
                <div class="relative space-y-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-900 border border-red-100/50">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </div>
                        <h4 class="font-black text-[11px] text-gray-400 uppercase tracking-widest leading-none">Edit Batch Wisuda</h4>
                    </div>

                    <form action="{{ route('admin.wisuda.batches.update', $batch->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        {{-- Tanggal --}}
                        <div class="space-y-1">
                            <label for="edit_tanggal" class="text-[9px] font-black text-gray-400 uppercase tracking-widest block leading-none">Tanggal Pelaksanaan</label>
                            <input type="date" name="tanggal" id="edit_tanggal" value="{{ old('tanggal', $batch->tanggal->format('Y-m-d')) }}" required
                                class="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-3 py-2.5 text-xs text-gray-800 focus:outline-none focus:border-red-900/50 transition-all">
                        </div>

                        {{-- Waktu Mulai --}}
                        <div class="space-y-1">
                            <label for="edit_waktu_mulai" class="text-[9px] font-black text-gray-400 uppercase tracking-widest block leading-none">Waktu Mulai</label>
                            <input type="time" name="waktu_mulai" id="edit_waktu_mulai" value="{{ old('waktu_mulai', \Carbon\Carbon::parse($batch->waktu_mulai)->format('H:i')) }}" required
                                class="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-3 py-2.5 text-xs text-gray-800 focus:outline-none focus:border-red-900/50 transition-all">
                        </div>

                        {{-- Lokasi --}}
                        <div class="space-y-1">
                            <label for="edit_lokasi" class="text-[9px] font-black text-gray-400 uppercase tracking-widest block leading-none">Lokasi</label>
                            <input type="text" name="lokasi" id="edit_lokasi" value="{{ old('lokasi', $batch->lokasi) }}" required
                                class="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-3 py-2.5 text-xs text-gray-800 focus:outline-none focus:border-red-900/50 transition-all">
                        </div>

                        {{-- Catatan --}}
                        <div class="space-y-1">
                            <label for="edit_catatan" class="text-[9px] font-black text-gray-400 uppercase tracking-widest block leading-none">Catatan</label>
                            <textarea name="catatan" id="edit_catatan" rows="3"
                                class="w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:border-red-900/50 transition-all resize-none">{{ old('catatan', $batch->catatan) }}</textarea>
                        </div>

                        <div class="flex gap-2 pt-2">
                            <button type="button" @click="isEditing = false" class="flex-1 h-10 bg-gray-50 text-gray-500 rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-gray-100 transition-colors border border-gray-100">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 h-10 bg-red-900 text-white rounded-xl font-black text-[9px] uppercase tracking-widest shadow-md hover:bg-red-800 transition-all">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right Column: Scheduled Mahasiswa & Batch Assignment --}}
        <div class="lg:col-span-8 space-y-6">
            {{-- Tab Content --}}
            <div x-data="{ activeTab: 'scheduled' }" class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                {{-- Tabs Header --}}
                <div class="flex border-b border-gray-50 px-6 sm:px-8 bg-gray-50/50 backdrop-blur-sm overflow-x-auto">
                    <button @click="activeTab = 'scheduled'"
                        class="group flex items-center gap-2.5 px-6 py-5 text-sm font-black whitespace-nowrap transition-all relative"
                        :class="activeTab === 'scheduled' ? 'text-red-900' : 'text-gray-400 hover:text-gray-700'">
                        <span class="material-symbols-outlined text-[20px] transition-transform group-hover:scale-110" :class="activeTab === 'scheduled' ? 'text-red-900' : 'text-gray-400'">groups</span>
                        <span class="uppercase tracking-widest text-[11px]">Mahasiswa Terjadwal ({{ $batch->registrations->count() }})</span>
                        <div x-show="activeTab === 'scheduled'" class="absolute bottom-0 left-6 right-6 h-0.5 bg-red-900 rounded-full shadow-[0_-2px_6px_rgba(153,27,27,0.4)]"></div>
                    </button>
                    
                    <button @click="activeTab = 'available'"
                        class="group flex items-center gap-2.5 px-6 py-5 text-sm font-black whitespace-nowrap transition-all relative"
                        :class="activeTab === 'available' ? 'text-red-900' : 'text-gray-400 hover:text-gray-700'">
                        <span class="material-symbols-outlined text-[20px] transition-transform group-hover:scale-110" :class="activeTab === 'available' ? 'text-red-900' : 'text-gray-400'">person_add</span>
                        <span class="uppercase tracking-widest text-[11px]">Tempatkan Mahasiswa ({{ $availableRegistrations->count() }})</span>
                        @if($availableRegistrations->count() > 0)
                            <span class="px-2 py-0.5 rounded-full text-[10px] bg-amber-500 text-white font-bold animate-pulse">
                                {{ $availableRegistrations->count() }}
                            </span>
                        @endif
                        <div x-show="activeTab === 'available'" class="absolute bottom-0 left-6 right-6 h-0.5 bg-red-900 rounded-full shadow-[0_-2px_6px_rgba(153,27,27,0.4)]"></div>
                    </button>
                </div>

                {{-- Tab Panel: Scheduled Mahasiswa --}}
                <div x-show="activeTab === 'scheduled'" class="p-6 sm:p-8 min-h-[300px]">
                    <div class="grid gap-3">
                        @forelse($batch->registrations as $reg)
                            @php
                                $student = $reg->mahasiswa;
                            @endphp
                            <div class="bg-white border border-gray-100 rounded-2xl p-4 sm:p-5 hover:border-red-100 hover:shadow-md hover:shadow-red-900/5 transition-all group flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-red-50 group-hover:text-red-900 transition-colors shrink-0 overflow-hidden relative border border-gray-100 group-hover:border-red-100">
                                        <span class="material-symbols-outlined text-2xl font-light">account_circle</span>
                                        @if($student && $student->foto)
                                            <img src="{{ $student->foto_url }}" class="absolute inset-0 w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2 mb-1 leading-none">
                                            <h3 class="font-black text-gray-900 tracking-tight group-hover:text-red-900 transition-colors">
                                                {{ $student->nama ?? 'Mahasiswa' }}
                                            </h3>
                                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest truncate">
                                                {{ $student->nim ?? '-' }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-500 font-medium truncate">
                                            {{ $student->prodiData?->nama ?? $student->prodi ?? '-' }}
                                        </p>
                                        <div class="flex flex-wrap items-center gap-3 mt-2">
                                            <div class="flex items-center gap-1 px-2 py-0.5 bg-gray-50 rounded-lg border border-gray-100">
                                                <span class="material-symbols-outlined text-[13px] text-gray-400">phone</span>
                                                <span class="text-[9px] font-bold text-gray-500 uppercase tracking-tighter">{{ $reg->no_hp ?? '-' }}</span>
                                            </div>
                                            <div class="flex items-center gap-1 px-2 py-0.5 bg-gray-50 rounded-lg border border-gray-100">
                                                <span class="material-symbols-outlined text-[13px] text-gray-400">mail</span>
                                                <span class="text-[9px] font-bold text-gray-500 tracking-tighter">{{ $reg->email_aktif ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('admin.wisuda.show', $reg->id) }}"
                                    class="shrink-0 flex items-center justify-center gap-2 h-10 px-5 bg-gray-50 text-gray-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-50 hover:text-red-900 border border-gray-100 hover:border-red-100 transition-all">
                                    Detail Dokumen
                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                </a>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-16 text-center group">
                                <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4 text-gray-200 group-hover:scale-110 group-hover:text-red-900/20 transition-all duration-500">
                                    <span class="material-symbols-outlined text-3xl font-light">groups</span>
                                </div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Belum ada mahasiswa dijadwalkan.</p>
                                <p class="text-xs text-gray-400 font-medium max-w-xs leading-relaxed mt-1">Silakan beralih ke tab "Tempatkan Mahasiswa" untuk menambahkan peserta wisuda berstatus ACC.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Tab Panel: Available Mahasiswa to Assign --}}
                <div x-show="activeTab === 'available'" class="p-6 sm:p-8 min-h-[300px]" x-cloak>
                    @if($availableRegistrations->count() > 0)
                        <form action="{{ route('admin.wisuda.batches.assign', $batch->id) }}" method="POST"
                            x-data="{
                                selectedIds: [],
                                allIds: {{ json_encode($availableRegistrations->pluck('id')->map(fn($id) => (string)$id)->toArray()) }},
                                toggleAll(checked) {
                                    this.selectedIds = checked ? [...this.allIds] : [];
                                }
                            }">
                            @csrf

                            <div class="space-y-4">
                                {{-- Select All Action Bar --}}
                                <div class="flex items-center justify-between p-4 bg-gray-50/70 border border-gray-100 rounded-2xl mb-2">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" @change="toggleAll($event.target.checked)"
                                            :checked="selectedIds.length === allIds.length && allIds.length > 0"
                                            class="w-5 h-5 rounded-lg border-2 border-gray-200 text-red-900 focus:ring-red-900/20 transition-all">
                                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Pilih Semua Mahasiswa</span>
                                    </label>
                                    <span class="text-[10px] font-black text-red-900 uppercase tracking-widest bg-red-50 px-2.5 py-1 rounded-xl border border-red-100" x-show="selectedIds.length > 0" x-cloak>
                                        <span x-text="selectedIds.length"></span> Terpilih
                                    </span>
                                </div>

                                {{-- Mahasiswa Checkboxes --}}
                                <div class="grid gap-3">
                                    @foreach($availableRegistrations as $reg)
                                        @php
                                            $student = $reg->mahasiswa;
                                        @endphp
                                        <label class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-2xl hover:border-red-100 hover:bg-[#8B1538]/[0.01] transition-all cursor-pointer group">
                                            <div class="flex items-center gap-4 min-w-0">
                                                <input type="checkbox" name="registration_ids[]" value="{{ $reg->id }}" x-model="selectedIds"
                                                    class="student-checkbox w-5 h-5 rounded-lg border-2 border-gray-200 text-red-900 focus:ring-red-900/20 transition-all shrink-0">
                                                <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-red-50 group-hover:text-red-900 transition-colors shrink-0 overflow-hidden relative border border-gray-100 group-hover:border-red-100">
                                                    <span class="material-symbols-outlined text-2xl font-light">account_circle</span>
                                                    @if($student && $student->foto)
                                                        <img src="{{ $student->foto_url }}" class="absolute inset-0 w-full h-full object-cover">
                                                    @endif
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="flex flex-wrap items-center gap-2 mb-1 leading-none">
                                                        <h3 class="font-black text-gray-900 tracking-tight group-hover:text-red-900 transition-colors">
                                                            {{ $student->nama ?? 'Mahasiswa' }}
                                                        </h3>
                                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest truncate">
                                                            {{ $student->nim ?? '-' }}
                                                        </span>
                                                    </div>
                                                    <p class="text-sm text-gray-500 font-medium truncate">
                                                        {{ $student->prodiData?->nama ?? $student->prodi ?? '-' }}
                                                    </p>
                                                    <div class="flex flex-wrap items-center gap-3 mt-1.5">
                                                        <span class="text-[9px] font-black uppercase text-gray-400">ACC: {{ $reg->reviewed_at?->format('d M Y') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                                {{-- Action Submit --}}
                                <div class="pt-4 flex justify-end">
                                    <button type="submit" :disabled="selectedIds.length === 0"
                                        class="h-14 px-8 bg-gradient-to-r from-red-900 to-red-950 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-red-900/20 hover:bg-red-800 disabled:opacity-50 disabled:pointer-events-none disabled:shadow-none hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3">
                                        <span class="material-symbols-outlined text-xl">person_add</span>
                                        Tempatkan & Kirim Notifikasi Email
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="flex flex-col items-center justify-center py-16 text-center group">
                            <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4 text-gray-200 group-hover:scale-110 group-hover:text-red-900/20 transition-all duration-500">
                                <span class="material-symbols-outlined text-3xl font-light">person_add</span>
                            </div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tidak ada mahasiswa siap terjadwal.</p>
                            <p class="text-xs text-gray-400 font-medium max-w-xs leading-relaxed mt-1">Semua pendaftar berstatus ACC telah dijadwalkan pada batch wisuda yang tersedia.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
