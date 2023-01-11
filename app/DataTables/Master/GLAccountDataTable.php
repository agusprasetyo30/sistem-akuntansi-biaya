<?php

namespace App\DataTables\Master;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\DB;

class GLAccountDataTable extends DataTable
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

        $query = DB::table('gl_account')
            ->select('gl_account.*', 'group_account.group_account_code', 'group_account.group_account_desc')
            ->leftJoin('group_account', 'group_account.group_account_code', '=', 'gl_account.group_account_code')
            ->where('gl_account.company_code', $cc)
            ->whereNull('gl_account.deleted_at');

        return datatables()
            ->query($query)
            ->addIndexColumn()
            ->editColumn('group_account_code', function ($query) {
                return $query->group_account_code . ' ' . $query->group_account_desc;
            })
            ->orderColumn('filter_group_account', function ($query, $order) {
                $query->orderBy('group_account.group_account_code', $order);
            })
            ->filterColumn('filter_group_account', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('group_account.group_account_code', 'ilike', '%' . $keyword . '%');
                }
            })
            ->addColumn('action', 'pages.master.gl_account.action')
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->addTableClass('table table-bordered text-nowrap key-buttons')
            ->setTableId('dt_gl_account')
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
        return 'Master\GLAccount_' . date('YmdHis');
    }
}
