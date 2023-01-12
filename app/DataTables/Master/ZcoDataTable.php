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
            ->select('zco.*', 'produk.material_name as product_name', 'material.material_name', 'material.material_uom')
            ->leftJoin('material as produk', 'produk.material_code', '=', 'zco.product_code')
            ->leftJoin('material as material', 'material.material_code', '=', 'zco.material_code')
            ->leftjoin('plant', 'plant.plant_code', '=', 'zco.plant_code')
            ->where('zco.company_code', $cc)
            ->whereNull('zco.deleted_at');

        return datatables()
            ->query($query)
            ->addIndexColumn()
            ->addColumn('product', function ($query) {
                return $query->product_code . ' - ' . $query->product_name;
            })
            ->addColumn('material', function ($query) {
                return $query->material_code . ' - ' . $query->material_name;
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
