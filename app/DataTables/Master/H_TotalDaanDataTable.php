<?php

namespace App\DataTables\Master;

use App\Models\Master\H_QtyRenDaan;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use function PHPUnit\Framework\isEmpty;

class H_TotalDaanDataTable extends DataTable
{


    public function dataTable($query)
    {
        $query = DB::table('qty_rendaan')
            ->select('qty_rendaan.material_code', 'qty_rendaan.region_id', 'regions.region_name', 'material.material_name')
            ->leftjoin('material', 'material.material_code', '=', 'qty_rendaan.material_code')
            ->leftjoin('regions', 'regions.id', '=', 'qty_rendaan.region_id')
            ->whereNull('qty_rendaan.deleted_at')
            ->where('qty_rendaan.version_id', $this->version)
            ->groupBy('qty_rendaan.material_code', 'qty_rendaan.region_id', 'regions.region_name', 'material.material_name');

        $datatable = datatables()
            ->query($query)
            ->addColumn('material', function ($query) {
                return $query->material_code . ' - ' . $query->material_name;
            });

        $asumsi = DB::table('asumsi_umum')
            ->where('version_id', $this->version)
            ->get();
        $rendaanValues = DB::table('qty_rendaan')
            ->whereIn('asumsi_umum_id', $asumsi->pluck('id')->all())
            ->leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'qty_rendaan.asumsi_umum_id')
            ->get();

        foreach ($asumsi as $key => $a) {
            $datatable->addColumn($key, function ($query) use ($rendaanValues, $a) {
                $rendaanAsumsi = $rendaanValues
                    ->where('asumsi_umum_id', $a->id)
                    ->where('region_id', $query->region_id)
                    ->where('material_code', $query->material_code)
                    ->first();

                $result = 0;

                $query2 = DB::table('price_rendaan')
                    ->select('price_rendaan.price_rendaan_value')
                    ->where('material_code', $query->material_code)
                    ->where('region_id', $query->region_id)
                    ->where('version_id', $this->version)
                    ->where('asumsi_umum_id', $a->id)
                    ->whereNull('price_rendaan.deleted_at')
                    ->first();

                if ($rendaanAsumsi->qty_rendaan_value > 0 && $query2->price_rendaan_value == 0) {
                    return '-';
                } else {
                    $result = $rendaanAsumsi->qty_rendaan_value * ($query2->price_rendaan_value * (1 + $rendaanAsumsi->adjustment) * $rendaanAsumsi->usd_rate);
                    return rupiah($result);
                }

                // return $rendaanAsumsi ? rupiah($rendaanAsumsi->qty_rendaan_value) : '-';
            });
        }
        return $datatable;
    }


    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->addTableClass('table table-bordered text-nowrap key-buttons')
            ->setTableId('h_dt_total_daan')
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
        return 'Master\H_QtyRenDaan_' . date('YmdHis');
    }
}
