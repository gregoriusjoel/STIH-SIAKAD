<?php $__env->startSection('title', 'Manajemen Skripsi'); ?>
<?php $__env->startSection('page-title', 'Manajemen Skripsi'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    
    <div class="relative bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm overflow-hidden group">
        
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-red-900/[0.03] rounded-full blur-3xl transition-all duration-700 group-hover:scale-110"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-amber-500/[0.02] rounded-full blur-3xl transition-all duration-700 group-hover:scale-110"></div>

        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-red-900 via-[#800020] to-[#5a0015] flex items-center justify-center shadow-lg shadow-red-900/20 shrink-0">
                    <span class="material-symbols-outlined text-white text-3xl font-light">account_tree</span>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-gray-900 tracking-tight leading-none mb-2">Manajemen Skripsi</h1>
                    <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">Pusat Kendali & Monitoring Skripsi Mahasiswa</p>
                </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <div class="px-4 py-2 bg-gray-50 rounded-2xl border border-gray-100 flex items-center gap-2">
                    <span class="relative inline-flex items-center justify-center w-5 h-5">
                        <span class="absolute inline-flex h-4 w-4 rounded-full bg-red-700/30 animate-ping"></span>
                        <span class="material-symbols-outlined text-[18px] text-red-900 animate-pulse">radio_button_checked</span>
                    </span>
                    <span class="text-[11px] font-black text-gray-600 uppercase tracking-wider">Live Monitoring</span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <?php
            $metrics = [
                ['label' => 'Skripsi Masuk', 'count' => $proposals->where('status.value', 'PROPOSAL_SUBMITTED')->count(), 'color' => 'red', 'icon' => 'pending_actions'],
                ['label' => 'Aktif Bimbingan', 'count' => $activeBimbingan->count(), 'color' => 'blue', 'icon' => 'forum'],
                ['label' => 'Antrean Sidang', 'count' => $pendingSidang->count(), 'color' => 'orange', 'icon' => 'assignment_turned_in'],
                ['label' => 'Sidang Terjadwal', 'count' => $scheduled->count(), 'color' => 'indigo', 'icon' => 'calendar_month'],
                ['label' => 'Selesai', 'count' => $completed->count(), 'color' => 'emerald', 'icon' => 'verified_user'],
            ];
        ?>

        <?php $__currentLoopData = $metrics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 text-<?php echo e($m['color'] ?? 'gray'); ?>-500/5 group-hover:scale-110 transition-transform duration-700">
                <span class="material-symbols-outlined text-7xl font-light leading-none"><?php echo e($m['icon'] ?? 'analytics'); ?></span>
            </div>
            <div class="relative">
                <div class="w-10 h-10 rounded-xl bg-<?php echo e($m['color'] ?? 'gray'); ?>-50 flex items-center justify-center text-<?php echo e($m['color'] ?? 'gray'); ?>-600 mb-3 group-hover:scale-105 transition-transform border border-<?php echo e($m['color'] ?? 'gray'); ?>-100/50">
                    <span class="material-symbols-outlined text-[20px]"><?php echo e($m['icon'] ?? 'analytics'); ?></span>
                </div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 leading-none"><?php echo e($m['label'] ?? 'Metric'); ?></p>
                <p class="text-2xl font-black text-gray-900 tracking-tight leading-none"><?php echo e($m['count'] ?? 0); ?></p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="flex border-b border-gray-50 px-6 sm:px-8 bg-gray-50/50 backdrop-blur-sm overflow-x-auto">
            <?php $__currentLoopData = [
                ['tab'=>'proposal',  'label'=>'Skripsi Masuk', 'count'=>$proposals->count(), 'icon' => 'article'],
                ['tab'=>'bimbingan', 'label'=>'Bimbingan Aktif', 'count'=>$activeBimbingan->count(), 'icon' => 'forum'],
                ['tab'=>'sidang',    'label'=>'Daftar Sidang', 'count'=>$pendingSidang->count(), 'icon' => 'fact_check'],
                ['tab'=>'scheduled', 'label'=>'Terjadwal', 'count'=>$scheduled->count(), 'icon' => 'event'],
                ['tab'=>'revision',  'label'=>'Revisi Final', 'count'=>$revisions->count(), 'icon' => 'history_edu'],
                ['tab'=>'completed', 'label'=>'Selesai', 'count'=>$completed->count(), 'icon' => 'verified'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="?tab=<?php echo e($t['tab']); ?>"
                class="group flex items-center gap-2.5 px-6 py-5 text-sm font-black whitespace-nowrap transition-all relative
                    <?php echo e($tab === $t['tab'] ? 'text-red-900' : 'text-gray-400 hover:text-gray-700'); ?>">
                <span class="material-symbols-outlined text-[20px] group-hover:scale-110 transition-transform <?php echo e($tab === $t['tab'] ? 'fill-0' : 'font-light'); ?> text-<?php echo e($tab === $t['tab'] ? 'red-900' : 'gray-400'); ?>"><?php echo e($t['icon']); ?></span>
                <span class="uppercase tracking-widest text-[11px]"><?php echo e($t['label']); ?></span>
                <?php if($t['count'] > 0): ?>
                <span class="px-2 py-0.5 rounded-full text-[10px] <?php echo e($tab === $t['tab'] ? 'bg-red-900 text-white shadow-lg shadow-red-900/20' : 'bg-gray-100 text-gray-400 font-bold'); ?>">
                    <?php echo e($t['count']); ?>

                </span>
                <?php endif; ?>
                <?php if($tab === $t['tab']): ?>
                <div class="absolute bottom-0 left-6 right-6 h-0.5 bg-red-900 rounded-full shadow-[0_-2px_6px_rgba(153,27,27,0.4)]"></div>
                <?php endif; ?>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="p-6 sm:p-8 min-h-[400px] bg-white">
            
            <div class="grid gap-4">
                <?php if($tab === 'proposal'): ?>
                    <?php $__empty_1 = true; $__currentLoopData = $proposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skripsi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php 
                        $color = $skripsi->status->color(); 
                        $tColor = ['yellow' => 'amber', 'green' => 'emerald', 'blue' => 'blue', 'red' => 'red', 'purple' => 'purple', 'indigo' => 'indigo', 'gray' => 'gray', 'orange' => 'orange'][$color] ?? $color;
                    ?>
                    <div class="bg-white border border-gray-100 rounded-2xl p-4 sm:p-5 hover:border-red-100 hover:shadow-md hover:shadow-red-900/5 transition-all group flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-red-50 group-hover:text-red-900 transition-colors shrink-0 overflow-hidden relative border border-gray-100 group-hover:border-red-100">
                                <span class="material-symbols-outlined text-2xl font-light">account_circle</span>
                                <?php if($skripsi->mahasiswa?->user?->avatar): ?>
                                <img src="<?php echo e(Storage::url($skripsi->mahasiswa->user->avatar)); ?>" class="absolute inset-0 w-full h-full object-cover">
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1 leading-none">
                                    <h3 class="font-black text-gray-900 tracking-tight group-hover:text-red-900 transition-colors"><?php echo e($skripsi->mahasiswa?->user?->name ?? '-'); ?></h3>
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest truncate"><?php echo e($skripsi->mahasiswa?->nim ?? ''); ?></span>
                                </div>
                                <p class="text-sm text-gray-500 font-medium truncate italic leading-snug">"<?php echo e($skripsi->judul); ?>"</p>
                                <div class="flex items-center gap-3 mt-2">
                                    <div class="flex items-center gap-1.5 px-2 py-0.5 bg-gray-50 rounded-lg border border-gray-100">
                                        <span class="material-symbols-outlined text-[14px] text-gray-400">person</span>
                                        <span class="text-[9px] font-bold text-gray-500 uppercase tracking-tighter"><?php echo e($skripsi->requestedSupervisor?->nama ?? '-'); ?></span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-<?php echo e($tColor); ?>-50 text-<?php echo e($tColor); ?>-700 border border-<?php echo e($tColor); ?>-100/50">
                                        <?php echo e($skripsi->status->label()); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo e(route('admin.skripsi.show', $skripsi)); ?>"
                            class="shrink-0 flex items-center justify-center gap-2 h-10 px-6 bg-red-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-800 shadow-lg shadow-red-900/10 hover:-translate-y-0.5 transition-all">
                            Review Skripsi
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="flex flex-col items-center justify-center py-20 text-center group">
                        <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4 text-gray-200 group-hover:scale-110 group-hover:text-red-900/20 transition-all duration-500">
                            <span class="material-symbols-outlined text-3xl font-light">inventory_2</span>
                        </div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tidak ada skripsi dalam antrean.</p>
                    </div>
                    <?php endif; ?>
                
                <?php elseif($tab === 'bimbingan'): ?>
                    <?php $__empty_1 = true; $__currentLoopData = $activeBimbingan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skripsi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php 
                        $color = $skripsi->status->color(); 
                        $tColor = ['yellow' => 'amber', 'green' => 'emerald', 'blue' => 'blue', 'red' => 'red', 'purple' => 'purple', 'indigo' => 'indigo', 'gray' => 'gray', 'orange' => 'orange'][$color] ?? $color;
                    ?>
                    <div class="bg-white border border-gray-100 rounded-2xl p-4 sm:p-5 hover:border-blue-100 hover:shadow-md hover:shadow-blue-900/5 transition-all group flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-900 transition-colors shrink-0 overflow-hidden relative border border-gray-100 group-hover:border-blue-100">
                                <span class="material-symbols-outlined text-2xl font-light">account_circle</span>
                                <?php if($skripsi->mahasiswa?->user?->avatar): ?>
                                <img src="<?php echo e(Storage::url($skripsi->mahasiswa->user->avatar)); ?>" class="absolute inset-0 w-full h-full object-cover">
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1 leading-none">
                                    <h3 class="font-black text-gray-900 tracking-tight group-hover:text-blue-900 transition-colors"><?php echo e($skripsi->mahasiswa?->user?->name ?? '-'); ?></h3>
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest truncate"><?php echo e($skripsi->mahasiswa?->nim ?? ''); ?></span>
                                </div>
                                <p class="text-sm text-gray-500 font-medium truncate italic leading-snug">"<?php echo e($skripsi->judul); ?>"</p>
                                <div class="flex items-center gap-3 mt-2">
                                    <div class="flex items-center gap-1.5 px-2 py-0.5 bg-gray-50 rounded-lg border border-gray-100">
                                        <span class="material-symbols-outlined text-[14px] text-gray-400">psychology</span>
                                        <span class="text-[9px] font-bold text-gray-500 uppercase tracking-tighter"><?php echo e($skripsi->approvedSupervisor?->nama ?? '-'); ?></span>
                                    </div>
                                    <div class="flex items-center gap-1.5 px-2 py-0.5 bg-gray-50 rounded-lg border border-gray-100">
                                        <span class="material-symbols-outlined text-[14px] text-gray-400">forum</span>
                                        <span class="text-[9px] font-bold text-gray-500 uppercase tracking-tighter"><?php echo e($skripsi->total_bimbingan ?? 0); ?> Sesi</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-<?php echo e($tColor); ?>-50 text-<?php echo e($tColor); ?>-700 border border-<?php echo e($tColor); ?>-100/50">
                                        <?php echo e($skripsi->status->label()); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo e(route('admin.skripsi.show', $skripsi)); ?>"
                            class="shrink-0 flex items-center justify-center gap-2 h-10 px-6 bg-blue-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-700 shadow-lg shadow-blue-600/10 hover:-translate-y-0.5 transition-all">
                            Monitoring Bimbingan
                            <span class="material-symbols-outlined text-sm">visibility</span>
                        </a>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="flex flex-col items-center justify-center py-20 text-center group">
                        <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4 text-gray-200 group-hover:scale-110 group-hover:text-blue-900/20 transition-all duration-500">
                            <span class="material-symbols-outlined text-3xl font-light">forum</span>
                        </div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tidak ada bimbingan aktif saat ini.</p>
                    </div>
                    <?php endif; ?>

                <?php elseif($tab === 'sidang'): ?>
                    <?php $__empty_1 = true; $__currentLoopData = $pendingSidang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white border border-gray-100 rounded-2xl p-4 sm:p-5 hover:border-orange-100 hover:shadow-md hover:shadow-orange-900/5 transition-all group flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                        <div class="flex items-center gap-4 leading-none">
                            <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-400 shrink-0 border border-orange-100/50">
                                <span class="material-symbols-outlined text-2xl font-light">clinical_notes</span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-black text-gray-900 tracking-tight group-hover:text-orange-600 transition-colors mb-2"><?php echo e($reg->submission?->mahasiswa?->user?->name ?? '-'); ?></h3>
                                <p class="text-sm text-gray-500 font-medium truncate leading-snug mb-2">Jadwalkan sidang untuk: <span class="italic">"<?php echo e($reg->submission?->judul ?? '-'); ?>"</span></p>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[14px] text-gray-400">schedule</span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Dikirim <?php echo e($reg->submitted_at?->diffForHumans() ?? '-'); ?></span>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo e(route('admin.skripsi.show', $reg->submission)); ?>"
                            class="shrink-0 flex items-center justify-center gap-2 h-10 px-6 bg-orange-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-orange-700 shadow-lg shadow-orange-600/10 hover:-translate-y-0.5 transition-all">
                            Verifikasi Sidang
                            <span class="material-symbols-outlined text-sm">verified</span>
                        </a>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="flex flex-col items-center justify-center py-20 text-center group">
                        <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4 text-gray-200 group-hover:scale-110 group-hover:text-orange-900/20 transition-all duration-500">
                            <span class="material-symbols-outlined text-3xl font-light">event_busy</span>
                        </div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tidak ada pendaftaran sidang masuk.</p>
                    </div>
                    <?php endif; ?>

                <?php elseif($tab === 'scheduled'): ?>
                    <?php $__empty_1 = true; $__currentLoopData = $scheduled; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skripsi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white border border-gray-100 rounded-2xl p-4 sm:p-5 hover:border-indigo-100 hover:shadow-md hover:shadow-indigo-900/5 transition-all group flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                        <div class="flex items-center gap-4 leading-none">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-500 shrink-0 border border-indigo-100/50">
                                <span class="material-symbols-outlined text-2xl font-light">event_available</span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-black text-gray-900 tracking-tight group-hover:text-indigo-600 transition-colors mb-2 leading-none"><?php echo e($skripsi->mahasiswa?->user?->name ?? '-'); ?></h3>
                                <?php if($skripsi->sidangSchedule): ?>
                                <div class="flex flex-wrap items-center gap-3">
                                    <div class="flex items-center gap-1.5 px-2 py-1 bg-indigo-50/50 rounded-lg border border-indigo-100/50 leading-none">
                                        <span class="material-symbols-outlined text-[16px] text-indigo-400">calendar_month</span>
                                        <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest"><?php echo e($skripsi->sidangSchedule->tanggal->format('d M Y')); ?></span>
                                    </div>
                                    <div class="flex items-center gap-1.5 px-2 py-1 bg-indigo-50/50 rounded-lg border border-indigo-100/50 leading-none">
                                        <span class="material-symbols-outlined text-[16px] text-indigo-400">schedule</span>
                                        <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest"><?php echo e(substr($skripsi->sidangSchedule->waktu_mulai, 0, 5)); ?></span>
                                    </div>
                                    <div class="flex items-center gap-1.5 px-2 py-1 bg-indigo-50/50 rounded-lg border border-indigo-100/50 leading-none">
                                        <span class="material-symbols-outlined text-[16px] text-indigo-400">meeting_room</span>
                                        <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest"><?php echo e($skripsi->sidangSchedule->ruangan_label); ?></span>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex gap-2 shrink-0">
                            <a href="<?php echo e(route('admin.skripsi.show', $skripsi)); ?>"
                                class="flex items-center justify-center h-10 px-4 bg-gray-50 text-gray-500 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-100 border border-gray-100 transition-all">
                                Detail
                            </a>
                            <form action="<?php echo e(route('admin.skripsi.complete', $skripsi)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" onclick="return confirm('Tandai sidang sebagai selesai?')"
                                    class="flex items-center justify-center gap-2 h-10 px-5 bg-purple-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-purple-700 shadow-lg shadow-purple-600/10 hover:-translate-y-0.5 transition-all">
                                    Selesai
                                    <span class="material-symbols-outlined text-sm">task_alt</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="flex flex-col items-center justify-center py-20 text-center group">
                        <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4 text-gray-200 group-hover:scale-110 group-hover:text-indigo-900/20 transition-all duration-500">
                            <span class="material-symbols-outlined text-3xl font-light">event_repeat</span>
                        </div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tidak ada sidang terjadwal saat ini.</p>
                    </div>
                    <?php endif; ?>

                <?php elseif($tab === 'revision'): ?>
                    <?php $__empty_1 = true; $__currentLoopData = $revisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skripsi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white border border-gray-100 rounded-2xl p-4 sm:p-5 hover:border-amber-100 hover:shadow-md hover:shadow-amber-900/5 transition-all group flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500 shrink-0 border border-amber-100/50 leading-none">
                                <span class="material-symbols-outlined text-2xl font-light">rate_review</span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-black text-gray-900 tracking-tight group-hover:text-amber-600 transition-colors mb-1 leading-none"><?php echo e($skripsi->mahasiswa?->user?->name ?? '-'); ?></h3>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Revisi diupload: <?php echo e($skripsi->latestRevision?->uploaded_at?->format('d M Y') ?? '-'); ?></span>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo e(route('admin.skripsi.show', $skripsi)); ?>"
                            class="shrink-0 flex items-center justify-center gap-2 h-10 px-6 bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-600/10 hover:-translate-y-0.5 transition-all">
                            Review Revisi
                            <span class="material-symbols-outlined text-sm">history_edu</span>
                        </a>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="flex flex-col items-center justify-center py-20 text-center group">
                        <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4 text-gray-200 group-hover:scale-110 group-hover:text-amber-900/20 transition-all duration-500">
                            <span class="material-symbols-outlined text-3xl font-light">drive_file_rename_outline</span>
                        </div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tidak ada revisi menunggu review.</p>
                    </div>
                    <?php endif; ?>

                <?php elseif($tab === 'completed'): ?>
                    <?php $__empty_1 = true; $__currentLoopData = $completed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skripsi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white border border-gray-100 rounded-2xl p-4 sm:p-5 hover:border-emerald-100 hover:shadow-md hover:shadow-emerald-900/5 transition-all group flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500 shrink-0 border border-emerald-100/50 leading-none">
                                <span class="material-symbols-outlined text-2xl font-light">verified</span>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-black text-gray-900 tracking-tight group-hover:text-emerald-600 transition-colors mb-1 leading-none"><?php echo e($skripsi->mahasiswa?->user?->name ?? '-'); ?></h3>
                                <p class="text-sm text-gray-500 font-medium truncate italic leading-snug mb-2">"<?php echo e($skripsi->judul); ?>"</p>
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">✓ Selesai <?php echo e($skripsi->revision_approved_at?->format('d M Y') ?? ''); ?></span>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo e(route('admin.skripsi.show', $skripsi)); ?>"
                            class="shrink-0 flex items-center justify-center h-10 px-6 bg-gray-50 text-gray-400 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-100 border border-gray-100 transition-all">
                            Lihat Arsip
                        </a>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="flex flex-col items-center justify-center py-20 text-center group">
                        <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4 text-gray-200 group-hover:scale-110 group-hover:text-emerald-900/20 transition-all duration-500">
                            <span class="material-symbols-outlined text-3xl font-light">auto_awesome</span>
                        </div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Belum ada skripsi yang diselesaikan.</p>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/admin/skripsi/index.blade.php ENDPATH**/ ?>