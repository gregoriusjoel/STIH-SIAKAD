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
            Orang Tua
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
                <h3 class="text-gray-800 font-medium mb-6 pb-2 border-b border-gray-100">Data Pribadi</h3>
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

                    {{-- Negara (dropdown) --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Negara</label>
                        <div class="lg:col-span-9">
                            <select name="negara" id="negaraSelect" class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                <option value="">Pilih Negara</option>
                                @foreach(($countries ?? []) as $country)
                                    <option value="{{ $country->name }}" {{ $mahasiswa->negara === $country->name ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Provinsi (dropdown, populated based on negara) --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Provinsi</label>
                        <div class="lg:col-span-9">
                            <select name="provinsi" id="provinsiSelect" class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                <option value="">Pilih Provinsi</option>
                            </select>
                        </div>
                    </div>

                    {{-- Kota (dropdown, populated based on provinsi) --}}
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Kota</label>
                        <div class="lg:col-span-9">
                            <select name="kota" id="kotaSelect" class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-sm focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 shadow-sm transition-shadow">
                                <option value="">Pilih Kota</option>
                            </select>
                        </div>
                    </div>

                    <script>
                        (function(){
                            const locations = @json($countries ?? []);
                            console.debug('locations count', Array.isArray(locations) ? locations.length : 0);
                            console.debug('country names', Array.isArray(locations) ? locations.map(c => c.name).slice(0,10) : locations);
                            const negaraSelect = document.getElementById('negaraSelect');
                            const provinsiSelect = document.getElementById('provinsiSelect');
                            const kotaSelect = document.getElementById('kotaSelect');
                            const selectedNegara = {!! json_encode($mahasiswa->negara) !!};
                            const selectedProvinsi = {!! json_encode($mahasiswa->provinsi) !!};
                            const selectedKota = {!! json_encode($mahasiswa->kota) !!};

                            function clearSelect(sel, label = 'Pilih'){
                                sel.innerHTML = `<option value="">${label}</option>`;
                            }

                            function populateProvinces(countryName){
                                console.debug('populateProvinces called with', countryName);
                                clearSelect(provinsiSelect, 'Pilih Provinsi');
                                clearSelect(kotaSelect, 'Pilih Kota');
                                provinsiSelect.disabled = true;
                                kotaSelect.disabled = true;

                                if(!countryName) return;

                                const isIndonesia = String(countryName).trim().toLowerCase() === 'indonesia';
                                console.debug('isIndonesia?', isIndonesia);
                                if(!isIndonesia) {
                                    // only show provinces/cities when Indonesia is selected
                                    return;
                                }

                                const c = locations.find(x => String(x.name).trim().toLowerCase() === 'indonesia');
                                console.debug('found indonesia object?', !!c, c && c.provinces ? c.provinces.length : 0);
                                if(!c || !c.provinces) return;

                                c.provinces.forEach(p => {
                                    const opt = document.createElement('option');
                                    const provName = p.province || p.name || p.province;
                                    opt.value = provName;
                                    opt.textContent = provName;
                                    if(opt.value === selectedProvinsi) opt.selected = true;
                                    provinsiSelect.appendChild(opt);
                                });

                                provinsiSelect.disabled = false;

                                // if a province selected, populate cities
                                if(provinsiSelect.value) populateCities(countryName, provinsiSelect.value);
                            }

                            function populateCities(countryName, provinceName){
                                clearSelect(kotaSelect, 'Pilih Kota');
                                kotaSelect.disabled = true;
                                if(!countryName || !provinceName) return;

                                const isIndonesia = String(countryName).trim().toLowerCase() === 'indonesia';
                                if(!isIndonesia) return;

                                const c = locations.find(x => String(x.name).trim().toLowerCase() === 'indonesia');
                                if(!c || !c.provinces) return;
                                const p = c.provinces.find(pp => (pp.province || pp.name) === provinceName || pp.province === provinceName || String(pp.province).trim() === String(provinceName).trim());
                                if(!p || !p.cities) return;
                                p.cities.forEach(ci => {
                                    const opt = document.createElement('option');
                                    const cityName = ci.city || ci.name || ci.city;
                                    opt.value = cityName;
                                    opt.textContent = cityName;
                                    if(opt.value === selectedKota) opt.selected = true;
                                    kotaSelect.appendChild(opt);
                                });
                                kotaSelect.disabled = false;
                            }

                            negaraSelect.addEventListener('change', function(){
                                populateProvinces(this.value);
                            });

                            provinsiSelect.addEventListener('change', function(){
                                populateCities(negaraSelect.value, this.value);
                            });

                            // Initialize selects on page load
                            // disable provinsi/kota by default
                            provinsiSelect.disabled = true;
                            kotaSelect.disabled = true;

                            if(selectedNegara){
                                negaraSelect.value = selectedNegara;
                                populateProvinces(selectedNegara);
                            }
                        })();
                    </script>

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
                                <option value="Islam" {{ $mahasiswa->agama === 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ $mahasiswa->agama === 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ $mahasiswa->agama === 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ $mahasiswa->agama === 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ $mahasiswa->agama === 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ $mahasiswa->agama === 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
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
        </div>
        {{-- Tab: Orang Tua --}}
        <div x-show="activeTab === 'orang_tua'" x-cloak class="space-y-10 animate-fade-in">
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
                                <option value="Islam" {{ ($parent->agama_ayah ?? '') === 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ ($parent->agama_ayah ?? '') === 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ ($parent->agama_ayah ?? '') === 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ ($parent->agama_ayah ?? '') === 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ ($parent->agama_ayah ?? '') === 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ ($parent->agama_ayah ?? '') === 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
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
                                <option value="Islam" {{ ($parent->agama_ibu ?? '') === 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen" {{ ($parent->agama_ibu ?? '') === 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="Katolik" {{ ($parent->agama_ibu ?? '') === 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="Hindu" {{ ($parent->agama_ibu ?? '') === 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ ($parent->agama_ibu ?? '') === 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ ($parent->agama_ibu ?? '') === 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
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
                        <label class="lg:col-span-3 text-sm text-gray-600 font-medium">Negara</label>
                        <div class="lg:col-span-9">
                            <input type="text" name="negara_ortu" value="{{ $parent->negara_ortu ?? '' }}" 
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
@endsection
