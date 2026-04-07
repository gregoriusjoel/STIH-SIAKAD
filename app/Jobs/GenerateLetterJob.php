<?php

namespace App\Jobs;

use App\Models\Pengajuan;
use App\Models\Semester;
use App\Support\LetterTemplateConfig;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class GenerateLetterJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 60;

    public function __construct(
        public readonly int $pengajuanId
    ) {}

    public function handle(): void
    {
        $pengajuan = Pengajuan::with(['mahasiswa.user'])->findOrFail($this->pengajuanId);

        // Pastikan status masih draft (belum diproses)
        if ($pengajuan->status !== Pengajuan::STATUS_DRAFT) {
            Log::warning("GenerateLetterJob: pengajuan #{$this->pengajuanId} bukan draft, skip.");
            return;
        }

        $config = LetterTemplateConfig::get($pengajuan->jenis);
        if (!$config) {
            Log::error("GenerateLetterJob: jenis '{$pengajuan->jenis}' tidak ditemukan di config.");
            return;
        }

        // ── 1. Bangun context data ────────────────────────────────
        $mahasiswa = $pengajuan->mahasiswa;
        $user      = $mahasiswa->user;

        // Ambil semester aktif
        $semesterAktif = Semester::where('is_active', true)->first();

        $context = [
            'nama'         => $user->name ?? '-',
            'nim'          => $mahasiswa->nim ?? '-',
            'prodi'        => $mahasiswa->prodi ?? '-',
            'fakultas'     => 'Fakultas Hukum', // STIH Adhyaksa — single faculty institution
            'semester'     => $mahasiswa->semester ?? '-',
            'tahun_ajaran' => $semesterAktif?->tahun_ajaran ?? date('Y') . '/' . (date('Y') + 1),
            'tanggal'      => now()->locale('id')->isoFormat('D MMMM YYYY'),
            'alamat'       => $mahasiswa->alamat ?? $mahasiswa->address ?? '-',
            'no_hp'        => $mahasiswa->no_hp ?? $mahasiswa->phone ?? '-',
        ];

        // Merge payload_template ke context
        $payload = $pengajuan->payload_template ?? [];

        // ── 2. Buka template DOCX ─────────────────────────────────
        $templatePath = base_path('docs/' . $config['template']);
        if (!file_exists($templatePath)) {
            Log::error("GenerateLetterJob: template tidak ditemukan di {$templatePath}");
            return;
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        // ── 3. Isi semua placeholder ──────────────────────────────
        foreach ($config['placeholders'] as $placeholder => $source) {
            $value = '';

            if (str_starts_with((string) $source, '@')) {
                // Ambil dari payload_template
                $key   = ltrim($source, '@');
                $value = $payload[$key] ?? '';
            } else {
                // Ambil dari context DB
                $value = $context[$source] ?? '';
            }

            // PhpWord TemplateProcessor: setValue('nama', 'Budi')
            try {
                $templateProcessor->setValue($placeholder, htmlspecialchars((string) $value));
            } catch (\Throwable $e) {
                // Placeholder tidak ada di template, skip
                Log::debug("GenerateLetterJob: placeholder '{$placeholder}' tidak ada di template.");
            }
        }

        // ── 4. Simpan file output ─────────────────────────────────
        $fileName  = 'generated_' . $pengajuan->id . '_' . time() . '.docx';
        $storagePath = 'pengajuan/generated/' . $fileName;

        $tmpPath = sys_get_temp_dir() . '/' . $fileName;
        $templateProcessor->saveAs($tmpPath);

        Storage::disk('s3')->put($storagePath, file_get_contents($tmpPath));

        @unlink($tmpPath);

        // ── 5. Update status pengajuan ────────────────────────────
        $pengajuan->update([
            'generated_doc_path' => $storagePath,
            'status'             => Pengajuan::STATUS_GENERATED,
        ]);

        Log::info("GenerateLetterJob: pengajuan #{$this->pengajuanId} berhasil digenerate → {$storagePath}");
    }

    public function failed(\Throwable $e): void
    {
        Log::error("GenerateLetterJob FAILED pengajuan #{$this->pengajuanId}: " . $e->getMessage());
    }
}
