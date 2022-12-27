<?php

namespace App\Exports\MultipleSheet;

use App\Exports\Master\M_AsumsiUmumExport;
use App\Exports\Master\M_MaterialExport;
use App\Exports\Master\M_PlantExport;
use App\Exports\Template\T_ConsRateExport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MS_ComsumptionRatioExport implements WithMultipleSheets
{
    protected $version;

    public function __construct($version)
    {
        $this->version = $version;
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new T_ConsRateExport($this->version);
        $sheets[] = new M_PlantExport();
        $sheets[] = new M_MaterialExport();
        $sheets[] = new M_AsumsiUmumExport();
        return $sheets;
    }
}
