<?php

namespace App\DataTables\Master;

use App\Models\Material;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MaterialDataTable extends DataTable
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

        $query = DB::table('material')
            ->select('material.*', 'kategori_produk.id', 'kategori_produk.kategori_produk_name', 'kategori_material.id', 'kategori_material.kategori_material_name', 'group_account.group_account_code', 'group_account.group_account_desc')
            ->leftJoin('kategori_material', 'kategori_material.id', '=', 'material.kategori_material_id')
            ->leftJoin('kategori_produk', 'kategori_produk.id', '=', 'material.kategori_produk_id')
            ->leftJoin('group_account', 'group_account.group_account_code', '=', 'material.group_account_code')
            ->where('material.company_code', $cc)
            ->whereNull('material.deleted_at');

        return datatables()
            ->query($query)
            ->addIndexColumn()
            ->addColumn('status', function ($query) {
                if ($query->is_active == true) {
                    $span = "<span class='badge bg-success-light border-success fs-11 mt-2'>Aktif</span>";
                } else {
                    $span = "<span class='badge bg-danger-light border-danger mt-2'>Tidak Aktif</span>";
                }

                return $span;
            })
            ->addColumn('dummy', function ($query) {
                if ($query->is_dummy == true) {
                    $span = "<span class='badge bg-success-light border-success fs-11 mt-2'>Iya</span>";
                } else {
                    $span = "<span class='badge bg-danger-light border-danger mt-2'>Tidak</span>";
                }

                return $span;
            })
            ->editColumn('group_account_desc', function ($query) {
                return $query->group_account_code . ' ' . $query->group_account_desc;
            })
            ->orderColumn('filter_kategori_material', function ($query, $order) {
                $query->orderBy('kategori_material.kategori_material_name', $order);
            })
            ->orderColumn('filter_kategori_produk', function ($query, $order) {
                $query->orderBy('kategori_material.kategori_produk_name', $order);
            })
            ->orderColumn('filter_group_account', function ($query, $order) {
                $query->orderBy('group_account.group_account_code', $order);
            })
            ->filterColumn('filter_status', function ($query, $keyword) {
                if ($keyword != 'all') {
                    if ($keyword == true) {
                        $query->where('material.is_active', true);
                    } elseif ($keyword == false) {
                        $query->where('material.is_active', false);
                    }
                }
            })
            ->filterColumn('filter_dummy', function ($query, $keyword) {
                if ($keyword != 'all') {
                    if ($keyword == true) {
                        $query->where('is_dummy', true);
                    } elseif ($keyword == false) {
                        $query->where('is_dummy', false);
                    }
                }
            })
            ->filterColumn('filter_kategori_material', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('kategori_material.kategori_material_name', 'ilike', '%' . $keyword . '%')
                        ->orWhere('kategori_material.id', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_kategori_produk', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('kategori_produk.id', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_group_account', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('group_account.group_account_code', 'ilike', '%' . $keyword . '%')
                        ->orWhere('group_account.group_account_desc', 'ilike', '%' . $keyword . '%');
                }
            })
            ->addColumn('action', 'pages.master.material.action')
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->addTableClass('table table-bordered text-nowrap key-buttons')
            ->setTableId('dt_material')
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
        return 'Master\Material_' . date('YmdHis');
    }
}
