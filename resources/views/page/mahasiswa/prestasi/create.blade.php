@extends('layouts.mahasiswa')

@section('title', 'Buat ' . ($tipe === 'pelaporan' ? 'Laporan Prestasi' : 'Pengajuan Kegiatan'))
@section('page-title', 'Form ' . ($tipe === 'pelaporan' ? 'Pelaporan' : 'Pengajuan'))

@section('content')
    <div class="px-2 sm:px-4 py-4 sm:py-6 max-w-[1600px] mx-auto font-inter" x-data="{ 
            tingkat: '{{ old('tingkat_kegiatan', '') }}',
            jenis: '{{ old('jenis_kegiatan', 'akademik') }}',
            tipe: '{{ $tipe }}'
        }">

        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('mahasiswa.prestasi.index') }}"
                class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center border border-gray-100 text-gray-500 hover:text-[#7a1621] hover:border-red-100 hover:bg-red-50 transition-colors">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-black text-gray-900 leading-tight">Form
                    {{ $tipe === 'pelaporan' ? 'Pelaporan Prestasi' : 'Pengajuan Kegiatan' }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">Lengkapi form di bawah ini dengan data yang valid.</p>
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

        <form method="POST" action="{{ route('mahasiswa.prestasi.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="tipe" value="{{ $tipe }}">

            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 items-stretch">

                {{-- Section 1: Informasi Kegiatan --}}
                <div class="flex flex-col xl:col-span-1">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 h-full flex flex-col">
                        <h3
                            class="text-xs font-bold text-[#7a1621] uppercase tracking-widest flex items-center gap-2 border-b border-gray-100 pb-3 mb-5">
                            <span class="material-symbols-outlined text-[18px]">info</span> Informasi Kegiatan
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-600 mb-1.5">Nama Kegiatan <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required
                                    placeholder="Contoh: Lomba Peradilan Semu Nasional 2026"
                                    class="w-full rounded-xl border-0 bg-slate-50 text-sm px-4 py-3 focus:ring-2 focus:ring-[#7a1621]/20 focus:bg-white transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1.5">Jenis Kegiatan <span
                                        class="text-red-500">*</span></label>
                                <select name="jenis_kegiatan" x-model="jenis" required
                                    class="w-full rounded-xl border-0 bg-slate-50 text-sm px-4 py-3 focus:ring-2 focus:ring-[#7a1621]/20 focus:bg-white transition">
                                    <option value="akademik">Akademik</option>
                                    <option value="non-akademik">Non-Akademik</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1.5">Tingkat <span
                                        class="text-red-500">*</span></label>
                                <select name="tingkat_kegiatan" x-model="tingkat" required
                                    class="w-full rounded-xl border-0 bg-slate-50 text-sm px-4 py-3 focus:ring-2 focus:ring-[#7a1621]/20 focus:bg-white transition">
                                    <option value="">-- Pilih Tingkat --</option>
                                    <option value="internal">Internal (Kampus)</option>
                                    <option value="regional">Regional (Provinsi)</option>
                                    <option value="nasional">Nasional</option>
                                    <option value="internasional">Internasional</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1.5">Penyelenggara <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="penyelenggara" value="{{ old('penyelenggara') }}" required
                                    placeholder="Contoh: Universitas Gadjah Mada"
                                    class="w-full rounded-xl border-0 bg-slate-50 text-sm px-4 py-3 focus:ring-2 focus:ring-[#7a1621]/20 focus:bg-white transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1.5">Tempat Kegiatan <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="tempat_kegiatan" value="{{ old('tempat_kegiatan') }}" required
                                    placeholder="Contoh: Yogyakarta / Online"
                                    class="w-full rounded-xl border-0 bg-slate-50 text-sm px-4 py-3 focus:ring-2 focus:ring-[#7a1621]/20 focus:bg-white transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1.5">Tanggal Mulai <span
                                        class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                                    class="w-full rounded-xl border-0 bg-slate-50 text-sm px-4 py-3 focus:ring-2 focus:ring-[#7a1621]/20 focus:bg-white transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1.5">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                                    class="w-full rounded-xl border-0 bg-slate-50 text-sm px-4 py-3 focus:ring-2 focus:ring-[#7a1621]/20 focus:bg-white transition">
                                <p class="text-[10px] text-gray-500 mt-1 italic">Kosongkan jika kegiatan hanya 1 hari.</p>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-600 mb-1.5">Deskripsi Singkat /
                                    Abstrak</label>
                                <textarea name="deskripsi" rows="4"
                                    class="w-full rounded-xl border-0 bg-slate-50 text-sm px-4 py-3 focus:ring-2 focus:ring-[#7a1621]/20 focus:bg-white transition"
                                    placeholder="Deskripsikan secara singkat tentang kegiatan ini...">{{ old('deskripsi') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Hasil & Dosen --}}
                <div class="flex flex-col xl:col-span-1 space-y-6">
                    {{-- Hasil / Peran --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 flex-grow flex flex-col">
                        <h3
                            class="text-xs font-bold text-[#7a1621] uppercase tracking-widest flex items-center gap-2 border-b border-gray-100 pb-3 mb-5">
                            <span class="material-symbols-outlined text-[18px]">workspace_premium</span> Hasil / Peran
                        </h3>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1.5">
                                    <span
                                        x-text="tipe === 'pelaporan' ? 'Prestasi Diraih (Juara/Medali)' : 'Peran/Status Peserta'"></span>
                                    <span x-show="tipe === 'pelaporan'" class="text-red-500">*</span>
                                </label>
                                <input type="text" name="jenis_prestasi" value="{{ old('jenis_prestasi') }}"
                                    :required="tipe === 'pelaporan'" placeholder="Contoh: Juara 1 / Peserta / Pemakalah"
                                    class="w-full rounded-xl border-0 bg-slate-50 text-sm px-4 py-3 focus:ring-2 focus:ring-[#7a1621]/20 focus:bg-white transition">
                            </div>

                            <div x-show="tipe === 'pelaporan'">
                                <label class="block text-xs font-bold text-gray-600 mb-1.5">Nomor Sertifikat</label>
                                <input type="text" name="nomor_sertifikat" value="{{ old('nomor_sertifikat') }}"
                                    placeholder="Contoh: 123/SK/2026"
                                    class="w-full rounded-xl border-0 bg-slate-50 text-sm px-4 py-3 focus:ring-2 focus:ring-[#7a1621]/20 focus:bg-white transition">
                            </div>
                        </div>
                    </div>

                    {{-- Dosen Pendamping --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                        <h3
                            class="text-xs font-bold text-[#7a1621] uppercase tracking-widest flex items-center gap-2 border-b border-gray-100 pb-3 mb-5">
                            <span class="material-symbols-outlined text-[18px]">supervisor_account</span> Dosen Pendamping
                        </h3>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1.5">Pilih Dosen Pendamping (Opsional)</label>
                            <select name="dosen_pendamping_id" class="w-full rounded-xl border-0 bg-slate-50 text-sm px-4 py-3 focus:ring-2 focus:ring-[#7a1621]/20 focus:bg-white transition">
                                <option value="">-- Tidak ada dosen pendamping --</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}" {{ old('dosen_pendamping_id') == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->user->name ?? $dosen->nama }} ({{ $dosen->nidn }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Dokumen Lampiran --}}
                <div class="flex flex-col xl:col-span-1">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 h-full flex flex-col">
                        <h3
                            class="text-xs font-bold text-[#7a1621] uppercase tracking-widest flex items-center gap-2 border-b border-gray-100 pb-3 mb-5">
                            <span class="material-symbols-outlined text-[18px]">folder</span> Dokumen Lampiran
                        </h3>

                        <div class="space-y-4">
                            <div x-show="tipe === 'pelaporan'" class="p-4 bg-red-50/20 border border-maroon/20 rounded-xl">
                                <label class="block text-xs font-bold text-gray-700 mb-2">Sertifikat Asli <span
                                        class="text-red-500">*</span></label>
                                <input type="file" name="sertifikat" accept=".pdf,.jpg,.jpeg,.png"
                                    :required="tipe === 'pelaporan'"
                                    class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-[#7a1621] file:text-white hover:file:bg-[#63101a] transition cursor-pointer">
                                <p class="text-[10px] text-gray-400 mt-2 italic">Maksimal: 10MB.</p>
                            </div>

                            <div x-show="tipe === 'pelaporan'" class="p-4 bg-red-50/20 border border-maroon/20 rounded-xl">
                                <label class="block text-xs font-bold text-gray-700 mb-2">Surat Tugas (Jika ada)</label>
                                <input type="file" name="surat_tugas_lama" accept=".pdf"
                                    class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-[#7a1621] file:text-white hover:file:bg-[#63101a] transition cursor-pointer">
                            </div>

                            <div class="p-4 bg-red-50/20 border border-maroon/20 rounded-xl">
                                <label class="block text-xs font-bold text-gray-700 mb-2">Dokumentasi / Pendukung</label>
                                <input type="file" name="dokumentasi[]" accept=".pdf,.jpg,.jpeg,.png" multiple
                                    class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-[#7a1621] file:text-white hover:file:bg-[#63101a] transition cursor-pointer">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom: Action Full Width --}}
            <div
                class="mt-8 bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="hidden sm:block text-xs text-gray-400 font-medium italic">
                    <i class="fas fa-info-circle mr-1"></i> Data akan disimpan sebagai draft terlebih dahulu.
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('mahasiswa.prestasi.index') }}"
                        class="w-full sm:w-auto px-8 py-3 text-sm font-bold text-gray-600 hover:bg-gray-100 rounded-xl transition-colors text-center order-2 sm:order-1">Batal</a>
                    <button type="submit"
                        class="w-full sm:w-auto px-12 py-3 bg-gradient-to-r from-[#7a1621] to-[#6D1029] text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 order-1 sm:order-2">
                        <span class="material-symbols-outlined text-[20px]">save</span> Simpan sebagai Draft
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
