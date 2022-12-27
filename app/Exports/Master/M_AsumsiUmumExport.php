<?php

namespace App\Exports\Master;

use App\Models\Asumsi_Umum;
use App\Models\Regions;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class M_AsumsiUmumExport implements FromQuery, WithTitle, WithHeadings
{
    use Exportable;

    public function query()
    {
//        $data = Asumsi_Umum::query()
//            ->select('version_asumsi.id', 'version_asumsi.version', 'asumsi_umum.month_year', 'asumsi_umum.saldo_awal')
//            ->leftJoin('version_asumsi', 'version_asumsi.id', '=', 'asumsi_umum.version_id');

        $data = Asumsi_Umum::query()
            ->select('version_asumsi.id', 'version_asumsi.version', 'asumsi_umum.month_year', 'asumsi_umum.saldo_awal')
            ->leftJoin('version_asumsi', 'version_asumsi.id', '=', 'asumsi_umum.version_id');

        return $data;
    }

    public function title(): string
    {
        return 'Master Asumsi';
    }

    public function headings(): array
    {
        return ["id_version", "version_name", 'periode', 'saldo_awal'];
    }
}
