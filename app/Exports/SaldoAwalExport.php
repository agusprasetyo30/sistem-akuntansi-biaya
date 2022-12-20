<?php

namespace App\Exports;

use App\Models\SaldoAwal;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SaldoAwalExport implements WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["gl_account", "valuation_class", "price_control", "material_code", "plant_code", "total_stock", "total_value"];
    }
}
