<?php

namespace App\Http\Controllers;

use App\DataTables\Master\QtyRenProdDataTable;
use App\Models\QtyRenProd;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $request->validate([
                "material_id" => 'required',
                "periode_id" => 'required',
                "region_id" => 'required',
                "qty_renprod_desc" => 'required',
                "qty_renprod_value" => 'required',
            ]);

            $input['material_id'] = $request->material_id;
            $input['periode_id'] = $request->periode_id;
            $input['region_id'] = $request->region_id;
            $input['qty_renprod_desc'] = $request->qty_renprod_desc;
            $input['qty_renprod_value'] = $request->qty_renprod_value;
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
            $request->validate([
                "material_id" => 'required',
                "periode_id" => 'required',
                "region_id" => 'required',
                "qty_renprod_desc" => 'required',
                "qty_renprod_value" => 'required',
            ]);

            $input['material_id'] = $request->material_id;
            $input['periode_id'] = $request->periode_id;
            $input['region_id'] = $request->region_id;
            $input['qty_renprod_desc'] = $request->qty_renprod_desc;
            $input['qty_renprod_value'] = $request->qty_renprod_value;
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
}
