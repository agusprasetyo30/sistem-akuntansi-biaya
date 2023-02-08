<?php

namespace App\DataTables\Master;

use App\Models\ConsRate;
use App\Models\GroupAccountFC;
use App\Models\Material;
use App\Models\Saldo_Awal;
use App\Models\Salr;
use App\Models\Zco;
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
                        if ($this->produk == $query->code) {
                            return 0;
                        } else {
                            //mengambil biaya perton berdasarkan periode, material, dan tersedia
                            // $balans = DB::table("balans")
                            //     ->where('balans.material_code', $query->code)
                            //     ->where('balans.periode', $asum->id)
                            //     ->first();

                            return 0;
                        }
                    } else if ($query->kategori == 2) {
                        //rumus zco
                        $total_qty = Zco::select(DB::raw('SUM(total_qty) as total_qty'))
                            ->where([
                                'product_code' => $this->produk,
                                'plant_code' => $this->plant,
                                'material_code' => $query->code,
                            ]);

                        $total_biaya = Zco::select(DB::raw('SUM(total_amount) as total_amount'))
                            ->where([
                                'product_code' => $this->produk,
                                'plant_code' => $this->plant,
                                'material_code' => $query->code,
                            ]);

                        $kuantum_produksi = Zco::select(DB::raw('product_qty', 'periode'))
                            ->where([
                                'product_code' => $this->produk,
                                'plant_code' => $this->plant,
                            ])->groupBy('product_qty', 'periode');

                        // if ($this->format == '0') {
                        //     $temp = explode('-', $this->moth);
                        //     $timemonth = $temp[1] . '-' . $temp[0];

                        //     $total_qty->where('periode', 'ilike', '%' . $timemonth . '%');
                        //     $total_biaya->where('periode', 'ilike', '%' . $timemonth . '%');
                        //     $kuantum_produksi->where('periode', 'ilike', '%' . $timemonth . '%');
                        // } else if ($this->format == '1') {
                        //     $start_temp = explode('-', $this->start_month);
                        //     $end_temp = explode('-', $this->end_month);
                        //     $start_date = $start_temp[1] . '-' . $start_temp[0] . '-01 00:00:00';
                        //     $end_date = $end_temp[1] . '-' . $end_temp[0] . '-01 00:00:00';

                        //     $total_qty->whereBetween('periode', [$start_date, $end_date]);
                        //     $total_biaya->whereBetween('periode', [$start_date, $end_date]);
                        //     $kuantum_produksi->whereBetween('periode', [$start_date, $end_date]);
                        // }

                        $total_qty = $total_qty->first();
                        $total_biaya = $total_biaya->first();
                        $kuantum_produksi = $kuantum_produksi->get()->toArray();

                        $tot_kuanprod = 0;

                        for ($i = 0; $i < count($kuantum_produksi); $i++) {
                            $tot_kuanprod = $tot_kuanprod + $kuantum_produksi[$i]['product_qty'];
                        }

                        $biaya_perton = 0;
                        if ($total_biaya->total_amount > 0 && $tot_kuanprod > 0) {
                            $biaya_perton = $total_biaya->total_amount / $tot_kuanprod;
                        }

                        $cr = 0;
                        if ($total_qty->total_qty > 0 && $tot_kuanprod > 0) {
                            $cr = $total_qty->total_qty / $tot_kuanprod;
                        }

                        $harga_satuan = 0;
                        if ($biaya_perton > 0 && $cr > 0) {
                            $harga_satuan = $biaya_perton / $cr;
                        }

                        return $harga_satuan;
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
                        return '';
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
                    $renprod = DB::table("qty_renprod")
                        ->where('qty_renprod.cost_center', $this->cost_center)
                        ->where('qty_renprod.asumsi_umum_id', $asum->id)
                        ->first();

                    if ($renprod) {
                        $cr_ = ConsRate::select(DB::raw('SUM(cons_rate) as cons_rate'))
                            ->where([
                                'cons_rate.plant_code' => $this->plant,
                                'cons_rate.product_code' => $this->produk,
                                'cons_rate.material_code' => $query->code
                            ])->first();

                        $cr = $cr_->cons_rate;
                    } else {
                        $cr = 0;
                    }

                    return $cr;
                } else if ($ga) {
                    return '';
                } else {
                    return '';
                }
            })->addColumn($key, function ($query) use ($asum) {
                $mat = Material::where('material_code', $query->code)->first();
                $ga = GroupAccountFC::where('group_account_fc', $query->code)->first();

                if ($mat) {
                    //cr
                    $renprod = DB::table("qty_renprod")
                        ->where('qty_renprod.cost_center', $this->cost_center)
                        ->where('qty_renprod.asumsi_umum_id', $asum->id)
                        ->first();

                    if ($renprod) {
                        $cr_ = ConsRate::select(DB::raw('SUM(cons_rate) as cons_rate'))
                            ->where([
                                'cons_rate.plant_code' => $this->plant,
                                'cons_rate.product_code' => $this->produk,
                                'cons_rate.material_code' => $query->code
                            ])->first();

                        $cr = $cr_->cons_rate;
                    } else {
                        $cr = 0;
                    }

                    if ($query->kategori == 1) {
                        //rumus balans
                        if ($this->produk == $query->code) {
                            return 0;
                        } else {
                            //mengambil biaya perton berdasarkan periode, material, dan tersedia
                            // $balans = DB::table("balans")
                            //     ->where('balans.material_code', $query->code)
                            //     ->where('balans.periode', $asum->id)
                            //     ->first();
                            $balans = 0;

                            $biayaperton1 = $balans * $cr;
                            return $biayaperton1;
                        }
                    } else if ($query->kategori == 2) {
                        //rumus zco
                        $total_qty = Zco::select(DB::raw('SUM(total_qty) as total_qty'))
                            ->where([
                                'product_code' => $this->produk,
                                'plant_code' => $this->plant,
                                'material_code' => $query->code,
                            ]);

                        $total_biaya = Zco::select(DB::raw('SUM(total_amount) as total_amount'))
                            ->where([
                                'product_code' => $this->produk,
                                'plant_code' => $this->plant,
                                'material_code' => $query->code,
                            ]);

                        $kuantum_produksi = Zco::select(DB::raw('product_qty', 'periode'))
                            ->where([
                                'product_code' => $this->produk,
                                'plant_code' => $this->plant,
                            ])->groupBy('product_qty', 'periode');

                        // if ($this->format == '0') {
                        //     $temp = explode('-', $this->moth);
                        //     $timemonth = $temp[1] . '-' . $temp[0];

                        //     $total_qty->where('periode', 'ilike', '%' . $timemonth . '%');
                        //     $total_biaya->where('periode', 'ilike', '%' . $timemonth . '%');
                        //     $kuantum_produksi->where('periode', 'ilike', '%' . $timemonth . '%');
                        // } else if ($this->format == '1') {
                        //     $start_temp = explode('-', $this->start_month);
                        //     $end_temp = explode('-', $this->end_month);
                        //     $start_date = $start_temp[1] . '-' . $start_temp[0] . '-01 00:00:00';
                        //     $end_date = $end_temp[1] . '-' . $end_temp[0] . '-01 00:00:00';

                        //     $total_qty->whereBetween('periode', [$start_date, $end_date]);
                        //     $total_biaya->whereBetween('periode', [$start_date, $end_date]);
                        //     $kuantum_produksi->whereBetween('periode', [$start_date, $end_date]);
                        // }

                        $total_qty = $total_qty->first();
                        $total_biaya = $total_biaya->first();
                        $kuantum_produksi = $kuantum_produksi->get()->toArray();

                        $tot_kuanprod = 0;

                        for ($i = 0; $i < count($kuantum_produksi); $i++) {
                            $tot_kuanprod = $tot_kuanprod + $kuantum_produksi[$i]['product_qty'];
                        }

                        $biaya_perton = 0;
                        if ($total_biaya->total_amount > 0 && $tot_kuanprod > 0) {
                            $biaya_perton = $total_biaya->total_amount / $tot_kuanprod;
                        }

                        $kp = 0;
                        if ($total_qty->total_qty > 0 && $tot_kuanprod > 0) {
                            $kp = $total_qty->total_qty / $tot_kuanprod;
                        }

                        $harga_satuan = 0;
                        if ($biaya_perton > 0 && $kp > 0) {
                            $harga_satuan = $biaya_perton / $kp;
                        }

                        $biayaperton2 = $harga_satuan * $cr;
                        return $biayaperton2;
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

                        $biayaperton3 = $biaya_stok * $cr;
                        return $biayaperton3;
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

                        $biayaperton4 = $biaya_kantong * $cr;
                        return $biayaperton4;
                    } else {
                        return '';
                    }
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
                    if ($query->no == 5) {
                        return 'Total Bahan Baku';
                    } else if ($query->no == 7) {
                        return 'Total Overhead Langsung';
                    } else if ($query->no == 9) {
                        return 'Total Overhead Tidak Langsung';
                    } else if ($query->no == 10) {
                        return 'COGM';
                    } else if ($query->no == 11) {
                        return 'Biaya Administrasi & Umum';
                    } else if ($query->no == 12) {
                        return 'Biaya Pemasaran';
                    } else if ($query->no == 13) {
                        return 'Biaya Keuangan';
                    } else if ($query->no == 14) {
                        return 'Biaya Periodik';
                    } else if ($query->no == 15) {
                        return 'HPP Fullcosting Lini 1';
                    } else if ($query->no == 16) {
                        return 'HPP Fullcosting Lini 1 (USD)';
                    } else {
                        return '';
                    }
                }
            })->addColumn($key, function ($query) use ($asum) {
                $mat = Material::where('material_code', $query->code)->first();
                $ga = GroupAccountFC::where('group_account_fc', $query->code)->first();

                if ($mat) {
                    //cr
                    $renprod = DB::table("qty_renprod")
                        ->where('qty_renprod.cost_center', $this->cost_center)
                        ->where('qty_renprod.asumsi_umum_id', $asum->id)
                        ->first();

                    if ($renprod) {
                        $cr_ = ConsRate::select(DB::raw('SUM(cons_rate) as cons_rate'))
                            ->where([
                                'cons_rate.plant_code' => $this->plant,
                                'cons_rate.product_code' => $this->produk,
                                'cons_rate.material_code' => $query->code
                            ])->first();

                        $cr = $cr_->cons_rate;
                    } else {
                        $cr = 0;
                    }

                    if ($query->kategori == 1) {
                        //rumus balans
                        if ($this->produk == $query->code) {
                            return 0;
                        } else {
                            //mengambil biaya perton berdasarkan periode, material, dan tersedia
                            // $balans = DB::table("balans")
                            //     ->where('balans.material_code', $query->code)
                            //     ->where('balans.periode', $asum->id)
                            //     ->first();
                            $balans = 0;

                            $biayaperton1 = $balans * $cr * $renprod->qty_renprod_value;
                            return $biayaperton1;
                        }
                    } else if ($query->kategori == 2) {
                        //rumus zco
                        $total_qty = Zco::select(DB::raw('SUM(total_qty) as total_qty'))
                            ->where([
                                'product_code' => $this->produk,
                                'plant_code' => $this->plant,
                                'material_code' => $query->code,
                            ]);

                        $total_biaya = Zco::select(DB::raw('SUM(total_amount) as total_amount'))
                            ->where([
                                'product_code' => $this->produk,
                                'plant_code' => $this->plant,
                                'material_code' => $query->code,
                            ]);

                        $kuantum_produksi = Zco::select(DB::raw('product_qty', 'periode'))
                            ->where([
                                'product_code' => $this->produk,
                                'plant_code' => $this->plant,
                            ])->groupBy('product_qty', 'periode');

                        // if ($this->format == '0') {
                        //     $temp = explode('-', $this->moth);
                        //     $timemonth = $temp[1] . '-' . $temp[0];

                        //     $total_qty->where('periode', 'ilike', '%' . $timemonth . '%');
                        //     $total_biaya->where('periode', 'ilike', '%' . $timemonth . '%');
                        //     $kuantum_produksi->where('periode', 'ilike', '%' . $timemonth . '%');
                        // } else if ($this->format == '1') {
                        //     $start_temp = explode('-', $this->start_month);
                        //     $end_temp = explode('-', $this->end_month);
                        //     $start_date = $start_temp[1] . '-' . $start_temp[0] . '-01 00:00:00';
                        //     $end_date = $end_temp[1] . '-' . $end_temp[0] . '-01 00:00:00';

                        //     $total_qty->whereBetween('periode', [$start_date, $end_date]);
                        //     $total_biaya->whereBetween('periode', [$start_date, $end_date]);
                        //     $kuantum_produksi->whereBetween('periode', [$start_date, $end_date]);
                        // }

                        $total_qty = $total_qty->first();
                        $total_biaya = $total_biaya->first();
                        $kuantum_produksi = $kuantum_produksi->get()->toArray();

                        $tot_kuanprod = 0;

                        for ($i = 0; $i < count($kuantum_produksi); $i++) {
                            $tot_kuanprod = $tot_kuanprod + $kuantum_produksi[$i]['product_qty'];
                        }

                        $biaya_perton = 0;
                        if ($total_biaya->total_amount > 0 && $tot_kuanprod > 0) {
                            $biaya_perton = $total_biaya->total_amount / $tot_kuanprod;
                        }

                        $kp = 0;
                        if ($total_qty->total_qty > 0 && $tot_kuanprod > 0) {
                            $kp = $total_qty->total_qty / $tot_kuanprod;
                        }

                        $harga_satuan = 0;
                        if ($biaya_perton > 0 && $kp > 0) {
                            $harga_satuan = $biaya_perton / $kp;
                        }

                        $biayaperton2 = $harga_satuan * $cr * $renprod->qty_renprod_value;
                        return $biayaperton2;
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

                        $biayaperton3 = $biaya_stok * $cr * $renprod->qty_renprod_value;
                        return $biayaperton3;
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

                        $biayaperton4 = $biaya_kantong * $cr * $renprod->qty_renprod_value;
                        return $biayaperton4;
                    } else {
                        return '';
                    }
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
                    if ($query->no == 5) {
                        return 'Total Bahan Baku';
                    } else if ($query->no == 7) {
                        return 'Total Overhead Langsung';
                    } else if ($query->no == 9) {
                        return 'Total Overhead Tidak Langsung';
                    } else if ($query->no == 10) {
                        return 'COGM';
                    } else if ($query->no == 11) {
                        return 'Biaya Administrasi & Umum';
                    } else if ($query->no == 12) {
                        return 'Biaya Pemasaran';
                    } else if ($query->no == 13) {
                        return 'Biaya Keuangan';
                    } else if ($query->no == 14) {
                        return 'Biaya Periodik';
                    } else if ($query->no == 15) {
                        return 'HPP Fullcosting Lini 1';
                    } else if ($query->no == 16) {
                        return 'HPP Fullcosting Lini 1 (USD)';
                    } else {
                        return '';
                    }
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
