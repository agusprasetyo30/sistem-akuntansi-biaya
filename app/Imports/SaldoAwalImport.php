<?php

namespace App\Imports;

use App\Models\Saldo_Awal;
use App\Models\Version_Asumsi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithValidation;

class SaldoAwalImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure, WithBatchInserts, WithChunkReading, WithMultipleSheets, ToCollection, ShouldQueue
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $version;

    function __construct($version)
    {
        $this->version = $version;
    }

    public function model(array $row)
    {
        // $arrHeader = array_keys($row);
        // $arrVer = substr($arrHeader[7], 11);

        $satuan = $row['total_value'] / $row['total_stock'];
        // $my = Version_Asumsi::where('id', $this->version)->first();
        $my = Version_Asumsi::where('id', $this->version)->first();
        // dd($this->version);
        return new Saldo_Awal([
            'company_code' => auth()->user()->company_code,
            // 'month_year' => $my->saldo_awal,
            // 'version_id' => $this->version,
            'month_year' => $my->saldo_awal,
            'version_id' => $this->version,
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
            'gl_account' => ['required'],
            'valuation_class' => ['required'],
            'price_control' => ['required'],
            'material_code' => ['required'],
            'plant_code' => ['required'],
            'total_stock' => ['required'],
            'total_value' => ['required'],
        ];
    }

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function collection(Collection $collection)
    {
        return $collection;
    }
}
