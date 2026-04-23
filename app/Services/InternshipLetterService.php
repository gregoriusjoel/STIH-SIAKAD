<?php

namespace App\Services;

use App\Models\Internship;
use App\Models\Semester;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

/**
 * Generates DOCX letters for internship module using PhpWord TemplateProcessor.
 * Reuses the same pattern as the existing GenerateLetterJob for Pengajuan Surat.
 */
class InternshipLetterService
{
    /**
     * Generate "Surat Permohonan Magang" (filled by mahasiswa data + internship data).
     * Mahasiswa uses this to download, print, sign, and upload back.
     */
    public function generateRequestLetter(Internship $internship): string
    {
        $internship->loadMissing(['mahasiswa.user', 'semester']);

        $context = $this->buildContext($internship);

        $templatePath = base_path('docs/FORM PENGAJUAN SURAT PENGANTAR MAGANG.docx');
        if (!file_exists($templatePath)) {
            throw new \RuntimeException("Template 'FORM PENGAJUAN SURAT PENGANTAR MAGANG.docx' tidak ditemukan di folder docs/.");
        }

        $processor = new TemplateProcessor($templatePath);
        $this->fillPlaceholders($processor, $context);

        $folder = 'internship/request/' . $internship->mahasiswa->storage_folder;
        return $this->saveDocument($processor, $folder, "request_letter_{$internship->id}");
    }

    /**
     * Generate "Surat Permohonan Magang RESMI" sebagai PDF pixel-perfect untuk admin.
     *
     * Strategi:
     * 1. Isi template DOCX yang sama dengan data mahasiswa + nomor surat.
     * 2. Konversi DOCX → PDF via LibreOffice CLI (jika tersedia) — paling faithful.
     * 3. Jika LibreOffice tidak ada, simpan DOCX saja dan beri warning.
     *
     * Hasil: path ke file PDF/DOCX di storage disk 'public'.
     */
    public function generateOfficialPdf(Internship $internship): string
    {
        $internship->loadMissing(['mahasiswa.user', 'semester']);

        $context = $this->buildContext($internship);

        // Tambahkan nomor surat ke context
        $context['nomor_surat'] = $internship->nomor_surat ?? '-';

        $templatePath = base_path('docs/Surat Permohonan Magang.docx');
        if (!file_exists($templatePath)) {
            throw new \RuntimeException("Template 'Surat Permohonan Magang.docx' tidak ditemukan di folder docs/.");
        }

        $processor = new TemplateProcessor($templatePath);
        $this->fillPlaceholders($processor, $context);

        // Simpan DOCX ke temp
        $tmpDocx = sys_get_temp_dir() . '/admin_official_' . $internship->id . '_' . time() . '.docx';
        $processor->saveAs($tmpDocx);

        // Coba konversi ke PDF via LibreOffice CLI
        $pdfPath = $this->convertDocxToPdf($tmpDocx);
 
        if ($pdfPath) {
            // Berhasil konversi ke PDF
            $storageName = 'internship/admin_official/' . $internship->mahasiswa->storage_folder . '/official_' . $internship->id . '_' . time() . '.pdf';
            $resolvedDisk = \App\Helpers\FileHelper::resolveDiskForPath($storageName);
            Storage::disk($resolvedDisk)->put($storageName, file_get_contents($pdfPath));
            @unlink($tmpDocx);
            @unlink($pdfPath);
            return $storageName;
        }

        // Fallback: simpan DOCX
        $storageName = 'internship/admin_official/' . $internship->mahasiswa->storage_folder . '/official_' . $internship->id . '_' . time() . '.docx';
        $resolvedDisk = \App\Helpers\FileHelper::resolveDiskForPath($storageName);
        Storage::disk($resolvedDisk)->put($storageName, file_get_contents($tmpDocx));
        @unlink($tmpDocx);

        Log::warning("InternshipLetterService: LibreOffice tidak tersedia. File disimpan sebagai DOCX: {$storageName}");
        return $storageName;
    }

    /**
     * Build context array from internship + mahasiswa data.
     */
    private function buildContext(Internship $internship): array
    {
        $mahasiswa = $internship->mahasiswa;
        $user      = $mahasiswa->user;
        $semester  = $internship->semester ?? Semester::where('is_active', true)->first();

        $semNo = (int)($mahasiswa->semester ?? 0);

        return [
            // Mahasiswa data
            'nama'         => $user->name ?? '-',
            'nim'          => $mahasiswa->nim ?? '-',
            'email'        => $user->email ?? $internship->pembimbing_lapangan_email ?? '-',
            'prodi'        => $mahasiswa->prodi ?? '-',
            'fakultas'     => 'Fakultas Hukum',
            'semester'     => $semNo > 0 ? $this->toRoman($semNo) . ' (' . $this->toWords($semNo) . ')' : ($mahasiswa->semester ?? '-'),
            'tahun_ajaran' => $semester?->tahun_ajaran ?? date('Y') . '/' . (date('Y') + 1),
            'tanggal'      => now()->locale('id')->isoFormat('D MMMM YYYY'),
            'alamat'       => $mahasiswa->alamat ?? $mahasiswa->address ?? '-',
            'no_hp'        => $mahasiswa->no_hp ?? $mahasiswa->phone ?? '-',
            // Internship data
            'instansi'                => $internship->instansi ?? '-',
            'alamat_instansi'         => $internship->alamat_instansi ?? '-',
            'posisi'                  => $internship->posisi ?? '-',
            'periode_mulai'           => $internship->periode_mulai?->locale('id')->isoFormat('D MMMM YYYY') ?? '-',
            'periode_selesai'         => $internship->periode_selesai?->locale('id')->isoFormat('D MMMM YYYY') ?? '-',
            'deskripsi'               => $internship->deskripsi ?? '-',
            'pembimbing_lapangan'     => $internship->pembimbing_lapangan_nama ?? '-',
            'konversi_sks'            => (string)$internship->converted_sks,
            // Letter-specific
            'nama_pimpinan_instansi'  => $internship->pembimbing_lapangan_nama
                                          ? 'Yth. ' . $internship->pembimbing_lapangan_nama
                                          : 'Pimpinan / HRD ' . ($internship->instansi ?? '-'),
        ];
    }

    /**
     * Fill all placeholders that exist in the template.
     */
    private function fillPlaceholders(TemplateProcessor $processor, array $context): void
    {
        foreach ($context as $key => $value) {
            try {
                $processor->setValue($key, htmlspecialchars((string)$value));
            } catch (\Throwable $e) {
                // Placeholder not in template, skip silently
            }
        }
    }

    /** Convert integer to Roman numeral (1–8 cukup untuk semester mahasiswa). */
    private function toRoman(int $n): string
    {
        $map = [8 => 'VIII', 7 => 'VII', 6 => 'VI', 5 => 'V', 4 => 'IV', 3 => 'III', 2 => 'II', 1 => 'I'];
        foreach ($map as $val => $rom) {
            if ($n >= $val) return $rom;
        }
        return (string) $n;
    }

    /** Convert semester number to Indonesian word. */
    private function toWords(int $n): string
    {
        $words = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan'];
        return $words[$n] ?? (string) $n;
    }

    /**
     * Save processor output to storage and return path.
     */
    private function saveDocument(TemplateProcessor $processor, string $folder, string $prefix): string
    {
        $fileName    = $prefix . '_' . time() . '.docx';
        $storagePath = $folder . '/' . $fileName;
        $tmpPath     = sys_get_temp_dir() . '/' . $fileName;

        $processor->saveAs($tmpPath);
        $resolvedDisk = \App\Helpers\FileHelper::resolveDiskForPath($storagePath);
        Storage::disk($resolvedDisk)->put($storagePath, file_get_contents($tmpPath));
        @unlink($tmpPath);

        return $storagePath;
    }

    /**
     * Convert DOCX to PDF using LibreOffice CLI.
     * Returns path to generated PDF, or null if LibreOffice is not available.
     */
    private function convertDocxToPdf(string $docxPath): ?string
    {
        // Check if libreoffice is available
        $libreOfficeBin = $this->findLibreOfficeBin();
        if (!$libreOfficeBin) {
            return null;
        }

        $outDir = sys_get_temp_dir();
        $cmd = sprintf(
            '%s --headless --convert-to pdf --outdir %s %s 2>/dev/null',
            escapeshellarg($libreOfficeBin),
            escapeshellarg($outDir),
            escapeshellarg($docxPath)
        );

        exec($cmd, $output, $exitCode);

        if ($exitCode !== 0) {
            Log::warning("InternshipLetterService: LibreOffice konversi gagal (exit {$exitCode}).");
            return null;
        }

        // LibreOffice creates {filename_without_ext}.pdf in outDir
        $pdfPath = $outDir . '/' . pathinfo($docxPath, PATHINFO_FILENAME) . '.pdf';
        if (file_exists($pdfPath)) {
            return $pdfPath;
        }

        return null;
    }

    /**
     * Locate the LibreOffice binary on common paths.
     */
    private function findLibreOfficeBin(): ?string
    {
        $candidates = [
            '/usr/bin/libreoffice',
            '/usr/local/bin/libreoffice',
            '/opt/libreoffice/program/soffice',
            '/Applications/LibreOffice.app/Contents/MacOS/soffice',
            'libreoffice', // in PATH
            'soffice',
        ];

        foreach ($candidates as $bin) {
            // Quick existence check without exec
            if (str_contains($bin, '/') && file_exists($bin)) {
                return $bin;
            }
            // For PATH-based names, check with 'which'
            $result = shell_exec('which ' . escapeshellarg($bin) . ' 2>/dev/null');
            if ($result && trim($result) !== '') {
                return trim($result);
            }
        }

        return null;
    }
}