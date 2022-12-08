<?php

namespace App\Exports;

use App\Models\Plant;
use DateTime;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PlantExport implements WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["plant_code", "plant_desc", "is_active"];
    }
}
