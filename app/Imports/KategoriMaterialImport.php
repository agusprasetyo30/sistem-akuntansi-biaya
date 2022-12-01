<?php

namespace App\Imports;

use App\Models\KategoriMaterial;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel; 
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KategoriMaterialImport implements ToModel, WithValidation, WithHeadingRow
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new KategoriMaterial([
            'kategori_material_name' => $row['kategori_material_name'],
            'kategori_material_desc' => $row['kategori_material_desc'],
            'is_active' => $row['is_active'],
            'created_by' => auth()->user()->id,
        ]);
    }

    public function rules(): array{
        return[
            'kategori_material_name' => ['required']
        ];
    }
}
