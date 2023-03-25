<?php

namespace App\Exports\Horizontal;

use App\Models\Zco;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class ZCOGroupAccountExport implements FromView, ShouldAutoSize
{
    protected $data;

    function __construct($data) 
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('pages.buku_besar.zco.export_group_account', $this->data);
    }

    public static function afterSheet(AfterSheet $event)
    {
        $columns = ['A', 'B', 'C'];

        foreach ($columns as $column) {
            $event->sheet->getDelegate()->getColumnDimension($column)->setWidth(9999);
        }
    }
}