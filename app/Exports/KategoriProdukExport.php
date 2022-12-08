<?php

namespace App\Exports;

use App\Models\KategoriProduk;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KategoriProdukExport implements WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["kategori_produk_name", "kategori_produk_desc", "is_active"];
    }
}
