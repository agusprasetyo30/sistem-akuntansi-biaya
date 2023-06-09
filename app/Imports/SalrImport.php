<?php

namespace App\Imports;

use App\Models\Salr;
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

class SalrImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure, WithBatchInserts, WithChunkReading, WithMultipleSheets, ToCollection, ShouldQueue
{

    use Importable, SkipsErrors, SkipsFailures;

    protected $asumsi;

    public function __construct($asumsi)
    {
        $this->periode = $asumsi->month_year;
        $this->version_id = $asumsi->version_id;
    }

    public function model(array $row)
    {
        return new Salr([
            'gl_account_fc' => $row['gl_account_fc'],
            'cost_center' => $row['cost_center'],
            'periode' => $this->periode,
            'version_id' => $this->version_id,
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
            'created_at' => Carbon::now()->format('Y-m-d'),
            'updated_at' => Carbon::now()->format('Y-m-d'),
        ]);

    }

    public function batchSize(): int
    {
        return 20000;
    }

    public function chunkSize(): int
    {
        return 4000;
    }

    public function rules(): array
    {
        return [
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
