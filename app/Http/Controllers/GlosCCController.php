<?php

namespace App\Http\Controllers;

use App\DataTables\Master\GlosCCDataTable;
use App\Exports\MultipleSheet\MS_GlosCCExport;
use App\Imports\GlosCCImport;
use App\Models\CostCenter;
use App\Models\GLosCC;
use App\Models\Material;
use App\Models\Plant;
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
            return $glosccDataTable->with(['filter_company' => $request->filter_company])->render('pages.master.glos_cc.index');
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
                'title' => $exception->getMessage()
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
                'title' => $exception->getMessage()
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
        } catch (\Exception $exception) {
            $empty_excel = Excel::toArray(new GlosCCImport(), $request->file('file'));

            $plant = [];
            $plant_ = [];
            $cost_center = [];
            $cost_center_ = [];
            $material = [];
            $material_ = [];

            foreach ($empty_excel[0] as $key => $value) {
                array_push($plant, 'plant ' . $value['plant_code'] . ' tidak ada pada master');
                $d_plant = Plant::whereIn('plant_code', [$value['plant_code']])->first();
                if ($d_plant) {
                    array_push($plant_, 'plant ' . $d_plant->plant_code . ' tidak ada pada master');
                }

                array_push($cost_center, 'cost center ' . $value['cost_center'] . ' tidak ada pada master');
                $d_cost_center = CostCenter::whereIn('cost_center', [$value['cost_center']])->first();
                if ($d_cost_center) {
                    array_push($cost_center_, 'cost center ' . $d_cost_center->cost_center . ' tidak ada pada master');
                }

                array_push($material, 'material ' . $value['material_code'] . ' tidak ada pada master');
                $d_material = Material::whereIn('material_code', [$value['material_code']])->first();
                if ($d_material) {
                    array_push($material_, 'material ' . $d_material->material_code . ' tidak ada pada master');
                }
            }

            $result_plant = array_diff($plant, $plant_);
            $result_cost_center = array_diff($cost_center, $cost_center_);
            $result_material = array_diff($material, $material_);
            $result = array_merge($result_plant, $result_cost_center, $result_material);
            $res = array_unique($result);

            if ($res) {
                $msg = '';

                foreach ($res as $message)
                    $msg .= '<p>' . $message . '</p>';

                return setResponse([
                    'code' => 430,
                    'title' => 'Gagal meng-import data',
                    'message' => $msg
                ]);
            } else {
                return setResponse([
                    'code' => 400,
                    'title' => $exception->getMessage()
                ]);
            }
        }
    }

    public function export()
    {
        return Excel::download(new MS_GlosCCExport, 'glos_cc.xlsx');
    }
}
