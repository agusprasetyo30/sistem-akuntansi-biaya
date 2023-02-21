<?php

namespace App\Http\Controllers;

use App\DataTables\Master\TarifDataTable;
use App\Exports\MultipleSheet\MS_TarifExport;
use App\Imports\TarifImport;
use App\Models\GroupAccountFC;
use App\Models\Material;
use App\Models\Plant;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class TarifController extends Controller
{
    public function index(Request $request, TarifDataTable $tarifDataTable)
    {
        if ($request->data == 'index') {
            return $tarifDataTable->render('pages.master.tarif.index');
        }
        return view('pages.master.tarif.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'plant_code' => 'required',
                'product_code' => 'required',
                'group_account_fc' => 'required',
                'tarif_value' => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['company_code'] = auth()->user()->company_code;
            $input['plant_code'] = $request->plant_code;
            $input['product_code'] = $request->product_code;
            $input['group_account_fc'] = $request->group_account_fc;
            $input['tarif_value'] = $request->tarif_value;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            Tarif::create($input);

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
                'product_code' => 'required',
                'group_account_fc' => 'required',
                'tarif_value' => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['company_code'] = auth()->user()->company_code;
            $input['plant_code'] = $request->plant_code;
            $input['product_code'] = $request->product_code;
            $input['group_account_fc'] = $request->group_account_fc;
            $input['tarif_value'] = $request->tarif_value;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();
            DB::table('tarif')
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
            Tarif::where('id', $request->id)->delete();

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
            $import = new TarifImport;
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
            $empty_excel = Excel::toArray(new TarifImport(), $request->file('file'));

            $plant = [];
            $plant_ = [];
            $group_account = [];
            $group_account_ = [];
            $produk = [];
            $produk_ = [];

            foreach ($empty_excel[0] as $key => $value) {
                array_push($plant, 'plant ' . $value['plant_code'] . ' tidak ada pada master');
                $d_plant = Plant::whereIn('plant_code', [$value['plant_code']])->first();
                if ($d_plant) {
                    array_push($plant_, 'plant ' . $d_plant->plant_code . ' tidak ada pada master');
                }

                array_push($group_account, 'group account ' . $value['group_account_fc'] . ' tidak ada pada master');
                $d_groupaccount = GroupAccountFC::whereIn('group_account_fc', [$value['group_account_fc']])->first();
                if ($d_groupaccount) {
                    array_push($group_account, 'group account ' . $d_groupaccount->group_account_code . ' tidak ada pada master');
                }

                array_push($produk, 'produk ' . $value['product_code'] . ' tidak ada pada master');
                $d_produk = Material::whereIn('material_code', [$value['product_code']])->first();
                if ($d_produk) {
                    array_push($produk_, 'produk ' . $d_produk->material_code . ' tidak ada pada master');
                }
            }

            $result_plant = array_diff($plant, $plant_);
            $result_group_account = array_diff($group_account, $group_account_);
            $result_produk = array_diff($produk, $produk_);
            $result = array_merge($result_plant, $result_group_account, $result_produk);
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
                ]);
            }
        }
    }

    public function export()
    {
        return Excel::download(new MS_TarifExport, 'tarif.xlsx');
    }
}
