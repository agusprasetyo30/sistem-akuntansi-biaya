<?php

namespace App\Http\Controllers;

use App\DataTables\Master\SaldoAwalDataTable;
use App\Exports\MultipleSheet\MS_SaldoAwalExport;
use App\Imports\SaldoAwalImport;
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
            return $saldoawalDataTable->render('pages.buku_besar.saldo_awal.index');
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
        $validator = Validator::make($request->all(), [
            "file" => 'required',
            "version" => 'required',
        ], validatorMsg());

        if ($validator->fails())
            return $this->makeValidMsg($validator);

        try {
            DB::transaction(function () use ($request) {
                $version = $request->version;
                // $excel = Excel::toArray(new SaldoAwalImport($version), $request->file);
                // $colect = collect($excel[0]);
                // $header = array_keys($colect[0]);
                // $data_versi = explode('_', $header[7]);
                Saldo_Awal::where('version_id', $version)->delete();

                $file = $request->file('file')->store('import');
                $import = new SaldoAwalImport($version);
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
                    ]);
                }
            });

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
            ]);
        }
    }
}
