<?php

namespace App\Exports\Template;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_GroupAccountFCExport implements WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["group_account_fc", "group_account_fc_desc"];
    }

    public function title(): string
    {
        return 'Group Account Fixed Cost';
    }
}
