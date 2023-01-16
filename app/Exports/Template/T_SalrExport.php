<?php

namespace App\Exports\Template;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_SalrExport implements WithHeadings, WithTitle
{
    public function headings(): array
    {
        $result = ["group_account_fc", "gl_account_fc", "cost_center", "value", "name", "partner_cost_center", "username", "material_code", "document_number", 'document_number_text', 'purchase_order'];

        return $result;
    }

    public function title(): string
    {
        return 'SALR';
    }
}
