@extends('layouts.admin')

@section('title', 'Tambah Dosen')
@section('page-title', 'Tambah Dosen')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary: #800020;
            --border: #d1d5db;
        }

        .two-column-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            align-items: start;
        }

        @media (max-width: 1024px) {
            .two-column-form {
                grid-template-columns: 1fr;
            }
        }

        .form-column {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-column:first-child .form-card:last-child #mata-kuliah-list {
            max-height: clamp(280px, 56vh, 560px);
            overflow-y: auto;
            padding-right: 0.25rem;
        }

        @media (max-width: 1024px) {
            .form-column:first-child .form-card:last-child #mata-kuliah-list {
                max-height: none;
                overflow-y: visible;
                padding-right: 0;
            }
        }

        .form-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 2px 6px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        .form-card:hover {
            box-shadow: 0 4px 12px rgba(128, 0, 32, 0.12), 0 8px 20px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .form-card-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f3e8e8;
        }

        .form-card-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3e8e8;
            border-radius: 0.5rem;
            color: var(--primary);
        }

        .form-card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.375rem;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            font-size: 0.9375rem;
            color: #1f2937;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .form-input::placeholder,
        .form-select::placeholder,
        .form-textarea::placeholder {
            color: #9ca3af;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(128, 0, 32, 0.1);
            background-color: #fafafa;
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23800020' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            padding-right: 2.5rem;
        }

        .form-textarea {
            resize: vertical;
            min-height: 80px;
        }

        .input-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .input-list-item {
            display: flex;
            gap: 0.75rem;
            align-items: flex-end;
        }

        .input-list-item-input {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .input-list-btn {
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            background: #f9fafb;
            color: #6b7280;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s ease;
            min-width: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .input-list-btn:hover {
            background: #f3f4f6;
            border-color: var(--primary);
            color: var(--primary);
        }

        .input-list-btn.remove {
            background: #fecaca;
            color: #dc2626;
            border-color: #fca5a5;
        }

        .input-list-btn.remove:hover {
            background: #fda29b;
            border-color: #f87171;
        }

        .help-text {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.375rem;
        }

        .select2-container--default .select2-selection--single {
            height: 44px;
            padding: 0 0.5rem;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 42px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(128, 0, 32, 0.1);
        }

        .select2-dropdown {
            border: 1px solid var(--border);
            border-radius: 0.5rem;
        }

        .select2-search--dropdown .select2-search__field {
            border: 1px solid var(--border);
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
        }

        .select2-results__option--highlighted {
            background-color: #f3e8e8 !important;
            color: var(--primary) !important;
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

            <form id="dosenForm" action="{{ route('admin.dosen.store') }}" method="POST" class="p-6">
                @csrf

                <div class="two-column-form">
                    <!-- LEFT COLUMN: Login & Courses -->
                    <div class="form-column">
                        <!-- Data Akun Login Card -->
                        <div class="form-card">
                            <div class="form-card-header">
                                <div class="form-card-icon">
                                    <i class="fas fa-sign-in-alt"></i>
                                </div>
                                <h3 class="form-card-title">Data Akun Login</h3>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-user mr-2" style="color: #6b7280;"></i>Nama Lengkap *
                                    </label>
                                    <input type="text" name="name" value="{{ old('name') }}" class="form-input"
                                        placeholder="Contoh: Dr. Ahmad Solihin, M.H." required>
                                </div>

                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-envelope mr-2" style="color: #6b7280;"></i>Email *
                                    </label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-input"
                                        placeholder="nama@stih.ac.id" required>
                                </div>

                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-lock mr-2" style="color: #6b7280;"></i>Password *
                                    </label>
                                    <div class="relative">
                                        <input id="dosen_password" type="password" name="password"
                                            value="{{ old('password', 'dosen123') }}" class="form-input"
                                            style="padding-right: 2.75rem;" placeholder="••••••••" required>
                                        <button type="button" id="toggleDosenPw" aria-pressed="false"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 bg-transparent border-0"
                                            style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mata Kuliah Card -->
                        <div class="form-card">
                            <div class="form-card-header">
                                <div class="form-card-icon">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <h3 class="form-card-title">Mata Kuliah Pengajaran</h3>
                            </div>

                            <div>
                                <label class="form-label">
                                    <i class="fas fa-list mr-2" style="color: #6b7280;"></i>Pilih Mata Kuliah
                                </label>
                                <div id="mata-kuliah-list" class="input-list">
                                    <div class="input-list-item">
                                        <select name="mata_kuliah_ids[]" class="mk-select form-select flex-1">
                                            <option value="">-- Pilih Mata Kuliah --</option>
                                            @foreach($mataKuliahs as $mk)
                                                <option value="{{ $mk->id }}">{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" id="add-mk" class="input-list-btn">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <span class="help-text">Klik tombol + untuk menambah mata kuliah</span>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: Data Dosen -->
                    <div class="form-column">
                        <!-- Data Profil Dosen Card -->
                        <div class="form-card">
                            <div class="form-card-header">
                                <div class="form-card-icon">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <h3 class="form-card-title">Data Profil Dosen</h3>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-id-card mr-2" style="color: #6b7280;"></i>NIDN *
                                    </label>
                                    <input type="text" name="nidn" value="{{ old('nidn') }}" class="form-input"
                                        placeholder="0123456789012345" inputmode="numeric" pattern="\d{1,16}" maxlength="16"
                                        oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,16)" required>
                                </div>

                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-phone mr-2" style="color: #6b7280;"></i>No. Telepon
                                    </label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-input"
                                        placeholder="08xxxxxxxxxx" inputmode="numeric" minlength="11" maxlength="13"
                                        pattern="^[0-9]{11,13}$"
                                        oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,13)"
                                        onblur="if(this.value.length > 0 && this.value.length < 11) { alert('Nomor tidak valid! Minimal 11 angka.'); }">
                                </div>

                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-building-columns mr-2" style="color: #6b7280;"></i>Fakultas *
                                    </label>
                                    <select name="fakultas_id" class="form-select" required>
                                        <option value="">-- Pilih Fakultas --</option>
                                        @foreach($fakultas as $fk)
                                            <option value="{{ $fk->id }}" {{ old('fakultas_id') == $fk->id ? 'selected' : '' }}>
                                                {{ $fk->kode_fakultas }} - {{ $fk->nama_fakultas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-business-card mr-2" style="color: #6b7280;"></i>Status Dosen Tetap
                                        *
                                    </label>
                                    <select name="dosen_tetap" class="form-select" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="ya" {{ old('dosen_tetap') == 'ya' ? 'selected' : '' }}>Dosen Tetap
                                        </option>
                                        <option value="tidak" {{ old('dosen_tetap') == 'tidak' ? 'selected' : '' }}>Dosen
                                            Tidak Tetap</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-graduation-cap mr-2" style="color: #6b7280;"></i>Pendidikan
                                        Terakhir *
                                    </label>
                                    <div id="pendidikan-list" class="input-list">
                                        <div class="pendidikan-item input-list-item">
                                            <div class="input-list-item-input">
                                                <select name="pendidikan_terakhir[]" class="form-select" required>
                                                    <option value="">-- Pilih --</option>
                                                    <option value="S1" {{ old('pendidikan_terakhir.0') == 'S1' ? 'selected' : '' }}>S1</option>
                                                    <option value="S2" {{ old('pendidikan_terakhir.0') == 'S2' ? 'selected' : '' }}>S2</option>
                                                    <option value="S3" {{ old('pendidikan_terakhir.0') == 'S3' ? 'selected' : '' }}>S3</option>
                                                </select>
                                                <input type="text" name="universitas[]" placeholder="Nama Universitas"
                                                    class="form-input" value="{{ old('universitas.0') }}" required>
                                            </div>
                                            <button type="button" id="add-pendidikan" class="input-list-btn">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <span class="help-text">Klik tombol + untuk menambah pendidikan</span>
                                </div>

                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-university mr-2" style="color: #6b7280;"></i>Program Studi *
                                    </label>
                                    @php
                                        $oldProdi = old('prodi', []);
                                        if (!is_array($oldProdi))
                                            $oldProdi = [$oldProdi];
                                    @endphp
                                    <div id="prodi-list" class="input-list">
                                        @if(count($oldProdi) > 0)
                                            @foreach($oldProdi as $idx => $p)
                                                <div class="input-list-item">
                                                    <select name="prodi[]" class="form-select flex-1" required>
                                                        <option value="">-- Pilih Program Studi --</option>
                                                        @foreach($prodis as $prodi)
                                                            <option value="{{ $prodi->kode_prodi }}" {{ $p == $prodi->kode_prodi ? 'selected' : '' }}>
                                                                {{ $prodi->kode_prodi }} - {{ $prodi->nama_prodi }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if($idx == 0)
                                                        <button type="button" id="add-prodi" class="input-list-btn">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="remove-prodi input-list-btn remove">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="input-list-item">
                                                <select name="prodi[]" class="form-select flex-1" required>
                                                    <option value="">-- Pilih Program Studi --</option>
                                                    @foreach($prodis as $prodi)
                                                        <option value="{{ $prodi->kode_prodi }}">{{ $prodi->kode_prodi }} -
                                                            {{ $prodi->nama_prodi }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="button" id="add-prodi" class="input-list-btn">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                    <span class="help-text">Klik tombol + untuk menambah program studi</span>
                                </div>

                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-briefcase mr-2" style="color: #6b7280;"></i>Jabatan Fungsional
                                    </label>
                                    <select id="jabatan-dropdown" name="jabatan_fungsional" class="form-select">
                                        <option value="">-- Pilih Jabatan --</option>
                                        <option value="Lektor" {{ old('jabatan_fungsional') == 'Lektor' ? 'selected' : '' }}>
                                            Lektor</option>
                                        <option value="Lektor Kepala" {{ old('jabatan_fungsional') == 'Lektor Kepala' ? 'selected' : '' }}>Lektor Kepala</option>
                                        <option value="Profesor" {{ old('jabatan_fungsional') == 'Profesor' ? 'selected' : '' }}>Profesor</option>
                                        <option value="Asisten Ahli" {{ old('jabatan_fungsional') == 'Asisten Ahli' ? 'selected' : '' }}>Asisten Ahli</option>
                                        <option value="Tenaga Pengajar" {{ old('jabatan_fungsional') == 'Tenaga Pengajar' ? 'selected' : '' }}>Tenaga Pengajar</option>
                                        <option value="lainnya" {{ old('jabatan_fungsional') && !in_array(old('jabatan_fungsional'), ['Lektor', 'Lektor Kepala', 'Profesor', 'Asisten Ahli', 'Tenaga Pengajar']) ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>

                                <div class="hidden" id="jabatan-custom-container">
                                    <label class="form-label">
                                        <i class="fas fa-pen mr-2" style="color: #6b7280;"></i>Jabatan Lainnya
                                    </label>
                                    <input type="text" id="jabatan-custom" name="jabatan_fungsional_custom"
                                        class="form-input" placeholder="Contoh: Dosen Senior"
                                        value="{{ old('jabatan_fungsional_custom') }}">
                                </div>

                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-toggle-on mr-2" style="color: #6b7280;"></i>Status Akun *
                                    </label>
                                    <select name="status" class="form-select" required>
                                        <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="non-aktif" {{ old('status') == 'non-aktif' ? 'selected' : '' }}>
                                            Non-Aktif</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label">
                                        <i class="fas fa-map-marker-alt mr-2" style="color: #6b7280;"></i>Alamat
                                    </label>
                                    <textarea name="address" rows="4" class="form-textarea"
                                        placeholder="Jalan, nomor rumah, kelurahan, kecamatan, kota/kabupaten, provinsi">{{ old('address') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-8 pt-6">
                            <a href="{{ route('admin.dosen.index') }}"
                                class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition flex items-center">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-3 rounded-lg bg-maroon text-white hover:bg-opacity-90 transition font-600 flex items-center shadow-md hover:shadow-lg">
                                <i class="fas fa-save mr-2"></i>
                                Simpan
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

            // Add Mata Kuliah row
            const addBtn = document.getElementById('add-mk');
            const list = document.getElementById('mata-kuliah-list');
            addBtn?.addEventListener('click', function () {
                const row = document.createElement('div');
                row.className = 'input-list-item';
                row.innerHTML = `
                        <select name="mata_kuliah_ids[]" class="mk-select form-select flex-1">
                            <option value="">-- Pilih Mata Kuliah --</option>
                            @foreach($mataKuliahs as $mk)
                                <option value="{{ $mk->id }}">{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="remove-mk input-list-btn remove">
                            <i class="fas fa-minus"></i>
                        </button>
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

            // Password Toggle
            const pw = document.getElementById('dosen_password');
            const btnPw = document.getElementById('toggleDosenPw');
            if (btnPw && pw) {
                btnPw.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (pw.type === 'password') { pw.type = 'text'; btnPw.innerHTML = '<i class="fas fa-eye-slash"></i>'; btnPw.setAttribute('aria-pressed', 'true'); }
                    else { pw.type = 'password'; btnPw.innerHTML = '<i class="fas fa-eye"></i>'; btnPw.setAttribute('aria-pressed', 'false'); }
                });
            }

            // Pendidikan Terakhir handler
            const addPendidikanBtn = document.getElementById('add-pendidikan');
            const pendidikanList = document.getElementById('pendidikan-list');
            const pendidikanOptionsHtml = `
                    <option value="">-- Pilih --</option>
                    <option value="S1">S1</option>
                    <option value="S2">S2</option>
                    <option value="S3">S3</option>
                `;

            function makePendidikanRow() {
                const row = document.createElement('div');
                row.className = 'pendidikan-item input-list-item';
                row.innerHTML = `
                        <div class="input-list-item-input">
                            <select name="pendidikan_terakhir[]" class="form-select" required>
                                ${pendidikanOptionsHtml}
                            </select>
                            <input type="text" name="universitas[]" 
                                placeholder="Nama Universitas"
                                class="form-input"
                                required>
                        </div>
                        <button type="button" class="remove-pendidikan input-list-btn remove">
                            <i class="fas fa-minus"></i>
                        </button>
                    `;
                row.querySelector('.remove-pendidikan')?.addEventListener('click', function () {
                    row.remove();
                });
                return row;
            }

            addPendidikanBtn?.addEventListener('click', function () {
                const row = makePendidikanRow();
                pendidikanList.appendChild(row);
            });

            // Program Studi handler
            const addProdi = document.getElementById('add-prodi');
            const prodiList = document.getElementById('prodi-list');
            const prodiOptionsHtml = `
                    <option value="">-- Pilih Program Studi --</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->kode_prodi }}">{{ $prodi->kode_prodi }} - {{ $prodi->nama_prodi }}</option>
                    @endforeach
                `;

            function makeProdiRow() {
                const row = document.createElement('div');
                row.className = 'input-list-item';
                row.innerHTML = `
                        <select name="prodi[]" class="form-select flex-1" required>
                            ${prodiOptionsHtml}
                        </select>
                        <button type="button" class="remove-prodi input-list-btn remove">
                            <i class="fas fa-minus"></i>
                        </button>
                    `;
                row.querySelector('.remove-prodi')?.addEventListener('click', function () {
                    row.remove();
                });
                return row;
            }

            addProdi?.addEventListener('click', function () {
                const row = makeProdiRow();
                prodiList.appendChild(row);
            });

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

            // Form Submission with SweetAlert
            const form = document.getElementById('dosenForm');
            form?.addEventListener('submit', function (e) {
                e.preventDefault();

                // Merge Jabatan values
                const dropdownVal = jabatanDropdown.value;
                const customVal = jabatanCustomInput.value.trim();
                if (dropdownVal === 'lainnya' && customVal) {
                    jabatanDropdown.value = customVal;
                }

                // Validate mata kuliah selection
                const mataKuliahSelects = document.querySelectorAll('select[name="mata_kuliah_ids[]"]');
                let hasValidSelection = false;

                mataKuliahSelects.forEach(select => {
                    if (select.value && select.value !== '') {
                        hasValidSelection = true;
                    }
                });

                if (mataKuliahSelects.length > 0 && !hasValidSelection) {
                    Swal.fire({
                        title: 'Peringatan!',
                        text: 'Mata kuliah belum dipilih! Silakan pilih minimal 1 mata kuliah atau hapus field yang kosong.',
                        icon: 'warning',
                        iconColor: '#7a1621',
                        confirmButtonColor: '#7a1621',
                        confirmButtonText: 'OK',
                        background: '#ffffff',
                        customClass: {
                            popup: 'rounded-xl'
                        }
                    });
                    return false;
                }

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data dosen baru akan disimpan ke sistem.",
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
                        cancelButton: 'px-4 py-2 rounded-lg font-bold',
                        popup: 'rounded-xl'
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