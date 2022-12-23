<?php

namespace App\Exports\Master;

use App\Models\GroupAccount;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class M_GroupAccountExport implements FromQuery, WithTitle, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        return GroupAccount::query()
            ->select('company_code', 'group_account_code', 'group_account_desc');
    }
    /**
     * @return string
     */
    public function title(): string
    {
        return 'Master Group Account';
    }

    public function headings(): array
    {
        return ["company_code", "group_account_code", "group_account_desc"];
    }
}
