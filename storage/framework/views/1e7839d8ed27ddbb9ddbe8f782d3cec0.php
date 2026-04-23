<?php $__env->startSection('title', 'Edit Kelas Perkuliahan'); ?>
<?php $__env->startSection('page-title', 'Kelas Perkuliahan'); ?>
<?php $__env->startSection('content'); ?>

    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="<?php echo e(route('admin.kelas-perkuliahan.index')); ?>"
                class="text-maroon hover:text-red-800 text-sm font-medium flex items-center gap-1">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg  overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-edit text-maroon mr-2"></i>Edit Kelas Perkuliahan
                </h3>
                <p class="text-sm text-gray-600 mt-1">Mengedit data kelas
                    <strong><?php echo e($kelasPerkuliahan->nama_kelas); ?></strong></p>
            </div>

            <form method="POST" action="<?php echo e(route('admin.kelas-perkuliahan.update', $kelasPerkuliahan)); ?>"
                class="p-6 space-y-5" x-data="{
                tingkat: '<?php echo e(old('tingkat', $kelasPerkuliahan->tingkat)); ?>',
                kodeProdi: '<?php echo e(old('kode_prodi', $kelasPerkuliahan->kode_prodi)); ?>',
                kodeKelas: '<?php echo e(old('kode_kelas', $kelasPerkuliahan->kode_kelas)); ?>',
                get previewNamaKelas() {
                    if (!this.tingkat || !this.kodeProdi || !this.kodeKelas) return '...';
                    return this.tingkat + this.kodeProdi.toUpperCase() + this.kodeKelas;
                }
            }">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 text-center border border-blue-200">
                    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-1">Preview Nama Kelas</p>
                    <p class="text-3xl font-black text-blue-900 tracking-wider" x-text="previewNamaKelas"></p>
                </div>

                
                <div>
                    <label for="tingkat" class="block text-sm font-semibold text-gray-700 mb-1">Tingkat <span
                            class="text-red-500">*</span></label>
                    <select name="tingkat" id="tingkat" x-model="tingkat" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent">
                        <?php for($i = 1; $i <= 8; $i++): ?>
                            <option value="<?php echo e($i); ?>" <?php echo e(old('tingkat', $kelasPerkuliahan->tingkat) == $i ? 'selected' : ''); ?>>
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
                    <label for="prodi_id" class="block text-sm font-semibold text-gray-700 mb-1">Program Studi</label>
                    <select name="prodi_id" id="prodi_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent"
                        @change="kodeProdi = $event.target.selectedOptions[0]?.dataset?.kode || kodeProdi">
                        <option value="">Pilih Prodi (opsional)</option>
                        <?php $__currentLoopData = $prodis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prodi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($prodi->id); ?>" data-kode="<?php echo e($prodi->kode_prodi); ?>" <?php echo e(old('prodi_id', $kelasPerkuliahan->prodi_id) == $prodi->id ? 'selected' : ''); ?>>
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
                    <label for="kode_prodi" class="block text-sm font-semibold text-gray-700 mb-1">Kode Prodi <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="kode_prodi" id="kode_prodi" x-model="kodeProdi" required maxlength="10"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent font-mono uppercase">
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
                    <label for="kode_kelas" class="block text-sm font-semibold text-gray-700 mb-1">Kode Kelas <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="kode_kelas" id="kode_kelas" x-model="kodeKelas" required maxlength="5"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent font-mono">
                    <?php $__errorArgs = ['kode_kelas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label for="tahun_akademik_id" class="block text-sm font-semibold text-gray-700 mb-1">Tahun
                        Akademik</label>
                    <select name="tahun_akademik_id" id="tahun_akademik_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-maroon focus:border-transparent">
                        <option value="">Tidak terikat tahun akademik</option>
                        <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sem->id); ?>" <?php echo e(old('tahun_akademik_id', $kelasPerkuliahan->tahun_akademik_id) == $sem->id ? 'selected' : ''); ?>><?php echo e($sem->nama_semester); ?>

                                <?php echo e($sem->tahun_ajaran); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['tahun_akademik_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="flex justify-end gap-3 pt-2">
                    <a href="<?php echo e(route('admin.kelas-perkuliahan.index')); ?>"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">Batal</a>
                    <button type="submit"
                        class="px-6 py-2 bg-maroon text-white rounded-lg text-sm font-medium hover:bg-red-800 transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/admin/kelas-perkuliahan/edit.blade.php ENDPATH**/ ?>