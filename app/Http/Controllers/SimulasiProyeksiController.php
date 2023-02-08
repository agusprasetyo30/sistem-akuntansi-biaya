<?php

namespace App\Http\Controllers;

use App\DataTables\Master\SimulasiProyeksiDataTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SimulasiProyeksiController extends Controller
{
    public function index(Request $request, SimulasiProyeksiDataTable $simulasiproyeksiDatatable)
    {
        if ($request->data == 'horizontal') {
            return $simulasiproyeksiDatatable->with(['version' => $request->version, 'plant' => $request->plant, 'produk' => $request->produk, 'cost_center' => $request->cost_center])->render('pages.simulasi_proyeksi.index');
        } else if ($request->data == 'version') {
            $asumsi = DB::table('asumsi_umum')
                ->where('version_id', $request->version)
                ->orderBy('month_year', 'ASC')
                ->get();

            $produk = DB::table('material')
                ->where('material_code', $request->produk)
                ->whereNull('deleted_at')
                ->get();

            $plant = DB::table('plant')
                ->where('plant_code', $request->plant)
                ->whereNull('deleted_at')
                ->get();

            $glos_cc = DB::table('glos_cc')
                ->where('material_code', $request->produk)
                ->where('plant_code', $request->plant)
                ->where('cost_center', $request->cost_center)
                ->first();

            if ($glos_cc) {
                // dd('a');
                return response()->json(['code' => 200, 'asumsi' => $asumsi, 'produk' => $produk, 'plant' => $plant]);
            } else {
                // dd('b');
                return setResponse([
                    'code' => 430,
                    'title' => 'Gagal membuat proyeksi',
                    'message' => 'Data yang anda cari tidak ditemukan!'
                ]);
            }
        }
        return view('pages.simulasi_proyeksi.index');
    }
}
