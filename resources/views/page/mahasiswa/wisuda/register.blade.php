@extends('layouts.mahasiswa')
@section('title', 'Pendaftaran Wisuda')

@section('content')
<div class="px-4 py-6 max-w-[1000px] mx-auto space-y-5">

    {{-- Header --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-gradient-to-br from-[#8B1538]/5 to-transparent rounded-full blur-3xl"></div>

        <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('mahasiswa.wisuda.index') }}"
                    class="w-10 h-10 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl flex items-center justify-center text-gray-500 hover:text-[#8B1538] transition-all shrink-0 shadow-sm hover:shadow">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-xl font-black text-gray-900 tracking-tight">Form Pendaftaran Wisuda</h1>
                    <p class="text-xs text-gray-500 mt-0.5">Lengkapi kontak aktif Anda sebelum memulai pengunggahan berkas.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Card --}}
    <div class="bg-gradient-to-br from-[#8B1538]/5 to-transparent border border-red-100/50 rounded-2xl p-5 flex items-start gap-4">
        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm text-[#8B1538] shrink-0 border border-red-50">
            <span class="material-symbols-outlined text-2xl">school</span>
        </div>
        <div>
            <h4 class="text-sm font-bold text-[#8B1538]">Langkah Pendaftaran Wisuda</h4>
            <p class="text-xs text-gray-600 mt-1 leading-relaxed">
                1. Isi nomor HP aktif dan alamat email di bawah ini.<br>
                2. Setelah menekan tombol "Mulai Pendaftaran", Anda akan diarahkan ke halaman berkas.<br>
                3. Unggah semua dokumen kelengkapan wisuda yang wajib sebelum mengirim pendaftaran.
            </p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50">
            <h3 class="font-bold text-gray-800 text-sm">Data Kontak Mahasiswa</h3>
        </div>
        
        <form action="{{ route('mahasiswa.wisuda.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Nama & NIM (Read-only) --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Mahasiswa</label>
                    <input type="text" value="{{ $mahasiswa->nama }}" disabled
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-500 font-medium">
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">NIM</label>
                    <input type="text" value="{{ $mahasiswa->nim }}" disabled
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-500 font-medium">
                </div>

                {{-- Skripsi Title (Read-only) --}}
                <div class="space-y-1.5 md:col-span-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Judul Skripsi</label>
                    <textarea disabled rows="2"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-500 font-medium resize-none">{{ $submission->judul }}</textarea>
                </div>

                {{-- No HP (Editable) --}}
                <div class="space-y-1.5">
                    <label for="no_hp" class="text-xs font-bold text-gray-700 uppercase tracking-wider">Nomor HP / WhatsApp <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                            <span class="material-symbols-outlined text-[18px]">phone</span>
                        </span>
                        <input type="text" name="no_hp" id="no_hp" required
                            value="{{ old('no_hp', $mahasiswa->no_hp) }}"
                            placeholder="Contoh: 08123456789"
                            class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-[#8B1538] transition-all">
                    </div>
                    @error('no_hp')
                        <p class="text-xs text-red-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email Aktif (Editable) --}}
                <div class="space-y-1.5">
                    <label for="email_aktif" class="text-xs font-bold text-gray-700 uppercase tracking-wider">Alamat Email Aktif <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                            <span class="material-symbols-outlined text-[18px]">mail</span>
                        </span>
                        <input type="email" name="email_aktif" id="email_aktif" required
                            value="{{ old('email_aktif', $mahasiswa->getActiveEmail()) }}"
                            placeholder="Contoh: nama@domain.com"
                            class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-[#8B1538] transition-all">
                    </div>
                    @error('email_aktif')
                        <p class="text-xs text-red-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-4 border-t border-gray-50 flex justify-end gap-3">
                <a href="{{ route('mahasiswa.wisuda.index') }}"
                    class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-bold transition-all">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-gradient-to-r from-[#8B1538] to-[#6D1029] hover:from-[#6D1029] hover:to-[#500A1C] text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg shadow-red-900/10 hover:shadow-red-900/20 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">rocket_launch</span>
                    Mulai Pendaftaran
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
