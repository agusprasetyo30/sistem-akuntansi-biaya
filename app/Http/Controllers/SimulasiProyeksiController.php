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
            return $simulasiproyeksiDatatable->with(['version' => $request->version])->render('pages.simulasi_proyeksi.index');
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
            return response()->json(['code' => 200, 'asumsi' => $asumsi, 'produk' => $produk, 'plant' => $plant]);
        }
        return view('pages.simulasi_proyeksi.index');
    }
}
