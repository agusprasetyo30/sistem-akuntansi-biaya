<?php

namespace App\DataTables\Master;

use App\Models\Master\KelengkapanBiayaTetap;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class KelengkapanBiayaTetapDataTable extends DataTable
{

    public function dataTable($query)
    {
        $qty_renprod = DB::table('qty_renprod')
            ->select('cost_center')
            ->whereIn('asumsi_umum_id', $this->asumsi)
            ->where('company_code', $this->company)
            ->get()->pluck('cost_center')->all();

        $query = DB::table('salrs')
            ->select('salrs.cost_center', 'cost_center.cost_center_desc', 'salrs.company_code')
            ->leftJoin('cost_center', 'cost_center.cost_center', '=', 'salrs.cost_center')
            ->whereNotIn('salrs.cost_center', $qty_renprod)
            ->where('salrs.company_code', $this->company)
            ->where('salrs.periode', 'ilike', '%'.$this->date.'%');

        return datatables()
            ->query($query)
            ->addColumn('company', function ($query){
                return $query->company_code;
            })
            ->addColumn('cost_center_data', function ($query){
                return $query->cost_center.' - '.$query->cost_center_desc;
            })
            ->filterColumn('filter_company', function ($query, $keyword){
                $query->where('salrs.company_code', 'ilike', '%' . $keyword . '%');
            })
            ->filterColumn('filter_cost_center_data', function ($query, $keyword){
                $query->where('salrs.cost_center', 'ilike', '%' . $keyword . '%')
                    ->orwhere('cost_center.cost_center_desc', 'ilike', '%' . $keyword . '%');
            })
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('dt_kelengkapan_biaya_tetap')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1)
            ->buttons(
                Button::make('create'),
                Button::make('export'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
            Column::make('id'),
            Column::make('add your columns'),
            Column::make('created_at'),
            Column::make('updated_at'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Master\KelengkapanBiayaTetap_' . date('YmdHis');
    }
}
