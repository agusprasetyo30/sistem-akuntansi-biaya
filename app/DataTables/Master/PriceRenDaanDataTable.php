<?php

namespace App\DataTables\Master;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PriceRenDaanDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $query = DB::table('price_rendaan')
            ->select('price_rendaan.*', 'material.material_name', 'asumsi_umum.month_year', 'version_asumsi.version', 'regions.region_desc', 'asumsi_umum.usd_rate')
            ->leftjoin('material', 'material.material_code', '=', 'price_rendaan.material_code')
            ->leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'price_rendaan.asumsi_umum_id')
            ->leftjoin('version_asumsi', 'version_asumsi.id', '=', 'price_rendaan.version_id')
            ->leftjoin('regions', 'regions.region_name', '=', 'price_rendaan.region_name')
            ->whereNull('price_rendaan.deleted_at');

        if ($this->filter_company != 'all' && auth()->user()->mapping_akses('price_rendaan')->company_code == 'all') {
            $query = $query->where('price_rendaan.company_code', $this->filter_company);
        } else if ($this->filter_company != 'all' && auth()->user()->mapping_akses('price_rendaan')->company_code != 'all') {
            $query = $query->where('price_rendaan.company_code', auth()->user()->mapping_akses('price_rendaan')->company_code);
        }

        if ($this->filter_version != 'all') {
            $query = $query->where('price_rendaan.version_id', $this->filter_version);
        }

        return datatables()
            ->query($query)
            ->addIndexColumn()
            ->addColumn('version', function ($query) {
                return $query->version;
            })
            ->addColumn('periode', function ($query) {
                return format_month($query->month_year, 'bi');
            })
            ->addColumn('value', function ($query) {
                if ($this->currency == 'Rupiah') {
                    return rupiah($query->price_rendaan_value);
                } elseif ($this->currency == 'Dollar') {
                    return helpDollar($query->price_rendaan_value, $query->usd_rate);
                }
            })
            ->addColumn('material', function ($query) {
                return $query->material_code . ' - ' . $query->material_name;
            })
            ->filterColumn('filter_version', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('version_asumsi.id', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_periode', function ($query, $keyword) {
                $temp = explode('/', $keyword);
                if (count($temp) == 1) {
                    $query->Where('asumsi_umum.month_year', 'ilike', '%' . $keyword . '%');
                } elseif (count($temp) == 2) {
                    $keyword = $temp[1] . '-' . $temp[0];
                    $query->Where('asumsi_umum.month_year', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_material', function ($query, $keyword) {
                if ($keyword != 'all') {
                    $query->where('price_rendaan.material_code', 'ilike', '%' . $keyword . '%')
                        ->orWhere('material.material_name', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_region', function ($query, $keyword) {
                $query->where('regions.region_desc', 'ilike', '%' . $keyword . '%');
            })
            ->filterColumn('filter_price_rendaan_value', function ($query, $keyword) {
                $keyword = str_replace('.', '', str_replace('Rp ', '', $keyword));
                $query->where('price_rendaan.price_rendaan_value', 'ilike', '%' . $keyword . '%');
            })
            ->orderColumn('filter_version', function ($query, $order) {
                $query->orderBy('version_asumsi.version', $order);
            })
            ->orderColumn('filter_periode', function ($query, $order) {
                $query->orderBy('asumsi_umum.month_year', $order);
            })
            ->orderColumn('filter_material', function ($query, $order) {
                $query->orderBy('price_rendaan.material_code', $order);
            })
            ->orderColumn('filter_region', function ($query, $order) {
                $query->orderBy('regions.region_desc', $order);
            })
            ->orderColumn('filter_price_rendaan_value', function ($query, $order) {
                $query->orderBy('price_rendaan.price_rendaan_value', $order);
            })
            ->addColumn('action', 'pages.buku_besar.price_rendaan.action')
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->addTableClass('table table-bordered text-nowrap key-buttons')
            ->setTableId('dt_price_rendaan')
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
        return 'Master\PriceRenDaan_' . date('YmdHis');
    }
}
