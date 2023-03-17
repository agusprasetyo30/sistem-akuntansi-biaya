<?php

namespace App\DataTables\Master;

use App\Models\Management_Role;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ManagementUserAksesDataTable extends DataTable
{
    public function dataTable($query)
    {
        $query = DB::table('management_role')
            ->select('management_role.*', 'role.nama_role', 'feature.feature_name', 'feature.kode_unik')
            ->leftjoin('role', 'role.id', '=', 'management_role.role_id')
            ->leftjoin('feature', 'feature.db', '=', 'management_role.db');

        return datatables()
            ->query($query)
            ->addIndexColumn()
            ->addColumn('create', function ($query) {
                if ($query->create == true) {
                    $span = "<span class='badge bg-success-light border-success fs-11 mt-2'>Iya</span>";
                } else {
                    $span = "<span class='badge bg-danger-light border-danger mt-2'>Tidak</span>";
                }
                return $span;
            })
            ->addColumn('read', function ($query) {
                if ($query->read == true) {
                    $span = "<span class='badge bg-success-light border-success fs-11 mt-2'>Iya</span>";
                } else {
                    $span = "<span class='badge bg-danger-light border-danger mt-2'>Tidak</span>";
                }
                return $span;
            })
            ->addColumn('update', function ($query) {
                if ($query->update == true) {
                    $span = "<span class='badge bg-success-light border-success fs-11 mt-2'>Iya</span>";
                } else {
                    $span = "<span class='badge bg-danger-light border-danger mt-2'>Tidak</span>";
                }
                return $span;
            })
            ->addColumn('delete', function ($query) {
                if ($query->delete == true) {
                    $span = "<span class='badge bg-success-light border-success fs-11 mt-2'>Iya</span>";
                } else {
                    $span = "<span class='badge bg-danger-light border-danger mt-2'>Tidak</span>";
                }
                return $span;
            })
            ->addColumn('approve', function ($query) {
                if ($query->approve == true) {
                    $span = "<span class='badge bg-success-light border-success fs-11 mt-2'>Iya</span>";
                } else {
                    $span = "<span class='badge bg-danger-light border-danger mt-2'>Tidak</span>";
                }
                return $span;
            })
            ->addColumn('submit', function ($query) {
                if ($query->submit == true) {
                    $span = "<span class='badge bg-success-light border-success fs-11 mt-2'>Iya</span>";
                } else {
                    $span = "<span class='badge bg-danger-light border-danger mt-2'>Tidak</span>";
                }
                return $span;
            })
            ->filterColumn('filter_create', function ($query, $keyword) {
                if ($keyword != 'all') {
                    if ($keyword == true) {
                        $query->where('create', true);
                    } elseif ($keyword == false) {
                        $query->where('create', false);
                    }
                }
            })
            ->filterColumn('filter_read', function ($query, $keyword) {
                if ($keyword != 'all') {
                    if ($keyword == true) {
                        $query->where('read', true);
                    } elseif ($keyword == false) {
                        $query->where('read', false);
                    }
                }
            })
            ->filterColumn('filter_update', function ($query, $keyword) {
                if ($keyword != 'all') {
                    if ($keyword == true) {
                        $query->where('update', true);
                    } elseif ($keyword == false) {
                        $query->where('update', false);
                    }
                }
            })
            ->filterColumn('filter_delete', function ($query, $keyword) {
                if ($keyword != 'all') {
                    if ($keyword == true) {
                        $query->where('delete', true);
                    } elseif ($keyword == false) {
                        $query->where('delete', false);
                    }
                }
            })
            ->filterColumn('filter_approve', function ($query, $keyword) {
                if ($keyword != 'all') {
                    if ($keyword == true) {
                        $query->where('approve', true);
                    } elseif ($keyword == false) {
                        $query->where('approve', false);
                    }
                }
            })
            ->filterColumn('filter_submit', function ($query, $keyword) {
                if ($keyword != 'all') {
                    if ($keyword == true) {
                        $query->where('submit', true);
                    } elseif ($keyword == false) {
                        $query->where('submit', false);
                    }
                }
            })
            ->orderColumn('filter_create', function ($query, $order) {
                $query->orderBy('create', $order);
            })
            ->orderColumn('filter_read', function ($query, $order) {
                $query->orderBy('read', $order);
            })
            ->orderColumn('filter_update', function ($query, $order) {
                $query->orderBy('update', $order);
            })
            ->orderColumn('filter_delete', function ($query, $order) {
                $query->orderBy('delete', $order);
            })
            ->orderColumn('filter_approve', function ($query, $order) {
                $query->orderBy('approve', $order);
            })
            ->orderColumn('filter_submit', function ($query, $order) {
                $query->orderBy('submit', $order);
            })
            ->addColumn('action', 'pages.master.management_user_akses.action')
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
