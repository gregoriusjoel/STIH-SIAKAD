@extends('layouts.admin')

@section('title', 'Tambah Dosen PA')
@section('page-title', 'Tambah Dosen PA')

@section('content')
<div class="w-full">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border-t-4 border-maroon">
        <div class="p-6 border-b border-gray-200 bg-maroon text-white flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold flex items-center">
                    <i class="fas fa-user-tie mr-3 text-2xl"></i>
                    Form Tambah Dosen PA
                </h3>
                <p class="text-sm mt-1 text-white text-opacity-90">Tetapkan Dosen Pembimbing Akademik untuk Mahasiswa</p>
            </div>
            <button type="button" id="customSlotBtn" class="px-3 py-1 bg-white text-maroon rounded-md text-xs font-bold hover:bg-gray-100 transition shadow-sm flex items-center gap-1">
                <i class="fas fa-cog"></i> Custom Slot
            </button>
        </div>

        <form action="{{ route('admin.dosen-pa.store') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="custom_quota" id="custom_quota" value="6">
            
            <div class="space-y-6">
                <!-- Info Validasi -->
                <!-- <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                        <div class="text-sm text-blue-700">
                            <p class="font-semibold mb-1">Aturan Penetapan Dosen PA:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Setiap Dosen PA dapat membimbing maksimal <strong>6 mahasiswa</strong>.</li>
                                <li>Setiap Mahasiswa hanya dapat memiliki <strong>1 Dosen PA</strong>.</li>
                            </ul>
                        </div>
                    </div>
                </div> -->

                <!-- Data Pemilihan -->
                <div class="">
                    <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-clipboard-check text-maroon mr-2"></i>
                        Pilih Data
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Dropdown Dosen -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-chalkboard-teacher text-gray-400 mr-1"></i>
                                Pilih Dosen PA *
                            </label>
                            <select name="dosen_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition @error('dosen_id') border-red-500 @enderror" 
                                required>
                                <option value="">-- Pilih Dosen --</option>
                                @foreach($dosens as $dosen)
                                    @php
                                        $count = $dosen->mahasiswa_pa_count;
                                        $quota = $dosen->kuota ?: 6;
                                        $isFull = $count >= $quota;
                                    @endphp
                                    <option value="{{ $dosen->id }}"
                                        data-count="{{ $count }}"
                                        data-quota="{{ $quota }}"
                                        data-name="{{ $dosen->user->name }}"
                                        data-prodi='@json($dosen->prodi)'
                                        {{ $isFull ? 'disabled' : '' }}
                                        {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}
                                        class="{{ $isFull ? 'text-gray-400' : '' }}">
                                        {{ $dosen->user->name }} ({{ $count }}/{{ $quota }}){{ $isFull ? ' - PENUH' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dosen_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p id="dosenCapacityHint" class="text-xs text-gray-500 mt-1">Dosen dengan status "PENUH" sudah mencapai batas 6 mahasiswa.</p>
                        </div>

                        <!-- Dropdown Mahasiswa -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user-graduate text-gray-400 mr-1"></i>
                                Pilih Mahasiswa *
                            </label>
                            <div id="mahasiswaContainer">
                                <div class="mb-2 mahasiswa-row flex items-center gap-2">
                                    <select name="mahasiswa_ids[]" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition mahasiswa-select" required>
                                        <option value="">-- Pilih Dosen PA terlebih dahulu --</option>
                                    </select>
                                    <button type="button" class="remove-mahasiswa-btn inline-flex items-center gap-2 px-3 py-2 border border-maroon text-maroon rounded-md hover:bg-maroon hover:text-white transition text-sm">
                                        <i class="fas fa-trash"></i>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 mt-2">
                                <button type="button" id="addMahasiswaBtn" class="px-3 py-2 bg-white border border-gray-300 rounded-md text-sm hover:bg-gray-50">+ Tambah Mahasiswa</button>
                                <span id="slotsInfo" class="text-xs text-gray-500">Slot tersedia: 6</span>
                            </div>
                            @error('mahasiswa_ids')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Tambah mahasiswa satu-per-satu hingga memenuhi kuota dosen (maks. sesuai kuota). Hanya menampilkan mahasiswa yang belum memiliki Dosen PA.</p>
                            <!-- Hidden template for cloning -->
                            <select id="mahasiswaTemplate" class="hidden">
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($mahasiswas as $mahasiswa)
                                    <option value="{{ $mahasiswa->id }}" data-prodi="{{ $mahasiswa->prodi }}">{{ $mahasiswa->user->name }} ({{ $mahasiswa->nim }})</option>
                                @endforeach
                            </select>
                            @if($mahasiswas->isEmpty())
                                <p class="text-yellow-600 text-xs mt-1 font-semibold">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Semua mahasiswa sudah memiliki Dosen PA.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t">
                <a href="{{ route('admin.dosen-pa.index') }}" 
                    class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" 
                    class="bg-maroon text-white px-6 py-3 rounded-lg hover:bg-opacity-90 transition flex items-center shadow-md transform hover:scale-105"
                    {{ $mahasiswas->isEmpty() ? 'disabled' : '' }}>
                    <i class="fas fa-save mr-2"></i>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
    <style>
        .remove-mahasiswa-btn {
            transition: background-color .15s, color .15s, border-color .15s;
        }
        .remove-mahasiswa-btn:hover,
        .remove-mahasiswa-btn:focus {
            background-color: #8B1538 !important;
            color: #fff !important;
            border-color: #8B1538 !important;
        }
        .remove-mahasiswa-btn:disabled {
            opacity: 0.45;
            pointer-events: none;
        }
    </style>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        let MAX_PER_DOSEN = 6; // Changed to let to allow customization
        const prodiMap = @json($prodiMap ?? []);
        const dosenSelect = document.querySelector('select[name="dosen_id"]');
        const container = document.getElementById('mahasiswaContainer');
        const addBtn = document.getElementById('addMahasiswaBtn');
        const template = document.getElementById('mahasiswaTemplate');
        const slotsInfo = document.getElementById('slotsInfo');
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');
        const customSlotBtn = document.getElementById('customSlotBtn');

        // Custom Slot Button Logic
        customSlotBtn.addEventListener('click', function() {
            // Check if a Dosen PA is selected first
            if (!dosenSelect.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Dosen PA Terlebih Dahulu',
                    text: 'Silakan pilih Dosen PA sebelum mengatur Custom Slot.',
                    confirmButtonColor: '#8B1538',
                });
                return;
            }
            Swal.fire({
                title: 'Atur Batas Slot',
                text: `Masukkan batas maksimal mahasiswa per Dosen PA (Saat ini: ${MAX_PER_DOSEN})`,
                input: 'number',
                inputValue: MAX_PER_DOSEN,
                showCancelButton: true,
                confirmButtonText: 'Lanjutkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#8B1538',
                inputValidator: (value) => {
                    if (!value || value < 1) {
                        return 'Harap masukkan angka yang valid (minimal 1)!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const newSlot = parseInt(result.value);
                    // Step 2: Ask for admin password
                    Swal.fire({
                        title: 'Konfirmasi Password',
                        text: 'Masukkan password akun admin untuk menyimpan perubahan slot.',
                        input: 'password',
                        inputPlaceholder: 'Password admin...',
                        showCancelButton: true,
                        confirmButtonText: 'Simpan',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#8B1538',
                        showLoaderOnConfirm: true,
                        inputValidator: (value) => {
                            if (!value) return 'Password tidak boleh kosong!';
                        },
                        preConfirm: (password) => {
                            return password;
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((pwResult) => {
                        if (pwResult.isConfirmed) {
                            const password = pwResult.value;
                            
                            // Show loading
                            let loadingAlert = Swal.fire({
                                title: 'Memeriksa Password...',
                                didOpen: () => { Swal.showLoading() },
                                allowOutsideClick: false,
                                showConfirmButton: false
                            });

                            fetch('{{ route("admin.verify-password") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ password: password })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    return response.json().then(data => {
                                        throw new Error(data.message || 'Password salah.');
                                    });
                                }
                                return response.json();
                            })
                            .then(() => {
                                // Close loading manually if needed, but the next Swal will replace it
                                MAX_PER_DOSEN = newSlot;
                                document.getElementById('custom_quota').value = newSlot;

                                // Trim excess mahasiswa rows if current count exceeds new slot limit
                                const occupied = getDosenCount();
                                const maxAllowed = Math.max(0, MAX_PER_DOSEN - occupied);
                                const rows = Array.from(container.querySelectorAll('.mahasiswa-row'));
                                while (rows.length > maxAllowed) {
                                    rows.pop().remove();
                                }

                                updateSlots();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: `Batas slot berhasil diubah menjadi ${MAX_PER_DOSEN}`,
                                    confirmButtonColor: '#8B1538',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: error.message || 'Password salah!',
                                    confirmButtonColor: '#8B1538'
                                });
                            });
                        }
                    });
                }
            });
        });

        function getDosenCount() {
            const opt = dosenSelect.options[dosenSelect.selectedIndex];
            return opt ? parseInt(opt.dataset.count || '0', 10) : 0;
        }

        function currentSelectedCount() {
            return container.querySelectorAll('.mahasiswa-row').length;
        }

        function updateSlots() {
            const occupied = getDosenCount();
            const slotsLeft = Math.max(0, MAX_PER_DOSEN - occupied);
            slotsInfo.textContent = `Slot tersedia: ${slotsLeft}`;

            if (currentSelectedCount() > slotsLeft) {
                submitBtn.disabled = true;
                slotsInfo.textContent = `Slot tersedia: ${slotsLeft} — hapus beberapa pilihan untuk melanjutkan`;
            } else {
                submitBtn.disabled = false;
            }
            // Keep addBtn enabled so we can show an alert when user attempts to add beyond quota
            addBtn.disabled = false;

            // Update dosen dropdown options text and disabled state to reflect current MAX_PER_DOSEN
            Array.from(dosenSelect.options).forEach(opt => {
                if (!opt.value) return; // skip placeholder
                const count = parseInt(opt.dataset.count || '0', 10);
                const name = opt.dataset.name || '';
                const isFull = count >= MAX_PER_DOSEN;
                opt.textContent = `${name} (${count}/${MAX_PER_DOSEN})${isFull ? ' - PENUH' : ''}`;
                opt.disabled = isFull;
                opt.className = isFull ? 'text-gray-400' : '';
            });

            // Update hint text
            const hint = document.getElementById('dosenCapacityHint');
            if (hint) hint.textContent = `Dosen dengan status "PENUH" sudah mencapai batas ${MAX_PER_DOSEN} mahasiswa.`;
        }

        function syncOptions() {
            const selects = Array.from(container.querySelectorAll('select.mahasiswa-select'));
            const selectedValues = selects.map(s => s.value).filter(v => v);

            selects.forEach(s => {
                Array.from(s.options).forEach(opt => {
                    if (!opt.value) return;
                    const isSelectedHere = s.value === opt.value;
                    opt.disabled = !isSelectedHere && selectedValues.includes(opt.value);
                });
            });
        }

        // normalized (trim, lower) prodi comparison to avoid formatting mismatches
        let currentProdiList = [];
        function normalizeList(list){
            return (list || []).map(i => String(i || '').trim().toLowerCase());
        }

        function showAllMahasiswa() {
            currentProdiList = [];
            const selects = Array.from(container.querySelectorAll('select.mahasiswa-select'));
            const templateOptions = Array.from(template.querySelectorAll('option'));
            selects.forEach(sel => {
                const currentVal = sel.value;
                sel.innerHTML = '';
                const defaultOpt = document.createElement('option');
                defaultOpt.value = '';
                defaultOpt.textContent = '-- Pilih Mahasiswa --';
                sel.appendChild(defaultOpt);
                templateOptions.forEach(opt => {
                    if (!opt.value) return;
                    const clone = opt.cloneNode(true);
                    sel.appendChild(clone);
                });
                sel.value = currentVal && Array.from(sel.options).some(o => o.value === currentVal) ? currentVal : '';
            });
            syncOptions();
        }

        function filterMahasiswaByProdi(prodiCodes) {
            // Resolve prodi codes to names using prodiMap
            const resolvedNames = (prodiCodes || []).map(code => {
                const c = String(code || '').trim();
                // Check if it's a code that exists in prodiMap, otherwise use as-is (might already be a name)
                return (prodiMap[c] || c).trim().toLowerCase();
            }).filter(n => n);
            currentProdiList = resolvedNames;
            const selects = Array.from(container.querySelectorAll('select.mahasiswa-select'));
            const templateOptions = Array.from(template.querySelectorAll('option'));
            selects.forEach(sel => {
                const currentVal = sel.value;
                sel.innerHTML = '';
                const defaultOpt = document.createElement('option');
                defaultOpt.value = '';
                defaultOpt.textContent = '-- Pilih Mahasiswa --';
                sel.appendChild(defaultOpt);
                templateOptions.forEach(opt => {
                    if (!opt.value) return;
                    const optProdi = String(opt.dataset.prodi || '').trim().toLowerCase();
                    if (resolvedNames.length > 0 && resolvedNames.includes(optProdi)) {
                        const clone = opt.cloneNode(true);
                        sel.appendChild(clone);
                    }
                });
                // restore value if still present
                sel.value = currentVal && Array.from(sel.options).some(o => o.value === currentVal) ? currentVal : '';
            });
            syncOptions();
        }

    function addMahasiswaSelect(prefill = '') {
        const wrapper = document.createElement('div');
        wrapper.className = 'mb-2 mahasiswa-row flex items-center gap-2';

        const sel = document.createElement('select');
        sel.name = 'mahasiswa_ids[]';
        sel.className = 'flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition mahasiswa-select';
        sel.innerHTML = template.innerHTML;
        if (prefill) sel.value = prefill;

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'remove-mahasiswa-btn inline-flex items-center gap-2 px-3 py-2 border border-maroon text-maroon rounded-md hover:bg-maroon hover:text-white transition text-sm';
        removeBtn.innerHTML = '<i class="fas fa-trash"></i> <span>Hapus</span>';
        removeBtn.addEventListener('click', function () {
            wrapper.remove();
            updateSlots();
            syncOptions();
        });

        sel.addEventListener('change', function () {
            syncOptions();
        });

        wrapper.appendChild(sel);
        wrapper.appendChild(removeBtn);
        container.appendChild(wrapper);
        syncOptions();
        updateSlots();
    }

        const oldValues = @json(old('mahasiswa_ids', []));
        if (oldValues && oldValues.length > 0) {
            container.innerHTML = '';
            oldValues.forEach(v => addMahasiswaSelect(String(v)));
        }

        addBtn.addEventListener('click', function () {
            if (!dosenSelect.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Dosen PA Terlebih Dahulu',
                    text: 'Silakan pilih Dosen PA sebelum menambah mahasiswa.',
                    confirmButtonColor: '#8B1538',
                });
                return;
            }
            const occupied = getDosenCount();
            const slotsLeft = Math.max(0, MAX_PER_DOSEN - occupied);
            if (currentSelectedCount() >= slotsLeft) {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Slot penuh',
                        text: 'Slot sudah penuh. Hapus pilihan atau custom slot.',
                        confirmButtonColor: '#8B1538'
                    });
                } else {
                    showError('Slot sudah penuh. Hapus pilihan atau custom slot.');
                }
                return;
            }
            addMahasiswaSelect();
        });

        // Wire up any existing remove buttons (initial row)
        document.querySelectorAll('.remove-mahasiswa-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const wrapper = btn.closest('.mahasiswa-row');
                if (wrapper) wrapper.remove();
                updateSlots();
                syncOptions();
            });
        });

        dosenSelect.addEventListener('change', function () {
            updateSlots();
            const opt = dosenSelect.options[dosenSelect.selectedIndex];

            // If no dosen selected, clear mahasiswa dropdown
            if (!opt || !opt.value) {
                container.querySelectorAll('select.mahasiswa-select').forEach(s => { s.innerHTML = '<option value="">-- Pilih Dosen PA terlebih dahulu --</option>'; });
                addBtn.disabled = true;
                submitBtn.disabled = true;
                slotsInfo.textContent = 'Pilih Dosen PA terlebih dahulu.';
                return;
            }

            let prodiData = [];
            try { prodiData = opt.dataset && opt.dataset.prodi ? JSON.parse(opt.dataset.prodi) : []; } catch(e) { prodiData = []; }

            // Load the selected dosen's stored quota
            const storedQuota = parseInt(opt.dataset.quota || '6', 10);
            MAX_PER_DOSEN = storedQuota;
            document.getElementById('custom_quota').value = storedQuota;
            updateSlots();

            if (!prodiData || prodiData.length === 0) {
                // No prodi data - show all mahasiswa
                showAllMahasiswa();
                addBtn.disabled = false;
                submitBtn.disabled = false;
            } else {
                addBtn.disabled = false;
                submitBtn.disabled = false;
                filterMahasiswaByProdi(prodiData);
                updateSlots();
            }
        });

        // initialize filtering based on currently selected dosen (if any)
        (function initDosenFilter(){
            const evt = new Event('change');
            dosenSelect.dispatchEvent(evt);
        })();

        // Ensure newly added rows are filtered according to current prodi
        const originalAddMahasiswaSelect = addMahasiswaSelect;
        addMahasiswaSelect = function(prefill = ''){
            originalAddMahasiswaSelect(prefill);
            // after adding, if currentProdiList set, apply filter to the newest select
            if (currentProdiList && currentProdiList.length > 0) {
                filterMahasiswaByProdi(currentProdiList);
            } else {
                showAllMahasiswa();
            }
        }

        container.addEventListener('change', function (e) {
            if (e.target && e.target.matches('select.mahasiswa-select')) {
                syncOptions();
                updateSlots();
            }
        });

        updateSlots();
        syncOptions();
    });
    </script>
    @endpush

    @endsection
