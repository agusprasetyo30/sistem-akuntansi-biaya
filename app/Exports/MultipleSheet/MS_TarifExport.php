<?php

namespace App\Exports\MultipleSheet;

use App\Exports\Master\M_GroupAccountFCExport;
use App\Exports\Master\M_PlantExport;
use App\Exports\Master\M_ProdukExport;
use App\Exports\Template\T_TarifExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MS_TarifExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new T_TarifExport();
        $sheets[] = new M_ProdukExport();
        $sheets[] = new M_PlantExport();
        $sheets[] = new M_GroupAccountFCExport();
        return $sheets;
    }
}
