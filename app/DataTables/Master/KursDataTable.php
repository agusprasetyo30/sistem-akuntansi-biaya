<?php

namespace App\DataTables\Master;

use App\Models\Kurs;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class KursDataTable extends DataTable
{

    public function dataTable($query)
    {
        $query = Kurs::query();
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('mata_uang', function ($query){
                return rupiah($query->usd_rate);
            })
            ->addColumn('periode', function ($query){
                return format_month($query->month_year, 'bi');
            })
            ->filterColumn('filter_periode', function ($query, $keyword){
                $temp = explode('/', $keyword);
                if (count($temp) == 1){
                    $query->Where('month_year', 'ilike', '%'.$keyword.'%');
                }elseif (count($temp) == 2){
                    $keyword = $temp[1].'-'.$temp[0];
                    $query->Where('month_year', 'ilike', '%'.$keyword.'%');
                }
            })
            ->filterColumn('filter_mata_uang', function ($query, $keyword){
                $keyword = str_replace('.', '', str_replace('Rp ', '', $keyword));
                $query->where('usd_rate', 'ilike', '%'.$keyword.'%');
            })
            ->orderColumn('filter_mata_uang', function ($query, $order){
                $query->orderBy('usd_rate', $order);
            })
            ->orderColumn('filter_periode', function ($query, $order){
                $query->orderBy('month_year', $order);
            })
            ->addColumn('action', 'pages.master.kurs.action')
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('dt_kurs')
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
        return 'Master/Kurs_' . date('YmdHis');
    }
}
