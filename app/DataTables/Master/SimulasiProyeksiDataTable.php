<?php

namespace App\DataTables\Master;

use App\Models\GroupAccountFC;
use App\Models\Material;
use App\Models\Saldo_Awal;
use App\Models\Salr;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SimulasiProyeksiDataTable extends DataTable
{
    public function dataTable($query)
    {
        $cr = DB::table("cons_rate")
            ->select(
                DB::raw("(
                    CASE
                        WHEN material.kategori_material_id = 1 THEN 1
                        WHEN material.kategori_material_id = 2 THEN 3
                        WHEN material.kategori_material_id = 3 THEN 2
                        WHEN material.kategori_material_id = 4 THEN 4
                        ELSE 0 END)
                    AS no"),
                "material.kategori_material_id as kategori",
                "material.material_name as name",
                "cons_rate.material_code as code",
            )
            ->leftJoin('material', 'material.material_code', '=', 'cons_rate.material_code')
            ->where('product_code', $this->produk)
            ->where('plant_code', $this->plant);

        // $salr = DB::table("salrs")
        //     ->select(
        //         DB::raw("(
        //             CASE
        //                 WHEN group_account_fc.group_account_fc = '1200' OR
        //                 group_account_fc.group_account_fc = '1500' OR
        //                 group_account_fc.group_account_fc = '1100' OR
        //                 group_account_fc.group_account_fc = '1300' OR
        //                 group_account_fc.group_account_fc = '1600' OR
        //                 group_account_fc.group_account_fc = '1000' OR
        //                 group_account_fc.group_account_fc = '1400' THEN 8
        //                 ELSE 6 END)
        //             AS no"),
        //         DB::raw("(
        //             CASE
        //                 WHEN group_account_fc.group_account_fc IS NOT NULL THEN 1
        //                 ELSE 0 END)
        //             AS kategori"),
        //         "group_account_fc.group_account_fc_desc as name",
        //         "group_account_fc.group_account_fc as code",
        //     )
        //     ->leftjoin('gl_account_fc', 'gl_account_fc.gl_account_fc', '=', 'salrs.gl_account_fc')
        //     ->leftjoin('group_account_fc', 'group_account_fc.group_account_fc', '=', 'gl_account_fc.group_account_fc')
        //     ->where('cost_center', $this->cost_center)
        //     ->union($cr);

        $group_account = DB::table("group_account_fc")
            ->select(
                DB::raw("(
                CASE
                    WHEN group_account_fc.group_account_fc = '1200' OR
                    group_account_fc.group_account_fc = '1500' OR
                    group_account_fc.group_account_fc = '1100' OR
                    group_account_fc.group_account_fc = '1300' OR
                    group_account_fc.group_account_fc = '1600' OR
                    group_account_fc.group_account_fc = '1000' OR
                    group_account_fc.group_account_fc = '1400' THEN 8
                    ELSE 6 END)
                AS no"),
                DB::raw("(
                CASE
                    WHEN group_account_fc.group_account_fc IS NOT NULL THEN 1
                    ELSE 0 END)
                AS kategori"),
                "group_account_fc.group_account_fc_desc as name",
                "group_account_fc.group_account_fc as code",
            )
            ->union($cr);

        $query = DB::table("temp_proyeksi")
            ->select(
                "temp_proyeksi.id as no",
                DB::raw("(
                    CASE
                        WHEN temp_proyeksi.proyeksi_name  = 'Bahan Baku, Penolong & Utilitas - Dasar Balans' THEN 1
                        WHEN temp_proyeksi.proyeksi_name  = 'Bahan Baku, Penolong & Utilitas - Dasar ZCOHPPDET' THEN 2
                        WHEN temp_proyeksi.proyeksi_name  = 'Bahan Baku, Penolong & Utilitas - Dasar Stock' THEN 3
                        WHEN temp_proyeksi.proyeksi_name  = 'Bahan Baku, Penolong & Utilitas - Dasar Saldo Awal & CR Sesuai Perhitungan' THEN 4
                        ELSE 0 END)
                    AS kategori"),
                "temp_proyeksi.proyeksi_name as name",
                "temp_proyeksi.proyeksi_name as code",
            )
            ->union($group_account)
            ->orderBy('no', 'asc')
            ->orderBy('kategori', 'asc');

        // dd($query->get());
        $datatable = datatables()
            ->query($query)
            ->addColumn('name', function ($query) {
                return $query->name;
            });

        $asumsi = DB::table('asumsi_umum')
            ->where('version_id', $this->version)
            ->get();

        foreach ($asumsi as $key => $asum) {
            $datatable->addColumn($key, function ($query) use ($asum) {
                $mat = Material::where('material_code', $query->code)->first();
                $ga = GroupAccountFC::where('group_account_fc', $query->code)->first();

                if ($mat) {
                    if ($query->kategori == 1) {
                        //rumus balans
                        return 'balans';
                    } else if ($query->kategori == 2) {
                        //rumus aco
                        return 'zco';
                    } else if ($query->kategori == 3) {
                        //rumus stok
                        $total_sa = Saldo_Awal::select(DB::raw('SUM(total_value) as total_value'))
                            ->where([
                                'material_code' => $query->code,
                            ])->first();

                        $stok_sa = Saldo_Awal::select(DB::raw('SUM(total_stock) as total_stock'))
                            ->where([
                                'material_code' => $query->code,
                            ])->first();

                        if ($total_sa->total_value > 0 && $stok_sa->total_stock > 0) {
                            $biaya_stok = $total_sa->total_value / $stok_sa->total_stock;
                        } else {
                            $biaya_stok = 0;
                        }

                        return $biaya_stok;
                    } else if ($query->kategori == 4) {
                        //rumus kantong
                        $total_sa = Saldo_Awal::select(DB::raw('SUM(total_value) as total_value'))
                            ->where([
                                'material_code' => $query->code,
                            ])->first();

                        $stok_sa = Saldo_Awal::select(DB::raw('SUM(total_stock) as total_stock'))
                            ->where([
                                'material_code' => $query->code,
                            ])->first();

                        if ($total_sa->total_value > 0 && $stok_sa->total_stock > 0) {
                            $biaya_kantong = $total_sa->total_value / $stok_sa->total_stock;
                        } else {
                            $biaya_kantong = 0;
                        }

                        return $biaya_kantong;
                    } else {
                        return 'unknown';
                    }
                } else if ($ga) {
                    // if ($query->code == '1200' || $query->code == '1500' || $query->code == '1100' || $query->code == '1300' || $query->code == '1600' || $query->code == '1000' || $query->code == '1400') {
                    //     return 'tidak langsung';
                    // } else {
                    //     return 'langsung';
                    // }
                    return '';
                } else {
                    return '';
                }
            })->addColumn($key, function ($query) use ($asum) {
                $mat = Material::where('material_code', $query->code)->first();
                $ga = GroupAccountFC::where('group_account_fc', $query->code)->first();

                if ($mat) {
                    $res = 'plant : ' . $this->plant . ', produk : ' . $this->produk . ', material : ' . $query->code;
                    return $res;
                } else if ($ga) {
                    return '';
                } else {
                    return '';
                }
            })->addColumn($key, function ($query) use ($asum) {
                $mat = Material::where('material_code', $query->code)->first();
                $ga = GroupAccountFC::where('group_account_fc', $query->code)->first();

                if ($mat) {
                    return 'mat';
                } else if ($ga) {
                    $salr = DB::table("salrs")
                        ->leftjoin('gl_account_fc', 'gl_account_fc.gl_account_fc', '=', 'salrs.gl_account_fc')
                        ->leftjoin('group_account_fc', 'group_account_fc.group_account_fc', '=', 'gl_account_fc.group_account_fc')
                        ->where('salrs.cost_center', $this->cost_center)
                        ->where('group_account_fc.group_account_fc', $query->code)
                        ->first();

                    if ($salr) {
                        $renprod = DB::table("qty_renprod")
                            ->where('qty_renprod.cost_center', $this->cost_center)
                            ->where('qty_renprod.asumsi_umum_id', $asum->id)
                            ->first();

                        $total = Salr::select(DB::raw('SUM(value) as value'))
                            ->leftjoin('gl_account_fc', 'gl_account_fc.gl_account_fc', '=', 'salrs.gl_account_fc')
                            ->leftjoin('group_account_fc', 'group_account_fc.group_account_fc', '=', 'gl_account_fc.group_account_fc')
                            ->where([
                                'salrs.cost_center' => $salr->cost_center,
                                'group_account_fc.group_account_fc' => $salr->group_account_fc
                            ])->first();

                        $result = $total->value * $asum->inflasi / 100;

                        $biaya_perton = $result / $renprod->qty_renprod_value;

                        return round($biaya_perton, 2);
                    } else {
                        return '';
                    }
                } else {
                    return '';
                }
            })->addColumn($key, function ($query) use ($asum) {
                $mat = Material::where('material_code', $query->code)->first();
                $ga = GroupAccountFC::where('group_account_fc', $query->code)->first();

                if ($mat) {
                    return 'mat';
                } else if ($ga) {
                    $salr = DB::table("salrs")
                        ->leftjoin('gl_account_fc', 'gl_account_fc.gl_account_fc', '=', 'salrs.gl_account_fc')
                        ->leftjoin('group_account_fc', 'group_account_fc.group_account_fc', '=', 'gl_account_fc.group_account_fc')
                        ->where('salrs.cost_center', $this->cost_center)
                        ->where('group_account_fc.group_account_fc', $query->code)
                        ->first();
                    if ($salr) {
                        $total = Salr::select(DB::raw('SUM(value) as value'))
                            ->leftjoin('gl_account_fc', 'gl_account_fc.gl_account_fc', '=', 'salrs.gl_account_fc')
                            ->leftjoin('group_account_fc', 'group_account_fc.group_account_fc', '=', 'gl_account_fc.group_account_fc')
                            ->where([
                                'salrs.cost_center' => $salr->cost_center,
                                'group_account_fc.group_account_fc' => $salr->group_account_fc
                            ])->first();

                        $result = $total->value * $asum->inflasi / 100;

                        return $result;
                    } else {
                        return '';
                    }
                } else {
                    return '';
                }
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
