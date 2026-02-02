@extends('layouts.mahasiswa')

@section('title', 'Profile Mahasiswa')
@section('page-title', 'Profile Mahasiswa')

@section('content')
@php
    $isLocked = $mahasiswa->isProfileComplete();
@endphp

{{-- Flash Warning Message (from redirect) --}}
@if(session('warning'))
<div class="bg-orange-50 border-l-4 border-orange-500 p-4 mb-6 rounded-md">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle text-orange-600 mr-3"></i>
        <p class="text-sm text-orange-800 font-medium">{{ session('warning') }}</p>
    </div>
</div>
@endif

@if($isLocked)
<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-md">
    <div class="flex items-center">
        <i class="fas fa-lock text-yellow-600 mr-3"></i>
        <p class="text-sm text-yellow-800 font-medium">Data profil Anda sudah lengkap dan terkunci. Anda hanya dapat melihat data tanpa melakukan perubahan.</p>
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


<div class="bg-white rounded-lg shadow-sm p-8" x-data="{ 
    activeTab: 'akademik', 
    photoPreview: '{{ $mahasiswa->foto ? asset("storage/" . $mahasiswa->foto) : "" }}' 
}">

    {{-- Tabs Header --}}
    <div class="flex border-b border-gray-200 mb-8 overflow-x-auto">
        <button @click="activeTab = 'akademik'"
            class="flex-1 min-w-[120px] py-4 text-center text-sm transition-all duration-200 font-semibold"
            style="border-bottom: 2px solid #8B1538; color: #8B1538; background-color: #f9fafb;"
            x-bind:style="activeTab === 'akademik' ? 'border-bottom: 2px solid #8B1538; color: #8B1538; background-color: #f9fafb;' : 'border-bottom: 2px solid transparent; color: #6b7280; background-color: transparent;'">
            Akademik
        </button>
        <button @click="activeTab = 'data_pribadi'"
            class="flex-1 min-w-[120px] py-4 text-center text-sm transition-all duration-200"
            style="border-bottom: 2px solid transparent; color: #6b7280;"
            x-bind:style="activeTab === 'data_pribadi' ? 'border-bottom: 2px solid #8B1538; color: #8B1538; background-color: #f9fafb; font-weight: 600;' : 'border-bottom: 2px solid transparent; color: #6b7280; background-color: transparent;'"
            onmouseover="if(activeTab !== 'data_pribadi') { this.style.color='#374151'; this.style.backgroundColor='#f9fafb'; }"
            onmouseout="if(activeTab !== 'data_pribadi') { this.style.color='#6b7280'; this.style.backgroundColor='transparent'; }">
            Data Lanjutan
        </button>
        <button @click="activeTab = 'orang_tua'"
            class="flex-1 min-w-[120px] py-4 text-center text-sm transition-all duration-200"
            style="border-bottom: 2px solid transparent; color: #6b7280;"
            x-bind:style="activeTab === 'orang_tua' ? 'border-bottom: 2px solid #8B1538; color: #8B1538; background-color: #f9fafb; font-weight: 600;' : 'border-bottom: 2px solid transparent; color: #6b7280; background-color: transparent;'"
            onmouseover="if(activeTab !== 'orang_tua') { this.style.color='#374151'; this.style.backgroundColor='#f9fafb'; }"
            onmouseout="if(activeTab !== 'orang_tua') { this.style.color='#6b7280'; this.style.backgroundColor='transparent'; }">
            Orang Tua / Wali
        </button>
        <button @click="activeTab = 'asal_sekolah'"
            class="flex-1 min-w-[120px] py-4 text-center text-sm transition-all duration-200"
            style="border-bottom: 2px solid transparent; color: #6b7280;"
            x-bind:style="activeTab === 'asal_sekolah' ? 'border-bottom: 2px solid #8B1538; color: #8B1538; background-color: #f9fafb; font-weight: 600;' : 'border-bottom: 2px solid transparent; color: #6b7280; background-color: transparent;'"
            onmouseover="if(activeTab !== 'asal_sekolah') { this.style.color='#374151'; this.style.backgroundColor='#f9fafb'; }"
            onmouseout="if(activeTab !== 'asal_sekolah') { this.style.color='#6b7280'; this.style.backgroundColor='transparent'; }">
            Asal Sekolah
            </button>
    </div>

    <form action="{{ route('mahasiswa.profil.update') }}" method="POST" enctype="multipart/form-data" class="max-w-5xl mx-auto">
        @csrf
        @method('PUT')

        {{-- Tab: Akademik --}}
        <div x-show="activeTab === 'akademik'" x-cloak class="space-y-10 animate-fade-in">
            
            {{-- Username & Password Section --}}
            <div>
                <h3 class="text-gray-800 font-medium mb-6 pb-2 border-b border-gray-100">Username dan Password</h3>
                <div class="space-y-5">
                    {{-- Username --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Username</label>
                        <div class="lg:col-span-9">
                            <input type="text" value="{{ $mahasiswa->nim }}" readonly 
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-md text-gray-500 text-sm shadow-sm">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Password</label>
                        <div class="lg:col-span-9">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                <input type="password" name="password" placeholder="********"
                                    class="w-full sm:w-1/2 px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                <span class="text-xs text-red-500 italic">* Kosongan jika tidak ingin mengubah password</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Akademik Section --}}
            <div>
                <h3 class="text-gray-800 font-medium mb-6 pb-2 border-b border-gray-100">Data Akademik</h3>
                <div class="space-y-5">
                    {{-- NIM --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">NIM</label>
                        <div class="lg:col-span-9">
                            <input type="text" value="{{ $mahasiswa->nim }}" readonly 
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-md text-gray-500 text-sm shadow-sm">
                        </div>
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Nama Lengkap</label>
                        <div class="lg:col-span-9">
                            <input type="text" name="name" value="{{ $user->name }}" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                        </div>
                    </div>

                    {{-- Handphone --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Handphone</label>
                        <div class="lg:col-span-9">
                            <input type="text" name="no_hp" value="{{ $mahasiswa->no_hp }}" maxlength="13" inputmode="numeric" pattern="^[0-9]{1,13}$" title="Masukkan maksimal 13 digit angka" oninput="this.value = this.value.replace(/\D/g,'')" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Email</label>
                        <div class="lg:col-span-9">
                            <input type="email" name="email" value="{{ $user->email }}" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                        </div>
                    </div>

                    {{-- Foto --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-start">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium pt-2">Foto Mahasiswa</label>
                        <div class="lg:col-span-9">
                            <!-- Preview Image -->
                            <div class="w-32 h-40 border-2 border-gray-200 rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center"
                                style="width:128px; height:160px; overflow:hidden;">
                                <template x-if="photoPreview">
                                    <img
                                        :src="photoPreview"
                                        alt="Preview Foto Mahasiswa"
                                        class="w-full h-full object-cover"
                                        style="display:block; width:100%; height:100%; object-fit:cover;"
                                        loading="lazy">
                                </template>
                                <template x-if="!photoPreview">
                                    <div class="text-gray-300 flex flex-col items-center">
                                        <i class="fas fa-user text-4xl mb-2"></i>
                                        <span class="text-xs">No Photo</span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Upload Foto --}}
                     <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Upload Foto</label>
                        <div class="lg:col-span-9 flex items-center gap-3">
                            <label class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-700 cursor-pointer hover:bg-gray-50 transition shadow-sm font-medium">
                                Choose File
                                <input type="file" name="foto" class="hidden" @change="const file = $event.target.files[0]; if(file){ const reader = new FileReader(); reader.onload = (e) => photoPreview = e.target.result; reader.readAsDataURL(file); }">
                            </label>
                            <span class="text-xs text-gray-500 italic">Format: JPG/PNG, Max: 2MB</span>
                        </div>
                    </div>

                    {{-- Readonly Information --}}
                    @foreach([
                        'Jurusan' => $mahasiswa->prodi,
                        'Program' => '1 - REGULER',
                        'Kurikulum' => '32 - Kurikulum ' . $mahasiswa->prodi . ' ' . $mahasiswa->angkatan,
                        'Angkatan' => $mahasiswa->angkatan,
                        'Penasehat Akademik' => 'Dosen PA',
                        'Status Awal' => 'B - Baru',
                        'Status Mahasiswa' => 'A - Aktif'
                    ] as $label => $value)
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">{{ $label }}</label>
                        <div class="lg:col-span-9">
                            <div class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-md text-gray-500 text-sm flex justify-between items-center shadow-sm cursor-default">
                                <span>{{ $value ?? '-' }}</span>
                                <i class="fas fa-lock text-gray-300 text-xs"></i>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>

        {{-- Tab: Data Pribadi --}}
        <div x-show="activeTab === 'data_pribadi'" x-cloak class="space-y-10 animate-fade-in">
            <div>
                <h3 class="text-gray-800 font-medium mb-6 pb-2 border-b border-gray-100">Alamat Domisili</h3>
                <div class="space-y-5">
                    {{-- Alamat --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-start">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium pt-2">Alamat</label>
                        <div class="lg:col-span-9">
                            <textarea name="alamat" rows="3" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">{{ $mahasiswa->alamat }}</textarea>
                        </div>
                    </div>

                    {{-- RT / RW --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">RT / RW</label>
                        <div class="lg:col-span-9 grid grid-cols-2 gap-3">
                            <input type="text" name="rt" value="{{ $mahasiswa->rt }}" placeholder="RT"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                            <input type="text" name="rw" value="{{ $mahasiswa->rw }}" placeholder="RW"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                        </div>
                    </div>

                    {{-- Provinsi (dropdown) --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Provinsi</label>
                        <div class="lg:col-span-9">
                            <select name="provinsi" id="provinsiSelect" class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov['name'] }}" data-code="{{ $prov['province_code'] }}" {{ ($mahasiswa->provinsi ?? '') === $prov['name'] ? 'selected' : '' }}>{{ $prov['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Kota/Kabupaten (dropdown, populated based on provinsi) --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Kota/Kabupaten</label>
                        <div class="lg:col-span-9">
                            <select name="kota" id="kotaSelect" class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                <option value="">Pilih Kota/Kabupaten</option>
                            </select>
                        </div>
                    </div>

                    {{-- Desa (dropdown with all villages) --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Desa</label>
                        <div class="lg:col-span-9">
                            <select name="desa" id="desaSelect" class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                <option value="">Pilih Desa</option>
                                @foreach($villages as $village)
                                    <option value="{{ $village['name'] }}" {{ ($mahasiswa->desa ?? '') === $village['name'] ? 'selected' : '' }}>{{ $village['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alamat Sesuai KTP --}}
            <div>
                <h3 class="text-gray-800 font-medium mb-6 pb-2 border-b border-gray-100">Alamat Sesuai KTP</h3>
                <div class="space-y-5">
                    {{-- Alamat KTP --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-start">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium pt-2">Alamat</label>
                        <div class="lg:col-span-9">
                            <textarea name="alamat_ktp" rows="3" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">{{ $mahasiswa->alamat_ktp }}</textarea>
                        </div>
                    </div>

                    {{-- RT / RW KTP --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">RT / RW</label>
                        <div class="lg:col-span-9 grid grid-cols-2 gap-3">
                            <input type="text" name="rt_ktp" value="{{ $mahasiswa->rt_ktp }}" placeholder="RT"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                            <input type="text" name="rw_ktp" value="{{ $mahasiswa->rw_ktp }}" placeholder="RW"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                        </div>
                    </div>

                    {{-- Provinsi KTP --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Provinsi</label>
                        <div class="lg:col-span-9">
                            <select name="provinsi_ktp" id="provinsiKtpSelect" class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov['name'] }}" data-code="{{ $prov['province_code'] }}" {{ ($mahasiswa->provinsi_ktp ?? '') === $prov['name'] ? 'selected' : '' }}>{{ $prov['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Kota/Kabupaten KTP --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Kota/Kabupaten</label>
                        <div class="lg:col-span-9">
                            <select name="kota_ktp" id="kotaKtpSelect" class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                <option value="">Pilih Kota/Kabupaten</option>
                            </select>
                        </div>
                    </div>

                    {{-- Desa KTP --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Desa</label>
                        <div class="lg:col-span-9">
                            <select name="desa_ktp" id="desaKtpSelect" class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                <option value="">Pilih Desa</option>
                                @foreach($villages as $village)
                                    <option value="{{ $village['name'] }}" {{ ($mahasiswa->desa_ktp ?? '') === $village['name'] ? 'selected' : '' }}>{{ $village['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

                    <script>
                        (function(){
                            const cities = @json($cities);
                            const provinsiSelect = document.getElementById('provinsiSelect');
                            const kotaSelect = document.getElementById('kotaSelect');
                            const selectedKota = {!! json_encode($mahasiswa->kota ?? '') !!};

                            function populateCities(provinceCode) {
                                kotaSelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                                
                                if (!provinceCode) {
                                    kotaSelect.disabled = true;
                                    return;
                                }

                                const filteredCities = cities.filter(c => c.province_code === provinceCode);
                                filteredCities.forEach(city => {
                                    const opt = document.createElement('option');
                                    opt.value = city.name;
                                    opt.textContent = city.name;
                                    if (city.name === selectedKota) opt.selected = true;
                                    kotaSelect.appendChild(opt);
                                });

                                kotaSelect.disabled = filteredCities.length === 0;
                            }

                            provinsiSelect.addEventListener('change', function() {
                                const selectedOption = this.options[this.selectedIndex];
                                const provinceCode = selectedOption.dataset.code || '';
                                populateCities(provinceCode);
                            });

                            // Initialize on page load
                            const initialOption = provinsiSelect.options[provinsiSelect.selectedIndex];
                            if (initialOption && initialOption.dataset.code) {
                                populateCities(initialOption.dataset.code);
                            } else {
                                kotaSelect.disabled = true;
                            }

                            // KTP Address dropdowns
                            const provinsiKtpSelect = document.getElementById('provinsiKtpSelect');
                            const kotaKtpSelect = document.getElementById('kotaKtpSelect');
                            const selectedKotaKtp = {!! json_encode($mahasiswa->kota_ktp ?? '') !!};

                            function populateCitiesKtp(provinceCode) {
                                kotaKtpSelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                                
                                if (!provinceCode) {
                                    kotaKtpSelect.disabled = true;
                                    return;
                                }

                                const filteredCities = cities.filter(c => c.province_code === provinceCode);
                                filteredCities.forEach(city => {
                                    const opt = document.createElement('option');
                                    opt.value = city.name;
                                    opt.textContent = city.name;
                                    if (city.name === selectedKotaKtp) opt.selected = true;
                                    kotaKtpSelect.appendChild(opt);
                                });

                                kotaKtpSelect.disabled = filteredCities.length === 0;
                            }

                            provinsiKtpSelect.addEventListener('change', function() {
                                const selectedOption = this.options[this.selectedIndex];
                                const provinceCode = selectedOption.dataset.code || '';
                                populateCitiesKtp(provinceCode);
                            });

                            // Initialize KTP on page load
                            const initialKtpOption = provinsiKtpSelect.options[provinsiKtpSelect.selectedIndex];
                            if (initialKtpOption && initialKtpOption.dataset.code) {
                                populateCitiesKtp(initialKtpOption.dataset.code);
                            } else {
                                kotaKtpSelect.disabled = true;
                            }
                        })();
                    </script>

            {{-- Data Pribadi Lainnya Section --}}
            <div>
                <h3 class="text-gray-800 font-medium mb-6 pb-2 border-b border-gray-100">Data Pribadi</h3>
                <div class="space-y-5">
                    {{-- Tempat Lahir --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Tempat Lahir</label>
                        <div class="lg:col-span-9">
                            <input type="text" name="tempat_lahir" value="{{ $mahasiswa->tempat_lahir }}" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                        </div>
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Tanggal Lahir</label>
                        <div class="lg:col-span-9">
                            <input type="date" name="tanggal_lahir" value="{{ $mahasiswa->tanggal_lahir ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('Y-m-d') : '' }}" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                        </div>
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Jenis Kelamin</label>
                        <div class="lg:col-span-9">
                            <select name="jenis_kelamin" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-Laki" {{ $mahasiswa->jenis_kelamin === 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="Perempuan" {{ $mahasiswa->jenis_kelamin === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    {{-- Agama --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Agama</label>
                        <div class="lg:col-span-9">
                            <select name="agama" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                <option value="">Pilih Agama</option>
                                @if(isset($religions) && $religions->count())
                                    @foreach($religions as $rel)
                                        <option value="{{ $rel->name }}" {{ $mahasiswa->agama === $rel->name ? 'selected' : '' }}>{{ $rel->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    {{-- Status Sipil --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Status Sipil</label>
                        <div class="lg:col-span-9">
                            <select name="status_sipil" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                <option value="">Pilih Status Sipil</option>
                                <option value="Belum Menikah" {{ $mahasiswa->status_sipil === 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                <option value="Menikah" {{ $mahasiswa->status_sipil === 'Menikah' ? 'selected' : '' }}>Menikah</option>
                                <option value="Cerai" {{ $mahasiswa->status_sipil === 'Cerai' ? 'selected' : '' }}>Cerai</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section: Dokumen Pribadi --}}
            <div>
                <h3 class="text-gray-800 font-medium mb-6 pb-2 border-b border-gray-100">Dokumen Pribadi</h3>
                <p class="text-xs text-gray-500 mb-4">Upload dokumen dalam format PDF atau JPEG/PNG. Maksimal 5MB per file.</p>
                <div class="space-y-5">
                    {{-- File Ijazah --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-start">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium pt-2">Ijazah</label>
                        <div class="lg:col-span-9">
                            @if($mahasiswa->file_ijazah && count($mahasiswa->file_ijazah) > 0)
                                <div class="mb-2 space-y-1">
                                    @foreach($mahasiswa->file_ijazah as $file)
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <i class="fas fa-file text-cyan-600"></i>
                                            <a href="{{ asset('storage/' . $file) }}" target="_blank" class="hover:underline text-cyan-600">{{ basename($file) }}</a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" name="file_ijazah[]" multiple accept=".pdf,.jpeg,.jpg,.png"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100">
                        </div>
                    </div>

                    {{-- File Transkrip Nilai --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-start">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium pt-2">Transkrip Nilai</label>
                        <div class="lg:col-span-9">
                            @if($mahasiswa->file_transkrip && count($mahasiswa->file_transkrip) > 0)
                                <div class="mb-2 space-y-1">
                                    @foreach($mahasiswa->file_transkrip as $file)
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <i class="fas fa-file text-cyan-600"></i>
                                            <a href="{{ asset('storage/' . $file) }}" target="_blank" class="hover:underline text-cyan-600">{{ basename($file) }}</a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" name="file_transkrip[]" multiple accept=".pdf,.jpeg,.jpg,.png"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100">
                        </div>
                    </div>

                    {{-- File Kartu Keluarga --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-start">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium pt-2">Kartu Keluarga (KK)</label>
                        <div class="lg:col-span-9">
                            @if($mahasiswa->file_kk && count($mahasiswa->file_kk) > 0)
                                <div class="mb-2 space-y-1">
                                    @foreach($mahasiswa->file_kk as $file)
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <i class="fas fa-file text-cyan-600"></i>
                                            <a href="{{ asset('storage/' . $file) }}" target="_blank" class="hover:underline text-cyan-600">{{ basename($file) }}</a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" name="file_kk[]" multiple accept=".pdf,.jpeg,.jpg,.png"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100">
                        </div>
                    </div>

                    {{-- File KTP --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-start">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium pt-2">KTP</label>
                        <div class="lg:col-span-9">
                            @if($mahasiswa->file_ktp && count($mahasiswa->file_ktp) > 0)
                                <div class="mb-2 space-y-1">
                                    @foreach($mahasiswa->file_ktp as $file)
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <i class="fas fa-file text-cyan-600"></i>
                                            <a href="{{ asset('storage/' . $file) }}" target="_blank" class="hover:underline text-cyan-600">{{ basename($file) }}</a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" name="file_ktp[]" multiple accept=".pdf,.jpeg,.jpg,.png"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Tab: Orang Tua --}}
        <div x-show="activeTab === 'orang_tua'" x-cloak class="space-y-10 animate-fade-in"
            x-data="{ showOrangTua: true, showWali: {{ ($parent->nama_wali ?? '') ? 'true' : 'false' }} }">
            
            {{-- Toggle Orang Tua / Wali --}}
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <label class="text-sm text-gray-700 font-medium mb-3 block">Pilih Data yang Ingin Diisi:</label>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="show_orang_tua" x-model="showOrangTua"
                            class="w-4 h-4 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500">
                        <span class="text-sm text-gray-700">Orang Tua</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="show_wali" x-model="showWali"
                            class="w-4 h-4 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500">
                        <span class="text-sm text-gray-700">Wali</span>
                    </label>
                </div>
            </div>

            {{-- Orang Tua Section --}}
            <div x-show="showOrangTua" x-cloak class="space-y-10">
            {{-- Data Ayah --}}
            <div>
                <h3 class="text-gray-800 font-medium mb-6 pb-2 border-b border-gray-100">Data Ayah</h3>
                <div class="space-y-5">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Nama Ayah</label>
                        <div class="lg:col-span-9">
                            <input type="text" name="nama_ayah" value="{{ $parent->nama_ayah ?? '' }}" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Pendidikan Ayah</label>
                        <div class="lg:col-span-9">
                            <select name="pendidikan_ayah" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                <option value="">Pilih Pendidikan</option>
                                <option value="Tidak Sekolah" {{ ($parent->pendidikan_ayah ?? '') === 'Tidak Sekolah' ? 'selected' : '' }}>Tidak Sekolah</option>
                                <option value="Tamat SD" {{ ($parent->pendidikan_ayah ?? '') === 'Tamat SD' ? 'selected' : '' }}>Tamat SD</option>
                                <option value="Tamat SMTP" {{ ($parent->pendidikan_ayah ?? '') === 'Tamat SMTP' ? 'selected' : '' }}>Tamat SMTP</option>
                                <option value="Tamat SMTA" {{ ($parent->pendidikan_ayah ?? '') === 'Tamat SMTA' ? 'selected' : '' }}>Tamat SMTA</option>
                                <option value="Diploma" {{ ($parent->pendidikan_ayah ?? '') === 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                <option value="Sarjana" {{ ($parent->pendidikan_ayah ?? '') === 'Sarjana' ? 'selected' : '' }}>Sarjana</option>
                                <option value="Magister" {{ ($parent->pendidikan_ayah ?? '') === 'Magister' ? 'selected' : '' }}>Magister</option>
                                <option value="Doktor" {{ ($parent->pendidikan_ayah ?? '') === 'Doktor' ? 'selected' : '' }}>Doktor</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Pekerjaan Ayah</label>
                        <div class="lg:col-span-9">
                            <select name="pekerjaan_ayah" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                <option value="">Pilih Pekerjaan</option>
                                <option value="Tidak Bekerja" {{ ($parent->pekerjaan_ayah ?? '') === 'Tidak Bekerja' ? 'selected' : '' }}>Tidak Bekerja</option>
                                <option value="Pegawai Swasta" {{ ($parent->pekerjaan_ayah ?? '') === 'Pegawai Swasta' ? 'selected' : '' }}>Pegawai Swasta</option>
                                <option value="Pegawai Negeri" {{ ($parent->pekerjaan_ayah ?? '') === 'Pegawai Negeri' ? 'selected' : '' }}>Pegawai Negeri</option>
                                <option value="Wiraswasta" {{ ($parent->pekerjaan_ayah ?? '') === 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                                <option value="Petani" {{ ($parent->pekerjaan_ayah ?? '') === 'Petani' ? 'selected' : '' }}>Petani</option>
                                <option value="Nelayan" {{ ($parent->pekerjaan_ayah ?? '') === 'Nelayan' ? 'selected' : '' }}>Nelayan</option>
                                <option value="Pensiunan" {{ ($parent->pekerjaan_ayah ?? '') === 'Pensiunan' ? 'selected' : '' }}>Pensiunan</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Agama Ayah</label>
                        <div class="lg:col-span-9">
                            <select name="agama_ayah" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
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
                <h3 class="text-gray-800 font-medium mb-6 pb-2 border-b border-gray-100">Data Ibu</h3>
                <div class="space-y-5">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Nama Ibu</label>
                        <div class="lg:col-span-9">
                            <input type="text" name="nama_ibu" value="{{ $parent->nama_ibu ?? '' }}" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Pendidikan Ibu</label>
                        <div class="lg:col-span-9">
                            <select name="pendidikan_ibu" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                <option value="">Pilih Pendidikan</option>
                                <option value="Tidak Sekolah" {{ ($parent->pendidikan_ibu ?? '') === 'Tidak Sekolah' ? 'selected' : '' }}>Tidak Sekolah</option>
                                <option value="Tamat SD" {{ ($parent->pendidikan_ibu ?? '') === 'Tamat SD' ? 'selected' : '' }}>Tamat SD</option>
                                <option value="Tamat SMTP" {{ ($parent->pendidikan_ibu ?? '') === 'Tamat SMTP' ? 'selected' : '' }}>Tamat SMTP</option>
                                <option value="Tamat SMTA" {{ ($parent->pendidikan_ibu ?? '') === 'Tamat SMTA' ? 'selected' : '' }}>Tamat SMTA</option>
                                <option value="Diploma" {{ ($parent->pendidikan_ibu ?? '') === 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                <option value="Sarjana" {{ ($parent->pendidikan_ibu ?? '') === 'Sarjana' ? 'selected' : '' }}>Sarjana</option>
                                <option value="Magister" {{ ($parent->pendidikan_ibu ?? '') === 'Magister' ? 'selected' : '' }}>Magister</option>
                                <option value="Doktor" {{ ($parent->pendidikan_ibu ?? '') === 'Doktor' ? 'selected' : '' }}>Doktor</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Pekerjaan Ibu</label>
                        <div class="lg:col-span-9">
                            <select name="pekerjaan_ibu" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                <option value="">Pilih Pekerjaan</option>
                                <option value="Tidak Bekerja" {{ ($parent->pekerjaan_ibu ?? '') === 'Tidak Bekerja' ? 'selected' : '' }}>Tidak Bekerja</option>
                                <option value="Pegawai Swasta" {{ ($parent->pekerjaan_ibu ?? '') === 'Pegawai Swasta' ? 'selected' : '' }}>Pegawai Swasta</option>
                                <option value="Pegawai Negeri" {{ ($parent->pekerjaan_ibu ?? '') === 'Pegawai Negeri' ? 'selected' : '' }}>Pegawai Negeri</option>
                                <option value="Wiraswasta" {{ ($parent->pekerjaan_ibu ?? '') === 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                                <option value="Petani" {{ ($parent->pekerjaan_ibu ?? '') === 'Petani' ? 'selected' : '' }}>Petani</option>
                                <option value="Nelayan" {{ ($parent->pekerjaan_ibu ?? '') === 'Nelayan' ? 'selected' : '' }}>Nelayan</option>
                                <option value="Pensiunan" {{ ($parent->pekerjaan_ibu ?? '') === 'Pensiunan' ? 'selected' : '' }}>Pensiunan</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Agama Ibu</label>
                        <div class="lg:col-span-9">
                            <select name="agama_ibu" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
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

            {{-- Data Alamat Orang Tua --}}
                 <div>
                <h3 class="text-gray-800 font-medium mb-6 pb-2 border-b border-gray-100">Data Alamat Orang Tua</h3>
                <div class="space-y-5">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-start">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium pt-2">Alamat</label>
                        <div class="lg:col-span-9">
                            <textarea name="alamat_ortu" rows="3" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">{{ $parent->alamat_ortu ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Kota</label>
                        <div class="lg:col-span-9">
                            <input type="text" name="kota_ortu" value="{{ $parent->kota_ortu ?? '' }}" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Provinsi</label>
                        <div class="lg:col-span-9">
                            <input type="text" name="provinsi_ortu" value="{{ $parent->provinsi_ortu ?? '' }}" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Kab/Desa</label>
                        <div class="lg:col-span-9">
                            <input type="text" name="kabdesa_ortu" value="{{ $parent->kabupaten_ortu ?? '' }}" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Handphone Ortu</label>
                        <div class="lg:col-span-9">
                            <input type="text" name="handphone_ortu" value="{{ $parent->handphone_ortu ?? '' }}" maxlength="13" inputmode="numeric" pattern="^[0-9]{1,13}$" title="Masukkan maksimal 13 digit angka" oninput="this.value = this.value.replace(/\D/g,'')" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                        </div>
                    </div>
                </div>
            </div>


            </div> {{-- End Orang Tua Section --}}

            {{-- Wali Section --}}
            <div x-show="showWali" x-cloak class="space-y-10">
                {{-- Data Wali --}}
                <div>
                    <h3 class="text-gray-800 font-medium mb-6 pb-2 border-b border-gray-100">Data Wali</h3>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Nama Wali</label>
                            <div class="lg:col-span-9">
                                <input type="text" name="nama_wali" value="{{ $parent->nama_wali ?? '' }}" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Hubungan dengan Mahasiswa</label>
                            <div class="lg:col-span-9">
                                <select name="hubungan_wali" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                    <option value="">Pilih Hubungan</option>
                                    <option value="Kakek" {{ ($parent->hubungan_wali ?? '') === 'Kakek' ? 'selected' : '' }}>Kakek</option>
                                    <option value="Nenek" {{ ($parent->hubungan_wali ?? '') === 'Nenek' ? 'selected' : '' }}>Nenek</option>
                                    <option value="Paman" {{ ($parent->hubungan_wali ?? '') === 'Paman' ? 'selected' : '' }}>Paman</option>
                                    <option value="Bibi" {{ ($parent->hubungan_wali ?? '') === 'Bibi' ? 'selected' : '' }}>Bibi</option>
                                    <option value="Saudara Lainnya" {{ ($parent->hubungan_wali ?? '') === 'Saudara Lainnya' ? 'selected' : '' }}>Saudara Lainnya</option>
                                    <option value="Lainnya" {{ ($parent->hubungan_wali ?? '') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Pendidikan Wali</label>
                            <div class="lg:col-span-9">
                                <select name="pendidikan_wali" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                    <option value="">Pilih Pendidikan</option>
                                    <option value="Tidak Sekolah" {{ ($parent->pendidikan_wali ?? '') === 'Tidak Sekolah' ? 'selected' : '' }}>Tidak Sekolah</option>
                                    <option value="Tamat SD" {{ ($parent->pendidikan_wali ?? '') === 'Tamat SD' ? 'selected' : '' }}>Tamat SD</option>
                                    <option value="Tamat SMTP" {{ ($parent->pendidikan_wali ?? '') === 'Tamat SMTP' ? 'selected' : '' }}>Tamat SMTP</option>
                                    <option value="Tamat SMTA" {{ ($parent->pendidikan_wali ?? '') === 'Tamat SMTA' ? 'selected' : '' }}>Tamat SMTA</option>
                                    <option value="Diploma" {{ ($parent->pendidikan_wali ?? '') === 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                    <option value="Sarjana" {{ ($parent->pendidikan_wali ?? '') === 'Sarjana' ? 'selected' : '' }}>Sarjana</option>
                                    <option value="Magister" {{ ($parent->pendidikan_wali ?? '') === 'Magister' ? 'selected' : '' }}>Magister</option>
                                    <option value="Doktor" {{ ($parent->pendidikan_wali ?? '') === 'Doktor' ? 'selected' : '' }}>Doktor</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Pekerjaan Wali</label>
                            <div class="lg:col-span-9">
                                <select name="pekerjaan_wali" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                    <option value="">Pilih Pekerjaan</option>
                                    <option value="Tidak Bekerja" {{ ($parent->pekerjaan_wali ?? '') === 'Tidak Bekerja' ? 'selected' : '' }}>Tidak Bekerja</option>
                                    <option value="Pegawai Swasta" {{ ($parent->pekerjaan_wali ?? '') === 'Pegawai Swasta' ? 'selected' : '' }}>Pegawai Swasta</option>
                                    <option value="Pegawai Negeri" {{ ($parent->pekerjaan_wali ?? '') === 'Pegawai Negeri' ? 'selected' : '' }}>Pegawai Negeri</option>
                                    <option value="Wiraswasta" {{ ($parent->pekerjaan_wali ?? '') === 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                                    <option value="Petani" {{ ($parent->pekerjaan_wali ?? '') === 'Petani' ? 'selected' : '' }}>Petani</option>
                                    <option value="Nelayan" {{ ($parent->pekerjaan_wali ?? '') === 'Nelayan' ? 'selected' : '' }}>Nelayan</option>
                                    <option value="Pensiunan" {{ ($parent->pekerjaan_wali ?? '') === 'Pensiunan' ? 'selected' : '' }}>Pensiunan</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Agama Wali</label>
                            <div class="lg:col-span-9">
                                <select name="agama_wali" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
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
                    <h3 class="text-gray-800 font-medium mb-6 pb-2 border-b border-gray-100">Alamat Wali</h3>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-start">
                            <label class="lg:col-span-3 text-sm text-gray-600 font-medium pt-2">Alamat</label>
                            <div class="lg:col-span-9">
                                <textarea name="alamat_wali" rows="3" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">{{ $parent->alamat_wali ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Kota</label>
                            <div class="lg:col-span-9">
                                <input type="text" name="kota_wali" value="{{ $parent->kota_wali ?? '' }}" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Provinsi</label>
                            <div class="lg:col-span-9">
                                <input type="text" name="provinsi_wali" value="{{ $parent->provinsi_wali ?? '' }}" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Negara</label>
                            <div class="lg:col-span-9">
                                <input type="text" name="negara_wali" value="{{ $parent->negara_wali ?? '' }}" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Handphone Wali</label>
                            <div class="lg:col-span-9">
                                <input type="text" name="handphone_wali" value="{{ $parent->handphone_wali ?? '' }}" maxlength="13" inputmode="numeric" pattern="^[0-9]{1,13}$" title="Masukkan maksimal 13 digit angka" oninput="this.value = this.value.replace(/\D/g,'')" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
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
                <div class="flex items-center justify-between mb-6 pb-2 border-b border-gray-100">
                    <h3 class="text-gray-800 font-medium">Data Keluarga Lainnya</h3>
                    <button type="button" @click="addKeluarga()" 
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white rounded-lg shadow-sm transition-all"
                        style="background-color: #8B1538;" 
                        onmouseover="this.style.backgroundColor='#9f1c42'" 
                        onmouseout="this.style.backgroundColor='#8B1538'">
                        <i class="fas fa-plus"></i> Tambah Keluarga
                    </button>
                </div>
                
                <template x-if="keluarga.length === 0">
                    <p class="text-sm text-gray-500 italic">Belum ada data keluarga lainnya. Klik "Tambah Keluarga" untuk menambahkan.</p>
                </template>

                <div class="space-y-6">
                    <template x-for="(member, index) in keluarga" :key="index">
                        <div class="pb-6 border-b border-gray-100 last:border-0 relative">
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-start">
                                    <label class="lg:col-span-3 text-sm text-gray-600 font-medium pt-2">Nama</label>
                                    <div class="lg:col-span-9 flex gap-3">
                                        <input type="text" :name="'keluarga[' + index + '][nama]'" x-model="member.nama"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow"
                                            placeholder="Masukkan nama anggota keluarga">
                                        <button type="button" @click="removeKeluarga(index)" 
                                            class="flex-shrink-0 w-10 h-10 flex items-center justify-center text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors border border-gray-200 hover:border-red-200"
                                            title="Hapus keluarga ini">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                                    <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Hubungan</label>
                                    <div class="lg:col-span-9">
                                        <select :name="'keluarga[' + index + '][hubungan]'" x-model="member.hubungan"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                            <option value="">Pilih Hubungan</option>
                                            <option value="Kakak">Kakak</option>
                                            <option value="Adik">Adik</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                                    <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Pendidikan</label>
                                    <div class="lg:col-span-9">
                                        <select :name="'keluarga[' + index + '][pendidikan]'" x-model="member.pendidikan"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                            <option value="">Pilih Pendidikan</option>
                                            <option value="Tidak Sekolah">Tidak Sekolah</option>
                                            <option value="Tamat SD">Tamat SD</option>
                                            <option value="Tamat SMTP">Tamat SMTP</option>
                                            <option value="Tamat SMTA">Tamat SMTA</option>
                                            <option value="Diploma">Diploma</option>
                                            <option value="Sarjana">Sarjana</option>
                                            <option value="Magister">Magister</option>
                                            <option value="Doktor">Doktor</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                                    <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Pekerjaan</label>
                                    <div class="lg:col-span-9">
                                        <select :name="'keluarga[' + index + '][pekerjaan]'" x-model="member.pekerjaan"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                            <option value="">Pilih Pekerjaan</option>
                                            <option value="Tidak Bekerja">Tidak Bekerja</option>
                                            <option value="Pelajar/Mahasiswa">Pelajar/Mahasiswa</option>
                                            <option value="Pegawai Swasta">Pegawai Swasta</option>
                                            <option value="Pegawai Negeri">Pegawai Negeri</option>
                                            <option value="Wiraswasta">Wiraswasta</option>
                                            <option value="Petani">Petani</option>
                                            <option value="Nelayan">Nelayan</option>
                                            <option value="Pensiunan">Pensiunan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                                    <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Agama</label>
                                    <div class="lg:col-span-9">
                                        <select :name="'keluarga[' + index + '][agama]'" x-model="member.agama"
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
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
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Tab: Asal Sekolah --}}
        <div x-show="activeTab === 'asal_sekolah'" x-cloak class="space-y-10 animate-fade-in">
                 <div>
                <h3 class="text-gray-800 font-medium mb-6 pb-2 border-b border-gray-100">Data Asal Sekolah</h3>
                <div class="space-y-5">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Jenis Sekolah</label>
                        <div class="lg:col-span-9">
                            <select name="jenis_sekolah" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                <option value="">Pilih Jenis Sekolah</option>
                                <option value="1 - Umum" {{ $mahasiswa->jenis_sekolah === '1 - Umum' ? 'selected' : '' }}>1 - Umum</option>
                                <option value="2 - Kejuruan" {{ $mahasiswa->jenis_sekolah === '2 - Kejuruan' ? 'selected' : '' }}>2 - Kejuruan</option>
                                <option value="3 - Umum" {{ $mahasiswa->jenis_sekolah === '3 - Umum' ? 'selected' : '' }}>3 - Umum</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Jurusan Sekolah</label>
                        <div class="lg:col-span-9">
                            <select name="jurusan_sekolah" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                <option value="">Pilih Jurusan</option>
                                <option value="SMU - IPA" {{ $mahasiswa->jurusan_sekolah === 'SMU - IPA' ? 'selected' : '' }}>SMU - IPA</option>
                                <option value="SMU - IPS" {{ $mahasiswa->jurusan_sekolah === 'SMU - IPS' ? 'selected' : '' }}>SMU - IPS</option>
                                <option value="SMU - Bahasa" {{ $mahasiswa->jurusan_sekolah === 'SMU - Bahasa' ? 'selected' : '' }}>SMU - Bahasa</option>
                                <option value="SMK - Teknik Informatika" {{ $mahasiswa->jurusan_sekolah === 'SMK - Teknik Informatika' ? 'selected' : '' }}>SMK - Teknik Informatika</option>
                                <option value="SMK - Teknik Mesin" {{ $mahasiswa->jurusan_sekolah === 'SMK - Teknik Mesin' ? 'selected' : '' }}>SMK - Teknik Mesin</option>
                                <option value="SMK - Akuntansi" {{ $mahasiswa->jurusan_sekolah === 'SMK - Akuntansi' ? 'selected' : '' }}>SMK - Akuntansi</option>
                                <option value="SMK - Lainnya" {{ $mahasiswa->jurusan_sekolah === 'SMK - Lainnya' ? 'selected' : '' }}>SMK - Lainnya</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Tahun Lulus</label>
                        <div class="lg:col-span-9">
                            <input type="text" name="tahun_lulus" value="{{ $mahasiswa->tahun_lulus ?? '' }}" placeholder="Contoh: 2020"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Nilai Kelulusan</label>
                        <div class="lg:col-span-9">
                            <input type="number" name="nilai_kelulusan" value="{{ $mahasiswa->nilai_kelulusan ?? '' }}" step="0.01" min="0" max="100" placeholder="Contoh: 81.56"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-10 pt-6 border-t border-gray-100 flex items-center gap-4">
            @if(!$isLocked)
                <button type="submit" class="px-8 py-2.5 font-semibold rounded-full shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 text-sm" style="background-color: #8B1538; color: #ffffff;" onmouseover="this.style.backgroundColor='#6D1029'" onmouseout="this.style.backgroundColor='#8B1538'">
                    <i class="fas fa-save mr-2"></i> Update
                </button>
            @else
                <div class="flex items-center gap-2 px-8 py-2.5 bg-gray-100 text-gray-500 rounded-full text-sm">
                    <i class="fas fa-lock"></i>
                    <span>Data Terkunci</span>
                </div>
            @endif
            <a href="{{ route('mahasiswa.profil.index') }}" class="inline-flex items-center px-8 py-2.5 font-semibold rounded-full shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 text-sm" style="background-color: #dc2626; color: #ffffff;">
                <i class="fas fa-times mr-2"></i> Batal
            </a>
        </div>

    </form>
</div>

@if($isLocked)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Disable all input, select, and textarea fields
        const form = document.querySelector('form');
        if (form) {
            const fields = form.querySelectorAll('input:not([type="hidden"]), select, textarea');
            fields.forEach(field => {
                field.disabled = true;
                field.classList.add('bg-gray-50', 'cursor-not-allowed');
            });
        }
    });
</script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function(){
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
        'tempat_lahir': 'data_pribadi',
        'tanggal_lahir': 'data_pribadi',
        'jenis_kelamin': 'data_pribadi',
        'agama': 'data_pribadi',
        'status_sipil': 'data_pribadi',
        // orang_tua
        'nama_ayah': 'orang_tua',
        'nama_ibu': 'orang_tua',
        'alamat_ortu': 'orang_tua',
        'handphone_ortu': 'orang_tua'
    };

    const form = document.querySelector('form');
    if(!form) return;

    // helper: ensure input container is positioned relative for icon
    function ensureRelative(el){
        const parent = el.closest('.lg\\:col-span-9') || el.parentElement;
        if(parent && !parent.classList.contains('relative')) parent.classList.add('relative');
        return parent || el.parentElement;
    }

    function createIconSpan(parent){
        let span = parent.querySelector('.validation-icon');
        if(!span){
            span = document.createElement('span');
            span.className = 'validation-icon absolute right-3 top-1/2 transform -translate-y-1/2 text-sm pointer-events-none';
            parent.appendChild(span);
        }
        return span;
    }

    function markValid(field){
        const parent = ensureRelative(field);
        field.classList.remove('border-red-500','bg-red-50');
        field.classList.add('border-green-500','bg-green-50');
        const icon = createIconSpan(parent);
        icon.innerHTML = '<i class="fas fa-check text-green-600"></i>';
    }

    function markInvalid(field){
        const parent = ensureRelative(field);
        field.classList.remove('border-green-500','bg-green-50');
        field.classList.add('border-red-500','bg-red-50');
        const icon = createIconSpan(parent);
        icon.innerHTML = '<i class="fas fa-exclamation-triangle text-red-600"></i>';
    }

    function clearValidation(field){
        const parent = ensureRelative(field);
        field.classList.remove('border-green-500','bg-green-50','border-red-500','bg-red-50');
        const icon = parent.querySelector('.validation-icon');
        if(icon) icon.remove();
    }

    function validateField(field){
        const name = field.getAttribute('name');
        if(!name) return true;
        const val = (field.value || '').toString().trim();
        if(requiredMap[name]){
            if(val === ''){ markInvalid(field); return false; }
            else { markValid(field); return true; }
        }
        // not required: show green if filled
        if(val === '') { clearValidation(field); return true; }
        markValid(field); return true;
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
    form.addEventListener('submit', function(e){
        const invalid = [];
        Object.keys(requiredMap).forEach(name => {
            const field = form.querySelector('[name="'+name+'"]');
            if(!field) return;
            const ok = validateField(field);
            if(!ok) invalid.push({field, tab: requiredMap[name]});
        });
        if(invalid.length){
            e.preventDefault();
            // focus first invalid and switch to its tab
            const first = invalid[0];
            // try to set Alpine activeTab if available
            const alpineRoot = document.querySelector('[x-data]');
            try{
                if(alpineRoot && alpineRoot.__x){
                    alpineRoot.__x.$data.activeTab = first.tab;
                } else if(alpineRoot && window.Alpine){
                    alpineRoot.dispatchEvent(new CustomEvent('setActiveTab', {detail: first.tab}));
                }
            }catch(err){/* ignore */}

            // small timeout to allow tab switch then focus
            setTimeout(()=>{
                try { first.field.focus(); first.field.scrollIntoView({behavior:'smooth', block:'center'}); }catch(e){}
            },200);
            return false;
        }
    });
});
</script>
@endsection
