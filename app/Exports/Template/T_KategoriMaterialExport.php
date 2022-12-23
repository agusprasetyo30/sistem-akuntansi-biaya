<?php

namespace App\Exports\Template;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_KategoriMaterialExport implements WithHeadings, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["kategori_material_name", "kategori_material_desc", "is_active"];
    }

    public function title(): string
    {
        return 'Kategori Material';
    }
}
