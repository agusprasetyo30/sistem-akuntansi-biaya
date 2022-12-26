<?php

namespace App\Exports\Master;

use App\Models\Regions;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class M_RegionsExport implements FromQuery, WithTitle, WithHeadings
{
    use Exportable;

    public function query()
    {
        $data = Regions::query()->where('is_active','=', true);
        return $data;
    }

    public function title(): string
    {
        return 'Master Region';
    }

    public function headings(): array
    {
        return ["id", "region_name"];
    }
}
