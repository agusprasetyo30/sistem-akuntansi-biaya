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
//        $result_antrian = [];
//        foreach ($antrian as $items){
//            foreach ($items as $item){
//                array_push($result_antrian, $item);
//            }
//        }
        if ($request->data == 'index') {
            return $balansDataTable->with(['antrian' => $antrian, 'version' => $request->version, 'material' => $request->material])->render('pages.buku_besar.balans.index');
        }
        return view('pages.buku_besar.balans.index');

    }


    public function index_header(Request $request){
        $asumsi = Asumsi_Umum::where('version_id', $request->version)->get();

        return response()->json(['code' => 200, 'asumsi' => $asumsi]);
    }

    public function export(Request $request, BalansDataTable $balansDataTable)
    {
        // ini
        $antrian = antrian_material_balans($request->version);
        // $antrian = antrian_material_balans($request->version);
        // $result_antrian = [];
        // foreach ($antrian as $items){
        //     foreach ($items as $item){
        //         array_push($result_antrian, $item);
        //     }
        // }

        // $antrian = array_values(array_unique($result_antrian));

        $balans_data = Balans::select('kategori_balans_id','material_code', 'plant_code', 'company_code', 'kategori_balans_desc', 'order_view')
            ->whereIn('material_code', $request->material == 'all' ? $antrian : [$request->material])
            ->where('version_id', $request->version)
            ->groupBy('kategori_balans_id','material_code', 'plant_code', 'company_code', 'kategori_balans_desc', 'order_view')
            ->orderBy('material_code', 'ASC')
            ->orderBy('order_view', 'ASC')->get();

        $main_asumsi = Asumsi_Umum::where('version_id', $request->version)->get();

        // Query data untuk mendapatkan data balans default
        $balans_default = Balans::where('version_id', $request->version)
            ->get();

        $temporary_value['p'] = [];
        $temporary_value['q'] = [];
        $temporary_value['nilai'] = [];

        // Filtering Balans data dan Filtering
        foreach ($balans_data as $query) {
            // Melakukan filtering sesuai dengan data main asumsi
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

                // Memasukan data yang sudah difilter kedalam array & ditambahkan key sesuai dengan lokasi asumsi umum
                array_push($temporary_value['p'], ["key" => $key_sub, "value" => $p_value_temp->p]);
                array_push($temporary_value['q'], ["key" => $key_sub, "value" => $q_value_temp->q]);
                array_push($temporary_value['nilai'], ["key" => $key_sub, "value" => $result_value_temp->nilai]);
            }

        }

        // Menghitung jumlah total asumsi umum sebagai acuan index
        $main_asumsi_index_count = $main_asumsi->count() - 1;

        // Memisahkan data array yang disesuaikan dengan array key & transaksi (p, q, nilai)
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

            $msg = '';

            $cons_rate = ConsRate::with(
                ['glos_cc1']
            )
                ->select('product_code', 'plant_code', 'version_id')
                ->where('version_id', $request->version)
                ->groupBy('product_code', 'plant_code', 'version_id')
                ->get();

            foreach ($cons_rate as $items_cost_center){
                if ($items_cost_center->glos_cc1 == null){
                    $msg .= '<p>Mapping Cost Center Dengan Product ' . $items_cost_center->product_code . ' Pada Plant '.$items_cost_center->plant_code.' Tidak Ada Pada Gloss CC</p>';
                }
            }


            if ($msg != ''){
                return setResponse([
                    'code' => 430,
                    'title' => 'Data Gagal Diproyeksikan',
                    'message' => $msg,
                ]);
            }

            $antrian = array_unique(antrian_material_balans($request->version));

//            dd($antrian);
            $result_antrian = "";
            foreach ($antrian as $key_antrian =>$items_antrian){
                if ($key_antrian == 0){
                    $result_antrian .="'".$items_antrian."'";
                }else{
                    $result_antrian .=",'".$items_antrian."'";
                }
            }

            DB::transaction(function () use ($request, $antrian, $result_antrian){
                Balans::leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'balans.asumsi_umum_id')
                    ->where('asumsi_umum.version_id', $request->version)->delete();

                SimulasiProyeksi::where('version_id', $request->version)->delete();


                $simulasi_create = new SimulasiProyeksiController();
                $main_asumsi = Version_Asumsi::with('asumsi_umum:id,version_id,month_year,saldo_awal,usd_rate,adjustment,inflasi')
                    ->select('id', 'version')
                    ->where([
                        'id' => $request->version,
                        'company_code' => auth()->user()->company_code
                    ])->first();
                try {

                    $query = MapKategoriBalans::with(['material:material_code,material_name' ,'glos_cc', 'kategori_balans', 'saldo_awal:material_code,total_stock,total_value', 'pemakaian:material_code,pj_pemakaian_value,asumsi_umum_id', 'penjualan:material_code,pj_penjualan_value,asumsi_umum_id', 'price_rencana_pengadaan:material_code,price_rendaan_value,asumsi_umum_id', 'qty_rencana_pengadaan:material_code,qty_rendaan_value,asumsi_umum_id', 'const_rate', 'simulasi_proyeksi'])
                        ->select('kategori_balans.order_view', 'map_kategori_balans.kategori_balans_id','map_kategori_balans.material_code', 'map_kategori_balans.plant_code', 'map_kategori_balans.company_code')
                        ->leftJoin('kategori_balans', 'kategori_balans.id', '=', 'map_kategori_balans.kategori_balans_id')
                        ->whereIn('map_kategori_balans.material_code', $antrian)
                        ->where('map_kategori_balans.version_id', $request->version)
                        ->orderBy(DB::raw("array_position(ARRAY[".$result_antrian."]::varchar[],map_kategori_balans.material_code)"))
                        ->orderBy('kategori_balans.order_view', 'ASC')
//                        ->groupBy('kategori_balans.order_view', 'map_kategori_balans.kategori_balans_id','map_kategori_balans.material_code', 'map_kategori_balans.plant_code', 'map_kategori_balans.company_code')
                        ->get();

                }catch (\Exception $exception){
//                    dd(implode(',', $antrian));
                    dd($exception);
                }
//                dd($query);

                try {
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

//                                dd($data_map->material_code, $collection_input_temp->where('kategori_balans_id', 3));
                                $glos_cc = $data_map->get_data_glos_cc($data_map->plant_code);
                                if ($glos_cc != null){
                                    $check_simulasi = $collection_input_temp
                                        ->where('kategori_balans_id', '>', 6)
                                        ->where('cost_center', '=', $glos_cc->cost_center)
                                        ->where('material_code', $data_map->material_code)
                                        ->first();
                                    if ($check_simulasi == null){
                                        $simulasi_create->hitung_satuan_simpro($request->version, $data, $glos_cc->plant_code, $data_map->material_code, $glos_cc->cost_center, $collection_input_temp);

                                        $sim = SimulasiProyeksi::where('product_code', '2000002')
                                            ->where('asumsi_umum_id', 10)
                                            ->where('name', 'COGM')
                                            ->where('plant_code', 'B030')
                                            ->first();

                                        $p = (double) $data_map->get_data_simulasi($glos_cc, $data->id);
                                    }else{
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
                }catch (\Exception $exception){
                    dd($exception);
                }

                $chunk = array_chunk($collection_input_temp->toArray(), 5000);
                foreach ($chunk as $insert){
                    Balans::insert($insert);
                }

                SimulasiProyeksi::where('version_id', $request->version)->delete();
                $simulasi_create->hitung_simpro($request->version);

            });

            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil Diproyeksikan'
            ]);
        }catch (\Exception   $exception){
            return setResponse([
                'code' => 400,
                'title' => $exception->getMessage(),
                'message' => $exception->getMessage(),
            ]);
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
