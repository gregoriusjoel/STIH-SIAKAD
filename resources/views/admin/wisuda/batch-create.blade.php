@extends('layouts.admin')

@section('title', 'Buat Batch Wisuda Baru')
@section('page-title', 'Buat Batch Wisuda')

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
                        <span class="text-[10px] font-black text-red-900 uppercase tracking-widest">Buat Batch Baru</span>
                    </div>
                    <h1 class="text-xl font-black text-gray-900 tracking-tight leading-none">Buat Batch Wisuda</h1>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Form Card --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden relative">
        <div class="p-6 sm:p-8">
            <form action="{{ route('admin.wisuda.batches.store') }}" method="POST" class="space-y-6 max-w-3xl">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama Batch --}}
                    <div class="col-span-1 md:col-span-2 space-y-2">
                        <label for="nama_batch" class="text-[10px] font-black text-gray-400 uppercase tracking-widest block leading-none">Nama Batch Wisuda <span class="text-red-600">*</span></label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg font-light">school</span>
                            <input type="text" name="nama_batch" id="nama_batch" value="{{ old('nama_batch') }}" required placeholder="Contoh: Wisuda Gelombang II Tahun 2026"
                                class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl pl-12 pr-4 py-3.5 text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:border-red-900/50 focus:ring-4 focus:ring-red-900/5 transition-all @error('nama_batch') border-red-200 bg-red-50/20 @enderror">
                        </div>
                        @error('nama_batch')
                            <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal --}}
                    <div class="space-y-2">
                        <label for="tanggal" class="text-[10px] font-black text-gray-400 uppercase tracking-widest block leading-none">Tanggal Pelaksanaan <span class="text-red-600">*</span></label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg font-light">calendar_today</span>
                            <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}" required
                                class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl pl-12 pr-4 py-3.5 text-sm text-gray-800 focus:outline-none focus:border-red-900/50 focus:ring-4 focus:ring-red-900/5 transition-all @error('tanggal') border-red-200 bg-red-50/20 @enderror">
                        </div>
                        @error('tanggal')
                            <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Waktu Mulai --}}
                    <div class="space-y-2">
                        <label for="waktu_mulai" class="text-[10px] font-black text-gray-400 uppercase tracking-widest block leading-none">Waktu Mulai <span class="text-red-600">*</span></label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg font-light">alarm</span>
                            <input type="time" name="waktu_mulai" id="waktu_mulai" value="{{ old('waktu_mulai') }}" required
                                class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl pl-12 pr-4 py-3.5 text-sm text-gray-800 focus:outline-none focus:border-red-900/50 focus:ring-4 focus:ring-red-900/5 transition-all @error('waktu_mulai') border-red-200 bg-red-50/20 @enderror">
                        </div>
                        @error('waktu_mulai')
                            <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Lokasi --}}
                    <div class="col-span-1 md:col-span-2 space-y-2">
                        <label for="lokasi" class="text-[10px] font-black text-gray-400 uppercase tracking-widest block leading-none">Lokasi / Tempat Pelaksanaan <span class="text-red-600">*</span></label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg font-light">location_on</span>
                            <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}" required placeholder="Contoh: Convention Hall Universitas Adhyaksa"
                                class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl pl-12 pr-4 py-3.5 text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:border-red-900/50 focus:ring-4 focus:ring-red-900/5 transition-all @error('lokasi') border-red-200 bg-red-50/20 @enderror">
                        </div>
                        @error('lokasi')
                            <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Catatan --}}
                    <div class="col-span-1 md:col-span-2 space-y-2">
                        <label for="catatan" class="text-[10px] font-black text-gray-400 uppercase tracking-widest block leading-none">Catatan Tambahan (Opsional)</label>
                        <div class="relative">
                            <textarea name="catatan" id="catatan" rows="4" placeholder="Tuliskan info pakaian, gladi bersih, atau pengumuman lainnya yang akan dikirimkan ke mahasiswa..."
                                class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-4 py-3 text-sm text-gray-800 placeholder:text-gray-400 focus:outline-none focus:border-red-900/50 focus:ring-4 focus:ring-red-900/5 transition-all resize-none @error('catatan') border-red-200 bg-red-50/20 @enderror">{{ old('catatan') }}</textarea>
                        </div>
                        @error('catatan')
                            <p class="text-xs text-red-600 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 flex gap-4">
                    <a href="{{ route('admin.wisuda.batches') }}" class="h-14 px-8 bg-gray-50 text-gray-500 rounded-2xl font-black text-xs uppercase tracking-[0.15em] hover:bg-gray-100 transition-colors border border-gray-100 flex items-center justify-center">
                        Batal
                    </a>
                    <button type="submit" class="h-14 px-10 bg-gradient-to-r from-red-900 to-red-950 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-red-900/20 hover:bg-red-800 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                        Simpan Batch
                        <span class="material-symbols-outlined text-lg">save</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
