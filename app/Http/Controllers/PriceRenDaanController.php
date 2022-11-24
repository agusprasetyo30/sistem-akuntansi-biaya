<?php

namespace App\Http\Controllers;

use App\DataTables\Master\PriceRenDaanDataTable;
use App\Models\PriceRenDaan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PriceRenDaanController extends Controller
{
    public function index(Request $request, PriceRenDaanDataTable $pricerendaanDataTable)
    {
        if ($request->data == 'index') {
            return $pricerendaanDataTable->render('pages.buku_besar.price_rendaan.index');
        }
        return view('pages.buku_besar.price_rendaan.index');
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                "material_id" => 'required',
                "periode_id" => 'required',
                "region_id" => 'required',
                "price_rendaan_desc" => 'required',
                "price_rendaan_value" => 'required',
            ]);

            $input['material_id'] = $request->material_id;
            $input['periode_id'] = $request->periode_id;
            $input['region_id'] = $request->region_id;
            $input['price_rendaan_desc'] = $request->price_rendaan_desc;
            $input['price_rendaan_value'] = $request->price_rendaan_value;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            PriceRenDaan::create($input);

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
                "price_rendaan_desc" => 'required',
                "price_rendaan_value" => 'required',
            ]);

            $input['material_id'] = $request->material_id;
            $input['periode_id'] = $request->periode_id;
            $input['region_id'] = $request->region_id;
            $input['price_rendaan_desc'] = $request->price_rendaan_desc;
            $input['price_rendaan_value'] = $request->price_rendaan_value;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            PriceRenDaan::where('id', $request->id)
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

            PriceRenDaan::where('id', $request->id)
                ->update($input);
            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }
}
