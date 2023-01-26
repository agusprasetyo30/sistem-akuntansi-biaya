<?php

namespace App\Exports\Template;


use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_GlosCCExport implements WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["plant_code", "cost_center", "material_code"];
    }

    public function title(): string
    {
        return 'Glos CC';
    }
}
