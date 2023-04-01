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
        $query = Salr::select('salrs.id','salrs.value', 'salrs.gl_account_fc', 'salrs.cost_center', 'salrs.version_id', 'salrs.periode', 'version_asumsi.version', 'cost_center.cost_center_desc','group_account_fc.group_account_fc', 'group_account_fc.group_account_fc_desc', 'gl_account_fc.gl_account_fc_desc')
            ->leftjoin('version_asumsi', 'version_asumsi.id', '=', 'salrs.version_id')
            ->leftjoin('cost_center', 'cost_center.cost_center', '=', 'salrs.cost_center')
            ->leftjoin('gl_account_fc', 'gl_account_fc.gl_account_fc', '=', 'salrs.gl_account_fc')
            ->leftjoin('group_account_fc', 'group_account_fc.group_account_fc', '=', 'gl_account_fc.group_account_fc');

        if ($this->filter_company != 'all' && auth()->user()->mapping_akses('salrs')->company_code == 'all') {
            $query = $query->where('salrs.company_code', $this->filter_company);
        } else if ($this->filter_company != 'all' && auth()->user()->mapping_akses('salrs')->company_code != 'all') {
            $query = $query->where('salrs.company_code', auth()->user()->mapping_akses('salrs')->company_code);
        }

        if ($this->filter_version != 'all') {
            $query = $query->where('salrs.version_id', $this->filter_version);
        }

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
                return format_month($query->periode, 'eng');
            })
            ->addColumn('value', function ($query){
                return rupiah($query->value);
            })
            ->filterColumn('filter_group_account', function ($query, $keyword){
                if ($keyword != 'all'){
                    $query->where('group_account_fc.group_account_fc', 'ilike', '%'.$keyword.'%');
                }
            })
            ->filterColumn('filter_gl_account', function ($query, $keyword){
                if ($keyword != 'all'){
                    $query->where('salrs.gl_account_fc', 'ilike', '%'.$keyword.'%');
                }
            })
            ->filterColumn('filter_cost_center', function ($query, $keyword){
                if ($keyword != 'all'){
                    $query->where('salrs.cost_center', 'ilike', '%'.$keyword.'%');
                }
            })
            ->filterColumn('filter_periode', function ($query, $keyword){
                $temp = check_month_by_name($keyword);
                $query->Where('salrs.periode', 'ilike', '%-'.$temp.'-%');
            })
            ->orderColumn('filter_group_account', function ($query, $order) {
                $query->orderBy('group_account_fc.group_account_fc', $order);
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
