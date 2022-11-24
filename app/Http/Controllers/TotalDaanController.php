<?php

namespace App\Http\Controllers;

use App\DataTables\Master\TotalDaanDataTable;
use App\Models\TotalDaan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TotalDaanController extends Controller
{
    public function index(Request $request, TotalDaanDataTable $totaldaanDataTable)
    {
        if ($request->data == 'index') {
            return $totaldaanDataTable->render('pages.buku_besar.total_daan.index');
        }
        return view('pages.buku_besar.total_daan.index');
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                "material_id" => 'required',
                "periode_id" => 'required',
                "region_id" => 'required',
                "total_daan_desc" => 'required',
                "total_daan_value" => 'required',
            ]);

            $input['material_id'] = $request->material_id;
            $input['periode_id'] = $request->periode_id;
            $input['region_id'] = $request->region_id;
            $input['total_daan_desc'] = $request->total_daan_desc;
            $input['total_daan_value'] = $request->total_daan_value;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            TotalDaan::create($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                "material_id" => 'required',
                "periode_id" => 'required',
                "region_id" => 'required',
                "total_daan_desc" => 'required',
                "total_daan_value" => 'required',
            ]);

            $input['material_id'] = $request->material_id;
            $input['periode_id'] = $request->periode_id;
            $input['region_id'] = $request->region_id;
            $input['total_daan_desc'] = $request->total_daan_desc;
            $input['total_daan_value'] = $request->total_daan_value;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            TotalDaan::where('id', $request->id)
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

            TotalDaan::where('id', $request->id)
                ->update($input);
            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }
}
