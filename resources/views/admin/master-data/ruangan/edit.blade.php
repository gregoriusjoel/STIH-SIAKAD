@extends('layouts.admin')

@section('title', 'Edit Ruangan')
@section('page-title', 'Edit Ruangan')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-edit mr-3 text-maroon"></i>
                Edit Ruangan: {{ $ruangan->kode_ruangan }}
            </h2>
            <p class="text-gray-600 text-sm mt-1">Edit data ruangan kelas</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.ruangan.index') }}"
                class="bg-gray-600 text-white hover:bg-gray-700 px-6 py-3 rounded-lg transition flex items-center shadow-md">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800">Form Edit Ruangan</h3>
        </div>

        <form action="{{ route('admin.ruangan.update', $ruangan) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kode Ruangan -->
                <div>
                    <label for="kode_ruangan" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-barcode text-gray-400 mr-1"></i>
                        Kode Ruangan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="kode_ruangan" 
                           name="kode_ruangan" 
                           value="{{ old('kode_ruangan', $ruangan->kode_ruangan) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition @error('kode_ruangan') border-red-500 @enderror" 
                           placeholder="Contoh: R101, LAB01"
                           required>
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
                    <input type="text" 
                           id="nama_ruangan" 
                           name="nama_ruangan" 
                           value="{{ old('nama_ruangan', $ruangan->nama_ruangan) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition @error('nama_ruangan') border-red-500 @enderror" 
                           placeholder="Contoh: Ruang Kelas A, Lab Komputer 1"
                           required>
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
                    <input type="text" 
                           id="gedung" 
                           name="gedung" 
                           value="{{ old('gedung', $ruangan->gedung) }}"
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
                    <select name="lantai" 
                            id="lantai"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition @error('lantai') border-red-500 @enderror">
                        <option value="">Pilih Lantai</option>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('lantai', $ruangan->lantai) == $i ? 'selected' : '' }}>Lantai {{ $i }}</option>
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
                    <input type="number" 
                           id="kapasitas" 
                           name="kapasitas" 
                           value="{{ old('kapasitas', $ruangan->kapasitas) }}"
                           min="1" 
                           max="500"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition @error('kapasitas') border-red-500 @enderror" 
                           placeholder="Jumlah mahasiswa"
                           required>
                    @error('kapasitas')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-toggle-on text-gray-400 mr-1"></i>
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" 
                            id="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition @error('status') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Status</option>
                        <option value="aktif" {{ old('status', $ruangan->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $ruangan->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
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
                    Update Ruangan
                </button>
            </div>
        </form>
    </div>
@endsection