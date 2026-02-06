@extends('layouts.admin')

@section('title', 'Buat Pengumuman')
@section('page-title', 'Buat Pengumuman')

@section('content')
    <div class="w-full">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border-t-4 border-maroon">
            <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700 bg-maroon text-white">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-bullhorn mr-3 text-2xl"></i>
                    Buat Pengumuman Baru
                </h3>
                <p class="text-sm mt-1 text-white text-opacity-90">Publikasikan informasi terbaru untuk civitas akademika</p>
            </div>

            <form action="{{ route('admin.pengumuman.store') }}" method="POST" class="p-4 sm:p-6">
                @csrf

                <div class="space-y-6">
                    <!-- Judul -->
                    <div>
                        <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-heading mr-1"></i>
                            Judul Pengumuman
                        </label>
                        <input type="text" 
                            name="judul" 
                            id="judul" 
                            value="{{ old('judul') }}"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon focus:border-maroon transition @error('judul') border-red-500 @enderror"
                            placeholder="Masukkan judul pengumuman yang menarik..."
                            required>
                        @error('judul')
                            <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Isi -->
                    <div>
                        <label for="isi" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-align-left mr-1"></i>
                            Isi Pengumuman
                        </label>
                        <textarea name="isi" 
                            id="isi" 
                            rows="8"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon focus:border-maroon transition @error('isi') border-red-500 @enderror"
                            placeholder="Tuliskan detail pengumuman di sini..."
                            required>{{ old('isi') }}</textarea>
                        @error('isi')
                            <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Target & Tanggal -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Target Pengumuman -->
                        <div>
                            <label for="target" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-users mr-1"></i>
                                Target Pengumuman
                            </label>
                            <select name="target" 
                                id="target"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon focus:border-maroon transition @error('target') border-red-500 @enderror"
                                required>
                                <option value="semua" {{ old('target') == 'semua' ? 'selected' : '' }}>Semua (Dosen & Mahasiswa)</option>
                                <option value="dosen" {{ old('target') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                <option value="mahasiswa" {{ old('target') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                            </select>
                            @error('target')
                                <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Publikasi -->
                        <div>
                            <label for="published_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                Tanggal Publikasi (opsional)
                            </label>
                            <input type="datetime-local" 
                                name="published_at" 
                                id="published_at" 
                                value="{{ old('published_at') }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-maroon focus:border-maroon transition @error('published_at') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kosongkan jika ingin segera dipublikasikan</p>
                            @error('published_at')
                                <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Info Box (Matches Fakultas style) -->
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-900/30 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 dark:text-blue-400 mt-1 mr-2 flex-shrink-0"></i>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-300">Petunjuk Pengisian</h4>
                            <ul class="text-sm text-blue-700 dark:text-blue-400 mt-1 list-disc list-inside space-y-1">
                                <li>Gunakan judul yang singkat namun informatif</li>
                                <li>Pastikan isi pengumuman jelas dan mudah dipahami</li>
                                <li>Anda dapat menjadwalkan publikasi di masa mendatang</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 sm:gap-4 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.pengumuman.index') }}" 
                        class="w-full sm:w-auto px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition text-center flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" 
                        class="w-full sm:w-auto bg-maroon text-white px-6 py-3 rounded-lg hover:bg-red-900 transition shadow-md flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Pengumuman
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
