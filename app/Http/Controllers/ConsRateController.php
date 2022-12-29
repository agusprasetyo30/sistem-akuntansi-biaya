<?php

namespace App\Http\Controllers;

use App\DataTables\Master\ConsRateDataTable;
use App\Exports\MultipleSheet\MS_ComsumptionRatioExport;
use App\Exports\Template\T_ConsRateExport;
use App\Imports\ConsRateImport;
use App\Jobs\ConsRatePodcast;
use App\Models\Asumsi_Umum;
use App\Models\ConsRate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ConsRateController extends Controller
{
    public function index(Request $request, ConsRateDataTable $consRateDataTable)
    {
        if ($request->data == 'index') {
            return $consRateDataTable->render('pages.buku_besar.consrate.index');
        }

        return view('pages.buku_besar.consrate.index');
    }

    public function create(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                "id_plant" => 'required',
                "version" => 'required',
                "id_asumsi" => 'required',
                "produk" => 'required',
                "material" => 'required',
                "consrate" => 'required',
                "is_active" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $data_asumsi = Asumsi_Umum::where('id', $request->id_asumsi)
                ->first();

            $input['plant_code'] = $request->id_plant;
            $input['version_id'] = (int) $request->version;
            $input['product_code'] = $request->produk;
            $input['material_code'] = $request->material;
            $input['cons_rate'] = (float) $request->consrate;
            $input['month_year'] = $data_asumsi->month_year;
            $input['is_active'] = $request->is_active;
            $input['company_code'] = 'B000';
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();


            $data_cek = ConsRate::where([
                'plant_code' => $request->id_plant,
                'version_id' => (int) $request->version,
                'product_code' => $request->produk,
                'month_year' => $data_asumsi->month_year,
                'company_code' => 'B000'
            ])->first();

            if ($data_cek == null) {
                ConsRate::create($input);
            } else {
                ConsRate::where('id', $data_cek->id)->update($input);
            }
            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        } catch (\Exception $exception) {
//            dd($exception);
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "id_plant" => 'required',
                "version" => 'required',
                "id_asumsi" => 'required',
                "produk" => 'required',
                "material" => 'required',
                "consrate" => 'required',
                "is_active" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);


            if (strpos($request->id_asumsi, '-') == true) {
                $data_asumsi = Asumsi_Umum::where([
                    'month_year' => $request->id_asumsi,
                    'version_id' => (int) $request->version
                ])
                    ->first();
            } else {
                $data_asumsi = Asumsi_Umum::where('id', $request->id_asumsi)
                    ->first();
            }

            $input['plant_code'] = $request->id_plant;
            $input['version_id'] = (int) $request->version;
            $input['product_code'] = $request->produk;
            $input['material_code'] = $request->material;
            $input['cons_rate'] = (float) $request->consrate;
            $input['month_year'] = $data_asumsi->month_year;
            $input['is_active'] = $request->is_active;
            $input['company_code'] = 'B000';
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            ConsRate::where('id', $request->id)
                ->update($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        } catch (\Exception $exception) {
            //            dd($exception);
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {
            ConsRate::where('id', $request->id)
                ->delete();
            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new MS_ComsumptionRatioExport(), 'cons_rate.xlsx');
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

            DB::transaction(function () use ($request) {
                ConsRate::where('version_id', $request->version)->delete();

                $file = $request->file('file')->store('import');

                $data = new ConsRateImport($request->version);
                $data->import($file);
            });
            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        } catch (\Exception $exception) {
            //            dd($exception);
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function check(Request $request){
        try {
            $check = ConsRate::where('version_id', $request->version)
                ->first();
            if ($check == null){
                return response()->json(['Code' => 200, 'msg' => 'Data ']);
            }else{
                return response()->json(['Code' => 201, 'msg' => 'Data Berasil Disimpan']);
            }
        }catch (\Exception $exception){

        }
    }
}
