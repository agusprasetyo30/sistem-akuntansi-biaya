<?php

namespace App\Exports\MultipleSheet;

use App\Exports\Master\M_KategoriProdukExport;
use App\Exports\Template\T_LabaRugiExport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MS_LabaRugiExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new T_LabaRugiExport();
        $sheets[] = new M_KategoriProdukExport();
        return $sheets;
    }
}
