<?php

namespace App\Exports\Template;

use Maatwebsite\Excel\Concerns\WithHeadings;

class T_ConsRateExport implements WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["plant_code", "month_year", "product_code", "material_code", "cons_rate"];
    }
}
