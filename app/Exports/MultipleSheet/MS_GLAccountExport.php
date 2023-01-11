<?php

namespace App\Exports\MultipleSheet;

use App\Exports\Master\M_GroupAccountExport;
use App\Exports\Template\T_GLAccountExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MS_GLAccountExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new T_GLAccountExport();
        $sheets[] = new M_GroupAccountExport();
        return $sheets;
    }
}
