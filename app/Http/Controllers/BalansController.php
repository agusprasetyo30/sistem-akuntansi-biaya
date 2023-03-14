<?php

namespace App\Http\Controllers;

use App\DataTables\Master\BalansDataTable;
use App\DataTables\Master\BalansStoreDataTable;
use App\DataTables\Master\SimulasiProyeksiStoreDataTable;
use App\Models\Asumsi_Umum;
use App\Models\Balans;
use App\Models\ConsRate;
use App\Models\GroupAccountFC;
use App\Models\MapKategoriBalans;
use App\Models\Material;
use App\Models\SimulasiProyeksi;
use App\Models\TempProyeksi;
use App\Models\Version_Asumsi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class BalansController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.buku_besar.balans.index');
    }

    public function get_data(Request $request, BalansDataTable $balansDataTable){
        $antrian = antrian_material_balans($request->version);
        $result_antrian = [];
        foreach ($antrian as $items){
            foreach ($items as $item){
                array_push($result_antrian, $item);
            }
        }
        if ($request->data == 'index') {
            return $balansDataTable->with(['antrian' => array_values(array_unique($result_antrian)), 'version' => $request->version, 'material' => $request->material])->render('pages.buku_besar.balans.index');
        }
        return view('pages.buku_besar.balans.index');

    }

    public function index_header(Request $request){
        $asumsi = Asumsi_Umum::where('version_id', $request->version)->get();

        return response()->json(['code' => 200, 'asumsi' => $asumsi]);
    }

    public function store(Request $request){
        try {

            $antrian = antrian_material_balans($request->version);
            $result_antrian = [];
            foreach ($antrian as $items){
                foreach ($items as $item){
                    array_push($result_antrian, $item);
                }
            }

            DB::transaction(function () use ($request, $result_antrian){
                Balans::leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'balans.asumsi_umum_id')
                    ->where('asumsi_umum.version_id', $request->version)->delete();



                $simulasi_create = new SimulasiProyeksiController();
                $main_asumsi = Version_Asumsi::with('asumsi_umum:id,version_id,month_year,saldo_awal,usd_rate,adjustment,inflasi')
                    ->select('id', 'version')
                    ->where([
                        'id' => $request->version,
                        'company_code' => auth()->user()->company_code
                    ])->first();

                $antrian = array_values(array_unique($result_antrian));
                $query = MapKategoriBalans::with(['material:material_code,material_name' ,'glos_cc', 'kategori_balans:id,order_view,type_kategori_balans,kategori_balans_desc' , 'saldo_awal:material_code,total_stock,total_value', 'pemakaian:material_code,pj_pemakaian_value,asumsi_umum_id', 'penjualan:material_code,pj_penjualan_value,asumsi_umum_id', 'price_rencana_pengadaan:material_code,price_rendaan_value,asumsi_umum_id', 'qty_rencana_pengadaan:material_code,qty_rendaan_value,asumsi_umum_id', 'const_rate', 'simulasi_proyeksi'])
                    ->select('map_kategori_balans.kategori_balans_id','map_kategori_balans.material_code', 'map_kategori_balans.plant_code', 'map_kategori_balans.company_code')
                    ->whereIn('map_kategori_balans.material_code', $antrian)
                    ->where('map_kategori_balans.version_id', $request->version)
                    ->orderBy('map_kategori_balans.material_code', 'ASC')
                    ->get()->sortBy(['kategori_balans.order_view', function($query) use ($antrian){
                        return array_search($query['material_code'], $antrian);
                    }])->all();

//                dd($query[0]->material);
                $collection_input_temp = collect();
                foreach ($main_asumsi->asumsi_umum as $key => $data){
                    $q=0;
                    $p=0;
                    $nilai=0;

                    foreach ($query as $key1 => $data_map){
                        if ($data_map->kategori_balans_id == 1){
                            if ($key == 0){

                                $plant = explode(' - ', $data_map->plant_code);


                                if ($plant[0] != 'all'){

                                    $q = $data_map->get_data_saldo_awal($plant[0]);
                                    $nilai = $data_map->get_data_saldo_awal_nilai($plant[0]);


                                }else{
                                    $q = $data_map->saldo_awal->sum('total_stock');
                                    $nilai = $data_map->saldo_awal->sum('total_value');
                                }

                                if ($q != 0){
                                    $p = $nilai / $q;
                                }
                                else{
                                    $p = 0;
                                }

                                $type = $data_map->kategori_balans->type_kategori_balans;
                            }else{
                                $temp = $collection_input_temp->where('material_code', '=', $data_map->material_code)
                                    ->where('kategori_balans_id', '=', 6)
                                    ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key-1]->id)->first();

                                if ($temp != null){
                                    $q = $temp['q'];
                                    $p = $temp['p'];
                                    $nilai = $temp['nilai'];
                                    $type = $data_map->kategori_balans->type_kategori_balans;
                                }else{
                                    $q = 0;
                                    $p = 0;
                                    $nilai = 0;
                                    $type = $data_map->kategori_balans->type_kategori_balans;
                                }
                            }
                        }
                        elseif ($data_map->kategori_balans_id == 2){
                            $q = $data_map->get_data_qty_rencana_pengadaan($data->id);
//
                            $nilai = $data_map->get_data_total_pengadaan($data->id, $data->adjustment);


                            if ($q != 0){
                                $p = $nilai / $q;
                            }
                            else{
                                $p = 0;
                            }

                            $type = $data_map->kategori_balans->type_kategori_balans;
                        }
                        elseif ($data_map->kategori_balans_id == 3){
                            $q = $collection_input_temp->where('material_code', '=', $data_map->material_code)
                                ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key]->id)
                                ->sum('q');
                            $nilai = $collection_input_temp->where('material_code', '=', $data_map->material_code)
                                ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key]->id)
                                ->sum('nilai');

                            if ($q != 0){
                                $p = $nilai / $q;
                            }
                            else{
                                $p = 0;
                            }

                            $type = $data_map->kategori_balans->type_kategori_balans;
                        }
                        elseif ($data_map->kategori_balans_id == 4){
                            $q = $data_map->get_data_nilai_pamakaian($data->id) * -1;
                            $p = $collection_input_temp->where('material_code', '=', $data_map->material_code)
                                ->where('kategori_balans_id', '=', 3)
                                ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key]->id)
                                ->sum('p');

                            $nilai = $q * $p;
                            $type = $data_map->kategori_balans->type_kategori_balans;
                        }
                        elseif ($data_map->kategori_balans_id == 5){
                            $q = $data_map->get_data_nilai_penjualan($data->id)* -1;
                            $p = $collection_input_temp->where('material_code', '=', $data_map->material_code)
                                ->where('kategori_balans_id', '=', 3)
                                ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key]->id)
                                ->sum('p');
                            $nilai = $q * $p;
                            $type = $data_map->kategori_balans->type_kategori_balans;
                        }
                        elseif ($data_map->kategori_balans_id == 6){
                            $q_tersedia = $collection_input_temp->where('material_code', '=', $data_map->material_code)
                                ->where('kategori_balans_id', '=', 3)
                                ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key]->id)
                                ->sum('q');
                            $nilai_tersedia = $collection_input_temp->where('material_code', '=', $data_map->material_code)
                                ->where('kategori_balans_id', '=',3)
                                ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key]->id)
                                ->sum('nilai');

                            $q_pj = $collection_input_temp->where('material_code', '=', $data_map->material_code)
                                ->whereIn('kategori_balans_id', [4,5])
                                ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key]->id)
                                ->sum('q');
                            $nilai_pj = $collection_input_temp->where('material_code', '=', $data_map->material_code)
                                ->whereIn('kategori_balans_id', [4,5])
                                ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key]->id)
                                ->sum('nilai');


                            $q = $q_tersedia + $q_pj;
                            $nilai = $nilai_tersedia + $nilai_pj;
                            $type = $data_map->kategori_balans->type_kategori_balans;

                            if ($q != 0){
                                $p = $nilai / $q;
                            }else{
                                $p = 0;
                            }
                        }
                        elseif ($data_map->kategori_balans_id > 6){


                            $glos_cc = $data_map->get_data_glos_cc($data_map->plant_code);
                            if ($glos_cc != null){

//                                dd($request->version, $glos_cc[0]->plant_code, $data_map->material_code, $glos_cc[0]->cost_center);
//                                if ($data_map->material_code == '2000002' && $data_map->kategori_balans_id ==8){
//                                    dd($glos_cc->cost_center);
//                                }
                                $check_simulasi = $collection_input_temp
                                    ->where('kategori_balans_id', '>', 6)
                                    ->where('cost_center', '=', $glos_cc->cost_center)
                                    ->where('material_code', $data_map->material_code)
                                    ->first();

//                                if ($data_map->material_code == '2000002' && $data_map->kategori_balans_id ==8){
//                                    dd($check_simulasi);
//                                }

//                                if ($data_map->kategori_balans_id == 9){
//                                    dd($check_simulasi);
//                                }

//                                if ($key == 1){
//                                    dd($collection_input_temp, $check_simulasi);
//                                }
                                if ($check_simulasi == null){
//                                    if ($data_map->material_code == '2000002' && $data_map->kategori_balans_id ==7) {
//                                        dd($key1, $check_simulasi);
//                                    }
//                                    if ($key1 == 7){
//                                        dd($glos_cc->plant_code, $data_map->material_code, $glos_cc->cost_center);
//                                    }
//                                    dd('dawdawd');
                                    $simulasi_create->hitung_satuan_simpro($request->version, $main_asumsi, $glos_cc->plant_code, $data_map->material_code, $glos_cc->cost_center);
//                                    $this->simulasi($request->version, $glos_cc[0]->plant_code, $data_map->material_code, $glos_cc[0]->cost_center);
                                    $p = (double) $data_map->get_data_simulasi($glos_cc, $data->id);
                                }else{
//                                    if ($data_map->material_code == '2000002' && $data_map->kategori_balans_id ==8) {
//                                        dd($key1, $glos_cc, $check_simulasi);
//                                    }
                                    $p = (double) $check_simulasi['p'];

                                }

                                if ($data_map->kategori_balans->type_kategori_balans == 'produksi'){
                                    $temp_q = $data_map->get_data_qty_renprod($glos_cc->cost_center, $data->id);
                                    $q = $temp_q[0]->renprod->sum('qty_renprod_value');
                                    $nilai = $q * $p;

                                }else{

                                    // cell q
                                    $temp_q = $data_map->get_data_qty_renprod($glos_cc->cost_center, $data->id);
                                    $qty_renprod = (double) $temp_q[0]->renprod->sum('qty_renprod_value');
                                    $cons_rate = (double) $data_map->get_data_cons_rate($data_map, $glos_cc->plant_code, $data->id);
                                    $q = $qty_renprod * $cons_rate * -1;

                                    $nilai = $q * $p;
                                }

                                $type = $data_map->kategori_balans->type_kategori_balans;
                            }else {
                                $q = 0;
                                $p = 0;
                                $nilai = 0;
                                $type = $data_map->kategori_balans->type_kategori_balans;
                            }
                        }

                        $material_name = $data_map->material->material_name;
                        $version_id = $main_asumsi->id;
                        $order_view = $data_map->kategori_balans->order_view;
                        $kategori_balans_desc = $data_map->kategori_balans->kategori_balans_desc;
                        $month_year = $data->month_year;

                        $collection_input_temp->push($this->submit_temp($data->id, $data_map->kategori_balans_id, $data_map->plant_code, $data_map->material_code, $q, $p, $nilai, $type, $material_name, $version_id, $order_view, $kategori_balans_desc, $month_year));
                    }
                }

                $chunk = array_chunk($collection_input_temp->toArray(), 5000);
                foreach ($chunk as $insert){
                    Balans::insert($insert);
                }

                $data_simulasi = SimulasiProyeksi::where('product_code','2000001')
                    ->where('name', 'COGM')
                    ->where('asumsi_umum_id', '10')
                    ->where('plant_code', 'B019')
                    ->get();

//                dd($data_simulasi);

//                SimulasiProyeksi::where('version_id', $request->version)->delete();
//                $simulasi_create->hitung_simpro($request->version);
            });
            return response()->json(['code' => 200]);
        }catch (\Exception $exception){
            return response()->json(['code' => 500]);
        }
    }

    public function simulasi($data_version, $data_plant, $data_product, $data_cost_center){
        $collection_input_temp = collect();

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
        );

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
        )->union($group_account);

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
        })->union($temp_pro)
            ->orderBy('no', 'asc')
            ->orderBy('kategori', 'asc')
            ->get();
//         dd($query->toArray());

        $asumsi = Asumsi_Umum::where('version_id', $data_version)
            ->get();

//        dd($query)

        foreach ($asumsi as $key2 => $asum) {
            $hs = 0;
            $consrate = 0;
            $biaya_perton = 0;
            $total_biaya = 0;
            $kuan_prod = 0;
            $periode = $asum->id;
            $inflasi = $asum->inflasi ?? 0;

            $mat_ = Material::get();
            $ga_ = GroupAccountFC::get();
            // dd($asum->inflasi);
            foreach ($query as $key3 => $val) {
                $mat = $mat_->where('material_code', $val->code)->first();
                $ga = $ga_->where('group_account_fc', $val->code)->first();

//                if ($key3 == 0){
//
//                    dd($mat, $ga);
//                }
                if ($mat) {

                    //ConsRate
                    $kp = $val->kuantumProduksi($periode);


                    // print_r($kp);
                    $kp_val = 0;
                    if ($kp) {
                        $kp_v = $val->kpValue($periode);
                        $kuan_prod = $kp_v;
                        //Data Dummy
                        // $kp_v = 2;

                        if ($kp_v == 1) {
                            $consrate = 0;
                        } else {
                            $consrate = $val->consRate() ?? 0;
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
                        $res = $val->hsZco();
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
                } else if ($ga) {


                    $hs = 0;
                    $consrate = 0;
                    $biaya_perton = 0;
                    $total_biaya = 0;

                } else {


                    //Harga Satuan
                    $hs = 0;

                    //ConsRate
                    $consrate = 0;

                    //Biaya Perton dan Total Biaya
                    if ($val->no == 5) {
                        $res_biaya_perton = $collection_input_temp->where('product_code', $data_product)->whereIn('no', [1, 2, 3, 4])->where('asumsi_umum_id', '=', $periode)->sum('biaya_perton');
                        $res_total_biaya = $collection_input_temp->where('product_code', $data_product)->whereIn('no', [1, 2, 3, 4])->where('asumsi_umum_id', '=', $periode)->sum('total_biaya');

                        $biaya_perton = $res_biaya_perton;
                        $total_biaya = $res_total_biaya;
                    } else if ($val->no == 7) {
                        $res_biaya_perton = $collection_input_temp->where('product_code', $data_product)->whereIn('no', [6])->where('asumsi_umum_id', '=', $periode)->sum('biaya_perton');
                        $res_total_biaya = $collection_input_temp->where('product_code', $data_product)->whereIn('no', [6])->where('asumsi_umum_id', '=', $periode)->sum('total_biaya');

                        $biaya_perton = $res_biaya_perton;
                        $total_biaya = $res_total_biaya;
                    } else if ($val->no == 9) {
                        $res_biaya_perton = $collection_input_temp->where('product_code', $data_product)->whereIn('no', [8])->where('asumsi_umum_id', '=', $periode)->sum('biaya_perton');
                        $res_total_biaya = $collection_input_temp->where('product_code', $data_product)->whereIn('no', [8])->where('asumsi_umum_id', '=', $periode)->sum('total_biaya');

                        $biaya_perton = $res_biaya_perton;
                        $total_biaya = $res_total_biaya;
                    } else if ($val->no == 10) {
                        $res_biaya_perton = $collection_input_temp->where('product_code', $data_product)->whereIn('no', [1, 2, 3, 4, 6, 8])->where('asumsi_umum_id', '=', $periode)->sum('biaya_perton');
                        $res_total_biaya = $collection_input_temp->where('product_code', $data_product)->whereIn('no', [1, 2, 3, 4, 6, 8])->where('asumsi_umum_id', '=', $periode)->sum('total_biaya');

                        $biaya_perton = $res_biaya_perton;
                        $total_biaya = $res_total_biaya;
                    } else if ($val->no == 11) {
                        $biaya_admin_umum = $val->labaRugi($data_product);
                        $res = 0;

                        if ($biaya_admin_umum) {
                            $res = $biaya_admin_umum->value_bau;
                        }

                        $biaya_perton = $res;
                        $total_biaya = 0;
                    } else if ($val->no == 12) {
                        $biaya_pemasaran = $val->labaRugi($data_product);
                        $res = 0;

                        if ($biaya_pemasaran) {
                            $res = $biaya_pemasaran->value_bp;
                        }

                        $biaya_perton = $res;
                        $total_biaya = 0;
                    } else if ($val->no == 13) {
                        $biaya_keuangan = $val->labaRugi($data_product);
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
//                if ($key3 == 0){
//
//                    dd($collection_input_temp);
//                }
            }

        }



        $chunk = array_chunk($collection_input_temp->toArray(), 500);
        foreach ($chunk as $insert) {
            SimulasiProyeksi::insert($insert);
        }
    }

    public function submit_temp($asumsi, $kategori_balans, $plant_code, $material_code, $q, $p, $nilai, $type, $material_name, $version_id, $order_view, $kategori_balans_desc, $month_year){
        $input['asumsi_umum_id'] = $asumsi;
        $input['kategori_balans_id'] = $kategori_balans;
        $input['plant_code'] = $plant_code;
        $input['material_code'] = $material_code;
        $input['q'] =(double) $q;
        $input['p'] =(double) $p;
        $input['nilai'] =(double) $nilai;
        $input['type_kategori_balans'] =$type;
        $input['material_name'] =$material_name;
        $input['version_id'] =$version_id;
        $input['order_view'] =$order_view;
        $input['kategori_balans_desc'] =$kategori_balans_desc;
        $input['month_year'] =$month_year;
        $input['company_code'] = auth()->user()->company_code;
        $input['created_by'] = auth()->user()->id;
        $input['created_at'] = Carbon::now()->format('Y-m-d');
        $input['updated_at'] = Carbon::now()->format('Y-m-d');

        return $input;
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

    public function checker(Request $request){
        try {
            $balans = Balans::leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'balans.asumsi_umum_id')
                ->where('asumsi_umum.version_id', $request->version)->first();

            if ($balans == null) {
                return response()->json(['code' => 200, 'msg' => 'Data Tidak Ada']);
            } else {
                return response()->json(['code' => 201, 'msg' => 'Data Ada']);
            }
        }catch (\Exception $exception){
            return setResponse([
                'code' => 400,
            ]);
        }
    }
}
