<?php

namespace App\Imports;

use App\Models\GroupAccount;
use App\Models\Material;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithValidation;

class MaterialImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure, WithBatchInserts, WithChunkReading, WithMultipleSheets
{
    use Importable, SkipsErrors, SkipsFailures;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
        $mapping = [
            [
                'material_code' => $row['material_code'],
                'kategori_balans_id' => 1,
                'company_code' => auth()->user()->company_code,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'material_code' => $row['material_code'],
                'kategori_balans_id' => 2,
                'company_code' => auth()->user()->company_code,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'material_code' => $row['material_code'],
                'kategori_balans_id' => 3,
                'company_code' => auth()->user()->company_code,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'material_code' => $row['material_code'],
                'kategori_balans_id' => 4,
                'company_code' => auth()->user()->company_code,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'material_code' => $row['material_code'],
                'kategori_balans_id' => 5,
                'company_code' => auth()->user()->company_code,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        $material = [
            'material_code' => $row['material_code'],
            'material_name' => $row['material_name'],
            'material_desc' => $row['material_desc'],
            'group_account_code' => $row['group_account_code'],
            'kategori_material_id' => $row['kategori_material_id'],
            'kategori_produk_id' => $row['kategori_produk_id'],
            'material_uom' => $row['material_uom'],
            'company_code' => auth()->user()->company_code,
            'is_dummy' => $row['is_dummy'],
            'is_active' => $row['is_active'],
            'created_by' => auth()->user()->id,
        ];

        Material::create($material);
        DB::table('map_kategori_balans')->insert($mapping);
    }

    public function batchSize(): int
    {
        return 50;
    }

    public function chunkSize(): int
    {
        return 50;
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

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }
}
