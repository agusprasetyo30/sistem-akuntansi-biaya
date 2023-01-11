<?php

namespace App\Exports\MultipleSheet;

use App\Exports\Master\M_GroupAccountFCExport;
use App\Exports\Template\T_GLAccountFCExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MS_GLAccountFCExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new T_GLAccountFCExport();
        $sheets[] = new M_GroupAccountFCExport();
        return $sheets;
    }
}
