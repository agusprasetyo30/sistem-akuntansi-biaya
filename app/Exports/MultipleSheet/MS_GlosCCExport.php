<?php

namespace App\Exports\MultipleSheet;

use App\Exports\Master\M_CostCenterExport;
use App\Exports\Master\M_MaterialExport;
use App\Exports\Master\M_PlantExport;
use App\Exports\Template\T_GlosCCExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MS_GlosCCExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new T_GlosCCExport();
        $sheets[] = new M_PlantExport();
        $sheets[] = new M_CostCenterExport();
        $sheets[] = new M_MaterialExport();
        return $sheets;
    }
}
