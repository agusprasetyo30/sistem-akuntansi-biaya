<?php

namespace App\Exports;

use App\Models\Periode;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PeriodeExport implements WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return ["periode_name", "awal_periode", "akhir_periode", "is_active"];
    }
}
