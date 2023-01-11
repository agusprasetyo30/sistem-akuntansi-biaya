<?php

namespace App\DataTables\Master;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\DB;

class GLAccountFixedCostDataTable extends DataTable
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

        $query = DB::table('gl_account_fc')
            ->select('gl_account_fc.*', 'group_account_fc.group_account_fc', 'group_account_fc.group_account_fc_desc')
            ->leftJoin('group_account_fc', 'group_account_fc.group_account_fc', '=', 'gl_account_fc.group_account_fc')
            ->where('gl_account_fc.company_code', $cc)
            ->whereNull('gl_account_fc.deleted_at');

        return datatables()
            ->query($query)
            ->addIndexColumn()
            ->editColumn('group_account_fc', function ($query) {
                return $query->group_account_fc . ' ' . $query->group_account_fc_desc;
            })
            ->orderColumn('filter_group_account_fc', function ($query, $order) {
                $query->orderBy('group_account_fc.group_account_fc', $order);
            })
            ->filterColumn('filter_group_account_fc', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('group_account_fc.group_account_fc', 'ilike', '%' . $keyword . '%');
                }
            })
            ->addColumn('action', 'pages.master.gl_account_fc.action')
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->addTableClass('table table-bordered text-nowrap key-buttons')
            ->setTableId('dt_gl_account_fc')
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
        return 'Master\GLAccountFC_' . date('YmdHis');
    }
}
