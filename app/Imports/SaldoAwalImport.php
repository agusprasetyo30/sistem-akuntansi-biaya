<?php

namespace App\Imports;

use App\Models\Saldo_Awal;
use App\Models\Version_Asumsi;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SaldoAwalImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $satuan = $row['total_value'] / $row['total_stock'];
        $my = Version_Asumsi::where('id', $row['version_id'])->first();

        return new Saldo_Awal([
            'company_code' => 'B000',
            'month_year' => $my->saldo_awal,
            'version_id' => $row['version_id'],
            'gl_account' => $row['gl_account'],
            'valuation_class' => $row['valuation_class'],
            'price_control' => $row['price_control'],
            'material_code' => $row['material_code'],
            'plant_code' => $row['plant_code'],
            'total_stock' => $row['total_stock'],
            'total_value' => $row['total_value'],
            'nilai_satuan' => $satuan,
            'created_by' => auth()->user()->id,
        ]);
    }

    public function rules(): array
    {
        return [
            'version_id' => ['required'],
            'gl_account' => ['required'],
            'valuation_class' => ['required'],
            'price_control' => ['required'],
            'material_code' => ['required'],
            'plant_code' => ['required'],
            'total_stock' => ['required'],
            'total_value' => ['required'],
        ];
    }
}
