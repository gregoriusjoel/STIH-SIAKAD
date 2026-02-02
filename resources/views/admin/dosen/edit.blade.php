@extends('layouts.admin')

@section('title', 'Edit Dosen')
@section('page-title', 'Edit Dosen')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg border-t-4 border-maroon">
        <div class="p-6 border-b border-gray-200 bg-maroon text-white rounded-t-xl">
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
                                placeholder="Masukkan nama lengkap"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                Email *
                            </label>
                            <input type="email" name="email" value="{{ old('email', $dosen->user->email) }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition" 
                                placeholder="email@stih.ac.id"
                                required>
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
                                placeholder="Contoh: 0123456789"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-graduation-cap text-gray-400 mr-1"></i>
                                Pendidikan Terakhir *
                            </label>
                            <select name="pendidikan" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition" 
                                required>
                                <option value="">Pilih Pendidikan</option>
                                <option value="S1" {{ old('pendidikan', $dosen->pendidikan) == 'S1' ? 'selected' : '' }}>S1</option>
                                <option value="S2" {{ old('pendidikan', $dosen->pendidikan) == 'S2' ? 'selected' : '' }}>S2</option>
                                <option value="S3" {{ old('pendidikan', $dosen->pendidikan) == 'S3' ? 'selected' : '' }}>S3</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-university text-gray-400 mr-1"></i>
                                Program Studi *
                            </label>
                            @php
                                $selectedProdi = old('prodi', $dosen->prodi ?? []);
                                if(!is_array($selectedProdi)) $selectedProdi = [$selectedProdi];
                                $prodiOptions = ['Hukum Tata Kabupaten', 'Hukum Bisnis', 'Hukum Pidana'];
                            @endphp

                            <div id="prodi-list" class="space-y-2">
                                @if(count($selectedProdi) > 0)
                                    @foreach($selectedProdi as $idx => $p)
                                        <div class="flex items-center gap-3">
                                            <select name="prodi[]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition" required>
                                                <option value="">Pilih Program Studi</option>
                                                @foreach($prodiOptions as $opt)
                                                    <option value="{{ $opt }}" {{ $p == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                            @if($idx == 0)
                                                <button type="button" id="add-prodi" class="px-3 py-2 bg-gray-100 rounded-lg border hover:bg-gray-200">+</button>
                                            @else
                                                <button type="button" class="remove-prodi px-3 py-2 bg-red-100 text-red-700 rounded-lg border hover:bg-red-200">-</button>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex items-center gap-3">
                                        <select name="prodi[]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition" required>
                                            <option value="">Pilih Program Studi</option>
                                            @foreach($prodiOptions as $opt)
                                                <option value="{{ $opt }}">{{ $opt }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" id="add-prodi" class="px-3 py-2 bg-gray-100 rounded-lg border hover:bg-gray-200">+</button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-toggle-on text-gray-400 mr-1"></i>
                                Status *
                            </label>
                            <select name="status" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition" 
                                required>
                                <option value="aktif" {{ old('status', $dosen->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
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
                                placeholder="08xxxxxxxxxx">
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
                    <div>
                        <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-book text-maroon mr-2"></i>
                            Mata Kuliah Pengajaran
                        </h4>

                        @php
                            $totalMataKuliah = count($mataKuliahs);
                        @endphp
                        <div id="mata-kuliah-list" class="space-y-3" data-total-mk="{{ $totalMataKuliah }}">
                            @php
                                $dosenMkIds = $dosen->mata_kuliah_ids ?? [];
                                if (!is_array($dosenMkIds)) {
                                    $dosenMkIds = json_decode($dosenMkIds, true) ?: [];
                                }
                            @endphp

                            {{-- First row --}}
                            <div class="flex items-center gap-3 mk-row">
                                <select name="mata_kuliah_ids[]" class="mk-select w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition">
                                    <option value="">Pilih Mata Kuliah</option>
                                    @foreach($mataKuliahs as $mk)
                                        <option value="{{ $mk->id }}" {{ count($dosenMkIds) > 0 && $mk->id == $dosenMkIds[0] ? 'selected' : '' }}>{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="remove-mk px-3 py-2 bg-red-100 text-red-700 rounded-lg border hover:bg-red-200 {{ count($dosenMkIds) <= 1 ? 'hidden' : '' }}">-</button>
                            </div>

                            {{-- Additional rows have - button --}}
                            @if(count($dosenMkIds) > 1)
                                @foreach(array_slice($dosenMkIds, 1) as $mkId)
                                    <div class="flex items-center gap-3 mk-row">
                                        <select name="mata_kuliah_ids[]" class="mk-select w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition">
                                            <option value="">Pilih Mata Kuliah</option>
                                            @foreach($mataKuliahs as $mk)
                                                <option value="{{ $mk->id }}" {{ $mk->id == $mkId ? 'selected' : '' }}>{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="remove-mk px-3 py-2 bg-red-100 text-red-700 rounded-lg border hover:bg-red-200">-</button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        
                        {{-- Add button shown only if totalMataKuliah >= 2 --}}
                        @if($totalMataKuliah >= 2)
                            <div class="mt-3">
                                <button type="button" id="add-mk" class="inline-flex items-center px-4 py-2 bg-gray-100 rounded-lg border hover:bg-gray-200 text-sm font-medium text-gray-700">
                                    <i class="fas fa-plus mr-2"></i> Tambah Mata Kuliah
                                </button>
                            </div>
                        @endif
                        <p class="text-xs text-gray-400 mt-2">Tambahkan mata kuliah yang diampu oleh dosen.{{ $totalMataKuliah >= 2 ? ' Klik + untuk menambah baris.' : '' }}</p>
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
        <script>
        document.addEventListener('DOMContentLoaded', function(){
            // Mata Kuliah add/remove
            const addMkBtn = document.getElementById('add-mk');
            const mkList = document.getElementById('mata-kuliah-list');
            const totalMk = parseInt(mkList?.dataset.totalMk || '0', 10);

            // Get all mk options from first select
            function getAllMkOptions() {
                const firstSelect = mkList?.querySelector('.mk-select');
                if (!firstSelect) return [];
                return Array.from(firstSelect.options)
                    .filter(opt => opt.value !== '')
                    .map(opt => ({ value: opt.value, text: opt.textContent }));
            }

            // Get currently selected mk values
            function getSelectedMkValues() {
                const selects = mkList?.querySelectorAll('.mk-select') || [];
                return Array.from(selects).map(s => s.value).filter(v => v !== '');
            }

            // Update add button visibility
            function updateMkAddButtonVisibility() {
                if (!addMkBtn) return;
                const currentRowCount = mkList?.querySelectorAll('.mk-row').length || 0;
                // Hide if we've used all available mata kuliah
                if (currentRowCount >= totalMk) {
                    addMkBtn.classList.add('hidden');
                } else {
                    addMkBtn.classList.remove('hidden');
                }
            }

            // Update remove button visibility
            function updateMkRemoveButtons() {
                const rows = mkList?.querySelectorAll('.mk-row') || [];
                rows.forEach(row => {
                    const removeBtn = row.querySelector('.remove-mk');
                    if (removeBtn) {
                        if (rows.length === 1) {
                            removeBtn.classList.add('hidden');
                        } else {
                            removeBtn.classList.remove('hidden');
                        }
                    }
                });
            }

            // Sync options to disable already selected ones
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

            if(addMkBtn){
                addMkBtn.addEventListener('click', function(){
                    const currentRowCount = mkList?.querySelectorAll('.mk-row').length || 0;
                    if (currentRowCount >= totalMk) {
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Batas Tercapai',
                                text: 'Semua mata kuliah sudah ditambahkan.',
                                confirmButtonColor: '#8B1538'
                            });
                        } else {
                            alert('Semua mata kuliah sudah ditambahkan.');
                        }
                        return;
                    }

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
                    
                    row.querySelector('.remove-mk')?.addEventListener('click', function(){ 
                        row.remove(); 
                        syncMkOptions();
                        updateMkRemoveButtons();
                    });
                    row.querySelector('.mk-select')?.addEventListener('change', syncMkOptions);
                    
                    updateMkRemoveButtons();
                    updateMkAddButtonVisibility();
                });
            }
            
            document.querySelectorAll('.remove-mk').forEach(btn => btn.addEventListener('click', function(){
                const row = this.closest('.mk-row'); 
                if(row) row.remove();
                syncMkOptions();
                updateMkRemoveButtons();
            }));

            // Wire up existing selects
            document.querySelectorAll('.mk-select').forEach(select => {
                select.addEventListener('change', syncMkOptions);
            });

            // Initial sync
            syncMkOptions();
            updateMkRemoveButtons();

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
                row.querySelector('.remove-prodi')?.addEventListener('click', function(){ row.remove(); });
                return row;
            }

            if(addProdi){
                addProdi.addEventListener('click', function(){
                    const row = makeProdiRow();
                    prodiList.appendChild(row);
                });
            }

            document.querySelectorAll('.remove-prodi').forEach(btn => btn.addEventListener('click', function(){
                const row = this.closest('.flex'); if(row) row.remove();
            }));
        });
        </script>
@endsection
