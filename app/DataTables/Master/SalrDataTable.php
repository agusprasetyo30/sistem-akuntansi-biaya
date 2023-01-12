<?php

namespace App\DataTables\Master;

use App\Models\Salr;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SalrDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $query = Salr::select('salrs.*', 'cost_center.cost_center_desc', 'group_account_fc.group_account_fc_desc', 'gl_account_fc.gl_account_fc_desc')
            ->leftjoin('cost_center', 'cost_center.cost_center', '=', 'salrs.cost_center')
            ->leftjoin('group_account_fc', 'group_account_fc.group_account_fc', '=', 'salrs.group_account_fc')
            ->leftjoin('gl_account_fc', 'gl_account_fc.gl_account_fc', '=', 'salrs.gl_account_fc');
        return datatables()
            ->eloquent($query)
            ->addColumn('group_account', function ($query){
                return $query->group_account_fc.' - '. $query->group_account_fc_desc;
            })
            ->addColumn('gl_account', function ($query){
                return $query->gl_account_fc.' - '. $query->gl_account_fc_desc;
            })
            ->addColumn('cost_center', function ($query){
                return $query->cost_center.' - '. $query->cost_center_desc;
            })
            ->addColumn('periode', function ($query){
                return format_month($query->periode, 'bi');
            })
            ->addColumn('value', function ($query){
                return rupiah($query->value);
            })
            ->filterColumn('filter_group_account', function ($query, $keyword){
                $query->where('salrs.group_account_fc', 'ilike', '%'.$keyword.'%');
            })
            ->filterColumn('filter_gl_account', function ($query, $keyword){
                $query->where('salrs.gl_account_fc', 'ilike', '%'.$keyword.'%');
            })
            ->filterColumn('filter_cost_center', function ($query, $keyword){
                $query->where('salrs.cost_center', 'ilike', '%'.$keyword.'%');
            })
            ->filterColumn('filter_periode', function ($query, $keyword){
                $temp = explode('/', $keyword);
                if (count($temp) == 1){
                    $query->Where('salrs.periode', 'ilike', '%'.$keyword.'%');
                }elseif (count($temp) == 2){
                    $keyword = $temp[1].'-'.$temp[0];
                    $query->Where('salrs.periode', 'ilike', '%'.$keyword.'%');
                }
            })
            ->orderColumn('filter_group_account', function ($query, $order) {
                $query->orderBy('salrs.group_account_fc', $order);
            })
            ->orderColumn('filter_gl_account', function ($query, $order) {
                $query->orderBy('salrs.gl_account_fc', $order);
            })
            ->orderColumn('filter_cost_center', function ($query, $order) {
                $query->orderBy('salrs.cost_center', $order);
            })
            ->orderColumn('filter_periode', function ($query, $order) {
                $query->orderBy('salrs.periode', $order);
            })
            ->orderColumn('filter_value', function ($query, $order) {
                $query->orderBy('salrs.value', $order);
            })
            ->addColumn('action', 'pages.buku_besar.salr.action')
            ->escapeColumns([]);
    }
    public function html()
    {
        return $this->builder()
            ->setTableId('dt_salr')
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
        return 'Master\Salr_' . date('YmdHis');
    }
}
