@extends('layouts.admin')

@section('title', 'Edit Fakultas')
@section('page-title', 'Edit Fakultas')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-edit mr-3 text-maroon"></i>
                Edit Fakultas
            </h2>
            <p class="text-gray-600 text-sm mt-1">Edit data fakultas {{ $fakultas->nama_fakultas }}</p>
        </div>
        <a href="{{ route('admin.fakultas.index') }}"
            class="bg-gray-600 text-white hover:bg-gray-700 px-6 py-3 rounded-lg transition flex items-center shadow-md">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Informasi Fakultas</h3>
        </div>

        <form action="{{ route('admin.fakultas.update', $fakultas->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kode Fakultas -->
                <div>
                    <label for="kode_fakultas" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Fakultas
                    </label>
                    <input type="text" 
                        name="kode_fakultas" 
                        id="kode_fakultas" 
                        value="{{ old('kode_fakultas', $fakultas->kode_fakultas) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-maroon transition @error('kode_fakultas') border-red-500 @enderror"
                        placeholder="Contoh: FTEK, FEB, FH"
                        required>
                    @error('kode_fakultas')
                        <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Fakultas -->
                <div>
                    <label for="nama_fakultas" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-university mr-1"></i>
                        Nama Fakultas
                    </label>
                    <input type="text" 
                        name="nama_fakultas" 
                        id="nama_fakultas" 
                        value="{{ old('nama_fakultas', $fakultas->nama_fakultas) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-maroon transition @error('nama_fakultas') border-red-500 @enderror"
                        placeholder="Contoh: Fakultas Teknik, Fakultas Ekonomi"
                        required>
                    @error('nama_fakultas')
                        <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prodi -->
                <div>
                    <label for="prodi_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-graduation-cap mr-1"></i>
                        Prodi
                    </label>
                    <select name="prodi_id" 
                        id="prodi_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-maroon transition @error('prodi_id') border-red-500 @enderror"
                        required>
                        <option value="">Pilih Prodi</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ old('prodi_id', $fakultas->prodis->first()?->id) == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }} ({{ $prodi->jenjang }}) - {{ $prodi->kode_prodi }}
                            </option>
                        @endforeach
                    </select>
                    @error('prodi_id')
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
                        <option value="aktif" {{ old('status', $fakultas->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $fakultas->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-2"></i>
                    <div>
                        <h4 class="text-sm font-semibold text-blue-800">Informasi Relasi</h4>
                        <p class="text-sm text-blue-700 mt-1">
                            Fakultas ini terkait dengan Prodi: <strong>{{ $fakultas->prodis->first()->nama_prodi ?? '-' }}</strong> ({{ $fakultas->prodis->first()->jenjang ?? '-' }})
                        </p>
                        <p class="text-sm text-blue-700 mt-1">
                            Pastikan perubahan tidak mengganggu data yang sudah ada.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.fakultas.index') }}" 
                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" 
                    class="bg-maroon text-white px-6 py-3 rounded-lg hover:bg-red-900 transition shadow-md">
                    <i class="fas fa-save mr-2"></i>
                    Update Fakultas
                </button>
            </div>
        </form>
    </div>
@endsection