@extends('layouts.admin')

@section('title', 'Edit Prodi')
@section('page-title', 'Edit Prodi')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-edit mr-3 text-maroon"></i>
                Edit Prodi
            </h2>
            <p class="text-gray-600 text-sm mt-1">Edit data program studi {{ $prodi->nama_prodi }}</p>
        </div>
        <a href="{{ route('admin.prodi.index') }}"
            class="bg-gray-600 text-white hover:bg-gray-700 px-6 py-3 rounded-lg transition flex items-center shadow-md">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Informasi Prodi</h3>
        </div>

        <form action="{{ route('admin.prodi.update', $prodi->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

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
                        value="{{ old('kode_prodi', $prodi->kode_prodi) }}"
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
                        value="{{ old('nama_prodi', $prodi->nama_prodi) }}"
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
                            <option value="{{ $f->id }}" {{ old('fakultas_id', $prodi->fakultas_id) == $f->id ? 'selected' : '' }}>
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
                        <option value="D3" {{ old('jenjang', $prodi->jenjang) == 'D3' ? 'selected' : '' }}>D3 - Diploma Tiga</option>
                        <option value="S1" {{ old('jenjang', $prodi->jenjang) == 'S1' ? 'selected' : '' }}>S1 - Sarjana</option>
                        <option value="S2" {{ old('jenjang', $prodi->jenjang) == 'S2' ? 'selected' : '' }}>S2 - Magister</option>
                        <option value="S3" {{ old('jenjang', $prodi->jenjang) == 'S3' ? 'selected' : '' }}>S3 - Doktor</option>
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
                        <option value="aktif" {{ old('status', $prodi->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $prodi->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>
            </div>

            @if($prodi->fakultas)
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-2"></i>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-800">Informasi Terkait</h4>
                            <p class="text-sm text-blue-700 mt-1">
                                Prodi ini saat ini terkait dengan fakultas <strong>{{ $prodi->fakultas->nama_fakultas }}</strong>. 
                                Pastikan perubahan tidak mengganggu data yang sudah ada.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

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
                    Update Prodi
                </button>
            </div>
        </form>
    </div>
@endsection