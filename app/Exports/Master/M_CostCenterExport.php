<?php

namespace App\Exports\Master;

use App\Models\CostCenter;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class M_CostCenterExport implements FromQuery, WithTitle, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        return CostCenter::query()
            ->select('company_code', 'cost_center', 'cost_center_desc');
    }

    public function title(): string
    {
        return 'Master Cost Center';
    }

    public function headings(): array
    {
        return ["company_code", "cost_center", "cost_center_desc"];
    }
}
