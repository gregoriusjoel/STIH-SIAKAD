@extends('layouts.admin')

@section('title', 'Tambah Ruangan')
@section('page-title', 'Tambah Ruangan')

@section('content')
    <div class="w-full">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border-t-4 border-maroon">
            <div class="p-6 border-b border-gray-200 bg-maroon text-white">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-door-open mr-3 text-2xl"></i>
                    Form Tambah Ruangan
                </h3>
                <p class="text-sm mt-1 text-white text-opacity-90">Tambahkan data ruangan kelas baru ke dalam sistem</p>
            </div>

            <form action="{{ route('admin.ruangan.store') }}" method="POST" class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kode Ruangan -->
                    <div>
                        <label for="kode_ruangan" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-barcode text-gray-400 mr-1"></i>
                            Kode Ruangan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="kode_ruangan" name="kode_ruangan" value="{{ old('kode_ruangan') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition @error('kode_ruangan') border-red-500 @enderror"
                            placeholder="Contoh: R101, LAB01" required>
                        @error('kode_ruangan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Ruangan -->
                    <div>
                        <label for="nama_ruangan" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-door-open text-gray-400 mr-1"></i>
                            Nama Ruangan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_ruangan" name="nama_ruangan" value="{{ old('nama_ruangan') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition @error('nama_ruangan') border-red-500 @enderror"
                            placeholder="Contoh: Ruang Kelas A, Lab Komputer 1" required>
                        @error('nama_ruangan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gedung -->
                    <div>
                        <label for="gedung" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-building text-gray-400 mr-1"></i>
                            Gedung
                        </label>
                        <input type="text" id="gedung" name="gedung" value="{{ old('gedung') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition @error('gedung') border-red-500 @enderror"
                            placeholder="Contoh: Gedung A, Gedung Utama">
                        @error('gedung')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lantai -->
                    <div>
                        <label for="lantai" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-layer-group text-gray-400 mr-1"></i>
                            Lantai
                        </label>
                        <select name="lantai" id="lantai"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition @error('lantai') border-red-500 @enderror">
                            <option value="">Pilih Lantai</option>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ old('lantai') == $i ? 'selected' : '' }}>Lantai {{ $i }}</option>
                            @endfor
                        </select>
                        @error('lantai')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kapasitas -->
                    <div>
                        <label for="kapasitas" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-users text-gray-400 mr-1"></i>
                            Kapasitas <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', 30) }}" min="1"
                            max="500"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition @error('kapasitas') border-red-500 @enderror"
                            placeholder="Jumlah mahasiswa" required>
                        @error('kapasitas')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori Ruangan -->
                    <div>
                        <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag text-gray-400 mr-1"></i>
                            Kategori Ruangan
                        </label>
                        <select name="kategori_id" id="kategori_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition @error('kategori_id') border-red-500 @enderror">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}{{ $kat->deskripsi ? ' - ' . $kat->deskripsi : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Pilih kategori untuk mengelompokkan ruangan
                        </p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-toggle-on text-gray-400 mr-1"></i>
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition @error('status') border-red-500 @enderror"
                            required>
                            <option value="">Pilih Status</option>
                            <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.ruangan.index') }}"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-maroon text-white rounded-lg hover:bg-red-900 transition shadow-md">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Ruangan
                    </button>
                </div>
            </form>
        </div>
@endsection