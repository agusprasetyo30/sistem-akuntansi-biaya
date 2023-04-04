<?php

namespace App\Http\Controllers;

use App\DataTables\Master\RegionDataTable;
use App\Exports\Template\T_RegionsExport;
use App\Imports\RegionsImport;
use App\Models\Regions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class RegionsController extends Controller
{
    public function index(Request $request, RegionDataTable $regionDataTable)
    {
        if ($request->data == 'index') {
            //            dd($request->data);
            return $regionDataTable->render('pages.master.regions.index');
        }
        return view('pages.master.regions.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "nama" => 'required|unique:regions,region_name',
                "deskripsi" => 'required',
                "latitude" => '',
                "longtitude" => '',
                "is_active" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);


            $input['region_name'] = strtoupper($request->nama);
            $input['region_desc'] = $request->deskripsi;
            $input['latitude'] = $request->latitude;
            $input['longtitude'] = $request->longtitude;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            Regions::create($input);

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
        $data = Regions::where('id', $request->id)->first();

        if (!$data)
            return setResponse([
                'code' => 400,
                'title' => 'Data Tidak Ditemukan!'
            ]);

        if ($data->region_name != $request->nama) {
            $required['nama'] = 'required|unique:regions,region_name';
        } else {
            $required['nama'] = 'required';
        }

        $required['deskripsi'] = 'required';
        $required['latitude'] = '';
        $required['longtitude'] = '';
        $required['is_active'] = 'required';
        try {
            $validator = Validator::make($request->all(), $required, validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['region_name'] = strtoupper($request->nama);
            $input['region_desc'] = $request->deskripsi;
            $input['latitude'] = $request->latitude;
            $input['longtitude'] = $request->longtitude;
            $input['is_active'] = $request->is_active;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            Regions::where('id', $request->id)
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
            $input['deleted_by'] = auth()->user()->id;

            Regions::where('id', $request->id)
                ->delete();
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

    public function import(Request $request)
    {
        try {
            $file = $request->file('file')->store('import');
            $import = new RegionsImport;
            $import->import($file);

            $data_fail = $import->failures();

            if ($import->failures()->isNotEmpty()) {
                $err = [];

                foreach ($data_fail as $rows) {
                    $er = implode(' ', array_values($rows->errors()));
                    $hasil = $rows->values()[$rows->attribute()] . ' ' . $er;
                    array_push($err, $hasil);
                }
                if ($data_fail->isNotEmpty()) {
                    return setResponse([
                        'code' => 500,
                        'title' => 'Gagal meng-import data',
                    ]);
                }
            }

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

    public function export()
    {
        return Excel::download(new T_RegionsExport, 'regions.xlsx');
    }
}
