@extends('layouts.app')

@section('title', 'Edit ' . ($prestasi->tipe === 'pelaporan' ? 'Laporan Prestasi' : 'Pengajuan Kegiatan'))
@section('page-title', 'Edit ' . ($prestasi->tipe === 'pelaporan' ? 'Pelaporan' : 'Pengajuan'))

@section('content')
<div class="px-4 py-6 max-w-4xl mx-auto space-y-6 font-inter" x-data="{ 
    tingkat: '{{ old('tingkat_kegiatan', $prestasi->tingkat_kegiatan) }}',
    jenis: '{{ old('jenis_kegiatan', $prestasi->jenis_kegiatan) }}',
    tipe: '{{ $prestasi->tipe }}'
}">

    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('dosen.prestasi.show', $prestasi) }}" class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center border border-gray-100 text-gray-500 hover:text-[#7a1621] hover:border-red-100 hover:bg-red-50 transition-colors">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-black text-gray-900 leading-tight">Edit {{ $prestasi->tipe === 'pelaporan' ? 'Pelaporan Prestasi' : 'Pengajuan Kegiatan' }}</h1>
            <p class="text-sm text-gray-500 mt-1">Hanya bisa diubah saat status Draft atau Ditolak.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-200 rounded-2xl mb-6 flex items-start gap-3">
            <span class="material-symbols-outlined text-green-600 mt-0.5">check_circle</span>
            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 rounded-2xl mb-6 flex items-start gap-3">
            <span class="material-symbols-outlined text-red-600 mt-0.5">error</span>
            <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 rounded-2xl mb-6">
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('dosen.prestasi.update', $prestasi) }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @csrf
        @method('PUT')

        <div class="p-6 sm:p-8 space-y-8">
            {{-- Data Utama --}}
            <div>
                <h3 class="text-xs font-bold text-[#7a1621] uppercase tracking-widest flex items-center gap-2 border-b border-gray-100 pb-3 mb-5">
                    <span class="material-symbols-outlined text-[18px]">info</span> Informasi Kegiatan
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Nama Kegiatan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan', $prestasi->nama_kegiatan) }}" required placeholder="Contoh: Lomba Peradilan Semu Nasional 2026"
                               class="w-full rounded-xl border-gray-300 text-sm px-4 py-2.5 focus:ring-[#7a1621] focus:border-[#7a1621]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Jenis Kegiatan <span class="text-red-500">*</span></label>
                        <select name="jenis_kegiatan" x-model="jenis" required class="w-full rounded-xl border-gray-300 text-sm px-4 py-2.5 focus:ring-[#7a1621] focus:border-[#7a1621]">
                            <option value="akademik">Akademik</option>
                            <option value="non-akademik">Non-Akademik</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Tingkat <span class="text-red-500">*</span></label>
                        <select name="tingkat_kegiatan" x-model="tingkat" required class="w-full rounded-xl border-gray-300 text-sm px-4 py-2.5 focus:ring-[#7a1621] focus:border-[#7a1621]">
                            <option value="">-- Pilih Tingkat --</option>
                            <option value="internal">Internal (Kampus)</option>
                            <option value="regional">Regional (Provinsi)</option>
                            <option value="nasional">Nasional</option>
                            <option value="internasional">Internasional</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Penyelenggara <span class="text-red-500">*</span></label>
                        <input type="text" name="penyelenggara" value="{{ old('penyelenggara', $prestasi->penyelenggara) }}" required placeholder="Contoh: Universitas Gadjah Mada"
                               class="w-full rounded-xl border-gray-300 text-sm px-4 py-2.5 focus:ring-[#7a1621] focus:border-[#7a1621]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Tempat Kegiatan <span class="text-red-500">*</span></label>
                        <input type="text" name="tempat_kegiatan" value="{{ old('tempat_kegiatan', $prestasi->tempat_kegiatan) }}" required placeholder="Contoh: Yogyakarta / Online"
                               class="w-full rounded-xl border-gray-300 text-sm px-4 py-2.5 focus:ring-[#7a1621] focus:border-[#7a1621]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $prestasi->tanggal_mulai?->format('Y-m-d')) }}" required 
                               class="w-full rounded-xl border-gray-300 text-sm px-4 py-2.5 focus:ring-[#7a1621] focus:border-[#7a1621]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $prestasi->tanggal_selesai?->format('Y-m-d')) }}" 
                               class="w-full rounded-xl border-gray-300 text-sm px-4 py-2.5 focus:ring-[#7a1621] focus:border-[#7a1621]">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Deskripsi Singkat / Abstrak</label>
                        <textarea name="deskripsi" rows="3" class="w-full rounded-xl border-gray-300 text-sm px-4 py-2.5 focus:ring-[#7a1621] focus:border-[#7a1621]" placeholder="Deskripsikan secara singkat tentang kegiatan ini...">{{ old('deskripsi', $prestasi->deskripsi) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Prestasi / Peran --}}
            <div>
                <h3 class="text-xs font-bold text-[#7a1621] uppercase tracking-widest flex items-center gap-2 border-b border-gray-100 pb-3 mb-5">
                    <span class="material-symbols-outlined text-[18px]">workspace_premium</span> Hasil / Peran
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">
                            <span x-text="tipe === 'pelaporan' ? 'Prestasi Diraih (Juara/Medali)' : 'Peran/Status Peserta'"></span>
                            <span x-show="tipe === 'pelaporan'" class="text-red-500">*</span>
                        </label>
                        <input type="text" name="jenis_prestasi" value="{{ old('jenis_prestasi', $prestasi->jenis_prestasi) }}" :required="tipe === 'pelaporan'" placeholder="Contoh: Juara 1 / Peserta / Pemakalah"
                               class="w-full rounded-xl border-gray-300 text-sm px-4 py-2.5 focus:ring-[#7a1621] focus:border-[#7a1621]">
                    </div>

                    <div x-show="tipe === 'pelaporan'">
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Nomor Sertifikat</label>
                        <input type="text" name="nomor_sertifikat" value="{{ old('nomor_sertifikat', $prestasi->nomor_sertifikat) }}" placeholder="Contoh: 123/SK/2026"
                               class="w-full rounded-xl border-gray-300 text-sm px-4 py-2.5 focus:ring-[#7a1621] focus:border-[#7a1621]">
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
            <a href="{{ route('dosen.prestasi.show', $prestasi) }}" class="px-5 py-2.5 text-sm font-bold text-gray-600 hover:bg-gray-200 rounded-xl transition-colors">Batal</a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-[#7a1621] to-[#6D1029] text-white text-sm font-bold rounded-xl shadow-sm transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">save</span> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
