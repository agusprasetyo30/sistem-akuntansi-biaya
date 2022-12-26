<?php

namespace App\DataTables\Master;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class QtyRenProdDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $query = DB::table('qty_renprod')->select('qty_renprod.*', 'material.material_name', 'material.material_code', 'version_asumsi.version', 'asumsi_umum.month_year')
            ->leftjoin('material', 'material.material_code', '=', 'qty_renprod.material_code')
            ->leftjoin('version_asumsi', 'version_asumsi.id', '=', 'qty_renprod.version_id')
            ->leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'qty_renprod.asumsi_umum_id')
            ->whereNull('qty_renprod.deleted_at');

        return datatables()
            ->query($query)
            ->addIndexColumn()
            ->editColumn('month_year', function ($query) {
                return helpDate($query->month_year, 'bi');
            })
            ->editColumn('qty_renprod_value', function ($query) {
                return rupiah($query->qty_renprod_value);
            })
            ->editColumn('material_name', function ($query) {
                return $query->material_code . ' ' . $query->material_name;
            })
            ->filterColumn('filter_month_year', function ($query, $keyword){
                $query->where('asumsi_umum.month_year', 'ilike', '%'.$keyword.'%');
            })
            ->addColumn('action', 'pages.buku_besar.qty_renprod.action')
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->addTableClass('table table-bordered text-nowrap key-buttons')
            ->setTableId('dt_qty_renprod')
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
        return 'Master\QtyRenProd_' . date('YmdHis');
    }
}
