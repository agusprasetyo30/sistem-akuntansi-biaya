<?php

namespace App\Exports\Master;

use App\Models\GroupAccountFC;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class M_GroupAccountFCExport implements FromQuery, WithTitle, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        return GroupAccountFC::query()
            ->select('group_account_fc', 'group_account_fc_desc', 'company_code');
    }
    /**
     * @return string
     */
    public function title(): string
    {
        return 'Master Group Account FC';
    }

    public function headings(): array
    {
        return ["group_account_fc", "group_account_fc_desc", "company_code"];
    }
}
