<?php

namespace App\Imports;

use App\Models\Zco;
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

class ZcoImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure, WithBatchInserts, WithChunkReading, WithMultipleSheets, ToCollection, ShouldQueue
{
    use Importable, SkipsErrors, SkipsFailures;

    public function model(array $row)
    {
        return new Zco([
            'plant_code' => $row['plant_code'],
            'periode' => $row['periode'],
            'product_code' => $row['product_code'],
            'product_qty' => $row['product_qty'],
            'cost_element' => $row['cost_element'],
            'material_code' => $row['material_code'],
            'total_qty' => $row['total_qty'],
            'currency' => $row['currency'],
            'total_amount' => $row['total_amount'],
            'unit_price_product' => $row['unit_price_product'],
            'company_code' => auth()->user()->company_code,
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
            'plant_code' => [''],
            'periode' => [''],
            'product_code' => [''],
            'product_qty' => [''],
            'cost_element' => [''],
            'material_code' => [''],
            'total_qty' => [''],
            'currency' => [''],
            'total_amount' => [''],
            'unit_price_product' => [''],
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
