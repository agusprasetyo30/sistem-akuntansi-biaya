<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TemplateQtyRenProdExport implements WithMultipleSheets
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
        $sheets[] = new QtyRenProdExport($this->version);
        $sheets[] = new MasterMaterialExport();
        return $sheets;
    }
}
