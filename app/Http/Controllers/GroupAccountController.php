<?php

namespace App\Http\Controllers;

use App\DataTables\Master\GroupAccountDataTable;
use App\Exports\Template\T_GroupAccountExport;
use App\Imports\GroupAccountImport;
use App\Models\GroupAccount;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class GroupAccountController extends Controller
{
    public function index(Request $request, GroupAccountDataTable $groupaccountDataTable)
    {
        if ($request->data == 'index') {
            return $groupaccountDataTable->render('pages.master.group_account.index');
        }
        return view('pages.master.group_account.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required|unique:group_account,group_account_code',
                'deskripsi' => 'required',
                'is_active' => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $code = str_replace(" ", "", $request->code);

            $input['company_code'] = auth()->user()->company_code;
            $input['group_account_code'] = $code;
            $input['group_account_desc'] = $request->deskripsi;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            GroupAccount::create($input);

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
            $data = GroupAccount::where('group_account_code', $request->post('id'))->first();

            if (!$data)
                return setResponse([
                    'code' => 400,
                    'title' => 'Data Tidak Ditemukan!'
                ]);

            $required['deskripsi'] = 'required';

            if ($data->group_account_code != $request->post('code'))
                $required['code'] = 'required|unique:group_account,group_account_code';

            $validator = Validator::make($request->all(), $required, validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $code = str_replace(" ", "", $request->code);

            $input['company_code'] = auth()->user()->company_code;
            $input['group_account_code'] = $code;
            $input['group_account_desc'] = $request->deskripsi;
            $input['is_active'] = $request->is_active;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            DB::table('group_account')
                ->where('group_account_code', $request->id)->update($input);

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
            $group_account = GroupAccount::get_account($request->id);

            if ($group_account) {
                return setResponse([
                    'code' => 400,
                    'title' => 'Account masih digunakan, Account hanya bisa dinonaktifkan!'
                ]);
            } else {
                GroupAccount::where('group_account_code', $request->id)->delete();

                return setResponse([
                    'code' => 200,
                    'title' => 'Data berhasil dihapus'
                ]);
            }
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
            $import = new GroupAccountImport;
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
        } catch (Exception $exception) {
            return setResponse([
                'code' => 400,
            ]);
        }
    }

    public function export()
    {
        return Excel::download(new T_GroupAccountExport, 'group_account.xlsx');
    }
}
