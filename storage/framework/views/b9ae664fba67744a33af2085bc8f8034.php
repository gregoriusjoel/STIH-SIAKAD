<?php $__env->startSection('title', 'Pengumuman'); ?>
<?php $__env->startSection('page-title', 'Pengumuman'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-6 flex flex-col items-start md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center">
                <i class="fas fa-bullhorn mr-3 text-maroon dark:text-red-500"></i>
                Daftar Pengumuman
            </h2>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Kelola informasi dan pengumuman untuk sistem</p>
        </div>
        <div class="flex-shrink-0">
            <a href="<?php echo e(route('admin.pengumuman.create')); ?>"
                class="bg-maroon text-white hover:bg-red-900 px-6 py-3 rounded-lg transition flex items-center shadow-md">
                <i class="fas fa-plus mr-2"></i>
                Buat Pengumuman
            </a>
        </div>
    </div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
    

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border-separate" style="border-spacing: 0;">
            <thead class="bg-maroon text-white rounded-t-xl">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider rounded-tl-xl">
                        NO
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-bullhorn mr-2"></i>JUDUL
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-calendar-alt mr-2"></i>TANGGAL PUBLIKASI
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-users mr-2"></i>TARGET
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider rounded-tr-xl">
                        <i class="fas fa-cog mr-2"></i>AKSI
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $pengumumans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-blue-50 dark:hover:bg-blue-900/20 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 font-medium"><?php echo e($loop->iteration); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-maroon dark:text-red-400"><?php echo e($p->judul); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?php echo e($p->published_at ? \Carbon\Carbon::parse($p->published_at)->format('d M Y H:i') : '-'); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm capitalize">
                        <?php if($p->target == 'semua'): ?>
                            <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full text-xs font-semibold">Semua</span>
                        <?php elseif($p->target == 'dosen'): ?>
                            <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 rounded-full text-xs font-semibold">Dosen</span>
                        <?php elseif($p->target == 'mahasiswa'): ?>
                            <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 rounded-full text-xs font-semibold">Mahasiswa</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <a href="<?php echo e(route('admin.pengumuman.edit', $p)); ?>"
                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('admin.pengumuman.destroy', $p)); ?>" method="POST"
                                class="inline delete-form">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition"
                                    title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-800/50 rounded-full flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined text-4xl text-gray-400 opacity-50">campaign</span>
                            </div>
                            <p class="text-lg font-bold text-gray-600 dark:text-gray-300">Pengumuman belum dibuat</p>
                            <p class="text-sm mt-1 text-gray-400 dark:text-gray-500 mt-2 max-w-sm">Belum ada informasi yang dipublikasikan. Klik "Buat Pengumuman" untuk mulai menambahkan informasi.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="p-4">
        <?php echo e($pengumumans->links()); ?>

    </div>
</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // SweetAlert Delete Confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data pengumuman ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#7a1621',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#f3f4f6' : '#1f293b',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/admin/pengumuman/index.blade.php ENDPATH**/ ?>