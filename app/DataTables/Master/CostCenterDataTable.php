<?php

namespace App\DataTables\Master;


use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\Models\CostCenter;

class CostCenterDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $query = CostCenter::select('cost_center.*', 'plant.plant_code', 'plant.id as id_plant')
            ->leftJoin('plant', 'plant.id', '=', 'cost_center.plant_id');

        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('status', function ($query){
                if ($query->is_active == true){
                    $span = "<span class='badge bg-success-light border-success fs-11 mt-2'>Aktif</span>";
                }else{
                    $span = "<span class='badge bg-danger-light border-danger mt-2'>Tidak Aktif</span>";
                }


                return $span;
            })
            ->filterColumn('filter_status', function ($query, $keyword){

                if ($keyword == true){
                    $query->where('cost_center.is_active', true);
                }elseif ($keyword == false){
                    $query->where('cost_center.is_active', false);
                }

            })
            ->addColumn('action', 'pages.buku_besar.cost_center.action')
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->addTableClass('table table-bordered text-nowrap key-buttons')
            ->setTableId('dt_cost_center')
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
        return 'Master\CostCenter_' . date('YmdHis');
    }
}
