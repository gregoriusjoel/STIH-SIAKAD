<?php $__env->startSection('title', 'Master Data Fakultas'); ?>
<?php $__env->startSection('page-title', 'Master Data Fakultas'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-6 flex flex-col items-start md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-university mr-3 text-maroon"></i>
                Master Data Fakultas
            </h2>
            <p class="text-gray-600 text-sm mt-1">Kelola data fakultas yang tersedia di sistem</p>
        </div>
        <div class="flex-shrink-0">
            <a href="<?php echo e(route('admin.fakultas.create')); ?>"
                class="bg-maroon text-white hover:bg-red-900 px-6 py-3 rounded-lg transition flex items-center shadow-md transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i>
                Tambah Fakultas
            </a>
        </div>
    </div>

    <?php if($fakultas->isEmpty()): ?>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                <div>
                    <h3 class="text-blue-800 font-semibold">Belum Ada Data Fakultas</h3>
                    <p class="text-blue-700 text-sm mt-1">
                        Silakan tambahkan Fakultas baru untuk melanjutkan setup Program Studi.
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-maroon text-white">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            No
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            Kode Fakultas
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-university mr-2"></i>Nama Fakultas
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-toggle-on mr-2"></i>Status
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $fakultas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?php echo e($fakultas->firstItem() + $index); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($item->kode_fakultas); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($item->nama_fakultas); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    <?php echo e($item->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                    <i class="fas <?php echo e($item->status == 'aktif' ? 'fa-check' : 'fa-times'); ?> mr-1"></i>
                                    <?php echo e(ucfirst($item->status)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="<?php echo e(route('admin.fakultas.show', $item->id)); ?>" 
                                        class="text-blue-600 hover:text-blue-900 transition-colors" 
                                        title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('admin.fakultas.edit', $item->id)); ?>" 
                                        class="text-yellow-600 hover:text-yellow-900 transition-colors"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.fakultas.destroy', $item->id)); ?>" 
                                        method="POST" class="inline delete-form">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                            class="text-red-600 hover:text-red-900 transition-colors"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-university text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-semibold">Belum Ada Data Fakultas</p>
                                    <?php if($prodiCount > 0): ?>
                                        <p class="text-sm">Silakan tambahkan fakultas baru</p>
                                    <?php else: ?>
                                        <p class="text-sm">Silakan tambahkan Prodi terlebih dahulu</p>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($fakultas->hasPages()): ?>
            <div class="bg-gray-50 px-6 py-4">
                <?php echo e($fakultas->links()); ?>

            </div>
        <?php endif; ?>
    </div>
    
    <?php $__env->startPush('scripts'); ?>
        <script>
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data Fakultas ini akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7a1621',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/admin/master-data/fakultas/index.blade.php ENDPATH**/ ?>