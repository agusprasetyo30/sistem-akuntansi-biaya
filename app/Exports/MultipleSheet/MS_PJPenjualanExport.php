<?php

namespace App\Exports\MultipleSheet;

use App\Exports\Master\M_MaterialExport;
use App\Exports\Template\T_PJPenjualanExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MS_PJPenjualanExport implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    function __construct($version)
    {
        $this->version = $version;
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new T_PJPenjualanExport($this->version);
        $sheets[] = new M_MaterialExport();
        return $sheets;
    }
}
