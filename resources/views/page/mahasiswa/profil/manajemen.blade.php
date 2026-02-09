@extends('layouts.mahasiswa')

@section('title', 'Profile Mahasiswa')
@section('page-title', 'Profile Mahasiswa')

@section('content')
@php
$isLocked = $mahasiswa->isProfileComplete();
$missingFields = session('missing_fields', []);
$highlightMissing = session('highlight_missing', false);

// Count missing fields per tab
$missingByTab = [
    'akademik' => 0,
    'data_pribadi' => 0,
    'orang_tua' => 0,
    'asal_sekolah' => 0, // Keep for backwards compatibility
];
foreach ($missingFields as $field => $info) {
    if (isset($info['tab']) && isset($missingByTab[$info['tab']])) {
        $missingByTab[$info['tab']]++;
    }
}
@endphp

{{-- Flash Warning Message (from redirect) --}}
@if(session('warning'))
<div class="bg-orange-50 border-l-4 border-orange-500 p-4 mb-6 rounded-md">
    <div class="flex items-start gap-3">
        <i class="fas fa-exclamation-circle text-orange-600 mt-0.5"></i>
        <div class="flex-1">
            <p class="text-sm text-orange-800 font-medium">{{ session('warning') }}</p>
            @if($highlightMissing && count($missingFields) > 0)
            <div class="mt-3 p-3 bg-white/50 rounded-lg border border-orange-200">
                <p class="text-xs font-bold text-orange-700 uppercase mb-2">Data yang belum lengkap:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($missingFields as $field => $info)
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full">
                        <i class="fas fa-times-circle"></i>
                        {{ $info['label'] }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endif

@if($isLocked)
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md">
    <div class="flex items-center">
        <i class="fas fa-check-circle text-green-600 mr-3"></i>
        <p class="text-sm text-green-800 font-medium">Data profil Anda sudah lengkap. Anda masih dapat memperbarui data profil jika diperlukan.</p>
    </div>
</div>
@else
{{-- Profile Completion Progress --}}
<div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-md">
    <div class="flex items-center">
        <i class="fas fa-info-circle text-blue-600 mr-3"></i>
        <p class="text-sm text-blue-800 font-medium">Lengkapi semua data profil (Akademik, Data Pribadi, Orang Tua, dan Asal Sekolah) untuk mengakses fitur lainnya.</p>
    </div>
</div>
@endif

{{-- Global villages data - load once to prevent memory exhaustion --}}
<script>
    window.villagesData = @json(collect($villages)->map(fn($v) => ['value' => $v['name'], 'text' => $v['name'], 'district_code' => $v['district_code']])->toArray());
</script>

<div class="bg-white rounded-lg shadow-sm p-8" x-data="{ 
    activeTab: '{{ $highlightMissing && count($missingFields) > 0 ? (collect($missingFields)->first()['tab'] ?? 'akademik') : 'akademik' }}', 
    photoPreview: '{{ $mahasiswa->foto ? asset("storage/" . $mahasiswa->foto) : "" }}' 
}">

    {{-- Tabs Header --}}
    <div class="mb-8 border-b border-gray-200">
        <div class="flex overflow-x-auto no-scrollbar -mb-px">
            @foreach([
                'akademik' => 'Akademik',
                'data_pribadi' => 'Data Lanjutan',
                'orang_tua' => 'Orang Tua / Wali'
            ] as $key => $label)
            <button @click="activeTab = '{{ $key }}'" type="button"
                class="whitespace-nowrap px-8 py-4 text-sm font-bold transition-all duration-200 border-b-2 relative"
                :class="activeTab === '{{ $key }}' 
                    ? 'border-[#8B1538] text-[#8B1538]' 
                    : 'border-transparent text-[#9CA3AF] hover:text-[#1A1A1A] hover:border-gray-300'">
                {{ $label }}
                @if($highlightMissing && $missingByTab[$key] > 0)
                <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full">
                    {{ $missingByTab[$key] }}
                </span>
                @endif
            </button>
            @endforeach
        </div>
    </div>

    <form action="{{ route('mahasiswa.profil.update') }}" method="POST" enctype="multipart/form-data" class="animate-fade-in">
        @csrf
        @method('PUT')

        {{-- Tab Content: Akademik --}}
        <div x-show="activeTab === 'akademik'" x-cloak class="space-y-12">
            <div>
                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                    <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Data Akademik</h3>
                    <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Academic Info</span>
                </div>
                <div class="space-y-8">


                    {{-- Grid Container for Data Akademik --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        
                        {{-- Left Column: Foto & Upload --}}
                        <div class="space-y-4">
                            {{-- Foto Profil --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Foto Profil</label>
                                <div class="w-32 h-40 border-2 border-gray-200 rounded-lg overflow-hidden bg-gray-50">
                                    <template x-if="photoPreview">
                                        <img :src="photoPreview" class="w-full h-full object-cover" alt="Preview">
                                    </template>
                                    <template x-if="!photoPreview">
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <i class="fas fa-user text-4xl"></i>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Upload Foto --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Upload Foto</label>
                                <div class="flex items-center gap-3">
                                    <label class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-700 cursor-pointer hover:bg-gray-50 transition shadow-sm font-medium">
                                        Choose File
                                        <input type="file" name="foto" class="hidden" accept=".jpg,.jpeg,.png,image/jpeg,image/png" @change="const file = $event.target.files[0]; if(file){ const reader = new FileReader(); reader.onload = (e) => photoPreview = e.target.result; reader.readAsDataURL(file); }">
                                    </label>
                                    <span class="text-xs text-gray-500 italic">Format: JPG/PNG, Max: 2MB</span>
                                </div>
                            </div>
                        </div>

                        {{-- Right Column: Identity --}}
                        <div class="space-y-4">
                            {{-- Nama Lengkap --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ $user->name }}"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="{{ $user->email }}"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            </div>

                            {{-- No HP --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">No. HP <span class="text-red-500">*</span></label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-[#8B1538] transition-colors">
                                        <span class="text-sm font-bold opacity-50">+62</span>
                                    </div>
                                    <input type="text" name="no_hp" value="{{ $mahasiswa->no_hp }}" maxlength="13" inputmode="numeric" pattern="^[0-9]{1,13}$" oninput="this.value = this.value.replace(/\D/g,'')"
                                        class="w-full pl-14 pr-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium" placeholder="81xxxxxxxxx">
                                </div>
                            </div>
                        </div>

                        {{-- Full Width or 2-Col for Readonly --}}
                        @foreach([
                            'NIM' => $mahasiswa->nim,
                            'Jurusan' => $mahasiswa->prodi,
                            'Program' => '1 - REGULER',
                            'Kurikulum' => '32 - Kurikulum ' . $mahasiswa->prodi . ' ' . $mahasiswa->angkatan,
                            'Angkatan' => $mahasiswa->angkatan,
                            'Penasehat Akademik' => 'Dosen PA',
                            'Status Awal' => 'B - Baru',
                            'Status Mahasiswa' => 'A - Aktif'
                        ] as $label => $value)
                        <div>
                            <label class="block text-sm text-gray-600 font-medium mb-2">{{ $label }}</label>
                            <div class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-md text-gray-500 text-sm flex justify-between items-center shadow-sm cursor-default">
                                <span>{{ $value ?? '-' }}</span>
                                <i class="fas fa-lock text-gray-300 text-xs"></i>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab Content: Data Lanjutan --}}
        <div x-show="activeTab === 'data_pribadi'" x-cloak class="space-y-12">
            {{-- Alamat Domisili --}}
            <div>
                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                    <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Alamat Domisili</h3>
                    <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Current Residence</span>
                </div>
                <div class="space-y-5">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    {{-- Alamat Lengkap (Full Width) --}}
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3"
                            class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium resize-none">{{ $mahasiswa->alamat }}</textarea>
                    </div>

                    {{-- RT / RW --}}
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">RT / RW</label>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="rt" value="{{ $mahasiswa->rt }}" placeholder="RT"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            <input type="text" name="rw" value="{{ $mahasiswa->rw }}" placeholder="RW"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                        </div>
                    </div>

                    {{-- Provinsi --}}
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">Provinsi</label>
                        <select name="provinsi" id="provinsiSelect" 
                            class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                            <option value="">Pilih Provinsi</option>
                            @foreach($provinces as $prov)
                            <option value="{{ $prov['name'] }}" data-code="{{ $prov['province_code'] }}" {{ ($mahasiswa->provinsi ?? '') === $prov['name'] ? 'selected' : '' }}>{{ $prov['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kota/Kabupaten --}}
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">Kota/Kabupaten</label>
                        <select name="kota" id="kotaSelect" 
                            class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                            <option value="">Pilih Kota/Kabupaten</option>
                        </select>
                    </div>

                    {{-- Kecamatan --}}
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">Kecamatan</label>
                        <select name="kecamatan" id="kecamatanSelect" 
                            class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>

                    {{-- Desa/Kelurahan --}}
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">Desa/Kelurahan</label>
                        <!-- Custom Searchable Dropdown -->
                        <div x-data="desaDropdown()" class="relative" x-init="init()">
                            <!-- Hidden input for form submission -->
                            <input type="hidden" name="desa" :value="selected" x-ref="desaInput">
                            
                            <!-- Dropdown Trigger -->
                            <button type="button" @click="updateDistrict(); open = !open; if(open) $nextTick(() => $refs.searchInput.focus())" 
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white text-left flex items-center justify-between">
                                <span x-text="selectedText" :class="selected ? 'text-gray-900' : 'text-gray-400'"></span>
                                <svg class="w-5 h-5 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Panel -->
                            <div x-show="open" @click.away="open = false; search = ''" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                class="absolute z-50 w-full mt-1 bg-white border border-[#E5E7EB] rounded-xl shadow-lg overflow-hidden" style="display: none;">
                                <!-- Search Input Inside Dropdown -->
                                <div class="p-2 border-b border-gray-100 sticky top-0 bg-white">
                                    <input type="text" x-model="search" x-ref="searchInput" @click.stop @keydown.escape="open = false"
                                        placeholder="Cari desa/kelurahan..." 
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-[#8B1538] focus:ring-2 focus:ring-[#8B1538]/10 outline-none">
                                </div>
                                <!-- Options List -->
                                <div class="max-h-52 overflow-y-auto">
                                    <!-- No City Selected Message -->
                                    <div x-show="showNoCityMessage" class="px-4 py-8 text-center">
                                        <div class="text-orange-400 mb-1">
                                            <i class="fas fa-exclamation-circle text-xl"></i>
                                        </div>
                                        <p class="text-xs text-orange-500 font-medium">Pilih Kecamatan terlebih dahulu</p>
                                    </div>
                                    <!-- Hint Message -->
                                    <div x-show="showHint" class="px-4 py-8 text-center">
                                        <div class="text-gray-400 mb-1">
                                            <i class="fas fa-search text-xl"></i>
                                        </div>
                                        <p class="text-xs text-gray-500 font-medium">Ketik minimal 2 karakter<br>untuk mencari desa/kelurahan</p>
                                    </div>

                                    <template x-for="(opt, index) in filteredOptions" :key="index">
                                        <div @click="selectOption(opt)" 
                                            class="px-4 py-2.5 text-sm cursor-pointer hover:bg-[#8B1538]/5 transition-colors border-b border-gray-50 last:border-0"
                                            :class="selected === opt.value ? 'bg-[#8B1538]/10 text-[#8B1538] font-semibold' : 'text-gray-700'">
                                            <span x-text="opt.text"></span>
                                        </div>
                                    </template>
                                    <div x-show="!showHint && filteredOptions.length === 0" class="px-4 py-3 text-sm text-gray-400 text-center">
                                        Tidak ditemukan
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <script>
                            function desaDropdown() {
                                return {
                                    open: false,
                                    search: '',
                                    selected: @json($mahasiswa->desa ?? ''),
                                    selectedText: @json($mahasiswa->desa ?? 'Pilih Desa'),
                                    allOptions: window.villagesData,
                                    currentDistrictCode: '',
                                    isLoading: false,
                                    noKecamatanSelected: true,
                                    init() {
                                        if (!this.selected) {
                                            this.selectedText = 'Pilih Desa';
                                        }
                                        // Listen to Kecamatan dropdown changes
                                        const kecamatanSelect = document.getElementById('kecamatanSelect');
                                        if (kecamatanSelect) {
                                            // Initial district code
                                            const initialOpt = kecamatanSelect.options[kecamatanSelect.selectedIndex];
                                            if (initialOpt && initialOpt.dataset.districtCode) {
                                                this.currentDistrictCode = initialOpt.dataset.districtCode;
                                                this.noKecamatanSelected = false;
                                            } else {
                                                this.noKecamatanSelected = true;
                                            }
                                            // On change
                                            kecamatanSelect.addEventListener('change', (e) => {
                                                const opt = e.target.options[e.target.selectedIndex];
                                                this.currentDistrictCode = opt?.dataset?.districtCode || '';
                                                this.noKecamatanSelected = !this.currentDistrictCode;
                                                // Reset selection when kecamatan changes
                                                this.selected = '';
                                                this.selectedText = 'Pilih Desa';
                                                this.search = '';
                                            });
                                        }
                                    },
                                    get options() {
                                        if (!this.currentDistrictCode) return [];
                                        return this.allOptions.filter(opt => opt.district_code === this.currentDistrictCode);
                                    },
                                    get filteredOptions() {
                                        // Only show options when user types at least 2 characters
                                        if (!this.search || this.search.trim().length < 2) return [];
                                        const term = this.search.toLowerCase().trim();
                                        return this.options.filter(opt => opt.text.toLowerCase().includes(term)).slice(0, 20);
                                    },
                                    get showHint() {
                                        if (this.noKecamatanSelected) return false;
                                        return !this.search || this.search.trim().length < 2;
                                    },
                                    get showNoCityMessage() {
                                        return this.noKecamatanSelected;
                                    },
                                    selectOption(opt) {
                                        this.selected = opt.value;
                                        this.selectedText = opt.text;
                                        this.open = false;
                                        this.search = '';
                                        this.$nextTick(() => {
                                            const input = this.$refs.desaInput;
                                            if(input) {
                                                input.dispatchEvent(new Event('input', {bubbles:true}));
                                                input.dispatchEvent(new Event('change', {bubbles:true}));
                                            }
                                        });
                                    },
                                    updateDistrict() {
                                        const kecamatanSelect = document.getElementById('kecamatanSelect');
                                        if (kecamatanSelect) {
                                            const opt = kecamatanSelect.options[kecamatanSelect.selectedIndex];
                                            this.currentDistrictCode = opt?.dataset?.districtCode || '';
                                            this.noKecamatanSelected = !this.currentDistrictCode;
                                        }
                                    }
                                }
                            }
                        </script>
                    </div>
                </div>
                </div>
            </div>

            {{-- Alamat Sesuai KTP --}}
            <div>
                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                    <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Alamat Sesuai KTP</h3>
                    <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Identity Card Address</span>
                </div>
                <div class="space-y-5">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    {{-- Alamat Lengkap (Full Width) --}}
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">Alamat</label>
                        <textarea name="alamat_ktp" rows="3"
                            class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium resize-none">{{ $mahasiswa->alamat_ktp }}</textarea>
                    </div>

                    {{-- RT / RW --}}
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">RT / RW</label>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="rt_ktp" value="{{ $mahasiswa->rt_ktp }}" placeholder="RT"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            <input type="text" name="rw_ktp" value="{{ $mahasiswa->rw_ktp }}" placeholder="RW"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                        </div>
                    </div>

                    {{-- Provinsi --}}
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">Provinsi</label>
                        <select name="provinsi_ktp" id="provinsiKtpSelect" 
                            class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                            <option value="">Pilih Provinsi</option>
                            @foreach($provinces as $prov)
                            <option value="{{ $prov['name'] }}" data-code="{{ $prov['province_code'] }}" {{ ($mahasiswa->provinsi_ktp ?? '') === $prov['name'] ? 'selected' : '' }}>{{ $prov['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kota/Kabupaten --}}
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">Kota/Kabupaten</label>
                        <select name="kota_ktp" id="kotaKtpSelect" 
                            class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                            <option value="">Pilih Kota/Kabupaten</option>
                        </select>
                    </div>

                    {{-- Kecamatan --}}
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">Kecamatan</label>
                        <select name="kecamatan_ktp" id="kecamatanKtpSelect" 
                            class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>

                    {{-- Desa/Kelurahan --}}
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">Desa/Kelurahan</label>
                        <!-- Custom Searchable Dropdown for KTP -->
                        <div x-data="desaKtpDropdown()" class="relative" x-init="init()">
                            <!-- Hidden input for form submission -->
                            <input type="hidden" name="desa_ktp" :value="selected" x-ref="desaKtpInput">
                            
                            <!-- Dropdown Trigger -->
                            <button type="button" @click="updateDistrict(); open = !open; if(open) $nextTick(() => $refs.searchInputKtp.focus())" 
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white text-left flex items-center justify-between">
                                <span x-text="selectedText" :class="selected ? 'text-gray-900' : 'text-gray-400'"></span>
                                <svg class="w-5 h-5 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Panel -->
                            <div x-show="open" @click.away="open = false; search = ''" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                class="absolute z-50 w-full mt-1 bg-white border border-[#E5E7EB] rounded-xl shadow-lg overflow-hidden" style="display: none;">
                                <!-- Search Input Inside Dropdown -->
                                <div class="p-2 border-b border-gray-100 sticky top-0 bg-white">
                                    <input type="text" x-model="search" x-ref="searchInputKtp" @click.stop @keydown.escape="open = false"
                                        placeholder="Cari desa/kelurahan..." 
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-[#8B1538] focus:ring-2 focus:ring-[#8B1538]/10 outline-none">
                                </div>
                                <!-- Options List -->
                                <div class="max-h-52 overflow-y-auto">
                                    <!-- No City Selected Message -->
                                    <div x-show="showNoCityMessage" class="px-4 py-8 text-center">
                                        <div class="text-orange-400 mb-1">
                                            <i class="fas fa-exclamation-circle text-xl"></i>
                                        </div>
                                        <p class="text-xs text-orange-500 font-medium">Pilih Kecamatan terlebih dahulu</p>
                                    </div>
                                    <!-- Hint Message -->
                                    <div x-show="showHint" class="px-4 py-8 text-center">
                                        <div class="text-gray-400 mb-1">
                                            <i class="fas fa-search text-xl"></i>
                                        </div>
                                        <p class="text-xs text-gray-500 font-medium">Ketik minimal 2 karakter<br>untuk mencari desa/kelurahan</p>
                                    </div>

                                    <template x-for="(opt, index) in filteredOptions" :key="index">
                                        <div @click="selectOption(opt)" 
                                            class="px-4 py-2.5 text-sm cursor-pointer hover:bg-[#8B1538]/5 transition-colors border-b border-gray-50 last:border-0"
                                            :class="selected === opt.value ? 'bg-[#8B1538]/10 text-[#8B1538] font-semibold' : 'text-gray-700'">
                                            <span x-text="opt.text"></span>
                                        </div>
                                    </template>
                                    <div x-show="!showHint && !showNoCityMessage && filteredOptions.length === 0" class="px-4 py-3 text-sm text-gray-400 text-center">
                                        Tidak ditemukan
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <script>
                            function desaKtpDropdown() {
                                return {
                                    open: false,
                                    search: '',
                                    selected: @json($mahasiswa->desa_ktp ?? ''),
                                    selectedText: @json($mahasiswa->desa_ktp ?? 'Pilih Desa'),
                                    allOptions: window.villagesData,
                                    currentDistrictCode: '',
                                    isLoading: false,
                                    noKecamatanSelected: true,
                                    init() {
                                        if (!this.selected) {
                                            this.selectedText = 'Pilih Desa';
                                        }
                                        // Listen to Kecamatan KTP dropdown changes
                                        const kecamatanSelect = document.getElementById('kecamatanKtpSelect');
                                        if (kecamatanSelect) {
                                            // Initial district code
                                            const initialOpt = kecamatanSelect.options[kecamatanSelect.selectedIndex];
                                            if (initialOpt && initialOpt.dataset.districtCode) {
                                                this.currentDistrictCode = initialOpt.dataset.districtCode;
                                                this.noKecamatanSelected = false;
                                            } else {
                                                this.noKecamatanSelected = true;
                                            }
                                            // On change
                                            kecamatanSelect.addEventListener('change', (e) => {
                                                const opt = e.target.options[e.target.selectedIndex];
                                                this.currentDistrictCode = opt?.dataset?.districtCode || '';
                                                this.noKecamatanSelected = !this.currentDistrictCode;
                                                // Reset selection when kecamatan changes
                                                this.selected = '';
                                                this.selectedText = 'Pilih Desa';
                                                this.search = '';
                                            });
                                        }
                                    },
                                    get options() {
                                        if (!this.currentDistrictCode) return [];
                                        return this.allOptions.filter(opt => opt.district_code === this.currentDistrictCode);
                                    },
                                    get filteredOptions() {
                                        // Only show options when user types at least 2 characters
                                        if (!this.search || this.search.trim().length < 2) return [];
                                        const term = this.search.toLowerCase().trim();
                                        return this.options.filter(opt => opt.text.toLowerCase().includes(term)).slice(0, 20);
                                    },
                                    get showHint() {
                                        if (this.noKecamatanSelected) return false;
                                        return !this.search || this.search.trim().length < 2;
                                    },
                                    get showNoCityMessage() {
                                        return this.noKecamatanSelected;
                                    },
                                    selectOption(opt) {
                                        this.selected = opt.value;
                                        this.selectedText = opt.text;
                                        this.open = false;
                                        this.search = '';
                                        this.$nextTick(() => {
                                            const input = this.$refs.desaKtpInput;
                                            if(input) {
                                                input.dispatchEvent(new Event('input', {bubbles:true}));
                                                input.dispatchEvent(new Event('change', {bubbles:true}));
                                            }
                                        });
                                    },
                                    updateDistrict() {
                                        const kecamatanSelect = document.getElementById('kecamatanKtpSelect');
                                        if (kecamatanSelect) {
                                            const opt = kecamatanSelect.options[kecamatanSelect.selectedIndex];
                                            this.currentDistrictCode = opt?.dataset?.districtCode || '';
                                            this.noKecamatanSelected = !this.currentDistrictCode;
                                        }
                                    }
                                }
                            }
                        </script>
                    </div>
                </div>
                </div>
            </div>

            {{-- Scripts for Address Dropdowns --}}
            <script>
                (function(){
                    const cities = @json($cities);
                    const districts = @json($districts);
                    
                    function setupAddressGroup(provId, kotaId, kecamatanId, initialKota, initialKecamatan) {
                        const provSelect = document.getElementById(provId);
                        const kotaSelect = document.getElementById(kotaId);
                        const kecamatanSelect = kecamatanId ? document.getElementById(kecamatanId) : null;

                        function populateCities(provinceCode) {
                            kotaSelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                            if (!provinceCode) {
                                kotaSelect.disabled = true;
                                if (kecamatanSelect) {
                                    kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                                    kecamatanSelect.disabled = true;
                                }
                                return;
                            }
                            const filtered = cities.filter(c => c.province_code === provinceCode);
                            filtered.forEach(city => {
                                const opt = document.createElement('option');
                                opt.value = city.name;
                                opt.textContent = city.name;
                                opt.dataset.cityCode = city.city_code;
                                if (city.name === initialKota) opt.selected = true;
                                kotaSelect.appendChild(opt);
                            });
                            kotaSelect.disabled = filtered.length === 0;
                        }

                        function populateKecamatan(cityCode) {
                            if (!kecamatanSelect) return;
                            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                            if (!cityCode) {
                                kecamatanSelect.disabled = true;
                                return;
                            }
                            const filtered = districts.filter(d => d.city_code === cityCode);
                            filtered.forEach(dist => {
                                const opt = document.createElement('option');
                                opt.value = dist.name;
                                opt.textContent = dist.name;
                                opt.dataset.districtCode = dist.district_code;
                                if (dist.name === initialKecamatan) opt.selected = true;
                                kecamatanSelect.appendChild(opt);
                            });
                            kecamatanSelect.disabled = filtered.length === 0;
                        }

                        provSelect.addEventListener('change', function() {
                            const code = this.options[this.selectedIndex].dataset.code || '';
                            populateCities(code);
                            // Reset Kecamatan
                            if (kecamatanSelect) {
                                kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                                kecamatanSelect.disabled = true;
                                kecamatanSelect.dispatchEvent(new Event('change'));
                            }
                        });

                        kotaSelect.addEventListener('change', function() {
                            const opt = this.options[this.selectedIndex];
                            const cityCode = opt?.dataset?.cityCode || '';
                            populateKecamatan(cityCode);
                            // Dispatch change event for Kecamatan to reset Desa
                            if (kecamatanSelect) {
                                kecamatanSelect.dispatchEvent(new Event('change'));
                            }
                        });

                        // Init
                        const opt = provSelect.options[provSelect.selectedIndex];
                        if (opt && opt.dataset.code) {
                            populateCities(opt.dataset.code);
                            // Set initial Kecamatan after Kota is populated
                            setTimeout(() => {
                                const kotaOpt = kotaSelect.options[kotaSelect.selectedIndex];
                                if (kotaOpt && kotaOpt.dataset.cityCode) {
                                    populateKecamatan(kotaOpt.dataset.cityCode);
                                }
                            }, 10);
                        } else {
                            kotaSelect.disabled = true;
                            if (kecamatanSelect) kecamatanSelect.disabled = true;
                        }
                    }

                    setupAddressGroup('provinsiSelect', 'kotaSelect', 'kecamatanSelect', @json($mahasiswa->kota ?? ''), @json($mahasiswa->kecamatan ?? ''));
                    setupAddressGroup('provinsiKtpSelect', 'kotaKtpSelect', 'kecamatanKtpSelect', @json($mahasiswa->kota_ktp ?? ''), @json($mahasiswa->kecamatan_ktp ?? ''));
                    
                    // Setup for Orang Tua and Wali sections (delayed to ensure elements exist)
                    document.addEventListener('DOMContentLoaded', function() {
                        // Small delay to ensure Alpine.js has rendered the elements
                        setTimeout(function() {
                            setupAddressGroup('provinsiAyahSelect', 'kotaAyahSelect', 'kecamatanAyahSelect', @json($parent->kota_ayah ?? $parent->kota_ortu ?? ''), @json($parent->kecamatan_ayah ?? ''));
                            setupAddressGroup('provinsiIbuSelect', 'kotaIbuSelect', 'kecamatanIbuSelect', @json($parent->kota_ibu ?? ''), @json($parent->kecamatan_ibu ?? ''));
                            setupAddressGroup('provinsiWaliSelect', 'kotaWaliSelect', 'kecamatanWaliSelect', @json($parent->kota_wali ?? ''), @json($parent->kecamatan_wali ?? ''));
                        }, 100);
                    });
                })();
            </script>

            {{-- Data Pribadi --}}
            <div>
                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                    <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Data Pribadi</h3>
                    <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Personal Details</span>
                </div>
                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        {{-- Tempat Lahir --}}
                        <div>
                            <label class="block text-sm text-gray-600 font-medium mb-2">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ $mahasiswa->tempat_lahir }}"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                        </div>

                        {{-- Tanggal Lahir --}}
                        <div>
                            <label class="block text-sm text-gray-600 font-medium mb-2">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ $mahasiswa->tanggal_lahir ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('Y-m-d') : '' }}"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label class="block text-sm text-gray-600 font-medium mb-2">Jenis Kelamin</label>
                            <select name="jenis_kelamin"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-Laki" {{ $mahasiswa->jenis_kelamin === 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="Perempuan" {{ $mahasiswa->jenis_kelamin === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        {{-- Agama --}}
                        <div>
                            <label class="block text-sm text-gray-600 font-medium mb-2">Agama</label>
                            <select name="agama"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                <option value="">Pilih Agama</option>
                                @if(isset($religions) && $religions->count())
                                @foreach($religions as $rel)
                                <option value="{{ $rel->name }}" {{ $mahasiswa->agama === $rel->name ? 'selected' : '' }}>{{ $rel->name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                        {{-- Status Sipil --}}
                        <div>
                            <label class="block text-sm text-gray-600 font-medium mb-2">Status Sipil</label>
                            <select name="status_sipil"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                <option value="">Pilih Status Sipil</option>
                                <option value="Belum Menikah" {{ $mahasiswa->status_sipil === 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                <option value="Menikah" {{ $mahasiswa->status_sipil === 'Menikah' ? 'selected' : '' }}>Menikah</option>
                                <option value="Cerai" {{ $mahasiswa->status_sipil === 'Cerai' ? 'selected' : '' }}>Cerai</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dokumen Pribadi --}}
            <div>
                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                    <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Dokumen Pribadi</h3>
                    <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Identity Documents</span>
                </div>
                <div class="p-4 bg-orange-50 border border-orange-100 rounded-xl mb-6">
                    <p class="text-[11px] text-[#C2410C] font-semibold flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        Upload dokumen dalam format PDF atau JPEG/PNG (Maksimal 5MB per file).
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @foreach([
                        ['name' => 'file_ijazah', 'label' => 'Ijazah', 'data' => $mahasiswa->file_ijazah],
                        ['name' => 'file_transkrip', 'label' => 'Transkrip Nilai', 'data' => $mahasiswa->file_transkrip],
                        ['name' => 'file_kk', 'label' => 'Kartu Keluarga (KK)', 'data' => $mahasiswa->file_kk],
                        ['name' => 'file_ktp', 'label' => 'KTP', 'data' => $mahasiswa->file_ktp],
                    ] as $doc)
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">{{ $doc['label'] }}</label>
                            @if($doc['data'] && count($doc['data']) > 0)
                                <span class="text-[10px] font-bold text-green-600 uppercase flex items-center gap-1">
                                    <i class="fas fa-check-circle"></i> Terupload
                                </span>
                            @else
                                <span class="text-[10px] font-bold text-red-500 uppercase flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i> Belum Upload
                                </span>
                            @endif
                        </div>
                        <div class="relative group">
                            <input type="file" name="{{ $doc['name'] }}[]" multiple
                                accept=".pdf,.jpeg,.jpg,.png,application/pdf,image/jpeg,image/png"
                                onchange="validateDocumentFiles(this)"
                                class="w-full px-4 py-3 bg-[#F9FAFB] border border-[#E5E7EB] border-dashed rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-[#8B1538] file:text-white hover:file:bg-[#6D1029]">
                        </div>
                        @if($doc['data'] && count($doc['data']) > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($doc['data'] as $file)
                                <a href="{{ asset('storage/' . $file) }}" target="_blank" 
                                    class="inline-flex items-center gap-2 px-3 py-1.5 bg-[#F3F4F6] hover:bg-[#E5E7EB] text-[#4B5563] text-[11px] font-bold rounded-lg transition-colors border border-[#E5E7EB]">
                                    <i class="fas fa-file-pdf text-[#8B1538]"></i>
                                    {{ Str::limit(basename($file), 20) }}
                                </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Data Asal Sekolah (inside Data Lanjutan tab) --}}
            <div>
                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                    <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Data Asal Sekolah</h3>
                    <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Previous Education</span>
                </div>
                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        {{-- Jenis Sekolah --}}
                        <div>
                            <label class="block text-sm text-gray-600 font-medium mb-2">Jenis Sekolah</label>
                            <select name="jenis_sekolah"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                <option value="">Pilih Jenis Sekolah</option>
                                <option value="1 - Umum" {{ $mahasiswa->jenis_sekolah === '1 - Umum' ? 'selected' : '' }}>1 - Umum</option>
                                <option value="2 - Kejuruan" {{ $mahasiswa->jenis_sekolah === '2 - Kejuruan' ? 'selected' : '' }}>2 - Kejuruan</option>
                                <option value="3 - Umum" {{ $mahasiswa->jenis_sekolah === '3 - Umum' ? 'selected' : '' }}>3 - Umum</option>
                            </select>
                        </div>

                        {{-- Jurusan --}}
                        <div>
                            <label class="block text-sm text-gray-600 font-medium mb-2">Jurusan</label>
                            <select name="jurusan_sekolah"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                <option value="">Pilih Jurusan</option>
                                @foreach(['SMU - IPA', 'SMU - IPS', 'SMU - Bahasa', 'SMK - Teknik Informatika', 'SMK - Teknik Mesin', 'SMK - Akuntansi', 'SMK - Lainnya'] as $j)
                                <option value="{{ $j }}" {{ $mahasiswa->jurusan_sekolah === $j ? 'selected' : '' }}>{{ $j }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tahun Lulus --}}
                        <div>
                            <label class="block text-sm text-gray-600 font-medium mb-2">Tahun Lulus</label>
                            <input type="text" name="tahun_lulus" value="{{ $mahasiswa->tahun_lulus ?? '' }}" placeholder="Contoh: 2020"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                        </div>

                        {{-- Nilai Kelulusan --}}
                        <div>
                            <label class="block text-sm text-gray-600 font-medium mb-2">Nilai Kelulusan</label>
                            <input type="number" name="nilai_kelulusan" value="{{ $mahasiswa->nilai_kelulusan ?? '' }}" step="0.01" min="0" max="100" placeholder="Contoh: 81.56"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Tab Content: Orang Tua / Wali --}}
        <div x-show="activeTab === 'orang_tua'" x-cloak class="space-y-12"
            x-data="{ showOrangTua: true, showWali: {{ ($parent->nama_wali ?? '') ? 'true' : 'false' }} }">

            {{-- Toggle Orang Tua / Wali --}}
            <div class="bg-[#F9FAFB] rounded-2xl p-6 border border-[#E5E7EB] shadow-sm">
                <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider mb-4 block">Pilih Data yang Ingin Diisi:</label>
                <div class="flex flex-wrap gap-8">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="show_orang_tua" x-model="showOrangTua"
                                class="peer sr-only">
                            <div class="w-10 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#8B1538] transition-colors"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-4 shadow-sm"></div>
                        </div>
                        <span class="text-sm font-bold text-[#1A1A1A] group-hover:text-[#8B1538] transition-colors">Orang Tua</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="show_wali" x-model="showWali"
                                class="peer sr-only">
                            <div class="w-10 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#8B1538] transition-colors"></div>
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-4 shadow-sm"></div>
                        </div>
                        <span class="text-sm font-bold text-[#1A1A1A] group-hover:text-[#8B1538] transition-colors">Wali</span>
                    </label>
                </div>
            </div>

            {{-- Orang Tua Section --}}
            <div x-show="showOrangTua" x-cloak class="space-y-12">
                {{-- Data Ayah --}}
                <div>
                    <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                        <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Data Ayah</h3>
                        <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Father's Info</span>
                    </div>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            {{-- Nama Ayah --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Nama Ayah</label>
                                <input type="text" name="nama_ayah" value="{{ $parent->nama_ayah ?? '' }}"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            </div>

                            {{-- Pendidikan Ayah --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Pendidikan Ayah</label>
                                <select name="pendidikan_ayah"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Pendidikan</option>
                                    @foreach(['Tidak Sekolah', 'Tamat SD', 'Tamat SMTP', 'Tamat SMTA', 'Diploma', 'Sarjana', 'Magister', 'Doktor'] as $p)
                                    <option value="{{ $p }}" {{ ($parent->pendidikan_ayah ?? '') === $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Pekerjaan Ayah --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Pekerjaan Ayah</label>
                                <select name="pekerjaan_ayah"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Pekerjaan</option>
                                    @if(isset($pekerjaans) && $pekerjaans->count())
                                    @foreach($pekerjaans as $p)
                                    <option value="{{ $p->name }}" {{ ($parent->pekerjaan_ayah ?? '') === $p->name ? 'selected' : '' }}>{{ $p->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>

                            {{-- Agama Ayah --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Agama Ayah</label>
                                <select name="agama_ayah"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Agama</option>
                                    @if(isset($religions) && $religions->count())
                                    @foreach($religions as $rel)
                                    <option value="{{ $rel->name }}" {{ ($parent->agama_ayah ?? '') === $rel->name ? 'selected' : ''}}>{{ $rel->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Data Ibu --}}
                <div>
                    <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                        <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Data Ibu</h3>
                        <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Mother's Info</span>
                    </div>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            {{-- Nama Ibu --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Nama Ibu</label>
                                <input type="text" name="nama_ibu" value="{{ $parent->nama_ibu ?? '' }}"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            </div>

                            {{-- Pendidikan Ibu --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Pendidikan Ibu</label>
                                <select name="pendidikan_ibu"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Pendidikan</option>
                                    @foreach(['Tidak Sekolah', 'Tamat SD', 'Tamat SMTP', 'Tamat SMTA', 'Diploma', 'Sarjana', 'Magister', 'Doktor'] as $p)
                                    <option value="{{ $p }}" {{ ($parent->pendidikan_ibu ?? '') === $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Pekerjaan Ibu --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Pekerjaan Ibu</label>
                                <select name="pekerjaan_ibu"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Pekerjaan</option>
                                    @if(isset($pekerjaans) && $pekerjaans->count())
                                    @foreach($pekerjaans as $p)
                                    <option value="{{ $p->name }}" {{ ($parent->pekerjaan_ibu ?? '') === $p->name ? 'selected' : '' }}>{{ $p->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>

                            {{-- Agama Ibu --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Agama Ibu</label>
                                <select name="agama_ibu"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Agama</option>
                                    @if(isset($religions) && $religions->count())
                                    @foreach($religions as $rel)
                                    <option value="{{ $rel->name }}" {{ ($parent->agama_ibu ?? '') === $rel->name ? 'selected' : '' }}>{{ $rel->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Data Alamat Ayah --}}
                <div>
                    <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                        <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Alamat Ayah</h3>
                        <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Father's Residence</span>
                    </div>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            {{-- Alamat Lengkap --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm text-gray-600 font-medium mb-2">Alamat Lengkap</label>
                                <textarea name="alamat_ayah" rows="3"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium resize-none">{{ $parent->alamat_ayah ?? $parent->alamat_ortu ?? '' }}</textarea>
                            </div>

                            {{-- Provinsi --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Provinsi</label>
                                <select name="propinsi_ayah" id="provinsiAyahSelect"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Provinsi</option>
                                    @foreach($provinces as $prov)
                                    <option value="{{ $prov['name'] }}" data-code="{{ $prov['province_code'] }}" {{ ($parent->propinsi_ayah ?? $parent->propinsi_ortu ?? '') === $prov['name'] ? 'selected' : '' }}>{{ $prov['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Kota/Kabupaten --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Kota/Kabupaten</label>
                                <select name="kota_ayah" id="kotaAyahSelect"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                            </div>

                            {{-- Kecamatan --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Kecamatan</label>
                                <select name="kecamatan_ayah" id="kecamatanAyahSelect"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>

                            {{-- Desa/Kelurahan --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Desa/Kelurahan</label>
                                <div x-data="desaAyahDropdown()" class="relative">
                                    <input type="hidden" name="desa_ayah" :value="selected" x-ref="desaAyahInput">
                                    <button type="button" @click="updateDistrict(); open = !open; $nextTick(() => $refs.searchInputAyah.focus())"
                                        class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white text-left flex items-center justify-between">
                                        <span x-text="selectedText" :class="selected ? 'text-gray-900' : 'text-gray-400'"></span>
                                        <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                    </button>
                                    <div x-show="open" @click.away="open = false; search = ''" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                        class="absolute z-50 w-full mt-1 bg-white border border-[#E5E7EB] rounded-xl shadow-lg overflow-hidden" style="display: none;">
                                        <div class="p-2 border-b border-gray-100 sticky top-0 bg-white">
                                            <input type="text" x-model="search" x-ref="searchInputAyah" @click.stop @keydown.escape="open = false"
                                                placeholder="Cari desa/kelurahan..." 
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-[#8B1538] focus:ring-2 focus:ring-[#8B1538]/10 outline-none">
                                        </div>
                                        <div class="max-h-52 overflow-y-auto">
                                            <div x-show="showNoCityMessage" class="px-4 py-8 text-center">
                                                <div class="text-orange-400 mb-1"><i class="fas fa-exclamation-circle text-xl"></i></div>
                                                <p class="text-xs text-orange-500 font-medium">Pilih Kecamatan terlebih dahulu</p>
                                            </div>
                                            <div x-show="showHint" class="px-4 py-8 text-center">
                                                <div class="text-gray-400 mb-1"><i class="fas fa-search text-xl"></i></div>
                                                <p class="text-xs text-gray-500 font-medium">Ketik minimal 2 karakter<br>untuk mencari desa/kelurahan</p>
                                            </div>
                                            <template x-for="(opt, index) in filteredOptions" :key="index">
                                                <div @click="selectOption(opt)" 
                                                    class="px-4 py-2.5 text-sm cursor-pointer hover:bg-[#8B1538]/5 transition-colors border-b border-gray-50 last:border-0"
                                                    :class="selected === opt.value ? 'bg-[#8B1538]/10 text-[#8B1538] font-semibold' : 'text-gray-700'">
                                                    <span x-text="opt.text"></span>
                                                </div>
                                            </template>
                                            <div x-show="!showHint && !showNoCityMessage && filteredOptions.length === 0" class="px-4 py-3 text-sm text-gray-400 text-center">Tidak ditemukan</div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    function desaAyahDropdown() {
                                        return {
                                            open: false,
                                            search: '',
                                            selected: @json($parent->desa_ayah ?? $parent->desa_ortu ?? ''),
                                            selectedText: @json($parent->desa_ayah ?? $parent->desa_ortu ?? 'Pilih Desa'),
                                            allOptions: window.villagesData,
                                            currentDistrictCode: '',
                                            noKecamatanSelected: true,
                                            init() {
                                                if (!this.selected) this.selectedText = 'Pilih Desa';
                                                const kecamatanSelect = document.getElementById('kecamatanAyahSelect');
                                                if (kecamatanSelect) {
                                                    const initialOpt = kecamatanSelect.options[kecamatanSelect.selectedIndex];
                                                    if (initialOpt && initialOpt.dataset.districtCode) {
                                                        this.currentDistrictCode = initialOpt.dataset.districtCode;
                                                        this.noKecamatanSelected = false;
                                                    } else {
                                                        this.noKecamatanSelected = true;
                                                    }
                                                    kecamatanSelect.addEventListener('change', (e) => {
                                                        const opt = e.target.options[e.target.selectedIndex];
                                                        this.currentDistrictCode = opt?.dataset?.districtCode || '';
                                                        this.noKecamatanSelected = !this.currentDistrictCode;
                                                        this.selected = '';
                                                        this.selectedText = 'Pilih Desa';
                                                        this.search = '';
                                                    });
                                                }
                                            },
                                            get options() {
                                                if (!this.currentDistrictCode) return [];
                                                return this.allOptions.filter(opt => opt.district_code === this.currentDistrictCode);
                                            },
                                            get filteredOptions() {
                                                if (!this.search || this.search.trim().length < 2) return [];
                                                const term = this.search.toLowerCase().trim();
                                                return this.options.filter(opt => opt.text.toLowerCase().includes(term)).slice(0, 20);
                                            },
                                            get showHint() {
                                                if (this.noKecamatanSelected) return false;
                                                return !this.search || this.search.trim().length < 2;
                                            },
                                            get showNoCityMessage() {
                                                return this.noKecamatanSelected;
                                            },
                                            selectOption(opt) {
                                                this.selected = opt.value;
                                                this.selectedText = opt.text;
                                                this.open = false;
                                                this.search = '';
                                                this.$nextTick(() => {
                                                    const input = this.$refs.desaAyahInput;
                                                    if(input) {
                                                        input.dispatchEvent(new Event('input', {bubbles:true}));
                                                        input.dispatchEvent(new Event('change', {bubbles:true}));
                                                    }
                                                });
                                            },
                                            updateDistrict() {
                                                const kecamatanSelect = document.getElementById('kecamatanAyahSelect');
                                                if (kecamatanSelect) {
                                                    const opt = kecamatanSelect.options[kecamatanSelect.selectedIndex];
                                                    this.currentDistrictCode = opt?.dataset?.districtCode || '';
                                                    this.noKecamatanSelected = !this.currentDistrictCode;
                                                }
                                            }
                                        }
                                    }
                                </script>
                            </div>
                            
                            {{-- Handphone Ayah (Using Grid Flow to fit in remaining space) --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Handphone Ayah</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-[#8B1538] transition-colors">
                                        <span class="text-sm font-bold opacity-50">+62</span>
                                    </div>
                                    <input type="text" name="handphone_ayah" value="{{ $parent->handphone_ayah ?? $parent->handphone_ortu ?? '' }}" maxlength="13" inputmode="numeric" pattern="^[0-9]{1,13}$" oninput="this.value = this.value.replace(/\D/g,'')"
                                        class="w-full pl-14 pr-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium" placeholder="81xxxxxxxxx">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Data Alamat Ibu --}}
                <div class="border-t border-gray-100 pt-8 mt-8">
                    <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                        <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Alamat Ibu</h3>
                        <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Mother's Residence</span>
                    </div>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            {{-- Alamat Lengkap --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm text-gray-600 font-medium mb-2">Alamat Lengkap</label>
                                <textarea name="alamat_ibu" rows="3"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium resize-none">{{ $parent->alamat_ibu ?? '' }}</textarea>
                            </div>

                            {{-- Provinsi --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Provinsi</label>
                                <select name="propinsi_ibu" id="provinsiIbuSelect"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Provinsi</option>
                                    @foreach($provinces as $prov)
                                    <option value="{{ $prov['name'] }}" data-code="{{ $prov['province_code'] }}" {{ ($parent->propinsi_ibu ?? '') === $prov['name'] ? 'selected' : '' }}>{{ $prov['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Kota/Kabupaten --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Kota/Kabupaten</label>
                                <select name="kota_ibu" id="kotaIbuSelect"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                            </div>

                            {{-- Kecamatan --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Kecamatan</label>
                                <select name="kecamatan_ibu" id="kecamatanIbuSelect"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>

                            {{-- Desa/Kelurahan --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Desa/Kelurahan</label>
                                <div x-data="desaIbuDropdown()" class="relative">
                                    <input type="hidden" name="desa_ibu" :value="selected" x-ref="desaIbuInput">
                                    <button type="button" @click="updateDistrict(); open = !open; $nextTick(() => $refs.searchInputIbu.focus())"
                                        class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white text-left flex items-center justify-between">
                                        <span x-text="selectedText" :class="selected ? 'text-gray-900' : 'text-gray-400'"></span>
                                        <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                    </button>
                                    <div x-show="open" @click.away="open = false; search = ''" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                        class="absolute z-50 w-full mt-1 bg-white border border-[#E5E7EB] rounded-xl shadow-lg overflow-hidden" style="display: none;">
                                        <div class="p-2 border-b border-gray-100 sticky top-0 bg-white">
                                            <input type="text" x-model="search" x-ref="searchInputIbu" @click.stop @keydown.escape="open = false"
                                                placeholder="Cari desa/kelurahan..." 
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-[#8B1538] focus:ring-2 focus:ring-[#8B1538]/10 outline-none">
                                        </div>
                                        <div class="max-h-52 overflow-y-auto">
                                            <div x-show="showNoCityMessage" class="px-4 py-8 text-center">
                                                <div class="text-orange-400 mb-1"><i class="fas fa-exclamation-circle text-xl"></i></div>
                                                <p class="text-xs text-orange-500 font-medium">Pilih Kecamatan terlebih dahulu</p>
                                            </div>
                                            <div x-show="showHint" class="px-4 py-8 text-center">
                                                <div class="text-gray-400 mb-1"><i class="fas fa-search text-xl"></i></div>
                                                <p class="text-xs text-gray-500 font-medium">Ketik minimal 2 karakter<br>untuk mencari desa/kelurahan</p>
                                            </div>
                                            <template x-for="(opt, index) in filteredOptions" :key="index">
                                                <div @click="selectOption(opt)" 
                                                    class="px-4 py-2.5 text-sm cursor-pointer hover:bg-[#8B1538]/5 transition-colors border-b border-gray-50 last:border-0"
                                                    :class="selected === opt.value ? 'bg-[#8B1538]/10 text-[#8B1538] font-semibold' : 'text-gray-700'">
                                                    <span x-text="opt.text"></span>
                                                </div>
                                            </template>
                                            <div x-show="!showHint && !showNoCityMessage && filteredOptions.length === 0" class="px-4 py-3 text-sm text-gray-400 text-center">Tidak ditemukan</div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    function desaIbuDropdown() {
                                        return {
                                            open: false,
                                            search: '',
                                            selected: @json($parent->desa_ibu ?? ''),
                                            selectedText: @json($parent->desa_ibu ?? 'Pilih Desa'),
                                            allOptions: window.villagesData,
                                            currentDistrictCode: '',
                                            noKecamatanSelected: true,
                                            init() {
                                                if (!this.selected) this.selectedText = 'Pilih Desa';
                                                const kecamatanSelect = document.getElementById('kecamatanIbuSelect');
                                                if (kecamatanSelect) {
                                                    const initialOpt = kecamatanSelect.options[kecamatanSelect.selectedIndex];
                                                    if (initialOpt && initialOpt.dataset.districtCode) {
                                                        this.currentDistrictCode = initialOpt.dataset.districtCode;
                                                        this.noKecamatanSelected = false;
                                                    } else {
                                                        this.noKecamatanSelected = true;
                                                    }
                                                    kecamatanSelect.addEventListener('change', (e) => {
                                                        const opt = e.target.options[e.target.selectedIndex];
                                                        this.currentDistrictCode = opt?.dataset?.districtCode || '';
                                                        this.noKecamatanSelected = !this.currentDistrictCode;
                                                        this.selected = '';
                                                        this.selectedText = 'Pilih Desa';
                                                        this.search = '';
                                                    });
                                                }
                                            },
                                            get options() {
                                                if (!this.currentDistrictCode) return [];
                                                return this.allOptions.filter(opt => opt.district_code === this.currentDistrictCode);
                                            },
                                            get filteredOptions() {
                                                if (!this.search || this.search.trim().length < 2) return [];
                                                const term = this.search.toLowerCase().trim();
                                                return this.options.filter(opt => opt.text.toLowerCase().includes(term)).slice(0, 20);
                                            },
                                            get showHint() {
                                                if (this.noKecamatanSelected) return false;
                                                return !this.search || this.search.trim().length < 2;
                                            },
                                            get showNoCityMessage() {
                                                return this.noKecamatanSelected;
                                            },
                                            selectOption(opt) {
                                                this.selected = opt.value;
                                                this.selectedText = opt.text;
                                                this.open = false;
                                                this.search = '';
                                                this.$nextTick(() => {
                                                    const input = this.$refs.desaIbuInput;
                                                    if(input) {
                                                        input.dispatchEvent(new Event('input', {bubbles:true}));
                                                        input.dispatchEvent(new Event('change', {bubbles:true}));
                                                    }
                                                });
                                            },
                                            updateDistrict() {
                                                const kecamatanSelect = document.getElementById('kecamatanIbuSelect');
                                                if (kecamatanSelect) {
                                                    const opt = kecamatanSelect.options[kecamatanSelect.selectedIndex];
                                                    this.currentDistrictCode = opt?.dataset?.districtCode || '';
                                                    this.noKecamatanSelected = !this.currentDistrictCode;
                                                }
                                            }
                                        }
                                    }
                                </script>
                            </div>
                            
                            {{-- Handphone Ibu --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Handphone Ibu</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-[#8B1538] transition-colors">
                                        <span class="text-sm font-bold opacity-50">+62</span>
                                    </div>
                                    <input type="text" name="handphone_ibu" value="{{ $parent->handphone_ibu ?? '' }}" maxlength="13" inputmode="numeric" pattern="^[0-9]{1,13}$" oninput="this.value = this.value.replace(/\D/g,'')"
                                        class="w-full pl-14 pr-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium" placeholder="81xxxxxxxxx">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> {{-- End Orang Tua Section --}}

            {{-- Wali Section --}}
            <div x-show="showWali" x-cloak class="space-y-12">
                {{-- Data Wali --}}
                <div>
                    <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                        <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Data Wali</h3>
                        <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Guardian's Info</span>
                    </div>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            {{-- Nama Wali --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Nama Wali</label>
                                <input type="text" name="nama_wali" value="{{ $parent->nama_wali ?? '' }}"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            </div>

                            {{-- Hubungan --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Hubungan</label>
                                <select name="hubungan_wali"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Hubungan</option>
                                    @foreach(['Kakek', 'Nenek', 'Paman', 'Bibi', 'Saudara Lainnya', 'Lainnya'] as $h)
                                    <option value="{{ $h }}" {{ ($parent->hubungan_wali ?? '') === $h ? 'selected' : '' }}>{{ $h }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Pendidikan --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Pendidikan</label>
                                <select name="pendidikan_wali"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Pendidikan</option>
                                    @foreach(['Tidak Sekolah', 'Tamat SD', 'Tamat SMTP', 'Tamat SMTA', 'Diploma', 'Sarjana', 'Magister', 'Doktor'] as $p)
                                    <option value="{{ $p }}" {{ ($parent->pendidikan_wali ?? '') === $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Pekerjaan --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Pekerjaan</label>
                                <select name="pekerjaan_wali"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Pekerjaan</option>
                                    @if(isset($pekerjaans) && $pekerjaans->count())
                                    @foreach($pekerjaans as $p)
                                    <option value="{{ $p->name }}" {{ ($parent->pekerjaan_wali ?? '') === $p->name ? 'selected' : '' }}>{{ $p->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>

                            {{-- Agama --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Agama</label>
                                <select name="agama_wali"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Agama</option>
                                    @if(isset($religions) && $religions->count())
                                    @foreach($religions as $rel)
                                    <option value="{{ $rel->name }}" {{ ($parent->agama_wali ?? '') === $rel->name ? 'selected' : ''}}>{{ $rel->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alamat Wali --}}
                <div>
                    <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                        <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Alamat Wali</h3>
                        <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Guardian's Residence</span>
                    </div>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            {{-- Alamat Lengkap --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm text-gray-600 font-medium mb-2">Alamat Lengkap</label>
                                <textarea name="alamat_wali" rows="3"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium resize-none">{{ $parent->alamat_wali ?? '' }}</textarea>
                            </div>

                            {{-- Provinsi --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Provinsi</label>
                                <select name="provinsi_wali" id="provinsiWaliSelect"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Provinsi</option>
                                    @foreach($provinces as $prov)
                                    <option value="{{ $prov['name'] }}" data-code="{{ $prov['province_code'] }}" {{ ($parent->provinsi_wali ?? '') === $prov['name'] ? 'selected' : '' }}>{{ $prov['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Kota/Kabupaten --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Kota/Kabupaten</label>
                                <select name="kota_wali" id="kotaWaliSelect"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                            </div>

                            {{-- Kecamatan --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Kecamatan</label>
                                <select name="kecamatan_wali" id="kecamatanWaliSelect"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>

                            {{-- Desa/Kelurahan --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Desa/Kelurahan</label>
                                <div x-data="desaWaliDropdown()" class="relative">
                                    <input type="hidden" name="desa_wali" :value="selected" x-ref="desaWaliInput">
                                    <button type="button" @click="updateDistrict(); open = !open; $nextTick(() => $refs.searchInputWali.focus())"
                                        class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white text-left flex items-center justify-between">
                                        <span x-text="selectedText" :class="selected ? 'text-gray-900' : 'text-gray-400'"></span>
                                        <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                    </button>
                                    <!-- Dropdown Panel -->
                                    <div x-show="open" @click.away="open = false; search = ''" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                        class="absolute z-50 w-full mt-1 bg-white border border-[#E5E7EB] rounded-xl shadow-lg overflow-hidden" style="display: none;">
                                        <!-- Search Input Inside Dropdown -->
                                        <div class="p-2 border-b border-gray-100 sticky top-0 bg-white">
                                            <input type="text" x-model="search" x-ref="searchInputWali" @click.stop @keydown.escape="open = false"
                                                placeholder="Cari desa/kelurahan..." 
                                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:border-[#8B1538] focus:ring-2 focus:ring-[#8B1538]/10 outline-none">
                                        </div>
                                        <!-- Options List -->
                                        <div class="max-h-52 overflow-y-auto">
                                            <!-- No City Selected Message -->
                                            <div x-show="showNoCityMessage" class="px-4 py-8 text-center">
                                                <div class="text-orange-400 mb-1">
                                                    <i class="fas fa-exclamation-circle text-xl"></i>
                                                </div>
                                                <p class="text-xs text-orange-500 font-medium">Pilih Kecamatan terlebih dahulu</p>
                                            </div>
                                            <!-- Hint Message -->
                                            <div x-show="showHint" class="px-4 py-8 text-center">
                                                <div class="text-gray-400 mb-1">
                                                    <i class="fas fa-search text-xl"></i>
                                                </div>
                                                <p class="text-xs text-gray-500 font-medium">Ketik minimal 2 karakter<br>untuk mencari desa/kelurahan</p>
                                            </div>

                                            <template x-for="(opt, index) in filteredOptions" :key="index">
                                                <div @click="selectOption(opt)" 
                                                    class="px-4 py-2.5 text-sm cursor-pointer hover:bg-[#8B1538]/5 transition-colors border-b border-gray-50 last:border-0"
                                                    :class="selected === opt.value ? 'bg-[#8B1538]/10 text-[#8B1538] font-semibold' : 'text-gray-700'">
                                                    <span x-text="opt.text"></span>
                                                </div>
                                            </template>
                                            <div x-show="!showHint && !showNoCityMessage && filteredOptions.length === 0" class="px-4 py-3 text-sm text-gray-400 text-center">
                                                Tidak ditemukan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <script>
                                    function desaWaliDropdown() {
                                        return {
                                            open: false,
                                            search: '',
                                            selected: @json($parent->desa_wali ?? ''),
                                            selectedText: @json($parent->desa_wali ?? 'Pilih Desa'),
                                            allOptions: window.villagesData,
                                            currentDistrictCode: '',
                                            noKecamatanSelected: true,
                                            init() {
                                                if (!this.selected) {
                                                    this.selectedText = 'Pilih Desa';
                                                }
                                                // Listen to Kecamatan Wali dropdown changes
                                                const kecamatanSelect = document.getElementById('kecamatanWaliSelect');
                                                if (kecamatanSelect) {
                                                    // Initial district code
                                                    const initialOpt = kecamatanSelect.options[kecamatanSelect.selectedIndex];
                                                    if (initialOpt && initialOpt.dataset.districtCode) {
                                                        this.currentDistrictCode = initialOpt.dataset.districtCode;
                                                        this.noKecamatanSelected = false;
                                                    } else {
                                                        this.noKecamatanSelected = true;
                                                    }
                                                    // On change
                                                    kecamatanSelect.addEventListener('change', (e) => {
                                                        const opt = e.target.options[e.target.selectedIndex];
                                                        this.currentDistrictCode = opt?.dataset?.districtCode || '';
                                                        this.noKecamatanSelected = !this.currentDistrictCode;
                                                        // Reset selection when kecamatan changes
                                                        this.selected = '';
                                                        this.selectedText = 'Pilih Desa';
                                                        this.search = '';
                                                    });
                                                }
                                            },
                                            get options() {
                                                if (!this.currentDistrictCode) return [];
                                                return this.allOptions.filter(opt => opt.district_code === this.currentDistrictCode);
                                            },
                                            get filteredOptions() {
                                                if (!this.search || this.search.trim().length < 2) return [];
                                                const term = this.search.toLowerCase().trim();
                                                return this.options.filter(opt => opt.text.toLowerCase().includes(term)).slice(0, 20);
                                            },
                                            get showHint() {
                                                if (this.noKecamatanSelected) return false;
                                                return !this.search || this.search.trim().length < 2;
                                            },
                                            get showNoCityMessage() {
                                                return this.noKecamatanSelected;
                                            },
                                            selectOption(opt) {
                                                this.selected = opt.value;
                                                this.selectedText = opt.text;
                                                this.open = false;
                                                this.search = '';
                                                this.$nextTick(() => {
                                                    const input = this.$refs.desaWaliInput;
                                                    if(input) {
                                                        input.dispatchEvent(new Event('input', {bubbles:true}));
                                                        input.dispatchEvent(new Event('change', {bubbles:true}));
                                                    }
                                                });
                                            },
                                            updateDistrict() {
                                                const kecamatanSelect = document.getElementById('kecamatanWaliSelect');
                                                if (kecamatanSelect) {
                                                    const opt = kecamatanSelect.options[kecamatanSelect.selectedIndex];
                                                    this.currentDistrictCode = opt?.dataset?.districtCode || '';
                                                    this.noKecamatanSelected = !this.currentDistrictCode;
                                                }
                                            }
                                        }
                                    }
                                </script>
                            </div>

                            {{-- Handphone Wali --}}
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Handphone Wali</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-[#8B1538] transition-colors">
                                        <span class="text-sm font-bold opacity-50">+62</span>
                                    </div>
                                    <input type="text" name="handphone_wali" value="{{ $parent->handphone_wali ?? '' }}" maxlength="13" inputmode="numeric" pattern="^[0-9]{1,13}$" oninput="this.value = this.value.replace(/\D/g,'')"
                                        class="w-full pl-14 pr-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium" placeholder="81xxxxxxxxx">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> {{-- End Wali Section --}}

            {{-- Data Keluarga Lainnya --}}
            <div x-show="showOrangTua" x-cloak x-data="{ 
                keluarga: {{ json_encode($parent->keluarga ?? []) }},
                addKeluarga() {
                    this.keluarga.push({ nama: '', hubungan: '', pendidikan: '', pekerjaan: '', agama: '' });
                },
                removeKeluarga(index) {
                    this.keluarga.splice(index, 1);
                }
            }">
                <div class="flex items-center justify-between mb-8 pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Data Keluarga Lainnya</h3>
                        <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Extended Family</span>
                    </div>
                    <button type="button" @click="addKeluarga()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#8B1538] hover:bg-[#6D1029] text-white text-xs font-bold rounded-xl shadow-lg shadow-maroon/20 transition-all hover:-translate-y-0.5">
                        <i class="fas fa-plus"></i> Tambah Anggota
                    </button>
                </div>

                <template x-if="keluarga.length === 0">
                    <div class="text-center py-10 bg-[#F9FAFB] rounded-2xl border-2 border-dashed border-[#E5E7EB]">
                        <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center mx-auto mb-3 shadow-sm text-gray-400">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <p class="text-xs font-bold text-[#9CA3AF] uppercase tracking-wider">Belum ada data keluarga lainnya</p>
                    </div>
                </template>

                <div class="space-y-6">
                    <template x-for="(member, index) in keluarga" :key="index">
                        <div class="p-6 bg-white border border-[#E5E7EB] rounded-2xl shadow-sm relative group animate-fade-in">
                            <button type="button" @click="removeKeluarga(index)"
                                class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center text-[#9CA3AF] hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                <i class="fas fa-times"></i>
                            </button>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-[#6B7280] uppercase tracking-wider">Nama Lengkap</label>
                                    <input type="text" :name="'keluarga[' + index + '][nama]'" x-model="member.nama"
                                        class="w-full px-4 py-2.5 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium"
                                        placeholder="Nama anggota keluarga">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-[#6B7280] uppercase tracking-wider">Hubungan</label>
                                    <select :name="'keluarga[' + index + '][hubungan]'" x-model="member.hubungan"
                                        class="w-full px-4 py-2.5 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                        <option value="">Pilih Hubungan</option>
                                        <option value="Kakak">Kakak</option>
                                        <option value="Adik">Adik</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-[#6B7280] uppercase tracking-wider">Pendidikan</label>
                                    <select :name="'keluarga[' + index + '][pendidikan]'" x-model="member.pendidikan"
                                        class="w-full px-4 py-2.5 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                        <option value="">Pilih Pendidikan</option>
                                        @foreach(['Tidak Sekolah', 'Tamat SD', 'Tamat SMTP', 'Tamat SMTA', 'Diploma', 'Sarjana', 'Magister', 'Doktor'] as $p)
                                        <option value="{{ $p }}">{{ $p }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-[#6B7280] uppercase tracking-wider">Pekerjaan</label>
                                    <select :name="'keluarga[' + index + '][pekerjaan]'" x-model="member.pekerjaan"
                                        class="w-full px-4 py-2.5 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                        <option value="">Pilih Pekerjaan</option>
                                        @if(isset($pekerjaans) && $pekerjaans->count())
                                        @foreach($pekerjaans as $p)
                                        <option value="{{ $p->name }}">{{ $p->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-[#6B7280] uppercase tracking-wider">Agama</label>
                                    <select :name="'keluarga[' + index + '][agama]'" x-model="member.agama"
                                        class="w-full px-4 py-2.5 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                        <option value="">Pilih Agama</option>
                                        @if(isset($religions) && $religions->count())
                                        @foreach($religions as $rel)
                                        <option value="{{ $rel->name }}">{{ $rel->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Actions (inside Orang Tua/Wali tab) --}}
            <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-end gap-3">
                <a href="{{ route('mahasiswa.profil.index') }}" 
                    class="w-full sm:w-auto px-8 py-3 bg-[#F3F4F6] hover:bg-[#E5E7EB] text-[#4B5563] text-sm font-bold rounded-xl transition-all hover:-translate-y-0.5 text-center">
                    <i class="fas fa-times mr-2 font-medium"></i> Batal
                </a>
                
                @if(!$isLocked)
                <button type="submit" 
                    class="w-full sm:w-auto px-10 py-3 bg-gradient-to-r from-[#8B1538] to-[#6D1029] hover:from-[#6D1029] hover:to-[#550c20] text-white text-sm font-bold rounded-xl shadow-lg shadow-maroon/20 transition-all hover:-translate-y-0.5 flex items-center justify-center">
                    <i class="fas fa-save mr-2 font-medium"></i> Update Profil Mahasiswa
                </button>
                @else
                <button type="submit" 
                    class="w-full sm:w-auto px-10 py-3 bg-gradient-to-r from-[#8B1538] to-[#6D1029] hover:from-[#6D1029] hover:to-[#550c20] text-white text-sm font-bold rounded-xl shadow-lg shadow-maroon/20 transition-all hover:-translate-y-0.5 flex items-center justify-center">
                    <i class="fas fa-save mr-2 font-medium"></i> Update Profil Mahasiswa
                </button>
                @endif
            </div>
        </div>

    </form>
</div>

<script>
    function validateDocumentFiles(input) {
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
        const allowedExtensions = ['.pdf', '.jpg', '.jpeg', '.png'];
        const files = input.files;
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const fileName = file.name.toLowerCase();
            const fileType = file.type;
            
            // Check if file type is allowed
            const isValidType = allowedTypes.includes(fileType);
            const isValidExtension = allowedExtensions.some(ext => fileName.endsWith(ext));
            
            if (!isValidType && !isValidExtension) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format File Tidak Valid!',
                    html: 'File yang diizinkan: <strong>PDF, JPEG, atau PNG</strong>.<br><br>File "<strong>' + file.name + '</strong>" tidak dapat diupload.',
                    confirmButtonColor: '#8B1538',
                    confirmButtonText: 'OK'
                });
                input.value = ''; // Clear the input
                return false;
            }
        }
        return true;
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fields to require and which tab they belong to
        const requiredMap = {
            // akademik
            'name': 'akademik',
            'no_hp': 'akademik',
            'email': 'akademik',
            // data_pribadi
            'alamat': 'data_pribadi',
            'provinsi': 'data_pribadi',
            'kota': 'data_pribadi',
            'kecamatan': 'data_pribadi',
            'tempat_lahir': 'data_pribadi',
            'tanggal_lahir': 'data_pribadi',
            'jenis_kelamin': 'data_pribadi',
            'agama': 'data_pribadi',
            'status_sipil': 'data_pribadi',
            // orang_tua
            'nama_ayah': 'orang_tua',
            'nama_ibu': 'orang_tua',
            'alamat_ayah': 'orang_tua',
            'handphone_ayah': 'orang_tua',
            'alamat_ibu': 'orang_tua',
            'handphone_ibu': 'orang_tua'
        };

        const form = document.querySelector('form');
        if (!form) return;

        // helper: ensure input container is positioned relative for icon
        function ensureRelative(el) {
            const parent = el.closest('.lg\\:col-span-9') || el.parentElement;
            if (parent && !parent.classList.contains('relative')) parent.classList.add('relative');
            return parent || el.parentElement;
        }

        function createIconSpan(parent) {
            let span = parent.querySelector('.validation-icon');
            if (!span) {
                span = document.createElement('span');
                span.className = 'validation-icon absolute right-3 top-1/2 transform -translate-y-1/2 text-sm pointer-events-none';
                parent.appendChild(span);
            }
            return span;
        }

        function markValid(field) {
            const parent = ensureRelative(field);
            field.classList.remove('border-red-500', 'bg-red-50');
            field.classList.add('border-green-500', 'bg-green-50');
            const icon = createIconSpan(parent);
            icon.innerHTML = '<i class="fas fa-check text-green-600"></i>';
        }

        function markInvalid(field) {
            const parent = ensureRelative(field);
            field.classList.remove('border-green-500', 'bg-green-50');
            field.classList.add('border-red-500', 'bg-red-50');
            const icon = createIconSpan(parent);
            icon.innerHTML = '<i class="fas fa-exclamation-triangle text-red-600"></i>';
        }

        function clearValidation(field) {
            const parent = ensureRelative(field);
            field.classList.remove('border-green-500', 'bg-green-50', 'border-red-500', 'bg-red-50');
            const icon = parent.querySelector('.validation-icon');
            if (icon) icon.remove();
        }

        function validateField(field) {
            const name = field.getAttribute('name');
            if (!name) return true;
            const val = (field.value || '').toString().trim();
            if (requiredMap[name]) {
                if (val === '') {
                    markInvalid(field);
                    return false;
                } else {
                    markValid(field);
                    return true;
                }
            }
            // not required: show green if filled
            if (val === '') {
                clearValidation(field);
                return true;
            }
            markValid(field);
            return true;
        }

        // attach listeners to all inputs/selects/textareas in form
        const fields = Array.from(form.querySelectorAll('input:not([type="hidden"]), select, textarea'));
        fields.forEach(f => {
            // initial state
            validateField(f);
            // events
            f.addEventListener('input', () => validateField(f));
            f.addEventListener('change', () => validateField(f));
            f.addEventListener('blur', () => validateField(f));
        });

        // submit handler
        form.addEventListener('submit', function(e) {
            const invalid = [];
            Object.keys(requiredMap).forEach(name => {
                const field = form.querySelector('[name="' + name + '"]');
                if (!field) return;
                const ok = validateField(field);
                if (!ok) invalid.push({
                    field,
                    tab: requiredMap[name]
                });
            });
            if (invalid.length) {
                e.preventDefault();
                // focus first invalid and switch to its tab
                const first = invalid[0];
                // try to set Alpine activeTab if available
                const alpineRoot = document.querySelector('[x-data]');
                try {
                    if (alpineRoot && alpineRoot.__x) {
                        alpineRoot.__x.$data.activeTab = first.tab;
                    } else if (alpineRoot && window.Alpine) {
                        alpineRoot.dispatchEvent(new CustomEvent('setActiveTab', {
                            detail: first.tab
                        }));
                    }
                } catch (err) {
                    /* ignore */
                }

                // small timeout to allow tab switch then focus
                setTimeout(() => {
                    try {
                        first.field.focus();
                        first.field.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    } catch (e) {}
                }, 200);
                return false;
            }
        });
    });

</script>

{{-- Dynamic "Belum Diisi" Label Styles (always active) --}}
<style>
    .empty-field-label::after {
        content: ' (Belum diisi)';
        font-size: 10px;
        font-weight: normal;
        color: #9CA3AF;
    }
    .missing-field {
        border-color: #ef4444 !important;
        background-color: #fef2f2 !important;
        animation: pulse-red 2s ease-in-out;
    }
    .missing-field:focus {
        border-color: #ef4444 !important;
        ring-color: rgba(239, 68, 68, 0.2) !important;
    }
    @keyframes pulse-red {
        0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
        50% { box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.2); }
    }
    .missing-label {
        color: #ef4444 !important;
    }
    .missing-label::after {
        content: ' (Belum diisi)';
        font-size: 10px;
        font-weight: normal;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Find the label for a given form element
        function findLabelForElement(element) {
            // Traverse up through parent elements to find a label
            let current = element.parentElement;
            while (current && !current.matches('form, body')) {
                // Check if this container has a direct label child
                let label = null;
                for (const child of current.children) {
                    if (child.tagName === 'LABEL') {
                        label = child;
                        break;
                    }
                }
                if (label) return label;
                current = current.parentElement;
            }
            return null;
        }
        
        // Toggle the empty-field-label class based on whether the field has a value
        function toggleEmptyLabel(element) {
            const label = findLabelForElement(element);
            if (!label) return;
            
            let hasValue = false;
            if (element.type === 'file') {
                hasValue = element.files && element.files.length > 0;
            } else if (element.type === 'hidden') {
                hasValue = element.value.trim() !== '';
            } else {
                hasValue = element.value.trim() !== '';
            }
            
            if (hasValue) {
                label.classList.remove('empty-field-label');
            } else {
                // Only add if not already a missing-label (which has higher priority)
                if (!label.classList.contains('missing-label')) {
                    label.classList.add('empty-field-label');
                }
            }
        }
        
        // Apply to all visible form fields
        const allFields = document.querySelectorAll('input:not([type="hidden"]):not([type="submit"]):not([type="button"]), select, textarea');
        
        allFields.forEach(element => {
            // Initial check
            toggleEmptyLabel(element);
            
            // Add event listeners
            const eventType = (element.tagName === 'SELECT' || element.type === 'file') ? 'change' : 'input';
            element.addEventListener(eventType, function() {
                toggleEmptyLabel(element);
            });
            element.addEventListener('blur', function() {
                toggleEmptyLabel(element);
            });
        });
        
        // Handle hidden inputs for custom dropdowns (like Desa/Kelurahan)
        const hiddenInputs = document.querySelectorAll('input[type="hidden"][name^="desa"]');
        
        // Store previous values to detect changes
        const previousValues = {};
        hiddenInputs.forEach(element => {
            previousValues[element.name] = element.value;
            toggleEmptyLabel(element);
        });
        
        // Poll hidden inputs for changes (since they are updated by Alpine)
        setInterval(() => {
            hiddenInputs.forEach(element => {
                const currentValue = element.value;
                const prevValue = previousValues[element.name];
                
                // Always update the label state
                toggleEmptyLabel(element);
                
                // If value changed, dispatch a change event for other listeners
                if (currentValue !== prevValue) {
                    previousValues[element.name] = currentValue;
                    element.dispatchEvent(new Event('input', { bubbles: true }));
                    element.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        }, 200);
        
        // Backend-driven missing fields highlight (additional red styling)
        @if($highlightMissing && count($missingFields) > 0)
        const missingFields = @json(array_keys($missingFields));
        const fieldSelectors = {
            'no_hp': 'input[name="no_hp"]',
            'alamat': 'textarea[name="alamat"]',
            'rt': 'input[name="rt"]',
            'rw': 'input[name="rw"]',
            'tempat_lahir': 'input[name="tempat_lahir"]',
            'tanggal_lahir': 'input[name="tanggal_lahir"]',
            'jenis_kelamin': 'select[name="jenis_kelamin"]',
            'agama': 'select[name="agama"]',
            'status_sipil': 'select[name="status_sipil"]',
            'kota': 'select[name="kota"]',
            'kecamatan': 'select[name="kecamatan"]',
            'provinsi': 'select[name="provinsi"]',
            'desa': 'input[name="desa"]',
            'jenis_sekolah': 'select[name="jenis_sekolah"]',
            'jurusan_sekolah': 'select[name="jurusan_sekolah"]',
            'tahun_lulus': 'input[name="tahun_lulus"]',
            'nilai_kelulusan': 'input[name="nilai_kelulusan"]',
            'file_ijazah': 'input[name="file_ijazah[]"]',
            'file_transkrip': 'input[name="file_transkrip[]"]',
            'file_kk': 'input[name="file_kk[]"]',
            'file_ktp': 'input[name="file_ktp[]"]',
            'nama_ayah': 'input[name="nama_ayah"]',
            'pendidikan_ayah': 'select[name="pendidikan_ayah"]',
            'pekerjaan_ayah': 'select[name="pekerjaan_ayah"]',
            'agama_ayah': 'select[name="agama_ayah"]',
            'nama_ibu': 'input[name="nama_ibu"]',
            'pendidikan_ibu': 'select[name="pendidikan_ibu"]',
            'pekerjaan_ibu': 'select[name="pekerjaan_ibu"]',
            'agama_ibu': 'select[name="agama_ibu"]',
            'alamat_ayah': 'textarea[name="alamat_ayah"]',
            'kota_ayah': 'select[name="kota_ayah"]',
            'kecamatan_ayah': 'select[name="kecamatan_ayah"]',
            'propinsi_ayah': 'select[name="propinsi_ayah"]',
            'desa_ayah': 'input[name="desa_ayah"]',
            'handphone_ayah': 'input[name="handphone_ayah"]',
            'alamat_ibu': 'textarea[name="alamat_ibu"]',
            'kota_ibu': 'select[name="kota_ibu"]',
            'kecamatan_ibu': 'select[name="kecamatan_ibu"]',
            'propinsi_ibu': 'select[name="propinsi_ibu"]',
            'desa_ibu': 'input[name="desa_ibu"]',
            'handphone_ibu': 'input[name="handphone_ibu"]',
            'alamat_ktp': 'textarea[name="alamat_ktp"]',
            'rt_ktp': 'input[name="rt_ktp"]',
            'rw_ktp': 'input[name="rw_ktp"]',
            'provinsi_ktp': 'select[name="provinsi_ktp"]',
            'kota_ktp': 'select[name="kota_ktp"]',
            'kecamatan_ktp': 'select[name="kecamatan_ktp"]',
            'desa_ktp': 'input[name="desa_ktp"]',
            'nama_wali': 'input[name="nama_wali"]',
            'hubungan_wali': 'select[name="hubungan_wali"]',
            'pendidikan_wali': 'select[name="pendidikan_wali"]',
            'pekerjaan_wali': 'select[name="pekerjaan_wali"]',
            'agama_wali': 'select[name="agama_wali"]',
            'alamat_wali': 'textarea[name="alamat_wali"]',
            'provinsi_wali': 'select[name="provinsi_wali"]',
            'kota_wali': 'select[name="kota_wali"]',
            'kecamatan_wali': 'select[name="kecamatan_wali"]',
            'desa_wali': 'input[name="desa_wali"]',
            'handphone_wali': 'input[name="handphone_wali"]',
        };
        
        function toggleMissingState(element, isEmpty) {
            const label = findLabelForElement(element);
            
            if (isEmpty) {
                element.classList.add('missing-field');
                if (label) {
                    label.classList.remove('empty-field-label');
                    label.classList.add('missing-label');
                }
                if (element.type === 'hidden' && element.nextElementSibling && element.nextElementSibling.tagName === 'BUTTON') {
                    element.nextElementSibling.classList.remove('border-[#E5E7EB]');
                    element.nextElementSibling.classList.add('border-red-500', 'bg-red-50');
                }
            } else {
                element.classList.remove('missing-field');
                if (label) {
                    label.classList.remove('missing-label');
                }
                if (element.type === 'hidden' && element.nextElementSibling && element.nextElementSibling.tagName === 'BUTTON') {
                    element.nextElementSibling.classList.remove('border-red-500', 'bg-red-50');
                    element.nextElementSibling.classList.add('border-[#E5E7EB]');
                }
                // Re-check if it should show empty-field-label
                toggleEmptyLabel(element);
            }
        }
        
        missingFields.forEach(field => {
            let selector = fieldSelectors[field];
            if (!selector && field.startsWith('keluarga.')) {
                const parts = field.split('.');
                if (parts.length === 3) {
                    selector = `[name="keluarga[${parts[1]}][${parts[2]}]"]`; 
                }
            }
            if (selector) {
                const element = document.querySelector(selector);
                if (element) {
                    element.classList.add('missing-field');
                    const label = findLabelForElement(element);
                    if (label) {
                        label.classList.remove('empty-field-label');
                        label.classList.add('missing-label');
                    }
                    
                    const eventType = (element.tagName === 'SELECT' || element.type === 'file') ? 'change' : 'input';
                    element.addEventListener(eventType, function() {
                        let hasValue = element.type === 'file' ? (element.files && element.files.length > 0) : element.value.trim() !== '';
                        toggleMissingState(element, !hasValue);
                    });
                }
            }
        });
        
        setTimeout(() => {
            const firstMissing = document.querySelector('.missing-field');
            if (firstMissing) {
                firstMissing.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 500);
        @endif
    });
</script>
@endsection
