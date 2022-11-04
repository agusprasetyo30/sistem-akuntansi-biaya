<?php

namespace App\DataTables\Master;

use App\Models\Asumsi_Umum;
use App\Models\Master\AsumsiUmum;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AsumsiUmumDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $query = Asumsi_Umum::select('asumsi_umum.*', 'periode.periode_name', 'periode.awal_periode', 'periode.akhir_periode')
            ->leftjoin('periode','periode.id', '=', 'asumsi_umum.periode_id')
            ->where('asumsi_umum.deleted_at', '=', null);
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('start_date', function ($query){
                $data = "<p>".Carbon::parse($query->awal_periode)->format('D, d-m-Y')."</p>";

                return $data;
            })

            ->addColumn('end_date', function ($query){
                $data = "<p>".Carbon::parse($query->akhir_periode)->format('D, d-m-Y')."</p>";

                return $data;
            })
            ->addColumn('action', 'pages.buku_besar.asumsi_umum.action')
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('master\asumsiumum-table')
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
        return 'Master\AsumsiUmum_' . date('YmdHis');
    }
}
