<?php

namespace App\DataTables\Master;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class QtyRenDaanDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $query = DB::table('qty_rendaan')
            ->select('qty_rendaan.*', 'material.material_name', 'asumsi_umum.month_year', 'version_asumsi.version', 'regions.region_name')
            ->leftjoin('material', 'material.material_code', '=', 'qty_rendaan.material_code')
            ->leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'qty_rendaan.asumsi_umum_id')
            ->leftjoin('version_asumsi', 'version_asumsi.id', '=', 'qty_rendaan.version_id')
            ->leftjoin('regions', 'regions.id', '=', 'qty_rendaan.region_id')
            ->whereNull('qty_rendaan.deleted_at');

        return datatables()
            ->query($query)
            ->addIndexColumn()
            ->addColumn('version_periode', function ($query){
                return $query->version.' - '.format_month($query->month_year,'bi');
            })
            ->filterColumn('filter_version_periode', function ($query, $keyword){
                $query->where('version_asumsi.version', 'ilike', '%'.$keyword.'%')
                    ->orWhere('asumsi_umum.month_year', 'ilike', '%'.$keyword.'%');
            })
            ->addColumn('material', function ($query){
                return $query->material_code.' - '.$query->material_name;
            })
            ->filterColumn('filter_material', function ($query, $keyword){
                $query->where('qty_rendaan.material_code', 'ilike', '%'.$keyword.'%')
                    ->orWhere('material.material_name', 'ilike', '%'.$keyword.'%');
            })
            ->filterColumn('filter_region',function ($query, $keyword){
                $query->where('regions.region_name', 'ilike', '%'.$keyword.'%');
            })
            ->addColumn('action', 'pages.buku_besar.qty_rendaan.action')
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->addTableClass('table table-bordered text-nowrap key-buttons')
            ->setTableId('dt_qty_rendaan')
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
        return 'Master\QtyRenDaan_' . date('YmdHis');
    }
}
