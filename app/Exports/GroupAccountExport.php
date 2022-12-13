<?php

namespace App\Exports;

use App\Models\GroupAccount;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GroupAccountExport implements WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["group_account_code", "group_account_desc", "is_active"];
    }
}
