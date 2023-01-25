<?php

namespace App\Exports\Template;

use App\Models\Asumsi_Umum;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_PJPenjualanExport implements WithHeadings, WithTitle
{
    protected $version;

    function __construct($version)
    {
        $this->version = $version;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        $period = Asumsi_Umum::where('version_id', $this->version)->get();

        $result = ["material_code"];

        foreach ($period as $dt) {
            $per = format_month($dt->month_year, 'ye');
            array_push($result, $per);
        }

        return $result;
    }

    public function title(): string
    {
        return 'Penjualan';
    }
}
