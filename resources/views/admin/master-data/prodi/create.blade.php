@extends('layouts.admin')

@section('title', 'Tambah Prodi')
@section('page-title', 'Tambah Prodi')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border-t-4 border-maroon">
            <div class="p-6 border-b border-gray-200 bg-maroon text-white">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-graduation-cap mr-3 text-2xl"></i>
                    Form Tambah Prodi
                </h3>
                <p class="text-sm mt-1 text-white text-opacity-90">Tambahkan program studi baru ke dalam sistem</p>
            </div>

        <form action="{{ route('admin.prodi.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kode Prodi -->
                <div>
                    <label for="kode_prodi" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-code mr-1"></i>
                        Kode Prodi
                    </label>
                    <input type="text" 
                        name="kode_prodi" 
                        id="kode_prodi" 
                        value="{{ old('kode_prodi') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-maroon transition @error('kode_prodi') border-red-500 @enderror"
                        placeholder="Contoh: INF, MAN, HUK"
                        required>
                    @error('kode_prodi')
                        <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Prodi -->
                <div>
                    <label for="nama_prodi" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-graduation-cap mr-1"></i>
                        Nama Prodi
                    </label>
                    <input type="text" 
                        name="nama_prodi" 
                        id="nama_prodi" 
                        value="{{ old('nama_prodi') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-maroon transition @error('nama_prodi') border-red-500 @enderror"
                        placeholder="Contoh: Informatika, Manajemen"
                        required>
                    @error('nama_prodi')
                        <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fakultas -->
                <div>
                    <label for="fakultas_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-university mr-1"></i>
                        Fakultas
                    </label>
                    <select name="fakultas_id" 
                        id="fakultas_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-maroon transition @error('fakultas_id') border-red-500 @enderror"
                        required>
                        <option value="">Pilih Fakultas</option>
                        @foreach($fakultas as $f)
                            <option value="{{ $f->id }}" {{ old('fakultas_id') == $f->id ? 'selected' : '' }}>
                                {{ $f->nama_fakultas }} ({{ $f->kode_fakultas }})
                            </option>
                        @endforeach
                    </select>
                    @error('fakultas_id')
                        <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenjang -->
                <div>
                    <label for="jenjang" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-layer-group mr-1"></i>
                        Jenjang
                    </label>
                    <select name="jenjang" 
                        id="jenjang"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-maroon transition @error('jenjang') border-red-500 @enderror"
                        required>
                        <option value="">Pilih Jenjang</option>
                        <option value="D3" {{ old('jenjang') == 'D3' ? 'selected' : '' }}>D3 - Diploma Tiga</option>
                        <option value="S1" {{ old('jenjang') == 'S1' ? 'selected' : '' }}>S1 - Sarjana</option>
                        <option value="S2" {{ old('jenjang') == 'S2' ? 'selected' : '' }}>S2 - Magister</option>
                        <option value="S3" {{ old('jenjang') == 'S3' ? 'selected' : '' }}>S3 - Doktor</option>
                    </select>
                    @error('jenjang')
                        <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-toggle-on mr-1"></i>
                        Status
                    </label>
                    <select name="status" 
                        id="status"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-maroon transition @error('status') border-red-500 @enderror"
                        required>
                        <option value="">Pilih Status</option>
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.prodi.index') }}" 
                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" 
                    class="bg-maroon text-white px-6 py-3 rounded-lg hover:bg-red-900 transition shadow-md">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Prodi
                </button>
            </div>
        </form>
    </div>
@endsection