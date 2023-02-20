<?php

namespace App\Exports\Template;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_TarifExport implements WithHeadings, WithTitle
{
    public function headings(): array
    {
        $result = ["product_code", "plant_code", "group_account_fc", "tarif_value"];

        return $result;
    }

    public function title(): string
    {
        return 'Tarif';
    }
}
