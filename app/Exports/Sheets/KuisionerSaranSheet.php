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

class KuisionerSaranSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $results;

    public function __construct($results)
    {
        $this->results = $results;
    }

    public function collection()
    {
        return $this->results->filter(fn($r) => !empty($r->saran))->map(function($row) {
            return [
                'name' => $row->mahasiswa?->user?->name ?? 'N/A',
                'nim' => $row->mahasiswa?->nim ?? 'N/A',
                'saran' => $row->saran,
                'date' => $row->created_at->format('Y-m-d H:i')
            ];
        });
    }

    public function title(): string
    {
        return 'Kritik & Saran';
    }

    public function headings(): array
    {
        return ['Nama', 'NIM', 'Kritik & Saran', 'Tanggal'];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->results->filter(fn($r) => !empty($r->saran))->count() + 1;
        
        // Wrap text for saran column
        $sheet->getStyle('C2:C' . $lastRow)->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('C')->setWidth(50);

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '8B1538']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            'B2:B' . $lastRow => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'D2:D' . $lastRow => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'A1:D' . $lastRow => [
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
