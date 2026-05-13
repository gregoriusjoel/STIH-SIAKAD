<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class KuisionerSummarySheet implements FromCollection, WithTitle, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $type;
    protected $stats;

    public function __construct($type, $stats)
    {
        $this->type = $type;
        $this->stats = $stats;
    }

    public function collection()
    {
        $data = [];
        foreach ($this->stats['rekap'] as $key => $val) {
            $data[] = [
                'question' => $val['text'],
                'average' => $val['avg'],
                'total_responses' => $val['count']
            ];
        }
        return collect($data);
    }

    public function title(): string
    {
        return 'Ringkasan';
    }

    public function headings(): array
    {
        return [
            ['HASIL EVALUASI KUISIONER: ' . ($this->type === 'mahasiswa_baru' ? 'MAHASISWA BARU' : 'AKTIVASI SEMESTER')],
            ['PERIODE: ' . strtoupper($this->stats['period'])],
            ['TOTAL RESPONDEN: ' . $this->stats['total_respondents']],
            ['TANGGAL EKSPOR: ' . date('d F Y H:i')],
            [''],
            ['Pertanyaan', 'Nilai Rata-rata', 'Total Jawaban']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = count($this->stats['rekap']) + 6;
        
        // Merge title cells
        $sheet->mergeCells('A1:C1');
        $sheet->mergeCells('A2:C2');
        $sheet->mergeCells('A3:C3');
        $sheet->mergeCells('A4:C4');

        return [
            1 => [
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '8B1538']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            2 => ['font' => ['bold' => true, 'size' => 12]],
            3 => ['font' => ['bold' => true, 'size' => 12]],
            6 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '8B1538']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            'B6:C' . $lastRow => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            'A6:C' . $lastRow => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
        ];
    }
}
