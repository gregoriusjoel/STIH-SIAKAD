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

class KuisionerDetailSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $type;
    protected $results;

    public function __construct($type, $results)
    {
        $this->type = $type;
        $this->results = $results;
    }

    public function collection()
    {
        $data = [];
        foreach ($this->results as $row) {
            $item = [
                'name' => $row->mahasiswa?->user?->name ?? 'N/A',
                'nim' => $row->mahasiswa?->nim ?? 'N/A',
                'prodi' => $row->mahasiswa?->prodiData?->nama_prodi ?? 'N/A',
            ];

            if ($this->type === 'mahasiswa_baru') {
                foreach (range(1, 7) as $i) {
                    $item['q'.$i] = $row->{'q'.$i};
                }
            } else {
                $item['fasilitas'] = $row->fasilitas_kampus;
                $item['akademik'] = $row->sistem_akademik;
                $item['dosen'] = $row->kualitas_dosen;
                $item['admin'] = $row->layanan_administrasi;
                $item['overall'] = $row->kepuasan_keseluruhan;
            }

            $item['date'] = $row->created_at->format('Y-m-d H:i');
            $data[] = $item;
        }
        return collect($data);
    }

    public function title(): string
    {
        return 'Detail Jawaban';
    }

    public function headings(): array
    {
        $headings = ['Nama', 'NIM', 'Program Studi'];
        if ($this->type === 'mahasiswa_baru') {
            foreach (range(1, 7) as $i) $headings[] = 'Q'.$i;
        } else {
            $headings = array_merge($headings, ['Fasilitas', 'Akademik', 'Dosen', 'Admin', 'Overall']);
        }
        $headings[] = 'Tanggal Isi';
        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = count($this->results) + 1;
        $lastCol = $this->type === 'mahasiswa_baru' ? 'K' : 'I';

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
            'D2:' . $lastCol . $lastRow => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'A1:' . $lastCol . $lastRow => [
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
