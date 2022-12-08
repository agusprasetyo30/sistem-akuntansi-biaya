<?php

namespace App\Exports;

use App\Models\Regions;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RegionsExport implements WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["region_name", "region_desc", "latitude", "longtitude", "is_active"];
    }
}
