@extends('layouts.admin')

@section('title', 'Tambah Mahasiswa')
@section('page-title', 'Tambah Mahasiswa')

@section('content')
    <div class="w-full">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden ">
            <div class="p-6 border-b border-gray-200 bg-maroon text-white">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-user-plus mr-3 text-2xl"></i>
                    Form Tambah Mahasiswa Baru
                </h3>
                <p class="text-sm mt-1 text-white text-opacity-90">Lengkapi formulir di bawah ini untuk menambahkan
                    mahasiswa</p>
            </div>

            <form id="mahasiswaForm" action="{{ route('admin.mahasiswa.store') }}" method="POST" class="p-6">
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
                                    placeholder="Masukkan nama lengkap" 
                                    oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '').replace(/\b\w/g, char => char.toUpperCase())"
                                    required>
                                <small class="text-gray-500 mt-1 block">Hanya huruf dan spasi (angka & karakter khusus otomatis hilang)</small>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                    Email Pribadi
                                </label>
                                <input type="email" name="email_pribadi" value="{{ old('email_pribadi') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    placeholder="email@gmail.com">
                                <small class="text-gray-500 mt-1 block">Opsional - untuk komunikasi alternatif</small>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope text-blue-500 mr-1"></i>
                                    Email Kampus (Auto-generate) *
                                </label>
                                <input type="text" id="email_kampus_display" readonly
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-maroon focus:border-transparent transition cursor-not-allowed"
                                    placeholder="otomatis dari Nama">
                                <input type="hidden" name="email_kampus" id="email_kampus_field">
                                <small class="text-gray-500 mt-1 block">Dibuat otomatis dari Nama Lengkap untuk login utama</small>
                            </div>

                            <div style="display: none;">
                                <input type="hidden" name="email_aktif" value="kampus">
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
                                    @foreach($prodis as $prodi)
                                        <option value="{{ $prodi->nama_prodi }}" {{ old('prodi') == $prodi->nama_prodi ? 'selected' : '' }}>
                                            {{ $prodi->nama_prodi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar-alt text-gray-400 mr-1"></i>
                                    Angkatan *
                                </label>
                                
                                <select name="angkatan" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition" required>
                                    <option value="">Pilih Angkatan</option>
                                    @for ($year = date('Y'); $year >= 1960; $year--)
                                        <option value="{{ $year }}" {{ old('angkatan', date('Y')) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
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
                                    <i class="fas fa-venus-mars text-gray-400 mr-1"></i>
                                    Jenis Kelamin *
                                </label>
                                <select name="jenis_kelamin"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-Laki" {{ old('jenis_kelamin') == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i>
                                    No. Telepon
                                </label>
                                <input type="text" name="phone" value="{{ old('phone') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    placeholder="08xxxxxxxxxx" inputmode="numeric" minlength="11" maxlength="13" pattern="^[0-9]{11,13}$"
                                    oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,13)"
                                    onblur="if(this.value.length > 0 && this.value.length < 11) { alert('Nomor tidak valid! Minimal 11 angka.'); }">
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

@push('scripts')
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

            // Auto-generate email kampus from Nama Lengkap
            const namaInput = document.querySelector('input[name="name"]');
            const emailKampusDisplay = document.getElementById('email_kampus_display');
            const emailKampusField = document.getElementById('email_kampus_field');
            
            if (namaInput && emailKampusDisplay && emailKampusField) {
                function generateEmailKampus() {
                    const nama = namaInput.value.trim();
                    if (nama) {
                        // Remove spaces and special chars, convert to lowercase
                        const emailKampus = nama.toLowerCase().replace(/\s+/g, '') + '@student.stih.ac.id';
                        emailKampusDisplay.value = emailKampus;
                        emailKampusField.value = emailKampus;
                    } else {
                        emailKampusDisplay.value = '';
                        emailKampusField.value = '';
                    }
                }
                
                namaInput.addEventListener('input', generateEmailKampus);
                // Generate on load if Nama is already filled
                generateEmailKampus();
            }

            // SweetAlert Save Confirmation
            const form = document.getElementById('mahasiswaForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data mahasiswa baru akan disimpan ke sistem.",
                        icon: 'question',
                        iconColor: '#7a1621',
                        showCancelButton: true,
                        confirmButtonColor: '#7a1621',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Simpan!',
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
            }
        });
    </script>
@endpush