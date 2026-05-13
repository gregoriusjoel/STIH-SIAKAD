<?php

namespace App\Services;

use App\Models\Prestasi;
use App\Models\PrestasiSurat;
use App\Models\PrestasiSuratSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\SimpleQrCode\Facades\QrCode;

/**
 * Generates PDF letters and manages letter numbering for the Prestasi module.
 */
class PrestasiLetterService
{
    private const ROMAN_MONTHS = [
        1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
        5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
        9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
    ];

    private const JENIS_SURAT_CODES = [
        'surat_tugas'       => 'ST',
        'surat_rekomendasi' => 'SR',
        'surat_keterangan'  => 'SK',
        'surat_penghargaan' => 'SP',
        'surat_arsip'       => 'SA',
    ];

    private const SURAT_TEMPLATES = [
        'surat_tugas'       => 'surat.prestasi.surat-tugas',
        'surat_rekomendasi' => 'surat.prestasi.surat-rekomendasi',
        'surat_keterangan'  => 'surat.prestasi.surat-keterangan',
        'surat_penghargaan' => 'surat.prestasi.surat-penghargaan',
        'surat_arsip'       => 'surat.prestasi.surat-keterangan', // use same template
    ];

    /**
     * Generate a surat for a prestasi.
     */
    public function generateSurat(
        Prestasi $prestasi,
        string $jenisSurat,
        string $tanggalSurat,
        string $penandatanganNama,
        string $penandatanganJabatan,
        ?string $penandatanganNip,
        ?string $nomorSuratManual,
        int $userId
    ): PrestasiSurat {
        $prestasi->loadMissing(['pengaju.user', 'dosenPendamping.user']);

        // Generate or use manual nomor surat
        $isBackdate = $tanggalSurat !== now()->format('Y-m-d');
        $nomorSurat = $nomorSuratManual ?: $this->generateNomorSurat($jenisSurat, $tanggalSurat);

        // Generate QR verification code
        $verificationCode = strtoupper(substr(md5($nomorSurat . $prestasi->id . time()), 0, 10));

        // 1. Create surat record first (with placeholder file_path)
        $surat = PrestasiSurat::create([
            'prestasi_id'           => $prestasi->id,
            'jenis_surat'           => $jenisSurat,
            'nomor_surat'           => $nomorSurat,
            'tanggal_surat'         => $tanggalSurat,
            'penandatangan_nama'    => $penandatanganNama,
            'penandatangan_jabatan' => $penandatanganJabatan,
            'penandatangan_nip'     => $penandatanganNip,
            'file_path'             => 'generating...',
            'is_backdate'           => $isBackdate,
            'generated_by'          => $userId,
            'metadata'              => [
                'verification_code' => $verificationCode,
                'generated_at'      => now()->toISOString(),
            ],
        ]);

        // 2. Build context data for template (passing the surat object)
        $context = $this->buildContext($prestasi, $surat, $verificationCode);

        // 3. Generate PDF
        $template = self::SURAT_TEMPLATES[$jenisSurat] ?? 'surat.prestasi.surat-keterangan';
        $pdf = Pdf::loadView($template, $context)
            ->setPaper('a4', 'portrait');

        // 4. Save PDF to storage
        $folder = $prestasi->storage_folder . '/surat';
        $fileName = $jenisSurat . '_' . $prestasi->id . '_' . time() . '.pdf';
        $storagePath = $folder . '/' . $fileName;

        $resolvedDisk = \App\Helpers\FileHelper::resolveDiskForPath($storagePath);
        Storage::disk($resolvedDisk)->put($storagePath, $pdf->output());

        // 5. Update surat record with final path
        $surat->update(['file_path' => $storagePath]);

        return $surat;
    }

    /**
     * Generate auto nomor surat based on configurable format.
     */
    public function generateNomorSurat(string $jenisSurat, string $tanggalSurat): string
    {
        $tanggal = \Carbon\Carbon::parse($tanggalSurat);
        $bulan = $tanggal->month;
        $tahun = $tanggal->year;

        // Get settings
        $format = PrestasiSuratSetting::getFormat($jenisSurat, '{counter}/STIH/{tipe}/{month}/{year}');
        $instansiCode = 'STIH'; // Fixed or could be another setting

        // Calculate counter
        $counter = $this->getNextCounter($jenisSurat, $bulan, $tahun);

        // Build nomor surat
        $replacements = [
            '{counter}'       => str_pad($counter, 3, '0', STR_PAD_LEFT),
            '{instansi}'      => $instansiCode,
            '{tipe}'          => self::JENIS_SURAT_CODES[$jenisSurat] ?? 'XX',
            '{month}'         => $bulan,
            '{roman_month}'   => self::ROMAN_MONTHS[$bulan] ?? 'I',
            '{year}'          => $tahun,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $format);
    }

    /**
     * Get next counter number for nomor surat.
     */
    private function getNextCounter(string $jenisSurat, int $bulan, int $tahun, string $resetMode = 'yearly'): int
    {
        $setting = PrestasiSuratSetting::getForJenis($jenisSurat);
        
        if (!$setting) {
            return PrestasiSurat::where('jenis_surat', $jenisSurat)->whereYear('tanggal_surat', $tahun)->count() + 1;
        }

        // Check for yearly reset
        if ($setting->reset_year != $tahun) {
            $setting->update([
                'last_counter' => 1,
                'reset_year'   => $tahun
            ]);
            return 1;
        }

        $newCounter = $setting->last_counter + 1;
        $setting->update(['last_counter' => $newCounter]);
        
        return $newCounter;
    }

    /**
     * Preview nomor surat (without creating record).
     */
    public function previewNomorSurat(string $jenisSurat, ?string $tanggalSurat = null): string
    {
        $tanggalSurat = $tanggalSurat ?: now()->format('Y-m-d');
        return $this->generateNomorSurat($jenisSurat, $tanggalSurat);
    }

    /**
     * Build context array for surat template.
     */
    private function buildContext(
        Prestasi $prestasi,
        PrestasiSurat $surat,
        string $verificationCode
    ): array {
        $pengaju = $prestasi->pengaju;
        $user = $pengaju?->user;

        $penandatanganNama = $surat->penandatangan_nama;
        $penandatanganJabatan = $surat->penandatangan_jabatan;
        $penandatanganNip = $surat->penandatangan_nip;
        $jenisSurat = $surat->jenis_surat;
        $nomorSurat = $surat->nomor_surat;
        $tanggalSurat = $surat->tanggal_surat->format('Y-m-d');

        // Generate QR code as base64
        $qrBase64 = null;
        try {
            $verifyUrl = url('/verify-surat/' . $verificationCode);
            $qrSvg = QrCode::format('svg')->size(100)->generate($verifyUrl);
            $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);
        } catch (\Throwable $e) {
            // QR code generation failed, continue without it
        }

        return [
            'prestasi'     => $prestasi,
            'surat'        => $surat,
            'user'         => $user,
            'pengaju'      => $pengaju,
            'jenis_surat'  => $jenisSurat,
            'jenis_surat_label' => Prestasi::JENIS_SURAT_LABELS[$jenisSurat] ?? ucfirst(str_replace('_', ' ', $jenisSurat)),
            'nomor_surat'  => $nomorSurat,
            'tanggal_surat' => \Carbon\Carbon::parse($tanggalSurat)->locale('id')->isoFormat('D MMMM YYYY'),
            'tanggal_surat_raw' => $tanggalSurat,

            // Pengaju data
            'user'         => $user,
            'pengaju'      => $pengaju,
            'nama'         => $user?->name ?? '-',
            'identifier'   => $prestasi->pengaju_identifier,
            'role'         => $prestasi->pengaju_role,
            'prodi'        => $pengaju?->prodi ?? 'Ilmu Hukum',

            // Kegiatan data
            'nama_kegiatan'    => $prestasi->nama_kegiatan,
            'jenis_kegiatan'   => $prestasi->jenis_kegiatan,
            'tingkat_kegiatan' => $prestasi->tingkat_label,
            'tempat_kegiatan'  => $prestasi->tempat_kegiatan,
            'tanggal_mulai'    => $prestasi->tanggal_mulai?->locale('id')->isoFormat('D MMMM YYYY'),
            'tanggal_selesai'  => $prestasi->tanggal_selesai?->locale('id')->isoFormat('D MMMM YYYY'),
            'penyelenggara'    => $prestasi->penyelenggara,
            'deskripsi'        => $prestasi->deskripsi,
            'jenis_prestasi'   => $prestasi->jenis_prestasi,

            // Dosen pendamping
            'dosen_pendamping' => $prestasi->dosenPendamping?->user?->name,

            // Penandatangan
            'penandatangan_nama'    => $penandatanganNama,
            'penandatangan_jabatan' => $penandatanganJabatan,
            'penandatangan_nip'     => $penandatanganNip,

            // Verification
            'verification_code' => $verificationCode,
            'qr_base64'        => $qrBase64,

            // Draft watermark
            'is_draft' => !in_array($prestasi->status, [Prestasi::STATUS_SURAT_DITERBITKAN, Prestasi::STATUS_SELESAI]),
        ];
    }

    /**
     * Get current format settings for display/editing.
     */
    public function getSettings(): array
    {
        return PrestasiSuratSetting::all()->keyBy('jenis_surat')->map(function($item) {
            return [
                'format' => $item->format_nomor,
                'counter' => $item->last_counter,
                'year' => $item->reset_year
            ];
        })->toArray();
    }

    /**
     * Update format settings.
     */
    public function updateSettings(array $settings): void
    {
        foreach ($settings as $jenis => $data) {
            $setting = PrestasiSuratSetting::getForJenis($jenis);
            if ($setting) {
                $setting->update([
                    'format_nomor' => $data['format'] ?? $setting->format_nomor,
                    'last_counter' => isset($data['counter']) ? (int)$data['counter'] : $setting->last_counter,
                ]);
            }
        }
    }
}
