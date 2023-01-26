<?php

namespace App\Exports\Master;

use App\Models\KategoriProduk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class M_KategoriProdukExport implements FromQuery, WithTitle, WithHeadings
{

    public function query()
    {
        return KategoriProduk::query()
            ->select('id', 'company_code', 'kategori_produk_name', 'kategori_produk_desc');
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function title(): string
    {
        return 'Master Kategori Produk';
    }

    public function headings(): array
    {
        return ["id", "company_code", "kategori_material_name", "kategori_material_desc"];
    }
}
