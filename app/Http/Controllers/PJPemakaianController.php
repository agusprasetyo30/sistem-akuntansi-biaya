<?php

namespace App\Http\Controllers;

use App\DataTables\Master\H_PJPemakaianDataTable;
use App\DataTables\Master\PJPemakaianDataTable;
use App\Exports\MultipleSheet\MS_PJPemakaianExport;
use App\Imports\PJPemakaianImport;
use App\Models\PJ_Pemakaian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class PJPemakaianController extends Controller
{
    public function index(Request $request, PJPemakaianDataTable $pemakaianDataTable, H_PJPemakaianDataTable $h_pemakaianDataTable)
    {
        if ($request->data == 'index') {
            return $pemakaianDataTable->render('pages.buku_besar.pakai_jual.pemakaian.index');
        } elseif ($request->data == 'horizontal') {
            return $h_pemakaianDataTable->with(['version' => $request->version])->render('pages.buku_besar.pakai_jual.pemakaian.index');
        } elseif ($request->data == 'version') {
            $asumsi = DB::table('asumsi_umum')
                ->where('version_id', $request->version)
                ->orderBy('month_year', 'ASC')
                ->get();
            return response()->json(['code' => 200, 'asumsi' => $asumsi]);
        }
        return view('pages.buku_besar.pakai_jual.pemakaian.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "material_code" => 'required',
                "pj_pemakaian_value" => 'required',
                "version_id" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            // $pj_pemakaian_value = (float) str_replace('.', '', str_replace('Rp ', '', $request->pj_pemakaian_value));

            $input['company_code'] = auth()->user()->company_code;
            $input['material_code'] = $request->material_code;
            $input['version_id'] = $request->version_id;
            $input['asumsi_umum_id'] = $request->month_year;
            $input['pj_pemakaian_value'] = $request->pj_pemakaian_value;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            $data_renprod = PJ_Pemakaian::where([
                'company_code' => auth()->user()->company_code,
                'material_code' => $request->material_code,
                'version_id' => (int) $request->version_id,
                'asumsi_umum_id' => $request->month_year,
            ])->first();

            if (!$data_renprod) {
                PJ_Pemakaian::create($input);
            } else {
                PJ_Pemakaian::where('id', $data_renprod->id)->update($input);
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
                "material_code" => 'required',
                "pj_pemakaian_value" => 'required',
                "version_id" => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            // $pj_pemakaian_value = (float) str_replace('.', '', str_replace('Rp ', '', $request->pj_pemakaian_value));

            $input['company_code'] = auth()->user()->company_code;
            $input['material_code'] = $request->material_code;
            $input['version_id'] = $request->version_id;
            $input['asumsi_umum_id'] = $request->month_year;
            $input['pj_pemakaian_value'] = $request->pj_pemakaian_value;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            PJ_Pemakaian::where('id', $request->id)
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
            PJ_Pemakaian::where('id', $request->id)
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
            // "version" => 'required',
        ], validatorMsg());

        if ($validator->fails())
            return $this->makeValidMsg($validator);

        try {
            DB::transaction(function () use ($request) {
                $version = $request->version;
                // $excel = Excel::toArray(new QtyRenProdImport($version), $request->file);
                // $colect = collect($excel[0]);
                // $header = array_keys($colect[0]);
                // $data_versi = explode('_', $header[1]);
                // $version = Asumsi_Umum::where('id', $data_versi[2])->first();
                PJ_Pemakaian::where('version_id', $version)->delete();

                $file = $request->file('file')->store('import');
                $import = new PJPemakaianImport($version);
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
        if (!$request->version) {
            return setResponse([
                'code' => 500,
            ]);
        }
        $version = $request->version;

        return Excel::download(new MS_PJPemakaianExport($version), 'qty_renprod.xlsx');
    }

    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // "file" => 'required',
            "version" => 'required',
        ], validatorMsg());

        if ($validator->fails())
            return $this->makeValidMsg($validator);

        try {
            $check = PJ_Pemakaian::where('version_id', $request->version)
                ->first();
            if ($check == null) {
                return setResponse([
                    'code' => 200,
                ]);
            } else {
                return setResponse([
                    'code' => 201,
                    'title' => 'Apakah anda yakin?',
                    'message' => 'Data Pada Versi Ini Telah Ada, Yakin Untuk Mengganti ?'
                ]);
            }
        } catch (\Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }
}
