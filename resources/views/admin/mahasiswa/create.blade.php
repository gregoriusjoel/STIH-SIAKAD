@extends('layouts.admin')

@section('title', 'Tambah Mahasiswa')
@section('page-title', 'Tambah Mahasiswa')

@section('content')
    <div class="w-full">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border-t-4 border-maroon">
            <div class="p-6 border-b border-gray-200 bg-maroon text-white">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-user-plus mr-3 text-2xl"></i>
                    Form Tambah Mahasiswa Baru
                </h3>
                <p class="text-sm mt-1 text-white text-opacity-90">Lengkapi formulir di bawah ini untuk menambahkan
                    mahasiswa</p>
            </div>

            <form action="{{ route('admin.mahasiswa.store') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-6">
                    <!-- Data User -->
                    <div class="border-b pb-6">
                        <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-id-badge text-maroon mr-2"></i>
                            Data Akun Login
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user text-gray-400 mr-1"></i>
                                    Nama Lengkap *
                                </label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    placeholder="Masukkan nama lengkap" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                    Email *
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    placeholder="email@student.stih.ac.id" required>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock text-gray-400 mr-1"></i>
                                    Password *
                                </label>
                                <div class="relative">
                                    <input id="mahasiswa_password" type="password" name="password"
                                        value="{{ old('password', 'mahasiswa123') }}"
                                        class="w-full pr-10 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                        placeholder="Minimal 6 karakter" required>
                                    <button type="button" id="toggleMahasiswaPw" aria-pressed="false"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 inline-flex items-center px-2 text-sm text-gray-500 hover:text-gray-700 bg-transparent border-0"><i
                                            class="fas fa-eye"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Mahasiswa -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-graduation-cap text-maroon mr-2"></i>
                            Data Akademik
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-id-card text-gray-400 mr-1"></i>
                                    NIM *
                                </label>
                                <input type="text" name="nim" value="{{ old('nim') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    placeholder="Contoh: 2024010001" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-book-open text-gray-400 mr-1"></i>
                                    Program Studi *
                                </label>
                                <select name="prodi"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    required>
                                    <option value="">Pilih Program Studi</option>
                                    <option value="Hukum Tata Kabupaten" {{ old('prodi') == 'Hukum Tata Kabupaten' ? 'selected' : '' }}>Hukum Tata Kabupaten</option>
                                    <option value="Hukum Bisnis" {{ old('prodi') == 'Hukum Bisnis' ? 'selected' : '' }}>Hukum
                                        Bisnis</option>
                                    <option value="Hukum Pidana" {{ old('prodi') == 'Hukum Pidana' ? 'selected' : '' }}>Hukum
                                        Pidana</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar-alt text-gray-400 mr-1"></i>
                                    Angkatan *
                                </label>
                                <input type="text" name="angkatan" value="{{ old('angkatan', date('Y')) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    placeholder="Contoh: 2024" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-layer-group text-gray-400 mr-1"></i>
                                    Semester *
                                </label>
                                <select name="semester" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition">
                                    @for($i = 1; $i <= 8; $i++)
                                        <option value="{{ $i }}" {{ old('semester', 1) == $i ? 'selected' : '' }}>Semester
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i>
                                    No. Telepon
                                </label>
                                <input type="text" name="phone" value="{{ old('phone') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    placeholder="08xxxxxxxxxx" inputmode="numeric" pattern="\d{1,13}" maxlength="13"
                                    oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,13)">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                                    Alamat
                                </label>
                                <textarea name="address" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    placeholder="Alamat lengkap mahasiswa">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t">
                    <a href="{{ route('admin.mahasiswa.index') }}"
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pw = document.getElementById('mahasiswa_password');
        const btn = document.getElementById('toggleMahasiswaPw');
        if (btn && pw) {
            btn.addEventListener('click', function () {
                if (pw.type === 'password') { pw.type = 'text'; btn.innerHTML = '<i class="fas fa-eye-slash"></i>'; btn.setAttribute('aria-pressed', 'true'); }
                else { pw.type = 'password'; btn.innerHTML = '<i class="fas fa-eye"></i>'; btn.setAttribute('aria-pressed', 'false'); }
            });
        }
    });
</script>