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
use Illuminate\Support\Facades\Validator;
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
            $validator = Validator::make($request->all(), [
                "version_asumsi" => 'required',
                "bulan" => 'required',
                "material_id" => 'required',
                "region_id" => 'required',
                "price_rendaan_value" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);


            $input['version_id'] = $request->version_asumsi;
            $input['asumsi_umum_id'] = $request->bulan;
            $input['material_code'] = $request->material_id;
            $input['region_id'] = $request->region_id;
            $input['price_rendaan_value'] = (float) str_replace('.', '', str_replace('Rp ', '', $request->price_rendaan_value));
            $input['company_code'] = 'B000';
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            PriceRenDaan::create($input);

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
                "version_asumsi" => 'required',
                "bulan" => 'required',
                "material_id" => 'required',
                "region_id" => 'required',
                "price_rendaan_value" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['version_id'] = $request->version_asumsi;
            $input['asumsi_umum_id'] = $request->bulan;
            $input['material_code'] = $request->material_id;
            $input['region_id'] = $request->region_id;
            $input['price_rendaan_value'] = (float) str_replace('.', '', str_replace('Rp ', '', $request->price_rendaan_value));
            $input['company_code'] = 'B000';
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;

            PriceRenDaan::where('id', $request->id)
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

            PriceRenDaan::where('id', $request->id)
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

    public function export(Request $request)
    {
        $version = $request->temp;

        return Excel::download(new MS_PriceRenDaanExport($version), 'price_rendaan.xlsx');
    }

    public function import(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "file" => 'required',
                "version" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            DB::transaction(function () use ($request){
                PriceRenDaan::where('version_id', $request->version_id)->delete();

                $file = $request->file('file')->store('import');

                $import = new PriceRenDaanImport($request->version);
                $import->import($file);

                $data_fail = $import->failures();
                if ($data_fail->isNotEmpty()){
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
        }catch (\Exception $exception){
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function check(Request $request){
        try {
            $check = PriceRenDaan::where('version_id', $request->version)
                ->first();
            if ($check == null){
                return response()->json(['Code' => 200, 'msg' => 'Data Tidak Ada']);
            }else{
                return response()->json(['Code' => 201, 'msg' => 'Data Ada']);
            }
        }catch (\Exception $exception){
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }
}
