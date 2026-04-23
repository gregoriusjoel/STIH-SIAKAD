<?php $__env->startSection('title', 'Profile Mahasiswa'); ?>
<?php $__env->startSection('page-title', 'Profile Mahasiswa'); ?>

<?php $__env->startSection('content'); ?>
<?php
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
?>


<?php if(session('warning')): ?>
<div class="bg-orange-50 border-l-4 border-orange-500 p-4 mb-6 rounded-md">
    <div class="flex items-start gap-3">
        <i class="fas fa-exclamation-circle text-orange-600 mt-0.5"></i>
        <div class="flex-1">
            <p class="text-sm text-orange-800 font-medium"><?php echo e(session('warning')); ?></p>
            <?php if($highlightMissing && count($missingFields) > 0): ?>
            <div class="mt-3 p-3 bg-white/50 rounded-lg border border-orange-200">
                <p class="text-xs font-bold text-orange-700 uppercase mb-2">Data yang belum lengkap:</p>
                <div class="flex flex-wrap gap-2">
                    <?php $__currentLoopData = $missingFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full">
                        <i class="fas fa-times-circle"></i>
                        <?php echo e($info['label']); ?>

                    </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if($isLocked): ?>
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-md">
    <div class="flex items-center">
        <i class="fas fa-check-circle text-green-600 mr-3"></i>
        <p class="text-sm text-green-800 font-medium">Data profil Anda sudah lengkap. Anda masih dapat memperbarui data profil jika diperlukan.</p>
    </div>
</div>
<?php else: ?>

<div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-md">
    <div class="flex items-center">
        <i class="fas fa-info-circle text-blue-600 mr-3"></i>
        <p class="text-sm text-blue-800 font-medium">Lengkapi semua data profil (Akademik, Data Pribadi, Orang Tua, dan Asal Sekolah) untuk mengakses fitur lainnya.</p>
    </div>
</div>
<?php endif; ?>


<script>
    window.villagesData = <?php echo json_encode(collect($villages)->map(fn($v) => ['value' => $v['name'], 'text' => $v['name'], 'district_code' => $v['district_code']])->toArray()) ?>;
</script>

<div class="bg-white rounded-lg shadow-sm p-8" x-data="{ 
    activeTab: '<?php echo e($highlightMissing && count($missingFields) > 0 ? (collect($missingFields)->first()['tab'] ?? 'akademik') : 'akademik'); ?>', 
    photoPreview: '<?php echo e($mahasiswa->foto_url ?? ""); ?>' 
}">

    
    <div class="mb-8 border-b border-gray-200">
        <div class="flex overflow-x-auto no-scrollbar -mb-px">
            <?php $__currentLoopData = [
            'akademik' => 'Akademik',
            'data_pribadi' => 'Data Lanjutan',
            'orang_tua' => 'Orang Tua / Wali'
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button @click="activeTab = '<?php echo e($key); ?>'" type="button"
                class="whitespace-nowrap px-8 py-4 text-sm font-bold transition-all duration-200 border-b-2 relative"
                :class="activeTab === '<?php echo e($key); ?>' 
                    ? 'border-[#8B1538] text-[#8B1538]' 
                    : 'border-transparent text-[#9CA3AF] hover:text-[#1A1A1A] hover:border-gray-300'">
                <?php echo e($label); ?>

                <?php if($highlightMissing && $missingByTab[$key] > 0): ?>
                <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full">
                    <?php echo e($missingByTab[$key]); ?>

                </span>
                <?php endif; ?>
            </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <form action="<?php echo e(route('mahasiswa.profil.update')); ?>" method="POST" enctype="multipart/form-data" class="animate-fade-in">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div x-show="activeTab === 'akademik'" x-cloak class="space-y-12">
            <div>
                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                    <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Data Akademik</h3>
                    <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Academic Info</span>
                </div>
                <div class="space-y-8">


                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                        
                        <div class="space-y-4">
                            
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

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Upload Foto</label>
                                <div class="flex items-center gap-3">
                                    <label class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm text-gray-700 cursor-pointer hover:bg-gray-50 transition shadow-sm font-medium">
                                        Choose File
                                        <input type="file" name="foto" class="hidden" accept=".jpg,.jpeg,.png,image/jpeg,image/png" 
                                            @change="const file = $event.target.files[0]; 
                                            if(file) { 
                                                if(file.size > 2 * 1024 * 1024) { 
                                                    Swal.fire({
                                                        icon: 'warning',
                                                        title: 'Ukuran Foto Terlalu Besar!',
                                                        html: 'Batas maksimal ukuran foto adalah <strong>2MB</strong>.<br><br>File Anda (' + (file.size / (1024 * 1024)).toFixed(2) + 'MB) terlalu besar.',
                                                        confirmButtonColor: '#8B1538',
                                                        confirmButtonText: 'OK'
                                                    });
                                                    $event.target.value = '';
                                                    return;
                                                }
                                                const reader = new FileReader(); 
                                                reader.onload = (e) => photoPreview = e.target.result; 
                                                reader.readAsDataURL(file); 
                                            }">
                                    </label>
                                    <span class="text-xs text-gray-500 italic">Format: JPG/PNG, Max: 2MB</span>
                                </div>
                            </div>
                        </div>

                        
                        <div class="space-y-4">
                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="<?php echo e($user->name); ?>"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium"
                                    oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '').replace(/\b\w/g, char => char.toUpperCase())">
                                <p class="mt-1.5 text-[11px] text-gray-400 font-medium italic">Hanya huruf dan spasi (angka & karakter khusus otomatis hilang)</p>
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Email Kampus <span class="text-red-500">*</span></label>
                                <input type="email" name="email_kampus_display" value="<?php echo e($mahasiswa->email_kampus); ?>"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm bg-gray-50 text-gray-500 cursor-not-allowed"
                                    readonly>
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Email Pribadi</label>
                                <input type="email" name="email_pribadi" value="<?php echo e($mahasiswa->email_pribadi); ?>"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium"
                                    placeholder="email@gmail.com">
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">No. HP <span class="text-red-500">*</span></label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-[#8B1538] transition-colors">
                                        <span class="text-sm font-bold opacity-50">+62</span>
                                    </div>
                                    <input type="text" name="no_hp" value="<?php echo e($mahasiswa->no_hp); ?>" minlength="11" maxlength="13" inputmode="numeric" pattern="^[0-9]{11,13}$" oninput="this.value = this.value.replace(/\D/g,'')" onblur="if(this.value.length > 0 && this.value.length < 11) { alert('Nomor tidak valid! Minimal 11 angka.'); }"
                                        class="w-full pl-14 pr-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium" placeholder="81xxxxxxxxx">
                                </div>
                            </div>
                        </div>

                        
                        <?php $__currentLoopData = [
                        'NIM' => $mahasiswa->nim,
                        'Jurusan' => $mahasiswa->prodi,
                        'Program' => '1 - REGULER',
                        'Kurikulum' => '32 - Kurikulum ' . $mahasiswa->prodi . ' ' . $mahasiswa->angkatan,
                        'Angkatan' => $mahasiswa->angkatan,
                        'Penasehat Akademik' => 'Dosen PA',
                        'Status Awal' => 'B - Baru',
                        'Status Mahasiswa' => 'A - Aktif'
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <label class="block text-sm text-gray-600 font-medium mb-2"><?php echo e($label); ?></label>
                            <div class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-md text-gray-500 text-sm flex justify-between items-center shadow-sm cursor-default">
                                <span><?php echo e($value ?? '-'); ?></span>
                                <i class="fas fa-lock text-gray-300 text-xs"></i>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>

        
        <div x-show="activeTab === 'data_pribadi'" x-cloak class="space-y-12">
            
            <div>
                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                    <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Alamat Domisili</h3>
                    <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Current Residence</span>
                </div>
                <div class="space-y-5">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium resize-none"><?php echo e($mahasiswa->alamat); ?></textarea>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">RT / RW</label>
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="rt" value="<?php echo e($mahasiswa->rt); ?>" placeholder="RT"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                                <input type="text" name="rw" value="<?php echo e($mahasiswa->rw); ?>" placeholder="RW"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            </div>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">Provinsi</label>
                            <select name="provinsi" id="provinsiSelect"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                <option value="">Pilih Provinsi</option>
                                <?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($prov['name']); ?>" data-code="<?php echo e($prov['province_code']); ?>" <?php echo e(($mahasiswa->provinsi ?? '') === $prov['name'] ? 'selected' : ''); ?>><?php echo e($prov['name']); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">Kota/Kabupaten</label>
                            <select name="kota" id="kotaSelect"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                                <option value="">Pilih Kota/Kabupaten</option>
                            </select>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">Kecamatan</label>
                            <select name="kecamatan" id="kecamatanSelect"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>

                        
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
                                        selected: <?php echo json_encode($mahasiswa->desa ?? '', 15, 512) ?>,
                                        selectedText: <?php echo json_encode($mahasiswa->desa ?? 'Pilih Desa', 15, 512) ?>,
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
                                                if (input) {
                                                    input.dispatchEvent(new Event('input', {
                                                        bubbles: true
                                                    }));
                                                    input.dispatchEvent(new Event('change', {
                                                        bubbles: true
                                                    }));
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

            
            <div>
                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                    <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Alamat Sesuai KTP</h3>
                    <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Identity Card Address</span>
                    <button type="button" id="copyDomisiliToKtp"
                        class="ml-auto inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#8B1538]/10 hover:bg-[#8B1538]/20 text-[#8B1538] text-[11px] font-bold rounded-lg transition-all duration-200 border border-[#8B1538]/20 hover:border-[#8B1538]/30 hover:shadow-sm active:scale-95">
                        <i class="fas fa-copy text-[10px]"></i>
                        Salin dari Alamat Domisili
                    </button>
                </div>
                <script>
                    document.getElementById('copyDomisiliToKtp').addEventListener('click', function() {
                        const btn = this;
                        const origHTML = btn.innerHTML;

                        // 1. Copy alamat textarea
                        const alamatDom = document.querySelector('textarea[name="alamat"]');
                        const alamatKtp = document.querySelector('textarea[name="alamat_ktp"]');
                        if (alamatDom && alamatKtp) alamatKtp.value = alamatDom.value;

                        // 2. Copy RT / RW
                        const rtDom = document.querySelector('input[name="rt"]');
                        const rwDom = document.querySelector('input[name="rw"]');
                        const rtKtp = document.querySelector('input[name="rt_ktp"]');
                        const rwKtp = document.querySelector('input[name="rw_ktp"]');
                        if (rtDom && rtKtp) rtKtp.value = rtDom.value;
                        if (rwDom && rwKtp) rwKtp.value = rwDom.value;

                        // 3. Copy Provinsi and trigger cascade
                        const provDom = document.getElementById('provinsiSelect');
                        const provKtp = document.getElementById('provinsiKtpSelect');
                        if (provDom && provKtp) {
                            provKtp.value = provDom.value;
                            provKtp.dispatchEvent(new Event('change'));
                        }

                        // 4. After provinsi populates kota, copy kota
                        const kotaDomValue = document.getElementById('kotaSelect')?.value || '';
                        setTimeout(function() {
                            const kotaKtp = document.getElementById('kotaKtpSelect');
                            if (kotaKtp) {
                                // Find and select the matching option
                                for (let i = 0; i < kotaKtp.options.length; i++) {
                                    if (kotaKtp.options[i].value === kotaDomValue) {
                                        kotaKtp.selectedIndex = i;
                                        break;
                                    }
                                }
                                kotaKtp.dispatchEvent(new Event('change'));
                            }

                            // 5. After kota populates kecamatan, copy kecamatan
                            const kecDomValue = document.getElementById('kecamatanSelect')?.value || '';
                            setTimeout(function() {
                                const kecKtp = document.getElementById('kecamatanKtpSelect');
                                if (kecKtp) {
                                    for (let i = 0; i < kecKtp.options.length; i++) {
                                        if (kecKtp.options[i].value === kecDomValue) {
                                            kecKtp.selectedIndex = i;
                                            break;
                                        }
                                    }
                                    kecKtp.dispatchEvent(new Event('change'));
                                }

                                // 6. After kecamatan set, copy desa via Alpine component
                                const desaDomInput = document.querySelector('input[name="desa"]');
                                const desaDomValue = desaDomInput ? desaDomInput.value : '';
                                setTimeout(function() {
                                    // Find the Alpine component for desa KTP
                                    const desaKtpInput = document.querySelector('input[name="desa_ktp"]');
                                    if (desaKtpInput && desaDomValue) {
                                        const alpineEl = desaKtpInput.closest('[x-data]');
                                        if (alpineEl && alpineEl.__x) {
                                            alpineEl.__x.$data.selected = desaDomValue;
                                            alpineEl.__x.$data.selectedText = desaDomValue;
                                        } else if (alpineEl && typeof Alpine !== 'undefined') {
                                            Alpine.evaluate(alpineEl, `selected = '${desaDomValue.replace(/'/g, "\\'")}'`);
                                            Alpine.evaluate(alpineEl, `selectedText = '${desaDomValue.replace(/'/g, "\\'")}'`);
                                        }
                                        desaKtpInput.value = desaDomValue;
                                        desaKtpInput.dispatchEvent(new Event('input', { bubbles: true }));
                                    }
                                }, 150);
                            }, 100);
                        }, 100);

                        // Button feedback animation
                        btn.innerHTML = '<i class="fas fa-check text-[10px]"></i> Berhasil Disalin';
                        btn.classList.add('bg-green-100', 'text-green-700', 'border-green-300');
                        btn.classList.remove('bg-[#8B1538]/10', 'text-[#8B1538]', 'border-[#8B1538]/20');
                        setTimeout(function() {
                            btn.innerHTML = origHTML;
                            btn.classList.remove('bg-green-100', 'text-green-700', 'border-green-300');
                            btn.classList.add('bg-[#8B1538]/10', 'text-[#8B1538]', 'border-[#8B1538]/20');
                        }, 2000);
                    });
                </script>
                <div class="space-y-5">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">Alamat Lengkap</label>
                            <textarea name="alamat_ktp" rows="3"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium resize-none"><?php echo e($mahasiswa->alamat_ktp); ?></textarea>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">RT / RW</label>
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="rt_ktp" value="<?php echo e($mahasiswa->rt_ktp); ?>" placeholder="RT"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                                <input type="text" name="rw_ktp" value="<?php echo e($mahasiswa->rw_ktp); ?>" placeholder="RW"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            </div>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">Provinsi</label>
                            <select name="provinsi_ktp" id="provinsiKtpSelect"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                <option value="">Pilih Provinsi</option>
                                <?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($prov['name']); ?>" data-code="<?php echo e($prov['province_code']); ?>" <?php echo e(($mahasiswa->provinsi_ktp ?? '') === $prov['name'] ? 'selected' : ''); ?>><?php echo e($prov['name']); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">Kota/Kabupaten</label>
                            <select name="kota_ktp" id="kotaKtpSelect"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                                <option value="">Pilih Kota/Kabupaten</option>
                            </select>
                        </div>

                        
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider">Kecamatan</label>
                            <select name="kecamatan_ktp" id="kecamatanKtpSelect"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>

                        
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
                                        selected: <?php echo json_encode($mahasiswa->desa_ktp ?? '', 15, 512) ?>,
                                        selectedText: <?php echo json_encode($mahasiswa->desa_ktp ?? 'Pilih Desa', 15, 512) ?>,
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
                                                if (input) {
                                                    input.dispatchEvent(new Event('input', {
                                                        bubbles: true
                                                    }));
                                                    input.dispatchEvent(new Event('change', {
                                                        bubbles: true
                                                    }));
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

            
            <script>
                (function() {
                    const cities = <?php echo json_encode($cities, 15, 512) ?>;
                    const districts = <?php echo json_encode($districts, 15, 512) ?>;

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

                    setupAddressGroup('provinsiSelect', 'kotaSelect', 'kecamatanSelect', <?php echo json_encode($mahasiswa->kota ?? '', 15, 512) ?>, <?php echo json_encode($mahasiswa->kecamatan ?? '', 15, 512) ?>);
                    setupAddressGroup('provinsiKtpSelect', 'kotaKtpSelect', 'kecamatanKtpSelect', <?php echo json_encode($mahasiswa->kota_ktp ?? '', 15, 512) ?>, <?php echo json_encode($mahasiswa->kecamatan_ktp ?? '', 15, 512) ?>);

                    // Setup for Orang Tua and Wali sections (delayed to ensure elements exist)
                    document.addEventListener('DOMContentLoaded', function() {
                        // Small delay to ensure Alpine.js has rendered the elements
                        setTimeout(function() {
                            setupAddressGroup('provinsiAyahSelect', 'kotaAyahSelect', 'kecamatanAyahSelect', <?php echo json_encode($parent->kota_ayah ?? '', 15, 512) ?>, <?php echo json_encode($parent->kecamatan_ayah ?? '', 15, 512) ?>);
                            setupAddressGroup('provinsiIbuSelect', 'kotaIbuSelect', 'kecamatanIbuSelect', <?php echo json_encode($parent->kota_ibu ?? '', 15, 512) ?>, <?php echo json_encode($parent->kecamatan_ibu ?? '', 15, 512) ?>);
                            setupAddressGroup('provinsiWaliSelect', 'kotaWaliSelect', 'kecamatanWaliSelect', <?php echo json_encode($parent->kota_wali ?? '', 15, 512) ?>, <?php echo json_encode($parent->kecamatan_wali ?? '', 15, 512) ?>);
                        }, 100);
                    });
                })();
            </script>

            
            <div>
                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                    <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Data Pribadi</h3>
                    <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Personal Details</span>
                </div>
                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        
                        <div>
                            <label class="block text-sm text-gray-600 font-medium mb-2">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="<?php echo e($mahasiswa->tempat_lahir); ?>"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                        </div>

                        
                        <div>
                            <label class="block text-sm text-gray-600 font-medium mb-2">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="<?php echo e($mahasiswa->tanggal_lahir ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('Y-m-d') : ''); ?>" max="9999-12-31"
                                oninput="if(this.value && this.value.split('-')[0].length > 4) { const parts = this.value.split('-'); parts[0] = parts[0].slice(0, 4); this.value = parts.join('-'); }"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                        </div>

                        
                        <div>
                            <label class="block text-sm text-gray-600 font-medium mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="jenis_kelamin" required
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-Laki" <?php echo e($mahasiswa->jenis_kelamin === 'Laki-Laki' ? 'selected' : ''); ?>>Laki-Laki</option>
                                <option value="Perempuan" <?php echo e($mahasiswa->jenis_kelamin === 'Perempuan' ? 'selected' : ''); ?>>Perempuan</option>
                            </select>
                        </div>

                        
                        <div>
                            <label class="block text-sm text-gray-600 font-medium mb-2">Agama</label>
                            <select name="agama"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                <option value="">Pilih Agama</option>
                                <?php if(isset($religions) && $religions->count()): ?>
                                <?php $__currentLoopData = $religions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($rel->name); ?>" <?php echo e($mahasiswa->agama === $rel->name ? 'selected' : ''); ?>><?php echo e($rel->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        
                        <div>
                            <label class="block text-sm text-gray-600 font-medium mb-2">Status Sipil</label>
                            <select name="status_sipil"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                <option value="">Pilih Status Sipil</option>
                                <option value="Belum Menikah" <?php echo e($mahasiswa->status_sipil === 'Belum Menikah' ? 'selected' : ''); ?>>Belum Menikah</option>
                                <option value="Menikah" <?php echo e($mahasiswa->status_sipil === 'Menikah' ? 'selected' : ''); ?>>Menikah</option>
                                <option value="Cerai" <?php echo e($mahasiswa->status_sipil === 'Cerai' ? 'selected' : ''); ?>>Cerai</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            
            <?php
                $allDocsUploadedAndLocked = 
                    !empty($mahasiswa->file_ijazah) && !collect($mahasiswa->file_ijazah)->filter()->isEmpty() &&
                    !empty($mahasiswa->file_transkrip) && !collect($mahasiswa->file_transkrip)->filter()->isEmpty() &&
                    !empty($mahasiswa->file_kk) && !collect($mahasiswa->file_kk)->filter()->isEmpty() &&
                    !empty($mahasiswa->file_ktp) && !collect($mahasiswa->file_ktp)->filter()->isEmpty() &&
                    !$mahasiswa->is_dokumen_unlocked;
            ?>
            <div>
                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                    <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Dokumen Pribadi</h3>
                    <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Identity Documents</span>
                </div>
                
                <?php if($allDocsUploadedAndLocked): ?>
                <div class="p-4 bg-green-50 border border-green-100 rounded-xl mb-6 flex items-start gap-3">
                    <i class="fas fa-check-circle text-green-600 text-lg mt-0.5"></i>
                    <div>
                        <p class="text-sm font-bold text-green-800">Dokumen Lengkap & Terverifikasi</p>
                        <p class="text-xs text-green-700 mt-1">Semua dokumen pribadi Anda telah diunggah dan tersimpan dalam sistem. Jika Anda perlu memperbarui dokumen, silakan hubungi Akademik.</p>
                    </div>
                </div>
                <?php else: ?>
                <div class="p-4 bg-orange-50 border border-orange-100 rounded-xl mb-6">
                    <p class="text-[11px] text-[#C2410C] font-semibold flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        Upload dokumen dalam format PDF atau JPEG/PNG (Maksimal 5MB per file).
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <?php $__currentLoopData = [
                    ['name' => 'file_ijazah', 'label' => 'Ijazah', 'data' => $mahasiswa->file_ijazah],
                    ['name' => 'file_transkrip', 'label' => 'Transkrip Nilai', 'data' => $mahasiswa->file_transkrip],
                    ['name' => 'file_kk', 'label' => 'Kartu Keluarga (KK)', 'data' => $mahasiswa->file_kk],
                    ['name' => 'file_ktp', 'label' => 'KTP', 'data' => $mahasiswa->file_ktp],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(empty($doc['data']) || collect($doc['data'])->filter()->isEmpty() || $mahasiswa->is_dokumen_unlocked): ?>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-[#6B7280] uppercase tracking-wider"><?php echo e($doc['label']); ?></label>
                            <?php if($doc['data'] && count($doc['data']) > 0): ?>
                            <span class="text-[10px] font-bold text-green-600 uppercase flex items-center gap-1">
                                <i class="fas fa-check-circle"></i> Terupload
                            </span>
                            <?php else: ?>
                            <span class="text-[10px] font-bold text-red-500 uppercase flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i> Belum Upload
                            </span>
                            <?php endif; ?>
                        </div>
                        <div class="relative group">
                            <input type="file" name="<?php echo e($doc['name']); ?>[]" multiple
                                accept=".pdf,.jpeg,.jpg,.png,application/pdf,image/jpeg,image/png"
                                onchange="validateDocumentFiles(this)"
                                class="w-full px-4 py-3 bg-[#F9FAFB] border border-[#E5E7EB] border-dashed rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-[#8B1538] file:text-white hover:file:bg-[#6D1029]">
                        </div>
                        <?php if($doc['data'] && count($doc['data']) > 0): ?>
                        <div class="flex flex-wrap gap-2">
                            <?php $__currentLoopData = $doc['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            $iconClass = in_array($ext, ['jpg','jpeg','png']) ? 'fa-file-image text-blue-600' : 'fa-file-pdf text-[#8B1538]';
                            ?>
                            <button type="button"
                                onclick="openFilePreview('<?php echo e(\Illuminate\Support\Facades\Storage::disk('s3')->url($file)); ?>', 'Preview <?php echo e($doc['label']); ?>', '<?php echo e($ext); ?>')"
                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-[#F3F4F6] hover:bg-[#E5E7EB] text-[#4B5563] text-[11px] font-bold rounded-lg transition-colors border border-[#E5E7EB] cursor-pointer">
                                <i class="fas <?php echo e($iconClass); ?>"></i>
                                <?php echo e(Str::limit(basename($file), 20)); ?>

                            </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            </div>

            
            <div>
                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                    <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Data Asal Sekolah</h3>
                    <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Previous Education</span>
                </div>
                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        
                        <div>
                            <label class="block text-sm text-gray-600 font-medium mb-2">Jenis Sekolah</label>
                            <select name="jenis_sekolah"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                <option value="">Pilih Jenis Sekolah</option>
                                <option value="1 - Umum" <?php echo e($mahasiswa->jenis_sekolah === '1 - Umum' ? 'selected' : ''); ?>>1 - Umum</option>
                                <option value="2 - Kejuruan" <?php echo e($mahasiswa->jenis_sekolah === '2 - Kejuruan' ? 'selected' : ''); ?>>2 - Kejuruan</option>
                            </select>
                        </div>

                        
                        <div>
                            <label class="block text-sm text-gray-600 font-medium mb-2">Jurusan</label>
                            <select name="jurusan_sekolah"
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                <option value="">Pilih Jurusan</option>
                                <?php $__currentLoopData = ['SMA', 'SMK', 'MA']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($j); ?>" <?php echo e($mahasiswa->jurusan_sekolah === $j ? 'selected' : ''); ?>><?php echo e($j); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        
                        <div>
                            <label class="block text-sm text-gray-600 font-medium mb-2">Tahun Lulus</label>
                            <select name="tahun_lulus" 
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                <option value="">Pilih Tahun</option>
                                <?php $currentYear = date('Y'); ?>
                                <?php for($year = $currentYear; $year >= 1970; $year--): ?>
                                    <option value="<?php echo e($year); ?>" <?php echo e(($mahasiswa->tahun_lulus ?? '') == $year ? 'selected' : ''); ?>>
                                        <?php echo e($year); ?>

                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        
                        <div>
                            <label class="block text-sm text-gray-600 font-medium mb-2">Nilai Kelulusan</label>
                            <input type="number" name="nilai_kelulusan" value="<?php echo e($mahasiswa->nilai_kelulusan ?? ''); ?>" step="0.01" min="0" max="100" placeholder="Contoh: 81,56"
                                oninput="
                                    if (this.value > 100) this.value = 100;
                                    if (this.value < 0) this.value = 0;
                                    let parts = this.value.toString().split('.');
                                    if (parts[1] && parts[1].length > 2) this.value = parts[0] + '.' + parts[1].substring(0, 2);
                                "
                                class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium text-gray-700">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div x-show="activeTab === 'orang_tua'" x-cloak class="space-y-12"
            x-data="{ 
                showOrangTua: <?php echo e((empty($parent) || empty($parent->nama_wali) || !empty($parent->nama_ayah) || !empty($parent->nama_ibu)) ? 'true' : 'false'); ?>, 
                showWali: <?php echo e(!empty($parent->nama_wali) ? 'true' : 'false'); ?> 
            }">

            
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

            
            <div x-show="showOrangTua" x-cloak class="space-y-12">
                
                <div>
                    <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                        <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Data Ayah</h3>
                        <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Father's Info</span>
                    </div>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Nama Ayah</label>
                                <input type="text" name="nama_ayah" value="<?php echo e($parent->nama_ayah ?? ''); ?>"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Pendidikan Ayah</label>
                                <select name="pendidikan_ayah"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Pendidikan</option>
                                    <?php $__currentLoopData = ['Tidak Sekolah', 'Tamat SD', 'Tamat SMTP', 'Tamat SMTA', 'Diploma', 'Sarjana', 'Magister', 'Doktor']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($p); ?>" <?php echo e(($parent->pendidikan_ayah ?? '') === $p ? 'selected' : ''); ?>><?php echo e($p); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Pekerjaan Ayah</label>
                                <select name="pekerjaan_ayah"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Pekerjaan</option>
                                    <?php if(isset($pekerjaans) && $pekerjaans->count()): ?>
                                    <?php $__currentLoopData = $pekerjaans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($p->name); ?>" <?php echo e(($parent->pekerjaan_ayah ?? '') === $p->name ? 'selected' : ''); ?>><?php echo e($p->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Agama Ayah</label>
                                <select name="agama_ayah"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Agama</option>
                                    <?php if(isset($religions) && $religions->count()): ?>
                                    <?php $__currentLoopData = $religions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($rel->name); ?>" <?php echo e(($parent->agama_ayah ?? '') === $rel->name ? 'selected' : ''); ?>><?php echo e($rel->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div>
                    <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                        <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Data Ibu</h3>
                        <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Mother's Info</span>
                    </div>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Nama Ibu</label>
                                <input type="text" name="nama_ibu" value="<?php echo e($parent->nama_ibu ?? ''); ?>"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Pendidikan Ibu</label>
                                <select name="pendidikan_ibu"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Pendidikan</option>
                                    <?php $__currentLoopData = ['Tidak Sekolah', 'Tamat SD', 'Tamat SMTP', 'Tamat SMTA', 'Diploma', 'Sarjana', 'Magister', 'Doktor']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($p); ?>" <?php echo e(($parent->pendidikan_ibu ?? '') === $p ? 'selected' : ''); ?>><?php echo e($p); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Pekerjaan Ibu</label>
                                <select name="pekerjaan_ibu"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Pekerjaan</option>
                                    <?php if(isset($pekerjaans) && $pekerjaans->count()): ?>
                                    <?php $__currentLoopData = $pekerjaans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($p->name); ?>" <?php echo e(($parent->pekerjaan_ibu ?? '') === $p->name ? 'selected' : ''); ?>><?php echo e($p->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Agama Ibu</label>
                                <select name="agama_ibu"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Agama</option>
                                    <?php if(isset($religions) && $religions->count()): ?>
                                    <?php $__currentLoopData = $religions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($rel->name); ?>" <?php echo e(($parent->agama_ibu ?? '') === $rel->name ? 'selected' : ''); ?>><?php echo e($rel->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div>
                    <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                        <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Alamat Ayah</h3>
                        <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Father's Residence</span>
                    </div>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm text-gray-600 font-medium mb-2">Alamat Lengkap</label>
                                <textarea name="alamat_ayah" rows="3"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium resize-none"><?php echo e($parent->alamat_ayah ?? ''); ?></textarea>
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Provinsi</label>
                                <select name="propinsi_ayah" id="provinsiAyahSelect"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Provinsi</option>
                                    <?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($prov['name']); ?>" data-code="<?php echo e($prov['province_code']); ?>" <?php echo e(($parent->propinsi_ayah ?? $parent->propinsi_ortu ?? '') === $prov['name'] ? 'selected' : ''); ?>><?php echo e($prov['name']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Kota/Kabupaten</label>
                                <select name="kota_ayah" id="kotaAyahSelect"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Kecamatan</label>
                                <select name="kecamatan_ayah" id="kecamatanAyahSelect"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>

                            
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
                                            selected: <?php echo json_encode($parent->desa_ayah ?? '', 15, 512) ?>,
                                            selectedText: <?php echo json_encode($parent->desa_ayah ?? 'Pilih Desa', 15, 512) ?>,
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
                                                    if (input) {
                                                        input.dispatchEvent(new Event('input', {
                                                            bubbles: true
                                                        }));
                                                        input.dispatchEvent(new Event('change', {
                                                            bubbles: true
                                                        }));
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

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Handphone Ayah</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-[#8B1538] transition-colors">
                                        <span class="text-sm font-bold opacity-50">+62</span>
                                    </div>
                                    <input type="text" name="handphone_ayah" value="<?php echo e($parent->handphone_ayah ?? ''); ?>" minlength="11" maxlength="13" inputmode="numeric" pattern="^[0-9]{11,13}$" oninput="this.value = this.value.replace(/\D/g,'')" onblur="if(this.value.length > 0 && this.value.length < 11) { alert('Nomor tidak valid! Minimal 11 angka.'); }"
                                        class="w-full pl-14 pr-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium" placeholder="81xxxxxxxxx">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="border-t border-gray-100 pt-8 mt-8">
                    <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                        <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Alamat Ibu</h3>
                        <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Mother's Residence</span>
                    </div>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm text-gray-600 font-medium mb-2">Alamat Lengkap</label>
                                <textarea name="alamat_ibu" rows="3"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium resize-none"><?php echo e($parent->alamat_ibu ?? ''); ?></textarea>
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Provinsi</label>
                                <select name="propinsi_ibu" id="provinsiIbuSelect"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Provinsi</option>
                                    <?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($prov['name']); ?>" data-code="<?php echo e($prov['province_code']); ?>" <?php echo e(($parent->propinsi_ibu ?? '') === $prov['name'] ? 'selected' : ''); ?>><?php echo e($prov['name']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Kota/Kabupaten</label>
                                <select name="kota_ibu" id="kotaIbuSelect"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Kecamatan</label>
                                <select name="kecamatan_ibu" id="kecamatanIbuSelect"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>

                            
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
                                            selected: <?php echo json_encode($parent->desa_ibu ?? '', 15, 512) ?>,
                                            selectedText: <?php echo json_encode($parent->desa_ibu ?? 'Pilih Desa', 15, 512) ?>,
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
                                                    if (input) {
                                                        input.dispatchEvent(new Event('input', {
                                                            bubbles: true
                                                        }));
                                                        input.dispatchEvent(new Event('change', {
                                                            bubbles: true
                                                        }));
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

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Handphone Ibu</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-[#8B1538] transition-colors">
                                        <span class="text-sm font-bold opacity-50">+62</span>
                                    </div>
                                    <input type="text" name="handphone_ibu" value="<?php echo e($parent->handphone_ibu ?? ''); ?>" minlength="11" maxlength="13" inputmode="numeric" pattern="^[0-9]{11,13}$" oninput="this.value = this.value.replace(/\D/g,'')" onblur="if(this.value.length > 0 && this.value.length < 11) { alert('Nomor tidak valid! Minimal 11 angka.'); }"
                                        class="w-full pl-14 pr-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium" placeholder="81xxxxxxxxx">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 

            
            <div x-show="showWali" x-cloak class="space-y-12">
                
                <div>
                    <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                        <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Data Wali</h3>
                        <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Guardian's Info</span>
                    </div>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Nama Wali</label>
                                <input type="text" name="nama_wali" value="<?php echo e($parent->nama_wali ?? ''); ?>"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium">
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Hubungan</label>
                                <select name="hubungan_wali"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Hubungan</option>
                                    <?php $__currentLoopData = ['Kakek', 'Nenek', 'Paman', 'Bibi', 'Saudara Lainnya', 'Lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($h); ?>" <?php echo e(($parent->hubungan_wali ?? '') === $h ? 'selected' : ''); ?>><?php echo e($h); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Pendidikan</label>
                                <select name="pendidikan_wali"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Pendidikan</option>
                                    <?php $__currentLoopData = ['Tidak Sekolah', 'Tamat SD', 'Tamat SMTP', 'Tamat SMTA', 'Diploma', 'Sarjana', 'Magister', 'Doktor']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($p); ?>" <?php echo e(($parent->pendidikan_wali ?? '') === $p ? 'selected' : ''); ?>><?php echo e($p); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Pekerjaan</label>
                                <select name="pekerjaan_wali"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Pekerjaan</option>
                                    <?php if(isset($pekerjaans) && $pekerjaans->count()): ?>
                                    <?php $__currentLoopData = $pekerjaans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($p->name); ?>" <?php echo e(($parent->pekerjaan_wali ?? '') === $p->name ? 'selected' : ''); ?>><?php echo e($p->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Agama</label>
                                <select name="agama_wali"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Agama</option>
                                    <?php if(isset($religions) && $religions->count()): ?>
                                    <?php $__currentLoopData = $religions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($rel->name); ?>" <?php echo e(($parent->agama_wali ?? '') === $rel->name ? 'selected' : ''); ?>><?php echo e($rel->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div>
                    <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-100">
                        <h3 class="text-[#1A1A1A] font-bold text-base tracking-tight">Alamat Wali</h3>
                        <span class="px-2 py-0.5 bg-gray-100 text-[#6B7280] text-[10px] font-bold rounded uppercase">Guardian's Residence</span>
                    </div>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm text-gray-600 font-medium mb-2">Alamat Lengkap</label>
                                <textarea name="alamat_wali" rows="3"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium resize-none"><?php echo e($parent->alamat_wali ?? ''); ?></textarea>
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Provinsi</label>
                                <select name="provinsi_wali" id="provinsiWaliSelect"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                    <option value="">Pilih Provinsi</option>
                                    <?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($prov['name']); ?>" data-code="<?php echo e($prov['province_code']); ?>" <?php echo e(($parent->provinsi_wali ?? '') === $prov['name'] ? 'selected' : ''); ?>><?php echo e($prov['name']); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Kota/Kabupaten</label>
                                <select name="kota_wali" id="kotaWaliSelect"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                            </div>

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Kecamatan</label>
                                <select name="kecamatan_wali" id="kecamatanWaliSelect"
                                    class="w-full px-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white disabled:bg-gray-50 disabled:cursor-not-allowed">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>

                            
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
                                            selected: <?php echo json_encode($parent->desa_wali ?? '', 15, 512) ?>,
                                            selectedText: <?php echo json_encode($parent->desa_wali ?? 'Pilih Desa', 15, 512) ?>,
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
                                                    if (input) {
                                                        input.dispatchEvent(new Event('input', {
                                                            bubbles: true
                                                        }));
                                                        input.dispatchEvent(new Event('change', {
                                                            bubbles: true
                                                        }));
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

                            
                            <div>
                                <label class="block text-sm text-gray-600 font-medium mb-2">Handphone Wali</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-[#8B1538] transition-colors">
                                        <span class="text-sm font-bold opacity-50">+62</span>
                                    </div>
                                    <input type="text" name="handphone_wali" value="<?php echo e($parent->handphone_wali ?? ''); ?>" minlength="11" maxlength="13" inputmode="numeric" pattern="^[0-9]{11,13}$" oninput="this.value = this.value.replace(/\D/g,'')" onblur="if(this.value.length > 0 && this.value.length < 11) { alert('Nomor tidak valid! Minimal 11 angka.'); }"
                                        class="w-full pl-14 pr-4 py-3 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium" placeholder="81xxxxxxxxx">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 

            
            <div x-show="showOrangTua" x-cloak x-data="{ 
                keluarga: <?php echo e(json_encode($parent->keluarga ?? [])); ?>,
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
                                        <?php $__currentLoopData = ['Tidak Sekolah', 'Tamat SD', 'Tamat SMTP', 'Tamat SMTA', 'Diploma', 'Sarjana', 'Magister', 'Doktor']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($p); ?>"><?php echo e($p); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-[#6B7280] uppercase tracking-wider">Pekerjaan</label>
                                    <select :name="'keluarga[' + index + '][pekerjaan]'" x-model="member.pekerjaan"
                                        class="w-full px-4 py-2.5 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                        <option value="">Pilih Pekerjaan</option>
                                        <?php if(isset($pekerjaans) && $pekerjaans->count()): ?>
                                        <?php $__currentLoopData = $pekerjaans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($p->name); ?>"><?php echo e($p->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-[#6B7280] uppercase tracking-wider">Agama</label>
                                    <select :name="'keluarga[' + index + '][agama]'" x-model="member.agama"
                                        class="w-full px-4 py-2.5 border border-[#E5E7EB] rounded-xl text-sm focus:border-[#8B1538] focus:ring-4 focus:ring-[#8B1538]/5 transition-all outline-none font-medium bg-white">
                                        <option value="">Pilih Agama</option>
                                        <?php if(isset($religions) && $religions->count()): ?>
                                        <?php $__currentLoopData = $religions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($rel->name); ?>"><?php echo e($rel->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            
            <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-end gap-3">
                <a href="<?php echo e(route('mahasiswa.profil.index')); ?>"
                    class="w-full sm:w-auto px-8 py-3 bg-[#F3F4F6] hover:bg-[#E5E7EB] text-[#4B5563] text-sm font-bold rounded-xl transition-all hover:-translate-y-0.5 text-center">
                    <i class="fas fa-times mr-2 font-medium"></i> Batal
                </a>

                <?php if(!$isLocked): ?>
                <button type="submit"
                    class="w-full sm:w-auto px-10 py-3 bg-gradient-to-r from-[#8B1538] to-[#6D1029] hover:from-[#6D1029] hover:to-[#550c20] text-white text-sm font-bold rounded-xl shadow-lg shadow-maroon/20 transition-all hover:-translate-y-0.5 flex items-center justify-center">
                    <i class="fas fa-save mr-2 font-medium"></i> Update Profil Mahasiswa
                </button>
                <?php else: ?>
                <button type="submit"
                    class="w-full sm:w-auto px-10 py-3 bg-gradient-to-r from-[#8B1538] to-[#6D1029] hover:from-[#6D1029] hover:to-[#550c20] text-white text-sm font-bold rounded-xl shadow-lg shadow-maroon/20 transition-all hover:-translate-y-0.5 flex items-center justify-center">
                    <i class="fas fa-save mr-2 font-medium"></i> Update Profil Mahasiswa
                </button>
                <?php endif; ?>
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

            // Check file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Ukuran File Terlalu Besar!',
                    html: 'Batas maksimal ukuran file adalah <strong>5MB</strong>.<br><br>File "<strong>' + file.name + '</strong>" (' + (file.size / (1024 * 1024)).toFixed(2) + 'MB) terlalu besar.',
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

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
        }

        50% {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.2);
        }
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

            // events
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
                    element.dispatchEvent(new Event('input', {
                        bubbles: true
                    }));
                    element.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                }
            });
        }, 200);

        // Backend-driven missing fields highlight (additional red styling)
        <?php if($highlightMissing && count($missingFields) > 0): ?>
        const missingFields = <?php echo json_encode(array_keys($missingFields), 15, 512) ?>;
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
                firstMissing.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        }, 500);
        <?php endif; ?>
    });
</script>


<div id="filePreviewModal"
    class="fixed inset-0 z-[9999] flex items-center justify-center p-4 hidden"
    onclick="if(event.target === this) closeFilePreview()">
    
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden z-10 animate-fade-in">
        
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-8 h-8 rounded-lg bg-maroon/10 flex items-center justify-center shrink-0">
                    <i class="fas fa-eye text-maroon text-sm"></i>
                </div>
                <span id="previewFileName" class="text-sm font-bold text-gray-800 truncate"></span>
            </div>
            <div class="flex items-center gap-2">
                <a id="previewOpenNewTabBtn" href="#" target="_blank"
                    class="px-2 md:px-3 py-1.5 text-xs font-bold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg transition shadow-sm flex items-center"
                    title="Buka di tab baru">
                    <i class="fas fa-external-link-alt md:mr-1.5"></i><span class="hidden md:inline ml-1">Buka Tab Baru</span>
                </a>
                <a id="previewDownloadBtn" href="#" download
                    class="inline-flex items-center px-2 md:px-3 py-1.5 bg-[#8B1538] hover:bg-[#6D1029] text-white text-xs font-bold rounded-lg transition-colors">
                    <i class="fas fa-download"></i><span class="hidden md:inline ml-1.5">Download</span>
                </a>
                <button type="button" onclick="closeFilePreview()"
                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-200 text-gray-500 hover:text-gray-800 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>
        
        <div id="previewBody" class="flex-1 overflow-auto bg-gray-100 flex items-center justify-center min-h-[50vh] md:min-h-[400px]">
            
        </div>
    </div>
</div>

<style>
    #filePreviewModal .animate-fade-in {
        animation: modalFadeIn 0.2s ease-out;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>

<script>
    function openFilePreview(url, filename, ext) {
        const modal = document.getElementById('filePreviewModal');
        const body = document.getElementById('previewBody');
        const nameEl = document.getElementById('previewFileName');
        // const iconEl = document.getElementById('previewIcon');
        const downloadBtn = document.getElementById('previewDownloadBtn');
        const newTabBtn = document.getElementById('previewOpenNewTabBtn');

        nameEl.textContent = filename;
        downloadBtn.href = url;
        if (newTabBtn) newTabBtn.href = url;

        const isImage = ['jpg', 'jpeg', 'png'].includes(ext.toLowerCase());

        if (isImage) {
            // iconEl.className = 'fas fa-file-image text-blue-600 text-lg';
            body.innerHTML = '<img src="' + url + '" alt="' + filename + '" class="max-w-full max-h-[75vh] object-contain rounded shadow-sm" />';
        } else {
            // iconEl.className = 'fas fa-file-pdf text-[#8B1538] text-lg';
            body.innerHTML = '<iframe src="' + url + '" class="w-full h-full border-0 min-h-[60vh] md:min-h-[75vh]"></iframe>';
        }

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeFilePreview() {
        const modal = document.getElementById('filePreviewModal');
        const body = document.getElementById('previewBody');
        modal.classList.add('hidden');
        body.innerHTML = '';
        document.body.style.overflow = '';
    }

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeFilePreview();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/page/mahasiswa/profil/manajemen.blade.php ENDPATH**/ ?>