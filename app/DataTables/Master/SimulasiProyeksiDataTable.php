<?php

namespace App\DataTables\Master;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use function PHPUnit\Framework\isEmpty;

class SimulasiProyeksiDataTable extends DataTable
{
    public function dataTable($query)
    {
        //UNION

        // $cr = DB::table("cons_rate")
        //     ->select(
        //         "cons_rate.product_code as code",
        //         "material.material_name as name",
        //         DB::raw('(CASE WHEN material.group_account_code IS NOT NULL THEN 1 ELSE 0 END) AS keterangan')
        //     )
        //     ->leftJoin('material', 'material.material_code', '=', 'cons_rate.material_code');

        // $query = DB::table("salrs")
        //     ->select(
        //         "group_account_fc.group_account_fc as code",
        //         "group_account_fc.group_account_fc_desc as name",
        //         DB::raw('(CASE WHEN group_account_fc.group_account_fc IS NOT NULL THEN 2 ELSE 1 END) AS keterangan')
        //     )
        //     ->leftjoin('gl_account_fc', 'gl_account_fc.gl_account_fc', '=', 'salrs.gl_account_fc')
        //     ->leftjoin('group_account_fc', 'group_account_fc.group_account_fc', '=', 'gl_account_fc.group_account_fc')
        //     ->union($cr)
        //     ->orderBy('keterangan', 'asc')
        //     ->orderBy('code', 'asc');

        $query = DB::table("temp_proyeksi")
            ->select(
                "temp_proyeksi.proyeksi_no as code",
                "temp_proyeksi.proyeksi_name as name",
            );

        $datatable = datatables()
            ->query($query)
            ->addColumn('code', function ($query) {
                return $query->code;
            })
            ->addColumn('name', function ($query) {
                return $query->name;
            });

        return $datatable;
    }


    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->addTableClass('table table-bordered text-nowrap key-buttons')
            ->setTableId('h_dt_simulasi_proyeksi')
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
        return 'Master\H_SimulasiProyeksi_' . date('YmdHis');
    }
}