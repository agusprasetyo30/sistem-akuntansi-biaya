<?php

namespace App\Http\Controllers;

use App\DataTables\Master\GeneralLedgerAccountDataTable;
use App\Exports\MultipleSheet\MS_GeneralLedgerAccountExport;
use App\Imports\GeneralLedgerAccountImport;
use App\Models\GeneralLedgerAccount;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class GeneralLedgerAccountController extends Controller
{
    public function index(Request $request, GeneralLedgerAccountDataTable $generalledgeraccountDataTable)
    {
        if ($request->data == 'index') {
            return $generalledgeraccountDataTable->render('pages.master.general_ledger_account.index');
        }
        return view('pages.master.general_ledger_account.index');
    }

    public function create(Request $request)
    {
        // try {
        $validator = Validator::make($request->all(), [
            'general_ledger_account' => 'required|unique:general_ledger_account,general_ledger_account',
            'general_ledger_account_desc' => 'required',
        ], validatorMsg());

        if ($validator->fails())
            return $this->makeValidMsg($validator);

        $code = str_replace(" ", "", $request->general_ledger_account);

        $input['company_code'] = auth()->user()->company_code;
        $input['general_ledger_account'] = $code;
        $input['general_ledger_account_desc'] = $request->general_ledger_account_desc;
        $input['group_account_fc'] = $request->group_account_fc;
        $input['created_by'] = auth()->user()->id;
        $input['updated_by'] = auth()->user()->id;
        $input['created_at'] = Carbon::now();
        $input['updated_at'] = Carbon::now();

        GeneralLedgerAccount::create($input);

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
            $data = GeneralLedgerAccount::where('general_ledger_account', $request->post('id'))->first();

            if (!$data)
                return setResponse([
                    'code' => 400,
                    'title' => 'Data Tidak Ditemukan!'
                ]);

            $required['general_ledger_account_desc'] = 'required';

            if ($data->general_ledger_account != $request->post('general_ledger_account'))
                $required['general_ledger_account'] = 'required|unique:general_ledger_account,general_ledger_account';

            $validator = Validator::make($request->all(), $required, validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $code = str_replace(" ", "", $request->general_ledger_account);

            $input['company_code'] = auth()->user()->company_code;
            $input['general_ledger_account'] = $code;
            $input['general_ledger_account_desc'] = $request->general_ledger_account_desc;
            $input['group_account_fc'] = $request->group_account_fc;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            DB::table('general_ledger_account')
                ->where('general_ledger_account', $request->id)->update($input);

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
            // $group_account = GeneralLedgerAccount::get_account($request->id);

            // if ($group_account) {
            //     return setResponse([
            //         'code' => 400,
            //         'title' => 'Account masih digunakan, Account hanya bisa dinonaktifkan!'
            //     ]);
            // } else {
            GeneralLedgerAccount::where('general_ledger_account', $request->id)->delete();

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
            $import = new GeneralLedgerAccountImport;
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
        return Excel::download(new MS_GeneralLedgerAccountExport, 'general_ledger_account.xlsx');
    }
}
