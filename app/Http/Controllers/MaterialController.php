<?php

namespace App\Http\Controllers;

use App\DataTables\Master\MaterialDataTable;
use App\Exports\MultipleSheet\MS_MaterialExport;
use App\Imports\MaterialImport;
use App\Models\Material;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
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
                "material_code" => 'required|unique:material,material_code',
                "material_name" => 'required',
                "material_desc" => 'required',
                "group_account_code" => 'required',
                "kategori_material_id" => 'required',
                "material_uom" => 'required',
                "is_dummy" => 'required',
                "is_active" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $material_code = str_replace(" ", "", $request->material_code);

            $input['company_code'] = auth()->user()->company_code;
            $input['material_code'] = $material_code;
            $input['material_name'] = $request->material_name;
            $input['material_desc'] = $request->material_desc;
            $input['group_account_code'] = $request->group_account_code;
            $input['kategori_material_id'] = $request->kategori_material_id;
            $input['material_uom'] = $request->material_uom;
            $input['is_dummy'] = $request->is_dummy;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            // dd($input);
            Material::create($input);

            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function update(Request $request)
    {
        try {
            $data = Material::where('material_code', $request->post('id'))->first();

            if (!$data)
                return setResponse([
                    'code' => 400,
                    'title' => 'Data Tidak Ditemukan!'
                ]);

            $required['material_name'] = 'required';
            $required['material_desc'] = 'required';
            $required['group_account_code'] = 'required';
            $required['kategori_material_id'] = 'required';
            $required['material_uom'] = 'required';
            $required['is_dummy'] = 'required';
            $required['is_active'] = 'required';

            if ($data->material_code != $request->post('material_code'))
                $required['material_code'] = 'required|unique:material,material_code';

            $validator = Validator::make($request->all(), $required, validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $material_code = str_replace(" ", "", $request->material_code);

            $input['company_code'] = auth()->user()->company_code;
            $input['material_code'] = $material_code;
            $input['material_name'] = $request->material_name;
            $input['material_desc'] = $request->material_desc;
            $input['group_account_code'] = $request->group_account_code;
            $input['kategori_material_id'] = $request->kategori_material_id;
            $input['material_uom'] = $request->material_uom;
            $input['is_dummy'] = $request->is_dummy;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            DB::table('material')->where('material_code', $request->id)->update($input);

            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function delete(Request $request)
    {
        try {
            Material::where('material_code', $request->id)->delete();

            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function import(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "file" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $file = $request->file('file')->store('import');
            $import = new MaterialImport;
            $import->import($file);

            $data_fail = $import->failures();

            if ($import->failures()->isNotEmpty()) {
                $err = [];

                foreach ($data_fail as $rows) {
                    try {
                        $er = implode(' ', array_values($rows->errors()));
                        $hasil = $rows->values()[$rows->attribute()] . ' ' . $er;
                        array_push($err, $hasil);
                    } catch (\Throwable $th) {
                        return response()->json(['Code' => $th->getCode(), 'msg' => $th->getMessage()]);
                    }
                }
                // dd(implode(' ', $err));
                return setResponse([
                    'code' => 500,
                    'title' => 'Gagal meng-import data',
                    'message' => $err
                ]);
            }

            return setResponse([
                'code' => 200,
                'title' => 'Berhasil meng-import data'
            ]);
        } catch (Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function export()
    {
        return Excel::download(new MS_MaterialExport(), 'material.xlsx');
    }
}
