<?php

namespace App\DataTables\Master;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PJPenjualanDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $query = DB::table('pj_penjualan')
            ->select('pj_penjualan.*', 'material.material_name', 'asumsi_umum.month_year', 'version_asumsi.version')
            ->leftjoin('material', 'material.material_code', '=', 'pj_penjualan.material_code')
            ->leftjoin('version_asumsi', 'version_asumsi.id', '=', 'pj_penjualan.version_id')
            ->leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'pj_penjualan.asumsi_umum_id')
            ->whereNull('pj_penjualan.deleted_at');

        if ($this->filter_company != 'all' && auth()->user()->mapping_akses('pj_penjualan')->company_code == 'all') {
            $query = $query->where('pj_penjualan.company_code', $this->filter_company);
        } else if ($this->filter_company != 'all' && auth()->user()->mapping_akses('pj_penjualan')->company_code != 'all') {
            $query = $query->where('pj_penjualan.company_code', auth()->user()->mapping_akses('pj_penjualan')->company_code);
        }

        if ($this->filter_version != 'all') {
            $query = $query->where('pj_penjualan.version_id', $this->filter_version);
        }

        return datatables()
            ->query($query)
            ->addIndexColumn()
            ->editColumn('month_year', function ($query) {
                return format_month($query->month_year, 'bi');
            })
            ->editColumn('pj_penjualan_value', function ($query) {
                return helpRibuan($query->pj_penjualan_value);
            })
            ->editColumn('material', function ($query) {
                return $query->material_code . ' ' . $query->material_name;
            })
            ->orderColumn('filter_material', function ($query, $order) {
                $query->orderBy('material.material_code', $order);
            })
            ->orderColumn('filter_version', function ($query, $order) {
                $query->orderBy('version_asumsi.id', $order);
            })
            ->orderColumn('filter_month_year', function ($query, $order) {
                $query->orderBy('asumsi_umum.month_year', $order);
            })
            ->orderColumn('filter_pj_penjualan_value', function ($query, $order) {
                $query->orderBy('pj_penjualan.pj_penjualan_value', $order);
            })
            ->filterColumn('filter_material', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('material.material_code', 'ilike', '%' . $keyword . '%')
                        ->orWhere('material.material_name', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_version', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('version_asumsi.id', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_month_year', function ($query, $keyword) {
                $temp = explode('/', $keyword);
                if (count($temp) == 1) {
                    $query->Where('asumsi_umum.month_year', 'ilike', '%' . $keyword . '%');
                } elseif (count($temp) == 2) {
                    $keyword = $temp[1] . '-' . $temp[0];
                    $query->Where('asumsi_umum.month_year', 'ilike', '%' . $keyword . '%');
                }
            })
            ->addColumn('action', 'pages.buku_besar.pakai_jual.penjualan.action')
            ->escapeColumns([]);
    }

    // public function html()
    // {
    //     return $this->builder()
    //         ->addTableClass('table table-bordered text-nowrap key-buttons')
    //         ->setTableId('dt_qty_renprod')
    //         ->columns($this->getColumns())
    //         ->minifiedAjax()
    //         ->dom('Bfrtip')
    //         ->orderBy(1)
    //         ->buttons(
    //             Button::make('create'),
    //             Button::make('export'),
    //             Button::make('print'),
    //             Button::make('reset'),
    //             Button::make('reload')
    //         );
    // }

    /**
     * Get columns.
     *
     * @return array
     */
    // protected function getColumns()
    // {
    //     return [
    //         Column::computed('action')
    //             ->exportable(false)
    //             ->printable(false)
    //             ->width(60)
    //             ->addClass('text-center'),
    //         Column::make('id'),
    //         Column::make('add your columns'),
    //         Column::make('created_at'),
    //         Column::make('updated_at'),
    //     ];
    // }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Master\Penjualan_' . date('YmdHis');
    }
}
