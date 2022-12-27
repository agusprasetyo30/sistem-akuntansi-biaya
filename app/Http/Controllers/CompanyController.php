<?php

namespace App\Http\Controllers;

use App\DataTables\Master\CompanyDataTable;
use App\Imports\CompanyImport;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index(Request $request, CompanyDataTable $data)
    {

        if ($request->data == 'index') {
            return $data->render('pages.master.company.index');
        }
        return view('pages.master.company.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'company_code' => 'required|unique:company,company_code',
                'company_name' => 'required',
                'link_sso' => 'required',
                'is_active' => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $input['company_code'] = strtoupper($request->company_code);
            $input['company_name'] = $request->company_name;
            $input['link_sso'] = $request->link_sso;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            Company::create($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                "company_code" => 'required',
                "company_name" => 'required',
                "link_sso" => 'required',
                "is_active" => 'required',
            ]);

            $input['company_code'] = strtoupper($request->company_code);
            $input['company_name'] = $request->company_name;
            $input['link_sso'] = $request->link_sso;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();
            DB::table('company')
                ->where('company_code', $request->company_code)->update($input);

            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        // try {
        //     Company::where('company_code', $request->company_code)
        //         ->update([
        //             'deleted_at' => Carbon::now(),
        //             'deleted_by' => auth()->user()->company_code
        //         ]);
        //     return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        // } catch (\Exception $exception) {
        //     return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        // }
        try {
            Company::where('company_code', $request->company_code)
                ->delete();
            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Dihapus']);
        } catch (\Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }

    public function import(Request $request)
    {
        try {
            $file = $request->file('file')->store('import');
            $import = new CompanyImport;
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
                return response()->json(['Code' => 500, 'msg' => $err]);
            }

            return response()->json(['Code' => 200, 'msg' => 'Data Berhasil Disimpan']);
        } catch (Exception $exception) {
            return response()->json(['Code' => $exception->getCode(), 'msg' => $exception->getMessage()]);
        }
    }
}
