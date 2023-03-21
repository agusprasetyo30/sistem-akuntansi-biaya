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

class SimulasiProyeksiDataTable extends DataTable
{
    public function dataTable($query)
    {
        if ($this->save == false) {
            $query = DB::table('simulasi_proyeksi')
                ->select('simulasi_proyeksi.no', 'simulasi_proyeksi.kategori', 'simulasi_proyeksi.name', 'simulasi_proyeksi.code')
                ->where('simulasi_proyeksi.version_id', $this->version)
                ->where('simulasi_proyeksi.plant_code', $this->plant)
                ->where('simulasi_proyeksi.product_code', $this->produk)
                ->where('simulasi_proyeksi.cost_center', $this->cost_center)
                ->groupBy('simulasi_proyeksi.no', 'simulasi_proyeksi.kategori', 'simulasi_proyeksi.name', 'simulasi_proyeksi.code')
                ->orderBy('no', 'asc')
                ->orderBy('kategori', 'asc');

            $datatable = datatables()
                ->query($query)
                ->addColumn('name', function ($query) {
                    if ($query->no == 1 || $query->no == 2 || $query->no == 3 || $query->no == 4 || $query->no == 6 || $query->no == 8 && $query->kategori != 0) {
                        $res = $query->code . ' - ' . $query->name;
                    } else {
                        $res = $query->name;
                    }
                    return $res;
                });

            $asumsi = DB::table('asumsi_umum')
                ->where('version_id', $this->version)
                ->get();

            $simproValues = DB::table('simulasi_proyeksi')
                ->where('plant_code', $this->plant)
                ->where('product_code', $this->produk)
                ->where('cost_center', $this->cost_center)
                ->whereIn('asumsi_umum_id', $asumsi->pluck('id')->all())
                ->get();

            foreach ($asumsi as $key => $asum) {
                $datatable->addColumn($key . 'harga_satuan', function ($query) use ($simproValues, $asum) {
                    $mat = Material::where('material_code', $query->code)->first();
                    $ga = GroupAccountFC::where('group_account_fc', $query->code)->first();

                    if ($mat) {
                        $simproAsumsi = $simproValues
                            ->where('asumsi_umum_id', $asum->id)
                            ->where('name', $query->name)
                            ->first();

                        return rupiah($simproAsumsi->harga_satuan);
                    } else if ($ga) {
                        return '-';
                    } else {
                        return '';
                    }
                })->addColumn($key . 'cr', function ($query) use ($simproValues, $asum) {
                    $mat = Material::where('material_code', $query->code)->first();
                    $ga = GroupAccountFC::where('group_account_fc', $query->code)->first();

                    if ($mat) {
                        $simproAsumsi = $simproValues
                            ->where('asumsi_umum_id', $asum->id)
                            ->where('name', $query->name)
                            ->first();

                        return helpRibuanKoma($simproAsumsi->cr);
                    } else if ($ga) {
                        return '-';
                    } else {
                        return '';
                    }
                })->addColumn($key . 'biaya_perton', function ($query) use ($simproValues, $asum) {
                    $mat = Material::where('material_code', $query->code)->first();
                    $ga = GroupAccountFC::where('group_account_fc', $query->code)->first();

                    if ($mat) {
                        $simproAsumsi = $simproValues
                            ->where('asumsi_umum_id', $asum->id)
                            ->where('name', $query->name)
                            ->first();

                        return rupiah($simproAsumsi->biaya_perton);
                    } else if ($ga) {
                        $simproAsumsi = $simproValues
                            ->where('asumsi_umum_id', $asum->id)
                            ->where('name', $query->name)
                            ->first();

                        return rupiah($simproAsumsi->biaya_perton);
                    } else {
                        if ($query->no == 5 || $query->no == 7 || $query->no == 9 || $query->no == 10 || $query->no == 11 || $query->no == 12 || $query->no == 13 || $query->no == 14 || $query->no == 15) {
                            $simproAsumsi = $simproValues
                                ->where('asumsi_umum_id', $asum->id)
                                ->where('name', $query->name)
                                ->first();

                            return rupiah($simproAsumsi->biaya_perton);
                        } else if ($query->no == 16) {
                            $simproAsumsi = $simproValues
                                ->where('asumsi_umum_id', $asum->id)
                                ->where('name', $query->name)
                                ->first();

                            return '$ ' . helpRibuanKoma($simproAsumsi->biaya_perton);
                        } else {
                            return '';
                        }
                    }
                })->addColumn($key . 'total_biaya', function ($query) use ($simproValues, $asum) {
                    $mat = Material::where('material_code', $query->code)->first();
                    $ga = GroupAccountFC::where('group_account_fc', $query->code)->first();

                    if ($mat) {
                        $simproAsumsi = $simproValues
                            ->where('asumsi_umum_id', $asum->id)
                            ->where('name', $query->name)
                            ->first();

                        return rupiah($simproAsumsi->total_biaya);
                    } else if ($ga) {
                        $simproAsumsi = $simproValues
                            ->where('asumsi_umum_id', $asum->id)
                            ->where('name', $query->name)
                            ->first();

                        return rupiah($simproAsumsi->total_biaya);
                    } else {
                        if ($query->no == 5 || $query->no == 7 || $query->no == 9 || $query->no == 10) {
                            $simproAsumsi = $simproValues
                                ->where('asumsi_umum_id', $asum->id)
                                ->where('name', $query->name)
                                ->first();

                            return rupiah($simproAsumsi->total_biaya);
                        } else if ($query->no == 11) {
                            return '';
                        } else if ($query->no == 12) {
                            return '';
                        } else if ($query->no == 13) {
                            return '';
                        } else if ($query->no == 14) {
                            return '';
                        } else if ($query->no == 15) {
                            return '';
                        } else if ($query->no == 16) {
                            return '';
                        } else {
                            return '';
                        }
                    }
                });
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
