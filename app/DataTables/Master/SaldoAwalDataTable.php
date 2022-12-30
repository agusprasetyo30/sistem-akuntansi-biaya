<?php

namespace App\DataTables\Master;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SaldoAwalDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $query = DB::table('saldo_awal')->select('saldo_awal.*', 'material.material_code', 'material.material_name', 'plant.plant_code', 'plant.plant_desc', 'version_asumsi.version')
            ->leftjoin('material', 'material.material_code', '=', 'saldo_awal.material_code')
            ->leftjoin('plant', 'plant.plant_code', '=', 'saldo_awal.plant_code')
            ->leftjoin('version_asumsi', 'version_asumsi.id', '=', 'saldo_awal.version_id')
            ->whereNull('saldo_awal.deleted_at');

        return datatables()
            ->query($query)
            ->addIndexColumn()
            ->editColumn('month_year', function ($query) {
                return helpDate($query->month_year, 'bi');
            })
            ->editColumn('material_name', function ($query) {
                return $query->material_code . ' ' . $query->material_name;
            })
            ->editColumn('total_value', function ($query) {
                return rupiah($query->total_value);
            })
            ->editColumn('nilai_satuan', function ($query) {
                return rupiah($query->nilai_satuan);
            })
            ->orderColumn('filter_material', function ($query, $order) {
                $query->orderBy('material.material_code', $order);
            })
            ->orderColumn('filter_plant', function ($query, $order) {
                $query->orderBy('plant.plant_code', $order);
            })
            ->filterColumn('filter_material', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('material.material_code', 'ilike', '%' . $keyword . '%')
                        ->orWhere('material.material_name', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_plant', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('plant.plant_code', 'ilike', '%' . $keyword . '%');
                }
            })
            ->addColumn('action', 'pages.buku_besar.saldo_awal.action')
            ->escapeColumns([]);
    }

    // public function html()
    // {
    //     return $this->builder()
    //         ->addTableClass('table table-bordered text-nowrap key-buttons')
    //         ->setTableId('dt_saldo_awal')
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
        return 'Master\SaldoAwal_' . date('YmdHis');
    }
}
