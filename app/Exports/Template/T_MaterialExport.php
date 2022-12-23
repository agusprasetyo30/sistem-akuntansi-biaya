<?php

namespace App\Exports\Template;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_MaterialExport implements WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["material_code", "material_name", "material_desc", "group_account_code", "kategori_material_id", "material_uom", "is_active", "is_dummy"];
    }

    public function title(): string
    {
        return 'Material';
    }
}
