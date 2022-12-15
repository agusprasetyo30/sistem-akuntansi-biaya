<?php

namespace App\Http\Controllers;

use App\DataTables\Master\ConsRateDataTable;
use App\Models\CostCenter;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ConsRateController extends Controller
{
    public function index(Request $request, ConsRateDataTable $consRateDataTable){

        if ($request->data == 'index'){
            return $consRateDataTable->render('pages.buku_besar.consrate.index');
        }

        return view('pages.buku_besar.consrate.index');

    }

    public function create(Request $request){
        try {
            $request->validate([
                "id_plant" => 'required',
                "code" => 'required',
                "deskripsi" => 'required',
                "is_active" => 'required',
            ]);


            $input['plant_id'] = $request->id_plant;
            $input['cost_center'] = $request->code;
            $input['cost_center_desc'] = $request->deskripsi;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            CostCenter::create($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        }catch (\Exception $exception){
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function update(Request $request){
        try {
            $request->validate([
                "id_plant" => 'required',
                "code" => 'required',
                "deskripsi" => 'required',
                "is_active" => 'required',
            ]);
//            dd($request);

            $input['plant_id'] = $request->id_plant;
            $input['cost_center'] = $request->code;
            $input['cost_center_desc'] = $request->deskripsi;
            $input['is_active'] = $request->is_active;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            CostCenter::where('id', $request->id)
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

            CostCenter::where('id', $request->id)
                ->update($input);
            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        }catch (\Exception $exception){
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }
}
