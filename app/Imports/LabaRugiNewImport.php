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

class LabaRugiNewImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure, WithBatchInserts, WithChunkReading, WithMultipleSheets, ToCollection, ShouldQueue
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $periode;

    public function __construct($periode)
    {
        $this->data_main_version_import = $periode;
    }

    public function model(array $row)
    {
        $input['version_id'] = $this->data_main_version_import;
        $input['kategori_produk_id'] = $row['kategori_produk_id'];
        $input['value_bp'] = $row['biaya_penjualan'] != null ? (double) $row['biaya_penjualan']: 0;
        $input['value_bau'] = $row['biaya_adm_umum'] != null ? (double) $row['biaya_adm_umum'] : 0;
        $input['value_bb'] = $row['biaya_bunga'] != null ? (double) $row['biaya_bunga'] : 0;
        $input['company_code'] = auth()->user()->company_code;
        $input['created_by'] = auth()->user()->id;
        $input['created_at'] = Carbon::now()->format('Y-m-d');
        $input['updated_at'] = Carbon::now()->format('Y-m-d');
        DB::table('laba_rugi')->insert($input);
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
            'biaya_penjualan' => 'required',
            'biaya_adm_umum' => 'required',
            'biaya_bunga' => 'required',
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
