<?php $__env->startSection('title', 'Detail Magang – ' . ($internship->mahasiswa?->user?->name ?? 'N/A')); ?>

<?php $__env->startSection('content'); ?>
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-[1600px] mx-auto font-inter" x-data="{ showGradeForm: false, showMappingForm: false, showRejectForm: false, showPdfPanel: window.location.hash === '#pdf-panel', showDateForm: false }" x-init="if(window.location.hash === '#pdf-panel') { setTimeout(() => { document.getElementById('pdf-panel')?.scrollIntoView({ behavior: 'smooth', block: 'start' }) }, 100); }">

    
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">
        <a href="<?php echo e(route('admin.magang.index')); ?>" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-[#7a1621] transition-colors group w-fit">
            <span class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center border border-gray-100 group-hover:border-red-100 group-hover:bg-red-50 transition-colors">
                <i class="fas fa-arrow-left text-xs"></i>
            </span>
            Kembali ke Daftar
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-6 p-4 bg-green-50/80 border border-green-200/60 rounded-2xl flex items-start gap-3 shadow-sm">
            <span class="material-symbols-outlined text-green-600 mt-0.5">check_circle</span>
            <p class="text-sm text-green-700 font-medium"><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="mb-6 p-4 bg-red-50/80 border border-red-200/60 rounded-2xl flex items-start gap-3 shadow-sm">
            <span class="material-symbols-outlined text-red-600 mt-0.5">error</span>
            <p class="text-sm text-red-700 font-medium"><?php echo e(session('error')); ?></p>
        </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 relative overflow-hidden mb-6">
        <div class="absolute top-0 right-0 -mt-8 -mr-8 w-40 h-40 bg-gradient-to-br from-[#7a1621]/5 to-transparent rounded-full blur-3xl pointer-events-none"></div>
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6">
            <div class="flex items-start gap-5">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 flex items-center justify-center shrink-0 text-gray-400">
                    <span class="material-symbols-outlined text-4xl">account_circle</span>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-gray-900 leading-tight"><?php echo e($internship->mahasiswa?->user?->name ?? '-'); ?></h1>
                    <p class="text-sm text-gray-500 font-medium mt-1"><?php echo e($internship->mahasiswa?->nim ?? '-'); ?> &bull; <?php echo e($internship->instansi); ?></p>
                    <div class="flex flex-wrap items-center gap-3 mt-3 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px] text-gray-400">work</span>
                            <?php echo e($internship->posisi ?? 'Posisi belum ditentukan'); ?>

                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px] text-gray-400">calendar_month</span>
                            <?php echo e($internship->periode_mulai?->format('d M Y')); ?> – <?php echo e($internship->periode_selesai?->format('d M Y')); ?>

                        </span>
                    </div>
                </div>
            </div>
            <div class="shrink-0">
                <?php echo $internship->status_badge; ?>

            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 mb-6">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">info</span> Informasi Magang
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[18px] text-gray-400">corporate_fare</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Instansi</p>
                    <p class="text-sm font-semibold text-gray-800"><?php echo e($internship->instansi); ?></p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[18px] text-gray-400">location_on</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Alamat</p>
                    <p class="text-sm font-semibold text-gray-800"><?php echo e($internship->alamat_instansi); ?></p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[18px] text-gray-400">description</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Deskripsi</p>
                    <p class="text-sm font-semibold text-gray-800"><?php echo e($internship->deskripsi ?? '-'); ?></p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-orange-50 border border-orange-100 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[18px] text-orange-400">badge</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-orange-400/70 uppercase tracking-widest mb-0.5">Pembimbing Lapangan</p>
                    <p class="text-sm font-semibold text-gray-800">
                        <?php echo e($internship->pembimbing_lapangan_nama ?? '-'); ?>

                        <?php if($internship->pembimbing_lapangan_telp): ?>
                            <span class="text-gray-400 font-normal">(<?php echo e($internship->pembimbing_lapangan_telp); ?>)</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[18px] text-blue-400">supervisor_account</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-blue-400/70 uppercase tracking-widest mb-0.5">Dosen Pembimbing</p>
                    <p class="text-sm font-semibold text-gray-800"><?php echo e($internship->supervisorDosen?->user?->name ?? 'Belum ditentukan'); ?></p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[18px] text-purple-400">school</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-purple-400/70 uppercase tracking-widest mb-0.5">Semester Mahasiswa</p>
                    <p class="text-sm font-semibold text-gray-800"><?php echo e($internship->semester_mahasiswa ?? $internship->mahasiswa->semester ?? '-'); ?></p>
                </div>
            </div>
        </div>
    </div>

    
    <?php if($internship->status !== \App\Models\Internship::STATUS_CLOSED): ?>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 mb-6">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5 flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">admin_panel_settings</span> Aksi Admin
        </h3>
        <div class="flex flex-wrap gap-3">

            <?php if($internship->status === \App\Models\Internship::STATUS_APPROVED): ?>
                
                <button @click="showPdfPanel = !showPdfPanel" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-indigo-600/20">
                    <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span> Generate / Kelola PDF Resmi
                </button>
            <?php endif; ?>

            <?php if($internship->status === \App\Models\Internship::STATUS_SENT_TO_STUDENT): ?>
                <form method="POST" action="<?php echo e(route('admin.magang.assign-supervisor', $internship)); ?>" class="inline-flex items-center gap-2">
                    <?php echo csrf_field(); ?>
                    <select name="supervisor_dosen_id" required class="rounded-xl border-gray-200 bg-gray-50 text-sm px-4 py-2.5 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400">
                        <option value="">— Pilih Dosen Pembimbing —</option>
                        <?php $__currentLoopData = $dosens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($d->id); ?>" <?php echo e($internship->supervisor_dosen_id == $d->id ? 'selected' : ''); ?>><?php echo e($d->user?->name ?? $d->nama); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-indigo-600/20">
                        <span class="material-symbols-outlined text-[16px]">person_add</span> Tetapkan Pembimbing
                    </button>
                </form>
                
                <?php if($internship->admin_signed_pdf_path || $internship->admin_final_pdf_path): ?>
                    <a href="<?php echo e(route('admin.magang.download-signed-pdf', $internship)); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-sky-200 hover:bg-sky-50 text-sky-600 text-sm font-bold rounded-xl transition">
                        <span class="material-symbols-outlined text-[16px]">download</span> Unduh Surat Terkirim
                    </a>
                <?php endif; ?>
            <?php endif; ?>

            <?php if($internship->status === \App\Models\Internship::STATUS_UNDER_REVIEW): ?>
                <form method="POST" action="<?php echo e(route('admin.magang.approve', $internship)); ?>" class="inline">
                    <?php echo csrf_field(); ?>
                    <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-green-600/20">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span> Setujui
                    </button>
                </form>
                <button @click="showRejectForm = !showRejectForm" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-red-200 hover:bg-red-50 text-red-600 text-sm font-bold rounded-xl transition shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">cancel</span> Tolak
                </button>
            <?php endif; ?>

            <?php if($internship->status === \App\Models\Internship::STATUS_ACCEPTANCE_LETTER_READY): ?>
                
                <div class="flex flex-wrap items-center gap-3">
                    <form method="POST" action="<?php echo e(route('admin.magang.start', $internship)); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                                onclick="return confirm('Mulai magang sekarang? Status akan berubah menjadi Magang Berjalan dan KRS konversi akan diinjeksi.')"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-emerald-600/20">
                            <span class="material-symbols-outlined text-[16px]">play_circle</span> Mulai Magang
                        </button>
                    </form>
                    <span class="text-xs text-gray-500 font-medium">
                        Atau biarkan otomatis — magang akan dimulai pada
                        <span class="font-bold text-gray-700"><?php echo e($internship->periode_mulai?->format('d M Y') ?? '-'); ?></span>
                        saat scheduler berjalan.
                    </span>
                </div>
            <?php endif; ?>

            <?php if($internship->status === \App\Models\Internship::STATUS_SUPERVISOR_ASSIGNED): ?>
                
                <?php if($internship->acceptance_letter_path): ?>
                    
                    <a href="<?php echo e(\App\Helpers\FileHelper::filePrivateUrl($internship->acceptance_letter_path)); ?>"
                       target="_blank"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-teal-200 hover:bg-teal-50 text-teal-700 text-sm font-bold rounded-xl transition shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">download</span> Lihat Surat Penerimaan (upload mahasiswa)
                    </a>
                    <form method="POST" action="<?php echo e(route('admin.magang.upload-acceptance', $internship)); ?>" class="inline-flex items-center">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                                onclick="return confirm('Konfirmasi surat penerimaan dari mahasiswa sudah valid? Status magang akan menjadi Surat Siap dan magang akan dimulai sesuai tanggal mulai.')"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-teal-600/20">
                            <span class="material-symbols-outlined text-[16px]">check_circle</span> Konfirmasi Surat Penerimaan
                        </button>
                    </form>
                <?php else: ?>
                    
                    <div class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-50 border border-amber-200 text-amber-700 text-sm font-semibold rounded-xl">
                        <span class="material-symbols-outlined text-[16px]">hourglass_top</span>
                        Menunggu mahasiswa mengunggah surat penerimaan dari instansi
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            

            <?php if($internship->status === \App\Models\Internship::STATUS_ONGOING): ?>
                <form method="POST" action="<?php echo e(route('admin.magang.complete', $internship)); ?>" class="inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                            onclick="return confirm('Tandai magang sebagai selesai? Status akan berubah menjadi Magang Selesai.')"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-blue-600/20">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span> Selesai
                    </button>
                </form>
            <?php endif; ?>

            <?php if(in_array($internship->status, [\App\Models\Internship::STATUS_COMPLETED, \App\Models\Internship::STATUS_GRADED])): ?>
                <button @click="showGradeForm = !showGradeForm" class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-purple-600/20">
                    <span class="material-symbols-outlined text-[16px]">grade</span> Input Nilai
                </button>
            <?php endif; ?>

            <?php if($internship->status === \App\Models\Internship::STATUS_GRADED): ?>
                <form method="POST" action="<?php echo e(route('admin.magang.close', $internship)); ?>" class="inline">
                    <?php echo csrf_field(); ?>
                    <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-600 hover:bg-gray-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-gray-600/20">
                        <span class="material-symbols-outlined text-[16px]">lock</span> Tutup
                    </button>
                </form>
            <?php endif; ?>

            <button @click="showMappingForm = !showMappingForm" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-xl transition">
                <span class="material-symbols-outlined text-[16px]">library_books</span> Edit MK Konversi
            </button>

            
            <?php if(in_array($internship->status, [
                \App\Models\Internship::STATUS_APPROVED,
                \App\Models\Internship::STATUS_SENT_TO_STUDENT,
                \App\Models\Internship::STATUS_SUPERVISOR_ASSIGNED,
                \App\Models\Internship::STATUS_ACCEPTANCE_LETTER_READY,
                \App\Models\Internship::STATUS_ONGOING,
                \App\Models\Internship::STATUS_COMPLETED,
            ])): ?>
                <button @click="showDateForm = !showDateForm" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-100 hover:bg-amber-200 text-amber-700 text-sm font-bold rounded-xl transition">
                    <span class="material-symbols-outlined text-[16px]">edit_calendar</span> Ubah Tanggal
                </button>
            <?php endif; ?>
        </div>

        
        <div x-show="showRejectForm" x-cloak class="mt-5 p-5 bg-red-50/80 border border-red-200/60 rounded-2xl">
            <form method="POST" action="<?php echo e(route('admin.magang.reject', $internship)); ?>" class="space-y-3">
                <?php echo csrf_field(); ?>
                <label class="block text-xs font-bold text-red-600 uppercase tracking-widest mb-1.5">Alasan Penolakan</label>
                <textarea name="rejected_reason" rows="3" required class="w-full rounded-xl border-red-200 bg-white text-sm px-4 py-3 focus:ring-4 focus:ring-red-100 focus:border-red-400" placeholder="Jelaskan alasan penolakan pengajuan magang ini..."></textarea>
                <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white text-sm font-bold rounded-xl shadow-sm shadow-red-600/20">
                    <span class="material-symbols-outlined text-[16px]">send</span> Kirim Penolakan
                </button>
            </form>
        </div>

        
        <div id="pdf-panel" x-show="showPdfPanel" x-cloak class="mt-5 p-5 bg-indigo-50/80 border border-indigo-200/60 rounded-2xl space-y-5">
            <h4 class="text-xs font-bold text-indigo-600 uppercase tracking-widest flex items-center gap-2">
                <span class="material-symbols-outlined text-[15px]">picture_as_pdf</span> Surat Permohonan Magang Resmi
            </h4>

            
            <div class="p-4 bg-white rounded-xl border border-indigo-100">
                <p class="text-xs font-bold text-gray-500 mb-3">Langkah 1 — Generate PDF Resmi</p>
                <form method="POST" action="<?php echo e(route('admin.magang.generate-official-pdf', $internship)); ?>" class="flex flex-wrap items-end gap-3">
                    <?php echo csrf_field(); ?>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Nomor Surat</label>
                        <input type="text" name="nomor_surat" value="<?php echo e($internship->nomor_surat ?? ''); ?>"
                               placeholder="contoh: 099/SK/STIH/III/2026"
                               class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm px-4 py-2.5 focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                               required>
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-indigo-600/20">
                        <span class="material-symbols-outlined text-[15px]">auto_fix_high</span> Generate PDF
                    </button>
                    <?php if($internship->admin_final_pdf_path): ?>
                        <a href="<?php echo e(route('admin.magang.download-official-pdf', $internship)); ?>"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-indigo-200 hover:bg-indigo-50 text-indigo-600 text-sm font-bold rounded-xl transition">
                            <span class="material-symbols-outlined text-[15px]">download</span> Unduh PDF
                        </a>
                    <?php endif; ?>
                </form>
                <?php if($internship->admin_final_pdf_path): ?>
                    <p class="mt-2 text-[11px] text-green-600 font-semibold flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px]">check_circle</span>
                        PDF sudah digenerate &bull; <?php echo e(pathinfo($internship->admin_final_pdf_path, PATHINFO_EXTENSION) === 'pdf' ? 'Format: PDF' : 'Format: DOCX'); ?>

                    </p>
                <?php endif; ?>
            </div>

            
            <div class="p-4 bg-white rounded-xl border border-indigo-100">
                <p class="text-xs font-bold text-gray-500 mb-3">Langkah 2 — Upload PDF Sudah TTD + Cap</p>
                <p class="text-[11px] text-gray-400 mb-3">Download PDF di atas → cetak → tanda tangan + cap → scan → upload kembali. <br/> <strong class="text-indigo-600">Surat akan otomatis dikirim ke mahasiswa setelah diupload.</strong></p>
                <form method="POST" action="<?php echo e(route('admin.magang.upload-signed-pdf', $internship)); ?>"
                      enctype="multipart/form-data" class="flex flex-wrap items-end gap-3">
                    <?php echo csrf_field(); ?>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">File PDF Bertandatangan (maks 10 MB)</label>
                        <input type="file" name="signed_pdf" accept=".pdf" required
                               class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                    </div>
                    <button type="submit" 
                            onclick="return confirm('Upload file ini dan langsung kirimkan ke mahasiswa?')"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-teal-600/20">
                        <span class="material-symbols-outlined text-[15px]">upload_file</span> Upload & Kirimkan ke Mahasiswa
                    </button>
                    <?php if($internship->admin_signed_pdf_path): ?>
                        <a href="<?php echo e(route('admin.magang.download-signed-pdf', $internship)); ?>"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-teal-200 hover:bg-teal-50 text-teal-600 text-sm font-bold rounded-xl transition">
                            <span class="material-symbols-outlined text-[15px]">download</span> Unduh Signed PDF
                        </a>
                    <?php endif; ?>
                </form>
                <?php if($internship->admin_signed_pdf_path): ?>
                    <p class="mt-2 text-[11px] text-green-600 font-semibold flex items-center gap-1">
                        <span class="material-symbols-outlined text-[13px]">check_circle</span>
                        PDF bertandatangan sudah diupload.
                    </p>
                <?php endif; ?>
            </div>



            
            <?php if($internship->sent_to_student_at): ?>
                <div class="flex items-center gap-2 text-[11px] text-sky-600 font-semibold">
                    <span class="material-symbols-outlined text-[14px]">mark_email_read</span>
                    Terkirim pada <?php echo e($internship->sent_to_student_at->format('d M Y, H:i')); ?>

                    oleh <?php echo e($internship->sentBy?->name ?? '-'); ?>

                </div>
            <?php endif; ?>
        </div>

        
        <div x-show="showDateForm" x-cloak class="mt-5 p-5 bg-amber-50/80 border border-amber-200/60 rounded-2xl">
            <h4 class="text-xs font-bold text-amber-700 uppercase tracking-widest flex items-center gap-2 mb-4">
                <span class="material-symbols-outlined text-[15px]">edit_calendar</span> Ubah Tanggal Magang
            </h4>
            <form method="POST" action="<?php echo e(route('admin.magang.update-dates', $internship)); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-amber-700/70 uppercase tracking-widest mb-1.5">Tanggal Mulai</label>
                        <input type="date" name="periode_mulai"
                               value="<?php echo e($internship->periode_mulai?->format('Y-m-d')); ?>"
                               required
                               class="w-full rounded-xl border-amber-200 bg-white text-sm px-4 py-2.5 focus:ring-4 focus:ring-amber-100 focus:border-amber-400">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-amber-700/70 uppercase tracking-widest mb-1.5">Tanggal Selesai</label>
                        <input type="date" name="periode_selesai"
                               value="<?php echo e($internship->periode_selesai?->format('Y-m-d')); ?>"
                               required
                               class="w-full rounded-xl border-amber-200 bg-white text-sm px-4 py-2.5 focus:ring-4 focus:ring-amber-100 focus:border-amber-400">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-amber-700/70 uppercase tracking-widest mb-1.5">Alasan Perubahan <span class="text-red-500">*</span></label>
                    <input type="text" name="date_change_reason" required maxlength="500"
                           placeholder="Contoh: Mahasiswa mengajukan perpanjangan magang satu bulan"
                           class="w-full rounded-xl border-amber-200 bg-white text-sm px-4 py-2.5 focus:ring-4 focus:ring-amber-100 focus:border-amber-400">
                </div>
                <?php if($internship->date_changed_at): ?>
                    <p class="text-[11px] text-gray-500">
                        <span class="font-bold">Terakhir diubah:</span>
                        <?php echo e($internship->date_changed_at->format('d M Y, H:i')); ?>

                        oleh <?php echo e($internship->dateChangedBy?->name ?? '-'); ?> &bull; <?php echo e($internship->date_change_reason); ?>

                    </p>
                <?php endif; ?>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-amber-600/20">
                        <span class="material-symbols-outlined text-[15px]">save</span> Simpan Tanggal
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if($internship->status !== \App\Models\Internship::STATUS_CLOSED): ?>
    <div x-show="showMappingForm" x-cloak x-data="courseMappingForm()" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 mb-6">
        <form method="POST" action="<?php echo e(route('admin.magang.course-mappings', $internship)); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="max_sks" :value="maxSks">
            
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-[16px] text-gray-400">library_books</span>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Mapping Mata Kuliah Konversi</h3>
                <div class="ml-auto flex items-center gap-2">
                    <span class="text-xs font-semibold text-gray-500">Maks <span x-text="maxSks" class="font-black text-gray-700"></span> SKS</span>
                    <button type="button" @click="openPasswordModal()"
                            class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition"
                            title="Ubah batas SKS maksimum">
                        <span class="material-symbols-outlined text-[16px] text-gray-500">settings</span>
                    </button>
                </div>
            </div>
            <div class="flex items-center gap-3 px-3 mb-1">
                <p class="flex-1 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mata Kuliah</p>
                <p class="w-20 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">SKS</p>
                <div class="w-9"></div>
            </div>
            <template x-for="(row, idx) in rows" :key="idx">
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                    <select :name="'mappings['+idx+'][mata_kuliah_id]'" required x-model="row.mata_kuliah_id"
                            @change="updateSks(row)"
                            class="flex-1 rounded-xl border-gray-200 bg-white text-sm px-4 py-2.5 focus:ring-4 focus:ring-red-100 focus:border-[#7a1621]">
                        <option value="">— Pilih Mata Kuliah —</option>
                        <?php $__currentLoopData = $mataKuliahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($mk->id); ?>" :disabled="isMkSelected(<?php echo e($mk->id); ?>, idx)"><?php echo e($mk->kode_mk); ?> – <?php echo e($mk->nama_mk); ?> (<?php echo e($mk->sks); ?> SKS)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <input type="number" :name="'mappings['+idx+'][sks]'" x-model.number="row.sks" readonly
                           class="w-20 rounded-xl border-gray-200 bg-gray-100 text-sm px-3 py-2.5 text-center text-gray-500 font-bold cursor-default select-none" placeholder="SKS">
                    <button type="button" @click="rows.splice(idx, 1)" class="w-9 h-9 rounded-xl bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                </div>
            </template>
            <div class="flex items-center justify-between pt-2">
                <button type="button" @click="rows.push({mata_kuliah_id:'', sks:''})" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#7a1621] bg-red-50 hover:bg-red-100 px-3 py-2 rounded-lg transition">
                    <span class="material-symbols-outlined text-[14px]">add</span> Tambah MK
                </button>
                <span class="text-sm font-bold" :class="totalSks > maxSks ? 'text-red-600' : 'text-green-600'">
                    Total: <span x-text="totalSks"></span> / <span x-text="maxSks"></span> SKS
                </span>
            </div>
            <div class="flex justify-end pt-2 border-t border-gray-100">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-[#7a1621] to-[#6D1029] text-white text-sm font-bold rounded-xl shadow-sm shadow-red-900/20 hover:shadow-md transition">
                    <span class="material-symbols-outlined text-[16px]">save</span> Simpan Mapping
                </button>
            </div>
        </form>

        
        <div x-show="showPasswordModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showPasswordModal = false"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 z-10">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-11 h-11 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-amber-500 text-[22px]">lock</span>
                        </div>
                        <div>
                            <h4 class="text-base font-black text-gray-900">Konfirmasi Identitas</h4>
                            <p class="text-xs text-gray-400">Masukkan password akun Anda untuk melanjutkan</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1.5">Password</label>
                            <div class="relative">
                                <input :type="showPw ? 'text' : 'password'" x-model="passwordInput"
                                       @keydown.enter="submitPassword()"
                                       class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm px-4 py-2.5 pr-10 focus:ring-4 focus:ring-red-100 focus:border-[#7a1621]"
                                       placeholder="Masukkan password...">
                                <button type="button" @click="showPw = !showPw"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <span class="material-symbols-outlined text-[18px]" x-text="showPw ? 'visibility_off' : 'visibility'"></span>
                                </button>
                            </div>
                            <p x-show="passwordError" x-text="passwordError" class="text-xs text-red-500 mt-1.5 font-medium"></p>
                        </div>
                        <div class="flex gap-3 pt-1">
                            <button type="button" @click="showPasswordModal = false"
                                    class="flex-1 px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-sm font-bold text-gray-600 transition">Batal</button>
                            <button type="button" @click="submitPassword()" :disabled="verifying"
                                    class="flex-1 px-4 py-2.5 rounded-xl bg-[#7a1621] hover:bg-[#6D1029] text-white text-sm font-bold transition disabled:opacity-60 inline-flex items-center justify-center gap-2">
                                <span x-text="verifying ? 'Memeriksa...' : 'Lanjutkan'"></span>
                            </button>
                        </div>
                    </div>
                </div>
        </div>

        
        <div x-show="showSksModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="showSksModal = false"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 z-10">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-11 h-11 rounded-xl bg-purple-50 border border-purple-200 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-purple-500 text-[22px]">tune</span>
                        </div>
                        <div>
                            <h4 class="text-base font-black text-gray-900">Pengaturan Batas SKS</h4>
                            <p class="text-xs text-gray-400">Ubah batas maksimum SKS konversi untuk pengajuan ini</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1.5">Maksimum SKS</label>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="tempMaxSks = Math.max(1, tempMaxSks - 1)"
                                        class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-lg transition">&minus;</button>
                                <input type="number" x-model.number="tempMaxSks" min="1" max="40"
                                       class="flex-1 rounded-xl border-gray-200 bg-gray-50 text-xl font-black text-center px-4 py-2 focus:ring-4 focus:ring-purple-100 focus:border-purple-400">
                                <button type="button" @click="tempMaxSks = Math.min(40, tempMaxSks + 1)"
                                        class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-lg transition">+</button>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-1.5 text-center">Default: 16 SKS &middot; Maks: 40 SKS</p>
                        </div>
                        <div class="flex gap-3 pt-1">
                            <button type="button" @click="showSksModal = false"
                                    class="flex-1 px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-sm font-bold text-gray-600 transition">Batal</button>
                            <button type="button" @click="applySksLimit()"
                                    class="flex-1 px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold transition">Terapkan</button>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <?php endif; ?>

    


    <?php if($internship->courseMappings->isNotEmpty()): ?>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 mb-6">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">library_books</span> MK Konversi Saat Ini
        </h3>
        <div class="overflow-x-auto rounded-2xl border border-gray-100">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kode MK</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nama Mata Kuliah</th>
                        <th class="px-5 py-3.5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">SKS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php $__currentLoopData = $internship->courseMappings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-xs text-gray-700"><?php echo e($m->mataKuliah?->kode_mk ?? '-'); ?></td>
                        <td class="px-5 py-3.5 font-semibold text-gray-800"><?php echo e($m->mataKuliah?->nama_mk ?? '-'); ?></td>
                        <td class="px-5 py-3.5 text-center font-bold text-purple-600"><?php echo e($m->sks); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 border-t border-gray-100">
                        <td class="px-5 py-3.5 font-bold text-gray-700" colspan="2">Total SKS</td>
                        <td class="px-5 py-3.5 text-center font-black text-purple-600"><?php echo e($internship->courseMappings->sum('sks')); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if($internship->status !== \App\Models\Internship::STATUS_CLOSED): ?>
    <div x-show="showGradeForm" x-cloak class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 mb-6">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">grade</span> Input Nilai Konversi
        </h3>
        <?php if($gradeSummary): ?>
        <div x-data="realtimeGradeCalc()"
             x-init="initFromServer(<?php echo e(json_encode(array_map(fn($g) => ['mk_id' => $g['mata_kuliah_id'], 'sks' => (int)$g['sks'], 'nilai' => $g['nilai_akhir'] ?? ''], $gradeSummary))); ?>)">

            <form method="POST" action="<?php echo e(route('admin.magang.grades', $internship)); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div class="overflow-x-auto rounded-2xl border border-gray-100">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3.5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mata Kuliah</th>
                                <th class="px-5 py-3.5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">SKS</th>
                                <th class="px-5 py-3.5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nilai Akhir (0–100)</th>
                                <th class="px-5 py-3.5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Grade</th>
                                <th class="px-5 py-3.5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Bobot</th>
                                <th class="px-5 py-3.5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mutu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php $__currentLoopData = $gradeSummary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $gs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-5 py-3.5 font-semibold text-gray-800"><?php echo e($gs['kode_mk']); ?> – <?php echo e($gs['nama_mk']); ?></td>
                                <td class="px-5 py-3.5 text-center font-bold text-purple-600"><?php echo e($gs['sks']); ?></td>
                                <td class="px-5 py-3.5 text-center">
                                    <input type="number"
                                           name="grades[<?php echo e($gs['mata_kuliah_id']); ?>][nilai_akhir]"
                                           x-model.number="rows[<?php echo e($i); ?>].nilai"
                                           @input="compute()"
                                           value="<?php echo e($gs['nilai_akhir'] ?? ''); ?>"
                                           min="0" max="100" step="0.01"
                                           class="w-28 rounded-xl border-gray-200 bg-gray-50 text-sm px-3 py-2 text-center focus:ring-4 focus:ring-purple-100 focus:border-purple-400">
                                </td>
                                <td class="px-5 py-3.5 text-center font-black"
                                    :class="gradeClass(rows[<?php echo e($i); ?>].grade)">
                                    <span x-text="rows[<?php echo e($i); ?>].grade || '<?php echo e($gs['grade'] ?? '-'); ?>'"></span>
                                </td>
                                <td class="px-5 py-3.5 text-center text-gray-600 font-semibold">
                                    <span x-text="rows[<?php echo e($i); ?>].bobot !== null ? rows[<?php echo e($i); ?>].bobot.toFixed(2) : '<?php echo e($gs['bobot'] ?? '-'); ?>'"></span>
                                </td>
                                <td class="px-5 py-3.5 text-center text-blue-600 font-semibold">
                                    <span x-text="rows[<?php echo e($i); ?>].mutu !== null ? rows[<?php echo e($i); ?>].mutu.toFixed(2) : '-'"></span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="grid grid-cols-3 gap-4 p-4 bg-purple-50/70 border border-purple-100 rounded-2xl">
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-purple-400 uppercase tracking-widest mb-1">Total SKS Konversi</p>
                        <p class="text-2xl font-black text-purple-700" x-text="totalSks"></p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-purple-400 uppercase tracking-widest mb-1">Total Mutu</p>
                        <p class="text-2xl font-black text-purple-700" x-text="totalMutu.toFixed(2)"></p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-purple-400 uppercase tracking-widest mb-1">IPS Konversi (Preview)</p>
                        <p class="text-2xl font-black text-green-600" x-text="ips.toFixed(2)"></p>
                    </div>
                </div>
                <p class="text-[11px] text-gray-400 text-center">
                    Preview dihitung <em>client-side</em> secara realtime. Nilai baru di-publish ke mahasiswa saat Anda klik Simpan.
                </p>

                <div class="flex justify-end pt-2">
                    <button type="submit"
                            onclick="return confirm('Simpan & publish nilai ke mahasiswa?')"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold rounded-xl shadow-sm shadow-purple-600/20 transition">
                        <span class="material-symbols-outlined text-[16px]">save</span> Simpan & Publish Nilai
                    </button>
                </div>
            </form>
        </div>
        <?php else: ?>
            <div class="text-center py-10">
                <span class="material-symbols-outlined text-4xl text-gray-200 mb-2 block">library_books</span>
                <p class="text-sm text-gray-400 font-medium">Belum ada mapping MK konversi untuk diisi nilainya.</p>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mt-6 mb-6">
        <div class="p-6 sm:p-8 bg-gradient-to-br from-white to-gray-50/50 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
                <div>
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-indigo-600/10 text-indigo-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">menu_book</span>
                        </div>
                        Logbook Magang
                    </h3>
                    <p class="text-xs font-medium text-gray-500 mt-1 ml-10">Kegiatan harian yang dicatat oleh mahasiswa dan catatan dari pembimbing.</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-50/30">
            <?php if($internship->logbooks->isEmpty()): ?>
                <div class="text-center py-16 px-4">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-200">
                        <span class="material-symbols-outlined text-3xl text-gray-300">history_edu</span>
                    </div>
                    <h4 class="text-sm font-bold text-gray-600 mb-1">Belum Ada Entri Logbook</h4>
                    <p class="text-xs text-gray-400 max-w-sm mx-auto leading-relaxed">Mahasiswa belum mencatat kegiatan apapun.</p>
                </div>
            <?php else: ?>
                <div class="divide-y divide-gray-100/80 max-h-[600px] overflow-y-auto pr-1">
                    <?php $__currentLoopData = $internship->logbooks->sortByDesc('tanggal'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-6 sm:px-8 hover:bg-white transition-colors group">
                            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                                
                                <div class="shrink-0 sm:w-32 pt-1 border-l-2 <?php echo e($log->created_by_role === 'dosen' ? 'border-sky-400' : 'border-indigo-600'); ?> pl-3 sm:pl-0 sm:border-l-0 sm:text-right">
                                    <div class="text-[10px] font-black tracking-widest uppercase text-gray-400 mb-1">
                                        <?php echo e(\Carbon\Carbon::parse($log->tanggal)->format('M Y')); ?>

                                    </div>
                                    <div class="text-xl font-black text-gray-800 leading-none mb-1.5">
                                        <?php echo e(\Carbon\Carbon::parse($log->tanggal)->format('d')); ?>

                                    </div>
                                    <div class="text-[11px] font-bold text-gray-500">
                                        <?php echo e(\Carbon\Carbon::parse($log->tanggal)->isoFormat('dddd')); ?>

                                    </div>
                                </div>
                                
                                
                                <div class="flex-1 min-w-0 space-y-3">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider <?php echo e($log->created_by_role === 'dosen' ? 'bg-sky-50 text-sky-600 border border-sky-100' : 'bg-indigo-50 text-indigo-700 border border-indigo-100'); ?>">
                                            <span class="material-symbols-outlined text-[12px]"><?php echo e($log->created_by_role === 'dosen' ? 'school' : 'person'); ?></span>
                                            <?php echo e(ucfirst($log->created_by_role)); ?>

                                        </span>
                                    </div>
                                    
                                    <?php if($log->kegiatan): ?>
                                        <div class="text-sm text-gray-700 leading-relaxed font-medium break-words">
                                            <?php echo nl2br(e($log->kegiatan)); ?>

                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if($log->catatan_dosen): ?>
                                        <div class="mt-4 p-4 rounded-xl bg-sky-50/50 border border-sky-100 flex gap-3 items-start">
                                            <div class="shrink-0 w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center text-sky-500 border border-sky-100">
                                                <span class="material-symbols-outlined text-[16px]">forum</span>
                                            </div>
                                            <div class="min-w-0 flex-1 z-10">
                                                <p class="text-[10px] font-bold text-sky-600 uppercase tracking-widest mb-1.5">Catatan Bimbingan</p>
                                                <p class="text-sm text-sky-900 leading-relaxed break-words"><?php echo nl2br(e($log->catatan_dosen)); ?></p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <?php if($internship->revisions->isNotEmpty()): ?>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">history</span> Riwayat Revisi
        </h3>
        <div class="space-y-3">
            <?php $__currentLoopData = $internship->revisions->sortByDesc('revision_no'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="p-5 border border-gray-100 rounded-2xl text-sm bg-gray-50/30">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 font-black text-xs flex items-center justify-center">#<?php echo e($rev->revision_no); ?></span>
                        <p class="text-xs text-gray-400"><?php echo e($rev->created_at->format('d M Y, H:i')); ?></p>
                    </div>
                    <?php if($rev->note_from_admin): ?>
                        <div class="mb-2"><span class="text-xs font-bold text-red-500">Admin:</span> <span class="text-gray-700"><?php echo e($rev->note_from_admin); ?></span></div>
                    <?php endif; ?>
                    <?php if($rev->note_from_mahasiswa): ?>
                        <div><span class="text-xs font-bold text-gray-500">Mahasiswa:</span> <span class="text-gray-700"><?php echo e($rev->note_from_mahasiswa); ?></span></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php $__env->startPush('scripts'); ?>
<script>
// ── Grade Map (synced with InternshipGradingService::GRADE_MAP) ─────────────
const GRADE_MAP = <?php echo json_encode(collect($gradeMap)->mapWithKeys(fn($v, $k) => [$k => ['min' => $v['min'], 'bobot' => $v['bobot']]])->toArray()) ?>;
const GRADE_KEYS = <?php echo json_encode(array_keys($gradeMap), 15, 512) ?>;

function calcGrade(nilai) {
    for (let i = 0; i < GRADE_KEYS.length; i++) {
        const key  = GRADE_KEYS[i];
        const conf = GRADE_MAP[key];
        if (nilai >= conf.min) return { grade: key, bobot: conf.bobot };
    }
    return { grade: 'E', bobot: 0 };
}

// ── Realtime Grade Calculator ────────────────────────────────────────────────
function realtimeGradeCalc() {
    return {
        rows: [],

        initFromServer(serverRows) {
            this.rows = serverRows.map(r => ({
                mk_id:  r.mk_id,
                sks:    r.sks,
                nilai:  r.nilai !== '' ? parseFloat(r.nilai) : null,
                grade:  null,
                bobot:  null,
                mutu:   null,
            }));
            this.compute();
        },

        compute() {
            for (const row of this.rows) {
                if (row.nilai !== null && row.nilai !== '') {
                    const res  = calcGrade(parseFloat(row.nilai));
                    row.grade  = res.grade;
                    row.bobot  = res.bobot;
                    row.mutu   = res.bobot * row.sks;
                } else {
                    row.grade = null;
                    row.bobot = null;
                    row.mutu  = null;
                }
            }
        },

        get totalSks() {
            return this.rows.reduce((s, r) => s + r.sks, 0);
        },

        get totalMutu() {
            return this.rows.reduce((s, r) => s + (r.mutu ?? 0), 0);
        },

        get ips() {
            return this.totalSks > 0 ? this.totalMutu / this.totalSks : 0;
        },

        gradeClass(grade) {
            const map = {
                'A': 'text-green-600', 'A-': 'text-green-500',
                'B+': 'text-blue-600', 'B': 'text-blue-500', 'B-': 'text-blue-400',
                'C+': 'text-yellow-600', 'C': 'text-yellow-500',
                'D': 'text-orange-500', 'E': 'text-red-600',
            };
            return map[grade] ?? 'text-gray-500';
        },
    };
}

// ── Course Mapping Form ───────────────────────────────────────────────────────
function courseMappingForm() {
    const existing = <?php echo json_encode($internship->courseMappings->map(fn($m) => ['mata_kuliah_id' => (string)$m->mata_kuliah_id, 'sks' => $m->sks])->values(), 512) ?>;
    const mkSksMap = <?php echo json_encode($mataKuliahs->pluck('sks', 'id'), 512) ?>;
    return {
        maxSks: 16,
        tempMaxSks: 16,
        rows: existing.length ? existing : [{mata_kuliah_id: '', sks: ''}],
        showPasswordModal: false,
        showSksModal: false,
        passwordInput: '',
        passwordError: '',
        showPw: false,
        verifying: false,
        get totalSks() { return this.rows.reduce((s, r) => s + (parseInt(r.sks) || 0), 0); },
        updateSks(row) {
            const sks = mkSksMap[row.mata_kuliah_id];
            if (sks !== undefined) row.sks = sks;
        },
        isMkSelected(mkId, currentRowIdx) {
            return this.rows.some((row, idx) => idx !== currentRowIdx && parseInt(row.mata_kuliah_id) === mkId);
        },
        openPasswordModal() {
            this.passwordInput = '';
            this.passwordError = '';
            this.showPw = false;
            this.showPasswordModal = true;
        },
        async submitPassword() {
            if (!this.passwordInput) { this.passwordError = 'Password tidak boleh kosong.'; return; }
            this.verifying = true;
            this.passwordError = '';
            try {
                const res = await fetch('<?php echo e(route('admin.verify-password')); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    body: JSON.stringify({ password: this.passwordInput }),
                });
                const data = await res.json();
                if (data.success) {
                    this.showPasswordModal = false;
                    this.tempMaxSks = this.maxSks;
                    this.showSksModal = true;
                } else {
                    this.passwordError = data.message || 'Password salah. Coba lagi.';
                }
            } catch(e) {
                this.passwordError = 'Terjadi kesalahan. Coba lagi.';
            } finally {
                this.verifying = false;
            }
        },
        applySksLimit() {
            this.maxSks = parseInt(this.tempMaxSks) || 16;
            this.showSksModal = false;
        }
    };
}
</script>

<?php if(session('auto_download_official')): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(() => {
            window.location.href = "<?php echo e(route('admin.magang.download-official-pdf', $internship)); ?>";
        }, 500); // Small delay to let the page render properly
    });
</script>
<?php endif; ?>

<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/admin/magang/show.blade.php ENDPATH**/ ?>