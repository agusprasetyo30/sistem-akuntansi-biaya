<?php

namespace App\Exports\Template;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_ConsRateExport implements WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["plant_code", "periode", "product_code", "material_code", "cons_rate"];
    }

    public function title(): string
    {
        return 'Consumption Rate';
    }
}
