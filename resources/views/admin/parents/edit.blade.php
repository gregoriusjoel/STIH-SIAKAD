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

            <form id="parentForm" action="{{ route('admin.parents.update', $parent) }}" method="POST" class="p-6">
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
                    <div>
                        <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-users text-maroon mr-2"></i>
                            Data Orang Tua/Wali
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user-graduate text-gray-400 mr-1"></i>
                                    Mahasiswa *
                                </label>
                                <select name="mahasiswa_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition">
                                    <option value="">Pilih Mahasiswa</option>
                                    @foreach($mahasiswas as $mhs)
                                        <option value="{{ $mhs->id }}" {{ old('mahasiswa_id', $parent->mahasiswa_id) == $mhs->id ? 'selected' : '' }}>
                                            {{ $mhs->user->name }} - {{ $mhs->nim }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-heart text-gray-400 mr-1"></i>
                                    Hubungan *
                                </label>
                                <select name="hubungan"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    required>
                                    <option value="">Pilih Hubungan</option>
                                    <option value="ayah" {{ old('hubungan', $parent->hubungan) == 'ayah' ? 'selected' : '' }}>
                                        Ayah</option>
                                    <option value="ibu" {{ old('hubungan', $parent->hubungan) == 'ibu' ? 'selected' : '' }}>
                                        Ibu</option>
                                    <option value="wali" {{ old('hubungan', $parent->hubungan) == 'wali' ? 'selected' : '' }}>
                                        Wali</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-briefcase text-gray-400 mr-1"></i>
                                    Pekerjaan
                                </label>
                                <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $parent->pekerjaan) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i>
                                    No. Telepon
                                </label>
                                <input type="text" name="phone" value="{{ old('phone', $parent->phone) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    inputmode="numeric" pattern="\d{1,13}" maxlength="13"
                                    oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,13)">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                                    Alamat
                                </label>
                                <textarea name="address" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition">{{ old('address', $parent->address) }}</textarea>
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
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SweetAlert Update Confirmation
            const form = document.getElementById('parentForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Perubahan data orang tua/wali akan disimpan.",
                    icon: 'question',
                    iconColor: '#7a1621',
                    showCancelButton: true,
                    confirmButtonColor: '#7a1621',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Perbarui!',
                    cancelButtonText: 'Batal',
                    background: '#ffffff',
                    customClass: {
                        confirmButton: 'px-4 py-2 rounded-lg font-bold',
                        cancelButton: 'px-4 py-2 rounded-lg font-bold'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
@endsection