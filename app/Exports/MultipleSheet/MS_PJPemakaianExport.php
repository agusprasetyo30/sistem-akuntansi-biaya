<?php

namespace App\Exports\MultipleSheet;

use App\Exports\Master\M_MaterialExport;
use App\Exports\Template\T_PJPemakaianExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MS_PJPemakaianExport implements WithMultipleSheets
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
        $sheets[] = new T_PJPemakaianExport($this->version);
        $sheets[] = new M_MaterialExport();
        return $sheets;
    }
}
