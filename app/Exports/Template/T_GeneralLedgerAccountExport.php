<?php

namespace App\Exports\Template;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_GeneralLedgerAccountExport implements WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["general_ledger_account", "general_ledger_account_desc", "group_account_fc"];
    }

    public function title(): string
    {
        return 'Group Ledger Account';
    }
}
