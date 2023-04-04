<?php

namespace App\DataTables\Master;

use App\Models\ConsRate;
use App\Models\GroupAccountFC;
use App\Models\LabaRugi;
use App\Models\Material;
use App\Models\Saldo_Awal;
use App\Models\Salr;
use App\Models\SimulasiProyeksi;
use App\Models\Zco;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SummarySimulasiProyeksiDataTable extends DataTable
{
    public function dataTable($query)
    {
        if ($this->save == false) {
            $query = DB::table('simulasi_proyeksi')
                ->select('simulasi_proyeksi.no', 'simulasi_proyeksi.kategori', 'simulasi_proyeksi.name', 'simulasi_proyeksi.code', 'simulasi_proyeksi.plant_code', 'simulasi_proyeksi.product_code', 'material.material_name', 'plant.plant_desc')
                ->leftjoin('material', 'material.material_code', '=', 'simulasi_proyeksi.product_code')
                ->leftjoin('plant', 'plant.plant_code', '=', 'simulasi_proyeksi.plant_code')
                ->where('simulasi_proyeksi.version_id', $this->version)
                ->where('simulasi_proyeksi.plant_code', $this->plant)
                ->where('simulasi_proyeksi.product_code', $this->produk)
                ->where('simulasi_proyeksi.cost_center', $this->cost_center)
                ->groupBy('simulasi_proyeksi.no', 'simulasi_proyeksi.kategori', 'simulasi_proyeksi.name', 'simulasi_proyeksi.code', 'simulasi_proyeksi.plant_code', 'simulasi_proyeksi.product_code', 'material.material_name', 'plant.plant_desc')
                ->whereIn('no', [10, 15, 16, 9]);

            $datatable = datatables()
                ->query($query)
                ->addColumn('material', function ($query) {
                    return $query->product_code . ' ' . $query->material_name;
                })
                ->addColumn('plant', function ($query) {
                    return $query->plant_code . ' ' . $query->plant_desc;
                })
                ->addColumn('keterangan', function ($query) {
                    if ($query->no == 10 || $query->no == 15 || $query->no == 16) {
                        return $query->code;
                    } else {
                        return 'Kuantiti Produksi 1 Cost Center';
                    }
                });

            $main_asumsi = DB::table('asumsi_umum')
                ->where('version_id', $this->version)->get();

            $asumsi_balans = $main_asumsi->pluck('id')->all();

            $simproValues = DB::table('simulasi_proyeksi')
                ->select('plant_code', 'product_code', 'cost_center', 'biaya_perton', 'asumsi_umum_id', 'no', 'kuantum_produksi')
                ->where('plant_code', $this->plant)
                ->where('product_code', $this->produk)
                ->where('cost_center', $this->cost_center)
                ->where('simulasi_proyeksi.version_id', $this->version)
                // ->whereIn('no', [10, 15, 16])
                ->get();

            foreach ($asumsi_balans as $key => $items) {
                $datatable->addColumn($key, function ($query) use ($simproValues, $key, $items, $main_asumsi) {
                    $result = $simproValues
                        ->where('asumsi_umum_id', $main_asumsi[$key]->id)
                        ->where('no', $query->no)
                        ->first();

                    // return $result ? rupiah($result->biaya_perton) : 0;

                    if ($result->no == 10 || $result->no == 15) {
                        return $result ? rupiah($result->biaya_perton) : 0;
                    } else if ($result->no == 16) {
                        return '$ ' . helpRibuanKoma($result->biaya_perton);
                    } else if ($result->no == 9) {
                        return helpRibuanKoma($result->kuantum_produksi);
                    } else {
                        return 0;
                    }
                });

                // ->addColumn('kuanprod' . $key, function ($query) use ($simproValues, $key, $items, $main_asumsi) {
                //     $result = $simproValues
                //         ->where('asumsi_umum_id', $main_asumsi[$key]->id)
                //         ->where('no', $query->no)
                //         ->first();

                //     return helpRibuanKoma($result->kuantum_produksi);
                // })
            }

            return $datatable;
        }
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
            ->setTableId('h_dt_simulasi_proyeksi')
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
        return 'Master\H_SimulasiProyeksi_' . date('YmdHis');
    }
}
