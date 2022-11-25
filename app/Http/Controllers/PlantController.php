<?php

namespace App\Http\Controllers;

use App\DataTables\Master\PlantDataTable;
use App\Imports\PlantImport;
use App\Models\Plant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $request->validate([
                "code" => 'required',
                "deskripsi" => 'required',
                "is_active" => 'required',
            ]);


            $input['plant_code'] = $request->code;
            $input['plant_desc'] = $request->deskripsi;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

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

            $input['plant_code'] = $request->code;
            $input['plant_desc'] = $request->deskripsi;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();
            DB::table('plant')
                ->where('id', $request->id)->update($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {
            Plant::where('id', $request->id)
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
}
