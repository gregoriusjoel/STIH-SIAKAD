@extends('layouts.admin')

@section('title', 'Edit Mata Kuliah')
@section('page-title', 'Edit Mata Kuliah')

@section('content')
    <div class="w-full">
        <div class="bg-white rounded-xl shadow-lg  overflow-hidden">
            <div class="p-6 border-b border-gray-200 bg-maroon text-white">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-edit mr-3 text-2xl"></i>
                    Edit Data Mata Kuliah
                </h3>
                <p class="text-sm mt-1 text-white text-opacity-90">Perbarui data mata kuliah {{ $mataKuliah->nama_mk }}</p>
            </div>

            <form action="{{ route('admin.mata-kuliah.update', $mataKuliah) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-barcode text-gray-400 mr-1"></i>
                                Kode Mata Kuliah *
                            </label>
                            <input type="text" name="kode_mk" value="{{ old('kode_mk', $mataKuliah->kode_mk) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                placeholder="Contoh: MK001" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calculator text-gray-400 mr-1"></i>
                                SKS *
                            </label>
                            <input type="number" name="sks" value="{{ old('sks', $mataKuliah->sks) }}" min="1" max="6"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                placeholder="Jumlah SKS" required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-book-open text-gray-400 mr-1"></i>
                                Nama Mata Kuliah *
                            </label>
                            <input type="text" name="nama_mk" value="{{ old('nama_mk', $mataKuliah->nama_mk) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                placeholder="Masukkan nama mata kuliah" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tags text-gray-400 mr-1"></i>
                                Jenis Mata Kuliah *
                            </label>
                            <select name="jenis"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                required>
                                <option value="">Pilih Jenis</option>
                                <option value="wajib_nasional" {{ old('jenis', $mataKuliah->jenis) == 'wajib_nasional' ? 'selected' : '' }}>Wajib Nasional</option>
                                <option value="wajib_prodi" {{ old('jenis', $mataKuliah->jenis) == 'wajib_prodi' ? 'selected' : '' }}>Wajib Prodi</option>
                                <option value="pilihan" {{ old('jenis', $mataKuliah->jenis) == 'pilihan' ? 'selected' : '' }}>
                                    Pilihan</option>
                                <option value="peminatan" {{ old('jenis', $mataKuliah->jenis) == 'peminatan' ? 'selected' : '' }}>Peminatan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-layer-group text-gray-400 mr-1"></i>
                                Semester *
                            </label>
                            <select name="semester"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                required>
                                <option value="">Pilih Semester</option>
                                @for($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}" {{ old('semester', $mataKuliah->semester) == $i ? 'selected' : '' }}>
                                        Semester {{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                                <input type="checkbox" name="praktikum" value="1" {{ old('praktikum', $mataKuliah->praktikum) ? 'checked' : '' }}
                                    class="w-4 h-4 text-maroon border-gray-300 rounded focus:ring-maroon transition">
                                <span class="ml-2 flex items-center">
                                    <i class="fas fa-flask text-gray-400 mr-1"></i>
                                    Mata Kuliah Ini Memiliki Praktikum / Lab
                                </span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1">Centang jika mata kuliah ini memiliki komponen
                                praktikum/lab. Jadwal praktikumnya akan ditentukan oleh dosen.</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-university text-gray-400 mr-1"></i>
                                Program Studi *
                            </label>
                            <select name="prodi_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                required>
                                <option value="">Pilih Program Studi</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id }}" {{ old('prodi_id', $mataKuliah->prodi_id) == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-align-left text-gray-400 mr-1"></i>
                                Deskripsi
                            </label>
                            <textarea name="deskripsi" rows="4"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                placeholder="Deskripsi mata kuliah (opsional)">{{ old('deskripsi', $mataKuliah->deskripsi) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t">
                    <a href="{{ route('admin.mata-kuliah.index') }}"
                        class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-maroon text-white px-6 py-3 rounded-lg hover:bg-opacity-90 transition flex items-center shadow-md transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i>
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fakultasSelect = document.getElementById('fakultas_id_edit');
            const prodiSelect = document.getElementById('prodi_id_edit');
            const allProdiOptions = Array.from(prodiSelect.options);

            fakultasSelect.addEventListener('change', function () {
                const selectedFakultasId = this.value;

                // Clear prodi select
                prodiSelect.innerHTML = '<option value="">Pilih Program Studi</option>';

                if (selectedFakultasId) {
                    // Filter prodi based on fakultas
                    allProdiOptions.forEach(option => {
                        if (option.dataset.fakultasId === selectedFakultasId) {
                            prodiSelect.appendChild(option.cloneNode(true));
                        }
                    });
                }
            });

            // Trigger change event if fakultas already selected (for old input)
            if (fakultasSelect.value) {
                fakultasSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>

@endsection