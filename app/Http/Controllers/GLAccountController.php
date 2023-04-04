<?php

namespace App\Http\Controllers;

use App\DataTables\Master\GLAccountDataTable;
use App\Exports\MultipleSheet\MS_GLAccountExport;
use App\Imports\GLAccountImport;
use App\Models\GLAccount;
use App\Models\GroupAccount;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class GLAccountController extends Controller
{
    public function index(Request $request, GLAccountDataTable $glaccountDataTable)
    {
        if ($request->data == 'index') {
            return $glaccountDataTable->with(['filter_company' => $request->filter_company])->render('pages.master.gl_account.index');
        }
        return view('pages.master.gl_account.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'gl_account' => 'required|unique:gl_account,gl_account',
                'gl_account_desc' => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $code = str_replace(" ", "", $request->gl_account);

            $input['company_code'] = auth()->user()->company_code;
            $input['gl_account'] = $code;
            $input['gl_account_desc'] = $request->gl_account_desc;
            $input['group_account_code'] = $request->group_account_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            GLAccount::create($input);

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
            $data = GLAccount::where('gl_account', $request->post('id'))->first();

            if (!$data)
                return setResponse([
                    'code' => 400,
                    'title' => 'Data Tidak Ditemukan!'
                ]);

            $required['gl_account_desc'] = 'required';

            if ($data->gl_account != $request->post('gl_account'))
                $required['gl_account'] = 'required|unique:gl_account,gl_account';

            $validator = Validator::make($request->all(), $required, validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $code = str_replace(" ", "", $request->gl_account);

            $input['company_code'] = auth()->user()->company_code;
            $input['gl_account'] = $code;
            $input['gl_account_desc'] = $request->gl_account_desc;
            $input['group_account_code'] = $request->group_account_code;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            DB::table('gl_account')
                ->where('gl_account', $request->id)->update($input);

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
            // $group_account = GeneralLedgerAccount::get_account($request->id);

            // if ($group_account) {
            //     return setResponse([
            //         'code' => 400,
            //         'title' => 'Account masih digunakan, Account hanya bisa dinonaktifkan!'
            //     ]);
            // } else {
            GLAccount::where('gl_account', $request->id)->delete();

            return setResponse([
                'code' => 200,
                'title' => 'Data berhasil dihapus'
            ]);
            // }
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
            $import = new GLAccountImport;
            $import->import($file);

            $data_fail = $import->failures();

            if ($import->failures()->isNotEmpty()) {
                $err = [];

                foreach ($data_fail as $rows) {
                    $er = implode(' ', array_values($rows->errors()));
                    $hasil = $rows->values()[$rows->attribute()] . ' ' . $er;
                    array_push($err, $hasil);
                }

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
            $empty_excel = Excel::toArray(new GLAccountImport(), $request->file('file'));

            $group_account = [];
            $group_account_ = [];

            foreach ($empty_excel[0] as $key => $value) {
                array_push($group_account, 'group account ' . $value['group_account_code'] . ' tidak ada pada master');
                $d_groupaccount = GroupAccount::whereIn('group_account_code', [$value['group_account_code']])->first();
                if ($d_groupaccount) {
                    array_push($group_account_, 'group account ' . $d_groupaccount->group_account_code . ' tidak ada pada master');
                }
            }
            $result_group_account = array_diff($group_account, $group_account_);
            $result = array_merge($result_group_account);
            $res = array_unique($result);

            if ($res) {
                $msg = '';

                foreach ($res as $message)
                    $msg .= '<p>' . $message . '</p>';

                return setResponse([
                    'code' => 430,
                    'title' => 'Gagal meng-import data',
                    'message' => $msg
                ]);
            } else {
                return setResponse([
                    'code' => 400,
                    'title' => $exception->getMessage()
                ]);
            }
        }
    }

    public function export()
    {
        return Excel::download(new MS_GLAccountExport, 'gl_account.xlsx');
    }
}
