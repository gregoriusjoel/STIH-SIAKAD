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
    <div class="mb-8 border-b border-gray-200">
        <div class="flex overflow-x-auto no-scrollbar -mb-px">
            @foreach([
                'akademik' => 'Akademik',
                'data_pribadi' => 'Data Lanjutan',
                'orang_tua' => 'Orang Tua / Wali',
                'asal_sekolah' => 'Asal Sekolah'
            ] as $key => $label)
            <button @click="activeTab = '{{ $key }}'"
                class="whitespace-nowrap px-8 py-4 text-sm font-bold transition-all duration-200 border-b-2"
                :class="activeTab === '{{ $key }}' 
                    ? 'border-[#8B1538] text-[#8B1538]' 
                    : 'border-transparent text-[#9CA3AF] hover:text-[#1A1A1A] hover:border-gray-300'">
                {{ $label }}
            </button>
            @endforeach
        </div>
    </div>

    <form action="{{ route('mahasiswa.profil.update') }}" method="POST" enctype="multipart/form-data" class="animate-fade-in">
        @csrf
        @method('PUT')

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

        {{-- Tab Content: Data Lanjutan --}}
        <div x-show="activeTab === 'data_pribadi'" x-cloak class="space-y-12">
            {{-- Alamat Domisili --}}
            <div>
                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                    <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Alamat Domisili</h3>
                    <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Current Residence</span>
                </div>
                <div class="space-y-5">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-start">
                        <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider pt-3">Alamat Lengkap</label>
                        <div class="lg:col-span-9">
                            <textarea name="alamat" rows="3"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium resize-none">{{ $mahasiswa->alamat }}</textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">RT / RW</label>
                        <div class="lg:col-span-9 grid grid-cols-2 gap-4">
                            <input type="text" name="rt" value="{{ $mahasiswa->rt }}" placeholder="RT"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            <input type="text" name="rw" value="{{ $mahasiswa->rw }}" placeholder="RW"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Provinsi</label>
                        <div class="lg:col-span-9">
                            <select name="provinsi" id="provinsiSelect" 
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinces as $prov)
                                <option value="{{ $prov['name'] }}" data-code="{{ $prov['province_code'] }}" {{ ($mahasiswa->provinsi ?? '') === $prov['name'] ? 'selected' : '' }}>{{ $prov['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Kota/Kabupaten</label>
                        <div class="lg:col-span-9">
                            <select name="kota" id="kotaSelect" 
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                                <option value="">Pilih Kota/Kabupaten</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Desa/Kelurahan</label>
                        <div class="lg:col-span-9">
                            <select name="desa" id="desaSelect" 
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
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
                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                    <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Alamat Sesuai KTP</h3>
                    <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Identity Card Address</span>
                </div>
                <div class="space-y-5">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-start">
                        <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider pt-3">Alamat</label>
                        <div class="lg:col-span-9">
                            <textarea name="alamat_ktp" rows="3"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium resize-none">{{ $mahasiswa->alamat_ktp }}</textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">RT / RW</label>
                        <div class="lg:col-span-9 grid grid-cols-2 gap-4">
                            <input type="text" name="rt_ktp" value="{{ $mahasiswa->rt_ktp }}" placeholder="RT"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            <input type="text" name="rw_ktp" value="{{ $mahasiswa->rw_ktp }}" placeholder="RW"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Provinsi</label>
                        <div class="lg:col-span-9">
                            <select name="provinsi_ktp" id="provinsiKtpSelect" 
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinces as $prov)
                                <option value="{{ $prov['name'] }}" data-code="{{ $prov['province_code'] }}" {{ ($mahasiswa->provinsi_ktp ?? '') === $prov['name'] ? 'selected' : '' }}>{{ $prov['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Kota/Kabupaten</label>
                        <div class="lg:col-span-9">
                            <select name="kota_ktp" id="kotaKtpSelect" 
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                                <option value="">Pilih Kota/Kabupaten</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Desa</label>
                        <div class="lg:col-span-9">
                            <select name="desa_ktp" id="desaKtpSelect" 
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                <option value="">Pilih Desa</option>
                                @foreach($villages as $village)
                                <option value="{{ $village['name'] }}" {{ ($mahasiswa->desa_ktp ?? '') === $village['name'] ? 'selected' : '' }}>{{ $village['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Scripts for Address Dropdowns --}}
            <script>
                (function(){
                    const cities = @json($cities);
                    
                    function setupAddressGroup(provId, kotaId, initialKota) {
                        const provSelect = document.getElementById(provId);
                        const kotaSelect = document.getElementById(kotaId);

                        function populateCities(provinceCode) {
                            kotaSelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                            if (!provinceCode) {
                                kotaSelect.disabled = true;
                                return;
                            }
                            const filtered = cities.filter(c => c.province_code === provinceCode);
                            filtered.forEach(city => {
                                const opt = document.createElement('option');
                                opt.value = city.name;
                                opt.textContent = city.name;
                                if (city.name === initialKota) opt.selected = true;
                                kotaSelect.appendChild(opt);
                            });
                            kotaSelect.disabled = filtered.length === 0;
                        }

                        provSelect.addEventListener('change', function() {
                            const code = this.options[this.selectedIndex].dataset.code || '';
                            populateCities(code);
                        });

                        // Init
                        const opt = provSelect.options[provSelect.selectedIndex];
                        if (opt && opt.dataset.code) populateCities(opt.dataset.code);
                        else kotaSelect.disabled = true;
                    }

                    setupAddressGroup('provinsiSelect', 'kotaSelect', @json($mahasiswa->kota ?? ''));
                    setupAddressGroup('provinsiKtpSelect', 'kotaKtpSelect', @json($mahasiswa->kota_ktp ?? ''));
                })();
            </script>

            {{-- Data Pribadi --}}
            <div>
                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                    <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Data Pribadi</h3>
                    <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Personal Details</span>
                </div>
                <div class="space-y-5">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Tempat Lahir</label>
                        <div class="lg:col-span-9">
                            <input type="text" name="tempat_lahir" value="{{ $mahasiswa->tempat_lahir }}"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Tanggal Lahir</label>
                        <div class="lg:col-span-9">
                            <input type="date" name="tanggal_lahir" value="{{ $mahasiswa->tanggal_lahir ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('Y-m-d') : '' }}"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Jenis Kelamin</label>
                        <div class="lg:col-span-9">
                            <select name="jenis_kelamin"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-Laki" {{ $mahasiswa->jenis_kelamin === 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="Perempuan" {{ $mahasiswa->jenis_kelamin === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Agama</label>
                        <div class="lg:col-span-9">
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
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Status Sipil</label>
                        <div class="lg:col-span-9">
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
                            @endif
                        </div>
                        <div class="relative group">
                            <input type="file" name="{{ $doc['name'] }}[]" multiple
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
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Nama Ayah</label>
                            <div class="lg:col-span-9">
                                <input type="text" name="nama_ayah" value="{{ $parent->nama_ayah ?? '' }}"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Pendidikan Ayah</label>
                            <div class="lg:col-span-9">
                                <select name="pendidikan_ayah"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Pendidikan</option>
                                    @foreach(['Tidak Sekolah', 'Tamat SD', 'Tamat SMTP', 'Tamat SMTA', 'Diploma', 'Sarjana', 'Magister', 'Doktor'] as $p)
                                    <option value="{{ $p }}" {{ ($parent->pendidikan_ayah ?? '') === $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Pekerjaan Ayah</label>
                            <div class="lg:col-span-9">
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
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Agama Ayah</label>
                            <div class="lg:col-span-9">
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
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Nama Ibu</label>
                            <div class="lg:col-span-9">
                                <input type="text" name="nama_ibu" value="{{ $parent->nama_ibu ?? '' }}"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Pendidikan Ibu</label>
                            <div class="lg:col-span-9">
                                <select name="pendidikan_ibu"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Pendidikan</option>
                                    @foreach(['Tidak Sekolah', 'Tamat SD', 'Tamat SMTP', 'Tamat SMTA', 'Diploma', 'Sarjana', 'Magister', 'Doktor'] as $p)
                                    <option value="{{ $p }}" {{ ($parent->pendidikan_ibu ?? '') === $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Pekerjaan Ibu</label>
                            <div class="lg:col-span-9">
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
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Agama Ibu</label>
                            <div class="lg:col-span-9">
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

                {{-- Data Alamat Orang Tua --}}
                <div>
                    <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                        <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Alamat Orang Tua</h3>
                        <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Parent's Residence</span>
                    </div>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-start">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider pt-3">Alamat Lengkap</label>
                            <div class="lg:col-span-9">
                                <textarea name="alamat_ortu" rows="3"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium resize-none">{{ $parent->alamat_ortu ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Kota</label>
                            <div class="lg:col-span-9">
                                <input type="text" name="kota_ortu" value="{{ $parent->kota_ortu ?? '' }}"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Provinsi</label>
                            <div class="lg:col-span-9">
                                <input type="text" name="provinsi_ortu" value="{{ $parent->provinsi_ortu ?? '' }}"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Kab/Desa</label>
                            <div class="lg:col-span-9">
                                <input type="text" name="kabdesa_ortu" value="{{ $parent->kabupaten_ortu ?? '' }}"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Handphone Ortu</label>
                            <div class="lg:col-span-9">
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-[#8B1538] transition-colors">
                                        <span class="text-sm font-bold opacity-50">+62</span>
                                    </div>
                                    <input type="text" name="handphone_ortu" value="{{ $parent->handphone_ortu ?? '' }}" maxlength="13" inputmode="numeric" pattern="^[0-9]{1,13}$" oninput="this.value = this.value.replace(/\D/g,'')"
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
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Nama Wali</label>
                            <div class="lg:col-span-9">
                                <input type="text" name="nama_wali" value="{{ $parent->nama_wali ?? '' }}"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Hubungan</label>
                            <div class="lg:col-span-9">
                                <select name="hubungan_wali"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Hubungan</option>
                                    @foreach(['Kakek', 'Nenek', 'Paman', 'Bibi', 'Saudara Lainnya', 'Lainnya'] as $h)
                                    <option value="{{ $h }}" {{ ($parent->hubungan_wali ?? '') === $h ? 'selected' : '' }}>{{ $h }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Pendidikan</label>
                            <div class="lg:col-span-9">
                                <select name="pendidikan_wali"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Pendidikan</option>
                                    @foreach(['Tidak Sekolah', 'Tamat SD', 'Tamat SMTP', 'Tamat SMTA', 'Diploma', 'Sarjana', 'Magister', 'Doktor'] as $p)
                                    <option value="{{ $p }}" {{ ($parent->pendidikan_wali ?? '') === $p ? 'selected' : '' }}>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Pekerjaan</label>
                            <div class="lg:col-span-9">
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
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Agama</label>
                            <div class="lg:col-span-9">
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
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-start">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider pt-3">Alamat Lengkap</label>
                            <div class="lg:col-span-9">
                                <textarea name="alamat_wali" rows="3"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium resize-none">{{ $parent->alamat_wali ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Kota</label>
                            <div class="lg:col-span-9">
                                <input type="text" name="kota_wali" value="{{ $parent->kota_wali ?? '' }}"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Provinsi</label>
                            <div class="lg:col-span-9">
                                <input type="text" name="provinsi_wali" value="{{ $parent->provinsi_wali ?? '' }}"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Negara</label>
                            <div class="lg:col-span-9">
                                <input type="text" name="negara_wali" value="{{ $parent->negara_wali ?? '' }}"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                            <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Handphone Wali</label>
                            <div class="lg:col-span-9">
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
        </div>

        {{-- Tab: Asal Sekolah --}}
        <div x-show="activeTab === 'asal_sekolah'" x-cloak class="space-y-12 animate-fade-in">
            <div>
                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                    <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Data Asal Sekolah</h3>
                    <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Previous Education</span>
                </div>
                <div class="space-y-5">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Jenis Sekolah</label>
                        <div class="lg:col-span-9">
                            <select name="jenis_sekolah"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                <option value="">Pilih Jenis Sekolah</option>
                                <option value="1 - Umum" {{ $mahasiswa->jenis_sekolah === '1 - Umum' ? 'selected' : '' }}>1 - Umum</option>
                                <option value="2 - Kejuruan" {{ $mahasiswa->jenis_sekolah === '2 - Kejuruan' ? 'selected' : '' }}>2 - Kejuruan</option>
                                <option value="3 - Umum" {{ $mahasiswa->jenis_sekolah === '3 - Umum' ? 'selected' : '' }}>3 - Umum</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Jurusan</label>
                        <div class="lg:col-span-9">
                            <select name="jurusan_sekolah"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                <option value="">Pilih Jurusan</option>
                                @foreach(['SMU - IPA', 'SMU - IPS', 'SMU - Bahasa', 'SMK - Teknik Informatika', 'SMK - Teknik Mesin', 'SMK - Akuntansi', 'SMK - Lainnya'] as $j)
                                <option value="{{ $j }}" {{ $mahasiswa->jurusan_sekolah === $j ? 'selected' : '' }}>{{ $j }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Tahun Lulus</label>
                        <div class="lg:col-span-9">
                            <input type="text" name="tahun_lulus" value="{{ $mahasiswa->tahun_lulus ?? '' }}" placeholder="Contoh: 2020"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-y-2 gap-x-6 items-center">
                        <label class="lg:col-span-3 text-xs font-bold text-[#6B7280] uppercase tracking-wider">Nilai Kelulusan</label>
                        <div class="lg:col-span-9">
                            <input type="number" name="nilai_kelulusan" value="{{ $mahasiswa->nilai_kelulusan ?? '' }}" step="0.01" min="0" max="100" placeholder="Contoh: 81.56"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
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
            <div class="flex items-center gap-3 px-8 py-3 bg-gray-50 text-[#9CA3AF] rounded-xl border border-gray-100 cursor-not-allowed">
                <i class="fas fa-lock"></i>
                <span class="text-xs font-bold uppercase tracking-wider">Data Terkunci</span>
            </div>
            @endif
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
@endsection