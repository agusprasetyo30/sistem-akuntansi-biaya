<?php

namespace App\DataTables\Master;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class H_ZcoDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        // $cc = auth()->user()->company_code;

        // $query = DB::table('zco')
        //     ->select('zco.*', 'produk.material_name as product_name', 'material.material_name', 'material.material_uom')
        //     ->leftJoin('material as produk', 'produk.material_code', '=', 'zco.product_code')
        //     ->leftJoin('material as material', 'material.material_code', '=', 'zco.material_code')
        //     ->leftjoin('plant', 'plant.plant_code', '=', 'zco.plant_code')
        //     ->where('zco.company_code', $cc)
        //     ->whereNull('zco.deleted_at');

        // return datatables()
        //     ->query($query)
        //     ->addIndexColumn()
        //     ->addColumn('action', 'pages.buku_besar.zco.action')
        //     ->escapeColumns([]);


        $cc = auth()->user()->company_code;

        $query = DB::table('zco')
            ->select('zco.cost_element', 'gl_account.gl_account_desc')
            ->leftjoin('gl_account', 'gl_account.gl_account', '=', 'zco.cost_element')
            ->whereNull('zco.deleted_at')
            ->where('zco.company_code', $cc)
            ->groupBy('zco.cost_element', 'gl_account.gl_account_desc');

        $datatable = datatables()
            ->query($query)
            ->addColumn('cost_element', function ($query) {
                return $query->cost_element . ' ' . $query->gl_account_desc;
            });

        // $asumsi = DB::table('asumsi_umum')
        //     ->where('version_id', $this->version)
        //     ->get();

        // $renprodValues = DB::table('qty_renprod')
        //     ->whereIn('asumsi_umum_id', $asumsi->pluck('id')->all())
        //     ->get();

        // foreach ($asumsi as $key => $a) {
        //     $datatable->addColumn($key, function ($query) use ($renprodValues, $a) {
        //         $renprodAsumsi = $renprodValues
        //             ->where('asumsi_umum_id', $a->id)
        //             ->where('cost_center', $query->cost_center)
        //             ->first();

        //         return $renprodAsumsi ? $renprodAsumsi->qty_renprod_value : '-';
        //     });
        // }

        return $datatable;
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
