<?php

namespace App\Exports\Template;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_LabaRugiExport implements WithHeadings, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return ["kategori_produk_id", "biaya_penjualan", "biaya_adm_umum", "biaya_bunga"];
    }

    public function title(): string
    {
        return 'Laba Rugi';
    }
}
