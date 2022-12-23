<?php

namespace App\Exports\Template;

use App\Models\Asumsi_Umum;
use App\Models\QtyRenDaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class T_KuantitiRenDaanExport implements WithHeadings
{
    protected $version;

    function __construct($version)
    {
        $this->version = $version;
    }


    public function headings(): array
    {
        try {
            $template = ['material_code', 'region_id'];
            $data = Asumsi_Umum::where('version_id', $this->version)
                ->get();

            foreach ($data as $items) {
                $temp = format_month($items->month_year, 'fe') . '|' . $items->id;
                array_push($template, $temp);
            }

            return $template;
        } catch (\Exception $exception) {
            return $template;
        }
    }
}
