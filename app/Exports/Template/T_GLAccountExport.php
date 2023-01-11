<?php

namespace App\Exports\Template;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_GLAccountExport implements WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["gl_account", "gl_account_desc", "group_account_code"];
    }

    public function title(): string
    {
        return 'Group Ledger Account';
    }
}
