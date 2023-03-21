<?php

namespace App\Exports\MultipleSheet;

use App\Exports\Master\M_GroupAccountFCExport;
use App\Exports\Master\M_PlantExport;
use App\Exports\Master\M_ProdukExport;
use App\Exports\Template\T_TarifExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MS_TarifExport implements WithMultipleSheets
{
    protected $version;

    public function __construct($version)
    {
        $this->version = $version;
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new T_TarifExport($this->version);
        $sheets[] = new M_ProdukExport();
        $sheets[] = new M_PlantExport();
        $sheets[] = new M_GroupAccountFCExport();
        return $sheets;
    }
}
