<?php

namespace App\Http\Controllers;

use App\DataTables\Master\H_SalrDataTable;
use App\DataTables\Master\SalrDataTable;
use Illuminate\Http\Request;

class SalrController extends Controller
{
    public function index(Request $request, SalrDataTable $salrDataTable, H_SalrDataTable $h_SalrDataTable)
    {
        if ($request->data == 'index') {
            return $salrDataTable->render('pages.buku_besar.salr.index');
        }elseif ($request->data == 'horizontal'){
            return $h_SalrDataTable->render('pages.buku_besar.salr.index');
        }
        return view('pages.buku_besar.salr.index');
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

            $check_data = PriceRenDaan::where([
                'company_code' => auth()->user()->company_code,
                'version_id' => $request->version_asumsi,
                'asumsi_umum_id' => $request->bulan,
                'region_name' => $request->region_id,
                'material_code' => $request->material_id
            ])->first();


            $input['version_id'] = $request->version_asumsi;
            $input['asumsi_umum_id'] = $request->bulan;
            $input['material_code'] = $request->material_id;
            $input['region_name'] = $request->region_id;
            $input['price_rendaan_value'] = (float) str_replace('.', '', str_replace('Rp ', '', $request->price_rendaan_value));
            $input['company_code'] = 'B000';
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;

            if ($check_data != null){
                PriceRenDaan::where('id', $check_data->id)
                    ->update($input);
            }else{
                PriceRenDaan::create($input);
            }

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
            $input['region_name'] = $request->region_id;
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

            if ($validator->fails()){
                return $this->makeValidMsg($validator);
            }

            $transaction = DB::transaction(function () use ($request){
                $empty_excel = Excel::toArray(new PriceRenDaanImport($request->version), $request->file('file'));
                if ($empty_excel[0]){
                    $file = $request->file('file')->store('import');

                    PriceRenDaan::where('version_id', $request->version)->delete();
                    $import = new PriceRenDaanImport($request->version);
                    $import->import($file);

                    $data_fail = $import->failures();

                }else{
                    $data_fail = [];
                }
                return $data_fail;
            });

            if ($transaction->isNotEmpty()){
                return setResponse([
                    'code' => 500,
                    'title' => 'Gagal meng-import data',
                ]);
            }else{
                return setResponse([
                    'code' => 200,
                    'title' => 'Berhasil meng-import data'
                ]);
            }
        }catch (\Exception $exception){
            return setResponse([
                'code' => 400,
            ]);
        }
    }
}
