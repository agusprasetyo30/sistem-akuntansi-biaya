<?php

namespace App\DataTables\Master;

use App\Models\Asumsi_Umum;
use App\Models\Master\AsumsiUmum;
use App\Models\Version_Asumsi;
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
        $query = Version_Asumsi::select('version_asumsi.*');
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('c_version', function ($query){
                return $query->version;
            })
            ->addColumn('c_data_bulan', function ($query){
                $data = "<p>".$query->data_bulan." Bulan</p>";

                return $data;
            })
            ->addColumn('c_saldo_awal', function ($query){
                return format_month($query->saldo_awal);
            })
            ->addColumn('c_awal_periode', function ($query){
                return format_month($query->awal_periode);
            })
            ->addColumn('c_akhir_periode', function ($query){
                return format_month($query->akhir_periode);
            })
            ->addColumn('action', 'pages.master.asumsi_umum.action')
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
