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
        $query = CostCenter::where('company_code', auth()->user()->company_code);

        if ($this->filter_company != 'all' && auth()->user()->mapping_akses('cost_center')->company_code == 'all') {
            $query = $query->where('cost_center.company_code', $this->filter_company);
        } else if ($this->filter_company != 'all' && auth()->user()->mapping_akses('cost_center')->company_code != 'all') {
            $query = $query->where('cost_center.company_code', auth()->user()->mapping_akses('cost_center')->company_code);
        }

        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('cost_center', function ($query) {
                return $query->cost_center;
            })
            ->addColumn('cost_center_desc', function ($query) {
                return $query->cost_center_desc;
            })
            ->filterColumn('filter_cost_center', function ($query, $keyword) {
                $query->where('cost_center', 'ilike', '%' . $keyword . '%');
            })
            ->filterColumn('filter_cost_center_desc', function ($query, $keyword) {
                $query->where('cost_center_desc', 'ilike', '%' . $keyword . '%');
            })
            ->orderColumn('filter_cost_center', function ($query, $order) {
                $query->orderBy('cost_center', $order);
            })
            ->orderColumn('filter_cost_center_desc', function ($query, $order) {
                $query->orderBy('cost_center_desc', $order);
            })
            ->addColumn('action', 'pages.master.cost_center.action')
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->addTableClass('table table-bordered text-warp warp key-buttons')
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
