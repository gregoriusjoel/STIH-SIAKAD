@extends('layouts.admin')

@section('title', 'Edit Mahasiswa')
@section('page-title', 'Edit Mahasiswa')

@section('content')
    <div class="w-full">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden ">
            <div class="p-6 border-b border-gray-200 bg-maroon text-white">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-user-edit mr-3 text-2xl"></i>
                    Edit Data Mahasiswa
                </h3>
                <p class="text-sm mt-1 text-white text-opacity-90">Perbarui data mahasiswa {{ $mahasiswa->user->name }}</p>
            </div>

            <form action="{{ route('admin.mahasiswa.update', $mahasiswa) }}" method="POST" class="p-6 edit-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="tahun_akademik_id" value="{{ old('tahun_akademik_id', $activeTahunAkademik?->id ?? $mahasiswa->tahun_akademik_id) }}">

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
                                <input type="text" name="name" value="{{ old('name', $mahasiswa->user->name) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    placeholder="Masukkan nama lengkap"
                                    oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '').replace(/\b\w/g, char => char.toUpperCase())"
                                    required>
                                <small class="text-gray-500 mt-1 block">Hanya huruf dan spasi (angka & karakter khusus
                                    otomatis hilang)</small>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                    Email Pribadi
                                </label>
                                <input type="email" name="email_pribadi"
                                    value="{{ old('email_pribadi', $mahasiswa->email_pribadi) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    placeholder="email@gmail.com">
                                <small class="text-gray-500 mt-1 block">Opsional - untuk komunikasi alternatif</small>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope text-blue-500 mr-1"></i>
                                    Email Kampus *
                                </label>
                                <input type="email" name="email_kampus"
                                    value="{{ old('email_kampus', $mahasiswa->email_kampus) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    placeholder="nama@student.stih.ac.id" required>
                                <small class="text-gray-500 mt-1 block">Email kampus untuk login utama:
                                    [nama]@student.stih.ac.id</small>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-toggle-on text-green-500 mr-1"></i>
                                    Email Login Utama
                                </label>
                                <input type="text" value="Email Kampus"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed"
                                    readonly>
                                <input type="hidden" name="email_aktif" value="kampus">
                                <small class="text-gray-500 mt-1 block">Login mahasiswa tetap menggunakan Email Kampus</small>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock text-gray-400 mr-1"></i>
                                    Password (kosongkan jika tidak ingin mengubah)
                                </label>
                                <input type="password" name="password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    placeholder="Minimal 6 karakter">
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Biarkan kosong jika tidak ingin mengubah password
                                </p>
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
                                <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    placeholder="Contoh: 2024010001" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-book-open text-gray-400 mr-1"></i>
                                    Program Studi *
                                </label>
                                <select name="prodi_id" id="prodi_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    required>
                                    <option value="">Pilih Program Studi</option>
                                    @foreach($prodis as $prodi)
                                        <option value="{{ $prodi->id }}" {{ (string) old('prodi_id', $mahasiswa->prodi_id) === (string) $prodi->id ? 'selected' : '' }}>
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
                                <select name="angkatan" id="angkatan"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    required>
                                    <option value="">Pilih Angkatan</option>
                                    @for ($year = date('Y'); $year >= 1960; $year--)
                                        <option value="{{ $year }}" {{ old('angkatan', $mahasiswa->angkatan) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-layer-group text-gray-400 mr-1"></i>
                                    Semester *
                                </label>
                                <select name="semester" id="semester" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition">
                                    @for($i = 1; $i <= 8; $i++)
                                        <option value="{{ $i }}" {{ old('semester', $mahasiswa->semester ?? 1) == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-school text-gray-400 mr-1"></i>
                                    Kelas Perkuliahan
                                </label>
                                <select name="kelas_perkuliahan_id" id="kelas_perkuliahan_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition">
                                    <option value="">Pilih prodi dan angkatan terlebih dahulu</option>
                                    @if($selectedKelasPerkuliahan)
                                        <option value="{{ $selectedKelasPerkuliahan->id }}" selected>
                                            {{ $selectedKelasPerkuliahan->display_label }}
                                        </option>
                                    @endif
                                </select>
                                <small class="text-gray-500 mt-1 block">
                                    Kelas difilter berdasarkan prodi, angkatan, dan tahun akademik.
                                </small>
                                <small class="text-maroon mt-1 block" id="kelas_option_message">
                                    {{ $activeTahunAkademik ? 'Tahun akademik aktif: ' . $activeTahunAkademik->display_label : 'Tahun akademik aktif belum diatur.' }}
                                </small>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-toggle-on text-gray-400 mr-1"></i>
                                    Status *
                                </label>
                                <select name="status"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    required>
                                    <option value="aktif" {{ old('status', $mahasiswa->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="cuti" {{ old('status', $mahasiswa->status) == 'cuti' ? 'selected' : '' }}>
                                        Cuti</option>
                                    <option value="lulus" {{ old('status', $mahasiswa->status) == 'lulus' ? 'selected' : '' }}>Lulus</option>
                                    <option value="do" {{ old('status', $mahasiswa->status) == 'do' ? 'selected' : '' }}>Drop Out</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-venus-mars text-gray-400 mr-1"></i>
                                    Jenis Kelamin
                                </label>
                                <select name="jenis_kelamin"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-Laki" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i>
                                    No. Telepon
                                </label>
                                <input type="text" name="phone" value="{{ old('phone', $mahasiswa->phone) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    placeholder="08xxxxxxxxxx" inputmode="numeric" minlength="11" maxlength="13"
                                    pattern="^[0-9]{11,13}$"
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
                                    placeholder="Alamat lengkap mahasiswa">{{ old('address', $mahasiswa->address) }}</textarea>
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
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            const prodiSelect = document.getElementById('prodi_id');
            const angkatanSelect = document.getElementById('angkatan');
            const kelasSelect = document.getElementById('kelas_perkuliahan_id');
            const kelasMessage = document.getElementById('kelas_option_message');
            const tahunAkademikId = @json(old('tahun_akademik_id', $activeTahunAkademik?->id ?? $mahasiswa->tahun_akademik_id));
            const selectedKelasId = @json((string) old('kelas_perkuliahan_id', $selectedKelasPerkuliahan?->id));
            const optionsUrl = @json(route('kelas-perkuliahan.options'));

            async function loadKelasOptions() {
                if (!prodiSelect.value || !angkatanSelect.value) {
                    kelasSelect.innerHTML = '<option value="">Pilih prodi dan angkatan terlebih dahulu</option>';
                    kelasSelect.disabled = true;
                    kelasMessage.textContent = 'Kelas difilter berdasarkan prodi, angkatan, dan tahun akademik.';
                    return;
                }

                kelasSelect.disabled = true;
                kelasSelect.innerHTML = '<option value="">Memuat data kelas...</option>';

                try {
                    const params = new URLSearchParams({
                        prodi_id: prodiSelect.value,
                        angkatan: angkatanSelect.value,
                    });

                    if (tahunAkademikId) {
                        params.set('tahun_akademik_id', tahunAkademikId);
                    }

                    const response = await fetch(`${optionsUrl}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                    });

                    const payload = await response.json();
                    const options = payload.data || [];
                    const currentValue = kelasSelect.dataset.currentValue || selectedKelasId || '';

                    kelasSelect.innerHTML = '<option value="">Pilih Kelas Perkuliahan</option>';

                    if (options.length === 0) {
                        kelasSelect.innerHTML = '<option value="">Belum ada kelas tersedia</option>';
                        kelasSelect.disabled = true;
                    } else {
                        options.forEach((option) => {
                            const item = document.createElement('option');
                            item.value = option.id;
                            item.textContent = option.display_label;
                            if (String(currentValue) === String(option.id)) {
                                item.selected = true;
                            }
                            kelasSelect.appendChild(item);
                        });
                        kelasSelect.disabled = false;
                    }

                    kelasMessage.textContent = payload.meta?.message || 'Kelas difilter berdasarkan prodi, angkatan, dan tahun akademik.';
                    kelasSelect.dataset.currentValue = kelasSelect.value;
                } catch (error) {
                    kelasSelect.innerHTML = '<option value="">Gagal memuat kelas</option>';
                    kelasSelect.disabled = true;
                    kelasMessage.textContent = 'Gagal memuat kelas. Silakan coba lagi.';
                }
            }

            if (kelasSelect) {
                kelasSelect.dataset.currentValue = selectedKelasId || '';
            }

            prodiSelect?.addEventListener('change', () => {
                kelasSelect.dataset.currentValue = '';
                loadKelasOptions();
            });

            angkatanSelect?.addEventListener('change', () => {
                kelasSelect.dataset.currentValue = '';
                loadKelasOptions();
            });
            loadKelasOptions();

            document.querySelectorAll('.edit-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data mahasiswa ini akan diupdate!",
                        icon: 'question',
                        iconColor: '#7a1621',
                        showCancelButton: true,
                        confirmButtonColor: '#7a1621',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Update!',
                        cancelButtonText: 'Batal',
                        background: '#ffffff',
                        allowOutsideClick: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const formData = new FormData(form);

                            fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                },
                                body: formData,
                            })
                                .then(response => {
                                    if (response.redirected || response.ok) {
                                        Swal.fire({
                                            title: 'Berhasil!',
                                            text: 'Data mahasiswa berhasil diperbarui.',
                                            icon: 'success',
                                            confirmButtonColor: '#7a1621',
                                            confirmButtonText: 'OK'
                                        }).then(() => {
                                            window.location.href = "{{ route('admin.mahasiswa.index') }}";
                                        });
                                    } else {
                                        return response.json().then(data => {
                                            let errorMsg = 'Terjadi kesalahan saat mengupdate data.';
                                            if (data.errors) {
                                                errorMsg = Object.values(data.errors).flat().join('\n');
                                            } else if (data.message) {
                                                errorMsg = data.message;
                                            }
                                            Swal.fire({
                                                title: 'Gagal!',
                                                text: errorMsg,
                                                icon: 'error',
                                                confirmButtonColor: '#7a1621',
                                                confirmButtonText: 'OK'
                                            });
                                        });
                                    }
                                })
                                .catch(() => {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Terjadi kesalahan koneksi. Silakan coba lagi.',
                                        icon: 'error',
                                        confirmButtonColor: '#7a1621',
                                        confirmButtonText: 'OK'
                                    });
                                });
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
