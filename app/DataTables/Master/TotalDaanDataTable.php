<?php

namespace App\DataTables\Master;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TotalDaanDataTable extends DataTable
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
        ->select('qty_rendaan.*', 'material.material_name', 'asumsi_umum.month_year', 'asumsi_umum.usd_rate', 'asumsi_umum.adjustment', 'version_asumsi.version', 'regions.region_name')
        ->leftjoin('material', 'material.material_code', '=', 'qty_rendaan.material_code')
        ->leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'qty_rendaan.asumsi_umum_id')
        ->leftjoin('version_asumsi', 'version_asumsi.id', '=', 'qty_rendaan.version_id')
        ->leftjoin('regions', 'regions.id', '=', 'qty_rendaan.region_id')
        // ->leftjoin('price_rendaan', 'price_rendaan.material_code', '=', 'qty_rendaan.material_code')
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
            ->addColumn('value', function ($query){
                $result = 0;
                $query2 = DB::table('price_rendaan')
                ->select('price_rendaan.price_rendaan_value')
                ->where('material_code',$query->material_code)
                ->where('region_id',$query->region_id)
                ->where('version_id',$query->version_id)
                ->where('asumsi_umum_id',$query->asumsi_umum_id)
                ->whereNull('price_rendaan.deleted_at')
                ->first();

                // dd($query2->price_rendaan_value, $query->qty_rendaan_value);
                if ($query->qty_rendaan_value > 0 && $query2->price_rendaan_value == 0) {
                    return 0;
                } else {
                    $result = $query->qty_rendaan_value * ($query2->price_rendaan_value * ( 1 + $query->adjustment ) * $query->usd_rate );
                    return $result;
                }
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
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->addTableClass('table table-bordered text-nowrap key-buttons')
            ->setTableId('dt_total_daan')
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
        return 'Master\QtyTotalDaan_' . date('YmdHis');
    }
}
