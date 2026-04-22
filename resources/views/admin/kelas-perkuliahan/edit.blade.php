@extends('layouts.admin')
@section('title', 'Edit Kelas Perkuliahan')
@section('page-title', 'Kelas Perkuliahan')
@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.kelas-perkuliahan.index') }}" class="text-maroon hover:text-red-800 text-sm font-medium flex items-center gap-1">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-edit text-maroon mr-2"></i>Edit Kelas Perkuliahan
            </h3>
            <p class="text-sm text-gray-600 mt-1">Mengedit data kelas <strong>{{ $kelasPerkuliahan->nama_kelas }}</strong></p>
        </div>

        <form method="POST" action="{{ route('admin.kelas-perkuliahan.update', $kelasPerkuliahan) }}" class="p-6 space-y-5" x-data="{
            tingkat: '{{ old('tingkat', $kelasPerkuliahan->tingkat) }}',
            kodeProdi: '{{ old('kode_prodi', $kelasPerkuliahan->kode_prodi) }}',
            kodeKelas: '{{ old('kode_kelas', $kelasPerkuliahan->kode_kelas) }}',
            get previewNamaKelas() {
                if (!this.tingkat || !this.kodeProdi || !this.kodeKelas) return '...';
                return this.tingkat + this.kodeProdi.toUpperCase() + this.kodeKelas;
            }
        }">
            @csrf
            @method('PUT')

            {{-- Preview --}}
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 text-center border border-blue-200">
                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Preview Nama Kelas</p>
                <p class="text-3xl font-black text-blue-900 tracking-wider" x-text="previewNamaKelas"></p>
            </div>

            {{-- Tingkat --}}
            <div>
                <label for="tingkat" class="block text-sm font-semibold text-gray-700 mb-1">Tingkat <span class="text-red-500">*</span></label>
                <select name="tingkat" id="tingkat" x-model="tingkat" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent">
                    @for($i = 1; $i <= 8; $i++)
                        <option value="{{ $i }}" {{ old('tingkat', $kelasPerkuliahan->tingkat) == $i ? 'selected' : '' }}>Tingkat {{ $i }}</option>
                    @endfor
                </select>
                @error('tingkat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Prodi --}}
            <div>
                <label for="prodi_id" class="block text-sm font-semibold text-gray-700 mb-1">Program Studi</label>
                <select name="prodi_id" id="prodi_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent"
                    @change="kodeProdi = $event.target.selectedOptions[0]?.dataset?.kode || kodeProdi">
                    <option value="">Pilih Prodi (opsional)</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}" data-kode="{{ $prodi->kode_prodi }}" {{ old('prodi_id', $kelasPerkuliahan->prodi_id) == $prodi->id ? 'selected' : '' }}>
                            {{ $prodi->nama_prodi }} ({{ $prodi->kode_prodi }})
                        </option>
                    @endforeach
                </select>
                @error('prodi_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Kode Prodi --}}
            <div>
                <label for="kode_prodi" class="block text-sm font-semibold text-gray-700 mb-1">Kode Prodi <span class="text-red-500">*</span></label>
                <input type="text" name="kode_prodi" id="kode_prodi" x-model="kodeProdi"
                       required maxlength="10"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent font-mono uppercase">
                @error('kode_prodi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Kode Kelas --}}
            <div>
                <label for="kode_kelas" class="block text-sm font-semibold text-gray-700 mb-1">Kode Kelas <span class="text-red-500">*</span></label>
                <input type="text" name="kode_kelas" id="kode_kelas" x-model="kodeKelas"
                       required maxlength="5"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent font-mono">
                @error('kode_kelas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tahun Akademik --}}
            <div>
                <label for="tahun_akademik_id" class="block text-sm font-semibold text-gray-700 mb-1">Tahun Akademik</label>
                <select name="tahun_akademik_id" id="tahun_akademik_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent">
                    <option value="">Tidak terikat tahun akademik</option>
                    @foreach($semesters as $sem)
                        <option value="{{ $sem->id }}" {{ old('tahun_akademik_id', $kelasPerkuliahan->tahun_akademik_id) == $sem->id ? 'selected' : '' }}>{{ $sem->nama_semester }} {{ $sem->tahun_ajaran }}</option>
                    @endforeach
                </select>
                @error('tahun_akademik_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.kelas-perkuliahan.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="px-6 py-2 bg-maroon text-white rounded-lg text-sm font-medium hover:bg-red-800 transition flex items-center gap-2">
                    <i class="fas fa-save"></i> Perbarui
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
