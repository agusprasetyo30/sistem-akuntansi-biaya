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

        $asumsi = DB::table('material')
            // ->where('version_id', $this->version)
            ->get();

        $renprodValues = DB::table('zco')
            ->whereIn('product_code', $asumsi->pluck('material_code')->all())
            ->get();

        foreach ($asumsi as $key => $a) {
            $datatable->addColumn($key, function ($query) use ($renprodValues, $a) {
                $renprodAsumsi = $renprodValues
                    ->where('product_code', $a->material_code)
                    ->first();

                return $renprodAsumsi ? $renprodAsumsi->total_qty : '-';
            });
        }

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
        return 'BukuBesar\H_ZCO_' . date('YmdHis');
    }
}
