<?php

namespace App\Http\Controllers;

use App\DataTables\Master\SimulasiProyeksiDataTable;
use App\DataTables\Master\SimulasiProyeksiStoreDataTable;
use App\Models\Asumsi_Umum;
use App\Models\SimulasiProyeksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SimulasiProyeksiController extends Controller
{
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
            $validator = Validator::make($request->all(), [
                "version" => 'required',
                "produk" => 'required',
                "plant" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $produk = DB::table('material')
                ->where('material_code', $request->produk)
                ->whereNull('deleted_at')
                ->get();

            $plant = DB::table('plant')
                ->where('plant_code', $request->plant)
                ->whereNull('deleted_at')
                ->get();

            $asumsi = Asumsi_Umum::where('version_id', $request->version)->get();

            $glos_cc = DB::table('glos_cc')
                ->where('glos_cc.material_code', $request->produk)
                ->where('glos_cc.plant_code', $request->plant)
                ->first();

            if (!$glos_cc) {
                return setResponse([
                    'code' => 430,
                    'title' => 'Gagal menampilkan data',
                    'message' => 'Data cost center tidak ditemukan!',
                ]);
            }

            $simpro = DB::table('simulasi_proyeksi')
                ->where('simulasi_proyeksi.product_code', $request->produk)
                ->where('simulasi_proyeksi.plant_code', $request->plant)
                ->where('simulasi_proyeksi.cost_center', $glos_cc->cost_center)
                ->first();

            if (!$simpro) {
                return setResponse([
                    'code' => 430,
                    'title' => 'Gagal menampilkan data',
                    'message' => 'Data simulasi proyeksi tidak ditemukan!',
                ]);
            }

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
