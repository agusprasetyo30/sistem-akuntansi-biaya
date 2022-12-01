<?php

namespace App\Http\Controllers;

use App\DataTables\Master\PeriodeDataTable;
use App\Imports\PeriodeImport;
use App\Models\Periode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PeriodeController extends Controller
{
    public function index(Request $request, PeriodeDataTable $periodeDataTable)
    {
        if ($request->data == 'index') {
            return $periodeDataTable->render('pages.master.periode.index');
        }
        return view('pages.master.periode.index');
    }

    public function create(Request $request)
    {
        dd(date('Y-m-d H:i:s', strtotime($request->awal_periode)));
        try {
            $validator = Validator::make($request->all(), [
                "nama" => 'required',
                "awal_periode" => 'required',
                "akhir_periode" => 'required',
                "is_active" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['periode_name'] = $request->nama;
            $input['awal_periode'] = date('Y-m-d H:i:s', strtotime($request->awal_periode));
            $input['akhir_periode'] = date('Y-m-d H:i:s', strtotime($request->akhir_periode));
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            Periode::create($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                "nama" => 'required',
                "awal_periode" => 'required',
                "akhir_periode" => 'required',
                "is_active" => 'required',
            ]);

            $input['periode_name'] = $request->nama;
            $input['awal_periode'] = date('Y-m-d', strtotime($request->awal_periode));
            $input['akhir_periode'] = date('Y-m-d', strtotime($request->akhir_periode));
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();
            DB::table('periode')
                ->where('id', $request->id)->update($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {
            Periode::where('id', $request->id)
                ->update([
                    'deleted_at' => Carbon::now(),
                    'deleted_by' => auth()->user()->id
                ]);
            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function import(Request $request)
    {
        try {
            $file = $request->file('file')->store('import');
            $import = new PeriodeImport;
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
