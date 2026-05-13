<?php

namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KuisionerExport;

class ExportKuisionerExcelService
{
    public function export($type, $results, $stats)
    {
        $fileName = 'Hasil_Kuisioner_' . ucfirst($type) . '_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(new KuisionerExport($type, $results, $stats), $fileName);
    }
}
