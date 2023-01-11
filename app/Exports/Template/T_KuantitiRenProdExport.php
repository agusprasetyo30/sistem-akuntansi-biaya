<?php

namespace App\Exports\Template;

use App\Models\Asumsi_Umum;
use App\Models\Version_Asumsi;
use DateInterval;
use DatePeriod;
use DateTime;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_KuantitiRenProdExport implements WithHeadings, WithTitle
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

        // if ($this->version == null) {
        //     $asum = Version_Asumsi::first();
        // } else {
        //     $asum = Version_Asumsi::where('id', $this->version)->first();
        // }

        // $asum = Version_Asumsi::where('id', $this->version)->first();

        $period = Asumsi_Umum::where('version_id', $this->version)->get();

        // $asum = Periode::where('id', $this->version)->first();
        // $awal = date('Y-m-d', strtotime($asum->awal_periode));
        // $akhir = date('Y-m-d', strtotime($asum->akhir_periode . ' + 1 month'));
        // $start = new DateTime($awal);
        // $interval = new DateInterval('P1M');
        // $end = new DateTime($akhir);
        // $period = new DatePeriod($start, $interval, $end);
        $result = ["cost_center"];

        foreach ($period as $dt) {
            $per = format_month($dt->month_year, 'ye');
            array_push($result, $per);
        }

        return $result;
    }

    public function title(): string
    {
        return 'Kuantiti Rencana Produksi';
    }
}
