<?php

namespace App\Exports;

use App\Models\QtyRenProd;
use App\Models\Version_Asumsi;
use DateInterval;
use DatePeriod;
use DateTime;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QtyRenProdExport implements WithHeadings
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

        if ($this->version == null) {
            $asum = Version_Asumsi::first();
        } else {
            $asum = Version_Asumsi::where('id', $this->version)->first();
        }

        // $asum = Periode::where('id', $this->version)->first();
        $awal = date('Y-m-d', strtotime($asum->awal_periode));
        $akhir = date('Y-m-d', strtotime($asum->akhir_periode . ' + 1 month'));
        $start = new DateTime($awal);
        $interval = new DateInterval('P1M');
        $end = new DateTime($akhir);
        $period = new DatePeriod($start, $interval, $end);
        $result = ["material_code"];

        foreach ($period as $dt) {
            $per = $dt->format('Y-m');
            array_push($result, $per);
        }

        return $result;
    }
}
