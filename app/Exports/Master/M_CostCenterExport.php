<?php

namespace App\Exports\Master;

use App\Models\CostCenter;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class M_CostCenterExport implements FromQuery, WithTitle, WithHeadings
{
    use Exportable;

    public function query()
    {
        $data = CostCenter::query()
            ->select('cost_center', 'cost_center_desc', 'company_code')
            ->where('company_code', auth()->user()->company_code);
        return $data;
    }

    public function title(): string
    {
        return 'Master Cost Center';
    }

    public function headings(): array
    {
        return ["cost_center", "cost_center_desc", "company_code"];
    }
}
