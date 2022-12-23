<?php

namespace App\Exports\Master;

use App\Models\Plant;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class M_PlantExport implements FromQuery, WithTitle, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        return Plant::query()
            ->select('company_code', 'plant_code', 'plant_desc');
    }
    /**
     * @return string
     */
    public function title(): string
    {
        return 'Master Plant';
    }

    public function headings(): array
    {
        return ["company_code", "plant_code", "plant_desc"];
    }
}
