<?php

namespace App\Exports;

use App\Models\KategoriMaterial;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KategoriMaterialExport implements WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["kategori_material_name", "kategori_material_desc", "is_active"];
    }
}
