@extends('layouts.admin')

@section('title', 'Jadwalkan Sidang')
@section('page-title', 'Penjadwalan Sidang')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header Section --}}
    <div class="relative bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm overflow-hidden group">
        {{-- Background Accents --}}
        <div class="absolute top-0 right-0 -mr-12 -mt-12 w-48 h-48 bg-indigo-900/[0.03] rounded-full blur-3xl transition-all duration-700 group-hover:scale-110"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.thesis.show', $thesis) }}" class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-indigo-50 hover:text-indigo-900 transition-all group/back">
                    <span class="material-symbols-outlined text-[20px] group-hover/back:-translate-x-1 transition-transform">arrow_back</span>
                </a>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Manajemen Skripsi</span>
                        <span class="text-gray-300 leading-none">/</span>
                        <span class="text-[10px] font-black text-indigo-800 uppercase tracking-widest leading-none">Penjadwalan</span>
                    </div>
                    <h1 class="text-xl font-black text-gray-900 tracking-tight leading-none">Atur Jadwal Sidang</h1>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-3">
                <div class="px-4 py-2 bg-indigo-50 rounded-2xl border border-indigo-100/50 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-indigo-600">event_available</span>
                    <span class="text-[11px] font-black text-indigo-700 uppercase tracking-wider">Tahap Konfirmasi</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Form Card --}}
    <div class="bg-white rounded-[2.5rem] p-6 sm:p-10 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400">
                <span class="material-symbols-outlined text-2xl">person_pin</span>
            </div>
            <div>
                <h3 class="font-black text-gray-900 tracking-tight leading-none mb-2">{{ $thesis->mahasiswa?->user?->name }}</h3>
                <p class="text-sm text-gray-500 font-medium italic leading-none">"{{ $thesis->judul }}"</p>
            </div>
        </div>

        @if($errors->any())
        <div class="mb-8 p-4 bg-red-50 border border-red-100 rounded-2xl animate-shake">
            <div class="flex items-center gap-3 mb-2 text-red-600">
                <span class="material-symbols-outlined">error</span>
                <p class="text-xs font-black uppercase tracking-widest">Terdapat Kesalahan Input</p>
            </div>
            <ul class="list-disc list-inside text-xs text-red-500 font-medium space-y-1 ml-9">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.thesis.schedule.store', $thesis) }}" method="POST" class="space-y-8">
            @csrf

            {{-- Date & Time Section --}}
            <div class="space-y-4">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                    Waktu Pelaksanaan
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-1">
                        <label class="block text-[11px] font-black text-gray-700 uppercase tracking-widest mb-2">Tanggal Sidang <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" value="{{ old('tanggal') }}" required min="{{ now()->toDateString() }}"
                            class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-4 py-3 text-sm font-bold text-gray-700 focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-400/5 transition-all">
                    </div>
                    <div class="md:col-span-2 grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-black text-gray-700 uppercase tracking-widest mb-2">Mulai <span class="text-red-500">*</span></label>
                            <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required
                                class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-4 py-3 text-sm font-bold text-gray-700 focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-400/5 transition-all">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-700 uppercase tracking-widest mb-2">Selesai (Opsional)</label>
                            <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai') }}"
                                class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-4 py-3 text-sm font-bold text-gray-700 focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-400/5 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Location Section --}}
            <div class="space-y-4">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                    Lokasi Sidang
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] font-black text-gray-700 uppercase tracking-widest mb-2">Pilih Ruangan Terdaftar</label>
                        <select name="ruangan_id" class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-4 py-3 text-sm font-bold text-gray-700 focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-400/5 transition-all appearance-none">
                            <option value="">Pilih ruangan...</option>
                            @foreach($ruangans as $r)
                            <option value="{{ $r->id }}" {{ old('ruangan_id') == $r->id ? 'selected' : '' }}>{{ $r->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-gray-700 uppercase tracking-widest mb-2">Atau Tulis Manual</label>
                        <input type="text" name="ruangan_manual" value="{{ old('ruangan_manual') }}" placeholder="Cth: Ruang Sidang Lantai 3"
                            class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-4 py-3 text-sm font-bold text-gray-700 focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-400/5 transition-all">
                    </div>
                </div>
            </div>

            {{-- Examiners Section --}}
            <div class="space-y-4">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                    Tim Panel Penguji
                </h4>
                <div class="grid grid-cols-1 gap-4">
                    <div class="p-5 bg-indigo-50/30 rounded-2xl border-2 border-indigo-100/50">
                        <label class="block text-[11px] font-black text-indigo-900 uppercase tracking-widest mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">person_search</span>
                            Dosen Pembimbing <span class="text-red-500 font-bold ml-1">*</span>
                        </label>
                        <select name="pembimbing_id" required class="w-full bg-white border-2 border-indigo-100 rounded-xl px-4 py-2 text-sm font-bold text-gray-700 focus:outline-none focus:border-indigo-400 transition-all appearance-none shadow-sm">
                            <option value="">Pilih dosen pembimbing...</option>
                            @foreach($dosens as $d)
                            <option value="{{ $d->id }}" {{ old('pembimbing_id', $thesis->approved_supervisor_id) == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-5 bg-gray-50 rounded-2xl border-2 border-gray-100">
                            <label class="block text-[11px] font-black text-gray-700 uppercase tracking-widest mb-2">Penguji Utama <span class="text-red-500 font-bold ml-1">*</span></label>
                            <select name="penguji_1_id" required class="w-full bg-white border-2 border-gray-200 rounded-xl px-4 py-2 text-sm font-bold text-gray-700 focus:outline-none focus:border-indigo-400 transition-all appearance-none shadow-sm">
                                <option value="">Pilih penguji 1...</option>
                                @foreach($dosens as $d)
                                <option value="{{ $d->id }}" {{ old('penguji_1_id') == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="p-5 bg-gray-50 rounded-2xl border-2 border-gray-100">
                            <label class="block text-[11px] font-black text-gray-700 uppercase tracking-widest mb-2">Penguji Pendamping</label>
                            <select name="penguji_2_id" class="w-full bg-white border-2 border-gray-200 rounded-xl px-4 py-2 text-sm font-bold text-gray-700 focus:outline-none focus:border-indigo-400 transition-all appearance-none shadow-sm">
                                <option value="">Pilih penguji 2 (opsional)...</option>
                                @foreach($dosens as $d)
                                <option value="{{ $d->id }}" {{ old('penguji_2_id') == $d->id ? 'selected' : '' }}>{{ $d->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Additional Notes --}}
            <div class="space-y-4">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span>
                    Informasi Tambahan
                </h4>
                <textarea name="notes" rows="3" placeholder="Berikan instruksi atau catatan tambahan untuk mahasiswa dan tim penguji jika diperlukan..."
                    class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-4 py-4 text-sm font-medium text-gray-700 focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-400/5 transition-all resize-none">{{ old('notes') }}</textarea>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-50">
                <a href="{{ route('admin.thesis.show', $thesis) }}"
                    class="h-14 px-8 flex items-center justify-center rounded-2xl text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-all">
                    Batalkan
                </a>
                <button type="submit"
                    class="h-14 px-10 bg-indigo-700 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-indigo-700/20 hover:bg-indigo-800 hover:-translate-y-0.5 transition-all flex items-center gap-3">
                    <span class="material-symbols-outlined text-xl">event_available</span>
                    Simpan & Publish Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-4px); }
    75% { transform: translateX(4px); }
}
.animate-shake {
    animation: shake 0.4s ease-in-out;
}
</style>
@endsection
