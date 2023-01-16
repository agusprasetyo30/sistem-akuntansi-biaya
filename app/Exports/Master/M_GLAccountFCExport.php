<?php

namespace App\Exports\Master;

use App\Models\GLAccountFC;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class M_GLAccountFCExport implements FromQuery, WithTitle, WithHeadings
{
    public function query()
    {
        return GLAccountFC::query()
            ->select('gl_account_fc.gl_account_fc', 'gl_account_fc.gl_account_fc_desc', 'group_account_fc.group_account_fc',  'group_account_fc.group_account_fc_desc')
            ->leftJoin('group_account_fc', 'group_account_fc.group_account_fc', '=', 'gl_account_fc.group_account_fc')
            ->where('gl_account_fc.company_code', auth()->user()->company_code)
            ->orderBy('gl_account_fc.group_account_fc', 'ASC');
    }

    public function title(): string
    {
        return 'Master General Ledger FC';
    }

    public function headings(): array
    {
        return ["gl_account_fc", "gl_account_fc_desc", "group_account_fc", "group_account_fc_desc"];
    }
}
