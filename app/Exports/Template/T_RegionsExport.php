<?php

namespace App\Exports\Template;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_RegionsExport implements WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["region_name", "region_desc", "latitude", "longtitude", "is_active"];
    }

    public function title(): string
    {
        return 'Region';
    }
}
