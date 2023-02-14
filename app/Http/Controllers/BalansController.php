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

        $antrian = antrian_material_balans(1);
//        dd($antrian);
        if ($request->data == 'index') {
            return $balansDataTable->with(['antrian' => $antrian[0], 'version' => 1, 'save' => false])->render('pages.buku_besar.balans.index');
        }
        return view('pages.buku_besar.balans.index');
    }

    public function index_header(Request $request){
        $asumsi = Asumsi_Umum::where('version_id', $request->version)->get();

        return response()->json(['code' => 200, 'asumsi' => $asumsi]);
    }

    public function store(){
        try {
            $antrian = antrian_material_balans(1);
            $data = new BalansStoreDataTable();
            $data->dataTable(1, $antrian);
            return response()->json(['code' => 200]);
        }catch (\Exception $exception){
            return response()->json(['code' => 500]);
        }
    }
}
