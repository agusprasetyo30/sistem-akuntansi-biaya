<?php

namespace App\Http\Controllers;

use App\DataTables\Master\GlosCCDataTable;
use App\Exports\MultipleSheet\MS_GlosCCExport;
use App\Imports\GlosCCImport;
use App\Models\GLosCC;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class GlosCCController extends Controller
{
    public function index(Request $request, GlosCCDataTable $glosccDataTable)
    {
        if ($request->data == 'index') {
            return $glosccDataTable->render('pages.master.glos_cc.index');
        }
        return view('pages.master.glos_cc.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'plant_code' => 'required',
                'cost_center' => 'required',
                'material_code' => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['company_code'] = auth()->user()->company_code;
            $input['plant_code'] = $request->plant_code;
            $input['cost_center'] = $request->cost_center;
            $input['material_code'] = $request->material_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            GLosCC::create($input);

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
            $validator = Validator::make($request->all(), [
                'plant_code' => 'required',
                'cost_center' => 'required',
                'material_code' => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['company_code'] = auth()->user()->company_code;
            $input['plant_code'] = $request->plant_code;
            $input['cost_center'] = $request->cost_center;
            $input['material_code'] = $request->material_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();
            DB::table('glos_cc')
                ->where('id', $request->id)->update($input);

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
            // $material = GLosCC::get_kategori($request->id);

            // if ($material) {
            //     return setResponse([
            //         'code' => 400,
            //         'title' => 'Kategori masih digunakan, kategori hanya bisa dinonaktifkan!'
            //     ]);
            // } else {
            GLosCC::where('id', $request->id)->delete();

            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil dihapus'
            ]);
            // }
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
            $import = new GlosCCImport;
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
        return Excel::download(new MS_GlosCCExport, 'glos_cc.xlsx');
    }
}
