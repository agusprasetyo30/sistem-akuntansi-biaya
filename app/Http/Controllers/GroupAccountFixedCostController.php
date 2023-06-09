<?php

namespace App\Http\Controllers;

use App\DataTables\Master\GroupAccountFixedCostDataTable;
use App\Exports\Template\T_GroupAccountFCExport;
use App\Imports\GroupAccountFCImport;
use App\Models\GroupAccountFC;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class GroupAccountFixedCostController extends Controller
{
    public function index(Request $request, GroupAccountFixedCostDataTable $groupaccountfcDataTable)
    {
        if ($request->data == 'index') {
            return $groupaccountfcDataTable->with(['filter_company' => $request->filter_company])->render('pages.master.group_account_fc.index');
        }
        return view('pages.master.group_account_fc.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'group_account_fc' => 'required|unique:group_account_fc,group_account_fc',
                'group_account_fc_desc' => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $code = str_replace(" ", "", $request->group_account_fc);

            $input['company_code'] = auth()->user()->company_code;
            $input['group_account_fc'] = $code;
            $input['group_account_fc_desc'] = $request->group_account_fc_desc;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            GroupAccountFC::create($input);

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
            $data = GroupAccountFC::where('group_account_fc', $request->post('id'))->first();

            if (!$data)
                return setResponse([
                    'code' => 400,
                    'title' => 'Data Tidak Ditemukan!'
                ]);

            $required['group_account_fc_desc'] = 'required';

            if ($data->group_account_fc != $request->post('group_account_fc'))
                $required['group_account_fc'] = 'required|unique:group_account_fc,group_account_fc';

            $validator = Validator::make($request->all(), $required, validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $code = str_replace(" ", "", $request->group_account_fc);

            $input['company_code'] = auth()->user()->company_code;
            $input['group_account_fc'] = $code;
            $input['group_account_fc_desc'] = $request->group_account_fc_desc;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            DB::table('group_account_fc')
                ->where('group_account_fc', $request->id)->update($input);

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
            $group_account = GroupAccountFC::get_account($request->id);

            if ($group_account) {
                return setResponse([
                    'code' => 400,
                    'title' => 'Account masih digunakan, Account hanya bisa dinonaktifkan!'
                ]);
            } else {
                GroupAccountFC::where('group_account_fc', $request->id)->delete();

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
            $import = new GroupAccountFCImport;
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
                'title' => $exception->getMessage()
            ]);
        }
    }

    public function export()
    {
        return Excel::download(new T_GroupAccountFCExport, 'group_account_fc.xlsx');
    }
}
