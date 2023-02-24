<?php

namespace App\Http\Controllers;

use App\DataTables\Master\BalansDataTable;
use App\DataTables\Master\BalansStoreDataTable;
use App\DataTables\Master\SimulasiProyeksiStoreDataTable;
use App\Models\Asumsi_Umum;
use App\Models\Balans;
use App\Models\ConsRate;
use App\Models\MapKategoriBalans;
use App\Models\Material;
use App\Models\SimulasiProyeksi;
use App\Models\Version_Asumsi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

class BalansController extends Controller
{
    public function index(Request $request, BalansDataTable $balansDataTable)
    {

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

            $antrian = antrian_material_balans(1);
            $result_antrian = [];
            foreach ($antrian as $items){
                foreach ($items as $item){
                    array_push($result_antrian, $item);
                }
            }

            DB::transaction(function () use ($request, $result_antrian){
                Balans::leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'balans.asumsi_umum_id')
                    ->where('asumsi_umum.version_id', $request->version)->delete();

                $main_asumsi = Version_Asumsi::with('asumsi_umum:id,version_id,month_year,saldo_awal,usd_rate,adjustment')
                    ->select('id', 'version')
                    ->where([
                        'id' => $request->version,
                        'company_code' => auth()->user()->company_code
                    ])->first();

                $antrian = array_values(array_unique($result_antrian));
                $query = MapKategoriBalans::with(['kategori_balans:id,order_view,type_kategori_balans' , 'saldo_awal:material_code,total_stock,total_value', 'pemakaian:material_code,pj_pemakaian_value,asumsi_umum_id', 'penjualan:material_code,pj_penjualan_value,asumsi_umum_id', 'price_rencana_pengadaan:material_code,price_rendaan_value,asumsi_umum_id', 'qty_rencana_pengadaan:material_code,qty_rendaan_value,asumsi_umum_id', 'const_rate.glos_cc', 'simulasi_proyeksi'])
                    ->select('map_kategori_balans.kategori_balans_id','map_kategori_balans.material_code', 'map_kategori_balans.plant_code', 'map_kategori_balans.company_code')
                    ->whereIn('map_kategori_balans.material_code', $antrian)
                    ->where('map_kategori_balans.version_id', $request->version)
                    ->orderBy('map_kategori_balans.material_code', 'ASC')
                    ->get()->sortBy(['kategori_balans.order_view', function($query) use ($antrian){
                        return array_search($query['material_code'], $antrian);
                    }])->all();

                $collection_input_temp = collect();
                foreach ($main_asumsi->asumsi_umum as $key => $data){
                    $q=0;
                    $p=0;
                    $nilai=0;

                    foreach ($query as $key1 => $data_map){
                        if ($data_map->kategori_balans_id == 1){
                            if ($key == 0){
                                $q = $data_map->saldo_awal->sum('total_stock');
                                $nilai = $data_map->saldo_awal->sum('total_value');
                                $p = $nilai / $q;
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
                            $nilai = $data_map->get_data_total_pengadaan($data->id, $data->usd_rate, $data->adjustment);
                            $p = $nilai / $q ;
                            $type = $data_map->kategori_balans->type_kategori_balans;
                        }
                        elseif ($data_map->kategori_balans_id == 3){
                            $q = $collection_input_temp->where('material_code', '=', $data_map->material_code)
                                ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key]->id)
                                ->sum('q');
                            $nilai = $collection_input_temp->where('material_code', '=', $data_map->material_code)
                                ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key]->id)
                                ->sum('nilai');
                            $p = $nilai / $q ;
                            $type = $data_map->kategori_balans->type_kategori_balans;
                        }
                        elseif ($data_map->kategori_balans_id == 4){
                            $q = $data_map->get_data_nilai_pamakaian($data->id);
                            $p = $collection_input_temp->where('material_code', '=', $data_map->material_code)
                                ->where('kategori_balans_id', '=', 3)
                                ->where('asumsi_umum_id', '=', $main_asumsi->asumsi_umum[$key]->id)
                                ->sum('p');
                            $nilai = $q * $p;
                            $type = $data_map->kategori_balans->type_kategori_balans;
                        }
                        elseif ($data_map->kategori_balans_id == 5){
                            $q = $data_map->get_data_nilai_penjualan($data->id);
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


                            $q = $q_tersedia - $q_pj;
                            $nilai = $nilai_tersedia - $nilai_pj;
                            $type = $data_map->kategori_balans->type_kategori_balans;

                            if ($q == 0){
                                $p = 0;
                            }else{
                                $p = $nilai / $q;
                            }
                        }
                        elseif ($data_map->kategori_balans_id > 6){
                            $glos_cc = $data_map->get_data_glos_cc($data_map->plant_code);
                            if ($glos_cc->isNotEmpty()){
                                $check_simulasi = $collection_input_temp
                                    ->where('kategori_balans_id', '>', 6)
                                    ->where('asumsi_umum_id', '=', $data->id)
                                    ->where('material_code', $data_map->material_code)->first();

                                if ($check_simulasi == null){
                                    $p = (double) $data_map->get_data_simulasi($glos_cc, $data->id);
                                }else{
                                    $p = (double) $check_simulasi['p'];
                                }

                                if ($data_map->kategori_balans->type_kategori_balans == 'produksi'){
                                    $temp_q = $data_map->get_data_qty_renprod($glos_cc[0]->cost_center, $data->id);
                                    $q = $temp_q[0]->qty_rencana_produksi->sum('qty_renprod_value');
                                    $nilai = $q * $p;

                                }else{

                                    // cell q
                                    $temp_q = $data_map->get_data_qty_renprod($glos_cc[0]->cost_center, $data->id);
                                    $qty_renprod = (double) $temp_q[0]->qty_rencana_produksi->sum('qty_renprod_value');
                                    $cons_rate = (double) $data_map->get_data_cons_rate($data_map, $glos_cc[0]->plant_code, $data->id);
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

                        $collection_input_temp->push($this->submit_temp($data->id, $data_map->kategori_balans_id, $data_map->plant_code, $data_map->material_code, $q, $p, $nilai, $type));
                    }
                }

                $chunk = array_chunk($collection_input_temp->toArray(), 5000);
                foreach ($chunk as $insert){
                    Balans::insert($insert);
                }
            });
            return response()->json(['code' => 200]);
        }catch (\Exception $exception){
            dd($exception);
            return response()->json(['code' => 500]);
        }
    }

    public function submit_temp($asumsi, $kategori_balans, $plant_code, $material_code, $q, $p, $nilai, $type){
        $input['asumsi_umum_id'] = $asumsi;
        $input['kategori_balans_id'] = $kategori_balans;
        $input['plant_code'] = $plant_code;
        $input['material_code'] = $material_code;
        $input['q'] =(double) $q;
        $input['p'] =(double) $p;
        $input['nilai'] =(double) $nilai;
        $input['type_kategori_balans'] =$type;
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
