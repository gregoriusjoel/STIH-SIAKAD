@extends('layouts.admin')

@section('title', 'Tambah Mata Kuliah')
@section('page-title', 'Tambah Mata Kuliah')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon">
        <div class="p-6 border-b border-gray-200 bg-maroon text-white rounded-t-xl">
            <h3 class="text-xl font-bold flex items-center">
                <i class="fas fa-book-medical mr-3 text-2xl"></i>
                Form Tambah Mata Kuliah Baru
            </h3>
            <p class="text-sm mt-1 text-white text-opacity-90">Lengkapi formulir di bawah ini untuk menambahkan mata kuliah</p>
        </div>

        <form action="{{ route('admin.mata-kuliah.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-barcode text-gray-400 mr-1"></i>
                            Kode Mata Kuliah *
                        </label>
                        <input type="text" name="kode_mk" value="{{ old('kode_mk') }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition" 
                            placeholder="Contoh: MK001"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calculator text-gray-400 mr-1"></i>
                            SKS *
                        </label>
                        <input type="number" name="sks" value="{{ old('sks') }}" min="1" max="6"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition" 
                            placeholder="Jumlah SKS"
                            required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-book-open text-gray-400 mr-1"></i>
                            Nama Mata Kuliah *
                        </label>
                        <input type="text" name="nama_mk" value="{{ old('nama_mk') }}" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition" 
                            placeholder="Masukkan nama mata kuliah"
                            required>
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
                            <option value="wajib_nasional" {{ old('jenis') == 'wajib_nasional' ? 'selected' : '' }}>Wajib Nasional</option>
                            <option value="wajib_prodi" {{ old('jenis') == 'wajib_prodi' ? 'selected' : '' }}>Wajib Prodi</option>
                            <option value="pilihan" {{ old('jenis') == 'pilihan' ? 'selected' : '' }}>Pilihan</option>
                            <option value="peminatan" {{ old('jenis') == 'peminatan' ? 'selected' : '' }}>Peminatan</option>
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
                                <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-university text-gray-400 mr-1"></i>
                            Program Studi *
                        </label>
                        <select name="prodi" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition" 
                            required>
                            <option value="">Pilih Program Studi</option>
                            <option value="Hukum Tata Kabupaten" {{ old('prodi') == 'Hukum Tata Kabupaten' ? 'selected' : '' }}>Hukum Tata Kabupaten</option>
                            <option value="Hukum Bisnis" {{ old('prodi') == 'Hukum Bisnis' ? 'selected' : '' }}>Hukum Bisnis</option>
                            <option value="Hukum Pidana" {{ old('prodi') == 'Hukum Pidana' ? 'selected' : '' }}>Hukum Pidana</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left text-gray-400 mr-1"></i>
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" rows="4" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition" 
                            placeholder="Deskripsi mata kuliah (opsional)">{{ old('deskripsi') }}</textarea>
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
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
