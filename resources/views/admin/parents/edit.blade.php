@extends('layouts.admin')

@section('title', 'Edit Orang Tua/Wali')
@section('page-title', 'Edit Orang Tua/Wali')

@section('content')
    <div class="w-full">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden ">
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
                    <!-- Data Parent -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-users text-maroon mr-2"></i>
                            Data Mahasiswa & Orang Tua/Wali
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2 relative">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user-graduate text-gray-400 mr-1"></i>
                                    Mahasiswa *
                                </label>
                                <div class="flex gap-2">
                                    <select name="mahasiswa_id" id="mahasiswa_id"
                                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition">
                                        <option value="">Pilih Mahasiswa</option>
                                        @foreach($mahasiswas as $mhs)
                                            <option value="{{ $mhs->id }}" {{ old('mahasiswa_id', $parent->mahasiswa_id) == $mhs->id ? 'selected' : '' }}>
                                                {{ $mhs->user->name }} - {{ $mhs->nim }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="button" id="btnSyncParent"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition shadow-md flex items-center gap-2 whitespace-nowrap">
                                        <i class="fas fa-sync-alt"></i> Tarik Data
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Pilih mahasiswa, lalu klik 'Tarik Data' untuk mengisi
                                    otomatis dari database.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-heart text-gray-400 mr-1"></i>
                                    Hubungan *
                                </label>
                                <select name="hubungan" id="hubungan"
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
                                <input type="text" name="pekerjaan" id="pekerjaan"
                                    value="{{ old('pekerjaan', $parent->pekerjaan) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i>
                                    No. Telepon
                                </label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $parent->phone) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    inputmode="numeric" minlength="11" maxlength="13" pattern="^[0-9]{11,13}$"
                                    oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,13)"
                                    onblur="if(this.value.length > 0 && this.value.length < 11) { alert('Nomor tidak valid! Minimal 11 angka.'); }">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                                    Alamat
                                </label>
                                <textarea name="address" id="address" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition">{{ old('address', $parent->address) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Data Akun -->
                    <div class="border-t pt-6 mt-6">
                        <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-id-badge text-maroon mr-2"></i>
                            Data Akun Login
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @php
                                $loginName = $parent->user->name;
                                $loginEmail = $parent->user->email;
                                $loginPassword = '';
                                $isMahasiswaAccount = $parent->user && $parent->user->role === 'mahasiswa';

                                if ($isMahasiswaAccount) {
                                    if ($parent->hubungan === 'ayah' && $parent->nama_ayah)
                                        $loginName = $parent->nama_ayah;
                                    elseif ($parent->hubungan === 'ibu' && $parent->nama_ibu)
                                        $loginName = $parent->nama_ibu;
                                    elseif ($parent->hubungan === 'wali' && $parent->nama_wali)
                                        $loginName = $parent->nama_wali;
                                    elseif ($parent->nama_ayah)
                                        $loginName = $parent->nama_ayah;
                                    elseif ($parent->nama_ibu)
                                        $loginName = $parent->nama_ibu;
                                    elseif ($parent->nama_wali)
                                        $loginName = $parent->nama_wali;
                                    else
                                        $loginName = '';

                                    // Generate Email and Password from NIM
                                    $nim = $parent->mahasiswa ? $parent->mahasiswa->nim : '';
                                    $domain = '@' . 'parent.stih.ac.id';
                                    $loginEmail = $nim ? $nim . $domain : '';
                                    $loginPassword = $nim ? 'orangtua' . $nim : 'parent123';
                                }
                            @endphp

                            @if($isMahasiswaAccount)
                                <div class="md:col-span-2 bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-2">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800">Perhatian: Akun Terhubung dengan
                                                Mahasiswa</h3>
                                            <div class="mt-2 text-sm text-yellow-700">
                                                <p>Data orang tua ini ditambahkan oleh mahasiswa dan belum memiliki akun login
                                                    mandiri. Menyimpan form ini <b>tidak akan mengubah data login mahasiswa</b>.
                                                    Sistem otomatis menyarankan Email dan Password baru untuk orang tua ini.
                                                    Jika disimpan, akun baru akan dibuatkan.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user text-gray-400 mr-1"></i>
                                    Nama Lengkap *
                                </label>
                                <input type="text" name="name" id="name" value="{{ old('name', $loginName) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                    Email *
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email', $loginEmail) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock text-gray-400 mr-1"></i>
                                    Password {{ $isMahasiswaAccount ? '*' : '(kosongkan jika tidak ingin mengubah)' }}
                                </label>
                                <input type="{{ $isMahasiswaAccount ? 'text' : 'password' }}" name="password"
                                    id="parent_password" value="{{ $loginPassword }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    {{ $isMahasiswaAccount ? 'required' : '' }}>
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
            document.addEventListener('DOMContentLoaded', function () {
                // Sync Parent Data logic
                const btnSync = document.getElementById('btnSyncParent');
                const mhsSelect = document.getElementById('mahasiswa_id');
                const hubunganSelect = document.getElementById('hubungan');

                btnSync.addEventListener('click', async function () {
                    const mahasiswaId = mhsSelect.value;
                    if (!mahasiswaId) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pilih Mahasiswa',
                            text: 'Silakan pilih mahasiswa terlebih dahulu.',
                            confirmButtonColor: '#7a1621'
                        });
                        return;
                    }

                    // Add loading state
                    const originalText = btnSync.innerHTML;
                    btnSync.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                    btnSync.disabled = true;

                    try {
                        const response = await fetch(`/admin/parents/existing/${mahasiswaId}`);
                        const result = await response.json();

                        if (result.success && result.data) {
                            const data = result.data;

                            let hubungan = hubunganSelect.value;
                            if (!hubungan) {
                                if (data.nama_ayah) hubungan = 'ayah';
                                else if (data.nama_ibu) hubungan = 'ibu';
                                else if (data.nama_wali) hubungan = 'wali';
                                else hubungan = 'ayah';

                                hubunganSelect.value = hubungan;
                            }

                            if (hubungan === 'ayah') {
                                document.getElementById('name').value = data.nama_ayah || '';
                                document.getElementById('pekerjaan').value = data.pekerjaan_ayah || data.pekerjaan || '';
                                document.getElementById('phone').value = data.handphone_ayah || data.phone || '';
                                document.getElementById('address').value = data.alamat_ayah || data.address || '';
                            } else if (hubungan === 'ibu') {
                                document.getElementById('name').value = data.nama_ibu || '';
                                document.getElementById('pekerjaan').value = data.pekerjaan_ibu || data.pekerjaan || '';
                                document.getElementById('phone').value = data.handphone_ibu || data.phone || '';
                                document.getElementById('address').value = data.alamat_ibu || data.address || '';
                            } else if (hubungan === 'wali') {
                                document.getElementById('name').value = data.nama_wali || '';
                                document.getElementById('pekerjaan').value = data.pekerjaan_wali || data.pekerjaan || '';
                                document.getElementById('phone').value = data.handphone_wali || data.phone || '';
                                document.getElementById('address').value = data.alamat_wali || data.address || '';
                            }

                            // Trigger highlighting
                            ['name', 'pekerjaan', 'phone', 'address', 'hubungan'].forEach(id => {
                                const el = document.getElementById(id);
                                if (el && el.value) {
                                    el.classList.add('bg-green-50');
                                    setTimeout(() => el.classList.remove('bg-green-50'), 1500);
                                }
                            });

                            Swal.fire({
                                icon: 'success',
                                title: 'Data Ditemukan!',
                                text: `Berhasil menarik data ${hubungan} dari database.`,
                                confirmButtonColor: '#7a1621',
                                timer: 2000,
                                showConfirmButton: false
                            });

                        } else {
                            Swal.fire({
                                icon: 'info',
                                title: 'Data Kosong',
                                text: 'Mahasiswa ini belum memiliki data orang tua yang tercatat.',
                                confirmButtonColor: '#7a1621'
                            });
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Gagal menarik data dari server.',
                            confirmButtonColor: '#7a1621'
                        });
                    } finally {
                        btnSync.innerHTML = originalText;
                        btnSync.disabled = false;
                    }
                });

                // Update data dynamically if user changes 'hubungan' after syncing
                hubunganSelect.addEventListener('change', function () {
                    if (document.getElementById('name').value !== '') {
                        btnSync.click();
                    }
                });

                // SweetAlert Update Confirmation
                const form = document.getElementById('parentForm');
                form.addEventListener('submit', function (e) {
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