<?php $__env->startSection('title', 'Tambah Kelas Perkuliahan'); ?>
<?php $__env->startSection('page-title', 'Kelas Perkuliahan'); ?>
<?php $__env->startSection('content'); ?>

    <div class="w-full">
        <div class="mb-6">
            <a href="<?php echo e(route('admin.kelas-perkuliahan.index')); ?>"
                class="text-maroon hover:text-red-800 text-sm font-medium flex items-center gap-1 w-max">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg  overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-plus-circle text-maroon mr-2"></i>Tambah Kelas Perkuliahan
                </h3>
                <p class="text-sm text-gray-600 mt-1">Nama kelas akan di-generate otomatis dari kombinasi Tingkat, Kode
                    Prodi, dan Kode Kelas.</p>
            </div>

            <form method="POST" action="<?php echo e(route('admin.kelas-perkuliahan.store')); ?>" class="p-6" x-data="{
                tingkat: '<?php echo e(old('tingkat', 1)); ?>',
                kodeProdi: '<?php echo e(old('kode_prodi', '')); ?>',
                kodeKelas: '<?php echo e(old('kode_kelas', '01')); ?>',
                get previewNamaKelas() {
                    if (!this.tingkat || !this.kodeProdi || !this.kodeKelas) return '...';
                    return this.tingkat + this.kodeProdi.toUpperCase() + this.kodeKelas;
                }
            }">
                <?php echo csrf_field(); ?>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-6">
                    <!-- Kolom Kiri: Form -->
                    <div class="lg:col-span-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <label for="tingkat" class="block text-sm font-semibold text-gray-700 mb-2">Tingkat <span
                                        class="text-red-500">*</span></label>
                                <select name="tingkat" id="tingkat" x-model="tingkat" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent bg-gray-50 hover:bg-white transition-colors">
                                    <?php for($i = 1; $i <= 8; $i++): ?>
                                        <option value="<?php echo e($i); ?>" <?php echo e(old('tingkat') == $i ? 'selected' : ''); ?>>
                                            Tingkat <?php echo e($i); ?> (Semester <?php echo e(($i * 2) - 1); ?> & <?php echo e($i * 2); ?>)
                                        </option>
                                    <?php endfor; ?>
                                </select>
                                <?php $__errorArgs = ['tingkat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div>
                                <label for="tahun_akademik_id" class="block text-sm font-semibold text-gray-700 mb-2">Tahun
                                    Akademik</label>
                                <select name="tahun_akademik_id" id="tahun_akademik_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent bg-gray-50 hover:bg-white transition-colors">
                                    <option value="">Tidak terikat tahun akademik</option>
                                    <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($sem->id); ?>" <?php echo e(old('tahun_akademik_id') == $sem->id ? 'selected' : ''); ?>><?php echo e($sem->nama_semester); ?> <?php echo e($sem->tahun_ajaran); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['tahun_akademik_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="md:col-span-2">
                                <label for="prodi_id" class="block text-sm font-semibold text-gray-700 mb-2">Program Studi
                                    <span class="text-red-500">*</span></label>
                                <select name="prodi_id" id="prodi_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent bg-gray-50 hover:bg-white transition-colors"
                                    @change="kodeProdi = $event.target.selectedOptions[0]?.dataset?.kode || ''">
                                    <option value="" disabled <?php echo e(old('prodi_id') ? '' : 'selected'); ?>>Pilih Prodi</option>
                                    <?php $__currentLoopData = $prodis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prodi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($prodi->id); ?>" data-kode="<?php echo e($prodi->kode_prodi); ?>" <?php echo e(old('prodi_id') == $prodi->id ? 'selected' : ''); ?>>
                                            <?php echo e($prodi->nama_prodi); ?> (<?php echo e($prodi->kode_prodi); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['prodi_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div>
                                <label for="kode_prodi" class="block text-sm font-semibold text-gray-700 mb-2">Kode Prodi
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="kode_prodi" id="kode_prodi" x-model="kodeProdi"
                                    placeholder="Contoh: HK, PRWT" required maxlength="10"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent font-mono uppercase bg-gray-50 hover:bg-white transition-colors">
                                <p class="text-xs text-gray-500 mt-1.5">Otomatis terisi saat memilih Prodi.</p>
                                <?php $__errorArgs = ['kode_prodi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div>
                                <label for="kode_kelas" class="block text-sm font-semibold text-gray-700 mb-2">Kode Kelas
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="kode_kelas" id="kode_kelas" x-model="kodeKelas"
                                    value="<?php echo e(old('kode_kelas', '01')); ?>" placeholder="01" required maxlength="5"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent font-mono bg-gray-50 hover:bg-white transition-colors">
                                <p class="text-xs text-gray-500 mt-1.5">Kode kelas paralel: 01, 02, 03, dst.</p>
                                <?php $__errorArgs = ['kode_kelas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Preview -->
                    <div class="lg:col-span-4">
                        <div class="sticky top-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Preview Nama Kelas</label>
                            <div
                                class="bg-gradient-to-br from-blue-50 to-indigo-50/50 rounded-xl p-8 text-center border-2 border-blue-100 shadow-sm h-full flex flex-col justify-center py-12">
                                <p class="text-5xl font-black text-blue-900 tracking-widest font-mono mb-3"
                                    x-text="previewNamaKelas"></p>
                                <div
                                    class="flex items-center justify-center gap-2 text-xs font-medium text-blue-600/70 tracking-wider">
                                    <span>[TINGKAT]</span><i class="fas fa-plus text-[10px]"></i>
                                    <span>[PRODI]</span><i class="fas fa-plus text-[10px]"></i>
                                    <span>[KELAS]</span>
                                </div>
                            </div>

                            <div
                                class="bg-blue-50/80 border border-blue-100 rounded-lg p-4 text-sm text-blue-800 mt-6 leading-relaxed">
                                <i class="fas fa-info-circle mr-1.5 text-blue-500"></i>
                                Jika kombinasi <strong>Tingkat + Kode Prodi + Kode Kelas + Tahun Akademik</strong> sudah
                                ada, data yang ada akan digunakan kembali (tidak duplikat).
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 mt-2">
                    <a href="<?php echo e(route('admin.kelas-perkuliahan.index')); ?>"
                        class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">Batal</a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-maroon text-white rounded-lg text-sm font-medium hover:bg-red-800 transition-colors flex items-center gap-2 shadow-sm">
                        <i class="fas fa-save"></i> Simpan Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/admin/kelas-perkuliahan/create.blade.php ENDPATH**/ ?>