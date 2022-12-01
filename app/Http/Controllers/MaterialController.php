<?php

namespace App\Http\Controllers;

use App\DataTables\Master\MaterialDataTable;
use App\Imports\MaterialImport;
use App\Models\Material;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class MaterialController extends Controller
{
    public function index(Request $request, MaterialDataTable $materialDataTable)
    {
        if ($request->data == 'index') {
            return $materialDataTable->render('pages.master.material.index');
        }
        return view('pages.master.material.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "material_name" => 'required',
                "material_desc" => 'required',
                "kategori_material_id" => 'required',
                "uom" => 'required',
                "is_dummy" => 'required',
                "is_active" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['material_name'] = $request->material_name;
            $input['material_desc'] = $request->material_desc;
            $input['kategori_material_id'] = $request->kategori_material_id;
            $input['uom'] = $request->uom;
            $input['is_dummy'] = $request->is_dummy;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            Material::create($input);

            // return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
            return setResponse([
                'code' => 200,
                'title' => 'Data Berhasil Disimpan'
            ]);
        } catch (\Exception $error) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                "material_name" => 'required',
                "material_desc" => 'required',
                "kategori_material_id" => 'required',
                "uom" => 'required',
                "is_dummy" => 'required',
                "is_active" => 'required',
            ]);

            $input['material_name'] = $request->material_name;
            $input['material_desc'] = $request->material_desc;
            $input['kategori_material_id'] = $request->kategori_material_id;
            $input['uom'] = $request->uom;
            $input['is_dummy'] = $request->is_dummy;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            DB::table('material')->where('id', $request->id)->update($input);

            return setResponse([
                'code' => 200,
                'title' => 'Data Berhasil Disimpan'
            ]);
        } catch (\Exception $error) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function delete(Request $request)
    {
        try {
            Material::where('id', $request->id)
                ->update([
                    'deleted_at' => Carbon::now(),
                    'deleted_by' => auth()->user()->id
                ]);
            return setResponse([
                'code' => 200,
                'title' => 'Data Berhasil Disimpan',
            ]);
        } catch (\Exception $error) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function import(Request $request)
    {
        try {
            $file = $request->file('file')->store('import');
            $import = new MaterialImport;
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
