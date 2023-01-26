<?php

namespace App\Exports\MultipleSheet;

use App\Exports\Master\M_GroupAccountExport;
use App\Exports\Master\M_KategoriMaterialExport;
use App\Exports\Master\M_KategoriProdukExport;
use App\Exports\Template\T_MaterialExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MS_MaterialExport implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new T_MaterialExport();
        $sheets[] = new M_KategoriMaterialExport();
        $sheets[] = new M_KategoriProdukExport();
        $sheets[] = new M_GroupAccountExport();
        return $sheets;
    }
}
