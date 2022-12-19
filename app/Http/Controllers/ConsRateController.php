<?php

namespace App\Http\Controllers;

use App\DataTables\Master\ConsRateDataTable;
use App\Models\Asumsi_Umum;
use App\Models\ConsRate;
use App\Models\CostCenter;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ConsRateController extends Controller
{
    public function index(Request $request, ConsRateDataTable $consRateDataTable){

        if ($request->data == 'index'){
            return $consRateDataTable->render('pages.buku_besar.consrate.index');
        }

        return view('pages.buku_besar.consrate.index');

    }

    public function create(Request $request){
        try {
            $request->validate([
                "id_plant" => 'required',
                "version" => 'required',
                "id_asumsi" => 'required',
                "produk" => 'required',
                "material" => 'required',
                "consrate" => 'required',
                "is_active" => 'required',
            ]);

            $data_asumsi = Asumsi_Umum::where('id', $request->id_asumsi)
                ->first();

            $input['plant_code'] = $request->id_plant;
            $input['version_id'] = (int) $request->version;
            $input['product_code'] = $request->produk;
            $input['material_code'] = $request->material;
            $input['cons_rate'] = (double) $request->consrate;
            $input['month_year'] = $data_asumsi->month_year;
            $input['is_active'] = $request->is_active;
            $input['company_code'] = 'B000';
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

//            dd($input);

            $data_cek = ConsRate::where([
                'plant_code' => $request->id_plant,
                'version_id' => (int) $request->version,
                'product_code' => $request->produk,
                'company_code' => 'B000'
            ])->first();

            if ($data_cek == null){
                ConsRate::create($input);
            }else{
                ConsRate::where('id', $data_cek->id)->update($input);
            }



//            ConsRate::where([
//                'plant_code' => $request->id_plant,
//                'version_id' => (int) $request->version,
//                'product_code' => $request->produk,
//                'company_code' => 'B000'
//            ])
//                ->upsert([
//                [
//                    'plant_code' => $request->id_plant,
//                    'version_id' => (int) $request->version,
//                    'product_code' => $request->produk,
//                    'material_code' => $request->material,
//                    'cons_rate' => (double) $request->consrate,
//                    'month_year' => $data_asumsi->month_year,
//                    'is_active' => $request->is_active,
//                    'company_code' => 'B000',
//                    'created_by' => auth()->user()->id,
//                    'updated_by' => auth()->user()->id
//                ],
//            ],[
//                'plant_code',
//                'version_id',
//                'product_code',
//                'company_code'
//            ], [
//                'material_code',
//                'cons_rate',
//                'month_year',
//                'is_active',
//                'created_by',
//                'updated_by'
//            ]);

//            ConsRate::updateOrCreate([
//                [
//                    'plant_code' => $request->id_plant,
//                    'version_id' => (int) $request->version,
//                    'product_code' => $request->produk,
//                    'company_code' => 'B000',
//                    'material_code' => $request->material,
//                    'cons_rate' => (double) $request->consrate,
//                    'month_year' => $data_asumsi->month_year,
//                    'is_active' => $request->is_active,
//                    'created_by' => auth()->user()->id,
//                    'updated_by' => auth()->user()->id
//                ]
//            ]);



            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        }catch (\Exception $exception){
            dd($exception);
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function update(Request $request){
        try {
            $request->validate([
                "id_plant" => 'required',
                "version" => 'required',
                "id_asumsi" => 'required',
                "produk" => 'required',
                "material" => 'required',
                "consrate" => 'required',
                "is_active" => 'required',
            ]);
//            dd($request);

            if (strpos($request->id_asumsi, '-') == true){
                $data_asumsi = Asumsi_Umum::where([
                    'month_year' => $request->id_asumsi,
                    'version_id' => (int) $request->version
                ])
                    ->first();
            }else{
                $data_asumsi = Asumsi_Umum::where('id', $request->id_asumsi)
                    ->first();
            }

            $input['plant_code'] = $request->id_plant;
            $input['version_id'] = (int) $request->version;
            $input['product_code'] = $request->produk;
            $input['material_code'] = $request->material;
            $input['cons_rate'] = (double) $request->consrate;
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
        }catch (\Exception $exception){
//            dd($exception);
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function delete(Request $request){
        try {
            ConsRate::where('id', $request->id)
                ->delete();
            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        }catch (\Exception $exception){
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }
}