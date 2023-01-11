<?php

namespace App\Exports\MultipleSheet;

use App\Exports\Master\M_CostCenterExport;
use App\Exports\Template\T_KuantitiRenProdExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MS_KuantitiRenProdExport implements WithMultipleSheets
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
        $sheets[] = new T_KuantitiRenProdExport($this->version);
        $sheets[] = new M_CostCenterExport();
        return $sheets;
    }
}
