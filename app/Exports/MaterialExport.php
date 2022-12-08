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
        return ["material_name", "material_desc", "kategori_material_id", "uom", "is_dummy", "is_active"];
    }
}
