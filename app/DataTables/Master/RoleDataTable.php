<?php

namespace App\DataTables\Master;

use App\Models\Role;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RoleDataTable extends DataTable
{
    public function dataTable($query)
    {
        $query = Role::query();
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            // ->addColumn('status', function ($query){
            //     if ($query->is_active == true){
            //         $span = "<span class='badge bg-success-light border-success fs-11 mt-2'>Aktif</span>";
            //     }else{
            //         $span = "<span class='badge bg-danger-light border-danger mt-2'>Tidak Aktif</span>";
            //     }
            //     return $span;
            // })
            // ->filterColumn('filter_status', function ($query, $keyword) {
            //     if ($keyword != 'all'){
            //         if ($keyword == true) {
            //             $query->where('is_active', true);
            //         } elseif ($keyword == false) {
            //             $query->where('is_active', false);
            //         }
            //     }
            // })
            ->addColumn('action', 'pages.master.role.action')
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
