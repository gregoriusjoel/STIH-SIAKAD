<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-4 max-w-none">
    <div class="space-y-6">
        
        <div class="flex items-center justify-between">
            <a href="<?php echo e(route('mahasiswa.pembayaran.index')); ?>" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-[#8B1538] transition-colors group">
                <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Tagihan
            </a>
            
            <span class="px-4 py-1.5 text-xs font-bold uppercase tracking-wider rounded-full shadow-sm
                <?php echo e($invoice->status === 'LUNAS' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : ''); ?>

                <?php echo e($invoice->status === 'PUBLISHED' ? 'bg-blue-100 text-blue-700 border border-blue-200' : ''); ?>

                <?php echo e($invoice->status === 'IN_INSTALLMENT' ? 'bg-amber-100 text-amber-700 border border-amber-200' : ''); ?>">
                <?php echo e($invoice->status_label); ?>

            </span>
        </div>

        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-gradient-to-r from-[#8B1538] to-[#6A102B] px-8 py-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                </div>
                <h1 class="text-3xl font-bold tracking-tight mb-2">Semester <?php echo e($invoice->semester); ?> - <?php echo e($invoice->tahun_ajaran); ?></h1>
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 text-white/90 text-sm">
                    <p class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        <?php echo e($invoice->student->user->name ?? 'N/A'); ?> (<?php echo e($invoice->student->nim ?? 'N/A'); ?>)
                    </p>
                    <?php if($invoice->sks_ambil): ?>
                        <span class="hidden sm:inline text-white/50">•</span>
                        <p>SKS Ambil: <span class="font-semibold"><?php echo e($invoice->sks_ambil); ?></span></p>
                        <?php if($invoice->paket_sks_bayar): ?>
                            <span class="hidden sm:inline text-white/50">•</span>
                            <p>Paket Bayar: <span class="font-semibold"><?php echo e($invoice->paket_sks_bayar); ?></span></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-slate-50 p-5 rounded-xl border border-slate-100">
                        <p class="text-slate-500 text-sm font-medium mb-1">Total Tagihan</p>
                        <p class="text-2xl font-bold text-slate-800">Rp <?php echo e(number_format($invoice->total_tagihan, 0, ',', '.')); ?></p>
                    </div>
                    <?php if($invoice->status === 'IN_INSTALLMENT' || $invoice->status === 'LUNAS'): ?>
                        <div class="bg-emerald-50 p-5 rounded-xl border border-emerald-100">
                            <p class="text-emerald-600 text-sm font-medium mb-1">Total Terbayar</p>
                            <p class="text-2xl font-bold text-emerald-700">Rp <?php echo e(number_format($invoice->total_paid, 0, ',', '.')); ?></p>
                        </div>
                        <div class="bg-red-50 p-5 rounded-xl border border-red-100">
                            <p class="text-red-600 text-sm font-medium mb-1">Sisa Tagihan</p>
                            <p class="text-2xl font-bold text-red-700">Rp <?php echo e(number_format($invoice->total_tagihan - $invoice->total_paid, 0, ',', '.')); ?></p>
                        </div>
                    <?php else: ?>
                        <div class="bg-slate-50 p-5 rounded-xl border border-slate-100 md:col-span-2 flex items-center justify-center text-slate-400 text-sm italic">
                            Belum ada pembayaran
                        </div>
                    <?php endif; ?>
                </div>

                <?php if($invoice->notes): ?>
                    <div class="mt-6 p-4 bg-amber-50 border border-amber-100 rounded-lg flex gap-3">
                        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-amber-800">
                            <span class="font-semibold">Catatan:</span> <?php echo e($invoice->notes); ?>

                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if($invoice->installmentRequest): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-slate-800">Status Pengajuan Cicilan</h2>
                    <span class="px-3 py-1 text-xs font-bold rounded-full uppercase tracking-wide
                        <?php echo e($invoice->installmentRequest->status === 'SUBMITTED' ? 'bg-blue-100 text-blue-700' : ''); ?>

                        <?php echo e($invoice->installmentRequest->status === 'APPROVED' ? 'bg-emerald-100 text-emerald-700' : ''); ?>

                        <?php echo e($invoice->installmentRequest->status === 'REJECTED' ? 'bg-red-100 text-red-700' : ''); ?>">
                        <?php echo e($invoice->installmentRequest->status_label); ?>

                    </span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-sm text-slate-500 mb-1">Rencana Cicilan</p>
                            <div class="flex items-center gap-2">
                                <span class="text-xl font-bold text-slate-800"><?php echo e($invoice->installmentRequest->requested_terms); ?>x</span>
                                <span class="text-sm text-slate-400">Diminta</span>
                                <?php if($invoice->installmentRequest->approved_terms): ?>
                                    <span class="mx-2 text-slate-300">|</span>
                                    <span class="text-xl font-bold text-emerald-600"><?php echo e($invoice->installmentRequest->approved_terms); ?>x</span>
                                    <span class="text-sm text-emerald-600">Disetujui</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if($invoice->installmentRequest->rejection_reason): ?>
                            <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                                <p class="text-sm font-medium text-red-800 mb-1">Alasan Penolakan</p>
                                <p class="text-sm text-red-600"><?php echo e($invoice->installmentRequest->rejection_reason); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        
        <?php if($invoice->installments->isNotEmpty()): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-800">Daftar Cicilan</h2>
                    <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-1 rounded-md">
                        <?php echo e($invoice->installments->count()); ?> Pembayaran
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-center w-16">No</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">Nominal</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Jatuh Tempo</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Status</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Tanggal Bayar</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__currentLoopData = $invoice->installments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-center font-bold text-slate-600">
                                        <?php echo e($installment->installment_no); ?>

                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-slate-800">
                                        Rp <?php echo e(number_format($installment->amount, 0, ',', '.')); ?>

                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        <?php echo e($installment->due_date ? $installment->due_date->format('d M Y') : '-'); ?>

                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                                            <?php echo e($installment->status === 'PAID' ? 'bg-emerald-100 text-emerald-700' : ''); ?>

                                            <?php echo e($installment->status === 'UNPAID' ? 'bg-slate-100 text-slate-600' : ''); ?>

                                            <?php echo e($installment->status === 'WAITING_VERIFICATION' ? 'bg-amber-100 text-amber-700' : ''); ?>

                                            <?php echo e($installment->status === 'REJECTED_PAYMENT' ? 'bg-red-100 text-red-700' : ''); ?>">
                                            <?php if($installment->status === 'PAID'): ?>
                                                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            <?php endif; ?>
                                            <?php echo e($installment->status_label); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        <?php echo e($installment->paid_at ? $installment->paid_at->format('d M Y') : '-'); ?>

                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <?php if($installment->status === 'UNPAID' || $installment->status === 'REJECTED_PAYMENT'): ?>
                                            <?php if($installment->canBePaid()): ?>
                                                <a href="<?php echo e(route('mahasiswa.payment-proofs.create', $installment)); ?>" class="inline-flex items-center px-3 py-1.5 bg-[#8B1538] text-white text-xs font-bold rounded-lg hover:bg-[#6A102B] transition-colors shadow-sm hover:shadow">
                                                    Upload Bukti
                                                </a>
                                            <?php else: ?>
                                                <span class="text-xs text-slate-400 italic">Bayar cicilan sebelumnya</span>
                                            <?php endif; ?>
                                        <?php elseif($installment->status === 'WAITING_VERIFICATION'): ?>
                                            <span class="text-xs font-medium text-amber-600">Verifikasi...</span>
                                        <?php elseif($installment->status === 'PAID'): ?>
                                            <span class="text-xs font-bold text-emerald-600">Lunas</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        
        <?php if($invoice->status === 'PUBLISHED' && !$invoice->installmentRequest): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 text-center">
                <h2 class="text-lg font-bold text-slate-800 mb-2">Pilih Metode Pembayaran</h2>
                <p class="text-slate-500 mb-6 max-w-lg mx-auto">Anda dapat membayar penuh sekaligus, atau mengajukan pembayaran bertahap (cicilan) untuk tagihan ini.</p>

                <?php if($pendingFullProof): ?>
                    <div class="mb-6 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-50 border border-blue-100 text-blue-700 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Bukti bayar penuh sudah diupload dan sedang diverifikasi.
                    </div>
                <?php endif; ?>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo e(route('mahasiswa.installment-requests.create', $invoice)); ?>" class="inline-flex items-center justify-center px-6 py-3 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition-colors shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Ajukan Cicilan
                    </a>
                    <?php if(!$pendingFullProof): ?>
                        <a href="<?php echo e(route('mahasiswa.payment-proofs.full.create', $invoice)); ?>" class="inline-flex items-center justify-center px-6 py-3 bg-[#8B1538] text-white font-bold rounded-xl hover:bg-[#6A102B] transition-colors shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a5 5 0 00-10 0v2m-2 0h14l-1 10H6L5 9z" /></svg>
                            Bayar Full
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/page/mahasiswa/pembayaran/invoices/show.blade.php ENDPATH**/ ?>