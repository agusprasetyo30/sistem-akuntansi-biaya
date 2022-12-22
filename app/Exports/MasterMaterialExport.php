<?php

namespace App\Exports;

use App\Models\Material;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class MasterMaterialExport implements FromQuery, WithTitle, WithHeadings
{
    /**
     * @return Builder
     */
    public function query()
    {
        return Material::query()
            ->select('material_code', 'material_name', 'material_desc', 'group_account_code', 'kategori_material_name', 'material_uom')
            ->leftJoin('kategori_material', 'kategori_material.id', '=', 'material.kategori_material_id');
    }
    /**
     * @return string
     */
    public function title(): string
    {
        return 'Master Material';
    }

    public function headings(): array
    {
        return ["material_code", "material_name", "material_desc", "group_account_code", "kategori_material_name", "material_uom"];
    }
}
