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

class SimulasiProyeksiStoreDataTable extends DataTable
{
    public function dataTable($d_version, $d_plant, $d_produk, $d_cost_center)
    {
        $this->save = true;
        if ($this->save == true) {
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
                ->where('product_code', $d_produk)
                ->where('plant_code', $d_plant);

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
                ->where('version_id', $d_version)
                ->get();

            $cekBB = $query->get();
            $resBB = [];
            for ($i = 0; $i < count($cekBB); $i++) {
                if ($cekBB[$i]->kategori != 0 && ($cekBB[$i]->no == 1 || $cekBB[$i]->no == 2 || $cekBB[$i]->no == 3 || $cekBB[$i]->no == 4)) {
                    array_push($resBB, $cekBB[$i]);
                }
            }

            $gaLangsung = $query->get();
            $resgaLangsung = [];
            for ($i = 0; $i < count($gaLangsung); $i++) {
                if ($gaLangsung[$i]->kategori != 0 && $gaLangsung[$i]->no == 6) {
                    array_push($resgaLangsung, $gaLangsung[$i]);
                }
            }

            $gatidakLangsung = $query->get();
            $resgatidakLangsung = [];
            for ($i = 0; $i < count($gatidakLangsung); $i++) {
                if ($gatidakLangsung[$i]->kategori != 0 && $gatidakLangsung[$i]->no == 8) {
                    array_push($resgatidakLangsung, $gatidakLangsung[$i]);
                }
            }

            foreach ($asumsi as $key => $asum) {
                $datatable->addColumn($key . 'harga_satuan', function ($query) use ($asum, $d_version, $d_plant, $d_produk, $d_cost_center) {
                    return 0 ;
                })->addColumn($key . 'cr', function ($query) use ($asum, $d_version, $d_plant, $d_produk, $d_cost_center) {
                    return 0 ;
                })->addColumn($key . 'biaya_perton', function ($query) use ($asum, $resBB, $resgaLangsung, $resgatidakLangsung, $d_version, $d_plant, $d_produk, $d_cost_center) {
                    return 0 ;
                })->addColumn($key . 'total_biaya', function ($query) use ($asum, $resBB, $resgaLangsung, $resgatidakLangsung, $d_version, $d_plant, $d_produk, $d_cost_center) {
                    return 0 ;
                })->addColumn($key . 'periode', function ($query) use ($asum) {
                    return $asum->id;
                });

                if ($this->save == true) {
                    DB::transaction(function () use ($datatable, $key, $d_version, $d_plant, $d_produk, $d_cost_center) {
                        $dt = $datatable->toArray();

                        $result = [];
                        foreach ($dt['data'] as $data) {
                            $input['version_id'] = $d_version;
                            $input['plant_code'] = $d_plant;
                            $input['product_code'] = $d_produk;
                            $input['cost_center'] = $d_cost_center;
                            $input['asumsi_umum_id'] = $data[$key . 'periode'];
                            $input['no'] = $data['no'];
                            $input['kategori'] = $data['kategori'];
                            $input['name'] = $data['name'];
                            $input['code'] = $data['code'];
                            // $input_nilai['harga_satuan'] = (float) str_replace('.', '', str_replace('Rp ', '', $data[$key . 'harga_satuan']));
                            $input['harga_satuan'] = (float) $data[$key . 'harga_satuan'];
                            $input['cr'] = (float) $data[$key . 'cr'];
                            // $input_nilai['biaya_perton'] = (float) str_replace('.', '', str_replace('Rp ', '', $data[$key . 'biaya_perton']));
                            // $input_nilai['total_biaya'] = (float) str_replace('.', '', str_replace('Rp ', '', $data[$key . 'total_biaya']));
                            $input['biaya_perton'] = (float) $data[$key . 'biaya_perton'];
                            $input['total_biaya'] = (float) $data[$key . 'total_biaya'];
                            $input['created_by'] = auth()->user()->id;
                            $input['created_at'] = Carbon::now()->format('Y-m-d');
                            $input['updated_at'] = Carbon::now()->format('Y-m-d');

                            array_push($result, $input);
                        }
                        $chunk = array_chunk($result, 500);
                        foreach ($chunk as $y){
                            SimulasiProyeksi::insert($y);
                        }
//                        var_dump($result);
                    });
                }
            }

            return $datatable;
        } else {
            $query = DB::table('simulasi_proyeksi')
                ->select('simulasi_proyeksi.no', 'simulasi_proyeksi.kategori', 'simulasi_proyeksi.name', 'simulasi_proyeksi.code')
                ->where('simulasi_proyeksi.version_id', $d_version)
                ->where('simulasi_proyeksi.plant_code', $d_plant)
                ->where('simulasi_proyeksi.product_code', $d_produk)
                ->where('simulasi_proyeksi.cost_center', $d_cost_center)
                ->groupBy('simulasi_proyeksi.no', 'simulasi_proyeksi.kategori', 'simulasi_proyeksi.name', 'simulasi_proyeksi.code')
                ->orderBy('no', 'asc')
                ->orderBy('kategori', 'asc');

            $datatable = datatables()
                ->query($query)
                ->addColumn('name', function ($query) {
                    return $query->name;
                });

            $asumsi = DB::table('asumsi_umum')
                ->where('version_id', $d_version)
                ->get();

            $simproValues = DB::table('simulasi_proyeksi')
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

                        return $simproAsumsi->harga_satuan;
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

                        return $simproAsumsi->cr;
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

                        return $simproAsumsi->biaya_perton;
                    } else if ($ga) {
                        $simproAsumsi = $simproValues
                            ->where('asumsi_umum_id', $asum->id)
                            ->where('name', $query->name)
                            ->first();

                        return $simproAsumsi->biaya_perton;
                    } else {
                        if ($query->no == 5 || $query->no == 7 || $query->no == 9 || $query->no == 10 || $query->no == 11 || $query->no == 12 || $query->no == 13 || $query->no == 14 || $query->no == 15 || $query->no == 16) {
                            $simproAsumsi = $simproValues
                                ->where('asumsi_umum_id', $asum->id)
                                ->where('name', $query->name)
                                ->first();

                            return $simproAsumsi->biaya_perton;
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

                        return $simproAsumsi->total_biaya;
                    } else if ($ga) {
                        $simproAsumsi = $simproValues
                            ->where('asumsi_umum_id', $asum->id)
                            ->where('name', $query->name)
                            ->first();

                        return $simproAsumsi->total_biaya;
                    } else {
                        if ($query->no == 5 || $query->no == 7 || $query->no == 9 || $query->no == 10) {
                            $simproAsumsi = $simproValues
                                ->where('asumsi_umum_id', $asum->id)
                                ->where('name', $query->name)
                                ->first();

                            return $simproAsumsi->total_biaya;
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
