<?php

namespace App\Exports\Template;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_GLAccountFCExport implements WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["gl_account_fc", "gl_account_fc_desc", "group_account_fc"];
    }

    public function title(): string
    {
        return 'Group Ledger Account';
    }
}
