<?php

namespace App\DataTables\Master;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\DB;

class GeneralLedgerAccountDataTable extends DataTable
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

        $query = DB::table('general_ledger_account')
            ->select('general_ledger_account.*', 'group_account_fc.group_account_fc', 'group_account_fc.group_account_fc_desc')
            ->leftJoin('group_account_fc', 'group_account_fc.group_account_fc', '=', 'general_ledger_account.group_account_fc')
            ->where('general_ledger_account.company_code', $cc)
            ->whereNull('general_ledger_account.deleted_at');

        return datatables()
            ->query($query)
            ->addIndexColumn()
            ->orderColumn('filter_group_account_fc', function ($query, $order) {
                $query->orderBy('group_account_fc.group_account_fc', $order);
            })
            ->filterColumn('filter_group_account_fc', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('group_account_fc.group_account_fc', 'ilike', '%' . $keyword . '%')
                        ->orWhere('group_account_fc.group_account_fc_desc', 'ilike', '%' . $keyword . '%');
                }
            })
            ->addColumn('action', 'pages.master.general_ledger_account.action')
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->addTableClass('table table-bordered text-nowrap key-buttons')
            ->setTableId('dt_general_ledger_account')
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
        return 'Master\GeneralLedgerAccount_' . date('YmdHis');
    }
}
