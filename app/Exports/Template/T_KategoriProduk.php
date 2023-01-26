<?php

namespace App\Exports\Template;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_KategoriProduk implements WithHeadings, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return ["kategori_produk_name", "kategori_produk_desc", "is_active"];
    }

    public function title(): string
    {
        return 'Kategori Produk';
    }
}
