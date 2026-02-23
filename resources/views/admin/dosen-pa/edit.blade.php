@extends('layouts.admin')

@section('title', 'Edit Dosen PA')
@section('page-title', 'Edit Dosen PA')

@section('content')
<div class="w-full" x-data='{ activeTab: @json(request()->get("tab", "transfer")) }'>
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border-t-4 border-maroon">
        <div class="p-6 border-b border-gray-200 bg-maroon text-white">
            <h3 class="text-xl font-bold flex items-center">
                <i class="fas fa-user-tie mr-3 text-2xl"></i>
                Edit Dosen PA
            </h3>
            <p class="text-sm mt-1 text-white text-opacity-90">Kelola mahasiswa bimbingan</p>
        </div>

        <!-- Current Dosen Info -->
        <div class="p-6 bg-gray-50 border-b">
            <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                <i class="fas fa-user-tie text-maroon mr-2"></i>
                Dosen PA Saat Ini
            </h4>
            <div class="flex items-center">
                <div class="h-12 w-12 rounded-full bg-maroon flex items-center justify-center text-white font-bold mr-4">
                    {{ strtoupper(substr($dosen->user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="text-lg font-semibold text-gray-900">{{ $dosen->user->name }}</div>
                    <div class="text-sm text-gray-500">
                        <span class="font-mono text-maroon">{{ $dosen->nidn }}</span> • 
                        {{ $dosen->user->email }} •
                        @php
                            $currentQuota = $dosen->kuota ?: 6;
                            // Handle legacy data where count > kuota
                            $displayQuota = $dosen->mahasiswaPa->count() > $currentQuota ? $dosen->mahasiswaPa->count() + 1 : $currentQuota;
                        @endphp
                        <span class="font-semibold">{{ $dosen->mahasiswaPa->count() }}/{{ $displayQuota }} Mahasiswa</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button @click="activeTab = 'transfer'" 
                        :class="activeTab === 'transfer' ? 'border-maroon text-maroon' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-6 border-b-2 font-medium text-sm transition">
                    <i class="fas fa-exchange-alt mr-2"></i>
                    Pindahkan ke Dosen Lain
                </button>
                <button @click="activeTab = 'swap'" 
                        :class="activeTab === 'swap' ? 'border-maroon text-maroon' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-6 border-b-2 font-medium text-sm transition">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Ganti Mahasiswa
                </button>
                <button @click="activeTab = 'add'" 
                        :class="activeTab === 'add' ? 'border-maroon text-maroon' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="py-4 px-6 border-b-2 font-medium text-sm transition">
                    <i class="fas fa-user-plus mr-2"></i>
                    Tambah Mahasiswa
                </button>
            </nav>
        </div>

        <!-- Tab: Transfer to Another Dosen -->
        <div x-show="activeTab === 'transfer'" x-cloak>
            <form id="transferForm" action="{{ route('admin.dosen-pa.update', $dosen->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="current_dosen_id" value="{{ $dosen->id }}">
                <input type="hidden" name="action" value="transfer">
                
                <div class="space-y-6">
                    <!-- Mahasiswa List -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-semibold text-gray-700 flex items-center">
                                <i class="fas fa-users text-maroon mr-2"></i>
                                Pilih Mahasiswa yang Akan Dipindahkan
                            </h4>
                            @if(!$dosen->mahasiswaPa->isEmpty())
                                    <label class="inline-flex items-center cursor-pointer text-sm font-medium text-gray-700">
                                        <input type="checkbox" id="selectAllMahasiswa" class="w-4 h-4 text-maroon border-gray-300 rounded focus:ring-maroon mr-2 transition duration-150 ease-in-out">
                                        Pilih Semua
                                    </label>
                                @endif
                        </div>
                        
                        @if($dosen->mahasiswaPa->isEmpty())
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-yellow-700">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Dosen ini tidak memiliki mahasiswa PA.
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-lg border border-gray-200 max-h-64 overflow-y-auto">
                                @foreach($dosen->mahasiswaPa as $mahasiswa)
                                    <label class="flex items-center p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0">
                                        <input type="checkbox" 
                                               name="mahasiswa_ids[]" 
                                               value="{{ $mahasiswa->id }}"
                                               class="w-4 h-4 text-maroon border-gray-300 rounded focus:ring-maroon">
                                        <div class="ml-3 flex-1">
                                            <div class="text-sm font-medium text-gray-900">{{ $mahasiswa->user->name }}</div>
                                            <div class="text-xs text-gray-500">NIM: {{ $mahasiswa->nim }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Total: {{ $dosen->mahasiswaPa->count() }} mahasiswa
                            </p>
                            @error('mahasiswa_ids')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    <!-- Target Dosen -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-user-tie text-maroon mr-2"></i>
                            Pindahkan ke Dosen PA
                        </h4>
                        
                        <select name="new_dosen_id" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition @error('new_dosen_id') border-red-500 @enderror" 
                            required>
                            <option value="">-- Pilih Dosen Tujuan --</option>
                            @foreach($allDosens as $targetDosen)
                                @php
                                    $count = $targetDosen->mahasiswa_pa_count;
                                    $tQuota = $targetDosen->kuota ?: 6;
                                    // Handle legacy data
                                    $tDisplayQuota = $count > $tQuota ? $count + 1 : $tQuota;
                                    $isFull = $count >= $tDisplayQuota;
                                @endphp
                                <option value="{{ $targetDosen->id }}" 
                                    {{ $isFull ? 'disabled' : '' }}
                                    {{ old('new_dosen_id') == $targetDosen->id ? 'selected' : '' }}
                                    class="{{ $isFull ? 'text-gray-400' : '' }}">
                                    {{ $targetDosen->user->name }} ({{ $count }}/{{ $tDisplayQuota }}){{ $isFull ? ' - PENUH' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('new_dosen_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Dosen dengan status "PENUH" sudah mencapai batas kuota mahasiswa.</p>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t">
                    <a href="{{ route('admin.dosen-pa.index') }}" 
                        class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" 
                        class="btn-maroon px-6 py-3 rounded-lg hover:bg-opacity-90 transition flex items-center shadow-md transform hover:scale-105"
                        {{ $dosen->mahasiswaPa->isEmpty() ? 'disabled' : '' }}>
                        <i class="fas fa-exchange-alt mr-2"></i>
                        Pindahkan Mahasiswa
                    </button>
                </div>
            </form>
        </div>

        <!-- Tab: Swap Mahasiswa -->
        <div x-show="activeTab === 'swap'" x-cloak>
            <form id="swapForm" action="{{ route('admin.dosen-pa.update', $dosen->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="current_dosen_id" value="{{ $dosen->id }}">
                <input type="hidden" name="action" value="swap">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Mahasiswa to Remove -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-user-minus text-red-500 mr-2"></i>
                            Mahasiswa yang Akan Dilepas
                        </h4>
                        
                        @if($dosen->mahasiswaPa->isEmpty())
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-yellow-700">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Tidak ada mahasiswa.
                            </div>
                        @else
                            <select name="remove_mahasiswa_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition @error('remove_mahasiswa_id') border-red-500 @enderror" 
                                required>
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($dosen->mahasiswaPa as $mahasiswa)
                                    <option value="{{ $mahasiswa->id }}" {{ old('remove_mahasiswa_id') == $mahasiswa->id ? 'selected' : '' }}>
                                        {{ $mahasiswa->user->name }} ({{ $mahasiswa->nim }})
                                    </option>
                                @endforeach
                            </select>
                            @error('remove_mahasiswa_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Mahasiswa ini akan dilepas dari Dosen PA ini.
                            </p>
                        @endif
                    </div>

                    <!-- Mahasiswa to Add -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-user-plus text-green-500 mr-2"></i>
                            Mahasiswa Pengganti (Belum Ada Dosen PA)
                        </h4>
                        
                        @if($availableMahasiswas->isEmpty())
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-yellow-700">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Semua mahasiswa sudah memiliki Dosen PA.
                            </div>
                        @else
                            <select name="add_mahasiswa_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent transition @error('add_mahasiswa_id') border-red-500 @enderror" 
                                required>
                                <option value="">-- Pilih Mahasiswa --</option>
                                @foreach($availableMahasiswas as $mahasiswa)
                                    <option value="{{ $mahasiswa->id }}" {{ old('add_mahasiswa_id') == $mahasiswa->id ? 'selected' : '' }}>
                                        {{ $mahasiswa->user->name }} ({{ $mahasiswa->nim }})
                                    </option>
                                @endforeach
                            </select>
                            @error('add_mahasiswa_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Mahasiswa ini akan ditambahkan ke Dosen PA ini.
                            </p>
                        @endif
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
                        {{ $dosen->mahasiswaPa->isEmpty() || $availableMahasiswas->isEmpty() ? 'disabled' : '' }}>
                        <i class="fas fa-sync-alt mr-2"></i>
                        Ganti Mahasiswa
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Tab: Add Mahasiswa -->
        <div x-show="activeTab === 'add'" x-cloak>
            <form id="addForm" action="{{ route('admin.dosen-pa.update', $dosen->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="current_dosen_id" value="{{ $dosen->id }}">
                <input type="hidden" name="action" value="add">

                <div>
                    <div class="mb-4 flex items-center gap-3">
                        <div class="w-full flex">
                            <input id="search-add-input" type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIM" class="w-full px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-maroon" />
                            <button type="button" id="search-add-btn" class="px-4 py-2 bg-maroon text-white rounded-r-lg">Cari</button>
                        </div>
                    </div>

                    
                    <h4 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                        <i class="fas fa-user-plus text-green-600 mr-2"></i>
                        Pilih Mahasiswa yang Akan Ditambahkan
                    </h4>

                    @if($availableMahasiswas->isEmpty())
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-yellow-700">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Tidak ada mahasiswa yang tersedia (semua sudah memiliki Dosen PA).
                        </div>
                    @else
                        <div id="available-mahasiswa-container" class="bg-gray-50 rounded-lg border border-gray-200 max-h-64 overflow-y-auto">
                            @include('admin.dosen-pa._available_list')
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Pilih satu atau beberapa mahasiswa untuk ditambahkan ke dosen ini.
                        </p>
                        @error('mahasiswa_ids')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    @endif
                </div>

                <div class="flex justify-end space-x-3 mt-8 pt-6 border-t">
                    <a href="{{ route('admin.dosen-pa.index') }}" 
                        class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" 
                        @php
                             $btnQuota = $dosen->kuota ?: 6;
                             $btnDisplayQuota = $dosen->mahasiswaPa->count() > $btnQuota ? $dosen->mahasiswaPa->count() + 1 : $btnQuota;
                        @endphp
                        class="bg-maroon text-white px-6 py-3 rounded-lg hover:bg-opacity-90 transition flex items-center shadow-md transform hover:scale-105"
                        {{ $availableMahasiswas->isEmpty() || $dosen->mahasiswaPa->count() >= $btnDisplayQuota ? 'disabled' : '' }}>
                        <i class="fas fa-user-plus mr-2"></i>
                        Tambah Mahasiswa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const searchBtn = document.getElementById('search-add-btn');
    const searchInput = document.getElementById('search-add-input');
    const container = document.getElementById('available-mahasiswa-container');

    async function loadList(url) {
        try {
            const res = await fetch(url, {
                credentials: 'same-origin',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            if (!res.ok) throw new Error('Network response was not ok');
            const data = await res.json();
            if (container) container.innerHTML = data.html;
            bindPaginationLinks();
        } catch (err) {
            console.error(err);
        }
    }

    function bindPaginationLinks() {
        if (!container) return;
        container.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', function(e){
                const href = this.getAttribute('href');
                if (!href) return;
                e.preventDefault();
                const url = new URL(href, window.location.origin);
                url.searchParams.set('tab', 'add');
                loadList(url.toString());
            });
        });
    }

    if(searchBtn && searchInput){
        searchBtn.addEventListener('click', function(){
            const q = searchInput.value || '';
            const base = "{{ route('admin.dosen-pa.edit', $dosen->id) }}";
            const url = new URL(base, window.location.origin);
            url.searchParams.set('tab', 'add');
            if(q.trim() !== '') url.searchParams.set('search', q.trim());
            loadList(url.toString());
        });
        // Prevent Enter key from submitting the surrounding form — trigger AJAX search instead
        searchInput.addEventListener('keydown', function(e){
            if (e.key === 'Enter') {
                e.preventDefault();
                searchBtn.click();
            }
        });
        // Debounced live search as the user types (no Enter required)
        let searchTimeout = null;
        searchInput.addEventListener('input', function(){
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function(){
                searchBtn.click();
            }, 300);
        });
    }

    // bind links on initial render
    bindPaginationLinks();
});
</script>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCb = document.getElementById('selectAllMahasiswa');
        const checkboxes = document.querySelectorAll('input[name="mahasiswa_ids[]"]');

        if (selectAllCb) {
            // Handle "Select All" click
            selectAllCb.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
            });

            // Handle individual checkbox clicks to update "Select All" state
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const allChecked = Array.from(checkboxes).every(c => c.checked);
                    const someChecked = Array.from(checkboxes).some(c => c.checked);
                    
                    selectAllCb.checked = allChecked;
                    selectAllCb.indeterminate = someChecked && !allChecked;
                });
            });
        }

        // SweetAlert Confirmations
        function initConfirm(formId, title, text, confirmText) {
            const form = document.getElementById(formId);
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: title,
                        text: text,
                        icon: 'question',
                        iconColor: '#7a1621',
                        showCancelButton: true,
                        confirmButtonColor: '#7a1621',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: confirmText,
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
            }
        }

        initConfirm('transferForm', 'Pindahkan Mahasiswa?', 'Mahasiswa yang dipilih akan dipindahkan ke dosen bimbingan lain.', 'Ya, Pindahkan!');
        initConfirm('swapForm', 'Ganti Mahasiswa?', 'Mahasiswa akan dilepas dan digantikan dengan mahasiswa baru.', 'Ya, Ganti!');
        initConfirm('addForm', 'Tambah Mahasiswa?', 'Mahasiswa yang dipilih akan ditambahkan ke daftar bimbingan dosen ini.', 'Ya, Tambah!');

        // Success Notification Handler
        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#7a1621',
                confirmButtonText: 'OK',
                background: '#ffffff',
                customClass: {
                    confirmButton: 'px-6 py-2 rounded-lg font-bold',
                    popup: 'rounded-xl'
                }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: 'Kesalahan!',
                text: "{{ session('error') }}",
                icon: 'error',
                iconColor: '#7a1621',
                confirmButtonColor: '#7a1621',
                confirmButtonText: 'Tutup',
                background: '#ffffff',
                customClass: {
                    confirmButton: 'px-6 py-2 rounded-lg font-bold',
                    popup: 'rounded-xl'
                }
            });
        @endif
    });
</script>
@endpush