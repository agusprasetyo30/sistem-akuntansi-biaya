<?php

namespace App\Http\Controllers;

use App\DataTables\Master\QtyRenProdDataTable;
use App\Exports\MultipleSheet\MS_KuantitiRenProdExport;
use App\Imports\QtyRenProdImport;
use App\Models\Asumsi_Umum;
use App\Models\QtyRenProd;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class QtyRenProdController extends Controller
{
    public function index(Request $request, QtyRenProdDataTable $qtyrenprodDataTable)
    {
        if ($request->data == 'index') {
            return $qtyrenprodDataTable->render('pages.buku_besar.qty_renprod.index');
        }
        return view('pages.buku_besar.qty_renprod.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "material_code" => 'required',
                "qty_renprod_value" => 'required',
                "version_id" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $qty_renprod_value = (float) str_replace('.', '', str_replace('Rp ', '', $request->qty_renprod_value));

            $input['company_code'] = auth()->user()->company_code;
            $input['material_code'] = $request->material_code;
            $input['version_id'] = $request->version_id;
            $input['asumsi_umum_id'] = $request->month_year;
            $input['qty_renprod_value'] = $qty_renprod_value;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            QtyRenProd::create($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "material_code" => 'required',
                "qty_renprod_value" => 'required',
                "version_id" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $qty_renprod_value = (float) str_replace('.', '', str_replace('Rp ', '', $request->qty_renprod_value));

            $input['company_code'] = auth()->user()->company_code;
            $input['material_code'] = $request->material_code;
            $input['version_id'] = $request->version_id;
            $input['asumsi_umum_id'] = $request->month_year;
            $input['qty_renprod_value'] = $qty_renprod_value;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            QtyRenProd::where('id', $request->id)
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

            QtyRenProd::where('id', $request->id)
                ->update($input);
            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
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
                // $excel = Excel::toArray(new QtyRenProdImport($version), $request->file);
                // $colect = collect($excel[0]);
                // $header = array_keys($colect[0]);
                // $data_versi = explode('_', $header[1]);
                // $version = Asumsi_Umum::where('id', $data_versi[2])->first();
                QtyRenProd::where('version_id', $version)->delete();

                $file = $request->file('file')->store('import');
                $import = new QtyRenProdImport($version);
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
            });

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

        return Excel::download(new MS_KuantitiRenProdExport($version), 'qty_renprod.xlsx');
    }

    public function check(Request $request)
    {
        try {
            $check = QtyRenProd::where('version_id', $request->version)
                ->first();
            if ($check == null) {
                return response()->json(['Code' => 200, 'msg' => 'Data Tidak Ada']);
            } else {
                return response()->json(['Code' => 201, 'msg' => 'Data Ada']);
            }
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }
}
