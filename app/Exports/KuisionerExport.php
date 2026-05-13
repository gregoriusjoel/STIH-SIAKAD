<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\KuisionerSummarySheet;
use App\Exports\Sheets\KuisionerDetailSheet;
use App\Exports\Sheets\KuisionerSaranSheet;

class KuisionerExport implements WithMultipleSheets
{
    protected $type;
    protected $results;
    protected $stats;

    public function __construct($type, $results, $stats)
    {
        $this->type = $type;
        $this->results = $results;
        $this->stats = $stats;
    }

    public function sheets(): array
    {
        return [
            new KuisionerSummarySheet($this->type, $this->stats),
            new KuisionerDetailSheet($this->type, $this->results),
            new KuisionerSaranSheet($this->results),
        ];
    }
}
