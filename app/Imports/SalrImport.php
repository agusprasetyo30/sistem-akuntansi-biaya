<?php

namespace App\Imports;

use App\Models\Salr;
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

class SalrImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure, WithBatchInserts, WithChunkReading, WithMultipleSheets, ToCollection, ShouldQueue
{

    use Importable, SkipsErrors, SkipsFailures;

    protected $periode;

    public function __construct($periode)
    {
        $this->periode = $periode;
    }

    public function model(array $row)
    {
//        dd($row);
        Salr::create([
            'group_account_fc' => $row['group_account_fc'],
            'gl_account_fc' => $row['gl_account_fc'],
            'cost_center' => $row['cost_center'],
            'periode' => $this->periode,
            'name' => $row['name'],
            'value' => $row['value'] != null ? (double) str_replace('.', '', str_replace('Rp ', '', $row['value'])) : 0,
            'partner_cost_center' => $row['partner_cost_center'],
            'username' => $row['username'],
            'material_code' => $row['material_code'],
            'document_number' => $row['document_number'],
            'document_number_text' => $row['document_number_text'],
            'purchase_order' => $row['purchase_order'],
            'company_code' => auth()->user()->company_code,
            'created_by' => auth()->user()->id,
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
            'group_account_fc' => 'required',
            'gl_account_fc' => 'required',
            'cost_center' => 'required',
            'value' => 'required',
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
