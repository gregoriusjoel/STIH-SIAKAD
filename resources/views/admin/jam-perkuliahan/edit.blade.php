@extends('layouts.admin')

@section('title', 'Edit Orang Tua/Wali')
@section('page-title', 'Edit Orang Tua/Wali')

@section('content')
    <div class="w-full">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border-t-4 border-maroon">
            <div class="p-6 border-b border-gray-200 bg-maroon text-white">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-user-edit mr-3 text-2xl"></i>
                    Edit Data Orang Tua/Wali
                </h3>
                <p class="text-sm mt-1 text-white text-opacity-90">Perbarui informasi orang tua/wali</p>
            </div>

            <form action="{{ route('admin.parents.update', $parent) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Data Akun -->
                    <div class="border-b pb-6">
                        <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-id-badge text-maroon mr-2"></i>
                            Data Akun Login
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user text-gray-400 mr-1"></i>
                                    Nama Lengkap *
                                </label>
                                <input type="text" name="name" value="{{ old('name', $parent->user->name) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                    Email *
                                </label>
                                <input type="email" name="email" value="{{ old('email', $parent->user->email) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock text-gray-400 mr-1"></i>
                                    Password (kosongkan jika tidak ingin mengubah)
                                </label>
                                <input type="password" name="password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition">
                            </div>
                        </div>
                    </div>

                    <!-- Data Parent -->
                    <!-- Data Parent -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center border-b pb-2">
                            <i class="fas fa-users text-maroon mr-2"></i>
                            Informasi Orang Tua
                        </h4>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user-graduate text-gray-400 mr-1"></i>
                                Mahasiswa *
                            </label>
                            <select name="mahasiswa_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                required>
                                <option value="">Pilih Mahasiswa</option>
                                @foreach($mahasiswas as $mhs)
                                    <option value="{{ $mhs->id }}" {{ old('mahasiswa_id', $parent->mahasiswa_id) == $mhs->id ? 'selected' : '' }}>
                                        {{ $mhs->user->name }} - {{ $mhs->nim }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Data Ayah -->
                            <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                                <h5 class="font-bold text-gray-800 mb-4 flex items-center">
                                    <span
                                        class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-2 text-sm"><i
                                            class="fas fa-male"></i></span>
                                    Data Ayah
                                </h5>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nama
                                            Ayah</label>
                                        <input type="text" name="nama_ayah"
                                            value="{{ old('nama_ayah', $parent->nama_ayah) }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded focus:border-maroon focus:ring-1 focus:ring-maroon">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-semibold text-gray-500 uppercase mb-1">Pekerjaan</label>
                                        <input type="text" name="pekerjaan_ayah"
                                            value="{{ old('pekerjaan_ayah', $parent->pekerjaan_ayah) }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded focus:border-maroon focus:ring-1 focus:ring-maroon">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">No.
                                            HP</label>
                                        <input type="text" name="handphone_ayah"
                                            value="{{ old('handphone_ayah', $parent->handphone_ayah) }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded focus:border-maroon focus:ring-1 focus:ring-maroon">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Alamat
                                            Jalan</label>
                                        <textarea name="alamat_ayah" rows="2"
                                            class="w-full px-3 py-2 border border-gray-300 rounded focus:border-maroon focus:ring-1 focus:ring-maroon">{{ old('alamat_ayah', $parent->alamat_ayah) }}</textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-500 uppercase mb-1">Kota/Kab</label>
                                            <input type="text" name="kota_ayah"
                                                value="{{ old('kota_ayah', $parent->kota_ayah) }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded focus:border-maroon focus:ring-1 focus:ring-maroon">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-500 uppercase mb-1">Provinsi</label>
                                            <input type="text" name="propinsi_ayah"
                                                value="{{ old('propinsi_ayah', $parent->propinsi_ayah) }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded focus:border-maroon focus:ring-1 focus:ring-maroon">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Ibu -->
                            <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                                <h5 class="font-bold text-gray-800 mb-4 flex items-center">
                                    <span
                                        class="w-8 h-8 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center mr-2 text-sm"><i
                                            class="fas fa-female"></i></span>
                                    Data Ibu
                                </h5>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nama
                                            Ibu</label>
                                        <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $parent->nama_ibu) }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded focus:border-maroon focus:ring-1 focus:ring-maroon">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-semibold text-gray-500 uppercase mb-1">Pekerjaan</label>
                                        <input type="text" name="pekerjaan_ibu"
                                            value="{{ old('pekerjaan_ibu', $parent->pekerjaan_ibu) }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded focus:border-maroon focus:ring-1 focus:ring-maroon">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">No.
                                            HP</label>
                                        <input type="text" name="handphone_ibu"
                                            value="{{ old('handphone_ibu', $parent->handphone_ibu) }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded focus:border-maroon focus:ring-1 focus:ring-maroon">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Alamat
                                            Jalan</label>
                                        <textarea name="alamat_ibu" rows="2"
                                            class="w-full px-3 py-2 border border-gray-300 rounded focus:border-maroon focus:ring-1 focus:ring-maroon">{{ old('alamat_ibu', $parent->alamat_ibu) }}</textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-500 uppercase mb-1">Kota/Kab</label>
                                            <input type="text" name="kota_ibu"
                                                value="{{ old('kota_ibu', $parent->kota_ibu) }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded focus:border-maroon focus:ring-1 focus:ring-maroon">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-gray-500 uppercase mb-1">Provinsi</label>
                                            <input type="text" name="propinsi_ibu"
                                                value="{{ old('propinsi_ibu', $parent->propinsi_ibu) }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded focus:border-maroon focus:ring-1 focus:ring-maroon">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hubungan Akun -->
                        <div class="mt-6 border-t pt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Akun ini milik siapa? *
                            </label>
                            <div class="flex space-x-4">
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="hubungan" value="ayah" class="text-maroon focus:ring-maroon"
                                        {{ old('hubungan', $parent->hubungan) == 'ayah' ? 'checked' : '' }} required>
                                    <span>Ayah</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="hubungan" value="ibu" class="text-maroon focus:ring-maroon" {{ old('hubungan', $parent->hubungan) == 'ibu' ? 'checked' : '' }}>
                                    <span>Ibu</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="hubungan" value="wali" class="text-maroon focus:ring-maroon"
                                        {{ old('hubungan', $parent->hubungan) == 'wali' ? 'checked' : '' }}>
                                    <span>Wali</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t">
                    <a href="{{ route('admin.parents.index') }}"
                        class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-maroon text-white px-6 py-3 rounded-lg hover:bg-opacity-90 transition flex items-center shadow-md transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i>
                        Perbarui Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection