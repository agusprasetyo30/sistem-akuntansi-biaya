<?php

namespace App\Http\Controllers;

use App\DataTables\Master\SimulasiProyeksiDataTable;
use App\DataTables\Master\SimulasiProyeksiStoreDataTable;
use App\Exports\Horizontal\SimulasiProyeksiExport;
use App\Models\Asumsi_Umum;
use App\Models\Balans;
use App\Models\ConsRate;
use App\Models\GroupAccountFC;
use App\Models\Material;
use App\Models\Plant;
use App\Models\Salr;
use App\Models\SimulasiProyeksi;
use App\Models\TempProyeksi;
use App\Models\Version_Asumsi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Collection\Collection;

class SimulasiProyeksiController extends Controller
{
    public function store(Request $request)
    {
        $this->hitung_simpro($request->version);
        // $this->hitung_satuan_simpro(1, 'B001', 'MATERIAL 10');
    }

    public function hitung_simpro($version)
    {
        try {
            $balans = Balans::select('material_code')
                ->where('version_id', $version)
                ->where('kategori_balans_id', '>', 6)
                ->groupBy('material_code')->get()
                ->pluck('material_code')
                ->all();

            $cons_rate = ConsRate::with(
                ['glos_cc1']
            )
                ->select('product_code', 'plant_code', 'version_id')
                ->where('version_id', $version)
                //                ->whereNotIn('product_code', $balans)
                ->groupBy('product_code', 'plant_code', 'version_id')
                ->get();

//            dd($cons_rate);

            $asumsi = Version_Asumsi::with('asumsi_umum:id,version_id,month_year,saldo_awal,usd_rate,adjustment,inflasi')
                ->select('id', 'version')
                ->where([
                    'id' => $version,
                    'company_code' => auth()->user()->company_code
                ])->first();

            $collection_input_temp = collect();


            foreach ($cons_rate as $key1 => $cr) {
                $data_version = $cr->version_id;
                $data_plant = $cr->plant_code;
                $data_product = $cr->product_code;
                $data_cost_center = $cr->glos_cc1->cost_center;

                $lb = Material::select('kategori_produk_id')->where('material_code', $data_product)->first();

                $group_account = GroupAccountFC::select(
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
                    "group_account_fc.group_account_fc as material_code",
                )
                    ->addSelect(DB::raw("'ga' as jenis "))
                    ->addSelect(DB::raw("'$data_product' as data_produk "))
                    ->addSelect(DB::raw("'$lb->kategori_produk_id' as kategori_produk "));

                $temp_pro = TempProyeksi::select(
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
                    "temp_proyeksi.proyeksi_name as material_code",
                )
                    ->addSelect(DB::raw("'template' as jenis "))
                    ->addSelect(DB::raw("'$data_product' as data_produk "))
                    ->addSelect(DB::raw("'$lb->kategori_produk_id' as kategori_produk "))
                    ->union($group_account);

                $query = Material::select(
                    DB::raw("(
                    CASE
                        WHEN kategori_material_id = 1 THEN 1
                        WHEN kategori_material_id = 2 THEN 3
                        WHEN kategori_material_id = 3 THEN 2
                        WHEN kategori_material_id = 4 THEN 4
                        ELSE 0 END)
                    AS no"),
                    "kategori_material_id as kategori",
                    "material_name as name",
                    "material_code as code",
                    "material_code as material_code",
                )->whereHas('const_rate', function ($cr_cek) use ($data_product, $data_plant) {
                    $cr_cek->where('product_code', '=', $data_product)
                        ->where('plant_code', '=', $data_plant);
                })
                    ->addSelect(DB::raw("'material' as jenis "))
                    ->addSelect(DB::raw("'$data_product' as data_produk "))
                    ->addSelect(DB::raw("'$lb->kategori_produk_id' as kategori_produk "))
                    ->union($temp_pro)
                    ->orderBy('no', 'asc')
                    ->orderBy('kategori', 'asc')
                    ->get();
//                 dd($query->toArray());

                // $asumsi = Asumsi_Umum::where('version_id', $data_version)
                //     ->get();

//                dd($query)
                foreach ($asumsi->asumsi_umum as $key2 => $asum) {
                    $hs = 0;
                    $consrate = 0;
                    $biaya_perton = 0;
                    $total_biaya = 0;
                    $kuan_prod = 0;
                    $periode = $asum->id;
                    $inflasi = $asum->inflasi ?? 0;

                    foreach ($query as $key3 => $val) {
                        if ($val->jenis == 'material') {
                            //ConsRate

                            $kp_v = $val->kpValue($periode, $data_product, $data_plant);

                            if ($kp_v != 0) {
                                //                        if ($kp) {
                                // dd($kp);
                                // $kp_v = (float) $kp[0]->glos_cc->renprod[0]->qty_renprod_value;
                                // $kp_v = (float) $val->kpValue($periode);

                                $kuan_prod = $kp_v;
                                //Data Dummy
                                // $kp_v = 2;

                                if ($kp_v == 1) {
                                    $consrate = 0;
                                } else {
//                                    dd($data_version, $data_plant, $asum->month_year);
                                    $consrate = $val->consRate($data_version, $data_plant, $asum->month_year) ?? 0;
                                }
                                $kp_val = $kp_v;
                            } else {
                                $consrate = 0;
                            }


                            //Harga Satuan dan Biaya Perton
                            if ($val->kategori == 1) {
                                //Harga Satuan
                                $res = $val->hsBalans($periode, $val->code, $data_product);
                                $hs = $res;
                                // dd($hs);
                                //Biaya Perton
                                if ($data_product == $val->code) {
                                    $biaya_perton = 0;
                                } else {
                                    $biaya_perton = $hs * $consrate;

                                    //Total Biaya
                                    $total_biaya = $biaya_perton * $kp_val;
                                }
                            } else if ($val->kategori == 2) {
                                //Harga Satuan
                                $res = $val->hsZco($data_plant);
                                $hs = $res;

                                //Biaya Perton
                                $biaya_perton = $hs * $consrate;

                                //Total Biaya
                                $total_biaya = $biaya_perton * $kp_val;
                            } else if ($val->kategori == 3) {
                                //Harga Satuan
                                $res = $val->hsStock($data_version);
                                $hs = $res;

                                //Biaya Perton
                                $biaya_perton = $hs * $consrate;

                                //Total Biaya
                                $total_biaya = $biaya_perton * $kp_val;
                            } else if ($val->kategori == 4) {
                                //Harga Satuan
                                $res = $val->hsKantong($data_version);
                                $hs = $res;

                                //Biaya Perton
                                $biaya_perton = $hs * $consrate;

                                //Total Biaya
                                $total_biaya = $biaya_perton * $kp_val;
                            } else {
                                //Harga Satuan
                                $hs = 0;

                                //Biaya Perton
                                $biaya_perton = 0;

                                //Total Biaya
                                $total_biaya = 0;
                            }
                        } else if ($val->jenis == 'ga') {
                            $hs = 0;
                            $consrate = 0;

                            $tar = $val->gaTarif($val->code, $data_plant);
                            $salr = $val->getSalr($data_cost_center, $val->code);

                            $temp_perton = 0;
                            $temp_total = 0;

                            foreach ($salr as $k => $value) {
                                $sumval = $value->salr->sum('value');
                                if ($sumval != 0) {
                                    $temp_perton += $sumval;
                                    $temp_total += $sumval;
                                } else {
                                    $biaya_perton = 0;
                                    $total_biaya = 0;
                                }
                            }


                            if ($temp_total != 0 && $temp_perton != 0) {
                                if ($inflasi == 0) {
                                    $total_biaya = $temp_total + $tar;
                                    if ($kuan_prod != 0){
                                        $biaya_perton = $total_biaya / $kuan_prod;
                                    }else{
                                        $biaya_perton = 0;
                                    }
                                } else {
                                    $total_biaya = ($temp_total * ($inflasi / 100)) + $tar;
                                    if ($kuan_prod != 0){
                                        $biaya_perton = $total_biaya / $kuan_prod;
                                    }else{
                                        $biaya_perton = 0;
                                    }
                                }
                            } else {
                                $biaya_perton = 0;
                                $total_biaya = 0;
                            }
                        } else {
                            //Harga Satuan
                            $hs = 0;

                            //ConsRate
                            $consrate = 0;


                            //Biaya Perton dan Total Biaya
                            if ($val->no == 5) {

                                $res_biaya_perton = $collection_input_temp
                                    ->where('product_code', $data_product)
                                    ->where('kategori', '!=', 0)
                                    ->where('plant_code', $data_plant)
                                    ->whereIn('no', [1, 2, 3, 4])
                                    ->where('asumsi_umum_id', '=', $periode)
                                    ->sum('biaya_perton');

                                $res_total_biaya = $collection_input_temp
                                    ->where('product_code', $data_product)
                                    ->where('kategori', '!=', 0)
                                    ->where('plant_code', $data_plant)
                                    ->whereIn('no', [1, 2, 3, 4])
                                    ->where('asumsi_umum_id', '=', $periode)
                                    ->sum('total_biaya');


                                $biaya_perton = $res_biaya_perton;
                                $total_biaya = $res_total_biaya;
                            } else if ($val->no == 7) {
                                $res_biaya_perton = $collection_input_temp
                                    ->where('product_code', $data_product)
                                    ->whereIn('no', [6])
                                    ->where('kategori', '!=', 0)
                                    ->where('plant_code', $data_plant)
                                    ->where('asumsi_umum_id', '=', $periode)
                                    ->sum('biaya_perton');
                                $res_total_biaya = $collection_input_temp
                                    ->where('product_code', $data_product)
                                    ->whereIn('no', [6])
                                    ->where('kategori', '!=', 0)
                                    ->where('plant_code', $data_plant)
                                    ->where('asumsi_umum_id', '=', $periode)
                                    ->sum('total_biaya');

                                $biaya_perton = $res_biaya_perton;
                                $total_biaya = $res_total_biaya;
                            } else if ($val->no == 9) {
                                $res_biaya_perton = $collection_input_temp
                                    ->where('product_code', $data_product)
                                    ->whereIn('no', [8])
                                    ->where('kategori', '!=', 0)
                                    ->where('plant_code', $data_plant)
                                    ->where('asumsi_umum_id', '=', $periode)
                                    ->sum('biaya_perton');
                                $res_total_biaya = $collection_input_temp
                                    ->where('product_code', $data_product)
                                    ->whereIn('no', [8])
                                    ->where('kategori', '!=', 0)
                                    ->where('plant_code', $data_plant)
                                    ->where('asumsi_umum_id', '=', $periode)
                                    ->sum('total_biaya');

                                $biaya_perton = $res_biaya_perton;
                                $total_biaya = $res_total_biaya;
                            } else if ($val->no == 10) {
                                $res_biaya_perton = $collection_input_temp
                                    ->where('product_code', $data_product)
                                    ->whereIn('no', [1, 2, 3, 4, 6, 8])
                                    ->where('kategori', '!=', 0)
                                    ->where('plant_code', $data_plant)
                                    ->where('asumsi_umum_id', '=', $periode)
                                    ->sum('biaya_perton');
                                $res_total_biaya = $collection_input_temp
                                    ->where('product_code', $data_product)
                                    ->whereIn('no', [1, 2, 3, 4, 6, 8])
                                    ->where('kategori', '!=', 0)
                                    ->where('plant_code', $data_plant)
                                    ->where('asumsi_umum_id', '=', $periode)
                                    ->sum('total_biaya');

                                $biaya_perton = $res_biaya_perton;
                                $total_biaya = $res_total_biaya;
                            } else if ($val->no == 11) {
                                $biaya_admin_umum = $val->getLabarugi();
                                $res = 0;

                                if ($biaya_admin_umum) {
                                    $res = $biaya_admin_umum->value_bau;
                                }

                                $biaya_perton = $res;
                                $total_biaya = 0;
                            } else if ($val->no == 12) {
                                $biaya_pemasaran = $val->getLabarugi();
                                $res = 0;

                                if ($biaya_pemasaran) {
                                    $res = $biaya_pemasaran->value_bp;
                                }

                                $biaya_perton = $res;
                                $total_biaya = 0;
                            } else if ($val->no == 13) {
                                $biaya_keuangan = $val->getLabarugi();
                                $res = 0;

                                if ($biaya_keuangan) {
                                    $res = $biaya_keuangan->value_bb;
                                }

                                $biaya_perton = $res;
                                $total_biaya = 0;
                            } else if ($val->no == 14) {
                                $res_biaya_perton = $collection_input_temp
                                    ->where('product_code', $data_product)
                                    ->whereIn('no', [11, 12, 13])
                                    ->where('plant_code', $data_plant)
                                    ->where('asumsi_umum_id', '=', $periode)
                                    ->sum('biaya_perton');

                                $biaya_perton = $res_biaya_perton;
                                $total_biaya = 0;
                            } else if ($val->no == 15) {
                                $res_biaya_perton = $collection_input_temp
                                    ->where('product_code', $data_product)
                                    ->where('plant_code', $data_plant)
                                    ->whereIn('no', [10, 14])
                                    ->where('asumsi_umum_id', '=', $periode)
                                    ->sum('biaya_perton');

                                $biaya_perton = $res_biaya_perton;
                                $total_biaya = 0;
                            } else if ($val->no == 16) {
                                $res_biaya_perton = $collection_input_temp->where('product_code', $data_product)->whereIn('no', [15])->where('asumsi_umum_id', '=', $periode)->sum('biaya_perton');
                                $kurs = $asum->usd_rate ?? 0;
                                if ($res_biaya_perton > 0 && $kurs > 0) {
                                    $total_hpp_usd = $res_biaya_perton / $kurs;
                                } else {
                                    $total_hpp_usd = 0;
                                }

                                $biaya_perton = $total_hpp_usd;
                                $total_biaya = 0;
                            } else {
                                $biaya_perton = 0;
                                $total_biaya = 0;
                            }
                        }

                        $collection_input_temp->push($this->submit_data($data_version, $data_plant, $data_product, $data_cost_center, $hs, $consrate, $biaya_perton, $total_biaya, $periode, $val->no, $val->kategori, $val->name, $val->code, $kuan_prod));
                    }


                }
            }
//            dd($collection_input_temp->where('product_code', '2000001')->where('plant_code', 'B019'));
//            dd($collection_input_temp[0]['product_code']);
            $chunk = array_chunk($collection_input_temp->toArray(), 500);
            foreach ($chunk as $insert) {
                SimulasiProyeksi::insert($insert);
            }
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function hitung_satuan_simpro($version, $asumsi, $plant, $product, $cost_center, $collections)
    {
        // dd($asumsi);
        try {

            $collection_input_temp = collect();

            $data_version = $version;
            $data_plant = $plant;
            $data_product = $product;
            $data_cost_center = $cost_center;

            $lb = Material::select('kategori_produk_id')->where('material_code', $data_product)->first();

            $group_account = GroupAccountFC::select(
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
                "group_account_fc.group_account_fc as material_code",
            )
                ->addSelect(DB::raw("'ga' as jenis "))
                ->addSelect(DB::raw("'$data_product' as data_produk "))
                ->addSelect(DB::raw("'$lb->kategori_produk_id' as kategori_produk "));

            $temp_pro = TempProyeksi::select(
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
                "temp_proyeksi.proyeksi_name as material_code",
            )
                ->addSelect(DB::raw("'template' as jenis "))
                ->addSelect(DB::raw("'$data_product' as data_produk "))
                ->addSelect(DB::raw("'$lb->kategori_produk_id' as kategori_produk "))
                ->union($group_account);

            $query = Material::select(
                DB::raw("(
                    CASE
                        WHEN kategori_material_id = 1 THEN 1
                        WHEN kategori_material_id = 2 THEN 3
                        WHEN kategori_material_id = 3 THEN 2
                        WHEN kategori_material_id = 4 THEN 4
                        ELSE 0 END)
                    AS no"),
                "kategori_material_id as kategori",
                "material_name as name",
                "material_code as code",
                "material_code as material_code",
            )->whereHas('const_rate', function ($cr_cek) use ($data_product, $data_plant) {
                $cr_cek->where('product_code', '=', $data_product)
                    ->where('plant_code', '=', $data_plant);
            })
                ->addSelect(DB::raw("'material' as jenis "))
                ->addSelect(DB::raw("'$data_product' as data_produk "))
                ->addSelect(DB::raw("'$lb->kategori_produk_id' as kategori_produk "))
                ->union($temp_pro)
                ->orderBy('no', 'asc')
                ->with(['const_rate.glos_cc.renprod', 'tarif'])
                ->orderBy('kategori', 'asc')
                ->get();

            $hs = 0;
            $consrate = 0;
            $biaya_perton = 0;
            $total_biaya = 0;
            $kuan_prod = 0;
            $periode = $asumsi->id;
            $inflasi = $asumsi->inflasi ?? 0;
            foreach ($query as $key3 => $val) {
                $kp_val = 0;
                if ($val->jenis == 'material') {
                    $kp_v = $val->kpValue($periode, $data_product, $data_plant);
                    if ($kp_v != 0) {
                        $kuan_prod = $kp_v;
                        if ($kp_v == 1) {
                            $consrate = 0;
                        } else {
                            $consrate = $val->consRate($data_version, $data_plant, $asumsi->month_year) ?? 0;
                        }
                        $kp_val = $kp_v;
                    } else {
                        $consrate = 0;
                    }
                    //Harga Satuan dan Biaya Perton
                    if ($val->kategori == 1) {
                        //Harga Satuan



                        if ($val->code != $data_product){
                            $balans = $collections
                                ->where('kategori_balans_id', 3)
                                ->where('material_code', $val->code)
                                ->where('asumsi_umum_id', $periode)->first();

                            try {
                                if ($balans != null){
                                    $res = $balans['p'];
                                }else{
                                    $res = 0;
                                }
                            }catch (\Exception $exception){
                                $res = 0;
                            }
                        }else{
                            $res = 0;
                        }

                        $hs = $res;
                        // dd($hs);
                        //Biaya Perton
                        if ($data_product == $val->code) {
                            $biaya_perton = 0;
                        } else {
                            $biaya_perton = $hs * $consrate;

                            //Total Biaya
                            $total_biaya = $biaya_perton * $kp_val;
                        }
                    } else if ($val->kategori == 2) {
                        //Harga Satuan
//                        dd($data_plant);
                        $res = $val->hsZco($data_plant);
                        $hs = $res;

                        //Biaya Perton
                        $biaya_perton = $hs * $consrate;

                        //Total Biaya
                        $total_biaya = $biaya_perton * $kp_val;
                    } else if ($val->kategori == 3) {
                        //Harga Satuan
                        $res = $val->hsStock($data_version);
                        $hs = $res;

                        //Biaya Perton
                        $biaya_perton = $hs * $consrate;

                        //Total Biaya
                        $total_biaya = $biaya_perton * $kp_val;
                    } else if ($val->kategori == 4) {
                        //Harga Satuan
                        $res = $val->hsKantong($data_version);
                        $hs = $res;

                        //Biaya Perton
                        $biaya_perton = $hs * $consrate;

                        //Total Biaya
                        $total_biaya = $biaya_perton * $kp_val;
                    } else {
                        //Harga Satuan
                        $hs = 0;

                        //Biaya Perton
                        $biaya_perton = 0;

                        //Total Biaya
                        $total_biaya = 0;
                    }
                } else if ($val->jenis == 'ga') {
                    $hs = 0;
                    $consrate = 0;

                    $tar = $val->gaTarif($val->code, $data_plant);
                    $salr = $val->getSalr($data_cost_center, $val->code);

                    $temp_perton = 0;
                    $temp_total = 0;

                    foreach ($salr as $k => $value) {
                        $sumval = $value->salr->sum('value');
                        if ($sumval != 0) {
                            $temp_perton += $sumval;
                            $temp_total += $sumval;
                        } else {
                            $biaya_perton = 0;
                            $total_biaya = 0;
                        }
                    }

                    if ($temp_total != 0 && $temp_perton != 0) {
                        if ($inflasi == 0) {
                            $total_biaya = $temp_total + $tar;
                            if ($kuan_prod != 0){
                                $biaya_perton = $total_biaya / $kuan_prod;
                            }else{
                                $biaya_perton = 0;
                            }

                        } else {
                            $total_biaya = ($temp_total * ($inflasi / 100)) + $tar;
                            if ($kuan_prod != 0){
                                $biaya_perton = $total_biaya / $kuan_prod;
                            }else{
                                $biaya_perton = 0;
                            }
                        }
                    } else {
                        $biaya_perton = 0;
                        $total_biaya = 0;
                    }
                } else {
                    //Harga Satuan
                    $hs = 0;

                    //ConsRate
                    $consrate = 0;

                    //Biaya Perton dan Total Biaya
                    if ($val->no == 5) {
                        $res_biaya_perton = $collection_input_temp
                            ->where('product_code', $data_product)
                            ->where('kategori', '!=', 0)
                            ->whereIn('no', [1, 2, 3, 4])
                            ->where('plant_code', $data_plant)
                            ->where('asumsi_umum_id', '=', $periode)
                            ->sum('biaya_perton');
                        $res_total_biaya = $collection_input_temp
                            ->where('product_code', $data_product)
                            ->where('kategori', '!=', 0)
                            ->where('plant_code', $data_plant)
                            ->whereIn('no', [1, 2, 3, 4])
                            ->where('asumsi_umum_id', '=', $periode)
                            ->sum('total_biaya');

                        $biaya_perton = $res_biaya_perton;
                        $total_biaya = $res_total_biaya;
                    } else if ($val->no == 7) {
                        $res_biaya_perton = $collection_input_temp
                            ->where('product_code', $data_product)
                            ->whereIn('no', [6])
                            ->where('kategori', '!=', 0)
                            ->where('plant_code', $data_plant)
                            ->where('asumsi_umum_id', '=', $periode)
                            ->sum('biaya_perton');
                        $res_total_biaya = $collection_input_temp
                            ->where('product_code', $data_product)
                            ->whereIn('no', [6])
                            ->where('kategori', '!=', 0)
                            ->where('plant_code', $data_plant)
                            ->where('asumsi_umum_id', '=', $periode)
                            ->sum('total_biaya');

                        $biaya_perton = $res_biaya_perton;
                        $total_biaya = $res_total_biaya;
                    } else if ($val->no == 9) {
                        $res_biaya_perton = $collection_input_temp
                            ->where('product_code', $data_product)
                            ->whereIn('no', [8])
                            ->where('kategori', '!=', 0)
                            ->where('plant_code', $data_plant)
                            ->where('asumsi_umum_id', '=', $periode)
                            ->sum('biaya_perton');
                        $res_total_biaya = $collection_input_temp
                            ->where('product_code', $data_product)
                            ->whereIn('no', [8])
                            ->where('kategori', '!=', 0)
                            ->where('plant_code', $data_plant)
                            ->where('asumsi_umum_id', '=', $periode)
                            ->sum('total_biaya');

                        $biaya_perton = $res_biaya_perton;
                        $total_biaya = $res_total_biaya;
                    } else if ($val->no == 10) {
                        $res_biaya_perton = $collection_input_temp
                            ->where('kategori', '!=', 0)
                            ->where('product_code', $data_product)
                            ->whereIn('no', [1, 2, 3, 4, 6, 8])
                            ->where('plant_code', $data_plant)
                            ->where('asumsi_umum_id', '=', $periode)
                            ->sum('biaya_perton');
                        $res_total_biaya = $collection_input_temp
                            ->where('kategori', '!=', 0)
                            ->where('product_code', $data_product)
                            ->whereIn('no', [1, 2, 3, 4, 6, 8])
                            ->where('plant_code', $data_plant)
                            ->where('asumsi_umum_id', '=', $periode)
                            ->sum('total_biaya');

                        $biaya_perton = $res_biaya_perton;
                        $total_biaya = $res_total_biaya;
                    } else if ($val->no == 11) {
                        $biaya_admin_umum = $val->getLabarugi();
                        $res = 0;

                        if ($biaya_admin_umum) {
                            $res = $biaya_admin_umum->value_bau;
                        }

                        $biaya_perton = $res;
                        $total_biaya = 0;
                    } else if ($val->no == 12) {
                        $biaya_pemasaran = $val->getLabarugi();
                        $res = 0;

                        if ($biaya_pemasaran) {
                            $res = $biaya_pemasaran->value_bp;
                        }

                        $biaya_perton = $res;
                        $total_biaya = 0;
                    } else if ($val->no == 13) {
                        $biaya_keuangan = $val->getLabarugi();
                        $res = 0;

                        if ($biaya_keuangan) {
                            $res = $biaya_keuangan->value_bb;
                        }

                        $biaya_perton = $res;
                        $total_biaya = 0;
                    } else if ($val->no == 14) {
                        $res_biaya_perton = $collection_input_temp->where('product_code', $data_product)->whereIn('no', [11, 12, 13])->where('asumsi_umum_id', '=', $periode)->sum('biaya_perton');

                        $biaya_perton = $res_biaya_perton;
                        $total_biaya = 0;
                    } else if ($val->no == 15) {
                        $res_biaya_perton = $collection_input_temp->where('product_code', $data_product)->whereIn('no', [10, 14])->where('asumsi_umum_id', '=', $periode)->sum('biaya_perton');

                        $biaya_perton = $res_biaya_perton;
                        $total_biaya = 0;
                    } else if ($val->no == 16) {
                        $res_biaya_perton = $collection_input_temp->where('product_code', $data_product)->whereIn('no', [15])->where('asumsi_umum_id', '=', $periode)->sum('biaya_perton');
                        $kurs = $asumsi->usd_rate ?? 0;
                        if ($res_biaya_perton > 0 && $kurs > 0) {
                            $total_hpp_usd = $res_biaya_perton / $kurs;
                        } else {
                            $total_hpp_usd = 0;
                        }

                        $biaya_perton = $total_hpp_usd;
                        $total_biaya = 0;
                    } else {
                        $biaya_perton = 0;
                        $total_biaya = 0;
                    }
                }
                $collection_input_temp->push($this->submit_data($data_version, $data_plant, $data_product, $data_cost_center, $hs, $consrate, $biaya_perton, $total_biaya, $periode, $val->no, $val->kategori, $val->name, $val->code, $kuan_prod));
//                dd($collection_input_temp);
            }
            // }

//             dd($collection_input_temp);
            $chunk = array_chunk($collection_input_temp->toArray(), 500);
            foreach ($chunk as $insert) {
                SimulasiProyeksi::insert($insert);
            }
        } catch (\Exception $exception) {
//            dd($exception);
            return setResponse([
                'code' => 400,
            ]);
        }
    }


    public function submit_data($data_version, $data_plant, $data_product, $data_cost_center, $hs, $consrate, $biaya_perton, $total_biaya, $periode, $no, $kategori, $name, $code, $kuan_prod)
    {
        $input['version_id'] = $data_version;
        $input['plant_code'] = $data_plant;
        $input['product_code'] = $data_product;
        $input['cost_center'] = $data_cost_center;
        $input['asumsi_umum_id'] = $periode;
        $input['no'] = $no;
        $input['kategori'] = $kategori;
        $input['name'] = $name;
        $input['code'] = $code;
        $input['harga_satuan'] = (float) $hs;
        $input['cr'] = (float) $consrate;
        $input['biaya_perton'] = (float) $biaya_perton;
        $input['total_biaya'] = (float) $total_biaya;
        $input['kuantum_produksi'] = (float) $kuan_prod;
        $input['created_by'] = auth()->user()->id;
        $input['created_at'] = Carbon::now()->format('Y-m-d');
        $input['updated_at'] = Carbon::now()->format('Y-m-d');

        return $input;
    }

    public function index(Request $request, SimulasiProyeksiDataTable $simulasiproyeksiDatatable)
    {
        try {
            if ($request->data == 'index') {
                $glos_cc = DB::table('glos_cc')
                    ->where('glos_cc.material_code', $request->produk)
                    ->where('glos_cc.plant_code', $request->plant)
                    ->first();

//                dd($glos_cc->cost_center,$request->version,$request->plant, $request->produk);

                return $simulasiproyeksiDatatable->with(['version' => $request->version, 'plant' => $request->plant, 'produk' => $request->produk, 'cost_center' => $glos_cc->cost_center, 'save' => false])->render('pages.simulasi_proyeksi.index');
            }
            return view('pages.simulasi_proyeksi.index');
        } catch (\Throwable $th) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function index_header(Request $request)
    {
        try {
            // $validator = Validator::make($request->all(), [
            //     "version" => 'required',
            //     "produk" => 'required',
            //     "plant" => 'required',
            // ], validatorMsg());

            // if ($validator->fails())
            //     return $this->makeValidMsg($validator);

            $produk = DB::table('material')
                ->where('material_code', $request->produk)
                ->whereNull('deleted_at')
                ->get();

            $plant = DB::table('plant')
                ->where('plant_code', $request->plant)
                ->whereNull('deleted_at')
                ->get();

            $asumsi = Asumsi_Umum::where('version_id', $request->version)->get();

            $kp = SimulasiProyeksi::select('asumsi_umum_id', 'kuantum_produksi')
                ->where('product_code', $request->produk)
                ->where('plant_code', $request->plant)
                ->where('kategori', 1)
                ->groupBy('asumsi_umum_id', 'kuantum_produksi')
                ->orderBy('asumsi_umum_id', 'asc')
                ->get();

            $glos_cc = DB::table('glos_cc')
                ->where('glos_cc.material_code', $request->produk)
                ->where('glos_cc.plant_code', $request->plant)
                ->first();

            if (!$glos_cc) {
                return setResponse([
                    'code' => 430,
                    'title' => 'Gagal menampilkan data',
                    'message' => 'Data cost center dari produk ' . $request->produk . ' dan plant ' . $request->plant . ' tidak ditemukan!',
                ]);
            }

            // $simpro = DB::table('simulasi_proyeksi')
            //     ->where('simulasi_proyeksi.product_code', $request->produk)
            //     ->where('simulasi_proyeksi.plant_code', $request->plant)
            //     ->where('simulasi_proyeksi.cost_center', $glos_cc->cost_center)
            //     ->first();

            // if (!$simpro) {
            //     return setResponse([
            //         'code' => 430,
            //         'title' => 'Gagal menampilkan data',
            //         'message' => 'Data simulasi proyeksi tidak ditemukan!',
            //     ]);
            // }

            return response()->json(['code' => 200, 'asumsi' => $asumsi, 'produk' => $produk, 'plant' => $plant, 'kuantum_produksi' => $kp]);
        } catch (\Throwable $th) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function store2(Request $request)
    {
        try {
            $cons_rate = DB::table('cons_rate')
                ->select('cons_rate.product_code', 'cons_rate.plant_code', 'glos_cc.cost_center', 'cons_rate.version_id')
                ->leftJoin('glos_cc', 'glos_cc.material_code', '=', 'cons_rate.product_code')
                ->where('cons_rate.version_id', $request->version)
                ->groupBy('cons_rate.product_code', 'cons_rate.plant_code', 'glos_cc.cost_center', 'cons_rate.version_id')
                ->get();

            DB::transaction(function () use ($cons_rate, $request) {
                SimulasiProyeksi::where('version_id', $request->version)->delete();
                foreach ($cons_rate as $key => $cr) {
                    $data = new SimulasiProyeksiStoreDataTable();
                    $data->dataTable($cr->version_id, $cr->plant_code, $cr->product_code, $cr->cost_center);
                }
            });

            // return response()->json(['code' => 200]);
        } catch (\Exception $exception) {
            return response()->json(['code' => 500]);
        }
    }

    public function export(Request $request)
    {
        $produk = Material::where('material_code', $request->produk)
            ->whereNull('deleted_at')
            ->get();

        $plant = Plant::where('plant_code', $request->plant)
            ->whereNull('deleted_at')
            ->get();

        $asumsi = Asumsi_Umum::where('version_id',  $request->version)->get();

        $kp = SimulasiProyeksi::select('asumsi_umum_id', 'kuantum_produksi')
                ->where('product_code', $request->produk)
                ->where('plant_code', $request->plant)
                ->where('kategori', 1)
                ->groupBy('asumsi_umum_id', 'kuantum_produksi')
                ->orderBy('asumsi_umum_id', 'asc')
                ->get();
        
        $glos_cc = DB::table('glos_cc')
            ->where('glos_cc.material_code', $request->produk)
            ->where('glos_cc.plant_code', $request->plant)
            ->first();

        $simproValues = DB::table('simulasi_proyeksi')
            ->where('plant_code', $request->plant)
            ->where('product_code', $request->produk)
            ->where('cost_center', $glos_cc->cost_center)
            ->whereIn('asumsi_umum_id', $asumsi->pluck('id')->all())
            ->get();
        // dd($simproValues);

        $query_simulasi_proyeksi = DB::table('simulasi_proyeksi')
            ->select('simulasi_proyeksi.no', 'simulasi_proyeksi.kategori', 'simulasi_proyeksi.name', 'simulasi_proyeksi.code')
            ->where('simulasi_proyeksi.version_id', $request->version)
            ->where('simulasi_proyeksi.plant_code', $request->plant)
            ->where('simulasi_proyeksi.product_code', $request->produk)
            ->where('simulasi_proyeksi.cost_center', $glos_cc->cost_center)
            ->groupBy('simulasi_proyeksi.no', 'simulasi_proyeksi.kategori', 'simulasi_proyeksi.name', 'simulasi_proyeksi.code')
            ->orderBy('no', 'asc')
            ->orderBy('kategori', 'asc')->get();


        $temporary_value['harga_satuan'] = [];
        $temporary_value['cr'] = [];
        $temporary_value['biaya_per_ton'] = [];
        $temporary_value['total_biaya'] = [];

        // Dibuat variabel index temporary dikarenakan case nya ada index yang tidak diawali dengan 0
        $key_temp = 0;
        
        foreach ($query_simulasi_proyeksi as $key => $data_query) {
            foreach ($asumsi as $key1 => $data_asumsi) {
                array_push($temporary_value['harga_satuan'], ['key' => $key_temp, 'value' => $this->hargaSatuanCount($data_query, $simproValues, $data_asumsi)]);
                array_push($temporary_value['cr'], ['key' => $key_temp, 'value' => $this->crCount($data_query, $simproValues, $data_asumsi)]);
                array_push($temporary_value['biaya_per_ton'], ['key' => $key_temp, 'value' => $this->biayaPerTonCount($data_query, $simproValues, $data_asumsi)]);
                array_push($temporary_value['total_biaya'], ['key' => $key_temp, 'value' => $this->totalBiayaCount($data_query, $simproValues, $data_asumsi)]);
                
                $key_temp++;
            }

            $key_temp = 0;
        }

        // Menghitung jumlah total asumsi umum sebagai acuan index
        $asumsi_index_count = $asumsi->count() - 1;

        $fixed_value['harga_satuan'] = getSeparateValue($temporary_value['harga_satuan'], $asumsi_index_count);
        $fixed_value['cr'] = getSeparateValue($temporary_value['cr'], $asumsi_index_count);
        $fixed_value['biaya_per_ton'] = getSeparateValue($temporary_value['biaya_per_ton'], $asumsi_index_count);
        $fixed_value['total_biaya'] = getSeparateValue($temporary_value['total_biaya'], $asumsi_index_count);

        // return response()->json( $query_simulasi_proyeksi);

        // dd($temporary_value['harga_satuan'], $fixed_value['harga_satuan'], count($temporary_value['harga_satuan']));
        // return response()->json($fixed_value['harga_satuan']);

        $data = [
            'product' => $produk,
            'plant'   => $plant,
            'asumsi'  => $asumsi,
            'kp'      => $kp,
            'query'   => $query_simulasi_proyeksi,
            'fixed_value_data' => $fixed_value,
        ];

        // return view('pages.kontrol_proyeksi.export', $data);
        return Excel::download(new SimulasiProyeksiExport($data), "Simulasi Proyeksi Horizontal.xlsx");
    }

    /**
     * Undocumented function
     *
     * @param [type] $query
     * @param [type] $simproValues
     * @param [type] $asumsi
     * @return void
     */
    public function hargaSatuanCount($query, $simproValues, $asumsi)
    {
        $mat = Material::where('material_code', $query->code)->first();
        $ga = GroupAccountFC::where('group_account_fc', $query->code)->first();

        if ($mat) {
            $simproAsumsi = $simproValues
                ->where('asumsi_umum_id', $asumsi->id)
                ->where('name', $query->name)
                ->first();

            return $simproAsumsi->harga_satuan;
        } else if ($ga) {
            return '-';
            // return '0';
        } else {
            // return '0';
            return '';
        }
    }

    public function crCount($query, $simproValues, $asumsi)
    {
        $mat = Material::where('material_code', $query->code)->first();
        $ga = GroupAccountFC::where('group_account_fc', $query->code)->first();

        if ($mat) {
            $simproAsumsi = $simproValues
                ->where('asumsi_umum_id', $asumsi->id)
                ->where('name', $query->name)
                ->first();

            // return round($simproAsumsi->cr, 4);
            return $simproAsumsi->cr;
        } else if ($ga) {
            // return '0';
            return '-';
        } else {
            // return '0';
            return '';
        }
    }

    public function biayaPerTonCount($query, $simproValues, $asumsi)
    {
        $mat = Material::where('material_code', $query->code)->first();
        $ga = GroupAccountFC::where('group_account_fc', $query->code)->first();

        if ($mat) {
            $simproAsumsi = $simproValues
                ->where('asumsi_umum_id', $asumsi->id)
                ->where('name', $query->name)
                ->first();

            // return rupiah($simproAsumsi->biaya_perton);
            return $simproAsumsi->biaya_perton;
        } else if ($ga) {
            $simproAsumsi = $simproValues
                ->where('asumsi_umum_id', $asumsi->id)
                ->where('name', $query->name)
                ->first();

            // return rupiah($simproAsumsi->biaya_perton);
            return $simproAsumsi->biaya_perton;
        } else {
            if ($query->no == 5 || $query->no == 7 || $query->no == 9 || $query->no == 10 || $query->no == 11 || $query->no == 12 || $query->no == 13 || $query->no == 14 || $query->no == 15) {
                $simproAsumsi = $simproValues
                    ->where('asumsi_umum_id', $asumsi->id)
                    ->where('name', $query->name)
                    ->first();

                // return rupiah($simproAsumsi->biaya_perton);
                return $simproAsumsi->biaya_perton;
            } else if ($query->no == 16) {
                $simproAsumsi = $simproValues
                    ->where('asumsi_umum_id', $asumsi->id)
                    ->where('name', $query->name)
                    ->first();

                // return '$ ' . helpRibuanKoma($simproAsumsi->biaya_perton);
                return $simproAsumsi->biaya_perton;
            } else {
                // return '0';
                return '';
            }
        }
    }

    public function totalBiayaCount($query, $simproValues, $asumsi)
    {
        $mat = Material::where('material_code', $query->code)->first();
        $ga = GroupAccountFC::where('group_account_fc', $query->code)->first();

        if ($mat) {
            $simproAsumsi = $simproValues
                ->where('asumsi_umum_id', $asumsi->id)
                ->where('name', $query->name)
                ->first();

            // return rupiah($simproAsumsi->total_biaya);
            return $simproAsumsi->total_biaya;
        } else if ($ga) {
            $simproAsumsi = $simproValues
                ->where('asumsi_umum_id', $asumsi->id)
                ->where('name', $query->name)
                ->first();

            // return rupiah($simproAsumsi->total_biaya);
            return $simproAsumsi->total_biaya;
        } else {
            if ($query->no == 5 || $query->no == 7 || $query->no == 9 || $query->no == 10) {
                $simproAsumsi = $simproValues
                    ->where('asumsi_umum_id', $asumsi->id)
                    ->where('name', $query->name)
                    ->first();

                // return rupiah($simproAsumsi->total_biaya);
                return $simproAsumsi->total_biaya;
            } else if ($query->no == 11) {
                return '';
                // return '0';
            } else if ($query->no == 12) {
                return '';
                // return '0';
            } else if ($query->no == 13) {
                return '';
                // return '0';
            } else if ($query->no == 14) {
                return '';
                // return '0';
            } else if ($query->no == 15) {
                return '';
                // return '0';
            } else if ($query->no == 16) {
                return '';
                // return '0';
            } else {
                return '';
                // return '0';
            }
        }
    }
}
