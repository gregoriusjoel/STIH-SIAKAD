<?php $__env->startSection('title', 'Manajemen Magang Mahasiswa'); ?>

<?php $__env->startSection('content'); ?>
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-[1600px] mx-auto font-inter">

    
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl font-bold text-text-primary tracking-tight">Magang Mahasiswa</h1>
            <p class="text-sm text-text-secondary mt-1">Kelola dan proses pengajuan magang mahasiswa.</p>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-bg-card rounded-2xl p-6 shadow-sm border border-border-color">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-bold text-blue-600 uppercase tracking-wider">Total</h3>
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center"><i class="fas fa-briefcase text-blue-600"></i></div>
            </div>
            <div class="text-3xl font-bold text-text-primary"><?php echo e($stats['total']); ?></div>
        </div>
        <div class="bg-white dark:bg-bg-card rounded-2xl p-6 shadow-sm border border-yellow-200">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-bold text-yellow-600 uppercase tracking-wider">Perlu Review</h3>
                <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center"><i class="fas fa-clock text-yellow-600"></i></div>
            </div>
            <div class="text-3xl font-bold text-text-primary"><?php echo e($stats['submitted']); ?></div>
        </div>
        <div class="bg-white dark:bg-bg-card rounded-2xl p-6 shadow-sm border border-green-200">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-bold text-green-600 uppercase tracking-wider">Sedang Berjalan</h3>
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center"><i class="fas fa-play text-green-600"></i></div>
            </div>
            <div class="text-3xl font-bold text-text-primary"><?php echo e($stats['ongoing']); ?></div>
        </div>
        <div class="bg-white dark:bg-bg-card rounded-2xl p-6 shadow-sm border border-indigo-200">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Selesai</h3>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center"><i class="fas fa-check text-indigo-600"></i></div>
            </div>
            <div class="text-3xl font-bold text-text-primary"><?php echo e($stats['completed']); ?></div>
        </div>
    </div>

    
    <div class="bg-white dark:bg-bg-card rounded-2xl shadow-sm border border-border-color mb-6">
        <form method="GET" class="p-4 flex flex-wrap items-center gap-4">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari NIM / Nama..."
                   class="rounded-xl border-gray-300 bg-gray-100 dark:border-gray-600 dark:bg-gray-800 text-sm px-4 py-2.5 w-64 focus:ring-[#7a1621] focus:border-[#7a1621] transition-all">
            <select name="status" class="rounded-xl border-gray-300 bg-gray-100 dark:border-gray-600 dark:bg-gray-800 text-sm px-4 py-2.5 focus:ring-[#7a1621] focus:border-[#7a1621] transition-all">
                <option value="all">Semua Status</option>
                <?php $__currentLoopData = \App\Models\Internship::STATUS_LABELS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($val); ?>" <?php echo e(request('status') == $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-[#7a1621] hover:bg-[#63101a] text-white text-sm font-semibold rounded-xl transition-colors shadow-sm">Filter</button>
            <?php if(request()->hasAny(['search','status'])): ?>
                <a href="<?php echo e(route('admin.magang.index')); ?>" 
                   class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 text-sm text-gray-600 dark:text-gray-400 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 font-semibold rounded-xl transition-colors">
                    Reset
                </a>
            <?php endif; ?>
        </form>
    </div>

    
    <div class="bg-white dark:bg-bg-card rounded-2xl shadow-sm border border-border-color overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800 text-left">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-gray-600">NIM</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Nama</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Instansi</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Periode</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Pembimbing</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Status</th>
                        <th class="px-6 py-4 font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $internships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-6 py-4 font-mono text-xs"><?php echo e($i->mahasiswa?->nim ?? '-'); ?></td>
                        <td class="px-6 py-4"><?php echo e($i->mahasiswa?->user?->name ?? '-'); ?></td>
                        <td class="px-6 py-4"><?php echo e($i->instansi); ?></td>
                        <td class="px-6 py-4 text-xs"><?php echo e($i->periode_mulai?->format('d/m/Y')); ?> – <?php echo e($i->periode_selesai?->format('d/m/Y')); ?></td>
                        <td class="px-6 py-4 text-xs"><?php echo e($i->supervisorDosen?->user?->name ?? '-'); ?></td>
                        <td class="px-6 py-4">
                            <?php echo $i->status_badge; ?>

                        </td>
                        <td class="px-6 py-4">
                            <a href="<?php echo e(route('admin.magang.show', $i)); ?>" class="text-[#7a1621] hover:underline text-sm font-semibold">Detail</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">Belum ada data magang.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($internships->hasPages()): ?>
            <div class="p-4 border-t border-gray-100 dark:border-gray-700"><?php echo e($internships->withQueryString()->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/admin/magang/index.blade.php ENDPATH**/ ?>