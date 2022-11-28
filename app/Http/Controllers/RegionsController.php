<?php

namespace App\Http\Controllers;

use App\DataTables\Master\RegionDataTable;
use App\Models\Regions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class RegionsController extends Controller
{
    public function index(Request $request, RegionDataTable $regionDataTable)
    {
        if ($request->data == 'index') {
            //            dd($request->data);
            return $regionDataTable->render('pages.master.regions.index');
        }
        return view('pages.master.regions.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "nama" => 'required',
                "deskripsi" => 'required',
                "latitude" => 'required',
                "longtitude" => 'required',
                "is_active" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['region_name'] = $request->nama;
            $input['region_desc'] = $request->deskripsi;
            $input['latitude'] = $request->latitude;
            $input['longtitude'] = $request->longtitude;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            Regions::create($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required',
                "deskripsi" => 'required',
                "latitude" => 'required',
                "longtitude" => 'required',
                "is_active" => 'required',
            ]);

            $input['region_name'] = $request->nama;
            $input['region_desc'] = $request->deskripsi;
            $input['latitude'] = $request->latitude;
            $input['longtitude'] = $request->longtitude;
            $input['is_active'] = $request->is_active;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            Regions::where('id', $request->id)
                ->update($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        } catch (\Exception $exception) {
            //            dd($exception);
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $input['deleted_at'] = Carbon::now();
            $input['deleted_by'] = auth()->user()->id;

            Regions::where('id', $request->id)
                ->update($input);
            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }
}
