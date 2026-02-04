@extends('layouts.admin')

@section('title', 'Edit Pengumuman')
@section('page-title', 'Edit Pengumuman')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-edit mr-3 text-maroon"></i>
                Edit Pengumuman
            </h2>
            <p class="text-gray-600 text-sm mt-1">Perbarui informasi pengumuman: {{ $pengumuman->judul }}</p>
        </div>
        <a href="{{ route('admin.pengumuman.index') }}"
            class="bg-gray-600 text-white hover:bg-gray-700 px-6 py-3 rounded-lg transition flex items-center shadow-md">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Konten Pengumuman</h3>
        </div>

        <form action="{{ route('admin.pengumuman.update', $pengumuman) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Judul -->
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-heading mr-1"></i>
                        Judul Pengumuman
                    </label>
                    <input type="text" 
                        name="judul" 
                        id="judul" 
                        value="{{ old('judul', $pengumuman->judul) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-maroon transition @error('judul') border-red-500 @enderror"
                        placeholder="Masukkan judul pengumuman..."
                        required>
                    @error('judul')
                        <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Isi -->
                <div>
                    <label for="isi" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-1"></i>
                        Isi Pengumuman
                    </label>
                    <textarea name="isi" 
                        id="isi" 
                        rows="8"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-maroon transition @error('isi') border-red-500 @enderror"
                        placeholder="Tuliskan detail pengumuman..."
                        required>{{ old('isi', $pengumuman->isi) }}</textarea>
                    @error('isi')
                        <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Target & Tanggal -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Target Pengumuman -->
                    <div>
                        <label for="target" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-users mr-1"></i>
                            Target Pengumuman
                        </label>
                        <select name="target" 
                            id="target"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-maroon transition @error('target') border-red-500 @enderror"
                            required>
                            <option value="semua" {{ old('target', $pengumuman->target) == 'semua' ? 'selected' : '' }}>Semua (Dosen & Mahasiswa)</option>
                            <option value="dosen" {{ old('target', $pengumuman->target) == 'dosen' ? 'selected' : '' }}>Dosen Only</option>
                            <option value="mahasiswa" {{ old('target', $pengumuman->target) == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa Only</option>
                        </select>
                        @error('target')
                            <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Publikasi -->
                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Tanggal Publikasi (opsional)
                        </label>
                        <input type="datetime-local" 
                            name="published_at" 
                            id="published_at" 
                            value="{{ old('published_at', $pengumuman->published_at ? \Carbon\Carbon::parse($pengumuman->published_at)->format('Y-m-d\\TH:i') : '') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-maroon transition @error('published_at') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika ingin segera dipublikasikan</p>
                        @error('published_at')
                            <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Guidance Box -->
            <div class="mt-8 p-4 bg-maroon/5 border border-maroon/10 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-maroon mt-1 mr-3 text-lg"></i>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">Petunjuk Pengeditan</h4>
                        <p class="text-sm text-gray-700 mt-1">
                            Perubahan pada pengumuman akan langsung terlihat oleh pengguna yang dituju setelah disimpan. 
                            Pastikan informasi yang diperbarui sudah akurat.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.pengumuman.index') }}" 
                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" 
                    class="bg-maroon text-white px-8 py-3 rounded-lg hover:bg-red-900 transition shadow-md flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Perbarui Pengumuman
                </button>
            </div>
        </form>
    </div>
@endsection
