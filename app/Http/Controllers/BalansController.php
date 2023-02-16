<?php

namespace App\Http\Controllers;

use App\DataTables\Master\BalansDataTable;
use App\DataTables\Master\BalansStoreDataTable;
use App\Models\Asumsi_Umum;
use App\Models\Balans;
use App\Models\ConsRate;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            return $balansDataTable->with(['antrian' => array_values(array_unique($result_antrian)), 'version' => $request->version, 'save' => false])->render('pages.buku_besar.balans.index');
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

                $data = new BalansStoreDataTable();
                $data->dataTable($request->version, array_values(array_unique($result_antrian)));
            });


            return response()->json(['code' => 200]);
        }catch (\Exception $exception){
            return response()->json(['code' => 500]);
        }
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
