<?php

namespace App\DataTables\Master;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ZcoDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $cc = auth()->user()->company_code;

        $query = DB::table('zco')
            ->select('zco.*', 'produk.material_name as product_name', 'material.material_name', 'material.material_uom', 'gl_account.gl_account_desc', 'plant.plant_desc', 'version_asumsi.version')
            ->leftJoin('material as produk', 'produk.material_code', '=', 'zco.product_code')
            ->leftJoin('material as material', 'material.material_code', '=', 'zco.material_code')
            ->leftjoin('plant', 'plant.plant_code', '=', 'zco.plant_code')
            ->leftjoin('gl_account', 'gl_account.gl_account', '=', 'zco.cost_element')
            ->leftjoin('version_asumsi', 'version_asumsi.id', '=', 'zco.version_id')
            ->where('zco.company_code', $cc)
            ->whereNull('zco.deleted_at');

        return datatables()
            ->query($query)
            ->addIndexColumn()
            ->addColumn('version', function ($query) {
                return $query->version;
            })
            ->addColumn('product', function ($query) {
                return $query->product_code . ' - ' . $query->product_name;
            })
            ->addColumn('material', function ($query) {
                return $query->material_code . ' - ' . $query->material_name;
            })
            ->addColumn('plant_code', function ($query) {
                return $query->plant_code . ' ' . $query->plant_desc;
            })
            ->addColumn('periode', function ($query) {
                return format_month($query->periode, 'bi');
            })
            ->addColumn('cost_element', function ($query) {
                return $query->cost_element . ' ' . $query->gl_account_desc;
            })
            ->addColumn('product_qty', function ($query) {
                return helpRibuanKoma($query->product_qty);
            })
            ->addColumn('total_qty', function ($query) {
                return helpRibuanKoma($query->total_qty);
            })
            ->addColumn('total_amount', function ($query) {
                return helpRibuan($query->total_amount);
            })
            ->addColumn('unit_price_product', function ($query) {
                return rupiah($query->unit_price_product);
            })
            ->orderColumn('filter_plant', function ($query, $order) {
                $query->orderBy('plant.plant_code', $order);
            })
            ->orderColumn('filter_product', function ($query, $order) {
                $query->orderBy('material.material_code', $order);
            })
            ->orderColumn('filter_material', function ($query, $order) {
                $query->orderBy('material.material_code', $order);
            })
            ->orderColumn('filter_cost_element', function ($query, $order) {
                $query->orderBy('gl_account.gl_account', $order);
            })
            ->orderColumn('filter_version', function ($query, $order) {
                $query->orderBy('version_asumsi.version', $order);
            })
            ->filterColumn('filter_plant', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('zco.plant_code', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_product', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('zco.product_code', 'ilike', '%' . $keyword . '%')
                        ->orWhere('produk.material_name', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_material', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('zco.material_code', 'ilike', '%' . $keyword . '%')
                        ->orWhere('material.material_name', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_cost_element', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('zco.cost_element', 'ilike', '%' . $keyword . '%')
                        ->orWhere('gl_account.gl_account', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_version', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('version_asumsi.id', 'ilike', '%' . $keyword . '%');
                }
            })
            ->addColumn('action', 'pages.buku_besar.zco.action')
            ->escapeColumns([]);
    }

    // public function html()
    // {
    //     return $this->builder()
    //         ->addTableClass('table table-bordered text-nowrap key-buttons')
    //         ->setTableId('dt_qty_renprod')
    //         ->columns($this->getColumns())
    //         ->minifiedAjax()
    //         ->dom('Bfrtip')
    //         ->orderBy(1)
    //         ->buttons(
    //             Button::make('create'),
    //             Button::make('export'),
    //             Button::make('print'),
    //             Button::make('reset'),
    //             Button::make('reload')
    //         );
    // }

    /**
     * Get columns.
     *
     * @return array
     */
    // protected function getColumns()
    // {
    //     return [
    //         Column::computed('action')
    //             ->exportable(false)
    //             ->printable(false)
    //             ->width(60)
    //             ->addClass('text-center'),
    //         Column::make('id'),
    //         Column::make('add your columns'),
    //         Column::make('created_at'),
    //         Column::make('updated_at'),
    //     ];
    // }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'BukuBesar\ZCO_' . date('YmdHis');
    }
}
