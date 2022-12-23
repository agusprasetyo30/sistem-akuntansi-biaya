<?php

namespace App\Exports\Template;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_GroupAccountExport implements WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["group_account_code", "group_account_desc", "is_active"];
    }

    public function title(): string
    {
        return 'Group Account';
    }
}
