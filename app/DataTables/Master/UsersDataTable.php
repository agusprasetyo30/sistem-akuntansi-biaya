<?php

namespace App\DataTables\Master;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    public function dataTable($query)
    {
        $query = User::select('users.name', 'users.username', 'users.company_code', 'company.company_name')
            ->leftjoin('company', 'company.company_code', '=', 'users.company_code');
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('company', function ($query){
                return $query->company_code. ' - '. $query->company_name;
            })
            ->filterColumn('filter_company', function ($query, $keyword){
                $query->where('company.company_code', 'ilike', '%'.$keyword.'%')
                    ->orWhere('company.company_name', 'ilike', '%'.$keyword.'%');
            })
            ->addColumn('action', 'pages.master.user.action')
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('dt_users')
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
        return 'Users_' . date('YmdHis');
    }
}
