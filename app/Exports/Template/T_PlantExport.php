<?php

namespace App\Exports\Template;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_PlantExport implements WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["plant_code", "plant_desc", "is_active"];
    }

    public function title(): string
    {
        return 'Plant';
    }
}
