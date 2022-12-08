<?php

namespace App\Exports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProdukExport implements WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["produk_name", "produk_desc", "kategori_produk_id", "is_dummy", "is_active"];
    }
}
