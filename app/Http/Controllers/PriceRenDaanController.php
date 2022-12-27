<?php

namespace App\Http\Controllers;

use App\DataTables\Master\PriceRenDaanDataTable;
use App\Exports\MultipleSheet\MS_KuantitiRenDaanExport;
use App\Exports\MultipleSheet\MS_PriceRenDaanExport;
use App\Imports\KuantitiRenDaanImport;
use App\Imports\PriceRenDaanImport;
use App\Models\Asumsi_Umum;
use App\Models\PriceRenDaan;
use App\Models\QtyRenDaan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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
                "version_asumsi" => 'required',
                "bulan" => 'required',
                "material_id" => 'required',
                "region_id" => 'required',
                "price_rendaan_value" => 'required',
            ]);


            $input['version_id'] = $request->version_asumsi;
            $input['asumsi_umum_id'] = $request->bulan;
            $input['material_code'] = $request->material_id;
            $input['region_id'] = $request->region_id;
            $input['price_rendaan_value'] = $request->price_rendaan_value;
            $input['company_code'] = 'B000';
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
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
                "version_asumsi" => 'required',
                "bulan" => 'required',
                "material_id" => 'required',
                "region_id" => 'required',
                "price_rendaan_value" => 'required',
            ]);

            $input['version_id'] = $request->version_asumsi;
            $input['asumsi_umum_id'] = $request->bulan;
            $input['material_code'] = $request->material_id;
            $input['region_id'] = $request->region_id;
            $input['price_rendaan_value'] = $request->price_rendaan_value;
            $input['company_code'] = 'B000';
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;

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

            PriceRenDaan::where('id', $request->id)
                ->delete();
            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function export(Request $request)
    {
        $version = $request->temp;

        return Excel::download(new MS_PriceRenDaanExport($version), 'price_rendaan.xlsx');
    }

    public function import(Request $request)
    {
        try {
            DB::transaction(function () use ($request){
                $request->validate([
                    'file' => 'required'
                ]);
                $excel = Excel::toArray(new PriceRenDaanImport(), $request->file);
                $colect = collect($excel[0]);
                $header = array_keys($colect[0]);
                $data_versi = explode('_', $header[2]);
                $version = Asumsi_Umum::where('id', $data_versi[2])->first();
                PriceRenDaan::where('version_id', $version->version_id)->delete();

                $file = $request->file('file')->store('import');

                $data = new PriceRenDaanImport();
                $data->import($file);
            });
            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        }catch (\Exception $exception){
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }
}
