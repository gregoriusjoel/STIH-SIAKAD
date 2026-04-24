<?php $__env->startSection('title', 'Jadwal Kelas'); ?>
<?php $__env->startSection('page-title', 'Jadwal Kelas'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">

        
        <div class="bg-white dark:bg-[#1a1d2e] rounded-xl shadow-sm border border-gray-100 dark:border-slate-800 p-5">
            <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6">
                
                <!-- Title Section -->
                <div class="flex-shrink-0">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-1">Jadwal Kelas</h2>
                    <p class="text-xs text-gray-500 dark:text-slate-400 font-medium">Semester: <span
                            class="text-gray-700 dark:text-slate-300"><?php echo e(Auth::user()->mahasiswa->getCurrentSemester() ?? 'Tidak ada semester aktif'); ?></span>
                    </p>
                </div>

                <!-- Summary Grid Section -->
                <div class="flex-grow w-full xl:w-auto">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 xl:gap-4">
                        <!-- Total MK -->
                        <div class="bg-[#F4F8FC] dark:bg-blue-900/10 rounded-lg p-3 text-center border border-[#EBF2F9] dark:border-blue-900/20 shadow-sm">
                            <p class="text-[10px] text-[#5C6E85] dark:text-slate-400 font-medium mb-1 truncate">Total MK</p>
                            <p class="text-xl font-bold text-[#2D60DF] dark:text-blue-400 leading-none"><?php echo e($krsData->count()); ?></p>
                        </div>
                        
                        <!-- Total SKS -->
                        <div class="bg-[#F2FCF5] dark:bg-green-900/10 rounded-lg p-3 text-center border border-[#E5F5EB] dark:border-green-900/20 shadow-sm">
                            <p class="text-[10px] text-[#5C6E85] dark:text-slate-400 font-medium mb-1 truncate">Total SKS</p>
                            <p class="text-xl font-bold text-[#1FBC5A] dark:text-green-500 leading-none">
                                <?php echo e($krsData->sum(function ($krs) {
                                    return $krs->kelas->mataKuliah->sks ?? 0; 
                                })); ?>

                            </p>
                        </div>

                        <!-- Hari Kuliah -->
                        <div class="bg-[#FCF5FD] dark:bg-purple-900/10 rounded-lg p-3 text-center border border-[#F5EAF7] dark:border-purple-900/20 shadow-sm">
                            <p class="text-[10px] text-[#5C6E85] dark:text-slate-400 font-medium mb-1 truncate">Hari Kuliah</p>
                            <p class="text-xl font-bold text-[#A81FDF] dark:text-purple-500 leading-none">
                                <?php echo e(count(array_filter($jadwalPerHari, function ($j) {
                                    return count($j) > 0; 
                                }))); ?>

                            </p>
                        </div>

                        <!-- Semester -->
                        <div class="bg-[#FFF9F2] dark:bg-orange-900/10 rounded-lg p-3 text-center border border-[#FDF1E4] dark:border-orange-900/20 shadow-sm">
                            <p class="text-[10px] text-[#5C6E85] dark:text-slate-400 font-medium mb-1 truncate">Semester</p>
                            <p class="text-xl font-bold text-[#F06C13] dark:text-orange-500 leading-none"><?php echo e(Auth::user()->mahasiswa->semester ?? '-'); ?></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <?php if($krsData->isEmpty()): ?>
            <div class="bg-white dark:bg-[#1a1d2e] rounded-xl shadow-lg p-12">
                <div class="text-center">
                    <i class="fas fa-calendar-times text-6xl text-gray-300 dark:text-slate-700 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-700 dark:text-white mb-2">Tidak Ada Jadwal</h3>
                    <p class="text-gray-500 dark:text-slate-500 mb-4">Belum ada mata kuliah yang diambil. Silakan isi KRS terlebih dahulu.</p>
                    <a href="<?php echo e(route('mahasiswa.krs.index')); ?>"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-maroon text-white rounded-lg hover:bg-maroon-hover transition">
                        <i class="fas fa-file-alt"></i>
                        Lihat KRS
                    </a>
                </div>
            </div>
        <?php else: ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $jadwalPerHari; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hari => $jadwals): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white dark:bg-[#1a1d2e] rounded-2xl shadow-xl overflow-hidden h-full border border-gray-100/50 dark:border-slate-800 flex flex-col hover:shadow-2xl transition-shadow duration-300">
                        
                        <div class="relative bg-gradient-to-br from-[#8B1538] to-[#5C0A22] text-white px-6 py-5 flex-shrink-0 overflow-hidden">
                            <!-- Background Pattern/Glow -->
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>
                            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-16 h-16 bg-white opacity-10 rounded-full blur-lg"></div>
                            
                            <div class="relative flex items-center justify-between z-10">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl flex items-center justify-center shadow-inner">
                                        <i class="fas fa-calendar-alt text-xl text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold tracking-wide"><?php echo e($hari); ?></h3>
                                        <p class="text-xs text-red-100 mt-0.5 font-medium"><?php echo e(count($jadwals)); ?> Mata Kuliah</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <?php if(count($jadwals) > 0): ?>
                            <div class="p-5 space-y-4 bg-gray-50/50 dark:bg-slate-900/50 flex-grow max-h-[420px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-slate-600 scrollbar-track-transparent">
                                <?php $__currentLoopData = $jadwals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="relative bg-white dark:bg-[#1a1d2e] rounded-xl p-5 shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-slate-700 group transform hover:-translate-y-1">
                                        <!-- Animated Line Accent -->
                                        <div class="absolute inset-y-0 left-0 w-1 bg-gradient-to-b from-[#8B1538] to-[#D92B5A] rounded-l-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        
                                        
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="bg-rose-50 dark:bg-rose-900/20 px-3 py-1.5 rounded-full flex items-center gap-2 border border-rose-100 dark:border-rose-800/30">
                                                <i class="fas fa-clock text-[#8B1538] dark:text-rose-400 text-xs opacity-80"></i>
                                                <span class="font-bold text-[#8B1538] dark:text-rose-400 text-xs tracking-wide">
                                                    <?php echo e(substr($jadwal['jam_mulai'], 0, 5)); ?> - <?php echo e(substr($jadwal['jam_selesai'], 0, 5)); ?>

                                                </span>
                                            </div>
                                            <div class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 flex items-center gap-1.5 bg-gray-100 dark:bg-slate-800 px-3 py-1 rounded-full">
                                                <i class="fas fa-map-marker-alt text-gray-400 text-xs"></i> <span><?php echo e($jadwal['ruangan']); ?></span>
                                            </div>
                                        </div>

                                        
                                        <h4 class="font-bold text-gray-900 dark:text-white text-[15px] mb-2 leading-tight group-hover:text-[#8B1538] dark:group-hover:text-rose-400 transition-colors">
                                            <?php echo e($jadwal['mata_kuliah']); ?>

                                        </h4>
                                        
                                        
                                        <div class="text-xs text-gray-600 dark:text-gray-400 mb-4">
                                            <div class="flex items-center gap-2">
                                                <div class="bg-gray-100 dark:bg-slate-800 p-1.5 rounded-md flex-shrink-0 group-hover:bg-rose-50 dark:group-hover:bg-rose-900/20 transition-colors flex items-center justify-center">
                                                    <i class="fas fa-user-tie text-gray-500 dark:text-gray-400 text-[10px] group-hover:text-[#8B1538] transition-colors leading-none"></i>
                                                </div>
                                                <span class="font-medium leading-tight"><?php echo e($jadwal['dosen']); ?></span>
                                            </div>
                                        </div>

                                        
                                        <div class="pt-3 border-t border-gray-100 dark:border-slate-700/50 flex justify-between items-center">
                                            <div class="flex items-center gap-2 text-xs font-medium text-gray-500">
                                                <span class="bg-gray-100 dark:bg-slate-800 px-2 py-1 rounded text-[11px] font-mono font-bold"><?php echo e($jadwal['kode_mk']); ?></span>
                                                <span class="text-gray-300 dark:text-slate-600">•</span>
                                                <span class="text-gray-600 dark:text-gray-400 font-bold"><?php echo e($jadwal['sks']); ?> SKS</span>
                                            </div>
                                            <span class="px-3 py-1 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 text-blue-700 dark:text-blue-400 rounded-md text-xs font-bold border border-blue-100/50 dark:border-blue-800/30 shadow-sm">
                                                Kelas <?php echo e($jadwal['kelas']); ?>

                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="p-8 text-center text-gray-400 flex flex-col items-center justify-center flex-grow min-h-[200px] bg-gray-50 dark:bg-slate-900">
                                <i class="fas fa-mug-hot text-3xl mb-2 opacity-50"></i>
                                <p class="text-sm">Libur</p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>


        <?php endif; ?>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/page/mahasiswa/jadwal/index.blade.php ENDPATH**/ ?>