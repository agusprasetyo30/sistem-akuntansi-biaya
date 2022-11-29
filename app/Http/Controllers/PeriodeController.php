<?php

namespace App\Http\Controllers;

use App\DataTables\Master\PeriodeDataTable;
use App\Models\Periode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        try {
            $request->validate([
                "nama" => 'required',
                "awal_periode" => 'required',
                "akhir_periode" => 'required',
                "is_active" => 'required',
            ]);
//            dd($request);

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
//            dd($exception);
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
}
