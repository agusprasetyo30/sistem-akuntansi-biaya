<?php

namespace App\Exports;

use App\Models\Material;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MaterialExport implements WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["material_code", "material_name", "material_desc", "group_account_code", "kategori_material_id", "material_uom", "is_active", "is_dummy"];
    }
}
