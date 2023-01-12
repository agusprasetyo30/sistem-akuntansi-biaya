<?php

namespace App\Http\Controllers;

use App\DataTables\Master\H_ZcoDataTable;
use App\DataTables\Master\ZcoDataTable;
use App\Models\Zco;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ZcoController extends Controller
{
    public function index(Request $request, ZcoDataTable $zcoDataTable, H_ZcoDataTable $h_zcoDataTable)
    {
        if ($request->data == 'index') {
            return $zcoDataTable->render('pages.buku_besar.zco.index');
        } else if ($request->data == 'horizontal') {
            return $h_zcoDataTable->render('pages.buku_besar.zco.index');
        }

        return view('pages.buku_besar.zco.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "plant_code" => '',
                "product_code" => '',
                "material_code" => '',
                "cost_element" => '',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['company_code'] = auth()->user()->company_code;
            $input['plant_code'] = $request->plant_code;
            $input['periode'] = $request->periode;
            $input['product_code'] = $request->product_code;
            $input['product_qty'] = $request->product_qty;
            $input['cost_element'] = $request->cost_element;
            $input['material_code'] = $request->material_code;
            $input['total_qty'] = $request->total_qty;
            $input['currency'] = $request->currency;
            $input['total_amount'] = $request->total_amount;
            $input['unit_price_product'] = $request->unit_price_product;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            Zco::create($input);

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
                "plant_code" => '',
                "product_code" => '',
                "material_code" => '',
                "cost_element" => '',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['company_code'] = auth()->user()->company_code;
            $input['plant_code'] = $request->plant_code;
            $input['periode'] = $request->periode;
            $input['product_code'] = $request->product_code;
            $input['product_qty'] = $request->product_qty;
            $input['cost_element'] = $request->cost_element;
            $input['material_code'] = $request->material_code;
            $input['total_qty'] = $request->total_qty;
            $input['currency'] = $request->currency;
            $input['total_amount'] = $request->total_amount;
            $input['unit_price_product'] = $request->unit_price_product;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            Zco::where('id', $request->id)
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
            Zco::where('id', $request->id)
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

    // public function import(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         "file" => 'required',
    //         "version" => 'required',
    //     ], validatorMsg());

    //     if ($validator->fails())
    //         return $this->makeValidMsg($validator);

    //     try {
    //         DB::transaction(function () use ($request) {
    //             $version = $request->version;
    //             // $excel = Excel::toArray(new QtyRenProdImport($version), $request->file);
    //             // $colect = collect($excel[0]);
    //             // $header = array_keys($colect[0]);
    //             // $data_versi = explode('_', $header[1]);
    //             // $version = Asumsi_Umum::where('id', $data_versi[2])->first();
    //             QtyRenProd::where('version_id', $version)->delete();

    //             $file = $request->file('file')->store('import');
    //             $import = new QtyRenProdImport($version);
    //             $import->import($file);

    //             $data_fail = $import->failures();

    //             if ($import->failures()->isNotEmpty()) {
    //                 $err = [];

    //                 foreach ($data_fail as $rows) {
    //                     $er = implode(' ', array_values($rows->errors()));
    //                     $hasil = $rows->values()[$rows->attribute()] . ' ' . $er;
    //                     array_push($err, $hasil);
    //                 }
    //                 // dd(implode(' ', $err));
    //                 return setResponse([
    //                     'code' => 500,
    //                     'title' => 'Gagal meng-import data',
    //                 ]);
    //             }
    //         });

    //         return setResponse([
    //             'code' => 200,
    //             'title' => 'Berhasil meng-import data'
    //         ]);
    //     } catch (Exception $exception) {
    //         return setResponse([
    //             'code' => 400,
    //         ]);
    //     }
    // }

    // public function export(Request $request)
    // {
    //     if (!$request->version) {
    //         return setResponse([
    //             'code' => 500,
    //         ]);
    //     }
    //     $version = $request->version;

    //     return Excel::download(new MS_KuantitiRenProdExport($version), 'qty_renprod.xlsx');
    // }

    // public function check(Request $request)
    // {
    //     try {
    //         $check = QtyRenProd::where('version_id', $request->version)
    //             ->first();
    //         if ($check == null) {
    //             return setResponse([
    //                 'code' => 200,
    //             ]);
    //         } else {
    //             return setResponse([
    //                 'code' => 201,
    //                 'title' => 'Apakah anda yakin?',
    //                 'message' => 'Data Pada Versi Ini Telah Ada, Yakin Untuk Mengganti ?'
    //             ]);
    //         }
    //     } catch (\Exception $exception) {
    //         return setResponse([
    //             'code' => 400,
    //         ]);
    //     }
    // }
}
