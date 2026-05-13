<?php

namespace App\Services;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class ExportKuisionerWordService
{
    public function export($type, $results, $stats)
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Style Definitions
        $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 16], ['alignment' => 'center']);
        $phpWord->addFontStyle('headerStyle', ['bold' => true, 'size' => 12]);
        $phpWord->addFontStyle('boldStyle', ['bold' => true]);

        // Title
        $section->addTitle('LAPORAN EVALUASI HASIL KUISIONER', 1);
        $section->addText(strtoupper($type === 'mahasiswa_baru' ? 'Kuesioner Mahasiswa Baru' : 'Kuesioner Aktivasi Semester'), ['bold' => true, 'size' => 14], ['alignment' => 'center']);
        $section->addTextBreak(2);

        // Info
        $section->addText('Periode / Tahun Ajaran: ' . htmlspecialchars($stats['period']), 'boldStyle');
        $section->addText('Total Responden: ' . (string)$stats['total_respondents'], 'boldStyle');
        $section->addText('Tanggal Laporan: ' . date('d F Y'), 'boldStyle');
        $section->addTextBreak(1);

        // Summary of Results
        $section->addText('RINGKASAN HASIL', 'headerStyle');
        $section->addTextBreak(1);

        $tableStyle = ['borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 80];
        $phpWord->addTableStyle('ResultsTable', $tableStyle);
        $resultsTable = $section->addTable('ResultsTable');
        
        $resultsTable->addRow();
        $resultsTable->addCell(7000)->addText('Pertanyaan', 'boldStyle');
        $resultsTable->addCell(3000)->addText('Rata-rata', 'boldStyle');

        foreach ($stats['rekap'] as $item) {
            $resultsTable->addRow();
            $resultsTable->addCell(7000)->addText(htmlspecialchars($item['text']));
            $resultsTable->addCell(3000)->addText((string)$item['avg']);
        }
        $section->addTextBreak(2);

        // Suggestions
        $section->addText('KRITIK & SARAN RESPONDEN', 'headerStyle');
        $section->addTextBreak(1);

        if (empty($stats['suggestions'])) {
            $section->addText('Tidak ada kritik dan saran.');
        } else {
            foreach ($stats['suggestions'] as $index => $saran) {
                $text = ($index + 1) . '. ' . $saran['text'] . ' (' . $saran['name'] . ' - ' . $saran['nim'] . ')';
                $section->addText(htmlspecialchars($text));
            }
        }

        $fileName = 'Laporan_Kuisioner_' . $type . '_' . date('Ymd_His') . '.docx';
        $tempFile = tempnam(sys_get_temp_dir(), 'word');
        
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
