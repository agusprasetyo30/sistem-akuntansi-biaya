<?php

namespace App\Http\Controllers;

use App\DataTables\Master\CostCenterDataTable;
use App\Models\CostCenter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CostCenterController extends Controller
{
    public function index(Request $request, CostCenterDataTable $costCenterDataTable){
        if ($request->data == 'index'){
//            dd($request->data);
            return $costCenterDataTable->render('pages.master.cost_center.index');
        }
        return view('pages.master.cost_center.index');
    }

    public function create(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                "code" => 'required',
                "deskripsi" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);


            $input['cost_center'] = $request->code;
            $input['cost_center_desc'] = $request->deskripsi;
            $input['company_code'] = auth()->user()->company_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            CostCenter::create($input);

            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil disimpan'
            ]);
        }catch (\Exception $exception){
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function update(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                "code" => 'required',
                "deskripsi" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['cost_center'] = $request->code;
            $input['cost_center_desc'] = $request->deskripsi;
            $input['company_code'] = auth()->user()->company_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            CostCenter::where('cost_center', $request->code)
                ->update($input);

            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil disimpan'
            ]);
        }catch (\Exception $exception){
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function delete(Request $request){
        try {

            $input['deleted_at'] = Carbon::now();
            $input['deleted_by'] = auth()->user()->id;

            CostCenter::where('cost_center', $request->id)
                ->delete();
            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil disimpan'
            ]);
        }catch (\Exception $exception){
            return setResponse([
                'code' => 400,
            ]);
        }
    }
}
