<?php

namespace App\Exports\MultipleSheet;

use App\Exports\Master\M_CostElementExport;
use App\Exports\Master\M_MaterialExport;
use App\Exports\Master\M_PlantExport;
use App\Exports\Master\M_ProdukExport;
use App\Exports\Template\T_ZcoExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MS_ZcoExport implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new T_ZcoExport();
        $sheets[] = new M_PlantExport();
        $sheets[] = new M_MaterialExport();
        $sheets[] = new M_ProdukExport();
        $sheets[] = new M_CostElementExport();
        return $sheets;
    }
}
