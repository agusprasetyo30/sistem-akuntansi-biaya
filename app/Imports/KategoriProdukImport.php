<?php

namespace App\Imports;

use App\Models\KategoriProduk;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class KategoriProdukImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure, WithBatchInserts, WithChunkReading, SkipsEmptyRows
{
    use Importable, SkipsErrors, SkipsFailures;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        KategoriProduk::create([
            'kategori_produk_name' => strtoupper($row['kategori_produk_name']),
            'kategori_produk_desc' => $row['kategori_produk_desc'],
            'company_code' => auth()->user()->company_code,
            'is_active' => $row['is_active'],
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
            'kategori_produk_name' => ['required', 'unique:kategori_produk,kategori_produk_name']
        ];
    }
}
