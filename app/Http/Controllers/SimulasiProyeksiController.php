<?php

namespace App\Http\Controllers;

use App\DataTables\Master\SimulasiProyeksiDataTable;
use App\DataTables\Master\SimulasiProyeksiStoreDataTable;
use App\Models\Asumsi_Umum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SimulasiProyeksiController extends Controller
{
    public function index(Request $request, SimulasiProyeksiDataTable $simulasiproyeksiDatatable)
    {
        if ($request->data == 'index') {
            $cons_rate = DB::table('cons_rate')
                ->select('cons_rate.product_code', 'cons_rate.plant_code', 'glos_cc.cost_center', 'cons_rate.version_id')
                ->leftJoin('glos_cc', 'glos_cc.material_code', '=', 'cons_rate.product_code')
                ->where('cons_rate.version_id', $request->version)
                ->groupBy('cons_rate.product_code', 'cons_rate.plant_code', 'glos_cc.cost_center', 'cons_rate.version_id')
                ->get();

            foreach ($cons_rate as $key => $cr) {
                return $simulasiproyeksiDatatable->with(['version' => $cr->version_id, 'plant' => $cr->plant_code, 'produk' => $cr->product_code, 'cost_center' => $cr->cost_center, 'save' => false])->render('pages.simulasi_proyeksi.index');
            }
        }
        return view('pages.simulasi_proyeksi.index');
    }

    public function index_header(Request $request)
    {

        $produk = DB::table('material')
            ->where('material_code', $request->produk)
            ->whereNull('deleted_at')
            ->get();

        $plant = DB::table('plant')
            ->where('plant_code', $request->plant)
            ->whereNull('deleted_at')
            ->get();

        $asumsi = Asumsi_Umum::where('version_id', $request->version)->get();

        return response()->json(['code' => 200, 'asumsi' => $asumsi, 'produk' => $produk, 'plant' => $plant]);
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

            foreach ($cons_rate as $key => $cr) {
                $data = new SimulasiProyeksiStoreDataTable();
                $data->dataTable($cr->version_id, $cr->plant_code, $cr->product_code, $cr->cost_center);
            }
            // return response()->json(['code' => 200]);
        } catch (\Exception $exception) {
            return response()->json(['code' => 500]);
        }
    }
}
