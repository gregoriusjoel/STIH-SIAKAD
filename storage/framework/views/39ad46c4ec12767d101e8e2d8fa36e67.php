<?php $__env->startSection('title', 'Ajukan Magang'); ?>
<?php $__env->startSection('page-title', 'Ajukan Magang Baru'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-[1600px] mx-auto px-4 py-8">

    
    <div class="mb-6">
        <a href="<?php echo e(route('mahasiswa.magang.index')); ?>" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-[#8B1538] transition-colors group">
            <span class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center border border-gray-100 group-hover:border-red-100 group-hover:bg-red-50 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </span>
            Kembali ke Daftar Magang
        </a>
    </div>

    
    <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm border border-gray-100 dark:border-gray-700 p-8 lg:p-12 relative overflow-hidden">
        
        <!-- Background Accents -->
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-gradient-to-br from-[#8B1538]/5 to-transparent rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex items-center gap-4 mb-10 pb-6 border-b border-gray-100 dark:border-gray-700">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border border-gray-200 dark:border-gray-600 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-3xl flex items-center justify-center text-[#8B1538]">post_add</span>
            </div>
            <div>
                <h2 class="text-2xl font-black text-gray-900 dark:text-white leading-tight">Form Pengajuan Magang</h2>
                <p class="text-sm text-gray-500 font-medium">Lengkapi informasi di bawah ini untuk memulai pengajuan magang Anda.</p>
            </div>
        </div>

        <?php if($errors->any()): ?>
            <div class="mb-8 p-5 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm flex gap-3">
                <span class="material-symbols-outlined shrink-0 mt-0.5">error</span>
                <ul class="list-disc list-inside space-y-1 font-medium">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($e); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('mahasiswa.magang.store')); ?>" enctype="multipart/form-data" class="space-y-8">
            <?php echo csrf_field(); ?>

        
        <div class="mb-2 p-4 rounded-2xl bg-blue-50/60 border border-blue-100 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-blue-500 text-[20px]">school</span>
            </div>
            <div class="flex-1">
                <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mb-0.5">Semester Mahasiswa</p>
                <p class="text-sm font-bold text-blue-800">
                    Semester <?php echo e($mahasiswa->semester ?? '-'); ?>

                </p>
                <p class="text-xs text-blue-500 mt-0.5">Semester Anda saat ini berdasarkan data akademik.</p>
            </div>
            <span class="shrink-0 text-xs font-bold bg-blue-100 text-blue-700 ring-1 ring-inset ring-blue-200 px-3 py-1 rounded-full">
                <?php echo e($activeSemester?->display_label ?? '-'); ?>

            </span>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Instansi / Perusahaan <span class="text-red-500">*</span></label>
                    <input type="text" name="instansi" value="<?php echo e(old('instansi')); ?>" required placeholder="Contoh: PT. Adhyaksa Corp"
                           class="w-full rounded-2xl border-gray-200 bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-5 py-3.5 text-sm focus:bg-white transition-colors focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538]">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Alamat Instansi <span class="text-red-500">*</span></label>
                    <textarea name="alamat_instansi" rows="2" required placeholder="Alamat lengkap instansi tempat magang"
                              class="w-full rounded-2xl border-gray-200 bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-5 py-3.5 text-sm focus:bg-white transition-colors focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538]"><?php echo e(old('alamat_instansi')); ?></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Posisi / Bagian</label>
                    <input type="text" name="posisi" value="<?php echo e(old('posisi')); ?>" placeholder="Contoh: Legal Officer Intern"
                           class="w-full rounded-2xl border-gray-200 bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-5 py-3.5 text-sm focus:bg-white transition-colors focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538]">
                </div>
            </div>


            
            <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700"
                 x-data="{
                    startDate: '<?php echo e(old('periode_mulai')); ?>',
                    endDate: '<?php echo e(old('periode_selesai')); ?>',
                    durasi: <?php echo e(old('periode_selesai') ? 0 : 0); ?>,
                    custom: false,
                    customMonth: '',
                    setDurasi(n) {
                        this.custom = (n === 'other');
                        if (n !== 'other') {
                            this.durasi = n;
                            this.customMonth = '';
                            this.calcEnd();
                        } else {
                            this.durasi = 0;
                        }
                    },
                    calcEnd() {
                        const m = this.custom ? parseInt(this.customMonth) : this.durasi;
                        if (!this.startDate || !m) return;
                        const d = new Date(this.startDate);
                        d.setMonth(d.getMonth() + m);
                        this.endDate = d.toISOString().split('T')[0];
                    },
                    get label() {
                        const m = this.custom ? parseInt(this.customMonth) : this.durasi;
                        return m ? m + ' Bulan' : '';
                    }
                 }">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">date_range</span> Periode Magang
                    <span x-show="label" x-text="label"
                          class="ml-auto text-[10px] font-black px-2 py-0.5 rounded-full bg-[#8B1538]/10 text-[#8B1538]"></span>
                </h3>

                
                <div class="flex flex-wrap items-center gap-2 mb-5">
                    <span class="text-xs font-bold text-gray-400">Durasi:</span>
                    <template x-for="opt in [3,6,9]" :key="opt">
                        <button type="button"
                                @click="setDurasi(opt)"
                                :class="durasi === opt && !custom
                                    ? 'bg-[#8B1538] text-white border-[#8B1538]'
                                    : 'bg-white text-gray-600 border-gray-200 hover:border-[#8B1538] hover:text-[#8B1538]'"
                                class="text-xs font-bold px-3 py-1.5 rounded-lg border transition">
                            <span x-text="opt + ' Bulan'"></span>
                        </button>
                    </template>
                    <button type="button"
                            @click="setDurasi('other')"
                            :class="custom ? 'bg-[#8B1538] text-white border-[#8B1538]' : 'bg-white text-gray-600 border-gray-200 hover:border-[#8B1538] hover:text-[#8B1538]'"
                            class="text-xs font-bold px-3 py-1.5 rounded-lg border transition">Lain-lain</button>
                    <div x-show="custom" x-cloak class="flex items-center gap-1.5">
                        <input type="number" x-model.number="customMonth" @input="calcEnd()" min="1" max="60"
                               placeholder="e.g. 12"
                               class="w-20 rounded-lg border-gray-200 bg-white text-sm px-3 py-1.5 text-center focus:ring-4 focus:ring-red-100 focus:border-[#8B1538]">
                        <span class="text-xs text-gray-400 font-medium">bulan</span>
                    </div>
                </div>

                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" name="periode_mulai" x-model="startDate" @change="calcEnd()" required
                               class="w-full rounded-xl border-gray-200 bg-white dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 text-sm focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538] shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                        <input type="date" name="periode_selesai" x-model="endDate" required
                               class="w-full rounded-xl border-gray-200 bg-white dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-3 text-sm focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538] shadow-sm">
                    </div>
                </div>
            </div>

            
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center justify-between">
                    <span>Deskripsi Kegiatan</span>
                    <span class="text-xs font-medium text-gray-400 font-normal">Opsional</span>
                </label>
                <textarea name="deskripsi" rows="3" placeholder="Jelaskan secara singkat rencana kegiatan / tugas magang Anda"
                          class="w-full rounded-2xl border-gray-200 bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-5 py-3.5 text-sm focus:bg-white transition-colors focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538]"><?php echo e(old('deskripsi')); ?></textarea>
            </div>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2 border-t border-gray-100 dark:border-gray-700">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Pembimbing Lapangan (Nama)</label>
                    <input type="text" name="pembimbing_lapangan_nama" value="<?php echo e(old('pembimbing_lapangan_nama')); ?>" placeholder="Nama Mentor / Supervisor Instansi"
                           class="w-full rounded-2xl border-gray-200 bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-5 py-3.5 text-sm focus:bg-white transition-colors focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538]">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">No. Telp Pembimbing</label>
                    <input type="text" name="pembimbing_lapangan_telp" value="<?php echo e(old('pembimbing_lapangan_telp')); ?>" placeholder="08xxxxxxxx"
                           minlength="12" maxlength="13" pattern="^[0-9]+$" title="Harus terdiri dari 12 hingga 13 angka"
                           class="w-full rounded-2xl border-gray-200 bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-5 py-3.5 text-sm focus:bg-white transition-colors focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538]">
                </div>
            </div>

            
            <div class="px-6 py-6 rounded-2xl border-2 border-dashed border-gray-200 hover:border-red-200 bg-gray-50/50 transition-colors group">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 group-hover:text-[#8B1538] transition-colors">Dokumen Pendukung <span class="text-xs font-medium text-gray-400 font-normal ml-2">(Opsional)</span></label>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-white border border-gray-100 shadow-sm flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-gray-400">upload_file</span>
                    </div>
                    <div class="flex-1">
                        <input type="file" name="dokumen_pendukung" accept=".pdf,.jpg,.png"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-white hover:file:bg-gray-700 transition-colors file:cursor-pointer">
                        <p class="text-xs text-gray-400 mt-2">Format yang didukung: PDF, JPG, PNG. Ukuran maksimal 5MB.</p>
                    </div>
                </div>
            </div>

            
            <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 pt-8 border-t border-gray-100 dark:border-gray-700">
                <a href="<?php echo e(route('mahasiswa.magang.index')); ?>"
                   class="w-full sm:w-auto px-6 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-xl transition text-center focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Batal
                </a>
                <button type="submit"
                        class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-[#8B1538] to-[#6D1029] hover:from-[#6D1029] hover:to-[#500a1c] text-white text-sm font-bold rounded-xl shadow-lg shadow-red-900/20 transition-all hover:shadow-xl hover:shadow-red-900/30 flex items-center justify-center gap-2 focus:outline-none focus:ring-4 focus:ring-red-900/30">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Draft
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/page/mahasiswa/magang/create.blade.php ENDPATH**/ ?>