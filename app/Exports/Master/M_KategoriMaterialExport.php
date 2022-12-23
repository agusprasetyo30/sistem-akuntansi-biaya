<?php

namespace App\Exports\Master;

use App\Models\KategoriMaterial;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class M_KategoriMaterialExport implements FromQuery, WithTitle, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        return KategoriMaterial::query()
            ->select('company_code', 'kategori_material_name', 'kategori_material_desc');
    }
    /**
     * @return string
     */
    public function title(): string
    {
        return 'Master Kategori Material';
    }

    public function headings(): array
    {
        return ["company_code", "kategori_material_name", "kategori_material_desc"];
    }
}
