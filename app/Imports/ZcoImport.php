<?php

namespace App\Imports;

use App\Models\Zco;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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

    protected $periode;

    public function __construct($periode)
    {
        $this->periode = $periode;
    }

    public function model(array $row)
    {
        DB::table('zco')->insert([
            'plant_code' => $row['plant_code'],
            'periode' => $this->periode,
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
            'created_at' => Carbon::now()->format('Y-m-d'),
            'updated_at' => Carbon::now()->format('Y-m-d'),
        ]);
    }

    public function batchSize(): int
    {
        return 5000;
    }

    public function chunkSize(): int
    {
        return 5000;
    }

    public function rules(): array
    {
        return [
            'plant_code' => 'required',
            'product_code' => 'required',
            'material_code' => 'required',
            'cost_element' => 'required',
            'product_qty' => 'required',
            'total_qty' => 'required',
            'currency' => 'required',
            'total_amount' => 'required',
            'unit_price_product' => 'required',
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
