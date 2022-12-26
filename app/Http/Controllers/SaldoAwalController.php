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

            Saldo_Awal::create($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
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

            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $input['deleted_at'] = Carbon::now();
            $input['deleted_by'] = auth()->user()->id;

            Saldo_Awal::where('id', $request->id)
                ->update($input);
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

            // if (!$request->version) {
            //     return response()->json(['Code' => 0]);
            // }

            // $sawal = Saldo_Awal::where('version_id', $request->version)->count();

            // if ($sawal > 0) {
            //     Saldo_Awal::where('version_id', $request->version)->delete();
            // }

            $version = $request->version;
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
                return response()->json(['Code' => 500, 'msg' => $err]);
            }

            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function export(Request $request)
    {
        if (!$request->version) {
            return response()->json(['Code' => 500]);
        }
        $version = $request->version;

        return Excel::download(new MS_SaldoAwalExport($version), 'saldo_awal.xlsx');
    }
}
