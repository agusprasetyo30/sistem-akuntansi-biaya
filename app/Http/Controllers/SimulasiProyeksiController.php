<?php

namespace App\Http\Controllers;

use App\DataTables\Master\SimulasiProyeksiDataTable;
use App\DataTables\Master\SimulasiProyeksiStoreDataTable;
use App\Models\Asumsi_Umum;
use App\Models\GroupAccountFC;
use App\Models\Material;
use App\Models\Salr;
use App\Models\SimulasiProyeksi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SimulasiProyeksiController extends Controller
{
    public function hitung(Request $request)
    {
        try {
            $arr = [];

            $cons_rate = DB::table('cons_rate')
                ->select('cons_rate.product_code', 'cons_rate.plant_code', 'glos_cc.cost_center', 'cons_rate.version_id')
                ->leftJoin('glos_cc', 'glos_cc.material_code', '=', 'cons_rate.product_code')
                ->where('cons_rate.version_id', $request->version)
                ->groupBy('cons_rate.product_code', 'cons_rate.plant_code', 'glos_cc.cost_center', 'cons_rate.version_id')
                ->get();

            foreach ($cons_rate as $key => $cr) {
                $data_version = $cr->version_id;
                $data_plant = $cr->plant_code;
                $data_product = $cr->product_code;
                $data_cost_center = $cr->cost_center;

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
                    ->where('product_code', $data_product)
                    ->where('plant_code', $data_plant);

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
                    ->orderBy('kategori', 'asc')
                    ->get()->toArray();


                $asumsi = DB::table('asumsi_umum')
                    ->where('version_id', $data_version)
                    ->get();

                $cekBB = $query;
                $resBB = [];
                for ($i = 0; $i < count($cekBB); $i++) {
                    if ($cekBB[$i]->kategori != 0 && ($cekBB[$i]->no == 1 || $cekBB[$i]->no == 2 || $cekBB[$i]->no == 3 || $cekBB[$i]->no == 4)) {
                        array_push($resBB, $cekBB[$i]);
                    }
                }

                $gaLangsung = $query;
                $resgaLangsung = [];
                for ($i = 0; $i < count($gaLangsung); $i++) {
                    if ($gaLangsung[$i]->kategori != 0 && $gaLangsung[$i]->no == 6) {
                        array_push($resgaLangsung, $gaLangsung[$i]);
                    }
                }

                $gatidakLangsung = $query;
                $resgatidakLangsung = [];
                for ($i = 0; $i < count($gatidakLangsung); $i++) {
                    if ($gatidakLangsung[$i]->kategori != 0 && $gatidakLangsung[$i]->no == 8) {
                        array_push($resgatidakLangsung, $gatidakLangsung[$i]);
                    }
                }

                foreach ($asumsi as $key => $asum) {
                    $hs = '';
                    $consrate = '';
                    $biaya_perton = '';
                    $total_biaya = '';
                    $periode = $asum->id;
                    $inflasi = $asum->inflasi;

                    $tot_bb = [];
                    $perton_bb = [];
                    $perton_cogm = [];
                    $tot_cogm = [];
                    $perton_periodik = [];
                    $perton_ls = [];
                    $perton_tls = [];

                    $data_simpro = [];
                    array_push($data_simpro, $query);

                    // dd($asum->inflasi);
                    foreach ($data_simpro[0] as $key => $val) {

                        // dd($resBB, $resgaLangsung, $resgatidakLangsung);
                        $mat = Material::where('material_code', $val->code)->first();
                        $ga = GroupAccountFC::where('group_account_fc', $val->code)->first();

                        if ($mat) {
                            //ConsRate
                            $kp = SimulasiProyeksi::kuantumProduksi($data_cost_center, $periode);
                            $kp_val = 0;

                            if ($kp) {
                                if ($kp->qty_renprod_value == 1) {
                                    $consrate = 0;
                                } else {
                                    $consrate = SimulasiProyeksi::consRate($data_plant, $data_product, $val->code) ?? 0;
                                }
                                $kp_val = $kp->qty_renprod_value;
                            } else {
                                $consrate = 0;
                            }

                            //Harga Satuan dan Biaya Perton
                            if ($val->kategori == 1) {
                                //Harga Satuan
                                $res = SimulasiProyeksi::hsBalans($periode, $val->code, $data_product);
                                $hs = $res;

                                //Biaya Perton
                                if ($data_product == $val->code) {
                                    $biaya_perton = 0;
                                    array_push($perton_bb, $biaya_perton);
                                } else {
                                    $biaya_perton = $hs * $consrate;
                                    array_push($perton_bb, $biaya_perton);

                                    //Total Biaya
                                    $total_biaya = $biaya_perton * $kp_val;
                                    array_push($tot_bb, $total_biaya);
                                }
                            } else if ($val->kategori == 2) {
                                //Harga Satuan
                                $res = SimulasiProyeksi::hsZco($data_product, $data_plant,  $val->code);
                                $hs = $res;

                                //Biaya Perton
                                $biaya_perton = $hs * $consrate;
                                array_push($perton_bb, $biaya_perton);

                                //Total Biaya
                                $total_biaya = $biaya_perton * $kp_val;
                                array_push($tot_bb, $total_biaya);
                            } else if ($val->kategori == 3) {
                                //Harga Satuan
                                $res = SimulasiProyeksi::hsStock($val->code, $data_version);
                                $hs = $res;

                                //Biaya Perton
                                $biaya_perton = $hs * $consrate;
                                array_push($perton_bb, $biaya_perton);

                                //Total Biaya
                                $total_biaya = $biaya_perton * $kp_val;
                                array_push($tot_bb, $total_biaya);
                            } else if ($val->kategori == 4) {
                                //Harga Satuan
                                $res = SimulasiProyeksi::hsKantong($val->code, $data_version);
                                $hs = $res;

                                //Biaya Perton
                                $biaya_perton = $hs * $consrate;
                                array_push($perton_bb, $biaya_perton);

                                //Total Biaya
                                $total_biaya = $biaya_perton * $kp_val;
                                array_push($tot_bb, $total_biaya);
                            } else {
                                //Harga Satuan
                                $hs = '';

                                //Biaya Perton
                                $biaya_perton = '';

                                //Total Biaya
                                $total_biaya = '';
                            }
                        } else if ($ga) {
                            //Harga Satuan
                            $hs = '-';

                            //ConsRate
                            $consrate = '-';

                            //Biaya Perton dan Biaya Perton
                            $salr = Salr::getData($data_cost_center, $val->code);

                            if ($salr) {
                                $kp = SimulasiProyeksi::kuantumProduksi($data_cost_center, $periode);

                                $total = SimulasiProyeksi::totalSalr($salr->cost_center, $salr->group_account_fc, $inflasi);

                                //Total Biaya
                                $total_biaya = $total;

                                //Biaya Perton
                                $biaya_perton = 0;
                                if ($total > 0 && $kp != null) {
                                    $biaya_perton = $total / $kp->qty_renprod_value;
                                }
                            } else {
                                //Biaya Perton
                                $biaya_perton = '-';

                                //Total Biaya
                                $total_biaya = '-';
                            }
                        } else {
                            //Harga Satuan
                            $hs = '';

                            //ConsRate
                            $consrate = '';

                            //Biaya Perton dan Total Biaya
                            $kp = SimulasiProyeksi::kuantumProduksi($data_cost_center, $periode);

                            $kp_value = 0;
                            if ($kp) {
                                $kp_value = $kp->qty_renprod_value;
                            }

                            if ($val->no == 5) {
                                //Biaya Perton
                                // $res = SimulasiProyeksi::totalBB($resBB, $data_plant, $data_product, $data_version, $periode, $data_cost_center);
                                $res = array_sum($perton_bb);
                                $biaya_perton = $res;

                                //TotalBiaya
                                // $res_total_biaya = $biaya_perton * $kp_value;
                                $res_total_biaya = array_sum($tot_bb);
                                $total_biaya = $res_total_biaya;
                            } else if ($val->no == 7) {
                                //Biaya Perton
                                $res = SimulasiProyeksi::totalGL($resgaLangsung, $data_cost_center,  $periode, $inflasi);
                                array_push($perton_ls, $res);
                                $biaya_perton = $res;

                                //TotalBiaya
                                $res_total_biaya = $biaya_perton * $kp_value;
                                $total_biaya = $res_total_biaya;
                            } else if ($val->no == 9) {
                                //Biaya Perton
                                $res = SimulasiProyeksi::totalGL($resgatidakLangsung, $data_cost_center,  $periode, $inflasi);
                                array_push($perton_tls, $res);
                                $biaya_perton = $res;

                                //TotalBiaya
                                $res_total_biaya = $biaya_perton * $kp_value;
                                $total_biaya = $res_total_biaya;
                            } else if ($val->no == 10) {
                                //Biaya Perton
                                // $total_bb = SimulasiProyeksi::totalBB($resBB, $data_plant, $data_product, $data_version, $periode, $data_cost_center);
                                $total_bb = array_sum($perton_bb);
                                $total_gl_langsung = array_sum($perton_ls);
                                $total_gl_tidak_langsung = array_sum($perton_tls);
                                $cogm = $total_bb + $total_gl_langsung + $total_gl_tidak_langsung;
                                array_push($perton_cogm, $cogm);
                                $biaya_perton = $cogm;

                                //TotalBiaya
                                $total_bb_tb = array_sum($tot_bb);
                                $total_gl_langsung_tb =  $total_gl_langsung * $kp_value;
                                $total_gl_tidak_langsung_tb = $total_gl_tidak_langsung * $kp_value;
                                $cogm_tb = $total_bb_tb + $total_gl_langsung_tb + $total_gl_tidak_langsung_tb;
                                array_push($tot_cogm, $cogm_tb);

                                $total_biaya = $cogm_tb;
                            } else if ($val->no == 11) {
                                //Biaya Perton
                                $biaya_admin_umum = SimulasiProyeksi::labaRugi($data_product);

                                if ($biaya_admin_umum) {
                                    $res = $biaya_admin_umum->value_bau;
                                } else {
                                    $res = 0;
                                }

                                array_push($perton_periodik, $res);
                                $biaya_perton = $res;
                                $total_biaya = '';
                            } else if ($val->no == 12) {
                                //Biaya Perton
                                $biaya_pemasaran = SimulasiProyeksi::labaRugi($data_product);

                                if ($biaya_pemasaran) {
                                    $res = $biaya_pemasaran->value_bp;
                                } else {
                                    $res = 0;
                                }

                                array_push($perton_periodik, $res);
                                $biaya_perton = $res;
                                $total_biaya = '';
                            } else if ($val->no == 13) {
                                //Biaya Perton
                                $biaya_keuangan = SimulasiProyeksi::labaRugi($data_product);

                                if ($biaya_keuangan) {
                                    $res = $biaya_keuangan->value_bb;
                                } else {
                                    $res = 0;
                                }

                                array_push($perton_periodik, $res);
                                $biaya_perton = $res;
                                $total_biaya = '';
                            } else if ($val->no == 14) {
                                //Biaya Perton
                                // $biaya_periodik = SimulasiProyeksi::labaRugi($data_product);

                                // if ($biaya_periodik) {
                                //     $res =  $biaya_periodik->value_bp + $biaya_periodik->value_bau + $biaya_periodik->value_bb;
                                // } else {
                                //     $res = 0;
                                // }

                                // $biaya_perton = $res;
                                $biaya_perton = array_sum($perton_periodik);
                                $total_biaya = '';
                            } else if ($val->no == 15) {
                                //Biaya Perton
                                //periodik
                                // $biaya_periodik = SimulasiProyeksi::labaRugi($data_product);
                                // $bp = $biaya_periodik->value_bp ?? 0;
                                // $bau = $biaya_periodik->value_bau ?? 0;
                                // $bb = $biaya_periodik->value_bb ?? 0;

                                // $total_periodik =  $bp + $bau + $bb;
                                // $total_periodik =  array_sum($perton_periodik);

                                //cogm
                                // $total_bb = SimulasiProyeksi::totalBB($resBB, $data_plant, $data_product, $data_version, $periode, $data_cost_center) ?? 0;
                                // $total_gl_langsung = SimulasiProyeksi::totalGL($resgaLangsung, $data_cost_center,  $periode, $inflasi) ?? 0;
                                // $total_gl_tidak_langsung = SimulasiProyeksi::totalGL($resgatidakLangsung, $data_cost_center,  $periode, $inflasi) ?? 0;
                                // $total_cogm = $total_bb + $total_gl_langsung + $total_gl_tidak_langsung;

                                // $res = $total_cogm + $total_periodik;
                                $res_cogm = array_sum($perton_cogm);
                                $res_periodik = array_sum($perton_periodik);

                                $biaya_perton = $res_cogm + $res_periodik;


                                $total_biaya = '';
                            } else if ($val->no == 16) {
                                //Biaya Perton
                                //periodik
                                // $biaya_periodik = SimulasiProyeksi::labaRugi($data_product);
                                // $bp = $biaya_periodik->value_bp ?? 0;
                                // $bau = $biaya_periodik->value_bau ?? 0;
                                // $bb = $biaya_periodik->value_bb ?? 0;

                                // $total_periodik =  $bp + $bau + $bb;

                                // //cogm
                                // $total_bb = SimulasiProyeksi::totalBB($resBB, $data_plant, $data_product, $data_version, $periode, $data_cost_center) ?? 0;
                                // $total_gl_langsung = SimulasiProyeksi::totalGL($resgaLangsung, $data_cost_center,  $periode, $inflasi) ?? 0;
                                // $total_gl_tidak_langsung = SimulasiProyeksi::totalGL($resgatidakLangsung, $data_cost_center,  $periode, $inflasi) ?? 0;
                                // $total_cogm = $total_bb + $total_gl_langsung + $total_gl_tidak_langsung;

                                // $total_hpp = $total_cogm + $total_periodik;
                                // $kurs = $asum->usd_rate ?? 0;

                                $res_cogm = array_sum($perton_cogm);
                                $res_periodik = array_sum($perton_periodik);

                                $total_hpp = $res_cogm + $res_periodik;
                                $kurs = $asum->usd_rate ?? 0;

                                if ($total_hpp > 0 && $kurs > 0) {
                                    $total_hpp_usd = $total_hpp / $kurs;
                                } else {
                                    $total_hpp_usd = 0;
                                }

                                $biaya_perton = $total_hpp_usd;
                                $total_biaya = '';
                            } else {
                                $biaya_perton = '';
                                $total_biaya = '';
                            }
                        }


                        $val = (array) $val;
                        $val['harga_satuan'] = $hs;
                        $val['cr'] = $consrate;
                        $val['biaya_perton'] = $biaya_perton;
                        $val['total_biaya'] = $total_biaya;
                        $val['asumsi_umum_id'] = $periode;
                        $val['harga_satuan'] = $hs;
                        $val['version_id'] = $data_version;
                        $val['plant_code'] = $data_plant;
                        $val['product_code'] = $data_product;
                        $val['cost_center'] = $data_cost_center;
                        array_push($arr, $val);
                    }
                }
            }

            // dd($arr);
            DB::transaction(function () use ($arr) {
                $insert_data = [];

                foreach ($arr as $key => $res) {
                    $input['version_id'] = $res['version_id'];
                    $input['plant_code'] = $res['plant_code'];
                    $input['product_code'] = $res['product_code'];
                    $input['cost_center'] = $res['cost_center'];
                    $input['asumsi_umum_id'] = $res['asumsi_umum_id'];
                    $input['no'] = $res['no'];
                    $input['kategori'] = $res['kategori'];
                    $input['name'] = $res['name'];
                    $input['code'] = $res['code'];
                    $input['harga_satuan'] = (float) $res['harga_satuan'];
                    $input['cr'] = (float) $res['cr'];
                    $input['biaya_perton'] = (float) $res['biaya_perton'];
                    $input['total_biaya'] = (float) $res['total_biaya'];
                    $input['created_by'] = auth()->user()->id;
                    $input['created_at'] = Carbon::now()->format('Y-m-d');
                    $input['updated_at'] = Carbon::now()->format('Y-m-d');

                    $insert_data[] = $input;
                }

                $insert_data = collect($insert_data);

                $chunks = $insert_data->chunk(100);

                foreach ($chunks as $chunk) {
                    \DB::table('simulasi_proyeksi')->insert($chunk->toArray());
                }
            });
        } catch (\Throwable $th) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function index(Request $request, SimulasiProyeksiDataTable $simulasiproyeksiDatatable)
    {
        try {
            if ($request->data == 'index') {
                $glos_cc = DB::table('glos_cc')
                    ->where('glos_cc.material_code', $request->produk)
                    ->where('glos_cc.plant_code', $request->plant)
                    ->first();

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

            // $glos_cc = DB::table('glos_cc')
            //     ->where('glos_cc.material_code', $request->produk)
            //     ->where('glos_cc.plant_code', $request->plant)
            //     ->first();

            // if (!$glos_cc) {
            //     return setResponse([
            //         'code' => 430,
            //         'title' => 'Gagal menampilkan data',
            //         'message' => 'Data cost center tidak ditemukan!',
            //     ]);
            // }

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

            return response()->json(['code' => 200, 'asumsi' => $asumsi, 'produk' => $produk, 'plant' => $plant]);
        } catch (\Throwable $th) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function store(Request $request)
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
}
