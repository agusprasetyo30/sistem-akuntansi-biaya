<?php

namespace App\Exports\Template;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_SaldoAwalExport implements WithHeadings, WithTitle
{
    protected $version;

    function __construct($version)
    {
        $this->version = $version;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        $result = ["gl_account", "valuation_class", "price_control", "material_code", "plant_code", "total_stock", "total_value"];
        
        return $result;
    }

    public function title(): string
    {
        return 'Saldo Awal';
    }
}
