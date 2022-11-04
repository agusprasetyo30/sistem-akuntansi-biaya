<?php

namespace App\Http\Controllers;

use App\DataTables\Master\AsumsiUmumDataTable;
use App\Models\Asumsi_Umum;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AsumsiUmumController extends Controller
{
    public function index(Request $request, AsumsiUmumDataTable $asumsiUmumDataTable){
        if ($request->data == 'index'){
//            dd($request->data);
            return $asumsiUmumDataTable->render('pages.buku_besar.asumsi_umum.index');
        }
        return view('pages.buku_besar.asumsi_umum.index');
    }

    public function create(Request $request){
        try {
//            dd($request);
            $request->validate([
                "id_periode" => 'required',
                "kurs" => 'required',
                "handling_bb" => 'required',
                "data_saldo_awal" => 'required',
            ]);


            $input['periode_id'] = $request->id_periode;
            $input['kurs'] = $request->kurs;
            $input['handling_bb'] = $request->handling_bb;
//            $input['data_saldo_awal'] = $request->data_saldo_awal;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            Asumsi_Umum::create($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        }catch (\Exception $exception){
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function update(Request $request){
        try {
//            dd($request);
            $request->validate([
                "id_periode" => 'required',
                "kurs" => 'required',
                "handling_bb" => 'required',
                "data_saldo_awal" => 'required',
            ]);

            $input['periode_id'] = $request->id_periode;
            $input['kurs'] = $request->kurs;
            $input['handling_bb'] = $request->handling_bb;
//            $input['data_saldo_awal'] = $request->data_saldo_awal;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            Asumsi_Umum::where('id', $request->id)
                ->update($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        }catch (\Exception $exception){
//            dd($exception);
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function delete(Request $request){
        try {

            $input['deleted_at'] = Carbon::now();
            $input['deleted_by'] = auth()->user()->id;

            Asumsi_Umum::where('id', $request->id)
                ->update($input);
            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        }catch (\Exception $exception){
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }
}
