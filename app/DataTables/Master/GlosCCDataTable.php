<?php

namespace App\DataTables\Master;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class GlosCCDataTable extends DataTable
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

        $query = DB::table('glos_cc')
            ->select('glos_cc.*', 'material.material_name', 'plant.plant_desc', 'cost_center.cost_center_desc')
            ->leftjoin('plant', 'plant.plant_code', '=', 'glos_cc.plant_code')
            ->leftjoin('cost_center', 'cost_center.cost_center', '=', 'glos_cc.cost_center')
            ->leftjoin('material', 'material.material_code', '=', 'glos_cc.material_code')
            ->where('glos_cc.company_code', $cc)
            ->whereNull('glos_cc.deleted_at');

        return datatables()
            ->query($query)
            ->addIndexColumn()
            ->editColumn('plant', function ($query) {
                return $query->plant_code . ' ' . $query->plant_desc;
            })
            ->editColumn('cost_center', function ($query) {
                return $query->cost_center . ' ' . $query->cost_center_desc;
            })
            ->editColumn('material', function ($query) {
                return $query->material_code . ' ' . $query->material_name;
            })
            ->orderColumn('filter_plant', function ($query, $order) {
                $query->orderBy('plant.plant_code', $order);
            })
            ->orderColumn('filter_cost_center', function ($query, $order) {
                $query->orderBy('cost_center.cost_center', $order);
            })
            ->orderColumn('filter_material', function ($query, $order) {
                $query->orderBy('material.material_code', $order);
            })
            ->filterColumn('filter_plant', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('plant.plant_code', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_cost_center', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('cost_center.cost_center', 'ilike', '%' . $keyword . '%')
                        ->orWhere('cost_center.cost_center_desc', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_material', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('material.material_code', 'ilike', '%' . $keyword . '%')
                        ->orWhere('material.material_name', 'ilike', '%' . $keyword . '%');
                }
            })
            ->addColumn('action', 'pages.master.glos_cc.action')
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->addTableClass('table table-bordered text-nowrap key-buttons')
            ->setTableId('dt_glos_cc')
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
        return 'Master\GlosCC_' . date('YmdHis');
    }
}
