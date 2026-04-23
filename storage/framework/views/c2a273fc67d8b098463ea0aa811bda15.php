<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>KRS - <?php echo e($mahasiswa->user->name ?? ''); ?></title>
    <style>
        @page { margin: 10mm 8mm; }
        body { font-family: Helvetica, Arial, sans-serif; font-size: 9px; color: #111827; line-height: 1.2; }
        .container { width: 100%; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .header-table td { border: 0; vertical-align: top; }
        .center-title { text-align: center; font-weight: 700; font-size: 18px; margin-top: 0; }
        .subtitle { text-align: center; font-weight: 700; margin-top: 4px; margin-bottom: 6px; font-size: 15px; }
        .muted { color: #4b5563; font-size: 11px; }
        .meta-table { width: 100%; border-collapse: collapse; margin-top: 6px; page-break-inside: avoid; }
        .meta-table td { border: 1px solid #c7c7c7; padding: 3px 4px; font-size: 8.5px; }
        .krs-table { width: 100%; border-collapse: collapse; margin-top: 6px; table-layout: fixed; }
        .krs-table th, .krs-table td { border: 1px solid #bfc3c9; padding: 3px; font-size: 8.5px; }
        .krs-table th { background: #f3f4f6; font-weight: 700; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .signature-wrap { width: 100%; margin-top: 45px; page-break-inside: avoid; }
        .signature-table { width: 100%; border-collapse: collapse; }
        .signature-table td { width: 50%; border: 0; text-align: center; vertical-align: top; }
        .sign-line { border-top: 1px solid #111827; width: 45%; margin: 40px auto 4px auto; }
        .footer-note { margin-top: 6px; font-size: 7.5px; color: #4b5563; }
    </style>
</head>
<body>
<?php
    $semesterNumber = $semesterAktif->semester_number ?? $mahasiswa->getCurrentSemester();
    $semesterName = $semesterAktif->nama_semester ?? (($semesterNumber % 2 === 1) ? 'Ganjil' : 'Genap');
    $prodiText = $mahasiswa->program_studi ?? $mahasiswa->prodi ?? '-';
    $totalSks = 0;
?>

<div class="container">
    <table class="header-table">
        <tr>
            <td style="width: 82px;">
                <img src="<?php echo e(public_path('images/logo_stih_white.png')); ?>" alt="logo" style="width: 72px; height: auto;">
            </td>
            <td>
                <div class="center-title">SEKOLAH TINGGI ILMU HUKUM (STIH) ADHYAKSA</div>
                <div class="muted" style="text-align:center; margin-top:4px;">
                    Jl. Margasatwa No.39, RT.1/RW.6, Jagakarsa, Jakarta Selatan, DKI Jakarta 12620
                </div>
                <div class="muted" style="text-align:center;">
                    Telepon: (021) 220 99999 | Email: info@stih-adhyaksa.ac.id
                </div>
            </td>
            <td style="width: 92px; text-align: right;">
                <div class="muted">Tanggal Cetak</div>
                <div><?php echo e(\Carbon\Carbon::now()->format('d F Y')); ?></div>
            </td>
        </tr>
    </table>

    <div class="subtitle">KARTU RENCANA STUDI (KRS)</div>
    <div class="muted" style="text-align:center; margin-bottom:4px;">
        <?php echo e($semesterName); ?> - <?php echo e($semesterAktif->tahun_ajaran ?? '-'); ?>

    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 14%;">NIM</td>
            <td style="width: 36%;"><?php echo e($mahasiswa->nim ?? '-'); ?></td>
            <td style="width: 14%;">Tahun Ajaran</td>
            <td style="width: 36%;"><?php echo e($semesterAktif->tahun_ajaran ?? '-'); ?></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td><?php echo e($mahasiswa->user->name ?? '-'); ?></td>
            <td>Semester</td>
            <td>Semester <?php echo e($semesterNumber); ?> (<?php echo e($semesterName); ?>)</td>
        </tr>
        <tr>
            <td>Dosen PA</td>
            <td><?php echo e(optional($mahasiswa->dosen_pa)->user->name ?? '-'); ?></td>
            <td>Prodi</td>
            <td><?php echo e($prodiText); ?></td>
        </tr>
    </table>

    <table class="krs-table">
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">No</th>
                <th style="width: 11%;">Kode MK</th>
                <th style="width: 33%;">Mata Kuliah</th>
                <th style="width: 5%;" class="text-center">SKS</th>
                <th style="width: 7%;" class="text-center">Hari</th>
                <th style="width: 10%;" class="text-center">Jam</th>
                <th style="width: 10%;">Ruangan</th>
                <th style="width: 20%;">Dosen</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $existingKrs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $kelasMk = $k->kelasMataKuliah;
                    $kelasLegacy = $k->kelas;
                    $jadwalLegacy = $kelasLegacy?->jadwals?->first();

                    $mk = $kelasMk?->mataKuliah ?? $kelasLegacy?->mataKuliah ?? $k->mataKuliah;
                    $sks = (int) ($mk?->sks ?? 0);
                    $totalSks += $sks;

                    $hari = $kelasMk?->hari ?? $jadwalLegacy?->hari ?? '-';
                    $jamMulai = $kelasMk?->jam_mulai ?? $jadwalLegacy?->jam_mulai;
                    $jamSelesai = $kelasMk?->jam_selesai ?? $jadwalLegacy?->jam_selesai;
                    $jamLabel = ($jamMulai && $jamSelesai)
                        ? substr($jamMulai, 0, 5) . ' - ' . substr($jamSelesai, 0, 5)
                        : '-';

                    $ruangan = $kelasMk?->ruang ?? $jadwalLegacy?->ruangan ?? '-';
                    $kelasKode = $kelasMk?->kode_kelas ?? $kelasLegacy?->section;
                    $ruanganLabel = $kelasKode ? ($ruangan . ' | ' . $kelasKode) : $ruangan;

                    $dosenName = $kelasMk?->dosen?->user?->name
                        ?? $kelasLegacy?->dosen?->user?->name
                        ?? '-';
                ?>
                <tr>
                    <td class="text-center"><?php echo e($i + 1); ?></td>
                    <td><?php echo e($mk?->kode_mk ?? '-'); ?></td>
                    <td><?php echo e($mk?->nama_mk ?? '-'); ?></td>
                    <td class="text-center"><?php echo e($sks); ?></td>
                    <td class="text-center"><?php echo e($hari); ?></td>
                    <td class="text-center"><?php echo e($jamLabel); ?></td>
                    <td><?php echo e($ruanganLabel); ?></td>
                    <td><?php echo e($dosenName); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data KRS untuk semester ini.</td>
                </tr>
            <?php endif; ?>
            <tr>
                <td colspan="3" class="text-right"><strong>Total SKS</strong></td>
                <td class="text-center"><strong><?php echo e($totalSks); ?></strong></td>
                <td colspan="4"></td>
            </tr>
        </tbody>
    </table>

    <div class="signature-wrap">
        <table class="signature-table">
            <tr>
                <td>
                    <div class="muted">Dosen Pembimbing Akademik</div>
                    <div class="sign-line"></div>
                    <div class="muted">(<?php echo e(optional($mahasiswa->dosen_pa)->user->name ?? '................'); ?>)</div>
                </td>
                <td>
                    <div class="muted">Jakarta, <?php echo e(\Carbon\Carbon::now()->format('d F Y')); ?></div>
                    <div class="muted">Mahasiswa</div>
                    <div class="sign-line"></div>
                    <div class="muted">(<?php echo e($mahasiswa->user->name ?? '-'); ?>)</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer-note">
        *KRS ini dicetak otomatis oleh sistem SIAKAD pada <?php echo e(\Carbon\Carbon::now()->format('d-m-Y H:i:s')); ?>.
    </div>
</div>
</body>
</html>
<?php /**PATH /Users/naradata/STIH-SIAKAD/resources/views/page/mahasiswa/krs/pdf.blade.php ENDPATH**/ ?>