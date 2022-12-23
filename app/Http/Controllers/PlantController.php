<?php

namespace App\Http\Controllers;

use App\DataTables\Master\PlantDataTable;
use App\Exports\Template\T_PlantExport;
use App\Imports\PlantImport;
use App\Models\Periode;
use App\Models\Plant;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class PlantController extends Controller
{
    public function index(Request $request, PlantDataTable $plantDataTable)
    {
        if ($request->data == 'index') {
            return $plantDataTable->render('pages.master.plant.index');
        }
        return view('pages.master.plant.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|unique:plant,plant_code',
                'deskripsi' => 'required',
                'is_active' => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['plant_code'] = strtoupper($request->code);
            $input['plant_desc'] = $request->deskripsi;
            $input['is_active'] = $request->is_active;
            $input['company_code'] = auth()->user()->company_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();
            //            dd($input);

            Plant::create($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                "code" => 'required',
                "deskripsi" => 'required',
                "is_active" => 'required',
            ]);

            $input['plant_code'] = strtoupper($request->code);
            $input['plant_desc'] = $request->deskripsi;
            $input['is_active'] = $request->is_active;
            $input['company_code'] = auth()->user()->company_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();
            Plant::where('plant_code', $request->id)
                ->update($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {
            Plant::where('plant_code', $request->id)
                ->update([
                    'deleted_at' => Carbon::now(),
                    'deleted_by' => auth()->user()->id
                ]);
            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function import(Request $request)
    {
        try {
            if (!$request->file('file')) {
                return response()->json(['Code' => 0]);
            }

            $file = $request->file('file')->store('import');
            $import = new PlantImport;
            $import->import($file);

            $data_fail = $import->failures();

            if ($import->failures()->isNotEmpty()) {
                $err = [];

                foreach ($data_fail as $rows) {
                    $er = implode(' ', array_values($rows->errors()));
                    $hasil = $rows->values()[$rows->attribute()] . ' ' . $er;
                    array_push($err, $hasil);
                }
                // dd(implode(' ', $err));
                return response()->json(['Code' => 500, 'msg' => $err]);
            }

            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function export()
    {
        return Excel::download(new T_PlantExport, 'plant.xlsx');
    }
}
