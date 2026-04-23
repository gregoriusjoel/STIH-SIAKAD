@extends('layouts.admin')

@section('title', 'Tambah Fakultas')
@section('page-title', 'Tambah Fakultas')

@section('content')
    <div class="w-full">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden ">
            <div class="p-6 border-b border-gray-200 bg-maroon text-white">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-university mr-3 text-2xl"></i>
                    Form Tambah Fakultas
                </h3>
                <p class="text-sm mt-1 text-white text-opacity-90">Tambahkan fakultas baru ke dalam sistem</p>
            </div>

            <form action="{{ route('admin.fakultas.store') }}" method="POST" class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kode Fakultas -->
                    <div>
                        <label for="kode_fakultas" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Fakultas
                        </label>
                        <input type="text" name="kode_fakultas" id="kode_fakultas" value="{{ old('kode_fakultas') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-maroon transition @error('kode_fakultas') border-red-500 @enderror"
                            placeholder="Contoh: FTEK, FEB, FH" required>
                        @error('kode_fakultas')
                            <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Nama Fakultas -->
                    <div>
                        <label for="nama_fakultas" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-university mr-1"></i>
                            Nama Fakultas
                        </label>
                        <input type="text" name="nama_fakultas" id="nama_fakultas" value="{{ old('nama_fakultas') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-maroon transition @error('nama_fakultas') border-red-500 @enderror"
                            placeholder="Contoh: Fakultas Teknik, Fakultas Ekonomi" required>
                        @error('nama_fakultas')
                            <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-toggle-on mr-1"></i>
                            Status
                        </label>
                        <select name="status" id="status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-maroon transition @error('status') border-red-500 @enderror"
                            required>
                            <option value="">Pilih Status</option>
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-2"></i>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-800">Informasi Penting</h4>
                            <ul class="text-sm text-blue-700 mt-1 list-disc list-inside space-y-1">
                                <li>Fakultas bisa dibuat tanpa mengaitkan Program Studi (Prodi)</li>
                                <li>Status aktif berarti fakultas dapat digunakan dalam sistem</li>
                            </ul>
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
                        Simpan Fakultas
                    </button>
                </div>
            </form>
        </div>
@endsection