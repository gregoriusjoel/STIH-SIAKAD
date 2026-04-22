@extends('layouts.admin')
@section('title', 'Tambah Kelas Perkuliahan')
@section('page-title', 'Kelas Perkuliahan')
@section('content')

<div class="w-full">
    <div class="mb-6">
        <a href="{{ route('admin.kelas-perkuliahan.index') }}" class="text-maroon hover:text-red-800 text-sm font-medium flex items-center gap-1 w-max">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-plus-circle text-maroon mr-2"></i>Tambah Kelas Perkuliahan
            </h3>
            <p class="text-sm text-gray-600 mt-1">Nama kelas akan di-generate otomatis dari kombinasi Tingkat, Kode Prodi, dan Kode Kelas.</p>
        </div>

        <form method="POST" action="{{ route('admin.kelas-perkuliahan.store') }}" class="p-6" x-data="{
            tingkat: '{{ old('tingkat', 1) }}',
            kodeProdi: '{{ old('kode_prodi', '') }}',
            kodeKelas: '{{ old('kode_kelas', '01') }}',
            get previewNamaKelas() {
                if (!this.tingkat || !this.kodeProdi || !this.kodeKelas) return '...';
                return this.tingkat + this.kodeProdi.toUpperCase() + this.kodeKelas;
            }
        }">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-6">
                <!-- Kolom Kiri: Form -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Tingkat --}}
                        <div>
                            <label for="tingkat" class="block text-sm font-semibold text-gray-700 mb-2">Tingkat <span class="text-red-500">*</span></label>
                            <select name="tingkat" id="tingkat" x-model="tingkat" required class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent bg-gray-50 hover:bg-white transition-colors">
                                @for($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}" {{ old('tingkat') == $i ? 'selected' : '' }}>Tingkat {{ $i }}</option>
                                @endfor
                            </select>
                            @error('tingkat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Tahun Akademik --}}
                        <div>
                            <label for="tahun_akademik_id" class="block text-sm font-semibold text-gray-700 mb-2">Tahun Akademik</label>
                            <select name="tahun_akademik_id" id="tahun_akademik_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent bg-gray-50 hover:bg-white transition-colors">
                                <option value="">Tidak terikat tahun akademik</option>
                                @foreach($semesters as $sem)
                                    <option value="{{ $sem->id }}" {{ old('tahun_akademik_id') == $sem->id ? 'selected' : '' }}>{{ $sem->nama_semester }} {{ $sem->tahun_ajaran }}</option>
                                @endforeach
                            </select>
                            @error('tahun_akademik_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Prodi --}}
                        <div class="md:col-span-2">
                            <label for="prodi_id" class="block text-sm font-semibold text-gray-700 mb-2">Program Studi <span class="text-red-500">*</span></label>
                            <select name="prodi_id" id="prodi_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent bg-gray-50 hover:bg-white transition-colors"
                                @change="kodeProdi = $event.target.selectedOptions[0]?.dataset?.kode || ''">
                                <option value="" disabled {{ old('prodi_id') ? '' : 'selected' }}>Pilih Prodi</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id }}" data-kode="{{ $prodi->kode_prodi }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                        {{ $prodi->nama_prodi }} ({{ $prodi->kode_prodi }})
                                    </option>
                                @endforeach
                            </select>
                            @error('prodi_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Kode Prodi --}}
                        <div>
                            <label for="kode_prodi" class="block text-sm font-semibold text-gray-700 mb-2">Kode Prodi <span class="text-red-500">*</span></label>
                            <input type="text" name="kode_prodi" id="kode_prodi" x-model="kodeProdi"
                                placeholder="Contoh: HK, PRWT" required maxlength="10"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent font-mono uppercase bg-gray-50 hover:bg-white transition-colors">
                            <p class="text-xs text-gray-500 mt-1.5">Otomatis terisi saat memilih Prodi.</p>
                            @error('kode_prodi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Kode Kelas --}}
                        <div>
                            <label for="kode_kelas" class="block text-sm font-semibold text-gray-700 mb-2">Kode Kelas <span class="text-red-500">*</span></label>
                            <input type="text" name="kode_kelas" id="kode_kelas" x-model="kodeKelas"
                                value="{{ old('kode_kelas', '01') }}" placeholder="01" required maxlength="5"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent font-mono bg-gray-50 hover:bg-white transition-colors">
                            <p class="text-xs text-gray-500 mt-1.5">Kode kelas paralel: 01, 02, 03, dst.</p>
                            @error('kode_kelas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Preview -->
                <div class="lg:col-span-4">
                    <div class="sticky top-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Preview Nama Kelas</label>
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50/50 rounded-xl p-8 text-center border-2 border-blue-100 shadow-sm h-full flex flex-col justify-center py-12">
                            <p class="text-5xl font-black text-blue-900 tracking-widest font-mono mb-3" x-text="previewNamaKelas"></p>
                            <div class="flex items-center justify-center gap-2 text-xs font-medium text-blue-600/70 tracking-wider">
                                <span>[TINGKAT]</span><i class="fas fa-plus text-[10px]"></i>
                                <span>[PRODI]</span><i class="fas fa-plus text-[10px]"></i>
                                <span>[KELAS]</span>
                            </div>
                        </div>

                        <div class="bg-blue-50/80 border border-blue-100 rounded-lg p-4 text-sm text-blue-800 mt-6 leading-relaxed">
                            <i class="fas fa-info-circle mr-1.5 text-blue-500"></i>
                            Jika kombinasi <strong>Tingkat + Kode Prodi + Kode Kelas + Tahun Akademik</strong> sudah ada, data yang ada akan digunakan kembali (tidak duplikat).
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 mt-2">
                <a href="{{ route('admin.kelas-perkuliahan.index') }}" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-maroon text-white rounded-lg text-sm font-medium hover:bg-red-800 transition-colors flex items-center gap-2 shadow-sm">
                    <i class="fas fa-save"></i> Simpan Kelas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
