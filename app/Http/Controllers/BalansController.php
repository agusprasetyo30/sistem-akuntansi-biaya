<?php

namespace App\Http\Controllers;

use App\DataTables\Master\BalansDataTable;
use App\DataTables\Master\BalansStoreDataTable;
use App\DataTables\Master\SimulasiProyeksiStoreDataTable;
use App\Exports\Horizontal\BalansExport;
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
use Maatwebsite\Excel\Facades\Excel;

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

    public function export(Request $request, BalansDataTable $balansDataTable)
    {
        $antrian = antrian_material_balans($request->version);
        $result_antrian = [];
        foreach ($antrian as $items){
            foreach ($items as $item){
                array_push($result_antrian, $item);
            }
        }

        $antrian = array_values(array_unique($result_antrian));

        $balans_data = Balans::select('kategori_balans_id','material_code', 'plant_code', 'company_code', 'kategori_balans_desc', 'order_view')
            ->whereIn('material_code', $request->material == 'all' ? $antrian : [$request->material])
            ->where('version_id', $request->version)
            ->groupBy('kategori_balans_id','material_code', 'plant_code', 'company_code', 'kategori_balans_desc', 'order_view')
            ->orderBy('material_code', 'ASC')
            ->orderBy('order_view', 'ASC')->get();

        $main_asumsi = Asumsi_Umum::where('version_id', $request->version)->get();

        $balans_default = Balans::where('version_id', $request->version)
            ->get();

        $temporary_value['p'] = [];
        $temporary_value['q'] = [];
        $temporary_value['nilai'] = [];

        foreach ($balans_data as $query) {
            foreach ($main_asumsi as $key_sub => $value) {
                $p_value_temp = $balans_default->where('kategori_balans_id', $query->kategori_balans_id)
                    ->where('asumsi_umum_id', $main_asumsi[$key_sub]->id)
                    ->where('company_code', $query->company_code)
                    ->where('plant_code', $query->plant_code)
                    ->where('material_code', $query->material_code)
                    ->first();

                $q_value_temp = $balans_default->where('kategori_balans_id', $query->kategori_balans_id)
                    ->where('asumsi_umum_id', $main_asumsi[$key_sub]->id)
                    ->where('company_code', $query->company_code)
                    ->where('plant_code', $query->plant_code)
                    ->where('material_code', $query->material_code)
                    ->first();

                $result_value_temp = $balans_default->where('kategori_balans_id', $query->kategori_balans_id)
                    ->where('asumsi_umum_id', $main_asumsi[$key_sub]->id)
                    ->where('company_code', $query->company_code)
                    ->where('plant_code', $query->plant_code)
                    ->where('material_code', $query->material_code)
                    ->first();

                array_push($temporary_value['p'], ["key" => $key_sub, "value" => $p_value_temp->p]);
                array_push($temporary_value['q'], ["key" => $key_sub, "value" => $q_value_temp->q]);
                array_push($temporary_value['nilai'], ["key" => $key_sub, "value" => $result_value_temp->nilai]);
            }

        }

        $main_asumsi_index_count = $main_asumsi->count() - 1;

        $fixed_value['p'] = $this->getSeparateValue($temporary_value['p'], $main_asumsi_index_count);
        $fixed_value['q'] = $this->getSeparateValue($temporary_value['q'], $main_asumsi_index_count);
        $fixed_value['nilai'] = $this->getSeparateValue($temporary_value['nilai'], $main_asumsi_index_count);

        $data = [
            'balans_datas'     => $balans_data,
            'asumsi_umum'      => $main_asumsi,
            'fixed_value_data' => $fixed_value
        ];

        $filename = "Balans " . $request->material . '.xlsx';

        return Excel::download(new BalansExport($data), $filename);
    }

    /**
     * melakukan filter dan memisahkan data array sesuai dengan
     *
     * @param [type] $arr
     * @param [type] $dinamic_reference_count
     * @return array
     */
    public function getSeparateValue($arr, $dinamic_reference_count) : array
    {
        $temp_index = 0;

        foreach ($arr as $key => $value) {
            if ($arr[$key]['key'] == $temp_index) {
                $fixed_value[$temp_index][] = $arr[$key]['value'];

                $temp_index++;

                if ($temp_index > $dinamic_reference_count) {
                    $temp_index = 0;
                }
            }
        }

        return $fixed_value;
    }

    public function store(Request $request)
    {
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

//                SimulasiProyeksi::where('version_id', $request->version)->delete();


                $simulasi_create = new SimulasiProyeksiController();
                $main_asumsi = Version_Asumsi::with('asumsi_umum:id,version_id,month_year,saldo_awal,usd_rate,adjustment,inflasi')
                    ->select('id', 'version')
                    ->where([
                        'id' => $request->version,
                        'company_code' => auth()->user()->company_code
                    ])->first();

                $antrian = array_values(array_unique($result_antrian));
//                $main_query = MapKategoriBalans::with(['material:material_code,material_name' ,'glos_cc', 'kategori_balans:id,order_view,type_kategori_balans,kategori_balans_desc' , 'saldo_awal:material_code,total_stock,total_value', 'pemakaian:material_code,pj_pemakaian_value,asumsi_umum_id', 'penjualan:material_code,pj_penjualan_value,asumsi_umum_id', 'price_rencana_pengadaan:material_code,price_rendaan_value,asumsi_umum_id', 'qty_rencana_pengadaan:material_code,qty_rendaan_value,asumsi_umum_id', 'const_rate', 'simulasi_proyeksi'])
//                    ->select('map_kategori_balans.kategori_balans_id','map_kategori_balans.material_code', 'map_kategori_balans.plant_code', 'map_kategori_balans.company_code')
//                    ->whereIn('map_kategori_balans.material_code', $antrian)
//                    ->where('map_kategori_balans.version_id', $request->version)
//                    ->orderBy('map_kategori_balans.material_code', 'ASC')
//                    ->get();

                $main_query = MapKategoriBalans::with(['material:material_code,material_name' ,'glos_cc', 'kategori_balans:id,order_view,type_kategori_balans,kategori_balans_desc' , 'saldo_awal:material_code,total_stock,total_value', 'pemakaian:material_code,pj_pemakaian_value,asumsi_umum_id', 'penjualan:material_code,pj_penjualan_value,asumsi_umum_id', 'price_rencana_pengadaan:material_code,price_rendaan_value,asumsi_umum_id', 'qty_rencana_pengadaan:material_code,qty_rendaan_value,asumsi_umum_id', 'const_rate', 'simulasi_proyeksi'])
                    ->select('map_kategori_balans.kategori_balans_id','map_kategori_balans.material_code', 'map_kategori_balans.plant_code', 'map_kategori_balans.company_code')
                    ->whereIn('map_kategori_balans.material_code', $antrian)
                    ->where('map_kategori_balans.version_id', $request->version)
                    ->where('map_kategori_balans.kategori_balans_id', '<=', 6)
                    ->orderBy('map_kategori_balans.kategori_balans_id', 'ASC')
                    ->orderBy('map_kategori_balans.material_code', 'ASC')
                    ->get();

                $main_query_spesial = MapKategoriBalans::with(['material:material_code,material_name' ,'glos_cc', 'kategori_balans:id,order_view,type_kategori_balans,kategori_balans_desc' , 'saldo_awal:material_code,total_stock,total_value', 'pemakaian:material_code,pj_pemakaian_value,asumsi_umum_id', 'penjualan:material_code,pj_penjualan_value,asumsi_umum_id', 'price_rencana_pengadaan:material_code,price_rendaan_value,asumsi_umum_id', 'qty_rencana_pengadaan:material_code,qty_rendaan_value,asumsi_umum_id', 'const_rate', 'simulasi_proyeksi'])
                    ->select('map_kategori_balans.kategori_balans_id','map_kategori_balans.material_code', 'map_kategori_balans.plant_code', 'map_kategori_balans.company_code')
                    ->whereIn('map_kategori_balans.material_code', $antrian)
                    ->where('map_kategori_balans.version_id', $request->version)
                    ->where('map_kategori_balans.kategori_balans_id', '>', 6)
                    ->orderBy('map_kategori_balans.material_code', 'ASC')
                    ->get();

                $temp_query = $main_query;

//                dd($temp_query);

                $query = $main_query->sortBy(['kategori_balans.order_view', function($query) use ($antrian){
                    return array_search($query['material_code'], $antrian);
                }])->all();

                $collection_input_temp = collect();
                foreach ($main_asumsi->asumsi_umum as $key => $data){
                    $q=0;
                    $p=0;
                    $nilai=0;

//                    foreach ($query as $key1 => $data_map){
//                        if ($data_map->kategori_balans_id == 1){
//                            if ($key == 0){
//
//                                $plant = explode(' - ', $data_map->plant_code);
//
//
//                                if ($plant[0] != 'all'){
//
//                                    $q = $data_map->get_data_saldo_awal($plant[0]);
//                                    $nilai = $data_map->get_data_saldo_awal_nilai($plant[0]);
//
//
//                                }else{
//                                    $q = $data_map->saldo_awal->sum('total_stock');
//                                    $nilai = $data_map->saldo_awal->sum('total_value');
//                                }
//
//                                if ($q != 0){
//                                    $p = $nilai / $q;
//                                }
//                                else{
//                                    $p = 0;
//                                }
//
//                                $type = $data_map->kategori_balans->type_kategori_balans;
//                            }else{
//                                $temp = $collection_input_temp->where('material_code', '=', $data_map->material_code)
//                                    ->where('kategori_balans_id', '=', 6)
//                                    ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key-1]->id)->first();
//
//                                if ($temp != null){
//                                    $q = $temp['q'];
//                                    $p = $temp['p'];
//                                    $nilai = $temp['nilai'];
//                                    $type = $data_map->kategori_balans->type_kategori_balans;
//                                }else{
//                                    $q = 0;
//                                    $p = 0;
//                                    $nilai = 0;
//                                    $type = $data_map->kategori_balans->type_kategori_balans;
//                                }
//                            }
//                        }
//                        elseif ($data_map->kategori_balans_id == 2){
//                            $q = $data_map->get_data_qty_rencana_pengadaan($data->id);
////
//                            $nilai = $data_map->get_data_total_pengadaan($data->id, $data->adjustment);
//
//
//                            if ($q != 0){
//                                $p = $nilai / $q;
//                            }
//                            else{
//                                $p = 0;
//                            }
//
//                            $type = $data_map->kategori_balans->type_kategori_balans;
//                        }
//                        elseif ($data_map->kategori_balans_id == 3){
//                            $q = $collection_input_temp->where('material_code', '=', $data_map->material_code)
//                                ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key]->id)
//                                ->sum('q');
//                            $nilai = $collection_input_temp->where('material_code', '=', $data_map->material_code)
//                                ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key]->id)
//                                ->sum('nilai');
//
//                            if ($q != 0){
//                                $p = $nilai / $q;
//                            }
//                            else{
//                                $p = 0;
//                            }
//
//                            $type = $data_map->kategori_balans->type_kategori_balans;
//                        }
//                        elseif ($data_map->kategori_balans_id == 4){
//                            $q = $data_map->get_data_nilai_pamakaian($data->id) * -1;
//                            $p = $collection_input_temp->where('material_code', '=', $data_map->material_code)
//                                ->where('kategori_balans_id', '=', 3)
//                                ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key]->id)
//                                ->sum('p');
//
//                            $nilai = $q * $p;
//                            $type = $data_map->kategori_balans->type_kategori_balans;
//                        }
//                        elseif ($data_map->kategori_balans_id == 5){
//                            $q = $data_map->get_data_nilai_penjualan($data->id)* -1;
//                            $p = $collection_input_temp->where('material_code', '=', $data_map->material_code)
//                                ->where('kategori_balans_id', '=', 3)
//                                ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key]->id)
//                                ->sum('p');
//                            $nilai = $q * $p;
//                            $type = $data_map->kategori_balans->type_kategori_balans;
//                        }
//                        elseif ($data_map->kategori_balans_id == 6){
//                            $q_tersedia = $collection_input_temp->where('material_code', '=', $data_map->material_code)
//                                ->where('kategori_balans_id', '=', 3)
//                                ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key]->id)
//                                ->sum('q');
//                            $nilai_tersedia = $collection_input_temp->where('material_code', '=', $data_map->material_code)
//                                ->where('kategori_balans_id', '=',3)
//                                ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key]->id)
//                                ->sum('nilai');
//
//                            $q_pj = $collection_input_temp->where('material_code', '=', $data_map->material_code)
//                                ->whereIn('kategori_balans_id', [4,5])
//                                ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key]->id)
//                                ->sum('q');
//                            $nilai_pj = $collection_input_temp->where('material_code', '=', $data_map->material_code)
//                                ->whereIn('kategori_balans_id', [4,5])
//                                ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key]->id)
//                                ->sum('nilai');
//
//
//                            $q = $q_tersedia + $q_pj;
//                            $nilai = $nilai_tersedia + $nilai_pj;
//                            $type = $data_map->kategori_balans->type_kategori_balans;
//
//                            if ($q != 0){
//                                $p = $nilai / $q;
//                            }else{
//                                $p = 0;
//                            }
//                        }
//                        elseif ($data_map->kategori_balans_id > 6){
////                            $sub_temp_query = $temp_query
////                                ->whereNotIn('kategori_balans_id', [1,4,5,6])
////                                ->where('material_code', $data_map->material_code)
////                                ->values();
////
////                            $count_data = count($sub_temp_query);
////
////                            foreach ($sub_temp_query as $item){
////                                if ($item->kategori_balans_id == 2){
////                                    $q = $item->get_data_qty_rencana_pengadaan($data->id);
//////
////                                    $nilai = $item->get_data_total_pengadaan($data->id, $data->adjustment);
////
////
////                                    if ($q != 0){
////                                        $p = $nilai / $q;
////                                    }
////                                    else{
////                                        $p = 0;
////                                    }
////
////                                    $type = $item->kategori_balans->type_kategori_balans;
////                                }
////                                $material_name = $item->material->material_name;
////                                $version_id = $main_asumsi->id;
////                                $order_view = $item->kategori_balans->order_view;
////                                $kategori_balans_desc = $item->kategori_balans->kategori_balans_desc;
////                                $month_year = $data->month_year;
////
////                                dd($data->id, $item->kategori_balans_id, $item->plant_code, $item->material_code, $q, $p, $nilai, $type, $material_name, $version_id, $order_view, $kategori_balans_desc, $month_year);
////                                $collection_input_temp->push($this->submit_temp($data->id, $item->kategori_balans_id, $item->plant_code, $item->material_code, $q, $p, $nilai, $type, $material_name, $version_id, $order_view, $kategori_balans_desc, $month_year));
////                            }
//                            $glos_cc = $data_map->get_data_glos_cc($data_map->plant_code);
//                            if ($glos_cc != null){
//
////                                dd($request->version, $glos_cc[0]->plant_code, $data_map->material_code, $glos_cc[0]->cost_center);
////                                if ($data_map->material_code == '2000002' && $data_map->kategori_balans_id ==8){
////                                    dd($glos_cc->cost_center);
////                                }
//                                $check_simulasi = $collection_input_temp
//                                    ->where('kategori_balans_id', '>', 6)
//                                    ->where('cost_center', '=', $glos_cc->cost_center)
//                                    ->where('material_code', $data_map->material_code)
//                                    ->first();
//
////                                dd($check_simulasi);
//
////                                if ($data_map->material_code == '2000002' && $data_map->kategori_balans_id ==8){
////                                    dd($check_simulasi);
////                                }
//
////                                if ($data_map->kategori_balans_id == 9){
////                                    dd($check_simulasi);
////                                }
//
////                                if ($key == 1){
////                                    dd($collection_input_temp, $check_simulasi);
////                                }
//                                if ($check_simulasi == null){
//
////                                    $ce = $collection_input_temp;
////                                    dd($data_map, $ce);
////                                    dd($request->version, $data, $glos_cc->plant_code, $data_map->material_code, $glos_cc->cost_center);
//                                    $simulasi_create->hitung_satuan_simpro($request->version, $data, $glos_cc->plant_code, $data_map->material_code, $glos_cc->cost_center);
////                                    $this->simulasi($request->version, $glos_cc[0]->plant_code, $data_map->material_code, $glos_cc[0]->cost_center);
//
//                                    $sim = SimulasiProyeksi::where('product_code', '2000002')
//                                        ->where('asumsi_umum_id', 10)
//                                        ->where('name', 'COGM')
//                                        ->where('plant_code', 'B030')
//                                        ->first();
//
////                                    dd($glos_cc, $data->id);
//                                    $p = (double) $data_map->get_data_simulasi($glos_cc, $data->id);
////                                    dd($p, $sim, $glos_cc);
//                                }else{
////                                    if ($data_map->material_code == '2000002' && $data_map->kategori_balans_id ==8) {
////                                        dd($key1, $glos_cc, $check_simulasi);
////                                    }
//                                    $p = (double) $check_simulasi['p'];
//
//                                }
//
//                                if ($data_map->kategori_balans->type_kategori_balans == 'produksi'){
//                                    $temp_q = $data_map->get_data_qty_renprod($glos_cc->cost_center, $data->id);
//                                    $q = $temp_q[0]->renprod->sum('qty_renprod_value');
//                                    $nilai = $q * $p;
//
//                                }else{
//
//                                    // cell q
//                                    $temp_q = $data_map->get_data_qty_renprod($glos_cc->cost_center, $data->id);
//                                    $qty_renprod = (double) $temp_q[0]->renprod->sum('qty_renprod_value');
//                                    $cons_rate = (double) $data_map->get_data_cons_rate($data_map, $glos_cc->plant_code, $data->id);
//                                    $q = $qty_renprod * $cons_rate * -1;
//
//                                    $nilai = $q * $p;
//                                }
//
//                                $type = $data_map->kategori_balans->type_kategori_balans;
//                            }else {
//                                $q = 0;
//                                $p = 0;
//                                $nilai = 0;
//                                $type = $data_map->kategori_balans->type_kategori_balans;
//                            }
//                        }
//
//                        $material_name = $data_map->material->material_name;
//                        $version_id = $main_asumsi->id;
//                        $order_view = $data_map->kategori_balans->order_view;
//                        $kategori_balans_desc = $data_map->kategori_balans->kategori_balans_desc;
//                        $month_year = $data->month_year;
//
//                        $collection_input_temp->push($this->submit_temp($data->id, $data_map->kategori_balans_id, $data_map->plant_code, $data_map->material_code, $q, $p, $nilai, $type, $material_name, $version_id, $order_view, $kategori_balans_desc, $month_year));
//                    }
//

                    foreach ($temp_query as $key1 => $data_map){
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
                                ->where('kategori_balans_id', '<', 3)
                                ->sum('q');
                            $nilai = $collection_input_temp->where('material_code', '=', $data_map->material_code)
                                ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key]->id)
                                ->where('kategori_balans_id', '<', 3)
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

                        $material_name = $data_map->material->material_name;
                        $version_id = $main_asumsi->id;
                        $order_view = $data_map->kategori_balans->order_view;
                        $kategori_balans_desc = $data_map->kategori_balans->kategori_balans_desc;
                        $month_year = $data->month_year;

                        $collection_input_temp->push($this->submit_temp($data->id, $data_map->kategori_balans_id, $data_map->plant_code, $data_map->material_code, $q, $p, $nilai, $type, $material_name, $version_id, $order_view, $kategori_balans_desc, $month_year));
                    }
                }


//                dd($collection_input_temp->where('kategori_balans_id', 6));dd($collection_input_temp->where('kategori_balans_id', 6));

                foreach ($main_asumsi->asumsi_umum as $key => $data_asum){
                    $q=0;
                    $p=0;
                    $nilai=0;
                    foreach ($main_query_spesial as $key2 => $data_maps_spesial){
                        $glos_cc = $data_maps_spesial->get_data_glos_cc($data_maps_spesial->plant_code);
                        if ($glos_cc != null){
                            $check_simulasi = $collection_input_temp
                                ->where('kategori_balans_id', '>', 6)
                                ->where('cost_center', '=', $glos_cc->cost_center)
                                ->where('material_code', $data_maps_spesial->material_code)
                                ->first();

                            if ($check_simulasi == null){
                                $simulasi_create->hitung_satuan_simpro($request->version, $data, $glos_cc->plant_code, $data_maps_spesial->material_code, $glos_cc->cost_center);

                                $p = (double) $data_maps_spesial->get_data_simulasi($glos_cc, $data->id);
                            }else{
                                $p = (double) $check_simulasi['p'];

                            }

                            if ($data_maps_spesial->kategori_balans->type_kategori_balans == 'produksi'){
                                $temp_q = $data_maps_spesial->get_data_qty_renprod($glos_cc->cost_center, $data->id);
                                $q = $temp_q[0]->renprod->sum('qty_renprod_value');
                                $nilai = $q * $p;

                            }else{
                                // cell q
                                $temp_q = $data_maps_spesial->get_data_qty_renprod($glos_cc->cost_center, $data->id);
                                $qty_renprod = (double) $temp_q[0]->renprod->sum('qty_renprod_value');
                                $cons_rate = (double) $data_maps_spesial->get_data_cons_rate($data_map, $glos_cc->plant_code, $data->id);
                                $q = $qty_renprod * $cons_rate * -1;

                                $nilai = $q * $p;
                            }

                            $type = $data_maps_spesial->kategori_balans->type_kategori_balans;
                        }else {
                            $q = 0;
                            $p = 0;
                            $nilai = 0;
                            $type = $data_maps_spesial->kategori_balans->type_kategori_balans;
                        }

                        try {
                            $collection_input_temp = $collection_input_temp->map(function ($query) use ($collection_input_temp, $data_asum, $data_maps_spesial){
                                if ($query['kategori_balans_id'] == 3){

                                    $tersedia = $collection_input_temp->where('kategori_balans_id', 3)
                                        ->where('asumsi_umum_id', $data_asum->id)
                                        ->where('material_code', $data_maps_spesial->material_code)->first();

                                    $saldo_akhir = $collection_input_temp->where('kategori_balans_id', 6)
                                        ->where('asumsi_umum_id', $data_asum->id)
                                        ->where('material_code', $data_maps_spesial->material_code)->first();


                                    $result = [
                                        "asumsi_umum_id" => $query['asumsi_umum_id'],
                                        "kategori_balans_id" => $query['kategori_balans_id'],
                                        "plant_code" => $query['plant_code'],
                                        "material_code" => $query['material_code'],
                                        "q" => 5,
                                        "p" => 5,
                                        "nilai" => 5,
                                        "type_kategori_balans" => $query['type_kategori_balans'],
                                        "material_name" => $query['material_name'],
                                        "version_id" => $query['version_id'],
                                        "order_view" => $query['order_view'],
                                        "kategori_balans_desc" => $query['kategori_balans_desc'],
                                        "month_year" => $query['month_year'],
                                        "company_code" => $query['company_code'],
                                        "created_by" => $query['created_by'],
                                        "created_at" => $query['created_at'],
                                        "updated_at" => $query['updated_at'],
                                    ];
                                }elseif ($query['kategori_balans_id'] == 6){

                                    $tersedia = $collection_input_temp->where('kategori_balans_id', 3)
                                        ->where('asumsi_umum_id', $data_asum->id)
                                        ->where('material_code', $data_maps_spesial->material_code)->first();

                                    $saldo_akhir = $collection_input_temp->where('kategori_balans_id', 6)
                                        ->where('asumsi_umum_id', $data_asum->id)
                                        ->where('material_code', $data_maps_spesial->material_code)->first();

                                    $result = [
                                        "asumsi_umum_id" => $query['asumsi_umum_id'],
                                        "kategori_balans_id" => $query['kategori_balans_id'],
                                        "plant_code" => $query['plant_code'],
                                        "material_code" => $query['material_code'],
                                        "q" => 5,
                                        "p" => 5,
                                        "nilai" => 5,
                                        "type_kategori_balans" => $query['type_kategori_balans'],
                                        "material_name" => $query['material_name'],
                                        "version_id" => $query['version_id'],
                                        "order_view" => $query['order_view'],
                                        "kategori_balans_desc" => $query['kategori_balans_desc'],
                                        "month_year" => $query['month_year'],
                                        "company_code" => $query['company_code'],
                                        "created_by" => $query['created_by'],
                                        "created_at" => $query['created_at'],
                                        "updated_at" => $query['updated_at'],
                                    ];
                                }else{
                                    $result = $query;
                                }

                                return $result;
                            });

                            dd($collection_input_temp->where('kategori_balans_id', 6));
                        }catch (\Exception $exception){
                            dd($exception);
                        }

                        $saldo_akhir['q'] = $saldo_akhir['q'] + $q;
                        $saldo_akhir['nilai'] = $saldo_akhir['nilai'] + $nilai;
                        $saldo_akhir['p'] = $saldo_akhir['nilai'] / $saldo_akhir['q'];

                        $material_name = $data_map->material->material_name;
                        $version_id = $main_asumsi->id;
                        $order_view = $data_map->kategori_balans->order_view;
                        $kategori_balans_desc = $data_map->kategori_balans->kategori_balans_desc;
                        $month_year = $data->month_year;

                        $collection_input_temp->push($this->submit_temp($data->id, $data_map->kategori_balans_id, $data_map->plant_code, $data_map->material_code, $q, $p, $nilai, $type, $material_name, $version_id, $order_view, $kategori_balans_desc, $month_year));
                    }

                }
                dd($collection_input_temp->where('kategori_balans_id', 6));


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

                SimulasiProyeksi::where('version_id', $request->version)->delete();
                $simulasi_create->hitung_simpro($request->version);
            });
            return response()->json(['code' => 200]);
        }catch (\Exception   $exception){
            return response()->json(['code' => 500]);
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
