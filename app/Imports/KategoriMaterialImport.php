<?php

namespace App\Imports;

use App\Models\KategoriMaterial;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KategoriMaterialImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;
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
            'company_code' => 'B000',
            'is_active' => $row['is_active'],
            'created_by' => auth()->user()->id,
        ]);
    }

    public function rules(): array
    {
        return [
            'kategori_material_name' => ['required']
        ];
    }
}
