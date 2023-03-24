<?php

namespace App\DataTables\Master;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class QtyRenProdDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $query = DB::table('qty_renprod')->select('qty_renprod.*', 'cost_center.cost_center', 'cost_center.cost_center_desc', 'version_asumsi.version', 'asumsi_umum.month_year')
            ->leftjoin('cost_center', 'cost_center.cost_center', '=', 'qty_renprod.cost_center')
            ->leftjoin('version_asumsi', 'version_asumsi.id', '=', 'qty_renprod.version_id')
            ->leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'qty_renprod.asumsi_umum_id')
            ->whereNull('qty_renprod.deleted_at');

        if ($this->filter_company != 'all' && auth()->user()->mapping_akses('qty_renprod')->company_code == 'all') {
            $query = $query->where('qty_renprod.company_code', $this->filter_company);
        } else if ($this->filter_company != 'all' && auth()->user()->mapping_akses('qty_renprod')->company_code != 'all') {
            $query = $query->where('qty_renprod.company_code', auth()->user()->mapping_akses('qty_renprod')->company_code);
        }

        if ($this->filter_version != 'all') {
            $query = $query->where('qty_renprod.version_id', $this->filter_version);
        }

        return datatables()
            ->query($query)
            ->addIndexColumn()
            ->editColumn('month_year', function ($query) {
                return format_month($query->month_year, 'bi');
            })
            ->editColumn('qty_renprod_value', function ($query) {
                return helpRibuan($query->qty_renprod_value);
            })
            ->editColumn('cost_center', function ($query) {
                return $query->cost_center . ' ' . $query->cost_center_desc;
            })
            ->orderColumn('filter_cost_center', function ($query, $order) {
                $query->orderBy('cost_center.cost_center', $order);
            })
            ->orderColumn('filter_version', function ($query, $order) {
                $query->orderBy('version_asumsi.id', $order);
            })
            ->orderColumn('filter_month_year', function ($query, $order) {
                $query->orderBy('asumsi_umum.month_year', $order);
            })
            ->orderColumn('filter_qty_renprod_value', function ($query, $order) {
                $query->orderBy('qty_renprod.qty_renprod_value', $order);
            })
            ->filterColumn('filter_cost_center', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('cost_center.cost_center', 'ilike', '%' . $keyword . '%')
                        ->orWhere('cost_center.cost_center_desc', 'ilike', '%' . $keyword . '%');
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
            ->filterColumn('filter_qty_renprod_value', function ($query, $keyword) {
                $keyword = str_replace('.', '', str_replace('Rp ', '', $keyword));
                $query->where('qty_renprod.qty_renprod_value', 'ilike', '%' . $keyword . '%');
            })
            ->addColumn('action', 'pages.buku_besar.qty_renprod.action')
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
        return 'Master\QtyRenProd_' . date('YmdHis');
    }
}
