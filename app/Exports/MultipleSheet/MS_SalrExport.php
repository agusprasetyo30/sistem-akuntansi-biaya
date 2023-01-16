<?php

namespace App\Exports\MultipleSheet;

use App\Exports\Master\M_CostCenterExport;
use App\Exports\Master\M_GLAccountFCExport;
use App\Exports\Master\M_GroupAccountFCExport;
use App\Exports\Master\M_MaterialExport;
use App\Exports\Template\T_SalrExport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MS_SalrExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new T_SalrExport();
        $sheets[] = new M_GroupAccountFCExport();
        $sheets[] = new M_GLAccountFCExport();
        $sheets[] = new M_CostCenterExport();
        $sheets[] = new M_MaterialExport();
        return $sheets;
    }
}
