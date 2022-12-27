<?php

namespace App\DataTables\Master;

use App\Models\ConsRate;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ConsRateDataTable extends DataTable
{
    public function dataTable($query)
    {
        $query = ConsRate::leftJoin('version_asumsi', 'version_asumsi.id', '=', 'cons_rate.version_id')
            ->leftJoin('material as produk', 'produk.material_code', '=', 'cons_rate.product_code')
            ->leftJoin('material as material', 'material.material_code', '=', 'cons_rate.material_code')
            ->select('cons_rate.*', 'version_asumsi.version', DB::raw('produk.material_name as product_name'), DB::raw('material.material_name'), DB::raw('material.material_uom'))
            ->distinct();
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('version', function ($query){
                return $query->version;
            })
            ->addColumn('periode', function ($query){
                return format_month($query->month_year,'bi');
            })
            ->filterColumn('filter_version', function ($query, $keyword){
                $query->where('version_asumsi.version', 'ilike', '%'.$keyword.'%');
            })
            ->filterColumn('filter_periode', function ($query, $keyword){
                $query->Where('cons_rate.month_year', 'ilike', '%'.$keyword.'%');
            })
            ->addColumn('product', function ($query){
                return $query->product_code.' - '.$query->product_name;
            })
            ->filterColumn('filter_product', function ($query, $keyword){
                $query->where('cons_rate.product_code', 'ilike', '%'.$keyword.'%')
                    ->orWhere('produk.material_name', 'ilike', '%'.$keyword.'%');
            })
            ->addColumn('material', function ($query){
                return $query->material_code.' - '.$query->material_name;
            })
            ->filterColumn('filter_material', function ($query, $keyword){
                $query->where('cons_rate.material_code', 'ilike', '%'.$keyword.'%')
                    ->orWhere('material.material_name', 'ilike', '%'.$keyword.'%');
            })
            ->addColumn('uom', function ($query){
                return $query->material_uom;
            })
            ->filterColumn('filter_uom', function ($query, $keyword){
                $query->where('material.material_uom', 'ilike', '%'.$keyword.'%');
            })
            ->addColumn('status', function ($query) {
                if ($query->is_active == true) {
                    $span = "<span class='badge bg-success-light border-success fs-11 mt-2'>Aktif</span>";
                } else {
                    $span = "<span class='badge bg-danger-light border-danger mt-2'>Tidak Aktif</span>";
                }

                return $span;
            })
            ->filterColumn('filter_status', function ($query, $keyword) {

                if ($keyword == true) {
                    $query->where('cons_rate.is_active', true);
                } elseif ($keyword == false) {
                    $query->where('cons_rate.is_active', false);
                }
            })
            ->addColumn('action', 'pages.buku_besar.consrate.action')
            ->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ConsRate $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ConsRate $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('consrate-table')
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
        return 'ConsRate_' . date('YmdHis');
    }
}
