<?php

namespace App\Http\Controllers;

use App\DataTables\Master\CostCenterDataTable;
use App\Exports\Template\T_CostCenterExport;
use App\Exports\Template\T_PlantExport;
use App\Imports\CostCenterImport;
use App\Models\CostCenter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class CostCenterController extends Controller
{
    public function index(Request $request, CostCenterDataTable $costCenterDataTable)
    {
        if ($request->data == 'index') {
            return $costCenterDataTable->with(['filter_company' => $request->filter_company])->render('pages.master.cost_center.index');
        }
        return view('pages.master.cost_center.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "code" => 'required',
                "deskripsi" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);


            $input['cost_center'] = $request->code;
            $input['cost_center_desc'] = $request->deskripsi;
            $input['company_code'] = auth()->user()->company_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            CostCenter::create($input);

            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
                'title' => $exception->getMessage()
            ]);
        }
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "code" => 'required',
                "deskripsi" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['cost_center'] = $request->code;
            $input['cost_center_desc'] = $request->deskripsi;
            $input['company_code'] = auth()->user()->company_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            CostCenter::where('cost_center', $request->id)
                ->update($input);

            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
                'title' => $exception->getMessage()
            ]);
        }
    }

    public function delete(Request $request)
    {
        try {

            $input['deleted_at'] = Carbon::now();

            CostCenter::where('cost_center', $request->id)
                ->delete();
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

    public function import(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "file" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $file = $request->file('file')->store('import');
            $import = new CostCenterImport();
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
                    'message' => $err
                ]);
            }

            return setResponse([
                'code' => 200,
                'title' => 'Berhasil meng-import data'
            ]);
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
                'title' => $exception->getMessage()
            ]);
        }
    }

    public function export()
    {
        return Excel::download(new T_CostCenterExport(), 'cost center.xlsx');
    }
}
