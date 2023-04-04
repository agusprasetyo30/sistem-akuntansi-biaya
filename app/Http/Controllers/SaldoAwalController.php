<?php

namespace App\Http\Controllers;

use App\DataTables\Master\SaldoAwalDataTable;
use App\Exports\MultipleSheet\MS_SaldoAwalExport;
use App\Imports\SaldoAwalImport;
use App\Models\CostCenter;
use App\Models\GLAccount;
use App\Models\Material;
use App\Models\Plant;
use App\Models\Saldo_Awal;
use App\Models\Version_Asumsi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class SaldoAwalController extends Controller
{
    public function index(Request $request, SaldoAwalDataTable $saldoawalDataTable)
    {
        if ($request->data == 'index') {
            return $saldoawalDataTable->with(['filter_company' => $request->filter_company, 'filter_version' => $request->filter_version])->render('pages.buku_besar.saldo_awal.index');
        }
        return view('pages.buku_besar.saldo_awal.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "gl_account" => 'required',
                "valuation_class" => 'required',
                "price_control" => 'required',
                "material_code" => 'required',
                "plant_code" => 'required',
                "total_stock" => 'required',
                "total_value" => 'required',
                "version_id" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $total_value = (float) str_replace('.', '', str_replace('Rp ', '', $request->total_value));
            $harga_satuan = $total_value / $request->total_stock;

            $my = Version_Asumsi::where('id', $request->version_id)->first();

            $input['company_code'] = auth()->user()->company_code;
            $input['version_id'] = $request->version_id;
            $input['month_year'] = $my->saldo_awal;
            $input['gl_account'] = $request->gl_account;
            $input['valuation_class'] = $request->valuation_class;
            $input['price_control'] = $request->price_control;
            $input['material_code'] = $request->material_code;
            $input['plant_code'] = $request->plant_code;
            $input['total_stock'] = $request->total_stock;
            $input['total_value'] = $total_value;
            $input['nilai_satuan'] = $harga_satuan;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            $data_saldo_awal = Saldo_Awal::where([
                'company_code' => auth()->user()->company_code,
                'plant_code' => $request->plant_code,
                'version_id' => (int) $request->version_id,
                'material_code' => $request->material_code,
                'gl_account' => $request->gl_account,
            ])->first();

            if (!$data_saldo_awal) {
                Saldo_Awal::create($input);
            } else {
                Saldo_Awal::where('id', $data_saldo_awal->id)->update($input);
            }

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
                "gl_account" => 'required',
                "valuation_class" => 'required',
                "price_control" => 'required',
                "material_code" => 'required',
                "plant_code" => 'required',
                "total_stock" => 'required',
                "total_value" => 'required',
                "version_id" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $total_value = (float) str_replace('.', '', str_replace('Rp ', '', $request->total_value));
            $harga_satuan = $total_value / $request->total_stock;

            $my = Version_Asumsi::where('id', $request->version_id)->first();

            $input['company_code'] = auth()->user()->company_code;
            $input['version_id'] = $request->version_id;
            $input['month_year'] = $my->saldo_awal;
            $input['gl_account'] = $request->gl_account;
            $input['valuation_class'] = $request->valuation_class;
            $input['price_control'] = $request->price_control;
            $input['material_code'] = $request->material_code;
            $input['plant_code'] = $request->plant_code;
            $input['total_stock'] = $request->total_stock;
            $input['total_value'] = $total_value;
            $input['nilai_satuan'] = $harga_satuan;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            Saldo_Awal::where('id', $request->id)
                ->update($input);

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
            Saldo_Awal::where('id', $request->id)
                ->delete();

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

            DB::transaction(function () use ($request) {
                Saldo_Awal::where('version_id', $request->version)->delete();
                $file = $request->file('file')->store('import');
                $import = new SaldoAwalImport($request->version);
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
            });
            return setResponse([
                'code' => 200,
                'title' => 'Berhasil meng-import data'
            ]);
        } catch (\Exception $exception) {
            $empty_excel = Excel::toArray(new SaldoAwalImport($request->version), $request->file('file'));

            $plant = [];
            $plant_ = [];
            $gl_account = [];
            $gl_account_ = [];
            $material = [];
            $material_ = [];

            foreach ($empty_excel[0] as $key => $value) {
                array_push($plant, 'plant ' . $value['plant_code'] . ' tidak ada pada master');
                $d_plant = Plant::whereIn('plant_code', [$value['plant_code']])->first();
                if ($d_plant) {
                    array_push($plant_, 'plant ' . $d_plant->plant_code . ' tidak ada pada master');
                }

                array_push($gl_account, 'gl account ' . $value['gl_account'] . ' tidak ada pada master');
                $d_gl_account = GLAccount::whereIn('gl_account', [$value['gl_account']])->first();
                if ($d_gl_account) {
                    array_push($gl_account_, 'gl account ' . $d_gl_account->gl_account . ' tidak ada pada master');
                }

                array_push($material, 'material ' . $value['material_code'] . ' tidak ada pada master');
                $d_material = Material::whereIn('material_code', [$value['material_code']])->first();
                if ($d_material) {
                    array_push($material_, 'material ' . $d_material->material_code . ' tidak ada pada master');
                }
            }

            $result_plant = array_diff($plant, $plant_);
            $result_gl_account = array_diff($gl_account, $gl_account_);
            $result_material = array_diff($material, $material_);
            $result = array_merge($result_plant, $result_gl_account, $result_material);
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

    public function export(Request $request)
    {
        if (!$request->version) {
            return setResponse([
                'code' => 500,
            ]);
        }

        $version = $request->version;

        return Excel::download(new MS_SaldoAwalExport($version), 'saldo_awal.xlsx');
    }

    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // "file" => 'required',
            "version" => 'required',
        ], validatorMsg());

        if ($validator->fails())
            return $this->makeValidMsg($validator);
        try {
            $check = Saldo_Awal::where('version_id', $request->version)
                ->first();
            if ($check == null) {
                return setResponse([
                    'code' => 200,
                ]);
            } else {
                return setResponse([
                    'code' => 201,
                    'title' => 'Apakah anda yakin?',
                    'message' => 'Data Pada Versi Ini Telah Ada, Yakin Untuk Mengganti ?'
                ]);
            }
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
                'title' => $exception->getMessage()
            ]);
        }
    }

    public function check_input(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "gl_account" => 'required',
                "valuation_class" => 'required',
                "price_control" => 'required',
                "material_code" => 'required',
                "plant_code" => 'required',
                "total_stock" => 'required',
                "total_value" => 'required',
                "version_id" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $check = Saldo_Awal::where([
                'company_code' => auth()->user()->company_code,
                'plant_code' => $request->plant_code,
                'version_id' => (int) $request->version_id,
                'material_code' => $request->material_code,
                'gl_account' => $request->gl_account,
            ])->first();

            if ($check == null) {
                return setResponse([
                    'code' => 200,
                ]);
            } else {
                return setResponse([
                    'code' => 201,
                    'title' => 'Apakah anda yakin?',
                    'message' => 'Data Pada Versi Ini Telah Ada, Yakin Untuk Mengganti ?'
                ]);
            }
        } catch (\Throwable $th) {
            return setResponse([
                'code' => 400,
                'title' => $exception->getMessage()
            ]);
        }
    }
}
