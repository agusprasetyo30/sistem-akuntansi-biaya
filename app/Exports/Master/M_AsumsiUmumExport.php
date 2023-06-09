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
        $data = Asumsi_Umum::query()
            ->select('version_asumsi.id', 'version_asumsi.version', DB::raw("date_part( 'year' :: TEXT, asumsi_umum.month_year )||'-'|| lpad(date_part( 'month' :: TEXT, asumsi_umum.month_year)::TEXT, 2, '0') as month_year"), DB::raw("date_part( 'year' :: TEXT, asumsi_umum.saldo_awal )||'-'|| lpad(date_part( 'month' :: TEXT, asumsi_umum.saldo_awal)::TEXT, 2, '0') as saldo_awal"))
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
