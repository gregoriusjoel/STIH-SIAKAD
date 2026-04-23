<?php

namespace App\Domain\Thesis\Services;

use App\Models\Mahasiswa;
use App\Models\ThesisSidangRegistration;
use App\Models\ThesisSubmission;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Style\Font;

/**
 * Manages all file storage for the thesis module.
 * All paths are relative to the storage/app/private disk unless noted.
 */
class ThesisFileService
{
    public const DISK = 's3';


    public function storeProposal(Mahasiswa $mahasiswa, UploadedFile $file): string
    {
        return $file->store("skripsi/{$mahasiswa->storage_folder}/proposal", self::DISK);
    }

    public function storeGuidanceFile(Mahasiswa $mahasiswa, UploadedFile $file): string
    {
        return $file->store("skripsi/{$mahasiswa->storage_folder}/bimbingan", self::DISK);
    }

    public function storeSidangFile(Mahasiswa $mahasiswa, string $type, UploadedFile $file): string
    {
        return $file->store("skripsi/{$mahasiswa->storage_folder}/sidang/{$type}", self::DISK);
    }

    public function storeRevision(Mahasiswa $mahasiswa, UploadedFile $file): string
    {
        return $file->store("skripsi/{$mahasiswa->storage_folder}/revisi", self::DISK);
    }

    /**
     * Build all files for the sidang registration from the request.
     * Returns array suitable for ThesisSidangFile::insert().
     */
    public function processSidangFiles(
        ThesisSidangRegistration $reg,
        Mahasiswa $mahasiswa,
        array $files // ['file_type' => UploadedFile]
    ): array {
        $records = [];

        foreach ($files as $type => $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $path = $this->storeSidangFile($mahasiswa, $type, $file);

            $records[] = [
                'sidang_registration_id' => $reg->id,
                'file_type'              => $type,
                'file_path'              => $path,
                'original_name'          => $file->getClientOriginalName(),
                'file_size'              => $file->getSize(),
                'created_at'             => now(),
                'updated_at'             => now(),
            ];
        }

        return $records;
    }

    /**
     * Delete a file from storage.
     */
    public function delete(string $path): void
    {
        if ($path && Storage::disk(self::DISK)->exists($path)) {
            Storage::disk(self::DISK)->delete($path);
        }
    }

    /**
     * Get a temporary URL or stream path for private files.
     */
    public function downloadResponse(string $path, string $name): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return Storage::disk(self::DISK)->download($path, $name);
    }

    /**
     * Stream file inline for browser preview (PDF, images).
     */
    public function previewResponse(string $path): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $disk = Storage::disk(self::DISK);
        $mimeType = $disk->mimeType($path) ?: 'application/octet-stream';

        return response()->stream(function () use ($disk, $path) {
            $stream = $disk->readStream($path);
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, [
            'Content-Type'        => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }

    // ── Logbook Bimbingan ────────────────────────────────────────

    /**
     * Store logbook PDF file.
     */
    public function storeLogbook(Mahasiswa $mahasiswa, UploadedFile $file): string
    {
        return $file->store("skripsi/{$mahasiswa->storage_folder}/logbook", self::DISK);
    }

    /**
     * Generate a .docx logbook bimbingan template with PhpWord.
     * Returns the file content as a string for streaming download.
     */
    public function generateLogbookTemplate(ThesisSubmission $submission): string
    {
        $mahasiswa = $submission->mahasiswa;
        $dosen     = $submission->approvedSupervisor;

        $phpWord = new PhpWord();

        // -- Styles --
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        $phpWord->addParagraphStyle('Title', [
            'alignment'  => 'center',
            'spaceAfter' => 0,
        ]);
        $phpWord->addParagraphStyle('Subtitle', [
            'alignment'  => 'center',
            'spaceAfter' => 200,
        ]);
        $phpWord->addFontStyle('TitleFont', [
            'bold' => true,
            'size' => 16,
        ]);
        $phpWord->addFontStyle('SubtitleFont', [
            'bold' => true,
            'size' => 13,
        ]);

        $section = $phpWord->addSection([
            'marginTop'    => 1000,
            'marginBottom' => 1000,
            'marginLeft'   => 1200,
            'marginRight'  => 1200,
        ]);

        // -- Header --
        $section->addText('LOGBOOK BIMBINGAN SKRIPSI', 'TitleFont', 'Title');
        $section->addText('STIH ADHYAKSA', 'SubtitleFont', 'Subtitle');
        $section->addTextBreak(1);

        // -- Student info table --
        $infoTable = $section->addTable([
            'borderSize'  => 0,
            'cellMargin'  => 50,
        ]);

        $infoItems = [
            ['Nama Mahasiswa',   $mahasiswa?->user?->name ?? '____________________'],
            ['NIM',              $mahasiswa?->nim ?? '____________________'],
            ['Dosen Pembimbing', $dosen?->nama ?? '____________________'],
            ['Judul Skripsi',    $submission->judul ?? '____________________'],
        ];

        foreach ($infoItems as $item) {
            $row = $infoTable->addRow();
            $row->addCell(3000)->addText($item[0], ['bold' => true], ['spaceAfter' => 50]);
            $row->addCell(500)->addText(':', null, ['spaceAfter' => 50]);
            $row->addCell(7000)->addText($item[1], null, ['spaceAfter' => 50]);
        }

        $section->addTextBreak(1);

        // -- Logbook Table --
        $tableStyle = [
            'borderSize'  => 6,
            'borderColor' => '000000',
            'cellMargin'  => 80,
            'unit'        => TblWidth::PERCENT,
            'width'       => 100 * 50,
        ];
        $phpWord->addTableStyle('LogbookTable', $tableStyle);

        $headerCellStyle = ['bgColor' => 'D9E2F3', 'valign' => 'center'];
        $headerFontStyle = ['bold' => true, 'size' => 10];
        $cellFontStyle   = ['size' => 10];
        $centerParagraph = ['alignment' => 'center', 'spaceAfter' => 0, 'spaceBefore' => 0];
        $leftParagraph   = ['spaceAfter' => 0, 'spaceBefore' => 0];

        $table = $section->addTable('LogbookTable');

        // Header row
        $headerRow = $table->addRow(500);
        $headerRow->addCell(600,  $headerCellStyle)->addText('No', $headerFontStyle, $centerParagraph);
        $headerRow->addCell(1800, $headerCellStyle)->addText('Tanggal', $headerFontStyle, $centerParagraph);
        $headerRow->addCell(3500, $headerCellStyle)->addText('Kegiatan Bimbingan', $headerFontStyle, $centerParagraph);
        $headerRow->addCell(3500, $headerCellStyle)->addText('Catatan / Revisi Dosen', $headerFontStyle, $centerParagraph);
        $headerRow->addCell(2000, $headerCellStyle)->addText('Tanda Tangan Dosen', $headerFontStyle, $centerParagraph);

        // Data rows (15 empty rows)
        for ($i = 1; $i <= 15; $i++) {
            $row = $table->addRow(800);
            $row->addCell(600)->addText((string) $i, $cellFontStyle, $centerParagraph);
            $row->addCell(1800)->addText('', $cellFontStyle, $leftParagraph);
            $row->addCell(3500)->addText('', $cellFontStyle, $leftParagraph);
            $row->addCell(3500)->addText('', $cellFontStyle, $leftParagraph);
            $row->addCell(2000)->addText('', $cellFontStyle, $centerParagraph);
        }

        $section->addTextBreak(2);

        // -- Signature area --
        $section->addText(
            'Mengetahui,',
            ['size' => 11],
            ['alignment' => 'right', 'spaceAfter' => 0]
        );
        $section->addText(
            'Dosen Pembimbing',
            ['size' => 11],
            ['alignment' => 'right', 'spaceAfter' => 0]
        );
        $section->addTextBreak(3);
        $section->addText(
            '(' . ($dosen?->nama ?? '____________________') . ')',
            ['size' => 11, 'underline' => 'single'],
            ['alignment' => 'right']
        );

        // -- Save to temp & return content --
        $tempFile = tempnam(sys_get_temp_dir(), 'logbook_') . '.docx';
        $writer   = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        $content = file_get_contents($tempFile);
        @unlink($tempFile);

        return $content;
    }
}
