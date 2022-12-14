<?php

namespace App\Imports;

use App\Models\GroupAccount;
use App\Models\Material;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MaterialImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Material([
            'material_code' => $row['material_code'],
            'material_name' => $row['material_name'],
            'material_desc' => $row['material_desc'],
            'group_account_code' => $row['group_account_code'],
            'kategori_material_id' => $row['kategori_material_id'],
            'material_uom' => $row['material_uom'],
            'company_code' => 'B000',
            'is_dummy' => $row['is_dummy'],
            'is_active' => $row['is_active'],
            'created_by' => auth()->user()->id,
        ]);
    }

    public function rules(): array
    {
        return [
            'material_code' => ['required', 'unique:material,material_code'],
            'material_name' => ['required'],
            'material_desc' => ['required'],
            'group_account_code' => ['required'],
            'kategori_material_id' => ['required'],
            'material_uom' => ['required'],
        ];
    }
}