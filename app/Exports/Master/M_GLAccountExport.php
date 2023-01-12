<?php

namespace App\Exports\Master;

use App\Models\GLAccount;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class M_GLAccountExport implements FromQuery, WithTitle, WithHeadings
{
    public function query()
    {
        return GLAccount::query()
            ->select('gl_account.gl_account', 'gl_account.gl_account_desc', 'group_account.group_account_code',  'group_account.group_account_desc')
            ->leftJoin('group_account', 'group_account.group_account_code', '=', 'gl_account.group_account_code');;
    }

    public function title(): string
    {
        return 'Master Cost Element';
    }

    public function headings(): array
    {
        return ["gl_account", "gl_account_desc", "group_account", "group_account_desc"];
    }
}
