<?php

namespace App\Http\Controllers;

use App\DataTables\Master\GLAccountFixedCostDataTable;
use App\Exports\MultipleSheet\MS_GLAccountFCExport;
use App\Imports\GLAccountFCImport;
use App\Models\GLAccountFC;
use App\Models\GroupAccountFC;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class GLAccountFixedCostController extends Controller
{
    public function index(Request $request, GLAccountFixedCostDataTable $glaccountfcDataTable)
    {
        if ($request->data == 'index') {
            return $glaccountfcDataTable->with(['filter_company' => $request->filter_company])->render('pages.master.gl_account_fc.index');
        }
        return view('pages.master.gl_account_fc.index');
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'gl_account_fc' => 'required|unique:gl_account_fc,gl_account_fc',
                'gl_account_fc_desc' => 'required',
            ], validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $code = str_replace(" ", "", $request->gl_account_fc);

            $input['company_code'] = auth()->user()->company_code;
            $input['gl_account_fc'] = $code;
            $input['gl_account_fc_desc'] = $request->gl_account_fc_desc;
            $input['group_account_fc'] = $request->group_account_fc;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['created_at'] = Carbon::now();
            $input['updated_at'] = Carbon::now();

            GLAccountFC::create($input);

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
            $data = GLAccountFC::where('gl_account_fc', $request->post('id'))->first();

            if (!$data)
                return setResponse([
                    'code' => 400,
                    'title' => 'Data Tidak Ditemukan!'
                ]);

            $required['gl_account_fc_desc'] = 'required';

            if ($data->gl_account_fc != $request->post('gl_account_fc'))
                $required['gl_account_fc'] = 'required|unique:gl_account_fc,gl_account_fc';

            $validator = Validator::make($request->all(), $required, validatorMsg());

            if ($validator->fails())
                return $this->makeValidMsg($validator);

            $code = str_replace(" ", "", $request->gl_account_fc);

            $input['company_code'] = auth()->user()->company_code;
            $input['gl_account_fc'] = $code;
            $input['gl_account_fc_desc'] = $request->gl_account_fc_desc;
            $input['group_account_fc'] = $request->group_account_fc;
            $input['created_by'] = auth()->user()->id;
            $input['updated_by'] = auth()->user()->id;
            $input['updated_at'] = Carbon::now();

            DB::table('gl_account_fc')
                ->where('gl_account_fc', $request->id)->update($input);

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
            GLAccountFC::where('gl_account_fc', $request->id)->delete();

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
            $import = new GLAccountFCImport;
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
            //            dd($exception);
            return setResponse([
                'code' => 400,
                'title' => $exception->getMessage()
            ]);
            //            $empty_excel = Excel::toArray(new GLAccountFCImport(), $request->file('file'));
            //
            //            $grouo_account = [];
            //            $grouo_account_ = [];
            //
            //            foreach ($empty_excel[0] as $key => $value) {
            //                array_push($grouo_account, 'Group Account ' . $value['group_account_fc'] . ' tidak ada pada master');
            //                $d_grouoaccount = GroupAccountFC::whereIn('group_account_fc', [$value['group_account_fc']])->first();
            //                if ($d_grouoaccount) {
            //                    array_push($grouo_account, 'Group Account ' . $d_grouoaccount->group_account_code . ' tidak ada pada master');
            //                }
            //
            //            }
            //
            //            $result_grouo_account = array_diff($grouo_account, $grouo_account_);
            //            $result = array_merge($result_grouo_account);
            //            $res = array_unique($result);
            //
            //            if ($res) {
            //                $msg = '';
            //
            //                foreach ($res as $message)
            //                    $msg .= '<p>' . $message . '</p>';
            //
            //                return setResponse([
            //                    'code' => 430,
            //                    'title' => 'Gagal meng-import data',
            //                    'message' => $msg
            //                ]);
            //            } else {
            //                return setResponse([
            //                    'code' => 400,
            //                ]);
            //            }
        }
    }

    public function export()
    {
        return Excel::download(new MS_GLAccountFCExport, 'gl_account_fc.xlsx');
    }
}
