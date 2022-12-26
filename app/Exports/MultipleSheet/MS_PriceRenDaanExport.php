<?php

namespace App\Exports\MultipleSheet;

use App\Exports\Master\M_MaterialExport;
use App\Exports\Master\M_RegionsExport;

use App\Exports\Template\T_PriceRenDaanExport;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MS_PriceRenDaanExport implements WithMultipleSheets
{
    use Exportable;
    protected $version;

    public function __construct($version)
    {
        $this->version = $version;
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new T_PriceRenDaanExport($this->version);
        $sheets[] = new M_MaterialExport();
        $sheets[] = new M_RegionsExport();
        return $sheets;
    }
}
