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
