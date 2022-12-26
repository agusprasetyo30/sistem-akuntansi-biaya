<?php

namespace App\Exports\MultipleSheet;

use App\Exports\Master\M_MaterialExport;
use App\Exports\Master\M_PlantExport;
use App\Exports\Template\T_SaldoAwalExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MS_SaldoAwalExport implements WithMultipleSheets
{
    protected $version;

    public function __construct($version)
    {
        $this->version = $version;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new T_SaldoAwalExport($this->version);
        $sheets[] = new M_PlantExport();
        $sheets[] = new M_MaterialExport();
        return $sheets;
    }
}
