<?php

namespace App\Http\Controllers;

use App\DataTables\Master\MaterialDataTable;
use App\Exports\MultipleSheet\MS_MaterialExport;
use App\Imports\MaterialImport;
use App\Models\GroupAccount;
use App\Models\GroupAccountFC;
use App\Models\KategoriMaterial;
use App\Models\KategoriProduk;
use App\Models\MapKategoriBalans;
use App\Models\Material;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class MaterialController extends Controller
{
    public function index(Request $request, MaterialDataTable $materialDataTable)
    {
        if ($request->data == 'index') {
            return $materialDataTable->with(['filter_company' => $request->filter_company])->render('pages.master.material.index');
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
//                "kategori_produk_id" => 'required',
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
            $input['kategori_produk_id'] = $request->kategori_produk_id;
            $input['material_uom'] = $request->material_uom;
            $input['is_dummy'] = $request->is_dummy;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            DB::transaction(function () use ($input, $material_code, $request) {
                Material::create($input);
                if ($request->kategori_material_id == '1') {
                    mapping_plant_insert($material_code);
                }
            });

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
//            $required['kategori_produk_id'] = 'required';
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
            $input['kategori_produk_id'] = $request->kategori_produk_id;
            $input['material_uom'] = $request->material_uom;
            $input['is_dummy'] = $request->is_dummy;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            DB::transaction(function () use ($input, $material_code, $request) {
                $check_mapping_material_balans = MapKategoriBalans::where('material_code', $request->id)->first();

                DB::table('material')->where('material_code', $request->id)->update($input);
                if ($request->kategori_material_id == '1') {
                    if ($check_mapping_material_balans == null){
                        mapping_plant_insert($material_code);
                    }
                }
            });

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
                        array_push($err, '<p>' . $hasil . '</p>');
                    } catch (\Throwable $th) {
                        return response()->json(['Code' => $th->getCode(), 'msg' => $th->getMessage()]);
                    }
                }
                return setResponse([
                    'code' => 430,
                    'title' => 'Gagal meng-import data',
                    'message' => str_replace(',', '', implode(',', array_unique($err)))
                ]);
            }

            return setResponse([
                'code' => 200,
                'title' => 'Berhasil meng-import data'
            ]);
        } catch (\Exception $exception) {
            //            dd($exception);
            $empty_excel = Excel::toArray(new MaterialImport(), $request->file('file'));

            $grouo_account = [];
            $grouo_account_ = [];
            $kategori_material = [];
            $kategori_material_ = [];
            $kategori_produk = [];
            $kategori_produk_ = [];

            foreach ($empty_excel[0] as $key => $value) {
                array_push($grouo_account, 'Group Account ' . $value['group_account_code'] . ' tidak ada pada master');
                $d_grouoaccount = GroupAccount::whereIn('group_account_code', [$value['group_account_code']])->first();
                if ($d_grouoaccount) {
                    array_push($grouo_account_, 'Group Account ' . $d_grouoaccount->group_account_code . ' tidak ada pada master');
                }

                array_push($kategori_material, 'Kategori Material ID ' . $value['kategori_material_id'] . ' tidak ada pada master');
                $d_kategori_material = KategoriMaterial::where('id', $value['kategori_material_id'])->first();
                if ($d_kategori_material) {
                    array_push($kategori_material_, 'Kategori Material ID ' . $d_kategori_material->id . ' tidak ada pada master');
                }

                array_push($kategori_produk, 'Kategori Produk ID ' . $value['kategori_produk_id'] . ' tidak ada pada master');
                $d_kategori_produk = KategoriProduk::where('id', $value['kategori_produk_id'])->first();
                if ($d_kategori_produk) {
                    array_push($kategori_produk_, 'Kategori Produk ID ' . $d_kategori_produk->id . ' tidak ada pada master');
                }
            }

            $result_grouo_account = array_diff($grouo_account, $grouo_account_);
            $result_kategori_material = array_diff($kategori_material, $kategori_material_);
            $result_kategori_produk = array_diff($kategori_produk, $kategori_produk_);
            $result = array_merge($result_grouo_account, $result_kategori_material, $result_kategori_produk);
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
        return Excel::download(new MS_MaterialExport(), 'material.xlsx');
    }
}
