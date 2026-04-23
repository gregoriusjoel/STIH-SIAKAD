<?php $__env->startSection('title', 'KRS Ditutup'); ?>
<?php $__env->startSection('page-title', 'Pengisian KRS'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-12">
    <div class="max-w-[1600px] mx-auto">
        <div class="flex flex-col lg:flex-row gap-8 px-4 sm:px-6 lg:px-8">
            <!-- Left: Card Section (35%) -->
            <div class="w-full lg:w-[35%] flex justify-center items-start">
                <div class="w-full">
                    <div class="relative bg-white dark:bg-[#1a1c23] rounded-[1.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800 transition-all duration-500 hover:shadow-maroon/5 group">
                        <!-- Floating Glass Shapes for Depth -->
                        <div class="absolute -top-10 -right-10 w-24 h-24 bg-maroon/5 rounded-full blur-2xl group-hover:bg-maroon/10 transition-colors duration-500"></div>
                        <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-orange-400/5 rounded-full blur-2xl group-hover:bg-orange-400/10 transition-colors duration-500"></div>

                        <div class="p-8 text-center relative z-10">
                            <!-- Icon Container with Animated Glow -->
                            <div class="relative mb-6 mx-auto w-24 h-24">
                                <!-- Multi-layered Glow -->
                                <div class="absolute inset-0 bg-maroon/10 rounded-full blur-xl animate-pulse"></div>
                                <div class="absolute inset-1.5 bg-orange-100 dark:bg-orange-900/20 rounded-full border border-orange-200 dark:border-orange-800/30"></div>
                                
                                <!-- Main Icon Base -->
                                <div class="relative w-full h-full bg-white dark:bg-[#252831] rounded-full flex items-center justify-center border-[4px] border-white dark:border-[#1a1c23] shadow-lg">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-lock text-[#8B1538] text-4xl drop-shadow-sm"></i>
                                    </div>
                                </div>
                                
                                <!-- Status Badge -->
                                <div class="absolute -bottom-0.5 -right-0.5 bg-gradient-to-br from-[#8B1538] to-[#6D1029] text-white w-8 h-8 rounded-xl flex items-center justify-center border-[3px] border-white dark:border-[#1a1c23] shadow-md rotate-12 group-hover:rotate-0 transition-transform duration-300">
                                    <i class="fas fa-hourglass-half text-[10px] animate-spin-slow"></i>
                                </div>
                            </div>

                            <div class="space-y-3 mb-8">
                                <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                                    Pengisian KRS <span class="text-[#8B1538]">Ditutup</span>
                                </h2>
                                <p class="text-gray-500 dark:text-gray-400 text-[13px] leading-relaxed max-w-[260px] mx-auto font-medium">
                                    <?php echo e($message ?? 'Akses pengisian Kartu Rencana Studi (KRS) saat ini tidak tersedia atau sudah melewati batas waktu.'); ?>

                                </p>
                            </div>

                            <?php if($semesterAktif): ?>
                            <div class="bg-gradient-to-br from-gray-50 to-white dark:from-white/5 dark:to-transparent rounded-2xl border border-gray-100 dark:border-gray-700/50 p-5 mb-6 text-left shadow-sm">
                                <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100 dark:border-white/10">
                                    <div class="w-10 h-10 rounded-lg bg-[#8B1538] text-white flex items-center justify-center shadow-lg shadow-maroon/20">
                                        <i class="fas fa-calendar-check text-base"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-[#8B1538] uppercase tracking-[0.2em]">Info Semester</p>
                                        <h4 class="font-extrabold text-gray-900 dark:text-white text-sm">
                                            <?php echo e($semesterAktif->nama_semester ?? '-'); ?> <?php echo e($semesterAktif->tahun_ajaran ?? ''); ?>

                                        </h4>
                                    </div>
                                </div>
                                
                                <div class="space-y-3">
                                    <?php
                                        $krsMultai = $krsSemester->krs_mulai ?? null;
                                        $krsSelesai = $krsSemester->krs_selesai ?? null;
                                    ?>
                                    
                                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-white dark:bg-black/20 border border-gray-50 dark:border-white/5 shadow-sm">
                                        <div class="flex items-center gap-2">
                                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Mulai</span>
                                        </div>
                                        <span class="text-xs font-black text-gray-900 dark:text-white">
                                            <?php echo e($krsMultai ? \Carbon\Carbon::parse($krsMultai)->translatedFormat('d M Y') : '-'); ?>

                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-white dark:bg-black/20 border border-gray-50 dark:border-white/5 shadow-sm">
                                        <div class="flex items-center gap-2">
                                            <div class="w-1.5 h-1.5 rounded-full bg-rose-500"></div>
                                            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Berakhir</span>
                                        </div>
                                        <span class="text-xs font-black text-gray-900 dark:text-white">
                                            <?php echo e($krsSelesai ? \Carbon\Carbon::parse($krsSelesai)->translatedFormat('d M Y') : '-'); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="space-y-5">
                                <div class="relative p-4 bg-blue-50/50 dark:bg-blue-900/10 rounded-xl border border-blue-100/50 dark:border-blue-800/30 text-left overflow-hidden">
                                    <div class="absolute top-0 right-0 w-16 h-16 bg-blue-500/5 rounded-full -mr-8 -mt-8 blur-2xl"></div>
                                    <div class="flex items-start gap-3 relative z-10">
                                        <div class="w-5 h-5 rounded-full bg-blue-500 text-white flex items-center justify-center shrink-0 shadow-md">
                                            <i class="fas fa-info text-[9px]"></i>
                                        </div>
                                        <p class="text-[10px] text-blue-800 dark:text-blue-300 leading-relaxed font-bold italic">
                                            Butuh dispensasi? Hubungi Biro Akademik untuk bantuan lebih lanjut.
                                        </p>
                                    </div>
                                </div>

                                <a href="<?php echo e(route('mahasiswa.dashboard')); ?>" class="group relative flex items-center justify-center w-full px-6 py-3.5 bg-[#1a1c23] dark:bg-white text-white dark:text-[#1a1c23] rounded-xl font-black text-xs tracking-wide transition-all duration-300 hover:shadow-[0_15px_40px_rgba(0,0,0,0.15)] dark:hover:shadow-[0_15px_40px_rgba(255,255,255,0.05)] overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-r from-maroon to-maroon opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    <span class="relative flex items-center">
                                        <i class="fas fa-chevron-left mr-2 text-[9px] group-hover:-translate-x-1 transition-transform"></i>
                                        KEMBALI KE DASHBOARD
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Download History Section (65%) -->
            <div class="w-full lg:w-[65%] flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight flex items-center gap-2">
                            <i class="fas fa-history text-[#8B1538]"></i>
                            Riwayat <span class="text-[#8B1538]">KRS</span>
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mt-1 uppercase tracking-widest">Download semester sebelumnya</p>
                    </div>
                </div>
            
                <div class="bg-white dark:bg-[#1a1c23] rounded-[1.5rem] shadow-xl border border-gray-100 dark:border-gray-800 overflow-hidden">
                    
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50/50 dark:bg-white/5 border-b border-gray-100 dark:border-white/10">
                                    <th class="px-6 py-4 text-left text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">
                                        Semester
                                    </th>
                                    <th class="px-6 py-4 text-left text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">
                                        Tahun Ajaran
                                    </th>
                                    <th class="px-6 py-4 text-right text-[9px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">
                                        Dokumen
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                                <?php $__empty_1 = true; $__currentLoopData = isset($semesterList) ? $semesterList : collect(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $canDownload = isset($downloadable) && in_array($sem->semester_number ?? $sem->id, $downloadable);
                                    ?>
                                    <tr class="group hover:bg-gray-50/80 dark:hover:bg-white/[0.02] transition-colors duration-300">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-500 dark:text-gray-400 group-hover:bg-[#8B1538] group-hover:text-white transition-all duration-300 shadow-sm">
                                                    <span class="text-xs font-black"><?php echo e($sem->semester_number ?? 1); ?></span>
                                                </div>
                                                <span class="text-xs font-bold text-gray-900 dark:text-white">Semester <?php echo e($sem->semester_number ?? 1); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-gray-100 dark:bg-white/5 text-[10px] font-bold text-gray-600 dark:text-gray-400 border border-gray-200/50 dark:border-white/10">
                                                <?php echo e($sem->tahun_ajaran ?? '-'); ?>

                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <?php if($canDownload): ?>
                                                <a href="<?php echo e(route('mahasiswa.krs.print', ['semester_number' => $sem->semester_number ?? $sem->id])); ?>"
                                                   class="inline-flex items-center px-4 py-2 bg-white dark:bg-[#252831] border-2 border-[#8B1538] text-[10px] font-black rounded-xl text-[#8B1538] hover:bg-[#8B1538] hover:text-white transition-all duration-300 shadow-sm group/btn uppercase tracking-wider">
                                                    <i class="fas fa-file-pdf mr-2 text-xs group-hover/btn:scale-110 transition-transform"></i>
                                                    Download
                                                </a>
                                            <?php else: ?>
                                                <div class="inline-flex items-center px-4 py-2 bg-gray-50 dark:bg-black/20 border-2 border-gray-100 dark:border-white/5 text-[10px] font-black rounded-xl text-gray-400 dark:text-gray-600 cursor-not-allowed uppercase tracking-wider">
                                                    <i class="fas fa-lock mr-2"></i> Lock
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="3" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center gap-3">
                                                <div class="w-16 h-16 rounded-[1.5rem] bg-gray-50 dark:bg-white/5 flex items-center justify-center text-gray-300 dark:text-gray-700 rotate-12">
                                                    <i class="fas fa-folder-open text-3xl"></i>
                                                </div>
                                                <p class="text-[10px] font-bold text-gray-400 dark:text-gray-600 uppercase tracking-widest">Belum ada riwayat</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
 
                    
                    <div class="md:hidden divide-y divide-gray-50 dark:divide-white/5">
                        <?php $__empty_1 = true; $__currentLoopData = isset($semesterList) ? $semesterList : collect(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $canDownload = isset($downloadable) && in_array($sem->semester_number ?? $sem->id, $downloadable);
                            ?>
                            <div class="p-6 space-y-5">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-[#8B1538] text-white flex items-center justify-center shadow-lg shadow-maroon/20">
                                            <span class="text-xs font-black"><?php echo e($sem->semester_number ?? 1); ?></span>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-black text-gray-900 dark:text-white">Semester <?php echo e($sem->semester_number ?? 1); ?></h4>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">TA <?php echo e($sem->tahun_ajaran ?? '-'); ?></p>
                                        </div>
                                    </div>
                                    <div class="text-[#8B1538]/20">
                                        <i class="fas fa-history text-xl"></i>
                                    </div>
                                </div>
                                
                                <div>
                                    <?php if($canDownload): ?>
                                        <a href="<?php echo e(route('mahasiswa.krs.print', ['semester_number' => $sem->semester_number ?? $sem->id])); ?>"
                                           class="flex items-center justify-center w-full px-4 py-3.5 bg-[#8B1538] text-white text-[11px] font-black rounded-2xl shadow-lg shadow-maroon/20 uppercase tracking-widest transition-transform active:scale-95">
                                            <i class="fas fa-download mr-2"></i> Download KRS
                                        </a>
                                    <?php else: ?>
                                        <button disabled class="flex items-center justify-center w-full px-4 py-3.5 bg-gray-50 dark:bg-white/5 text-gray-400 dark:text-gray-600 text-[11px] font-black rounded-2xl border border-gray-100 dark:border-white/5 cursor-not-allowed uppercase tracking-widest">
                                            <i class="fas fa-lock mr-2"></i> Belum Tersedia
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="p-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <i class="fas fa-file-invoice text-4xl text-gray-200 dark:text-gray-800"></i>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Belum ada riwayat</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->startPush('styles'); ?>
<style>
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 8s linear infinite;
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/page/mahasiswa/krs/closed.blade.php ENDPATH**/ ?>