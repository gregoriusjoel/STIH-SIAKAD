@extends('layouts.admin')

@section('title', 'Edit Dosen')
@section('page-title', 'Edit Dosen')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border-t-4 border-maroon">
            <div class="p-6 border-b border-gray-200 bg-maroon text-white">
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-user-edit mr-3 text-2xl"></i>
                    Form Edit Dosen
                </h3>
                <p class="text-sm mt-1 text-white text-opacity-90">Perbarui data dosen {{ $dosen->user->name }}</p>
            </div>

            <form action="{{ route('admin.dosen.update', $dosen) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

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
                                <input type="text" name="name" value="{{ old('name', $dosen->user->name) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    placeholder="Masukkan nama lengkap" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                    Email *
                                </label>
                                <input type="email" name="email" value="{{ old('email', $dosen->user->email) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    placeholder="email@stih.ac.id" required>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock text-gray-400 mr-1"></i>
                                    Password (kosongkan jika tidak ingin mengubah)
                                </label>
                                <div class="relative">
                                    <input id="dosen_password" type="password" name="password"
                                        class="w-full pr-10 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                        placeholder="Minimal 6 karakter">
                                    <button type="button" id="toggleDosenPw" aria-pressed="false"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 inline-flex items-center px-2 text-sm text-gray-500 hover:text-gray-700 bg-transparent border-0"><i
                                            class="fas fa-eye"></i></button>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Biarkan kosong jika tidak ingin mengubah password
                                </p>
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
                                <input type="text" name="nidn" value="{{ old('nidn', $dosen->nidn) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    placeholder="Contoh: 0123456789" inputmode="numeric" pattern="\d{1,10}" maxlength="10"
                                    oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,10)" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-graduation-cap text-gray-400 mr-1"></i>
                                    Pendidikan Terakhir *
                                </label>
                                <div id="pendidikan-list" class="space-y-2">
                                    @php
                                        $pendidikanValues = old('pendidikan_terakhir', $dosen->pendidikan_terakhir ?? []);
                                        if (!is_array($pendidikanValues))
                                            $pendidikanValues = [];
                                    @endphp
                                    @if(count($pendidikanValues) > 0)
                                        @foreach($pendidikanValues as $idx => $p)
                                            <div class="flex items-center gap-3">
                                                <select name="pendidikan_terakhir[]"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                                    required>
                                                    <option value="">Pilih Pendidikan</option>
                                                    <option value="S1" {{ $p == 'S1' ? 'selected' : '' }}>S1</option>
                                                    <option value="S2" {{ $p == 'S2' ? 'selected' : '' }}>S2</option>
                                                    <option value="S3" {{ $p == 'S3' ? 'selected' : '' }}>S3</option>
                                                </select>
                                                @if($idx == 0)
                                                    <button type="button" id="add-pendidikan"
                                                        class="px-3 py-2 bg-gray-100 rounded-lg border hover:bg-gray-200">+</button>
                                                @else
                                                    <button type="button"
                                                        class="remove-pendidikan px-3 py-2 bg-red-100 text-red-700 rounded-lg border hover:bg-red-200">-</button>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="flex items-center gap-3">
                                            <select name="pendidikan_terakhir[]"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                                required>
                                                <option value="">Pilih Pendidikan</option>
                                                <option value="S1">S1</option>
                                                <option value="S2">S2</option>
                                                <option value="S3">S3</option>
                                            </select>
                                            <button type="button" id="add-pendidikan"
                                                class="px-3 py-2 bg-gray-100 rounded-lg border hover:bg-gray-200">+</button>
                                        </div>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 mt-2">Tambahkan satu atau lebih pendidikan. Klik + untuk
                                    menambah.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-id-card text-gray-400 mr-1"></i>
                                    Dosen Tetap *
                                </label>
                                <select name="dosen_tetap"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    required>
                                    <option value="">Pilih Status</option>
                                    <option value="ya" {{ old('dosen_tetap', $dosen->dosen_tetap ? 'ya' : 'tidak') == 'ya' ? 'selected' : '' }}>Dosen Tetap</option>
                                    <option value="tidak" {{ old('dosen_tetap', $dosen->dosen_tetap ? 'ya' : 'tidak') == 'tidak' ? 'selected' : '' }}>Dosen Tidak Tetap</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-university text-gray-400 mr-1"></i>
                                    Program Studi *
                                </label>
                                @php
                                    $selectedProdi = old('prodi', $dosen->prodi ?? []);
                                    if (!is_array($selectedProdi))
                                        $selectedProdi = [$selectedProdi];
                                    $prodiOptions = ['Hukum Tata Kabupaten', 'Hukum Bisnis', 'Hukum Pidana'];
                                @endphp

                                <div id="prodi-list" class="space-y-2">
                                    @if(count($selectedProdi) > 0)
                                        @foreach($selectedProdi as $idx => $p)
                                            <div class="flex items-center gap-3">
                                                <select name="prodi[]"
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                                    required>
                                                    <option value="">Pilih Program Studi</option>
                                                    @foreach($prodiOptions as $opt)
                                                        <option value="{{ $opt }}" {{ $p == $opt ? 'selected' : '' }}>{{ $opt }}</option>
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
                                                @foreach($prodiOptions as $opt)
                                                    <option value="{{ $opt }}">{{ $opt }}</option>
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
                                @php
                                    $currentJabatan = old('jabatan_fungsional', ($dosen->jabatan_fungsional[0] ?? ''));
                                    $standardJabatans = ['Lektor', 'Lektor Kepala', 'Profesor', 'Asisten Ahli', 'Tenaga Pengajar'];
                                    $isCustomJabatan = !empty($currentJabatan) && !in_array($currentJabatan, $standardJabatans);
                                @endphp
                                <div class="flex gap-3">
                                    <select id="jabatan-dropdown" name="jabatan_fungsional"
                                        class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition">
                                        <option value="">Pilih Jabatan Fungsional</option>
                                        @foreach($standardJabatans as $sj)
                                            <option value="{{ $sj }}" {{ $currentJabatan == $sj ? 'selected' : '' }}>{{ $sj }}
                                            </option>
                                        @endforeach
                                        <option value="lainnya" {{ $isCustomJabatan ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>
                                <div id="jabatan-custom-container" class="{{ $isCustomJabatan ? '' : 'hidden' }} mt-3">
                                    <input type="text" id="jabatan-custom" name="jabatan_fungsional_custom"
                                        value="{{ $isCustomJabatan ? $currentJabatan : '' }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                        placeholder="Masukkan jabatan fungsional lainnya">
                                </div>
                                <p class="text-xs text-gray-400 mt-2">Pilih satu jabatan fungsional. Jika tidak ada di
                                    daftar, pilih "Lainnya" dan isi di kolom bawah.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-toggle-on text-gray-400 mr-1"></i>
                                    Status Akun *
                                </label>
                                <select name="status"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition"
                                    required>
                                    <option value="aktif" {{ old('status', $dosen->status) == 'aktif' ? 'selected' : '' }}>
                                        Aktif</option>
                                    <option value="non-aktif" {{ old('status', $dosen->status) == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i>
                                    No. Telepon
                                </label>
                                <input type="text" name="phone" value="{{ old('phone', $dosen->phone) }}"
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
                                    placeholder="Alamat lengkap dosen">{{ old('address', $dosen->address) }}</textarea>
                            </div>
                        </div>

                        <!-- Mata Kuliah yang diajar -->
                        <div class="mt-8">
                            <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-book text-maroon mr-2"></i>
                                Mata Kuliah Pengajaran
                            </h4>

                            @php
                                $dosenMkIds = old('mata_kuliah_ids', $dosen->mata_kuliah_ids ?? []);
                                if (!is_array($dosenMkIds)) {
                                    $dosenMkIds = json_decode($dosenMkIds, true) ?: [];
                                }
                            @endphp

                            <div id="mata-kuliah-list" class="space-y-3">
                                @if(count($dosenMkIds) > 0)
                                    @foreach($dosenMkIds as $idx => $mkId)
                                        <div class="flex items-center gap-3 mk-row">
                                            <select name="mata_kuliah_ids[]"
                                                class="mk-select w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition">
                                                <option value="">Pilih Mata Kuliah</option>
                                                @foreach($mataKuliahs as $mk)
                                                    <option value="{{ $mk->id }}" {{ $mkId == $mk->id ? 'selected' : '' }}>
                                                        {{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                                                @endforeach
                                            </select>
                                            @if($idx == 0)
                                                <button type="button" id="add-mk"
                                                    class="px-3 py-2 bg-gray-100 rounded-lg border hover:bg-gray-200">+</button>
                                            @else
                                                <button type="button"
                                                    class="remove-mk px-3 py-2 bg-red-100 text-red-700 rounded-lg border hover:bg-red-200">-</button>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex items-center gap-3 mk-row">
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
                                @endif
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
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Password Toggle
        const pw = document.getElementById('dosen_password');
        const btn = document.getElementById('toggleDosenPw');
        if (btn && pw) {
            btn.addEventListener('click', function () {
                if (pw.type === 'password') {
                    pw.type = 'text';
                    btn.innerHTML = '<i class="fas fa-eye-slash"></i>';
                    btn.setAttribute('aria-pressed', 'true');
                } else {
                    pw.type = 'password';
                    btn.innerHTML = '<i class="fas fa-eye"></i>';
                    btn.setAttribute('aria-pressed', 'false');
                }
            });
        }

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
            row.className = 'flex items-center gap-3';
            row.innerHTML = `
            <select name="pendidikan_terakhir[]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition" required>
                ${pendidikanOptionsHtml}
            </select>
            <button type="button" class="remove-pendidikan px-3 py-2 bg-red-100 text-red-700 rounded-lg border hover:bg-red-200">-</button>
        `;
            row.querySelector('.remove-pendidikan')?.addEventListener('click', function () { row.remove(); });
            return row;
        }

        addPendidikanBtn?.addEventListener('click', function () {
            const row = makePendidikanRow();
            pendidikanList.appendChild(row);
        });

        document.querySelectorAll('.remove-pendidikan').forEach(btn => btn.addEventListener('click', function () {
            const row = this.closest('.flex'); if (row) row.remove();
        }));

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

        // Program Studi add/remove
        const addProdi = document.getElementById('add-prodi');
        const prodiList = document.getElementById('prodi-list');
        const prodiOptionsHtml = `
        <option value="">Pilih Program Studi</option>
        <option value="Hukum Tata Kabupaten">Hukum Tata Kabupaten</option>
        <option value="Hukum Bisnis">Hukum Bisnis</option>
        <option value="Hukum Pidana">Hukum Pidana</option>
    `;

        function makeProdiRow() {
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
            const row = makeProdiRow();
            prodiList.appendChild(row);
        });

        document.querySelectorAll('.remove-prodi').forEach(btn => btn.addEventListener('click', function () {
            const row = this.closest('.flex'); if (row) row.remove();
        }));

        // Mata Kuliah add/remove logic (from create)
        const addMkBtn = document.getElementById('add-mk');
        const mkList = document.getElementById('mata-kuliah-list');
        const totalMk = {{ count($mataKuliahs) }};

        function getAllMkOptions() {
            const firstSelect = mkList?.querySelector('.mk-select');
            if (!firstSelect) return [];
            return Array.from(firstSelect.options)
                .filter(opt => opt.value !== '')
                .map(opt => ({ value: opt.value, text: opt.textContent }));
        }

        function getSelectedMkValues() {
            const selects = mkList?.querySelectorAll('.mk-select') || [];
            return Array.from(selects).map(s => s.value).filter(v => v !== '');
        }

        function updateMkAddButtonVisibility() {
            if (!addMkBtn) return;
            const currentRowCount = mkList?.querySelectorAll('.mk-row').length || 0;
            if (currentRowCount >= totalMk) addMkBtn.classList.add('hidden');
            else addMkBtn.classList.remove('hidden');
        }

        function updateMkRemoveButtons() {
            const rows = mkList?.querySelectorAll('.mk-row') || [];
            rows.forEach(row => {
                const removeBtn = row.querySelector('.remove-mk');
                if (removeBtn) {
                    if (rows.length === 1) removeBtn.classList.add('hidden');
                    else removeBtn.classList.remove('hidden');
                }
            });
        }

        function syncMkOptions() {
            const selects = mkList?.querySelectorAll('.mk-select') || [];
            const selectedValues = getSelectedMkValues();
            selects.forEach(select => {
                const currentValue = select.value;
                Array.from(select.options).forEach(opt => {
                    if (opt.value === '') return;
                    opt.disabled = opt.value !== currentValue && selectedValues.includes(opt.value);
                });
            });
            updateMkAddButtonVisibility();
        }

        if (addMkBtn) {
            addMkBtn.addEventListener('click', function () {
                const currentRowCount = mkList?.querySelectorAll('.mk-row').length || 0;
                if (currentRowCount >= totalMk) return;

                const allOptions = getAllMkOptions();
                const selectedValues = getSelectedMkValues();

                let optionsHtml = '<option value="">Pilih Mata Kuliah</option>';
                allOptions.forEach(opt => {
                    const isDisabled = selectedValues.includes(opt.value) ? 'disabled' : '';
                    optionsHtml += `<option value="${opt.value}" ${isDisabled}>${opt.text}</option>`;
                });

                const row = document.createElement('div');
                row.className = 'flex items-center gap-3 mk-row';
                row.innerHTML = `
                <select name="mata_kuliah_ids[]" class="mk-select w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition">
                    ${optionsHtml}
                </select>
                <button type="button" class="remove-mk px-3 py-2 bg-red-100 text-red-700 rounded-lg border hover:bg-red-200">-</button>
            `;
                mkList.appendChild(row);

                row.querySelector('.remove-mk')?.addEventListener('click', function () {
                    row.remove();
                    syncMkOptions();
                    updateMkRemoveButtons();
                });
                row.querySelector('.mk-select')?.addEventListener('change', syncMkOptions);

                updateMkRemoveButtons();
                updateMkAddButtonVisibility();
            });
        }

        // Form submit handler
        const form = document.querySelector('form');
        form?.addEventListener('submit', function (e) {
            // Merge Jabatan
            if (jabatanDropdown.value === 'lainnya' && jabatanCustomInput.value.trim()) {
                jabatanDropdown.value = jabatanCustomInput.value.trim();
            }
        });

        // Initial MK setup
        document.querySelectorAll('.mk-row').forEach(row => {
            row.querySelector('.remove-mk')?.addEventListener('click', function () {
                row.remove();
                syncMkOptions();
                updateMkRemoveButtons();
            });
            row.querySelector('.mk-select')?.addEventListener('change', syncMkOptions);
        });
        syncMkOptions();
        updateMkRemoveButtons();
    });
</script>
@endpush