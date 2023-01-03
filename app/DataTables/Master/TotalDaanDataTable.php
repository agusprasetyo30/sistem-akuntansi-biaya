<?php

namespace App\DataTables\Master;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TotalDaanDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $query = DB::table('qty_rendaan')
            ->select('qty_rendaan.*', 'material.material_name', 'asumsi_umum.month_year', 'asumsi_umum.usd_rate', 'asumsi_umum.adjustment', 'version_asumsi.version', 'regions.region_name')
            ->leftjoin('material', 'material.material_code', '=', 'qty_rendaan.material_code')
            ->leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'qty_rendaan.asumsi_umum_id')
            ->leftjoin('version_asumsi', 'version_asumsi.id', '=', 'qty_rendaan.version_id')
            ->leftjoin('regions', 'regions.id', '=', 'qty_rendaan.region_id')
            // ->leftjoin('price_rendaan', 'price_rendaan.material_code', '=', 'qty_rendaan.material_code')
            ->whereNull('qty_rendaan.deleted_at');

        return datatables()
            ->query($query)
            ->addIndexColumn()
            ->addColumn('material', function ($query) {
                return $query->material_code . ' - ' . $query->material_name;
            })
            ->addColumn('version', function ($query) {
                return $query->version;
            })
            ->addColumn('periode', function ($query) {
                return format_month($query->month_year, 'bi');
            })
            ->addColumn('value', function ($query) {
                $result = 0;
                $query2 = DB::table('price_rendaan')
                    ->select('price_rendaan.price_rendaan_value')
                    ->where('material_code', $query->material_code)
                    ->where('region_id', $query->region_id)
                    ->where('version_id', $query->version_id)
                    ->where('asumsi_umum_id', $query->asumsi_umum_id)
                    ->whereNull('price_rendaan.deleted_at')
                    ->first();

                $val_qty_rendaan = $query ? $query->qty_rendaan_value : 0;
                $val_price_daan = $query2 ? $query2->price_rendaan_value : 0;
                $val_adjustment = $query ? $query->adjustment : 0;
                $val_kurs = $query ? $query->usd_rate : 0;

                if ($val_qty_rendaan > 0 && $val_price_daan == 0) {
                    return '-';
                } else {
                    $result = $val_qty_rendaan * ($val_price_daan * (1 + $val_adjustment) * $val_kurs);
                    return rupiah($result);
                }
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
                    $query->where('qty_rendaan.material_code', 'ilike', '%' . $keyword . '%')
                        ->orWhere('material.material_name', 'ilike', '%' . $keyword . '%');
                }
            })
            ->filterColumn('filter_region', function ($query, $keyword) {
                $query->where('regions.region_name', 'ilike', '%' . $keyword . '%');
            })
            ->orderColumn('filter_version', function ($query, $order) {
                $query->orderBy('version_asumsi.version', $order);
            })
            ->orderColumn('filter_periode', function ($query, $order) {
                $query->orderBy('asumsi_umum.month_year', $order);
            })
            ->orderColumn('filter_material', function ($query, $order) {
                $query->orderBy('qty_rendaan.material_code', $order);
            })
            ->orderColumn('filter_region', function ($query, $order) {
                $query->orderBy('regions.region_name', $order);
            })
            ->escapeColumns([]);
    }

    public function html()
    {
        return $this->builder()
            ->addTableClass('table table-bordered text-nowrap key-buttons')
            ->setTableId('dt_total_daan')
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
        return 'Master\QtyTotalDaan_' . date('YmdHis');
    }
}
