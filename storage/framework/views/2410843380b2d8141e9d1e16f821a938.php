<?php $__env->startSection('title', 'Detail Magang'); ?>
<?php $__env->startSection('page-title', 'Detail Magang'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-[1600px] mx-auto px-4 py-8 space-y-6" x-data="{ showLogbookForm: false }">

    
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
        <a href="<?php echo e(route('mahasiswa.magang.index')); ?>" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-[#8B1538] transition-colors group w-fit">
            <span class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center border border-gray-100 group-hover:border-red-100 group-hover:bg-red-50 transition-colors">
                <i class="fas fa-arrow-left text-xs"></i>
            </span>
            Kembali ke Daftar Magang
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="p-4 bg-green-50/80 border border-green-200/60 rounded-2xl flex items-start gap-3 shadow-sm">
            <span class="material-symbols-outlined text-green-600 mt-0.5">check_circle</span>
            <p class="text-sm text-green-700 font-medium"><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="p-4 bg-red-50/80 border border-red-200/60 rounded-2xl flex items-start gap-3 shadow-sm">
            <span class="material-symbols-outlined text-red-600 mt-0.5">error</span>
            <p class="text-sm text-red-700 font-medium"><?php echo e(session('error')); ?></p>
        </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-6 -mr-6 w-32 h-32 bg-gradient-to-br from-[#8B1538]/5 to-transparent rounded-full blur-2xl pointer-events-none"></div>
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6">
            <div class="flex items-start gap-5">
                <div class="w-16 h-16 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0 text-gray-400">
                    <span class="material-symbols-outlined text-4xl">corporate_fare</span>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-gray-900 leading-tight tracking-tight"><?php echo e($internship->instansi); ?></h1>
                    <p class="text-sm text-gray-500 font-medium mt-1"><?php echo e($internship->posisi ?? 'Posisi belum ditentukan'); ?></p>
                    <div class="flex flex-wrap items-center gap-3 mt-3 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px] text-gray-400">calendar_month</span>
                            <?php echo e($internship->periode_mulai?->format('d M Y')); ?> – <?php echo e($internship->periode_selesai?->format('d M Y')); ?>

                        </span>
                        <?php if($internship->semester): ?>
                        <span class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px] text-purple-400">school</span>
                            <?php echo e($internship->semester->nama); ?>

                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="shrink-0">
                <?php echo $internship->status_badge; ?>

            </div>
        </div>

        <?php if($internship->rejected_reason): ?>
            <div class="mt-5 p-4 bg-red-50 border border-red-200 rounded-2xl text-sm text-red-700 flex items-start gap-3">
                <span class="material-symbols-outlined text-red-500 mt-0.5">cancel</span>
                <div><span class="font-bold">Alasan Penolakan:</span> <?php echo e($internship->rejected_reason); ?></div>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">info</span> Detail Data Magang
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
                    <p class="text-sm font-semibold text-gray-800"><?php echo e($internship->alamat_instansi ?? '-'); ?></p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[18px] text-gray-400">work</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Posisi / Bagian</p>
                    <p class="text-sm font-semibold text-gray-800"><?php echo e($internship->posisi ?? '-'); ?></p>
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
                <div class="w-9 h-9 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0">
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
                    <span class="material-symbols-outlined text-[18px] text-purple-400">library_books</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-purple-400/70 uppercase tracking-widest mb-0.5">Total SKS Konversi</p>
                    <p class="text-sm font-semibold text-gray-800"><?php echo e($internship->total_mapped_sks); ?> SKS <span class="text-gray-400 font-normal">(maks 16)</span></p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5 flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">touch_app</span> Aksi
        </h3>
        <div class="flex flex-wrap gap-3 items-start">

            <?php if($internship->isEditable()): ?>
                <a href="<?php echo e(route('mahasiswa.magang.edit', $internship)); ?>"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-amber-500/20">
                    <span class="material-symbols-outlined text-[16px]">edit</span> Edit Data
                </a>
            <?php endif; ?>

            <?php if($internship->status === \App\Models\Internship::STATUS_DRAFT): ?>
                <form method="POST" action="<?php echo e(route('mahasiswa.magang.submit', $internship)); ?>" class="inline">
                    <?php echo csrf_field(); ?>
                    <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-blue-600/20">
                        <span class="material-symbols-outlined text-[16px]">send</span> Submit Pengajuan
                    </button>
                </form>
            <?php endif; ?>

            <?php if($internship->status === \App\Models\Internship::STATUS_WAITING_REQUEST_LETTER): ?>
                <a href="<?php echo e(route('mahasiswa.magang.generate-letter', $internship)); ?>"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-indigo-600/20">
                    <span class="material-symbols-outlined text-[16px]">download</span> Download Surat Pengantar Magang
                </a>

                
                <div x-data="{ fileName: '' }" class="w-full mt-2">
                    <p class="text-xs font-bold text-gray-500 mb-2 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[14px] text-green-500">upload_file</span>
                        Upload Surat Pengantar Magang yang Sudah Ditandatangani
                    </p>
                    <form method="POST" action="<?php echo e(route('mahasiswa.magang.upload-signed', $internship)); ?>" enctype="multipart/form-data"
                          class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        <?php echo csrf_field(); ?>
                        <label class="flex items-center gap-3 px-4 py-2.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl cursor-pointer transition group flex-1 min-w-0">
                            <span class="w-8 h-8 rounded-lg bg-white border border-gray-100 shadow-sm flex items-center justify-center shrink-0 group-hover:border-green-200 transition">
                                <span class="material-symbols-outlined text-[18px] text-gray-400 group-hover:text-green-500 transition">attach_file</span>
                            </span>
                            <span class="text-sm text-gray-500 font-medium truncate" x-text="fileName || 'Pilih file (PDF, DOCX, JPG, PNG)'"></span>
                            <input type="file" name="signed_letter" accept=".pdf,.docx,.jpg,.png" required class="hidden"
                                   @change="fileName = $event.target.files[0]?.name ?? ''">
                        </label>
                        <button type="submit"
                                class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-green-600/20">
                            <span class="material-symbols-outlined text-[16px]">upload</span> Upload
                        </button>
                    </form>
                    <p class="text-[11px] text-gray-400 mt-1.5 ml-1">Format: PDF, DOCX, JPG, PNG · Maks 5MB</p>
                </div>
            <?php endif; ?>

            
            <?php
                $canUploadAcceptance = in_array($internship->status, [
                    \App\Models\Internship::STATUS_APPROVED,
                    \App\Models\Internship::STATUS_SENT_TO_STUDENT,
                    \App\Models\Internship::STATUS_SUPERVISOR_ASSIGNED,
                ]);
            ?>

            <?php if($canUploadAcceptance && !$internship->acceptance_letter_path): ?>
                
                <div x-data="{ fileNameAcceptance: '' }" class="w-full mt-2">
                    <p class="text-xs font-bold text-gray-500 mb-2 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[14px] text-teal-500">upload_file</span>
                        Upload Surat Penerimaan dari Instansi
                    </p>
                    <form method="POST" action="<?php echo e(route('mahasiswa.magang.upload-acceptance', $internship)); ?>" enctype="multipart/form-data"
                          class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                        <?php echo csrf_field(); ?>
                        <label class="flex items-center gap-3 px-4 py-2.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl cursor-pointer transition group flex-1 min-w-0">
                            <span class="w-8 h-8 rounded-lg bg-white border border-gray-100 shadow-sm flex items-center justify-center shrink-0 group-hover:border-teal-200 transition">
                                <span class="material-symbols-outlined text-[18px] text-gray-400 group-hover:text-teal-500 transition">attach_file</span>
                            </span>
                            <span class="text-sm text-gray-500 font-medium truncate" x-text="fileNameAcceptance || 'Pilih file Surat Penerimaan (PDF, DOCX, JPG, PNG)'"></span>
                            <input type="file" name="acceptance_letter" accept=".pdf,.docx,.jpg,.png" required class="hidden"
                                   @change="fileNameAcceptance = $event.target.files[0]?.name ?? ''">
                        </label>
                        <button type="submit"
                                class="shrink-0 inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-teal-600/20">
                            <span class="material-symbols-outlined text-[16px]">upload</span> Upload Surat Penerimaan
                        </button>
                    </form>
                    <p class="text-[11px] text-gray-400 mt-1.5 ml-1">Format: PDF, DOCX, JPG, PNG · Maks 5MB</p>
                </div>

            <?php elseif($internship->acceptance_letter_path && $internship->status === \App\Models\Internship::STATUS_SUPERVISOR_ASSIGNED): ?>
                
                <div class="w-full mt-2 p-4 bg-amber-50 border border-amber-200 rounded-2xl flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="flex items-center gap-3 flex-1">
                        <span class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-amber-600 text-[20px]">hourglass_top</span>
                        </span>
                        <div>
                            <p class="text-sm font-bold text-amber-800">Surat Penerimaan Sudah Diunggah</p>
                            <p class="text-xs text-amber-600 mt-0.5">Menunggu konfirmasi dari admin. Magang akan dimulai sesuai tanggal mulai setelah dikonfirmasi.</p>
                        </div>
                    </div>
                    <div class="flex gap-2 shrink-0">
                        <a href="<?php echo e(route('mahasiswa.magang.download-acceptance', $internship)); ?>"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-amber-200 hover:bg-amber-50 text-amber-700 text-sm font-bold rounded-xl transition">
                            <span class="material-symbols-outlined text-[15px]">download</span> Lihat File
                        </a>
                        
                        <div x-data="{ showReplace: false }">
                            <button @click="showReplace = !showReplace"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-semibold rounded-xl transition">
                                <span class="material-symbols-outlined text-[15px]">swap_horiz</span> Ganti
                            </button>
                            <div x-show="showReplace" x-cloak class="mt-3 w-full" x-data="{ fn: '' }">
                                <form method="POST" action="<?php echo e(route('mahasiswa.magang.upload-acceptance', $internship)); ?>" enctype="multipart/form-data"
                                      class="flex items-center gap-2">
                                    <?php echo csrf_field(); ?>
                                    <label class="flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-xl cursor-pointer flex-1">
                                        <span class="text-xs text-gray-500 truncate" x-text="fn || 'Pilih file baru'"></span>
                                        <input type="file" name="acceptance_letter" accept=".pdf,.docx,.jpg,.png" required class="hidden"
                                               @change="fn = $event.target.files[0]?.name ?? ''">
                                    </label>
                                    <button class="px-4 py-2 bg-teal-600 text-white text-xs font-bold rounded-xl">Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            <?php elseif($internship->status === \App\Models\Internship::STATUS_ACCEPTANCE_LETTER_READY): ?>
                
                <div class="w-full mt-2 p-4 bg-teal-50 border border-teal-200 rounded-2xl flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-teal-100 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-teal-600 text-[20px]">verified</span>
                    </span>
                    <div>
                        <p class="text-sm font-bold text-teal-800">Surat Penerimaan Dikonfirmasi Admin</p>
                        <p class="text-xs text-teal-600 mt-0.5">
                            Magang akan dimulai pada
                            <span class="font-bold"><?php echo e($internship->periode_mulai?->format('d M Y') ?? '-'); ?></span>.
                        </p>
                    </div>
                    <?php if($internship->acceptance_letter_path): ?>
                        <a href="<?php echo e(route('mahasiswa.magang.download-acceptance', $internship)); ?>"
                           class="ml-auto inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-teal-200 hover:bg-teal-50 text-teal-700 text-sm font-bold rounded-xl transition">
                            <span class="material-symbols-outlined text-[15px]">download</span> Download
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if($internship->status === \App\Models\Internship::STATUS_REQUEST_LETTER_UPLOADED): ?>
                <form method="POST" action="<?php echo e(route('mahasiswa.magang.submit-review', $internship)); ?>" class="inline">
                    <?php echo csrf_field(); ?>
                    <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-blue-600/20">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span> Kirim untuk Review Admin
                    </button>
                </form>
            <?php endif; ?>

            
            <?php if($internship->admin_signed_pdf_path && in_array($internship->status, [
                \App\Models\Internship::STATUS_SENT_TO_STUDENT,
                \App\Models\Internship::STATUS_SUPERVISOR_ASSIGNED,
                \App\Models\Internship::STATUS_ACCEPTANCE_LETTER_READY,
                \App\Models\Internship::STATUS_ONGOING,
                \App\Models\Internship::STATUS_COMPLETED,
            ])): ?>
                <a href="<?php echo e(route('mahasiswa.magang.download-official', $internship)); ?>"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-sky-600/20">
                    <span class="material-symbols-outlined text-[16px]">forward_to_inbox</span> Download Surat Resmi (TTD)
                </a>
            <?php endif; ?>

            <?php if($internship->acceptance_letter_path && in_array($internship->status, [
                \App\Models\Internship::STATUS_ONGOING,
                \App\Models\Internship::STATUS_COMPLETED,
                \App\Models\Internship::STATUS_GRADED,
                \App\Models\Internship::STATUS_CLOSED,
            ])): ?>
                <a href="<?php echo e(route('mahasiswa.magang.download-acceptance', $internship)); ?>"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-teal-600/20">
                    <span class="material-symbols-outlined text-[16px]">download</span> Download Surat Penerimaan
                </a>
            <?php endif; ?>

            <?php if(in_array($internship->status, [\App\Models\Internship::STATUS_DRAFT, \App\Models\Internship::STATUS_REJECTED])): ?>
                <form method="POST" action="<?php echo e(route('mahasiswa.magang.destroy', $internship)); ?>" class="inline"
                      onsubmit="return confirm('Yakin ingin menghapus pengajuan ini?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-red-200 hover:bg-red-50 text-red-600 text-sm font-bold rounded-xl transition shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">delete</span> Hapus
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>


    
    <?php if($internship->courseMappings->isNotEmpty()): ?>
    <?php
        // Build a lookup: mata_kuliah_id => nilai from krsEntries
        $nilaiByMkId = $internship->krsEntries->keyBy('mata_kuliah_id');
    ?>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-[16px]">library_books</span> Mata Kuliah Konversi
        </h3>
        <div class="overflow-x-auto rounded-2xl border border-gray-100">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kode MK</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nama Mata Kuliah</th>
                        <th class="px-5 py-3.5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">SKS</th>
                        <th class="px-5 py-3.5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nilai Akhir</th>
                        <th class="px-5 py-3.5 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Grade</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php $__currentLoopData = $internship->courseMappings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mapping): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $krsEntry = $nilaiByMkId->get($mapping->mata_kuliah_id);
                        $nilaiRecord = $krsEntry?->nilai;
                    ?>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-xs text-gray-700"><?php echo e($mapping->mataKuliah?->kode_mk ?? '-'); ?></td>
                        <td class="px-5 py-3.5 font-semibold text-gray-800"><?php echo e($mapping->mataKuliah?->nama_mk ?? '-'); ?></td>
                        <td class="px-5 py-3.5 text-center font-bold text-purple-600"><?php echo e($mapping->sks); ?></td>
                        <td class="px-5 py-3.5 text-center">
                            <?php if($nilaiRecord && $nilaiRecord->nilai_akhir !== null): ?>
                                <span class="font-bold text-gray-800"><?php echo e(number_format($nilaiRecord->nilai_akhir, 1)); ?></span>
                            <?php else: ?>
                                <span class="text-gray-400 text-xs">Belum ada</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <?php if($nilaiRecord && $nilaiRecord->grade): ?>
                                <?php
                                    $gradeColors = ['A'=>'green','B'=>'blue','C'=>'yellow','D'=>'orange','E'=>'red'];
                                    $gradeColor = $gradeColors[strtoupper($nilaiRecord->grade)] ?? 'gray';
                                ?>
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-<?php echo e($gradeColor); ?>-100 text-<?php echo e($gradeColor); ?>-700 font-black text-sm">
                                    <?php echo e(strtoupper($nilaiRecord->grade)); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-gray-400 text-xs">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 border-t border-gray-100">
                        <td class="px-5 py-3.5 font-bold text-gray-700" colspan="2">Total SKS</td>
                        <td class="px-5 py-3.5 text-center font-black text-purple-600"><?php echo e($internship->courseMappings->sum('sks')); ?></td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mt-6">
        <div class="p-6 sm:p-8 bg-gradient-to-br from-white to-gray-50/50 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
                <div>
                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-[#8B1538]/10 text-[#8B1538] flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">menu_book</span>
                        </div>
                        Logbook Magang
                    </h3>
                    <p class="text-xs font-medium text-gray-500 mt-1 ml-10">Catat setiap kegiatan harian magang Anda secara rutin.</p>
                </div>
                <?php if($internship->isOngoing()): ?>
                    <button @click="showLogbookForm = !showLogbookForm"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#8B1538] hover:bg-[#6D1029] text-white text-sm font-bold rounded-xl transition shadow-sm shadow-[#8B1538]/20 group">
                        <span class="material-symbols-outlined text-[16px] group-hover:rotate-90 transition-transform">add</span> 
                        <span x-text="showLogbookForm ? 'Batal' : 'Tambah Entri'"></span>
                    </button>
                <?php endif; ?>
            </div>
            
            <div x-show="showLogbookForm" x-cloak 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="mt-6 p-6 bg-white border border-[#8B1538]/10 rounded-2xl shadow-sm relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-[#8B1538]/[0.02] to-transparent pointer-events-none"></div>
                <form method="POST" action="<?php echo e(route('mahasiswa.magang.logbook.store', $internship)); ?>" class="space-y-5 relative">
                    <?php echo csrf_field(); ?>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Tanggal Kegiatan</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                                </span>
                                <input type="date" name="tanggal" value="<?php echo e(date('Y-m-d')); ?>" required
                                       class="w-full rounded-xl border border-gray-200 bg-gray-50 text-sm pl-10 pr-4 py-2.5 transition-all text-gray-700 focus:bg-white focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538]">
                            </div>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Deskripsi Kegiatan</label>
                            <textarea name="kegiatan" rows="3" required
                                      class="w-full rounded-xl border border-gray-200 bg-gray-50 text-sm px-4 py-3 transition-all text-gray-700 focus:bg-white focus:ring-4 focus:ring-red-900/10 focus:border-[#8B1538] resize-y"
                                      placeholder="Jelaskan apa saja yang Anda kerjakan atau pelajari hari ini..."></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#8B1538] text-white text-sm font-bold rounded-xl shadow-sm shadow-[#8B1538]/20 hover:bg-[#6D1029] transition group">
                            <span class="material-symbols-outlined text-[16px] group-hover:-translate-y-0.5 transition-transform">send</span> 
                            Simpan Logbook
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-gray-50/30">
            <?php if($internship->logbooks->isEmpty()): ?>
                <div class="text-center py-16 px-4">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-200">
                        <span class="material-symbols-outlined text-3xl text-gray-300">history_edu</span>
                    </div>
                    <h4 class="text-sm font-bold text-gray-600 mb-1">Belum Ada Entri Logbook</h4>
                    <p class="text-xs text-gray-400 max-w-sm mx-auto leading-relaxed">Anda belum mencatat kegiatan apapun. Mulai catat kegiatan magang Anda setelah status magang Anda berjalan.</p>
                </div>
            <?php else: ?>
                <div class="divide-y divide-gray-100/80">
                    <?php $__currentLoopData = $internship->logbooks->sortByDesc('tanggal'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-6 sm:px-8 hover:bg-white transition-colors group">
                            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                                
                                <div class="shrink-0 sm:w-32 pt-1 border-l-2 <?php echo e($log->created_by_role === 'dosen' ? 'border-sky-400' : 'border-[#8B1538]'); ?> pl-3 sm:pl-0 sm:border-l-0 sm:text-right">
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
                                
                                
                                <div class="flex-1 space-y-3">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider <?php echo e($log->created_by_role === 'dosen' ? 'bg-sky-50 text-sky-600 border border-sky-100' : 'bg-rose-50 text-[#8B1538] border border-rose-100'); ?>">
                                            <span class="material-symbols-outlined text-[12px]"><?php echo e($log->created_by_role === 'dosen' ? 'school' : 'person'); ?></span>
                                            <?php echo e($log->created_by_role === 'dosen' ? 'Dosen' : 'Mahasiswa'); ?>

                                        </span>
                                    </div>
                                    
                                    <?php if($log->kegiatan): ?>
                                        <div class="text-sm text-gray-700 leading-relaxed font-medium">
                                            <?php echo nl2br(e($log->kegiatan)); ?>

                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if($log->catatan_dosen): ?>
                                        <div class="mt-4 p-4 rounded-xl bg-sky-50/50 border border-sky-100 flex gap-3 items-start">
                                            <div class="shrink-0 w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center text-sky-500 border border-sky-100">
                                                <span class="material-symbols-outlined text-[16px]">forum</span>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-bold text-sky-600 uppercase tracking-widest mb-1">Catatan Pembimbing</p>
                                                <p class="text-sm text-sky-800 leading-relaxed"><?php echo e($log->catatan_dosen); ?></p>
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
                        <span class="w-7 h-7 rounded-full bg-gray-100 text-gray-600 font-black text-xs flex items-center justify-center">#<?php echo e($rev->revision_no); ?></span>
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mahasiswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/page/mahasiswa/magang/show.blade.php ENDPATH**/ ?>