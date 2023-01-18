<?php

namespace App\Exports\Template;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_ZcoExport implements WithHeadings, WithTitle
{
    public function headings(): array
    {
        $result = ["plant_code", "product_code", "product_qty", "cost_element", "material_code", "total_qty", "currency", "total_amount", "unit_price_product"];

        return $result;
    }

    public function title(): string
    {
        return 'ZCO';
    }
}
