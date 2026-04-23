<?php $__env->startSection('title', 'Data Ruangan Kelas'); ?>
<?php $__env->startSection('page-title', 'Data Ruangan Kelas'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-6 flex flex-col items-start md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-door-open mr-3 text-maroon"></i>
                Data Ruangan Kelas
            </h2>
            <p class="text-gray-600 text-sm mt-1">Kelola data ruangan untuk perkuliahan</p>
        </div>
        <div class="flex-shrink-0">
            <div class="flex space-x-2">
                <a href="<?php echo e(route('admin.ruangan.create')); ?>"
                    class="bg-maroon text-white hover:bg-red-900 px-6 py-3 rounded-lg transition flex items-center shadow-md">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Ruangan
                </a>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="mb-6 bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <form method="GET" action="<?php echo e(route('admin.ruangan.index')); ?>" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Ruangan</label>
                    <input type="text" name="search" placeholder="Kode / Nama / Gedung..." 
                        value="<?php echo e(request('search')); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent">
                </div>

                <!-- Filter Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent">
                        <option value="">Semua Kategori</option>
                        <?php $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($kat->id); ?>" <?php echo e(request('kategori') == $kat->id ? 'selected' : ''); ?>>
                                <?php echo e($kat->nama_kategori); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Filter Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-maroon focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="aktif" <?php echo e(request('status') == 'aktif' ? 'selected' : ''); ?>>Aktif</option>
                        <option value="nonaktif" <?php echo e(request('status') == 'nonaktif' ? 'selected' : ''); ?>>Nonaktif</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="px-4 py-2 bg-maroon text-white rounded-lg hover:bg-red-900 transition flex items-center">
                        <i class="fas fa-search mr-2"></i>
                        Cari
                    </button>
                    <a href="<?php echo e(route('admin.ruangan.index')); ?>" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition">
                        <i class="fas fa-redo mr-2"></i>
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">

        <?php if($ruangans->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-maroon text-white">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                Kode Ruangan
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                Nama Ruangan
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                Kategori
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                Gedung/Lantai
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                Kapasitas
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $ruangans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ruangan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-maroon"><?php echo e($ruangan->kode_ruangan); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($ruangan->nama_ruangan); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($ruangan->kategori): ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold <?php echo e($ruangan->kategori->badge_color); ?>">
                                            <i class="fas <?php echo e($ruangan->kategori->icon); ?> mr-1"></i>
                                            <?php echo e($ruangan->kategori->nama_kategori); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-500">Tidak dikategorisasi</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <?php echo e($ruangan->gedung ? $ruangan->gedung : '-'); ?>

                                        <?php echo e($ruangan->lantai ? ' - Lantai ' . $ruangan->lantai : ''); ?>

                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-users text-gray-400 mr-1"></i>
                                        <?php echo e($ruangan->kapasitas); ?> orang
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        <?php echo e($ruangan->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                        <i class="fas <?php echo e($ruangan->status == 'aktif' ? 'fa-check' : 'fa-times'); ?> mr-1"></i>
                                        <?php echo e(ucfirst($ruangan->status)); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center space-x-2">
                                        <a href="<?php echo e(route('admin.ruangan.show', $ruangan)); ?>" 
                                            class="text-blue-600 hover:text-blue-900 transition-colors"
                                            title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.ruangan.edit', $ruangan)); ?>" 
                                            class="text-yellow-600 hover:text-yellow-900 transition-colors"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.ruangan.destroy', $ruangan)); ?>" 
                                              method="POST" 
                                              class="inline delete-form">
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
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <?php echo e($ruangans->links()); ?>

            </div>
        <?php else: ?>
            <div class="p-12 text-center text-gray-500">
                <i class="fas fa-door-open text-4xl text-gray-300 mb-4"></i>
                <p class="text-lg font-semibold">Belum Ada Data Ruangan</p>
                <p class="text-sm mb-4">Silakan tambah ruangan kelas untuk memulai</p>
                <a href="<?php echo e(route('admin.ruangan.create')); ?>" 
                    class="bg-maroon text-white px-6 py-2 rounded-lg hover:bg-red-900 transition">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Ruangan
                </a>
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
                        text: "Data Ruangan ini akan dihapus permanen!",
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
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/admin/master-data/ruangan/index.blade.php ENDPATH**/ ?>