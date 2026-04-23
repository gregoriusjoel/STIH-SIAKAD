<?php $__env->startSection('title', 'Import Data'); ?>
<?php $__env->startSection('page-title', 'Import Data'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-12">
    <div>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
                <i class="fas fa-file-import mr-3 text-maroon dark:text-red-500"></i>
                Import Data
            </h2>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Import data ke sistem melalui file CSV atau XLSX</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('admin.import.history')); ?>" 
                class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600 px-4 py-2 rounded-lg transition flex items-center gap-2">
                <i class="fas fa-history"></i>
                <span>Riwayat Import</span>
            </a>
        </div>
    </div>
</div>

<!-- Import Type Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <?php $__currentLoopData = $importTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $config): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e(route('admin.import.show', $type)); ?>" 
        class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:border-maroon/30 dark:hover:border-red-500/30 transition-all duration-300">
        <div class="flex items-start justify-between">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-maroon/10 to-red-100 dark:from-red-900/30 dark:to-red-800/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i class="fas <?php echo e($config['icon']); ?> text-2xl text-maroon dark:text-red-400"></i>
            </div>
            <i class="fas fa-arrow-right text-gray-400 group-hover:text-maroon dark:group-hover:text-red-400 transform group-hover:translate-x-1 transition-all"></i>
        </div>
        <h3 class="mt-4 text-lg font-bold text-gray-800 dark:text-gray-100 group-hover:text-maroon dark:group-hover:text-red-400 transition-colors">
            <?php echo e($config['title']); ?>

        </h3>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            <?php echo e($config['description']); ?>

        </p>
        <div class="mt-4 flex flex-wrap gap-2">
            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                <i class="fas fa-file-csv mr-1"></i>CSV
            </span>
            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                <i class="fas fa-file-excel mr-1"></i>XLSX
            </span>
        </div>
    </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


</div>

<!-- Panduan Import & Import Terakhir - Side by Side -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
    <!-- Panduan Import Data (always visible) -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 bg-maroon dark:bg-red-900 border-b border-maroon dark:border-red-800">
            <h4 class="text-lg font-bold text-white flex items-center">
                <i class="fas fa-book mr-3 text-white/80"></i>
                Panduan Import Data
            </h4>
        </div>
        <div class="p-5">
            <div class="space-y-5">
                <div>
                    <h5 class="font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                        <span class="w-7 h-7 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center mr-3 text-xs">
                            <i class="fas fa-file-alt"></i>
                        </span>
                        <span class="text-[15px]">Format File</span>
                    </h5>
                    <ul class="space-y-2 ml-1">
                        <li class="flex items-start text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                            <span>Tipe file: <strong>.CSV</strong> (Comma Separated) atau <strong>.XLSX</strong> (Excel)</span>
                        </li>
                        <li class="flex items-start text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                            <span>Pastikan header kolom sesuai template</span>
                        </li>
                        <li class="flex items-start text-sm text-gray-600 dark:text-gray-400">
                            <i class="fas fa-check text-green-500 mt-1 mr-3"></i>
                            <span>Maksimal ukuran file: 10MB</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                        <span class="w-7 h-7 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center mr-3 text-xs">
                            <i class="fas fa-list-ol"></i>
                        </span>
                        <span class="text-[15px]">Langkah Import</span>
                    </h5>
                    <ol class="space-y-2 ml-1">
                        <li class="flex items-start text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-bold text-gray-400 mr-3">1.</span>
                            <span>Pilih jenis data yang akan diimport</span>
                        </li>
                        <li class="flex items-start text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-bold text-gray-400 mr-3">2.</span>
                            <span>Download template & isi data</span>
                        </li>
                        <li class="flex items-start text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-bold text-gray-400 mr-3">3.</span>
                            <span>Upload file & konfirmasi hasil preview</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Import Logs -->
    <?php if($recentLogs->count() > 0): ?>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 bg-maroon dark:bg-red-900 border-b border-maroon dark:border-red-800 flex items-center justify-between">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="fas fa-clock text-white/80 mr-2"></i>
                Import Terakhir
            </h3>
            <a href="<?php echo e(route('admin.import.history')); ?>" class="text-sm font-medium text-white/90 hover:text-white transition">
                Lihat Semua
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tipe</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Berhasil</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Gagal</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Waktu</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <?php $__currentLoopData = $recentLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-maroon/10 dark:bg-red-900/30 text-maroon dark:text-red-400">
                                <?php echo e($log->type_name); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium text-gray-900 dark:text-gray-100">
                            <?php echo e($log->total_rows); ?>

                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                <?php echo e($log->success_count); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo e($log->failed_count > 0 ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400'); ?>">
                                <?php echo e($log->failed_count); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            <?php echo e($log->created_at->diffForHumans()); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php else: ?>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 bg-maroon dark:bg-red-900 border-b border-maroon dark:border-red-800">
            <h3 class="text-lg font-bold text-white flex items-center">
                <i class="fas fa-clock text-white/80 mr-2"></i>
                Import Terakhir
            </h3>
        </div>
        <div class="p-8 text-center text-gray-500 dark:text-gray-400">
            <i class="fas fa-inbox text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
            <p class="text-sm">Belum ada riwayat import</p>
        </div>
    </div>
    <?php endif; ?>
</div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/admin/import/index.blade.php ENDPATH**/ ?>