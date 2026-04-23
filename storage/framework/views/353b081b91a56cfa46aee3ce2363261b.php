<?php $__env->startSection('title', 'Detail Pengajuan'); ?>

<?php $__env->startSection('content'); ?>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full font-inter">

        
        <div class="mb-6">
            <a href="<?php echo e(route('admin.pengajuan.index')); ?>" 
                class="inline-flex items-center gap-2 text-text-secondary hover:text-text-primary transition-colors">
                <i class="fas fa-arrow-left"></i>
                <span class="font-medium">Kembali ke Daftar</span>
            </a>
        </div>

        
        <div class="bg-white dark:bg-bg-card rounded-2xl shadow-sm border border-border-color p-6 mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-text-primary mb-2">Detail Pengajuan #<?php echo e($pengajuan->id); ?></h1>
                    <p class="text-text-secondary">Diajukan pada <?php echo e($pengajuan->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm')); ?></p>
                </div>
                <div>
                    <?php echo $pengajuan->status_badge; ?>

                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-2 space-y-6">
                
                
                <div class="bg-white dark:bg-bg-card rounded-2xl shadow-sm border border-border-color overflow-hidden">
                    <div class="px-6 py-4 bg-primary/5 border-b border-primary/10">
                        <h2 class="font-bold text-text-primary">Informasi Pengajuan</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-text-muted uppercase mb-1">Jenis Pengajuan</label>
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium 
                                <?php switch($pengajuan->jenis):
                                    case ('cuti'): ?> bg-orange-50 text-orange-700 <?php break; ?>
                                    <?php case ('dispensasi'): ?> bg-purple-50 text-purple-700 <?php break; ?>
                                    <?php case ('izin_penelitian'): ?> bg-teal-50 text-teal-700 <?php break; ?>
                                    <?php default: ?> bg-blue-50 text-blue-700
                                <?php endswitch; ?>">
                                <i class="fas
                                    <?php switch($pengajuan->jenis):
                                        case ('cuti'): ?> fa-pause <?php break; ?>
                                        <?php case ('dispensasi'): ?> fa-calendar-times <?php break; ?>
                                        <?php case ('izin_penelitian'): ?> fa-flask <?php break; ?>
                                        <?php default: ?> fa-file-signature
                                    <?php endswitch; ?>"></i>
                                <?php echo e($pengajuan->jenis_label); ?>

                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-text-muted uppercase mb-1">Keterangan / Alasan</label>
                            <p class="text-text-primary bg-gray-50 dark:bg-bg-hover rounded-lg p-4 leading-relaxed">
                                <?php echo e($pengajuan->keterangan); ?>

                            </p>
                        </div>

                        <?php if($pengajuan->file_path): ?>
                            <div>
                                <label class="block text-xs font-semibold text-text-muted uppercase mb-2">Dokumen Pendukung</label>
                                <a href="<?php echo e(Storage::url($pengajuan->file_path)); ?>" target="_blank" 
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium text-text-primary transition-colors">
                                    <i class="fas fa-paperclip text-primary"></i>
                                    Lihat Dokumen
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($pengajuan->nomor_surat): ?>
                            <div>
                                <label class="block text-xs font-semibold text-text-muted uppercase mb-1">Nomor Surat</label>
                                <p class="text-text-primary font-mono font-semibold"><?php echo e($pengajuan->nomor_surat); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if($pengajuan->rejected_reason): ?>
                            <div>
                                <label class="block text-xs font-semibold text-text-muted uppercase mb-1">Alasan Penolakan</label>
                                <p class="text-red-700 bg-red-50 border-l-4 border-red-400 rounded p-4 italic">
                                    "<?php echo e($pengajuan->rejected_reason); ?>"
                                </p>
                            </div>
                        <?php endif; ?>

                        <?php if($pengajuan->payload_template && count($pengajuan->payload_template)): ?>
                            <div class="pt-4 border-t border-border-color">
                                <label class="block text-xs font-semibold text-text-muted uppercase mb-3">Data Isian Surat</label>
                                <dl class="space-y-2">
                                    <?php $__currentLoopData = $pengajuan->payload_template; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex gap-2">
                                            <dt class="text-xs text-text-muted w-40 shrink-0 capitalize"><?php echo e(str_replace('_', ' ', $key)); ?></dt>
                                            <dd class="text-sm text-text-primary font-medium"><?php echo e($val); ?></dd>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </dl>
                            </div>
                        <?php endif; ?>

                        <?php if($pengajuan->approved_by): ?>
                            <div class="pt-4 border-t border-border-color">
                                <label class="block text-xs font-semibold text-text-muted uppercase mb-2">Informasi Approval</label>
                                <div class="flex items-center gap-3 text-sm">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">
                                        <?php echo e(substr($pengajuan->approver->name ?? '', 0, 1)); ?>

                                    </div>
                                    <div>
                                        <p class="font-medium text-text-primary"><?php echo e($pengajuan->approver->name ?? 'Admin'); ?></p>
                                        <?php if($pengajuan->approved_at): ?>
                                            <p class="text-xs text-text-muted"><?php echo e($pengajuan->approved_at->locale('id')->isoFormat('D MMMM YYYY, HH:mm')); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <?php if($pengajuan->generated_doc_path): ?>
                    <div class="bg-white dark:bg-bg-card rounded-2xl shadow-sm border border-blue-200">
                        <div class="px-6 py-4 bg-blue-50 border-b border-blue-200">
                            <h2 class="font-bold text-blue-800 flex items-center gap-2">
                                <i class="fas fa-file-word"></i>
                                Dokumen DOCX (Digenerate Mahasiswa)
                            </h2>
                        </div>
                        <div class="p-6">
                            <a href="<?php echo e(route('admin.pengajuan.download-generated', $pengajuan->id)); ?>" 
                                class="btn bg-blue-600 text-white hover:bg-blue-700 rounded-xl px-5 py-2.5 flex items-center gap-2 w-fit">
                                <i class="fas fa-download"></i>
                                Download DOCX
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                
                <?php if($pengajuan->signed_doc_path): ?>
                    <div class="bg-white dark:bg-bg-card rounded-2xl shadow-sm border border-indigo-200">
                        <div class="px-6 py-4 bg-indigo-50 border-b border-indigo-200">
                            <h2 class="font-bold text-indigo-800 flex items-center gap-2">
                                <i class="fas fa-file-signature"></i>
                                Dokumen Bertanda Tangan (Upload Mahasiswa)
                            </h2>
                        </div>
                        <div class="p-6">
                            <a href="<?php echo e(route('admin.pengajuan.download-signed', $pengajuan->id)); ?>" 
                                class="btn bg-indigo-600 text-white hover:bg-indigo-700 rounded-xl px-5 py-2.5 flex items-center gap-2 w-fit">
                                <i class="fas fa-download"></i>
                                Download Dokumen TTD
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                
                <?php if($pengajuan->status === 'approved' && $pengajuan->file_surat): ?>
                    <div class="bg-white dark:bg-bg-card rounded-2xl shadow-sm border border-green-200">
                        <div class="px-6 py-4 bg-green-50 border-b border-green-200">
                            <h2 class="font-bold text-green-800 flex items-center gap-2">
                                <i class="fas fa-file-pdf"></i>
                                Surat Resmi (Disetujui)
                            </h2>
                        </div>
                        <div class="p-6">
                            <a href="<?php echo e(route('admin.pengajuan.download', $pengajuan->id)); ?>" 
                                class="btn bg-green-600 text-white hover:bg-green-700 rounded-xl px-5 py-2.5 flex items-center gap-2 w-fit">
                                <i class="fas fa-download"></i>
                                Download Surat Resmi
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                
                <?php if($pengajuan->revisions->count() > 0): ?>
                    <div class="bg-white dark:bg-bg-card rounded-2xl shadow-sm border border-border-color overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-border-color">
                            <h2 class="font-bold text-text-primary flex items-center gap-2">
                                <i class="fas fa-history text-gray-500"></i>
                                Riwayat Revisi (<?php echo e($pengajuan->revisions->count()); ?>x)
                            </h2>
                        </div>
                        <div class="p-6">
                            <ol class="relative border-l border-gray-200 dark:border-gray-700 space-y-6">
                                <?php $__currentLoopData = $pengajuan->revisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="ml-4">
                                        <div class="absolute w-3 h-3 bg-gray-300 rounded-full -left-1.5 border border-white"></div>
                                        <div class="text-xs text-text-muted mb-1">Revisi #<?php echo e($rev->revision_no); ?></div>
                                        <?php if($rev->note_from_admin): ?>
                                            <p class="text-sm bg-red-50 border-l-4 border-red-300 rounded p-2 text-red-700 mb-2">
                                                <span class="font-semibold">Catatan admin:</span> <?php echo e($rev->note_from_admin); ?>

                                            </p>
                                        <?php endif; ?>
                                        <?php if($rev->note_from_mahasiswa): ?>
                                            <p class="text-sm bg-blue-50 border-l-4 border-blue-300 rounded p-2 text-blue-700 mb-2">
                                                <span class="font-semibold">Catatan mahasiswa:</span> <?php echo e($rev->note_from_mahasiswa); ?>

                                            </p>
                                        <?php endif; ?>
                                        <?php if($rev->signed_doc_path): ?>
                                            <a href="<?php echo e(Storage::url($rev->signed_doc_path)); ?>" target="_blank"
                                                class="inline-flex items-center gap-1 text-xs px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded-lg text-text-secondary transition-colors">
                                                <i class="fas fa-paperclip"></i> Lihat Dokumen TTD
                                            </a>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ol>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            
            <div class="space-y-6">
                
                
                <div class="bg-white dark:bg-bg-card rounded-2xl shadow-sm border border-border-color overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-border-color">
                        <h2 class="font-bold text-text-primary">Data Mahasiswa</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4 pb-4 border-b border-border-color">
                            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-lg">
                                <?php echo e(substr($pengajuan->mahasiswa->user->name, 0, 1)); ?>

                            </div>
                            <div>
                                <h3 class="font-bold text-text-primary"><?php echo e($pengajuan->mahasiswa->user->name); ?></h3>
                                <p class="text-xs text-text-muted"><?php echo e($pengajuan->mahasiswa->nim); ?></p>
                            </div>
                        </div>
                        
                        <div class="space-y-3 text-sm">
                            <div>
                                <label class="block text-xs font-semibold text-text-muted mb-0.5">Program Studi</label>
                                <p class="text-text-primary"><?php echo e($pengajuan->mahasiswa->prodi ?? '-'); ?></p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-text-muted mb-0.5">Fakultas</label>
                                <p class="text-text-primary"><?php echo e($pengajuan->mahasiswa->fakultas ?? '-'); ?></p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-text-muted mb-0.5">Semester</label>
                                <p class="text-text-primary"><?php echo e($pengajuan->mahasiswa->semester ?? '-'); ?></p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-text-muted mb-0.5">Email</label>
                                <p class="text-text-primary text-xs"><?php echo e($pengajuan->mahasiswa->user->email); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                
                <?php if($pengajuan->status === 'submitted'): ?>
                    <div class="bg-white dark:bg-bg-card rounded-2xl shadow-sm border border-border-color overflow-hidden" x-data>
                        <div class="px-6 py-4 bg-yellow-50 border-b border-yellow-200">
                            <h2 class="font-bold text-yellow-800">Tindakan</h2>
                        </div>
                        <div class="p-6 space-y-3">
                            <button @click="$dispatch('open-modal', 'approve-modal')" 
                                class="w-full btn bg-green-600 text-white hover:bg-green-700 rounded-xl px-5 py-3 flex items-center justify-center gap-2 font-medium">
                                <i class="fas fa-check-circle"></i>
                                Setujui Pengajuan
                            </button>
                            <button @click="$dispatch('open-modal', 'reject-modal')" 
                                class="w-full btn bg-red-600 text-white hover:bg-red-700 rounded-xl px-5 py-3 flex items-center justify-center gap-2 font-medium">
                                <i class="fas fa-times-circle"></i>
                                Tolak Pengajuan
                            </button>
                        </div>
                    </div>
                <?php endif; ?>

                
                <?php if(in_array($pengajuan->status, ['draft', 'generated'])): ?>
                    <div class="bg-white dark:bg-bg-card rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h2 class="font-bold text-gray-700">Status</h2>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-text-secondary">
                                <?php if($pengajuan->status === 'draft'): ?>
                                    Mahasiswa belum selesai mengisi formulir.
                                <?php else: ?>
                                    Dokumen sudah digenerate. Menunggu mahasiswa mengunggah dokumen bertanda tangan.
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    
    <div x-data="{ open: false }" 
         x-show="open" 
         @open-modal.window="if ($event.detail === 'approve-modal') open = true" 
         @keydown.escape.window="open = false"
         class="relative z-50" 
         style="display: none;">
        
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" x-show="open" x-transition.opacity></div>

        <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="open"
                     x-transition
                     @click.outside="open = false"
                     class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-bg-card shadow-2xl w-full max-w-md border border-green-200">
                    
                    <div class="bg-green-50 px-6 py-5 border-b border-green-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-green-600 flex items-center justify-center text-white">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-green-900">Setujui Pengajuan</h3>
                                <p class="text-xs text-green-700">Surat akan otomatis digenerate</p>
                            </div>
                        </div>
                    </div>

                    <form action="<?php echo e(route('admin.pengajuan.approve', $pengajuan->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="p-6">
                            <p class="text-text-secondary mb-4">Anda yakin ingin menyetujui pengajuan ini? Nomor surat akan otomatis digenerate.</p>
                            
                            <div>
                                <label class="block text-sm font-medium text-text-secondary mb-2">Catatan (Opsional)</label>
                                <textarea name="admin_note" rows="3" 
                                    class="w-full rounded-xl border-border-color py-2.5 px-4 text-sm focus:ring-2 focus:ring-green-500"
                                    placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex gap-3 border-t border-border-color">
                            <button type="submit" class="flex-1 btn bg-green-600 text-white hover:bg-green-700 rounded-xl px-5 py-2.5 font-semibold">
                                <i class="fas fa-check mr-2"></i> Ya, Setujui
                            </button>
                            <button type="button" @click="open = false" class="flex-1 btn bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl px-5 py-2.5 font-semibold">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div x-data="{ open: false }" 
         x-show="open" 
         @open-modal.window="if ($event.detail === 'reject-modal') open = true" 
         @keydown.escape.window="open = false"
         class="relative z-50" 
         style="display: none;">
        
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" x-show="open" x-transition.opacity></div>

        <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="open"
                     x-transition
                     @click.outside="open = false"
                     class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-bg-card shadow-2xl w-full max-w-md border border-red-200">
                    
                    <div class="bg-red-50 px-6 py-5 border-b border-red-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-red-600 flex items-center justify-center text-white">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-red-900">Tolak Pengajuan</h3>
                                <p class="text-xs text-red-700">Mahasiswa akan menerima notifikasi</p>
                            </div>
                        </div>
                    </div>

                    <form action="<?php echo e(route('admin.pengajuan.reject', $pengajuan->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="p-6">
                            <p class="text-text-secondary mb-4">Berikan alasan penolakan yang jelas agar mahasiswa memahami keputusan Anda.</p>
                            
                            <div>
                                <label class="block text-sm font-medium text-text-secondary mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                                <textarea name="rejected_reason" rows="4" required
                                    class="w-full rounded-xl border-border-color py-2.5 px-4 text-sm focus:ring-2 focus:ring-red-500"
                                    placeholder="Contoh: Dokumen pendukung tidak lengkap..."></textarea>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex gap-3 border-t border-border-color">
                            <button type="submit" class="flex-1 btn bg-red-600 text-white hover:bg-red-700 rounded-xl px-5 py-2.5 font-semibold">
                                <i class="fas fa-times mr-2"></i> Ya, Tolak
                            </button>
                            <button type="button" @click="open = false" class="flex-1 btn bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl px-5 py-2.5 font-semibold">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/admin/pengajuan/show.blade.php ENDPATH**/ ?>