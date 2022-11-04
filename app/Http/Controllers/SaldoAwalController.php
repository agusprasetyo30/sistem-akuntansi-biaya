<?php

namespace App\Http\Controllers;

use App\DataTables\Master\SaldoAwalDataTable;
use App\Models\Saldo_Awal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaldoAwalController extends Controller
{
    public function index(Request $request, SaldoAwalDataTable $saldoawalDataTable){
        if ($request->data == 'index'){
            return $saldoawalDataTable->render('pages.buku_besar.saldo_awal.index');
        }
        return view('pages.buku_besar.saldo_awal.index');
    }

    public function create(Request $request){
        try {
            $request->validate([
                "company_code" => 'required',
                "gl_account" => 'required',
                "valuation_class" => 'required',
                "price_control" => 'required',
                "material_id" => 'required',
                "plant_id" => 'required',
                "total_stock" => 'required',
                "total_value" => 'required',
                "nilai_satuan" => 'required',
            ]);

            $input['company_code'] = $request->company_code;
            $input['gl_account'] = $request->gl_account;
            $input['valuation_class'] = $request->valuation_class;
            $input['price_control'] = $request->price_control;
            $input['material_id'] = $request->material_id;
            $input['plant_id'] = $request->plant_id;
            $input['total_stock'] = $request->total_stock;
            $input['total_value'] = $request->total_value;
            $input['nilai_satuan'] = $request->nilai_satuan;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            Saldo_Awal::create($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        }catch (\Exception $exception){
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function update(Request $request){
        try {
            $request->validate([
                "company_code" => 'required',
                "gl_account" => 'required',
                "valuation_class" => 'required',
                "price_control" => 'required',
                "material_id" => 'required',
                "plant_id" => 'required',
                "total_stock" => 'required',
                "total_value" => 'required',
                "nilai_satuan" => 'required',
            ]);

            $input['company_code'] = $request->company_code;
            $input['gl_account'] = $request->gl_account;
            $input['valuation_class'] = $request->valuation_class;
            $input['price_control'] = $request->price_control;
            $input['material_id'] = $request->material_id;
            $input['plant_id'] = $request->plant_id;
            $input['total_stock'] = $request->total_stock;
            $input['total_value'] = $request->total_value;
            $input['nilai_satuan'] = $request->nilai_satuan;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            Saldo_Awal::where('id', $request->id)
                ->update($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        }catch (\Exception $exception){
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function delete(Request $request){
        try {
            $input['deleted_at'] = Carbon::now();
            $input['deleted_by'] = auth()->user()->id;

            Saldo_Awal::where('id', $request->id)
                ->update($input);
            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        }catch (\Exception $exception){
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }
}
