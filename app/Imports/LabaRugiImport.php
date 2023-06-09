<?php

namespace App\Imports;

use App\Models\LabaRugi;
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

class LabaRugiImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure, WithBatchInserts, WithChunkReading, WithMultipleSheets, ToCollection, ShouldQueue
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $periode;

    public function __construct($periode)
    {
        $this->periode = $periode;
    }

    public function model(array $row)
    {
//        return $row;
        LabaRugi::create([
            'periode' => $this->periode.'-01-01',
            'kategori_produk_id' => $row['kategori_produk_id'],
            'value_bp' => $row['biaya_penjualan'] != null ? (double) str_replace('.', '', str_replace('Rp ', '', $row['biaya_penjualan'])) : 0,
            'value_bau' => $row['biaya_adm_umum'] != null ? (double) str_replace('.', '', str_replace('Rp ', '', $row['biaya_adm_umum'])) : 0,
            'value_bb' => $row['biaya_bunga'] != null ? (double) str_replace('.', '', str_replace('Rp ', '', $row['biaya_bunga'])) : 0,
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
            'kategori_produk_id' => 'required',
            'value_bp' => 'required',
            'value_bau' => 'required',
            'value_bb' => 'required',
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
