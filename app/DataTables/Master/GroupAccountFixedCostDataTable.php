<?php

namespace App\DataTables\Master;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\DB;

class GroupAccountFixedCostDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $query = DB::table('group_account_fc')->whereNull('deleted_at');

        if ($this->filter_company != 'all' && auth()->user()->mapping_akses('group_account_fc')->company_code == 'all') {
            $query = $query->where('group_account_fc.company_code', $this->filter_company);
        } else if ($this->filter_company != 'all' && auth()->user()->mapping_akses('group_account_fc')->company_code != 'all') {
            $query = $query->where('group_account_fc.company_code', auth()->user()->mapping_akses('group_account_fc')->company_code);
        }

        return datatables()
            ->query($query)
            ->addIndexColumn()
            ->addColumn('action', 'pages.master.group_account_fc.action')
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->addTableClass('table table-bordered text-nowrap key-buttons')
            ->setTableId('dt_group_account_fc')
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
        return 'Master\GroupAccountFixedCost_' . date('YmdHis');
    }
}
