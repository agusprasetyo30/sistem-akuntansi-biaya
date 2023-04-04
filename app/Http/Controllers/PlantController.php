<?php

namespace App\Http\Controllers;

use App\DataTables\Master\PlantDataTable;
use App\Exports\Template\T_PlantExport;
use App\Imports\PlantImport;
use App\Models\Periode;
use App\Models\Plant;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class PlantController extends Controller
{
    public function index(Request $request, PlantDataTable $plantDataTable)
    {
        if ($request->data == 'index') {
            return $plantDataTable->with(['filter_company' => $request->filter_company])->render('pages.master.plant.index');
        }
        return view('pages.master.plant.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|unique:plant,plant_code',
                'deskripsi' => 'required',
                'is_active' => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $code = str_replace(" ", "", $request->code);

            $input['plant_code'] = $code;
            $input['plant_desc'] = $request->deskripsi;
            $input['is_active'] = $request->is_active;
            $input['company_code'] = auth()->user()->company_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();
            //            dd($input);

            Plant::create($input);

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
            $data = Plant::where('plant_code', $request->post('id'))->first();

            if (!$data)
                return setResponse([
                    'code' => 400,
                    'title' => 'Data Tidak Ditemukan!'
                ]);

            $required['deskripsi'] = 'required';
            $required['is_active'] = 'required';

            if ($data->plant_code != $request->post('code'))
                $required['code'] = 'required|unique:plant,plant_code';

            $validator = Validator::make($request->all(), $required, validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $code = str_replace(" ", "", $request->code);

            $input['plant_code'] = $code;
            $input['plant_desc'] = $request->deskripsi;
            $input['is_active'] = $request->is_active;
            $input['company_code'] = auth()->user()->company_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();
            Plant::where('plant_code', $request->id)
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
            Plant::where('plant_code', $request->id)
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
        try {
            $validator = Validator::make($request->all(), [
                "file" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $file = $request->file('file')->store('import');
            $import = new PlantImport;
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
        return Excel::download(new T_PlantExport, 'plant.xlsx');
    }
}
