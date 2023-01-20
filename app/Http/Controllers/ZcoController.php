<?php

namespace App\Http\Controllers;

use App\DataTables\Master\H_ZcoDataTable;
use App\DataTables\Master\H_ZcoGroupAccountDataTable;
use App\DataTables\Master\ZcoDataTable;
use App\Exports\MultipleSheet\MS_ZcoExport;
use App\Imports\ZcoImport;
use App\Models\Zco;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ZcoController extends Controller
{
    public function index(Request $request, ZcoDataTable $zcoDataTable, H_ZcoDataTable $h_zcoDataTable, H_ZcoGroupAccountDataTable $h_zcogroupaccountDataTable)
    {
        if ($request->data == 'index') {
            return $zcoDataTable->render('pages.buku_besar.zco.index');
        } else if ($request->data == 'horizontal') {
            return $h_zcoDataTable->with([
                'material' => $request->material,
                'plant' => $request->plant,
                'format' => $request->format_data,
                'start_month' => $request->start_month,
                'end_month' => $request->end_month,
                'moth' => $request->moth,
            ])->render('pages.buku_besar.zco.index');
        } else if ($request->data == 'horizontal_group_account') {
            return $h_zcogroupaccountDataTable->with([
                'material' => $request->material,
                'plant' => $request->plant,
                'format' => $request->format_data,
                'start_month' => $request->start_month,
                'end_month' => $request->end_month,
                'moth' => $request->moth,
            ])->render('pages.buku_besar.zco.index');
        } else if ($request->data == 'material') {
            $material = Zco::select('zco.product_code', 'zco.plant_code', 'material.material_name', 'zco.periode')
                ->leftjoin('material', 'zco.product_code', '=', 'material.material_code')
                ->groupBy('zco.product_code', 'zco.plant_code', 'material.material_name', 'zco.periode');

            if ($request->material != 'all') {
                $material->where('zco.product_code', $request->material);
            }

            if ($request->plant != 'all') {
                $material->where('zco.plant_code', $request->plant);
            }

            if ($request->format_data == '0') {
                $temp = explode('-', $request->moth);
                $timemonth = $temp[1] . '-' . $temp[0];

                $material->where('periode', 'ilike', '%' . $timemonth . '%');
            } else if ($request->format_data == '1') {
                $start_temp = explode('-', $request->start_month);
                $end_temp = explode('-', $request->end_month);
                $start_date = $start_temp[1] . '-' . $start_temp[0] . '-01 00:00:00';
                $end_date = $end_temp[1] . '-' . $end_temp[0] . '-01 00:00:00';

                $material->whereBetween('periode', [$start_date, $end_date]);
            }
            // dd($request->material, $request->plant);
            $material = $material->get();
            // dd($material);
            return response()->json(['code' => 200, 'material' => $material]);
        } else if ($request->data == 'group_account') {
            $group_account = Zco::select('zco.product_code', 'zco.plant_code', 'material.material_name', 'zco.periode')
                ->leftjoin('material', 'zco.product_code', '=', 'material.material_code')
                ->groupBy('zco.product_code', 'zco.plant_code', 'material.material_name', 'zco.periode');

            if ($request->material != 'all') {
                $group_account->where('zco.product_code', $request->material);
            }

            if ($request->plant != 'all') {
                $group_account->where('zco.plant_code', $request->plant);
            }

            if ($request->format_data == '0') {
                $temp = explode('-', $request->moth);
                $timemonth = $temp[1] . '-' . $temp[0];

                $group_account->where('periode', 'ilike', '%' . $timemonth . '%');
            } else if ($request->format_data == '1') {
                $start_temp = explode('-', $request->start_month);
                $end_temp = explode('-', $request->end_month);
                $start_date = $start_temp[1] . '-' . $start_temp[0] . '-01 00:00:00';
                $end_date = $end_temp[1] . '-' . $end_temp[0] . '-01 00:00:00';

                $group_account->whereBetween('periode', [$start_date, $end_date]);
            }
            // dd($request->material, $request->plant);
            $group_account = $group_account->get();

            return response()->json(['code' => 200, 'group_account' => $group_account]);
        }

        return view('pages.buku_besar.zco.index');
    }

    public function create(Request $request)
    {
        // try {
        $validator = Validator::make($request->all(), [
            "plant_code" => 'required',
            "product_code" => 'required',
            "material_code" => 'required',
            "cost_element" => 'required',
            "periode" => 'required',
        ], validatorMsg());

        if ($validator->fails())
            return $this->makeValidMsg($validator);

        $periode = explode('-', $request->periode);

        $input['company_code'] = auth()->user()->company_code;
        $input['plant_code'] = $request->plant_code;
        $input['periode'] = $periode[1] . '-' . $periode[0] . '-01';
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
        // } catch (\Exception $exception) {
        //     return setResponse([
        //         'code' => 400,
        //     ]);
        // }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "plant_code" => 'required',
                "product_code" => 'required',
                "material_code" => 'required',
                "cost_element" => 'required',
                "periode" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $periode = explode('-', $request->periode);

            $input['company_code'] = auth()->user()->company_code;
            $input['plant_code'] = $request->plant_code;
            $input['periode'] = $periode[1] . '-' . $periode[0] . '-01';
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

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "file" => 'required',
            "periode_import" => 'required',
        ], validatorMsg());

        if ($validator->fails())
            return $this->makeValidMsg($validator);

        try {
            DB::transaction(function () use ($request) {
                $per = explode('-', $request->periode_import);
                $periode = $per[1] . '-' . $per[0] . '-01';

                Zco::where('periode', $periode)->delete();

                $file = $request->file('file')->store('import');
                $import = new ZcoImport($periode);
                $import->import($file);

                $data_fail = $import->failures();

                if ($import->failures()->isNotEmpty()) {
                    $err = [];

                    foreach ($data_fail as $rows) {
                        $er = implode(' ', array_values($rows->errors()));
                        $hasil = $rows->values()[$rows->attribute()] . ' ' . $er;
                        array_push($err, $hasil);
                    }
                    // dd(implode(' ', $err));
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
        } catch (Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new MS_ZcoExport(), 'zco.xlsx');
    }

    public function check(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "file" => 'required',
            "periode_import" => 'required',
        ], validatorMsg());

        if ($validator->fails())
            return $this->makeValidMsg($validator);

        try {
            $per = explode('-', $request->periode);

            $check = Zco::where('periode', 'ilike', '%' . $per[1] . '-' . $per[0] . '-01' . '%')
                ->first();

            if ($check == null) {
                return setResponse([
                    'code' => 200,
                ]);
            } else {
                return setResponse([
                    'code' => 201,
                    'title' => 'Apakah anda yakin?',
                    'message' => 'Data Pada Periode Ini Telah Ada, Yakin Untuk Mengganti ?'
                ]);
            }
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }
}
