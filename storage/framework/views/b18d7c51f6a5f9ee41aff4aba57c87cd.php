<?php $__env->startSection('title', 'Detail Ruangan'); ?>
<?php $__env->startSection('page-title', 'Detail Ruangan'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-6 flex flex-col items-start md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-door-open mr-3 text-maroon"></i>
                Detail Ruangan: <?php echo e($ruangan->kode_ruangan); ?>

            </h2>
            <p class="text-gray-600 text-sm mt-1">Informasi lengkap ruangan <?php echo e($ruangan->nama_ruangan); ?></p>
        </div>
        <div class="flex-shrink-0">
            <div class="flex space-x-2">
                <a href="<?php echo e(route('admin.ruangan.edit', $ruangan)); ?>"
                    class="bg-yellow-600 text-white hover:bg-yellow-700 px-6 py-3 rounded-lg transition flex items-center shadow-md">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
                <a href="<?php echo e(route('admin.ruangan.index')); ?>"
                    class="bg-gray-600 text-white hover:bg-gray-700 px-6 py-3 rounded-lg transition flex items-center shadow-md">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Info Ruangan -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-maroon border-b border-maroon">
                    <h3 class="text-lg font-semibold text-white">Informasi Ruangan</h3>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Kode Ruangan</label>
                            <div class="mt-1 text-lg font-semibold text-maroon">
                                <i class="fas fa-barcode mr-2"></i>
                                <?php echo e($ruangan->kode_ruangan); ?>

                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nama Ruangan</label>
                            <div class="mt-1 text-lg font-semibold text-gray-900">
                                <i class="fas fa-door-open mr-2 text-maroon"></i>
                                <?php echo e($ruangan->nama_ruangan); ?>

                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Gedung</label>
                            <div class="mt-1 text-sm text-gray-900">
                                <i class="fas fa-building mr-1"></i>
                                <?php echo e($ruangan->gedung ?: '-'); ?>

                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Lantai</label>
                            <div class="mt-1 text-sm text-gray-900">
                                <i class="fas fa-layer-group mr-1"></i>
                                <?php echo e($ruangan->lantai ? 'Lantai ' . $ruangan->lantai : '-'); ?>

                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Kapasitas</label>
                            <div class="mt-1 text-sm text-gray-900">
                                <i class="fas fa-users mr-1"></i>
                                <?php echo e($ruangan->kapasitas); ?> orang
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Kategori</label>
                            <div class="mt-1">
                                <?php if($ruangan->kategori): ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo e($ruangan->kategori->badge_color); ?>">
                                        <i class="fas <?php echo e($ruangan->kategori->icon); ?> mr-1"></i>
                                        <?php echo e($ruangan->kategori->nama_kategori); ?>

                                    </span>
                                    <?php if($ruangan->kategori->deskripsi): ?>
                                        <p class="text-xs text-gray-500 mt-1"><?php echo e($ruangan->kategori->deskripsi); ?></p>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-sm text-gray-500">Tidak dikategorisasi</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    <?php echo e($ruangan->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                    <i class="fas <?php echo e($ruangan->status == 'aktif' ? 'fa-check' : 'fa-times'); ?> mr-1"></i>
                                    <?php echo e(ucfirst($ruangan->status)); ?>

                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Dibuat Pada</label>
                            <div class="mt-1 text-sm text-gray-900">
                                <i class="fas fa-calendar mr-1"></i>
                                <?php echo e($ruangan->created_at->format('d F Y H:i')); ?>

                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500">Terakhir Diubah</label>
                            <div class="mt-1 text-sm text-gray-900">
                                <i class="fas fa-clock mr-1"></i>
                                <?php echo e($ruangan->updated_at->format('d F Y H:i')); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Penggunaan -->
        <div>
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-maroon border-b border-maroon">
                    <h3 class="text-lg font-semibold text-white">Statistik Penggunaan</h3>
                </div>

                <div class="p-6 space-y-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600"><?php echo e($ruangan->jadwals->count()); ?></div>
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-calendar-check mr-1"></i>
                            Jadwal Aktif
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-600"><?php echo e($ruangan->jadwalProposals->count()); ?></div>
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-clock mr-1"></i>
                            Pengajuan Jadwal
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Aktif -->
    <?php if($ruangan->jadwals->count() > 0): ?>
        <div class="mt-6 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-maroon border-b border-maroon">
                <h3 class="text-lg font-semibold text-white">
                    <i class="fas fa-calendar-check mr-2"></i>
                    Jadwal Aktif di Ruangan Ini
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mata Kuliah
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kelas
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hari & Waktu
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $ruangan->jadwals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($jadwal->kelas->mataKuliah->nama_mk ?? '-'); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e($jadwal->kelas->mataKuliah->kode_mk ?? '-'); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo e($jadwal->kelas->kode_kelas ?? '-'); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo e($jadwal->hari); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e(substr($jadwal->jam_mulai, 0, 5)); ?> - <?php echo e(substr($jadwal->jam_selesai, 0, 5)); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        <?php echo e($jadwal->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>">
                                        <?php echo e(ucfirst($jadwal->status)); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <!-- Pengajuan Jadwal -->
    <?php if($ruangan->jadwalProposals->count() > 0): ?>
        <div class="mt-6 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-maroon border-b border-maroon">
                <h3 class="text-lg font-semibold text-white">
                    <i class="fas fa-clock mr-2"></i>
                    Pengajuan Jadwal di Ruangan Ini
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mata Kuliah
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hari & Waktu
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $ruangan->jadwalProposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($proposal->mataKuliah->nama_mk ?? '-'); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e($proposal->mataKuliah->kode_mk ?? '-'); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo e($proposal->hari); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e(substr($proposal->jam_mulai, 0, 5)); ?> - <?php echo e(substr($proposal->jam_selesai, 0, 5)); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <?php if(in_array($proposal->status, ['pending_dosen'])): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu Dosen</span>
                                    <?php elseif(in_array($proposal->status, ['approved_dosen','pending_admin'])): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Menunggu Admin</span>
                                    <?php elseif($proposal->status === 'approved_admin'): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <?php if($ruangan->jadwals->count() == 0 && $ruangan->jadwalProposals->count() == 0): ?>
        <div class="mt-6 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="p-12 text-center text-gray-500">
                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                <p class="text-lg font-semibold">Ruangan Belum Digunakan</p>
                <p class="text-sm">Ruangan ini belum memiliki jadwal aktif atau pengajuan jadwal</p>
            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/admin/master-data/ruangan/show.blade.php ENDPATH**/ ?>