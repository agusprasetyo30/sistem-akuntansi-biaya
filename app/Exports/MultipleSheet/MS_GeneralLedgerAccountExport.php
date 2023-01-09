<?php

namespace App\Exports\MultipleSheet;

use App\Exports\Master\M_GroupAccountFCExport;
use App\Exports\Template\T_GeneralLedgerAccountExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MS_GeneralLedgerAccountExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new T_GeneralLedgerAccountExport();
        $sheets[] = new M_GroupAccountFCExport();
        return $sheets;
    }
}
