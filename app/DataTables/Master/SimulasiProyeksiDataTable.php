<?php

namespace App\DataTables\Master;

use App\Models\ConsRate;
use App\Models\GroupAccountFC;
use App\Models\LabaRugi;
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

        function hsBalans()
        {
            // if ($this->produk == $query->code) {
            //     return 0;
            // } else {
            //     //mengambil biaya perton berdasarkan periode, material, dan tersedia
            //     // $balans = DB::table("balans")
            //     //     ->where('balans.material_code', $query->code)
            //     //     ->where('balans.periode', $asum->id)
            //     //     ->first();

            //     return 0;
            // }
            return 0;
        }

        function hsZco($produk, $plant, $material)
        {
            $total_qty = Zco::select(DB::raw('SUM(total_qty) as total_qty'))
                ->where([
                    'product_code' => $produk,
                    'plant_code' => $plant,
                    'material_code' => $material,
                ]);

            $total_biaya = Zco::select(DB::raw('SUM(total_amount) as total_amount'))
                ->where([
                    'product_code' => $produk,
                    'plant_code' => $plant,
                    'material_code' => $material,
                ]);

            $kuantum_produksi = Zco::select(DB::raw('product_qty', 'periode'))
                ->where([
                    'product_code' => $produk,
                    'plant_code' => $plant,
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
        }

        function hsStock($material)
        {
            $total_sa = Saldo_Awal::select(DB::raw('SUM(total_value) as total_value'))
                ->where([
                    'material_code' => $material,
                ])->first();

            $stok_sa = Saldo_Awal::select(DB::raw('SUM(total_stock) as total_stock'))
                ->where([
                    'material_code' => $material,
                ])->first();

            if ($total_sa->total_value > 0 && $stok_sa->total_stock > 0) {
                $biaya_stok = $total_sa->total_value / $stok_sa->total_stock;
            } else {
                $biaya_stok = 0;
            }

            return $biaya_stok;
        }

        function hsKantong($material)
        {
            $total_sa = Saldo_Awal::select(DB::raw('SUM(total_value) as total_value'))
                ->where([
                    'material_code' => $material,
                ])->first();

            $stok_sa = Saldo_Awal::select(DB::raw('SUM(total_stock) as total_stock'))
                ->where([
                    'material_code' => $material,
                ])->first();

            if ($total_sa->total_value > 0 && $stok_sa->total_stock > 0) {
                $biaya_kantong = $total_sa->total_value / $stok_sa->total_stock;
            } else {
                $biaya_kantong = 0;
            }

            return $biaya_kantong;
        }

        function kuantumProduksi($cost_center, $periode)
        {
            $renprod = DB::table("qty_renprod")
                ->where('qty_renprod.cost_center', $cost_center)
                ->where('qty_renprod.asumsi_umum_id', $periode)
                ->first();
            return $renprod;
        }

        function consRate($plant, $produk, $material)
        {
            $total_cr = ConsRate::select(DB::raw('SUM(cons_rate) as cons_rate'))
                ->where([
                    'cons_rate.plant_code' => $plant,
                    'cons_rate.product_code' => $produk,
                    'cons_rate.material_code' => $material
                ])->first();

            $cr = $total_cr->cons_rate;
            return $cr;
        }

        function totalSalr($cost_center, $group_account, $inflasi)
        {
            $total = Salr::select(DB::raw('SUM(value) as value'))
                ->leftjoin('gl_account_fc', 'gl_account_fc.gl_account_fc', '=', 'salrs.gl_account_fc')
                ->leftjoin('group_account_fc', 'group_account_fc.group_account_fc', '=', 'gl_account_fc.group_account_fc')
                ->where([
                    'salrs.cost_center' => $cost_center,
                    'group_account_fc.group_account_fc' => $group_account
                ])->first();

            $result = $total->value * $inflasi / 100;
            return $result;
        }

        function labaRugi($produk)
        {
            $lb = DB::table("laba_rugi")
                ->leftjoin('material', 'material.kategori_produk_id', '=', 'laba_rugi.kategori_produk_id')
                ->where('material.material_code', $produk)
                ->first();

            return $lb;
        }

        function totalBB($data, $plant, $produk)
        {
            $res_bb = [];

            foreach ($data as $key => $value) {
                $consrate_bb = consRate($plant, $produk, $value->code);

                if ($value->kategori == 1) {
                    $hs_balans = hsBalans();
                    $biayaperton1 = $hs_balans * $consrate_bb;
                    array_push($res_bb, $biayaperton1);
                } else if ($value->kategori == 2) {
                    $hs_zco = hsZco($produk, $plant, $value->code);
                    $biayaperton2 = $hs_zco * $consrate_bb;
                    array_push($res_bb, $biayaperton2);
                } else if ($value->kategori == 3) {
                    $hs_stock = hsStock($value->code);
                    $biayaperton3 = $hs_stock * $consrate_bb;
                    array_push($res_bb, $biayaperton3);
                } else {
                    $hs_kantong = hsKantong($value->code);
                    $biayaperton4 = $hs_kantong * $consrate_bb;
                    array_push($res_bb, $biayaperton4);
                }
            }

            $res = array_sum($res_bb);
            return $res;
        }

        function totalGL($data, $cost_center, $asum_id, $asum_inflasi)
        {
            $res_gl = [];

            foreach ($data as $key => $value) {
                $salr = DB::table("salrs")
                    ->leftjoin('gl_account_fc', 'gl_account_fc.gl_account_fc', '=', 'salrs.gl_account_fc')
                    ->leftjoin('group_account_fc', 'group_account_fc.group_account_fc', '=', 'gl_account_fc.group_account_fc')
                    ->where('salrs.cost_center', $cost_center)
                    ->where('group_account_fc.group_account_fc', $value->code)
                    ->first();

                if ($salr) {
                    $kp = kuantumProduksi($cost_center, $asum_id);
                    $total = totalSalr($salr->cost_center, $salr->group_account_fc, $asum_inflasi);
                    $biaya_perton = $total / $kp->qty_renprod_value;
                    array_push($res_gl, $biaya_perton);
                }
            }

            $res = array_sum($res_gl);
            return $res;
        }

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
            $datatable->addColumn($key, function ($query) use ($asum) {
                $mat = Material::where('material_code', $query->code)->first();
                $ga = GroupAccountFC::where('group_account_fc', $query->code)->first();

                if ($mat) {
                    if ($query->kategori == 1) {
                        $res = hsBalans();
                        return $res;
                    } else if ($query->kategori == 2) {
                        $res = hsZco($this->produk, $this->plant, $query->code);
                        return $res;
                    } else if ($query->kategori == 3) {
                        $res = hsStock($query->code);
                        return $res;
                    } else if ($query->kategori == 4) {
                        $res = hsKantong($query->code);
                        return $res;
                    } else {
                        return '';
                    }
                } else if ($ga) {
                    return '-';
                } else {
                    return '';
                }
            })->addColumn($key, function ($query) use ($asum) {
                $mat = Material::where('material_code', $query->code)->first();
                $ga = GroupAccountFC::where('group_account_fc', $query->code)->first();

                if ($mat) {
                    $kp = kuantumProduksi($this->cost_center, $asum->id);

                    if ($kp) {
                        $consrate = consRate($this->plant, $this->produk, $query->code);
                    } else {
                        $consrate = 0;
                    }

                    return $consrate;
                } else if ($ga) {
                    return '-';
                } else {
                    return '';
                }
            })->addColumn($key, function ($query) use ($asum, $resBB, $resgaLangsung, $resgatidakLangsung) {
                $mat = Material::where('material_code', $query->code)->first();
                $ga = GroupAccountFC::where('group_account_fc', $query->code)->first();

                if ($mat) {
                    $kp = kuantumProduksi($this->cost_center, $asum->id);

                    if ($kp) {
                        $consrate = consRate($this->plant, $this->produk, $query->code);
                    } else {
                        $consrate = 0;
                    }

                    if ($query->kategori == 1) {
                        if ($this->produk == $query->code) {
                            return 0;
                        } else {
                            $hs_balans = hsBalans();
                            $biayaperton1 = $hs_balans * $consrate;

                            return $biayaperton1;
                        }
                    } else if ($query->kategori == 2) {
                        $hs_zco = hsZco($this->produk, $this->plant, $query->code);
                        $biayaperton2 = $hs_zco * $consrate;

                        return $biayaperton2;
                    } else if ($query->kategori == 3) {
                        $hs_stock = hsStock($query->code);
                        $biayaperton3 = $hs_stock * $consrate;

                        return $biayaperton3;
                    } else if ($query->kategori == 4) {
                        $hs_kantong = hsKantong($query->code);
                        $biayaperton4 = $hs_kantong * $consrate;

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
                        $kp = kuantumProduksi($this->cost_center, $asum->id);
                        $total = totalSalr($salr->cost_center, $salr->group_account_fc, $asum->inflasi);
                        $biaya_perton = $total / $kp->qty_renprod_value;

                        return round($biaya_perton, 4);
                    } else {
                        return '-';
                    }
                } else {
                    if ($query->no == 5) {
                        $res = totalBB($resBB, $this->plant, $this->produk);
                        return $res;
                    } else if ($query->no == 7) {
                        $res = totalGL($resgaLangsung, $this->cost_center,  $asum->id, $asum->inflasi);
                        return $res;
                    } else if ($query->no == 9) {
                        $res = totalGL($resgatidakLangsung, $this->cost_center,  $asum->id, $asum->inflasi);
                        return $res;
                    } else if ($query->no == 10) {
                        $total_bb = totalBB($resBB, $this->plant, $this->produk);
                        $total_gl_langsung = totalGL($resgaLangsung, $this->cost_center,  $asum->id, $asum->inflasi);
                        $total_gl_tidak_langsung = totalGL($resgatidakLangsung, $this->cost_center,  $asum->id, $asum->inflasi);
                        $cogm = $total_bb + $total_gl_langsung + $total_gl_tidak_langsung;

                        return $cogm;
                    } else if ($query->no == 11) {
                        $biaya_admin_umum = labaRugi($this->produk);

                        if ($biaya_admin_umum) {
                            $res = $biaya_admin_umum->value_bau;
                        } else {
                            $res = 0;
                        }
                        return $res;
                    } else if ($query->no == 12) {
                        $biaya_pemasaran = labaRugi($this->produk);

                        if ($biaya_pemasaran) {
                            $res = $biaya_pemasaran->value_bp;
                        } else {
                            $res = 0;
                        }
                        return $res;
                    } else if ($query->no == 13) {
                        $biaya_keuangan = labaRugi($this->produk);

                        if ($biaya_keuangan) {
                            $res = $biaya_keuangan->value_bb;
                        } else {
                            $res = 0;
                        }
                        return $res;
                    } else if ($query->no == 14) {
                        $biaya_periodik = labaRugi($this->produk);

                        if ($biaya_periodik) {
                            $res =  $biaya_periodik->value_bp + $biaya_periodik->value_bau + $biaya_periodik->value_bb;
                        } else {
                            $res = 0;
                        }
                        return $res;
                    } else if ($query->no == 15) {
                        //periodik
                        $biaya_periodik = labaRugi($this->produk);
                        $total_periodik =  $biaya_periodik->value_bp + $biaya_periodik->value_bau + $biaya_periodik->value_bb;

                        //cogm
                        $total_bb = totalBB($resBB, $this->plant, $this->produk);
                        $total_gl_langsung = totalGL($resgaLangsung, $this->cost_center,  $asum->id, $asum->inflasi);
                        $total_gl_tidak_langsung = totalGL($resgatidakLangsung, $this->cost_center,  $asum->id, $asum->inflasi);
                        $total_cogm = $total_bb + $total_gl_langsung + $total_gl_tidak_langsung;

                        $res = $total_cogm + $total_periodik;
                        return $res;
                    } else if ($query->no == 16) {
                        //periodik
                        $biaya_periodik = labaRugi($this->produk);
                        $total_periodik =  $biaya_periodik->value_bp + $biaya_periodik->value_bau + $biaya_periodik->value_bb;

                        //cogm
                        $total_bb = totalBB($resBB, $this->plant, $this->produk);
                        $total_gl_langsung = totalGL($resgaLangsung, $this->cost_center,  $asum->id, $asum->inflasi);
                        $total_gl_tidak_langsung = totalGL($resgatidakLangsung, $this->cost_center,  $asum->id, $asum->inflasi);
                        $total_cogm = $total_bb + $total_gl_langsung + $total_gl_tidak_langsung;

                        $total_hpp = $total_cogm + $total_periodik;
                        $total_hpp_usd = $total_hpp / $asum->usd_rate;

                        return $total_hpp_usd;
                    } else {
                        return '';
                    }
                }
            })->addColumn($key, function ($query) use ($asum, $resBB, $resgaLangsung, $resgatidakLangsung) {
                $mat = Material::where('material_code', $query->code)->first();
                $ga = GroupAccountFC::where('group_account_fc', $query->code)->first();

                if ($mat) {
                    $kp = kuantumProduksi($this->cost_center, $asum->id);

                    if ($kp) {
                        $consrate = consRate($this->plant, $this->produk, $query->code);
                    } else {
                        $consrate = 0;
                    }

                    if ($query->kategori == 1) {
                        if ($this->produk == $query->code) {
                            return 0;
                        } else {
                            $hs_balans = hsBalans();
                            $total_biaya1 = $hs_balans * $consrate * $kp->qty_renprod_value;

                            return $total_biaya1;
                        }
                    } else if ($query->kategori == 2) {
                        $hs_zco = hsZco($this->produk, $this->plant, $query->code);
                        $total_biaya2 = $hs_zco * $consrate * $kp->qty_renprod_value;

                        return $total_biaya2;
                    } else if ($query->kategori == 3) {
                        $hs_stock = hsStock($query->code);
                        $total_biaya3 = $hs_stock * $consrate * $kp->qty_renprod_value;

                        return $total_biaya3;
                    } else if ($query->kategori == 4) {
                        $hs_kantong = hsKantong($query->code);
                        $total_biaya4 = $hs_kantong * $consrate * $kp->qty_renprod_value;

                        return $total_biaya4;
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
                        $res = totalSalr($salr->cost_center, $salr->group_account_fc, $asum->inflasi);
                        return $res;
                    } else {
                        return '-';
                    }
                } else {
                    $kp = kuantumProduksi($this->cost_center, $asum->id);

                    if ($query->no == 5) {
                        $res = totalBB($resBB, $this->plant, $this->produk) * $kp->qty_renprod_value;
                        return $res;
                    } else if ($query->no == 7) {
                        $res = totalGL($resgaLangsung, $this->cost_center,  $asum->id, $asum->inflasi) * $kp->qty_renprod_value;
                        return $res;
                    } else if ($query->no == 9) {
                        $res = totalGL($resgatidakLangsung, $this->cost_center,  $asum->id, $asum->inflasi) * $kp->qty_renprod_value;
                        return $res;
                    } else if ($query->no == 10) {
                        $total_bb = totalBB($resBB, $this->plant, $this->produk) * $kp->qty_renprod_value;
                        $total_gl_langsung = totalGL($resgaLangsung, $this->cost_center,  $asum->id, $asum->inflasi) * $kp->qty_renprod_value;
                        $total_gl_tidak_langsung = totalGL($resgatidakLangsung, $this->cost_center,  $asum->id, $asum->inflasi) * $kp->qty_renprod_value;
                        $cogm = $total_bb + $total_gl_langsung + $total_gl_tidak_langsung;

                        return $cogm;
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
