@extends('layouts.admin')

@section('title', 'Tambah Dosen')
@section('page-title', 'Tambah Dosen')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container--default .select2-selection--single {
    height: 48px;
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 32px;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 46px;
}
.select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #800020;
    box-shadow: 0 0 0 2px rgba(128, 0, 32, 0.1);
}
.select2-dropdown {
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
}
.select2-search--dropdown .select2-search__field {
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 8px 12px;
}
.select2-results__option--highlighted {
    background-color: #800020 !important;
}
</style>
@endpush

@section('content')
    <div class="w-full">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border-t-4 border-maroon">
            <div class="p-6 border-b border-gray-200 bg-maroon text-white">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-chalkboard-teacher mr-3 text-2xl"></i>
                    Form Tambah Dosen Baru
                </h3>
                <p class="text-sm mt-1 text-white text-opacity-90">Lengkapi formulir di bawah ini untuk menambahkan dosen
                </p>
            </div>

            <form action="{{ route('admin.dosen.store') }}" method="POST" class="p-6">
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
                                    placeholder="email@stih.ac.id" required>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock text-gray-400 mr-1"></i>
                                    Password *
                                </label>
                                <div class="relative">
                                    <input id="dosen_password" type="password" name="password"
                                        value="{{ old('password', 'dosen123') }}"
                                        class="w-full pr-10 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                        placeholder="Minimal 6 karakter" required>
                                    <button type="button" id="toggleDosenPw" aria-pressed="false"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 inline-flex items-center px-2 text-sm text-gray-500 hover:text-gray-700 bg-transparent border-0"><i
                                            class="fas fa-eye"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Dosen -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-graduation-cap text-maroon mr-2"></i>
                            Data Dosen
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-id-card text-gray-400 mr-1"></i>
                                    NIDN *
                                </label>
                                <input type="text" name="nidn" value="{{ old('nidn') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    placeholder="Contoh: 0123456789012345" inputmode="numeric" pattern="\d{1,16}" maxlength="16"
                                    oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,16)" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-graduation-cap text-gray-400 mr-1"></i>
                                    Pendidikan Terakhir *
                                </label>
                                <div id="pendidikan-list" class="space-y-2">
                                    <div class="pendidikan-item flex items-center gap-3">
                                        <div class="flex-1 grid grid-cols-2 gap-3">
                                            <select name="pendidikan_terakhir[]"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                                required>
                                                <option value="">Pilih Pendidikan</option>
                                                <option value="S1" {{ old('pendidikan_terakhir.0') == 'S1' ? 'selected' : '' }}>S1</option>
                                                <option value="S2" {{ old('pendidikan_terakhir.0') == 'S2' ? 'selected' : '' }}>S2</option>
                                                <option value="S3" {{ old('pendidikan_terakhir.0') == 'S3' ? 'selected' : '' }}>S3</option>
                                            </select>
                                            <input type="text" name="universitas[]" 
                                                placeholder="Nama Universitas"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                                value="{{ old('universitas.0') }}"
                                                required>
                                        </div>
                                        <button type="button" id="add-pendidikan"
                                            class="px-3 py-2 bg-gray-100 rounded-lg border hover:bg-gray-200 mt-1">+</button>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mt-2">Tambahkan satu atau lebih pendidikan. Klik + untuk
                                    menambah.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-id-card text-gray-400 mr-1"></i>
                                    Status Dosen Tetap *
                                </label>
                                <select name="dosen_tetap"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    required>
                                    <option value="">Pilih Status</option>
                                    <option value="ya">Ya, Dosen Tetap</option>
                                    <option value="tidak">Tidak, Dosen Tidak Tetap</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-university text-gray-400 mr-1"></i>
                                    Program Studi *
                                </label>
                                @php
                                    $oldProdi = old('prodi', []);
                                    if (!is_array($oldProdi))
                                        $oldProdi = [$oldProdi];
                                @endphp

                                <div id="prodi-list" class="space-y-2">
                                    @if(count($oldProdi) > 0)
                                        @foreach($oldProdi as $idx => $p)
                                            <div class="flex items-center gap-3">
                                                <select name="prodi[]"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                                    required>
                                                    <option value="">Pilih Program Studi</option>
                                                    @foreach($prodis as $prodi)
                                                        <option value="{{ $prodi->kode_prodi }}" {{ $p == $prodi->kode_prodi ? 'selected' : '' }}>
                                                            {{ $prodi->kode_prodi }} - {{ $prodi->nama_prodi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if($idx == 0)
                                                    <button type="button" id="add-prodi"
                                                        class="px-3 py-2 bg-gray-100 rounded-lg border hover:bg-gray-200">+</button>
                                                @else
                                                    <button type="button"
                                                        class="remove-prodi px-3 py-2 bg-red-100 text-red-700 rounded-lg border hover:bg-red-200">-</button>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="flex items-center gap-3">
                                            <select name="prodi[]"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                                required>
                                                <option value="">Pilih Program Studi</option>
                                                @foreach($prodis as $prodi)
                                                    <option value="{{ $prodi->kode_prodi }}">{{ $prodi->kode_prodi }} - {{ $prodi->nama_prodi }}</option>
                                                @endforeach
                                            </select>
                                            <button type="button" id="add-prodi"
                                                class="px-3 py-2 bg-gray-100 rounded-lg border hover:bg-gray-200">+</button>
                                        </div>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 mt-2">Pilih satu atau lebih Program Studi. Klik + untuk
                                    menambah.</p>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-briefcase text-gray-400 mr-1"></i>
                                    Jabatan Fungsional
                                </label>
                                <div class="flex gap-3">
                                    <select id="jabatan-dropdown" name="jabatan_fungsional"
                                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition">
                                        <option value="">Pilih Jabatan Fungsional</option>
                                        <option value="Lektor">Lektor</option>
                                        <option value="Lektor Kepala">Lektor Kepala</option>
                                        <option value="Profesor">Profesor</option>
                                        <option value="Asisten Ahli">Asisten Ahli</option>
                                        <option value="Tenaga Pengajar">Tenaga Pengajar</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div id="jabatan-custom-container" class="hidden mt-3">
                                    <input type="text" id="jabatan-custom" name="jabatan_fungsional_custom"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                        placeholder="Masukkan jabatan fungsional lainnya">
                                </div>
                                <p class="text-xs text-gray-400 mt-2">Pilih satu jabatan fungsional. Jika tidak ada di
                                    daftar, pilih "Lainnya" dan isi di kolom bawah.</p>
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
                                    placeholder="Alamat lengkap dosen">{{ old('address') }}</textarea>
                            </div>
                        </div>

                        <!-- Mata Kuliah yang diajar -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-book text-maroon mr-2"></i>
                                Mata Kuliah Pengajaran
                            </h4>

                            <div id="mata-kuliah-list" class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <select name="mata_kuliah_ids[]"
                                        class="mk-select w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition">
                                        <option value="">Pilih Mata Kuliah</option>
                                        @foreach($mataKuliahs as $mk)
                                            <option value="{{ $mk->id }}">{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" id="add-mk"
                                        class="px-3 py-2 bg-gray-100 rounded-lg border hover:bg-gray-200">+</button>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">Tambahkan mata kuliah yang diampu oleh dosen. Klik + untuk
                                menambah baris.</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t">
                    <a href="{{ route('admin.dosen.index') }}"
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Select2 for initial dropdown
        $('.mk-select').select2({
            placeholder: 'Cari Mata Kuliah...',
            allowClear: true,
            width: '100%'
        });

        const addBtn = document.getElementById('add-mk');
        const list = document.getElementById('mata-kuliah-list');
        addBtn?.addEventListener('click', function () {
            const row = document.createElement('div');
            row.className = 'flex items-center gap-3';
            row.innerHTML = `
            <select name="mata_kuliah_ids[]" class="mk-select w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition">
                <option value="">Pilih Mata Kuliah</option>
                @foreach($mataKuliahs as $mk)
                    <option value="{{ $mk->id }}">{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                @endforeach
            </select>
            <button type="button" class="remove-mk px-3 py-2 bg-red-100 text-red-700 rounded-lg border hover:bg-red-200">-</button>
        `;
            list.appendChild(row);
            
            // Initialize Select2 for the new dropdown
            $(row).find('.mk-select').select2({
                placeholder: 'Cari Mata Kuliah...',
                allowClear: true,
                width: '100%'
            });
            
            row.querySelector('.remove-mk')?.addEventListener('click', function () { 
                $(row).find('.mk-select').select2('destroy');
                row.remove(); 
            });
        });

        // Form submission validation
        const form = document.querySelector('form');
        form?.addEventListener('submit', function(e) {
            const mataKuliahSelects = document.querySelectorAll('select[name="mata_kuliah_ids[]"]');
            let hasValidSelection = false;
            
            mataKuliahSelects.forEach(select => {
                if (select.value && select.value !== '') {
                    hasValidSelection = true;
                }
            });

            if (mataKuliahSelects.length > 0 && !hasValidSelection) {
                e.preventDefault();
                alert('Mata kuliah belum dipilih! Silakan pilih minimal 1 mata kuliah atau hapus field yang kosong.');
                return false;
            }
        });
    });
</script>
@endpush

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pw = document.getElementById('dosen_password');
        const btn = document.getElementById('toggleDosenPw');
        if (btn && pw) {
            btn.addEventListener('click', function () {
                if (pw.type === 'password') { pw.type = 'text'; btn.innerHTML = '<i class="fas fa-eye-slash"></i>'; btn.setAttribute('aria-pressed', 'true'); }
                else { pw.type = 'password'; btn.innerHTML = '<i class="fas fa-eye"></i>'; btn.setAttribute('aria-pressed', 'false'); }
            });
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Pendidikan Terakhir handler
        const addPendidikanBtn = document.getElementById('add-pendidikan');
        const pendidikanList = document.getElementById('pendidikan-list');
        const pendidikanOptionsHtml = `
        <option value="">Pilih Pendidikan</option>
        <option value="S1">S1</option>
        <option value="S2">S2</option>
        <option value="S3">S3</option>
    `;

        function makePendidikanRow() {
            const row = document.createElement('div');
            row.className = 'pendidikan-item flex items-center gap-3';
            row.innerHTML = `
            <div class="flex-1 grid grid-cols-2 gap-3">
                <select name="pendidikan_terakhir[]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition" required>
                    ${pendidikanOptionsHtml}
                </select>
                <input type="text" name="universitas[]" 
                    placeholder="Nama Universitas"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                    required>
            </div>
            <button type="button" class="remove-pendidikan px-3 py-2 bg-red-100 text-red-700 rounded-lg border hover:bg-red-200 mt-1">-</button>
        `;
            row.querySelector('.remove-pendidikan')?.addEventListener('click', function () { row.remove(); });
            return row;
        }

        addPendidikanBtn?.addEventListener('click', function () {
            const row = makePendidikanRow();
            pendidikanList.appendChild(row);
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Jabatan Fungsional handler
        const jabatanDropdown = document.getElementById('jabatan-dropdown');
        const jabatanCustomContainer = document.getElementById('jabatan-custom-container');
        const jabatanCustomInput = document.getElementById('jabatan-custom');

        jabatanDropdown?.addEventListener('change', function () {
            if (this.value === 'lainnya') {
                jabatanCustomContainer.classList.remove('hidden');
                jabatanCustomInput.focus();
            } else {
                jabatanCustomContainer.classList.add('hidden');
                jabatanCustomInput.value = '';
            }
        });

        // Form submit handler to merge values
        const form = document.querySelector('form');
        form?.addEventListener('submit', function (e) {
            const dropdownVal = jabatanDropdown.value;
            const customVal = jabatanCustomInput.value.trim();

            // If "Lainnya" is selected, use custom value instead
            if (dropdownVal === 'lainnya' && customVal) {
                jabatanDropdown.value = customVal;
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addProdi = document.getElementById('add-prodi');
        const prodiList = document.getElementById('prodi-list');
        const prodiOptionsHtml = `
        <option value="">Pilih Program Studi</option>
        @foreach($prodis as $prodi)
        <option value="{{ $prodi->kode_prodi }}">{{ $prodi->kode_prodi }} - {{ $prodi->nama_prodi }}</option>
        @endforeach
    `;

        function makeRow() {
            const row = document.createElement('div');
            row.className = 'flex items-center gap-3';
            row.innerHTML = `
            <select name="prodi[]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition" required>
                ${prodiOptionsHtml}
            </select>
            <button type="button" class="remove-prodi px-3 py-2 bg-red-100 text-red-700 rounded-lg border hover:bg-red-200">-</button>
        `;
            row.querySelector('.remove-prodi')?.addEventListener('click', function () { row.remove(); });
            return row;
        }

        addProdi?.addEventListener('click', function () {
            const row = makeRow();
            prodiList.appendChild(row);
        });

        // wire up existing remove buttons if any
        document.querySelectorAll('.remove-prodi').forEach(btn => btn.addEventListener('click', function () {
            const row = this.closest('.flex'); if (row) row.remove();
        }));
    });
</script>