<?php

namespace App\DataTables\Master;

use App\Models\Management_Role;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ManagementUserRoleDataTable extends DataTable
{
    public function dataTable($query)
    {
        $query = DB::table('mapping_role')
            ->select('mapping_role.*', 'role.nama_role', 'users.name')
            ->leftjoin('role', 'role.id', '=', 'mapping_role.role_id')
            ->leftjoin('users', 'users.id', '=', 'mapping_role.user_id');

        return datatables()
            ->query($query)
            ->addIndexColumn()
            ->addColumn('action', 'pages.master.management_user_role.action')
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('role-table')
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
        return 'Role_' . date('YmdHis');
    }
}
