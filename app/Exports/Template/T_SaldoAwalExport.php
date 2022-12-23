<?php

namespace App\Exports\Template;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_SaldoAwalExport implements WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["gl_account", "valuation_class", "price_control", "material_code", "plant_code", "total_stock", "total_value"];
    }

    public function title(): string
    {
        return 'Saldo Awal';
    }
}
