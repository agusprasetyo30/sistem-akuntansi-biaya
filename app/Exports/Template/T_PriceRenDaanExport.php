<?php

namespace App\Exports\Template;

use App\Models\Asumsi_Umum;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class T_PriceRenDaanExport implements WithHeadings, WithTitle
{
    protected $version;

    function __construct($version)
    {
        $this->version = $version;
    }

    public function title(): string
    {
        return 'Template Kuantiti Rencana Pengadaan';
    }

    public function headings(): array
    {
        try {
            $template = ['material_code', 'region_id'];
            $data = Asumsi_Umum::where('version_id', $this->version)
                ->get();

            foreach ($data as $items) {
                $temp = format_month($items->month_year, 'ye');
                array_push($template, $temp);
            }
            return $template;
        } catch (\Exception $exception) {
            return $template;
        }
    }
}
