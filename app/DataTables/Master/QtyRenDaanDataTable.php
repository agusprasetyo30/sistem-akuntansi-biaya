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
            ->addColumn('version', function ($query){
                return $query->version;
            })
            ->addColumn('periode', function ($query){
                return format_month($query->month_year,'bi');
            })
            ->addColumn('value', function ($query){
                return rupiah($query->qty_rendaan_value);
            })
            ->addColumn('material', function ($query){
                return $query->material_code.' - '.$query->material_name;
            })
            ->filterColumn('filter_version', function ($query, $keyword){
                if ($keyword != 'all'){
                    $query->where('version_asumsi.id', 'ilike', '%'.$keyword.'%');
                }
            })
            ->filterColumn('filter_periode', function ($query, $keyword){
                $temp = explode('/', $keyword);
                if (count($temp) == 1){
                    $query->Where('asumsi_umum.month_year', 'ilike', '%'.$keyword.'%');
                }elseif (count($temp) == 2){
                    $keyword = $temp[1].'-'.$temp[0];
                    $query->Where('asumsi_umum.month_year', 'ilike', '%'.$keyword.'%');
                }
            })
            ->filterColumn('filter_material', function ($query, $keyword){
                if ($keyword != 'all'){
                    $query->where('qty_rendaan.material_code', 'ilike', '%'.$keyword.'%')
                        ->orWhere('material.material_name', 'ilike', '%'.$keyword.'%');
                }
            })
            ->filterColumn('filter_region',function ($query, $keyword){
                $query->where('regions.region_name', 'ilike', '%'.$keyword.'%');
            })
            ->filterColumn('filter_qty_rendaan_value',function ($query, $keyword){
                $keyword = str_replace('.', '', str_replace('Rp ', '', $keyword));
                $query->where('qty_rendaan.qty_rendaan_value', 'ilike', '%'.$keyword.'%');
            })
            ->orderColumn('filter_version', function ($query, $order) {
                $query->orderBy('version_asumsi.version', $order);
            })
            ->orderColumn('filter_periode', function ($query, $order) {
                $query->orderBy('asumsi_umum.month_year', $order);
            })
            ->orderColumn('filter_material', function ($query, $order) {
                $query->orderBy('qty_rendaan.material_code', $order);
            })
            ->orderColumn('filter_region', function ($query, $order) {
                $query->orderBy('regions.region_name', $order);
            })
            ->orderColumn('filter_qty_rendaan_value', function ($query, $order) {
                $query->orderBy('qty_rendaan.qty_rendaan_value', $order);
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
            ->buttons(
                Button::make('create'),
                Button::make('export'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            );
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
