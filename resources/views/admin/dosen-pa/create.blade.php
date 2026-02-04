@extends('layouts.admin')

@section('title', 'Tambah Dosen PA')
@section('page-title', 'Tambah Dosen PA')

@section('content')
<div class="w-full">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border-t-4 border-maroon">
        <div class="p-6 border-b border-gray-200 bg-maroon text-white">
            <h3 class="text-xl font-bold flex items-center">
                <i class="fas fa-user-tie mr-3 text-2xl"></i>
                Form Tambah Dosen PA
            </h3>
            <p class="text-sm mt-1 text-white text-opacity-90">Tetapkan Dosen Pembimbing Akademik untuk Mahasiswa</p>
        </div>

        <form action="{{ route('admin.dosen-pa.store') }}" method="POST" class="p-6">
            @csrf
            
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
                                        $isFull = $count >= 6;
                                    @endphp
                                    <option value="{{ $dosen->id }}"
                                        data-count="{{ $count }}"
                                        data-prodi='@json($dosen->prodi)'
                                        {{ $isFull ? 'disabled' : '' }}
                                        {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}
                                        class="{{ $isFull ? 'text-gray-400' : '' }}">
                                        {{ $dosen->user->name }} ({{ $count }}/6){{ $isFull ? ' - PENUH' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dosen_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Dosen dengan status "PENUH" sudah mencapai batas 6 mahasiswa.</p>
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
                                        <option value="">-- Pilih Mahasiswa --</option>
                                        @foreach($mahasiswas as $mahasiswa)
                                            <option value="{{ $mahasiswa->id }}" data-prodi="{{ $mahasiswa->prodi }}" {{ (collect(old('mahasiswa_ids'))->contains($mahasiswa->id)) ? 'selected' : '' }}>
                                                {{ $mahasiswa->user->name }} ({{ $mahasiswa->nim }})
                                            </option>
                                        @endforeach
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
        const MAX_PER_DOSEN = 6;
        const dosenSelect = document.querySelector('select[name="dosen_id"]');
        const container = document.getElementById('mahasiswaContainer');
        const addBtn = document.getElementById('addMahasiswaBtn');
        const template = document.getElementById('mahasiswaTemplate');
        const slotsInfo = document.getElementById('slotsInfo');
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');

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

        function filterMahasiswaByProdi(prodiList) {
            const normalized = normalizeList(prodiList);
            currentProdiList = normalized;
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
                    if (normalized.length > 0 && normalized.includes(optProdi)) {
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
            const occupied = getDosenCount();
            const slotsLeft = Math.max(0, MAX_PER_DOSEN - occupied);
            if (currentSelectedCount() >= slotsLeft) {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Slot penuh',
                        text: 'Slot sudah penuh untuk dosen ini. Hapus pilihan atau pilih dosen lain.',
                        confirmButtonColor: '#8B1538'
                    });
                } else {
                    alert('Slot sudah penuh untuk dosen ini. Hapus pilihan atau pilih dosen lain.');
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
            let prodiData = [];
            try { prodiData = opt && opt.dataset && opt.dataset.prodi ? JSON.parse(opt.dataset.prodi) : []; } catch(e) { prodiData = []; }

            if (!prodiData || prodiData.length === 0) {
                // disable mahasiswa selection and adding
                container.querySelectorAll('select.mahasiswa-select').forEach(s => { s.innerHTML = '<option value="">-- Pilih Mahasiswa --</option>'; });
                addBtn.disabled = true;
                submitBtn.disabled = true;
                slotsInfo.textContent = 'Dosen belum memiliki Program Studi. Tambahkan prodi pada data dosen terlebih dahulu.';
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
