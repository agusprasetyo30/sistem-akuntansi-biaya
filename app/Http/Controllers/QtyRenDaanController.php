<?php

namespace App\Http\Controllers;

use App\DataTables\Master\QtyRenDaanDataTable;
use App\Exports\MultipleSheet\MS_KuantitiRenDaanExport;
use App\Exports\Template\T_KuantitiRenDaanExport;
use App\Imports\KuantitiRenDaanImport;
use App\Models\Asumsi_Umum;
use App\Models\QtyRenDaan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class QtyRenDaanController extends Controller
{
    public function index(Request $request, QtyRenDaanDataTable $qtyrendaanDataTable)
    {
        if ($request->data == 'index') {
            return $qtyrendaanDataTable->render('pages.buku_besar.qty_rendaan.index');
        }
        return view('pages.buku_besar.qty_rendaan.index');
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                "version_asumsi" => 'required',
                "bulan" => 'required',
                "material_id" => 'required',
                "region_id" => 'required',
                "qty_rendaan_value" => 'required',
            ]);


            $input['version_id'] = $request->version_asumsi;
            $input['asumsi_umum_id'] = $request->bulan;
            $input['material_code'] = $request->material_id;
            $input['region_id'] = $request->region_id;
            $input['qty_rendaan_value'] = $request->qty_rendaan_value;
            $input['company_code'] = 'B000';
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            QtyRenDaan::create($input);

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
                "qty_rendaan_value" => 'required',
            ]);

            $input['version_id'] = $request->version_asumsi;
            $input['asumsi_umum_id'] = $request->bulan;
            $input['material_code'] = $request->material_id;
            $input['region_id'] = $request->region_id;
            $input['qty_rendaan_value'] = $request->qty_rendaan_value;
            $input['company_code'] = 'B000';
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;

            QtyRenDaan::where('id', $request->id)
                ->update($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
//            dd($exception);
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $input['deleted_at'] = Carbon::now();
            $input['deleted_by'] = auth()->user()->id;

            QtyRenDaan::where('id', $request->id)
                ->update($input);
            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function export(Request $request)
    {
        $version = $request->temp;

        return Excel::download(new MS_KuantitiRenDaanExport($version), 'qty_rendaan.xlsx', null, [\Maatwebsite\Excel\Excel::XLSX]);
    }

    public function import(Request $request)
    {
//        $excel = Excel::toArray(new KuantitiRenDaanImport(), $request->file);
        $file = $request->file('file')->store('import');

        $data = new KuantitiRenDaanImport();
        $data->import($file);
//        try {
//            $excel = Excel::toArray(new KuantitiRenDaanImport(), $request->file);
//            $colect = collect($excel[0]);
//            $header = $colect[0];
//            unset($colect[0]);
//            DB::transaction(function () use ($colect, $header){
//                $colect->chunk(20)->each(function ($query) use ($header){
//                    $versi = null;
//                    foreach ($query as $items){
//                        $result = [];
//                        foreach ($header as $head => $data){
//                            if ($head > 1){
//                                $temp_date = explode('|', $data);
//                                $input['qty_rendaan_value'] = $items[$head];
//                                $input['asumsi_umum_id'] = $temp_date[1];
//                                if ($versi == null){
//                                    $versi = $temp_date[1];
//                                    $data_version = Asumsi_Umum::where('id', $versi)
//                                        ->first();
//                                }
//                                $input['version_id'] = $data_version->version_id;
//                                $input['company_code'] = auth()->user()->company_code;
//                                $input['created_by'] = auth()->user()->id;
//                                $input['updated_by'] = auth()->user()->id;
//                                array_push($result, $input);
//                            }else{
//                                $input[$data] = $items[$head];
//                            }
//                        }
//                        collect($result)->each(function ($result){QtyRenDaan::create($result);});
//                    }
//                });
//            });
//            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
//        }catch (\Exception $exception){
//            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
//        }
    }
}
